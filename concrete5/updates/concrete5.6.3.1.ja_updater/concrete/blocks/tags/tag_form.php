<?php defined('C5_EXECUTE') or die("Access Denied.");  
$form = Loader::helper('form');
$c = Page::getCurrentPage();

if(!$ak instanceof CollectionAttributeKey) {?>
	<div class="ccm-error"><?php echo t('Error: The required page attribute with the handle of: "%s" doesn\'t exist',$controller->attributeHandle)?><br/><br/></div>
<?php } else { ?>
<input type="hidden" name="attributeHandle" value="<?php echo $controller->attributeHandle?>" />
<ul id="ccm-tags-tabs" class="tabs">
	<li class="active"><a id="ccm-tags-tab-add" href="javascript:void(0);"><?php echo ($bID>0)? t('Edit') : t('Add') ?></a></li>
	<li class=""><a id="ccm-tags-tab-advanced"  href="javascript:void(0);"><?php echo t('Advanced')?></a></li>
</ul>

<div id="ccm-tagsPane-add" class="ccm-tagsPane">

	<div class="clearfix">
	<?php echo $form->label('title', t('Display Title'))?>
	<div class="input">
		<?php echo $form->text('title',$title);?>
	</div>
	</div>

	<div class="clearfix">
	<label><?php echo t('Display')?></label>
	<div class="input">
	<ul class="inputs-list">
		<li><label><?php echo $form->radio('displayMode','page',$displayMode)?> <span><?php echo t('Display Tags for the current page')?></span></label></li>
		<li><label><?php echo $form->radio('displayMode','cloud',$displayMode)?> <span><?php echo t('Display available tags')?></span></label></li>
	</ul>
	</div>
	</div>

	<?php if (!$inStackDashboardPage) { ?>
	<div id="ccm-tags-display-page" class="clearfix">
	<label><?php echo $ak->getAttributeKeyDisplayName();?></label>
	<div class="input">
		<?php
			$av = $c->getAttributeValueObject($ak);
			$ak->render('form',$av);
		?>
	</div>
	</div>
	<?php } ?>

	<div id="ccm-tags-display-cloud" class="clearfix">
	<?php echo $form->label('cloudCount', t('Number to Display'))?>
	<div class="input">
			<?php echo $form->text('cloudCount',$cloudCount,array('size'=>4))?>
	</div>
	</div>

</div>

<div id="ccm-tagsPane-advanced" class="ccm-tagsPane" style="display:none">
	<div class="clearfix">
	<label><?php echo t('Link Tags to Page')?></label>
	<div class="input">
		<?php
		$form_selector = Loader::helper('form/page_selector');
		print $form_selector->selectPage('targetCID', $targetCID);
		?>
	</div>
	</div>

</div>
<?php } ?>
