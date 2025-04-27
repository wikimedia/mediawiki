<?php

use MediaWiki\Cache\LinkCache;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;

/**
 * @covers \MediaWiki\Linker\LinkRendererFactory
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
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @var HookContainer
	 */
	private $hookContainer;

	private TempUserConfig $tempUserConfig;
	private TempUserDetailsLookup $tempUserDetailsLookup;
	private UserIdentityLookup $userIdentityLookup;
	private UserNameUtils $userNameUtils;

	protected function setUp(): void {
		parent::setUp();

		$this->titleFormatter = $this->createMock( TitleFormatter::class );
		$this->linkCache = $this->createMock( LinkCache::class );
		$this->specialPageFactory = $this->createMock( SpecialPageFactory::class );
		$this->hookContainer = $this->createMock( HookContainer::class );
		$this->tempUserConfig = $this->createMock( TempUserConfig::class );
		$this->tempUserDetailsLookup = $this->createMock( TempUserDetailsLookup::class );
		$this->userIdentityLookup = $this->createMock( UserIdentityLookup::class );
		$this->userNameUtils = $this->createMock( UserNameUtils::class );
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
			$this->titleFormatter,
			$this->linkCache,
			$this->specialPageFactory,
			$this->hookContainer,
			$this->tempUserConfig,
			$this->tempUserDetailsLookup,
			$this->userIdentityLookup,
			$this->userNameUtils
		);
		$linkRenderer = $factory->createFromLegacyOptions(
			$options
		);
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertEquals( $val, $linkRenderer->$func(), $func );
		$this->assertFalse(
			$linkRenderer->isForComment(),
			'isForComment should default to false in legacy implementation'
		);
	}

	public function testCreate() {
		$factory = new LinkRendererFactory(
			$this->titleFormatter,
			$this->linkCache,
			$this->specialPageFactory,
			$this->hookContainer,
			$this->tempUserConfig,
			$this->tempUserDetailsLookup,
			$this->userIdentityLookup,
			$this->userNameUtils
		);
		$linkRenderer = $factory->create();
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertFalse( $linkRenderer->isForComment(), 'isForComment should default to false' );
	}

	public function testCreateForComment() {
		$factory = new LinkRendererFactory(
			$this->titleFormatter,
			$this->linkCache,
			$this->specialPageFactory,
			$this->hookContainer,
			$this->tempUserConfig,
			$this->tempUserDetailsLookup,
			$this->userIdentityLookup,
			$this->userNameUtils
		);
		$linkRenderer = $factory->create( [ 'renderForComment' => true ] );
		$this->assertInstanceOf( LinkRenderer::class, $linkRenderer );
		$this->assertTrue( $linkRenderer->isForComment() );
	}
}
