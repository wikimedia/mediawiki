/*!
 * Live edit preview.
 */
( function () {

	var parsedMessages = require( './mediawiki.action.edit.preview.parsedMessages.json' ),
		api = new mw.Api();

	/**
	 * Parse preview response
	 *
	 * @ignore
	 * @param {Object} response Response data
	 */
	function showPreviewNotes( response ) {
		var arrow, $previewHeader, $editform;

		$editform = $( '#editform' );

		arrow = $( document.body ).css( 'direction' ) === 'rtl' ? '←' : '→';
		$previewHeader = $( '<div>' )
			.addClass( 'previewnote' )
			.append( $( '<h2>' )
				.attr( 'id', 'mw-previewheader' )
				.text( mw.msg( 'preview' ) )
			)
			.append( $( '<div>' )
				.addClass( 'mw-message-box-warning mw-message-box' )
				.html( parsedMessages.previewnote )
				.append( ' ' )
				.append( $( '<span>' )
					.addClass( 'mw-continue-editing' )
					.append( $( '<a>' )
						.attr( 'href', '#' + $editform.attr( 'id' ) )
						.text( arrow + ' ' + mw.msg( 'continue-editing' ) )
					)
				)
			);
		response.parse.parsewarningshtml.forEach( function ( warning ) {
			$previewHeader.find( '.mw-message-box-warning' ).append( $( '<p>' ).append( warning ) );
		} );

		$( '#wikiPreview' ).prepend( $previewHeader );
	}

	/**
	 * @ignore
	 * @param {jQuery.Event} e
	 */
	function doLivePreview( e ) {
		var isDiff, section, $editform, $textbox, preview, $wikiPreview;

		preview = require( 'mediawiki.page.preview' );
		isDiff = ( e.target.name === 'wpDiff' );
		$wikiPreview = $( '#wikiPreview' );
		$editform = $( '#editform' );
		$textbox = $editform.find( '#wpTextbox1' );

		section = $editform.find( '[name="wpSection"]' ).val();

		if ( $textbox.length === 0 ) {
			return;
		}
		// Show changes for a new section is not yet supported
		if ( isDiff && section === 'new' ) {
			return;
		}

		e.preventDefault();

		// Not shown during normal preview, to be removed if present
		$( '.mw-newarticletext, .mw-message-box-error' ).remove();

		// Show #wikiPreview if it's hidden to be able to scroll to it.
		// (If it is hidden, it's also empty, so nothing changes in the rendering.)
		$wikiPreview.show();

		// Jump to where the preview will appear
		$wikiPreview[ 0 ].scrollIntoView();

		var $spinner = $( '.mw-spinner-preview' );
		if ( $spinner.length === 0 ) {
			$spinner = $.createSpinner( {
				size: 'large',
				type: 'block'
			} )
				.addClass( 'mw-spinner-preview' )
				.css( 'margin-top', '1em' );
			$wikiPreview.before( $spinner );
		}

		preview.doPreview( {
			showDiff: isDiff,
			spinnerNode: $spinner
		} ).done( function ( response ) {
			if ( !isDiff ) {
				showPreviewNotes( response[ 0 ] );
			}
		} ).fail( function ( code, result ) {
			// This just shows the error for whatever request failed first
			var $errorMsg = api.getErrorMessage( result ),
				$errorBox = $( '<div>' )
					.addClass( 'mw-message-box-error mw-message-box' )
					.append( $( '<strong>' ).text( mw.msg( 'previewerrortext' ) ) )
					.append( $errorMsg );
			$wikiPreview.hide().before( $errorBox );
		} );
	}

	$( function () {
		var selector;

		// Enable only live diff on user .js/.css pages, as there's no sensible way of
		// "previewing" the scripts or styles without reloading the page.
		if ( $( '#mw-userjsyoucanpreview, #mw-usercssyoucanpreview, #mw-userjspreview, #mw-usercsspreview' ).length ) {
			selector = '#wpDiff';
		} else {
			selector = '#wpPreview, #wpDiff';
		}

		// The following elements can change in a preview but are not output
		// by the server when they're empty until the preview response.
		// TODO: Make the server output these always (in a hidden state), so we don't
		// have to fish and (hopefully) put them in the right place (since skins
		// can change where they are output).
		// FIXME: This is prone to breaking any time Vector's HTML for portals change.

		if ( !document.getElementById( 'p-lang' ) && document.getElementById( 'p-tb' ) && ( mw.config.get( 'skin' ) === 'vector' || mw.config.get( 'skin' ) === 'vector-2022' ) ) {
			$( '.portal' ).last().after(
				$( '<nav>' )
					.attr( {
						class: 'mw-portlet mw-portlet-lang vector-menu vector-menu-portal portal',
						id: 'p-lang',
						role: 'navigation',
						'aria-labelledby': 'p-lang-label'
					} )
					.append(
						$( '<label>' )
							.attr( {
								id: 'p-lang-label',
								class: 'vector-menu-heading'
							} )
							.append(
								$( '<span>' )
									.addClass( 'vector-menu-heading-label' )
									.text( mw.msg( 'otherlanguages' ) )
							)
					)
					.append(
						$( '<div>' )
							.addClass( 'body vector-menu-content' )
							.append( $( '<ul>' ).addClass( 'vector-menu-content-list' ) )
					)
			);
		}

		if ( !$( '.mw-summary-preview' ).length ) {
			$( '#wpSummaryWidget' ).after(
				$( '<div>' ).addClass( 'mw-summary-preview' )
			);
		}

		if ( !document.getElementById( 'wikiDiff' ) && document.getElementById( 'wikiPreview' ) ) {
			var alignStart, rtlDir;
			rtlDir = $( '#wpTextbox1' ).attr( 'dir' ) === 'rtl';
			alignStart = rtlDir ? 'right' : 'left';
			$( '#wikiPreview' ).after(
				$( '<div>' )
					.hide()
					.attr( 'id', 'wikiDiff' )
					// The following classes are used here:
					// * diff-editfont-monospace
					// * diff-editfont-sans-serif
					// * diff-editfont-serif
					.addClass( 'diff-editfont-' + mw.user.options.get( 'editfont' ) )
					// The following classes are used here:
					// * diff-contentalign-left
					// * diff-contentalign-right
					.addClass( 'diff-contentalign-' + alignStart )
					.append(
						$( '<table>' ).addClass( 'diff' ).append(
							$( '<col>' ).addClass( 'diff-marker' ),
							$( '<col>' ).addClass( 'diff-content' ),
							$( '<col>' ).addClass( 'diff-marker' ),
							$( '<col>' ).addClass( 'diff-content' ),
							$( '<thead>' ).append(
								$( '<tr>' ).addClass( 'diff-title' ).append(
									$( '<td>' )
										.attr( 'colspan', 2 )
										.addClass( 'diff-otitle diff-side-deleted' )
										.text( mw.msg( 'currentrev' ) ),
									$( '<td>' )
										.attr( 'colspan', 2 )
										.addClass( 'diff-ntitle diff-side-added' )
										.text( mw.msg( 'yourtext' ) )
								)
							),
							$( '<tbody>' )
						)
					)
			);
		}

		// This should be moved down to '#editform', but is kept on the body for now
		// because the LiquidThreads extension is re-using this module with only half
		// the EditPage (doesn't include #editform presumably, T57463).
		$( document.body ).on( 'click', selector, doLivePreview );
	} );

}() );
