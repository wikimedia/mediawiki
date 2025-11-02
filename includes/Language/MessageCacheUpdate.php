<?php
/**
 * Message cache purging and in-place update handler for specific message page changes
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use MediaWiki\Deferred\DeferrableUpdate;
use MediaWiki\Deferred\MergeableUpdate;
use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;

/**
 * Message cache purging and in-place update handler for specific message page changes
 *
 * @ingroup Language
 * @internal For use by MessageCache only.
 * @since 1.32
 */
class MessageCacheUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var array[] Map of (language code => list of (DB key, DB key without code)) */
	private $replacements = [];

	/**
	 * @param string $code Language code
	 * @param string $title Message cache key with initial uppercase letter
	 * @param string $msg Message cache key with initial uppercase letter and without the code
	 */
	public function __construct( $code, $title, $msg ) {
		$this->replacements[$code][] = [ $title, $msg ];
	}

	public function merge( MergeableUpdate $update ) {
		/** @var self $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var self $update';

		foreach ( $update->replacements as $code => $messages ) {
			$this->replacements[$code] = array_merge( $this->replacements[$code] ?? [], $messages );
		}
	}

	public function doUpdate() {
		$messageCache = MediaWikiServices::getInstance()->getMessageCache();
		foreach ( $this->replacements as $code => $replacements ) {
			$messageCache->refreshAndReplaceInternal( $code, $replacements );
		}
	}
}
