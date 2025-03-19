<?php

use MediaWiki\JobQueue\JobSpecification;

/**
 * @covers \MediaWiki\JobQueue\JobSpecification
 */
class JobSpecificationTest extends MediaWikiUnitTestCase {
	private const JOB_TYPE = 'testJob';
	private const JOB_PARAMS = [ 'param' => 'value' ];

	/**
	 * @covers \MediaWiki\JobQueue\JobSpecification::ignoreDuplicates
	 */
	public function testNotRemoveDuplicates() {
		$jobSpec = new JobSpecification(
			self::JOB_TYPE,
			self::JOB_PARAMS
		);
		$this->assertFalse( $jobSpec->ignoreDuplicates(),
			'Must not be deduplicated if removeDuplicates not set' );
	}

	/**
	 * @covers \MediaWiki\JobQueue\JobSpecification::ignoreDuplicates
	 */
	public function testRemoveDuplicates() {
		$jobSpec = new JobSpecification(
			self::JOB_TYPE,
			self::JOB_PARAMS,
			[ 'removeDuplicates' => true ]
		);
		$this->assertTrue( $jobSpec->ignoreDuplicates(),
			'Must be deduplicated if removeDuplicate is set' );
	}

	/**
	 * @covers \MediaWiki\JobQueue\JobSpecification::getDeduplicationInfo
	 */
	public function testGetDeduplicationInfo() {
		$jobSpec = new JobSpecification(
			self::JOB_TYPE,
			self::JOB_PARAMS,
			[ 'removeDuplicates' => true ]
		);
		$this->assertEquals(
			[ 'type' => self::JOB_TYPE, 'params' => self::JOB_PARAMS ],
			$jobSpec->getDeduplicationInfo()
		);
	}

	/**
	 * @covers \MediaWiki\JobQueue\JobSpecification::getDeduplicationInfo
	 */
	public function testGetDeduplicationInfo_ignoreParams() {
		$jobSpec = new JobSpecification(
			self::JOB_TYPE,
			self::JOB_PARAMS + [ 'ignored_param' => 'ignored_value' ],
			[ 'removeDuplicates' => true, 'removeDuplicatesIgnoreParams' => [ 'ignored_param' ] ]
		);
		$this->assertEquals(
			[ 'type' => self::JOB_TYPE, 'params' => self::JOB_PARAMS ],
			$jobSpec->getDeduplicationInfo()
		);
	}
}
