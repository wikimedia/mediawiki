'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class UndoPage extends Page {

	get save() { return $( '#wpSave' ); }

	undo( title, previousRev, undoRev ) {
		super.openTitle( title, { action: 'edit', undoafter: previousRev, undo: undoRev } );
		this.save.click();
	}

}

module.exports = new UndoPage();
