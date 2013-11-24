/**
 * JavaScript for Special:Search
 */
( function ( mw, $ ) {
	$( function () {
		var $checkboxes, $headerLinks;

		// Emulate HTML5 autofocus behavior in non HTML5 compliant browsers
		if ( !( 'autofocus' in document.createElement( 'input' ) ) ) {
			$( 'input[autofocus]' ).eq( 0 ).focus();
		}

		// Create check all/none button
		$checkboxes = $('#powersearch input[id^=mw-search-ns]');
		$('#mw-search-togglebox').append(
			$('<label>')
				.text(mw.msg('powersearch-togglelabel'))
		).append(
			$('<input type="button" />')
				.attr( 'id', 'mw-search-toggleall' )
				.prop( 'value', mw.msg('powersearch-toggleall' ) )
				.click( function () {
					$checkboxes.prop('checked', true);
				} )
		).append(
			$('<input type="button" />')
				.attr( 'id', 'mw-search-togglenone' )
				.prop( 'value', mw.msg('powersearch-togglenone' ) )
				.click( function() {
					$checkboxes.prop( 'checked', false );
				} )
		);

		// Change the header search links to what user entered
		$headerLinks = $( '.search-types a' );
		$( '#searchText, #powerSearchText' ).change( function () {
			var searchterm = $(this).val();
			$headerLinks.each( function () {
				var parts = $(this).attr('href').split( 'search=' ),
					lastpart = '',
					prefix = 'search=';
				if ( parts.length > 1 && parts[1].indexOf('&') >= 0 ) {
					lastpart = parts[1].substring( parts[1].indexOf('&') );
				} else {
					prefix = '&search=';
				}
				this.href = parts[0] + prefix + encodeURIComponent( searchterm ) + lastpart;
			});
		}).trigger( 'change' );

	} );

}( mediaWiki, jQuery ) );
