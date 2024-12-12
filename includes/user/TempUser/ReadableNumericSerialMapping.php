<?php

namespace MediaWiki\User\TempUser;

/**
 * Simple serial mapping for ASCII decimal numbers with hyphens for readability
 *
 * @since 1.44
 */
class ReadableNumericSerialMapping implements SerialMapping {
	private int $offset;

	/**
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->offset = $config['offset'] ?? 0;
	}

	public function getSerialIdForIndex( int $index ): string {
		$mapped = (string)( $index + $this->offset );
		return rtrim( chunk_split( $mapped, 5, '-' ), '-' );
	}
}
