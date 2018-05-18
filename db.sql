CREATE TABLE `leads` (
    `id`  int(10) unsigned NOT NULL AUTO_INCREMENT,
    `first_name`  varchar(70),
    `last_name`  varchar(70),
    `email_address`  varchar(70),
    `phone`  varchar(20),
    `address`  varchar(255),
    `square_footage`  int(8),
    `created_on`  datetime,
    `completed_on`  datetime,
    `session_id`  varchar(40),
    PRIMARY KEY (`id`)
);
