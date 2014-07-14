<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<?php
$pk = BasicWorkflowPermissionKey::getByID($_REQUEST['pkID']);
$pk->setPermissionObject($workflow);
?>

<?php Loader::element("permission/detail", array('permissionKey' => $pk)); ?>

<script type="text/javascript">
var ccm_permissionDialogURL = '<?php echo REL_DIR_FILES_TOOLS_REQUIRED?>/permissions/dialogs/basic_workflow?wfID=<?php echo $workflow->getWorkflowID()?>'; 
</script>