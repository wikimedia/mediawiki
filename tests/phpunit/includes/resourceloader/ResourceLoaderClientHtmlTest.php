<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 */
class ResourceLoaderClientHtmlTest extends PHPUnit_Framework_TestCase {

	protected static function expandVariables( $text ) {
		return strtr( $text, [
			'{blankVer}' => ResourceLoaderTestCase::BLANK_VERSION
		] );
	}

	protected static function makeContext( $extraQuery = [] ) {
		$conf = new HashConfig( [
			'ResourceLoaderSources' => [],
			'ResourceModuleSkinStyles' => [],
			'ResourceModules' => [],
			'EnableJavaScriptTest' => false,
			'ResourceLoaderDebug' => false,
			'LoadScript' => '/w/load.php',
		] );
		return new ResourceLoaderContext(
			new ResourceLoader( $conf ),
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
			'test.shouldembed' => [ 'shouldEmbed' => true ],

			'test.styles.pure' => [ 'type' => ResourceLoaderModule::LOAD_STYLES ],
			'test.styles.mixed' => [],
			'test.styles.noscript' => [
				'type' => ResourceLoaderModule::LOAD_STYLES,
				'group' => 'noscript',
			],
			'test.styles.user' => [
				'type' => ResourceLoaderModule::LOAD_STYLES,
				'group' => 'user',
			],
			'test.styles.user.empty' => [
				'type' => ResourceLoaderModule::LOAD_STYLES,
				'group' => 'user',
				'isKnownEmpty' => true,
			],
			'test.styles.private' => [
				'type' => ResourceLoaderModule::LOAD_STYLES,
				'group' => 'private',
				'styles' => '.private{}',
			],
			'test.styles.shouldembed' => [
				'type' => ResourceLoaderModule::LOAD_STYLES,
				'shouldEmbed' => true,
				'styles' => '.shouldembed{}',
			],

			'test.scripts' => [],
			'test.scripts.top' => [ 'position' => 'top' ],
			'test.scripts.user' => [ 'group' => 'user' ],
			'test.scripts.user.empty' => [ 'group' => 'user', 'isKnownEmpty' => true ],
			'test.scripts.raw' => [ 'isRaw' => true ],
			'test.scripts.shouldembed' => [ 'shouldEmbed' => true ],

			'test.ordering.a' => [ 'shouldEmbed' => false ],
			'test.ordering.b' => [ 'shouldEmbed' => false ],
			'test.ordering.c' => [ 'shouldEmbed' => true, 'styles' => '.orderingC{}' ],
			'test.ordering.d' => [ 'shouldEmbed' => true, 'styles' => '.orderingD{}' ],
			'test.ordering.e' => [ 'shouldEmbed' => false ],
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
			'test.shouldembed',
			'test.unregistered',
		] );
		$client->setModuleStyles( [
			'test.styles.mixed',
			'test.styles.user.empty',
			'test.styles.private',
			'test.styles.pure',
			'test.styles.shouldembed',
			'test.unregistered.styles',
		] );
		$client->setModuleScripts( [
			'test.scripts',
			'test.scripts.user.empty',
			'test.scripts.top',
			'test.scripts.shouldembed',
			'test.unregistered.scripts',
		] );

