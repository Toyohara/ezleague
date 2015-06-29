<?php include('header.php'); ?>
<!-- BEGIN CONTAINER -->
<div class="page-container">
<?php include('navbar.php'); ?>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		<div class="row">
			<div class="col-md-12">
				<div class="col-lg-4">
					<h3 class="page-title">
					Турниры <small>регистрация, состязание, игра.</small>
					</h3>
				</div>
				<div class="col-lg-8">
					<?php include( 'tpls/system/top-leaderboard.php' ); ?>
				</div>
				<div class="col-lg-12">
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="index.php">Домой</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Турниры</a>
						</li>
					<?php if( isset($_GET['p'] ) ) { ?>
						<?php if( $_GET['p'] == 'matchup' ) { ?>
							<li>
								<i class="fa fa-angle-right"></i>
								<a href="#">Посмотреть матчи</a>
							</li>
						<?php } elseif( $_GET['p'] == 'view' ) { ?>
							<li>
								<i class="fa fa-angle-right"></i>
								<a href="#">Посмотреть брекеты</a>
							</li>
						<?php } elseif( $_GET['p'] == 'rules' ) { ?>
							<li>
								<i class="fa fa-angle-right"></i>
								<a href="#">Посмотреть правила</a>
							</li>
						<?php } ?>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12 blog-page">
				<div class="row">
					<?php 
						if( ! isset( $_GET['id'] ) && ! isset( $_GET['status'] ) && ! isset( $_GET['p'] ) ) {
							include( 'tpls/tournaments/view-public-tournaments.php' );
						} elseif( isset( $_GET['status'] ) ) {
							$status = trim( $_GET['status'] );
							switch( $status ) {
								case 'public':
									include( 'tpls/tournaments/view-public-tournaments.php' );
									break;
								case 'running':
									include( 'tpls/tournaments/view-running-tournaments.php' );
									break;
								case 'private':
									include( 'tpls/tournaments/view-private-tournaments.php' );
									break;
								case 'closed':
									include( 'tpls/tournaments/view-closed-tournaments.php' );
									break;
								default:
									include( 'tpls/tournaments/view-public-tournaments.php' );
									break;
							}
						} elseif( isset( $_GET['p'] ) ) {
							$page = trim( $_GET['p'] );
							if( isset( $_GET['id'] ) ) {
								$tournament_id 	= trim( $_GET['id'] );
								if( is_numeric( $tournament_id ) ) {
									switch( $page ) {
										case 'view':
											include( 'tpls/tournaments/view-tournament.php' );
											break;
										case 'rules':
											include( 'tpls/tournaments/view-rules.php' );
											break;
										case 'matchup':
											include( 'tpls/tournaments/view-matchup.php' );
											break;
										default:
											break;
									}
								} else {
									echo 'Sorry, <em>tournament ids</em> must be integers';
								}
							} else {
								echo 'Sorry, no tournament id detected';
							}
						}
					?>
					<?php include( 'tpls/system/bottom-leaderboard.php' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<?php include('footer.php'); ?>
<script src="assets/global/scripts/normal.js" type="text/javascript"></script>
<div id="register-tournament-team-confirm" title="Join this tournament?" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Подтвердите регистрацию на турнире.</p>
</div>
<div id="make-prediction-confirm" title="Make game prediction" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Подтвердите свой прогноз.</p>
</div>
