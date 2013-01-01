<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>
	
	<div class="row">
		<section id="main" class="eight columns push-four" role="main">
		
			<?php 
			$a = new Area('Main');
			$a->display($c);
			?>
			
		</section>
		
		<aside class="sidebar four columns pull-eight" role="complementary">
		
			<?php 
			$a = new Area('Sidebar');
			$a->display($c);
			?>
			
		</aside>
	</div>
	
<?php $this->inc('elements/footer.php'); ?>
