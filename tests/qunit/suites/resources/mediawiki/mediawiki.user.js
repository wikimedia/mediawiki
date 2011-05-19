module( 'mediawiki.user.js' );

test( '-- Initial check', function(){

	ok( mw.user, 'mw.user defined' );

});


test( 'options', function(){

	ok( mw.user.options instanceof mw.Map, 'options instance of mw.Map' );

});

test( 'User login status', function(){

	deepEqual( mw.user.name(), null, 'user.name() When anonymous' );
	ok( mw.user.anonymous(), 'user.anonymous() When anonymous' );

	// Not part of startUp module
	mw.config.set( 'wgUserName', 'John' );

	equal( mw.user.name(), 'John', 'user.name() When logged-in as John' );
	ok( !mw.user.anonymous(), 'user.anonymous() When logged-in' );

	equal( mw.user.id(), 'John', 'user.id() When logged-in as John' );


});