<?php

defined('C5_EXECUTE') or die("Access Denied.");
$u = new User();
$fp = FilePermissions::getGlobal();
if (!$fp->canAddFiles()) {
	die(t("Unable to add files."));
}
$cf = Loader::helper("file");
$valt = Loader::helper('validation/token');
Loader::library("file/importer");

$error = "";

if (isset($_POST['fID'])) {
	// we are replacing a file
	$fr = File::getByID($_REQUEST['fID']);
	$file_permissions = new Permissions($fr);
	if (!$file_permissions->canEditFileContents()) {
		die(t("Unable to add files."));
	}
} else {
	$fr = false;
}

$searchInstance = $_POST['searchInstance'];

$files = array();
if ($valt->validate('import_incoming')) {
	if( !empty($_POST) ) {
		$fi = new FileImporter();
		foreach($_POST as $k=>$name) {
			if(preg_match("#^send_file#", $k)) {
				if (!$fp->canAddFileType($cf->getExtension($name))) {
					$resp = FileImporter::E_FILE_INVALID_EXTENSION;
				} else {
					$resp = $fi->import(DIR_FILES_INCOMING .'/'. $name, $name, $fr);
				}
				if (!($resp instanceof FileVersion)) {
					$error .= $name . ': ' . FileImporter::getErrorMessage($resp) . "\n";

				} else {
					$files[] = $resp;
					if ($_POST['removeFilesAfterPost'] == 1) {
						unlink(DIR_FILES_INCOMING .'/'. $name);
					}

					if (!is_object($fr)) {
						// we check $fr because we don't want to set it if we are replacing an existing file
						$respf = $resp->getFile();
						$respf->setOriginalPage($_POST['ocID']);
					}
				}
			}
		}
	}

	if (count($files) == 0) {
		$error .= t('You must select at least one file.');
	}

} else {
	$error = $valt->getErrorMessage();
}
?>
<html>
<head>
<script language="javascript">
	<?php if(strlen($error)) { ?>
		window.parent.ccmAlert.notice("<?php echo t('Upload Error')?>", "<?php echo str_replace("\n", '', nl2br($error))?>");
		window.parent.ccm_alResetSingle();
	<?php } else { ?>
		highlight = new Array();
		<?php foreach($files as $resp) { ?>
			highlight.push(<?php echo $resp->getFileID()?>);
			window.parent.ccm_uploadedFiles.push(<?php echo intval($resp->getFileID())?>);
		<?php } ?>
		window.parent.jQuery.fn.dialog.closeTop();
		setTimeout(function() {
			window.parent.ccm_filesUploadedDialog('<?php echo $searchInstance?>');
		}, 100);
	<?php } ?>
</script>
</head>
<body>
</body>
</html>
