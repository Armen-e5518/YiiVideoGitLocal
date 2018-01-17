CREATE TABLE `vid_player_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `player_id` varchar(37) CHARACTER SET latin1 DEFAULT NULL,
  `substitute_player` varchar(37) DEFAULT NULL,
  `pos_from` int(2) DEFAULT NULL,
  `pos_to` int(2) DEFAULT NULL,
  `time` int(5) DEFAULT NULL,
  `half_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_match_id` (`match_id`) USING BTREE,
  KEY `ix_player_id` (`player_id`) USING BTREE,
  CONSTRAINT `fk_match_id_1` FOREIGN KEY (`match_id`) REFERENCES `vid_matchs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_player_id_2` FOREIGN KEY (`player_id`) REFERENCES `vid_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

ALTER TABLE `vid_player_positions` ADD `main` INT(1)  NULL COMMENT '1-main ' AFTER `player_position`;
