<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LikeValue;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/moveToExternal.php';
// @codeCoverageIgnoreEnd

class FixLegacyEncoding extends MoveToExternal {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Change encoding of stored content from legacy encoding to UTF-8' );
	}

	protected function getConditions( int $blockStart, int $blockEnd, IReadableDatabase $dbr ): array {
		return [
			$dbr->expr( 'old_id', '>=', $blockStart ),
			$dbr->expr( 'old_id', '<=', $blockEnd ),
			$dbr->expr( 'old_flags', IExpression::NOT_LIKE,
				new LikeValue( $dbr->anyString(), 'utf-8', $dbr->anyString() ) ),
			$dbr->expr( 'old_flags', IExpression::NOT_LIKE,
				new LikeValue( $dbr->anyString(), 'utf8', $dbr->anyString() ) ),
		];
	}

	protected function resolveText( string $text, array $flags ): array {
		if ( in_array( 'error', $flags ) ) {
			return [ $text, $flags ];
		}
		$blobStore = $this->getServiceContainer()->getBlobStore();
		if ( in_array( 'external', $flags ) && $blobStore instanceof SqlBlobStore ) {
			$newText = $blobStore->expandBlob( $text, $flags );
			if ( $newText === false ) {
				return [ false, $flags ];
			}
			$text = $newText;
			// It will be put back in external storage again
			$flags = array_diff( $flags, [ 'external' ] );
		}
		if ( in_array( 'gzip', $flags ) ) {
			$newText = gzinflate( $text );
			if ( $newText === false ) {
				return [ false, $flags ];
			}
			$text = $newText;
			$flags = array_diff( $flags, [ 'gzip' ] );
		}
		return [ $text, $flags ];
	}

}

// @codeCoverageIgnoreStart
$maintClass = FixLegacyEncoding::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
