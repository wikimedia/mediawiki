<?php

use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;

/**
 * @covers HTMLRestrictionsField
 */
class HTMLRestrictionsFieldTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testConstruct() {
		$htmlForm = $this->createMock( HTMLForm::class );
		$htmlForm->method( 'msg' )->willReturnCallback( 'wfMessage' );

		$field = new HTMLRestrictionsField( [ 'fieldname' => 'restrictions', 'parent' => $htmlForm ] );
		$this->assertNotEmpty( $field->getLabel(), 'has a default label' );
		$this->assertNotEmpty( $field->getHelpText(), 'has a default help text' );
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
	public function testForm( $text, $value ) {
		$request = new FauxRequest( [ 'wprestrictions' => $text ], true );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$form = HTMLForm::factory( 'ooui', [
			'restrictions' => [ 'class' => HTMLRestrictionsField::class ],
		], $context );
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

	public function provideValidate() {
		return [
			// submitted text, value of 'IPAddresses' key or false for validation error
			[ null, [ '0.0.0.0/0', '::/0' ] ],
			[ '', [] ],
			[ "1.2.3.4\n::0", [ '1.2.3.4', '::0' ] ],
			[ "1.2.3.4\n::/x", false ],
		];
	}
}
