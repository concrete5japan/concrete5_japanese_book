<?php defined('C5_EXECUTE') or die("Access Denied.");?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Environment'), false, 'span8 offset2');?>

<textarea style="width: 99%; height: 340px;" onclick="this.select()" id="ccm-dashboard-environment-info"><?php echo t('Unable to load environment info')?></textarea>

<script type="text/javascript">
$(document).ready(function() {
	$('#ccm-dashboard-environment-info').load('<?php  echo $this->action('get_environment_info')?>');	
});
</script>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper();?>