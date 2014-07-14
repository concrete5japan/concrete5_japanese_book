<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Controller_AttributeType_Address extends AttributeTypeController  {

	public $helpers = array('form');
	
	public function searchKeywords($keywords) {
		$db = Loader::db();
		$qkeywords = $db->quote('%' . $keywords . '%');
		// todo make this less hardcoded (with ak_ in front of it)
		$str = '(ak_' . $this->attributeKey->getAttributeKeyHandle() . '_address1 like '.$qkeywords.' or ';
		$str .= 'ak_' . $this->attributeKey->getAttributeKeyHandle() . '_address2 like '.$qkeywords.' or ';
		$str .= 'ak_' . $this->attributeKey->getAttributeKeyHandle() . '_city like '.$qkeywords.' or ';
		$str .= 'ak_' . $this->attributeKey->getAttributeKeyHandle() . '_state_province like '.$qkeywords.' or ';
		$str .= 'ak_' . $this->attributeKey->getAttributeKeyHandle() . '_postal_code like '.$qkeywords.' or ';
		$str .= 'ak_' . $this->attributeKey->getAttributeKeyHandle() . '_country like '.$qkeywords.' )';
		return $str;
	}
	
	public function searchForm($list) {
		$address1 = $this->request('address1');
		$address2 = $this->request('address2');
		$city = $this->request('city');
		$state_province = $this->request('state_province');
		$postal_code = $this->request('postal_code');
		$country = $this->request('country');
		if ($address1) {
			$list->filterByAttribute(array('address1' => $this->attributeKey->getAttributeKeyHandle()), '%' . $address1 . '%', 'like');
		}
		if ($address2) {
			$list->filterByAttribute(array('address2' => $this->attributeKey->getAttributeKeyHandle()), '%' . $address2 . '%', 'like');
		}
		if ($city) {
			$list->filterByAttribute(array('city' => $this->attributeKey->getAttributeKeyHandle()), '%' . $city . '%', 'like');
		}
		if ($state_province) {
			$list->filterByAttribute(array('state_province' => $this->attributeKey->getAttributeKeyHandle()), $state_province);
		}
		if ($postal_code) {
			$list->filterByAttribute(array('postal_code' => $this->attributeKey->getAttributeKeyHandle()), '%' . $postal_code . '%', 'like');
		}
		if ($country) {
			$list->filterByAttribute(array('country' => $this->attributeKey->getAttributeKeyHandle()), $country);
		}
		return $list;
	}

	protected $searchIndexFieldDefinition = array(
		'address1' => 'C 255 NULL',
		'address2' => 'C 255 NULL',
		'city' => 'C 255 NULL',
		'state_province' => 'C 255 NULL',
		'country' => 'C 255 NULL',
		'postal_code' => 'C 255 NULL'
	);
	
	public function search() {
		$this->load();
		print $this->form();
		$v = $this->getView();
		$this->set('search', true);
		$v->render('form');
	}

	public function saveForm($data) {
		$this->saveValue($data);
	}

	public function validateForm($data) {
		return ($data['address1'] != '' && $data['city'] != '' && $data['state_province'] != '' && $data['country'] != '' && $data['postal_code'] != '');	
	}	
	
	public function getSearchIndexValue() {
		$v = $this->getValue();
		$args = array();
		$args['address1'] = $v->getAddress1();
		$args['address2'] = $v->getAddress2();
		$args['city'] = $v->getCity();
		$args['state_province'] = $v->getStateProvince();
		$args['country'] = $v->getCountry();
		$args['postal_code'] = $v->getPostalCode();
		return $args;
	}
	
	public function deleteKey() {
		$db = Loader::db();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atAddress where avID = ?', array($id));
		}
	}
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atAddress where avID = ?', array($this->getAttributeValueID()));
	}
	
	public function saveValue($data) {
		$db = Loader::db();
		if ($data instanceof AddressAttributeTypeValue) {
			$data = (array) $data;
		}
		extract($data);
		$db->Replace('atAddress', array('avID' => $this->getAttributeValueID(),
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state_province' => $state_province,
			'country' => $country,
			'postal_code' => $postal_code
			),
			'avID', true
		);
	}

	public function getValue() {
		$val = AddressAttributeTypeValue::getByID($this->getAttributeValueID());		
		return $val;
	}
	
	public function getDisplayValue() {
		$v = Loader::helper('text')->entities($this->getValue());
		$ret = nl2br($v);
		return $ret;
	}
	
	public function action_load_provinces_js() {
		$h = Loader::helper('lists/states_provinces');
		print "var ccm_attributeTypeAddressStatesTextList = '\\\n";
		$all = $h->getAll();
		foreach($all as $country => $countries) {
			foreach($countries as $value => $text) {
				print addslashes($country) . ':' . addslashes($value) . ':' . addslashes($text) . "|\\\n";
			}
		}
		print "'";
	}
	
	public function validateKey($data) {
		$e = parent::validateKey($data);
		
		// additional validation for select type
		$akCustomCountries = $data['akCustomCountries'];
		$akHasCustomCountries = $data['akHasCustomCountries'];
		if ($data['akHasCustomCountries'] != 1) {
			$akHasCustomCountries = 0;
		}

		if (!is_array($data['akCustomCountries'])) {
			$akCustomCountries = array();
		}
		
		if ($akHasCustomCountries && (count($akCustomCountries) == 0)) {
			$e->add(t('You must specify at least one country.'));
		} else if ($akHasCustomCountries && $data['akDefaultCountry'] != '' && (!in_array($data['akDefaultCountry'], $akCustomCountries))) {
			$e->add(t('The default country must be in the list of custom countries.'));
		}
		
		return $e;
	}

	public function duplicateKey($newAK) {
		$this->load();
		$db = Loader::db();
		$db->Execute('insert into atAddressSettings (akID, akHasCustomCountries, akDefaultCountry) values (?, ?, ?)', array($newAK->getAttributeKeyID(), $this->akHasCustomCountries, $this->akDefaultCountry));	
		if ($this->akHasCustomCountries) {
			foreach($this->akCustomCountries as $country) {
				$db->Execute('insert into atAddressCustomCountries (akID, country) values (?, ?)', array($newAK->getAttributeKeyID(), $country));
			}
		}
	}

	public function exportKey($akey) {
		$this->load();
		$type = $akey->addChild('type');
		$type->addAttribute('custom-countries', $this->akHasCustomCountries);
		$type->addAttribute('default-country', $this->akDefaultCountry);
		if ($this->akHasCustomCountries) {
			$countries = $type->addChild('countries');
			foreach($this->akCustomCountries as $country) {
				$countries->addChild('country', $country);			
			}
		}
		return $akey;
	}

	public function exportValue($akn) {
		$avn = $akn->addChild('value');
		$address = $this->getValue();
		$avn->addAttribute('address1', $address->getAddress1());
		$avn->addAttribute('address2', $address->getAddress2());
		$avn->addAttribute('city', $address->getCity());
		$avn->addAttribute('state-province', $address->getStateProvince());
		$avn->addAttribute('country', $address->getCountry());
		$avn->addAttribute('postal-code', $address->getPostalCode());
	}

	public function importValue(SimpleXMLElement $akv) {
		if (isset($akv->value)) {
			$data['address1'] = $akv->value['address1'];
			$data['address2'] = $akv->value['address2'];
			$data['city'] = $akv->value['city'];
			$data['state_province'] = $akv->value['state-province'];
			$data['country'] = $akv->value['country'];
			$data['postal_code'] = $akv->value['postal-code'];
			return $data;
		}
	}
	
	public function importKey($akey) {
		if (isset($akey->type)) {
			$data['akHasCustomCountries'] = $akey->type['custom-countries'];
			$data['akDefaultCountry'] = $akey->type['default-country'];
			if (isset($akey->type->countries)) {
				foreach($akey->type->countries->children() as $country) {
					$data['akCustomCountries'][] = (string) $country;
				}
			}
			$this->saveKey($data);
		}
	}

	public function saveKey($data) {
		$e = Loader::helper('validation/error');
		
		$ak = $this->getAttributeKey();
		$db = Loader::db();

		$akCustomCountries = $data['akCustomCountries'];
		$akHasCustomCountries = $data['akHasCustomCountries'];
		if ($data['akHasCustomCountries'] != 1) {
			$akHasCustomCountries = 0;
		}		
		if (!is_array($data['akCustomCountries'])) {
			$akCustomCountries = array();
		}		
		if (!$e->has()) {
			$db->Replace('atAddressSettings', array(
				'akID' => $ak->getAttributeKeyID(), 
				'akHasCustomCountries' => $akHasCustomCountries,
				'akDefaultCountry' => $data['akDefaultCountry']			
			), array('akID'), true);
	
			$db->Execute('delete from atAddressCustomCountries where akID = ?', array($ak->getAttributeKeyID()));
			if (count($akCustomCountries)) {
				foreach($akCustomCountries as $cnt) {
					$db->Execute('insert into atAddressCustomCountries (akID, country) values (?, ?)', array($ak->getAttributeKeyID(), $cnt));
				}
			}
		} else {
			return $e;
		}
	}
	
	protected function load() {
		$ak = $this->getAttributeKey();
		if (!is_object($ak)) {
			return false;
		}
		
		$db = Loader::db();
		$row = $db->GetRow('select akHasCustomCountries, akDefaultCountry from atAddressSettings where akID = ?', $ak->getAttributeKeyID());
		$countries = array();
		if ($row['akHasCustomCountries'] == 1) { 
			$countries = $db->GetCol('select country from atAddressCustomCountries where akID = ?', $ak->getAttributeKeyID());
		}
		$this->akHasCustomCountries = $row['akHasCustomCountries'];
		$this->akDefaultCountry = $row['akDefaultCountry'];
		$this->akCustomCountries = $countries;
		$this->set('akDefaultCountry', $this->akDefaultCountry);
		$this->set('akHasCustomCountries', $this->akHasCustomCountries);
		$this->set('akCustomCountries', $countries);
	}

	public function type_form() {
		$this->load();
	}
	
	public function form() {
		$this->load();
		if (is_object($this->attributeValue)) {
			$value = $this->getAttributeValue()->getValue();
			$this->set('address1', $value->getAddress1());
			$this->set('address2', $value->getAddress2());
			$this->set('city', $value->getCity());
			$this->set('state_province', $value->getStateProvince());
			$this->set('country', $value->getCountry());
			$this->set('postal_code', $value->getPostalCode());
		}
		$this->addHeaderItem(Loader::helper('html')->javascript($this->getView()->action('load_provinces_js')));
		$this->addHeaderItem(Loader::helper('html')->javascript($this->attributeType->getAttributeTypeFileURL('country_state.js')));
		$this->set('key', $this->attributeKey);
	}

}

