<?php  defined('C5_EXECUTE') or die("Access Denied.");

class [[[GENERATOR_REPLACE_CLASSNAME]]] extends BlockController {
	
	protected $btName = '[[[GENERATOR_REPLACE_NAME]]]';
	protected $btDescription = '[[[GENERATOR_REPLACE_DESCRIPTION]]]';
[[[GENERATOR_REPLACE_TABLEDEF]]]
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
[[[GENERATOR_REPLACE_GETSEARCHABLECONTENT]]]
[[[GENERATOR_REPLACE_VIEW]]]
[[[GENERATOR_REPLACE_ADD]]]
[[[GENERATOR_REPLACE_EDIT]]]
[[[GENERATOR_REPLACE_SAVE]]]
[[[GENERATOR_REPLACE_IMAGEHELPER]]]
[[[GENERATOR_REPLACE_URLHELPER]]]
[[[GENERATOR_REPLACE_CONTENTHELPER]]]
}
