const { test } = require( '../../../resources/src/mediawiki.page.ready/updateThumbnailsToPreferredSize.js' );
const { updateThumbnailToPreferredSize } = test;

const runTest = ( srcset ) => {
	const img = document.createElement( 'img' );
	img.srcset = srcset;
	updateThumbnailToPreferredSize( img );
	return img.srcset;
};

const runElementTest = ( src, srcset ) => {
	const img = document.createElement( 'img' );
	img.src = src;
	img.srcset = srcset;
	updateThumbnailToPreferredSize( img );
	return img;
};

describe( 'updateThumbnailToPreferredSize', () => {
	it( 'should shuffle srcset correctly', () => {
		const shuffled = runTest( 'b.jpg 1.5x, c.jpg 2x, d.jpg 4x' );
		expect( shuffled ).toBe( 'd.jpg 3x' );
	} );

	it( 'will not shuffle srcset where none exists', () => {
		const shuffled = runTest( '' );
		expect( shuffled ).toBe( '' );
	} );

	it( 'retain existing srcset if no better options exist', () => {
		const shuffled = runTest( 'a.jpg 1x, b.jpg 1.5x' );
		expect( shuffled ).toBe( 'a.jpg 1x, b.jpg 1.5x' );
	} );

	it( 'only shuffles retina', () => {
		const shuffled = runTest( 'new-york-skyline-wide.jpg 3724w, new-york-skyline-4by3.jpg 1961w, new-york-skyline-tall.jpg 1060w' );
		expect( shuffled ).toBe( 'new-york-skyline-wide.jpg 3724w, new-york-skyline-4by3.jpg 1961w, new-york-skyline-tall.jpg 1060w' );
	} );

	it( 'replaces src with 2x value', () => {
		const srcset = 'https://commons.wikimedia.org/500px-Michelangelo%2C_Creation_of_Adam_04.jpg 1.5x,' +
			'https://commons.wikimedia.org/720px-Michelangelo%2C_Creation_of_Adam_04.jpg 2x';
		const element = runElementTest(
			'https://commons.wikimedia.org/250px-Michelangelo%2C_Creation_of_Adam_04.jpg',
			srcset
		);
		expect( element.src ).toBe( 'https://commons.wikimedia.org/720px-Michelangelo%2C_Creation_of_Adam_04.jpg' );
		expect( element.srcset ).toBe( srcset );
	} );
} );
