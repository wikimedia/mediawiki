<?php

namespace MediaWiki\User\TempUser;

/**
 * Simple serial mapping for ASCII decimal numbers
 *
 * @since 1.39
 */
class PlainNumericSerialMapping implements SerialMapping {
	/** @var int */
	private $offset;

	/**
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->offset = $config['offset'] ?? 0;
	}

	public function getSerialIdForIndex( int $index ): string {
		return (string)( $index + $this->offset );
	}
}
