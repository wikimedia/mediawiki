<?php

/**
 * @since 1.30
 */
interface WikiRevisionOldRevisionImporter {

	/**
	 * @since 1.30
	 *
	 * @param WikiRevisionOldRevision $wikiRevision
	 *
	 * @return bool Success
	 */
	public function import( WikiRevisionOldRevision $wikiRevision );

}
