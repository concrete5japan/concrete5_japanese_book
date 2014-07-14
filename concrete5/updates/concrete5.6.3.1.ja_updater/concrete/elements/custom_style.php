<?php
defined('C5_EXECUTE') or die("Access Denied.");
if (ENABLE_CUSTOM_DESIGN == false) {
	die(t('Custom design options have been disabled.'));
}

global $c;

$txt = Loader::helper('text');
$form = Loader::helper('form');
$fh = Loader::helper('form/color'); 
$ah = Loader::helper("concrete/asset_library");

if (isset($_REQUEST['cspID']) && $_REQUEST['cspID'] > 0) {
	$csp = CustomStylePreset::getByID($_REQUEST['cspID']);
	if (is_object($csp)) {
		$style = $csp->getCustomStylePresetRuleObject();
	}
} else if (is_object($style)) {
	$selectedCsrID = $style->getCustomStyleRuleID();
}

if(!$style) $style = new CustomStyleRule();

$cssData = $style->getCustomStyleRuleCustomStylesArray();

$presets = CustomStylePreset::getList();
$presetsArray = array();
foreach($presets as $csp) {
	$presetsArray[$csp->getCustomStylePresetID()] = $csp->getCustomStylePresetName();
}

$presetsArray[0] = t('** Custom (No Preset)');
ksort($presetsArray);

if (!isset($_REQUEST['csrID'])) {
	$cspID = $style->getCustomStylePresetID();
}

if ($_REQUEST['subtask'] == 'delete_custom_style_preset') {
	$cspID = 0;
}
?>

<?php if (!$_REQUEST['refresh']) { ?>
	<div class="ccm-ui" id="ccm-custom-style-wrapper">
<?php } ?>

<form class="form-stacked" method="post" id="ccmCustomCssForm" action="<?php echo $action?>" style="width:96%; margin:auto;">

	<input id="ccm-reset-style" name="reset_css" type="hidden" value="0" />
	
	<?php if (count($presets) > 0) { ?>
		<h3><?php echo t('Saved Presets')?></h3>
	
		<?php echo $form->select('cspID', $presetsArray, $cspID, array('style' => 'vertical-align: middle'))?>
		<a href="javascript:void(0)" id="ccm-style-delete-preset" style="display: none" onclick="ccmCustomStyle.deletePreset()"><img src="<?php echo ASSETS_URL_IMAGES?>/icons/delete_small.png" style="vertical-align: middle" width="16" height="16" border="0" /></a>
		
		<br/><br/>
		
		<input type="hidden" id="ccm-custom-style-refresh-action" value="<?php echo $refreshAction?>" /> 
	<?php } ?>
	
	<input type="hidden" name="selectedCsrID" value="<?php echo $selectedCsrID?>" />
	<ul id="ccm-styleEditPane-tabs" class="ccm-dialog-tabs" style="margin-left:0px">
		<li class="ccm-nav-active"><a id="ccm-styleEditPane-tab-fonts" href="#" onclick="return ccmCustomStyle.tabs(this,'fonts');"><?php echo t('Fonts') ?></a></li>
		<li><a href="javascript:void(0);" onclick="return ccmCustomStyle.tabs(this,'background');"><?php echo t('Background') ?></a></li>
		<li><a href="javascript:void(0);" onclick="return ccmCustomStyle.tabs(this,'border');"><?php echo t('Border') ?></a></li>
		<li><a href="javascript:void(0);" onclick="return ccmCustomStyle.tabs(this,'spacing');"><?php echo t('Spacing') ?></a></li>
		<li><a href="javascript:void(0);" onclick="return ccmCustomStyle.tabs(this,'css');"><?php echo t('CSS')?></a></li> 
	</ul>		
	
	<div id="ccmCustomCssFormTabs">
	
		<?php Loader::element('custom_style/fonts', array('cssData' => $cssData, 'fh' => $fh));?>
	
		<?php Loader::element('custom_style/background', array('cssData' => $cssData, 'fh' => $fh, 'ah' => $ah));?>
	
		<?php Loader::element('custom_style/border', array('cssData' => $cssData, 'fh' => $fh));?>

		<?php Loader::element('custom_style/spacing', array('cssData' => $cssData, 'fh' => $fh));?>
		
		<?php Loader::element('custom_style/css', array('style' => $style));?>

	</div>
	
	<br/>
	
	<?php if ($cspID > 0) { 
		$cspx = CustomStylePreset::getByID($cspID);?>
		<div id="cspFooterPreset" style="display: none">
			<div class="ccm-note-important">
				<h2><?php echo t('You are changing a preset')?></h2>
				<label class="radio"><?php echo $form->radio('cspPresetAction', 'update_existing_preset', true)?> <?php echo t('Update "%s" preset everywhere it is used', $cspx->getCustomStylePresetName())?></label>
				<label class="radio"><?php echo $form->radio('cspPresetAction', 'save_as_custom_style')?> <?php echo t('Use this style here, and leave "%s" unchanged', $cspx->getCustomStylePresetName())?></label>
				<label class="radio"><?php echo $form->radio('cspPresetAction', 'create_new_preset')?> <?php echo t('Save this style as a new preset')?><br/><span style="margin-left: 20px"><?php echo $form->text('cspName', array('style' => 'width:  127px', 'disabled' => true))?></span></label>
			</div>
		</div>
	<?php } ?>
	
	<div id="cspFooterNoPreset" >
		<label for="cspPresetAction" class="checkbox inline">
			<?php echo $form->checkbox('cspPresetAction', 'create_new_preset')?>
			<?php echo t('Save this style as a new preset.')?>
		</label>
		<span style="margin-left: 10px">
			<?php echo $form->text('cspName', array('style' => 'width:  140px', 'disabled' => true))?>
		</span>
	</div>
	
	<br/>
	
	<?php if (!$_REQUEST['refresh']) { ?>
		<div class="dialog-buttons">
			<a href="#" class="ccm-button-left cancel btn" onclick="jQuery.fn.dialog.closeTop(); return false"><?php echo t('Cancel')?></a>
			<a href="javascript:void(0)" onclick="$('#ccmCustomCssForm').submit()" class="btn primary ccm-button-right accept"><span><?php echo t('Save')?></span></a>
			<?php if ($cspID < 1) { ?>
				<a onclick="return ccmCustomStyle.resetAll();" id="ccm-reset-style-button" class="btn ccm-button-right accept" style="margin-right:8px; "><span><?php echo t('Reset Styles')?></span></a>
			<?php } ?>
		</div>
	<?php } ?>

	<div class="ccm-spacer"></div> 
	
	<div class="ccm-note" style="margin-top:16px;">
		<?php echo t('Note: Styles set here are often overridden by those defined within the various block types.')?>
	</div>		
	
<?php
$valt = Loader::helper('validation/token');
$valt->output();
?>
</form>

<script type="text/javascript">
	$(function() {
		ccmCustomStyle.initForm();
	});
</script>

<?php if (!$_REQUEST['refresh']) { ?>
	</div>
<?php } ?>