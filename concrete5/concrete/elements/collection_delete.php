<?php	 defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="ccm-ui">
<?php	
$sh = Loader::helper('concrete/dashboard/sitemap');
$numChildren = $c->getNumChildren();
$u = new User();
?>

<script type="text/javascript">
$(function() {
	$("#ccmDeletePageForm").ajaxForm({
		type: 'POST',
		iframe: true,
		beforeSubmit: function() {
			jQuery.fn.dialog.showLoader();
		},
		success: function(r) {
			var r = eval('(' + r + ')');
			if (r != null && r.rel == 'SITEMAP') {
				jQuery.fn.dialog.hideLoader();
				jQuery.fn.dialog.closeTop();
				if (r.deferred) {
		 			ccmAlert.hud(ccmi18n_sitemap.deletePageSuccessDeferredMsg, 2000, 'delete_small', ccmi18n_sitemap.deletePage);
				} else {
		 			ccmAlert.hud(ccmi18n_sitemap.deletePageSuccessMsg, 2000, 'delete_small', ccmi18n_sitemap.deletePage);
					<?php	 if ($_REQUEST['display_mode'] == 'explore') { ?>
						ccmSitemapExploreNode('<?php	echo $_REQUEST['instance_id']?>', 'explore', '<?php	echo $_REQUEST['select_mode']?>', resp.cParentID);
					<?php	 } else { ?>
						deleteBranchFade(r.cID);
					<?php	 } ?>
				}
			} else {
				window.location.href = '<?php	echo DIR_REL?>/<?php	echo DISPATCHER_FILENAME?>?cID=' + r.refreshCID;
			}
		}
	});
});
</script>

<?php	 if ($c->getCollectionID() == 1) {  ?>
	<div class="error alert-message"><?php	echo t('You may not delete the home page.');?></div>
	<div class="dialog-buttons"><input type="button" class="btn" value="<?php	echo t('Cancel')?>" onclick="jQuery.fn.dialog.closeTop()" /></div>
<?php	 }  else if ($numChildren > 0 && !$u->isSuperUser()) { ?>
		<div class="error alert-message"><?php	echo t('Before you can delete this page, you must delete all of its child pages.')?></div>
		<div class="dialog-buttons"><input type="button" class="btn" value="<?php	echo t('Cancel')?>" onclick="jQuery.fn.dialog.closeTop()" /></div>
		
	<?php	 } else { 
		?>
		
		<div class="ccm-buttons">

		<form method="post" id="ccmDeletePageForm" action="<?php	echo $c->getCollectionAction()?>">	
			<input type="hidden" name="rel" value="<?php	echo $_REQUEST['rel']?>" />

			<div class="dialog-buttons"><input type="button" class="btn" value="<?php	echo t('Cancel')?>" onclick="jQuery.fn.dialog.closeTop()" />
			<a href="javascript:void(0)" onclick="$('#ccmDeletePageForm').submit()" class="ccm-button-right btn error"><span><?php	echo t('Delete')?></span></a>
			</div>
		<h3><?php	echo t('Are you sure you wish to delete this page?')?></h3>
		<?php	 if ($u->isSuperUser() && $numChildren > 0) { ?>
			<h4><?php	echo t('This will remove %s child page(s).', $numChildren)?></h4>
		<?php	 } ?>
		
		<?php	 if (ENABLE_TRASH_CAN) { ?>
			<p><?php	echo t('Deleted pages are moved to the trash can in the sitemap.')?></p>
		<?php	 } else { ?>
			<p><?php	echo t('This cannot be undone.')?></p>
		<?php	 } ?>
		
			<input type="hidden" name="cID" value="<?php	echo $c->getCollectionID()?>">
			<input type="hidden" name="ctask" value="delete">
			<input type="hidden" name="processCollection" value="1" />
			<input type="hidden" name="display_mode" value="<?php	echo $_REQUEST['display_mode']?>" />
			<input type="hidden" name="instance_id" value="<?php	echo $_REQUEST['instance_id']?>" />
			<input type="hidden" name="select_mode" value="<?php	echo $_REQUEST['select_mode']?>" />
		</form>
		</div>
		
	<?php	 }
?>