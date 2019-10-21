<?php

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;

/**
 * @covers MediaWiki\Linker\LinkRendererFactory
 */
class LinkRendererFactoryTest extends MediaWikiLangTestCase {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var NamespaceInfo
	 */
	private $nsInfo;

	public function setUp() {
		parent::setUp();

		$services = MediaWikiServices::getInstance();
		$this->titleFormatter = $services->getTitleFormatter();
		$this->linkCache = $services->getLinkCache();
		$this->nsInfo = $services->getNamespaceInfo();
	}

	public static function provideCreateFromLegacyOptions() {
		return [
			[
				[ 'forcearticlepath' ],
				'getForceArticlePath',
				true
			],
			[
				[ 'http' ],
				'getExpandURLs',
				PROTO_HTTP
			],
			[
				[ 'https' ],
				'getExpandURLs',
				PROTO_HTTPS
			],
			[
				[ 'stubThreshold' => 150 ],
				'getStubThreshold',
				150
			],
		];
	}

	/**
	 * @dataProvider provideCreateFromLegacyOptions
	 */
	public function testCreateFromLegacyOptions( $options, $func, $val ) {
		$factory =
			new LinkRendererFactory( $this->titleFormatter, $this->linkCache, $this->nsInfo );
		$linkRenderer = $factory->createFromLegacyOptions(
			$options
		);
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertEquals( $val, $linkRenderer->$func(), $func );
	}

	public function testCreate() {
		$factory =
			new LinkRendererFactory( $this->titleFormatter, $this->linkCache, $this->nsInfo );
		$this->assertInstanceOf( LinkRenderer::class, $factory->create() );
	}

	public function testCreateForUser() {
		/** @var PHPUnit_Framework_MockObject_MockObject|User $user */
		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getStubThreshold' ] )->getMock();
		$user->expects( $this->once() )
			->method( 'getStubThreshold' )
			->willReturn( 15 );
		$factory =
			new LinkRendererFactory( $this->titleFormatter, $this->linkCache, $this->nsInfo );
		$linkRenderer = $factory->createForUser( $user );
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertEquals( 15, $linkRenderer->getStubThreshold() );
	}
}
