const Page = require( 'wdio-mediawiki/Page' ),
	Api = require( 'wdio-mediawiki/Api' );

class EditPage extends Page {
	get content() { return $( '#wpTextbox1' ); }
	get conflictingContent() { return $( '#wpTextbox2' ); }
	get displayedContent() { return $( '#mw-content-text .mw-parser-output' ); }
	get heading() { return $( '#firstHeading' ); }
	get save() { return $( '#wpSave' ); }
	get previewButton() { return $( '#wpPreview' ); }

	openForEditing( title ) {
		super.openTitle( title, { action: 'edit' } );
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

	// @deprecated Use wdio-mediawiki/Api#edit() instead.
	apiEdit( name, content ) {
		return Api.edit( name, content );
	}
}

module.exports = new EditPage();
