<?php defined('C5_EXECUTE') or die("Access Denied.");
$searchInstance = Loader::helper('text')->entities($_REQUEST['searchInstance']);
if(!strlen($searchInstance)) {
	$searchInstance = 'user';
}

$sk = PermissionKey::getByHandle('access_user_search');
$ek = PermissionKey::getByHandle('activate_user');

$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');
$tp = new TaskPermission();
if (!$tp->canActivateUser()) { 
	die(t("Access Denied."));
}

$users = array();
if (is_array($_REQUEST['uID'])) {
	foreach($_REQUEST['uID'] as $uID) {
		$ui = UserInfo::getByID($uID);
		$users[] = $ui;
	}
}

foreach($users as $ui) {
	if (!$sk->validate($ui)) { 
		die(t("Access Denied."));
	}
}

if ($_POST['task'] == 'activate') {
	foreach($users as $ui) {
		if(!$ui->isActive()) {
			$ui->activate();
		}
	}
	echo Loader::helper('json')->encode(array('error'=>false));
	exit;
} 

if (!isset($_REQUEST['reload'])) { ?>
	<div id="ccm-user-bulk-activate-wrapper">
<?php } ?>

	<div id="ccm-user-activate" class="ccm-ui">
		<form method="post" id="ccm-user-bulk-activate" action="<?php echo REL_DIR_FILES_TOOLS_REQUIRED ?>/users/bulk_activate">
			<?php
			echo $form->hidden('task','activate');
			foreach($users as $ui) {
				echo $form->hidden('uID[]' , $ui->getUserID());
			}
			?>
			<?php echo t('Are you sure you would like to activate the following users?');?><br/><br/>
			<?php Loader::element('users/confirm_list',array('users'=>$users)); ?>
		</form>	
	</div>
	<div class="dialog-buttons">
		<?php echo $ih->button_js(t('Cancel'), 'jQuery.fn.dialog.closeTop()', 'left', 'btn')?>	
		<?php echo $ih->button_js(t('Activate'), 'ccm_userBulkActivate()', 'right', 'btn primary')?>
	</div>
<?php
if (!isset($_REQUEST['reload'])) { ?>
</div>
<?php } ?>

<script type="text/javascript">
ccm_userBulkActivate = function() { 
	jQuery.fn.dialog.showLoader();
	$("#ccm-user-bulk-activate").ajaxSubmit(function(resp) {
		jQuery.fn.dialog.closeTop();
		jQuery.fn.dialog.hideLoader();
		ccm_deactivateSearchResults('<?php echo $searchInstance?>');
		ccmAlert.hud(ccmi18n.saveUserSettingsMsg, 2000, 'success', ccmi18n.user_activate);
		$("#ccm-<?php echo $searchInstance?>-advanced-search").ajaxSubmit(function(r) {
		       ccm_parseAdvancedSearchResponse(r, '<?php echo $searchInstance?>');
		});
	});
};
</script>
