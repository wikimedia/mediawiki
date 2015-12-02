var malu = require( 'malu' );

before( function () {
	var self = this;

	return malu.runner.start().then( function ( running ) {
		self.runtime = running.runtime;
		self.wiki = running.wiki;

		afterEach( function () {
			return this.runtime.finish( this.currentTest );
		} );
	} );
} );
