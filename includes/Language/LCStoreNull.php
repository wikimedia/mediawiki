<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Null store backend, used to avoid DB errors during MediaWiki installation.
 *
 * @ingroup Language
 */
class LCStoreNull implements LCStore {

	/** @inheritDoc */
	public function get( $code, $key ) {
		return null;
	}

	/** @inheritDoc */
	public function startWrite( $code ) {
	}

	public function finishWrite() {
	}

	/** @inheritDoc */
	public function set( $key, $value ) {
	}

}
