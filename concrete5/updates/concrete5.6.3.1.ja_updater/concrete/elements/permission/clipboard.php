<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
	$set = PermissionSet::getSavedPermissionSetFromSession();
?>
<button class="btn btn-mini" type="button" id="ccm-permissions-list-copy-permissions"><?php echo t('Copy')?></button>
<?php if (is_object($set) && $set->getPermissionKeyCategory() == $pkCategory->getPermissionKeyCategoryHandle()) { ?>
	<button class="btn btn-mini" type="button" id="ccm-permissions-list-paste-permissions"><?php echo t('Paste')?></button>
<?php } ?>
<input type="hidden" name="pkCategoryHandle" value="<?php echo $pkCategory->getPermissionKeyCategoryHandle()?>" />
<script type="text/javascript">

$(function() {
	$('#ccm-permissions-list-copy-permissions').click(function() {
		var frm = $('#ccm-permission-list-form');
		jQuery.fn.dialog.showLoader();
		var data = '';
		frm.find('.ccm-permission-access-line input[type=hidden]').each(function() {
			data += $(this).attr('name') + '=' + $(this).val() + '&';
		});
		data += 'pkCategoryHandle=' + frm.find('input[name=pkCategoryHandle]').val();
		$.ajax({
			dataType: 'json',
			type: 'post',
			data: data,
			url: '<?php echo REL_DIR_FILES_TOOLS_REQUIRED?>/permissions/set?task=copy_permission_set&<?php echo Loader::helper('validation/token')->getParameter('copy_permission_set')?>',
			success: function(r) {
				jQuery.fn.dialog.hideLoader();
			}				
		})
	})
	
	$('#ccm-permissions-list-paste-permissions').click(function() {
		jQuery.fn.dialog.showLoader();
		var frm = $('#ccm-permission-list-form');
		var data = 'pkCategoryHandle=' + frm.find('input[name=pkCategoryHandle]').val();
		$.ajax({
			dataType: 'json',
			type: 'post',
			data: data,
			url: '<?php echo REL_DIR_FILES_TOOLS_REQUIRED?>/permissions/set?task=paste_permission_set&<?php echo Loader::helper('validation/token')->getParameter('paste_permission_set')?>',
			success: function(r) {
				jQuery.fn.dialog.hideLoader();
				for (i = 0; i < r.length; i++) {
					var cell = r[i];
					$('#ccm-permission-grid-cell-' + cell.pkID).html(cell.html);
					$('#ccm-permission-grid-name-' + cell.pkID + ' a').attr('data-paID', cell.paID);		
				}

			}				
		})
	})

})
</script>