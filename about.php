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
						О Нас <small>создание, управление, игра.</small>
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
							<a href="#">О Нас</a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 blog-page">
						<div class="col-md-9 col-sm-8 article-block">
							<h1>О нас <?php echo $site_settings['name']; ?></h1>
								<div class="about-content">
								<?php echo $site_settings['about']; ?>
								</div>
							<?php include( 'tpls/system/bottom-leaderboard.php' ); ?>
						</div>
						<!--end col-md-9-->
						<?php include('sidebar.php'); ?>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<?php include('footer.php'); ?>
