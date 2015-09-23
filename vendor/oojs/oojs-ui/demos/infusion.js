// Demonstrate JavaScript 'infusion' of PHP-generated widgets.
// Used by widgets.php.

var infuseButton;

// Helper function to get high resolution profiling data, where available.
function now() {
	/*global performance */
	return ( typeof performance !== 'undefined' ) ? performance.now() :
		Date.now ? Date.now() : new Date().getTime();
}

// Add a button to infuse everything!
// (You wouldn't typically do this: you'd only infuse those objects which you needed to attach
// client-side behaviors to, or where the JS implementation provides additional features over PHP,
// like DropdownInputWidget. We do it here because it's a good overall test.)
function infuseAll() {
	var start, end, all;
	start = now();
	all = $( '*[data-ooui]' ).map( function ( _, e ) {
		return OO.ui.infuse( e.id );
	} );
	end = now();
	window.console.log( 'Infusion time: ' + ( end - start ) );
	infuseButton.setDisabled( true );
}

// More typical usage: we take the existing server-side
// button group and do things to it, here adding a new button.
infuseButton = new OO.ui.ButtonWidget( { label: 'Infuse' } )
	.on( 'click', infuseAll );

OO.ui.ButtonGroupWidget.static.infuse( 'oo-ui-demo-menu-infuse' )
	.addItems( [ infuseButton ] );
