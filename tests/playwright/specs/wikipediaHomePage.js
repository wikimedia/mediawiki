const assert = require( 'assert' );
const { chromium } = require( 'playwright' );

( async () => {
	let browser, page;
	browser = await chromium.launch( { headless: false } );

	page = await browser.newPage();
	await page.goto( 'https://www.wikipedia.org/' );

	const text = await page.innerText( '.localized-slogan' );
	assert( text === 'The Free Encyclopedia' );
	await browser.close();
} )();
