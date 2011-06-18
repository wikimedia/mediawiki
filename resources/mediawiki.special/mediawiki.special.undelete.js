/*
 * JavaScript for Specical:Undelete
 * @author: Code taken from [[b:MediaWiki:Gadget-EnhancedUndelete.js]] (originally written by [[b:User:Darklama]])
 */
( function( $ ) {
	$(function() {
		$('#mw-undelete-invert').click( function(e) {
			e.stopImmediatePropagation();
			$('input:checkbox').each( function() {
				this.checked = !this.checked;
			});
			return false;
		});
	});
} )( jQuery );
