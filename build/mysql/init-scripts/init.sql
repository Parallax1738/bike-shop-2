CREATE DATABASE IF NOT EXISTS BIKE_SHOP;
USE BIKE_SHOP;

-- Bikes, Scooters, etc...
DROP TABLE IF EXISTS CATEGORY;
CREATE TABLE CATEGORY(
    ID int NOT NULL,
    NAME varchar(20),
    PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS USER_ROLES;
CREATE TABLE USER_ROLES(
    ID INT NOT NULL,
    NAME NVARCHAR(100),
    PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS PRODUCT;
CREATE TABLE PRODUCT(
       ID int NOT NULL AUTO_INCREMENT,
       CATEGORY_ID INT NOT NULL,
       NAME varchar(300),
       PRICE decimal(10, 2),
       PRIMARY KEY (ID),
       FOREIGN KEY(CATEGORY_ID) REFERENCES CATEGORY(ID)
);

DROP TABLE IF EXISTS USER;
CREATE TABLE USER(
    ID int primary key auto_increment,
    USER_ROLE_ID INT NOT NULL DEFAULT(1),
    EMAIL_ADDRESS varchar(500) unique,
    FIRST_NAME varchar(50),
    LAST_NAME varchar(50),
    PASSWORD varchar(500),
    ADDRESS varchar(200),
    SUBURB varchar(10),
    STATE varchar(10),
    POSTCODE varchar(4),
    COUNTRY varchar(50),
    PHONE varchar(14),
    FOREIGN KEY (USER_ROLE_ID) REFERENCES USER_ROLES(ID)
);

-- Test Data
INSERT INTO CATEGORY (ID, NAME)
VALUES (1, 'Bikes'),
       (2, 'Scooters'),
       (3, 'Accessories'),
       (4, 'Apparel'),
       (5, 'Components');

INSERT INTO USER_ROLES(ID, NAME)
VALUES (1, 'Member'), -- Can read the database and make purchases
       (2, 'Staff'), -- Can access the rosters
       (3, 'Managers'),
       (4, 'SysAdmin'); -- Full read write access, even to users

INSERT INTO PRODUCT (CATEGORY_ID, NAME, PRICE)
VALUES
    (1, 'Road Bike', 1000),
    (1, 'Mountain Bike', 1200),
    (1, 'Hybrid Bike', 800),
    (1, 'Cruiser Bike', 600),
    (1, 'Folding Bike', 500),
    (1, 'Electric Bike', 2000),
    (1, 'Cargo Bike', 1500),
    (1, 'Tandem Bike', 2500),
    (1, 'BMX Bike', 400),
    (1, 'Kids Bike', 300),
    (1, 'Bicycle Helmet', 50),
    (1, 'Bicycle Lights', 20),
    (1, 'Bicycle Pump', 10),
    (1, 'Bicycle Lock', 30),
    (1, 'Bicycle Tires', 20),
    (1, 'Bicycle Pedals', 10),
    (1, 'Bicycle Chain', 15),
    (1, 'Bicycle Gears', 20),
    (1, 'Bicycle Saddle', 25),
    (1, 'Bicycle Handlebars', 15),
    (1, 'Bicycle Basket', 20),
    (1, 'Bicycle Trailer', 100),
    (1, 'Bicycle Repair Kit', 10),
    (1, 'Bicycle Maintenance Manual', 5),
    (1, 'Bicycle Touring Guide', 10),
    (1, 'Bicycle Racing Calendar', 5),
    (1, 'Bicycle Clothing', 20),
    (1, 'Bicycle Shoes', 30),
    (1, 'Bicycle Gloves', 20),
    (1, 'Bicycle Sunglasses', 15),
    (1, 'Bicycle Rain Gear', 20),
    (1, 'Bicycle GPS Tracker', 50),
    (1, 'Bicycle Speedometer', 20),
    (1, 'Bicycle Odometer', 15),
    (1, 'Bicycle Computer', 30),
    (1, 'Bicycle Phone Holder', 10),
    (1, 'Bicycle Water Bottle Cage', 5),
    (1, 'Bicycle Tools', 20),
    (1, 'Bicycle Multitool', 15),
    (1, 'Bicycle Tire Levers', 10),
    (1, 'Bicycle Patch Kit', 5),
    (1, 'Bicycle Pump Stand', 20),
    (1, 'Bicycle Storage Rack', 30),
    (1, 'Bicycle Garage', 100),
    (1, 'Bicycle Trip Insurance', 50),
    (1, 'Bicycle Rental', 20),
    (1, 'Bicycle Repair Shop', 30),
    (1, 'Bicycle Training Camp', 100),
    (1, 'Bicycle Race Entry Fee', 50),
    (1, 'Bicycle Tour Package', 200),
    (1, 'Bicycle Museum Admission', 10),
    (1, 'Bicycle Race Jersey', 30),
    (1, 'Bicycle Race Bib Shorts', 40),
    (1, 'Bicycle Race Shoes', 60),
    (1, 'Bicycle Race Hydration Pack', 20),
    (1, 'Bicycle Race GPS Watch', 100),
    (1, 'Bicycle Race Nutrition', 15),
    (1, 'Bicycle Race Camera', 50),
    (1, 'Bicycle Race Wheels', 200),
    (1, 'Bicycle Race Components', 300),
    (1, 'Bicycle Cargo Rack', 50),
    (1, 'Bicycle Child Carrier', 100),
    (1, 'Bicycle Fenders', 20),
    (1, 'Bicycle Kickstand', 15),
    (1, 'Bicycle Reflectors', 10),
    (1, 'Bicycle Taillight', 20),
    (1, 'Bicycle Bell', 15),
    (1, 'Bicycle Bikepacking Gear', 50),
    (1, 'Bicycle Touring Gear', 75),
    (1, 'Bicycle Camping Gear', 100),
    (1, 'Bicycle Adventure Gear', 150),
    (1, 'Bicycle Cleaning Products', 20),
    (1, 'Bicycle Lubricants', 15),
    (1, 'Bicycle Wash', 10),
    (1, 'Bicycle Tire Sealant', 5),
    (1, 'Bicycle Chain Lube', 20),
    (1, 'Bicycle Brake Pads', 15),
    (1, 'Bicycle Shifter Cable', 10),
    (1, 'Bicycle Derailleur Hanger', 5),
    (1, 'Bicycle Spokes', 20),
    (1, 'Bicycle Tubeless Valves', 15),
    (1, 'Bicycle Tire Bead Jack', 10),
    (1, 'Bicycle Wheelbuilding Tools', 5),
    (1, 'Bicycle Workshop Equipment', 20),
    (1, 'Bicycle Race Start Number', 15),
    (1, 'Bicycle Race Timing Chip', 20),
    (1, 'Bicycle Race Medal', 10),
    (1, 'Bicycle Race Jersey Customization', 50),
    (1, 'Bicycle Race Bib Shorts Customization', 60),
    (1, 'Bicycle Race Shoes Customization', 80),
    (1, 'Bicycle Race Hydration Pack Customization', 30),
    (1, 'Bicycle Race GPS Watch Customization', 120),
    (1, 'Bicycle Race Nutrition Customization', 18),
    (1, 'Bicycle Race Camera Customization', 60),
    (1, 'Bicycle Race Wheels Customization', 240),
    (1, 'Bicycle Race Components Customization', 360),
    (1, 'Bicycle Insurance', 50),
    (1, 'Bicycle Theft Protection', 100),
    (1, 'Bicycle Crash Replacement', 150),
    (1, 'Bicycle Medical Evacuation', 423);

-- Scooters
INSERT INTO PRODUCT (CATEGORY_ID, NAME, PRICE)
VALUES
    (2, 'Electric Scooter', 500),
    (2, 'Gas Scooter', 700),
    (2, 'Kick Scooter', 200),
    (2, 'Hoverboard', 300),
    (2, 'Segway', 1000),
    (2, 'Onewheel', 1500),
    (2, 'Electric Scooter for Kids', 300),
    (2, 'Gas Scooter for Adults', 900),
    (2, 'Kick Scooter for Adults', 400),
    (2, 'Hoverboard for Adults', 500),
    (2, 'Segway for Adults', 1200),
    (2, 'Onewheel for Adults', 1800),
    (2, 'Electric Scooter Accessories', 20),
    (2, 'Gas Scooter Accessories', 30),
    (2, 'Kick Scooter Accessories', 10),
    (2, 'Hoverboard Accessories', 15),
    (2, 'Segway Accessories', 20),
    (2, 'Onewheel Accessories', 25),
    (2, 'Electric Scooter Helmet', 50),
    (2, 'Gas Scooter Helmet', 60),
    (2, 'Kick Scooter Helmet', 25),
    (2, 'Hoverboard Helmet', 30),
    (2, 'Segway Helmet', 40),
    (2, 'Onewheel Helmet', 50),
    (2, 'Electric Scooter Lock', 30),
    (2, 'Gas Scooter Lock', 40),
    (2, 'Kick Scooter Lock', 15),
    (2, 'Hoverboard Lock', 20),
    (2, 'Segway Lock', 25),
    (2, 'Onewheel Lock', 30),
    (2, 'Electric Scooter Charger', 20),
    (2, 'Gas Scooter Fuel Tank', 30),
    (2, 'Kick Scooter Tires', 20),
    (2, 'Hoverboard Tires', 15),
    (2, 'Segway Tires', 20),
    (2, 'Onewheel Tires', 25),
    (2, 'Electric Scooter Basket', 20),
    (2, 'Gas Scooter Cargo Carrier', 30),
    (2, 'Kick Scooter Fenders', 10),
    (2, 'Hoverboard Carrying Case', 15),
    (2, 'Segway Carrying Case', 20),
    (2, 'Onewheel Carrying Case', 25),
    (2, 'Electric Scooter Repair Kit', 10),
    (2, 'Gas Scooter Repair Manual', 5),
    (2, 'Kick Scooter Maintenance Guide', 10),
    (2, 'Hoverboard Safety Tips', 5),
    (2, 'Segway Training Course', 100),
    (2, 'Onewheel Tips and Tricks', 15),
    (2, 'Electric Scooter Insurance', 50),
    (2, 'Gas Scooter Theft Protection', 100),
    (2, 'Kick Scooter Crash Replacement', 150),
    (2, 'Hoverboard Medical Evacuation', 200),
    (2, 'Segway Tour Package', 300),
    (2, 'Onewheel Race Entry Fee', 50),
    (2, 'Electric Scooter Racing Jersey', 30),
    (2, 'Gas Scooter Racing Bib Shorts', 40),
    (2, 'Kick Scooter Racing Shoes', 60),
    (2, 'Hoverboard Racing Hydration Pack', 20),
    (2, 'Segway Racing GPS Watch', 100),
    (2, 'Onewheel Racing Nutrition', 15),
    (2, 'Electric Scooter Racing Camera', 50),
    (2, 'Gas Scooter Racing Wheels', 200),
    (2, 'Kick Scooter Racing Components', 300);

-- Accessories
INSERT INTO PRODUCT (CATEGORY_ID, NAME, PRICE)
VALUES
    (3, 'Bike Bikepacking Gear', 50),
    (3, 'Bike Touring Gear', 75),
    (3, 'Bike Camping Gear', 100),
    (3, 'Bike Adventure Gear', 150),
    (3, 'Bike Cleaning Products', 20),
    (3, 'Bike Lubricants', 15),
    (3, 'Bike Wash', 10),
    (3, 'Bike Tire Sealant', 5),
    (3, 'Bike Chain Lube', 20),
    (3, 'Bike Brake Pads', 15),
    (3, 'Bike Shifter Cable', 10),
    (3, 'Bike Derailleur Hanger', 5),
    (3, 'Bike Spokes', 20),
    (3, 'Bike Tubeless Valves', 15),
    (3, 'Bike Tire Bead Jack', 10),
    (3, 'Bike Wheelbuilding Tools', 5),
    (3, 'Bike Workshop Equipment', 20),
    (3, 'Bike Race Start Number', 15),
    (3, 'Bike Race Timing Chip', 20),
    (3, 'Bike Race Medal', 10),
    (3, 'Bike Race Jersey Customization', 50),
    (3, 'Bike Race Bib Shorts Customization', 60),
    (3, 'Bike Race Shoes Customization', 80),
    (3, 'Bike Race Hydration Pack Customization', 30),
    (3, 'Bike Race GPS Watch Customization', 120),
    (3, 'Bike Race Nutrition Customization', 18),
    (3, 'Bike Race Camera Customization', 60),
    (3, 'Bike Race Wheels Customization', 240),
    (3, 'Bike Race Components Customization', 360),
    (3, 'Bike Cargo Rack', 50),
    (3, 'Bike Child Carrier', 100),
    (3, 'Bike Fender Extenders', 20),
    (3, 'Bike Rack Mount', 15),
    (3, 'Bike Wheel Reflectors', 10),
    (3, 'Bike Handlebar Bag', 20),
    (3, 'Bike Saddle Bag', 15),
    (3, 'Bike Bottle Cage Extenders', 1),
    (3, 'Bike Pump Stand', 20),
    (3, 'Bike Repair Stand', 30),
    (3, 'Bike Tubeless Tire Kit', 50),
    (3, 'Bike Air Compressor', 75);

-- Apparel
INSERT INTO PRODUCT (CATEGORY_ID, NAME, PRICE)
VALUES
    (4, 'Bike Jersey', 30),
    (4, 'Bike Bib Shorts', 40),
    (4, 'Bike Gloves', 20),
    (4, 'Bike Shoes', 50),
    (4, 'Bike Helmet Visor', 15),
    (4, 'Bike Goggles', 20),
    (4, 'Bike Headband', 10),
    (4, 'Bike Balaclava', 15),
    (4, 'Bike Arm Warmers', 20),
    (4, 'Bike Leg Warmers', 25),
    (4, 'Bike Rain Jacket', 50),
    (4, 'Bike Rain Pants', 40),
    (4, 'Bike Jersey Customization', 50),
    (4, 'Bike Bib Shorts Customization', 60),
    (4, 'Bike Glove Customization', 80),
    (4, 'Bike Shoe Customization', 30),
    (4, 'Bike Jacket Customization', 120),
    (4, 'Bike Base Layer', 25),
    (4, 'Bike Sweatshirt', 40),
    (4, 'Bike Jacket', 50),
    (4, 'Bike Pants', 40),
    (4, 'Bike Shorts', 35),
    (4, 'Bike Jersey Liner', 20),
    (4, 'Bike Shoe Covers', 15),
    (4, 'Bike Socks', 10);

-- Components
INSERT INTO PRODUCT (CATEGORY_ID, NAME, PRICE)
VALUES
    (5, 'Bike Handlebars', 30),
    (5, 'Bike Stems', 20),
    (5, 'Bike Seatposts', 25),
    (5, 'Bike Saddles', 40),
    (5, 'Bike Cranksets', 50),
    (5, 'Bike Bottom Brackets', 35),
    (5, 'Bike Chainrings', 20),
    (5, 'Bike Cassette', 25),
    (5, 'Bike Derailleurs', 40),
    (5, 'Bike Shifters', 50),
    (5, 'Bike Brakes', 35),
    (5, 'Bike Wheels', 20),
    (5, 'Bike Tires', 25),
    (5, 'Bike Pedals', 40),
    (5, 'Bike Seatbelt', 50),
    (5, 'Bike Computer Mount', 35),
    (5, 'Bike Lights', 20),
    (5, 'Bike Mirrors', 25),
    (5, 'Bike Bell', 40),
    (5, 'Bike Rack', 50),
    (5, 'Bike Lock', 35),
    (5, 'Bike Pump', 20),
    (5, 'Bike Tire Levers', 25),
    (5, 'Bike Multitool', 40),
    (5, 'Bike Patch Kit', 50),
    (5, 'Bike Spare Tube', 35),
    (5, 'Bike Bottle Cage', 20),
    (5, 'Bike Frame', 100);

