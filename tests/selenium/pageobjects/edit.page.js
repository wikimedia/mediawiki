const Page = require( 'wdio-mediawiki/Page' ),
	Api = require( 'wdio-mediawiki/Api' );

class EditPage extends Page {
	get content() { return browser.element( '#wpTextbox1' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }
	get heading() { return browser.element( '#firstHeading' ); }
	get save() { return browser.element( '#wpSave' ); }

	openForEditing( title ) {
		super.openTitle( title, { action: 'edit' } );
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
