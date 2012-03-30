/* Module for displaying a toggle link for hidden categories */
(function( $, mw ) {

	$( '#catlinks' ).attr( 'class', 'catlinks' );
	var count = $( '#mw-hidden-catlinks' ).find( 'li' ).length;
	var $toggleLink = $( '<a>', {
		id: 'mw-cat-toggle',
		href: '#',
		text: mw.msg( 'category-toggle-expand', count )
	} ).toggle(
		function(){
			$( '#mw-hidden-catlinks' ).show();
			$( '#mw-cat-toggle' ).text( mw.msg( 'category-toggle-collapse', count ) );
		},
		function(){
			$( '#mw-hidden-catlinks' ).hide();
			$( '#mw-cat-toggle' ).text( mw.msg( 'category-toggle-expand', count ) );
		}
	);
	$( '#mw-hidden-catlinks').before( $toggleLink );

})( jQuery, mediaWiki );