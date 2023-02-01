<?php
/**
 * @covers HTMLTitlesMultiselectField
 */
class HTMLTitlesMultiselectFieldTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideValidate
	 */
	public function testForm( $text, $expectedValue, $options = [] ) {
		$request = new FauxRequest( [ 'wptest' => $text ], true );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$form = HTMLForm::factory( 'ooui', [
			'test' => [ 'class' => HTMLTitlesMultiselectField::class ] + $options,
		], $context );
		$form
			->setTitle( Title::makeTitle( NS_MAIN, 'Main Page' ) )
			->setSubmitCallback( static function () {
				return true;
			} )
			->prepareForm();
		$status = $form->trySubmit();

		if ( $status instanceof StatusValue ) {
			$this->assertSame( $expectedValue !== false, $status->isGood() );
		} elseif ( $expectedValue === false ) {
			$this->assertFalse( $status );
		} else {
			$this->assertTrue( $status );
		}

		if ( $expectedValue !== false ) {
			$value = $form->mFieldData['test'];
			$this->assertSame( $expectedValue, explode( "\n", $value ) );
		}

		$form->getHTML( $status );
	}

	public function provideValidate() {
		return [
			// empty form
			[ null, [ '' ] ],

			// Good values
			[ '', [ '' ], [], [ 'required' => false ] ],
			[ 'Single', [ 'Single' ] ],
			[ "One\nTwo", [ 'One', 'Two' ] ],

			// Bad values
			[ '', false, [ 'required' => true ] ],
			[ "One\nTwo", false, [ 'max' => 1 ] ],
			[ "In<valid", false ],
			[ "In<valid\nAnother|invalid", false ],
		];
	}
}
