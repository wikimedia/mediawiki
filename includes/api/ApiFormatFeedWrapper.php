<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Feed\ChannelFeed;
use MediaWiki\Feed\FeedItem;

/**
 * This printer is used to wrap an instance of the Feed class
 * @ingroup API
 */
class ApiFormatFeedWrapper extends ApiFormatBase {

	public function __construct( ApiMain $main ) {
		parent::__construct( $main, 'feed' );
	}

	/**
	 * Call this method to initialize output data. See execute()
	 * @param ApiResult $result
	 * @param ChannelFeed $feed An instance of one of the $wgFeedClasses classes
	 * @param FeedItem[] $feedItems
	 */
	public static function setResult( $result, $feed, $feedItems ) {
		// Store output in the Result data.
		// This way we can check during execution if any error has occurred
		// Disable size checking for this because we can't continue
		// cleanly; size checking would cause more problems than it'd
		// solve
		$result->addValue( null, '_feed', $feed, ApiResult::NO_VALIDATE );
		$result->addValue( null, '_feeditems', $feedItems, ApiResult::NO_VALIDATE );
	}

	/**
	 * Feed does its own headers
	 *
	 * @return null
	 */
	public function getMimeType() {
		return null;
	}

	/**
	 * MediaWiki\Feed\ChannelFeed doesn't give us a method to print errors in a friendly
	 * manner, so just punt errors to the default printer.
	 * @return bool
	 */
	public function canPrintErrors() {
		return false;
	}

	/**
	 * This class expects the result data to be in a custom format set by self::setResult()
	 * $result['_feed'] - an instance of one of the $wgFeedClasses classes
	 * $result['_feeditems'] - an array of MediaWiki\Feed\FeedItem instances
	 * @param bool $unused
	 */
	public function initPrinter( $unused = false ) {
		parent::initPrinter( $unused );

		if ( $this->isDisabled() ) {
			return;
		}

		$data = $this->getResult()->getResultData();
		if ( isset( $data['_feed'] ) && isset( $data['_feeditems'] ) ) {
			/** @var ChannelFeed $feed */
			$feed = $data['_feed'];
			'@phan-var ChannelFeed $feed';

			$feed->httpHeaders();
		} else {
			// Error has occurred, print something useful
			ApiBase::dieDebug( __METHOD__, 'Invalid feed class/item' );
		}
	}

	/**
	 * This class expects the result data to be in a custom format set by self::setResult()
	 * $result['_feed'] - an instance of one of the $wgFeedClasses classes
	 * $result['_feeditems'] - an array of MediaWiki\Feed\FeedItem instances
	 */
	public function execute() {
		$data = $this->getResult()->getResultData();
		if ( isset( $data['_feed'] ) && isset( $data['_feeditems'] ) ) {
			/** @var ChannelFeed $feed */
			$feed = $data['_feed'];
			$items = $data['_feeditems'];

			'@phan-var ChannelFeed $feed';
			'@phan-var FeedItem[] $items';

			// execute() needs to pass strings to $this->printText, not produce output itself.
			ob_start();
			$feed->outHeader();
			foreach ( $items as $item ) {
				$feed->outItem( $item );
			}
			$feed->outFooter();
			$this->printText( ob_get_clean() );
		} else {
			// Error has occurred, print something useful
			ApiBase::dieDebug( __METHOD__, 'Invalid feed class/item' );
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiFormatFeedWrapper::class, 'ApiFormatFeedWrapper' );
