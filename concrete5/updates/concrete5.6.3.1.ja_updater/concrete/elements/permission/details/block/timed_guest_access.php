<?php
defined('C5_EXECUTE') or die("Access Denied.");
$c = $b->getBlockCollectionObject();
$arHandle = $b->getAreaHandle();
$pk = PermissionKey::getByHandle('view_block');
$pk->setPermissionObject($b);
$list = $pk->getAccessListItems();
foreach($list as $pa) { 
	$pae = $pa->getAccessEntityObject(); 
	if ($pae->getAccessEntityTypeHandle() == 'group') {
		if ($pae->getGroupObject()->getGroupID() == GUEST_GROUP_ID) {
			$pd = $pa->getPermissionDurationObject();
			if (!is_object($pd)) {
				$pd = new PermissionDuration();
			}
		}
	}
}



?>
<div class="ccm-ui" id="ccm-permissions-access-entity-wrapper">
<form id="ccm-permissions-timed-guest-access-form" class="form-stacked" method="post" action="<?php echo REL_DIR_FILES_TOOLS_REQUIRED?>/permissions/categories/block">
<input type="hidden" name="task" value="set_timed_guest_access" />
<?php echo Loader::helper('validation/token')->output('set_timed_guest_access');?>
<input type="hidden" name="cID" value="<?php echo $c->getCollectionID()?>" />
<input type="hidden" name="bID" value="<?php echo $b->getBlockID()?>" />
<input type="hidden" name="arHandle" value="<?php echo $arHandle?>" />


<p><?php echo t('When should guests be able to view this block?')?></p>

<?php echo Loader::element('permission/duration', array('pd' => $pd)); ?>

<div class="dialog-buttons">
	<input type="button" onclick="jQuery.fn.dialog.closeTop()" value="<?php echo t('Cancel')?>" class="btn" />
	<input type="submit" onclick="$('#ccm-permissions-timed-guest-access-form').submit()" value="<?php echo t('Save')?>" class="btn primary ccm-button-right" />
</div>

</form>

</div>

<script type="text/javascript">
$(function() {
	$("#ccm-permissions-timed-guest-access-form").ajaxForm({
		beforeSubmit: function(r) {
			jQuery.fn.dialog.showLoader();
		},
		success: function(r) {
			ccm_mainNavDisableDirectExit();
			jQuery.fn.dialog.hideLoader();
			jQuery.fn.dialog.closeTop();
			ccmAlert.hud(ccmi18n.scheduleGuestAccessSuccess, 2000, 'success', ccmi18n.scheduleGuestAccess);
		}
	});
});
</script>