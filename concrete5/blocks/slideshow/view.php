<?php	 defined('C5_EXECUTE') or die("Access Denied."); ?>
<div id="orbit">
	<?php	 
	foreach($images as $imgInfo) {
		$f = File::getByID($imgInfo['fID']);
		$fp = new Permissions($f);
		if ($fp->canViewFile()) {
			?>
			<div>
				<?php if($imgInfo['url']): ?><a href="<?php	echo $imgInfo['url']?>"><?php endif; ?>
				<img src="<?php	echo $f->getRelativePath()?>" alt="<?php	echo $f->getFileName()?>" />
				<?php if($imgInfo['url']): ?></a><?php endif; ?>
			</div>
		<?php	 }
		} ?>
</div>
