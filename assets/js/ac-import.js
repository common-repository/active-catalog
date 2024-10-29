jQuery(document).ready(function($) {

	// Add catalog button
	$('.ac-add-catalog').on('click', function(event) {
		event.preventDefault();

	    media_uploader = wp.media({
	        frame:    "post",
	        state:    "insert",
	        multiple: false
	    });

	    media_uploader.on("insert", function(){
	        var json = media_uploader.state().get("selection").first().toJSON();
	        var csv_url = json.url;

			$.ajax({
				method: 'post',
				url: acPlugin.ajaxurl,
				data: {
					action: 'accp_import_catalog',
					csv_url: csv_url
				},
				beforeSend: function() {
					$('.import-btn-container .spinner').css('visibility', 'visible');
				},
				success: function( data ) {
					$('.import-infobox p').remove();
					$('.import-btn-container .spinner').css('visibility', 'hidden');
					$('.ac-import-popup').stop().fadeIn();

					if ( data === 'error' ) {
						$('.import-infobox').html('<p class="ac-import-error-notification"><span>An error occurs. Check a CSV file structure and try again.</span></p>');
						$('.js-confirm-import').attr('disabled', 'disabled' );
						return;
					}

					var total_tml = _.template('<p><span>Total items to be imported:</span> <%= number %></p>');
					// var created_tml = _.template('<p><span>Items to be created:</span> <%= number %></p>');
					// var updated_tml = _.template('<p><span>Items to be updated:</span> <%= number %></p>');

					$('.ac-import-error-notification').remove();

					$('.js-confirm-import').removeAttr('disabled');

					$('.import-infobox').append('<input name="ac-csv-url" type="hidden" value="' + csv_url + '">');

					// $('.import-infobox').append( total_tml({ number: data.total }) + created_tml({ number: data.created }) + updated_tml({ number: data.updated }) );
					$('.import-infobox').append( total_tml({ number: data.total }) );

				}
			});

	    });

	    media_uploader.open();
	});

	// Close popup
	$('.import-popup-inner i, .js-cancel-import').on('click', function(event) {
		event.preventDefault();
		$('.ac-import-popup').stop().fadeOut();
	});

	// Confirm import
	$('.js-confirm-import').on('click', function(event) {
		event.preventDefault();
		var csv_url = $('input[name="ac-csv-url"]').val();
		$.ajax({
			method: 'post',
			url: acPlugin.ajaxurl,
			data: {
				action: 'accp_confirm_import_catalog',
				csv_url: csv_url
			},
			beforeSend: function() {
				$('.button-box button').attr('disabled', 'disabled');
				$('.button-box .spinner').css('visibility', 'visible');
				$('.warning-popup-message').css('visibility', 'visible');

			},
			success: function( data ) {
				$('.button-box *').not('.button.js-close-import').css('visibility', 'hidden');
				$('.warning-popup-message').css('visibility', 'hidden');
				$('.ac-import-popup').stop().fadeIn();
				$('.import-infobox').html('<p class="ac-success-imported"><span><i class="dashicons dashicons-yes"></i>All products imported successfully!</span></p>');

				$('.ac-import-popup button.js-close-import').addClass('btnEnabled').removeAttr('disabled').click(function () {
					$('.ac-import-popup').stop().fadeOut(300, function () {
						location.reload();
					});
				});

				var created_tml = _.template('<p><span>Products Created:</span> <%= number %></p>');
				var skipped_tml = _.template('<p><span>Products Updated:</span> <%= number %><br /><small>Please make sure your csv file doesn\'t contain products with the same title</small></p>');

				if (data.skipped > 0) {
					$('.import-infobox').append(created_tml({number: data.items_created}));
					$('.import-infobox').append(skipped_tml({number: data.skipped}));
				}
			},

		}).fail(function (error) {
			console.log(error);
		});
	});

});
