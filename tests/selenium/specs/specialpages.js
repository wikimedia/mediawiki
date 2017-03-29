'use strict';
const assert = require( 'assert' ),
	SpecialPages = require( '../pageobjects/specialpages.page' );

describe( 'Special:SpecialPages', function () {
	it( 'should not have Edit link', function () {
		SpecialPages.open();
		assert( !SpecialPages.edit.isExisting() );
	} );
} );
