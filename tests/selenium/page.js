module.exports = {

	'Page should be created': function ( client ) {
		client
		.url( client.launch_url + 'Does_not_exist' ) // jscs:ignore requireCamelCaseOrUpperCaseIdentifiers
		.assert.elementPresent( 'li#ca-edit a' )
		.end();
	},

	'Page should be edited': function ( client ) {
		client
		.url( client.launch_url + 'Main_Page' ) // jscs:ignore requireCamelCaseOrUpperCaseIdentifiers
		.assert.elementPresent( 'li#ca-edit a' )
		.end();
	},

	'Page should have history': function ( client ) {
		client
		.url( client.launch_url + 'Main_Page' ) // jscs:ignore requireCamelCaseOrUpperCaseIdentifiers
		.assert.elementPresent( 'li#ca-history a' )
		.end();
	}

};
