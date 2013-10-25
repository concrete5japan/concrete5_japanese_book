<?php
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Controller_Dashboard_Workflow_Me extends DashboardBaseController {

	protected $wpCategoryHandleActive = 'page';
	
	public function on_start() {
		parent::on_start();
		$this->addHeaderItem("<script type=\"text/javascript\">$(function() { $('.dialog-launch').dialog();});</script>");
		$this->categories = WorkflowProgressCategory::getList();
		foreach($this->categories as $cat) {
			$this->categoryHandles[] = $cat->getWorkflowProgressCategoryHandle();
		}
	}
	
	public function on_before_render() {
		$tabs = array();
		foreach($this->categories as $cat) { 
			$active = ($cat->getWorkflowProgressCategoryHandle() == $this->wpCategoryHandleActive);
			if ($active) { 
				$this->set('category', $cat);
			}
			switch($cat->getWorkflowProgressCategoryHandle()) {
				case 'page':
					$tabName = t('Pages');
					break;
				case 'file':
					$tabName = t('Files');
					break;
				case 'user':
					$tabName = t('Users');
					break;
				default:
					$tabName = t(sprintf('%ss', Loader::helper('text')->unhandle($cat->getWorkflowProgressCategoryHandle())));
					break;
			}
			$tabs[] = array(View::url('/dashboard/workflow/me/', 'view', $cat->getWorkflowProgressCategoryHandle()), $tabName, $active);
		}
		$this->set('tabs', $tabs);
	}
	
	public function view($wpCategoryHandle = false) {
		if (in_array($wpCategoryHandle, $this->categoryHandles)) {
			$this->wpCategoryHandleActive = $wpCategoryHandle;		
		}
	}
		
	
}