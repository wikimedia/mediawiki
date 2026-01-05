<?php

namespace MediaWiki\Import;

/**
 * @since 1.31
 */
interface OldRevisionImporter {

	/**
	 * @since 1.31
	 *
	 * @param ImportableOldRevision $importableRevision
	 *
	 * @return bool Success
	 */
	public function import( ImportableOldRevision $importableRevision );

}

/** @deprecated class alias since 1.46 */
class_alias( OldRevisionImporter::class, 'OldRevisionImporter' );
