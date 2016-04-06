$.noConflict();
function giftwrapid(id) {
	jQuery('#giftwrap_product_id').val(id);
	jQuery('#popup').show();
	jQuery('#popup').css('z-index','1000');
	jQuery('body').prepend('<div class="overlay">&nbsp;</div>');
}
jQuery(document).ready(function() {
	jQuery('.close-btn').click(function() {
		jQuery('#popup').hide();
		jQuery('.overlay').remove();
	});
});
