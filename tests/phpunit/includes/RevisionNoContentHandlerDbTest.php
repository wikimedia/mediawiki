<?php

/**
 * @group Database
 * @group medium
 * @group ContentHandler
 */
class RevisionNoContentHandlerDbTest extends RevisionDbTestBase {

	protected function getContentHandlerUseDB() {
		return false;
	}

}
