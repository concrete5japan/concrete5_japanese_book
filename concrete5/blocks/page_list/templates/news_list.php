<?php
defined('C5_EXECUTE') or die("Access Denied.");
$rssUrl = $showRss ? $controller->getRssUrl($b) : '';
$th = Loader::helper('text');
//$ih = Loader::helper('image'); //<--uncomment this line if displaying image attributes (see below)
$dh = Loader::helper('date'); //<--uncomment this line if displaying dates (see below)
//Note that $nh (navigation helper) is already loaded for us by the controller (for legacy reasons)
?>

<div class="news-list hfeed">

	<?php foreach ($pages as $page):

		// Prepare data for each page being listed...
		$title = $th->entities($page->getCollectionName());
		$url = $nh->getLinkToCollection($page);
		$target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
		$target = empty($target) ? '_self' : $target;
		$description = $page->getCollectionDescription();
		$description = $controller->truncateSummaries ? $th->shorten($description, $controller->truncateChars) : $description;
		$description = $th->entities($description);	
		
		//Other useful page data...
		
		$date = $dh->date(DATE_APP_GENERIC_MDY_FULL, strtotime($page->getCollectionDatePublic()));
		//If you use the date also uncomment the "$dh = Loader::helper('date');" line on top 
		
		//$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();
		
		//$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();
		
		/* CUSTOM ATTRIBUTE EXAMPLES:
		 * $example_value = $page->getAttribute('example_attribute_handle');
		 *
		 * HOW TO USE IMAGE ATTRIBUTES:
		 * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
		 * 2) Put in some code here like the following 2 lines:
		 *      $img = $page->getAttribute('example_image_attribute_handle');
		 *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
		 *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
		 *    (Change the last argument from false to true if you want thumbnails cropped.)
		 * 3) Output the image tag below like this:
		 *		<img src="<?php echo $thumb->src ?>" width="<?php echo $thumb->width ?>" height="<?php echo $thumb->height ?>" alt="" />
		 *
		 * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
		 * 1) Put in some code here like the following 2 lines:
		 * 	    $img_src = $img->getRelativePath();
		 * 	    list($img_width, $img_height) = getimagesize($img->getPath());
		 * 2) Output the image tag below like this:
		 * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
		 */
		
		/* End data preparation. */

		/* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>
		<div class="hentry entry">
			<div class="published"><?php echo $date ?></div>
			<div class="entry-title"><a href="<?php echo $url ?>" target="<?php echo $target ?>" rel="bookmark"><?php echo $title ?></a></div>
		</div>
		
	<?php endforeach; ?>
 

	<?php if ($showRss): ?>
		<div class="ccm-page-list-rss-icon">
			<a href="<?php echo $rssUrl ?>" target="_blank"><img src="<?php echo $rssIconSrc ?>" width="14" height="14" alt="<?php echo t('RSS Icon') ?>" title="<?php echo t('RSS Feed') ?>" /></a>
		</div>
		<link href="<?php echo BASE_URL.$rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php echo $rssTitle; ?>" />
	<?php endif; ?>
 
</div><!-- end .ccm-page-list -->


<?php if ($showPagination): ?>
	<?php
	// set current class name
	$paginator->classCurrent = 'current';
	?>
	<ul class="pagination">
		<li class="arrow"><?php echo $paginator->getPrevious('&laquo;','a') ?></li>
		<?php echo $paginator->getPages('li') ?>
		<li class="arrow"><?php echo $paginator->getNext('&raquo;','a') ?></li>
	</ul>
<?php endif; ?>
