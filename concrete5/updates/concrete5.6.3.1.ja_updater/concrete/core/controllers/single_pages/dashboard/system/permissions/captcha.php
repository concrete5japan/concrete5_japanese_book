<?php
defined('C5_EXECUTE') or die("Access Denied.");
Loader::model("system/captcha/library");
Loader::model("system/captcha/controller");

class Concrete5_Controller_Dashboard_System_Permissions_Captcha extends DashboardBaseController {
	
	public function view() {
		$list = SystemCaptchaLibrary::getList();
		$captchas = array();
		foreach($list as $sc) {
			$captchas[$sc->getSystemCaptchaLibraryHandle()] = $sc->getSystemCaptchaLibraryName();
		}
		$scl = SystemCaptchaLibrary::getActive();
		$this->set('activeCaptcha', $scl);
		$this->set('captchas', $captchas);
	}
	
	public function captcha_saved() {
		$this->set('message', t('Captcha settings saved.'));
		$this->view();
	}
	
	public function update_captcha() {
		if (Loader::helper("validation/token")->validate('update_captcha')) {
			$scl = SystemCaptchaLibrary::getByHandle($this->post('activeCaptcha'));
			if (is_object($scl)) {
				$scl->activate();
				if ($scl->hasOptionsForm() && $this->post('ccm-submit-submit')) {
					$controller = $scl->getController();
					$controller->saveOptions($this->post());
				}
				$this->redirect('/dashboard/system/permissions/captcha', 'captcha_saved');
			} else {
				$this->error->add(t('Invalid captcha library.'));
			}
		} else {
			$this->error->add(Loader::helper('validation/token')->getErrorMessage());
		}
		$this->view();
	}
}