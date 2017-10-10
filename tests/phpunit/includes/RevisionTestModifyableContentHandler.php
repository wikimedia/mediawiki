<?php

class RevisionTestModifyableContentHandler extends TextContentHandler {

	public function __construct() {
		parent::__construct( "RevisionTestModifyableContent", [ CONTENT_FORMAT_TEXT ] );
	}

	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new RevisionTestModifyableContent( $text );
	}

	public function makeEmptyContent() {
		return new RevisionTestModifyableContent( '' );
	}

}
