( function ( $ ) {
	// Waiting until dom ready as the module is loaded in the head.
	$( document ).ready( function () {
		$( '.templatesUsed' ).footerCollapsibleList( {
			name: 'templates-used-list',
			title: mw.msg( 'templatesusedlabel' )
		} );

		$( '.hiddencats' ).footerCollapsibleList( {
			name: 'hidden-categories-list',
			title: mw.msg( 'hiddencategorieslabel' )
		} );
	} );
} ( jQuery ) );
