/*
 * Legacy emulation for the now depricated skins/common/enhancedchanges.js
 */

( function( $, mw ) {

/* Extension */

$.extend( true, mw.legacy, {
	
	/* Functions */
	
	/**
	 * Switch an RC line between hidden/shown
	 * 
	 * @param integer idNumber : the id number of the RC group
	 */ 
	'toggleVisibility': function( idNumber ) {
		var elements = [
			'#mw-rc-openarrow-' + idNumber,
			'#mw-rc-closearrow-' + idNumber,
			'#mw-rc-subentries-' + idNumber
		];
		$( elements.join( ',' ) ).toggleClass( 'mw-changeslist-hidden' ).toggleClass( 'mw-changeslist-expanded' );
	}
} );

/* Initialization */

$( document ).ready( function() {
	/*
	* Add the CSS to hide parts that should be collapsed
	*
	* We do this with JS so everything will be expanded by default
	* if JS is disabled
	*/
	$( 'head' ).append(
		'<style type="text/css">\
			.mw-changeslist-hidden { display:none; }\
			div.mw-changeslist-expanded { display:block; }\
			span.mw-changeslist-expanded { display:inline !important; visibility:visible !important; }\
		</style>'
	);
} );

} )( jQuery, mediaWiki );