'use strict';

// Loads a REST module's OpenAPI spec for chai-openapi-response-validator, resolving the
// external schemas it references to their local copies in maintenance/external-schemas/ (no network access).
const path = require( 'path' );
const { openApi } = require( 'api-testing' );

const localRefs = {
	'https://': path.join( __dirname, '../../maintenance/external-schemas' )
};

module.exports = ( moduleName ) => openApi.useModuleSpec( moduleName, { localRefs } );
