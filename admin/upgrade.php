<?php session_start();
date_default_timezone_set('America/Chicago');
include('lib/class-db.php');
include('lib/class-ezadmin.php');
$ez = new ezAdmin();
    
     if( isset($_SESSION['ez_admin'] ) ) {
     	$ez->run_upgrade();
     	unlink('upgrade.php');
     } else {
     	echo 'Ты не пройдешь!!!';
 	 }
