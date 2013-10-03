<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
	
	<div class="row">
		<section id="main" class="twelve columns" role="main">
		
			<?php  Loader::element('system_errors', array('error' => $error)); ?>
			<?php	 print $innerContent; ?>
			
		</section>
	</div>
	
<?php $this->inc('elements/footer.php'); ?>
