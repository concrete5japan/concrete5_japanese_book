<?php	
defined('C5_EXECUTE') or die("Access Denied.");
class Localization extends Concrete5_Library_Localization {
		
	public function setLocale($locale) {
		if ($locale == 'en_US' && isset($this->translate)) {
			unset($this->translate);
		} else if ($locale != 'en_US') {

			if(defined("DIRNAME_APP_UPDATED")){
				$languageFolder = DIR_BASE . '/'. DIRNAME_UPDATES .'/' . DIRNAME_APP_UPDATED . '/' . DIRNAME_LANGUAGES;
			} else {
				$languageFolder = DIR_LANGUAGES;
			}
			
			if(is_dir($languageFolder . '/' . $locale)) {
				$options = array('adapter' => 'gettext');
				if (defined('TRANSLATE_OPTIONS')) {
					$_options = unserialize(TRANSLATE_OPTIONS); 
					if (is_array($_options)) {
						$options = array_merge($options, $_options);
					}
				}
				$options = array_merge($options, array(
					'content' => $languageFolder . '/' . $locale,
					'locale' => $locale
				));
				if (!isset($this->translate)) {
					$this->translate = new Zend_Translate($options);
				} else {
					if (!in_array($locale, $this->translate->getList())) {
						$this->translate->addTranslation($options);
					}
					$this->translate->setLocale($locale);
				}
			}
		}
	}
	
	public static function getAvailableInterfaceLanguages() {
		$languages = array();
		$fh = Loader::helper('file');
		
		if(defined("DIRNAME_APP_UPDATED")){
			$languageFolder = DIR_BASE . '/'. DIRNAME_UPDATES .'/' . DIRNAME_APP_UPDATED . '/' . DIRNAME_LANGUAGES;
		} else {
			$languageFolder = DIR_LANGUAGES;
		}
		
		if (file_exists($languageFolder)) {
			$contents = $fh->getDirectoryContents($languageFolder);
			foreach($contents as $con) {
				if (is_dir($languageFolder . '/' . $con) && file_exists($languageFolder . '/' . $con . '/LC_MESSAGES/messages.mo')) {
					$languages[] = $con;					
				}
			}
		}
		if (file_exists(DIR_LANGUAGES_CORE)) {
			$contents = $fh->getDirectoryContents(DIR_LANGUAGES_CORE);
			foreach($contents as $con) {
				if (is_dir(DIR_LANGUAGES_CORE . '/' . $con) && file_exists(DIR_LANGUAGES_CORE . '/' . $con . '/LC_MESSAGES/messages.mo') && (!in_array($con, $languages))) {
					$languages[] = $con;					
				}
			}
		}
		
		return $languages;
	}
	
	
	
}