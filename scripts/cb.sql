create table if not exists intervals
(
    id         int auto_increment
        primary key,
    start_date date     not null,
    end_date   date     not null,
    price      double   not null,
    created_at datetime null,
    updated_at datetime null
);
