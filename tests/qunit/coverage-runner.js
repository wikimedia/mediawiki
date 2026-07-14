'use strict';

/**
 * Browser code-coverage runner for the QUnit test suite.
 *
 * QUnit tests are run via Special:JavaScriptTest served by ResourceLoader.
 * Because Karma does not serve those files itself the usual karma-coverage
 * instrumenting preprocessor. Instead we:
 *
 * - Drives Special:JavaScriptTest in headless Chrome via the DevTools protocol.
 * - Captures the browser's native V8 coverage (no source instrumentation).
 * - Uses ResourceLoader's `SourceMap:` HTTP response header to map the coverage
 *   back to the original source files.
 * - Feeds V8 coverage + maps to monocart-coverage-reports, which flattens the
 *   sectioned index maps that batched load.php responses produce and writes
 *   lcov, HTML and Cobertura reports to docs/coverage/.
 *
 * Usage:
 *   MW_SERVER=http://localhost MW_SCRIPT_PATH=/w \
 *     node tests/qunit/coverage-runner.js [--component=Name]
 *
 * Environment variables:
 *   MW_SERVER                 e.g. "http://localhost" (required)
 *   MW_SCRIPT_PATH            e.g. "/w" ("" is valid for docroot installs) (required)
 *   PUPPETEER_EXECUTABLE_PATH / CHROME_BIN   path to the Chrome/Chromium binary
 *   CHROMIUM_FLAGS            extra Chrome flags (WMF CI sets e.g. "--no-sandbox")
 *   MW_QUNIT_TIMEOUT          overall timeout in ms (default 300_000)
 *
 * @file
 */

const fs = require( 'fs' );
const path = require( 'path' );
const ignore = require( 'ignore' );
const puppeteer = require( 'puppeteer-core' );
const { CoverageReport } = require( 'monocart-coverage-reports' );

const rootDir = path.resolve( __dirname, '..', '..' );

// Configuration
const server = process.env.MW_SERVER;
const scriptPath = process.env.MW_SCRIPT_PATH;
const timeout = Number( process.env.MW_QUNIT_TIMEOUT ) || 300_000;
const outputDir = path.join( rootDir, 'docs', 'coverage' );

const docRoot = ( scriptPath && rootDir.endsWith( scriptPath ) ) ?
	rootDir.slice( 0, rootDir.length - scriptPath.length ) :
	rootDir;

function getArg( name ) {
	const prefixes = [ '--' + name + '=' ];
	for ( const arg of process.argv.slice( 2 ) ) {
		for ( const prefix of prefixes ) {
			if ( arg.startsWith( prefix ) ) {
				return arg.slice( prefix.length );
			}
		}
	}
	return null;
}

// Same knob as the Gruntfile's `--qunit-component`: "MediaWiki" for core, or an
// extension/skin name. When omitted, all registered test suite modules run.
const component = getArg( 'component' ) || getArg( 'qunit-component' ) ||
	process.env.MW_QUNIT_COMPONENT || null;

// Source path handling

// ResourceLoader source-map `sources` are server-absolute URL paths, e.g.
// "/w/resources/src/mediawiki.base/mediawiki.base.js" or
// "/w/virtual-resource/<module>-<type>-<n>.js". Turn them into repo-relative
// paths ("resources/src/...", "extensions/Foo/resources/...") for the reports.
// monocart strips the leading slash before invoking this, so compare against a
// slash-stripped copy of the script path too.
const scriptPathPrefix = ( scriptPath || '' ).replace( /^\/+/, '' ).replace( /\/+$/, '' );
function normalizeSourcePath( filePath ) {
	let p = filePath;
	if ( /^https?:\/\//.test( p ) ) {
		try {
			p = new URL( p ).pathname;
		} catch ( e ) {
			// leave as-is
		}
	}
	p = p.replace( /^\/+/, '' );
	// Core files sit under the script path; strip it for a repo-relative path.
	if ( scriptPathPrefix && p.startsWith( scriptPathPrefix + '/' ) ) {
		return p.slice( scriptPathPrefix.length + 1 );
	}
	// Extensions and skins are served from $wgExtensionAssetsPath, a different
	// prefix; normalize to start at the component root ("extensions/Foo/...").
	const m = p.match( /(?:^|\/)((?:extensions|skins)\/.+)$/ );
	if ( m ) {
		return m[ 1 ];
	}
	return p;
}

