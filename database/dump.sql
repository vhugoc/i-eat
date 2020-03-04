CREATE DATABASE ieat;

USE ieat;

CREATE TABLE admin (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    thumb_uri VARCHAR(120),
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    password VARCHAR(64) NOT NULL,
    department VARCHAR(50) NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE subscription_plan (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL,
    access INT NOT NULL,
    max_posts INT NOT NULL,
    max_employees INT NOT NULL,
    max_products INT NOT NULL,
    payment VARCHAR(45) NOT NULL DEFAULT "monthly",
    value FLOAT(7,2) NOT NULL,
    description VARCHAR(120) NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE notification (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    target VARCHAR(300) NOT NULL,
    type VARCHAR(45) NOT NULL DEFAULT "info",
    title VARCHAR(80) NOT NULL,
    description VARCHAR(200) NOT NULL,
    seen VARCHAR(300),
    timestamp TIMESTAMP NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE support_ticket (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    description VARCHAR(200) NOT NULL,
    response VARCHAR(200),
    seen BOOLEAN DEFAULT false,
    timestamp TIMESTAMP NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE signin_out (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    employee_id INT,
    action VARCHAR(5),
    timestamp TIMESTAMP
) DEFAULT CHARSET = utf8;

CREATE TABLE user (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	company VARCHAR(80) NOT NULL,
    phone VARCHAR(15),
    landline VARCHAR(15),
    email VARCHAR(120) NOT NULL,
    password VARCHAR(64) NOT NULL,
    thumb_uri VARCHAR(120),
    splan_id INT NOT NULL,
    cnpj VARCHAR(45),
    city VARCHAR(80),
    is_active TINYINT NOT NULL DEFAULT true,
    status TINYINT NOT NULL DEFAULT false,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    expired_at DATE NOT NULL,
    ip VARCHAR(100),
    timezone VARCHAR(64) NOT NULL DEFAULT 'America/Sao_Paulo'
) DEFAULT CHARSET = utf8;

CREATE TABLE post (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(80) NOT NULL,
    access INT NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    description VARCHAR(150),
    created_at DATETIME NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE employee (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    password VARCHAR(64) NOT NULL,
    post_id INT NOT NULL,
    phone VARCHAR(15),
    landline VARCHAR(15),
    city VARCHAR(80),
    district VARCHAR(80),
    street VARCHAR(80),
    complement VARCHAR(80),
    get_in TIME,
    get_out TIME,
    thumb_uri VARCHAR(120),
    created_at DATETIME NOT NULL
) DEFAULT CHARSET = utf8;

CREATE TABLE client (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(80) NOT NULL,
    phone VARCHAR(15),
    landline VARCHAR(15),
    city VARCHAR(80),
    district VARCHAR(80),
    street VARCHAR(80),
    complement VARCHAR(80),
    is_loyalty BOOLEAN NOT NULL DEFAULT false,
    birthday DATE,
    obs VARCHAR(200),
    created_at DATETIME NOT NULL
) DEFAULT CHARSET = utf8;