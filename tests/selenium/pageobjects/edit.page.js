'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class EditPage extends Page {
	get content() { return $( '#wpTextbox1' ); }
	get conflictingContent() { return $( '#wpTextbox2' ); }
	get displayedContent() { return $( '#mw-content-text .mw-parser-output' ); }
	get heading() { return $( '.firstHeading' ); }
	get save() { return $( '#wpSave' ); }
	get previewButton() { return $( '#wpPreview' ); }

	openForEditing( title ) {
		super.openTitle( title, { action: 'edit', vehidebetadialog: 1, hidewelcomedialog: 1 } );
	}

	preview( name, content ) {
		this.openForEditing( name );
		this.content.setValue( content );
		this.previewButton.click();
	}

	edit( name, content ) {
		this.openForEditing( name );
		this.content.setValue( content );
		this.save.click();
	}
}

module.exports = new EditPage();
