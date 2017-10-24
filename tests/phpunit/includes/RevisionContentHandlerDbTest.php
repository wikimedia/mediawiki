<?php

/**
 * @group Database
 * @group medium
 * @group ContentHandler
 */
class RevisionContentHandlerDbTest extends RevisionDbTestBase {

	protected function getContentHandlerUseDB() {
		return true;
	}

}
