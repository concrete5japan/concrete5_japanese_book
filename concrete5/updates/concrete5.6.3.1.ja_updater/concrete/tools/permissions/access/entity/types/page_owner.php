<?php
defined('C5_EXECUTE') or die("Access Denied.");
if (Loader::helper('validation/token')->validate('process')) {
	
	$js = Loader::helper('json');
	$obj = new stdClass;
	$pae = PageOwnerPermissionAccessEntity::getOrCreate();
	$obj->peID = $pae->getAccessEntityID();
	$obj->label = $pae->getAccessEntityLabel();
	print $js->encode($obj);	

}