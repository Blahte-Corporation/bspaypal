<?php

namespace BlahteSoftware\BsPaypal;

use BlahteSoftware\BsPaypal\Contracts\DatabaseInterface;
use BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface;
use BlahteSoftware\BsPaypal\Contracts\PaypalInterface;
use BlahteSoftware\BsPaypal\Traits\RequestIdTrait;
use BlahteSoftware\BsPaypal\Validators\JsonValidator;
use DateInterval;
use DateTime;
use Exception;
use PDO;
use PDOException;
use Throwable;

use function BlahteSoftware\BsPaypal\Utils\findObjectByPropertyValue;
use function BlahteSoftware\BsPaypal\Utils\table_exists;
use function BlahteSoftware\BsPaypal\Utils\table_insert;

class Paypal implements PaypalInterface {
    /**
     * @var \BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface
     */
    protected $core;

    /**
     * @var \BlahteSoftware\BsPaypal\Contracts\PaypalInterface
     */
    protected static $instance;

    /**
     * @var \BlahteSoftware\BsPaypal\Database
     */
    protected $db;

    private function __construct(PaypalCoreInterface $paypalCore)
    {
        $this->core = $paypalCore;
        try {
            $this->db = Database::getInstance();
        } catch(Exception $e) {}
    }

    public static function getInstance(PaypalCoreInterface $paypalCore = null): PaypalInterface
    {
        if( is_null($paypalCore) ) {
            if( is_null(static::$instance) ) {
                if( function_exists('app') && method_exists(app(), 'make') && method_exists(app(), 'bound') ) {
                    if(app()->bound(PaypalCoreInterface::class)) {
                        return static::$instance = new static(app()->make(PaypalCoreInterface::class));
                    }
                }
                throw new Exception("Paypal Core Object Not Found.");
            }
            if( !static::$instance instanceof PaypalInterface ) {
                throw  new Exception("Invalid Paypal Instance.");
            }
            return static::$instance;
        }
        if( is_null(static::$instance) ) {
            static::$instance = new static($paypalCore);
        }
        return static::$instance;
    }

    public function setDatabaseInstance(DatabaseInterface $db) {
        $this->db = $db;
    }

    public function ensureDatabaseTablesExist() {
        try {
            if(! table_exists($this->db->pdo, BSPAYPAL_TABLE_ACCESS_TOKENS) ) {
                $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '.sql');
                $this->db::run($sql);
            }
        } catch(Throwable $e) {
            throw $e;
        }
    }

    public function getAccessToken() : string {
        $tableName = BSPAYPAL_TABLE_ACCESS_TOKENS;
        $sql = 
<<<SQL
SELECT * FROM `{$tableName}` WHERE
expiry_date<:expiryDate
LIMIT 1
SQL;
        $now = new DateTime();
        $now->sub(new DateInterval('P60S'));
        $stmt = $this->db::run($sql, [
            'expiryDate' => $now->format(DATE_W3C)
        ]);
        if($stmt->errorCode() === BSPAYPAL_PDOSTATEMENT_ERROR) {
            throw new PDOException("SQLSTATE {$stmt->errorCode} {$stmt->errorInfo[2]}");
        }
        $n = $stmt->fetch(PDO::FETCH_OBJ);
        if(! empty($n)) {
            return $n->access_token;
        }
        $response = $this->core->generateAccessToken();
        if(! property_exists($response['success'], 'access_token') ) {
            throw new Exception("Failed to fetch access token.");
        }
        $expiry = new DateTime();
        $expiry->add(new DateInterval('P'. $response['success']->expires_in .'S'));
        table_insert($this->db->pdo, $tableName, [
            'expiry_date' => $expiry->format(DATE_W3C),
            'scope' => $response['success']->scope,
            'access_token' => $response['success']->access_token,
            'token_type' => $response['success']->token_type,
            'app_id' => $response['success']->app_id,
            'expires_in' => $response['success']->expires_in,
            'nonce' => $response['success']->nonce,
            'created_at' => (new DateTime())->format(DATE_W3C)
        ]);
        return $response['success']->access_token;
    }

    public function getRequestId() : string {
        $tableName = BSPAYPAL_TABLE_REQUEST_IDS;
        $sql = 
<<<SQL
SELECT * FROM `{$tableName}` 
ORDER BY id DESC
LIMIT 1
SQL;
        $stmt = $this->db::run($sql);
        if($stmt->errorCode() === BSPAYPAL_PDOSTATEMENT_ERROR) {
            throw new PDOException("SQLSTATE {$stmt->errorCode} {$stmt->errorInfo[2]}");
        }
        $n = $stmt->fetch(PDO::FETCH_OBJ);
        $lastId = empty($n) ? 0 : $n->id;
        $requestId = (new class { use RequestIdTrait; })->getRequestNumber($lastId);
        table_insert($this->db->getPdo(), BSPAYPAL_TABLE_REQUEST_IDS, [
            'name' => $requestId,
            'created_at' => (new DateTime())->format(DATE_W3C)
        ]);
        return $requestId;
    }

    /**
     * @inheritdoc
     */
    public function createOrder(
        string $requestId,
        string $body,
        bool $preferCompleteRepresentation = true
    ) : CreateOrderResponse {
        JsonValidator::validate($body);
        $obj = json_decode($body, false);
        table_insert($this->db->getPdo(), BSPAYPAL_TABLE_ORDERS, [
            'request_id' => $requestId,
            'amount' => $obj->purchase_units[0]->amount->value,
            'currency' => $obj->purchase_units[0]->amount->currency_code,
            'status' => 'AWAITING CREATION',
            'return_url' => $obj->application_context->return_url,
            'cancel_url' => $obj->application_context->cancel_url,
            'request_body' => $body,
            'created_at' => (new DateTime())->format(DATE_W3C)
        ]);
        $url = $this->core->url("/v2/checkout/orders");
        $response = [
            'success' => null,
            'error' => null,
            'code' => null
        ];
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer {$this->getAccessToken()}",
            "Paypal-Request-Id: {$requestId}"
        ];
        if($preferCompleteRepresentation == true) $headers[] = "Prefer: return=representation"; 
        $c = curl_init();
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        $response['code'] = curl_getinfo($c, CURLINFO_RESPONSE_CODE);
        $response['success'] = json_decode(curl_exec($c));
        if(curl_errno($c)) $response['error'] = curl_error($c);
        curl_close($c);
        $tableName = BSPAYPAL_TABLE_ORDERS;
        $sql = 
