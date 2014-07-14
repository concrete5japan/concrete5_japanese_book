<?php
defined('C5_EXECUTE') or die("Access Denied.");
$p = Page::getByPath('/dashboard/workflow/list');
$cp = new Permissions($p);
$json = Loader::helper('json');
$workflow = Workflow::getByID($_REQUEST['wfID']);

if ($cp->canViewPage()) { 

	if ($_REQUEST['task'] == 'add_access_entity' && Loader::helper("validation/token")->validate('add_access_entity')) {
		$pk = BasicWorkflowPermissionKey::getByID($_REQUEST['pkID']);
		$pk->setPermissionObject($workflow);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		$pe = PermissionAccessEntity::getByID($_REQUEST['peID']);
		$pd = PermissionDuration::getByID($_REQUEST['pdID']);
		$pa->addListItem($pe, $pd, $_REQUEST['accessType']);
	}

	if ($_REQUEST['task'] == 'remove_access_entity' && Loader::helper("validation/token")->validate('remove_access_entity')) {
		$pk = BasicWorkflowPermissionKey::getByID($_REQUEST['pkID']);
		$pk->setPermissionObject($workflow);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		$pe = PermissionAccessEntity::getByID($_REQUEST['peID']);
		$pa->removeListItem($pe);
	}

	if ($_REQUEST['task'] == 'save_permission' && Loader::helper("validation/token")->validate('save_permission')) {
		$pk = BasicWorkflowPermissionKey::getByID($_REQUEST['pkID']);
		$pk->setPermissionObject($workflow);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		$pa->save($_POST);
	}

	if ($_REQUEST['task'] == 'display_access_cell' && Loader::helper("validation/token")->validate('display_access_cell')) {
		$pk = PermissionKey::getByID($_REQUEST['pkID']);
		$pk->setPermissionObject($workflow);
		$pa = PermissionAccess::getByID($_REQUEST['paID'], $pk);
		Loader::element('permission/labels', array('pk' => $pk, 'pa' => $pa));
	}

}

