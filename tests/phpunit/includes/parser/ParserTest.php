<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use ReflectionObject;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @covers \MediaWiki\Parser\Parser
 */
class ParserTest extends MediaWikiIntegrationTestCase {
	private function createConstructorArguments(): array {
		$options = new ServiceOptions(
			Parser::CONSTRUCTOR_OPTIONS,
			array_fill_keys( Parser::CONSTRUCTOR_OPTIONS, null )
		);

		$contLang = $this->createMock( Language::class );
		$mw = new MagicWord( null, [], true, $contLang );

		// Stub out a MagicWordFactory so the Parser can initialize its
		// function hooks when it is created.
		$mwFactory = $this->createNoOpMock( MagicWordFactory::class,
			[ 'get', 'getVariableIDs', 'getSubstArray', 'newArray' ] );
		$mwFactory->method( 'get' )->willReturn( $mw );
		$mwFactory->method( 'getVariableIDs' )->willReturn( [] );

		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'validProtocols' ] );
		$urlUtils->method( 'validProtocols' )->willReturn( '' );

		return [
			$options,
			$mwFactory,
			$contLang,
			$this->createNoOpMock( ParserFactory::class ),
			$urlUtils,
			$this->createNoOpMock( SpecialPageFactory::class ),
			$this->createNoOpMock( LinkRendererFactory::class ),
			$this->createNoOpMock( NamespaceInfo::class ),
			new NullLogger(),
			$this->createNoOpMock( BadFileLookup::class ),
			$this->createNoOpMock( LanguageConverterFactory::class, [ 'isConversionDisabled' ] ),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createNoOpMock( HookContainer::class, [ 'run' ] ),
			$this->createNoOpMock( TidyDriverBase::class ),
			$this->createNoOpMock( WANObjectCache::class ),
			$this->createNoOpMock( UserOptionsLookup::class ),
			$this->createNoOpMock( UserFactory::class ),
			$this->createNoOpMock( TitleFormatter::class ),
			$this->createNoOpMock( HttpRequestFactory::class ),
			$this->createNoOpMock( TrackingCategories::class ),
			$this->createNoOpMock( SignatureValidatorFactory::class ),
			$this->createNoOpMock( UserNameUtils::class )
		];
	}

	public function testConstructorArguments() {
		$args = $this->createConstructorArguments();

		// Fool Parser into thinking we are constructing via a ParserFactory
		ParserFactory::$inParserFactory++;
		try {
			$parser = new Parser( ...$args );
		} finally {
			ParserFactory::$inParserFactory--;
		}

		$refObject = new ReflectionObject( $parser );
		foreach ( $refObject->getProperties() as $prop ) {
			$prop->setAccessible( true );
			foreach ( $args as $idx => $mockTest ) {
				if ( $prop->isInitialized( $parser ) && $prop->getValue( $parser ) === $mockTest ) {
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

	private function newParser(): Parser {
		$args = $this->createConstructorArguments();
		ParserFactory::$inParserFactory++;
		try {
			return new Parser( ...$args );
		} finally {
			ParserFactory::$inParserFactory--;
		}
	}

	public function testSetPage() {
		$parser = $this->newParser();

		$page = PageReferenceValue::localReference( NS_SPECIAL, 'Dummy' );
		$parser->setPage( $page );
		$this->assertTrue( $page->isSamePageAs( $parser->getPage() ) );
		$this->assertTrue( $page->isSamePageAs( $parser->getTitle() ) );
		$this->assertInstanceOf( Title::class, $parser->getTitle() );
	}

	public function testSetTitle() {
		$parser = $this->newParser();

		$title = Title::makeTitle( NS_SPECIAL, 'Dummy' );
		$parser->setTitle( $title );
		$this->assertSame( $title, $parser->getTitle() );
		$this->assertTrue( $title->isSamePageAs( $parser->getPage() ) );
	}
}
