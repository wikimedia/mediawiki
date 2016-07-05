<?php

namespace MediaWiki\Storage;

/**
 * Service interface for retrieving information associated with a revision.
 * Such information may be persistent or purely virtual, it may be a single
 * value or a complex data object.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface RevisionInfoLookup {

	/**
	 * Returns information associated with the revision.
	 *
	 * @param int $revisionId
	 * @param string $name
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return mixed
	 */
	public function getRevisionInfo( $revisionId, $name );

}
