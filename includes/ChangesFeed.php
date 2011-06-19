<?php

/**
 * Feed to Special:RecentChanges and Special:RecentChangesLiked
 *
 * @ingroup Feed
 */
class ChangesFeed {
	public $format, $type, $titleMsg, $descMsg;

	/**
	 * Constructor
	 *
	 * @param $format String: feed's format (either 'rss' or 'atom')
	 * @param $type String: type of feed (for cache keys)
	 */
	public function __construct( $format, $type ) {
		$this->format = $format;
		$this->type = $type;
	}

	/**
	 * Get a ChannelFeed subclass object to use
	 *
	 * @param $title String: feed's title
	 * @param $description String: feed's description
	 * @param $url String: url of origin page
	 * @return ChannelFeed subclass or false on failure
	 */
	public function getFeedObject( $title, $description, $url ) {
		global $wgSitename, $wgLanguageCode, $wgFeedClasses;

		if ( !isset( $wgFeedClasses[$this->format] ) ) {
			return false;
		}

		$feedTitle = "$wgSitename  - {$title} [$wgLanguageCode]";
		return new $wgFeedClasses[$this->format](
			$feedTitle, htmlspecialchars( $description ), $url );
	}

	/**
	 * Generates feed's content
	 *
	 * @param $feed ChannelFeed subclass object (generally the one returned by getFeedObject())
	 * @param $rows ResultWrapper object with rows in recentchanges table
	 * @param $lastmod Integer: timestamp of the last item in the recentchanges table (only used for the cache key)
	 * @param $opts FormOptions as in SpecialRecentChanges::getDefaultOptions()
	 * @return null or true
	 */
	public function execute( $feed, $rows, $lastmod, $opts ) {
		global $wgLang, $wgRenderHashAppend;

		if ( !FeedUtils::checkFeedOutput( $this->format ) ) {
			return;
		}

		$optionsHash = md5( serialize( $opts->getAllValues() ) ) . $wgRenderHashAppend;
		$timekey = wfMemcKey( $this->type, $this->format, $wgLang->getCode(), $optionsHash, 'timestamp' );
		$key = wfMemcKey( $this->type, $this->format, $wgLang->getCode(), $optionsHash );

		FeedUtils::checkPurge( $timekey, $key );

		/**
		 * Bumping around loading up diffs can be pretty slow, so where
		 * possible we want to cache the feed output so the next visitor
		 * gets it quick too.
		 */
		$cachedFeed = $this->loadFromCache( $lastmod, $timekey, $key );
		if( is_string( $cachedFeed ) ) {
			wfDebug( "RC: Outputting cached feed\n" );
			$feed->httpHeaders();
			echo $cachedFeed;
		} else {
			wfDebug( "RC: rendering new feed and caching it\n" );
			ob_start();
			self::generateFeed( $rows, $feed );
			$cachedFeed = ob_get_contents();
			ob_end_flush();
			$this->saveToCache( $cachedFeed, $timekey, $key );
		}
		return true;
	}

	/**
	 * Save to feed result to $messageMemc
	 *
	 * @param $feed String: feed's content
	 * @param $timekey String: memcached key of the last modification
	 * @param $key String: memcached key of the content
	 */
	public function saveToCache( $feed, $timekey, $key ) {
		global $messageMemc;
		$expire = 3600 * 24; # One day
		$messageMemc->set( $key, $feed, $expire );
		$messageMemc->set( $timekey, wfTimestamp( TS_MW ), $expire );
	}

	/**
	 * Try to load the feed result from $messageMemc
	 *
	 * @param $lastmod Integer: timestamp of the last item in the recentchanges table
	 * @param $timekey String: memcached key of the last modification
	 * @param $key String: memcached key of the content
	 * @return feed's content on cache hit or false on cache miss
	 */
	public function loadFromCache( $lastmod, $timekey, $key ) {
		global $wgFeedCacheTimeout, $wgOut, $messageMemc;

		$feedLastmod = $messageMemc->get( $timekey );

		if( ( $wgFeedCacheTimeout > 0 ) && $feedLastmod ) {
		    /**
			 * If the cached feed was rendered very recently, we may
			 * go ahead and use it even if there have been edits made
			 * since it was rendered. This keeps a swarm of requests
			 * from being too bad on a super-frequently edited wiki.
			 */

			$feedAge = time() - wfTimestamp( TS_UNIX, $feedLastmod );
			$feedLastmodUnix = wfTimestamp( TS_UNIX, $feedLastmod );
			$lastmodUnix = wfTimestamp( TS_UNIX, $lastmod );

			if( $feedAge < $wgFeedCacheTimeout || $feedLastmodUnix > $lastmodUnix) {
				wfDebug( "RC: loading feed from cache ($key; $feedLastmod; $lastmod)...\n" );
				if ( $feedLastmodUnix < $lastmodUnix ) {
					$wgOut->setLastModified( $feedLastmod ); // bug 21916
				}
				return $messageMemc->get( $key );
			} else {
				wfDebug( "RC: cached feed timestamp check failed ($feedLastmod; $lastmod)\n" );
			}
		}
		return false;
	}

	/**
	 * Generate the feed items given a row from the database.
	 * @param $rows DatabaseBase resource with recentchanges rows
	 * @param $feed Feed object
	 */
	public static function generateFeed( $rows, &$feed ) {
		wfProfileIn( __METHOD__ );

		$feed->outHeader();

		# Merge adjacent edits by one user
		$sorted = array();
		$n = 0;
		foreach( $rows as $obj ) {
			if( $n > 0 &&
				$obj->rc_type == RC_EDIT &&
				$obj->rc_namespace >= 0 &&
				$obj->rc_cur_id == $sorted[$n-1]->rc_cur_id &&
				$obj->rc_user_text == $sorted[$n-1]->rc_user_text ) {
				$sorted[$n-1]->rc_last_oldid = $obj->rc_last_oldid;
			} else {
				$sorted[$n] = $obj;
				$n++;
			}
		}

		foreach( $sorted as $obj ) {
			$title = Title::makeTitle( $obj->rc_namespace, $obj->rc_title );
			$talkpage = MWNamespace::canTalk( $obj->rc_namespace ) ? $title->getTalkPage()->getFullUrl() : '';
			// Skip items with deleted content (avoids partially complete/inconsistent output)
			if( $obj->rc_deleted ) continue;

			if ( $obj->rc_this_oldid ) {
				$url = $title->getFullURL(
					'diff=' . $obj->rc_this_oldid .
					'&oldid=' . $obj->rc_last_oldid
				);
			} else {
				// log entry or something like that.
				$url = $title->getFullURL();
			}

			$item = new FeedItem(
				$title->getPrefixedText(),
				FeedUtils::formatDiff( $obj ),
				$url,
				$obj->rc_timestamp,
				($obj->rc_deleted & Revision::DELETED_USER) ? wfMsgHtml('rev-deleted-user') : $obj->rc_user_text,
				$talkpage
			);
			$feed->outItem( $item );
		}
		$feed->outFooter();
		wfProfileOut( __METHOD__ );
	}

}
