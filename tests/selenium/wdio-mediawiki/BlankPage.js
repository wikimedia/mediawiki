const Page = require( './Page' );

class BlankPage extends Page {
	get heading() { return browser.element( '#firstHeading' ); }

	open() {
		super.openTitle( 'Special:BlankPage', { uselang: 'en' } );
	}
}

module.exports = new BlankPage();
