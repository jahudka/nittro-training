CREATE TABLE "public"."users" (
    "id" INT NOT NULL,
    "login" VARCHAR(127) NOT NULL,
    "password_hash" VARCHAR(72) NOT NULL,
    CONSTRAINT "uniq_1483a5e9aa08cb10" UNIQUE ("login"),
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
);

CREATE SEQUENCE "public"."users_id_seq";



CREATE TABLE "public"."homepage_panels" (
    "id" INT NOT NULL,
    "position" INT NOT NULL,
    "type" VARCHAR(5) NOT NULL,
    "content" TEXT,
    "width" INT,
    "height" INT,
    "publish_from" DATE,
    "publish_until" DATE,
    CONSTRAINT "homepage_panels_pkey" PRIMARY KEY ("id")
);

COMMENT ON COLUMN "public"."homepage_panels"."publish_from" IS '(DC2Type:date_immutable)';
COMMENT ON COLUMN "public"."homepage_panels"."publish_until" IS '(DC2Type:date_immutable)';
CREATE SEQUENCE "public"."homepage_panels_id_seq" START WITH 11;



CREATE TABLE "public"."menu_categories" (
    "id" INT NOT NULL,
    "position" INT NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    CONSTRAINT "menu_categories_pkey" PRIMARY KEY ("id")
);

CREATE SEQUENCE "public"."menu_categories_id_seq" START WITH 8;



CREATE TABLE "public"."menu_items" (
   "id" INT NOT NULL,
   "category_id" INT NOT NULL,
   "position" INT NOT NULL,
   "name" VARCHAR(255) NOT NULL,
   "price" text NOT NULL,
   CONSTRAINT "menu_items_pkey" PRIMARY KEY ("id"),
   CONSTRAINT "fk_70b2ca2a12469de2" FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
);

COMMENT ON COLUMN "public"."menu_items"."price" IS '(DC2Type:array)';
CREATE INDEX "idx_70b2ca2a12469de2" ON "public"."menu_items" USING btree ("category_id");
CREATE SEQUENCE "public"."menu_items_id_seq" START WITH 176;




INSERT INTO "homepage_panels" ("id", "position", "type", "content", "width", "height", "publish_from", "publish_until")
VALUES
    (1, 0, 'text', E'dobrá hudba,\ndobrý kafe.\npojď!', NULL, NULL, NULL, NULL),
    (5, 4, 'text', E'otevřeno:\npo - pá 10 - 1\nso - ne 12 - 1', NULL, NULL, '2018-09-01', '2019-06-30'),
    (7, 1, 'image', NULL, 1021, 680, NULL, NULL),
    (8, 2, 'image', NULL, 1021, 680, NULL, NULL),
    (9, 3, 'image', NULL, 1021, 680, NULL, NULL),
    (10, 5, 'text', E'otevřeno:\npo - ne 12 - 1', NULL, NULL, '2019-07-01', '2019-08-31');




INSERT INTO "menu_categories" ("id", "position", "name")
VALUES
    (1, 0, 'káva'),
    (2, 1, 'horké nápoje'),
    (3, 5, 'nealko'),
    (4, 2, 'pivo'),
    (5, 3, 'víno & cider'),
    (6, 4, 'panáky'),
    (7, 6, 'jídlo & pochutiny');




