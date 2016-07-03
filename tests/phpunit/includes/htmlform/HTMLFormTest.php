<?php


class HTMLFormTest extends MediaWikiTestCase {
	public function testGetHTML_empty() {
		$form = new HTMLForm( [] );
		$form->setTitle( Title::newFromText( 'Foo' ) );
		$form->prepareForm();
		$html = $form->getHTML( false );
		$this->assertRegExp( '/<form\b/', $html );
	}

	/**
	 * @expectedException LogicException
	 */
	public function testGetHTML_noPrepare() {
		$form = new HTMLForm( [] );
		$form->setTitle( Title::newFromText( 'Foo' ) );
		$form->getHTML( false );
	}
}
