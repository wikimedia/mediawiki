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
		list( $html ) = $this->executeSpecialPage( $this->admin->getUser()->getName(), null, 'qqx', $this->admin, true );
		$this->assertStringContainsString( '<div class="mw-contribute-wrapper">', $html );
		$this->assertStringContainsString( '<div class="mw-contribute-card-content">', $html );
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage(): SpecialContribute {
		return new SpecialContribute();
	}

}
