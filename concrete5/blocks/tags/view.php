<?php defined('C5_EXECUTE') or die("Access Denied.");  ?>
<div class="ccm-tags-display">
<?php if(strlen($title)) {
	?><h3><?php echo $title ?></h3><?php
}
if($options instanceof SelectAttributeTypeOptionList && $options->count() > 0) {
	?><ul class="ccm-tag-list">
		<?php foreach($options as $opt) {
			$qs = $akc->field('atSelectOptionID') . '[]=' . $opt->getSelectAttributeOptionID();
			?><li <?php echo ($selectedOptionID == $opt->getSelectAttributeOptionID()?'class="ccm-tag-selected"':'')?>><?php	 if ($target instanceof Page) { ?>
				<a href="<?php	echo $navigation->getLinkToCollection($target)?>?<?php	echo $qs?>" class="round label"><?php echo $opt ?></a><?php	 }  else { echo $opt; }?></li><?php 
		}?>	
	</ul>
<?php } ?>
</div>