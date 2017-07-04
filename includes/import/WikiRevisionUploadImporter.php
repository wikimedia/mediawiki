<?php

/**
 * @since 1.30
 */
interface WikiRevisionUploadImporter {

	/**
	 * @since 1.31
	 *
	 * @param WikiRevisionUpload $wikiRevision
	 *
	 * @return StatusValue On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	public function import( WikiRevisionUpload $wikiRevision );

}
