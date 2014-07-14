<?php
defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getByID($_REQUEST['cID']);
$cp = new Permissions($c);
$obj = new stdClass;
if ($_REQUEST['task'] == 'save_workflow_progress' && Loader::helper("validation/token")->validate('save_workflow_progress')) {
	$wp = PageWorkflowProgress::getByID($_REQUEST['wpID']);
	if (is_object($wp)) { 
		$wf = $wp->getWorkflowObject();
		$form = Loader::helper('form'); 
		$obj->wpID = $wp->getWorkflowProgressID();
		if ($wf->canApproveWorkflowProgressObject($wp)) {
			$task = WorkflowProgress::getRequestedTask();
			if ($task) {
				$r = $wp->runTask($task, $_POST);
				if (($r instanceof WorkflowProgressResponse) && $r->getWorkflowProgressResponseURL() != '') {
					$obj->redirect = $r->getWorkflowProgressResponseURL();
				} else { 
					$obj->redirect = BASE_URL . DIR_REL . '/' . DISPATCHER_FILENAME . '?cID=' . $_REQUEST['cID'];
				}
			}
		}
	}
}

print Loader::helper('json')->encode($obj);