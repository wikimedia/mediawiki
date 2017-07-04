<?php

/**
 * @since 1.31
 */
interface WikiRevisionOldRevisionImporter {

	/**
	 * @since 1.31
	 *
	 * @param WikiRevisionOldRevision $wikiRevision
	 *
	 * @return bool Success
	 */
	public function import( WikiRevisionOldRevision $wikiRevision );

}
