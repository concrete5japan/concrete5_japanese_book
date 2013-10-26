<?php	 defined('C5_EXECUTE') or die("Access Denied."); ?>

<form action="<?php	echo $this->url( $resultTargetURL )?>" method="get" class="ccm-search-block-form">
	
	<?php	 if(strlen($query)==0){ ?>
	<input name="search_paths[]" type="hidden" value="<?php	echo h($baseSearchPath)?>" />
	<?php	 } else if (is_array($_REQUEST['search_paths'])) { 
		foreach($_REQUEST['search_paths'] as $search_path){ ?>
			<input name="search_paths[]" type="hidden" value="<?php	echo h($search_path)?>" />
	<?php	  }
	} ?>
	
	<input name="query" type="text" value="<?php	echo h($query)?>" class="ccm-search-block-text" />
	
	<input name="submit" type="submit" value="<?php	echo h($buttonText)?>" class="ccm-search-block-submit" />

</form>