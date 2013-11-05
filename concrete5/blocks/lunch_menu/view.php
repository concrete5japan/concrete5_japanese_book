<?php  defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="menu-item clearfix">

<?php  if (!empty($field_2_image)): ?>
	<div class="photo">
	<img src="<?php  echo $field_2_image->src; ?>" width="<?php  echo $field_2_image->width; ?>" height="<?php  echo $field_2_image->height; ?>" alt="" />
	</div>
<?php  endif; ?>

<div class="summary">

<?php  if (!empty($field_4_image)): ?>
	<h2 class="menu-item-title">
	<img src="<?php  echo $field_4_image->src; ?>" width="<?php  echo $field_4_image->width; ?>" height="<?php  echo $field_4_image->height; ?>" alt="<?php  echo $field_4_image_altText; ?>" />
	</h2>
<?php  endif; ?>

<p>

<?php  if (!empty($field_6_textbox_text)): ?>
	<?php  echo htmlentities($field_6_textbox_text, ENT_QUOTES, APP_CHARSET); ?><br />
<?php  endif; ?>

<?php  if (!empty($field_7_textbox_text)): ?>
	<em>￥<?php  echo htmlentities($field_7_textbox_text, ENT_QUOTES, APP_CHARSET); ?>ー</em>
<?php  endif; ?>

</p>

<?php  if (!empty($field_9_textbox_text)): ?>
	<h3>食後のお飲み物</h3><p><b>＋￥<?php  echo htmlentities($field_9_textbox_text, ENT_QUOTES, APP_CHARSET); ?>ー</b>
<?php  endif; ?>

<?php  if (!empty($field_10_textbox_text)): ?>
	<?php  echo htmlentities($field_10_textbox_text, ENT_QUOTES, APP_CHARSET); ?><br />
<?php  endif; ?>

<?php  if (!empty($field_11_textbox_text)): ?>
	<b>＋￥<?php  echo htmlentities($field_11_textbox_text, ENT_QUOTES, APP_CHARSET); ?>ー</b>
<?php  endif; ?>

<?php  if (!empty($field_12_textbox_text)): ?>
	<?php  echo htmlentities($field_12_textbox_text, ENT_QUOTES, APP_CHARSET); ?>
<?php  endif; ?>

</p>

</div><!-- summary -->

</div><!-- menu-item clearfix -->


