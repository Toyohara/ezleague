<?php
	if( $match_details['dispute'] != 'no' ) {
		if( $match_details['dispute'] == 'pending' ) {
			$dispute_text = " - Match Dispute is <span class='text-danger bolder'>" . $match_details['dispute'] . '</span>';
		} else {
			$dispute_text = " - Match Dispute is <span class='text-success bolder'>" . $match_details['dispute'] . '</span>';
		}
	} else {
		$dispute_text = '';
	}
?>
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-picture"></i><?php echo $match_details['tournament'] . ' Match Details' . $dispute_text; ?>
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse">
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
		<div class="col-md-12">
			<div class="success"><span class="success_text"></span></div>
		</div>
	<?php 
		if( $match_details['home_id'] == $profile['guild_id'] || $match_details['away_id'] == $profile['guild_id'] ) { 
			$team_admin = $ez_users->get_team_admin( $profile['guild_id'] );
			 if( $team_admin == $profile['username'] ) {
			 	include('tpls/settings/tournaments/view-match-admin.php');
			 } else {
			 	include('tpls/settings/tournaments/view-match-user.php');	
			 }

		} else { ?>
		<h3>You are not authorized here</h3>
	<?php } ?>
		</div>
	</div>
</div>