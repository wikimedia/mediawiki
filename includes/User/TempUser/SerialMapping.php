<?php

namespace MediaWiki\User\TempUser;

/**
 * Interface for integer to string mappings for temporary user autocreation
 *
 * @since 1.39
 */
interface SerialMapping {
	/**
	 * @param int $index
	 * @return string The serial ID. This should consist of title characters
	 *   but should not be a single asterisk.
	 */
	public function getSerialIdForIndex( int $index ): string;
}
