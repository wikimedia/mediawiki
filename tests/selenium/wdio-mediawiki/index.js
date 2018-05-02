const fs = require( 'fs' );

module.exports = {
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
