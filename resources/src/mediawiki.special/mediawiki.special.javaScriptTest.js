/**
 * JavaScript for Special:JavaScriptTest
 */
( function ( mw, $ ) {
	$( function () {

		// Create useskin dropdown menu and reload onchange to the selected skin
		// (only if a framework was found, not on error pages).
		$( '#mw-javascripttest-summary' ).append( function () {

			var $html = $( '<p><label for="useskin">'
					+ mw.message( 'javascripttest-pagetext-skins' ).escaped()
					+ ' '
					+ '</label></p>' ),
				select = '<select name="useskin" id="useskin">';

			// Build <select> further
			$.each( mw.config.get( 'wgAvailableSkins' ), function ( id ) {
				select += '<option value="' + id + '"'
					+ ( mw.config.get( 'skin' ) === id ? ' selected="selected"' : '' )
					+ '>' + mw.message( 'skinname-' + id ).escaped() + '</option>';
			} );
			select += '</select>';

			// Bind onchange event handler and append to form
			$html.append(
				$( select ).change( function () {
					var url = new mw.Uri();
					location.href = url.extend( { useskin: $( this ).val() } );
				} )
			);

			return $html;
		} );
	} );

}( mediaWiki, jQuery ) );
