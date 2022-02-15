/**
 *
 * JS to hanlde guest post AJAX form submission.
 *
 * @package Guest_Post
 **/

jQuery(
	function ( $ ) {

		if ( typeof gp_form_params === 'undefined' ) {
			return false;
		}

		// Guest Post Form Submission JS.
		var gp_form = {

			// Init JS.
			init: function() {
				$( 'body' )
				.on( 'submit', '#guest-post-form', this.handle_gp_form_submission );
			},

			handle_gp_form_submission: function(e) {
				e.preventDefault();

				// Get form.
				var form = $( this )[0];

				// FormData object.
				var data = new FormData( form );

				data.append( 'action', 'guest_post_form_submission' );
				data.append( 'security', gp_form_params.gp_form_nonce );

				$.ajax(
					{
						type: "POST",
						enctype: 'multipart/form-data',
						url: gp_form_params.ajax_url,
						data: data,
						processData: false,
						contentType: false,
						cache: false,
						timeout: 800000,
						beforeSend: function() {
							$( '#guest-post-form #gues-post' ).attr( 'disabled', true );
							$( '#guest-post-form .message' ).removeClass( 'hidden' ).html( gp_form_params.processing_text );
						},
						success: function( response ) {
							$( '#guest-post-form .message' ).html( response.data.message );

							if ( response.success ) {
								$( '#guest-post-form .message' ).removeClass( 'hidden' ).addClass( 'success' );
								$( '#guest-post-form #gues-post' ).attr( 'disabled', false );
								$( "#guest-post-form" ).trigger( 'reset' );
							} else {
								$( '#guest-post-form .message' ).removeClass( 'hidden' ).addClass( 'error' );
							}
						},
					}
				);

				return false;
			},
		};

		gp_form.init();
	}
);
