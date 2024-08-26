import Page from './Page.js';

class BlankPage extends Page {
	get heading() {
		return $( '#firstHeading' );
	}

	async open() {
		await super.openTitle( 'Special:BlankPage', { uselang: 'en' } );
	}
}

export default new BlankPage();
