DROP TABLE IF EXISTS "announcements";
DROP TABLE IF EXISTS "clubs";
DROP TABLE IF EXISTS "competitions";
DROP TABLE IF EXISTS "competitors";
DROP TABLE IF EXISTS "federations";
DROP TABLE IF EXISTS "matches";
DROP TABLE IF EXISTS "users";
DROP TABLE IF EXISTS "teams";
DROP TABLE IF EXISTS "referees";
DROP TABLE IF EXISTS "groups";
DROP TABLE IF EXISTS "gradings";
DROP TABLE IF EXISTS "participants";
DROP TABLE IF EXISTS "team_groups";
DROP TABLE IF EXISTS "team_matches";
DROP TABLE IF EXISTS "team_participants";
DROP TABLE IF EXISTS "team_participants_competitor_id";
DROP TABLE IF EXISTS "referee_matches_referee_id";
DROP TABLE IF EXISTS "referee_matches";
DROP TABLE IF EXISTS "rounds";
DROP TABLE IF EXISTS "tasks";


CREATE TABLE "announcements" (
  "tournament_id" int(11) NOT NULL,
  "host" varchar(100) NOT NULL,
  "organisator" varchar(100) NOT NULL,
  "location" int(11) NOT NULL,
  "schedule" text NOT NULL,
  "guidance" varchar(100) NOT NULL,
  "registration_info" text NOT NULL,
  "mode_info" text NOT NULL,
  "costs" float NOT NULL,
  "catering" text NOT NULL,
  "nightstay" text NOT NULL,
  "referee_info" text NOT NULL
);
CREATE TABLE "clubs" (
  "id" int(11) NOT NULL ,
  "name" varchar(100) NOT NULL,
  "abbrev" varchar(10) NOT NULL,
  "location" varchar(100) NOT NULL,
  "federation_id" varchar(100) NOT NULL,
  "contact" varchar(100) NOT NULL,
  "email" varchar(100) NOT NULL,
  "phone" varchar(100) NOT NULL,
  "fax" varchar(100) NOT NULL,
  "address" varchar(100) NOT NULL,
  "competitors_count" int(11) NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "clubs" VALUES (20,'asdasd','sdasd','asda','','','','','','',0);
INSERT INTO "clubs" VALUES (21,'asdasd','sdasd','asda','','','','','','',0);
CREATE TABLE "competitions" (
  "id" int(11) NOT NULL ,
  "name" varchar(100) NOT NULL,
  "date" date NOT NULL,
  "location" varchar(100) NOT NULL,
  "is_running" tinyint(1) NOT NULL,
  "status" int(11) NOT NULL,
  "round" int(11) NOT NULL,
  "logo" varchar(50) NOT NULL,
  "active" tinyint(4) NOT NULL,
  "type" varchar(50) NOT NULL,
  "areas" int(11) NOT NULL,
  "mode" varchar(50) NOT NULL,
  "grading_mode" varchar(30) NOT NULL,
  "participant_count" int(11) NOT NULL,
  "matches_count" int(11) NOT NULL,
  "group_size" int(11) NOT NULL,
  "team_size" int(11) NOT NULL,
  "options" text NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "competitions" VALUES (1,'Test Tournament','2017-01-31','Testhausen',0,0,0,'',0,'individual',0,'elimination','',0,0,0,0,'');
CREATE TABLE "competitors" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "name" varchar(100) NOT NULL,
  "first_name" varchar(255) DEFAULT NULL,
  "alias" varchar(50) NOT NULL,
  "club_id" int(11) DEFAULT NULL,
  "grading_id" int(30) NOT NULL,
  "birth_date" date NOT NULL,
  "pass_nr" varchar(10) NOT NULL,
  "body_height" float NOT NULL,
  "image" varchar(100) NOT NULL,
  "list" varchar(255) NOT NULL,
  "gender" varchar(6) NOT NULL,
  "blocked" tinyint(1) NOT NULL
);
INSERT INTO "competitors" VALUES (1,'Hessel','Chance','',NULL,0,'1999-08-12','1',0,'','','',0);
INSERT INTO "competitors" VALUES (2,'Kerluke','Jaiden','',NULL,0,'1976-03-23','2',0,'','','',0);
INSERT INTO "competitors" VALUES (3,'Feeney','Libby','',NULL,0,'2004-07-06','3',0,'','','',0);
INSERT INTO "competitors" VALUES (4,'Fay','Deven','',NULL,0,'1973-04-08','4',0,'','','',0);
INSERT INTO "competitors" VALUES (5,'West','Chelsie','',NULL,0,'1976-08-01','5',0,'','','',0);
INSERT INTO "competitors" VALUES (6,'Ondricka','Felix','',NULL,0,'1997-12-07','6',0,'','','',0);
INSERT INTO "competitors" VALUES (7,'Jaskolski','Santino','',NULL,0,'1979-02-19','7',0,'','','',0);
INSERT INTO "competitors" VALUES (8,'Vandervort','August','',NULL,0,'2002-07-05','8',0,'','','',0);
INSERT INTO "competitors" VALUES (9,'Osinski','Dayne','',NULL,0,'2004-03-18','9',0,'','','',0);
INSERT INTO "competitors" VALUES (10,'Ullrich','Demarcus','',NULL,0,'2006-03-07','10',0,'','','',0);
INSERT INTO "competitors" VALUES (11,'Streich','Allene','',NULL,0,'2007-03-16','11',0,'','','',0);
INSERT INTO "competitors" VALUES (12,'Senger','Adah','',NULL,0,'1999-12-12','12',0,'','','',0);
INSERT INTO "competitors" VALUES (13,'Ryan','Cyril','',NULL,0,'1999-01-07','13',0,'','','',0);
INSERT INTO "competitors" VALUES (14,'Emmerich','Ayla','',NULL,0,'1987-11-15','14',0,'','','',0);
INSERT INTO "competitors" VALUES (15,'Morar','Kaelyn','',NULL,0,'1995-10-25','15',0,'','','',0);
INSERT INTO "competitors" VALUES (16,'Rowe','Marcelino','',NULL,0,'2015-09-07','16',0,'','','',0);
INSERT INTO "competitors" VALUES (17,'Zboncak','Ayden','',NULL,0,'1998-11-26','17',0,'','','',0);
INSERT INTO "competitors" VALUES (18,'Simonis','Maurine','',NULL,0,'2000-01-15','18',0,'','','',0);
INSERT INTO "competitors" VALUES (19,'Mueller','Tony','',NULL,0,'1972-01-26','19',0,'','','',0);
INSERT INTO "competitors" VALUES (20,'Jast','Ricky','',NULL,0,'2015-08-25','20',0,'','','',0);
INSERT INTO "competitors" VALUES (21,'Stiedemann','Darlene','',NULL,0,'1981-08-14','21',0,'','','',0);
INSERT INTO "competitors" VALUES (22,'Gibson','Adalberto','',NULL,0,'1991-07-12','22',0,'','','',0);
INSERT INTO "competitors" VALUES (23,'Miller','Domenica','',NULL,0,'1999-04-16','23',0,'','','',0);
INSERT INTO "competitors" VALUES (24,'McKenzie','German','',NULL,0,'2009-07-13','24',0,'','','',0);
INSERT INTO "competitors" VALUES (25,'Bernhard','Julianne','',NULL,0,'1978-03-21','25',0,'','','',0);
INSERT INTO "competitors" VALUES (26,'Rolfson','Garland','',NULL,0,'1975-01-27','26',0,'','','',0);
INSERT INTO "competitors" VALUES (27,'Kemmer','Nelson','',NULL,0,'2004-12-07','27',0,'','','',0);
INSERT INTO "competitors" VALUES (28,'Kuphal','Jerel','',NULL,0,'1993-12-04','28',0,'','','',0);
INSERT INTO "competitors" VALUES (29,'Lynch','Delia','',NULL,0,'1992-11-05','29',0,'','','',0);
INSERT INTO "competitors" VALUES (30,'Labadie','Ella','',NULL,0,'2011-04-08','30',0,'','','',0);
INSERT INTO "competitors" VALUES (31,'Larson','Jakob','',NULL,0,'1978-01-24','31',0,'','','',0);
INSERT INTO "competitors" VALUES (32,'Zboncak','Gillian','',NULL,0,'1976-09-29','32',0,'','','',0);
INSERT INTO "competitors" VALUES (33,'Oberbrunner','Allie','',NULL,0,'1970-08-30','33',0,'','','',0);
INSERT INTO "competitors" VALUES (34,'Hamill','Imelda','',NULL,0,'2016-09-22','34',0,'','','',0);
INSERT INTO "competitors" VALUES (35,'Jacobson','Eleanora','',NULL,0,'1997-07-06','35',0,'','','',0);
INSERT INTO "competitors" VALUES (36,'Sauer','Will','',NULL,0,'2000-06-07','36',0,'','','',0);
INSERT INTO "competitors" VALUES (37,'Bartoletti','Lora','',NULL,0,'1979-12-18','37',0,'','','',0);
INSERT INTO "competitors" VALUES (38,'Crona','Eunice','',NULL,0,'1988-09-11','38',0,'','','',0);
INSERT INTO "competitors" VALUES (39,'White','Mohammed','',NULL,0,'1990-12-12','39',0,'','','',0);
INSERT INTO "competitors" VALUES (40,'Windler','Sandy','',NULL,0,'1993-04-16','40',0,'','','',0);
INSERT INTO "competitors" VALUES (41,'Turner','Thurman','',NULL,0,'2009-05-27','41',0,'','','',0);
INSERT INTO "competitors" VALUES (42,'Shanahan','Rolando','',NULL,0,'1984-11-05','42',0,'','','',0);
INSERT INTO "competitors" VALUES (43,'Pouros','Rosina','',NULL,0,'2000-08-22','43',0,'','','',0);
INSERT INTO "competitors" VALUES (44,'Luettgen','Wiley','',NULL,0,'2003-01-28','44',0,'','','',0);
INSERT INTO "competitors" VALUES (45,'Mohr','Carmelo','',NULL,0,'2003-03-19','45',0,'','','',0);
INSERT INTO "competitors" VALUES (46,'McLaughlin','Virgie','',NULL,0,'1989-09-02','46',0,'','','',0);
INSERT INTO "competitors" VALUES (47,'Powlowski','Trace','',NULL,0,'1980-07-20','47',0,'','','',0);
INSERT INTO "competitors" VALUES (48,'Hettinger','Bruce','',NULL,0,'1989-04-10','48',0,'','','',0);
INSERT INTO "competitors" VALUES (49,'O''Keefe','Moshe','',NULL,0,'2004-06-07','49',0,'','','',0);
INSERT INTO "competitors" VALUES (50,'Rippin','Alexis','',NULL,0,'1988-08-10','50',0,'','','',0);
INSERT INTO "competitors" VALUES (51,'Rolfson','Breanna','',NULL,0,'2007-08-24','51',0,'','','',0);
INSERT INTO "competitors" VALUES (52,'Hettinger','Forest','',NULL,0,'1989-03-07','52',0,'','','',0);
INSERT INTO "competitors" VALUES (53,'Wintheiser','Oswald','',NULL,0,'2015-02-08','53',0,'','','',0);
INSERT INTO "competitors" VALUES (54,'Ondricka','Dwight','',NULL,0,'1996-01-12','54',0,'','','',0);
INSERT INTO "competitors" VALUES (55,'Sanford','Ashtyn','',NULL,0,'2010-08-03','55',0,'','','',0);
INSERT INTO "competitors" VALUES (56,'Greenfelder','Maci','',NULL,0,'1988-05-23','56',0,'','','',0);
INSERT INTO "competitors" VALUES (57,'Cummings','Herman','',NULL,0,'1976-09-29','57',0,'','','',0);
INSERT INTO "competitors" VALUES (58,'Marquardt','Yesenia','',NULL,0,'2005-04-13','58',0,'','','',0);
INSERT INTO "competitors" VALUES (59,'Maggio','Mason','',NULL,0,'1995-11-30','59',0,'','','',0);
INSERT INTO "competitors" VALUES (60,'Carroll','Alexandria','',NULL,0,'1984-07-16','60',0,'','','',0);
INSERT INTO "competitors" VALUES (61,'Macejkovic','Stefanie','',NULL,0,'2007-09-01','61',0,'','','',0);
INSERT INTO "competitors" VALUES (62,'Kozey','Alexane','',NULL,0,'1990-12-31','62',0,'','','',0);
INSERT INTO "competitors" VALUES (63,'Hane','Bessie','',NULL,0,'1991-09-04','63',0,'','','',0);
INSERT INTO "competitors" VALUES (64,'Blick','Flavie','',NULL,0,'1974-09-10','64',0,'','','',0);
INSERT INTO "competitors" VALUES (65,'Hettinger','Nathaniel','',NULL,0,'1994-05-19','65',0,'','','',0);
INSERT INTO "competitors" VALUES (66,'Rippin','Waino','',NULL,0,'1986-08-02','66',0,'','','',0);
INSERT INTO "competitors" VALUES (67,'Parker','Billy','',NULL,0,'2005-09-23','67',0,'','','',0);
INSERT INTO "competitors" VALUES (68,'Collins','Trenton','',NULL,0,'1993-08-16','68',0,'','','',0);
INSERT INTO "competitors" VALUES (69,'Vandervort','Amaya','',NULL,0,'2011-09-09','69',0,'','','',0);
INSERT INTO "competitors" VALUES (70,'Pfannerstill','Eino','',NULL,0,'1974-03-28','70',0,'','','',0);
INSERT INTO "competitors" VALUES (71,'Hills','Verna','',NULL,0,'2015-02-23','71',0,'','','',0);
INSERT INTO "competitors" VALUES (72,'Roob','Harvey','',NULL,0,'1988-11-20','72',0,'','','',0);
INSERT INTO "competitors" VALUES (73,'Crona','Lina','',NULL,0,'2015-06-26','73',0,'','','',0);
INSERT INTO "competitors" VALUES (74,'Ernser','Clarabelle','',NULL,0,'1996-01-03','74',0,'','','',0);
INSERT INTO "competitors" VALUES (75,'McGlynn','Lavina','',NULL,0,'1971-12-01','75',0,'','','',0);
INSERT INTO "competitors" VALUES (76,'Luettgen','Bruce','',NULL,0,'1981-01-13','76',0,'','','',0);
INSERT INTO "competitors" VALUES (77,'Keebler','Tristian','',NULL,0,'2000-05-16','77',0,'','','',0);
INSERT INTO "competitors" VALUES (78,'Wilkinson','Chaim','',NULL,0,'1974-01-05','78',0,'','','',0);
INSERT INTO "competitors" VALUES (79,'Hilll','Lyla','',NULL,0,'2005-10-27','79',0,'','','',0);
INSERT INTO "competitors" VALUES (80,'Sauer','Keely','',NULL,0,'1991-05-16','80',0,'','','',0);
INSERT INTO "competitors" VALUES (81,'Beatty','Claud','',NULL,0,'1995-02-01','81',0,'','','',0);
INSERT INTO "competitors" VALUES (82,'Nolan','Hailey','',NULL,0,'1999-03-12','82',0,'','','',0);
INSERT INTO "competitors" VALUES (83,'Bins','Fausto','',NULL,0,'1989-09-14','83',0,'','','',0);
INSERT INTO "competitors" VALUES (84,'Ward','Scottie','',NULL,0,'1970-02-28','84',0,'','','',0);
INSERT INTO "competitors" VALUES (85,'Barton','Michale','',NULL,0,'1987-08-13','85',0,'','','',0);
INSERT INTO "competitors" VALUES (86,'Mosciski','Mellie','',NULL,0,'2008-03-11','86',0,'','','',0);
INSERT INTO "competitors" VALUES (87,'Rath','Clifford','',NULL,0,'2015-01-03','87',0,'','','',0);
INSERT INTO "competitors" VALUES (88,'Emard','Karianne','',NULL,0,'2002-12-27','88',0,'','','',0);
INSERT INTO "competitors" VALUES (89,'Heaney','Lavon','',NULL,0,'2008-08-05','89',0,'','','',0);
INSERT INTO "competitors" VALUES (90,'Beer','Estrella','',NULL,0,'1989-12-17','90',0,'','','',0);
INSERT INTO "competitors" VALUES (91,'Schoen','Brody','',NULL,0,'1990-05-06','91',0,'','','',0);
INSERT INTO "competitors" VALUES (92,'Bauch','Lora','',NULL,0,'2007-08-08','92',0,'','','',0);
INSERT INTO "competitors" VALUES (93,'Boehm','Jonatan','',NULL,0,'1973-07-25','93',0,'','','',0);
INSERT INTO "competitors" VALUES (94,'Carroll','Blanche','',NULL,0,'2006-02-10','94',0,'','','',0);
INSERT INTO "competitors" VALUES (95,'Cummings','Rogers','',NULL,0,'2016-01-24','95',0,'','','',0);
INSERT INTO "competitors" VALUES (96,'Herman','Zora','',NULL,0,'2005-04-10','96',0,'','','',0);
INSERT INTO "competitors" VALUES (97,'Haag','May','',NULL,0,'1993-10-31','97',0,'','','',0);
INSERT INTO "competitors" VALUES (98,'Connelly','Rogers','',NULL,0,'1985-08-03','98',0,'','','',0);
INSERT INTO "competitors" VALUES (99,'Jast','Zora','',NULL,0,'1998-03-26','99',0,'','','',0);
INSERT INTO "competitors" VALUES (100,'Wyman','Kristin','',NULL,0,'2009-02-09','100',0,'','','',0);
INSERT INTO "competitors" VALUES (101,'Walsh','Hal','',NULL,0,'1976-07-07','101',0,'','','',0);
INSERT INTO "competitors" VALUES (102,'Rath','Jaydon','',NULL,0,'2000-07-05','102',0,'','','',0);
INSERT INTO "competitors" VALUES (103,'Mraz','Seth','',NULL,0,'2002-08-15','103',0,'','','',0);
INSERT INTO "competitors" VALUES (104,'Auer','River','',NULL,0,'1977-04-09','104',0,'','','',0);
INSERT INTO "competitors" VALUES (105,'Spinka','Lily','',NULL,0,'1973-06-25','105',0,'','','',0);
INSERT INTO "competitors" VALUES (106,'Friesen','Eudora','',NULL,0,'2009-03-04','106',0,'','','',0);
INSERT INTO "competitors" VALUES (107,'Conroy','Vernie','',NULL,0,'2012-01-20','107',0,'','','',0);
INSERT INTO "competitors" VALUES (108,'Kuphal','Aaliyah','',NULL,0,'2009-10-03','108',0,'','','',0);
INSERT INTO "competitors" VALUES (109,'Buckridge','Verdie','',NULL,0,'1986-04-15','109',0,'','','',0);
INSERT INTO "competitors" VALUES (110,'Schowalter','Rupert','',NULL,0,'1975-04-05','110',0,'','','',0);
INSERT INTO "competitors" VALUES (111,'Crona','Maddison','',NULL,0,'1972-03-30','111',0,'','','',0);
INSERT INTO "competitors" VALUES (112,'Greenholt','Harley','',NULL,0,'1997-01-05','112',0,'','','',0);
INSERT INTO "competitors" VALUES (113,'Cole','Ben','',NULL,0,'1984-06-08','113',0,'','','',0);
INSERT INTO "competitors" VALUES (114,'Bartoletti','Karley','',NULL,0,'1996-02-26','114',0,'','','',0);
INSERT INTO "competitors" VALUES (115,'Emard','Lauriane','',NULL,0,'2012-09-08','115',0,'','','',0);
INSERT INTO "competitors" VALUES (116,'Schaden','Alberto','',NULL,0,'2012-01-14','116',0,'','','',0);
INSERT INTO "competitors" VALUES (117,'Schmidt','Clinton','',NULL,0,'2000-02-06','117',0,'','','',0);
INSERT INTO "competitors" VALUES (118,'Miller','Bell','',NULL,0,'1992-07-15','118',0,'','','',0);
INSERT INTO "competitors" VALUES (119,'Hudson','Chad','',NULL,0,'1990-02-09','119',0,'','','',0);
INSERT INTO "competitors" VALUES (120,'Farrell','Leland','',NULL,0,'1983-12-12','120',0,'','','',0);
INSERT INTO "competitors" VALUES (121,'Beier','Milo','',NULL,0,'2010-10-04','121',0,'','','',0);
INSERT INTO "competitors" VALUES (122,'Heathcote','Bette','',NULL,0,'2016-02-16','122',0,'','','',0);
INSERT INTO "competitors" VALUES (123,'Torp','Jewel','',NULL,0,'2006-03-10','123',0,'','','',0);
INSERT INTO "competitors" VALUES (124,'Hahn','Kaya','',NULL,0,'1985-09-06','124',0,'','','',0);
INSERT INTO "competitors" VALUES (125,'Dooley','Paige','',NULL,0,'2003-09-09','125',0,'','','',0);
INSERT INTO "competitors" VALUES (126,'Koepp','Kitty','',NULL,0,'2000-06-30','126',0,'','','',0);
INSERT INTO "competitors" VALUES (127,'Murphy','Deonte','',NULL,0,'2005-10-19','127',0,'','','',0);
INSERT INTO "competitors" VALUES (128,'Feest','Abdullah','',NULL,0,'1992-01-11','128',0,'','','',0);
INSERT INTO "competitors" VALUES (129,'O''Keefe','Brock','',NULL,0,'1997-08-17','129',0,'','','',0);
INSERT INTO "competitors" VALUES (130,'Rodriguez','Greta','',NULL,0,'1983-01-29','130',0,'','','',0);
INSERT INTO "competitors" VALUES (131,'Predovic','Aiyana','',NULL,0,'1999-03-22','131',0,'','','',0);
INSERT INTO "competitors" VALUES (132,'Ullrich','Clotilde','',NULL,0,'2002-04-07','132',0,'','','',0);
INSERT INTO "competitors" VALUES (133,'Konopelski','Janis','',NULL,0,'1988-03-17','133',0,'','','',0);
INSERT INTO "competitors" VALUES (134,'Windler','Frank','',NULL,0,'2008-04-13','134',0,'','','',0);
INSERT INTO "competitors" VALUES (135,'Padberg','Susan','',NULL,0,'1993-09-16','135',0,'','','',0);
INSERT INTO "competitors" VALUES (136,'Bernhard','Francisca','',NULL,0,'2001-02-12','136',0,'','','',0);
INSERT INTO "competitors" VALUES (137,'Schroeder','Teresa','',NULL,0,'1982-10-03','137',0,'','','',0);
INSERT INTO "competitors" VALUES (138,'Muller','Billy','',NULL,0,'1970-02-25','138',0,'','','',0);
INSERT INTO "competitors" VALUES (139,'DuBuque','Noel','',NULL,0,'2006-04-27','139',0,'','','',0);
INSERT INTO "competitors" VALUES (140,'Carroll','Willy','',NULL,0,'2011-08-07','140',0,'','','',0);
INSERT INTO "competitors" VALUES (141,'Altenwerth','April','',NULL,0,'1970-02-17','141',0,'','','',0);
INSERT INTO "competitors" VALUES (142,'Senger','Avery','',NULL,0,'2000-09-27','142',0,'','','',0);
INSERT INTO "competitors" VALUES (143,'Lynch','Carey','',NULL,0,'1996-08-06','143',0,'','','',0);
INSERT INTO "competitors" VALUES (144,'Metz','Lenny','',NULL,0,'2005-06-18','144',0,'','','',0);
INSERT INTO "competitors" VALUES (145,'Rowe','Constance','',NULL,0,'1972-04-30','145',0,'','','',0);
INSERT INTO "competitors" VALUES (146,'Lesch','Rogers','',NULL,0,'1977-07-10','146',0,'','','',0);
INSERT INTO "competitors" VALUES (147,'Mueller','Guiseppe','',NULL,0,'1986-05-05','147',0,'','','',0);
INSERT INTO "competitors" VALUES (148,'Heaney','Gabriel','',NULL,0,'1982-11-19','148',0,'','','',0);
INSERT INTO "competitors" VALUES (149,'Mills','Henderson','',NULL,0,'2012-10-23','149',0,'','','',0);
INSERT INTO "competitors" VALUES (150,'Effertz','Loren','',NULL,0,'1998-11-09','150',0,'','','',0);
INSERT INTO "competitors" VALUES (151,'Prohaska','Walton','',NULL,0,'1999-03-26','151',0,'','','',0);
INSERT INTO "competitors" VALUES (152,'Nitzsche','Lelah','',NULL,0,'1992-06-21','152',0,'','','',0);
INSERT INTO "competitors" VALUES (153,'DuBuque','Erica','',NULL,0,'2009-12-25','153',0,'','','',0);
INSERT INTO "competitors" VALUES (154,'Thiel','Eugenia','',NULL,0,'1999-03-17','154',0,'','','',0);
INSERT INTO "competitors" VALUES (155,'Abbott','Frederick','',NULL,0,'1983-12-19','155',0,'','','',0);
INSERT INTO "competitors" VALUES (156,'Kris','Pinkie','',NULL,0,'1998-10-08','156',0,'','','',0);
INSERT INTO "competitors" VALUES (157,'Keeling','Stewart','',NULL,0,'2004-07-19','157',0,'','','',0);
INSERT INTO "competitors" VALUES (158,'Ledner','Helene','',NULL,0,'1979-10-04','158',0,'','','',0);
INSERT INTO "competitors" VALUES (159,'O''Reilly','Lincoln','',NULL,0,'2014-10-09','159',0,'','','',0);
INSERT INTO "competitors" VALUES (160,'Effertz','Alfonso','',NULL,0,'2012-11-15','160',0,'','','',0);
INSERT INTO "competitors" VALUES (161,'Heathcote','Bette','',NULL,0,'2001-08-01','161',0,'','','',0);
INSERT INTO "competitors" VALUES (162,'Raynor','Kade','',NULL,0,'2009-12-31','162',0,'','','',0);
INSERT INTO "competitors" VALUES (163,'Sawayn','Brent','',NULL,0,'1985-09-23','163',0,'','','',0);
INSERT INTO "competitors" VALUES (164,'Turner','Marlene','',NULL,0,'1970-01-22','164',0,'','','',0);
INSERT INTO "competitors" VALUES (165,'Lemke','Eve','',NULL,0,'1992-04-08','165',0,'','','',0);
INSERT INTO "competitors" VALUES (166,'Hickle','Bryon','',NULL,0,'1995-03-08','166',0,'','','',0);
INSERT INTO "competitors" VALUES (167,'Wolff','Karlie','',NULL,0,'2013-12-02','167',0,'','','',0);
INSERT INTO "competitors" VALUES (168,'Mraz','Eliseo','',NULL,0,'1995-12-09','168',0,'','','',0);
INSERT INTO "competitors" VALUES (169,'Reinger','Bartholome','',NULL,0,'1990-12-26','169',0,'','','',0);
INSERT INTO "competitors" VALUES (170,'Lebsack','Torey','',NULL,0,'2009-10-09','170',0,'','','',0);
INSERT INTO "competitors" VALUES (171,'Hartmann','Gaylord','',NULL,0,'1990-06-19','171',0,'','','',0);
INSERT INTO "competitors" VALUES (172,'Gislason','Luella','',NULL,0,'2017-01-17','172',0,'','','',0);
INSERT INTO "competitors" VALUES (173,'Olson','Fernando','',NULL,0,'2004-11-14','173',0,'','','',0);
INSERT INTO "competitors" VALUES (174,'Cormier','Kenneth','',NULL,0,'1977-01-09','174',0,'','','',0);
INSERT INTO "competitors" VALUES (175,'Wisoky','Cristina','',NULL,0,'1987-08-28','175',0,'','','',0);
INSERT INTO "competitors" VALUES (176,'Legros','Vance','',NULL,0,'1979-11-12','176',0,'','','',0);
INSERT INTO "competitors" VALUES (177,'Barton','Robbie','',NULL,0,'1994-05-17','177',0,'','','',0);
INSERT INTO "competitors" VALUES (178,'Roberts','Michale','',NULL,0,'2003-03-03','178',0,'','','',0);
INSERT INTO "competitors" VALUES (179,'Keebler','Russel','',NULL,0,'1979-06-01','179',0,'','','',0);
INSERT INTO "competitors" VALUES (180,'Fay','Keara','',NULL,0,'1974-05-10','180',0,'','','',0);
INSERT INTO "competitors" VALUES (181,'Maggio','Noah','',NULL,0,'2004-03-07','181',0,'','','',0);
INSERT INTO "competitors" VALUES (182,'Johns','Shanie','',NULL,0,'1975-04-23','182',0,'','','',0);
INSERT INTO "competitors" VALUES (183,'Ankunding','Felicity','',NULL,0,'2010-12-01','183',0,'','','',0);
INSERT INTO "competitors" VALUES (184,'Koepp','Cydney','',NULL,0,'1985-08-21','184',0,'','','',0);
INSERT INTO "competitors" VALUES (185,'Heidenreich','Allan','',NULL,0,'2011-03-25','185',0,'','','',0);
INSERT INTO "competitors" VALUES (186,'Keebler','Jaylon','',NULL,0,'2004-11-02','186',0,'','','',0);
INSERT INTO "competitors" VALUES (187,'Herzog','Bennie','',NULL,0,'2014-02-23','187',0,'','','',0);
INSERT INTO "competitors" VALUES (188,'O''Connell','Madaline','',NULL,0,'1972-07-25','188',0,'','','',0);
INSERT INTO "competitors" VALUES (189,'Kemmer','Sydnie','',NULL,0,'1981-06-13','189',0,'','','',0);
INSERT INTO "competitors" VALUES (190,'Gerlach','Lawrence','',NULL,0,'1985-05-16','190',0,'','','',0);
INSERT INTO "competitors" VALUES (191,'McClure','Adan','',NULL,0,'1971-11-24','191',0,'','','',0);
INSERT INTO "competitors" VALUES (192,'Hegmann','Raven','',NULL,0,'2003-01-31','192',0,'','','',0);
INSERT INTO "competitors" VALUES (193,'Quigley','Vicente','',NULL,0,'1994-01-09','193',0,'','','',0);
INSERT INTO "competitors" VALUES (194,'Bogisich','Esteban','',NULL,0,'1981-07-27','194',0,'','','',0);
INSERT INTO "competitors" VALUES (195,'Windler','Gordon','',NULL,0,'1972-02-15','195',0,'','','',0);
INSERT INTO "competitors" VALUES (196,'Reinger','Matteo','',NULL,0,'2002-03-17','196',0,'','','',0);
INSERT INTO "competitors" VALUES (197,'Walker','Reed','',NULL,0,'2006-09-16','197',0,'','','',0);
INSERT INTO "competitors" VALUES (198,'Tromp','Roberta','',NULL,0,'2014-06-07','198',0,'','','',0);
INSERT INTO "competitors" VALUES (199,'Hartmann','Gideon','',NULL,0,'2015-05-09','199',0,'','','',0);
INSERT INTO "competitors" VALUES (200,'Upton','Michale','',NULL,0,'2007-09-05','200',0,'','','',0);
CREATE TABLE "federations" (
  "name" varchar(200)  PRIMARY KEY NOT NULL,
  "id" varchar(10) NOT NULL
);
CREATE TABLE "gradings" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "name" varchar(30) NOT NULL
);
CREATE TABLE "groups" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "group_pos" int(11) NOT NULL,
  "parent_id" int(11) DEFAULT NULL,
  "next_pos" int(11) NOT NULL,
  "winner_id" int(11) DEFAULT NULL,
  "name" varchar(255) NOT NULL,
  "round_id" int(11) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "group_size" int(11) NOT NULL,
  "bye_count" int(11) NOT NULL,
  "group_match_count" int(11) NOT NULL,
  "need_decision" tinyint(1) NOT NULL,
  "decision_match" int(11) NOT NULL,
  "ranking" text NOT NULL,
  "created" datetime NOT NULL,
  "updated" datetime NOT NULL,
  "competitor_count" int(11) NOT NULL,
  "count_finished_matches" int(11) NOT NULL
);
CREATE TABLE "matches" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "order_number" int(11) NOT NULL,
  "area_id" int(11) NOT NULL,
  "title" varchar(255) DEFAULT '',
  "white_id" int(11) NOT NULL,
  "red_id" int(11) NOT NULL,
  "score_white" varchar(255) NOT NULL,
  "score_red" varchar(255) NOT NULL,
  "points_white" int(11) NOT NULL,
  "points_red" int(11) NOT NULL,
  "time" int(11) NOT NULL,
  "overtime" int(11) NOT NULL,
  "starttime" int(11) NOT NULL,
  "winner_id" int(11) NOT NULL,
  "status" int(11) NOT NULL,
  "lft" int(11) DEFAULT NULL,
  "rght" int(11) DEFAULT NULL,
  "parent_id" int(11) DEFAULT NULL,
  "pos" int(11) NOT NULL,
  "next_pos" int(11) DEFAULT NULL,
  "depth" int(11) DEFAULT NULL,
  "tournament_id" int(11) NOT NULL,
  "round_id" int(11) NOT NULL,
  "group_id" int(11) DEFAULT NULL,
  "history" text NOT NULL,
  "created" datetime NOT NULL,
  "updated" datetime NOT NULL,
  "team_matches_id" int(11) NOT NULL,
  "max_time" int(11) NOT NULL DEFAULT '180',
  "max_points" int(11) NOT NULL DEFAULT '2',
  "type" varchar(20) NOT NULL
);
INSERT INTO "matches" VALUES (1,0,0,'Finals-1',-1,-1,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,1,14,NULL,0,1,0,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
INSERT INTO "matches" VALUES (2,0,0,'Semi-1',-1,-1,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,8,13,1,0,1,1,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
INSERT INTO "matches" VALUES (3,0,0,'Quarter-1',4,3,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,11,12,2,0,1,2,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
INSERT INTO "matches" VALUES (4,0,0,'Quarter-2',1,7,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,9,10,2,0,2,2,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
INSERT INTO "matches" VALUES (5,0,0,'Semi-2',-1,-1,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,2,7,1,0,2,1,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
INSERT INTO "matches" VALUES (6,0,0,'Quarter-3',6,5,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,5,6,5,0,1,2,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
INSERT INTO "matches" VALUES (7,0,0,'Quarter-4',8,2,'{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}','{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',0,0,0,0,0,0,0,3,4,5,0,2,2,0,0,NULL,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,180,2,'');
CREATE TABLE "participants" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "competitor_id" int(11) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "group_id" int(11) NOT NULL,
  "alias" varchar(30) NOT NULL,
  "pos" int(11) NOT NULL,
  "team_id" int(11) NOT NULL
);
INSERT INTO "participants" VALUES (1,1,1,1,'',0,0);
INSERT INTO "participants" VALUES (2,2,1,1,'',0,0);
INSERT INTO "participants" VALUES (3,3,1,1,'',0,0);
INSERT INTO "participants" VALUES (4,4,1,1,'',0,0);
INSERT INTO "participants" VALUES (5,5,1,1,'',0,0);
INSERT INTO "participants" VALUES (6,6,1,1,'',0,0);
INSERT INTO "participants" VALUES (7,7,1,1,'',0,0);
INSERT INTO "participants" VALUES (8,8,1,1,'',0,0);
CREATE TABLE "referee_matches" (
  "referee_id" int(11)  PRIMARY KEY NOT NULL,
  "match_id" int(11) NOT NULL,
  "position" int(11) NOT NULL
);
CREATE TABLE "referees" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "first_name" varchar(100) NOT NULL,
  "last_name" varchar(100) NOT NULL,
  "alias" varchar(100) NOT NULL,
  "grading_id" int(11) NOT NULL
);
CREATE TABLE "rounds" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "title" varchar(100) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "matches_count" int(11) NOT NULL,
  "seeds_count" int(11) NOT NULL,
  "byes_count" int(11) NOT NULL,
  "matches_done" int(11) NOT NULL,
  "depth" int(11) NOT NULL,
  "extra_brackets" int(11) NOT NULL,
  "status" int(11) NOT NULL,
  "type" varchar(30) NOT NULL,
  "next" int(11) NOT NULL,
  "current_match" int(11) NOT NULL
);
CREATE TABLE "tasks" (
  "id" int(11) PRIMARY KEY NOT NULL,
  "model" varchar(50) NOT NULL,
  "action" varchar(255) NOT NULL,
  "params" varchar(50) DEFAULT NULL,
  "message" text NOT NULL,
  "progress" float NOT NULL
);
CREATE TABLE "team_groups" (
  "team_id" int(11)  PRIMARY KEY NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "group_id" int(11) NOT NULL,
  "pos" int(11) NOT NULL,
  "id" int(11) NOT NULL ,
  "alias" varchar(50) NOT NULL
);
CREATE TABLE "team_matches" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "title" varchar(255) NOT NULL,
  "team_id1" int(11) DEFAULT NULL,
  "team_id2" int(11) DEFAULT NULL,
  "points_white" int(11) NOT NULL,
  "points_red" int(11) NOT NULL,
  "winner_id" int(11) NOT NULL,
  "need_decision" tinyint(1) NOT NULL,
  "created" datetime NOT NULL,
  "match_time" int(11) NOT NULL,
  "status" int(11) NOT NULL,
  "matches_done" int(11) NOT NULL,
  "current_match" int(11) NOT NULL,
  "lft" int(11) NOT NULL,
  "rght" int(11) NOT NULL,
  "parent_id" int(11) DEFAULT NULL,
  "next_pos" int(11) NOT NULL,
  "depth" int(11) DEFAULT NULL,
  "type" varchar(50) NOT NULL,
  "round_id" int(11) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "group_id" int(11) NOT NULL
);
CREATE TABLE "team_participants" (
  "participant_id" int(11)  PRIMARY KEY NOT NULL,
  "team_id" int(11) DEFAULT NULL,
  "tournament_id" int(11) NOT NULL,
  "position" int(11) DEFAULT NULL,
  "id" int(11) NOT NULL
);
CREATE TABLE "teams" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "name" varchar(100) NOT NULL,
  "symbol" varchar(10) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "team_participant_count" int(11) NOT NULL,
  "level" float NOT NULL,
  "size" int(11) NOT NULL,
  "group_id" int(11) NOT NULL
);
CREATE TABLE "users" (
  "id" int(11)  PRIMARY KEY NOT NULL,
  "username" varchar(50) NOT NULL,
  "password" varchar(50) NOT NULL,
  "role" int(11) NOT NULL
);
INSERT INTO "users" VALUES (1,'mmuschol','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',0);