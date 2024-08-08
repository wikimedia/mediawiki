<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use EmptyIterator;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\HTMLForm\Field\HTMLRestrictionsField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\Language;
use MediaWiki\Page\PageSelectQueryBuilder;
use MediaWiki\Page\PageStore;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWikiCoversValidator;
use MediaWikiIntegrationTestCase;
use MWRestrictions;
use StatusValue;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLRestrictionsField
 */
class HTMLRestrictionsFieldTest extends MediaWikiIntegrationTestCase {

	use MediaWikiCoversValidator;

	public function testConstruct() {
		$htmlForm = $this->createMock( HTMLForm::class );
		$htmlForm->method( 'msg' )->willReturnCallback( 'wfMessage' );
		$languageMock = $this->createMock( Language::class );
		$languageMock->method( 'getCode' )->willReturn( 'en' );
		$titleMock = $this->createMock( Title::class );

		$htmlForm->method( 'getLanguage' )->willReturn( $languageMock );
		$htmlForm->method( 'getTitle' )->willReturn( $titleMock );

		$field = new HTMLRestrictionsField( [ 'fieldname' => 'restrictions', 'parent' => $htmlForm ] );
		$this->assertEquals( MWRestrictions::newDefault(), $field->getDefault(),
			'defaults to the default MWRestrictions object' );

		$field = new HTMLRestrictionsField( [
			'fieldname' => 'restrictions',
			'label' => 'foo',
			'help' => 'bar',
			'default' => 'baz',
			'parent' => $htmlForm,
		] );
		$this->assertEquals( 'foo', $field->getLabel(), 'label can be customized' );
		$this->assertEquals( 'bar', $field->getHelpText(), 'help text can be customized' );
		$this->assertEquals( 'baz', $field->getDefault(), 'default can be customized' );
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testForm( $ipText, $value ) {
		$request = new FauxRequest( [ 'wprestrictions-ip' => $ipText ], true );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$form = HTMLForm::factory( 'ooui', [
			'restrictions' => [ 'class' => HTMLRestrictionsField::class ],
		], $context );

		$pageStore = $this->createMock( PageStore::class );
		$this->setService( 'PageStore', $pageStore );
		$queryBuilderMock = $this->createMock( PageSelectQueryBuilder::class );
		$queryBuilderMock->method( 'fetchPageRecords' )->willReturn( new EmptyIterator() );
		$queryBuilderMock->method( 'wherePageIds' )->willReturnSelf();
		$queryBuilderMock->method( 'caller' )->willReturnSelf();
		$pageStore->method( 'newSelectQueryBuilder' )->willReturn( $queryBuilderMock );

		$form->setTitle( Title::makeTitle( NS_MAIN, 'Main Page' ) )->setSubmitCallback( static function () {
			return true;
		} )->prepareForm();
		$status = $form->trySubmit();

		if ( $status instanceof StatusValue ) {
			$this->assertEquals( $value !== false, $status->isGood() );
		} elseif ( $value === false ) {
			$this->assertFalse( $status );
		} else {
			$this->assertTrue( $status );
		}

		if ( $value !== false ) {
			$restrictions = $form->mFieldData['restrictions'];
			$this->assertInstanceOf( MWRestrictions::class, $restrictions );
			$this->assertEquals( $value, $restrictions->toArray()['IPAddresses'] );
		}

		$form->getHTML( $status );
	}

	public static function provideValidate() {
		return [
			// submitted text, value of 'IPAddresses' key or false for validation error
			[ null, [ '0.0.0.0/0', '::/0' ] ],
			[ '', [] ],
			[ "1.2.3.4\n::0", [ '1.2.3.4', '::0' ] ],
			[ "1.2.3.4\n::/x", false ],
		];
	}
}
