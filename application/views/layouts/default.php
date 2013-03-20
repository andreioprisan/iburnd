<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<?php if ($title == "iburnd - nutrient") {?>
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# iburndapp: http://ogp.me/ns/fb/iburndapp#">
	<meta property="fb:app_id" content="330097717027313" />
	<meta property="og:title" content="<?php if (isset($new_title)) { echo str_replace(array("\""), array(" inch"), $new_title); } else { echo 'unknown food'; } ?>" />
	<meta property="og:image" content="<?php if (isset($new_img_url)) { echo $new_img_url; } else { echo ''; } ?>" />
	<meta property="og:url" content="<?php if (isset($new_url)) { echo $new_url; } else { echo ''; } ?>" />
		<?php if ($nop == false) { ?>
		<meta property="og:description" content="Servings: <?= $servings ?>, Serving Size: <?= $serving ?>, Calories: <?= $calories ?>" />
		<?php } else { ?>
		<meta property="og:description" content="Servings: 1, Serving Size: 1 serving" />
		<?php }?>
	<meta property="og:type" content="iburndapp:food" />
	
	<?php } else if ($title == "iburnd - workout") {?>
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# iburndapp: http://ogp.me/ns/fb/iburndapp#">
	<meta property="fb:app_id" content="330097717027313" />
	<meta property="og:title" content="<?php if (isset($new_title)) { echo $new_title; } else { echo 'unknown workout'; } ?>" />
	<meta property="og:image" content="<?php if (isset($new_img_url)) { echo $new_img_url; } else { echo ''; } ?>" />
	<meta property="og:url" content="<?php if (isset($new_url)) { echo $new_url; } else { echo ''; } ?>" />
	<meta property="og:description" content="Time: <?= $time ?>, Mets: <?= $mets ?>, Calories: <?= $mets ?>, Date: <?= $date ?>">
	<meta property="og:type" content="iburndapp:workout" />
	<?php } else { ?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php } ?>
	<title><?= $title ?></title>
	<?php foreach($css as $link): ?>
	<link type="text/css" rel="stylesheet" href="/assets/css/<?= $link ?>.css" />
	<?php endforeach; ?>
	<?php foreach($js as $link): ?>
	<script type="text/javascript" src="/assets/js/<?= $link ?>.js"></script>
	<?php endforeach; ?>
	
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-29444973-1']);
	  _gaq.push(['_setDomainName', 'iburnd.com']);
	  _gaq.push(['_setAllowLinker', true]);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>
