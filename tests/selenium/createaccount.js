var Bot = require( 'nodemw' ),
	client = new Bot( {
		protocol: 'http',
		server: '127.0.0.1',
		port: '8080',
		path: '/w',
		debug: true
	} ),
	params = {
		action: 'query',
		meta: 'tokens',
		type: 'createaccount'
	};

client.api.call( params /* api.php parameters */, function ( err /* Error instance or null */, info /* processed query result */, next /* more results? */, data /* raw data */ ) {

	var paramsCreateaccount = {
		action: 'createaccount',
		createreturnurl: 'http://127.0.0.1:8080/',
		createtoken: data.query.tokens.createaccounttoken,
		username: 'User-4',
		password: 'password',
		retype: 'password'
	};

	// log all the things
	console.log( '\n-params-' );
	console.log( params );
	console.log( '\n-err-' );
	console.log( err );
	console.log( '\n-info-' );
	console.log( info );
	console.log( '\n-next-' );
	console.log( next );
	console.log( '\n-data-' );
	console.log( data );
	console.log( '\n-data.query.tokens.createaccounttoken-' );
	console.log( data.query.tokens.createaccounttoken );

	client.api.call( paramsCreateaccount /* api.php parameters */, function ( err /* Error instance or null */, info /* processed query result */, next /* more results? */, data /* raw data */ ) {

		// log all the things
		console.log( '\n-params-' );
		console.log( params );
		console.log( '\n-err-' );
		console.log( err );
		console.log( '\n-info-' );
		console.log( info );
		console.log( '\n-next-' );
		console.log( next );
		console.log( '\n-data-' );
		console.log( data );

	}, 'POST' );

} );
