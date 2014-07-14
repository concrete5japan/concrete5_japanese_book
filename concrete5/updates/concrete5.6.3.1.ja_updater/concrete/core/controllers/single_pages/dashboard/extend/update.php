<?php

defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Controller_Dashboard_Extend_Update extends Controller {
	
	public function on_start() {
		$this->error = Loader::helper('validation/error');
		Loader::library('marketplace');
	}
	public function do_update($pkgHandle = false) {
		$tp = new TaskPermission();
		if ($tp->canInstallPackages()) { 
			if ($pkgHandle) {
				$tests = Package::testForInstall($pkgHandle, false);
				if (is_array($tests)) {
					$tests = Package::mapError($tests);
					$this->set('error', $tests);
				} else {
					$p = Package::getByHandle($pkgHandle);
					$currentLocale = Localization::activeLocale();
					if ($currentLocale != 'en_US') {
						// Prevent the database records being stored in wrong language
						Localization::changeLocale('en_US');
					}
					try {
						$p->upgradeCoreData();
						$p->upgrade();
						if ($currentLocale != 'en_US') {
							Localization::changeLocale($currentLocale);
						}
						$this->set('message', t('The package has been updated successfully.'));
					} catch(Exception $e) {
						if ($currentLocale != 'en_US') {
							Localization::changeLocale($currentLocale);
						}
						$this->set('error', $e);
					}
				}
			}
		}
		$this->view();
	}
	
	public function view() {
		$tp = new TaskPermission();
		if ($tp->canInstallPackages()) { 
			$mi = Marketplace::getInstance();
			if ($mi->isConnected()) {
				Marketplace::checkPackageUpdates();
			}
		}
	}

    public function prepare_remote_upgrade($remoteMPID = 0){
		$tp = new TaskPermission();
		if ($tp->canInstallPackages()) { 
			Loader::model('marketplace_remote_item');
			$mri = MarketplaceRemoteItem::getByID($remoteMPID);
	
			if (!is_object($mri)) {
				$this->set('error', array(t('Invalid marketplace item ID.')));
				return;
			}
			
			$local = Package::getbyHandle($mri->getHandle());
			if (!is_object($local) || $local->isPackageInstalled() == false) {
				$this->set('error', array(Package::E_PACKAGE_NOT_FOUND));
				return;
			}		
			
			$r = $mri->downloadUpdate();
	
			if ($r != false) {
				if (!is_array($r)) {
					$this->set('error', array($r));
				} else {
					$errors = Package::mapError($r);
					$this->set('error', $errors);
				}
			} else {
				$this->redirect('/dashboard/extend/update', 'do_update', $mri->getHandle());
			}
		}
    }

}
