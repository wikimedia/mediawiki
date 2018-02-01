/*
 * HTMLForm enhancements:
 * Infuse some OOUI HTMLForm fields (those which benefit from always being infused).
 */
( function ( mw, $ ) {

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $oouiNodes, modules, extraModules;

		$oouiNodes = $root.find( '.mw-htmlform-field-autoinfuse' );
		if ( $oouiNodes.length ) {
			// The modules are preloaded (added server-side in HTMLFormField, and the individual fields
			// which need extra ones), but this module doesn't depend on them. Wait until they're loaded.
			modules = [ 'mediawiki.htmlform.ooui' ];
			$oouiNodes.each( function () {
				var data = $( this ).data( 'mw-modules' );
				if ( data ) {
					// We can trust this value, 'data-mw-*' attributes are banned from user content in Sanitizer
					extraModules = data.split( ',' );
					modules.push.apply( modules, extraModules );
				}
			} );
			mw.loader.using( modules ).done( function () {
				$oouiNodes.each( function () {
					OO.ui.infuse( this );
				} );
			} );
		}

	} );

}( mediaWiki, jQuery ) );