INSERT INTO "menu_items" ("id", "category_id", "position", "name", "price")
VALUES
    (1, 1, 0, 'Espresso', 'a:2:{i:0;i:32;s:11:"s koláčem";i:64;}'),
    (2, 1, 1, 'Americano', 'a:2:{i:0;i:35;s:11:"s koláčem";i:67;}'),
    (3, 1, 2, 'Espresso Double', 'a:2:{i:0;i:52;s:11:"s koláčem";i:84;}'),
    (4, 1, 3, 'Espresso Macchiato', 'a:2:{i:0;i:43;s:11:"s koláčem";i:75;}'),
    (5, 1, 4, 'Cappuccino', 'a:2:{i:0;i:49;s:11:"s koláčem";i:81;}'),
    (6, 1, 5, 'Flat White', 'a:2:{i:0;i:71;s:11:"s koláčem";i:103;}'),
    (48, 3, 45, 'Bezová limonáda s borůvkou', 'a:1:{i:0;i:54;}'),
    (49, 3, 46, 'Bezová limonáda s chilli', 'a:1:{i:0;i:54;}'),
    (50, 3, 47, 'Zázvorová limonáda', 'a:1:{i:0;i:54;}'),
    (10, 1, 9, 'Ice Latte', 'a:2:{i:0;i:54;s:11:"s koláčem";i:86;}'),
    (51, 3, 48, 'Domácí pomerančová limonáda s javorovým syrupem', 'a:1:{i:0;i:64;}'),
    (52, 3, 49, 'Švestková limonáda', 'a:1:{i:0;i:64;}'),
    (53, 3, 50, 'Meruňková limonáda', 'a:1:{i:0;i:64;}'),
    (54, 3, 51, 'Mattoni perlivá / jemně perlivá', 'a:1:{s:5:"0,33l";i:33;}'),
    (55, 3, 52, 'Aquila neperlivá', 'a:1:{s:5:"0,33l";i:33;}'),
    (56, 3, 53, 'Kofola točená', 'a:5:{s:4:"0,1l";i:15;s:4:"0,2l";i:29;s:4:"0,3l";i:36;s:4:"0,4l";i:40;s:4:"0,5l";i:45;}'),
    (12, 1, 10, 'Frappé z Double Espressa', 'a:2:{i:0;i:83;s:11:"s koláčem";i:115;}'),
    (57, 3, 54, 'Malinovka', 'a:1:{s:5:"0,33l";i:38;}'),
    (58, 3, 55, 'Coca Cola / Coca Cola light', 'a:1:{s:5:"0,33l";i:44;}'),
    (59, 3, 56, 'Tonic / Ginger Ale', 'a:1:{s:5:"0,25l";i:42;}'),
    (11, 1, 11, 'Frappé z Espressa', 'a:2:{i:0;i:60;s:11:"s koláčem";i:92;}'),
    (13, 1, 12, 'Espresso Tonic', 'a:1:{i:0;i:68;}'),
    (14, 1, 13, 'Irish Coffee', 'a:1:{i:0;i:95;}'),
    (60, 3, 57, 'Fentimans Ginger Beer', 'a:1:{s:6:"0,275l";i:58;}'),
    (7, 1, 6, 'Cappuccino Double', 'a:2:{i:0;i:82;s:11:"s koláčem";i:114;}'),
    (8, 1, 7, 'Latte Macchiato', 'a:2:{i:0;i:54;s:11:"s koláčem";i:86;}'),
    (9, 1, 8, 'Ice Espresso', 'a:2:{i:0;i:38;s:11:"s koláčem";i:70;}'),
    (17, 1, 14, 'Domácí koláč', 'a:1:{i:0;i:42;}'),
    (16, 2, 13, 'Čaj Dilmah', 'a:2:{i:0;i:38;s:11:"s koláčem";i:70;}'),
    (18, 2, 15, 'Čaj London Herb & Fruit', 'a:2:{i:0;i:38;s:11:"s koláčem";i:70;}'),
    (19, 2, 16, 'Čaj Harney & Sons', 'a:2:{i:0;i:48;s:11:"s koláčem";i:80;}'),
    (20, 2, 17, 'Čaj z čerstvé máty', 'a:2:{i:0;i:56;s:11:"s koláčem";i:88;}'),
    (21, 2, 18, 'Horký zázvor s medem a citronem', 'a:2:{i:0;i:56;s:11:"s koláčem";i:88;}'),
    (22, 2, 19, 'Royal Chai Latte', 'a:1:{i:0;i:56;}'),
    (23, 2, 20, 'Horká belgická čokoláda', 'a:1:{i:0;i:58;}'),
    (24, 2, 21, 'Kakao', 'a:1:{i:0;i:58;}'),
    (25, 2, 22, 'Horké mléko s medem', 'a:1:{i:0;i:39;}'),
    (26, 2, 23, 'Svařený juice / mošt', 'a:1:{i:0;i:49;}'),
    (27, 2, 24, 'Svařený BERNARD švestka', 'a:1:{i:0;i:66;}'),
    (28, 2, 25, 'Horký zázvor se slivovicí Žufánek a citronem', 'a:1:{i:0;i:99;}'),
    (29, 2, 26, 'Svařené víno', 'a:1:{i:0;i:66;}'),
    (30, 2, 27, 'Grog - Slivovica Žufánek + med, citron', 'a:1:{i:0;i:72;}'),
    (31, 2, 28, 'Grog - Hruškovica Žufánek + med, citron', 'a:1:{i:0;i:72;}'),
    (32, 2, 29, 'Grog - Meruňkovica Žufánek + med, citron', 'a:1:{i:0;i:72;}'),
    (33, 2, 30, 'Grog - Sicilský citron Žufánek + med, citron', 'a:1:{i:0;i:99;}'),
    (34, 2, 31, 'Grog - Višňovka Žufánek', 'a:1:{i:0;i:72;}'),
    (35, 2, 32, 'Horká medovina', 'a:1:{i:0;i:79;}'),
    (36, 2, 33, 'Grog - Rum Legendario Elixir de Cuba', 'a:1:{i:0;i:109;}'),
    (37, 2, 34, 'Grog - Rum Old J Spiced', 'a:1:{i:0;i:89;}'),
    (38, 2, 35, 'Grog - Rum Božkov', 'a:1:{i:0;i:66;}'),
    (39, 3, 36, 'Juice Rauch', 'a:1:{s:4:"0,1l";i:19;}'),
    (40, 3, 37, 'Jablečný mošt BIO', 'a:1:{s:4:"0,1l";i:19;}'),
    (41, 3, 38, 'Soda s ledem, mátou a citronem', 'a:2:{s:4:"0,5l";i:44;s:2:"1l";i:78;}'),
    (42, 3, 39, 'Domácí citronáda', 'a:1:{i:0;i:54;}'),
    (43, 3, 40, 'Domácí okurková limonáda', 'a:1:{i:0;i:54;}'),
    (44, 3, 41, 'Domácí mátová limonáda', 'a:1:{i:0;i:54;}'),
    (45, 3, 42, 'Domácí levandulová limonáda', 'a:1:{i:0;i:54;}'),
    (46, 3, 43, 'Malinová limonáda s hřebíčkem', 'a:1:{i:0;i:54;}'),
    (47, 3, 44, 'Rakytníková limonáda s lipovým květem', 'a:1:{i:0;i:54;}'),
    (61, 3, 58, 'Fentimans Curiosity Cola', 'a:1:{s:6:"0,275l";i:58;}'),
    (62, 3, 59, 'Fentimans Cherrytree Cola', 'a:1:{s:6:"0,275l";i:58;}'),
    (64, 3, 61, 'Fentimans Rose lemonade', 'a:1:{s:6:"0,275l";i:58;}'),
    (63, 3, 60, 'Fentimans Victorian lemonade', 'a:1:{s:6:"0,275l";i:58;}'),
    (65, 3, 62, 'Fentimans Mandarin and Seville Orange JIgger', 'a:1:{s:6:"0,275l";i:58;}'),
    (66, 3, 63, 'John Lemon Matchbata / Rebarbora', 'a:1:{i:0;i:52;}'),
    (67, 3, 64, 'Club-Mate', 'a:1:{i:0;i:52;}'),
    (68, 3, 65, 'Red Bull', 'a:1:{i:0;i:56;}'),
    (69, 3, 66, 'Soda', 'a:1:{s:4:"0,1l";i:5;}'),
    (70, 3, 67, 'Mléko', 'a:1:{s:4:"0,1l";i:5;}'),
    (71, 3, 68, 'Banánový milk shake', 'a:1:{i:0;i:54;}'),
    (72, 3, 69, 'Banánovo-kávový milk shake', 'a:1:{i:0;i:74;}'),
    (73, 3, 70, 'Okurkovo-řepový shake', 'a:1:{i:0;i:54;}'),
    (74, 4, 71, 'Kácovská desítka točená', 'a:2:{s:4:"0,5l";i:34;s:4:"0,3l";i:26;}'),
    (75, 4, 72, 'Kácovský ležák točený', 'a:2:{s:4:"0,5l";i:39;s:4:"0,3l";i:32;}'),
    (76, 4, 73, 'Regionální pivo točené', 'a:2:{s:4:"0,5l";i:39;s:4:"0,3l";i:32;}'),
    (77, 4, 74, 'Bernard tmavý ležák', 'a:1:{s:10:"lahev 0,3l";i:45;}'),
    (78, 4, 75, 'Pilsner Urquell', 'a:1:{s:11:"lahev 0,33l";i:45;}'),
    (79, 4, 76, 'Bernard - nealkoholické pivo', 'a:1:{s:11:"lahev 0,33l";i:45;}'),
    (80, 4, 77, 'Bernard Švestka - nealkoholické pivo', 'a:1:{s:11:"lahev 0,33l";i:45;}'),
    (81, 5, 78, 'Rulandské šedé', 'a:5:{s:4:"0,1l";i:32;s:4:"0,2l";i:56;s:5:"0,25l";i:60;s:4:"0,5l";i:115;s:2:"1l";i:230;}'),
    (82, 5, 79, 'Veltínské červené (BIO)', 'a:5:{s:4:"0,1l";i:38;s:4:"0,2l";i:66;s:5:"0,25l";i:70;s:4:"0,5l";i:135;s:2:"1l";i:270;}'),
    (83, 5, 80, 'Cabernet Sauvignon', 'a:5:{s:4:"0,1l";i:32;s:4:"0,2l";i:56;s:5:"0,25l";i:60;s:4:"0,5l";i:115;s:2:"1l";i:230;}'),
    (84, 5, 81, 'Merlot rosé', 'a:5:{s:4:"0,1l";i:32;s:4:"0,2l";i:56;s:5:"0,25l";i:60;s:4:"0,5l";i:115;s:2:"1l";i:230;}'),
    (162, 7, 159, 'Zeleninový salát', 'a:1:{i:0;i:89;}'),
    (85, 5, 82, 'Střik Rulandské / Cabernet', 'a:8:{s:3:"1:1";i:35;s:3:"1:2";i:40;s:3:"2:1";i:60;s:3:"2:2";i:65;s:7:"2,5:2,5";i:70;s:3:"2:3";i:70;s:3:"3:2";i:85;s:3:"5:5";i:130;}'),
    (86, 5, 83, 'Střik Veltínské červené', 'a:8:{s:3:"1:1";i:43;s:3:"1:2";i:47;s:3:"2:1";i:70;s:3:"2:2";i:75;s:7:"2,5:2,5";i:80;s:3:"2:3";i:80;s:3:"3:2";i:98;s:3:"5:5";i:145;}'),
    (87, 5, 84, 'Prosecco', 'a:2:{s:4:"0,1l";i:54;s:4:"0,7l";i:360;}'),
    (88, 5, 85, 'Bohemia Sekt Demi Sec / Brut', 'a:1:{s:5:"0,75l";i:299;}'),
    (89, 5, 86, 'Cider Aspall Premier Cru - Dry', 'a:1:{s:24:"7% alc. / suchý / 0,33l";i:64;}'),
    (90, 5, 87, 'Cider Aspall Draught - Crisp', 'a:1:{s:30:"5,5% alc. / polosuchý / 0,33l";i:64;}'),
    (91, 5, 88, 'Portské Sandeman White / Ruby', 'a:1:{s:4:"0,1l";i:88;}'),
    (92, 5, 89, 'Martini Bianco / Extra Dry / Rosso', 'a:1:{s:4:"0,1l";i:55;}'),
    (93, 5, 90, 'Campari Bitter', 'a:1:{s:5:"0,04l";i:55;}'),
    (94, 6, 91, 'Slivovica Žufánek', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (95, 6, 92, 'Slivovica Žufánek z dubového sudu', 'a:2:{s:5:"0,04l";i:85;s:5:"0,02l";i:50;}'),
    (96, 6, 93, 'Hruškovica Žufánek', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (97, 6, 94, 'Hruškovica Žufánek z dubového sudu', 'a:2:{s:5:"0,04l";i:85;s:5:"0,02l";i:50;}'),
    (98, 6, 95, 'Sicilský citron', 'a:2:{s:5:"0,04l";i:85;s:5:"0,02l";i:50;}'),
    (99, 6, 96, 'Bez černý', 'a:2:{s:5:"0,04l";i:85;s:5:"0,02l";i:50;}'),
    (100, 6, 97, 'Meruňkovica', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (101, 6, 98, 'Borovička', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (102, 6, 99, 'Ořechovka', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (103, 6, 100, 'Višňovka', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (104, 6, 101, 'OMG Gin', 'a:2:{s:5:"0,04l";i:85;s:5:"0,02l";i:50;}'),
    (105, 6, 102, 'Medovina', 'a:2:{s:4:"0,1l";i:45;s:5:"0,04l";i:25;}'),
    (106, 6, 103, 'Rum Božkov', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (107, 6, 104, 'Fernet', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (108, 6, 105, 'Fernet Citrus', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (109, 6, 106, 'Jan Becher', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (110, 6, 107, 'Jägermeister', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (111, 6, 108, 'Zelená Bartida', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (112, 6, 109, 'Vodka Russian STANDARD', 'a:2:{s:5:"0,04l";i:65;s:5:"0,02l";i:40;}'),
    (113, 6, 110, 'Vodka Stolichnaya', 'a:2:{s:5:"0,04l";i:65;s:5:"0,02l";i:40;}'),
    (114, 6, 111, 'Vodka Absolut', 'a:2:{s:5:"0,04l";i:65;s:5:"0,02l";i:40;}'),
    (115, 6, 112, 'Cachaça Velho Barreiro', 'a:2:{s:5:"0,04l";i:65;s:5:"0,02l";i:40;}'),
    (116, 6, 113, 'Rum Havana Club Añejo Blanco', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (117, 6, 114, 'Rum Havana Club Añejo Especial', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (118, 6, 115, 'Rum Havana Club Añejo 7 Años', 'a:2:{s:5:"0,04l";i:85;s:5:"0,02l";i:50;}'),
    (119, 6, 116, 'Rum Malecon 8y.', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (120, 6, 117, 'Rum Malteco 10y.', 'a:2:{s:5:"0,04l";i:80;s:5:"0,02l";i:45;}'),
    (121, 6, 118, 'Rum Malteco 15y.', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (122, 6, 119, 'Rum El Dorado 12y.', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (123, 6, 120, 'Rum Dictador 12y.', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (124, 6, 121, 'Rum Ron Zacapa Centenario 23y.', 'a:2:{s:5:"0,04l";i:140;s:5:"0,02l";i:80;}'),
    (125, 6, 122, 'Rum Legendario Elixir de Cuba', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (126, 6, 123, 'Rum Old J Spiced', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (127, 6, 124, 'Rum Malibu', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:40;}'),
    (128, 6, 125, 'Gin Bombay Sapphire', 'a:2:{s:5:"0,04l";i:80;s:5:"0,02l";i:45;}'),
    (129, 6, 126, 'Gin Saffron', 'a:2:{s:5:"0,04l";i:90;s:5:"0,02l";i:50;}'),
    (131, 6, 128, 'OMG Gin', 'a:2:{s:5:"0,04l";i:90;s:5:"0,02l";i:50;}'),
    (132, 6, 129, 'Absinthe St. Antoine Žufánek', 'a:2:{s:5:"0,04l";i:90;s:5:"0,02l";i:50;}'),
    (133, 6, 130, 'Justifiée & Ancienne', 'a:2:{s:5:"0,04l";i:170;s:5:"0,02l";i:90;}'),
    (134, 6, 131, 'Pastis Ricard', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (135, 6, 132, 'Tequilla El Jimador 100% Agave Blanco / Reposado', 'a:2:{s:5:"0,04l";i:90;s:5:"0,02l";i:50;}'),
    (136, 6, 133, 'Mezcal Gusano Rojo', 'a:2:{s:5:"0,04l";i:80;s:5:"0,02l";i:45;}'),
    (137, 6, 134, 'Whisky Ballantines', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:40;}'),
    (138, 6, 135, 'Whisky Johnnie Walker', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:40;}'),
    (139, 6, 136, 'Whisky Laphroaig 10y.', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (140, 6, 137, 'Whisky Chivas Regal 12y.', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (141, 6, 138, 'Whiskey Jameson', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (142, 6, 139, 'Whiskey Tullamore Dew', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (143, 6, 140, 'Whiskey Bushmills 10y.', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (144, 6, 141, 'Tennessee Whiskey Jack Daniels', 'a:2:{s:5:"0,04l";i:80;s:5:"0,02l";i:45;}'),
    (145, 6, 142, 'Bourbon Jim Beam', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:35;}'),
    (147, 6, 144, 'Metaxa *****', 'a:2:{s:5:"0,04l";i:70;s:5:"0,02l";i:40;}'),
    (148, 6, 145, 'Hennessy V.S.', 'a:2:{s:5:"0,04l";i:90;s:5:"0,02l";i:50;}'),
    (150, 6, 147, 'Kahlúa', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:40;}'),
    (151, 6, 148, 'Calvados Chateau du Breuil fine', 'a:2:{s:5:"0,04l";i:75;s:5:"0,02l";i:45;}'),
    (152, 6, 149, 'Grappa Fior di Vite', 'a:2:{s:5:"0,04l";i:75;s:5:"0,02l";i:45;}'),
    (130, 6, 127, 'Gin Hendrick''s', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (146, 6, 143, 'Bourbon Maker''s Mark', 'a:2:{s:5:"0,04l";i:95;s:5:"0,02l";i:55;}'),
    (149, 6, 146, 'Baileys', 'a:2:{s:5:"0,04l";i:60;s:5:"0,02l";i:40;}'),
    (153, 7, 150, 'Polévka', 'a:1:{s:19:"dle denní nabídky";i:54;}'),
    (154, 7, 151, 'Koláč', 'a:1:{s:19:"dle denní nabídky";i:42;}'),
    (155, 7, 152, 'Quiche', 'a:1:{s:19:"dle denní nabídky";i:54;}'),
    (156, 7, 153, 'Zapečený Camembert s pestem a olivami', 'a:1:{i:0;i:79;}'),
    (157, 7, 154, 'Zapečený Camembert s ajvarem a olivami', 'a:1:{i:0;i:79;}'),
    (158, 7, 155, 'Zapečený Camembert s brusinkami', 'a:1:{i:0;i:79;}'),
    (161, 7, 158, 'Salát s kozím sýrem', 'a:1:{i:0;i:109;}'),
    (160, 7, 157, 'Salát Mozzarella, rajčata, rukola', 'a:1:{i:0;i:99;}'),
    (164, 7, 161, 'Zapečený chléb', 'a:1:{s:73:"s Gorgonzolou, černými olivami, rajčaty, salátovými lístky a pestem";i:89;}'),
    (163, 7, 160, 'Zapečený chléb', 'a:1:{s:66:"s hovězí šunkou, goudou, kyselou okurkou a dijonskou hořčicí";i:89;}'),
    (165, 7, 162, 'Zapečený chléb', 'a:1:{s:54:"s Mozzarellou, rajčaty, salátovými lístky a pestem";i:89;}'),
    (166, 7, 163, 'Sendvič', 'a:1:{s:87:"s pažitkovým tvarohem, okurkou, rajčaty, salátovými lístky a dijonskou hořčicí";i:84;}'),
    (167, 7, 164, 'Otíkův utopenec', 'a:1:{i:0;i:49;}'),
    (159, 7, 156, 'Zapečená Mozzarella v sušené šunce', 'a:1:{i:0;i:89;}'),
    (169, 7, 166, 'Bake Rolls', 'a:1:{s:24:"sůl / česnek / slanina";i:38;}'),
    (168, 7, 165, 'Brambůrky', 'a:1:{s:14:"sůl / česnek";i:38;}'),
    (170, 7, 167, 'Tretter''s zeleninové chipsy', 'a:1:{i:0;i:44;}'),
    (171, 7, 168, 'Arašídy Ar Rashid', 'a:1:{i:0;i:36;}'),
    (172, 7, 169, 'Kešu Ar Rashid', 'a:1:{i:0;i:48;}'),
    (173, 7, 170, 'Mandle Ar Rashid', 'a:1:{i:0;i:58;}'),
    (174, 7, 171, 'Pistácie Ar Rashid', 'a:1:{i:0;i:48;}'),
    (175, 7, 172, 'Olivy', 'a:1:{i:0;i:58;}');
