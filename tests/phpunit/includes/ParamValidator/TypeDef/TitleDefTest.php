<?php

namespace MediaWiki\ParamValidator\TypeDef;

use CommentStoreComment;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use Title;
use TitleValue;
use User;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\TypeDefTestCase;
use WikitextContent;

/**
 * @covers \MediaWiki\ParamValidator\TypeDef\TitleDef
 */
class TitleDefTest extends TypeDefTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new TitleDef(
			$callbacks,
			MediaWikiServices::getInstance()->getTitleFactory()
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
			$updater = MediaWikiServices::getInstance()->getWikiPageFactory()
				->newFromTitle( Title::newFromText( 'exists' ) )
				->newPageUpdater( new User );
			$updater->setContent( SlotRecord::MAIN, new WikitextContent( 'exists' ) );
			$updater->saveRevision( CommentStoreComment::newUnsavedComment( 'test' ) );
			$this->assertTrue( $updater->getStatus()->isOK(), 'sanity' );
		}
		parent::testValidate( $value, $expect, $settings, $options, $expectConds );
	}

	public function provideValidate() {
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
				'expect' => $this->getValidationException( 'badtitle', '<script>' ),
				'settings' => [],
			],
			'as object' => [
				'value' => 'Foo',
				'expect' => new TitleValue( NS_MAIN, 'Foo' ),
				'settings' => [ TitleDef::PARAM_RETURN_OBJECT => true ],
			],
			'as object, with namespace' => [
				'value' => 'User:Foo',
				'expect' => new TitleValue( NS_USER, 'Foo' ),
				'settings' => [ TitleDef::PARAM_RETURN_OBJECT => true ],
			],
			'object normalization' => [
				'value' => 'foo_bar',
				'expect' => new TitleValue( NS_MAIN, 'Foo bar' ),
				'settings' => [ TitleDef::PARAM_RETURN_OBJECT => true ],
			],
			'must exist (success)' => [
				'value' => 'Exists',
				'expect' => 'Exists',
				'settings' => [ TitleDef::PARAM_MUST_EXIST => true ],
			],
			'must exist (failure)' => [
				'value' => 'does not exist',
				'expect' => $this->getValidationException( 'missingtitle', 'does not exist',
					[ TitleDef::PARAM_MUST_EXIST => true ] ),
				'settings' => [ TitleDef::PARAM_MUST_EXIST => true ],
			],
		];
	}

	public function provideStringifyValue() {
		return [
			// Underscore-to-space conversion not happening here but later in validate().
			'String' => [ 'User:John_Doe', 'User:John_Doe' ],
			'TitleValue' => [ new TitleValue( NS_USER, 'John_Doe' ), 'User:John Doe' ],
			'Title' => [ Title::newFromText( 'User:John_Doe' ), 'User:John Doe' ],
		];
	}

	public function provideCheckSettings() {
		// checkSettings() is itself used in tests. Testing it is a waste of time,
		// just provide the minimum required.
		return [
			'Basic test' => [ [], self::STDRET, array_merge_recursive( self::STDRET, [
				'allowedKeys' => [ TitleDef::PARAM_MUST_EXIST, TitleDef::PARAM_RETURN_OBJECT ],
			] ) ],
		];
	}

	public function provideGetInfo() {
		return [
			'no mustExist' => [
				'settings' => [],
				'expectParamInfo' => [ 'mustExist' => false ],
				'expectHelpInfo' => [
					ParamValidator::PARAM_TYPE =>
						'<message key="paramvalidator-help-type-title"></message>',
					TitleDef::PARAM_MUST_EXIST =>
						'<message key="paramvalidator-help-type-title-no-must-exist"></message>'
				],
			],
			'mustExist' => [
				'settings' => [ TitleDef::PARAM_MUST_EXIST => true ],
				'expectParamInfo' => [ 'mustExist' => true ],
				'expectHelpInfo' => [
					ParamValidator::PARAM_TYPE =>
						'<message key="paramvalidator-help-type-title"></message>',
					TitleDef::PARAM_MUST_EXIST =>
						'<message key="paramvalidator-help-type-title-must-exist"></message>'
				],
			],
		];
	}

}
