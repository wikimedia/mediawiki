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

	public function __construct( array $dimensions, string $mime = 'unknown/unknown' ) {
		parent::__construct( Title::makeTitle( NS_FILE, 'Test' ),
			new NullRepo( null ) );

		$this->dimensions = $dimensions;
		$this->mime = $mime;
	}

	/** @inheritDoc */
	public function getWidth( $page = 1 ) {
		return $this->dimensions[0];
	}

	/** @inheritDoc */
	public function getHeight( $page = 1 ) {
		return $this->dimensions[1];
	}

	/** @inheritDoc */
	public function mustRender() {
		return $this->mustRender;
	}

	/** @inheritDoc */
	public function getPath() {
		return '';
	}

	/** @inheritDoc */
	public function getMimeType() {
		return $this->mime;
	}
}
