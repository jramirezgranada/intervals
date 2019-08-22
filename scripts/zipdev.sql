create table if not exists api_users
(
    id         int auto_increment
        primary key,
    username   varchar(255)                        not null,
    password   varchar(255)                        not null,
    created_at timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    updated_at timestamp                           null,
    token      varchar(255)                        null
);

create table if not exists contacts
(
    id         int auto_increment
        primary key,
    firstname  varchar(255)                        not null,
    lastname   varchar(255)                        null,
    photo_url  varchar(255)                        null,
    created_at timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    updated_at timestamp                           null
);

create table if not exists contact_emails
(
    id         int auto_increment
        primary key,
    contact_id int                                 not null,
    email      varchar(255)                        not null,
    created_at timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    updated_at timestamp                           null,
    constraint contact_emails_contacts_id_fk
        foreign key (contact_id) references contacts (id)
            on delete cascade
);

create table if not exists contact_phones
(
    id         int auto_increment
        primary key,
    contact_id int                                 not null,
    phone      varchar(255)                        not null,
    created_at timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    updated_at timestamp                           null,
    constraint contact_phones_contacts_id_fk
        foreign key (contact_id) references contacts (id)
            on delete cascade
);

INSERT INTO api_users (username, password, created_at, updated_at, token) VALUES ('zipdev', '43efc605f7eb18171aaade61e36d3aed', '2019-08-04 20:09:02', '2019-08-05 00:50:34', '');