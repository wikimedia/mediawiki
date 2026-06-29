<?php

/**
 * @covers \LogEntryBase
 */
class LogEntryBaseTest extends MediaWikiIntegrationTestCase {

	public function testExtractParams() {
		$this->assertFalse( LogEntryBase::extractParams( serialize( 'asdf' ) ) );
		$this->assertEquals( [ 'foo' => 'bar' ], LogEntryBase::extractParams( serialize( [ 'foo' => 'bar' ] ) ) );

		$this->assertNotEquals(
			[ 'foo' => new stdClass() ],
			LogEntryBase::extractParams( serialize( [ 'foo' => new stdClass() ] ) )
		);

		$scope = ExtensionRegistry::getInstance()->setAttributeForTest( 'LogParamsAllowedClasses', [
			'test/test' => [ stdClass::class ],
		] );
		$this->assertEquals(
			[ 'foo' => new stdClass() ],
			LogEntryBase::extractParams( serialize( [ 'foo' => new stdClass() ] ), 'test/test' )
		);
		$this->assertNotEquals(
			[ 'foo' => new stdClass() ],
			LogEntryBase::extractParams( serialize( [ 'foo' => new stdClass() ] ), 'blah/blah' )
		);
	}

	public function testContainsUnsafeParams() {
		$hasIncompleteClass = unserialize( serialize( [ 'foo' => new stdClass() ] ), [ 'allowed_classes' => false ] );
		$hasStdClass = unserialize( serialize( [ 'foo' => new stdClass() ] ), [ 'allowed_classes' => [ stdClass::class ] ] );

		$this->assertTrue( LogEntryBase::containsUnsafeParams( $hasIncompleteClass ) );
		$this->assertFalse( LogEntryBase::containsUnsafeParams( $hasStdClass ) );
		$this->assertFalse( LogEntryBase::containsUnsafeParams( [ 'asdf' ] ) );
	}

}
