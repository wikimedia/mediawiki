var Page = require( './page' ),
	userLogoutPage = Object.create( Page, {

		open: { value: function() {
			Page.open.call( this, 'Special:UserLogout' );
		} }

	} );
module.exports = userLogoutPage;
