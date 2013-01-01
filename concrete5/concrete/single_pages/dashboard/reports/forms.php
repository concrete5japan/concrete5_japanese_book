<?php defined('C5_EXECUTE') or die("Access Denied.");
/* @var $h ConcreteDashboardHelper */
$h = Loader::helper('concrete/dashboard');
/* @var $ih ConcreteInterfaceHelper */
$ih = Loader::helper('concrete/interface');
/* @var $nh NavigationHelper */
$nh = Loader::helper('navigation');
/* @var $text TextHelper */
$text = Loader::helper('text');
/* @var $dh DateHelper*/
$dh = Loader::helper('date');
/* @var $urlhelper UrlHelper */
$urlhelper = Loader::helper('url');
/* @var $json JsonHelper */
$json = Loader::helper('json');
/* @var $db DataBase */
$db = Loader::db();
?>
<script>
jQuery(function($) {
	var deleteResponse = (<?php	echo $json->encode(t('Are you sure you want to delete this form submission?'))?>),
		deleteForm = (<?php	echo $json->encode(t('Are you sure you want to delete this form and its form submissions?'))?>);
	$('.delete-response').live('click', function(e) {
		if (!confirm(deleteResponse)) {
			e.preventDefault();
		}
	});
	$('.delete-form').live('click', function(e) {
		if (!confirm(deleteForm)) {
			e.preventDefault();
		}
	});
});
</script>
<?php	if(!isset($questionSet)):?>
<?php	echo $h->getDashboardPaneHeaderWrapper(t('Form Results'));?>
<?php	
$showTable = false;
foreach ($surveys as $qsid => $survey) {
	$block = Block::getByID((int) $survey['bID']);
	if (is_object($block)) {
		$showTable = true;
		break;
	}
}

if ($showTable) { ?>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo t('Form')?></th>
			<th><?php echo t('Submissions')?></th>
			<th><?php echo t('Options')?></th>
		</tr>
	</thead>
	<tbody>
		<?php	 foreach ($surveys as $qsid => $survey):
		$block = Block::getByID((int) $survey['bID']);
		if (!is_object($block)) {
			continue;
		}
		$in_use = (int) $db->getOne(
			'SELECT count(*)
			FROM CollectionVersionBlocks
			INNER JOIN Pages
			ON CollectionVersionBlocks.cID = Pages.cID
			INNER JOIN CollectionVersions
			ON CollectionVersions.cID = Pages.cID
			WHERE CollectionVersions.cvIsApproved = 1
			AND CollectionVersionBlocks.cvID = CollectionVersions.cvID
			AND CollectionVersionBlocks.bID = ?',
			array($block->bID)
		);
		$url = $nh->getLinkToCollection($block->getBlockCollectionObject());
?>
		<tr>
			<td><?php	echo $text->entities($survey['surveyName'])?></td>
			<td><?php	echo $text->entities($survey['answerSetCount'])?></td>
			<td>
				<?php	echo $ih->button(t('View Responses'), DIR_REL . '/index.php?cID=' . $c->getCollectionID().'&qsid='.$qsid, 'left', 'small')?>
				<?php	echo $ih->button(t('Open Page'), $url, 'left', 'small')?>
				<?php	if(!$in_use):?>
				<?php	echo $ih->button(t('Delete'), $this->action('').'?bID='.$survey['bID'].'&qsID='.$qsid.'&action=deleteForm', 'left', 'small error delete-form')?>
				<?php	endif?>
			</td>
		</tr>
		<?php	endforeach?>
	</tbody>
</table>
<?php	 } else { ?>
	<p><?php	echo t('There are no available forms in your site.')?></p>
<?php	 } ?>
<?php	echo $h->getDashboardPaneFooterWrapper();?>
<?php	else:?>
<?php	echo $h->getDashboardPaneHeaderWrapper(t('Responses to %s', $surveys[$questionSet]['surveyName']), false, false, false);?>
<div class="ccm-pane-body <?php	 if(!$paginator || !strlen($paginator->getPages())>0){ ?> ccm-pane-body-footer <?php	 } ?>">
<?php	if(count($answerSets) == 0):?>
<div><?php	echo t('No one has yet submitted this form.')?></div>
<?php	else:?>

<div class="ccm-list-action-row">
	<a id="ccm-export-results" href="<?php	echo $this->action('excel', '?qsid=' . $questionSet)?>"><span></span><?php	echo t('Export to Excel')?></a>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<?php	 if($_REQUEST['sortBy']=='chrono') { ?>
			<th class="header headerSortDown">
				<a href="<?php	echo $text->entities($urlhelper->unsetVariable('sortBy'))?>">
			<?php	 } else { ?>
			<th class="header headerSortUp">
				<a href="<?php	echo $text->entities($urlhelper->setVariable('sortBy', 'chrono'))?>">
			<?php	 } ?>		
				<?php	echo t('Date')?>
				</a>
			</th>
			<th><?php	echo t('User')?></th>
<?php	foreach($questions as $question):?>
			<th><?php	echo $question['question']?></th>
<?php	endforeach?>
			<th><?php	echo t('Actions')?></th>
		</tr>	
	</thead>
	<tbody>
<?php	foreach($answerSets as $answerSetId => $answerSet):?>
		<tr>
			<td>
<?php	echo $dh->getSystemDateTime($answerSet['created'])?></td>
			<td><?php	
			if ($answerSet['uID'] > 0) { 
				$ui = UserInfo::getByID($answerSet['uID']);
				if (is_object($ui)) {
					print $ui->getUserName().' ';
				}
				print t('(User ID: %s)', $answerSet['uID']);
			}
			?></td>
<?php	foreach($questions as $questionId => $question):
			if ($question['inputType'] == 'fileupload') {
				$fID = (int) $answerSet['answers'][$questionId]['answer'];
				$file = File::getByID($fID);
				if ($fID && $file) {
					$fileVersion = $file->getApprovedVersion();
					echo '<td><a href="' . $fileVersion->getRelativePath() .'">'.
						$text->entities($fileVersion->getFileName()).'</a></td>';
				} else {
					echo '<td>'.t('File not found').'</td>';
				}
			} else if($question['inputType'] == 'text') {
				echo '<td>'.$text->entities($answerSet['answers'][$questionId]['answerLong']).'</td>';
			} else {
				echo '<td>'.$text->entities($answerSet['answers'][$questionId]['answer']).'</td>';
			}
			
endforeach?>
			<td>
				<?php	echo $ih->button(
					t("Delete"),
					$this->action('').'?qsid='.$answerSet['questionSetId'].'&asid='.$answerSet['asID'].'&action=deleteResponse',
					'left',
					'danger delete-response small'
				)?>
			</td>
		</tr>
<?php	endforeach?>
	</tbody>
</table>
</div>
<?php	 if($paginator && strlen($paginator->getPages())>0){ ?>	 
<div class="ccm-pane-footer">
	<div class="pagination">
	  <ul>
		  <li class="prev"><?php	echo $paginator->getPrevious()?></li>
		  
		  <?php	 // Call to pagination helper's 'getPages' method with new $wrapper var ?>
		  <?php	echo $paginator->getPages('li')?>
		  
		  <li class="next"><?php	echo $paginator->getNext()?></li>
	  </ul>
	</div>
</div>
<?php	 } ?>		
<?php	endif?>
<?php	echo $h->getDashboardPaneFooterWrapper(false);?>
<?php	endif?>