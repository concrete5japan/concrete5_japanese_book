<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Controller_AttributeType_Number extends AttributeTypeController  {

	protected $searchIndexFieldDefinition = 'N 14.4 DEFAULT 0 NULL';

	public function getValue() {
		$db = Loader::db();
		return (float) $db->GetOne("select value from atNumber where avID = ?", array($this->getAttributeValueID()));
	}
	
	public function searchForm($list) {
		$numFrom = $this->request('from');
		$numTo = $this->request('to');
		if ($numFrom) {
			$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $numFrom, '>=');
		}
		if ($numTo) {
			$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $numTo, '<=');
		}
		return $list;
	}
	
	public function search() {
		$f = Loader::helper('form');
		$html = $f->text($this->field('from'), $this->request('from'));
		$html .= ' ' . t('to') . ' ';
		$html .= $f->text($this->field('to'), $this->request('to'));
		print $html;
	}
	
	public function form() {
		if (is_object($this->attributeValue)) {
			$value = $this->getAttributeValue()->getValue();
		}
		print Loader::helper('form')->text($this->field('value'), $value, array('style' => 'width:80px'));
	}
	
	public function validateForm($p) {
		return $p['value'] != false;
	}

	// run when we call setAttribute(), instead of saving through the UI
	public function saveValue($value) {
		$db = Loader::db();
		$value = ($value == false || $value == '0') ? 0 : $value;
		$db->Replace('atNumber', array('avID' => $this->getAttributeValueID(), 'value' => $value), 'avID', true);
	}
	
	public function deleteKey() {
		$db = Loader::db();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atNumber where avID = ?', array($id));
		}
	}
	
	public function saveForm($data) {
		$db = Loader::db();
		$this->saveValue($data['value']);
	}
	
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atNumber where avID = ?', array($this->getAttributeValueID()));
	}
	
}
