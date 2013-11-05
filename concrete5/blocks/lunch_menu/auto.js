ccmValidateBlockForm = function() {
	
	if ($('#field_2_image_fID-fm-value').val() == '' || $('#field_2_image_fID-fm-value').val() == 0) {
		ccm_addError('Missing required image: 料理画像');
	}

	if ($('#field_4_image_fID-fm-value').val() == '' || $('#field_4_image_fID-fm-value').val() == 0) {
		ccm_addError('Missing required image: メニュー文字画像');
	}

	if ($('#field_6_textbox_text').val() == '') {
		ccm_addError('Missing required text: メニュー');
	}


	return false;
}
