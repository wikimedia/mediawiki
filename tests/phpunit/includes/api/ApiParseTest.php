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
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'missingtitle' ),
				"Parse request for nonexistent page must give 'missingtitle' error: "
					. var_export( self::getErrorFormatter()->arrayFromStatus( $ex->getStatusValue() ), true )
			);
		}
	}
}
