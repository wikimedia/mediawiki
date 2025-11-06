<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Specials\SpecialContribute;
use MediaWiki\User\User;

/**
 * @author MAbualruz
 * @group Database
 * @covers \MediaWiki\Specials\SpecialContribute
 */
class SpecialContributeTest extends SpecialPageTestBase {
	private const CLAZZ = 'SpecialContributeTest';

	/** @var string */
	private $pageName = self::CLAZZ . 'BlaBlaTest';

	/** @var User */
	private $admin;

	/** @var SpecialContribute */
	private $specialContribute;

	protected function setUp(): void {
		parent::setUp();
		$this->admin = new UltimateAuthority( $this->getTestSysop()->getUser() );
	}

	/**
	 * @covers \MediaWiki\Specials\SpecialContribute::execute
	 */
	public function testExecute() {
		$this->specialContribute = new SpecialContribute();
		[ $html ] = $this->executeSpecialPage(
			$this->admin->getUser()->getName(),
			null,
			'qqx',
			$this->admin,
			true
		);
		$this->assertStringContainsString( '<div class="mw-contribute-wrapper">', $html );
		$this->assertStringContainsString( '<div class="mw-contribute-card-content">', $html );
	}

	public function testIsShowable() {
		$this->specialContribute = new SpecialContribute();
		$this->executeSpecialPage(
			$this->admin->getUser()->getName(),
			null,
			'qqx',
			$this->admin,
			true
		);
		$this->assertFalse( $this->specialContribute->isShowable() );
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage(): SpecialContribute {
		return $this->specialContribute;
	}

}