<<<SQL
UPDATE `{$tableName}` SET
response_body=:response_body,
updated_at=:updated_at
WHERE
request_id=:request_id
SQL;
        $this->db::run($sql, [
            'response_body' => json_encode($response['success']),
            'updated_at' => (new DateTime())->format(DATE_W3C),
            'request_id' => $requestId
        ]);
        if(property_exists($response['success'], 'id') &&
            property_exists($response['success'], 'CAPTURE') &&
            property_exists($response['success'], 'CREATED') &&
            $response['success']->intent == 'CAPTURE' &&
            $response['success']->status == 'CREATED'
        ) {
            $approvalLink = findObjectByPropertyValue($response['success']->links, 'ref', 'approve');
            $captureLink = findObjectByPropertyValue($response['success']->links, 'ref', 'capture');
            $updateLink = findObjectByPropertyValue($response['success']->links, 'ref', 'update');
            $infoLink = findObjectByPropertyValue($response['success']->links, 'ref', 'self');
            $sql = 
<<<SQL
UPDATE `{$tableName}` SET
order_id=:order_id,
status=:status,
approval_url=:approval_url,
capture_url=:capture_url,
update_url=:update_url,
info_url=:info_url,
updated_at=:updated_at
WHERE
request_id=:request_id
SQL;
            $this->db::run($sql, [
                'order_id' => $response['success']->id,
                'status' => $response['success']->status,
                'approval_url' => $approvalLink->href,
                'capture_url' => $captureLink->href,
                'update_url' => $updateLink->href,
                'info_url' => $infoLink->href,
                'updated_at' => (new DateTime())->format(DATE_W3C),
                'request_id' => $requestId
            ]);
            // return new CreateOrderResponse($this->response['success']);
        }
        return new CreateOrderResponse($this->response['success']);
    }

    public function requestForApproval(string $requestId) : PaypalInterface {
        $tableName = BSPAYPAL_TABLE_ORDERS;
        $sql = 
<<<SQL
UPDATE `{$tableName}` SET
status=:status,
updated_at=:updated_at
WHERE
request_id=:request_id
SQL;
        $this->db::run($sql, [
            'status' => 'AWAITING APPROVAL',
            'updated_at' => (new DateTime())->format(DATE_W3C),
            'request_id' => $requestId
        ]);
        return $this;
    }

    public function capture(string $requestId) : PaypalInterface {
        $tableName = BSPAYPAL_TABLE_ORDERS;
        $sql = 
<<<SQL
SELECT * FROM `{$tableName}` 
WHERE request_id=:request_id
SQL;
        $stmt = $this->db::run($sql, [
            'request_id' => $requestId
        ]);
        if($stmt->errorCode() === BSPAYPAL_PDOSTATEMENT_ERROR) {
            throw new PDOException("SQLSTATE {$stmt->errorCode} {$stmt->errorInfo[2]}");
        }
        $n = $stmt->fetch(PDO::FETCH_OBJ);
        $url = $n->capture_url;
        $response = [
            'success' => null,
            'error' => null,
            'code' => null
        ];
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer {$this->getAccessToken()}",
            "Paypal-Request-Id: {$requestId}"
        ];
        // $headers[] = "Prefer: return=representation"; 
        $body = json_encode([
            'PayerID' => $n->payer_id
        ]);
        $c = curl_init();
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        $response['code'] = curl_getinfo($c, CURLINFO_RESPONSE_CODE);
        $response['success'] = json_decode(curl_exec($c));
        if(curl_errno($c)) $response['error'] = curl_error($c);
        curl_close($c);
        $tableName = BSPAYPAL_TABLE_ORDERS;
        $sql = 
<<<SQL
UPDATE `{$tableName}` SET
status=:status,
updated_at=:updated_at
WHERE
request_id=:request_id
SQL;
        $this->db::run($sql, [
            'status' => $response['success']->status,
            'updated_at' => (new DateTime())->format(DATE_W3C),
            'request_id' => $requestId
        ]);
        return $this;
    }

    public function setPayerId(string $requestId, string $payerId) : PaypalInterface {
        $tableName = BSPAYPAL_TABLE_ORDERS;
        $sql = 
<<<SQL
UPDATE `{$tableName}` SET
payer_id=:payer_id,
updated_at=:updated_at
WHERE
request_id=:request_id
SQL;
        $this->db::run($sql, [
            'payer_id' => $payerId,
            'updated_at' => (new DateTime())->format(DATE_W3C),
            'request_id' => $requestId
        ]);
        return $this;
    }

    public function setCancelled(string $requestId) : PaypalInterface {
        $tableName = BSPAYPAL_TABLE_ORDERS;
        $sql = 
<<<SQL
UPDATE `{$tableName}` SET
status=:status,
updated_at=:updated_at
WHERE
request_id=:request_id
SQL;
        $this->db::run($sql, [
            'status' => 'CANCELLED',
            'updated_at' => (new DateTime())->format(DATE_W3C),
            'request_id' => $requestId
        ]);
        return $this;
    }
}