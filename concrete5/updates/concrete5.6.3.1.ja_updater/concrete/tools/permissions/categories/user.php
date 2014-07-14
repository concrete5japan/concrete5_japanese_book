<?php
defined('C5_EXECUTE') or die("Access Denied.");
$p = new Permissions();
if ($p->canAccessTaskPermissions()) { 

	if ($_REQUEST['task'] == 'add_access_entity' && Loader::helper("validation/token")->validate('add_access_entity')) {
		$pk = UserPermissionKey::getByID($_REQUEST['pkID']);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		$pe = PermissionAccessEntity::getByID($_REQUEST['peID']);
		$pd = PermissionDuration::getByID($_REQUEST['pdID']);
		$pa->addListItem($pe, $pd, $_REQUEST['accessType']);
	}

	if ($_REQUEST['task'] == 'remove_access_entity' && Loader::helper("validation/token")->validate('remove_access_entity')) {
		$pk = UserPermissionKey::getByID($_REQUEST['pkID']);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		$pe = PermissionAccessEntity::getByID($_REQUEST['peID']);
		$pa->removeListItem($pe);
	}

	if ($_REQUEST['task'] == 'save_permission' && Loader::helper("validation/token")->validate('save_permission')) {
		$pk = UserPermissionKey::getByID($_REQUEST['pkID']);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		$pa->save($_POST);
	}

	if ($_REQUEST['task'] == 'display_access_cell' && Loader::helper("validation/token")->validate('display_access_cell')) {
		$pk = UserPermissionKey::getByID($_REQUEST['pkID']);
		$pa = UserPermissionAccess::getByID($_REQUEST['paID'], $pk);
		Loader::element('permission/labels', array('pk' => $pk, 'pa' => $pa));
	}
	
	if ($_REQUEST['task'] == 'save_workflows' && Loader::helper("validation/token")->validate('save_workflows')) {
		$pk = UserPermissionKey::getByID($_REQUEST['pkID']);
		$pk->clearWorkflows();
		foreach($_POST['wfID'] as $wfID) {
			$wf = Workflow::getByID($wfID);
			if (is_object($wf)) {
				$pk->attachWorkflow($wf);
			}
		}
	}
	
	
}