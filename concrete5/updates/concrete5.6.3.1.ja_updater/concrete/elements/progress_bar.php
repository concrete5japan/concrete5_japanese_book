<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="ccm-ui">
	<div id="ccm-progressive-operation-progress-bar" data-total-items="<?php echo $totalItems?>">
	<div class="progress progress-striped active">
	<div class="bar" style="width: 0%;"></div>
	</div>
	</div>

	<div><span id="ccm-progressive-operation-status">1</span> <?php echo t('of')?> <?php echo $totalItemsSummary?></div>
</div>