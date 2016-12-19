/* global browser */
var Page = require( './page' ),
	specialCreateAccountPage = Object.create( Page, {
    /**
     * define elements
     */
		username: { get: function () { return browser.element( '#wpName2' ); } },
		password: { get: function () { return browser.element( '#wpPassword2' ); } },
		confirmPassword: { get: function () { return browser.element( '#wpRetype' ); } },
		create: { get: function () { return browser.element( '#wpCreateaccount' ); } },
		heading: { get: function () { return browser.element( '#firstHeading' ); } },
    /**
     * define or overwrite page methods
     */
		open: { value: function() {
			Page.open.call( this, 'Special:CreateAccount' );
		} }
	} );
module.exports = specialCreateAccountPage;
