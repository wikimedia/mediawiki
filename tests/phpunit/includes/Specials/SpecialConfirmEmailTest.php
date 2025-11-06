<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Specials\SpecialConfirmEmail;
use MediaWiki\User\UserFactory;

/**
 * @covers \MediaWiki\Specials\SpecialConfirmEmail
 * @group Database
 */
class SpecialConfirmEmailTest extends SpecialPageTestBase {
	protected function newSpecialPage() {
		return new SpecialConfirmEmail(
			$this->createMock( UserFactory::class )
		);
	}

	public function testExecute() {
		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->getTestUser()->getAuthority()
		);

		$this->assertStringContainsString( '(confirmemail_text)', $html );
	}
}
