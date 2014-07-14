<?php
defined('C5_EXECUTE') or die("Access Denied.");
$mi = Marketplace::getInstance();
if ($mi->hasConnectionError() && $mi->getConnectionError() == Marketplace::E_MARKETPLACE_SUPPORT_MANUALLY_DISABLED) { ?>
	<p><?php echo t('Marketplace integration disabled in configuration file.')?></p>

<?php } else {

	$h = Loader::helper('concrete/interface');
	?>
	
	<p><?php echo t('Your site is <strong>not</strong> connected to the concrete5 community.')?></p>
	
	<?php
	if ($mi->hasConnectionError()) { ?>
		<div class="ccm-error block-message alert-message error"><p>
		<?php
		switch($mi->getConnectionError()) {
			case Marketplace::E_INVALID_BASE_URL:
				print t('The base URL of your site does not match a registered instance of the site. Please click below to authenticate your site again.');
				break;
			case Marketplace::E_UNRECOGNIZED_SITE_TOKEN:
				print t('Unable to connect to your project page. Your database contains a marketplace token which concrete5.org cannot verify.');
				break;
			//case Marketplace::E_GENERAL_CONNECTION_ERROR:
			default:
				print t('Error establishing connection to the concrete5 community. Please check that curl and other required libraries are enabled.');
				break;
	}
		?>
		</p>
		</div>
		<?php
	} else { ?>
		
		<p><?php echo t('Setting up a project page for your site on concrete5.org is safe and private, and gives you lots of benefits including:')?></p>
		
		
		<ul>
			<li><?php echo t('Automatically install add-ons and themes with a mouse click.')?></li>
			<li><?php echo t('Ensure your software is up to date and stable.')?></li>
			<li><?php echo t('Get support from developers.')?></li>
			<li><?php echo t('And much more!')?></li>
		</ul>
		
		<p><?php echo t('It only takes a moment and you don\'t even have to leave your site.')?></p>
		
	
	<?php } ?>
	
	
	<?php print $h->button(t('Connect to Community'), View::url('/dashboard/extend/connect'), '', 'primary')?>
<?php } ?>