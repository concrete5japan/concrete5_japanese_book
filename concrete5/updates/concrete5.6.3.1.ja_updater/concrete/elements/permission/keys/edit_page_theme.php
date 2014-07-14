<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php $included = $permissionAccess->getAccessListItems(); ?>
<?php $excluded = $permissionAccess->getAccessListItems(PermissionKey::ACCESS_TYPE_EXCLUDE); ?>
<?php $themes = PageTheme::getList(); ?>
<?php $form = Loader::helper('form'); ?>

<?php if (count($included) > 0 || count($excluded) > 0) { ?>

<?php if (count($included) > 0) { ?>

<h3><?php echo t('Who can set what?')?></h3>

<?php foreach($included as $assignment) {
	$entity = $assignment->getAccessEntityObject(); 
?>


<div class="clearfix">
	<label><?php echo $entity->getAccessEntityLabel()?></label>
	<div class="input">
	<?php echo $form->select('themesIncluded[' . $entity->getAccessEntityID() . ']', array('A' => t('All Themes'), 'C' => t('Custom')), $assignment->getThemesAllowedPermission())?><br/><br/>
	<ul class="theme-list inputs-list" <?php if ($assignment->getThemesAllowedPermission() != 'C') { ?>style="display: none"<?php } ?>>
		<?php foreach($themes as $ct) { ?>
			<li><label><input type="checkbox" name="ptIDInclude[<?php echo $entity->getAccessEntityID()?>][]" value="<?php echo $ct->getThemeID()?>" <?php if (in_array($ct->getThemeID(), $assignment->getThemesAllowedArray()) || $assignment->getThemesAllowedPermission() == 'A') { ?> checked="checked" <?php } ?> /> <span><?php echo $ct->getThemeDisplayName()?></span></label></li>
		<?php } ?>
	</ul>
	</div>
</div>


<?php }

} ?>


<?php if (count($excluded) > 0) { ?>

<h3><?php echo t('Who can\'t set what?')?></h3>

<?php foreach($excluded as $assignment) {
	$entity = $assignment->getAccessEntityObject(); 
?>


<div class="clearfix">
	<label><?php echo $entity->getAccessEntityLabel()?></label>
	<div class="input">
	<?php echo $form->select('themesExcluded[' . $entity->getAccessEntityID() . ']', array('N' => t('No Themes'), 'C' => t('Custom')), $assignment->getThemesAllowedPermission())?><br/><br/>
	<ul class="theme-list inputs-list" <?php if ($assignment->getThemesAllowedPermission() != 'C') { ?>style="display: none"<?php } ?>>
		<?php foreach($themes as $ct) { ?>
			<li><label><input type="checkbox" name="ptIDExclude[<?php echo $entity->getAccessEntityID()?>][]" value="<?php echo $ct->getThemeID()?>" <?php if (in_array($ct->getThemeID(), $assignment->getThemesAllowedArray()) || $assignment->getThemesAllowedPermission() == 'N') { ?> checked="checked" <?php } ?> /> <span><?php echo $ct->getThemeDisplayName()?></span></label></li>
		<?php } ?>
	</ul>
	</div>
</div>



<?php }

} ?>

<?php } else {  ?>
	<p><?php echo t('No users or groups selected.')?></p>
<?php } ?>

<script type="text/javascript">
$(function() {
	$("#ccm-tab-content-custom-options select").change(function() {
		if ($(this).val() == 'C') {
			$(this).parent().find('ul.theme-list').show();
		} else {
			$(this).parent().find('ul.theme-list').hide();
		}
	});
});
</script>