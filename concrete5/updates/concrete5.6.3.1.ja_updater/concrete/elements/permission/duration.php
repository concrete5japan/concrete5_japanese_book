<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php

$repeats = array(
	'' => t('** Options'), 
	'daily' => t('Every Day'),
	'weekly' => t('Every Week'),
	'monthly' => t('Every Month')
);
$repeatDays = array();
for ($i = 1; $i <= 30; $i++) {
	$repeatDays[$i] = $i;
}
$repeatWeeks = array();
for ($i = 1; $i <= 30; $i++) {
	$repeatWeeks[$i] = $i;
}
$repeatMonths = array();
for ($i = 1; $i <= 12; $i++) {
	$repeatMonths[$i] = $i;
}


Loader::library('3rdparty/Zend/Locale');
$list = Zend_Locale::getTranslationList('Days', ACTIVE_LOCALE);


$pdStartDate = false;
$pdEndDate = false;
$pdRepeats = false;
$pdRepeatPeriod = false;
$pdRepeatPeriodWeekDays = array();
$pdRepeatPeriodDaysEvery = 1;
$pdRepeatPeriodWeeksEvery = 1;
$pdRepeatPeriodMonthsEvery = 1;
$pdRepeatPeriodMonthsRepeatBy = 'month';
$pdEndRepeatDateSpecific = false;
$pdEndRepeatDate = '';
if (is_object($pd)) {
	$pdStartDate = $pd->getStartDate();
	$pdEndDate = $pd->getEndDate();
	$pdRepeats = $pd->repeats();
	$pdStartDateAllDay = $pd->isStartDateAllDay(); 
	$pdEndDateAllDay = $pd->isEndDateAllDay(); 
	$pdRepeatPeriod = $pd->getRepeatPeriod();
	$pdRepeatPeriodWeekDays = $pd->getRepeatPeriodWeekDays();
	if ($pdRepeatPeriod == 'daily') {
		$pdRepeatPeriodDaysEvery = $pd->getRepeatPeriodEveryNum();
	}
	if ($pdRepeatPeriod == 'weekly') {
		$pdRepeatPeriodWeeksEvery = $pd->getRepeatPeriodEveryNum();
	}
	if ($pdRepeatPeriod == 'monthly') {
		$pdRepeatPeriodMonthsEvery = $pd->getRepeatPeriodEveryNum();
	}
	if ($pd->getRepeatMonthBy() != '') {
		$pdRepeatPeriodMonthsRepeatBy = $pd->getRepeatMonthBy();
	}
	$pdEndRepeatDateSpecific = $pd->getRepeatPeriodEnd();
	if ($pdEndRepeatDateSpecific) {
		$pdEndRepeatDate = 'date';
	}
}
$form = Loader::helper('form');
$dt = Loader::helper('form/date_time');

?>


<div id="ccm-permissions-access-entity-dates" class="form-horizontal">

<div class="control-group">
<?php echo $form->label('pdStartDate_activate', tc('Start date', 'From'))?>
<div class="controls">
	<?php echo $dt->datetime('pdStartDate', $pdStartDate, true);?>
	<label class="checkbox inline"><?php echo $form->checkbox('pdStartDateAllDayActivate', 1, $pdStartDateAllDay)?> <?php echo t("All Day")?></label>
</div>
</div>

<div class="control-group">
<?php echo $form->label('pdEndDate_activate', tc('End date', 'To'))?>
<div class="controls">
	<?php echo $dt->datetime('pdEndDate', $pdEndDate, true);?>
	<label class="checkbox inline"><?php echo $form->checkbox('pdEndDateAllDayActivate', 1, $pdEndDateAllDay)?> <?php echo t("All Day")?></label>
</div>
</div>

</div>

<div id="ccm-permissions-access-entity-repeat" style="display: none" class="form-horizontal">

<div class="control-group">
<label class="control-label"></label>
<div class="controls">
	<label class="checkbox"><?php echo $form->checkbox('pdRepeat', 1, $pdRepeats)?> <span><?php echo t('Repeat...')?></span></label>
</div>
</div>

<div id="ccm-permissions-access-entity-repeat-selector" style="display: none" class="form-horizontal">


<div class="control-group">
<?php echo $form->label('pdRepeatPeriod', t('Repeats'))?>
<div class="controls">
	<?php echo $form->select('pdRepeatPeriod', $repeats, $pdRepeatPeriod)?>	
</div>
</div>

<div id="ccm-permissions-access-entity-dates-repeat-daily" style="display: none" class="form-horizontal">

<div class="control-group">
<?php echo $form->label('pdRepeatPeriodDaysEvery', t('Repeat every'))?>
<div class="controls">
	<?php echo $form->select('pdRepeatPeriodDaysEvery', $repeatDays, $pdRepeatPeriodDaysEvery, array('style' => 'width: 60px'))?>
	<?php echo t('days')?>
</div>
</div>

</div>

