<?php

namespace MediaWiki\User\TempUser;

/**
 * Interface for serial providers for temporary users.
 *
 * @since 1.39
 */
interface SerialProvider {
	/**
	 * Acquire an integer such that it is unlikely to be used again, and return it.
	 * @param int $year The current year, as calculated by the caller (or 0 if the
	 *   year is not being used).
	 * @return int
	 */
	public function acquireIndex( int $year = 0 ): int;
}
