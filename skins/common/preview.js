/**
 * Live preview script for MediaWiki
 */
( function( mw, $ ) {
	var doLivePreview = function( e ) {
		e.preventDefault();

		$( mw ).trigger( 'LivePreviewPrepare' );

		var $wikiPreview = $( '#wikiPreview' );

		// this needs to be checked before we unconditionally show the preview
		var previewVisible = false;
		if ( $wikiPreview.is( ':visible' ) || $( '.mw-newarticletext:visible' ).length > 0 ) {
			previewVisible = true;
		}

		// show #wikiPreview if it's hidden to be able to scroll to it
		// (if it is hidden, it's also empty, so nothing changes in the rendering)
		$wikiPreview.show();
		// jump to where the preview will appear
		$wikiPreview[0].scrollIntoView();

		// list of elements that will be loaded from the preview page
		var copySelectors = [
			'#wikiPreview', '#wikiDiff', '#catlinks', '.hiddencats', '#p-lang', // the meat
			'.templatesUsed', '.mw-summary-preview', // editing-related
			'.mw-newarticletext' // it is not shown during normal preview, and looks weird with throbber overlaid
		];
		var $copyElements = $( copySelectors.join( ',' ) );

		var $loadSpinner = $( '<div>' ).addClass( 'mw-ajax-loader' );
		$loadSpinner.css( 'top', '0' ); // move away from header (default is -16px)

		// If the preview is already visible, overlay the spinner on top of it.
		if ( previewVisible ) {
			$( '#mw-content-text' ).css( 'position', 'relative' ); // FIXME this seems like a bad idea

			$loadSpinner.css( {
				'position': 'absolute',
				'z-index': '3',
				'left': '50%',
				'margin-left': '-16px'
			} );
		}

		// fade out the elements and display the throbber
		$( '#mw-content-text' ).prepend( $loadSpinner );
		// we can't use fadeTo because it calls show(), and we might want to keep some elements hidden (e.g. empty #catlinks)
		$copyElements.animate( { 'opacity': 0.4 }, 'fast' );

		var $page = $( '<div>' );
		var target = $( '#editform' ).attr( 'action' ) || window.location.href;

		// gather all the data from the form
		var postData = $( '#editform' ).formToArray(); // formToArray: from jquery.form
		postData.push( { 'name' : e.target.name, 'value' : '1' } );

		$page.load( target + ' ' + copySelectors.join( ',' ), postData, function() {
			for ( var i = 0; i < copySelectors.length; i++ ) {
				// For all the specified elements, copy their contents from the loaded page to the real page.
				// Also copy elements' class attributes.
				var $from = $page.find( copySelectors[i] );
				var $to = $( copySelectors[i] );

				$to.empty().append( $from.contents() );
				$to.attr( 'class', $from.attr( 'class' ) );
			}

			$loadSpinner.remove();
			$copyElements.animate( { 'opacity': 1 }, 'fast' );

			$( mw ).trigger( 'LivePreviewDone', [copySelectors] );
		} );
	};

	$( document ).ready( function() {
		// construct the elements we need if they are missing (usually when action=edit)
		// we don't need to hide them, because they are empty when created

		// interwiki links
		if ( !document.getElementById( 'p-lang' ) && document.getElementById( 'p-tb' ) ) {
			$( '#p-tb' ).after( $( '<div>' ).attr( 'id', 'p-lang' ) );
		}

		// summary preview
		if ( $( '.mw-summary-preview' ).length === 0 ) {
			$( '.editCheckboxes' ).before( $( '<div>' ).addClass( 'mw-summary-preview' ) );
		}

		// diff
		if ( !document.getElementById( 'wikiDiff' ) && document.getElementById( 'wikiPreview' ) ) {
			$( '#wikiPreview' ).after( $( '<div>' ).attr( 'id', 'wikiDiff' ) );
		}

		// diff styles are usually only loaded during, well, diff, and we might need them
		// (mw.loader takes care of stuff if they happen to be loaded already)
		mw.loader.load( 'mediawiki.action.history.diff' );

		$( '#wpPreview, #wpDiff' ).click( doLivePreview );
	} );
} )( mediaWiki, jQuery );
