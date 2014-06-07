<?php

class FakeDimensionFile extends File {
	public $mustRender = false;

	public function __construct( $dimensions ) {
		parent::__construct( Title::makeTitle( NS_FILE, 'Test' ),
			new NullRepo( null ) );

		$this->dimensions = $dimensions;
	}

	public function getWidth( $page = 1 ) {
		return $this->dimensions[0];
	}

	public function getHeight( $page = 1 ) {
		return $this->dimensions[1];
	}

	public function mustRender() {
		return $this->mustRender;
	}

	public function getPath() {
		return '';
	}
}
