#!/usr/bin/env node
/*
 * JQuery Migrate Plugin Release Management
 */

// Debugging variables
var	dryrun = false,
	skipRemote = false;

var fs = require( "fs" ),
	child = require( "child_process" ),
	path = require( "path" ),
	chalk = require( "chalk" );

var releaseVersion,
	nextVersion,
	finalFiles,
	isBeta,
	pkg,

	repoURL = "git@github.com:jquery/jquery-migrate.git",
	branch = "master",

	// Windows needs the .cmd version but will find the non-.cmd
	// On Windows, also ensure the HOME environment variable is set
	gruntCmd = process.platform === "win32" ? "grunt.cmd" : "grunt",
	npmCmd = process.platform === "win32" ? "npm.cmd" : "npm",

	readmeFile = "README.md",
	packageFile = "package.json",
	versionFile = "src/version.js",
	devFile = "dist/jquery-migrate.js",
	minFile = "dist/jquery-migrate.min.js",

	releaseFiles = {
		"CDN/jquery-migrate-VER.js": devFile,
		"CDN/jquery-migrate-VER.min.js": minFile
	};

steps(
	initialize,
	checkGitStatus,
	gruntBuild,
	updateVersions,
	tagReleaseVersion,
	gruntBuild,
	makeReleaseCopies,
	publishToNPM,
	setNextVersion,
	pushToRemote,
	remindAboutCDN,
	remindAboutSites,
	exit
);

function initialize( next ) {

	// -d dryrun mode, no commands are executed at all
	if ( process.argv[ 2 ] === "-d" ) {
		process.argv.shift();
		dryrun = true;
		console.warn( "=== DRY RUN MODE ===" );
	}

	// -r skip remote mode, no remote commands are executed
	// (git push, npm publish, cdn copy)
	// Reset with `git reset --hard HEAD~2 && git tag -d (version) && grunt`
	if ( process.argv[ 2 ] === "-r" ) {
		process.argv.shift();
		skipRemote = true;
		console.warn( "=== SKIPREMOTE MODE ===" );
	}

	// First arg should be the version number being released; this is a proper subset
	// of a full semver, see https://github.com/mojombo/semver/issues/32
	// Examples: 1.0.1, 1.0.1-pre, 1.0.1-rc1, 1.0.1-rc1.1
	var newver, oldver,
		rsemver = /^(\d+)\.(\d+)\.(\d+)(?:-([\dA-Za-z\-]+(?:\.[\dA-Za-z\-]+)*))?$/,
		version = rsemver.exec( process.argv[ 2 ] || "" ) || [],
		major = version[ 1 ],
		minor = version[ 2 ],
		patch = version[ 3 ],
		xbeta = version[ 4 ];

	releaseVersion = process.argv[ 2 ];
	isBeta = !!xbeta;

	if ( !releaseVersion ) {
		log( "Usage: release [ -d -r ] releaseVersion" );
		log( "       -d  Dry-run; no commands are executed at all" );
		log( "       -r  Skip-remote; nothing is pushed externally" );
		die( "Invalid args" );
	}
	if ( !version.length ) {
		die( "'" + releaseVersion + "' is not a valid semver!" );
	}
	if ( xbeta === "pre" ) {
		die( "Cannot release a 'pre' version!" );
	}
	if ( !( fs.existsSync || path.existsSync )( packageFile ) ) {
		die( "No " + packageFile + " in this directory" );
	}
	pkg = JSON.parse( fs.readFileSync( packageFile ) );

	status( "Current version is " + pkg.version + "; generating release " + releaseVersion );
	version = rsemver.exec( pkg.version );
	oldver = ( +version[ 1 ] ) * 10000 + ( +version[ 2 ] * 100 ) + ( +version[ 3 ] );
	newver = ( +major ) * 10000 + ( +minor * 100 ) + ( +patch );
	if ( newver < oldver ) {
		die( "Next version is older than current version!" );
	}

	nextVersion = major + "." + minor + "." + ( isBeta ? patch : +patch + 1 ) + "-pre";
	next();
}

//TODO: Check that remote doesn't have newer commits:
// git fetch repoURL
// git remote show repoURL
// (look for " BRANCH     pushes to BRANCH     (up to date)")

function checkGitStatus( next ) {
	child.execFile( "git", [ "status" ], function( error, stdout ) {
		var onBranch = ( ( stdout || "" ).match( /On branch (\S+)/ ) || [] )[ 1 ];
		if ( onBranch !== branch ) {
			die( "Branches don't match: Wanted " + branch + ", got " + onBranch );
		}
		if ( /Changes to be committed/i.test( stdout ) ) {
			die( "Please commit changed files before attemping to push a release." );
		}
		if ( /Changes not staged for commit/i.test( stdout ) ) {
			die( "Please stash files before attempting to push a release." );
		}
		next();
	} );
}

