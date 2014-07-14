<?php
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Model_WorkflowProgressCategory extends Object {

	public static function getByID($wpCategoryID) {
		$db = Loader::db();
		$row = $db->GetRow('select wpCategoryID, wpCategoryHandle, pkgID from WorkflowProgressCategories where wpCategoryID = ?', array($wpCategoryID));
		if (isset($row['wpCategoryID'])) {
			$pkc = new WorkflowProgressCategory();
			$pkc->setPropertiesFromArray($row);
			return $pkc;
		}
	}
	
	public static function getByHandle($wpCategoryHandle) {
		$db = Loader::db();
		$row = $db->GetRow('select wpCategoryID, wpCategoryHandle, pkgID from WorkflowProgressCategories where wpCategoryHandle = ?', array($wpCategoryHandle));
		if (isset($row['wpCategoryID'])) {
			$pkc = new WorkflowProgressCategory();
			$pkc->setPropertiesFromArray($row);
			return $pkc;
		}
	}
	
	public static function exportList($xml) {
		$attribs = self::getList();		
		$axml = $xml->addChild('workflowprogresscategories');
		foreach($attribs as $pkc) {
			$acat = $axml->addChild('category');
			$acat->addAttribute('handle', $pkc->getWorkflowProgressCategoryHandle());
			$acat->addAttribute('package', $pkc->getPackageHandle());
		}		
	}
	
	public static function getListByPackage($pkg) {
		$db = Loader::db();
		$list = array();
		$r = $db->Execute('select wpCategoryID from WorkflowProgressCategories where pkgID = ? order by wpCategoryID asc', array($pkg->getPackageID()));
		while ($row = $r->FetchRow()) {
			$list[] = WorkflowProgressCategory::getByID($row['wpCategoryID']);
		}
		$r->Close();
		return $list;
	}	

	public function getWorkflowProgressCategoryID() {return $this->wpCategoryID;}
	public function getWorkflowProgressCategoryHandle() {return $this->wpCategoryHandle;}
	public function getPackageID() {return $this->pkgID;}
	public function getPackageHandle() {return PackageList::getHandle($this->pkgID);}

	public function __call($method, $arguments) {
		$class = Loader::helper('text')->camelcase($this->wpCategoryHandle) . 'WorkflowProgress';
		return call_user_func_array(array($class, $method), $arguments);
	}
	
	public function delete() {
		$db = Loader::db();
		$db->Execute('delete from WorkflowProgressCategories where wpCategoryID = ?', array($this->wpCategoryID));
	}
	
	public static function getList() {
		$db = Loader::db();
		$cats = array();
		$r = $db->Execute('select wpCategoryID from WorkflowProgressCategories order by wpCategoryID asc');
		while ($row = $r->FetchRow()) {
			$cats[] = WorkflowProgressCategory::getByID($row['wpCategoryID']);
		}
		return $cats;
	}
	
	public static function add($wpCategoryHandle, $pkg = false) {
		$db = Loader::db();
		if (is_object($pkg)) {
			$pkgID = $pkg->getPackageID();
		}
		$db->Execute('insert into WorkflowProgressCategories (wpCategoryHandle, pkgID) values (?, ?)', array($wpCategoryHandle, $pkgID));
		$id = $db->Insert_ID();
		
		return WorkflowProgressCategory::getByID($id);
	}
	


}
