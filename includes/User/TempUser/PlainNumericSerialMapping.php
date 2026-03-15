<?php

namespace MediaWiki\User\TempUser;

/**
 * Simple serial mapping for ASCII decimal numbers
 *
 * @since 1.39
 */
class PlainNumericSerialMapping implements SerialMapping {
	private readonly int $offset;

	public function __construct( array $config ) {
		$this->offset = $config['offset'] ?? 0;
	}

	public function getSerialIdForIndex( int $index ): string {
		return (string)( $index + $this->offset );
	}
}
