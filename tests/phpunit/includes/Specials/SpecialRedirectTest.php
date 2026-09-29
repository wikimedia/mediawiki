<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialRedirect;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Specials\SpecialRedirect
 * @group Database
 * @license GPL-2.0-or-later
 */
class SpecialRedirectTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://example.org',
			MainConfigNames::CanonicalServer => 'https://example.org',
		] );
	}

	public function testUserRedirect() {
		$special = new SpecialRedirect(
			$this->getServiceContainer()->getRepoGroup(),
			$this->getServiceContainer()->getUserFactory()
		);
		$user = $this->getTestSysop()->getUser();

		$special->setParameter( 'user/' . $user->getId() );
		$ret = $special->dispatch();
		$this->assertSame( 'https://example.org/wiki/User:UTSysop', $special->getOutput()->getRedirect() );
		$this->assertSame( '302', $special->getOutput()->mRedirectCode );
		$this->assertTrue( $ret );
	}

	public function testRevisionRedirect() {
		$special = new SpecialRedirect(
			$this->getServiceContainer()->getRepoGroup(),
			$this->getServiceContainer()->getUserFactory()
		);

		$special->setParameter( 'revision/1' );
		$ret = $special->dispatch();
		$this->assertSame( '/index.php?oldid=1', $special->getOutput()->getRedirect() );
		$this->assertSame( '301', $special->getOutput()->mRedirectCode );
		$this->assertTrue( $ret );
	}

	public function testPageRedirect() {
		$special = new SpecialRedirect(
			$this->getServiceContainer()->getRepoGroup(),
			$this->getServiceContainer()->getUserFactory()
		);
		$page = $this->getExistingTestPage( 'Help:Test' );

		$special->setParameter( 'page/' . $page->getId() );
		$ret = $special->dispatch();
		$this->assertSame( '/index.php?curid=' . $page->getId(), $special->getOutput()->getRedirect() );
		$this->assertSame( '301', $special->getOutput()->mRedirectCode );
		$this->assertTrue( $ret );
	}

	public function testLogidRedirect() {
		$special = new SpecialRedirect(
			$this->getServiceContainer()->getRepoGroup(),
			$this->getServiceContainer()->getUserFactory()
		);

		$special->setParameter( 'logid/1' );
		$ret = $special->dispatch();
		$this->assertSame( '/index.php?title=Special%3ALog&logid=1', $special->getOutput()->getRedirect() );
		$this->assertSame( '301', $special->getOutput()->mRedirectCode );
		$this->assertTrue( $ret );
	}

	/**
	 * @dataProvider provideDispatchInvalid
	 */
	public function testDispatchInvalid( $method, $subpage ) {
		$page = new SpecialRedirect(
			$this->getServiceContainer()->getRepoGroup(),
			$this->getServiceContainer()->getUserFactory()
		);
		$page->setParameter( $subpage );
		$status = $page->dispatch();
		$this->assertFalse(
			$status->isGood(),
			$method . ' expects fatal status'
		);
	}

	public static function provideDispatchInvalid() {
		yield [ 'dispatchUser', 'user/nonumeric' ];
		yield [ 'dispatchUser', 'user/3' ];

		// TODO Cannot test the good path here, because a file must exists
		yield [ 'dispatchFile', 'file/bad<name' ];
		yield [ 'dispatchFile', 'file/File:Non-exists.jpg' ];

		yield [ 'dispatchRevision', 'revision/nonumeric' ];
		yield [ 'dispatchRevision', 'revision/0' ];

		yield [ 'dispatchPage', 'page/nonumeric' ];
		yield [ 'dispatchPage', 'page/0' ];

		yield [ 'dispatchLog', 'logid/nonumeric' ];
		yield [ 'dispatchLog', 'logid/0' ];
	}

}
