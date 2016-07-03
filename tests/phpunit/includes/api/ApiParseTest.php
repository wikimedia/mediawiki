<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiParse
 */
class ApiParseTest extends ApiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	public function testParseNonexistentPage() {
		$somePage = mt_rand();

		try {
			$this->doApiRequest( [
				'action' => 'parse',
				'page' => $somePage ] );

			$this->fail( "API did not return an error when parsing a nonexistent page" );
		} catch ( UsageException $ex ) {
			$this->assertEquals(
				'missingtitle',
				$ex->getCodeString(),
				"Parse request for nonexistent page must give 'missingtitle' error: "
					. var_export( $ex->getMessageArray(), true )
			);
		}
	}
}
