/* Module for displaying a toggle link for hidden categories */
( function( mw, $ ) {

	$( '#catlinks' ).removeClass( 'catlinks-allhidden' ).addClass( 'catlinks' );
	var $hiddenCatlinks = $( '#mw-hidden-catlinks' );
	var count = $hiddenCatlinks.find( 'li' ).length;
	var $toggleLink = $( '<a>' ).attr( {
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
	);
	$hiddenCatlinks.before( $toggleLink );

} )( mediaWiki, jQuery );