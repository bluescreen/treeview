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

CREATE TABLE "clubs" (
  "id"  integer not null primary key autoincrement,
  "name" varchar(100) NOT NULL,
  "abbrev" varchar(10) NOT NULL,
  "location" varchar(100) NOT NULL,
  "federation_id" varchar(100) NOT NULL,
  "contact" varchar(100) NOT NULL,
  "email" varchar(100) NOT NULL,
  "phone" varchar(100) NOT NULL,
  "fax" varchar(100) NOT NULL,
  "address" varchar(100) NOT NULL,
  "competitors_count" int(11) NOT NULL
);
CREATE TABLE "competitions" (
  "id"  integer not null primary key autoincrement,
  "name" varchar(100) NOT NULL,
  "date" date NOT NULL,
  "location" varchar(100) NOT NULL,
  "status" int(11),
  "round" int(11),
  "logo" varchar(50),
  "active" tinyint(4),
  "type" varchar(50),
  "areas" int(11),
  "mode" varchar(50),
  "grading_mode" varchar(30),
  "participant_count" int(11),
  "matches_count" int(11),
  "group_size" int(11),
  "team_size" int(11),
  "options" text
);
CREATE TABLE "competitors" (
  "id"  integer not null primary key autoincrement,
  "name" varchar(100) NOT NULL,
  "first_name" varchar(255) DEFAULT NULL,
  "alias" varchar(50) NOT NULL,
  "club_id" int(11) DEFAULT NULL,
  "grading_id" int(30) NOT NULL,
  "birth_date" date NOT NULL,
  "pass_nr" varchar(10) NOT NULL,
  "body_height" floatNULL,
  "image" varchar(100)NULL,
  "list" varchar(255) NULL,
  "gender" varchar(6) NULL,
  "blocked" tinyint(1) NULL
);
CREATE TABLE "federations" (
  "id" integer not null primary key autoincrement,
  "name" text
);
CREATE TABLE "gradings" (
  "id"  integer not null primary key autoincrement,
  "name" varchar(30) NOT NULL
);
CREATE TABLE "groups" (
  "id"  integer not null primary key autoincrement,
  "group_pos" int(11) NOT NULL,
  "parent_id" int(11) DEFAULT NULL,
  "next_pos" int(11) NOT NULL,
  "winner_id" int(11) DEFAULT NULL,
  "name" varchar(255) NOT NULL,
  "round_id" int(11),
  "tournament_id" int(11),
  "group_size" int(11),
  "bye_count" int(11),
  "group_match_count" int(11),
  "need_decision" tinyint(1),
  "decision_match" int(11),
  "ranking" text,
  "created" datetime,
  "updated" datetime,
  "competitor_count" int(11),
  "count_finished_matches" int(11)
);
CREATE TABLE "matches" (
  "id"  integer not null primary key autoincrement,
  "order_number" int(11) DEFAULT NULL,
  "area_id" int(11),
  "title" varchar(255) DEFAULT '',
  "white_id" int(11) NOT NULL,
  "red_id" int(11) NOT NULL,
  "score_white" varchar(255),
  "score_red" varchar(255),
  "points_white" int(11),
  "points_red" int(11),
  "time" int(11),
  "overtime" int(11),
  "starttime" int(11),
  "winner_id" int(11),
  "status" int(11),
  "lft" int(11) DEFAULT NULL,
  "rght" int(11) DEFAULT NULL,
  "parent_id" int(11) DEFAULT NULL,
  "pos" int(11),
  "next_pos" int(11),
  "depth" int(11),
  "tournament_id" int(11),
  "round_id" int(11),
  "group_id" int(11),
  "history" text,
  "created" datetime,
  "updated" datetime,
  "team_matches_id" int(11),
  "max_time" int(11) NOT NULL DEFAULT '180',
  "max_points" int(11) NOT NULL DEFAULT '2',
  "type" varchar(20)
);
CREATE TABLE "participants" (
  "id"  integer not null primary key autoincrement,
  "competitor_id" int(11) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "group_id" int(11) DEFAULT NULL,
  "alias" varchar(30) DEFAULT NULL,
  "pos" int(11) DEFAULT NULL,
  "team_id" int(11) DEFAULT NULL
);
CREATE TABLE "referee_matches" (
  "referee_id" int(11)  NOT NULL,
  "match_id" int(11) NOT NULL,
  "position" int(11) NOT NULL
);
CREATE TABLE "referees" (
  "id"  integer not null primary key autoincrement,
  "first_name" varchar(100) NOT NULL,
  "last_name" varchar(100) NOT NULL,
  "alias" varchar(100) NOT NULL,
  "grading_id" int(11) NOT NULL
);
CREATE TABLE "rounds" (
  "id"  integer not null primary key autoincrement,
  "title" varchar(100) NOT NULL,
  "tournament_id" int(11) DEFAULT NULL,
  "matches_count" int(11) DEFAULT NULL,
  "seeds_count" int(11) DEFAULT NULL,
  "byes_count" int(11) DEFAULT NULL,
  "matches_done" int(11) DEFAULT NULL,
  "depth" int(11) DEFAULT NULL,
  "extra_brackets" int(11) DEFAULT NULL,
  "status" int(11) DEFAULT NULL,
  "type" varchar(30) DEFAULT NULL,
  "next" int(11) DEFAULT NULL,
  "current_match" int(11) DEFAULT NULL
);
CREATE TABLE "team_groups" (
  "team_id"  integer not null primary key autoincrement,
  "tournament_id" int(11) NOT NULL,
  "group_id" int(11) NOT NULL,
  "pos" int(11) NOT NULL,
  "id" int(11) NOT NULL ,
  "alias" varchar(50) NOT NULL
);
CREATE TABLE "team_matches" (
  "id"  integer not null primary key autoincrement,
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
  "id"  integer not null primary key autoincrement,
  "name" varchar(100) NOT NULL,
  "symbol" varchar(10) NOT NULL,
  "tournament_id" int(11) NOT NULL,
  "team_participant_count" int(11) NOT NULL,
  "level" float NOT NULL,
  "size" int(11) NOT NULL,
  "group_id" int(11) NOT NULL
);
CREATE TABLE "users" (
  "id"  integer not null primary key autoincrement,
  "username" varchar(50) NOT NULL,
  "password" varchar(50) NOT NULL,
  "role" int(11) NOT NULL
);
INSERT INTO "users" VALUES (1,'mmuschol','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3',0);