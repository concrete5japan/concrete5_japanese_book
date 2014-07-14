<?php
defined('C5_EXECUTE') or die("Access Denied.");
if (Loader::helper('validation/token')->validate('process')) {
	
	$js = Loader::helper('json');
	$obj = new stdClass;
	$gs = GroupSet::getByID($_REQUEST['gsID']);
	if (is_object($gs)) {
		$pae = GroupSetPermissionAccessEntity::getOrCreate($gs);			
		$obj->peID = $pae->getAccessEntityID();
		$obj->label = $pae->getAccessEntityLabel();
	}
	print $js->encode($obj);	
}
