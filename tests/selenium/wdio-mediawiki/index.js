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
 * @since 1.1.0
 * @param {string} title Test title
 * @return {string} File name friendly version of the test title
 */
function testTitle( title ) {
	return encodeURIComponent( title.replace( /\s+/g, '-' ) );
}

/**
 * @since 1.1.0
 * @param {string} title Test title
 * @param {string} extension png for screenshots, mp4 for videos
 * @return {string} Full path of screenshot/video file
 */
function filePath( title, extension ) {
	return `${browser.config.screenshotPath}/${testTitle( title )}-${makeFilenameDate()}.${extension}`;
}

/**
 * Based on <https://github.com/webdriverio/webdriverio/issues/269#issuecomment-306342170>
 *
 * @since 1.0.0
 * @param {string} title Description (will be sanitised and used as file name)
 * @return {string} File path
 */
function saveScreenshot( title ) {
	// Create sensible file name for current test title
	const path = filePath( title, 'png' );
	// Ensure directory exists, based on WebDriverIO#saveScreenshotSync()
	try {
		fs.statSync( browser.config.screenshotPath );
	} catch ( err ) {
		fs.mkdirSync( browser.config.screenshotPath );
	}
	// Create and save screenshot
	browser.saveScreenshot( path );
	return path;
}

/**
 * @since 1.1.0
 * @param {Object} ffmpeg
 * @param {string} title Test title
 * @return {Object} ffmpeg object is returned so it could be used in stopVideo()
 */
function startVideo( ffmpeg, title ) {
	if ( process.env.DISPLAY && process.env.DISPLAY.startsWith( ':' ) ) {
		const videoPath = filePath( title, 'mp4' );
		const { spawn } = require( 'child_process' );
		ffmpeg = spawn( 'ffmpeg', [
			'-f', 'x11grab', //  grab the X11 display
			'-video_size', '1920x1080', // video size
			'-i', process.env.DISPLAY, // input file url
			'-loglevel', 'error', // log only errors
			'-y', // overwrite output files without asking
			'-pix_fmt', 'yuv420p', // QuickTime Player support, "Use -pix_fmt yuv420p for compatibility with outdated media players"
			videoPath // output file
		] );
		const logBuffer = function ( buffer, prefix ) {
			const lines = buffer.toString().trim().split( '\n' );
			lines.forEach( function ( line ) {
				console.log( prefix + line );
			} );
		};
		ffmpeg.stdout.on( 'data', ( data ) => {
			logBuffer( data, 'ffmpeg stdout: ' );
		} );
		ffmpeg.stderr.on( 'data', ( data ) => {
			logBuffer( data, 'ffmpeg stderr: ' );
		} );
	}
	return ffmpeg;
}

/**
 * @since 1.1.0
 * @param {Object} ffmpeg
 */
function stopVideo( ffmpeg ) {
	if ( ffmpeg ) {
		ffmpeg.kill( 'SIGINT' );
	}
}

module.exports = {
	makeFilenameDate,
	saveScreenshot,
	startVideo,
	stopVideo
};
