/*!
 * Live edit preview.
 */
( function () {
	'use strict';

	const parsedMessages = require( './mediawiki.action.edit.preview.parsedMessages.json' );

	/**
	 * @ignore
	 * @param {jQuery.Event} e
	 */
	function doLivePreview( e ) {
		const promise = require( 'mediawiki.page.preview' ).doPreview( {
			showDiff: e.target.name === 'wpDiff',
			isLivePreview: true,
			previewHeader: mw.msg( 'preview' ),
			previewNote: parsedMessages.previewnote,
			createSpinner: true
		} );

		if ( !promise ) {
			// Something has gone wrong, so submit the form the normal way.
			return;
		}

		e.preventDefault();
	}

	$( () => {
		let selector;

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

		// This should be moved down to '#editform', but is kept on the body for now
		// because the LiquidThreads extension is re-using this module with only half
		// the EditPage (doesn't include #editform presumably, T57463).
		$( document.body ).on( 'click', selector, doLivePreview );
	} );

}() );
