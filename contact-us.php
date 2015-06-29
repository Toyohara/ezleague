<?php include('header.php'); ?>
<?php 
	$num1 = rand(1,10);
	$num2 = rand(1,10);
	$captcha = $num1 + $num2;
?>
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
						Связь <small>коментарии, вопросы, контакты.</small>
						</h3>
					</div>
					<div class="col-lg-8">
						<?php include( 'tpls/system/top-leaderboard.php' ); ?>
					</div>
					<div class="col-lg-12">
						<ul class="page-breadcrumb breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="index.php">Home</a>
								<i class="fa fa-angle-right"></i>
							</li>
							<li>
								<a href="#">Связь</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 blog-page">
					<div class="row">
						<div class="col-md-9 col-sm-8 article-block">
							<div class="row">
								<div class="col-md-5">
									<div class="space20">
									</div>
									<h3 class="form-section">Социальные сети</h3>
									<p>
										 Свяжитесь с нами через социальные сети, покажите Вашу поддержку  <?php echo $site_settings['name']; ?> сообществу.
									</p>
									<div class="well-social-networks">
										<ul class="contact-social-networks margin-bottom-10">
									<?php if( $social_networks['facebook'] != '' ) { ?>
											<li>
												<a href="<?php echo $social_networks['facebook']; ?>" data-original-title="facebook"><i class="fa fa-facebook-square"></i></a>
											</li>
									<?php } ?>
									<?php if( $social_networks['twitter'] != '' ) { ?>
											<li>
												<a href="http://www.twitter.com/<?php echo $social_networks['twitter']; ?>" data-original-title="twitter"><i class="fa fa-twitter"></i></a>
											</li>
									<?php } ?>
									<?php if( $social_networks['youtube'] != '' ) { ?>
											<li>
												<a href="<?php echo $social_networks['youtube']; ?>" data-original-title="youtube"><i class="fa fa-youtube"></i></a>
											</li>
									<?php } ?>
									<?php if( $social_networks['google'] != '' ) { ?>
											<li>
												<a href="<?php echo $social_networks['google']; ?>" data-original-title="google plus"><i class="fa fa-google-plus"></i></a>
											</li>
									<?php } ?>
										</ul>
									</div>
								</div>
								<div class="col-md-7">
									<div class="space20">
									</div>
									<h3 class="form-section">Отправить сообщение</h3>
									<p>
										 Пришлите нам сообщение, используя форму ниже, и мы свяжемся с Вами так быстро как сможем.
									</p>
									<div class="success"><span class="success_text"></span></div>
									<form id="sendMessage" method="POST">
									 <input type="hidden" id="captcha-answer" value="<?php echo $captcha; ?>" />
									 <input type="hidden" id="to" value="<?php echo $site_settings['email']; ?>" />
										<div class="form-group">
											<div class="input-icon">
												<i class="fa fa-check"></i>
												<input type="text" id="subject" class="form-control" placeholder="E-Mail Subject">
											</div>
										</div>
										<div class="form-group">
											<div class="input-icon">
												<i class="fa fa-user"></i>
												<input type="text" id="name" class="form-control" placeholder="Your Name">
											</div>
										</div>
										<div class="form-group">
											<div class="input-icon">
												<i class="fa fa-envelope"></i>
												<input type="text" id="from" class="form-control" placeholder="Your Email">
											</div>
										</div>
										<div class="form-group">
											<textarea class="form-control" id="message" rows="3=6" placeholder="Message"></textarea>
										</div>
										<div class="form-group">
											<label>What is <?php echo $num1 . ' + ' . $num2; ?>?</label>
											<div class="input-icon">
												<i class="fa fa-lock"></i>
												<input type="text" id="captcha" class="form-control" placeholder="CAPTCHA">
											</div>
										</div>
										<button type="submit" class="btn green">Submit</button>
									</form>
								</div>
							</div>
							<?php include( 'tpls/system/bottom-leaderboard.php' ); ?>
						</div>
						<!--end col-md-9-->
						<?php include('sidebar.php'); ?>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<?php include('footer.php'); ?>
