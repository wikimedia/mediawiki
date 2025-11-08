<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Skin\SkinMustache;
use MediaWiki\Title\Title;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \MediaWiki\Skin\SkinMustache
 * @group Skin
 * @group Database
 */
class SkinMustacheTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param string $html
	 * @param Title $title
	 * @return MockObject|OutputPage
	 */
	private function getMockOutputPage( $html, $title ) {
		$mockContentSecurityPolicy = $this->createMock( ContentSecurityPolicy::class );

		$mock = $this->createMock( OutputPage::class );
		$mock->method( 'getHTML' )
			->willReturn( $html );
		$mock->method( 'getCategoryLinks' )
			->willReturn( [] );
		$mock->method( 'getIndicators' )
			->willReturn( [
				'id' => '<a>indicator</a>'
			] );
		$mock->method( 'getTitle' )
			->willReturn( $title );
		$mock->method( 'getIndicators' )
			->willReturn( [ '' ] );
		$mock->method( 'getLanguageLinks' )
			->willReturn( [] );
		$mock->method( 'isTOCEnabled' )
			->willReturn( true );
		$mock->method( 'getTOCData' )
			->willReturn( null );
		return $mock;
	}

	private function validateTemplateData( $data, $key ) {
		$value = $data[$key];
		if ( $value === null ) {
			// Cannot validate a null value
			return;
		} elseif ( is_array( $value ) ) {
			$this->assertTrue(
				str_starts_with( $key, 'data-' ) || str_starts_with( $key, 'array-' ),
				"Template data that is an object should be associated with a key" .
				" prefixed with `data-` or `array-` ($key)"
			);

			// Validate the children
			foreach ( $value as $childKey => $childValue ) {
				if ( is_string( $childKey ) ) {
					$this->validateTemplateData( $value, $childKey );
				} else {
					$this->assertStringStartsWith(
						'array-',
						$key,
						"Template data that is a flat array should be associated with a key prefixed `array-` ($key)"
					);
				}
			}
		} elseif ( is_string( $value ) ) {
			if ( str_contains( $value, '<' ) ) {
				$this->assertTrue(
					str_starts_with( $key, 'html-' ) || $key === 'html',
					"Template data containing HTML must be prefixed with `html-` ($key)"
				);
			}
		} elseif ( is_bool( $value ) ) {
			$this->assertTrue(
				str_starts_with( $key, 'is-' ) || str_starts_with( $key, 'has-' ),
				"Template data containing booleans must be prefixed with `is-` or `has-` ($key)"
			);
		} elseif ( is_numeric( $value ) ) {
			$this->assertTrue(
				str_starts_with( $key, 'number-' ),
				"Template data containing numbers must be prefixed with `number-` ($key)"
			);
		} else {
			$this->fail(
				"Keys must be primitives e.g. arrays OR strings OR bools OR null ($key)."
			);
		}
	}

	/**
	 * @covers \MediaWiki\Skin\Skin
	 * @covers \MediaWiki\Skin\SkinComponentLogo
	 * @covers \MediaWiki\Skin\SkinComponentSearch
	 * @covers \MediaWiki\Skin\SkinComponentTableOfContents
	 * @covers \MediaWiki\Skin\SkinComponentFooter
	 */
	public function testGetTemplateData() {
		$config = $this->getServiceContainer()->getMainConfig();
		$bodytext = '<p>hello</p>';
		$context = new RequestContext();
		$title = Title::makeTitle( NS_MAIN, 'Mustache skin' );
		$context->setTitle( $title );
		$out = $this->getMockOutputPage( $bodytext, $title );
		$context->setOutput( $out );
		$this->overrideConfigValue( MainConfigNames::Logos, [] );
		$skin = new SkinMustache( [
			'name' => 'test',
			'templateDirectory' => __DIR__,
		] );
		$context->setConfig( $config );
		$skin->setContext( $context );
		$data = $skin->getTemplateData();

		// Validate the default template data respects the naming rules
		foreach ( $data as $key => $_ ) {
			$this->validateTemplateData( $data, $key );
		}

		// Validate search data
		$searchData = $data['data-search-box'];
		foreach ( $searchData as $key => $_ ) {
			$this->validateTemplateData( $searchData, $key );
		}
	}
}
