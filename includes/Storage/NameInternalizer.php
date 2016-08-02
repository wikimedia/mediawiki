<?php

namespace MediaWiki\Storage;

use InvalidArgumentException;

/**
 * Service interface for managing the internalization of names.
 *
 * The idea is that extensions can register unique names, which will be mapped to
 * integers for efficient storage and indexing in the database.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface NameInternalizer {

	/**
	 * @param int $internalId
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function getName( $internalId );

	/**
	 * Returns an internal ID for the given name.
	 *
	 * @note Implementations must make a best effort to create a persistent mapping if none
	 * exists. Only if creating such a mapping fails should this method throw an exception.
	 *
	 * @param string $name
	 *
	 * @throws InvalidArgumentException
	 * @return int
	 */
	public function getInternalId( $name );

}