// A source is reportable if it is a JavaScript file that isn't excluded. We use
// a denylist rather than an allowlist because modules are served from many
// locations — resources/src, extensions/*/modules, or even flat paths
// (e.g. and mediawiki.language grammar files map to bare <lang>.js) and an allowlist
// silently drops the latter kinds.
//
// Provide some default exclusions for any component without its own .eslintignore.
const EXCLUDE_RE = /(^|\/)(node_modules|lib|dist|vendor|tests?)\/|(^|\/)virtual-resource\/|testdata\.js$|\.(test|spec)\.js$/;

// Cache of fs.existsSync results (the same paths are checked repeatedly).
const existsCache = new Map();
function existsCached( p ) {
	if ( !existsCache.has( p ) ) {
		let ok = false;
		try {
			// eslint-disable-next-line security/detect-non-literal-fs-filename
			ok = fs.existsSync( p );
		} catch ( e ) {
			ok = false;
		}
		existsCache.set( p, ok );
	}
	return existsCache.get( p );
}

function urlPathname( u ) {
	if ( /^https?:\/\//.test( u ) ) {
		try {
			return new URL( u ).pathname;
		} catch ( e ) {
			return u;
		}
	}
	return u;
}

// Lazily index .js files by basename under a directory subtree, so a file served
// from a flattened path (e.g. remoteExtPath + bare filenames) can be traced back
// to its real location. Directories that never hold ResourceLoader-served source
// are skipped, both to stay fast on large trees and to avoid basename clashes
// with vendored or test copies (e.g. a language file also under resources/lib).
const SKIP_DIRS = new Set( [
	'node_modules', '.git', 'vendor', 'dist', 'docs', 'cache', 'images',
	'lib', 'tests', 'test', 'includes', 'maintenance', 'mw-config', 'i18n'
] );
const basenameIndexCache = new Map();
function basenameIndex( rootAbs ) {
	if ( !basenameIndexCache.has( rootAbs ) ) {
		const index = new Map();
		const stack = [ rootAbs ];
		while ( stack.length ) {
			const dir = stack.pop();
			let entries;
			try {
				// eslint-disable-next-line security/detect-non-literal-fs-filename
				entries = fs.readdirSync( dir, { withFileTypes: true } );
			} catch ( e ) {
				continue;
			}
			for ( const entry of entries ) {
				if ( entry.isDirectory() ) {
					if ( !SKIP_DIRS.has( entry.name ) ) {
						stack.push( path.join( dir, entry.name ) );
					}
				} else if ( entry.isFile() && entry.name.endsWith( '.js' ) ) {
					const list = index.get( entry.name );
					if ( list ) {
						list.push( path.join( dir, entry.name ) );
					} else {
						index.set( entry.name, [ path.join( dir, entry.name ) ] );
					}
				}
			}
		}
		basenameIndexCache.set( rootAbs, index );
	}
	return basenameIndexCache.get( rootAbs );
}

