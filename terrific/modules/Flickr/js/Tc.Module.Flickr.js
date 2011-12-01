(function($) {
	
    /**
     * Flickr module implementation.
     *
     * @author Roger Dudler
     * @namespace Tc.Module
     * @class Flickr
     * @extends Tc.Module
     */
    Tc.Module.Flickr = Tc.Module.extend({
		
		init: function($ctx, sandbox, modId) {
	        this._super($ctx, sandbox, modId);
	    },
	
        onBinding: function() {
			var $ctx = this.$ctx;
			var that = this;
			$('.flickr-reload', $ctx).click(function() {
				that.onReload();
			});
        },

		afterBinding: function() {
			var $ctx = this.$ctx;
			var items = $('.flickr-image-item', $ctx);
			$.each(items, function(key, item) {
				//var random = Math.random()*500;
				//$(item).delay(random).fadeIn('slow');
                $(item).show();
			});
		},
		
		onReload: function () {
			var $ctx = this.$ctx;
			$('.flickr-image-item', $ctx).remove();
			$.ajax({
				url: Terrific.ajaxurl,
				data: 'action=latest&feed=' + encodeURIComponent($('.flickr', $ctx).data('flickr-feed')),
				dataType: 'json',
			    success: function(data){
                    
					$.each(data, function(key, item) {
						var image = item.image;
						var link = item.link;
						var random = Math.random()*500;
						$('<a href="' + link + '" class="flickr-image-item"><img src="' + image + '" alt="" /></a>')
							.appendTo('.image-' + key, $ctx)
							.delay(random)
							.fadeIn('slow');
					});
			  	}
			});
		}

    });
})(Tc.$);