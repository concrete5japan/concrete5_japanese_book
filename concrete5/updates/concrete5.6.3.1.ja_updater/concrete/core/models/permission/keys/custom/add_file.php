<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_AddFileFileSetPermissionKey extends FileSetPermissionKey  {

	public function getAllowedFileExtensions() {
		$u = new User();

		$extensions = array();
		if ($u->isSuperUser()) {
			$extensions = Loader::helper('concrete/file')->getAllowedFileExtensions();
			return $extensions;
		}

		$pae = $this->getPermissionAccessObject();
		if (!is_object($pae)) {
			return array();
		}
	
		$accessEntities = $u->getUserAccessEntityObjects();
		$accessEntities = $pae->validateAndFilterAccessEntities($accessEntities);
		$list = $this->getAccessListItems(FileSetPermissionKey::ACCESS_TYPE_ALL, $accessEntities);
		$list = PermissionDuration::filterByActive($list);

		foreach($list as $l) {
			if ($l->getFileTypesAllowedPermission() == 'N') {
				$extensions = array();
			}
			if ($l->getFileTypesAllowedPermission() == 'C') {
				$extensions = array_unique(array_merge($extensions, $l->getFileTypesAllowedArray()));
			}
			if ($l->getFileTypesAllowedPermission() == 'A') {
				$extensions = Loader::helper('concrete/file')->getAllowedFileExtensions();
			}
		}
		
		return $extensions;
	}
	
	public function validate($extension = false) {
		$extensions = $this->getAllowedFileExtensions();
		if ($ext != false) {
			return in_array($extension, $extensions);
		} else {
			return count($extensions) > 0;
		}
	}
	

}