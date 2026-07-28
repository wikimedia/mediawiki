<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Specials\SpecialVersion;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Title\Title;
use MediaWiki\Utils\ExtensionInfo;
use MediaWiki\Utils\GitInfo;
use Symfony\Component\Yaml\Yaml;
use Wikimedia\Composer\ComposerInstalled;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Ext\DOMUtils;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialVersion
 * @group Database
 */
class SpecialVersionTest extends SpecialPageTestBase {

	public function testViewForSoftwareSection(): void {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		ConvertibleTimestamp::setFakeTime( '20260504030201' );
		// If using the real "all things", then the test runs slow
		$this->installMockExtensionRegistry( [] );

		[ $html ] = $this->executeSpecialPage();
		$htmlAsNode = DOMUtils::parseHTML( $html );

		$versionLicenseHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-license',
			true
		);
		$this->assertStringContainsString( '(version-license)', $versionLicenseHeaderHtml );

		$versionPoweredByCreditsHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-poweredby-credits',
			true
		);
		$this->assertStringContainsString( '(version-poweredby-credits: 2026', $versionPoweredByCreditsHtml );
		$this->assertStringContainsString(
			'Special:Version/Credits',
			$versionPoweredByCreditsHtml,
			'Missing other credits link'
		);
		$this->assertStringContainsString( '(version-poweredby-others)', $versionPoweredByCreditsHtml );
		$this->assertStringContainsString(
			'https://translatewiki.net/wiki/Translating:MediaWiki/Credits',
			$versionPoweredByCreditsHtml,
			'Missing translatewiki.net credit link'
		);
		$this->assertStringContainsString( '(version-poweredby-translators)', $versionPoweredByCreditsHtml );

		$versionLicenseInfoHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'.mw-version-license-info',
			true
		);
		$this->assertStringContainsString( '(version-license-info)', $versionLicenseInfoHtml );

		$versionSoftwareHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-software',
			true
		);
		$this->assertStringContainsString( '(version-software)', $versionSoftwareHeaderHtml );

		$versionSoftwareTableHtml = $this->assertSelectorMatchesOneElementInNode( $htmlAsNode, '#sv-software', true );
		$this->assertStringContainsString( '(version-software-product)', $versionSoftwareTableHtml );
		$this->assertStringContainsString( '(version-software-version)', $versionSoftwareTableHtml );
		$this->assertStringContainsString( MW_VERSION, $versionSoftwareTableHtml, 'Missing MediaWiki version' );
		$this->assertStringContainsString( PHP_VERSION, $versionSoftwareTableHtml, 'Missing PHP version' );
		$this->assertStringContainsString( INTL_ICU_VERSION, $versionSoftwareTableHtml, 'Missing ICU version' );
		$this->assertStringContainsString(
			$this->getDb()->getServerInfo(),
			$versionSoftwareTableHtml,
			'Missing DB version'
		);
	}

	public function testViewForEntrypointSection(): void {
		$this->installMockExtensionRegistry( [] );

		[ $html ] = $this->executeSpecialPage();
		$htmlAsNode = DOMUtils::parseHTML( $html );

		$versionEntryPointsHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-entrypoints',
			true
		);
		$this->assertStringContainsString( '(version-entrypoints)', $versionEntryPointsHeaderHtml );

		$versionEntryPointsTableHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-entrypoints-table',
			true
		);
		$this->assertStringContainsString( '(version-entrypoints-header-entrypoint)', $versionEntryPointsTableHtml );
		$this->assertStringContainsString( '(version-entrypoints-header-url)', $versionEntryPointsTableHtml );

		$expectedEntryPoints = [
			'(version-entrypoints-articlepath)',
			'(version-entrypoints-scriptpath)',
			'(version-entrypoints-index-php)',
			'(version-entrypoints-api-php)',
			'(version-entrypoints-rest-php)',
		];
		foreach ( $expectedEntryPoints as $expectedEntryPoint ) {
			$this->assertStringContainsString(
				$expectedEntryPoint,
				$versionEntryPointsTableHtml,
				'Missing entry point in entrypoints table'
			);
		}
	}

	public function testViewForSkinsAndExtensionSections(): void {
		RequestContext::getMain()->setTitle( Title::makeTitle( NS_SPECIAL, 'Version' ) );

		// Create several fake extensions and some skins so that we can cover the different branches.
		// Doing this means we can also avoid the test running very slow on wikis with many extensions installed.
		$realExtensionRegistry = $this->getServiceContainer()->getExtensionRegistry();
		$realAllThings = $realExtensionRegistry->getAllThings();

		$componentWithLicenseDefinedInFileDir = $this->getNewTempDirectory();
		file_put_contents( $componentWithLicenseDefinedInFileDir . '/LICENSE', 'test' );

		$componentWithCreditsDefinedInFileDir = $this->getNewTempDirectory();
		file_put_contents( $componentWithCreditsDefinedInFileDir . '/CREDITS', 'test' );

		$mockAllThings = [
			'ComponentWithoutUrl' => [
				'path' => $this->getNewTempDirectory() . '/extension.json',
				'type' => 'antispam',
				'author' => [ 'ComponentWithoutUrlAuthor', 'TestAuthor' ],
				'descriptionmsg' => 'component-without-git-repo-desc',
				'license-name' => 'GPL-2.0-or-later',
				'name' => 'ComponentWithoutUrl',
				'version' => '1.6.0',
			],
			'ComponentWithLicenseDefinedInFile' => [
				'path' => $componentWithLicenseDefinedInFileDir . '/extension.json',
				'type' => 'antispam',
				'author' => [ '...' ],
				'descriptionmsg' => 'component-with-license-defined-in-file-desc',
				'name' => 'ComponentWithLicenseDefinedInFile',
				'url' => 'https://example.com',
				'version' => '1.6.0',
			],
			'ComponentWithNonLocalisedDescription' => [
				'path' => $this->getNewTempDirectory() . '/extension.json',
				'type' => 'other',
				'author' => [ 'ComponentWithNonLocalisedDescriptionAuthor', '...' ],
				'description' => 'Test description',
				'name' => 'ComponentWithNonLocalisedDescription',
				'license-name' => 'GNU General Public Licence 2.0',
				'url' => 'https://example.com',
				'version' => '1.6.1',
			],
			'ComponentWithCreditsDefinedInFile' => [
				'path' => $componentWithCreditsDefinedInFileDir . '/extension.json',
				'type' => 'unknown-type',
				'author' => [ '[https://example.org/authors.txt ...]' ],
				'name' => 'ComponentWithCreditsDefinedInFile',
				'url' => 'https://example.org',
				'version' => '1.2.3a',
			],
			'TestSkin' => [
				'path' => $this->getNewTempDirectory() . '/extension.json',
				'type' => 'skin',
				'author' => [],
				'name' => 'TestSkin',
				'version' => '',
			],
		];

		// Add a real component for better end to end testing. In CI this should the Vector skin.
		if ( array_key_exists( 'Vector', $realAllThings ) ) {
			$mockAllThings['Vector'] = $realAllThings['Vector'];
		} elseif ( count( $realAllThings ) ) {
			$firstThingKey = array_key_first( $realAllThings );
			$mockAllThings[$firstThingKey] = $realAllThings[$firstThingKey];
		}

		$this->installMockExtensionRegistry( $mockAllThings );

		[ $html ] = $this->executeSpecialPage();
		$htmlAsNode = DOMUtils::parseHTML( $html );

		$versionSkinsHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-skin',
			true
		);
		$this->assertStringContainsString( '(version-skins)', $versionSkinsHeaderHtml );

		$versionSkinsTableNode = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#sv-credits-skin.mw-installed-software'
		);
		$versionSkinsTableHtml = DOMCompat::getInnerHTML( $versionSkinsTableNode );

		$expectedVersionSkinsTableHeaderMessages = [
			'(version-skin-colheader-name)',
			'(version-ext-colheader-version)',
			'(version-ext-colheader-license)',
			'(version-ext-colheader-description)',
			'(version-ext-colheader-credits)',
		];
		foreach ( $expectedVersionSkinsTableHeaderMessages as $expectedMessage ) {
			$this->assertStringContainsString(
				$expectedMessage,
				$versionSkinsTableHtml,
				'Missing skin table header message'
			);
		}

		$loadedComponents = $this->getServiceContainer()->getExtensionRegistry()->getAllThings();

		$loadedSkins = array_filter( $loadedComponents, static fn ( $component ) => $component['type'] === 'skin' );

		foreach ( $loadedSkins as $loadedSkinDefinition ) {
			$this->verifyExtensionOrSkinRow( $versionSkinsTableNode, 'skin', $loadedSkinDefinition );
		}

		$versionExtensionsHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-ext',
			true
		);
		$this->assertStringContainsString( '(version-extensions)', $versionExtensionsHeaderHtml );

		$loadedExtensions = array_filter(
			$loadedComponents,
			static fn ( $component ) => $component['type'] !== 'skin'
		);

		$sectionNodeCache = [];
		foreach ( $loadedExtensions as $loadedExtensionDefinition ) {
			$expectedSection = $loadedExtensionDefinition['type'];
			if ( !array_key_exists( $expectedSection, SpecialVersion::getExtensionTypes() ) ) {
				// Some extensions may use an unregistered type, which gets defaulted to "other"
				$expectedSection = 'other';
			}

			// Verify the table headers each time that we are checking a new type of extension
			// by caching the HTML of the section (and verifying the headers on a cache miss)
			if ( !isset( $sectionNodeCache[$expectedSection] ) ) {
				$sectionNodeCache[$expectedSection] = $this->assertSelectorMatchesOneElementInNode(
					$htmlAsNode,
					'.mw-installed-software#sv-credits-' . $expectedSection
				);
				$sectionHtml = DOMCompat::getInnerHTML( $sectionNodeCache[$expectedSection] );

				$expectedVersionExtensionsTableHeaderMessages = [
					'(version-ext-colheader-name)',
					'(version-ext-colheader-version)',
					'(version-ext-colheader-license)',
					'(version-ext-colheader-description)',
					'(version-ext-colheader-credits)',
				];
				foreach ( $expectedVersionExtensionsTableHeaderMessages as $expectedMessage ) {
					$this->assertStringContainsString(
						$expectedMessage,
						$sectionHtml,
						'Missing extension table header message'
					);
				}
			}
			$this->verifyExtensionOrSkinRow( $sectionNodeCache[$expectedSection], $expectedSection, $loadedExtensionDefinition );
		}
	}

	private function verifyExtensionOrSkinRow(
		Document|Element $node,
		string $expectedType,
		array $componentDefinition
	): void {
		$tableRowNode = $this->assertSelectorMatchesOneElementInNode(
			$node,
			'.mw-version-ext#mw-version-ext-' . $expectedType . '-' .
			str_replace( ' ', '_', $componentDefinition['name'] )
		);
		$rowHtml = Html::rawElement( 'table', [], DOMCompat::getOuterHTML( $tableRowNode ) );

		$nameHtml = $this->assertSelectorMatchesOneElementInNode( $tableRowNode, '.mw-version-ext-name', true );
		$this->assertStringContainsString(
			$componentDefinition['namemsg'] ?? $componentDefinition['name'] ?? '(version-no-ext-name)',
			$nameHtml
		);

		if ( isset( $componentDefinition['version'] ) ) {
			$canonicalVersionHtml = $this->assertSelectorMatchesOneElementInNode(
				$tableRowNode,
				'.mw-version-ext-version',
				true
			);
			$this->assertStringContainsString( $componentDefinition['version'], $canonicalVersionHtml );
		}

		if ( str_contains( $rowHtml, 'mw-version-ext-meta-version' ) ) {
			$versionHtml = $this->assertSelectorMatchesOneElementInNode(
				$tableRowNode,
				'.mw-version-ext-meta-version',
				true
			);
			$this->assertStringContainsString( '(version-version', $versionHtml );
		}

		if ( isset( $componentDefinition['license-name'] ) ) {
			$licenseHtml = $this->assertSelectorMatchesOneElementInNode(
				$tableRowNode,
				'.mw-version-ext-license',
				true
			);
			$this->assertStringContainsString(
				RequestContext::getMain()->getOutput()->parseInlineAsInterface( $componentDefinition['license-name'] ),
				$licenseHtml
			);
		} elseif ( ExtensionInfo::getLicenseFileNames( dirname( $componentDefinition['path'] ) ) ) {
			$licenseHtml = $this->assertSelectorMatchesOneElementInNode(
				$tableRowNode,
				'.mw-version-ext-license',
				true
			);
			$this->assertStringContainsString( '(version-ext-license)', $licenseHtml );
		} else {
			$this->assertStringNotContainsString( 'mw-version-ext-license', $rowHtml );
		}

		$descriptionHtml = $this->assertSelectorMatchesOneElementInNode(
			$tableRowNode,
			'.mw-version-ext-description',
			true
		);
		$descriptionContent = $componentDefinition['descriptionmsg'] ?? $componentDefinition['description'] ?? '';
		if ( $descriptionContent ) {
			$this->assertStringContainsString( $descriptionContent, $descriptionHtml );
		}

		$authorsHtml = $this->assertSelectorMatchesOneElementInNode( $tableRowNode, '.mw-version-ext-authors', true );

		$actualAuthors = (array)( $componentDefinition['author'] ?? [] );
		if ( count( $actualAuthors ) === 1 && $actualAuthors[0] === '...' ) {
			$this->assertStringContainsString( '(version-poweredby-various)', $authorsHtml );
		} else {
			foreach ( $actualAuthors as $author ) {
				if ( $author === '...' || str_ends_with( $author, ' ...]' ) ) {
					$this->assertStringContainsString( '(version-poweredby-others)', $authorsHtml );
				} else {
					$this->assertStringContainsString(
						RequestContext::getMain()->getOutput()->parseInlineAsInterface( $author ),
						$authorsHtml
					);
				}
			}
			if ( ExtensionInfo::getAuthorsFileName( dirname( $componentDefinition['path'] ) ) ) {
				$this->assertStringContainsString( '(version-poweredby-others)', $authorsHtml );
			}
		}
	}

	public function testViewForLibrarySections(): void {
		$this->installMockExtensionRegistry( [] );

		[ $html ] = $this->executeSpecialPage();
		$htmlAsNode = DOMUtils::parseHTML( $html );

		$versionLibrariesHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-libraries',
			true
		);
		$this->assertStringContainsString( '(version-libraries)', $versionLibrariesHeaderHtml );

		$versionLibrariesServerHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-libraries-server',
			true
		);
		$this->assertStringContainsString( '(version-libraries-server)', $versionLibrariesServerHeaderHtml );

		$versionLibrariesServerTableNode = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#sv-libraries.mw-installed-software'
		);

		// Easier to just test against the MediaWiki core list, other tests check the
		// SpecialVersion::parseComposerInstalled method works as expected
		$mediaWikiCoreComposerInstalled = new ComposerInstalled( MW_INSTALL_PATH . '/vendor/composer/installed.json' );
		$expectedServerLibraries = $mediaWikiCoreComposerInstalled->getInstalledDependencies();

		// Checking every server library is expensive, so just check the top 5
		$expectedServerLibraries = array_slice( $expectedServerLibraries, 0, 5, true );
		foreach ( $expectedServerLibraries as $libraryName => $libraryDefinition ) {
			// DOMCompat considers unescaped "/" in a selector to be invalid
			$libraryNameForId = str_replace( '/', '\/', $libraryName );
			if ( str_contains( $libraryNameForId, '.' ) ) {
				// If the name of the library contains a dot, we need to use the "id" attribute
				// selector. This is much slower than using an normal ID selector so avoid it's
				// use unless necessary.
				$libraryNameSelector = "[id='mw-version-library-$libraryNameForId']";
			} else {
				$libraryNameSelector = '#mw-version-library-' . $libraryNameForId;
			}
			$rowHtml = $this->assertSelectorMatchesOneElementInNode(
				$versionLibrariesServerTableNode,
				$libraryNameSelector,
				true
			);

			$nameHtml = $this->assertSelectorMatchesOneElement( $rowHtml, '.mw-version-library-name' );
			$this->assertStringContainsString( $libraryName, $nameHtml );

			$this->assertStringContainsString( $libraryDefinition['version'], $rowHtml );
			foreach ( (array)$libraryDefinition['licenses'] as $licenseName ) {
				$this->assertStringContainsString( $licenseName, $rowHtml );
			}
			$this->assertStringContainsString( $libraryDefinition['description'], $rowHtml );
		}

		$versionLibrariesClientHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-libraries-client',
			true
		);
		$this->assertStringContainsString( '(version-libraries-client)', $versionLibrariesClientHeaderHtml );

		$versionLibrariesServerTableNode = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#sv-libraries-client.mw-installed-software'
		);

		$expectedForeignResources = Yaml::parseFile( MW_INSTALL_PATH . '/resources/lib/foreign-resources.yaml' );

		// Checking every server library is expensive, so just check the top 5
		$expectedForeignResources = array_slice( $expectedForeignResources, 0, 5, true );
		foreach ( $expectedForeignResources as $libraryName => $libraryDefinition ) {
			$libraryNameForId = $libraryName . $libraryDefinition['version'];
			if ( str_contains( $libraryNameForId, '.' ) ) {
				// If the name of the library contains a dot, we need to use the "id" attribute
				// selector. This is much slower than using an normal ID selector so avoid it's
				// use unless necessary.
				$libraryNameSelector = "[id='mw-version-library-$libraryNameForId']";
			} else {
				$libraryNameSelector = '#mw-version-library-' . $libraryNameForId;
			}
			$rowNode = $this->assertSelectorMatchesOneElementInNode(
				$versionLibrariesServerTableNode,
				$libraryNameSelector
			);

			$nameHtml = $this->assertSelectorMatchesOneElementInNode( $rowNode, '.mw-version-library-name', true );
			$this->assertStringContainsString( $libraryName, $nameHtml );

			$rowHtml = DOMCompat::getInnerHTML( $rowNode );
			$this->assertStringContainsString( $libraryDefinition['version'], $rowHtml );
			$this->assertStringContainsString( $libraryDefinition['license'], $rowHtml );
		}
	}

	public function testViewForParserSections(): void {
		$this->installMockExtensionRegistry( [] );

		[ $html ] = $this->executeSpecialPage();
		$htmlAsNode = DOMUtils::parseHTML( $html );

		$parserExtensionTagsHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-parser-extensiontags',
			true
		);
		$this->assertStringContainsString( '(version-parser-extensiontags)', $parserExtensionTagsHeaderHtml );

		$mainParser = $this->getServiceContainer()->getParserFactory()->getMainInstance();
		$parserExtensionTagsListHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-parser-extensiontags-list',
			true
		);
		$expectedTags = $mainParser->getTags();
		foreach ( $expectedTags as $expectedTag ) {
			$this->assertStringContainsString( "$expectedTag", $parserExtensionTagsListHtml );
		}

		$parserFunctionsHooksHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-parser-function-hooks',
			true
		);
		$this->assertStringContainsString( '(version-parser-function-hooks)', $parserFunctionsHooksHeaderHtml );

		$parserExtensionFunctionHooksListHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-parser-function-hooks-list',
			true
		);
		$expectedFunctionHooks = $mainParser->getFunctionHooks();

		// Code from SpecialVersion::getParserFunctionHooks that generates the display name for
		// the parser functions
		$funcSynonyms = $mainParser->getFunctionSynonyms();
		$preferredSynonyms = array_flip( array_reverse( $funcSynonyms[1] + $funcSynonyms[0] ) );
		array_walk( $expectedFunctionHooks, static function ( &$value ) use ( $preferredSynonyms ) {
			$value = $preferredSynonyms[$value];
		} );

		foreach ( $expectedFunctionHooks as $expectedFunctionHook ) {
			$this->assertStringContainsString(
				'{{' . $expectedFunctionHook . '}}',
				$parserExtensionFunctionHooksListHtml
			);
		}

		$parsoidModulesHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-parsoid-modules',
			true
		);
		$this->assertStringContainsString( '(version-parsoid-modules)', $parsoidModulesHeaderHtml );

		$parsoidModulesListHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-parsoid-modules-list',
			true
		);
		$expectedExtensionModules = $this->getServiceContainer()->getParsoidSiteConfig()->getExtensionModules();
		foreach ( $expectedExtensionModules as $expectedExtensionModule ) {
			$this->assertStringContainsString(
				// Do what Html::element does to escape the opening brace
				str_replace( '<', '&lt;', $expectedExtensionModule->getConfig()['name'] ),
				$parsoidModulesListHtml
			);
		}
	}

	public function testViewWhenHooksShown(): void {
		$this->overrideConfigValue( MainConfigNames::SpecialVersionShowHooks, true );

		$this->installMockExtensionRegistry( [] );

		$this->setTemporaryHook( 'SpecialPasswordResetOnSubmit', static function () {
		} );
		$this->setTemporaryHook( 'APIQueryAfterExecute', static function () {
		} );

		[ $html ] = $this->executeSpecialPage();
		$htmlAsNode = DOMUtils::parseHTML( $html );

		$versionHooksHeaderHtml = $this->assertSelectorMatchesOneElementInNode(
			$htmlAsNode,
			'#mw-version-hooks',
			true
		);
		$this->assertStringContainsString( '(version-hooks)', $versionHooksHeaderHtml );

		$versionHooksTableHtml = $this->assertSelectorMatchesOneElementInNode( $htmlAsNode, '#sv-hooks', true );
		$this->assertStringContainsString( '(version-hook-name)', $versionHooksTableHtml );
		$this->assertStringContainsString( '(version-hook-subscribedby)', $versionHooksTableHtml );

		// Only shows hooks that have handlers, so check the hooks we handled at the start of this method
		$this->assertStringContainsString( 'APIQueryAfterExecute', $versionHooksTableHtml );
		$this->assertStringContainsString( 'SpecialPasswordResetOnSubmit', $versionHooksTableHtml );
	}

	/**
	 * Mocks {@link ExtensionRegistry::getAllThings} to return the provided array, with
	 * other methods using the real service.
	 */
	private function installMockExtensionRegistry( array $allThings ): void {
		$realExtensionRegistry = $this->getServiceContainer()->getExtensionRegistry();

		$mockExtensionRegistry = $this->createMock( ExtensionRegistry::class );
		$mockExtensionRegistry->method( 'getAllThings' )
			->willReturn( $allThings );
		$mockExtensionRegistry->method( 'getAttribute' )
			->willReturnCallback( $realExtensionRegistry->getAttribute( ... ) );
		$mockExtensionRegistry->method( 'isLoaded' )
			->willReturnCallback( $realExtensionRegistry->isLoaded( ... ) );
		$this->setService( 'ExtensionRegistry', $mockExtensionRegistry );
	}

	public function testViewRootSpecialPageWhenNoComponents(): void {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );

		$this->installMockExtensionRegistry( [] );

		[ $html ] = $this->executeSpecialPage();
		$this->assertStringContainsString( '(version-skins-no-skin)', $html );
		$this->assertStringContainsString( '(version-extensions-no-ext)', $html );
	}

	public function testViewRootSpecialPageWhenHooksHidden(): void {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$this->overrideConfigValue( MainConfigNames::SpecialVersionShowHooks, false );

		$this->installMockExtensionRegistry( [] );

		$this->setTemporaryHook( 'SpecialPasswordResetOnSubmit', static function () {
		} );

		[ $html ] = $this->executeSpecialPage();
		$this->assertStringNotContainsString( 'version-hooks', $html );
	}

	public function testViewRootSpecialPageWhenNoHooksHandled(): void {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$this->overrideConfigValue( MainConfigNames::SpecialVersionShowHooks, true );
		$this->installMockExtensionRegistry( [] );

		// Code called during execution of special pages will need to use the real
		// HookContainer methods, so we need a partial mock
		$extRegistry = $this->getServiceContainer()->getExtensionRegistry();
		$mockHookContainer = $this->getMockBuilder( HookContainer::class )
			->setConstructorArgs( [
				new StaticHookRegistry(
					$this->getServiceContainer()->getMainConfig()->get( MainConfigNames::Hooks ),
					$extRegistry->getAttribute( 'Hooks' ),
					$extRegistry->getAttribute( 'DeprecatedHooks' )
				),
				$this->getServiceContainer()->getObjectFactory()
			] )
			->onlyMethods( [ 'getHandlerDescriptions' ] )
			->getMock();
		$mockHookContainer->method( 'getHandlerDescriptions' )
			->willReturn( [] );
		$this->setService( 'HookContainer', $mockHookContainer );

		[ $html ] = $this->executeSpecialPage();
		$this->assertStringNotContainsString( 'version-hooks', $html );
	}

	public function testViewLicenseSubpage(): void {
		[ $html ] = $this->executeSpecialPage( 'license' );
		$this->assertStringContainsString(
			'MediaWiki is licensed under the terms of the GNU General Public License',
			$html
		);
	}

	/** @dataProvider provideViewLicenseSubpageForComponent */
	public function testViewLicenseSubpageForComponent( string $componentName, bool $licenseFileExists ): void {
		$testExtensionDir = $this->getNewTempDirectory();
		$testExtensionPath = $testExtensionDir . '/extension.json';
		if ( $licenseFileExists ) {
			file_put_contents( $testExtensionDir . '/COPYING', 'Test license 12345' );
		}

		$this->installMockExtensionRegistry( [ [
			'path' => $testExtensionPath,
			'type' => 'other',
			'author' => [ 'Test author' ],
			'descriptionmsg' => 'rawmessage',
			'license-name' => 'GPL-2.0-or-later',
			'name' => 'Test',
			'namemsg' => 'rawmessage',
			'url' => 'https://example.com',
		] ] );

		[ $html ] = $this->executeSpecialPage( 'license/' . $componentName );

		if ( $licenseFileExists ) {
			$this->assertStringNotContainsString( '(version-license-not-found)', $html );
			$this->assertStringContainsString( 'Test license 12345', $html );
		} else {
			$this->assertStringContainsString( '(version-license-not-found)', $html );
		}
	}

	public static function provideViewLicenseSubpageForComponent(): array {
		return [
			'License exists' => [ 'componentName' => 'Test', 'licenseFileExists' => true ],
			'License does not exist for existing component' => [
				'componentName' => 'Test',
				'licenseFileExists' => false,
			],
			'License does not exist for non-existing component' => [
				'componentName' => 'NonExistingComponent',
				'licenseFileExists' => false,
			],
		];
	}

	public function testViewCreditsSubpage(): void {
		[ $html ] = $this->executeSpecialPage( 'credits' );
		$this->assertStringContainsString( '(version-credits-summary)', $html );
		$this->assertStringContainsString( '(version-credits-contributors)', $html );

		$creditsList = $this->assertSelectorMatchesOneElement( $html, '.mw-version-credits' );

		// Check users present in the CREDITS file at 26/08/26 are present in the HTML
		$this->assertStringContainsString( 'a smart kitten', $creditsList );
		$this->assertStringContainsString( 'Dreamy Jazz', $creditsList );
		$this->assertStringContainsString( 'SomeRandomDeveloper', $creditsList );
	}

	/** @dataProvider provideViewCreditsSubpageForComponent */
	public function testViewCreditsSubpageForComponent(
		string $componentName,
		?string $creditsFileName
	): void {
		$testExtensionDir = $this->getNewTempDirectory();
		$testExtensionPath = $testExtensionDir . '/extension.json';
		if ( $creditsFileName !== null ) {
			file_put_contents( $testExtensionDir . '/' . $creditsFileName, "Author1\nAuthor2\nAuthor3" );
		}

		$this->installMockExtensionRegistry( [ [
			'path' => $testExtensionPath,
			'type' => 'other',
			'author' => [ 'Test author' ],
			'descriptionmsg' => 'rawmessage',
			'license-name' => 'GPL-2.0-or-later',
			'name' => 'Test',
			'namemsg' => 'rawmessage',
			'url' => 'https://example.com',
		] ] );

		[ $html ] = $this->executeSpecialPage( 'credits/' . $componentName );

		if ( $creditsFileName !== null ) {
			$this->assertStringNotContainsString( '(version-credits-not-found)', $html );
			if ( str_ends_with( $creditsFileName, '.txt' ) ) {
				$preHtml = $this->assertSelectorMatchesOneElement( $html, 'pre' );
				$this->assertStringContainsString( "Author1\nAuthor2\nAuthor3", $preHtml );
			} else {
				$this->assertStringContainsString( "Author1\nAuthor2\nAuthor3", $html );
			}
		} else {
			$this->assertStringContainsString( '(version-credits-not-found)', $html );
		}
	}

	public static function provideViewCreditsSubpageForComponent(): array {
		return [
			'Credits is not a txt file' => [
				'componentName' => 'Test',
				'creditsFileName' => 'CREDITS',
			],
			'Credits is a txt file' => [
				'componentName' => 'Test',
				'creditsFileName' => 'CREDITS.txt',
			],
			'Credits does not exist for existing component' => [
				'componentName' => 'Test',
				'creditsFileName' => null,
			],
			'Credits does not exist for non-existing component' => [
				'componentName' => 'NonExistingComponent',
				'creditsFileName' => null,
			],
		];
	}

	public function testGetVersion(): void {
		$this->assertSame(
			MW_VERSION . ' (parentheses: ' . substr( GitInfo::repo()->getHeadSHA1(), 0, 7 ) . ')',
			SpecialVersion::getVersion( '', 'qqx' )
		);
	}

	public function testGetVersionForNoDB(): void {
		$this->assertSame(
			MW_VERSION . ' (' . substr( GitInfo::repo()->getHeadSHA1(), 0, 7 ) . ')',
			SpecialVersion::getVersion( 'nodb' )
		);
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Version' );
	}
}
