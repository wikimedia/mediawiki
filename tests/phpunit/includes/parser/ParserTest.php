<?php

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\Title\Title;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;

/**
 * @covers Parser::__construct
 */
class ParserTest extends MediaWikiIntegrationTestCase {
	/**
	 * Helper method to create mocks
	 * @return array
	 */
	private function createConstructorArguments() {
		$options = new ServiceOptions(
			Parser::CONSTRUCTOR_OPTIONS,
			array_combine(
				Parser::CONSTRUCTOR_OPTIONS,
				array_fill( 0, count( Parser::CONSTRUCTOR_OPTIONS ), null )
			)
		);

		// Stub out a MagicWordFactory so the Parser can initialize its
		// function hooks when it is created.
		$mwFactory = $this->getMockBuilder( MagicWordFactory::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'get', 'getVariableIDs' ] )
			->getMock();
		$mwFactory
			->method( 'get' )->willReturnCallback( function ( $arg ) {
				$mw = $this->getMockBuilder( MagicWord::class )
					->disableOriginalConstructor()
					->onlyMethods( [ 'getSynonyms' ] )
					->getMock();
				$mw->method( 'getSynonyms' )->willReturn( [] );
				return $mw;
			} );
		$mwFactory
			->method( 'getVariableIDs' )->willReturn( [] );

		return [
			$options,
			$mwFactory,
			$this->createMock( Language::class ),
			$this->createMock( ParserFactory::class ),
			$this->createMock( UrlUtils::class ),
			$this->createMock( MediaWiki\SpecialPage\SpecialPageFactory::class ),
			$this->createMock( MediaWiki\Linker\LinkRendererFactory::class ),
			$this->createMock( NamespaceInfo::class ),
			new Psr\Log\NullLogger(),
			$this->createMock( BadFileLookup::class ),
			$this->createMock( MediaWiki\Languages\LanguageConverterFactory::class ),
			$this->createMock( MediaWiki\HookContainer\HookContainer::class ),
			$this->createMock( MediaWiki\Tidy\TidyDriverBase::class ),
			$this->createMock( WANObjectCache::class ),
			$this->createMock( MediaWiki\User\UserOptionsLookup::class ),
			$this->createMock( MediaWiki\User\UserFactory::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( HttpRequestFactory::class ),
			$this->createMock( TrackingCategories::class ),
			$this->createMock( SignatureValidatorFactory::class ),
			$this->createMock( UserNameUtils::class )
		];
	}

	/**
	 * @covers Parser::__construct
	 */
	public function testConstructorArguments() {
		$args = $this->createConstructorArguments();

		// Fool Parser into thinking we are constructing via a ParserFactory
		ParserFactory::$inParserFactory += 1;
		try {
			$parser = new Parser( ...$args );
		} finally {
			ParserFactory::$inParserFactory -= 1;
		}

		$refObject = new ReflectionObject( $parser );
		foreach ( $refObject->getProperties() as $prop ) {
			$prop->setAccessible( true );
			foreach ( $args as $idx => $mockTest ) {
				if ( $prop->getValue( $parser ) === $mockTest ) {
					unset( $args[$idx] );
				}
			}
		}
		// The WANObjectCache gets set on the Preprocessor, not the
		// Parser.
		$preproc = $parser->getPreprocessor();
		$refObject = new ReflectionObject( $preproc );
		foreach ( $refObject->getProperties() as $prop ) {
			$prop->setAccessible( true );
			foreach ( $args as $idx => $mockTest ) {
				if ( $prop->getValue( $preproc ) === $mockTest ) {
					unset( $args[$idx] );
				}
			}
		}

		$this->assertSame( [], $args, 'Not all arguments to the Parser constructor were ' .
			'found on the Parser object' );
	}

	/**
	 * @return Parser
	 */
	private function newParser() {
		$args = $this->createConstructorArguments();
		ParserFactory::$inParserFactory += 1;
		try {
			return new Parser( ...$args );
		} finally {
			ParserFactory::$inParserFactory -= 1;
		}
	}

	/**
	 * @covers Parser::setPage
	 * @covers Parser::getPage
	 * @covers Parser::getTitle
	 */
	public function testSetPage() {
		$parser = $this->newParser();

		$page = new PageReferenceValue( NS_SPECIAL, 'Dummy', PageReference::LOCAL );
		$parser->setPage( $page );
		$this->assertTrue( $page->isSamePageAs( $parser->getPage() ) );
		$this->assertTrue( $page->isSamePageAs( $parser->getTitle() ) );
		$this->assertInstanceOf( Title::class, $parser->getTitle() );
	}

	/**
	 * @covers Parser::setPage
	 * @covers Parser::getPage
	 * @covers Parser::getTitle
	 */
	public function testSetTitle() {
		$parser = $this->newParser();

		$title = Title::makeTitle( NS_SPECIAL, 'Dummy' );
		$parser->setTitle( $title );
		$this->assertSame( $title, $parser->getTitle() );
		$this->assertTrue( $title->isSamePageAs( $parser->getPage() ) );
	}
}
