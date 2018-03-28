<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 10.04.18
 * Time: 20:48
 */

namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IDatabase;

trait McrSchemaDetection {

	protected function hasMcrTables( IDatabase $db ) {
		return $db->tableExists( 'slots', __METHOD__ );
	}

	protected function hasPreMcrFields( IDatabase $db ) {
		return $db->fieldExists( 'revision', 'rev_content_model', __METHOD__ );
	}

}
