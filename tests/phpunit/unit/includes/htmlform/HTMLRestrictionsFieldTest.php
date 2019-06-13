<?php

/**
 * @covers HTMLRestrictionsField
 */
class HTMLRestrictionsFieldTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testConstruct() {
		$field = new HTMLRestrictionsField( [ 'fieldname' => 'restrictions' ] );
		$this->assertNotEmpty( $field->getLabel(), 'has a default label' );
		$this->assertNotEmpty( $field->getHelpText(), 'has a default help text' );
		$this->assertEquals( MWRestrictions::newDefault(), $field->getDefault(),
			'defaults to the default MWRestrictions object' );

		$field = new HTMLRestrictionsField( [
			'fieldname' => 'restrictions',
			'label' => 'foo',
			'help' => 'bar',
			'default' => 'baz',
		] );
		$this->assertEquals( 'foo', $field->getLabel(), 'label can be customized' );
		$this->assertEquals( 'bar', $field->getHelpText(), 'help text can be customized' );
		$this->assertEquals( 'baz', $field->getDefault(), 'default can be customized' );
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testForm( $text, $value ) {
		$form = HTMLForm::factory( 'ooui', [
			'restrictions' => [ 'class' => HTMLRestrictionsField::class ],
		] );
		$request = new FauxRequest( [ 'wprestrictions' => $text ], true );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$form->setContext( $context );
		$form->setTitle( Title::newFromText( 'Main Page' ) )->setSubmitCallback( function () {
			return true;
		} )->prepareForm();
		$status = $form->trySubmit();

		if ( $status instanceof StatusValue ) {
			$this->assertEquals( $value !== false, $status->isGood() );
		} elseif ( $value === false ) {
			$this->assertNotSame( true, $status );
		} else {
			$this->assertSame( true, $status );
		}

		if ( $value !== false ) {
			$restrictions = $form->mFieldData['restrictions'];
			$this->assertInstanceOf( MWRestrictions::class, $restrictions );
			$this->assertEquals( $value, $restrictions->toArray()['IPAddresses'] );
		}

		// sanity
		$form->getHTML( $status );
	}

	public function provideValidate() {
		return [
			// submitted text, value of 'IPAddresses' key or false for validation error
			[ null, [ '0.0.0.0/0', '::/0' ] ],
			[ '', [] ],
			[ "1.2.3.4\n::/0", [ '1.2.3.4', '::/0' ] ],
			[ "1.2.3.4\n::/x", false ],
		];
	}
}