class Concrete5_Model_AddressAttributeTypeValue extends Object {
	
	public static function getByID($avID) {
		$db = Loader::db();
		$value = $db->GetRow("select avID, address1, address2, city, state_province, postal_code, country from atAddress where avID = ?", array($avID));
		$aa = new AddressAttributeTypeValue();
		$aa->setPropertiesFromArray($value);
		if ($value['avID']) {
			return $aa;
		}
	}
	
	public function __construct() {
		$h = Loader::helper('lists/countries');
		$this->countryFull = $h->getCountryName($this->country);		
	}	
	
	public function getAddress1() {return $this->address1;}
	public function getAddress2() {return $this->address2;}
	public function getCity() {return $this->city;}
	public function getStateProvince() {return $this->state_province;}
	public function getCountry() {return $this->country;}
	public function getPostalCode() {return $this->postal_code;}
	public function getFullCountry() {
		$h = Loader::helper('lists/countries');
		return $h->getCountryName($this->country);		
	}
	public function getFullStateProvince() {
		$h = Loader::helper('lists/states_provinces');
		$val = $h->getStateProvinceName($this->state_province, $this->country);
		if ($val == '') {
			return $this->state_province;
		} else {
			return $val;
		}
	}
	
	public function __toString() {
		$ret = '';
		if ($this->address1) {
			$ret .= $this->address1 . "\n";
		}
		if ($this->address2) {
			$ret .= $this->address2 . "\n";
		}
		if ($this->city) {
			$ret .= $this->city;
		}
		if ($this->city && $this->state_province) {
			$ret .= ", ";
		}
		if ($this->state_province) {
			$ret .= $this->getFullStateProvince();
		}
		if ($this->postal_code) {
			$ret .= " " . $this->postal_code;
		}
		if ($this->city || $this->state_province || $this->postal_code) {
			$ret .= "\n";
		}
		if ($this->country) {
			$ret .= $this->getFullCountry();
		}
		return $ret;		
	}
}