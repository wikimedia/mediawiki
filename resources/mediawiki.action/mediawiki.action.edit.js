/* Note, there is still stuff in skins/common/edit.js that
 * has not been jQuery-ized.
 */

(function( $ ) {
	//make sure edit summary does not exceed byte limit
	$( '#wpSummary' ).attr( 'maxLength', 250 ).keypress( function( e ) {
		// first check to see if this is actually a character key
		// being pressed.
		// Based on key-event info from http://unixpapa.com/js/key.html
		// JQuery should also normalize e.which to be consistent cross-browser,
		// however the same check is still needed regardless of jQuery.

		if ( e.which === 0 || e.charCode === 0 || e.ctrlKey || e.altKey || e.metaKey ) {
			return true; //a special key (backspace, etc) so don't interfere.
		}

		// This basically figures out how many bytes a UTF-16 string (which is what js sees)
		// will take in UTF-8 by replacing a 2 byte character with 2 *'s, etc, and counting that.
		// Note, surrogate (\uD800-\uDFFF) characters are counted as 2 bytes, since there's two of them
		// and the actual character takes 4 bytes in UTF-8 (2*2=4). Might not work perfectly in edge cases
		// such as illegal sequences, but that should never happen.

		var len = this.value.replace( /[\u0080-\u07FF\uD800-\uDFFF]/g, '**' ).replace( /[\u0800-\uD7FF\uE000-\uFFFF]/g, '***' ).length;
		//247 as this doesn't count character about to be inserted.
		if ( len > 247 ) {
			e.preventDefault();
		}
	});
})(jQuery);
