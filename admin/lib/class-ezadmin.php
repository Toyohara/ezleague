<?php
	
class ezAdmin extends DB_Class {
		
	/*
	 * Set up theme components
	 */
	public function setup_ezadmin() {
	
		$this->require_files();
		$this->frontend 	= new ezAdmin_Frontend();
		$this->news 		= new ezAdmin_News();
		$this->team 		= new ezAdmin_Team();
		$this->league 		= new ezAdmin_League();
		$this->matches		= new ezAdmin_Match();
		$this->settings 	= new ezAdmin_Settings();
		$this->forum		= new ezAdmin_Forum();
		$this->schedule 	= new ezAdmin_Schedule();
		$this->tournament 	= new ezAdmin_Tournament();
	
	}
	
	/*
	 * Require files we need to load
	 */
	public function require_files() {
	
		require_once dirname( __FILE__ ) . '/class-frontend.php';
		require_once dirname( __FILE__ ) . '/class-schedule.php';
		require_once dirname( __FILE__ ) . '/objects/class-news.php';
		require_once dirname( __FILE__ ) . '/objects/class-user.php';
		require_once dirname( __FILE__ ) . '/objects/class-team.php';
		require_once dirname( __FILE__ ) . '/objects/class-league.php';
		require_once dirname( __FILE__ ) . '/objects/class-match.php';
		require_once dirname( __FILE__ ) . '/objects/class-settings.php';
		require_once dirname( __FILE__ ) . '/objects/class-forum.php';
		require_once dirname( __FILE__ ) . '/objects/class-tournament.php';
		
	}
	
