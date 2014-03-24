CREATE TABLE businesses(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    lat FLOAT(10,6),
    lng FLOAT(10, 6),
    manager_id INT UNSIGNED,
    website VARCHAR(255));

CREATE TABLE users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40) NOT NULL,
    hashed_password VARCHAR(255),
    salt VARCHAR(255),
    trustability DOUBLE);

CREATE TABLE business_tags(
    id INT AUTO_INCREMENT,
    name VARCHAR(255));
