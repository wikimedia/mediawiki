<?php
use MediaWiki\Tests\Storage\McrWriteBothSchemaOverride;

/**
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageMcrWriteBothDbTest extends WikiPageDbTestBase {

	use McrWriteBothSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

}
