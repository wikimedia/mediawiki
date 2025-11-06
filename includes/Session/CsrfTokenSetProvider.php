<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

/**
 * Provide an instance of CsrfTokenSet.
 *
 * @since 1.37
 * @ingroup Session
 */
interface CsrfTokenSetProvider {

	/**
	 * Get a set of CSRF tokens to obtain and match specific tokens.
	 *
	 * @return CsrfTokenSet
	 */
	public function getCsrfTokenSet(): CsrfTokenSet;
}
