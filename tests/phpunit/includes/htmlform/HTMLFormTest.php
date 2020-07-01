<?php

/**
 * @covers HTMLForm
 *
 * @license GPL-2.0-or-later
 * @author Gergő Tisza
 */
class HTMLFormTest extends MediaWikiIntegrationTestCase {

	private function newInstance() {
		$context = new RequestContext();
		$out = new OutputPage( $context );
		$out->setTitle( Title::newMainPage() );
		$context->setOutput( $out );
		$form = new HTMLForm( [], $context );
		$form->setTitle( Title::newFromText( 'Foo' ) );
		return $form;
	}

	public function testGetHTML_empty() {
		$form = $this->newInstance();
		$form->prepareForm();
		$html = $form->getHTML( false );
		$this->assertStringStartsWith( '<form ', $html );
	}

	public function testGetHTML_noPrepare() {
		$form = $this->newInstance();
		$this->expectException( LogicException::class );
		$form->getHTML( false );
	}

	public function testAutocompleteDefaultsToNull() {
		$form = $this->newInstance();
		$this->assertStringNotContainsString( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToNull() {
		$form = $this->newInstance();
		$form->setAutocomplete( null );
		$this->assertStringNotContainsString( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToFalse() {
		$form = $this->newInstance();
		// Previously false was used instead of null to indicate the attribute should not be set
		$form->setAutocomplete( false );
		$this->assertStringNotContainsString( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToOff() {
		$form = $this->newInstance();
		$form->setAutocomplete( 'off' );
		$this->assertStringContainsString( ' autocomplete="off"', $form->wrapForm( '' ) );
	}

	public function testGetPreText() {
		$preText = 'TEST';
		$form = $this->newInstance();
		$form->setPreText( $preText );
		$this->assertSame( $preText, $form->getPreText() );
	}

	public function testGetErrorsOrWarningsWithRawParams() {
		$form = $this->newInstance();
		$msg = new RawMessage( 'message with $1' );
		$msg->rawParams( '<a href="raw">params</a>' );
		$status = Status::newFatal( $msg );

		$result = $form->getErrorsOrWarnings( $status, 'error' );

		$this->assertStringContainsString( 'message with <a href="raw">params</a>', $result );
	}

}
