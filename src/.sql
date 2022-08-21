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
    `created_at` datetime not null default now(),
    `updated_at` datetime null
);

