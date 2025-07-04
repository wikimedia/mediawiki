<?php

namespace MediaWiki\Rest;

use GuzzleHttp\Psr7;

class Stream extends Psr7\Stream implements CopyableStreamInterface {
	/** @var resource */
	private $stream;

	/** @inheritDoc */
	public function __construct( $stream, $options = [] ) {
		$this->stream = $stream;
		parent::__construct( $stream, $options );
	}

	/** @inheritDoc */
	public function copyToStream( $target ) {
		stream_copy_to_stream( $this->stream, $target );
	}
}
