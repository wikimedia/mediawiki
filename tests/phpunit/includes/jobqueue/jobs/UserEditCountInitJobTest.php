<?php
use MediaWiki\MediaWikiServices;

/**
 * @group JobQueue
 * @group Database
 */
class UserEditCountInitJobTest extends MediaWikiIntegrationTestCase {

	/** @var User */
	private $user;

	protected function setUp(): void {
		parent::setUp();

		$this->user = $this->getMutableTestUser()->getUser();
	}

	/** @covers UserEditCountInitJob */
	public function testShouldInitEditCountWhenNotYetSet(): void {
		$job = new UserEditCountInitJob( [
			'userId' => $this->user->getId(),
			'editCount' => 2
		] );

		$result = $job->run();

		$this->assertTrue( $result );
		$this->assertEquals( 2, $this->user->getEditCount() );
	}

	/** @covers UserEditCountInitJob */
	public function testShouldInitEditCountWhenGreaterThanCurrentCount(): void {
		$this->setTestUserEditCount( 2 );

		$job = new UserEditCountInitJob( [
			'userId' => $this->user->getId(),
			'editCount' => 3
		] );

		$result = $job->run();

		$this->assertTrue( $result );
		$this->assertEquals( 3, $this->user->getEditCount() );
	}

	/** @covers UserEditCountInitJob */
	public function testShouldNotChangeEditCountWhenLessThanCurrentCount(): void {
		$this->setTestUserEditCount( 10 );

		$job = new UserEditCountInitJob( [
			'userId' => $this->user->getId(),
			'editCount' => 3
		] );

		$result = $job->run();

		$this->assertTrue( $result );
		$this->assertEquals( 10, $this->user->getEditCount() );
	}

	private function setTestUserEditCount( int $editCount ): void {
		$services = MediaWikiServices::getInstance();

		$dbw = $services->getDBLoadBalancer()->getConnectionRef( DB_MASTER );

		$dbw->update(
			'user',
			// SET
			[ 'user_editcount' => $editCount ],
			// WHERE
			[ 'user_id' => $this->user->getId() ],
			__METHOD__
		);
	}
}
