/*!
 * mediawiki.filewarning
 *
 * @author Mark Holmquist, 2015
 * @since 1.25
 */
( function ( mw, $ ) {
	var dialogHovered = false, mimeHovered = false,
		warningMessages = mw.config.get( 'fileWarningMessages' ),
		warningLink = mw.config.get( 'fileWarningLink' ),
		$mimetype = $( '.fullMedia .fileInfo .mime-type' ),
		$dialog = $( '<div>' )
			.addClass( 'mediawiki-filewarning empty' )
			.appendTo( $( 'body' ) ),
		$warning = $( '<div>' )
			.addClass( 'mediawiki-filewarning-container' )
			.appendTo( $dialog )
			.append(
				$( '<div>' ).addClass( 'mediawiki-filewarning-arrow' )
			),
		$header = $( '<h3>' )
			.addClass( 'mediawiki-filewarning-header empty' )
			.appendTo( $warning ),
		$main = $( '<p>' )
			.addClass( 'mediawiki-filewarning-main empty' )
			.appendTo( $warning ),
		$info = $( '<a>' )
			.addClass( 'mediawiki-filewarning-info empty' )
			.appendTo( $warning ),
		$footer = $( '<p>' )
			.addClass( 'mediawiki-filewarning-footer empty' )
			.appendTo( $warning );

	function loadMessage( $target, message ) {
		$target.removeClass( 'empty' )
			.text( mw.message( message ).text() );
	}

	function tryOpen() {
		var offset = $mimetype.offset();

		$dialog
			.css( {
				top: ( offset.top + $mimetype.height() + 10 ) + 'px',
				left: ( offset.left - ( $dialog.width() - $mimetype.width() ) + 10 ) + 'px'
			} )
			.addClass( 'shown' );
	}

	function tryClose() {
		// Give the events a little bit of time to catch up.
		setTimeout( function () {
			if ( !dialogHovered && !mimeHovered ) {
				$dialog.removeClass( 'shown' );
			}
		}, 200 );
	}

	if ( warningMessages ) {
		if ( warningMessages.main ) {
			loadMessage( $main, warningMessages.main );

			// The main message must be populated for the dialog to show.
			$dialog.removeClass( 'empty' );
			$mimetype.addClass( 'has-warning' );
		}

		if ( warningMessages.header ) {
			loadMessage( $header, warningMessages.header );
		}

		if ( warningMessages.footer ) {
			loadMessage( $footer, warningMessages.footer );
		}

		if ( warningMessages.info && warningLink ) {
			loadMessage( $info, warningMessages.info );
			$info.attr( 'href', warningLink );
		}
	}

	$mimetype.hover( function () {
		mimeHovered = true;
		tryOpen();
	}, function () {
		mimeHovered = false;
		tryClose();
	} );

	$dialog.hover( function () {
		dialogHovered = true;
	}, function () {
		dialogHovered = false;
		tryClose();
	} );
}( mediaWiki, jQuery ) );
