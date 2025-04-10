'use strict';

describe( 'SpecialBlock init.js', () => {
	let targetInput, Vue;

	beforeEach( () => {
		document.body.innerHTML = '';
		const form = document.createElement( 'form' );
		form.className = 'mw-htmlform';
		document.body.appendChild( form );
		targetInput = document.createElement( 'input' );
		targetInput.id = 'mw-bi-target';
		form.appendChild( targetInput );
		mw.Api.prototype.loadMessagesIfMissing = jest.fn().mockResolvedValue( undefined );
		Vue = require( 'vue' );
		Vue.createMwApp = jest.fn().mockReturnValue( {
			use: jest.fn().mockReturnValue( {
				mount: jest.fn()
			} )
		} );
	} );

	afterEach( () => {
		jest.resetModules();
	} );

	it( 'should give the form the ID mw-block-form', () => {
		const mockConfig = {};
		mw.config.set = ( key, value ) => {
			mockConfig[ key ] = value;
		};
		require( '../../../resources/src/mediawiki.special.block/init.js' );
		expect( document.querySelector( '.mw-htmlform' ).id ).toBe( 'mw-block-form' );
	} );

	it( 'should do nothing if there is no mw-htmlform', () => {
		const htmlForm = document.querySelector( '.mw-htmlform' );
		htmlForm.className = 'not-mw-htmlform';
		require( '../../../resources/src/mediawiki.special.block/init.js' );
		expect( htmlForm.id ).not.toBe( 'mw-block-form' );
		expect( mw.Api.prototype.loadMessagesIfMissing ).not.toHaveBeenCalled();
		expect( Vue.createMwApp ).not.toHaveBeenCalled();
	} );
} );
