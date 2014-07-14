<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_EditPagePropertiesPagePermissionAccess extends PagePermissionAccess {

	public function duplicate($newPA = false) {
		$newPA = parent::duplicate($newPA);
		$db = Loader::db();
		$r = $db->Execute('select * from PagePermissionPropertyAccessList where paID = ?', array($this->getPermissionAccessID()));
		while ($row = $r->FetchRow()) {
			$v = array($row['peID'], $newPA->getPermissionAccessID(), 
			$row['attributePermission'],
			$row['name'],
			$row['publicDateTime'],
			$row['uID'],
			$row['description'],
			$row['paths']
			);
			$db->Execute('insert into PagePermissionPropertyAccessList (peID, paID, attributePermission, name, publicDateTime, uID, description, paths) values (?, ?, ?, ?, ?, ?, ?, ?)', $v);
		}
		$r = $db->Execute('select * from PagePermissionPropertyAttributeAccessListCustom where paID = ?', array($this->getPermissionAccessID()));
		while ($row = $r->FetchRow()) {
			$v = array($row['peID'], $newPA->getPermissionAccessID(), $row['akID']);
			$db->Execute('insert into PagePermissionPropertyAttributeAccessListCustom  (peID, paID, akID) values (?, ?, ?)', $v);
		}
		return $newPA;
	}

	public function save($args) {
		parent::save();
		$db = Loader::db();
		$db->Execute('delete from PagePermissionPropertyAccessList where paID = ?', array($this->getPermissionAccessID()));
		$db->Execute('delete from PagePermissionPropertyAttributeAccessListCustom where paID = ?', array($this->getPermissionAccessID()));
		if (is_array($args['propertiesIncluded'])) { 
			foreach($args['propertiesIncluded'] as $peID => $attributePermission) {
				$allowEditName = 0;
				$allowEditDateTime = 0;
				$allowEditUID = 0;
				$allowEditDescription = 0;
				$allowEditPaths = 0;
				if (!empty($args['allowEditName'][$peID])) {
					$allowEditName = $args['allowEditName'][$peID];
				}
				if (!empty($args['allowEditDateTime'][$peID])) {
					$allowEditDateTime = $args['allowEditDateTime'][$peID];
				}
				if (!empty($args['allowEditUID'][$peID])) {
					$allowEditUID = $args['allowEditUID'][$peID];
				}
				if (!empty($args['allowEditDescription'][$peID])) {
					$allowEditDescription = $args['allowEditDescription'][$peID];
				}
				if (!empty($args['allowEditPaths'][$peID])) {
					$allowEditPaths = $args['allowEditPaths'][$peID];
				}
				$v = array($this->getPermissionAccessID(), $peID, $attributePermission, $allowEditName, $allowEditDateTime, $allowEditUID, $allowEditDescription, $allowEditPaths);
				$db->Execute('insert into PagePermissionPropertyAccessList (paID, peID, attributePermission, name, publicDateTime, uID, description, paths) values (?, ?, ?, ?, ?, ?, ?, ?)', $v);
			}
		}
		
		if (is_array($args['propertiesExcluded'])) { 
			foreach($args['propertiesExcluded'] as $peID => $attributePermission) {
				$allowEditNameExcluded = 0;
				$allowEditDateTimeExcluded = 0;
				$allowEditUIDExcluded = 0;
				$allowEditDescriptionExcluded = 0;
				$allowEditPathsExcluded = 0;
				if (!empty($args['allowEditNameExcluded'][$peID])) {
					$allowEditNameExcluded = $args['allowEditNameExcluded'][$peID];
				}
				if (!empty($args['allowEditDateTimeExcluded'][$peID])) {
					$allowEditDateTimeExcluded = $args['allowEditDateTimeExcluded'][$peID];
				}
				if (!empty($args['allowEditUIDExcluded'][$peID])) {
					$allowEditUIDExcluded = $args['allowEditUIDExcluded'][$peID];
				}
				if (!empty($args['allowEditDescriptionExcluded'][$peID])) {
					$allowEditDescriptionExcluded = $args['allowEditDescriptionExcluded'][$peID];
				}
				if (!empty($args['allowEditPathsExcluded'][$peID])) {
					$allowEditPathsExcluded = $args['allowEditPathsExcluded'][$peID];
				}
				$v = array($this->getPermissionAccessID(), $peID, $attributePermission, $allowEditNameExcluded, $allowEditDateTimeExcluded, $allowEditUIDExcluded, $allowEditDescriptionExcluded, $allowEditPathsExcluded);
				$db->Execute('insert into PagePermissionPropertyAccessList (paID, peID, attributePermission, name, publicDateTime, uID, description, paths) values (?, ?, ?, ?, ?, ?, ?, ?)', $v);
			}
		}

		if (is_array($args['akIDInclude'])) { 
			foreach($args['akIDInclude'] as $peID => $akIDs) {
				foreach($akIDs as $akID) { 
					$v = array($this->getPermissionAccessID(), $peID, $akID);
					$db->Execute('insert into PagePermissionPropertyAttributeAccessListCustom (paID, peID, akID) values (?, ?, ?)', $v);
				}
			}
		}

		if (is_array($args['akIDExclude'])) { 
			foreach($args['akIDExclude'] as $peID => $akIDs) {
				foreach($akIDs as $akID) { 
					$v = array($this->getPermissionAccessID(), $peID, $akID);
					$db->Execute('insert into PagePermissionPropertyAttributeAccessListCustom (paID, peID, akID) values (?, ?, ?)', $v);
				}
			}
		}

	}
	
	public function getAccessListItems($accessType = PagePermissionKey::ACCESS_TYPE_INCLUDE, $filterEntities = array()) {
		$db = Loader::db();
		$list = parent::getAccessListItems($accessType, $filterEntities);
		$list = PermissionDuration::filterByActive($list);
		foreach($list as $l) {
			$pe = $l->getAccessEntityObject();
			$prow = $db->GetRow('select attributePermission, name, publicDateTime, uID, description, paths from PagePermissionPropertyAccessList where peID = ? and paID = ?', array($pe->getAccessEntityID(), $l->getPermissionAccessID()));
			if (is_array($prow) && $prow['attributePermission']) { 
				$l->setAttributesAllowedPermission($prow['attributePermission']);
				$l->setAllowEditName($prow['name']);
				$l->setAllowEditDateTime($prow['publicDateTime']);
				$l->setAllowEditUserID($prow['uID']);
				$l->setAllowEditDescription($prow['description']);
				$l->setAllowEditPaths($prow['paths']);
				$attributePermission = $prow['attributePermission'];
			} else if ($l->getAccessType() == PagePermissionKey::ACCESS_TYPE_INCLUDE) {
				$l->setAttributesAllowedPermission('A');
				$l->setAllowEditName(1);
				$l->setAllowEditDateTime(1);
				$l->setAllowEditUserID(1);
				$l->setAllowEditDescription(1);
				$l->setAllowEditPaths(1);
			} else {
				$l->setAttributesAllowedPermission('N');
				$l->setAllowEditName(0);
				$l->setAllowEditDateTime(0);
				$l->setAllowEditUserID(0);
				$l->setAllowEditDescription(0);
				$l->setAllowEditPaths(0);
			}
			if ($attributePermission == 'C') { 
				$akIDs = $db->GetCol('select akID from PagePermissionPropertyAttributeAccessListCustom where peID = ? and paID = ?', array($pe->getAccessEntityID(), $l->getPermissionAccessID()));
				$l->setAttributesAllowedArray($akIDs);
			}
		}
		return $list;
	}
	
}