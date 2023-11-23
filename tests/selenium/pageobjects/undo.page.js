'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class UndoPage extends Page {

	get save() {
		return $( '#wpSave' );
	}

	async undo( title, previousRev, undoRev ) {
		await super.openTitle( title, {
			action: 'edit',
			undoafter: previousRev,
			undo: undoRev,
			// T276783: suppress welcome dialog that would prevent save if VisualEditor is installed
			vehidebetadialog: 1
		} );
		await this.save.click();
	}

}

module.exports = new UndoPage();
