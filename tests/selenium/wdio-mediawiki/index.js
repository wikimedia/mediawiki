import { mkdir } from 'fs/promises';
import { readFileSync } from 'fs';
import os from 'node:os';
import process from 'node:process';
import { resolve } from 'path';
import { spawn } from 'child_process';

const DISPLAY_BASE = 100;

// eslint-disable-next-line security/detect-non-literal-fs-filename
export const version = JSON.parse( readFileSync( new URL( './package.json', import.meta.url ), 'utf8' ) ).version;

function getPackageVersion( packageName ) {
	try {
		// eslint-disable-next-line security/detect-non-literal-fs-filename
		return JSON.parse( readFileSync( resolve( process.cwd(), 'node_modules', packageName, 'package.json' ), 'utf8' ) ).version;
	} catch {}
	return 'unknown';
}

/**
 * @since 1.1.0
 * @return {string} File name friendly version of ISO 8601 date and time
 */
export function makeFilenameDate() {
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
export async function saveScreenshot( title ) {
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
export function startVideo( ffmpeg, title ) {
	if ( process.env.DISPLAY && process.env.DISPLAY.startsWith( ':' ) ) {
		const videoPath = filePath( title, 'mp4' );
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
export function stopVideo( ffmpeg ) {
	if ( ffmpeg ) {
		ffmpeg.kill( 'SIGINT' );
	}
}

export async function logBrowserInformation( browser ) {
	// Make sure we only log this once, maybe we can this smarter in the future
	if ( process.env.WDIO_WORKER_ID !== '0-0' ) {
		return;
	}
	const { browserName, browserVersion } = browser.capabilities;
	const viewport = await browser.execute( () => ( {
		// eslint-disable-next-line no-undef
		width: window.innerWidth,
		// eslint-disable-next-line no-undef
		height: window.innerHeight
	} ) );
	console.log( `[Browser information] ${ browserName } ${ browserVersion } viewPort ${ viewport.width }x${ viewport.height }` );
}

export function logSystemInformation() {
	const bytesPerMegabyte = 1_000_000;
	const bytesPerGigabyte = 1_000_000_000;

	const formatMegabytesAndGigabytes = ( bytes ) => {
		const megabytes = bytes / bytesPerMegabyte;
		const gigabytes = bytes / bytesPerGigabyte;

		if ( gigabytes >= 1 ) {
			return `${ megabytes.toFixed( 1 ) } MB (${ gigabytes.toFixed( 2 ) } GB)`;
		}

		return `${ megabytes.toFixed( 1 ) } MB`;
	};

	const cores =
		// eslint-disable-next-line n/no-unsupported-features/node-builtins
		typeof os.availableParallelism === 'function' ?
			// eslint-disable-next-line n/no-unsupported-features/node-builtins
			os.availableParallelism() :
			os.cpus().length;

	const freeBytes = os.freemem();
	const { rss } = process.memoryUsage();

	// eslint-disable-next-line n/no-unsupported-features/node-builtins
	const limit = typeof process.constrainedMemory === 'function' ?
		// eslint-disable-next-line n/no-unsupported-features/node-builtins
		process.constrainedMemory() :
		0;

	// If there's no limit set in the container, we got
	// 18446744073709.6 MB that seems wrong
	if ( limit > 0 && limit < 9000000000000000000 ) {
		console.log(
			`[System information] Memory limit (container): ${ formatMegabytesAndGigabytes( limit ) }`
		);
	}

	console.log( `[System information] Memory (host): ${ formatMegabytesAndGigabytes( freeBytes ) } free` );
	console.log( `[System information] RAM used by NodeJS ${ formatMegabytesAndGigabytes( rss ) }` );
	console.log( `[System information] CPU: ${ cores } cores` );
	console.log( `[Package information] WebdriverIO: ${ getPackageVersion( 'webdriverio' ) }` );
	console.log( `[Package information] wdio-mediawiki: ${ version }` );
}

/**
 * Export correct display number for this NodeJS process.
 * Chrome will use the DISPLAY that is exported.
 *
 * @param {number} instances number of NodeJS processes running jobs
 */
export function setDisplay( instances ) {
	// WDIO_WORKER_ID
	// "An unique id that helps identify the worker process.
	// It has format of {number}-{number} where the first number identifies the capability
	// and the second the spec file that capability is running, e.g. 0-5 indicates a
	// worker the first running the 6th spec file for the first capability."
	// https://webdriver.io/docs/api/environment#wdio_worker_id
	// By using the spec index and number of instances that runs simultanesly
	// and use modulus we set the correct display for just this spec file.
	const [ , index ] = ( process.env.WDIO_WORKER_ID ).split( '-' );
	const slot = Number( index ) % instances;
	const display = `:${ DISPLAY_BASE + slot }`;

	process.env.DISPLAY = display;
	console.log( `Worker ${ process.env.WDIO_WORKER_ID } DISPLAY ${ display }` );
}

/**
 * Start multipe Xvfb depending on number of simultanesly running
 * NodeJS instances.
 *
 * @param {number} instances The number of parallel Xvfb servers to launch.
 * @param {number} width The screen width
 * @param {number} height The screen height
 * @return {object[]} An array of Xvfb child processes.
 */
export function startXvfb( instances, width, height ) {
	const processes = [];
	for ( let i = 0; i < instances; i++ ) {
		const display = `:${ DISPLAY_BASE + i }`;
		// https://gerrit.wikimedia.org/g/integration/quibble/+/0055a00b78a31125de2b0a0e93401d8e26e69043/quibble/backend.py#509
		const xvfb = spawn(
			'Xvfb',
			[ display, '-screen', '0', `${ width }x${ height }x24`,
				'-nolisten', 'tcp', '-nolisten', 'unix' ],
			{ stdio: 'ignore' }
		);
		processes.push( xvfb );
		console.log( `Started Xvfb on display ${ display }` );
	}

	return processes;
}

/**
 * Stop Xvfb processes.
 *
 * @param {object[]} processes An array with Xvfb processed to stopped.
 */
export function stopXvfb( processes ) {
	for ( const xvfbProcess of processes ) {
		xvfbProcess.kill( 'SIGTERM' );
	}

}
