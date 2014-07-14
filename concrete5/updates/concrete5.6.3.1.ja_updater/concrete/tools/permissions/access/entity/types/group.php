<?php
defined('C5_EXECUTE') or die("Access Denied.");
if (Loader::helper('validation/token')->validate('process')) {
	
	$js = Loader::helper('json');
	$obj = new stdClass;
	$g = Group::getByID($_REQUEST['gID']);
	if (is_object($g)) {
		$pae = GroupPermissionAccessEntity::getOrCreate($g);			
		$obj->peID = $pae->getAccessEntityID();
		$obj->label = $pae->getAccessEntityLabel();
	}
	print $js->encode($obj);	
}
