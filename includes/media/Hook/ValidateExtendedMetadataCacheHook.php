<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ValidateExtendedMetadataCacheHook {
	/**
	 * Called to validate the cached metadata in
	 * FormatMetadata::getExtendedMeta (return false means cache will be
	 * invalidated and GetExtendedMetadata hook called again).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $timestamp The timestamp metadata was generated
	 * @param ?mixed $file The file the metadata is for
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onValidateExtendedMetadataCache( $timestamp, $file );
}
