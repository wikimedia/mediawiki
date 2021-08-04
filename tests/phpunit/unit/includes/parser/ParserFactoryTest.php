<?php

use MediaWiki\BadFileLookup;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserOptionsLookup;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ParserFactory
 */
class ParserFactoryTest extends MediaWikiUnitTestCase {
	private function createFactory() {
		$options = $this->getMockBuilder( ServiceOptions::class )
		->disableOriginalConstructor()
		->onlyMethods( [ 'assertRequiredOptions', 'get' ] )->getMock();

		$options->expects( $this->never() )
			->method( $this->anythingBut( 'assertRequiredOptions', 'get' ) );

		$this->assertInstanceOf( ServiceOptions::class, $options );

		// Stub out a MagicWordFactory so the Parser can initialize its
		// function hooks when it is created.
		$mwFactory = $this->getMockBuilder( MagicWordFactory::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'get', 'getVariableIDs' ] )
			->getMock();
		$mwFactory
			->method( 'get' )->will( $this->returnCallback( function ( $arg ) {
				$mw = $this->getMockBuilder( MagicWord::class )
				->disableOriginalConstructor()
				->onlyMethods( [ 'getSynonyms' ] )
				->getMock();
				$mw->method( 'getSynonyms' )->willReturn( [] );
				return $mw;
			} ) );
		$mwFactory
			->method( 'getVariableIDs' )->willReturn( [] );

		$factory = new ParserFactory(
			$options,
			$mwFactory,
			$this->createNoOpMock( Language::class ),
			"",
			$this->createNoOpMock( SpecialPageFactory::class ),
			$this->createNoOpMock( LinkRendererFactory::class ),
			$this->createNoOpMock( NamespaceInfo::class ),
			new TestLogger(),
			$this->createNoOpMock( BadFileLookup::class ),
			$this->createNoOpMock( LanguageConverterFactory::class ),
			$this->createHookContainer(),
			$this->createNoOpMock( TidyDriverBase::class ),
			$this->createNoOpMock( WANObjectCache::class ),
			$this->createNoOpMock( UserOptionsLookup::class ),
			$this->createNoOpMock( UserFactory::class ),
			$this->createNoOpMock( TitleFormatter::class ),
			$this->createNoOpMock( HttpRequestFactory::class )
		);
		return $factory;
	}

	/**
	 * @covers ParserFactory::__construct
	 */
	public function testConstructor() {
		$factory = $this->createFactory();
		$this->assertNotNull( $factory, "Factory should be created correctly" );
	}

	/**
	 * @covers ParserFactory::create
	 */
	public function testCreate() {
		$factory = $this->createFactory();
		$parser = $factory->create();
		$this->assertNotNull( $factory, "Factory should be created correctly" );
		$this->assertNotNull( $parser, "Factory should create parser correctly" );
		$this->assertInstanceOf( Parser::class, $parser );

		$parserWrapper = TestingAccessWrapper::newFromObject( $parser );
		$factoryWrapper = TestingAccessWrapper::newFromObject( $factory );
		$this->assertSame(
			$factoryWrapper->languageConverterFactory, $parserWrapper->languageConverterFactory
		);
		$this->assertSame(
			$factory, $parserWrapper->factory
		);
	}
}
