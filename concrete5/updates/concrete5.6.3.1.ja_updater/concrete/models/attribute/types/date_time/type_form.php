<fieldset>
<legend><?php echo t('Date/Time Options')?></legend>

<div class="clearfix">
<?php echo $form->label('akDateDisplayMode', t('Ask User For'))?>
<div class="input">
<?php 
	$akDateDisplayModeOptions = array(
		'date_time' => t('Both Date and Time'),
		'date' => t('Date Only'),
		'text' => t('Text Input Field')

	);
	?>
<?php echo $form->select('akDateDisplayMode', $akDateDisplayModeOptions, $akDateDisplayMode)?>
</div>
</div>

</fieldset>