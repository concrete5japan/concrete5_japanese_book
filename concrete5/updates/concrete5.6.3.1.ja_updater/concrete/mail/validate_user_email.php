<?php

defined('C5_EXECUTE') or die("Access Denied.");

$subject = SITE . " " . t("Registration - Validate Email Address");
$body = t("

You must click the following URL in order to activate your account for %s:

%s 

Thanks for your interest in %s

", SITE, BASE_URL . View::url('/login', 'v', $uHash), SITE);

?>