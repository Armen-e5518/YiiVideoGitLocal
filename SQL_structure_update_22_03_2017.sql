CREATE TABLE `vid_substitutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `player_id` varchar(37) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_match_id` (`match_id`),
  KEY `Fk_player_id` (`player_id`),
  CONSTRAINT `FK_match_id` FOREIGN KEY (`match_id`) REFERENCES `vid_matchs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Fk_player_id` FOREIGN KEY (`player_id`) REFERENCES `vid_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