<div id="ccm-permissions-access-entity-dates-repeat-monthly" style="display: none" class="form-horizontal">


<div class="control-group">
<?php echo $form->label('pdRepeatPeriodMonthsRepeatBy', t('Repeat By'))?>
<div class="controls">
	<label><?php echo $form->radio('pdRepeatPeriodMonthsRepeatBy', 'month', $pdRepeatPeriodMonthsRepeatBy)?> <span><?php echo t('Day of Month')?></span></label>
	<label><?php echo $form->radio('pdRepeatPeriodMonthsRepeatBy', 'week', $pdRepeatPeriodMonthsRepeatBy)?> <span><?php echo t('Day of Week')?></span></label>
</div>
</div>

<div class="control-group">
<?php echo $form->label('pdRepeatPeriodMonthsEvery', t('Repeat every'))?>
<div class="controls">
	<?php echo $form->select('pdRepeatPeriodMonthsEvery', $repeatMonths, $pdRepeatPeriodMonthsEvery, array('style' => 'width: 60px'))?>
	<?php echo t('months')?>
</div>
</div>

</div>


<div id="ccm-permissions-access-entity-dates-repeat-weekly" style="display: none" class="form-horizontal">


<div id="ccm-permissions-access-entity-dates-repeat-weekly-dow" style="display: none">

<div class="control-group">
<label class="control-label"><?php echo t('On')?></label>
<div class="controls">
<?php 
$x = 0;
foreach($list['format']['wide'] as $key => $value) { ?>
	<label><input <?php if (in_array($x, $pdRepeatPeriodWeekDays)) { ?>checked="checked" <?php } ?>
	type="checkbox" name="pdRepeatPeriodWeeksDays[]" value="<?php echo $x?>" /> <span><?php echo $value?></span></label>
	
<?php
	$x++;
} ?>
</div>
</div>

</div>

<div class="control-group">
<?php echo $form->label('pdRepeatPeriodWeeksEvery', t('Repeat every'))?>
<div class="controls">
	<?php echo $form->select('pdRepeatPeriodWeeksEvery', $repeatWeeks, $pdRepeatPeriodWeeksEvery, array('style' => 'width: 60px'))?>
	<?php echo t('weeks')?>
</div>
</div>

</div>

<div id="ccm-permissions-access-entity-dates-repeat-dates" style="display: none" class="form-horizontal">



<div class="clearfix">
<label><?php echo t('Starts On')?></label>
<div class="controls">
	<input type="text" disabled="disabled" value="" name="pdStartRepeatDate"   />
</div>
</div>

<div class="control-group">
<?php echo $form->label('pdEndRepeatDate', t('Ends'))?>
<div class="controls">
	<label><?php echo $form->radio('pdEndRepeatDate', '', $pdEndRepeatDate)?> <span><?php echo t('Never')?></span></label>
	<label><?php echo $form->radio('pdEndRepeatDate', 'date', $pdEndRepeatDate)?> <?php echo $dt->date('pdEndRepeatDateSpecific', $pdEndRepeatDateSpecific)?></label>
</div>


</div>


</div>

</div>
</div>

<script type="text/javascript">
ccm_accessEntityCalculateRepeatOptions = function() {
	// get the difference between start date and end date
	if (!$("#pdStartDate_activate").is(':checked')) {
		return false;
	}
       
	var sdf = ($("#pdStartDate_dt_pub").datepicker('option', 'altFormat'));
	var sdfr = $.datepicker.parseDate(sdf, $("#pdStartDate_dt").val());
	var edf = ($("#pdEndDate_dt_pub").datepicker('option', 'altFormat'));
	var edfr = $.datepicker.parseDate(edf, $("#pdEndDate_dt").val());
	var sh = $("select[name=pdStartDate_h]").val();
	var eh = $("select[name=pdEndDate_h]").val();
	if ($("select[name=pdStartDate_a]").val() == 'PM' && (sh < 12)) { 
		sh = parseInt(sh) + 12;
	} else if (sh == 12 && $("select[name=pdStartDate_a]").val() == 'AM') { 
		sh = 0;
	}
	if ($("select[name=pdEndDate_a]").val() == 'PM' && (eh < 12)) { 
		eh = parseInt(eh) + 12;
	} else if (eh == 12 && $("select[name=pdEndDate_a]").val() == 'AM') { 
		eh = 0;
	}
	var startDate = new Date(sdfr.getFullYear(), sdfr.getMonth(), sdfr.getDate(), sh, $('select[name=pdStartDate_m]').val(), 0);
	var endDate = new Date(edfr.getFullYear(), edfr.getMonth(), edfr.getDate(), eh, $('select[name=pdEndDate_m]').val(), 0);
	var difference = ((endDate.getTime() / 1000) - (startDate.getTime() / 1000));
	if (difference >= 60 * 60 * 24) {
		$('select[name=pdRepeatPeriod] option[value=daily]').attr('disabled', true);
		$("#ccm-permissions-access-entity-dates-repeat-weekly-dow").hide();
	} else {
		$('select[name=pdRepeatPeriod] option[value=daily]').attr('disabled', false);
		$("#ccm-permissions-access-entity-dates-repeat-weekly-dow").show();
	}
	$('input[name=pdStartRepeatDate]').val($("#pdStartDate_dt_pub").val());
	switch(sdfr.getDay()) {
		case 0:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=0]").attr('checked', true);
			break;
		case 1:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=1]").attr('checked', true);
			break;
		case 2:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=2]").attr('checked', true);
			break;
		case 3:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=3]").attr('checked', true);
			break;
		case 4:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=4]").attr('checked', true);
			break;
		case 5:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=5]").attr('checked', true);
			break;
		case 6:
			$("#ccm-permissions-access-entity-dates-repeat-weekly-dow input[value=6]").attr('checked', true);
			break;
	}
}

