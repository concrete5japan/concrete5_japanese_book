<?php

defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_Stack extends Page {

	const ST_TYPE_USER_ADDED = 0;
	const ST_TYPE_GLOBAL_AREA = 20;
	
	public function getStackName() {
		$db = Loader::db();
		return $db->GetOne('select stName from Stacks where cID = ?', array($this->getCollectionID()));
	}
	
	public function getStackType() {
		$db = Loader::db();
		return $db->GetOne('select stType from Stacks where cID = ?', array($this->getCollectionID()));
	}
	
	public function getStackTypeExportText() {
		switch($this->getStackType()) {
			case self::ST_TYPE_GLOBAL_AREA:
				return 'global_area';
				break;
			default: 
				return false;
				break;
		}
	}
	
	public static function mapImportTextToType($type) {
		switch($type) {
			case 'global_area':
				return self::ST_TYPE_GLOBAL_AREA;
				break;
			default:
				return self::ST_TYPE_USER_ADDED;
				break;
		}		
	}
	
	protected static function isValidStack($stack) {
		return $stack->getCollectionTypeHandle() == STACKS_PAGE_TYPE;
	}

	public static function addStack($stackName, $type = self::ST_TYPE_USER_ADDED) {
		$ct = new CollectionType();
		$data = array();

		$parent = Page::getByPath(STACKS_PAGE_PATH);
		$data = array();
		$data['name'] = $stackName;
		if (!$stackName) {
			$data['name'] = t('No Name');
		}
		$pagetype = CollectionType::getByHandle(STACKS_PAGE_TYPE);
		$page = $parent->add($pagetype, $data);	

		// we have to do this because we need the area to exist before we try and add something to it.
		$a = Area::getOrCreate($page, STACKS_AREA_NAME);
		
		// finally we add the row to the stacks table
		$db = Loader::db();
		$stackCID = $page->getCollectionID();
		$v = array($stackName, $stackCID, $type);
		$db->Execute('insert into Stacks (stName, cID, stType) values (?, ?, ?)', $v);
		
		//Return the new stack
		return self::getByID($stackCID);
	}
	
	public function duplicate($nc = null, $preserveUserID = false) {
		if (!is_object($nc)) {
			// There is not necessarily need to provide the parent
			// page for the duplicate since for stacks, that is 
			// always the same page.
			$nc = Page::getByPath(STACKS_PAGE_PATH);
		}
		$page = parent::duplicate($nc, $preserveUserID);
		
		// we have to do this because we need the area to exist before we try and add something to it.
		$a = Area::getOrCreate($page, STACKS_AREA_NAME);
		
		$db = Loader::db();
		$v = array($page->getCollectionName(), $page->getCollectionID(), $this->getStackType());
		$db->Execute('insert into Stacks (stName, cID, stType) values (?, ?, ?)', $v);
		
		// Make sure we return an up-to-date record
		return Stack::getByID($page->getCollectionID());
	}
	
	public static function getByName($stackName, $cvID = 'RECENT') {
		$cID = CacheLocal::getEntry('stack_by_name', $stackName);
		if (!$cID) {
			$db = Loader::db();
			$cID = $db->GetOne('select cID from Stacks where stName = ?', array($stackName));
			CacheLocal::set('stack_by_name', $stackName, $cID);
		}
		
		if ($cID) {
			return self::getByID($cID, $cvID);
		}
	}
	
	public function update($data) {
		if (isset($data['stackName'])) {
			$txt = Loader::helper('text');
			$data['cName'] = $data['stackName'];
			$data['cHandle'] = str_replace('-', PAGE_PATH_SEPARATOR, $txt->urlify($data['stackName']));
		}
		parent::update($data);
		
		if (isset($data['stackName'])) {
			// Make sure the stack path is always up-to-date after a name change
			$this->rescanCollectionPath();
			
			$db = Loader::db();
			$stackName = $data['stackName'];
			$db->Execute('update Stacks set stName = ? WHERE cID = ?',array($stackName, $this->getCollectionID()));
		}
	}
	
	public function delete() {
		if ($this->getStackType() == self::ST_TYPE_GLOBAL_AREA) {
			GlobalArea::deleteByName($this->getStackName());
		}

		parent::delete();
		$db = Loader::db();
		$db->Execute('delete from Stacks where cID = ?', array($this->getCollectionID()));
	}

	public function display() {
		$ax = Area::get($this, STACKS_AREA_NAME);
		$ax->display($this);
	}
	
	public static function getOrCreateGlobalArea($stackName) {
		$stack = self::getByName($stackName);
		if (!$stack) {		
			$stack = self::addStack($stackName, self::ST_TYPE_GLOBAL_AREA);
		}
		return $stack;
	}
	
	public static function getByID($cID, $cvID = 'RECENT') {
		$db = Loader::db();
		$c = parent::getByID($cID, $cvID, 'Stack');

		if (self::isValidStack($c)) {
			return $c;
		}
		return false;
	}

	public function export($pageNode) {

		$p = $pageNode->addChild('stack');
		$p->addAttribute('name', Loader::helper('text')->entities($this->getCollectionName()));
		if ($this->getStackTypeExportText()) {
			$p->addAttribute('type', $this->getStackTypeExportText());
		}
		
		$db = Loader::db();
		$r = $db->Execute('select arHandle from Areas where cID = ?', array($this->getCollectionID()));
		while ($row = $r->FetchRow()) {
			$ax = Area::get($this, $row['arHandle']);
			$ax->export($p, $this);
		}
	}

}
