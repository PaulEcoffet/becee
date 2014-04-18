-- BUSINESS TABLES

CREATE TABLE businesses(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    lat FLOAT(10,6),
    lng FLOAT(10, 6),
    manager_id INT UNSIGNED,
    website VARCHAR(255),
    PRIMARY KEY (id));

CREATE TABLE business_tags(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40),
    PRIMARY KEY (id));

CREATE TABLE business_images(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED,
    business_id INT UNSIGNED,
    order TINYINT UNSIGNED,
    path VARCHAR(40),
    PRIMARY KEY (id));

CREATE TABLE business_visits(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED,
    business_id INT UNSIGNED,
    visit_date DATETIME,
    PRIMARY KEY (id));

CREATE TABLE business_comments(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED,
    business_visit_id INT UNSIGNED,
    comment TEXT,
    pub_date DATETIME,
    status TINYINT UNSIGNED,
    vote_pos SMALLINT UNSIGNED,
    vote_neg SMALLINT UNSIGNED,
    PRIMARY KEY (id));

CREATE TABLE link_business_tags(
    id_business INT UNSIGNED,
    id_business_tag INT UNSIGNED,
    nb_yes SMALLINT UNSIGNED,
    nb_no SMALLINT UNSIGNED,
    PRIMARY KEY (id_business, id_business_tag));

CREATE TABLE business_features(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40),
    PRIMARY KEY (id));

CREATE TABLE link_features_tags(
    id_feature INT UNSIGNED,
    id_feature_tag INT UNSIGNED,
    pertinence DOUBLE,
    PRIMARY KEY (id_feature, id_feature_tag));

CREATE TABLE score_businesses_features(
    id_business INT UNSIGNED,
    id_feature INT UNSIGNED,
    elo_score INT UNSIGNED,
    PRIMARY KEY (id_business, id_feature));

CREATE TABLE businesses_comparaisons(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_business_visit1 INT UNSIGNED,
    id_business_visit2 INT UNSIGNED,
    winner TINYINT,
    trustability DOUBLE,
    PRIMARY KEY (id));

-- USERS TABLES

CREATE TABLE users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40) NOT NULL,
    hashed_password VARCHAR(255),
    salt VARCHAR(255),
    trustability DOUBLE,
    PRIMARY KEY (id));

CREATE TABLE user_categories(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40),
    PRIMARY KEY (id));