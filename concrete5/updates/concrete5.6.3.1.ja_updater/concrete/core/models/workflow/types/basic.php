<?php
defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_BasicWorkflowHistoryEntry extends WorkflowHistoryEntry {

	public function getWorkflowProgressHistoryDescription() {
		$uID = $this->getRequesterUserID();
		$ux = UserInfo::getByID($uID);
		switch($this->getAction()) {
			case 'approve':
				$d = t('Approved by %s', $ux->getUserName());
				break;
			case 'cancel':
				$d = t('Denied by %s', $ux->getUserName());
				break;
		}		
		if ($this->getWorkflowStepComments()) {
			$d .= t(' with the comments "%s"', $this->getWorkflowStepComments());
		}

		return $d;
	}
}

class Concrete5_Model_BasicWorkflow extends Workflow  {
	
	public function updateDetails($post) {
		$permissions = PermissionKey::getList('basic_workflow');
		foreach($permissions as $pk) {
			$pk->setPermissionObject($this);
			$pt = $pk->getPermissionAssignmentObject();
			$paID = $post['pkID'][$pk->getPermissionKeyID()];
			$pt->clearPermissionAssignment();
			if ($paID > 0) {
				$pa = PermissionAccess::getByID($paID, $pk);
				if (is_object($pa)) {
					$pt->assignPermissionAccess($pa);
				}			
			}		
		}			
	}

	public function loadDetails() {}
	
	public function delete() {
		$db = Loader::db();
		$db->Execute('delete from BasicWorkflowPermissionAssignments where wfID = ?', array($this->wfID));
		parent::delete();
	}
	
	public function start(WorkflowProgress $wp) {
		// lets save the basic data associated with this workflow. 
		$req = $wp->getWorkflowRequestObject();
		$db = Loader::db();
		$db->Execute('insert into BasicWorkflowProgressData (wpID, uIDStarted) values (?, ?)', array($wp->getWorkflowProgressID(), $req->getRequesterUserID()));
		
		$ui = UserInfo::getByID($req->getRequesterUserID());
		
		// let's get all the people who are set to be notified on entry
		$message = t('On %s, user %s submitted the following request: %s', date(DATE_APP_GENERIC_MDYT_FULL, strtotime($wp->getWorkflowProgressDateAdded())), $ui->getUserName(), $req->getWorkflowRequestDescriptionObject()->getEmailDescription());
		$this->notify($wp, $message, 'notify_on_basic_workflow_entry');
	}
	
	public function getWorkflowProgressCurrentDescription(WorkflowProgress $wp) {
		$bdw = new BasicWorkflowProgressData($wp);
		$ux = UserInfo::getByID($bdw->getUserStartedID());
		$req = $wp->getWorkflowRequestObject();
		$description = $req->getWorkflowRequestDescriptionObject()->getInContextDescription();
		return t('%s Submitted by <strong>%s</strong> on %s.', $description, $ux->getUserName(), date(DATE_APP_GENERIC_MDYT_FULL, strtotime($wp->getWorkflowProgressDateAdded())));
	}

	public function getWorkflowProgressStatusDescription(WorkflowProgress $wp) {
		$req = $wp->getWorkflowRequestObject();
		return $req->getWorkflowRequestDescriptionObject()->getShortStatus();
	}

	protected function notify(WorkflowProgress $wp, $message, $permission = 'notify_on_basic_workflow_entry', $parameters = array()) {
		$nk = PermissionKey::getByHandle($permission);
		$nk->setPermissionObject($this);
		$users = $nk->getCurrentlyActiveUsers($wp);
		$req = $wp->getWorkflowRequestObject();

		foreach($users as $ui) {
			$mh = Loader::helper('mail');
			$mh->addParameter('uName', $ui->getUserName());			
			$mh->to($ui->getUserEmail());
			$adminUser = UserInfo::getByID(USER_SUPER_ID);
			$mh->from($adminUser->getUserEmail(),  t('Basic Workflow'));
			$mh->addParameter('message', $message);
			foreach($parameters as $key => $value) {
				$mh->addParameter($key, $value);
			}
			$mh->load('basic_workflow_notification');
			$mh->sendMail();
			unset($mh);
		}
	}
	
	public function cancel(WorkflowProgress $wp) {
		if ($this->canApproveWorkflowProgressObject($wp)) {

			$req = $wp->getWorkflowRequestObject();
			$bdw = new BasicWorkflowProgressData($wp);
			$u = new User();
			$bdw->markCompleted($u);
			
			$ux = UserInfo::getByID($bdw->getUserCompletedID());

			$message = t("On %s, user %s cancelled the following request: \n\n---\n%s\n---\n\n", date(DATE_APP_GENERIC_MDYT_FULL, strtotime($bdw->getDateCompleted())), $ux->getUserName(), $req->getWorkflowRequestDescriptionObject()->getEmailDescription());
			$this->notify($wp, $message, 'notify_on_basic_workflow_deny');
			
			$hist = new BasicWorkflowHistoryEntry();
			$hist->setAction('cancel');
			$hist->setRequesterUserID($u->getUserID());
			$wp->addWorkflowProgressHistoryObject($hist);

			$wpr = $req->runTask('cancel', $wp);
			$wp->markCompleted();

			$bdw = new BasicWorkflowProgressData($wp);
			$bdw->delete();			

			return $wpr;
		}
	}
	
	public function approve(WorkflowProgress $wp) {
		if ($this->canApproveWorkflowProgressObject($wp)) {
			$req = $wp->getWorkflowRequestObject();
			$bdw = new BasicWorkflowProgressData($wp);
			$u = new User();
			$bdw->markCompleted($u);
			
			$ux = UserInfo::getByID($bdw->getUserCompletedID());

			$message = t("On %s, user %s approved the following request: \n\n---\n%s\n---\n\n", date(DATE_APP_GENERIC_MDYT_FULL, strtotime($bdw->getDateCompleted())), $ux->getUserName(), $req->getWorkflowRequestDescriptionObject()->getEmailDescription());
			$this->notify($wp, $message, 'notify_on_basic_workflow_approve');

			$wpr = $req->runTask('approve', $wp);
			$wp->markCompleted();

			
			$hist = new BasicWorkflowHistoryEntry();
			$hist->setAction('approve');
			$hist->setRequesterUserID($u->getUserID());
			$wp->addWorkflowProgressHistoryObject($hist);

			$bdw = new BasicWorkflowProgressData($wp);
			$bdw->delete();			

			return $wpr;
		}
	}
	
	public function canApproveWorkflowProgressObject(WorkflowProgress $wp) {
		$pk = PermissionKey::getByHandle('approve_basic_workflow_action');
		$pk->setPermissionObject($this);
		return $pk->validate();
	}
	
	public function getWorkflowProgressActions(WorkflowProgress $wp) {
		$pk = PermissionKey::getByHandle('approve_basic_workflow_action');
		$pk->setPermissionObject($this);
		$buttons = array();
		if ($this->canApproveWorkflowProgressObject($wp)) {
			$req = $wp->getWorkflowRequestObject();
			$button1 = new WorkflowProgressCancelAction();
	
			$button2 = new WorkflowProgressApprovalAction();
			$button2->setWorkflowProgressActionStyleClass($req->getWorkflowRequestApproveButtonClass());
			$button2->setWorkflowProgressActionStyleInnerButtonRightHTML($req->getWorkflowRequestApproveButtonInnerButtonRightHTML());
			$button2->setWorkflowProgressActionLabel($req->getWorkflowRequestApproveButtonText());
	
			$buttons[] = $button1;
			$buttons[] = $button2;
		}
		return $buttons;
	}

}