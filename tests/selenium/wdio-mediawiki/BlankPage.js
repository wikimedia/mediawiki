'use strict';

const Page = require( './Page' );

class BlankPage extends Page {
	get heading() { return $( '.firstHeading' ); }

	open() {
		super.openTitle( 'Special:BlankPage', { uselang: 'en' } );
	}
}

module.exports = new BlankPage();
