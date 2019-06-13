<?php

/**
 * @covers HTMLForm
 *
 * @license GPL-2.0-or-later
 * @author Gergő Tisza
 */
class HTMLFormTest extends MediaWikiTestCase {

	private function newInstance() {
		$form = new HTMLForm( [] );
		$form->setTitle( Title::newFromText( 'Foo' ) );
		return $form;
	}

	public function testGetHTML_empty() {
		$form = $this->newInstance();
		$form->prepareForm();
		$html = $form->getHTML( false );
		$this->assertStringStartsWith( '<form ', $html );
	}

	/**
	 * @expectedException LogicException
	 */
	public function testGetHTML_noPrepare() {
		$form = $this->newInstance();
		$form->getHTML( false );
	}

	public function testAutocompleteDefaultsToNull() {
		$form = $this->newInstance();
		$this->assertNotContains( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToNull() {
		$form = $this->newInstance();
		$form->setAutocomplete( null );
		$this->assertNotContains( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToFalse() {
		$form = $this->newInstance();
		// Previously false was used instead of null to indicate the attribute should not be set
		$form->setAutocomplete( false );
		$this->assertNotContains( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToOff() {
		$form = $this->newInstance();
		$form->setAutocomplete( 'off' );
		$this->assertContains( ' autocomplete="off"', $form->wrapForm( '' ) );
	}

	public function testGetPreText() {
		$preText = 'TEST';
		$form = $this->newInstance();
		$form->setPreText( $preText );
		$this->assertSame( $preText, $form->getPreText() );
	}

}
