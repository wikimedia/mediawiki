<?php

use MediaWiki\Permissions\UltimateAuthority;

/**
 * @author MAbualruz
 * @group Database
 * @covers SpecialContribute
 */
class SpecialContributeTest extends SpecialPageTestBase {
	private $pageName = __CLASS__ . 'BlaBlaTest';
	private $admin;

	protected function setUp(): void {
		parent::setUp();
		$this->admin = new UltimateAuthority( $this->getTestSysop()->getUser() );
	}

	/**
	 * @covers SpecialContribute::execute
	 */
	public function testExecute() {
		try {
			// Now only enabled for minerva skin
			list( $html ) = $this->executeSpecialPage( $this->admin->getUser()->getName(), null, 'qqx', $this->admin, true );
			$this->assertStringContainsString( '<div class="mw-contribute-wrapper">', $html );
			$this->assertStringContainsString( '<div class="mw-contribute-card-content">', $html );
		} catch ( Exception $e ) {
			// in case for other skins check if the page is not allowed to be shown
			$this->assertStringContainsString( 'You are not allowed to execute the action you have requested.', $e->getMessage() );
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage(): SpecialContribute {
		return new SpecialContribute();
	}

}
