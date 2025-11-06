<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Interwiki\Interwiki;
use MediaWiki\Interwiki\InterwikiLookupAdapter;
use MediaWiki\Site\HashSiteStore;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Specials\SpecialGoToInterwiki
 */
class SpecialGoToInterwikiTest extends MediaWikiIntegrationTestCase {

	public function testExecute() {
		$this->setService( 'InterwikiLookup', new InterwikiLookupAdapter(
			new HashSiteStore(), // won't be used
			[
				'local' => new Interwiki( 'local', 'https://local.example.com/$1',
					'https://local.example.com/api.php', 'unittest_localwiki', 1 ),
				'nonlocal' => new Interwiki( 'nonlocal', 'https://nonlocal.example.com/$1',
					'https://nonlocal.example.com/api.php', 'unittest_nonlocalwiki', 0 ),
			]
		) );
		$this->getServiceContainer()->resetServiceForTesting( 'TitleFormatter' );
		$this->getServiceContainer()->resetServiceForTesting( 'TitleParser' );

		$this->assertNotTrue( Title::newFromText( 'Foo' )->isExternal() );
		$this->assertTrue( Title::newFromText( 'local:Foo' )->isExternal() );
		$this->assertTrue( Title::newFromText( 'nonlocal:Foo' )->isExternal() );
		$this->assertTrue( Title::newFromText( 'local:Foo' )->isLocal() );
		$this->assertNotTrue( Title::newFromText( 'nonlocal:Foo' )->isLocal() );

		$goToInterwiki = $this->getServiceContainer()->getSpecialPageFactory()
			->getPage( 'GoToInterwiki' );

		RequestContext::resetMain();
		$context = new DerivativeContext( RequestContext::getMain() );
		$goToInterwiki->setContext( $context );
		$goToInterwiki->execute( 'Foo' );
		$this->assertSame( Title::newFromText( 'Foo' )->getFullURL(),
			$context->getOutput()->getRedirect() );

		RequestContext::resetMain();
		$context = new DerivativeContext( RequestContext::getMain() );
		$goToInterwiki->setContext( $context );
		$goToInterwiki->execute( 'local:Foo' );
		$this->assertSame( Title::newFromText( 'local:Foo' )->getFullURL(),
			$context->getOutput()->getRedirect() );

		RequestContext::resetMain();
		$context = new DerivativeContext( RequestContext::getMain() );
		$goToInterwiki->setContext( $context );
		$goToInterwiki->execute( 'nonlocal:Foo' );
		$this->assertSame( '', $context->getOutput()->getRedirect() );
		$this->assertStringContainsString( Title::newFromText( 'nonlocal:Foo' )->getFullURL(),
			$context->getOutput()->getHTML() );

		RequestContext::resetMain();
		$context = new DerivativeContext( RequestContext::getMain() );
		$goToInterwiki->setContext( $context );
		$goToInterwiki->execute( 'force/Foo' );
		$this->assertSame( Title::newFromText( 'Foo' )->getFullURL(),
			$context->getOutput()->getRedirect() );

		RequestContext::resetMain();
		$context = new DerivativeContext( RequestContext::getMain() );
		$goToInterwiki->setContext( $context );
		$goToInterwiki->execute( 'force/local:Foo' );
		$this->assertSame( '', $context->getOutput()->getRedirect() );
		$this->assertStringContainsString( Title::newFromText( 'local:Foo' )->getFullURL(),
			$context->getOutput()->getHTML() );

		RequestContext::resetMain();
		$context = new DerivativeContext( RequestContext::getMain() );
		$goToInterwiki->setContext( $context );
		$goToInterwiki->execute( 'force/nonlocal:Foo' );
		$this->assertSame( '', $context->getOutput()->getRedirect() );
		$this->assertStringContainsString( Title::newFromText( 'nonlocal:Foo' )->getFullURL(),
			$context->getOutput()->getHTML() );
	}

}
