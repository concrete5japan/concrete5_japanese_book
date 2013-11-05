<?php  defined('C5_EXECUTE') or die("Access Denied.");

class LunchMenuBlockController extends BlockController {
	
	protected $btName = 'ランチメニュー用';
	protected $btDescription = '';
	protected $btTable = 'btDCLunchMenu';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
	public function getSearchableContent() {
		$content = array();
		$content[] = $this->field_6_textbox_text;
		$content[] = $this->field_7_textbox_text;
		$content[] = $this->field_9_textbox_text;
		$content[] = $this->field_10_textbox_text;
		$content[] = $this->field_11_textbox_text;
		$content[] = $this->field_12_textbox_text;
		return implode(' - ', $content);
	}

	public function view() {
		$this->set('field_2_image', (empty($this->field_2_image_fID) ? null : $this->get_image_object($this->field_2_image_fID, 208, 0, false)));
		$this->set('field_4_image', (empty($this->field_4_image_fID) ? null : $this->get_image_object($this->field_4_image_fID, 315, 0, false)));
	}


	public function edit() {
		$this->set('field_2_image', (empty($this->field_2_image_fID) ? null : File::getByID($this->field_2_image_fID)));
		$this->set('field_4_image', (empty($this->field_4_image_fID) ? null : File::getByID($this->field_4_image_fID)));
	}

	public function save($args) {
		$args['field_2_image_fID'] = empty($args['field_2_image_fID']) ? 0 : $args['field_2_image_fID'];
		$args['field_4_image_fID'] = empty($args['field_4_image_fID']) ? 0 : $args['field_4_image_fID'];
		parent::save($args);
	}

	//Helper function for image fields
	private function get_image_object($fID, $width = 0, $height = 0, $crop = false) {
		if (empty($fID)) {
			$image = null;
		} else if (empty($width) && empty($height)) {
			//Show image at full size (do not generate a thumbnail)
			$file = File::getByID($fID);
			$image = new stdClass;
			$image->src = $file->getRelativePath();
			$image->width = $file->getAttribute('width');
			$image->height = $file->getAttribute('height');
		} else {
			//Generate a thumbnail
			$width = empty($width) ? 9999 : $width;
			$height = empty($height) ? 9999 : $height;
			$file = File::getByID($fID);
			$ih = Loader::helper('image');
			$image = $ih->getThumbnail($file, $width, $height, $crop);
		}
	
		return $image;
	}
	


}
