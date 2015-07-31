<?php

/**
 * @group FileRepo
 * @group FileBackend
 * @group medium
 */
class SwiftFileBackendTest extends MediaWikiTestCase {
	/** @var SwiftFileBackend */
	private $backend;

	protected function setUp() {
		parent::setUp();

		$this->backend = TestingAccessWrapper::newFromObject(
			new SwiftFileBackend( array(
				'name'             => 'local-swift-testing',
				'class'            => 'SwiftFileBackend',
				'wikiId'           => 'unit-testing',
				'lockManager'      => LockManagerGroup::singleton()->get( 'fsLockManager' ),
				'swiftAuthUrl'     => 'http://127.0.0.1:8080/auth', // unused
				'swiftUser'        => 'test:tester',
				'swiftKey'         => 'testing',
				'swiftTempUrlKey'  => 'b3968d0207b54ece87cccc06515a89d4' // unused
			) )
		);
	}

	/**
	 * @dataProvider provider_testSanitzeHdrs
	 * @covers SwiftFileBackend::sanitzeHdrs
	 */
	public function testSanitzeHdrs( $raw, $sanitized ) {
		$hdrs = $this->backend->sanitizeHdrs( array( 'headers' => $raw ) );

		$this->assertEquals( $hdrs, $sanitized, 'sanitizeHdrs() has expected result' );
	}

	public static function provider_testSanitzeHdrs() {
		return array(
			array(
				array(
					'content-length' => 345,
					'content-type'   => 'image+bitmap/jpeg',
					'content-disposition' => 'inline',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				),
				array(
					'content-disposition' => 'inline',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				)
			),
			array(
				array(
					'content-length' => 345,
					'content-type'   => 'image+bitmap/jpeg',
					'content-disposition' => 'inline; filename=xxx; ' . str_repeat( 'o', 1024 ),
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				),
				array(
					'content-disposition' => 'inline;filename=xxx',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				)
			),
			array(
				array(
					'content-length' => 345,
					'content-type'   => 'image+bitmap/jpeg',
					'content-disposition' => 'filename='. str_repeat( 'o', 1024 ) . ';inline',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				),
				array(
					'content-disposition' => '',
					'content-duration' => 35.6363,
					'content-custom' => 'hello',
					'x-content-custom' => 'hello'
				)
			)
		);
	}
}