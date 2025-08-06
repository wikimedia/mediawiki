<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use TestLogger;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Parser\ParserFactory
 */
class ParserFactoryTest extends MediaWikiUnitTestCase {

	private function createFactory(): ParserFactory {
		$options = new ServiceOptions(
			Parser::CONSTRUCTOR_OPTIONS,
			array_fill_keys( Parser::CONSTRUCTOR_OPTIONS, null )
		);

		$contLang = $this->createNoOpMock( Language::class );
		$mw = new MagicWord( null, [], true, $contLang );

		// Stub out a MagicWordFactory so the Parser can initialize its
		// function hooks when it is created.
		$mwFactory = $this->createNoOpMock( MagicWordFactory::class,
			[ 'get', 'getVariableIDs', 'getSubstArray', 'newArray' ] );
		$mwFactory->method( 'get' )->willReturn( $mw );
		$mwFactory->method( 'getVariableIDs' )->willReturn( [] );

		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'validProtocols' ] );
		$urlUtils->method( 'validProtocols' )->willReturn( '' );

		$factory = new ParserFactory(
			$options,
			$mwFactory,
			$contLang,
			$urlUtils,
			$this->createNoOpMock( SpecialPageFactory::class ),
			$this->createNoOpMock( LinkRendererFactory::class ),
			$this->createNoOpMock( NamespaceInfo::class ),
			new TestLogger(),
			$this->createNoOpMock( BadFileLookup::class ),
			$this->createNoOpMock( LanguageConverterFactory::class, [ 'isConversionDisabled' ] ),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createHookContainer(),
			$this->createNoOpMock( TidyDriverBase::class ),
			$this->createNoOpMock( WANObjectCache::class ),
			$this->createNoOpMock( UserOptionsLookup::class ),
			$this->createNoOpMock( UserFactory::class ),
			$this->createNoOpMock( TitleFormatter::class ),
			$this->createNoOpMock( HttpRequestFactory::class ),
			$this->createNoOpMock( TrackingCategories::class ),
			$this->createNoOpMock( SignatureValidatorFactory::class ),
			$this->createNoOpMock( UserNameUtils::class )
		);
		return $factory;
	}

	public function testCreate() {
		$factory = $this->createFactory();
		$parser = $factory->create();

		$parserWrapper = TestingAccessWrapper::newFromObject( $parser );
		$factoryWrapper = TestingAccessWrapper::newFromObject( $factory );
		$this->assertSame(
			$factoryWrapper->languageConverterFactory, $parserWrapper->languageConverterFactory
		);
	}
}
