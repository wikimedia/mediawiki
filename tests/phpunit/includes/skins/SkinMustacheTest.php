<?php

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers SkinMustache
 *
 * @group Output
 */
class SkinMustacheTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return MockObject|OutputPage
	 */
	private function getMockOutputPage( $html, $title ) {
		$mockContentSecurityPolicy = $this->getMockBuilder( ContentSecurityPolicy::class )
			->disableOriginalConstructor()
			->getMock();

		$mockContentSecurityPolicy->method( 'getNonce' )
			->willReturn( 'secret' );

		$mock = $this->createMock( OutputPage::class );
		$mock->method( 'getHTML' )
			->willReturn( $html );
		$mock->method( 'getIndicators' )
			->willReturn( [
				'id' => '<a>indicator</a>'
			] );
		$mock->method( 'getTitle' )
			->willReturn( $title );
		$mock->method( 'getIndicators' )
			->willReturn( '' );
		$mock->method( 'getLanguageLinks' )
			->willReturn( [] );
		$mock->method( 'getCSP' )
			->willReturn( $mockContentSecurityPolicy );
		return $mock;
	}

	private function validateTemplateData( $data, $key ) {
		$value = $data[$key];
		if ( $value === null ) {
			// Cannot validate a null value
			return;
		} elseif ( is_array( $value ) ) {
			$this->assertTrue(
				strpos( $key, 'data-' ) === 0 || strpos( $key, 'array-' ) === 0,
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
			if ( strpos( $value, '<' ) !== false ) {
				$this->assertTrue(
					strpos( $key, 'html-' ) === 0 || $key === 'html',
					"Template data containing HTML must be prefixed with `html-` ($key)"
				);
			}
		} elseif ( is_bool( $value ) ) {
			$this->assertTrue(
				strpos( $key, 'is-' ) === 0 || strpos( $key, 'has-' ) === 0,
				"Template data containing booleans must be prefixed with `is-` or `has-` ($key)"
			);
		} else {
			$this->fail(
				"Keys must be primitives e.g. arrays OR strings OR bools OR null ($key)."
			);
		}
	}

	/**
	 * @covers SkinMustache::getTemplateData
	 * @covers SkinMustache::buildSearchProps
	 */
	public function testGetTemplateData() {
		$config = new HashConfig();
		$bodytext = '<p>hello</p>';
		$context = new RequestContext();
		$title = Title::newFromText( 'Mustache skin' );
		$context->setTitle( $title );
		$out = $this->getMockOutputPage( $bodytext, $title );
		$context->setOutput( $out );
		$skin = new SkinMustache( [
			'name' => 'test',
			'templateDirectory' => __DIR__,
		] );
		$skin->setContext( $context );
		$data = $skin->getTemplateData();

		// Validate the default template data respects the naming rules
		foreach ( array_keys( $data ) as $key ) {
			$this->validateTemplateData( $data, $key );
		}

		// Validate search data
		$searchData = $data['data-search-box'];
		foreach ( array_keys( $searchData ) as $key ) {
			$this->validateTemplateData( $searchData, $key );
		}
	}
}
