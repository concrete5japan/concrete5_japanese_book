<?php defined('C5_EXECUTE') or die('Access Denied');
$form = Loader::helper('form');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Excluded URL Word List'), t("Words listed here will be automatically removed from url slugs."), false, false); ?>

<form method="post" id="url-form" action="<?php echo $this->action('save')?>">
	<div class="ccm-pane-body">
		<div class="control-group">
			<textarea style='width:100%;height:100px' name='SEO_EXCLUDE_WORDS'><?php echo $SEO_EXCLUDE_WORDS?></textarea>
		</div>
		<div class="alert alert-info"><?php echo t('Separate reserved words with a comma. These words will be automatically removed from URL slugs. To remove no words from URLs, delete all the words above.')?></div>

	</div>
	<div class="ccm-pane-footer">
	<?php if (count($SEO_EXCLUDE_WORDS_ORIGINAL_ARRAY) != count($SEO_EXCLUDE_WORDS_ARRAY) || !$SEO_EXCLUDE_WORDS) { ?>
		<a href="<?php echo $this->action('reset')?>" class="btn pull-left"><?php echo t('Reset To Default')?></a>
	<?php } ?>
		<?php echo $interface->submit(t('Save'), null, 'right', 'primary');?>
	</div>
</form>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper();
