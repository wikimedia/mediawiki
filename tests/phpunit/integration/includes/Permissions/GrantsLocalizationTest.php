<?php

namespace MediaWiki\Tests\Integration\Permissions;

use MediaWiki\Html\Html;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWikiIntegrationTestCase;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * @author Zabe
 *
 * @covers \MediaWiki\Permissions\GrantsLocalization
 */
class GrantsLocalizationTest extends MediaWikiIntegrationTestCase {
	private GrantsLocalization $grantsLocalization;

	protected function setUp(): void {
		parent::setUp();

		$this->grantsLocalization = $this->getServiceContainer()->getGrantsLocalization();
	}

	/**
	 * @dataProvider provideGrantDescriptions
	 */
	public function testGetGrantDescription( string $grant ) {
		$message = new Message( 'grant-' . $grant );
		$this->assertSame(
			$message->text(),
			$this->grantsLocalization->getGrantDescription( $grant )
		);
		$this->assertSame(
			$message->inLanguage( 'de' )->text(),
			$this->grantsLocalization->getGrantDescription( $grant, 'de' )
		);
	}

	public function provideGrantDescriptions() {
		yield [ 'blockusers' ];
		yield [ 'createeditmovepage' ];
		yield [ 'delete' ];
	}

	public function testGetNonExistingGrantDescription() {
		$message = ( new Message( 'grant-generic' ) )->params( 'foo' );
		$this->assertSame(
			$message->text(),
			$this->grantsLocalization->getGrantDescription( 'foo' )
		);
		$this->assertSame(
			$message->inLanguage( 'zh' )->text(),
			$this->grantsLocalization->getGrantDescription( 'foo', 'zh' )
		);
	}

	public function testGetGrantDescriptions() {
		$this->assertSame(
			[
				'blockusers' => ( new Message( 'grant-blockusers' ) )->inLanguage( 'de' )->text(),
				'delete' => ( new Message( 'grant-delete' ) )->inLanguage( 'de' )->text(),
			],
			$this->grantsLocalization->getGrantDescriptions(
				[
					'blockusers',
					'delete',
				],
				'de'
			)
		);
	}

	public function testGetGrantsLink() {
		$this->assertSame(
			$this->getServiceContainer()->getLinkRenderer()->makeKnownLink(
				SpecialPage::getTitleFor( 'Listgrants', false, 'delete' ),
				new HtmlArmor(
					( new Message( 'grant-delete' ) )->escaped() . ' ' .
					Html::element( 'span', [ 'class' => 'mw-grant mw-grantriskgroup-vandalism' ],
						( new Message( 'grantriskgroup-vandalism' ) )->text() )
				)
			),
			$this->grantsLocalization->getGrantsLink( 'delete' )
		);
	}

	public function testGetGrantsWikiText() {
		$this->assertSame(
			"*<span class=\"mw-grantgroup\">Perform high volume activity</span>\n:High-volume (bot) access\n\n",
			$this->grantsLocalization->getGrantsWikiText( [ 'highvolume' ] )
		);
	}
}
