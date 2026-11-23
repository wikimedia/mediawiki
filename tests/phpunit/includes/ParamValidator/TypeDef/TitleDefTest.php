<?php

namespace MediaWiki\Tests\ParamValidator\TypeDef;

use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\TitleDef;
use MediaWiki\ParamValidator\TypeDef\TitleParam;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \MediaWiki\ParamValidator\TypeDef\TitleDef
 * @group Database
 */
class TitleDefTest extends TypeDefIntegrationTestCase {
	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		return new TitleDef(
			$callbacks,
			$this->getServiceContainer()->getTitleFactory()
		);
	}

	/**
	 * @inheritDoc
	 * @dataProvider provideValidate
	 */
	public function testValidate(
		$value, $expect, array $settings = [], array $options = [], array $expectConds = []
	) {
		if ( $this->dataName() === 'must exist (success)' ) {
			$status = $this->editPage( Title::makeTitle( NS_MAIN, 'Exists' ), 'exists' );
			$this->assertStatusOK( $status );
		}
		parent::testValidate( $value, $expect, $settings, $options, $expectConds );
	}

	public static function provideValidate() {
		return [
			'plain' => [
				'value' => 'Foo',
				'expect' => 'Foo',
				'settings' => [],
			],
			'normalization' => [
				'value' => 'foo_bar',
				'expect' => 'Foo bar',
				'settings' => [],
			],
			'bad title' => [
				'value' => '<script>',
				'expect' => self::getValidationException( 'badtitle', '<script>' ),
				'settings' => [],
			],
			'as object' => [
				'value' => 'Foo',
				'expect' => new TitleValue( NS_MAIN, 'Foo' ),
				'settings' => [ TitleDef::PARAM_RETURN_OBJECT => TitleDef::LINK_TARGET_VALUE ],
			],
			'as object, with namespace' => [
				'value' => 'User:Foo',
				'expect' => new TitleValue( NS_USER, 'Foo' ),
				'settings' => [ TitleDef::PARAM_RETURN_OBJECT => TitleDef::LINK_TARGET_VALUE ],
			],
			'object normalization' => [
				'value' => 'foo_bar',
				'expect' => new TitleValue( NS_MAIN, 'Foo bar' ),
				'settings' => [ TitleDef::PARAM_RETURN_OBJECT => TitleDef::LINK_TARGET_VALUE ],
			],
			'must exist (success)' => [
				'value' => 'Exists',
				'expect' => 'Exists',
				'settings' => [ TitleDef::PARAM_MUST_EXIST => true ],
			],
			'must exist (failure)' => [
				'value' => 'does not exist',
				'expect' => self::getValidationException( 'missingtitle', 'does not exist',
					[ TitleDef::PARAM_MUST_EXIST => true ] ),
				'settings' => [ TitleDef::PARAM_MUST_EXIST => true ],
			],
			// Parsed as a fragment-only self-link (empty title), not as a page ID
			'ID without PARAM_ALLOW_ID' => [
				'value' => '#123',
				'expect' => '',
				'settings' => [],
			],
			'Not a string' => [
				[ 1, 2, 3 ],
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-needstring', [], 'needstring' ),
					'test', '', []
				)
			],
		];
	}

	public function testValidateById() {
		$page = $this->getExistingTestPage( 'ExistsById' );
		$id = $page->getId();
		$def = $this->getInstance( new SimpleCallbacks( [] ), [] );
		$settings = [ TitleDef::PARAM_ALLOW_ID => true ];

		$this->assertSame( 'ExistsById', $def->validate( 'test', "#$id", $settings, [] ) );
		$this->assertEquals(
			new TitleValue( NS_MAIN, 'ExistsById' ),
			$def->validate( 'test', "#$id", $settings + [ TitleDef::PARAM_RETURN_OBJECT => TitleDef::LINK_TARGET_VALUE ], [] )
		);

		$this->expectException( ValidationException::class );
		$def->validate( 'test', '#99999999', $settings, [] );
	}

	public function testValidateTitleParam() {
		$page = $this->getExistingTestPage( 'TitleParamExists' );
		$id = $page->getId();
		$def = $this->getInstance( new SimpleCallbacks( [] ), [] );
		$settings = [
			TitleDef::PARAM_RETURN_OBJECT => TitleDef::TITLE_PARAM_VALUE,
			TitleDef::PARAM_ALLOW_ID => true,
		];

		// The text is kept as requested, the page is normalized
		$res = $def->validate( 'test', 'titleParamExists', $settings, [] );
		$this->assertInstanceOf( TitleParam::class, $res );
		$this->assertSame( 'titleParamExists', $res->getText() );
		$this->assertSame( $id, $res->getPage()->getId() );
		$this->assertNull( $res->getRequestedPageId() );

		// Non-existing pages still get a page (id 0)
		$res = $def->validate( 'test', 'Does not exist', $settings, [] );
		$this->assertSame( 0, $res->getPage()->getId() );

		// Titles that cannot be pages get no page
		$res = $def->validate( 'test', 'Special:BlankPage', $settings, [] );
		$this->assertNull( $res->getPage() );

		// By ID
		$res = $def->validate( 'test', "#$id", $settings, [] );
		$this->assertSame( "#$id", $res->getText() );
		$this->assertSame( $id, $res->getPage()->getId() );
		$this->assertSame( $id, $res->getRequestedPageId() );

		// An unknown ID is not a validation failure
		$res = $def->validate( 'test', '#99999999', $settings, [] );
		$this->assertNull( $res->getPage() );
		$this->assertSame( 99999999, $res->getRequestedPageId() );

		// ...unless the page must exist
		$this->expectException( ValidationException::class );
		$def->validate( 'test', '#99999999', $settings + [ TitleDef::PARAM_MUST_EXIST => true ], [] );
	}

	public static function provideStringifyValue() {
		return [
			// Underscore-to-space conversion not happening here but later in validate().
			'String' => [ 'User:John_Doe', 'User:John_Doe' ],
			'TitleValue' => [ new TitleValue( NS_USER, 'John_Doe' ), 'User:John Doe' ],
			'Title' => [ Title::makeTitle( NS_USER, 'John_Doe' ), 'User:John Doe' ],
		];
	}

	public static function provideCheckSettings() {
		// checkSettings() is itself used in tests. Testing it is a waste of time,
		// just provide the minimum required.
		return [
			'Basic test' => [ [], self::STDRET, array_merge_recursive( self::STDRET, [
				'allowedKeys' => [ TitleDef::PARAM_MUST_EXIST, TitleDef::PARAM_RETURN_OBJECT, TitleDef::PARAM_ALLOW_ID ],
			] ) ],
		];
	}

	public static function provideGetInfo() {
		return [
			'no mustExist' => [
				'settings' => [],
				'expectParamInfo' => [ 'mustExist' => false, 'allowId' => false ],
				'expectHelpInfo' => [
					ParamValidator::PARAM_TYPE =>
						'<message key="paramvalidator-help-type-title"></message>',
					TitleDef::PARAM_MUST_EXIST =>
						'<message key="paramvalidator-help-type-title-no-must-exist"></message>'
				],
			],
			'mustExist' => [
				'settings' => [ TitleDef::PARAM_MUST_EXIST => true ],
				'expectParamInfo' => [ 'mustExist' => true, 'allowId' => false ],
				'expectHelpInfo' => [
					ParamValidator::PARAM_TYPE =>
						'<message key="paramvalidator-help-type-title"></message>',
					TitleDef::PARAM_MUST_EXIST =>
						'<message key="paramvalidator-help-type-title-must-exist"></message>'
				],
			],
			'allowId' => [
				'settings' => [ TitleDef::PARAM_ALLOW_ID => true ],
				'expectParamInfo' => [ 'mustExist' => false, 'allowId' => true ],
				'expectHelpInfo' => [
					ParamValidator::PARAM_TYPE =>
						'<message key="paramvalidator-help-type-title"></message>',
					TitleDef::PARAM_MUST_EXIST =>
						'<message key="paramvalidator-help-type-title-no-must-exist"></message>',
					TitleDef::PARAM_ALLOW_ID =>
						'<message key="paramvalidator-help-type-title-allow-id"></message>'
				],
			],
		];
	}

}
