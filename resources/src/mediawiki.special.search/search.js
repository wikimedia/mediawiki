/*!
 * JavaScript for Special:Search
 */
( function () {
	$( function () {
		var $checkboxes, $headerLinks, updateHeaderLinks, searchWidget;

		// Emulate HTML5 autofocus behavior in non HTML5 compliant browsers
		if ( !( 'autofocus' in document.createElement( 'input' ) ) ) {
			$( 'input[autofocus]' ).eq( 0 ).trigger( 'focus' );
		}

		// Attach handler for check all/none buttons
		$checkboxes = $( '#powersearch input[id^=mw-search-ns]' );
		$( '#mw-search-toggleall' ).on( 'click', function () {
			$checkboxes.prop( 'checked', true );
		} );
		$( '#mw-search-togglenone' ).on( 'click', function () {
			$checkboxes.prop( 'checked', false );
		} );

		// Change the header search links to what user entered
		$headerLinks = $( '.search-types a' );
		searchWidget = OO.ui.infuse( $( '#searchText' ) );
		updateHeaderLinks = function ( value ) {
			$headerLinks.each( function () {
				var parts = $( this ).attr( 'href' ).split( 'search=' ),
					lastpart = '',
					prefix = 'search=';
				if ( parts.length > 1 && parts[ 1 ].indexOf( '&' ) !== -1 ) {
					lastpart = parts[ 1 ].slice( parts[ 1 ].indexOf( '&' ) );
				} else {
					prefix = '&search=';
				}
				this.href = parts[ 0 ] + prefix + encodeURIComponent( value ) + lastpart;
			} );
		};
		searchWidget.on( 'change', updateHeaderLinks );
		updateHeaderLinks( searchWidget.getValue() );

		// When saving settings, use the proper request method (POST instead of GET).
		$( '#mw-search-powersearch-remember' ).on( 'change', function () {
			this.form.method = this.checked ? 'post' : 'get';
		} ).trigger( 'change' );

	} );

}() );
