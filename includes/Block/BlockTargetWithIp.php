<?php

namespace MediaWiki\Block;

/**
 * Shared interface for IP or range blocks
 *
 * @since 1.44
 */
interface BlockTargetWithIp {
	/**
	 * Get the range as a hexadecimal tuple.
	 *
	 * @return string[]
	 */
	public function toHexRange();
}
