const fs = require( 'fs' );

module.exports = {
	/**
	 * Based on <https://github.com/webdriverio/webdriverio/issues/269#issuecomment-306342170>
	 *
	 * @since 1.0.0
	 * @param {string} title Description (will be sanitised and used as file name)
	 * @return {string} File path
	 */
	saveScreenshot( title ) {
		var filename, filePath;
		// Create sane file name for current test title
		filename = encodeURIComponent( title.replace( /\s+/g, '-' ) );
		filePath = `${browser.options.screenshotPath}/${filename}.png`;
		// Ensure directory exists, based on WebDriverIO#saveScreenshotSync()
		try {
			fs.statSync( browser.options.screenshotPath );
		} catch ( err ) {
			fs.mkdirSync( browser.options.screenshotPath );
		}
		// Create and save screenshot
		browser.saveScreenshot( filePath );
		return filePath;
	}
};
