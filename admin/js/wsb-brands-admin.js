jQuery(document).ready( function($) {

	 if ( ! $( '#brandLogo' ).val() ) {
			$( '.wsb_brands_media_remove' ).hide();
			$( '.wsb_brands_media_button' ).val('Add logo')
	 }
	 
	 
	 // Uploading files
				var file_frame;

				$( document ).on( 'click', '.wsb_brands_media_button', function( event ) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: 'Choose an image',
						button: {
							text: 'Use image'
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
						var attachment_thumbnail = attachment.sizes.medium || attachment.sizes.full;

						$( '#brandLogo' ).val( attachment.id );
						$( '#brand-logo-wrapper').html('<img class="logo_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
						$( '#brand-logo-wrapper .logo_image' ).attr( 'src', attachment_thumbnail.url ).css('display','block');
						$( '.wsb_brands_media_remove' ).show();
						$( '.wsb_brands_media_button' ).val('Change logo')
					});

					file_frame.open();
				});
	 
	 $('body').on('click','.wsb_brands_media_remove',function(){
	  	$( '.wsb_brands_media_button' ).val( 'Add logo' );
        $( '#brandLogo' ).val('');
        $( '#brand-logo-wrapper' ).html( '<img class="logo_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />' );
		$( '.wsb_brands_media_remove' ).hide();
     });
	 
	 
	  
	 $( document ).ajaxComplete( function( event, request, options ) {
					if ( request && 4 === request.readyState && 200 === request.status
						&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

						var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
						if ( ! res || res.errors ) {
							return;
						}
						$( '#brand-logo-wrapper' ).html( '' );
						$( '#brandLogo' ).val( '' );
						$( '.wsb_brands_media_remove' ).hide();
						$( '.wsb_brands_media_button' ).val( 'Add logo' );
						
						return;
					}
				} );

var colorOptions = {
    hide: true,
    palettes: true
};
$('.wsb_color-field').wpColorPicker(colorOptions);

//$( "div.bulkactions" ).append("<p>Test</p>");
	 
});