'use strict';
import Page from './page';

class HistoryPage extends Page {

	get comment() { return browser.element( '#pagehistory .comment' ); }

	open( name ) {
		super.open( name + '&action=history' );
	}

}
export default new HistoryPage();
