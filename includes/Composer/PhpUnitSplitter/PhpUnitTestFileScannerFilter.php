<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use RecursiveFilterIterator;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitTestFileScannerFilter extends RecursiveFilterIterator {

	/**
	 * @var string[] list of folders and files to skip. We want to avoid
	 *               loading PHP files from the vendor folder since that's
	 *               not our code. ParserIntegrationTest is a complex suite
	 *               that we can't handle in the usual way, so we will add
	 *               that to a suite on its own, manually, as required.
	 * @see T345481
	 */
	private const IGNORE = [
		"vendor",
		"ParserIntegrationTest.php",
	];

	public function accept(): bool {
		$filename = $this->current()->getFilename();
		if ( $filename[0] === '.' ) {
			return false;
		}
		if ( in_array( $filename, self::IGNORE ) ) {
			return false;
		}
		return true;
	}

}
