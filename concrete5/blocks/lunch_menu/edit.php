<?php  defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library');
?>

<style type="text/css" media="screen">
	.ccm-block-field-group h2 { margin-bottom: 5px; }
	.ccm-block-field-group td { vertical-align: middle; }
</style>

<div class="ccm-block-field-group">
	<h2>料理画像</h2>
	<?php  echo $al->image('field_2_image_fID', 'field_2_image_fID', '画像を選択', $field_2_image); ?>
</div>

<div class="ccm-block-field-group">
	<h2>メニュー文字画像</h2>
	<?php  echo $al->image('field_4_image_fID', 'field_4_image_fID', '画像を選択', $field_4_image); ?>

	<table border="0" cellspacing="3" cellpadding="0" style="width: 95%; margin-top: 5px;">
		<tr>
			<td align="right" nowrap="nowrap"><label for="field_4_image_altText">Alt Text:</label>&nbsp;</td>
			<td align="left" style="width: 100%;"><?php  echo $form->text('field_4_image_altText', $field_4_image_altText, array('style' => 'width: 100%;')); ?></td>
		</tr>
	</table>
</div>

<div class="ccm-block-field-group">
	<h2>メニュー</h2>
	<?php  echo $form->text('field_6_textbox_text', $field_6_textbox_text, array('style' => 'width: 95%;')); ?>
</div>

<div class="ccm-block-field-group">
	<h2>メニュー金額</h2>
	<?php  echo $form->text('field_7_textbox_text', $field_7_textbox_text, array('style' => 'width: 95%;')); ?>
</div>

<div class="ccm-block-field-group">
	<h2>食後の飲み物１金額</h2>
	<?php  echo $form->text('field_9_textbox_text', $field_9_textbox_text, array('style' => 'width: 95%;')); ?>
</div>

<div class="ccm-block-field-group">
	<h2>食後の飲み物１メニュー</h2>
	<?php  echo $form->text('field_10_textbox_text', $field_10_textbox_text, array('style' => 'width: 95%;')); ?>
</div>

<div class="ccm-block-field-group">
	<h2>食後の飲み物２金額</h2>
	<?php  echo $form->text('field_11_textbox_text', $field_11_textbox_text, array('style' => 'width: 95%;')); ?>
</div>

<div class="ccm-block-field-group">
	<h2>食後の飲み物２メニュー</h2>
	<?php  echo $form->text('field_12_textbox_text', $field_12_textbox_text, array('style' => 'width: 95%;')); ?>
</div>


