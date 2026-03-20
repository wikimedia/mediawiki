<?php
namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialRandomPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Specials\SpecialRandomPage
 */
class SpecialRandomPageTest extends SpecialPageTestBase {

	/** @var SpecialRandomPage */
	private $page;

	public function setUp(): void {
		parent::setUp();
		$this->page = $this->newSpecialPage();
	}

	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new class(
			$services->getConnectionProvider(),
			$services->getNamespaceInfo()
		) extends SpecialRandomPage {
			public function getRandomTitle() {
				return Title::newFromText( 'Main Page' );
			}
		};
	}

	/**
	 * @dataProvider providerParsePar
	 * @param string $par
	 * @param array $expectedNS Array of integer namespace ids
	 */
	public function testParsePar( $par, $expectedNS ) {
		/** @var SpecialRandomPage $page */
		$page = TestingAccessWrapper::newFromObject( $this->page );
		$page->parsePar( $par );
		$this->assertEquals( $expectedNS, $this->page->getNamespaces() );
	}

	public static function providerParsePar() {
		global $wgContentNamespaces;

		return [
			[ null, $wgContentNamespaces ],
			[ '', [ NS_MAIN ] ],
			[ 'main', [ NS_MAIN ] ],
			[ 'article', [ NS_MAIN ] ],
			[ '0', [ NS_MAIN ] ],
			[ 'File', [ NS_FILE ] ],
			[ 'file_talk', [ NS_FILE_TALK ] ],
			[ 'hElP', [ NS_HELP ] ],
			[ 'Project,', [ NS_PROJECT, NS_MAIN ] ],
			[ 'Project,Help,User_talk', [ NS_PROJECT, NS_HELP, NS_USER_TALK ] ],
			[ '|invalid|,|namespaces|', $wgContentNamespaces ],
		];
	}

	/**
	 * @dataProvider provideDisallowedActions
	 */
	public function testDisallowedActions( array $params, string $expectedAction ): void {
		[ $html, $response ] = $this->executeSpecialPage( '', new FauxRequest( $params ), 'en' );
		$redirect = $response->getHeader( 'LOCATION' ) ?? '';

		if ( $expectedAction === 'redirectToRandomPage' ) {
			$this->assertNotSame( '', $redirect, 'Should redirect (to a random page)' );
		} elseif ( $expectedAction === 'disallowedActionError' ) {
			$this->assertSame( '', $redirect,
				'Should not redirect (to a random page)' );
			$this->assertStringContainsString( 'For security reasons, action', $html,
				'HTML should contain the randompage-disallowed-action message' );
			$escaped = htmlspecialchars( $params['action'] );
			$this->assertStringContainsString( $escaped, $html,
				'The ?action= from the URL, which is printed in the error message, must be HTML escaped' );
		}
	}

	public static function provideDisallowedActions(): iterable {
		yield [
			[],
			'redirectToRandomPage',
		];
		yield [
			[ 'action' => 'view' ],
			'redirectToRandomPage',
		];
		yield [
			[ 'action' => 'edit' ],
			'redirectToRandomPage',
		];
		yield [
			[ 'action' => 'history' ],
			'redirectToRandomPage',
		];
		yield [
			[ 'action' => 'delete' ],
			'disallowedActionError',
		];
		yield [
			[ 'action' => 'gibberish' ],
			'disallowedActionError',
		];
		yield [
			[ 'action' => '<b>this better be escaped!</b>' ],
			'disallowedActionError',
		];
	}
}
