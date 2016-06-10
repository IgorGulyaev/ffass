<?php

/**
 * Copyright [2015] [Dexxtz]
 *
 * @package   Dexxtz_Productzoom
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

class Dexxtz_Productzoom_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getActive()
	{
		$active = Mage::getStoreConfig('dexxtz_productzoom/general/active');
		
		return $active;
	}

	public function getAutoplay()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/general/autoplay');
		$autoplay = ($value == true) ? 'true' : 'false';
		
		return $autoplay;
	}

	public function getAutoplayInterval()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/general/autoplay_interval');
		$autoplayInterval = ($value) ? $value : 6000;
		
		return $autoplayInterval;
	}

	public function getFadeInterval()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/general/fadein_interval');
		$fadeInterval = ($value) ? $value : 0;
		
		return $fadeInterval;
	}

	public function getShowIcon()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/general/magnifier_icon');
		$magnifierIcon = ($value == true) ? 'true' : 'false';
		
		return $magnifierIcon;
	}

	public function getFeaturedWidth()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/featured_settings/featured_width');
		$featuredWidth = ($value) ? $value : '300';
		
		return $featuredWidth;
	}

	public function getFeaturedHeight()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/featured_settings/featured_height');
		$featuredHeight = ($value) ? $value : '300';
		
		return $featuredHeight;
	}

	public function getThubnailsQty()
	{
		$qty = Mage::getStoreConfig('dexxtz_productzoom/thumbnails_settings/thumbnails_qty');		
		$qty = ($this->getVideoActive() == 1) ? $qty + 1: $qty;
		
		return $qty;
	}

	public function getThubnailsPosition()
	{
		$position = Mage::getStoreConfig('dexxtz_productzoom/thumbnails_settings/thumbnails_position');
		
		return $position;
	}

	public function getImageZoomWidth()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/zoom_image_width');
		$zoomWidth = ($value) ? $value : 300;
		
		return $zoomWidth;
	}

	public function getImageZoomHeight()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/zoom_image_height');
		$zoomHeight = ($value) ? $value : 300;
		
		return $zoomHeight;
	}

	public function getZoomAreaWidth()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/zoom_area_width');
		$areaWidth = ($value) ? $value : 500;
		
		return $areaWidth;
	}

	public function getZoomAreaHeight()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/zoom_area_height');
		$areaHeight = ($value) ? $value : null;
		
		return $areaHeight;
	}

	public function getDescriptionActive()
	{
		$active = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/description_active');
		if ($active == 0) {
			$js = '			show_descriptions: false,';
		}
		
		return $js;
	}

	public function getDescriptionPosition()
	{
		$position = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/description_position');
		
		return $position;
	}

	public function getDescriptionOpacity()
	{
		$opacity = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/description_opacity');
		
		return $opacity;
	}

	public function getOpacityInative()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/css_settings/opacity_inative');
		$opacityInative = ($value) ? $value : '0.3';
		
		return $opacityInative;
	}

	public function getOpacityMagnifier()
	{
		$value = Mage::getStoreConfig('dexxtz_productzoom/css_settings/opacity_magnifier');
		$opacityMagnifier = ($value) ? $value : '0.5';
		
		return $opacityMagnifier;
	}
	
	public function getCss()
	{
		$border = Mage::getStoreConfig('dexxtz_productzoom/css_settings/border');
		$box = Mage::getStoreConfig('dexxtz_productzoom/css_settings/box_shadow');
		
		$css = '<style type="text/css" media="all">' . "\r";
		
		if ($border || $box) {
			
			$cssBorder = ($border) ? 'border: ' . $border . '; ' : '';
			$cssBox = ($box) ? 'box-shadow: ' . $box . '; ' : '';
			
			$css .= '	#etalage .etalage_magnifier, ';			
			$css .= "\r" . '	#etalage .etalage_thumb, ';
			$css .= "\r" . '	#etalage .etalage_small_thumbs li, ';
			$css .= "\r" . '	#etalage .etalage_zoom_area { ' . $cssBorder . $cssBox . '}' . "\r";			
		}
		
		$css .= $this->getDescriptionCss() . "\r";
		$css .= '	.dexxtz_video img { opacity: ' . $this->getOpacityMagnifier() . '; }' . "\r";		
		$css .= '	.product-view .product-img-box { width: ' . $this->getFeaturedWidth() . 'px; }' . "\r";
		$css .= '</style>' . "\r";
			
		echo $css;
	}
	
	public function getJs()
	{
		$js  = '<script type="text/javascript">' . "\r";
		$js .= '	var jQuery = jQuery.noConflict();' . "\r";
		$js .= '	jQuery(document).ready(function(jQuery){' . "\r";
		$js .= '		jQuery("#etalage").etalage({' . "\r";
		$js .= '			thumb_image_width: ' . $this->getFeaturedWidth() . ',' . "\r";
		$js .= '			thumb_image_height: ' . $this->getFeaturedHeight() . ',' . "\r";
		$js .= '			source_image_width: ' . $this->getImageZoomWidth() . ',' . "\r";
		$js .= '			source_image_height: ' . $this->getImageZoomHeight() . ',' . "\r";
		$js .= '			zoom_area_width: ' . $this->getZoomAreaWidth() . ',' . "\r";
		$js .= ($hz = $this->getZoomAreaHeight()) ? '			zoom_area_height: ' . $hz . ',' . "\r" : null;
		$js .= $this->getDescriptionActive(). "\r";
        $js .= '			description_location: \'' . $this->getDescriptionPosition() . '\',' . "\r";
        $js .= '			description_opacity: ' . $this->getDescriptionOpacity() . ',' . "\r";
		$js .= '			small_thumbs: ' . $this->getThubnailsQty() . ',' . "\r";
		$js .= '			smallthumb_inactive_opacity: ' . $this->getOpacityInative() . ',' . "\r";
		$js .= '			smallthumbs_position: "' . $this->getThubnailsPosition() . '",' . "\r";
		$js .= '			magnifier_opacity: ' . $this->getOpacityMagnifier() . ',' . "\r";
		$js .= '			show_icon: ' . $this->getShowIcon() . ',' . "\r";
		$js .= '			speed: ' . $this->getFadeInterval() . ',' . "\r";
		$js .= '			autoplay: ' . $this->getAutoplay() . ',' . "\r";
		$js .= '			autoplay_interval: ' . $this->getAutoplayInterval() . ',' . "\r";
		$js .= '		});' . "\r\r";
		
		$images = count(Mage::registry('current_product')->getMediaGalleryImages());
		
		if ($this->getVideoActive() ==  1 && $images > 1) {
			$js .= $this->getVideoJs();
		}
		
		$js .= '	});' . "\r";
		$js .= '</script>' . "\r";
		
		echo $js;
	}

	public function getVideoActive()
	{
		$active = Mage::getStoreConfig('dexxtz_productzoom/video/active');
		
		return $active;
	}

	public function getTabName()
	{
		$groupCollection = Mage::getModel('eav/entity_attribute_group')->getResourceCollection();
		$groups = $groupCollection->addFilter('attribute_group_name', 'Video')->addFilter('attribute_set_id', 4);
		
		if ($groups) {
			$group = current($groups->getData());
			if ($id = $group['attribute_group_id'])
				$groupTabName = 'group_' . $id;
		
			if ($this->getVideoActive() == 0) {
				return $groupTabName;
			} else {
				if ($this->getActive() == 0) {
					return $groupTabName;
				}
			}
		}
	}

	public function getVideoJs()
	{
		$video = (Mage::registry('current_product')->getDexxtzVideo()) ? true : false;
		$media = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$imgValue = Mage::getStoreConfig('dexxtz_productzoom/video/image_upload');
		$imgDefault = Mage::getBaseUrl('skin') . 'frontend/base/default/dexxtz/productzoom/images/play.jpg';
		$img = ($imgValue) ? $media . 'dexxtz/productzoom/images/' .$imgValue : $imgDefault;
		
		$js .= '		var video = "' . $video . '";' . "\r";
		$js .= '		var position = "' . $this->getThubnailsPosition() . '";' . "\r";
		$js .= '		var styleLi  = \'style="\' + jQuery(\'.etalage_small_thumbs\').attr(\'style\') + \'"\';' . "\r";
		$js .= '		var styleImg = \'style="\' + jQuery(\'.etalage_small_thumb\').attr(\'style\') + \'"\';' . "\r";
		$js .= '		var imgTag   = \'<img class="etalage_thumb_image" onclick="DexxtzModal(this)"\' + styleImg + \'src="' . $img .'" />\'' . "\r\r";
		$js .= '		if (jQuery(\'.etalage_smallthumb_active\').length && video == true){' . "\r";
		$js .= '			jQuery("ul#etalage").append(\'<li class="etalage_small_thumbs dexxtz_video"\'  + styleLi + \'><ul><li>\' + imgTag + \'</li></ul></li>\');' . "\r\r";	
		$js .= '		}' . "\r";
		$js .= '		var li  = jQuery(".etalage_small_thumbs");' . "\r";
		$js .= '		var img = jQuery(".dexxtz_video");' . "\r\r";
		$js .= '		var newWidth  = li.width()  - img.width();' . "\r";
		$js .= '		var newHeight = li.height() - img.height();' . "\r\r";
		$js .= '		if (position == "top" || position == "bottom") {' . "\r";
		$js .= '			jQuery("li.etalage_small_thumbs").css("width", newWidth + "px");' . "\r";
		$js .= '			jQuery(".dexxtz_video").attr("style", position + ": 0; right: 0;");' . "\r";
		$js .= '		} else {' . "\r";
		$js .= '			jQuery("li.etalage_small_thumbs").css("height", newHeight + "px");' . "\r";
		$js .= '			jQuery(".dexxtz_video").attr("style", position + ": 0; bottom: 0;");' . "\r";
		$js .= '		}' . "\r";

		return $js;
	}

	public function getDescriptionCss()
	{
		$bg    = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/background_color');
		$color = Mage::getStoreConfig('dexxtz_productzoom/zoom_settings/description_color');
		
		$css .= ($bg) ? ' background-color: ' . $bg . ';' : '';
		$css .= ($color) ? ' color: ' . $color . ';' : '';
		$css  = ($bg || $color) ? '	#etalage .etalage_description { ' . $css . ' }' : '';
		
		return $css;
	}

	public function getVideoCss()
	{
		$bg = Mage::getStoreConfig('dexxtz_productzoom/video/background_color');
		$op = Mage::getStoreConfig('dexxtz_productzoom/video/background_opacity');
		$border = Mage::getStoreConfig('dexxtz_productzoom/video/border');
		$box = Mage::getStoreConfig('dexxtz_productzoom/video/box_shadow');
		$bgContent = Mage::getStoreConfig('dexxtz_productzoom/video/background_content');
		
		$css = '<style type="text/css" media="all">' . "\r";
		
		if ($bg || $op) {
			$cssBg = ($bg) ? 'background-color: ' . $bg . '; ' : '';
			$cssOp = ($op) ? 'opacity: ' . $op . '; ' : '';			
			$css .= '	.dexxtz-bg-modal { ' . $cssBg . $cssOp . '}' . "\r";
		}
	
		if ($border || $box || $bgContent) {
			$cssBorder = ($border) ? 'border: ' . $border . '; ' : '';
			$cssBox = ($box) ? 'box-shadow: ' . $box . '; ' : '';
			$cssBgContent = ($bgContent) ? 'background-color: ' . $bgContent . '; ' : '';				
			$css .= '	.dexxtz-modal-content div { ' . $cssBorder . $cssBox . $cssBgContent . '}' . "\r";		
		}
		
		$css .= '</style>' . "\r";
			
		echo $css;
	}

	public function getImageResize()
	{
		$resize = Mage::getStoreConfig('dexxtz_productzoom/featured_settings/resize');
		
		return $resize;
	}

	public function getImageBg()
	{
		$bg = Mage::getStoreConfig('dexxtz_productzoom/featured_settings/background_color');
		
		return $bg;
	}
	
	public function getImageFeatured($img, $zoom = false)
	{
		$w = $this->getFeaturedWidth();
		$h = $this->getFeaturedHeight();
		$wz = $this->getImageZoomWidth();
		$hz = $this->getImageZoomHeight();
		$resize = $this->getImageResize();			
		$bg = $this->getImageBg();
		$imgFeatured = null;
		
		if ($resize) {
			if ($bg) {
				$hex = str_replace("#", "", $bg);
				
				if(strlen($hex) == 3) {
					$r = hexdec(substr($hex,0,1).substr($hex,0,1));
					$g = hexdec(substr($hex,1,1).substr($hex,1,1));
					$b = hexdec(substr($hex,2,1).substr($hex,2,1));
				} else {
					$r = hexdec(substr($hex,0,2));
					$g = hexdec(substr($hex,2,2));
					$b = hexdec(substr($hex,4,2));
				}
				
				$image = $img->backgroundColor($r, $g, $b);
			}
			
			$imgFeatured = ($zoom == true) ? $image->resize($wz, $hz) :  $image->resize($w, $h);
						
		} else {
			$imgFeatured = ($zoom == true) ? $img->keepFrame(false)->resize($wz, $hz) :  $img->keepFrame(false)->resize($w, $h) ;
		}		
		
		return $imgFeatured;
	}
}