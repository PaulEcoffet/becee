-- BUSINESS TABLES

CREATE TABLE businesses(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    lat FLOAT(10,6),
    lng FLOAT(10, 6),
    manager_id INT UNSIGNED,
    website VARCHAR(255),
    FOREIGN KEY (manager_id) REFERENCES users(id),
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
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (business_id) REFERENCES businesses(id),
    PRIMARY KEY (id));

CREATE TABLE business_visits(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED,
    business_id INT UNSIGNED,
    visit_date DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (business_id) REFERENCES businesses(id),
    PRIMARY KEY (id));

CREATE TABLE business_comments(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED,
    business_id INT UNSIGNED,
    comment TEXT,
    pub_date DATETIME,
    status TINYINT UNSIGNED,
    vote_pos SMALLINT UNSIGNED,
    vote_neg SMALLINT UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (business_id) REFERENCES businesses(id),
    PRIMARY KEY (id));

CREATE TABLE link_business_tags(
    business_id INT UNSIGNED,
    tag_id INT UNSIGNED,
    nb_yes SMALLINT UNSIGNED,
    nb_no SMALLINT UNSIGNED,
    FOREIGN KEY (tag_id) REFERENCES business_tags(id),
    FOREIGN KEY (business_id) REFERENCES businesses(id));

CREATE TABLE business_features(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40),
    PRIMARY KEY (id));

CREATE TABLE link_features_tags(
    feature_id INT UNSIGNED,
    tag_id INT UNSIGNED,
    pertinence DOUBLE,
    FOREIGN KEY (tag_id) REFERENCES business_tags(id),
    FOREIGN KEY (feature_id) REFERENCES business_features(id));

CREATE TABLE score_businesses_features(
    business_id INT UNSIGNED,
    feature_id INT UNSIGNED,
    elo_score INT UNSIGNED,
    FOREIGN KEY (feature_id) REFERENCES business_tags(id),
    FOREIGN KEY (business_id) REFERENCES businesses(id));

CREATE TABLE businesses_comparaisons(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    business_visit1_id INT UNSIGNED,
    business_visit2_id INT UNSIGNED,
    winner TINYINT,
    feature_id INT UNSIGNED,
    trustability DOUBLE,
    FOREIGN KEY (business_visit1_id) REFERENCES businesses(id));
    FOREIGN KEY (business_visit2_id) REFERENCES businesses(id));
    FOREIGN KEY (feature_id) REFERENCES business_features(id));
    PRIMARY KEY (id));

-- USERS TABLES

CREATE TABLE users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(40) NOT NULL,
    hashed_password VARCHAR(255),
    category INT UNSIGNED,
    salt VARCHAR(255),
    trustability DOUBLE,
    FOREIGN KEY (category) REFERENCES user_categories(id));
    PRIMARY KEY (id));

CREATE TABLE user_categories(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(40),
    PRIMARY KEY (id));