var Bot = require( 'nodemw' ),
	client = new Bot( {
		protocol: 'https',
		server: 'en.wikipedia.beta.wmflabs.org',
		path: '/w',
		username: 'Selenium_user',
		password: process.env.MEDIAWIKI_PASSWORD,
		debug: true
	} );

client.logIn( function ( err ) {
	if ( err ) {
		console.log( err );
		return;
	}
} );
