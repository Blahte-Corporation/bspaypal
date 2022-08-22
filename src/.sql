-- access tokens
create table `bspaypal_access_tokens`
(
    `id` bigint unsigned not null auto_increment primary key,
    `scope` longtext null,
    `access_token` text not null,
    `token_type` varchar(191) null,
    `app_id` varchar(255) null,
    `expires_in` bigint unsigned null,
    `nonce` text null,
    `expiry_date` datetime not null,
    `created_at` datetime not null default now(),
    `updated_at` datetime null
);

-- request ids
create table `bspaypal_request_ids`
(
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(191) not null,
    `created_at` datetime not null default now(),
    `updated_at` datetime null
);

-- orders 
create table `bspaypal_orders`
(
    `id` bigint unsigned not null auto_increment primary key,
    `request_id` text not null,
    `order_id` varchar(191) null,
    `amount` text not null,
    `currency` text not null,
    `status` text null,
    `request_body` json not null check(JSON_VALID(`request_body`)) default '[]',
    `response_body` json not null check(JSON_VALID(`response_body`)) default '[]',
    `approval_url` text null,
    `capture_url` text null,
    `update_url` text null,
    `info_url` text null,
    `payer_id` text null,
    `return_url` text null,
    `cancel_url` text null,
    `created_at` datetime not null default now(),
    `updated_at` datetime null
);
