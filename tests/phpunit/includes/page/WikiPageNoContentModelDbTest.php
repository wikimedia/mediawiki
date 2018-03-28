<?php
use MediaWiki\Tests\Storage\PreMcrSchemaOverride;

/**
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageNoContentModelDbTest extends WikiPageDbTestBase {

	use PreMcrSchemaOverride;

	protected function getContentHandlerUseDB() {
		return false;
	}

}
