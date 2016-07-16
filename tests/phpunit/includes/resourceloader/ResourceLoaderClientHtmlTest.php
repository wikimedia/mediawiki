<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderClientHtmlTest extends PHPUnit_Framework_TestCase {

	protected static function makeContext( $extraQuery = [] ) {
		return new ResourceLoaderContext(
			new ResourceLoader(),
			new FauxRequest( array_merge( [
				'lang' => 'nl',
				'skin' => 'fallback',
				'user' => 'Example',
				'target' => 'phpunit',
			], $extraQuery ) )
		);
	}

	protected static function makeModule( array $options = [] ) {
		return new ResourceLoaderTestModule( $options );
	}

	protected static function makeSampleModules() {
		$modules = [
			'test' => [],
			'test.top' => [ 'position' => 'top' ],
			'test.private.top' => [ 'group' => 'private', 'position' => 'top' ],
			'test.private.bottom' => [ 'group' => 'private', 'position' => 'bottom' ],

			'test.styles.pure' => [ 'type' => ResourceLoaderModule::LOAD_STYLES ],
			'test.styles.mixed' => [],
			'test.styles.noscript' => [ 'group' => 'noscript', 'type' => ResourceLoaderModule::LOAD_STYLES ],
			'test.styles.mixed.user' => [ 'group' => 'user' ],
			'test.styles.mixed.user.empty' => [ 'group' => 'user', 'isKnownEmpty' => true ],
			'test.styles.private' => [ 'group' => 'private', 'styles' => '.private{}' ],

			'test.scripts' => [],
			'test.scripts.top' => [ 'position' => 'top' ],
			'test.scripts.mixed.user' => [ 'group' => 'user' ],
			'test.scripts.mixed.user.empty' => [ 'group' => 'user', 'isKnownEmpty' => true ],
		];
		return array_map( function ( $options ) {
			return self::makeModule( $options );
		}, $modules );
	}

	/**
	 * @covers ResourceLoaderClientHtml::getDocumentAttributes
	 */
	public function testGetDocumentAttributes() {
		$client = new ResourceLoaderClientHtml( self::makeContext() );
		$this->assertInternalType( 'array', $client->getDocumentAttributes() );
	}

	/**
	 * @covers ResourceLoaderClientHtml::__construct
	 * @covers ResourceLoaderClientHtml::setModules
	 * @covers ResourceLoaderClientHtml::setModuleStyles
	 * @covers ResourceLoaderClientHtml::setModuleScripts
	 * @covers ResourceLoaderClientHtml::getData
	 * @covers ResourceLoaderClientHtml::getContext
	 */
	public function testGetData() {
		$context = self::makeContext();
		$context->getResourceLoader()->register( self::makeSampleModules() );

		$client = new ResourceLoaderClientHtml( $context );
		$client->setModules( [
			'test',
			'test.private.bottom',
			'test.private.top',
			'test.top',
			'test.unregistered',
		] );
		$client->setModuleStyles( [
			'test.styles.mixed',
			'test.styles.mixed.user.empty',
			'test.styles.private',
			'test.styles.pure',
			'test.unregistered.styles',
		] );
		$client->setModuleScripts( [
			'test.scripts',
			'test.scripts.mixed.user.empty',
			'test.scripts.top',
			'test.unregistered.scripts',
		] );

		$expected = [
			'states' => [
				'test.private.top' => 'loading',
				'test.private.bottom' => 'loading',
				'test.styles.pure' => 'ready',
				'test.styles.mixed.user.empty' => 'ready',
				'test.styles.private' => 'ready',
				'test.scripts' => 'loading',
				'test.scripts.top' => 'loading',
				'test.scripts.mixed.user.empty' => 'ready',
			],
			'general' => [
				'top' => [ 'test.top' ],
				'bottom' => [ 'test' ],
			],
			'styles' => [
				'test.styles.mixed',
				'test.styles.pure',
			],
			'scripts' => [
				'top' => [ 'test.scripts.top' ],
				'bottom' => [ 'test.scripts' ],
			],
			'embed' => [
				'styles' => [ 'test.styles.private' ],
				'general' => [
					'top' => [ 'test.private.top' ],
					'bottom' => [ 'test.private.bottom' ],
				],
			],
		];

		$access = TestingAccessWrapper::newFromObject( $client );
		$this->assertEquals( $expected, $access->getData() );
	}

	/**
	 * @covers ResourceLoaderClientHtml::setConfig
	 * @covers ResourceLoaderClientHtml::setExemptStates
	 * @covers ResourceLoaderClientHtml::getHeadHtml
	 * @covers ResourceLoaderClientHtml::makeLoad
	 */
	public function testGetHeadHtml() {
		$context = self::makeContext();
		$context->getResourceLoader()->register( self::makeSampleModules() );

		$client = new ResourceLoaderClientHtml( $context );
		$client->setConfig( [ 'key' => 'value' ] );
		$client->setModules( [
			'test.top',
			'test.private.top',
		] );
		$client->setModuleStyles( [
			'test.styles.pure',
			'test.styles.noscript',
			'test.styles.private',
		] );
		$client->setModuleScripts( [
			'test.scripts.top',
		] );
		$client->setExemptStates( [
			'test.exempt' => 'ready',
		] );

		// @codingStandardsIgnoreStart Generic.Files.LineLength
		$expected = '<script>document.documentElement.className = document.documentElement.className.replace( /(^|\s)client-nojs(\s|$)/, "$1client-js$2" );</script>' . "\n"
			. '<script>(window.RLQ=window.RLQ||[]).push(function(){'
			. 'mw.config.set({"key":"value"});'
			. 'mw.loader.state({"test.exempt":"ready","test.private.top":"loading","test.styles.pure":"ready","test.styles.noscript":"ready","test.styles.private":"ready","test.scripts.top":"loading"});'
			. 'mw.loader.implement("test.private.top",function($,jQuery,require,module){},{"css":[]});'
			. 'mw.loader.load(["test.top"]);'
			. 'mw.loader.load("/w/load.php?debug=false\u0026lang=nl\u0026modules=test.scripts.top\u0026only=scripts\u0026skin=fallback");'
			. '});</script>' . "\n"
			. '<noscript><link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.styles.noscript&amp;only=styles&amp;skin=fallback"/></noscript>' . "\n"
			. '<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.styles.pure&amp;only=styles&amp;skin=fallback"/>' . "\n"
			. '<style>.private{}</style>' . "\n"
			. '<script async="" src="/w/load.php?debug=false&amp;lang=nl&amp;modules=startup&amp;only=scripts&amp;skin=fallback"></script>';
		// @codingStandardsIgnoreEnd

		$this->assertEquals( $expected, $client->getHeadHtml() );
	}

	/**
	 * @covers ResourceLoaderClientHtml::getBodyHtml
	 * @covers ResourceLoaderClientHtml::getLoad
	 * @covers ResourceLoaderClientHtml::makeLoad
	 * @covers ResourceLoaderClientHtml::makeContext
	 */
	public function testGetBodyHtml() {
		$context = self::makeContext();
		$context->getResourceLoader()->register( self::makeSampleModules() );

		$client = new ResourceLoaderClientHtml( $context );
		$client->setConfig( [ 'key' => 'value' ] );
		$client->setModules( [
			'test',
			'test.private.bottom',
		] );
		$client->setModuleScripts( [
			'test.scripts',
			'test.scripts.mixed.user',
		] );

		// @codingStandardsIgnoreStart Generic.Files.LineLength
		$expected = '<script>(window.RLQ=window.RLQ||[]).push(function(){'
			. 'mw.loader.implement("test.private.bottom",function($,jQuery,require,module){},{"css":[]});'
			. 'mw.loader.load("/w/load.php?debug=false\u0026lang=nl\u0026modules=test.scripts\u0026only=scripts\u0026skin=fallback");'
			. 'mw.loader.load("/w/load.php?debug=false\u0026lang=nl\u0026modules=test.scripts.mixed.user\u0026only=scripts\u0026skin=fallback\u0026user=Example\u0026version=0a56zyi");'
			. 'mw.loader.load(["test"]);'
			. '});</script>';
		// @codingStandardsIgnoreEnd

		$this->assertEquals( $expected, $client->getBodyHtml() );
	}

	/**
	 * @covers ResourceLoaderClientHtml::makeLoad
	 */
	public function testMakeLoadUnknown() {
		$actual = ResourceLoaderClientHtml::makeLoad(
			self::makeContext(),
			[ 'test.unknown' ],
			ResourceLoaderModule::TYPE_STYLES
		);

		$this->assertEquals( '', (string)$actual );
	}

	/**
	 * @covers ResourceLoaderClientHtml::makeLoad
	 */
	public function testMakeLoadDebug() {
		$context = self::makeContext( [ 'debug' => true ] );
		$context->getResourceLoader()->register( self::makeSampleModules() );

		$actual = ResourceLoaderClientHtml::makeLoad(
			$context,
			[ 'test.styles.pure', 'test.styles.mixed' ],
			ResourceLoaderModule::TYPE_STYLES
		);

		// @codingStandardsIgnoreStart Generic.Files.LineLength
		$expected = '<link rel="stylesheet" href="/w/load.php?debug=true&amp;lang=nl&amp;modules=test.styles.pure&amp;only=styles&amp;skin=fallback"/>' . "\n"
			. '<link rel="stylesheet" href="/w/load.php?debug=true&amp;lang=nl&amp;modules=test.styles.mixed&amp;only=styles&amp;skin=fallback"/>';
		// @codingStandardsIgnoreEnd

		$this->assertEquals( $expected, (string)$actual );
	}
}