	/*
	 * Login User
	 */
	public function login($username, $password) {
		$saltData = $this->fetch("SELECT salt, hash, guild, role, status FROM `" . $this->prefix . "users` 
									WHERE (username = '$username') AND (role = 'admin')
								");
			$salt  	  = $saltData['0']['salt'];
			$hash  	  = $saltData['0']['hash'];
			$guild_id = $saltData['0']['guild'];
			$role  	  = $saltData['0']['role'];
			$status   = $saltData['0']['status'];
			 $hashCheck = crypt($password, $hash);
				  	
			  if( $hashCheck === $hash ) {
			  	 if( $status == 1 ) { 
			  	 	$this->error('Account suspended. Please contact the Admins');
			  	 	exit();
			  	 }

			  	 $_SESSION['ez_admin'] = $username;

			 	 $this->success('Logging in...');
			  } else {
			  	 $this->error('Incorrect username or password');
			  }
	}
				  
	/*
	 * Create User
	 * strength - [1-10] strength of the salt
	 * salt and hash - each user has a unique salt combined with a hash
	 * the password is not stored
	 */
	public function register($username, $password, $email) {		
		$strength = '5';
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		 //blowfish algorithm
		$salt = sprintf("$2a$%02d$", $strength) . $salt;
		$hash = crypt($password, $salt);
		 //check to make sure this username or email does not already exist
		$result = $this->link->query("SELECT * FROM `" . $this->prefix . "users` WHERE (username = '$username') OR (email = '$email')");
		$count = $this->numRows($result);
		if($count > 0) {
			print "<strong>Error</strong> Username or E-Mail already exists";
		} else {
			$this->link->query("INSERT INTO `" . $this->prefix . "users` SET username = '$username', email = '$email', salt = '$salt',
					hash = '$hash', role = 'user'
					");
			$this->success('Account has been created. You may now login.');
		}
	
	}
	
	/*
	 * Run the installation and create all necessary tables
	 * 
	 * @return string
	 */
	public function run_installer($site_name) {
		
		$test_connection = mysqli_connect($this->host, $this->username, $this->password, $this->database) or die("Error " . mysqli_error( $test_connection ) );
		if( $test_connection ) {
			$salt = '$2a$05$Bs3HEiQG6G9PZHkY.Ay3Cg==';

			$hash = '$2a$05$Bs3HEiQG6G9PZHkY.Ay3CeE1lBUiLRSiRSl57pmRs61C8GWsKAt6G';
			$sql = "
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "comments` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `post_id` int(10) DEFAULT NULL,
			  `author` varchar(100) DEFAULT NULL,
			  `author_id` int(10) DEFAULT NULL,
			  `comment` varchar(10000) DEFAULT NULL,
			  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			); 
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "disputes` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `match_id` int(10) DEFAULT NULL,
			  `category` varchar(20) DEFAULT NULL,
			  `description` varchar(2000) DEFAULT NULL,
			  `filed_by` varchar(100) DEFAULT NULL,
			  `status` int(1) DEFAULT '0',
			  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			); 
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "forum_answer` (
			  `a_id` int(10) NOT NULL AUTO_INCREMENT,
			  `question_id` int(10) NOT NULL,
			  `a_answer` longtext NOT NULL,
			  `a_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `a_section` int(10) NOT NULL,
			  `a_username` varchar(55) NOT NULL,
			  `a_user_id` int(10) NOT NULL,
			  PRIMARY KEY (`a_id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "forum_question` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `topic` varchar(255) NOT NULL,
			  `detail` longtext NOT NULL,
			  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `view` int(4) NOT NULL DEFAULT '0',
			  `reply` int(4) NOT NULL DEFAULT '0',
			  `section` int(10) NOT NULL,
			  `starter_user_id` int(10) NOT NULL,
			  `starter_username` varchar(55) NOT NULL,
			  `recent_username` varchar(55) NOT NULL,
			  `recent_user_id` int(10) NOT NULL,
			  `recent_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "forum_section` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `section_name` varchar(50) NOT NULL,
			  `type` varchar(25) NOT NULL DEFAULT 'public',
			  `status` varchar(50) DEFAULT 'enabled',
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "games` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `game` varchar(100) DEFAULT NULL,
			  `short_name` varchar(10) DEFAULT NULL,
			  `slug` varchar(50) NOT NULL,
			  `logo` varchar(100) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "guilds` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `guild` varchar(50) DEFAULT NULL,
			  `abbreviation` varchar(5) DEFAULT NULL,
			  `gm` varchar(50) DEFAULT NULL,
			  `agm` varchar(50) DEFAULT NULL,
			  `website` varchar(100) DEFAULT NULL,
			  `password` varchar(45) DEFAULT NULL,
			  `admin` varchar(50) NOT NULL,
			  `game` varchar(25) DEFAULT NULL,
			  `leagues` varchar(50) DEFAULT NULL,
			  `tournaments` varchar(50) NOT NULL,
			  `logo` varchar(250) DEFAULT NULL,
			  PRIMARY KEY (`id`,`admin`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "inbox_messages` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `msg_id` int(10) NOT NULL,
			  `sender` varchar(100) DEFAULT NULL,
			  `recipient` varchar(250) DEFAULT NULL,
			  `subject` varchar(250) DEFAULT NULL,
			  `status` varchar(10) DEFAULT 'unread',
			  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "inbox_original` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `sender` varchar(50) DEFAULT NULL,
			  `subject` varchar(250) DEFAULT NULL,
			  `message` blob,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "inbox_replies` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `msg_id` int(10) DEFAULT NULL,
			  `sender` varchar(50) DEFAULT NULL,
			  `message` blob,
			  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "leagues` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `league` varchar(50) DEFAULT NULL,
			  `teams` int(19) DEFAULT '6',
			  `game` varchar(50) DEFAULT NULL,
			  `open` int(1) DEFAULT '1',
			  `start_date` date DEFAULT NULL,
			  `end_date` date DEFAULT NULL,
			  `total_games` int(10) DEFAULT '8',
			  `rules` varchar(10000) DEFAULT NULL,
			  `rosters` int(1) DEFAULT '1',
			  `max_roster` int(10) DEFAULT '7',
			  `suspended` blob DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "links` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `url` varchar(500) DEFAULT NULL,
			  `text` varchar(250) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "map_schedule` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `map` varchar(250) DEFAULT NULL,
			  `week` int(10) DEFAULT NULL,
			  `league` int(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "maps` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `map` varchar(250) DEFAULT NULL,
			  `league` int(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "matches` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `league` int(10) DEFAULT NULL,
			  `homeTeam` varchar(50) DEFAULT NULL,
			  `homeTeamID` int(10) DEFAULT NULL,
			  `homeTeam_accept` int(1) DEFAULT '0',
			  `homeScore` int(10) DEFAULT NULL,
			  `awayTeam` varchar(50) DEFAULT NULL,
			  `awayTeamID` int(10) DEFAULT NULL,
			  `awayTeam_accept` int(1) DEFAULT '0',
			  `awayScore` int(10) DEFAULT NULL,
			  `winner` int(10) DEFAULT NULL,
			  `loser` int(10) DEFAULT NULL,
			  `season` varchar(20) DEFAULT NULL,
			  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `chat_log` blob,
			  `matchDate` date DEFAULT NULL,
			  `matchTime` varchar(30) DEFAULT NULL,
			  `matchZone` varchar(250) DEFAULT NULL,
			  `completed` int(1) DEFAULT '0',
			  `week` int(10) DEFAULT NULL,
			  `streamURL` varchar(200) DEFAULT NULL,
			  `featured` int(1) DEFAULT '0',
			  `reporter` varchar(50) DEFAULT NULL,
			  `server_ip` varchar(250) DEFAULT NULL,
			  `server_password` varchar(250) DEFAULT NULL,
			  `match_moderator` varchar(250) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "news` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL,
			  `body` varchar(5000) DEFAULT NULL,
			  `author` varchar(50) DEFAULT NULL,
			  `category` varchar(50) DEFAULT NULL,
			  `media` int(10) DEFAULT NULL,
			  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `published` int(1) DEFAULT '0',
			  `game` varchar(25) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "news_category` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `category` varchar(50) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "news_media` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `filename` varchar(100) DEFAULT NULL,
			  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "predictions` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `match_id` int(10) DEFAULT NULL,
			  `team` int(10) DEFAULT NULL,
			  `comment` varchar(500) DEFAULT NULL,
			  `user` varchar(50) DEFAULT NULL,
			  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "results` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `guild_id` int(10) NOT NULL,
			  `league_id` int(10) NOT NULL,
			  `result` varchar(1) NOT NULL,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `challenge_id` int(10) DEFAULT NULL,
			  `points_given` int(10) DEFAULT '0',
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "rosters` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `league` int(10) DEFAULT NULL,
			  `team` int(10) DEFAULT NULL,
			  `roster` blob,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "screenshots` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `filename` varchar(255) NOT NULL,
			  `match_id` int(10) NOT NULL,
			  `uploader` varchar(55) NOT NULL,
			  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "seasons` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `league_id` int(10) DEFAULT NULL,
			  `season` varchar(100) DEFAULT NULL,
			  `teams` blob,
			  `start` date DEFAULT NULL,
			  `end` date DEFAULT NULL,
			  `register_end` date DEFAULT NULL,
			  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "settings` (
			  `id` int(1) NOT NULL DEFAULT '1',
			  `site_name` varchar(100) DEFAULT NULL,
			  `site_url` varchar(255) DEFAULT NULL,
			  `site_about` blob,
			  `site_email` varchar(500) DEFAULT NULL,
			  `site_logo` varchar(250) DEFAULT 'logo.png',
			  `site_fav_icon` varchar(250) DEFAULT NULL,
			  `site_games` varchar(10000) DEFAULT NULL,
			  `site_twitter_handle` varchar(100) DEFAULT NULL,
			  `site_twitter_app` varchar(3000) DEFAULT NULL,
			  `site_facebook` varchar(100) DEFAULT NULL,
			  `site_google_plus` varchar(100) DEFAULT NULL,
			  `site_youtube` varchar(200) DEFAULT NULL,
			  `twitter_handle` varchar(250) DEFAULT NULL,
			  `twitter_count` int(10) DEFAULT '0',
			  `twitter_api` varchar(250) DEFAULT NULL,
			  `twitter_secret` varchar(250) DEFAULT NULL,
			  `twitter_token` varchar(250) DEFAULT NULL,
			  `twitter_token_secret` varchar(250) DEFAULT NULL,
			  `mandrill_username` varchar(250) DEFAULT NULL,
			  `mandrill_password` varchar(250) DEFAULT NULL,
			  `site_icon` varchar(250) DEFAULT NULL,
			  `site_timezone` varchar(250) DEFAULT NULL,
			  `forum_link` varchar(250) DEFAULT NULL,
			  `friends_email` int(1) DEFAULT '1',
			  `show_tournaments` int(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "tournaments` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `tournament` varchar(250) NOT NULL,
			  `max_teams` int(250) NOT NULL,
			  `start_date` date NOT NULL,
			  `registration_date` date NOT NULL,
			  `format` varchar(50) NOT NULL,
			  `maps` varchar(1000) NOT NULL,
			  `type` varchar(25) NOT NULL DEFAULT 'single',
			  `status` int(1) NOT NULL DEFAULT '1',
			  `first_place` int(10) NOT NULL,
			  `second_place` int(10) NOT NULL,
			  `game` varchar(100) NOT NULL,
			  `rules` varchar(10000) NOT NULL,
			  `public` int(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "tournament_map_schedule` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `map` varchar(250) DEFAULT NULL,
			  `round` int(10) DEFAULT NULL,
			  `tournament_id` int(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "tournament_matches` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `tid` int(10) NOT NULL,
			  `round` int(1) NOT NULL,
			  `home_team` varchar(100) NOT NULL,
			  `home_team_id` int(10) NOT NULL,
			  `home_score` int(10) NOT NULL,
			  `away_team` varchar(100) NOT NULL,
			  `away_team_id` int(10) NOT NULL,
			  `away_score` int(10) NOT NULL,
			  `home_accept` int(1) NOT NULL,
			  `away_accept` int(1) NOT NULL,
			  `winner` int(10) NOT NULL,
			  `loser` int(10) NOT NULL,
			  `match_date` date NOT NULL,
			  `match_time` varchar(100) NOT NULL DEFAULT '12:00',
			  `match_zone` varchar(250) NOT NULL,
			  `server_ip` varchar(250) NOT NULL,
			  `server_password` varchar(25) NOT NULL,
			  `stream_url` varchar(250) NOT NULL,
			  `match_log` blob NOT NULL,
			  `reporter` varchar(50) NOT NULL,
			  `match_moderator` varchar(50) NOT NULL,
			  `completed` int(1) NOT NULL,
			  PRIMARY KEY (`id`)
			);
			CREATE TABLE IF NOT EXISTS `" . $this->prefix . "users` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `username` varchar(50) DEFAULT NULL,
			  `first_name` varchar(250) DEFAULT NULL,
			  `last_name` varchar(250) DEFAULT NULL,
			  `email` varchar(150) DEFAULT NULL,
			  `guild` varchar(100) DEFAULT NULL,
			  `role` varchar(20) DEFAULT NULL,
			  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  `modified` timestamp NULL DEFAULT NULL,
			  `salt` varchar(100) DEFAULT NULL,
			  `hash` varchar(100) DEFAULT NULL,
			  `status` int(1) DEFAULT '0',
			  `forget` varchar(250) DEFAULT NULL,
			  `invites` varchar(100) DEFAULT NULL,
			  `post_count` int(10) DEFAULT '0',
			  `signature` varchar(1000) DEFAULT NULL,
			  `website` varchar(500) DEFAULT NULL,
			  `location` varchar(250) DEFAULT NULL,
			  `occupation` varchar(250) DEFAULT NULL,
			  `hobbies` varchar(1000) DEFAULT NULL,
			  `bio` varchar(50000) DEFAULT NULL,
			  `avatar` varchar(500) DEFAULT NULL,
			  `friends` blob,
			  PRIMARY KEY (`id`)
			); 
			INSERT INTO `" . $this->prefix . "settings` SET site_name = '" . $site_name . "', site_url = '" . $this->site_url . "';
			INSERT INTO `" . $this->prefix . "users` SET username = 'admin', salt = '" . $salt . "', hash = '" . $hash . "', role = 'admin';
			ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";

			if (mysqli_multi_query($test_connection, $sql)) {
			    do {
			        /* store first result set */
			        if ($result = mysqli_store_result($test_connection)) {
			            while ($row = mysqli_fetch_row($result)) {
			                printf("%s\n", $row[0]);
			            }
			            mysqli_free_result($result);
			        }
			        if (mysqli_more_results($test_connection)) {}
			    } while (mysqli_next_result($test_connection));
			}
			
		} else {
			$this->error('Please check your connection details and try again');
		}
		return;
		
	}

	/*
	 * Check for upgrade
	 *
	 * @return string
	 */
	public function check_for_upgrade() {

		$file = 'upgrade.php';
		if( file_exists( $file ) ) {
			echo '<div class="update">';
			echo 'Please <a href="upgrade.php">run the update file</a> to keep your database structured updated.';
			echo '</div>';
			return;
		} else {
			return;
		}

	}

	/*
	 * Run the upgrade
	 *
	 * @return string
	 */
	public function run_upgrade() {

		$test_connection = mysqli_connect($this->host, $this->username, $this->password, $this->database) or die("Error " . mysqli_error( $test_connection ) );
		if( $test_connection ) {
			$sql = "
					ALTER TABLE `" . $this->prefix . "tournament_matches` 
					CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT;
					ALTER TABLE `" . $this->prefix . "tournament_map_schedule` 
					CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT;
					ALTER TABLE `" . $this->prefix . "tournaments` 
					CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT;
					CREATE TABLE IF NOT EXISTS `" . $this->prefix . "tournaments` (
					  `id` int(10) NOT NULL,
					  `tournament` varchar(250) NOT NULL,
					  `max_teams` int(250) NOT NULL,
					  `start_date` date NOT NULL,
					  `registration_date` date NOT NULL,
					  `format` varchar(50) NOT NULL,
					  `maps` varchar(1000) NOT NULL,
					  `type` varchar(25) NOT NULL DEFAULT 'single',
					  `status` int(1) NOT NULL DEFAULT '1',
					  `first_place` int(10) NOT NULL,
					  `second_place` int(10) NOT NULL,
					  `game` varchar(100) NOT NULL,
					  `rules` varchar(10000) NOT NULL,
					  `public` int(1) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`id`)
					);
					CREATE TABLE IF NOT EXISTS `" . $this->prefix . "tournament_map_schedule` (
					  `id` int(10) NOT NULL,
					  `map` varchar(250) DEFAULT NULL,
					  `round` int(10) DEFAULT NULL,
					  `tournament_id` int(10) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					);
					CREATE TABLE IF NOT EXISTS `" . $this->prefix . "tournament_matches` (
					  `id` int(10) NOT NULL,
					  `tid` int(10) NOT NULL,
					  `round` int(1) NOT NULL,
					  `home_team` varchar(100) NOT NULL,
					  `home_team_id` int(10) NOT NULL,
					  `home_score` int(10) NOT NULL,
					  `away_team` varchar(100) NOT NULL,
					  `away_team_id` int(10) NOT NULL,
					  `away_score` int(10) NOT NULL,
					  `home_accept` int(1) NOT NULL,
					  `away_accept` int(1) NOT NULL,
					  `winner` int(10) NOT NULL,
					  `loser` int(10) NOT NULL,
					  `match_date` date NOT NULL,
					  `match_time` varchar(100) NOT NULL DEFAULT '12:00',
					  `match_zone` varchar(250) NOT NULL,
					  `server_ip` varchar(250) NOT NULL,
					  `server_password` varchar(25) NOT NULL,
					  `stream_url` varchar(250) NOT NULL,
					  `match_log` blob NOT NULL,
					  `reporter` varchar(50) NOT NULL,
					  `match_moderator` varchar(50) NOT NULL,
					  `completed` int(1) NOT NULL,
					  PRIMARY KEY (`id`)
					);
					ALTER TABLE `" . $this->prefix . "guilds`
					AND `tournaments` VARCHAR(50);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD `show_tournaments` INT(1) NOT NULL DEFAULT '1';
					ALTER TABLE `" . $this->prefix . "settings` 
					ADD `friends_email` INT(1) NOT NULL DEFAULT '1';
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN forum_link VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "matches`
					MODIFY matchZone VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "matches`
					ADD COLUMN server_ip VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "matches`
					ADD COLUMN server_password VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "matches`
					ADD COLUMN match_moderator VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "leagues`
					ADD COLUMN suspended BLOB;
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN site_timezone VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN mandrill_username VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN mandrill_password VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "leagues`
					ADD COLUMN max_roster INT(10) DEFAULT '8';
					ALTER TABLE `" . $this->prefix . "games`
					MODIFY short_name VARCHAR(10);
					ALTER TABLE `" . $this->prefix . "predictions`
					ADD COLUMN match_id INT(10);
					ALTER TABLE `" . $this->prefix . "predictions`
					DROP COLUMN cid;
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN site_icon VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN twitter_handle VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN twitter_count INT(10);
					UPDATE `" . $this->prefix . "settings`
					SET twitter_count = '0' WHERE id = '1';
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN twitter_api VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN twitter_secret VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN twitter_token VARCHAR(250);
					ALTER TABLE `" . $this->prefix . "settings`
					ADD COLUMN twitter_token_secret VARCHAR(250);
					";

			mysqli_multi_query($test_connection, $sql);
			$this->success('Upgrade completed. Head <a href="index.php">home</a>');
		} else {
			$this->error('Please check your connection details and try again');
		}
		return;
	}

	/*
	 * ABOUT: Check if a specific key and value exist in an array
	 * USED IN: 
	 * Matches View -> Check if match has an open dispute [status = 0]
	 */	
	public function multidimensional_search($parents, $searched) {
		
		if (empty($searched) || empty($parents)) {
			return false;
		}
	
		foreach ($parents as $key => $value) {
			$exists = true;
			foreach ($searched as $skey => $svalue) {
				$exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
			}
			if($exists){
				return true;
			}
		}
	
		return false;
		
	}
	
	public function removeArrayValue($string, $array) {
		
		//$array = unserialize($array);
		if(($key = array_search($string, $array)) !== false) {
			unset($array[$key]);
		}
	
		return $array;
		
	}
		
/*
 * END SPECIAL FUNCTIONS
 */		
	}
?>