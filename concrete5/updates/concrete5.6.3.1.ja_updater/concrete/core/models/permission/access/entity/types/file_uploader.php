<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_FileUploaderPermissionAccessEntity extends PermissionAccessEntity {

	public function getAccessEntityUsers(PermissionAccess $pa) {
		$f = $pa->getPermissionObject();
		if (is_object($f) && ($f instanceof File)) {
			return UserInfo::getByID($f->getUserID());
		}
	}
	
	public function validate(PermissionAccess $pae) {
		if ($pae instanceof FileSetPermissionAccess) {
			return true;
		}
		if ($pae instanceof FilePermissionAccess) {
			$f = $pae->getPermissionObject();
		}
		if (is_object($f)) {
			$u = new User();
			return $u->getUserID() == $f->getUserID();
		}

		return false;
	}
	
	public function getAccessEntityTypeLinkHTML() {
		$html = '<a href="javascript:void(0)" onclick="ccm_choosePermissionAccessEntityFileUploader()">' . tc('PermissionAccessEntityTypeName', 'File Uploader') . '</a>';
		return $html;		
	}

	public static function getAccessEntitiesForUser($user) {
		$entities = array();
		$db = Loader::db();
		if ($user->isRegistered()) { 
			$pae = FileUploaderPermissionAccessEntity::getOrCreate();
			$r = $db->GetOne('select fID from Files where uID = ?', array($user->getUserID()));
			if ($r > 0) {
				$entities[] = $pae;
			}
		}
		return $entities;		
	}
	
	public static function getOrCreate() {
		$db = Loader::db();
		$petID = $db->GetOne('select petID from PermissionAccessEntityTypes where petHandle = \'file_uploader\'');
		$peID = $db->GetOne('select peID from PermissionAccessEntities where petID = ?', 
			array($petID));
		if (!$peID) { 
			$db->Execute("insert into PermissionAccessEntities (petID) values(?)", array($petID));
			$peID = $db->Insert_ID();
			Config::save('ACCESS_ENTITY_UPDATED', time());
		}
		return PermissionAccessEntity::getByID($peID);
	}

	public function load() {
		$db = Loader::db();
		$this->label = t('File Uploader');
	}

}