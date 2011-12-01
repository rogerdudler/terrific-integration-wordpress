(function($) {
	
    /**
     * Share module implementation.
     *
     * @author Roger Dudler
     * @namespace Tc.Module
     * @class Share
     * @extends Tc.Module
     */
    Tc.Module.Share = Tc.Module.extend({
		
		init: function($ctx, sandbox, modId) {
	        this._super($ctx, sandbox, modId);
	    },
	
        onBinding: function() {
			var $ctx = this.$ctx;
			var that = this;
			$('.shortlink-input', $ctx).click(function() {
				$('.shortlink-input', $ctx).select();
			});
			$('.social-share', $ctx).appear(function() {
				
				var element = $(this);
				var widthGoogle = 80;
				var widthFacebook = 120;
				var widthTwitter = 115;
				
				// add google+ button via explicit rendering
				$('<div class="share-google"><div id="g-plusone-' + element.data('post-id') + '" class="g-plusone" data-size="medium" data-href="' + element.data('url') + '"></div></div>').appendTo(this);
				//gapi.plusone.go('g-plusone-' + element.data('post-id'));
				gapi.plusone.go();
				
				// add twitter button via iframe
				$('<div class="share-twitter"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?url=' + encodeURIComponent(element.data('shorturl')) + '&amp;lang=' + element.data('lang') + '&amp;text=' + element.data('text') + '&amp;via=' + element.data('via') + '&amp;counturl=' + encodeURIComponent(element.data('url')) + '" style="width:' + widthTwitter + 'px; height:27px;"></iframe></div>').appendTo(this);
			
				// add facebook like button via iframe
				$('<div class="share-facebook"><iframe src="http://www.facebook.com/plugins/like.php?href=' + element.data('url') + '&amp;layout=button_count&amp;show_faces=false&amp;width=' + widthFacebook + '&amp;action=like&amp;colorscheme=light&amp;send=false&amp;height=27" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' + widthFacebook + 'px; height:27px;" allowTransparency="true"></iframe></div>').appendTo(this);
				
			})
        },

		afterBinding: function() {
			
		}

    });
})(Tc.$);