function tagReleaseVersion( next ) {
	git( [ "commit", "-a", "--no-verify", "-m", "Tagging the " + releaseVersion + " release." ],
		function() {
			git( [ "tag", releaseVersion ], next );
		}
	);
}

function updateVersions( next ) {
	updateSourceVersion( releaseVersion );
	updateReadmeVersion( releaseVersion );
	updatePackageVersion( releaseVersion );
	next();
}

function gruntBuild( next ) {
	exec( gruntCmd, [], function( error, stdout, stderr ) {
		if ( error ) {
			die( error + stderr );
		}
		log( stdout || "(no output)" );
		next();
	} );
}

function makeReleaseCopies( next ) {
	finalFiles = {};
	Object.keys( releaseFiles ).forEach( function( key ) {
		var builtFile = releaseFiles[ key ],
			releaseFile = key.replace( /VER/g, releaseVersion );

		copy( builtFile, releaseFile );
		finalFiles[ releaseFile ] = builtFile;
	} );
	next();
}

function publishToNPM( next ) {

	// Don't update "latest" if this is a beta
	if ( isBeta ) {
		exec( npmCmd, [ "publish", "--tag", "beta" ], next, skipRemote );
	} else {
		exec( npmCmd, [ "publish" ], next, skipRemote );
	}
}

function setNextVersion( next ) {
	updateSourceVersion( nextVersion );
	updatePackageVersion( nextVersion, "master" );
	git( [ "commit", "-a", "--no-verify", "-m", "Updating the source version to " + nextVersion ],
		next );
}

function pushToRemote( next ) {
	git( [ "push", "--tags", repoURL, branch ], next, skipRemote );
}

function remindAboutCDN( next ) {
	console.log( chalk.red( "TODO: Update CDN with jquery-migrate." +
		releaseVersion + " files (min and regular)" ) );
	console.log( chalk.red( "  clone codeorigin.jquery.com, git add files, commit, push" ) );
	next();
}

function remindAboutSites( next ) {
	console.log( chalk.red( "TODO: Update jquery.com download page to " + releaseVersion ) );
	next();
}

//==============================

function steps() {
	var cur = 0,
		steps = arguments;
	( function next() {
		process.nextTick( function() {
			steps[ cur++ ]( next );
		} );
	} )();
}

function updatePackageVersion( ver, blobVer ) {
	status( "Updating " + packageFile + " version to " + ver );
	blobVer = blobVer || ver;
	pkg.version = ver;
	pkg.author.url = setBlobVersion( pkg.author.url, blobVer );
	writeJsonSync( packageFile, pkg );
}

function updateSourceVersion( ver ) {
	var stmt = "\njQuery.migrateVersion = \"" + ver + "\";\n";

	status( "Updating " + stmt.replace( /\n/g, "" ) );
	if ( !dryrun ) {
		fs.writeFileSync( versionFile, stmt );
	}
}

function updateReadmeVersion() {
	var readme = fs.readFileSync( readmeFile, "utf8" );

	// Change version references from the old version to the new one.
	// The regex can update beta versions in case it was changed manually.
	if ( isBeta ) {
		status( "Skipping " + readmeFile + " update (beta release)" );
	} else {
		status( "Updating " + readmeFile );
		readme = readme.replace(
			/jquery-migrate-\d+\.\d+\.\d+(?:-\w+)?/g,
			"jquery-migrate-" + releaseVersion
		);
		if ( !dryrun ) {
			fs.writeFileSync( readmeFile, readme );
		}
	}
}

function setBlobVersion( s, v ) {
	return s.replace( /\/blob\/(?:(\d+\.\d+[^\/]+)|master)/, "/blob/" + v );
}

function writeJsonSync( fname, json ) {
	if ( dryrun ) {
		console.log( JSON.stringify( json, null, "  " ) );
	} else {
		fs.writeFileSync( fname, JSON.stringify( json, null, "\t" ) + "\n" );
	}
}

function copy( oldFile, newFile ) {
	status( "Copying " + oldFile + " to " + newFile );
	if ( !dryrun ) {
		fs.writeFileSync( newFile, fs.readFileSync( oldFile, "utf8" ) );
	}
}

function git( args, fn, skip ) {
	exec( "git", args, fn, skip );
}

function exec( cmd, args, fn, skip ) {
	if ( dryrun || skip ) {
		log( chalk.black.bgBlue( "# " + cmd + " " + args.join( " " ) ) );
		fn();
	} else {
		log( chalk.green( cmd + " " + args.join( " " ) ) );
		child.execFile( cmd, args, { env: process.env },
			function( err, stdout, stderr ) {
				if ( err ) {
					die( stderr || stdout || err );
				}
				fn();
			}
		);
	}
}

function status( msg ) {
	console.log( chalk.black.bgGreen( msg ) );
}

function log( msg ) {
	console.log( msg );
}

function die( msg ) {
	console.error( chalk.red( "ERROR: " + msg ) );
	process.exit( 1 );
}

function exit() {
	process.exit( 0 );
}
