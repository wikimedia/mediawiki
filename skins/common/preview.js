/**
 * Live preview script for MediaWiki
 */
(function( $ ) {
	window.doLivePreview = function( e ) {
		var previewShowing = false;

		e.preventDefault();

		$( mw ).trigger( 'LivePreviewPrepare' );

		var $wikiPreview = $( '#wikiPreview' );

		$( '#mw-content-text' ).css( 'position', 'relative' );

		if ( $wikiPreview.is( ':visible' ) ) {
			previewShowing = true;
		}

		// show #wikiPreview if it's hidden (if it is hidden, it's also empty, so nothing changes in the rendering)
		// to be able to scroll to it
		$wikiPreview.show();

		// Jump to where the preview will appear
		$wikiPreview[0].scrollIntoView();

		var postData = $('#editform').formToArray(); // formToArray: from jquery.form
		postData.push( { 'name' : e.target.name, 'value' : '1' } );

		// Hide active diff, used templates, old preview if shown
		var copyElements = ['#wikiPreview', '#wikiDiff', '.templatesUsed', '.hiddencats',
							'#catlinks', '#p-lang', '.mw-summary-preview'];
		var copySelector = copyElements.join(',');

		$.each( copyElements, function( k, v ) {
			$( v ).fadeTo( 'fast', 0.4 );
		} );

		// Display a loading graphic
		var loadSpinner = $('<div class="mw-ajax-loader"/>');
		// Move away from header (default is -16px)
		loadSpinner.css( 'top', '0' );

		// If the preview is already showing, overlay the spinner on top of it.
		if ( previewShowing ) {
			loadSpinner.css( {
				'position': 'absolute',
				'z-index': '3',
				'left': '50%',
				'margin-left': '-16px'
			} );
		}
		$wikiPreview.before( loadSpinner );

		var page = $('<div/>');
		var target = $('#editform').attr('action');

		if ( !target ) {
			target = window.location.href;
		}

		page.load( target + ' ' + copySelector, postData,
			function() {

				for( var i=0; i<copyElements.length; ++i) {
					// For all the specified elements, find the elements in the loaded page
					//  and the real page, empty the element in the real page, and fill it
					//  with the content of the loaded page
					var copyContent = page.find( copyElements[i] ).contents();
					$(copyElements[i]).empty().append( copyContent );
					var newClasses = page.find( copyElements[i] ).prop('class');
					$(copyElements[i]).prop( 'class', newClasses );
				}

				$.each( copyElements, function( k, v ) {
					// Don't belligerently show elements that are supposed to be hidden
					$( v ).fadeTo( 'fast', 1, function() {
						$( this ).css( 'display', '' );
					} );
				} );

				loadSpinner.remove();

				$( mw ).trigger( 'LivePreviewDone', [copyElements] );
			} );
	};

	$(document).ready( function() {
		// construct space for interwiki links if missing
		// (it is usually not shown when action=edit, but shown if action=submit)
		if ( !document.getElementById( 'p-lang' ) && document.getElementById( 'p-tb' ) ) {
			// we need not hide this, because it's empty anyway
			$( '#p-tb' ).after( $( '<div>' ).attr( 'id', 'p-lang' ) );
		}

		// construct space for summary preview if missing
		if ( $( '.mw-summary-preview' ).length === 0 ) {
			$( '.editCheckboxes' ).before( $( '<div>' ).addClass( 'mw-summary-preview' ) );
		}

		// construct space for diff if missing. also load diff styles.
		if ( !document.getElementById( 'wikiDiff' ) && document.getElementById( 'wikiPreview' ) ) {
			$( '#wikiPreview' ).after( $( '<div>' ).attr( 'id', 'wikiDiff' ) );
			// diff styles are by default only loaded when needed
			// if there was no diff container, we can expect the styles not to be there either
			mw.loader.load( 'mediawiki.action.history.diff' );
		}

		$( '#wpPreview, #wpDiff' ).click( doLivePreview );
	} );
}) ( jQuery );
