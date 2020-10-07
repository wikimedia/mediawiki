<?php

namespace MediaWiki\Hook;

use File;
use IContextSource;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetExtendedMetadataHook {
	/**
	 * Use this hook to get extended file metadata for the API.
	 *
	 * @since 1.35
	 *
	 * @param array &$combinedMeta Array of the form:
	 *   	'MetadataPropName' => [
	 *   		value' => prop value,
	 *   		'source' => 'name of hook'
	 *   	 ]
	 * @param File $file File in question
	 * @param IContextSource $context RequestContext (including language to use)
	 * @param bool $single Only extract the current language; if false, the prop value should
	 *   be in the metadata multi-language array format:
	 *   https://mediawiki.org/wiki/Manual:File_metadata_handling#Multi-language_array_format
	 * @param int &$maxCacheTime How long the results can be cached
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetExtendedMetadata( &$combinedMeta, $file, $context,
		$single, &$maxCacheTime
	);
}
