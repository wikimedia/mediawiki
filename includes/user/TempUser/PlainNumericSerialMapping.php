<?php

namespace MediaWiki\User\TempUser;

/**
 * Simple serial mapping for ASCII decimal numbers
 *
 * @since 1.39
 */
class PlainNumericSerialMapping implements SerialMapping {
	/**
	 * @param array $config
	 */
	public function __construct( $config ) {
	}

	public function getSerialIdForIndex( int $index ): string {
		return (string)$index;
	}
}
