<?php

/**
 * @covers HTMLForm
 *
 * @licence GNU GPL v2+
 * @author Gergő Tisza
 * @author Thiemo Mättig
 */
class HTMLFormTest extends MediaWikiTestCase {

	private function getInstance() {
		$form = new HTMLForm( [] );
		$form->setTitle( Title::newFromText( 'Foo' ) );
		return $form;
	}

	public function testGetHTML_empty() {
		$form = $this->getInstance();
		$form->prepareForm();
		$html = $form->getHTML( false );
		$this->assertStringStartsWith( '<form ', $html );
	}

	/**
	 * @expectedException LogicException
	 */
	public function testGetHTML_noPrepare() {
		$form = $this->getInstance();
		$form->getHTML( false );
	}

	public function testAutocompleteDefaultsToTrue() {
		$form = $this->getInstance();
		$this->assertNotContains( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToTrue() {
		$form = $this->getInstance();
		$form->setAutocomplete( true );
		$this->assertNotContains( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToFalse() {
		$form = $this->getInstance();
		$form->setAutocomplete( false );
		$this->assertContains( ' autocomplete="off"', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToOff() {
		$form = $this->getInstance();
		$form->setAutocomplete( 'off' );
		$this->assertContains( ' autocomplete="off"', $form->wrapForm( '' ) );
	}

}
