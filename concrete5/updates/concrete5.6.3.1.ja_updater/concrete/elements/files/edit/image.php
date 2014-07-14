<?php
defined('C5_EXECUTE') or die("Access Denied.");
$u = new User();
$form = Loader::helper('form');
$f = $fv->getFile();
$fp = new Permissions($f);
if (!$fp->canEditFileContents()) {
	die(t("Access Denied."));
}
?>

<script type="text/javascript" src="<?php echo ASSETS_URL_JAVASCRIPT?>/jquery.cropzoom.js"></script>

<div class="ccm-ui">

<div class="ccm-pane-options">
<form class="clearfix">
<a href="javascript:void(0)" class="btn primary" id="ccm-file-manager-edit-save" style="float: right; margin-left: 10px"><?php echo t('Save')?></a>
<a href="javascript:void(0)" class="btn" id="ccm-file-manager-edit-restore" style="float: right"><?php echo t('Undo')?></a>

<div class="span6">
	<label><?php echo t('Zoom')?></label>
	<div class="input" style="margin-top: 11px">
		<div id="ccm-file-manager-zoom-slider"></div>
	</div>
</div>

<div class="span6">
	<label><?php echo t('Rotate')?></label>
	<div class="input" style="margin-top: 11px; position: relative">
		<a href="javascript:void(0)" id="ccm-file-manager-rotate-btn" class="btn" style="position: absolute; top: -10px; right: -50px">&crarr;</a>
		<div id="ccm-file-manager-rotate"></div>
	</div>
	
</div>

</form>
</div>

<div class="clearfix"></div>


<div id="ccm-file-manager-edit-image">

	<div class="PostContent">
		  <div class="boxes">
			  <div id="crop_container"></div>
			  <div class="cleared"></div> 
		  </div>  
	</div>

</div>

</div>


    <script type="text/javascript">
    
    $(document).ready(function(){
       var iw = <?php echo $f->getAttribute('width')?>;
       var ih = <?php echo $f->getAttribute('height')?>;
	   var w = $('#ccm-file-manager-edit-image').closest('.ui-dialog-content').width();
	   var h = $('#ccm-file-manager-edit-image').closest('.ui-dialog-content').height();
	   if (iw > (w + 20)) {
	   	w = iw;
	   } else {
	   	w = w - 20;
	   }
	   
	   if (ih > (h + 100)) {
	   	h = ih;
	   } else {
	   	h = h - 100;
	   }
	   var cropzoom = $('#crop_container').cropzoom({
            width: w,
            height: h,
            bgColor: '#CCC',
            enableRotation:true,
            enableZoom:true,
            zoomSteps:10,
            rotationSteps:1,
            expose: {
            slidersOrientation: 'horizontal',
            rotationElement: '#ccm-file-manager-rotate',
            zoomElement: '#ccm-file-manager-zoom-slider'
            },
            selector:{        
              centered:true,
              borderColor:'blue',
              <?php if ($_REQUEST['maxWidth']) { ?>
              	maxWidth: <?php echo $_REQUEST['maxWidth']?>,
              <?php } ?>
              <?php if ($_REQUEST['maxHeight']) { ?>
              	maxHeight: <?php echo $_REQUEST['maxHeight']?>,
              <?php } ?>
              <?php if ($_REQUEST['minWidth']) { ?>
              	minWidth: <?php echo $_REQUEST['minWidth']?>,
              <?php } ?>
              <?php if ($_REQUEST['minHeight']) { ?>
              	minHeight: <?php echo $_REQUEST['minHeight']?>,
              <?php } ?>
              borderColorHover:'red'
            },
            image:{
                source:'<?php echo $f->getRelativePath()?>',
                width: <?php echo $f->getAttribute('width')?>,
                height:<?php echo $f->getAttribute('height')?>,
                minZoom:5,
                startZoom: 100,
                maxZoom:300
            }
        });
        <?php
        $selectorStartWidth = $f->getAttribute('width');
        $selectorStartHeight = $f->getAttribute('height');
        if ($_REQUEST['maxWidth'] && ($_REQUEST['maxWidth'] < $selectorStartWidth)) {
        	$selectorStartWidth = $_REQUEST['maxWidth'];
        }
        if ($_REQUEST['maxHeight'] && ($_REQUEST['maxHeight'] < $selectorStartHeight)) {
        	$selectorStartHeight = $_REQUEST['maxHeight'];
        }
        if ($_REQUEST['minWidth'] > $selectorStartWidth) {
        	$selectorStartWidth = $_REQUEST['minWidth'];
        }
        if ($_REQUEST['minHeight'] > $selectorStartHeight) {
        	$selectorStartHeight = $_REQUEST['minHeight'];
        }        
        ?>
        
        ssw = <?php echo $selectorStartWidth?>;
        ssh = <?php echo $selectorStartHeight?>;
        
       if (w < ssw) {
       	ssw = w;
       	}
       	if (h < ssh) {
       		ssh = h;
       	}
       cropzoom.setSelector(0,0, ssw , ssh,true);
       
       $('#ccm-file-manager-rotate-btn').click(function() {
        var slideVal = $('#rotationSlider').slider('value');
		var newVal;
		if (slideVal < 90) {
			newVal = 90;
		} else if (slideVal < 180) {
			newVal = 180;
		} else if (slideVal < 270) {
			newVal = 270;
		} else {
			newVal = 0;
		}
       	$('#rotationSlider').slider('value', newVal);
       });
       
       $('#ccm-file-manager-edit-save').click(function(){ 
       		jQuery.fn.dialog.showLoader();
            cropzoom.send('<?php echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/image/process','POST',{
            	'fID': <?php echo $f->getFileID()?>,
            },function(rta){
            	jQuery.fn.dialog.hideLoader();
				highlight = new Array();
				highlight.push(<?php echo $f->getFileID()?>);
				jQuery.fn.dialog.closeTop();
				ccm_alRefresh(highlight, '<?php echo Loader::helper('text')->entities($_REQUEST['searchInstance'])?>');
            });            
        });
       
       $('#ccm-file-manager-edit-restore').click(function(){
            cropzoom.restore();
        })
    })
</script>
<style type="text/css">
	#img_to_crop{
		-webkit-user-drag: element;
		-webkit-user-select: none;
	}
</style>
