<?php

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\NullRepo;
use MediaWiki\Title\Title;

class FakeDimensionFile extends File {
	/** @var bool */
	public $mustRender = false;
	/** @var string */
	public $mime;
	/** @var int[] */
	public $dimensions;

	public function __construct( $dimensions, $mime = 'unknown/unknown' ) {
		parent::__construct( Title::makeTitle( NS_FILE, 'Test' ),
			new NullRepo( null ) );

		$this->dimensions = $dimensions;
		$this->mime = $mime;
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

	public function getMimeType() {
		return $this->mime;
	}
}
