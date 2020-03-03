<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetExtendedMetadataHook {
	/**
	 * Get extended file metadata for the API
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$combinedMeta Array of the form:
	 *   	'MetadataPropName' => [
	 *   		value' => prop value,
	 *   		'source' => 'name of hook'
	 *   	 ].
	 * @param ?mixed $file File object of file in question
	 * @param ?mixed $context RequestContext (including language to use)
	 * @param ?mixed $single Only extract the current language; if false, the prop value should
	 *   be in the metadata multi-language array format:
	 *   mediawiki.org/wiki/Manual:File_metadata_handling#Multi-language_array_format
	 * @param ?mixed &$maxCacheTime how long the results can be cached
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetExtendedMetadata( &$combinedMeta, $file, $context,
		$single, &$maxCacheTime
	);
}
