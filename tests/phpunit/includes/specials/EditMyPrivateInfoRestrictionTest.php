<?php
namespace MediaWiki\Tests\Specials;

use MediaWikiIntegrationTestCase;
use PermissionsError;
use SpecialPageExecutor;

/**
 * Special pages that override execute() must still enforce the
 * 'editmyprivateinfo' right they declare as their restriction.
 *
 * @covers \MediaWiki\Specials\SpecialChangeCredentials
 * @covers \MediaWiki\Specials\SpecialRemoveCredentials
 * @covers \MediaWiki\Specials\SpecialLinkAccounts
 * @covers \MediaWiki\Specials\SpecialUnlinkAccounts
 * @group Database
 */
class EditMyPrivateInfoRestrictionTest extends MediaWikiIntegrationTestCase {

	public static function provideSpecialPages(): array {
		return [
			[ 'ChangeCredentials' ],
			[ 'RemoveCredentials' ],
			[ 'LinkAccounts' ],
			[ 'UnlinkAccounts' ],
		];
	}

	/**
	 * @dataProvider provideSpecialPages
	 */
	public function testDeniedWithoutRight( string $name ): void {
		$this->setGroupPermissions( [
			'*' => [ 'editmyprivateinfo' => false ],
			'user' => [ 'editmyprivateinfo' => false ],
		] );
		$page = $this->getServiceContainer()->getSpecialPageFactory()->getPage( $name );

		$this->expectException( PermissionsError::class );
		( new SpecialPageExecutor() )->executeSpecialPage(
			$page, '', null, 'qqx', $this->getTestUser()->getAuthority()
		);
	}
}
