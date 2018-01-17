
ALTER TABLE `vid_events` ADD `goal_position` INT(2)  NULL AFTER `status_video`;
UPDATE `vid_actions` SET `action_type` = 'PENALTY' WHERE `vid_actions`.`id` = 8;
ALTER TABLE `vid_matchs` ADD `team1_color` VARCHAR(10) NULL AFTER `status_end`, ADD `team2_color` VARCHAR(10) NULL AFTER `team1_color`;