/**
 * Created by Vladimir on 6/10/2016.
 */

Ajax.Updater.prototype.updateContent  = Ajax.Updater.prototype.updateContent.wrap(function(superMethod,responseText){
    var receiver = this.container[this.success() ? 'success' : 'failure'],
        options = this.options;

    if(receiver.className != 'product-img-box'){
        return superMethod();
    }
    else{
        if (!options.evalScripts) responseText = responseText.stripScripts();

        if (receiver = $(receiver)) {
            if (options.insertion) {
                if (Object.isString(options.insertion)) {
                    var insertion = { }; insertion[options.insertion] = responseText;
                    receiver.insert(insertion);
                }
                else options.insertion(receiver, responseText);
            }
            else receiver.update(responseText);
            jQuery("#etalage").etalage({
                thumb_image_width: document.getElementById('thumb_image_width').value,
                thumb_image_height: document.getElementById('thumb_image_height').value,
                source_image_width: document.getElementById('source_image_width').value,
                source_image_height: document.getElementById('source_image_height').value,
                zoom_area_width: document.getElementById('zoom_area_width').value,
                zoom_area_height: document.getElementById('zoom_area_height').value,
                show_descriptions: document.getElementById('show_descriptions').value,
                description_location: document.getElementById('description_location').value,
                description_opacity: document.getElementById('description_opacity').value,
                small_thumbs: document.getElementById('small_thumbs').value,
                smallthumb_inactive_opacity: document.getElementById('smallthumb_inactive_opacity').value,
                smallthumbs_position: document.getElementById('smallthumbs_position').value,
                magnifier_opacity: document.getElementById('magnifier_opacity').value,
                show_icon: document.getElementById('show_icon').value,
                speed: document.getElementById('speed').value,
                autoplay: document.getElementById('autoplay').value,
                autoplay_interval: document.getElementById('autoplay_interval').value,
            });
        }
    }

});
