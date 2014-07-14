<?php
defined('C5_EXECUTE') or die("Access Denied.");
if (ENABLE_AREA_LAYOUTS == false) {
	die(t('Area layouts have been disabled.'));
}
global $c;

$form = Loader::helper('form'); 

//Loader::model('layout'); 


if( intval($_REQUEST['lpID']) ){ 
	$layoutPreset = LayoutPreset::getByID($_REQUEST['lpID']); 
	if(is_object($layoutPreset)){
		$layout = $layoutPreset->getLayoutObject();  
	}
}elseif(intval($_REQUEST['layoutID'])){
	$layout = Layout::getById( intval($_REQUEST['layoutID']) ); 
}else $layout = new Layout( array('type'=>'table','rows'=>1,'columns'=>3 ) ); 

if(!$layout ){ 
	echo t('Error: Layout not found');
	
}else{ 
	
	$layoutPresets=LayoutPreset::getList();
	
	if(intval($layout->lpID)) 
		$layoutPreset = LayoutPreset::getById($layout->lpID); 
	
	?>


<?php if (!$_REQUEST['refresh']) { ?>
<div id="ccm-layout-edit-wrapper">
<?php } ?>

<style type="text/css">
	#ccmLayoutConfigOptions { margin-top:12px; }
	#ccmLayoutConfigOptions table td { padding-bottom:4px; vertical-align:top; padding-bottom:12px; padding-right:12px; } 
	#ccmLayoutConfigOptions table td.padBottom {  }
</style>

<form method="post" class="ccm-ui" id="ccmAreaLayoutForm" action="<?php echo $action?>" style="width:96%; margin:auto;"> 

	<input id="ccmAreaLayoutForm_layoutID" name="layoutID" type="hidden" value="<?php echo intval( $layout->layoutID ) ?>" />  
	<input id="ccmAreaLayoutForm_arHandle" name="arHandle" type="hidden" value="<?php echo htmlentities( $a->getAreaHandle(), ENT_COMPAT, APP_CHARSET) ?>" /> 

	<?php if (count($layoutPresets) > 0) { ?>
		<h2><?php echo t('Saved Presets')?></h2>
		
		<input type="hidden" id="ccm-layout-refresh-action" value="<?php echo $refreshAction?>" /> 
		
		<select id="ccmLayoutPresentIdSelector" name="lpID">
			<option value="0"><?php echo t('** Custom (No Preset)') ?></option>
			<?php foreach($layoutPresets as $availablePreset){ ?>
				<option value="<?php echo $availablePreset->getLayoutPresetID() ?>" <?php echo ($availablePreset->getLayoutPresetID()==intval($layout->lpID))?'selected':''?>><?php echo $availablePreset->getLayoutPresetName() ?></option>
			<?php } ?>
		</select> 
		<a href="javascript:void(0)" id="ccm-layout-delete-preset" style="display: none" onclick="ccmLayoutEdit.deletePreset()"><img src="<?php echo ASSETS_URL_IMAGES?>/icons/delete_small.png" style="vertical-align: middle" width="16" height="16" border="0" /></a>
		
		<br/><br/>
		
	<?php } ?>

	<div id="ccmLayoutConfigOptions">
	
		<table> 
			<tr>
				<td><?php echo t('Columns')?>:</td>
				<td class="val">
					<input name="layout_columns" type="text" value="<?php echo intval($layout->columns) ?>" size=2 />
				</td>
				
			</tr>
			<tr>
				<td class="padBottom"><?php echo t('Rows')?>:</td>
				<td class="val padBottom">
					<input name="layout_rows" type="text" value="<?php echo intval($layout->rows) ?>" size=2 />
				</td>
			</tr>
			
			<tr>	
				<td class="padBottom"><?php echo t('Spacing')?>:</td>
				<td class="val padBottom">
					<input name="spacing" type="text" value="<?php echo intval($layout->spacing) ?>" size=2 /> <?php echo t('px')?>
				</td>				
			</tr>			
			
			<tr>
				<td class="padBottom"><label for="locked"><?php echo t('Lock Widths') ?>:</label></td>
				<td class="val padBottom">
					<input id="locked" name="locked" type="checkbox" value="1" <?php echo ( intval($layout->locked) ) ? 'checked="checked"' : '' ?> />
				</td>				
			</tr>			
							
		</table> 
	
	</div>	
	
	
	<?php 
	//To Do: only provide this option if there's 1) blocks in the main area, or 2) existing layouts 
	if( !intval($layout->layoutID) ){ ?>
	<?php /*
	<div style="margin:16px 0px"> 
		<?= t('Add layout to: ') ?> 
		<input name="add_to_position" type="radio" value="top" /> <?=t('top') ?>&nbsp; 
		<input name="add_to_position" type="radio" value="bottom" checked="checked" /> <?=t('bottom') ?> 
	</div>
	*/ ?>
	<input type="hidden" name="add_to_position" value="bottom" />
	
	<?php } ?>
	
	
	
	
	<?php if ( is_object($layoutPreset) ) { ?>
		<div id="layoutPresetActions" style="display: none">
			<label class="radio"><?php echo $form->radio('layoutPresetAction', 'update_existing_preset', true)?> <?php echo t('Update "%s" preset everywhere it is used?', $layoutPreset->getLayoutPresetName())?></label>
			<label class="radio"><?php echo $form->radio('layoutPresetAction', 'save_as_custom')?> <?php echo t('Use this layout here, and leave "%s" unchanged?', $layoutPreset->getLayoutPresetName())?></label>
			<label class="radio"><?php echo $form->radio('layoutPresetAction', 'create_new_preset')?> <?php echo t('Save this style as a new preset?')?><br/><span style="margin-left: 20px"><?php echo $form->text('layoutPresetNameAlt', array('style' => 'width:  127px', 'disabled' => true))?></span></label>
		</div>
	<?php } ?>	

	<div id="layoutPresetActionNew" style="margin-bottom:16px;"> 
		<label for="layoutPresetAction" class="checkbox inline">
			<?php echo $form->checkbox('layoutPresetAction', 'create_new_preset')?> 
			<?php echo t('Save this style as a new preset.')?>
		</label>
		<span style="margin-left: 10px"><?php echo $form->text('layoutPresetName', array('style' => 'width:  127px', 'disabled' => true))?></span>
	</div>
	
	
	
	<?php if(!$_REQUEST['refresh']) { ?>
		<div class="ccm-buttons dialog-buttons">
			<a href="#" class="btn ccm-button-left cancel" onclick="jQuery.fn.dialog.closeTop(); return false"><?php echo t('Cancel')?></a>
			<a href="javascript:void(0)" onclick="$('#ccmAreaLayoutForm').submit()" class="ccm-button-right accept btn primary"><?php echo intval($layout->layoutID)?t('Save Changes'):t('Add')?></a>
		</div>
	<?php } ?>
	

</form>

<script type="text/javascript">
$(function() { ccmLayoutEdit.init(); });
</script>

<?php if (!$_REQUEST['refresh']) { ?>
</div>
<?php } ?>

<?php } ?> 