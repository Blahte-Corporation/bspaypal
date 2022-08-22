create table `bspaypal_request_ids`
(
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(191) not null,
    `created_at` datetime not null default now(),
    `updated_at` datetime null
);