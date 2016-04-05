
/**
 * Copyright [2015] [Dexxtz]
 *
 * @package   Dexxtz_Productzoom
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

var jQuery = jQuery.noConflict();
var b, c, w, h, t, l;
			
b = jQuery('.dexxtz-bg-modal');
c = jQuery('.dexxtz-modal-content');
w = (jQuery('body').innerWidth() / 2);
h = (window.innerHeight / 2);
t = ((h - (c.height() / 2)) < 15) ? '15px' : h - (c.height() / 2) + 'px';
l = ((w - (c.width() / 2)) > 0) ? w - (c.width() / 2) + 'px' : 0;

jQuery(window).resize(function() {
	w = (jQuery('body').innerWidth() / 2);
	h = (window.innerHeight / 2);
	t = ((h - (c.height() / 2)) < 15) ? '15px' : h - (c.height() / 2) + 'px';
	l = ((w - (c.width() / 2)) > 0) ? w - (c.width() / 2) + 'px' : 0;
	
	c.css('top', t)
	c.css('left', l);
});

function DexxtzModal() {			
	c.css('top', t)
	c.css('left', l);
	b.fadeIn('slow');
	c.fadeIn('slow');	
}

function DexxtzModalClose() {
	b.fadeOut('slow');
	c.fadeOut('slow');
}