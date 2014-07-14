<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_PageContentPermissionTimedAssignment {
	
	protected $permissionKey;
	protected $durationObject;
	protected $accessEntity;
	
	public function getPermissionKeyObject() {return $this->permissionKey;}
	public function getDurationObject() {return $this->durationObject;}
	public function getAccessEntityObject() {return $this->accessEntity;}
	public function setPermissionKeyObject($pk) {$this->permissionKey = $pk;}
	public function setDurationObject($do) {$this->durationObject = $do;}
	public function setAccessEntityObject($accessEntity) {$this->accessEntity = $accessEntity;}
	
}