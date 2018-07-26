<?php

/**
 * Try to make sure that extensions register all rights in $wgAvailableRights
 * or via the 'UserGetAllRights' hook.
 *
 * @author Marius Hoch < hoo@online.de >
 */
class AvailableRightsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * Returns all rights that should be in $wgAvailableRights + all rights
	 * registered via the 'UserGetAllRights' hook + all "core" rights.
	 *
	 * @return string[]
	 */
	private function getAllVisibleRights() {
		global $wgGroupPermissions, $wgRevokePermissions;

		$rights = User::getAllRights();

		foreach ( $wgGroupPermissions as $permissions ) {
			$rights = array_merge( $rights, array_keys( $permissions ) );
		}

		foreach ( $wgRevokePermissions as $permissions ) {
			$rights = array_merge( $rights, array_keys( $permissions ) );
		}

		$rights = array_unique( $rights );
		sort( $rights );

		return $rights;
	}

	/**
	 * @coversNothing
	 */
	public function testAvailableRights() {
		$missingRights = array_diff(
			$this->getAllVisibleRights(),
			User::getAllRights()
		);

		$this->assertEquals(
			[],
			// Re-index to produce nicer output, keys are meaningless.
			array_values( $missingRights ),
			'Additional user rights need to be added to $wgAvailableRights or ' .
			'via the "UserGetAllRights" hook. See the instructions at: ' .
			'https://www.mediawiki.org/wiki/Manual:User_rights#Adding_new_rights'
		);
	}

	/**
	 * Test, if for all rights a right- message exist,
	 * which is used on Special:ListGroupRights as help text
	 * Extensions and core
	 *
	 * @coversNothing
	 */
	public function testAllRightsWithMessage() {
		// Getting all user rights, for core: User::$mCoreRights, for extensions: $wgAvailableRights
		$allRights = User::getAllRights();
		$allMessageKeys = Language::getMessageKeysFor( 'en' );

		$rightsWithMessage = [];
		foreach ( $allMessageKeys as $message ) {
			// === 0: must be at beginning of string (position 0)
			if ( strpos( $message, 'right-' ) === 0 ) {
				$rightsWithMessage[] = substr( $message, strlen( 'right-' ) );
			}
		}

		$missing = array_diff(
			$allRights,
			$rightsWithMessage
		);

		$this->assertEquals(
			[],
			$missing,
			'Each user rights (core/extensions) has a corresponding right- message.'
		);
	}
}
