CREATE DATABASE IF NOT EXISTS BIKE_SHOP;
USE BIKE_SHOP;

-- Bikes, Scooters, etc...
CREATE TABLE CATEGORY(
    ID int NOT NULL,
    NAME varchar(20),
    PRIMARY KEY (ID)
);

CREATE TABLE PRODUCT(
       ID int NOT NULL,
       CATEGORY_ID INT NOT NULL,
       NAME varchar(300),
       PRICE decimal(10, 2),
       FOREIGN KEY(CATEGORY_ID) REFERENCES CATEGORY(ID)
);

CREATE TABLE USER(
    ID int NOT NULL,
    FIRST_NAME varchar(50),
    LAST_NAME varchar(50),
    EMAIL_ADDRESS varchar(500),
    PASSWORD varchar(500),
    ADDRESS varchar(200),
    SUBURB varchar(10),
    STATE varchar(10),
    POSTCODE varchar(4),
    COUNTRY varchar(50),
    PHONE varchar(14),
    PRIMARY KEY (ID)
);





