<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Interwiki;

/**
 * An interwiki lookup that has no data, intended
 * for use in the installer.
 *
 * @since 1.31
 */
class NullInterwikiLookup implements InterwikiLookup {

	/**
	 * @inheritDoc
	 */
	public function isValidInterwiki( $prefix ) {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function fetch( $prefix ) {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function getAllPrefixes( $local = null ) {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function invalidateCache( $prefix ) {
	}
}
