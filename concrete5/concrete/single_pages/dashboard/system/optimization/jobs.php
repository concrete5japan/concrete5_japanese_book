<?php defined('C5_EXECUTE') or die("Access Denied.");
/* @var $h ConcreteDashboardHelper */
$h = Loader::helper('concrete/dashboard');
/* @var $ih ConcreteInterfaceHelper */
$ih = Loader::helper('concrete/interface');
/* @var $form FormHelper */
$form = Loader::helper('form');
/* @var $jh JsonHelper */
$jh = Loader::helper('json');
?>
<style type="text/css">
.run-all, .run-task { height: 16px; width: 16px; display: block; }
.run-indicator {
	height: 16px; width: 16px; display: none;
	background: url("<?php	echo ASSETS_URL_IMAGES?>/throbber_white_16.gif") no-repeat transparent;
}

.running .run-indicator {
	display: block;
}

.running .run-all, 
.running .run-task {
	display: none;
}

.run-task {
	background: url("<?php	echo ASSETS_URL_IMAGES?>/ui-icons_222222_256x240.png") no-repeat 0 -160px transparent;
}

.run-all {
	background: url("<?php	echo ASSETS_URL_IMAGES?>/ui-icons_222222_256x240.png") no-repeat -32px -160px transparent;
}
</style>
<script>
jQuery(function($) {
	$('.btn.remove').bind('click', function(e) {
		if (!confirm(<?php	echo $jh->encode(t("Are you sure you want to uninstall this job?"))?>)) {
			e.preventDefault();
		}
	});

	function runTaskForRow(row, jobId, cb) {
		row.addClass('running');
		$.ajax({ 
			url: CCM_TOOLS_PATH + '/jobs?auth=<?php	echo $auth?>&jID=' + jobId,
			dataType: 'json',
			cache: false,
			success: function(json) {
				row.removeClass('running green red');
				row.find('.jLastStatusText').html(json.message);
				row.find('.jDateLastRun').html(json.jDateLastRun);
				row.addClass(json.error == 0 ? 'green' : 'red');
				if (cb && $.isFunction(cb)) {
					cb(row, json, jobId);
				}
			}
		});
	}

	$('.run-task[data-jobId]').bind('click', function(e) {
		e.preventDefault();
		var $this = $(this),
			row = $this.closest('tr');
		runTaskForRow(row, $this.attr('data-jobId'));
	});

	$('.run-all').bind('click', function(e) {
		var $this = $(this),
			table = $this.closest('table'),
			links = table.find('tbody .run-task[data-jobId]'),
			jobs = links.map(function() {
					return {
						jobId : $(this).attr('data-jobId'),
						row : $(this).closest('tr')
					};
			}).get(),
			next = function() {
				var job = jobs.shift();
				if (job) {
					runTaskForRow(job.row, job.jobId, next);
				} else {
					table.removeClass('running');
				}
			};

		if (!table.hasClass('running')) {
			e.preventDefault();
			table.addClass('running');
			next();
		}
	});
});
</script>
<?php	echo $h->getDashboardPaneHeaderWrapper(t('Automated Jobs'), false, false, false);?>
<div class="ccm-pane-body">
<?php	if ($jobList->numRows() == 0):?>
<?php	echo t('You currently have no jobs installed.')?>
<?php	else:?>
<table class="table table-striped">
<thead>
<tr>
	<th><a class="run-all" href="<?php	echo BASE_URL.$this->url('/tools/required/jobs?auth='.$auth.'&debug=1')?>" title="<?php	echo t('Run all')?>"></a><span class="run-indicator"></span></th>
	<th><?php	echo t('ID')?></th>
	<th><?php	echo t('Name')?></th>
	<th><?php	echo t('Description')?></th>
	<th><?php	echo t('Last Run')?></th>
	<th><?php	echo t('Results of Last Run')?></th>
	<th></th>
</tr>
</thead>
<tbody>
<?php	 $jobrunning = false; ?>
<?php	foreach ($jobList as $job):?>
<tr <?php	 if ($job['jStatus'] == 'RUNNING') {
	
	$jobrunning = true;?>class="running" <?php	 } ?>>
	<td><a class="run-task" title="<?php	echo t('Run')?>" href="<?php	echo BASE_URL.$this->url('/tools/required/jobs?auth='.$auth.'&jID='.$job['jID'])?>" data-jobId="<?php	echo $job['jID']?>"></a><span class="run-indicator"></span></td>
	<td><?php	echo $job['jID']?></td>
	<td><?php	echo t($job['jName'])?></td>
	<td><?php	echo t($job['jDescription'])?></td>
	<td class="jDateLastRun"><?php	
	if ($job['jStatus'] == 'RUNNING') {
		$runtime = date(DATE_APP_GENERIC_TS, strtotime($job['jDateLastRun']));
		echo ("<strong>");
		echo t("Currently Running (Since %s)", $runtime);					
		echo ("</strong>");
	} else if($job['jDateLastRun'] == '' || substr($job['jDateLastRun'], 0, 4) == '0000') {
		echo t('Never');
	} else {
		$runtime = date(DATE_APP_GENERIC_MDY . t(' \a\t ') . DATE_APP_GENERIC_TS, strtotime($job['jDateLastRun']) );
		echo $runtime;
	}
?></td>
	<td class="jLastStatusText"><?php	echo t($job['jLastStatusText'])?></td>
	<td><?php	if(!$job['jNotUninstallable']):?>
		<?php	echo $ih->button(t('Remove'), $this->action('uninstall', $job['jID']), null, 'remove')?>
	<?php	endif?></td>
</tr>
<?php	endforeach?>
</tbody>
</table>
<?php	endif?>

<?php	if($availableJobs):?>
<h2><?php	echo t('Jobs Available for Installation')?></h2>
<table class="table table-striped">
<thead>
	<tr> 
		<th><?php	echo t('Name')?></th>
		<th><?php	echo t('Description')?></th> 
		<th></th>
	</tr>
</thead>
<tbody>
	<?php	foreach($availableJobs as $availableJobName => $job):?>
	<tr> 
		<td><?php	echo $job->getJobName() ?></td>
		<td><?php	echo $job->getJobDescription() ?></td> 
		<td><?php	if(!$job->invalid):?>
			<?php	echo $ih->button(t('Install'), $this->action('install', $job->jHandle))?>
		<?php	endif?></td>
	</tr>	
	<?php	endforeach?>
</tbody>
</table>
<?php	endif?>
<div><?php	echo t('If you wish to run these jobs in the background, automate access to the following URL:')?></div>
<div><a href="<?php	echo BASE_URL.$this->url('/tools/required/jobs?auth='.$auth)?>"><?php	echo BASE_URL . $this->url('/tools/required/jobs?auth=' . $auth)?></a></div>
</div>
<div class="ccm-pane-footer"><?php	 if ($jobrunning == true) { ?>
	<form method="post" style="display: inline" action="<?php	echo $this->action('reset_running_jobs')?>">
		<?php	echo Loader::helper('validation/token')->output('reset_running_jobs')?>
		<input type="submit" class="btn" value="<?php	echo t('Reset all Running Jobs')?>" />
	</form>
<?php	 } ?></div>
<?php	echo $h->getDashboardPaneFooterWrapper(false);?>