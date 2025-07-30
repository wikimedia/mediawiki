<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author DannyS712
 */

namespace MediaWiki\Page;

/**
 * Service for mergehistory actions.
 *
 * Default implementation is MediaWiki\Page\PageCommandFactory.
 *
 * @since 1.35
 */
interface MergeHistoryFactory {

	/**
	 * @param PageIdentity $source
	 * @param PageIdentity $destination
	 * @param string|null $timestamp
	 * @return MergeHistory
	 */
	public function newMergeHistory(
		PageIdentity $source,
		PageIdentity $destination,
		?string $timestamp = null,
		?string $timestampOld = null
	): MergeHistory;
}
