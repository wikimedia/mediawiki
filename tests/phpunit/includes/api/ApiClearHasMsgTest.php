<?php

/**
 * @group API
 * @group medium
 * @covers ApiClearHasMsg
 */
class ApiClearHasMsgTest extends ApiTestCase {

	/**
	 * Test clearing hasmsg flag for current user
	 */
	public function testClearFlag() {
		$user = self::$users['sysop']->getUser();
		$user->setNewtalk( true );
		$this->assertTrue( $user->getNewtalk(), 'sanity check' );

		$data = $this->doApiRequest( [ 'action' => 'clearhasmsg' ], [] );

		$this->assertEquals( 'success', $data[0]['clearhasmsg'] );
		$this->assertFalse( $user->getNewtalk() );
	}

}
