<?php

/**
 * Try to make sure that extensions register all rights in $wgAvailableRights
 * or via the 'UserGetAllRights' hook.
 *
 * @author Marius Hoch < hoo@online.de >
 */
class AvailableRightsTest extends PHPUnit_Framework_TestCase {

	/**
	 * Returns all rights that should be in $wgAvailableRights + all rights
	 * registered via the 'UserGetAllRights' hook + all "core" rights.
	 *
	 * @return string[]
	 */
	private function getAllVisibleRights() {
		global $wgGroupPermissions, $wgRevokePermissions;

		$rights = User::getAllRights();

		foreach( $wgGroupPermissions as $permissions ) {
			$rights = array_merge( $rights, array_keys( $permissions ) );
		}

		foreach( $wgRevokePermissions as $permissions ) {
			$rights = array_merge( $rights, array_keys( $permissions ) );
		}

		$rights = array_unique( $rights );
		sort( $rights );

		return $rights;
	}

	public function testAvailableRights() {
		$missingRights = array_diff( $this->getAllVisibleRights(), User::getAllRights() );

		$this->assertEquals(
			array(),
			array_values( $missingRights ), // Re-Index to produce nicer output, keys are meaningless
			'Additional user rights need to be added to $wgAvailableRights or via the "UserGetAllRights" hook'
		);
	}
}
