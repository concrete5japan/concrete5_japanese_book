<?php defined('C5_EXECUTE') or die('Access Denied');

echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Search Index'), t('Choose which areas on your site are indexed.'), 'span6 offset3', false); ?>
	<form method="post" id="ccm-search-index-manage" action="<?php echo $this->action('')?>">
		<div class="ccm-pane-body">
			<?php echo $this->controller->token->output('update_search_index');?>
			<fieldset>
			<legend><?php echo t('Indexing Method')?></legend>
			<div class="control-group">
			<?php $methods = array(
				'whitelist' => t('Whitelist: Selected areas are only areas indexed.'),
				'blacklist' => t('Blacklist: Every area but the selected areas are indexed.')
			);
			print $form->select('SEARCH_INDEX_AREA_METHOD', $methods, IndexedSearch::getSearchableAreaAction(), array('class'=>'xlarge'));?>
			</div>
			</fieldset>
			
			<fieldset>
			<legend><?php echo t('Areas')?></legend>
			<div class="control-group">	

			<?php foreach($areas as $a) { ?>
				<label class="checkbox"><?php echo $form->checkbox('arHandle[]', $a, in_array($a, $selectedAreas))?> <?php echo $a?></label>
			<?php } ?>
			</div>
			</fieldset>

		</div>
		<div class="ccm-pane-footer">
			<button class="error btn ccm-button-left" name="reindex" value="1" onclick="return confirm('<?php echo t('Once the index is clear, you must reindex your site from the Automated Jobs page.')?>')"><?php echo t('Clear Search Index')?></button>
			<?php
			$ih = Loader::helper('concrete/interface');
			print $ih->submit(t('Save'), 'ccm-search-index-manage', 'right', 'primary');
			?>
		</div>
	</form>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>