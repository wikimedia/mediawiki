/**
 * Live edit preview.
 */
( function ( mw, $ ) {

	/**
	 * @param {jQuery.Event} e
	 */
	function doLivePreview( e ) {
		var $wikiPreview, $editform, copySelectors, $copyElements, $spinner,
			targetUrl, postData, $previewDataHolder;

		e.preventDefault();

		// Deprecated: Use mw.hook instead
		$( mw ).trigger( 'LivePreviewPrepare' );

		$wikiPreview = $( '#wikiPreview' );
		$editform = $( '#editform' );

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
			'.limitreport',
			'.mw-summary-preview'
		];
		$copyElements = $( copySelectors.join( ',' ) );

		// Not shown during normal preview, to be removed if present
		$( '.mw-newarticletext' ).remove();

		$spinner = $.createSpinner( {
			size: 'large',
			type: 'block'
		});
		$wikiPreview.before( $spinner );
		$spinner.css( {
			marginTop: $spinner.height()
		} );

		// Can't use fadeTo because it calls show(), and we might want to keep some elements hidden
		// (e.g. empty #catlinks)
		$copyElements.animate( { opacity: 0.4 }, 'fast' );

		$previewDataHolder = $( '<div>' );
		targetUrl = $editform.attr( 'action' );

		// Gather all the data from the form
		postData = $editform.formToArray();
		postData.push( {
			name: e.target.name,
			value: ''
		} );

		// Load new preview data.
		// TODO: This should use the action=parse API instead of loading the entire page,
		// although that requires figuring out how to convert that raw data into proper HTML.
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

			// Deprecated: Use mw.hook instead
			$( mw ).trigger( 'LivePreviewDone', [copySelectors] );

			mw.hook( 'wikipage.content' ).fire( $wikiPreview );

			$spinner.remove();
			$copyElements.animate( {
				opacity: 1
			}, 'fast' );
		} );
	}

	$( function () {
		// Do not enable on user .js/.css pages, as there's no sane way of "previewing"
		// the scripts or styles without reloading the page.
		if ( $( '#mw-userjsyoucanpreview' ).length || $( '#mw-usercssyoucanpreview' ).length ) {
			return;
		}

		// The following elements can change in a preview but are not output
		// by the server when they're empty until the preview response.
		// TODO: Make the server output these always (in a hidden state), so we don't
		// have to fish and (hopefully) put them in the right place (since skins
		// can change where they are output).

		if ( !document.getElementById( 'p-lang' ) && document.getElementById( 'p-tb' ) ) {
			$( '#p-tb' ).after(
				$( '<div>' ).attr( 'id', 'p-lang' )
			);
		}

		if ( !$( '.mw-summary-preview' ).length ) {
			$( '.editCheckboxes' ).before(
				$( '<div>' ).addClass( 'mw-summary-preview' )
			);
		}

		if ( !document.getElementById( 'wikiDiff' ) && document.getElementById( 'wikiPreview' ) ) {
			$( '#wikiPreview' ).after(
				$( '<div>' ).attr( 'id', 'wikiDiff' )
			);
		}

		// This should be moved down to '#editform', but is kept on the body for now
		// because the LiquidThreads extension is re-using this module with only half
		// the EditPage (doesn't include #editform presumably, bug 55463).
		$( document.body ).on( 'click', '#wpPreview, #wpDiff', doLivePreview );
	} );

}( mediaWiki, jQuery ) );
