DROP TABLE IF EXISTS blog_users;
CREATE TABLE blog_users (
 username varchar(30) primary key,
 password varchar(32),
 userid varchar(32),
 userlevel tinyint(1) unsigned not null,
 email varchar(50),
 timestamp int(11) unsigned not null
);

DROP TABLE IF EXISTS blog_active_users;
CREATE TABLE blog_active_users (
 username varchar(30) primary key,
 timestamp int(11) unsigned not null
);

DROP TABLE IF EXISTS blog_active_guests;
CREATE TABLE blog_active_guests (
 ip varchar(15) primary key,
 timestamp int(11) unsigned not null
);

DROP TABLE IF EXISTS blog_banned_users;
CREATE TABLE blog_banned_users (
 username varchar(30) primary key,
 timestamp int(11) unsigned not null
);