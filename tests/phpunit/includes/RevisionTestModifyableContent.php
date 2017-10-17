<?php

class RevisionTestModifyableContent extends TextContent {

	const MODEL_ID = "RevisionTestModifyableContent";

	public function __construct( $text ) {
		parent::__construct( $text, self::MODEL_ID );
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