<body>
	<!--
	<script type="text/javascript">var _kiq = _kiq || [];</script>
	<script type="text/javascript" src="//s3.amazonaws.com/ki.js/33003/6qf.js" async="true"></script>
	-->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
	    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="/start"><img src="/assets/img/small.logo.writing.png" style="borer:0; height:34px; padding-top: 2px; position: absolute; top:0px; left:12px" ></a>
		<div class="nav-collapse">
		<?php if (isset($menu) && count($menu) > 0) { ?>
		
		<ul class="nav" style="position:absolute; left: -30%;">
			<?php if (isset($menu['calorietracker'])) { ?>
			<li class="dropdown" id="stuck-menuitem" style="position:absolute; right: -650px;">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color:white; text-decoration:none; height: 15px;">
				<div class="progress progress-danger" style="width: 140px;">
					<div id='caloriebar' class="bar" style="width: 0%;"></div>
				</div>
				<ul class="dropdown-menu" id="stuck" style="width: 790px; max-width: 850px; height: 50px">
					<li style="width: 790px; max-width: 850px; height:30px; background-color:white; display: block;">
						<div style="padding-top: 0px; right: 0px; top: -35px; position: relative; width: 750px; z-index: 10">
							<div class="btn-group" data-toggle="buttons-radio" id="mainlookuptype" style="position:relative; top: -0px; left: 0.11%; font-size:16px; height: 31px; display:inline">
								<button class="btn alert-info" id="2" style="font-size:13px; height: 32px;" onclick="mainsearchtypeupdate('nutrition');">Food</button>
								<button class="btn alert-danger" id="1" style="font-size:13px; height: 32px;" onclick="mainsearchtypeupdate('workout');">Workout</button>
							</div>		

							<input class="input-xlarge focused ui-autocomplete-input" id="tags" name="searchinput" type="text" placeholder="search for foods or workouts" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; height:1.3em; line-height:0.9em; font-size:1.1em; width: 620px; margin-top: 0px; position: relative" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
							<a class="" value="X" style="position:relative; top:-1px; font-size:16px; height: 17px; top: -27px; left: 730px;" href="#" onclick=" clearAlertTag(); $('#tags').val('');"><i class="icon-remove"></i></a>
						</div>
					</li>
				</ul>
				</a>
			</li>

			
			<?php } ?>
			
			<?php 
			foreach ($menu as $menuitem_val) {
			
			if (isset($menuitem_val['username']))
				continue;

			if (isset($menuitem_val['calorietracker']))
				continue;

			if ($menuitem_val['align'] == "right")
				continue;
					
			if (isset($menuitem_val['name']) && isset($menuitem_val['val'])) {
				if (is_array($menuitem_val['val']))
				{ ?>
				<li class="dropdown" style="padding-right: 30px;">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $menuitem_val['name'] ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php 
						foreach ($menuitem_val['val'] as $menuitem_dropdown) { ?>
							<?php if (!isset($menuitem_dropdown['val']) && !isset($menuitem_dropdown['name'])) {?>
								<li class="divider"></li>
							<?php } else { 
								?>
								<li><a href="<?= $menuitem_dropdown['val']?>"><?= $menuitem_dropdown['name']?></a></li>
							<?php } ?>
						<?php } ?>
					</ul>
				</li>
				<?php } else { ?>
						<li><a href="<?= $menuitem_val['val'] ?>"><?= $menuitem_val['name'] ?></a></li>
				<?php }
				}
			} ?>
		</ul>
		<?php 
		} ?>
		<ul class="nav pull-right">
			<?php 
			if ((isset($menuitem_val) && count($menuitem_val) > 0)) 
			{
				foreach ($menu as $menuitem_val) 
				{ 
				?>
				<?php if (isset($menuitem_val['align']) && $menuitem_val['align'] == "right") { ?>
					<?php if (isset($menuitem_val['username'])) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">me <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#">Your Profile</a></li>
							<li class="divider"></li>
							<li><a href="#">Account Settings</a></li>
							<li><a href="/facebook_auth/logout">Log Out</a></li>
						</ul>
					</li>
					<?php } else { ?>
					<?php 
					if (isset($menuitem_val['login'])) { ?>
						<li><a href="<?= $menuitem_val['val'] ?>"><?= $menuitem_val['name'] ?> <img src="/assets/img/facebook_32.png" style="height: 23px; position: relative; top: 7px; margin-top: -7px;"></a></li>
					<?php } else { ?>
						<?php if (is_array($menuitem_val['val']))
						{ ?>
						<li class="dropdown" style="padding-right: 5px;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $menuitem_val['name'] ?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<?php 
								foreach ($menuitem_val['val'] as $menuitem_dropdown) { ?>
									<?php if (!isset($menuitem_dropdown['val']) && !isset($menuitem_dropdown['name'])) {?>
										<li class="divider"></li>
									<?php } else { 
										?>
										<li><a href="<?= $menuitem_dropdown['val']?>"><?= $menuitem_dropdown['name']?></a></li>
									<?php } ?>
								<?php } ?>
							</ul>
						</li>
						<?php } else { ?>
								<li><a href="<?= $menuitem_val['val'] ?>"><?= $menuitem_val['name'] ?></a></li>
						<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } 
			} ?>
		</ul>
		</div>

    </div>
  </div>
</div>
<br>
<br>

<div class="container">
	<div class="content" style="min-height: 530px;">
		<?= $alertitemadd ?>

		<?= $start ?>

		<?= $dashboard ?>

		<?= $nikeplussync ?>
		<?= $nikeplusruns ?>
		
		<?= $nutritionsearch ?>
		
		<?= $login ?>

		<?= $nutrition ?>
		<?= $nutritionlog ?>
		<?= $nutritionfavorites ?>
		<?= $workout ?>
		<?= $workoutlog ?>
		<?= $workoutfavorites ?>

		<?= $privacy ?>
		<?= $tos ?>


	</div>



	
</div> 

<?= $content ?>

<footer>
      <div class="footer-content">
        <ul><!--
          <li>
            <a href="/support" target="_blank">Support @ (805) 322-8763</a>
          </li>-->
          <li class="icon">
            <a class="icon icons-social-facebook" href="http://www.facebook.com/iburnd" target="_blank">Facebook</a>
          </li>
          <li class="icon">
            <a class="icon icons-social-twitter" href="http://twitter.com/iburnd" target="_blank" style="background-position: 0 -52px;">Twitter</a>
          </li>
          <li class="">
            <a href="#">&copy; iburnd 2012</a>
          </li>
        </ul>
		<div class="footer-pages">
			<a class="branding" href="/privacy">privacy</a>
			<a class="branding" href="/tos">terms</a>
			<a class="branding" href="/api">api</a>
			<a class="branding" href="/jobs">jobs</a>
	        <a class="branding" href="/team">team</a>
		</div>
      </div>
    </footer>

</body>
</html>