ccm_accessEntityCheckRepeat = function() {
	if ($('input[name=pdRepeat]').is(':checked')) { 
		$("#ccm-permissions-access-entity-repeat-selector").show();
	} else { 
		$("#ccm-permissions-access-entity-repeat-selector").hide();
	}
}

ccm_accessEntityOnActivateDates = function() {
	if ($("#pdStartDate_activate").is(':checked') || $("#pdEndDate_activate").is(':checked')) {
		ccm_accessEntityCalculateRepeatOptions();
	}
	if ($("#pdStartDate_activate").is(':checked') && $("#pdEndDate_activate").is(':checked')) {
		$("#ccm-permissions-access-entity-repeat").show();
	} else {
		$("#ccm-permissions-access-entity-repeat").hide();
	}
	if ($("#pdStartDate_activate").is(':checked')) {
		$('#pdStartDateAllDayActivate').attr('disabled', false);		
	} else {
		$('input[name=pdStartDateAllDayActivate]').attr('disabled', true);
	}
	if ($("#pdEndDate_activate").is(':checked')) {
		$('#pdEndDateAllDayActivate').attr('disabled', false);		
	} else {
		$('input[name=pdEndDateAllDayActivate]').attr('disabled', true);
	}

	if ($("input[name=pdStartDateAllDayActivate]").is(':checked')) {
		$('span#pdStartDate_tw').hide();
	} else {
		$('span#pdStartDate_tw').show();
	}

	if ($("input[name=pdEndDateAllDayActivate]").is(':checked')) {
		$('span#pdEndDate_tw').hide();
	} else {
		$('span#pdEndDate_tw').show();
	}

}

ccm_accessEntityOnRepeatPeriodChange = function() {
	$("#ccm-permissions-access-entity-dates-repeat-daily").hide();
	$("#ccm-permissions-access-entity-dates-repeat-weekly").hide();
	$("#ccm-permissions-access-entity-dates-repeat-monthly").hide();
	if ($('select[name=pdRepeatPeriod]').val() != '') { 
		$("#ccm-permissions-access-entity-dates-repeat-" + $('select[name=pdRepeatPeriod]').val()).show();
		$("#ccm-permissions-access-entity-dates-repeat-dates").show();
	}
}

ccm_accessEntityCalculateRepeatEnd = function() {
	if ($('input[name=pdEndRepeatDate]:checked').val() == 'date') { 
		$("#ccm-permissions-access-entity-dates-repeat-dates .ccm-input-date-wrapper input").attr('disabled', false);
	} else {
		$("#ccm-permissions-access-entity-dates-repeat-dates .ccm-input-date-wrapper input").attr('disabled', true);
	}
}

$(function() {
	$("#ccm-permissions-access-entity-dates input[type=checkbox]").click(function() {
		ccm_accessEntityOnActivateDates();
	});	
	
	$("select[name=pdRepeatPeriod]").change(function() {
		ccm_accessEntityOnRepeatPeriodChange();
	});
	
	$("input[name=pdRepeat]").click(function() {
		ccm_accessEntityCheckRepeat();
	});

	$("#ccm-permissions-access-entity-dates span.ccm-input-date-wrapper input, #ccm-permissions-access-entity-dates span.ccm-input-time-wrapper select").change(function() {
		ccm_accessEntityCalculateRepeatOptions();
	});
	$("#ccm-permissions-access-entity-dates-repeat-dates input.ccm-input-date").attr('disabled', true);
	$('input[name=pdEndRepeatDate]').change(function() {
		ccm_accessEntityCalculateRepeatEnd();
	});
	ccm_accessEntityCalculateRepeatOptions();
	ccm_accessEntityOnActivateDates();
	ccm_accessEntityCheckRepeat();	
	ccm_accessEntityOnRepeatPeriodChange();
	ccm_accessEntityCalculateRepeatEnd();
});
</script>

<style type="text/css">
#ccm-permissions-access-entity-wrapper .ccm-activate-date-time {margin-right: 8px;}
</style>
