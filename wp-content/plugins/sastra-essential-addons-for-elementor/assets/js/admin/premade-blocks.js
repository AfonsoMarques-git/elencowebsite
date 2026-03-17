jQuery(document).ready(function( $ ) {
	"use strict";

    if ( $('.tmpcoder-tplib-template-gird-inner').length != 0 ){
        // Run Macy
        var macy = Macy({
            container: $('.tmpcoder-tplib-template-gird-inner')[0],
            waitForImages: true,
            margin: 30,
            columns: 5,
            breakAt: {
                1370: 4,
                940: 3,
                520: 2,
                400: 1
            }
        });
    }

	// setTimeout(function(){
	// 	macy.recalculate(true);
	// }, 100 );

	// setTimeout(function(){
	// 	macy.recalculate(true);
	// }, 600 );

	// lazy loader start
	const lazyLoader = function(){

	  	var observer = new IntersectionObserver(onIntersect);

	  	document.querySelectorAll('[data-lazy-load]').forEach(function(img) {
	    	observer.observe(img);
	  	});

	  	function onIntersect(entries, observer) {
	    	entries.forEach(function(entry) {
	      
	      	if (entry.target.getAttribute('data-processed') || !entry.isIntersecting) return true;
	      
	      	entry.target.setAttribute('src', entry.target.getAttribute('data-src'));

	      	entry.target.setAttribute('data-processed', true);

	      	if ( typeof macy != "undefined" ){
	      		macy.recalculate(true);
	      	}

	    	});
	  	}

		// setTimeout(function(){
			
		// }, 300 );	  	
	}	
	// lazy loader End
	lazyLoader();

	$(window).scroll(function(){
        if ( typeof macy != "undefined" ){
		    macy.recalculate(true);
        }
	});


	// Filters
	$('.tmpcoder-tplib-filters').on('click', function(){
		if ( '0' == $('.tmpcoder-tplib-filters-list').css('opacity') ) {
			$('.tmpcoder-tplib-filters-list').css({
				'opacity' : '1',
				'visibility' : 'visible'
			});
		} else {
			$('.tmpcoder-tplib-filters-list').css({
				'opacity' : '0',
				'visibility' : 'hidden'
			});
		}
	});

	$('body').on('click', function(){
		if ( '1' == $('.tmpcoder-tplib-filters-list').css('opacity') ) {
			$('.tmpcoder-tplib-filters-list').css({
				'opacity' : '0',
				'visibility' : 'hidden'
			});
		}
	});

	$( '.tmpcoder-tplib-filters-list ul li' ).on( 'click', function() {
		var current = $(this).attr( 'data-filter' );

		// Show/Hide
		if ( 'all' === current ) {
			$( '.tmpcoder-tplib-template' ).parent().show();
		} else {
			$( '.tmpcoder-tplib-template' ).parent().hide();
			$( '.tmpcoder-tplib-template[data-filter="'+ current +'"]' ).parent().fadeIn(500);
		}

		$('.tmpcoder-tplib-filters h3 span').attr('data-filter', current).text($(this).text());

		// Fix Grid
		macy.recalculate(true);

		setTimeout(function() {
			macy.recalculate(true);
		}, 500);
	});

	// Preview Links and Referrals
	$('.tmpcoder-tplib-template-media').on( 'click', function() {
		var module = $(this).parent().attr('data-filter'),
			template = $(this).parent().attr('data-slug'),
			previewUrl = TmpcoderLibFrontJs.demos_url+ $(this).parent().attr('data-preview-url'),
			proRefferal = '';

		if ( $(this).closest('.tmpcoder-tplib-pro-wrap').length ) {
			proRefferal = '-pro';
		}

		window.open(previewUrl +'?ref=tmpcoder-plugin-backend-premade-blocks'+ proRefferal, '_blank');
	});

	$('.tmpcoder-tplib-insert-pro').on( 'click', function() {
		var module = $(this).closest('.tmpcoder-tplib-template').attr('data-filter');
		window.open(TmpcoderLibFrontJs.TMPCODER_PURCHASE_PRO_URL+'?ref=tmpcoder-plugin-backend-premade-blocks-'+ module +'-upgrade-pro#purchasepro', '_blank');
	});

}); // end dom ready