<?php

class DummyContentHandlerForTesting extends ContentHandler {

	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, [ "testing" ] );
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

		return new DummyContentForTesting( $d );
	}

	/**
	 * Creates an empty Content object of the type supported by this ContentHandler.
	 */
	public function makeEmptyContent() {
		return new DummyContentForTesting( '' );
	}
}