		$expected = [
			'states' => [
				'test.private.top' => 'loading',
				'test.private.bottom' => 'loading',
				'test.shouldembed' => 'loading',
				'test.styles.pure' => 'ready',
				'test.styles.user.empty' => 'ready',
				'test.styles.private' => 'ready',
				'test.styles.shouldembed' => 'ready',
				'test.scripts' => 'loading',
				'test.scripts.top' => 'loading',
				'test.scripts.user.empty' => 'ready',
				'test.scripts.shouldembed' => 'loading',
			],
			'general' => [
				'test',
				'test.top',
			],
			'styles' => [
				'test.styles.pure',
			],
			'scripts' => [
				'test.scripts',
				'test.scripts.top',
				'test.scripts.shouldembed',
			],
			'embed' => [
				'styles' => [ 'test.styles.private', 'test.styles.shouldembed' ],
				'general' => [
					'test.private.bottom',
					'test.private.top',
					'test.shouldembed',
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
	 * @covers ResourceLoaderClientHtml::getLoad
	 * @covers ResourceLoader::makeLoaderStateScript
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
			. 'mw.loader.state({"test.exempt":"ready","test.private.top":"loading","test.styles.pure":"ready","test.styles.private":"ready","test.scripts.top":"loading"});'
			. 'mw.loader.implement("test.private.top@{blankVer}",function($,jQuery,require,module){},{"css":[]});'
			. 'mw.loader.load(["test.top"]);'
			. 'mw.loader.load("/w/load.php?debug=false\u0026lang=nl\u0026modules=test.scripts.top\u0026only=scripts\u0026skin=fallback");'
			. '});</script>' . "\n"
			. '<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.styles.pure&amp;only=styles&amp;skin=fallback"/>' . "\n"
			. '<style>.private{}</style>' . "\n"
			. '<script async="" src="/w/load.php?debug=false&amp;lang=nl&amp;modules=startup&amp;only=scripts&amp;skin=fallback"></script>';
		// @codingStandardsIgnoreEnd
		$expected = self::expandVariables( $expected );

		$this->assertEquals( $expected, $client->getHeadHtml() );
	}

	/**
	 * @covers ResourceLoaderClientHtml::getBodyHtml
	 * @covers ResourceLoaderClientHtml::getLoad
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
		] );

		$expected = '';
		$expected = self::expandVariables( $expected );

		$this->assertEquals( $expected, $client->getBodyHtml() );
	}

	public static function provideMakeLoad() {
		return [
			// @codingStandardsIgnoreStart Generic.Files.LineLength
			[
				'context' => [],
				'modules' => [ 'test.unknown' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' => '',
			],
			[
				'context' => [],
				'modules' => [ 'test.styles.private' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' => '<style>.private{}</style>',
			],
			[
				'context' => [],
				'modules' => [ 'test.private.top' ],
				'only' => ResourceLoaderModule::TYPE_COMBINED,
				'output' => '<script>(window.RLQ=window.RLQ||[]).push(function(){mw.loader.implement("test.private.top@{blankVer}",function($,jQuery,require,module){},{"css":[]});});</script>',
			],
			[
				'context' => [],
				// Eg. startup module
				'modules' => [ 'test.scripts.raw' ],
				'only' => ResourceLoaderModule::TYPE_SCRIPTS,
				'output' => '<script async="" src="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.scripts.raw&amp;only=scripts&amp;skin=fallback"></script>',
			],
			[
				'context' => [],
				'modules' => [ 'test.scripts.user' ],
				'only' => ResourceLoaderModule::TYPE_SCRIPTS,
				'output' => '<script>(window.RLQ=window.RLQ||[]).push(function(){mw.loader.load("/w/load.php?debug=false\u0026lang=nl\u0026modules=test.scripts.user\u0026only=scripts\u0026skin=fallback\u0026user=Example\u0026version=0a56zyi");});</script>',
			],
			[
				'context' => [ 'debug' => true ],
				'modules' => [ 'test.styles.pure', 'test.styles.mixed' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' => '<link rel="stylesheet" href="/w/load.php?debug=true&amp;lang=nl&amp;modules=test.styles.mixed&amp;only=styles&amp;skin=fallback"/>' . "\n"
					. '<link rel="stylesheet" href="/w/load.php?debug=true&amp;lang=nl&amp;modules=test.styles.pure&amp;only=styles&amp;skin=fallback"/>',
			],
			[
				'context' => [ 'debug' => false ],
				'modules' => [ 'test.styles.pure', 'test.styles.mixed' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' => '<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.styles.mixed%2Cpure&amp;only=styles&amp;skin=fallback"/>',
			],
			[
				'context' => [],
				'modules' => [ 'test.styles.noscript' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' => '<noscript><link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.styles.noscript&amp;only=styles&amp;skin=fallback"/></noscript>',
			],
			[
				'context' => [],
				'modules' => [ 'test.shouldembed' ],
				'only' => ResourceLoaderModule::TYPE_COMBINED,
				'output' => '<script>(window.RLQ=window.RLQ||[]).push(function(){mw.loader.implement("test.shouldembed@09p30q0",function($,jQuery,require,module){},{"css":[]});});</script>',
			],
			[
				'context' => [],
				'modules' => [ 'test.styles.shouldembed' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' => '<style>.shouldembed{}</style>',
			],
			[
				'context' => [],
				'modules' => [ 'test.scripts.shouldembed' ],
				'only' => ResourceLoaderModule::TYPE_SCRIPTS,
				'output' => '<script>(window.RLQ=window.RLQ||[]).push(function(){mw.loader.state({"test.scripts.shouldembed":"ready"});});</script>',
			],
			[
				'context' => [],
				'modules' => [ 'test', 'test.shouldembed' ],
				'only' => ResourceLoaderModule::TYPE_COMBINED,
				'output' => '<script>(window.RLQ=window.RLQ||[]).push(function(){mw.loader.load("/w/load.php?debug=false\u0026lang=nl\u0026modules=test\u0026skin=fallback");mw.loader.implement("test.shouldembed@09p30q0",function($,jQuery,require,module){},{"css":[]});});</script>',
			],
			[
				'context' => [],
				'modules' => [ 'test.styles.pure', 'test.styles.shouldembed' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' =>
					'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.styles.pure&amp;only=styles&amp;skin=fallback"/>' . "\n"
					. '<style>.shouldembed{}</style>'
			],
			[
				'context' => [],
				'modules' => [ 'test.ordering.a', 'test.ordering.e', 'test.ordering.b', 'test.ordering.d', 'test.ordering.c' ],
				'only' => ResourceLoaderModule::TYPE_STYLES,
				'output' =>
					'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.ordering.a%2Cb&amp;only=styles&amp;skin=fallback"/>' . "\n"
					. '<style>.orderingC{}.orderingD{}</style>' . "\n"
					. '<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=nl&amp;modules=test.ordering.e&amp;only=styles&amp;skin=fallback"/>'
			],
			// @codingStandardsIgnoreEnd
		];
	}

	/**
	 * @dataProvider provideMakeLoad
	 * @covers ResourceLoaderClientHtml::makeLoad
	 * @covers ResourceLoaderClientHtml::makeContext
	 * @covers ResourceLoader::makeModuleResponse
	 * @covers ResourceLoaderModule::getModuleContent
	 * @covers ResourceLoader::getCombinedVersion
	 * @covers ResourceLoader::createLoaderURL
	 * @covers ResourceLoader::createLoaderQuery
	 * @covers ResourceLoader::makeLoaderQuery
	 * @covers ResourceLoader::makeInlineScript
	 */
	public function testMakeLoad( array $extraQuery, array $modules, $type, $expected ) {
		$context = self::makeContext( $extraQuery );
		$context->getResourceLoader()->register( self::makeSampleModules() );
		$actual = ResourceLoaderClientHtml::makeLoad( $context, $modules, $type );
		$expected = self::expandVariables( $expected );
		$this->assertEquals( $expected, (string)$actual );
	}
}
