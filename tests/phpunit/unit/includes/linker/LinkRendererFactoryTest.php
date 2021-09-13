<?php

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;

/**
 * @covers MediaWiki\Linker\LinkRendererFactory
 */
class LinkRendererFactoryTest extends MediaWikiUnitTestCase {

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

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @var HookContainer
	 */
	private $hookContainer;

	protected function setUp(): void {
		parent::setUp();

		$this->titleFormatter = $this->createMock( TitleFormatter::class );
		$this->linkCache = $this->createMock( LinkCache::class );
		$this->nsInfo = $this->createMock( NamespaceInfo::class );
		$this->specialPageFactory = $this->createMock( SpecialPageFactory::class );
		$this->hookContainer = $this->createMock( HookContainer::class );
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
			]
		];
	}

	/**
	 * @dataProvider provideCreateFromLegacyOptions
	 */
	public function testCreateFromLegacyOptions( $options, $func, $val ) {
		$factory = new LinkRendererFactory(
				$this->titleFormatter, $this->linkCache, $this->nsInfo,
				$this->specialPageFactory, $this->hookContainer
			);
		$linkRenderer = $factory->createFromLegacyOptions(
			$options
		);
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertEquals( $val, $linkRenderer->$func(), $func );
	}

	public function testCreate() {
		$factory = new LinkRendererFactory(
			$this->titleFormatter, $this->linkCache, $this->nsInfo,
			$this->specialPageFactory, $this->hookContainer
		);
		$this->assertInstanceOf( LinkRenderer::class, $factory->create() );
	}
}
