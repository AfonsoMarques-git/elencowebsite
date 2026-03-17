jQuery(window).on('elementor/frontend/init', function () {
    window.elementorFrontend.hooks.addAction( 'frontend/element_ready/tmpcoder-product-media.default', function( $scope ) {
        if ( jQuery( '.woocommerce-product-gallery' ).length != 0 ){
            jQuery( '.woocommerce-product-gallery' ).each( function(){
                jQuery( this ).wc_product_gallery();
            });
        }
    });
    window.elementorFrontend.hooks.addAction( 'frontend/element_ready/tmpcoder-product-tabs.default', function( $scope ) {
        $scope.find('.description_tab a').trigger('click');
        if ( !$scope.find('p.stars').length ) {
            $scope.find('#rating').hide();
            $scope.find('#rating').before('<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>');
        }
    });
});