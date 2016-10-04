module.exports = {
	immediate: require( 'test.require.define' ),
	later: function () {
		return require( 'test.require.define' );
	}
};
