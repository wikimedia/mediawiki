<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo;

/**
 * A foreign repo that implement support for API queries.
 *
 * Extension file repos should implement this if they support making API queries
 * against the foreign repos. Media handler extensions (e.g. TimedMediaHandler)
 * can look for this interface if they need to look up additional information.
 * However, media handler extensions are encouraged to only use direct api calls
 * as a last resort, and try to use other methods to get the information they
 * need instead.
 *
 * @since 1.38
 * @ingroup FileRepo
 * @stable to implement
 */
interface IForeignRepoWithMWApi {
	/**
	 * Make an API query in the foreign repo, caching results
	 *
	 * @note action=query, format=json, redirects=true and uselang are automatically set.
	 * @param array $query Fields to pass to the query
	 * @return array|null
	 * @since 1.38
	 */
	public function fetchImageQuery( $query );
}

/** @deprecated class alias since 1.44 */
class_alias( IForeignRepoWithMWApi::class, 'IForeignRepoWithMWApi' );
