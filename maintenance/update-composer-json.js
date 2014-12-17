#!/node
// update-composer-json.js
// This script modifies our composer.json file to update a package requirement

if ( process.argv.length !== 4 ) {
	console.error( 'Syntax: node update-composer-json.sh <package> <version>' );
	process.exit( 1 );
}

var
	composerPath = '../composer.json',
	composerJson = require( composerPath ),
	targetPackage = process.argv[2],
	targetVersion = process.argv[3],
	currentVersion = composerJson.require[targetPackage];

if ( !currentVersion ) {
	console.error( 'composer.json does not contain a dependency for the "' + targetPackage + '" package' );
	process.exit( 1 );
}

composerJson.require[targetPackage] = targetVersion;

require( 'fs' ).writeFile(
	composerPath,
	JSON.stringify(composerJson, null, '\t' ) + '\n',
	function ( error ) {
		if ( error ) {
			console.error( 'Writing failed due to error: ', error );
		}
	}
);

console.log( 'Updated composer.json dependency for "' + targetPackage + '" package from "' + currentVersion + '" to "' + targetVersion + '".' );
