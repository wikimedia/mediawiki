<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWikiGroupValidator
 * @group MediaWikiIntegrationTestCaseTest
 */
class MediaWikiGroupValidatorTest extends MediaWikiIntegrationTestCase {

	public static function provideClassWithGroup(): iterable {
		return [
			[ MediaWikiGroupValidatorDatabaseGroup::class, true ],
			[ MediaWikiGroupValidatorWrongDatabaseGroup::class, false ],
			[ MediaWikiGroupValidatorSingleLineDatabaseGroup::class, true ],
		];
	}

	/** @dataProvider provideClassWithGroup */
	public function testClassWithGroup( string $className, bool $expected ): void {
		$testWrapper = TestingAccessWrapper::newFromClass( $className );
		$this->assertSame( $expected, $testWrapper->isTestInDatabaseGroup() );
	}
}

// @phpcs:disable MediaWiki.Commenting.PhpunitAnnotations.NotTestClass

/**
 * @group Database
 */
class MediaWikiGroupValidatorDatabaseGroup {
	use MediaWikiGroupValidator;
}

/**
 * @group Database With Extra Text
 */
class MediaWikiGroupValidatorWrongDatabaseGroup {
	use MediaWikiGroupValidator;
}

/** @group Database */
class MediaWikiGroupValidatorSingleLineDatabaseGroup {
	use MediaWikiGroupValidator;
}
