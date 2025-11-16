DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE student_passwords;

DROP USER IF EXISTS 'passwords_user'@'localhost';

CREATE USER 'passwords_user'@'localhost';
GRANT ALL ON student_passwords.* TO 'passwords_user'@'localhost';

USE student_passwords;

SET block_encryption_mode = 'aes-128-ecb';
SET @key_str = 'mysecretpass1234';

CREATE TABLE IF NOT EXISTS users (
  user_id     INT               NOT NULL AUTO_INCREMENT,
  username    VARCHAR(100)      NOT NULL,
  first_name  VARCHAR(100)      NOT NULL,
  last_name   VARCHAR(100)      NOT NULL,
  email       VARCHAR(100)      NOT NULL,

  PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS websites (
  site_id    INT                NOT NULL AUTO_INCREMENT,
  site_name  VARCHAR(100)       NOT NULL,
  site_url   VARCHAR(255)       NOT NULL,

  PRIMARY KEY (site_id)
);

CREATE TABLE IF NOT EXISTS accounts (
  user_id      INT              NOT NULL,
  site_id      INT              NOT NULL,
  password     VARBINARY(512)   NOT NULL,
  comment      VARCHAR(255),
  time_stamp   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (user_id, site_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (site_id) REFERENCES websites(site_id)
);

INSERT INTO users (username, first_name, last_name, email)
VALUES
  ('eschands', 'Edward', 'Scissorhands', 'ed3@gmail.com'),
  ('fkrueger', 'Freddy', 'Krueger', 'fredkru10@gmail.com'),
  ('leslion', 'Lestat', 'Lioncourt', 'lestatlion@gmail.com'),
  ('gnftz', 'Ginger', 'Fitzgerald', 'gingfitz@gmail.com'),
  ('mmfly', 'Marty', 'McFly', 'mcflymart@gmail.com');

INSERT INTO websites (site_name, site_url)
VALUES
  ('eBay', 'https://www.ebay.com/'),
  ('MGM Plus', 'https://www.mgmplus.com/'),
  ('Letterboxd', 'https://letterboxd.com/'),
  ('Food Network', 'https://www.foodnetwork.com/'),
  ('Youtube', 'https://www.youtube.com/'),
  ('Starz', 'https://www.starz.com/'),
  ('Paramount Plus', 'https://www.paramountplus.com/'),
  ('HBO Max', 'https://play.hbomax.com/');

INSERT INTO accounts (user_id, site_id, password, comment)
VALUES
  (1, 1, AES_ENCRYPT('ebaypass1', @key_str), 'eBay login'),
  (1, 2, AES_ENCRYPT('mgmsecure', @key_str), 'MGM Plus subscription'),
  (2, 3, AES_ENCRYPT('letterboxd123', @key_str), 'Review movies'),
  (2, 4, AES_ENCRYPT('foodpass', @key_str), 'Recipe ideas'),
  (3, 5, AES_ENCRYPT('yt123', @key_str), 'YouTube login'),
  (3, 6, AES_ENCRYPT('starzwatch', @key_str), 'Starz login'),
  (4, 7, AES_ENCRYPT('paramountpw', @key_str), 'Paramount Plus login'),
  (4, 8, AES_ENCRYPT('hbomaxpw', @key_str), 'HBO Max login'),
  (5, 1, AES_ENCRYPT('shopping123', @key_str), 'marty eBay login'),
  (5, 3, AES_ENCRYPT('letterb3', @key_str), 'marty letterboxd');
