<?php 
defined('C5_EXECUTE') or die("Access Denied.");

/*
書籍内ではBlog Entryテンプレートの解説はしていませんが、
Greek Yogurtなど同梱テーマをもとに作成することができます。
*/

$this->inc('elements/header.php'); ?>
	
	<div class="row">
		<?php
		$a = new GlobalArea('Breadcrumbs');
		$a->display();
		?>
		<hr />
		<section id="main" class="eight columns push-four" role="main">
			
			<h1><?php echo $c->getCollectionName(); ?></h1>
		
			<?php
			/*
			ページリストブロックのBlog Index Thumbnailテンプレートは、
			ページ内の'Thumbnail Image'エリア内の画像ブロックの画像を表示するしくみになっています。
			編集モードのときのみ'Thumbnail Image'エリアが現れるようになっています。
			*/
			if ($c->isEditMode()) {
				$a = new Area('Thumbnail Image');
				$a->display($c);
				echo '<hr>';
			}
			?>
			
			<?php 
			$a = new Area('Main');
			$a->display($c);
			?>
			
			<div id="main-content-post-author" class="panel">
			<?php
			/*
			Greek Yogurtテーマのコードを流用して執筆者と日付を表示しています。
			*/
			$vo = $c->getVersionObject();
			if (is_object($vo)) {
				$uID = $vo->getVersionAuthorUserID();
				$username = $vo->getVersionAuthorUserName();
				if (Config::get("ENABLE_USER_PROFILES")) {
					$profileLink= '<a href="' . $this->url('/profile/view/', $uID) . '">' . $username . '</a>';
				}else{ 
					$profileLink = $username;
				} ?>
				<p>
					<?php echo t(
						/*i18n: %1$s is an author name, 2$s is an URL, %3$s is a date, %4$s is a time */
						'Posted by <span class="post-author">%1$s at <a href="%2$s">%3$s on %4$s</a></span>',
						$profileLink,
						$c->getLinkToCollection,
						$c->getCollectionDatePublic(DATE_APP_GENERIC_T),
						$c->getCollectionDatePublic(DATE_APP_GENERIC_MDY_FULL)
					); ?>
				</p>
			<?php	 } ?>
			</div>
			
		</section>
		
		<aside class="sidebar four columns pull-eight" role="complementary">
		
			<?php 
			$a = new Area('Sidebar');
			$a->display($c);
			?>
			
		</aside>
	</div>
	
<?php $this->inc('elements/footer.php'); ?>
