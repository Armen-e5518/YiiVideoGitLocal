/*
Navicat MySQL Data Transfer

Source Server         : Loacal
Source Server Version : 50552
Source Host           : localhost:3306
Source Database       : VideoTest

Target Server Type    : MYSQL
Target Server Version : 50552
File Encoding         : 65001

Date: 2017-03-13 13:03:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for vid_actions
-- ----------------------------
DROP TABLE IF EXISTS `vid_actions`;
CREATE TABLE `vid_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(50) DEFAULT NULL,
  `action_type` varchar(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '"1"- goal keeper',
  `time_before` int(5) DEFAULT NULL,
  `time_after` int(5) DEFAULT NULL,
  `event_type` tinyint(1) DEFAULT '0' COMMENT '1-evnet 0-action',
  `main_type` tinyint(1) DEFAULT '0' COMMENT '1-show in main page',
  `timer_role` varchar(1) DEFAULT 'E',
  `sorting` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for vid_events
-- ----------------------------
DROP TABLE IF EXISTS `vid_events`;
CREATE TABLE `vid_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) DEFAULT NULL,
  `action_id` int(11) DEFAULT NULL,
  `player1_id` varchar(37) DEFAULT NULL,
  `player2_id` varchar(37) DEFAULT NULL,
  `time_game` int(5) DEFAULT NULL,
  `time_video` int(5) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL COMMENT '1-Yes 0-No',
  `card_yellow` tinyint(1) DEFAULT NULL,
  `card_red` tinyint(1) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '1',
  `full_geolocation` tinyint(2) DEFAULT NULL,
  `half_geolocation` tinyint(2) DEFAULT NULL,
  `half_time` tinyint(1) DEFAULT NULL,
  `favorite` tinyint(1) DEFAULT NULL,
  `sec_action_id` int(11) DEFAULT NULL,
  `sec_player1_id` varchar(37) DEFAULT NULL,
  `sec_player2_id` varchar(37) DEFAULT NULL,
  `sec_success` tinyint(1) DEFAULT NULL COMMENT '1-Yes 0-No',
  `sec_full_geolocation` tinyint(2) DEFAULT NULL,
  `time_from` int(5) DEFAULT NULL,
  `time_to` int(5) DEFAULT NULL,
  `video_src` varchar(100) DEFAULT NULL,
  `status_video` tinyint(1) DEFAULT NULL COMMENT '0-local,1-server',
  PRIMARY KEY (`id`),
  KEY `fk_player_id1` (`player1_id`),
  KEY `fk_player_id2` (`player2_id`),
  KEY `fk_sec_player_id1` (`sec_player1_id`),
  KEY `fk_sec_player_id2` (`sec_player2_id`),
  KEY `Fk_action_id` (`action_id`),
  CONSTRAINT `Fk_action_id` FOREIGN KEY (`action_id`) REFERENCES `vid_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2451 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for vid_match_players_number
-- ----------------------------
DROP TABLE IF EXISTS `vid_match_players_number`;
CREATE TABLE `vid_match_players_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) DEFAULT NULL,
  `player_id` varchar(37) DEFAULT NULL,
  `number` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for vid_matchs
-- ----------------------------
DROP TABLE IF EXISTS `vid_matchs`;
CREATE TABLE `vid_matchs` (
  `id` int(11) NOT NULL,
  `team1_id` varchar(37) DEFAULT NULL,
  `team2_id` varchar(37) DEFAULT NULL,
  `data` varchar(25) DEFAULT NULL,
  `video_src` varchar(100) DEFAULT NULL,
  `status_end` varchar(1) DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `tema_id` (`team1_id`),
  KEY `team_id_2` (`team2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for vid_player_positions
-- ----------------------------
DROP TABLE IF EXISTS `vid_player_positions`;
CREATE TABLE `vid_player_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) DEFAULT NULL,
  `player_id` varchar(37) DEFAULT NULL,
  `player_position` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_playrt_id` (`player_id`),
  KEY `fk_match_id` (`match_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16472 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for vid_players
-- ----------------------------
DROP TABLE IF EXISTS `vid_players`;
CREATE TABLE `vid_players` (
  `id` varchar(37) NOT NULL,
  `player_name` varchar(100) DEFAULT NULL,
  `player_number` int(3) DEFAULT NULL,
  `team_id` varchar(37) NOT NULL,
  `date` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id_teams` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for vid_teams
-- ----------------------------
DROP TABLE IF EXISTS `vid_teams`;
CREATE TABLE `vid_teams` (
  `id` varchar(255) NOT NULL,
  `team_name` varchar(37) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
SET FOREIGN_KEY_CHECKS=1;
