'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class EditPage extends Page {
	get content() { return $( '#wpTextbox1' ); }
	get conflictingContent() { return $( '#wpTextbox2' ); }
	get displayedContent() { return $( '#mw-content-text .mw-parser-output' ); }
	get heading() { return $( '#firstHeading' ); }
	get save() { return $( '#wpSave' ); }
	get previewButton() { return $( '#wpPreview' ); }

	async openForEditing( title ) {
		await super.openTitle( title, { action: 'submit', vehidebetadialog: 1, hidewelcomedialog: 1 } );
		// Compatibility with CodeMirror extension (T324879)
		const cmButton = $( '.mw-editbutton-codemirror-active' );
		if ( await cmButton.isExisting() ) {
			await cmButton.click();
		}
	}

	async preview( name, content ) {
		await this.openForEditing( name );
		await this.content.setValue( content );
		await this.previewButton.click();
	}

	async edit( name, content ) {
		await this.openForEditing( name );
		await this.content.setValue( content );
		await this.save.click();
	}
}

module.exports = new EditPage();
