function acParseGetParams(val) {
    var result = "Not found",
        tmp = [];
    location.search
    .substr(1)
        .split("&")
        .forEach(function (item) {
        tmp = item.split("=");
        if (tmp[0] === val) result = decodeURIComponent(tmp[1]);
    });
    return result;

}
jQuery(document).ready(function($) {

    // Select all on page Delete & Transfer
    $('.ac-select-all-products-li input').on('change', function(event) {

        if ( $('body').hasClass('ac_product_page_delete_transfer') ) {
            if ( $(this).prop('checked') === true ) {

                $(this).parent().parent().siblings('li').find('input').prop('checked', 'checked');
            } else {
                $(this).parent().parent().siblings('li').find('input').prop('checked', '');
            }

        }

    });

    $('.js-transfer').on('click', function(event) {

        event.preventDefault();
        var $products = $('.ac-postbox:first-of-type input');
        var $cats = $('.ac-postbox:last-of-type input');
        var oneProduct = true;
        var oneCat = true;

        $products.each( function() {
            var prop = $(this).prop('checked');
            if ( prop ) {
                oneProduct = false;
                return false;
            }
        });

        $cats.each( function() {
            var prop = $(this).prop('checked');
            if ( prop ) {
                oneCat = false;
                return false;
            }
        });

        if ( oneProduct ) {
            alert('Please select products to transfer');
        }

        if ( ! oneProduct && oneCat ) {
            alert('Please select category to transfer selected products');
        }

        if ( ! oneProduct && ! oneCat ) {
            var posts = [];
            var cats = [];
            var deleting = acParseGetParams( 'term_id' );
            // Create selected post array
            $('.ac-products-list input[name="products_for_transfer"]').each(function(index, el) {

                // Exclude first checkbox for "Select All"
                if ( $(el).prop('checked') === true && $(el).val() !== 'select-all-products' ) {
                    posts.push( $(el).val() );
                }

            });

            // Create selected cats array
            $('.ac-cats-list input[name="categories_for_transfer"]').each(function(index, el) {

                // Exclude first checkbox for "Select All"
                if ( $(el).prop('checked') === true && $(el).val() !== 'select-all-cats' ) {
                    cats.push( $(el).val() );
                }

            });

            $.ajax({
                method: 'POST',
                url: acPlugin.ajaxurl,
                data: {
                    action: 'accp_delete_transfer_action',
                    posts: posts,
                    cats: cats,
                    deleting: deleting
                },
                beforeSend: function() {
                    $('.ac-admin-main-title').append('<span class="ac-spinner ac-spinner-metabox spinner is-active"></span>');
                    $('.js-transfer').attr('disabled', 'disabled');
                },
                success: function( data ) {
                    $('.ac-admin-main-title .ac-spinner').remove();
                    $('.ac-admin-main-title').append('<span class="status"><i class="dashicons-before dashicons-yes"></i>Transfered successfully</span>');

                    setTimeout( function(){

                        $('.ac-admin-main-title .status').fadeOut( 200, function(){

                            $('.ac-admin-main-title .status').remove();

                        });

                    }, 1000 );

                    window.location.href = $('.ac-admin-back-link').attr('href');
                },

            }).always( function() {
                $('.js-transfer').removeAttr('disabled');
            });
        }

    });

    $(".meta-box-sortables").sortable({
        cancel:"#postimagediv"
    });

    if ( document.querySelector('.ac-css-editor') !== null ) {
        var editor = CodeMirror.fromTextArea(document.querySelector('.ac-css-editor'), {
            lineNumbers: true,
            matchBrackets: true,
            mode: "text/x-scss"
        });
    }

    $( function() {
        $( ".ac-pleudo-metabox" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
        $( "#ac-pleudo-metabox li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    } );

});
