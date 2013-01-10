<?php	 defined('C5_EXECUTE') or die(_("Access Denied."));
$navItems = $controller->getNavItems();

echo '<ul class="breadcrumbs">';

foreach ($navItems as $ni) {
	if ($ni->isCurrent) {
		echo '<li class="current"><span>' . $ni->name . '</span></li>';
	} else {
		echo '<li><a href="' . $ni->url . '" target="' . $ni->target . '">' . $ni->name . '</a></li>';
	}
}

echo '</ul>';
