<?php

/**
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageContentHandlerDbTest extends WikiPageDbTestBase {

	protected function getContentHandlerUseDB() {
		return true;
	}

}
