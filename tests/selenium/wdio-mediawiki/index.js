import { mkdir } from 'fs/promises';

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
	return `${ browser.options.capabilities[ 'mw:screenshotPath' ] }/${ testTitle( title ) }-${ makeFilenameDate() }.${ extension }`;
}

/**
 * Based on <https://github.com/webdriverio/webdriverio/issues/269#issuecomment-306342170>
 *
 * @since 1.0.0
 * @param {string} title Description (will be sanitised and used as file name)
 * @return {Promise<string>} File path
 */
async function saveScreenshot( title ) {
	// Create sensible file name for current test title
	const path = filePath( title, 'png' );

	// eslint-disable-next-line security/detect-non-literal-fs-filename
	await mkdir( browser.options.capabilities[ 'mw:screenshotPath' ], { recursive: true } );
	// Create and save screenshot
	await browser.saveScreenshot( path );
	return path;
}

/**
 * @since 1.1.0
 * @param {Object} ffmpeg
 * @param {string} title Test title
 * @return {Object} ffmpeg object is returned so it could be used in stopVideo()
 */
async function startVideo( ffmpeg, title ) {
	if ( process.env.DISPLAY && process.env.DISPLAY.startsWith( ':' ) ) {
		const videoPath = filePath( title, 'mp4' );
		const { spawn } = await import( 'child_process' );
		ffmpeg = spawn( 'ffmpeg', [
			'-f', 'x11grab', //  grab the X11 display
			'-video_size', `${ browser.options.capabilities[ 'mw:width' ] }x${ browser.options.capabilities[ 'mw:height' ] }`, // video size need to match our XVFB setup
			'-framerate', '10', // Capture framerate is 10 fps
			'-i', process.env.DISPLAY, // display used for input
			'-draw_mouse', '0', // skip the mouse (do we need it?)
			'-loglevel', 'error', // log only errors
			'-y', // overwrite output files without asking
			'-an', // skip sound
			'-c:v', 'libx264', // specify encoder
			'-preset', 'ultrafast', // fastest preset, reduse CPU overhead, creates larger files
			'-crf', '42', // 23 is default, higher number makes files smaller but lower quality
			'-pix_fmt', 'yuv420p', // QuickTime Player support, "Use -pix_fmt yuv420p for compatibility with outdated media players"
			videoPath // output file
		] );
		ffmpeg.on( 'error', ( e ) => {
			console.error( `Could not start ffmpeg or could not kill it, check the error ${ e }` );
		} );
		const logBuffer = function ( buffer, prefix ) {
			const lines = buffer.toString().trim().split( '\n' );
			lines.forEach( ( line ) => {
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

export {
	makeFilenameDate,
	saveScreenshot,
	startVideo,
	stopVideo
};
