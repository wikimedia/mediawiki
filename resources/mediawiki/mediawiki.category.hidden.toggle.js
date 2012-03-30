/* Module for displaying a toggle link for hidden categories */
( function( mw, $ ) {

	$( '#catlinks' ).removeClass( 'catlinks-allhidden' ).addClass( 'catlinks' );
	var $hiddenCatlinks = $( '#mw-hidden-catlinks' ),
		count = $hiddenCatlinks.find( 'li' ).length;
	$hiddenCatlinks.before( $( '<a>' ).attr( {
		id: 'mw-cat-toggle',
		href: '#'
	} )
	.text( mw.msg( 'category-toggle-expand', count ) )
	.toggle(
		function() {
			$hiddenCatlinks.show();
			$( '#mw-cat-toggle' ).text( mw.msg( 'category-toggle-collapse', count ) );
		},
		function() {
			$hiddenCatlinks.hide();
			$( '#mw-cat-toggle' ).text( mw.msg( 'category-toggle-expand', count ) );
		}
	)
	);

} )( mediaWiki, jQuery );