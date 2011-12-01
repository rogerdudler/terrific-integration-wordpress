(function($) {
    /**
     * Comment form validation module implementation.
     *
     * @namespace Tc.Module
     * @class CommentForm
     * @extends Tc.Module
     */
    Tc.Module.CommentForm = Tc.Module.extend({
		init: function($ctx, sandbox, modId) {
	        this._super($ctx, sandbox, modId);
	    },
	
	     onBinding: function() {

			$("#commentform").validate({
				rules: {
					author: "required",
					email: {
						required: true,
						email: true
					},
					//url: "url",
					comment: "required"
				},
				messages: {
                    author: {
                        required: "Bitte geben Sie den Namen an."
                    },
                    email: {
                        required: "Bitte füllen Sie dieses Feld aus.",
                        email: "Bitte geben Sie eine gültige E-Mail Adresse ein."
                    },
	                comment: {
	                    required: "Bitte geben Sie eine Kommentar."
	                }
                }
			});

        }
    });
})(Tc.$);