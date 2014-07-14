<?php

defined('C5_EXECUTE') or die("Access Denied.");
if (!ini_get('safe_mode')) {
	@set_time_limit(0);
}

$json = Loader::helper('json');
if (Job::authenticateRequest($_REQUEST['auth'])) {

	$list = Job::getList();
	foreach($list as $job) {
		if ($job->supportsQueue()) {
			$q = $job->getQueueObject();
			// don't process queues that are empty
			if($q->count() <1){
				continue;
			}
			$obj = new stdClass;
			$js = Loader::helper('json');
			try {
				$messages = $q->receive($job->getJobQueueBatchSize());
				foreach($messages as $key => $p) {
					$job->processQueueItem($p);
					$q->deleteMessage($p);
				}
				$totalItems = $q->count();	
				$obj->totalItems = $totalItems;
				$obj->jHandle = $job->getJobHandle();
				$obj->jID = $job->getJobID();
				if ($q->count() == 0) {
					$result = $job->finish($q);
					$obj = $job->markCompleted(0, $result);
					$obj->totalItems = $totalItems;
				}
			} catch(Exception $e) {
				$obj = $job->markCompleted(Job::JOB_ERROR_EXCEPTION_GENERAL, $e->getMessage());
				$obj->message = $obj->result; // needed for progressive library.
			}
			print $js->encode($obj);
			// End when one queue has processed a batch step
			break;
		}
	}
}

