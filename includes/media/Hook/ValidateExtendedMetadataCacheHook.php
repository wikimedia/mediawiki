<?php

namespace MediaWiki\Hook;

use File;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ValidateExtendedMetadataCacheHook {
	/**
	 * Use this hook to validate the cached metadata in
	 * FormatMetadata::getExtendedMeta.
	 *
	 * @since 1.35
	 *
	 * @param string $timestamp Timestamp metadata was generated
	 * @param File $file File the metadata is for
	 * @return bool|void True or no return value to continue, or false to
	 *   invalidate cache and call GetExtendedMetadata hook again
	 */
	public function onValidateExtendedMetadataCache( $timestamp, $file );
}
