<?php defined('C5_EXECUTE') or die('Access Denied');

class Concrete5_Controller_Dashboard_Users_Group_Sets extends DashboardBaseController {
	
	public $category;
	
	public function on_start() {
		parent::on_start();
		$this->set('groupSets', GroupSet::getList());
		$gl = new GroupSearch();
		$gl->setItemsPerPage(10000);
		$gArray = $gl->getPage();
		$groups = array();
		foreach($gArray as $g) {
			$groups[] = Group::getByID($g['gID']);
		}
		$this->set('groups', $groups);
	}
	
	public function add_set() {
		if ($this->token->validate('add_set')) { 
			if (!trim($this->post('gsName'))) { 
				$this->error->add(t("Specify a name for your group set."));
			}
			$gsName = trim($this->post('gsName'));
			if (!Loader::helper('validation/strings')->alphanum($gsName, true)) {
				$this->error->add(t('Set Names must only include alphanumerics and spaces.'));
			}
			if (!$this->error->has()) {
				$gs = GroupSet::add($gsName);
				if (is_array($_POST['gID'])) {
					foreach($_POST['gID'] as $gID) {
						$g = Group::getByID($gID);
						if(is_object($g)) {
							$gs->addGroup($g);
						}
					}					
				}
				$this->redirect('dashboard/users/group_sets', 'set_added');
			}
			
		} else {
			$this->error->add($this->token->getErrorMessage());
		}
	}
	
	public function set_added() {
		$this->set('success', t('Group set added successfully.'));
	}

	public function set_deleted() {
		$this->set('success', t('Group set deleted successfully.'));
	}
	
	public function set_updated() {
		$this->set('success', t('Group set updated successfully.'));
	}
	
	public function update_set() {
		$this->edit($this->post('gsID'));
		if ($this->token->validate('update_set')) { 
			$as = GroupSet::getByID($this->post('gsID'));
			if (!is_object($as)) {
				$this->error->add(t('Invalid group set.'));
			} else {
				if (!trim($this->post('gsName'))) { 
					$this->error->add(t("Specify a name for your group set."));
				}
			}
			
			$gsName = trim($this->post('gsName'));
			if (!Loader::helper('validation/strings')->alphanum($gsName, true)) {
				$this->error->add(t('Set Names must only include alphanumerics and spaces.'));
			}
			if (!$this->error->has()) {
				$as->updateGroupSetName($gsName);
				$this->redirect('dashboard/users/group_sets', 'set_updated');
			}
			
		} else {
			$this->error->add($this->token->getErrorMessage());
		}
	}
	
	public function update_set_groups() {
		if ($this->token->validate('update_set_groups')) { 
			$gs = GroupSet::getByID($this->post('gsID'));
			if (!is_object($gs)) {
				$this->error->add(t('Invalid group set.'));
			}

			if (!$this->error->has()) {
				// go through and add all the attributes that aren't in another set
				$gs->clearGroups();
				if (is_array($this->post('gID'))) {
					foreach($_POST['gID'] as $gID) {
						$g = Group::getByID($gID);
						if(is_object($g)) {
							$gs->addGroup($g);
						}
					}					
				}
				$this->redirect('dashboard/users/group_sets', 'set_updated');
			}	
			
		} else {
			$this->error->add($this->token->getErrorMessage());
		}
		$this->edit($this->post('asID'));
	}
	
	public function delete_set() {
		if ($this->token->validate('delete_set')) { 
			$gs = GroupSet::getByID($this->post('gsID'));
			if (!is_object($gs)) {
				$this->error->add(t('Invalid group set.'));
			}
			if (!$this->error->has()) {
				$gs->delete();
				$this->redirect('dashboard/users/group_sets', 'set_deleted');
			}			
		} else {
			$this->error->add($this->token->getErrorMessage());
		}
	}
	
	public function edit($gsID = false) {
		$gs = GroupSet::getByID($gsID);
		if (is_object($gs)) {
			$this->set('set', $gs);
		} else {
			$this->redirect('/dashboard/users/group_sets');
		}
	}
	
}