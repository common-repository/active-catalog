jQuery(document).ready(function($) {

	// Imitate upload function to simple button
	$('.js-ac-metabox-btn').on('click', function(event) {
		event.preventDefault();
		var that = $(this);

		// Media uploader
		open_media_uploader_image( that );
	});

	// Metaboxes AJAX
	$('.ac-metabox-btn').on('click', function(event) {
		event.preventDefault();

		var that = $(this);
		var name = that.siblings('input[type="text"]').val();
		var nonce = that.siblings('input[name="_ajax_nonce"]').val();
		var taxonomy = that.parent().parent().parent().attr('id').slice(0, -3);
		var file = that.siblings('input[name="file_url"]').val();

		if ( name.length < 2 ) {
			$('.ac-message-metabox').text('Name is not correct.');
			return false;
		}

		$.ajax({
			method: 'POST',
			url: acPlugin.ajaxurl,
			data: {
				action: 'accp_add_new_taxonomy_from_metaboxes',
				file_url: file,
				name: name,
				taxonomy: taxonomy
			},
			beforeSend: function() {
				that.siblings('.ac-spinner').addClass('ac-spinner-metabox-active');
			},
			success: function (data) {
				that.siblings('.ac-spinner').removeClass('ac-spinner-metabox-active');
				if ( data === 'This name already exists.') {

					// Error message
					that.siblings('.ac-message-metabox').text(data);

				} else {

					// Success message
					that.siblings('.ac-message-metabox').html(data);

					that.siblings('input[type="text"]').val('');
					that.siblings('input[name="_ajax_nonce"]').val('');
					that.siblings('.ac-uploaded-value-path').text('');

					// Hide success label after sometime
					setTimeout( function() {
						that.siblings('.ac-message-metabox').fadeOut('slow', function(){
							that.siblings('.ac-message-metabox').html('');
						});
					}, 1000 );

					// New ajax for refresh checkboxes in metabox
					$.ajax({
						method: 'POST',
						url: acPlugin.ajaxurl,
						data: {
							action: 'accp_update_term_list_after_add_new',
							taxonomy: taxonomy,
							post_id: acParseGetParams('post')
						},
						beforeSend: function() {

							// Add preloader
							that.parent().siblings('.ac-show-all-term-list').find('.ac-preloader-checklist').addClass('js-ac-preloader-checklist-active');

						},
						success: function(data) {

							// Remove preloader
							that.parent().siblings('.ac-show-all-term-list').find('.ac-preloader-checklist').removeClass('js-ac-preloader-checklist-active');

							// Remove old terms list
							that.parent().siblings('.ac-show-all-term-list').find('ul').remove();

							// Add new terms list
							that.parent().siblings('.ac-show-all-term-list').append(data);

							// Sorting plugin activate again
							acSortingInPost();

						}
					});
				}
			}
		});

	});

	var media_uploader = null;

	function open_media_uploader_image( obj ) {
	    media_uploader = wp.media({
	        frame:    "post",
	        state:    "insert",
	        multiple: false
	    });
	    var jsonMediaInserted = {};

	    media_uploader.on("insert", function(){
	        var json = media_uploader.state().get("selection").first().toJSON();

	        var image_url = json.url;
	        var image_caption = json.caption;
	        var image_title = json.title;
	        if ( obj != undefined ) {
		        obj.siblings('.uploaded_file_url').val(json.url);
		        obj.siblings('.ac-uploaded-value-path').text(json.url);
	        }
	        jsonMediaInserted = json;
	    });

	    media_uploader.open();
	    return jsonMediaInserted;
	}

	$('.tax-default-add-file').on('click', function(event) {
		event.preventDefault();

		    media_uploader = wp.media({
		        frame:    "post",
		        state:    "insert",
		        multiple: false
		    });
		    var jsonMediaInserted = {};

		    media_uploader.on("insert", function(){
		        var json = media_uploader.state().get("selection").first().toJSON();

		        var image_url = json.url;
		        var image_caption = json.caption;
		        var image_title = json.title;
		        $('.ac-tax-file-value').val(json.url);
		    });

		    media_uploader.open();

	});

	// $('#submit').on('click', function(event) {
	// 	$.ajax({
	// 		method: 'POST',
	// 		url: acPlugin.ajaxurl,
	// 		data: {
	// 			action: 'accp_save_created_taxonomy_meta',
	// 			file_url: $('.ac-tax-file-value').val()
	// 		}
	// 	});
	// });

	function acSortingInPost() {

		var sortObj = {};

		// Sortable
		$('.ac-show-all-term-list ul').sortable({
		  appendTo: document.body,
		  deactivate: function( event, ui ) {

		  	$(ui.item).parents('ul').children('li').each(function(index, el) {
		  		var value = $(this).children('label').children('input').val();
		  		$(this).data( 'order', $(this).index() + 1 );
		  		sortObj[value] = $(this).index() + 1;
		  	});

		  	// Set sorted value to terms order meta
		  	$.ajax({
		  		method: 'POST',
		  		url: acPlugin.ajaxurl,
		  		data: {
		  			action: 'accp_sorting_terms',
		  			sortObj: sortObj
		  		}
		  	});
		  }
		});

		// Pretty view of li after scrollbar appears
		$('.ac-show-all-term-list .ui-sortable').each(function(index, el) {
			if ( $(this).height() >= 250 ) {
				$('.ac-show-all-term-list .ui-sortable li').css('marginRight', '10px');
			}

		});
	}
	acSortingInPost();

	function acSortingInAddTermPage() {
		var sortObj = {};

		// Sortable
		if ( $('body').hasClass('taxonomy-ac_product_cat') ||
			$('body').hasClass('taxonomy-ac_product_brochure') ||
			$('body').hasClass('taxonomy-ac_product_specsheets') ) {

			$('.wp-list-table #the-list').sortable({
				appendTo: document.body,
				activate: function( event, ui ) {
					$(ui.item).addClass('ac-sort-active');
				},
				deactivate: function( event, ui ) {
					$(ui.item).removeClass('ac-sort-active');

					// Create {terms -> val} boject
					$(ui.item).parent().children('tr').each(function(index, el) {
						var value = $(this).find('input').val();
						sortObj[value] = $(this).index() + 1;
					});

					// Trigger when sorting
					$.ajax({
						method: 'POST',
						url: acPlugin.ajaxurl,
						data: {
							action: 'accp_sorting_terms',
							sortObj: sortObj
						}
					});
				},
			});
		}
	}
	acSortingInAddTermPage();

	// Images Metabox
	$('.js-ac-images-metabox-btn').on('click', function(event) {
		event.preventDefault();

		media_uploader = wp.media({
		    frame:    "post",
		    state:    "insert",
		    multiple: true
		});
		var jsonMediaInserted = {};

		media_uploader.on("insert", function(){
			var length = media_uploader.state().get("selection").length;
		    var images = media_uploader.state().get("selection").models;
		    var $additionalInput = $('input[name="ac-additional-product-images"]');
		    var $mainInput = $('input[name="ac-main-product-image"]');

		    // If Additional Input val is empty
		    if ( $additionalInput.val().length < 1 ) {
		    	$additionalInput.val('{}');
		    }

		    // If Main Input val is empty
		    if ( $mainInput.val().length < 1 ) {
		    	$mainInput.val('{}');
		    }

		    var $mainInputJSON = JSON.parse( $mainInput.val().replace(/'/g, '"') );
		    var oneUseJSON = {};

		    if ( Object.keys(JSON.parse($additionalInput.val().replace(/'/g, '"'))).length < 1 ) {
			    var json = {};
		    } else {
		    	var json = JSON.parse( $additionalInput.val().replace(/'/g, '"') );
		    }

		    for ( var i = 0; i < length; i++ ) {

                var image_url = images[i].changed.url;
                var image_order = $('.ac-images-metabox-list li').length + 1;
                var image_id = images[i].id;
                var image_title = images[i].changed.name;
                var image_sizes = {};

                // If this image exist in metabox
                if ( $mainInputJSON.hasOwnProperty(image_title) ) {
                	continue;
                }

                for( var size in images[i].changed.sizes ) {
                	image_sizes[size] = images[i].changed.sizes[size];
                }

                if ( ! json.hasOwnProperty(image_title) ){

	                json[image_title] = {
	                	image_url: image_url,
	                	image_id: image_id,
	                	image_order: image_order,
	                	image_title: image_title,
	                	image_sizes: image_sizes
	                };

	                oneUseJSON[image_title] = {
	                	image_url: image_url,
	                	image_id: image_id,
	                	image_order: image_order,
	                	image_title: image_title,
	                	image_sizes: image_sizes
	                };

				    // Add image to metabox (each itteration, each image)
				    if ( typeof  image_sizes.thumbnail == 'undefined' ) {
				    	var url = image_sizes.full.url;
				    } else {
				    	var url = image_sizes.thumbnail.url;
				    }
				    $('.ac-images-metabox-list').append('<li data-name="' + image_title + '" data-json="' + JSON.stringify( oneUseJSON ).replace(/"/g, "\'") + '"><div class="ac-image-metabox-header"><span class="ac-image-metabox-header-name">' + image_title + '</span><i class="dashicons-before dashicons-no-alt"></i></div><div class="ac-image-metabox-thumb-box"><img src="' + url + '" alt="" class="ac-images-metabox-thumbnail"></div></li>');
				    oneUseJSON = {};
                }

            }

            json = JSON.stringify( json );

		    // Add images json to hidden input value
		    $additionalInput.val( json );
		});

		media_uploader.open();
	});

	// Images Metabox Drag & Drop
	$( ".ac-images-metabox-main-image, .ac-images-metabox-list" ).sortable({
		connectWith: ".connectedSortable",
		receive: function(event, ui) {

            var $this = $(this);
            var $mainImageInput = $('input[name="ac-main-product-image"]');
            var singleImageJSON = JSON.parse( $(ui.item).data('json').replace(/'/g, "\"") );

            // Image puting in main UL
            if ( ! $this.hasClass('ac-images-metabox-list') ) {

	            // If main UL more than 1 li elements
	            if ( $this.children('li').length > 1 ) {

	            	$('.ac-images-metabox-main-image li').each( function(index, el) {

	            		// It this elem name the same as dragged elem
	            		if ( $(this).data('name') == $(ui.item).data('name') ) {

	            			// Second adding when there is existing main item
			                $(ui.item).appendTo('.ac-images-metabox-main-image');

			                // Add new val to main image input
			                $('input[name="ac-main-product-image"]').val( $(ui.item).data('json') );

	            		} else {

	            			// Remove old item to secondary UL (change image size of that)
	            			var currentEl = $(this);
	            			var currentJSON = currentEl.data('json');
            				var name = currentEl[0].textContent.trim();

							var image_sizes = JSON.parse(currentEl.data('json').replace(/'/g, '"'))[name].image_sizes;
							if ( image_sizes.hasOwnProperty('thumbnail') ) {
								var thumbnailImageSizeURL = image_sizes.thumbnail.url;
							} else {
								var thumbnailImageSizeURL = image_sizes.full.url;
							}

							var currentElJSON = JSON.parse( currentEl.data('json').replace(/'/g, '"') );

            			    var $secondaryInput = $('input[name="ac-additional-product-images"]');
            			    var secondaryInputJSON = JSON.parse( $secondaryInput.val().replace(/'/g, '"') );
            			    var readyForSaveObj = $.extend({}, currentElJSON, secondaryInputJSON);
            			    currentEl.find('img').attr('src', thumbnailImageSizeURL );
            			    currentEl.appendTo('.ac-images-metabox-list');
            			    $secondaryInput.val( JSON.stringify( readyForSaveObj ).replace(/"/g, "'") );
	            		}

	            	});

	            } else {
	            	// Fist adding when there is no main image
	            	$('input[name="ac-main-product-image"]').val( $(ui.item).data('json') );
	            }

	            // Change Main image size to medium
                for ( var i in singleImageJSON ) {

					if ( singleImageJSON[i].image_sizes.hasOwnProperty('medium') ) {
						var mediumImageUrl = singleImageJSON[i].image_sizes.medium.url;
					} else {
						var mediumImageUrl = singleImageJSON[i].image_sizes.full.url;
					}

                	$(ui.item).find('img').attr('src', mediumImageUrl);
                }

                acRemoveMainImageVal( ui.item );

            } else {
            	// Main Image putting in secondary UL without replacement
            	var singleImageJSON = JSON.parse( $(ui.item).data('json').replace(/'/g, "\"") );
            	var name = $(ui.item).data('name');
				var name = name.match(/[a-z\-_\(\)\[\]0-9]*/i)[0];
            	var thumbnail = singleImageJSON[name].image_sizes.thumbnail.url;
            	var $secondaryInput = $('input[name="ac-additional-product-images"]');
            	var secondaryInputJSON = JSON.parse( $secondaryInput.val().replace(/'/g, '"') );
            	var currentElJSON = JSON.parse( $(ui.item).data('json').replace(/'/g, '"') );

            	// If in secondary input value there is not current el JSON obj
            	if ( secondaryInputJSON[name] == undefined ) {
            		var readyForSaveObj = $.extend({}, currentElJSON, secondaryInputJSON);
            		$secondaryInput.val( JSON.stringify( readyForSaveObj ).replace(/"/, "'") );
            	}

            	// Set thumbnail image size after dragged
            	$(ui.item).find('img').attr('src', thumbnail );
            }

        	acSetProperHeightImageBox();
        },
        stop: function( event, ui ) {

        	// Set thumblail image src against medium when from top to bottom UL
        	if ( $(ui.item).parent().hasClass('ac-images-metabox-list') ) {
	        	var singleImageJSONurl = JSON.parse( $(ui.item).data('json').replace(/'/g, '"') );
				var name = $(ui.item).data('name').match(/[a-z\-_\(\)\[\]0-9]*/i)[0];
	        	singleImageJSONurl = singleImageJSONurl[ name ].image_sizes.thumbnail.url;
        		$(ui.item).find('img').attr('src', singleImageJSONurl );
        	}

        	// Empty main input val if there is no main image
        	if ( $('.ac-images-metabox-main-image li').length < 1 ) {
        		$('input[name="ac-main-product-image"]').val('');
        	}

        	// If there arn't images in secondary UL after drag
        	if ( $(this).hasClass('ac-images-metabox-list') && $(this).children('li').length < 1 ) {
        		var $secondaryInput = $('input[name="ac-additional-product-images"]');
        		$secondaryInput.val('');
        	}

        	// Images order value save to JSON
        	acSecondaryImagesMetaboxSorting( ui.item );
	        acSetProperHeightImageBox();
        }
    }).disableSelection();

	// Remove main image from secondary input json value
	function acRemoveMainImageVal( item ) {

		var objName = item.data('name');
		var secondaryInput = $('input[name="ac-additional-product-images"]');
		var currentInputObj = JSON.parse( secondaryInput.val().replace(/'/g, '"') );
		delete currentInputObj[objName];
		secondaryInput.val( JSON.stringify(currentInputObj) );

	}

	// Set main image box height for prevent twitching bug
	function acSetProperHeightImageBox() {
		setTimeout( function() {
			$('.ac-images-metabox-main-image').height( $('.ac-images-metabox-main-image li').height() + 3 );

			// Change dashed border for main image container
			if ( $('.ac-images-metabox-main-image').children('li').length < 1 ) {
				$('.ac-images-metabox-main-image').addClass('ac-main-image-empty-border');
			} else {
				$('.ac-images-metabox-main-image').removeClass('ac-main-image-empty-border');
			}
		}, 100 );

	}
	acSetProperHeightImageBox();

	// Remove Images from metabox
	$('.images-metabox-container ul').on('click', 'i', function(event) {

		event.preventDefault();
		$(this).parents('li').stop().fadeOut( 300, function() {
			var removedElJSON = JSON.parse( $(this).data('json').replace(/'/g, '"') );

			if ( $(this).parents('ul').hasClass('ac-images-metabox-list') ) {

				// If this parent is secondary UL
				var $secondaryInput = $('input[name="ac-additional-product-images"]');
				var secondaryInputJSON = JSON.parse( $secondaryInput.val().replace(/'/g, '"') );
				delete secondaryInputJSON[$(this).data('name')];

				if ( Object.keys(secondaryInputJSON).length > 0 ) {
					// If in value attr JSON there are some objects
					$secondaryInput.val( JSON.stringify( secondaryInputJSON ) );
				} else {
					// If in value attr JSON there arn't objects
					$secondaryInput.val('');
				}

			} else if ( $(this).parents('ul').hasClass('ac-images-metabox-main-image') ) {

				// If this parent is main UL
				var $mianInput = $('input[name="ac-main-product-image"]');
				$mianInput.val('');

			}

			$(this).remove();
			acSetProperHeightImageBox();
			acSecondaryImagesMetaboxSorting();
		});

	});

	// Secondary images sorting order
	function acSecondaryImagesMetaboxSorting() {

		$('.ac-images-metabox-list li').each(function(index, el) {
			// Change image_order property in JSON of data attr
			var elJSON = JSON.parse( $(el).data('json').replace(/'/g, '"') );
			var elName = $(el).data('name');

			// var name = elName.match(/[a-z\-_\(\)\[\]0-9]*/i)[0];
			elJSON[elName].image_order = index + 1;
			var elJSONstringify = JSON.stringify( elJSON ).replace(/"/g, "'");
			$(el).data('json', elJSONstringify);
			$(el).attr('data-json', elJSONstringify);

			// Change input value after dragging
			var $secondaryInput = $('input[name="ac-additional-product-images"]');
			var secondaryInputJSON = JSON.parse( $secondaryInput.val().replace(/'/g, '"') );
			delete secondaryInputJSON[elName];
			var readyForSaveObj = $.extend({}, elJSON, secondaryInputJSON);
			$secondaryInput.val( JSON.stringify( readyForSaveObj ).replace(/"/g, "'") );

		});

	}

});
