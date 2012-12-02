/**
 * Transfer of genitive month names from messages into mw.config
 */
( function ( mw ) {

	var monthNames = [ '', mw.msg( 'january-gen' ), mw.msg( 'february-gen' ), mw.msg( 'march-gen' ),
		mw.msg( 'april-gen' ), mw.msg( 'may-gen' ), mw.msg( 'june-gen' ), mw.msg( 'july-gen' ),
		mw.msg( 'august-gen' ), mw.msg( 'september-gen' ), mw.msg( 'october-gen' ), mw.msg( 'november-gen' ),
		mw.msg( 'december-gen' )
	];

	mw.config.set( 'wgMonthNamesGen', monthNames );
}( mediaWiki ) );
