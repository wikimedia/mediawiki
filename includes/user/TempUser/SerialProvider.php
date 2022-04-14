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
	 * @return int
	 */
	public function acquireIndex(): int;
}