// The subtree to search for a flattened file: the extension or skin it belongs
// to (so we don't scan the whole tree), else the core root.
function componentRoot( fsFile ) {
	const rel = path.relative( docRoot, fsFile );
	const m = rel.match( /^(.*?(?:extensions|skins)\/[^/]+)\// );
	return m ? path.join( docRoot, m[ 1 ] ) : rootDir;
}

// Trace a served file back to its real location on disk. Files served from a
// flattened path don't exist where the URL says; find the sole file with the
// same basename in the component. Returns an absolute path, or null when the
// served path is already correct or the match isn't unique.
function originalFsFile( servedFsFile ) {
	if ( existsCached( servedFsFile ) ) {
		return null;
	}
	const candidates = basenameIndex( componentRoot( servedFsFile ) )
		.get( path.basename( servedFsFile ) );
	return candidates && candidates.length === 1 ? candidates[ 0 ] : null;
}

// Resolve a source to its final report path and disk file, applying the
// flattened-path remap. Accepts either a raw served URL path (e.g. from
// mapHasReportableSource, before monocart runs) or a report path already
// produced here (monocart's sourceFilter calls, whose disk file we recorded).
const fsFileByReportPath = new Map();
function resolveSource( sourcePath ) {
	const normalized = normalizeSourcePath( sourcePath );
	if ( fsFileByReportPath.has( normalized ) ) {
		return { reportPath: normalized, fsFile: fsFileByReportPath.get( normalized ) };
	}
	let fsFile = ( /^https?:\/\//.test( sourcePath ) || sourcePath.startsWith( '/' ) ) ?
		path.join( docRoot, urlPathname( sourcePath ) ) :
		null;
	let reportPath = normalized;
	if ( fsFile ) {
		const original = originalFsFile( fsFile );
		if ( original ) {
			fsFile = original;
			reportPath = normalizeSourcePath( path.relative( docRoot, original ) );
		}
	}
	return { reportPath, fsFile };
}

// monocart's sourcePath option: report each source at its real location, and
// remember the disk file it maps to so filtering can resolve it later.
function toReportPath( filePath, meta ) {
	const url = ( meta && typeof meta.url === 'string' ) ? meta.url : null;
	const { reportPath, fsFile } = resolveSource( url || filePath );
	if ( fsFile ) {
		fsFileByReportPath.set( reportPath, fsFile );
	}
	return reportPath;
}

// .eslintignore matcher for a single directory (cached; null when none there).
const dirIgnoreCache = new Map();
function dirIgnoreMatcher( dir ) {
	if ( !dirIgnoreCache.has( dir ) ) {
		let matcher = null;
		try {
			// eslint-disable-next-line security/detect-non-literal-fs-filename
			matcher = ignore().add( fs.readFileSync( path.join( dir, '.eslintignore' ), 'utf8' ) );
		} catch ( e ) {
			// no .eslintignore in this directory
		}
		dirIgnoreCache.set( dir, matcher );
	}
	return dirIgnoreCache.get( dir );
}

// Every .eslintignore applies to its whole subtree (gitignore semantics). Walk
// from the file up to its component boundary, applying each .eslintignore found,
// matched against the path relative to its own directory. The walk stops at the
// component root (extensions/<X>, skins/<X>, else the core root) so a parent
// project's ignore never governs a nested one. This matters because skins are
// served under the core script path and so live inside the core tree: without
// the boundary, core's .eslintignore — which lists /skins/ and /extensions/ to
// exclude them from core's own lint — would wrongly ignore every skin file.
function isEslintIgnored( fsFile ) {
	const boundary = componentRoot( fsFile );
	let dir = path.dirname( fsFile );
	while ( true ) {
		const matcher = dirIgnoreMatcher( dir );
		if ( matcher && matcher.ignores( path.relative( dir, fsFile ) ) ) {
			return true;
		}
		const parent = path.dirname( dir );
		if ( dir === boundary || parent === dir ) {
			break;
		}
		dir = parent;
	}
	return false;
}

function isReportableSource( sourcePath ) {
	const { reportPath, fsFile } = resolveSource( sourcePath );
	if ( !/\.js$/.test( reportPath ) || EXCLUDE_RE.test( reportPath ) ) {
		return false;
	}
	// Files served with no remote base path that we couldn't trace to disk land
	// at the resource root (no directory). They are flattened test/data artifacts,
	// not locatable product source, so drop them. Real flattened product — e.g.
	// the mediawiki.language grammar files — is remapped to a real path above and
	// so keeps a directory.
	if ( !reportPath.includes( '/' ) ) {
		return false;
	}
	return !fsFile || !isEslintIgnored( fsFile );
}

// Whether a (flat or sectioned) source map contains at least one source we would
// report on. Used to drop styles-only or data-only load.php bundles, which have
// no mappable JavaScript and would otherwise appear as a bogus "load.php-..." file.
function mapHasReportableSource( map ) {
	let sources = [];
	if ( Array.isArray( map.sections ) ) {
		for ( const section of map.sections ) {
			if ( section.map && Array.isArray( section.map.sources ) ) {
				sources = sources.concat( section.map.sources );
			}
		}
	} else if ( Array.isArray( map.sources ) ) {
		sources = map.sources;
	}
	return sources.some( ( src ) => isReportableSource( src ) );
}

// Main

async function main() {
	// Match the Gruntfile's `assert-mw-env`
	if ( !server || scriptPath === undefined ) {
		throw new Error(
			'Environment variables MW_SERVER and MW_SCRIPT_PATH must be set.\n' +
			'Set these like $wgServer and $wgScriptPath, e.g. MW_SERVER="http://localhost" ' +
			'MW_SCRIPT_PATH="/w".'
		);
	}

	const hostUrl = server + scriptPath +
		'/index.php?title=Special:JavaScriptTest/qunit&debug=0' +
		( component ? '&component=' + encodeURIComponent( component ) : '' );

	process.stdout.write( 'Coverage runner\n' );
	process.stdout.write( '  Test page: ' + hostUrl + '\n' );
	process.stdout.write( '  Output:    ' + outputDir + '\n\n' );

	const launchArgs = process.env.CHROMIUM_FLAGS ?
		process.env.CHROMIUM_FLAGS.split( ' ' ).filter( Boolean ) : [];

	// puppeteer-core ships no browser of its own. Point it at an explicit binary
	// if one is configured (e.g. WMF CI), otherwise let it locate an installed
	// Chrome release via the "chrome" channel — no bundled download either way.
	const executablePath = process.env.PUPPETEER_EXECUTABLE_PATH || process.env.CHROME_BIN;
	const browser = await puppeteer.launch( {
		headless: true,
		args: launchArgs,
		...( executablePath ? { executablePath } : { channel: 'chrome' } )
	} );

	let result = null;
	let coverage = [];
	// scriptUrl (full href) -> absolute source-map URL, harvested from response headers.
	const sourceMapHeaders = new Map();
	// scriptUrl (full href) -> parsed source map.
	const maps = new Map();

	try {
		const page = await browser.newPage();
		// Special:JavaScriptTest relies on inline scripts; make sure a strict CSP
		// on the wiki can't break the harness, and force fresh network fetches so
		// every load.php response (and its SourceMap header) is observed.
		await page.setBypassCSP( true );
		await page.setCacheEnabled( false );

		page.on( 'response', ( res ) => {
			try {
				const sm = res.headers().sourcemap;
				if ( sm ) {
					sourceMapHeaders.set( res.url(), new URL( sm, res.url() ).href );
				}
			} catch ( e ) {
				// ignore malformed responses
			}
		} );
		page.on( 'pageerror', ( err ) => {
			process.stderr.write( 'Page error: ' + err.message + '\n' );
		} );

		// Resolve when QUnit finishes. Installed before any page script so it can
		// attach QUnit.on('runEnd') the moment qunit.js defines QUnit.
		await page.evaluateOnNewDocument( () => {
			window.mwCoverageDone = false;
			window.mwCoverageResult = null;
			const attach = () => {
				if ( window.QUnit && typeof window.QUnit.on === 'function' ) {
					window.QUnit.on( 'runEnd', ( data ) => {
						window.mwCoverageResult = {
							status: data.status,
							testCounts: data.testCounts,
							runtime: data.runtime
						};
						window.mwCoverageDone = true;
					} );
					return true;
				}
				return false;
			};
			if ( !attach() ) {
				const interval = setInterval( () => {
					if ( attach() ) {
						clearInterval( interval );
					}
				}, 25 );
			}
		} );

		await page.coverage.startJSCoverage( {
			resetOnNavigation: false,
			includeRawScriptCoverage: true
		} );

		await page.goto( hostUrl, { waitUntil: 'domcontentloaded', timeout } );

		process.stdout.write( 'Running QUnit tests...\n' );
		await page.waitForFunction(
			'window.mwCoverageDone === true',
			{ timeout, polling: 200 }
		);
		result = await page.evaluate( () => window.mwCoverageResult );

		coverage = await page.coverage.stopJSCoverage();

		// Fetch the advertised source maps from within the page, while the browser
		// is still open: fetch is native and same-origin there, so this avoids a
		// Node HTTP dependency. ResourceLoader serves the maps as raw JSON.
		// The callback body runs in the browser, so the fetch here is the browser's,
		// not Node's, but eslint can't tell them apart.
		/* eslint-disable n/no-unsupported-features/node-builtins */
		const mapPairs = await page.evaluate( async ( entries ) => {
			const out = [];
			for ( const [ scriptUrl, mapUrl ] of entries ) {
				try {
					let res = await fetch( mapUrl );
					if ( !res.ok ) {
						// Retry without the &version pin in case it went stale.
						res = await fetch( mapUrl.replace( /([&?])version=[^&]*/, '$1version=' ) );
					}
					if ( res.ok ) {
						out.push( [ scriptUrl, await res.text() ] );
					}
				} catch ( e ) {
					// skip maps that fail to load
				}
			}
			return out;
		}, Array.from( sourceMapHeaders.entries() ) );
		/* eslint-enable n/no-unsupported-features/node-builtins */

		for ( const [ scriptUrl, text ] of mapPairs ) {
			try {
				// getRawSourceMap() has no XSSI guard, but strip one defensively.
				maps.set( scriptUrl, JSON.parse( text.replace( /^\)\]\}'?\n?/, '' ) ) );
			} catch ( e ) {
				// skip unparseable maps
			}
		}
	} finally {
		await browser.close();
	}

	if ( result ) {
		const c = result.testCounts || {};
		process.stdout.write(
			'\nQUnit ' + result.status + ': ' +
			( c.total || 0 ) + ' tests, ' +
			( c.passed || 0 ) + ' passed, ' +
			( c.failed || 0 ) + ' failed, ' +
			( c.skipped || 0 ) + ' skipped' +
			' (' + ( result.runtime || 0 ) + 'ms)\n\n'
		);
	} else {
		process.stderr.write( '\nQUnit did not report completion.\n' );
	}

	// Keep only the load.php ResourceLoader bundles
	const v8list = [];
	for ( const entry of coverage ) {
		if ( !entry.url || !entry.url.includes( 'load.php' ) ) {
			continue;
		}
		const raw = entry.rawScriptCoverage;
		if ( !raw || !raw.functions ) {
			continue;
		}
		// Drop bundles we can't attribute to real source: unmapped ones, and
		// styles-only or data-only modules whose map has no reportable source.
		const map = maps.get( entry.url );
		if ( !map || !mapHasReportableSource( map ) ) {
			continue;
		}
		// Embed the map as an inline base64 sourceMappingURL comment rather than
		// attaching it as `entry.sourceMap`: monocart only flattens the sectioned
		// index maps that batched load.php responses produce when it discovers the
		// map this way. Appending at end-of-file leaves the V8 byte offsets (which
		// index into the original text) untouched.
		const encoded = Buffer.from( JSON.stringify( map ) ).toString( 'base64' );
		const source = entry.text +
			'\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,' + encoded + '\n';
		v8list.push( {
			url: entry.url,
			source: source,
			functions: raw.functions,
			scriptId: raw.scriptId
		} );
	}

	process.stdout.write(
		'Collected ' + v8list.length + ' reportable load.php bundle(s).\n'
	);

	if ( !v8list.length ) {
		throw new Error(
			'No coverage data collected. Is $wgResourceLoaderEnableSourceMapLinks ' +
			'enabled and the test suite loading?'
		);
	}

	const report = new CoverageReport( {
		name: 'MediaWiki QUnit JS coverage',
		outputDir: outputDir,
		reports: [ 'console-summary', 'v8', 'lcovonly', 'cobertura' ],
		entryFilter: ( entry ) => entry.url && entry.url.includes( 'load.php' ),
		sourceFilter: ( sourcePath ) => isReportableSource( sourcePath ),
		sourcePath: toReportPath
	} );

	await report.add( v8list );
	await report.generate();

	process.stdout.write( '\nReports written to ' + outputDir + '\n' );
	process.stdout.write( '  HTML:      ' + path.join( outputDir, 'index.html' ) + '\n' );
	process.stdout.write( '  lcov:      ' + path.join( outputDir, 'lcov.info' ) + '\n' );
	process.stdout.write( '  Cobertura: ' + path.join( outputDir, 'cobertura-coverage.xml' ) + '\n' );

	// Reflect test failures in the exit code so CI notices, but only after the
	// coverage report has been written.
	if ( !result || result.status !== 'passed' ||
		( result.testCounts && result.testCounts.failed > 0 ) ) {
		process.exitCode = 1;
	}
}

main().catch( ( err ) => {
	process.stderr.write( '\nCoverage run failed: ' + ( ( err && err.stack ) || err ) + '\n' );
	process.exitCode = 1;
} );
