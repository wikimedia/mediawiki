<?php

/**
 * Convert a JSON abstract schema change to a schema change file in the given DBMS type
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\SchemaGenerator;
use MediaWiki\Maintenance\SchemaMaintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/includes/SchemaMaintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to generate schema from abstract json files.
 *
 * @ingroup Maintenance
 */
class GenerateSchemaChangeSql extends SchemaMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build SQL files for schema changes from abstract JSON files' );
	}

	protected function generateSchema( string $platform, string $jsonPath ): string {
		return ( new SchemaGenerator() )->generateSchemaChange( $platform, $jsonPath );
	}

}

// @codeCoverageIgnoreStart
$maintClass = GenerateSchemaChangeSql::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
