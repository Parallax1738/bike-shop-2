-- | CREATING DATABASE | --

CREATE DATABASE IF NOT EXISTS BIKE_SHOP;
USE BIKE_SHOP;

-- Product type is a type of product based on it's category. For example, the category could be 'scooters', and the
-- product types could be 'electric scooter, adult scooters, etc...'
DROP TABLE IF EXISTS PRODUCT_FILTER;
CREATE TABLE PRODUCT_FILTER (
    ID INT NOT NULL,
    NAME VARCHAR(100) NOT NULL,
    PRIMARY KEY (ID)
);

-- Bikes, Scooters, etc...
DROP TABLE IF EXISTS CATEGORY;
CREATE TABLE CATEGORY(
    ID INT NOT NULL,
    NAME VARCHAR(20),
    PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS PRODUCT;
CREATE TABLE PRODUCT(
    ID INT NOT NULL AUTO_INCREMENT,
    CATEGORY_ID INT NOT NULL,
    NAME VARCHAR(300),
    DESCRIPTION LONGTEXT,
    PRICE DECIMAL(10, 2),
    PRIMARY KEY (ID),
    FOREIGN KEY (CATEGORY_ID) REFERENCES CATEGORY (ID)
);



DROP TABLE IF EXISTS PRODUCT_FILTER_LINK;
CREATE TABLE PRODUCT_FILTER_LINK (
     PRODUCT_FILTER_ID INT NOT NULL,
     PRODUCT_ID INT NOT NULL,
     PRIMARY KEY (PRODUCT_FILTER_ID, PRODUCT_ID),
     FOREIGN KEY (PRODUCT_FILTER_ID) REFERENCES PRODUCT_FILTER(ID),
     FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCT(ID)
);

INSERT INTO PRODUCT_FILTER (ID, NAME)
VALUES (1, 'Men'),
       (2, 'Woman'),
       (3, 'Mountain Bikes'),
       (4, 'Road Bikes'),
       (5, 'Electric Bikes'),
       (6, 'Casual'),
       (7, 'Street Scooters'),
       (8, 'Electric Scooters'),
       (9, 'Adult Scooters'),
       (10, 'Bike Locks'),
       (11, 'Bags'),
       (12, 'Baskets'),
       (13, 'Bells'),
       (14, 'Phone Mounts'),
       (15, 'Gloves'),
       (16, 'Goggles'),
       (17, 'Helmets'),
       (18, 'Knee Guards'),
       (19, 'Tyres'),
       (20, 'Grips'),
       (21, 'Pedals');

INSERT INTO CATEGORY (ID, NAME)
VALUES (1, 'Bikes'),
       (2, 'Scooters'),
       (3, 'Accessories'),
       (4, 'Apparel'),
       (5, 'Components');

INSERT INTO PRODUCT (ID, CATEGORY_ID, NAME, DESCRIPTION, PRICE)
VALUES
    -- [3] Mountain Bikes
    (1, 1, 'Trek Marlin 5', 'The Trek Marlin 5 is a great all-around mountain bike for beginners and experienced riders alike. It features a durable aluminum frame, a 21-speed drivetrain, and a suspension fork to help you tackle any terrain.', 599.99),
    (2, 1, 'Specialized Rockhopper Comp 29', 'The Specialized Rockhopper Comp 29 is a lightweight and fast mountain bike that\'s perfect for cross-country riding. It features a durable aluminum frame, a 29-inch wheelset, and a wide range of gears to help you climb any hill.', 1099.99),
    (3, 1, 'Giant Talon 2', 'The Giant Talon 2 is a versatile mountain bike that\'s perfect for trail riding and commuting. It features a lightweight aluminum frame, a 27.5-inch wheelset, and a 21-speed drivetrain.', 499.99),
    (4, 1, 'Cannondale Trail 8', 'The Cannondale Trail 8 is a full-suspension mountain bike that\'s perfect for technical trails. It features a durable aluminum frame, a 120mm suspension fork and rear shock, and a 1x12 drivetrain.', 2199.99),
    (5, 1, 'Santa Cruz Hightower LT S', 'The Santa Cruz Hightower LT S is a long-travel enduro mountain bike that\'s perfect for shredding the biggest descents. It features a durable carbon fiber frame, a 160mm suspension fork and rear shock, and a 1x12 drivetrain.', 5999.99),
    (6, 1, 'Yeti SB150 GX Eagle', 'The Yeti SB150 GX Eagle is a lightweight and responsive trail mountain bike that\'s perfect for all types of riding. It features a durable carbon fiber frame, a 150mm suspension fork and rear shock, and a 1x12 drivetrain.', 5499.99),
    -- [4] Road Bikes
    (7, 1, 'Specialized Tarmac SL7 Comp', 'The Specialized Tarmac SL7 Comp is a lightweight and fast road bike that\'s perfect for racing and long rides. It features a durable carbon fiber frame, a Shimano Ultegra drivetrain, and a set of deep-section wheels.', 3999.99),
    (8, 1, 'Cannondale CAAD Optimo 3 Disc', 'The Cannondale CAAD Optimo 3 Disc is a responsive and lightweight road bike that\'s perfect for club rides and races. It features an aluminum frame, a Shimano Sora drivetrain, and hydraulic disc brakes.', 1399.99),
    (9, 1, 'Cervelo R3', 'The Cervelo R3 is a high-performance road bike that\'s perfect for serious cyclists. It features a lightweight carbon fiber frame, a Shimano Ultegra Di2 drivetrain, and a set of deep-section wheels.', 4999.99),
    (10, 1, 'Pinarello Gan RS', 'The Pinarello Gan RS is a race-ready road bike that\'s perfect for the most demanding cyclists. It features a lightweight carbon fiber frame, a Shimano Dura-Ace Di2 drivetrain, and a set of lightweight wheels.', 7999.99),
    -- [5] Electric Bikes
    (11, 1, 'Specialized Turbo Vado SL 5.0 EQ', 'The Specialized Turbo Vado SL 5.0 EQ is a lightweight and versatile electric bike that\'s perfect for commuting, running errands, and exploring. It features a durable aluminum frame, a Shimano Deore 10-speed drivetrain, and a 500Wh battery.', 3999.99),
    (12, 1, 'Trek Émonda ALR 5 Electric', 'The Trek Émonda ALR 5 Electric is a lightweight and fast electric road bike that\'s perfect for fitness rides and commuting. It features a durable aluminum frame, a Shimano 105 drivetrain, and a 300Wh battery.', 3499.99),
    (13, 1, 'Giant Contend AR 3 E+ Disc', 'The Giant Contend AR 3 E+ Disc is a comfortable and affordable electric road bike that\'s perfect for beginners and casual riders. It features an aluminum frame, a Shimano Sora drivetrain, and a 250Wh battery.', 2899.99),
    (14, 1, 'Aventon Level 2', 'The Aventon Level 2 is a stylish and affordable electric commuter bike that\'s perfect for zipping around town. It features a lightweight aluminum frame, a 500W motor, and a 418Wh battery.', 1799.99),
    (15, 1, 'Cannondale Tesoro Neo 3 Disc', 'The Cannondale Tesoro Neo 3 Disc is a versatile and comfortable electric hybrid bike that\'s perfect for commuting, running errands, and exploring. It features an aluminum frame, a Shimano Deore 10-speed drivetrain, and a 500Wh battery.', 2999.99),
    -- [6] Casual Bikes
    (16, 2, 'Giant Escape 3', 'The Giant Escape 3 is a comfortable and affordable hybrid bike that\'s perfect for commuting, running errands, and exploring. It features a durable aluminum frame, a Shimano Altus 7-speed drivetrain, and a comfortable saddle.', 499.99),
    (17, 2, 'Trek Verve 1', 'The Trek Verve 1 is a versatile and comfortable hybrid bike that\'s perfect for commuting, running errands, and exploring. It features a durable aluminum frame, a Shimano Deore 9-speed drivetrain, and a comfortable saddle.', 699.99),
    (18, 2, 'Specialized Sirrus X 2.0', 'The Specialized Sirrus X 2.0 is a lightweight and fast hybrid bike that\'s perfect for commuting, fitness rides, and exploring. It features a durable aluminum frame, a Shimano Deore 10-speed drivetrain, and a comfortable saddle.', 899.99),
    (19, 2, 'Cannondale Quick 4', 'The Cannondale Quick 4 is a stylish and comfortable hybrid bike that\'s perfect for commuting, running errands, and exploring. It features a durable aluminum frame, a Shimano Deore 10-speed drivetrain, and a comfortable saddle.', 999.99),
    -- [7] Street Scooters
    (20, 2, 'Honda Metropolitan', 'The Honda Metropolitan is a popular street scooter that is known for its fuel efficiency and reliability. It features a 50cc engine, a comfortable seat, and plenty of storage space.', 2499.99),
    (21, 2, 'Yamaha Vino 125', 'The Yamaha Vino 125 is a stylish and affordable street scooter that is perfect for commuting and running errands. It features a 125cc engine, a comfortable seat, and a sleek design.', 2799.99),
    (22, 2, 'Kymco Like 200i', 'The Kymco Like 200i is a versatile and comfortable street scooter that is perfect for commuting, running errands, and exploring. It features a 200cc engine, a comfortable seat, and plenty of storage space.', 3299.99),
    (23, 2, 'SYM Fiddle III 150', 'The SYM Fiddle III 150 is a stylish and affordable street scooter that is perfect for commuting and running errands. It features a 150cc engine, a comfortable seat, and a sleek design.', 2999.99),
    (24, 2, 'Vespa LX 150', 'The Vespa LX 150 is a classic and stylish street scooter that is perfect for commuting and running errands. It features a 150cc engine, a comfortable seat, and a vintage design.', 3799.99),
    (25, 2, 'Piaggio Liberty 125', 'The Piaggio Liberty 125 is a lightweight and affordable street scooter that is perfect for commuting and running errands. It features a 125cc engine, a comfortable seat, and a modern design.', 2899.99),
    (26, 2, 'Genuine Buddy 175i', 'The Genuine Buddy 175i is a stylish and affordable street scooter that is perfect for commuting and running errands. It features a 175cc engine, a comfortable seat, and a retro design.', 3499.99),
    (27, 2, 'Aprilia Scarabeo 125 iGet', 'The Aprilia Scarabeo 125 iGet is a sporty and stylish street scooter that is perfect for commuting and running errands. It features a 125cc engine, a comfortable seat, and a modern design.', 3199.99),
    -- [8] Electric Scooters
    (28, 2, 'NIU NQi GTS', 'The NIU NQi GTS is a popular electric scooter that is known for its long range and stylish design. It features a 3.5kW motor, a 60V 35Ah battery, and a top speed of 70 km/h.', 3999.99),
    (29, 2, 'Gogoro S2', 'The Gogoro S2 is a lightweight and affordable electric scooter that is perfect for commuting and running errands. It features a 3.5kW motor, a 1.7kWh battery, and a top speed of 90 km/h.', 2999.99),
    (30, 2, 'Super Soco TC Max', 'The Super Soco TC Max is a powerful and sporty electric scooter that is perfect for city riding. It features a 5kW motor, a 72V 60Ah battery, and a top speed of 100 km/h.', 4999.99),
    -- [9] Adult Scooters
    (31, 2, 'Razor E300', 'The Razor E300 is a popular adult scooter that is known for its durability and affordability. It features a 250W motor, a 24V battery, and a top speed of 16 mph.', 399.99),
    (32, 2, 'Swagtron Swagger 5 Elite', 'The Swagtron Swagger 5 Elite is a lightweight and portable adult scooter that is perfect for commuting and running errands. It features a 350W motor, a 36V battery, and a top speed of 18 mph.', 499.99),
    -- [10] Bike Locks
    (33, 3, 'Kryptonite New York Fahgettaboudit Mini', 'The Kryptonite New York Fahgettaboudit Mini is a heavy-duty U-lock that is perfect for securing your bike in high-crime areas.', 79.99),
    (34, 3, 'Abus Granit X-Plus 540 U-Lock', 'The Abus Granit X-Plus 540 U-Lock is another heavy-duty U-lock that is known for its durability and security.', 89.99),
    (35, 3, 'OnGuard Brute LS U-Lock', 'The OnGuard Brute LS U-Lock is a lightweight and affordable U-lock that is perfect for everyday use.', 59.99),
    (36, 3, 'Hiplok Z Lok Combo', 'The Hiplok Z Lok Combo is a foldable lock that is easy to carry and use.', 49.99),
    -- [11] Bags
    (37, 3, 'Timbuk2 Classic Messenger Bag', 'The Timbuk2 Classic Messenger Bag is a durable and stylish messenger bag that is perfect for everyday use.', 99.99),
    (38, 3, 'Chrome Industries Barrage Cargo Backpack', 'The Chrome Industries Barrage Cargo Backpack is a waterproof and durable backpack that is perfect for commuting and running errands.', 129.99),
    (39, 3, 'Osprey Daylite Plus Backpack', 'The Osprey Daylite Plus Backpack is a lightweight and comfortable backpack that is perfect for hiking and day trips.', 79.99),
    (40, 3, 'Patagonia Arbor Grande Pack', 'The Patagonia Arbor Grande Pack is a versatile and durable backpack that is perfect for travel and everyday use.', 199.99),
    -- [12] Baskets
    (41, 3, 'Wald 137 Basket', 'A classic and durable bike basket that is perfect for carrying groceries or other items.', 24.99),
    (42, 3, 'Basil Portland Basket', 'A stylish and functional bike basket that is perfect for everyday use.', 34.99),
    (43, 3, 'Crate & Barrel Bike Basket', 'A modern and stylish bike basket that is perfect for carrying your essentials.', 44.99),
    -- [14] Phone Mounts
    (44, 3, 'Quad Lock Bike Mount', 'A secure and durable bike mount for your smartphone.', 24.99),
    (45, 3, 'SP Connect Bike Mount', 'A lightweight and easy-to-use bike mount for your smartphone.', 19.99),
    (46, 3, 'Garmin Varia Bike Mount', 'A bike mount with a built-in radar that alerts you to vehicles approaching from behind.', 49.99),
    (47, 3, 'Wahoo KICKR Bike Mount', 'A bike mount that is compatible with Wahoo KICKR trainers.', 34.99),
    (48, 3, 'Cateye Quick Release Bike Mount', 'A quick-release bike mount that is easy to install and remove.', 14.99),
    (49, 3, 'Topeak RideCase Bike Mount', 'A bike mount with a built-in case that protects your smartphone from the elements.', 29.99),
    (50, 3, 'Orucase Bike Mount', 'A stylish and durable bike mount that is made from recycled materials.', 39.99),
    -- [15] Gloves
    (51, 4, 'Giro DND Glove', 'A durable and comfortable bike glove that is perfect for all types of riding.', 39.99),
    (52, 4, 'Pearl Izumi Elite Gel Glove', 'A lightweight and breathable bike glove that is perfect for long rides.', 29.99),
    (53, 4, 'Specialized BG Sport Glove', 'A comfortable and supportive bike glove that is perfect for riders with hand pain.', 49.99),
    (54, 4, 'Fox Racing Ranger Glove', 'A durable and versatile bike glove that is perfect for all types of riding.', 59.99),
    -- [16] Goggles
    (55, 4, 'Oakley Jawbreaker', 'A lightweight and durable pair of cycling glasses that offer excellent protection from the sun and wind.', 299.99),
    (56, 4, 'Giro Factor MIPS', 'A comfortable and stylish pair of cycling glasses with MIPS technology to protect your head in the event of a crash.', 199.99),
    (57, 4, 'Smith Optics Pivlock Arena Max', 'A versatile pair of cycling glasses that are perfect for all types of riding.', 179.99),
    -- [17] Helmet
    (58, 4, 'Giro Foray MIPS', 'A lightweight and comfortable helmet with MIPS technology to protect your head in the event of a crash.', 49.99),
    (59, 4, 'Specialized Align II MIPS', 'A durable and versatile helmet with MIPS technology that is perfect for all types of riding.', 69.99),
    (60, 4, 'Bell Zephyr MIPS', 'A stylish and aerodynamic helmet with MIPS technology that is perfect for road cyclists.', 89.99),
    -- [18] Knee Guard
    (61, 4, 'POC VPD 2.0 Knee Guard', 'A versatile and durable knee guard with VPD 2.0 technology to protect your knees in the event of a crash.', 89.99),
    -- [19] Tires
    (62, 5, 'Continental Grand Prix 5000 TL', 'A high-performance road tire that is known for its speed and durability.', 89.99),
    (63, 5, 'Schwalbe Marathon Plus Tour', 'A puncture-resistant touring tire that is perfect for long rides.', 79.99),
    (64, 5, 'Specialized Pathfinder Pro', 'A versatile gravel tire that is perfect for both on-road and off-road riding.', 69.99),
    (65, 5, 'Maxxis Minion DHF', 'A high-performance downhill tire that is perfect for technical terrain.', 79.99),
    (66, 5, 'Vittoria Barzo', 'A lightweight and fast cross-country tire that is perfect for racing.', 69.99),
    (67, 5, 'Panaracer Pasela', 'A comfortable and versatile city tire that is perfect for commuting and running errands.', 49.99),
    (68, 5, 'Schwalbe Big Apple', 'A wide and comfortable tire that is perfect for beach cruisers and comfort bikes.', 59.99),
    (69, 5, 'Continental Double Decker', 'A puncture-resistant tire that is perfect for urban riding.', 69.99),
    (70, 5, 'Specialized Nimbus Airless', 'A puncture-proof tire that is perfect for low-maintenance riding.', 99.99),
    (71, 5, 'Maxxis Hookworm', 'A lightweight and fast fat bike tire that is perfect for riding in snow and sand.', 109.99),
    -- [20] Grips
    (72, 5, 'ESI Grips Chunky', 'A thick and soft grip that is perfect for riders with large hands.', 34.99),
    (73, 5, 'Lizard Skins DSP 1.8 Grips', 'A durable and comfortable grip with a diamond pattern that provides good grip in all conditions.', 39.99),
    (74, 5, 'ODI Rogue MX Grips', 'A durable and lightweight grip that is perfect for aggressive riding.', 29.99),
    (75, 5, 'Ergon GX1 Grips', 'A comfortable and durable grip with a cork surface that absorbs vibration.', 49.99),
    (76, 5, 'Wolf Tooth Kombucha Grips', 'A comfortable and sticky grip that is perfect for all types of riding.', 39.99),
    (77, 5, 'Pnw Components Lock-On Grips', 'A lightweight and affordable grip that is perfect for everyday riding.', 19.99),
    -- [21] Pedals
    (78, 5, 'Shimano XT M8100 Pedals', 'A durable and lightweight pedal that is perfect for all types of riding.', 399.99),
    (79, 5, 'Crankbrothers Mallet DH Pedals', 'A platform pedal with a large platform and adjustable pins that is perfect for downhill and enduro riding.', 499.99),
    (80, 5, 'Time ATAC XC 12 Pedals', 'A clipless pedal with a lightweight and durable design that is perfect for cross-country racing.', 299.99),
    (81, 5, 'Look Keo Blade Carbon Pedals', 'A lightweight and aerodynamic clipless pedal that is perfect for road racing.', 699.99),
    (82, 5, 'Speedplay Zero Aero Pedals', 'A lightweight and aerodynamic clipless pedal with a unique design that allows for easy entry and exit.', 499.99),
    (83, 5, 'Wahoo KICKR Power Pedals', 'A power meter pedal that is perfect for training and racing.', 799.99);


INSERT INTO PRODUCT_FILTER_LINK (PRODUCT_FILTER_ID, PRODUCT_ID)
VALUES
    -- Bikes
    (3, 1),
    (2, 1),
    (3, 2),
    (1, 2),
    (3, 3),
    (1, 3),
    (3, 4),
    (2, 4),
    (3, 5),
    (1, 5),
    (3, 6),
    (2, 6),
    -- Road Bikes
    (4, 7),
    (1, 7),
    (4, 8),
    (2, 8),
    (4, 9),
    (1, 9),
    (4, 10),
    (2, 10),
    -- Electric Bikes
    (5, 11),
    (1, 11),
    (5, 12),
    (2, 12),
    (5, 13),
    (1, 13),
    (5, 14),
    (2, 14),
    (5, 15),
    (1, 15),
    -- Casual Bikes
    (6, 16),
    (1, 16),
    (6, 17),
    (2, 17),
    (6, 18),
    (1, 18),
    (6, 19),
    (2, 19),
    -- Street Scooters
    (7, 20),
    (1, 20),
    (7, 21),
    (2, 21),
    (7, 22),
    (1, 22),
    (7, 23),
    (2, 23),
    (7, 24),
    (1, 24),
    (7, 25),
    (2, 25),
    (7, 26),
    (1, 26),
    (7, 27),
    (2, 27),
    -- Electric Scooters
    (8, 28),
    (1, 28),
    (8, 29),
    (2, 29),
    (8, 30),
    (1, 30),
    -- Adult Scooters
    (9, 31),
    (1, 31),
    (9, 32),
    (2, 32),
    -- Bike Locks
    (10, 33),
    (10, 34),
    (10, 35),
    (10, 36),
    -- Bags
    (11, 37),
    (11, 38),
    (11, 39),
    (11, 40),
    -- Baskets
    (12, 41),
    (12, 42),
    (12, 43),
    -- Phone Mounts
    (14, 44),
    (14, 45),
    (14, 46),
    (14, 47),
    (14, 48),
    (14, 49),
    (14, 50),
    -- Gloves
    (15, 51),
    (1, 51),
    (15, 52),
    (2, 52),
    (15, 53),
    (1, 53),
    (15, 54),
    (2, 54),
    -- Goggles
    (16, 55),
    (16, 56),
    (16, 57),
    -- Helmets
    (17, 58),
    (1, 58),
    (17, 59),
    (2, 59),
    (17, 60),
    (1, 60),
    -- Knee Guards
    (18, 61),
    -- Tires
    (19, 62),
    (19, 63),
    (19, 64),
    (19, 65),
    (19, 66),
    (19, 67),
    (19, 68),
    (19, 69),
    (19, 70),
    (19, 71),
    -- Grips
    (20, 72),
    (20, 73),
    (20, 74),
    (20, 75),
    (20, 76),
    (20, 77),
    -- Pedals
    (21, 78),
    (21, 79),
    (21, 80),
    (21, 81),
    (21, 82),
    (21, 83);

-- Roles that each user can have, for example, staff and managers would have their own roles
DROP TABLE IF EXISTS USER_ROLES;
CREATE TABLE USER_ROLES(
                           ID INT NOT NULL,
                           NAME NVARCHAR(100),
                           PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS USER;
CREATE TABLE USER(
                     ID INT primary key auto_increment,
                     USER_ROLE_ID INT NOT NULL DEFAULT(1),
                     EMAIL_ADDRESS VARCHAR(500) unique,
                     FIRST_NAME VARCHAR(50),
                     LAST_NAME VARCHAR(50),
                     PASSWORD VARCHAR(500),
                     ADDRESS VARCHAR(200),
                     SUBURB VARCHAR(10),
                     STATE VARCHAR(10),
                     POSTCODE VARCHAR(4),
                     COUNTRY VARCHAR(50),
                     PHONE VARCHAR(14),
                     FOREIGN KEY (USER_ROLE_ID) REFERENCES USER_ROLES(ID)
);

-- | INSERTING DATA | --

INSERT INTO USER_ROLES(ID, NAME)
VALUES (1, 'Member'), -- Can read the database and make purchases
       (2, 'Staff'), -- Can access the rosters
       (3, 'Managers'),
       (4, 'SysAdmin'); -- Full read write access, even to users

























