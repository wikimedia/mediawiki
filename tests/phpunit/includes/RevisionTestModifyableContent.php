<?php

class RevisionTestModifyableContent extends TextContent {

	public function __construct( $text ) {
		parent::__construct( $text, "RevisionTestModifyableContent" );
	}

	public function copy() {
		return new RevisionTestModifyableContent( $this->mText );
	}

	public function getText() {
		return $this->mText;
	}

	public function setText( $text ) {
		$this->mText = $text;
	}

}
