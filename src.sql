CREATE TABLE migration
(
    version VARCHAR(180) PRIMARY KEY NOT NULL,
    apply_time INT(11)
);
CREATE TABLE schema_version
(
    installed_rank INT(11) PRIMARY KEY NOT NULL,
    version VARCHAR(50),
    description VARCHAR(200) NOT NULL,
    type VARCHAR(20) NOT NULL,
    script VARCHAR(1000) NOT NULL,
    checksum INT(11),
    installed_by VARCHAR(100) NOT NULL,
    installed_on TIMESTAMP DEFAULT 'CURRENT_TIMESTAMP' NOT NULL,
    execution_time INT(11) NOT NULL,
    success TINYINT(1) NOT NULL
);
CREATE INDEX schema_version_s_idx ON schema_version (success);
CREATE TABLE user
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    auth_key VARCHAR(32) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    password_reset_token VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    status INT(6) DEFAULT '10' NOT NULL,
    created_at INT(11) NOT NULL,
    updated_at INT(11) NOT NULL
);
CREATE UNIQUE INDEX email ON user (email);
CREATE UNIQUE INDEX password_reset_token ON user (password_reset_token);
CREATE UNIQUE INDEX username ON user (username);
CREATE TABLE vid_actions
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    action_name VARCHAR(255),
    action_type VARCHAR(10),
    type TINYINT(1) DEFAULT '0',
    time_before INT(5),
    time_after INT(5),
    event_type INT(1) DEFAULT '0',
    main_type INT(1) DEFAULT '0',
    timer_role CHAR(1) DEFAULT 'E',
    sorting INT(11),
    `key` VARCHAR(255) DEFAULT ' '
);
CREATE TABLE vid_events
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    match_id INT(11),
    action_id INT(11),
    player1_id VARCHAR(37),
    player2_id VARCHAR(37),
    time_game INT(5),
    time_video INT(5),
    success INT(1),
    card_yellow INT(1),
    card_red INT(1),
    visible INT(1),
    full_geolocation INT(2),
    half_geolocation INT(2),
    half_time INT(2),
    favorite INT(1),
    sec_action_id INT(11),
    sec_player1_id VARCHAR(37),
    sec_player2_id VARCHAR(37),
    sec_success INT(1),
    sec_full_geolocation INT(2),
    time_from INT(5),
    time_to INT(5),
    video_src VARCHAR(255),
    status_video INT(1),
    CONSTRAINT FK_vid_events_player1 FOREIGN KEY (player1_id) REFERENCES vid_players (id),
    CONSTRAINT FK_vid_events_player2 FOREIGN KEY (player2_id) REFERENCES vid_players (id),
    CONSTRAINT FK_vid_events_sec_player1 FOREIGN KEY (sec_player1_id) REFERENCES vid_players (id),
    CONSTRAINT FK_vid_events_sec_player2 FOREIGN KEY (sec_player2_id) REFERENCES vid_players (id),
    CONSTRAINT FK_vid_events_vid_actions FOREIGN KEY (action_id) REFERENCES vid_actions (id),
    CONSTRAINT FK_vid_events_vid_sec_actions FOREIGN KEY (sec_action_id) REFERENCES vid_actions (id)
);
CREATE INDEX fk_player_id1 ON vid_events (player1_id);
CREATE INDEX fk_player_id2 ON vid_events (player2_id);
CREATE INDEX fk_sec_player_id1 ON vid_events (sec_player1_id);
CREATE INDEX fk_sec_player_id2 ON vid_events (sec_player2_id);
CREATE INDEX FK_vid_events_vid_actions ON vid_events (action_id);
CREATE INDEX FK_vid_events_vid_sec_actions ON vid_events (sec_action_id);
CREATE TABLE vid_match_players_number
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    match_id INT(11),
    player_id VARCHAR(37),
    number INT(3),
    CONSTRAINT FK_vid_match_players_number_match_id FOREIGN KEY (match_id) REFERENCES vid_matchs (id),
    CONSTRAINT FK_vid_match_players_number_player FOREIGN KEY (player_id) REFERENCES vid_players (id)
);
CREATE INDEX FK_vid_match_players_number_match_id ON vid_match_players_number (match_id);
CREATE INDEX FK_vid_match_players_number_player ON vid_match_players_number (player_id);


CREATE TABLE vid_matchs
(
    id INT(11) PRIMARY KEY NOT NULL,
    team1_id VARCHAR(37),
    team2_id VARCHAR(37),
    data VARCHAR(25),
    video_src VARCHAR(255),
    status_end INT(1) DEFAULT '0',
    CONSTRAINT FK_vid_matchs_team1 FOREIGN KEY (team1_id) REFERENCES vid_teams (id),
    CONSTRAINT FK_vid_matchs_team2 FOREIGN KEY (team2_id) REFERENCES vid_teams (id)
);
CREATE INDEX FK_vid_matchs_team1 ON vid_matchs (team1_id);
CREATE INDEX FK_vid_matchs_team2 ON vid_matchs (team2_id);



CREATE TABLE vid_player_positions
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    match_id INT(11),
    player_id VARCHAR(37),
    player_position INT(3),
    CONSTRAINT FK_vid_player_positions_match_id FOREIGN KEY (match_id) REFERENCES vid_matchs (id),
    CONSTRAINT FK_vid_player_positions_player FOREIGN KEY (player_id) REFERENCES vid_players (id)
);
CREATE INDEX fk_match_id ON vid_player_positions (match_id);
CREATE INDEX fk_playrt_id ON vid_player_positions (player_id);
CREATE TABLE vid_players
(
    id VARCHAR(37) PRIMARY KEY NOT NULL,
    player_name VARCHAR(100),
    player_number INT(3),
    team_id VARCHAR(255) NOT NULL,
    date VARCHAR(255) NOT NULL,
    CONSTRAINT FK_vid_players_team FOREIGN KEY (team_id) REFERENCES vid_teams (id)
);
CREATE INDEX team_id_teams ON vid_players (team_id);
CREATE TABLE vid_teams
(
    id VARCHAR(37) PRIMARY KEY NOT NULL,
    team_name VARCHAR(255)
);