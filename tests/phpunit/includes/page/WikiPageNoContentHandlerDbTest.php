<?php

/**
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageNoContentHandlerDbTest extends WikiPageDbTestBase {

	protected function getContentHandlerUseDB() {
		return false;
	}

	public function provideMigrations() {
		yield 'MIGRATION_OLD' => [ MIGRATION_OLD ];
	}

}
