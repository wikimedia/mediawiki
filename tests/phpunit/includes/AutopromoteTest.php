<?php

/**
 * @covers Autopromote
 */
class AutopromoteTest extends MediaWikiTestCase {
	/**
	 * T157718: Verify Autopromote does not perform edit count lookup if requirement is 0 or invalid
	 *
	 * @see Autopromote::getAutopromoteGroups()
	 * @dataProvider provideEditCountsAndRequirements
	 * @param int $editCount edit count of user to be checked by Autopromote
	 * @param int $requirement edit count required to autopromote user
	 */
	public function testEditCountLookupIsSkippedIfRequirementIsZero( $editCount, $requirement ) {
		$this->setMwGlobals( [
			'wgAutopromote' => [
				'autoconfirmed' => [ APCOND_EDITCOUNT, $requirement ]
			]
		] );

		/** @var PHPUnit_Framework_MockObject_MockObject|User $userMock */
		$userMock = $this->getMock( User::class, [ 'getEditCount' ] );
		if ( $requirement > 0 ) {
			$userMock->expects( $this->once() )
				->method( 'getEditCount' )
				->willReturn( $editCount );
		} else {
			$userMock->expects( $this->never() )
				->method( 'getEditCount' );
		}

		$result = Autopromote::getAutopromoteGroups( $userMock );
		if ( $editCount >= $requirement ) {
			$this->assertContains(
				'autoconfirmed',
				$result,
				'User must be promoted if they meet edit count requirement'
			);
		} else {
			$this->assertNotContains(
				'autoconfirmed',
				$result,
				'User must not be promoted if they fail edit count requirement'
			);
		}
	}

	public static function provideEditCountsAndRequirements() {
		return [
			'user with sufficient editcount' => [ 100, 10 ],
			'user with insufficient editcount' => [ 4, 10 ],
			'edit count requirement set to 0' => [ 1, 0 ],
		];
	}
}
