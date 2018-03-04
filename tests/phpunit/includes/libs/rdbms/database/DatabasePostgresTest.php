<?php

class DatabasePostgresTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers \Wikimedia\Rdbms\DatabasePostgres::buildIntegerCast
	 */
	public function testBuildIntegerCast() {
		$db = new FakeDatabasePostgres( [] );
		$output = $db->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'fieldName::int', $output );
	}

}
