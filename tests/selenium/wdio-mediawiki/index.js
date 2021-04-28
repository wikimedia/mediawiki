'use strict';

const fs = require( 'fs' );

/**
 * @since 1.1.0
 * @return {string} File name friendly version of ISO 8601 date and time
 */
function makeFilenameDate() {
	return new Date().toISOString().replace( /[:.]/g, '-' );
}

/**
 * Based on <https://github.com/webdriverio/webdriverio/issues/269#issuecomment-306342170>
 *
 * @since 1.0.0
 * @param {string} title Description (will be sanitised and used as file name)
 * @return {string} File path
 */
function saveScreenshot( title ) {
	// Create sane file name for current test title
	const filename = encodeURIComponent( title.replace( /\s+/g, '-' ) );
	const filePath = `${browser.config.screenshotPath}/${filename}.png`;
	// Ensure directory exists, based on WebDriverIO#saveScreenshotSync()
	try {
		fs.statSync( browser.config.screenshotPath );
	} catch ( err ) {
		fs.mkdirSync( browser.config.screenshotPath );
	}
	// Create and save screenshot
	browser.saveScreenshot( filePath );
	return filePath;
}

module.exports = {
	makeFilenameDate,
	saveScreenshot
};
