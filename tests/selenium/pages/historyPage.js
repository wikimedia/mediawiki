/* global browser */
var Page = require( './page' ),
	historyPage = Object.create( Page, {

		comment: { get: function () { return browser.element( '#pagehistory .comment' ); } },

		open: { value: function( name ) {
			Page.open.call( this, name + '&action=history' );
		} }

	} );
module.exports = historyPage;
