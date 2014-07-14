<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_FileSetPermissionAssignment extends PermissionAssignment {
	
	public function setPermissionObject(FileSet $fs) {
		$this->permissionObject = $fs;
		
		if ($fs->overrideGlobalPermissions()) {
			$this->permissionObjectToCheck = $fs;
		} else {
			$fs = FileSet::getGlobal();
			$this->permissionObjectToCheck = $fs;
		}
	}

	public function getPermissionAccessObject() {
		$db = Loader::db();
 		$r = $db->GetOne('select paID from FileSetPermissionAssignments where fsID = ? and pkID = ?', array(
 			$this->permissionObjectToCheck->getFileSetID(), $this->pk->getPermissionKeyID()
 		));
 		return PermissionAccess::getByID($r, $this->pk);
	}

	public function clearPermissionAssignment() {
		$db = Loader::db();
		$db->Execute('update FileSetPermissionAssignments set paID = 0 where pkID = ? and fsID = ?', array($this->pk->getPermissionKeyID(), $this->permissionObject->getFileSetID()));
	}
	
	public function assignPermissionAccess(PermissionAccess $pa) {
		$db = Loader::db();
		$db->Replace('FileSetPermissionAssignments', array('fsID' => $this->getPermissionObject()->getFileSetID(), 'paID' => $pa->getPermissionAccessID(), 'pkID' => $this->pk->getPermissionKeyID()), array('fsID', 'pkID'), true);
		$pa->markAsInUse();
	}
	
	public function getPermissionKeyToolsURL($task = false) {
		return parent::getPermissionKeyToolsURL($task) . '&fsID=' . $this->getPermissionObject()->getFileSetID();
	}
	
}