<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
	<div class="row">
		<div class="twelve columns">
			<p class="right scroll-page-top"><a href="#page">ページの先頭へ</a></p>
		</div>
	</div>
	
	<footer id="pageFooter" class="row" role="contentinfo">
		<div class="twelve columns">
			<hr />
			<div class="row">
				<div class="seven columns">
					<?php 
					$a = new GlobalArea('Footer');
					$a->display();
					?>
				</div>
				<div class="five columns">
					<p class="copyright right"><small>Copyright &copy; <?php echo date('Y')?> <?php echo SITE?></small></p>
				</div>
			</div>
		</div> 
	</footer>

</div>

<?php  Loader::element('footer_required'); ?>

<script src="<?php echo $this->getThemePath(); ?>/javascripts/jquery.foundation.forms.js"></script>
<script src="<?php echo $this->getThemePath(); ?>/javascripts/jquery.foundation.orbit.js"></script>
<script src="<?php echo $this->getThemePath(); ?>/main.js"></script>

</body>
</html>
