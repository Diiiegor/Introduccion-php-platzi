create database cursophp;
use cursophp;
create table jobs
(
    id          int unsigned auto_increment
        primary key,
    title       varchar(50)                            not null,
    description varchar(100)                           null,
    visible     tinyint(1) default 1                   not null,
    months      int(10)    default 0                   not null,
    created_at  timestamp  default current_timestamp() not null,
    updated_at  timestamp  default current_timestamp() not null on update current_timestamp(),
    file_name   varchar(100)                           null
)
    charset = utf8;

create table projects
(
    id          int unsigned auto_increment
        primary key,
    title       varchar(50)                            not null,
    description varchar(100)                           null,
    visible     tinyint(1) default 1                   not null,
    months      int(10)    default 0                   not null,
    created_at  timestamp  default current_timestamp() not null,
    updated_at  timestamp  default current_timestamp() not null on update current_timestamp()
)
    charset = utf8;

create table users
(
    id       int auto_increment
        primary key,
    email    varchar(100) not null,
    password varchar(250) not null
);

