<?php

use MediaWiki\MainConfigNames;

/**
 * Try to make sure that extensions register all rights in $wgAvailableRights
 * or via the 'UserGetAllRights' hook.
 * Note: this test can also fail for bad declarations in LocalSettings. Long-term fix is T277470.
 *
 * @author Marius Hoch < hoo@online.de >
 * @coversNothing
 */
class AvailableRightsTest extends MediaWikiIntegrationTestCase {

	use MediaWikiCoversValidator;

	/**
	 * Returns all rights that should be in $wgAvailableRights + all rights
	 * registered via the 'UserGetAllRights' hook + all "core" rights.
	 *
	 * @return string[]
	 */
	private function getAllVisibleRights() {
		global $wgGroupPermissions, $wgRevokePermissions;

		$rights = $this->getServiceContainer()->getPermissionManager()->getAllPermissions();

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

	public function testAvailableRights() {
		$missingRights = array_diff(
			$this->getAllVisibleRights(),
			$this->getServiceContainer()->getPermissionManager()->getAllPermissions()
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

	public function testAvailableRightsShouldNotBeImplicitRights() {
		$intersection = array_intersect(
			$this->getServiceContainer()->getPermissionManager()->getImplicitRights(),
			$this->getServiceContainer()->getPermissionManager()->getAllPermissions()
		);

		$this->assertEquals(
			[],
			// Re-index to produce nicer output, keys are meaningless.
			array_values( $intersection ),
			'Additional user rights can be added to $wgAvailableRights or $wgImplicitRights, ' .
			'but not both!'
		);
	}

	public function testLimitsAreRights() {
		$knownRights = array_merge(
			$this->getServiceContainer()->getPermissionManager()->getImplicitRights(),
			$this->getServiceContainer()->getPermissionManager()->getAllPermissions()
		);

		$missingRights = array_diff(
			array_keys( $this->getConfVar( MainConfigNames::RateLimits ) ),
			$knownRights
		);

		$this->assertEquals(
			[],
			// Re-index to produce nicer output, keys are meaningless.
			array_values( $missingRights ),
			'All keys in $wgRateLimits must be listed in $wgAvailableRights or $wgImplicitRights, ' .
			'unless the keys are defined as rights by MediaWiki core.'
		);
	}

	/**
	 * Test, if for all rights an action- message exists,
	 * which is used on Special:ListGroupRights as help text
	 * Extensions and core
	 *
	 * @coversNothing
	 */
	public function testAllActionsWithMessages() {
		$this->checkMessagesExist( 'action-' );
	}

	/**
	 * Test, if for all rights a right- message exists,
	 * which is used on Special:ListGroupRights as help text
	 * Extensions and core
	 */
	public function testAllRightsWithMessage() {
		$this->checkMessagesExist( 'right-' );
	}

	/**
	 * @param string $prefix
	 */
	private function checkMessagesExist( $prefix ) {
		// Getting all user rights, for core: User::$mCoreRights, for extensions: $wgAvailableRights
		$services = $this->getServiceContainer();
		$allRights = $services->getPermissionManager()->getAllPermissions();
		$allMessageKeys = $services->getLocalisationCache()->getSubitemList( 'en', 'messages' );

		$messagesToCheck = [];
		foreach ( $allMessageKeys as $message ) {
			if ( str_starts_with( $message, $prefix ) ) {
				$messagesToCheck[] = substr( $message, strlen( $prefix ) );
			}
		}

		$missing = array_diff(
			$allRights,
			$messagesToCheck
		);

		$this->assertEquals(
			[],
			$missing,
			"Each user right (core/extensions) has a corresponding $prefix message."
				. ' See the instructions at: '
				. 'https://www.mediawiki.org/wiki/Manual:User_rights#Adding_new_rights'
		);
	}
}
