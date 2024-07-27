<?php

namespace MediaWiki\Tests\Maintenance;

use CheckSignatures;
use MediaWiki\User\UserIdentity;

/**
 * @covers \CheckSignatures
 * @group Database
 * @author Dreamy Jazz
 */
class CheckSignaturesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CheckSignatures::class;
	}

	/**
	 * @param int $count
	 * @return UserIdentity[]
	 */
	private function generateMutableTestUsers( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$testUser = $this->getMutableTestUser()->getUserIdentity();
			$returnArray[] = $testUser;
		}
		return $returnArray;
	}

	/**
	 * @param int $count
	 * @return UserIdentity[]
	 */
	private function generateUsersWithInvalidSignatures( int $count ): array {
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		$returnArray = $this->generateMutableTestUsers( $count );
		foreach ( $returnArray as $user ) {
			// Set the signature to invalid HTML
			$userOptionsManager->setOption( $user, 'nickname', '<b>{{test' );
			$userOptionsManager->setOption( $user, 'fancysig', true );
			$userOptionsManager->saveOptions( $user );
		}
		return $returnArray;
	}

	private function generateUsersWithValidSignatures( int $count ) {
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		foreach ( $this->generateMutableTestUsers( $count ) as $user ) {
			// Set the signature to invalid HTML
			$userOptionsManager->setOption( $user, 'nickname', '[[User:' . $user->getName() . "]]" );
			$userOptionsManager->setOption( $user, 'fancysig', true );
			$userOptionsManager->saveOptions( $user );
		}
	}

	/** @dataProvider provideExecute */
	public function testExecute( $validCount, $invalidCount ) {
		$usersWithInvalidSignatures = $this->generateUsersWithInvalidSignatures( $invalidCount );
		$this->generateUsersWithValidSignatures( $validCount );
		$this->maintenance->execute();
		$expectedOutputRegex = '/';
		foreach ( $usersWithInvalidSignatures as $user ) {
			$expectedOutputRegex .= $user->getName() . "\n";
		}
		$expectedOutputRegex .= ".*$invalidCount invalid signatures/";
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecute() {
		return [
			'All signatures valid' => [ 4, 0 ],
			'Some signatures valid' => [ 2, 3 ],
		];
	}
}
