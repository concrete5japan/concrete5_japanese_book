<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php	echo LANGUAGE?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?php	echo LANGUAGE?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?php	echo LANGUAGE?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php	echo LANGUAGE?>"> <!--<![endif]-->
<head>

	<?php  Loader::element('header_required'); ?>

	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width" />
	
	<link rel="stylesheet" href="<?php echo $this->getThemePath(); ?>/stylesheets/foundation.min.css">
	<link rel="stylesheet" href="<?php echo $this->getThemePath(); ?>/main.css">

	<script src="<?php echo $this->getThemePath(); ?>/javascripts/modernizr.foundation.js"></script>
</head>
<body>

<div id="page" class="page">
	
	<div id="masthead" class="row">
		<div class="twelve columns">
			<?php 
			$a = new GlobalArea('Masthead');
			$a->display();
			?>
		</div>
	</div>
	
	<header id="pageHeader" class="row" role="banner">
		<div class="six columns">
			<?php 
			$a = new GlobalArea('Site Name');
			$a->display();
			?>
		</div>
		<div class="six columns">
			<?php 
			$a = new GlobalArea('Header Right');
			$a->display();
			?>
		</div>
	</header>
		
	<section id="featured" class="row">
		<div class="twelve columns">
			<?php 
			$a = new Area('Header');
			$a->display($c);
			?>
		</div>
	</section>
	
	<nav id="globalNav" class="row" role="navigation">
		<div class="twelve columns clearfix">
			<?php 
			$a = new GlobalArea('Header Nav');
			$a->display();
			?>
		</div>
	</nav>