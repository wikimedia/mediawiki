<?php

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
