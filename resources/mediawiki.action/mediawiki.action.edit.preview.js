/**
 * Live edit preview.
 */
( function ( mw, $ ) {

	/**
	 * @param {jQuery.Event} e
	 */
	function doLivePreview( e ) {
		var $wikiPreview, copySelectors, removeSelectors, $copyElements, $spinner,
			targetUrl, postData, $previewDataHolder;

		e.preventDefault();

		$( mw ).trigger( 'LivePreviewPrepare' );

		$wikiPreview = $( '#wikiPreview' );

		// Show #wikiPreview if it's hidden to be able to scroll to it
		// (if it is hidden, it's also empty, so nothing changes in the rendering)
		$wikiPreview.show();

		// Jump to where the preview will appear
		$wikiPreview[0].scrollIntoView();

		// List of selectors matching elements that we will
		// update from from the ajax-loaded preview page.
		copySelectors = [
			// Main
			'#wikiPreview',
			'#wikiDiff',
			'#catlinks',
			'.hiddencats',
			'#p-lang',
			// Editing-related
			'.templatesUsed',
			'.mw-summary-preview'
		];
		$copyElements = $( copySelectors.join( ',' ) );

		// Not shown during normal preview, to be removed if present
		removeSelectors = [
			'.mw-newarticletext'
		];

		$( removeSelectors.join( ',' ) ).remove();

		$spinner = $.createSpinner( {
			size: 'large',
			type: 'block'
		});
		$wikiPreview.before( $spinner );
		$spinner.css( {
			position: 'absolute',
			marginTop: $spinner.height()
		} );
		// Make sure preview area is at least as tall as 2x the height of the spinner.
		// 1x because if its smaller, it will spin behind the edit toolbar.
		// (this happens on the first preview when editPreview is still empty)
		// 2x because the spinner has 1x margin top breathing room.
		$wikiPreview.css( 'minHeight', $spinner.height() * 2 );

		// Can't use fadeTo because it calls show(), and we might want to keep some elements hidden
		// (e.g. empty #catlinks)
		$copyElements.animate( {
			opacity: 0.4
		}, 'fast' );

		$previewDataHolder = $( '<div>' );
		targetUrl = $( '#editform' ).attr( 'action' );

		// Gather all the data from the form
		postData = $( '#editform' ).formToArray();
		postData.push( {
			name: e.target.name,
			value: ''
		} );

		// Load new preview data.
		// TODO: This should use the action=parse API instead of loading the entire page
		// Though that requires figuring out how to conver that raw data into proper HTML.
		$previewDataHolder.load( targetUrl + ' ' + copySelectors.join( ',' ), postData, function () {
			var i, $from;
			// Copy the contents of the specified elements from the loaded page to the real page.
			// Also copy their class attributes.
			for ( i = 0; i < copySelectors.length; i++ ) {
				$from = $previewDataHolder.find( copySelectors[i] );

				$( copySelectors[i] )
					.empty()
					.append( $from.contents() )
					.attr( 'class', $from.attr( 'class' ) );
			}

			$spinner.remove();
			$copyElements.animate( {
				opacity: 1
			}, 'fast' );

			$( mw ).trigger( 'LivePreviewDone', [copySelectors] );
		} );
	}

	$( document ).ready( function () {
		// The following elements can change in a preview but are not output
		// by the server when they're empty until the preview reponse.
		// TODO: Make the server output these always (in a hidden state), so we don't
		// have to fish and (hopefully) put them in the right place (since skins
		// can change where they are output).

		if ( !document.getElementById( 'p-lang' ) && document.getElementById( 'p-tb' ) ) {
			$( '#p-tb' ).after(
				$( '<div>' ).prop( 'id', 'p-lang' )
			);
		}

		if ( !$( '.mw-summary-preview' ).length ) {
			$( '.editCheckboxes' ).before(
				$( '<div>' ).prop( 'className', 'mw-summary-preview' )
			);
		}

		if ( !document.getElementById( 'wikiDiff' ) && document.getElementById( 'wikiPreview' ) ) {
			$( '#wikiPreview' ).after(
				$( '<div>' ).prop( 'id', 'wikiDiff')
			);
		}

		// Make sure diff styles are loaded
		mw.loader.load( 'mediawiki.action.history.diff' );

		$( document.body ).on( 'click', '#wpPreview, #wpDiff', doLivePreview );
	} );

}( mediaWiki, jQuery ) );
