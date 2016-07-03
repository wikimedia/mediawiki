<?php

class DummyNonTextContentHandler extends DummyContentHandlerForTesting {

	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, [ "testing-nontext" ] );
	}

	/**
	 * @see ContentHandler::serializeContent
	 *
	 * @param Content $content
	 * @param string $format
	 *
	 * @return string
	 */
	public function serializeContent( Content $content, $format = null ) {
		return $content->serialize();
	}

	/**
	 * @see ContentHandler::unserializeContent
	 *
	 * @param string $blob
	 * @param string $format Unused.
	 *
	 * @return Content
	 */
	public function unserializeContent( $blob, $format = null ) {
		$d = unserialize( $blob );

		return new DummyNonTextContent( $d );
	}

	/**
	 * Creates an empty Content object of the type supported by this ContentHandler.
	 */
	public function makeEmptyContent() {
		return new DummyNonTextContent( '' );
	}

	public function supportsDirectApiEditing() {
		return true;
	}

}
