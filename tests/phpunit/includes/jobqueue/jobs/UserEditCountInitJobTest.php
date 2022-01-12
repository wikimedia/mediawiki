<?php

/**
 * @group JobQueue
 * @group Database
 */
class UserEditCountInitJobTest extends MediaWikiIntegrationTestCase {

	public function provideTestCases() {
		// $startingEditCount, $setCount, $finalCount
		yield 'Initiate count if not yet set' => [ false, 2, 2 ];
		yield 'Update count when increasing' => [ 2, 3, 3 ];
		yield 'Never decrease count' => [ 10, 3, 10 ];
	}

	/**
	 * @covers UserEditCountInitJob
	 * @dataProvider provideTestCases
	 */
	public function testUserEditCountInitJob( $startingEditCount, $setCount, $finalCount ) {
		$user = $this->getMutableTestUser()->getUser();

		if ( $startingEditCount !== false ) {
			$this->getServiceContainer()->getDbLoadBalancer()
				->getConnectionRef( DB_PRIMARY )
				->update(
					'user',
					[ 'user_editcount' => $startingEditCount ], // SET
					[ 'user_id' => $user->getId() ], // WHERE
					__METHOD__
				);
		}

		$job = new UserEditCountInitJob( [
			'userId' => $user->getId(),
			'editCount' => $setCount
		] );

		$result = $job->run();

		$this->assertTrue( $result );
		$this->assertEquals( $finalCount, $user->getEditCount() );
	}
}
