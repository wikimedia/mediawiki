<?php

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;

/**
 * @covers LinkRendererFactory
 */
class LinkRendererFactoryTest extends MediaWikiLangTestCase {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	public function setUp() {
		parent::setUp();
		$this->titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
	}

	public static function provideCreateFromLegacyOptions() {
		return [
			[
				[ 'noclasses' ],
				'getNoClasses',
				true
			],
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
		$factory = new LinkRendererFactory( $this->titleFormatter );
		$linkRenderer = $factory->createFromLegacyOptions(
			$options
		);
		$this->assertEquals( $val, $linkRenderer->$func() );
	}

	public function testCreate() {
		$factory = new LinkRendererFactory( $this->titleFormatter );
		$this->assertInstanceOf( LinkRenderer::class, $factory->create() );
	}
}
