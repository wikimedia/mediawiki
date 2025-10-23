<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use MediaWiki\Request\FauxRequest;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\User\UserRequirementsConditionChecker
 * @group Database
 */
class UserRequirementsConditionCheckerTest extends MediaWikiIntegrationTestCase {

	/** @dataProvider provideConditionsRelevantToPerformer */
	public function testConditionsRelevantToPerformer( array $conditions, bool $isPerformer, bool $expected ): void {
		$request = new FauxRequest();
		$request->setIP( '127.0.0.1' );

		$userMock = $this->createMock( User::class );
		$userMock->method( 'getRequest' )->willReturn( $request );
		$userMock->method( 'getRegistration' )->willReturn( '20000101000000' );

		$checker = $this->getServiceContainer()->getUserRequirementsConditionChecker();
		$result = $checker->recursivelyCheckCondition( $conditions, $userMock, $isPerformer );
		$this->assertSame( $expected, $result );
	}

	public static function provideConditionsRelevantToPerformer(): array {
		return [
			// Primitive conditions
			'The performer is from valid IP' => [
				'conditions' => [ APCOND_ISIP, '127.0.0.1' ],
				'isPerformer' => true,
				'expected' => true,
			],
			'The performer is from valid IP rage' => [
				'conditions' => [ APCOND_IPINRANGE, '127.0.0.0/8' ],
				'isPerformer' => true,
				'expected' => true,
			],
			'The non-performer is from otherwise valid IP' => [
				'conditions' => [ APCOND_ISIP, '127.0.0.1' ],
				'isPerformer' => false,
				'expected' => false,
			],
			'The non-performer is from otherwise valid IP rage' => [
				'conditions' => [ APCOND_IPINRANGE, '127.0.0.0/8' ],
				'isPerformer' => false,
				'expected' => false,
			],
			// Complex conditions
			'The non-performer is from otherwise valid IP but also has sufficiently old account (OR)' => [
				'conditions' => [ '|', [ APCOND_ISIP, '127.0.0.1' ], [ APCOND_AGE, 60 ] ],
				'isPerformer' => false,
				'expected' => true,
			],
			'The non-performer is from otherwise valid IP but also has sufficiently old account (AND)' => [
				'conditions' => [ '&', [ APCOND_ISIP, '127.0.0.1' ], [ APCOND_AGE, 60 ] ],
				'isPerformer' => false,
				'expected' => false,
			],
		];
	}
}
