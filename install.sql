create table contact
(
    id int auto_increment primary key,
    message longtext not null,
    sender varchar(255) not null,
    subject varchar(255) not null,
    firstname varchar(255) not null,
    lastname varchar(255) not null
) collate = utf8mb4_unicode_ci;

create table hotel
(
    id int auto_increment primary key,
    name varchar(255) not null,
    address varchar(255) not null,
    complement varchar(255) null,
    zipcode varchar(255) not null,
    city varchar(255) not null,
    description longtext null,
    slug varchar(255) not null
) collate = utf8mb4_unicode_ci;

create table image
(
    id int auto_increment primary key
) collate = utf8mb4_unicode_ci;

create table messenger_messages
(
    id bigint auto_increment primary key,
    body longtext not null,
    headers longtext not null,
    queue_name varchar(190) not null,
    created_at datetime not null,
    available_at datetime not null,
    delivered_at datetime null
) collate = utf8mb4_unicode_ci;

create table suite
(
    id int auto_increment primary key,
    hotel_id int null,
    name varchar(255) not null,
    description longtext not null,
    price double not null,
    booking_link longtext not null,
    slug varchar(255) not null,
    thumbnail varchar(255) not null,
    constraint FK_hotel foreign key (hotel_id) references hotel (id)
) collate = utf8mb4_unicode_ci;

create table attachment
(
    id int auto_increment primary key,
    suite_id int null,
    image varchar(255) not null,
    constraint FK_suite foreign key (suite_id) references suite (id)
) collate = utf8mb4_unicode_ci;

create table user
(
    id int auto_increment primary key,
    email varchar(180) not null,
    roles longtext not null comment '(DC2Type:json)',
    password varchar(255) not null,
    firstname varchar(255) not null,
    lastname varchar(255) not null,
    constraint UNIQ_email unique (email)
) collate = utf8mb4_unicode_ci;

create table reservation
(
    id int auto_increment primary key,
    user_id int null,
    suite_id int null,
    date_start date not null,
    date_end date not null,
    constraint FK_suite_2 foreign key (suite_id) references suite (id),
    constraint FK_user foreign key (user_id) references user (id)
) collate = utf8mb4_unicode_ci;

create table user_hotel
(
    user_id  int not null,
    hotel_id int not null,
    primary key (user_id, hotel_id),
    constraint FK_hotel_2 foreign key (hotel_id) references hotel (id) on delete cascade,
    constraint FK_user_2 foreign key (user_id) references user (id) on delete cascade
) collate = utf8mb4_unicode_ci;
