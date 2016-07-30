/*
 * HTMLForm enhancements:
 * Infuse some OOjs UI HTMLForm fields (those which benefit from always being infused).
 */
( function ( mw ) {

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $oouiNodes, modules;

		$oouiNodes = $root.find( '.mw-htmlform-field-autoinfuse' );
		if ( $oouiNodes.length ) {
			// The modules are preloaded (added server-side in HTMLFormField, and the individual fields
			// which need extra ones), but this module doesn't depend on them. Wait until they're loaded.
			modules = [ 'mediawiki.htmlform.ooui' ];
			if ( $oouiNodes.filter( '.mw-htmlform-field-HTMLTitleTextField' ).length ) {
				// FIXME: TitleInputWidget should be in its own module
				modules.push( 'mediawiki.widgets' );
			}
			if ( $oouiNodes.filter( '.mw-htmlform-field-HTMLUserTextField' ).length ) {
				modules.push( 'mediawiki.widgets.UserInputWidget' );
			}
			if (
				$oouiNodes.filter( '.mw-htmlform-field-HTMLSelectNamespace' ).length ||
				$oouiNodes.filter( '.mw-htmlform-field-HTMLSelectNamespaceWithButton' ).length
			) {
				// FIXME: NamespaceInputWidget should be in its own module (probably?)
				modules.push( 'mediawiki.widgets' );
			}
			mw.loader.using( modules ).done( function () {
				$oouiNodes.each( function () {
					OO.ui.infuse( this );
				} );
			} );
		}

	} );

}( mediaWiki ) );
