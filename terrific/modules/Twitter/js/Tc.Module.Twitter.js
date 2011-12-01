(function($) {
	
    /**
     * Twitter module implementation.
     *
     * @author Roger Dudler
     * @namespace Tc.Module
     * @class Twitter
     * @extends Tc.Module
     */
    Tc.Module.Twitter = Tc.Module.extend({
		
		init: function($ctx, sandbox, modId) {
	        this._super($ctx, sandbox, modId);
	    },

		onRefresh: function() {
			var $ctx = this.$ctx;
			var that = this;
			var tweets = $('.tweets', $ctx);
			$.ajax({
				url: Terrific.ajaxurl,
				data: 'action=tweets&screen_name=' + tweets.data('screen-name') + '&count=' + tweets.data('count'),
				dataType: 'json',
			    success: function(data){
					$.each(data, function(key, item) {
						var meta = item.created_at;
						var text = item.text;
						
						// replace urls with links
						$.each(item.entities.urls, function(index, entity) {
							text = text.replace(entity.url, '<a href="' + entity.url + '">' + entity.url + '</a>');
						});
						
						// replace hashtags with links
						$.each(item.entities.hashtags, function(index, hashtag) {
							text = text.replace('#' + hashtag.text, '<a href="http://twitter.com/#!/search?q=%23' + hashtag.text + '">#' + hashtag.text + '</a>');
						});
						
						// replace hashtags with links
						$.each(item.entities.user_mentions, function(index, user_mention) {
							text = text.replace('@' + user_mention.screen_name, '<a href="http://twitter.com/' + user_mention.screen_name + '">@' + user_mention.screen_name + '</a>');
						});
						
						// create tweet markup
						$('<div class="tweet"><img src="' + item.user.profile_image_url + '" width="32" height="32" /><p>' + text + '</p></div>').appendTo('.tweets', $ctx);
					});
			  	}
			});
		}

    });
})(Tc.$);