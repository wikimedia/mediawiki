<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

/**
 * @ingroup RecentChanges
 */
class RCCacheEntry extends RecentChange {
	/** @var string|null */
	public $curlink;
	/** @var string|null */
	public $difflink;
	/** @var string|null */
	public $lastlink;
	/** @var string|null */
	public $link;
	/** @var string|null */
	public $timestamp;
	/** @var bool|null */
	public $unpatrolled;
	/** @var string|null */
	public $userlink;
	/** @var string|null */
	public $usertalklink;
	/** @var bool|null */
	public $watched;
	/** @var string|null */
	public $watchlistExpiry;

	/**
	 * @param RecentChange $rc
	 * @return RCCacheEntry
	 */
	public static function newFromParent( $rc ) {
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;
		$rc2->setHighlights( $rc->getHighlights() );

		return $rc2;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RCCacheEntry::class, 'RCCacheEntry' );
