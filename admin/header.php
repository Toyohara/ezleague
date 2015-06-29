<?php session_start();
define( 'EZL_VERSION', '3.5.7' );
$check_for_update = file_get_contents( 'http://www.mdloring.com/ezleague_version.php', TRUE );
include('lib/class-db.php');
include('lib/class-ezadmin.php');
$ez = new ezAdmin();
$ez->setup_ezadmin();
$ez_settings    = new ezAdmin_Settings();
$site_settings  = $ez_settings->get_settings();
if( ! empty( $site_settings['timezone'] ) ) {
    date_default_timezone_set('' . $site_settings['timezone'] . '');
} else {
    date_default_timezone_set('UTC');
}

$ez_news 	    = new ezAdmin_News();
$ez_frontend    = new ezAdmin_Frontend();
$ez_user        = new ezAdmin_User();
$ez_team	    = new ezAdmin_Team();
$ez_league	    = new ezAdmin_League();
$ez_match	    = new ezAdmin_Match();
$ez_forum	    = new ezAdmin_Forum();
$ez_schedule    = new ezAdmin_Schedule();
$ez_tournament  = new ezAdmin_Tournament();
    
     if( ! isset($_SESSION['ez_admin'] ) ) {
        if( $ez_frontend->test_connection() ) {
            header("Location: login.php");
        } else {
            header("Location: install.php");
        }
     	
     } else {
     	$username = $_SESSION['ez_admin'];
     	$user_settings = $ez_user->get_user_settings( $username );
     }
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ezLeague - Менеджер лиги он-лайн игр</title>

    <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="css/plugins/timeline/timeline.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">
    
    <link href="css/ezleague.css?v1" rel="stylesheet">
    <link href="css/ezleague-tournament.css?v1" rel="stylesheet">
    <link href="css/redmond/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">ezLeague <sup>v<?php echo EZL_VERSION; ?></sup></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                 
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Приветсвуем <em><?php echo $username; ?></em>
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="settings.php?page=profile"><i class="fa fa-gear fa-fw"></i> Мои настройки</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="settings.php?page=admins"><i class="fa fa-users fa-fw"></i> Посмотреть админов</a>
                        </li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Выйти</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

        </nav>
        <!-- /.navbar-static-top -->
