<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\Feed\ChannelFeed;
use MediaWiki\Feed\FeedItem;
use MediaWiki\Feed\FeedUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * XML feed for Special:RecentChanges and Special:RecentChangesLinked.
 *
 * @ingroup RecentChanges
 * @ingroup Feed
 */
class ChangesFeed {
	/** @var string */
	private $format;

	/**
	 * @param string $format Feed's format (either 'rss' or 'atom')
	 */
	public function __construct( $format ) {
		$this->format = $format;
	}

	/**
	 * Get a MediaWiki\Feed\ChannelFeed subclass object to use
	 *
	 * @param string $title Feed's title
	 * @param string $description Feed's description
	 * @param string $url Url of origin page
	 * @return ChannelFeed|bool MediaWiki\Feed\ChannelFeed subclass or false on failure
	 */
	public function getFeedObject( $title, $description, $url ) {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$sitename = $mainConfig->get( MainConfigNames::Sitename );
		$languageCode = $mainConfig->get( MainConfigNames::LanguageCode );
		$feedClasses = $mainConfig->get( MainConfigNames::FeedClasses );
		'@phan-var array<string,class-string<ChannelFeed>> $feedClasses';
		if ( !isset( $feedClasses[$this->format] ) ) {
			return false;
		}

		if ( !array_key_exists( $this->format, $feedClasses ) ) {
			// falling back to atom
			$this->format = 'atom';
		}

		$feedTitle = "{$sitename}  - {$title} [{$languageCode}]";
		return new $feedClasses[$this->format](
			$feedTitle, htmlspecialchars( $description ), $url );
	}

	/**
	 * Generate the feed items given a row from the database.
	 * @param IResultWrapper $rows IDatabase resource with recentchanges rows
	 * @return array
	 * @suppress PhanTypeInvalidDimOffset False positives in the foreach
	 */
	public static function buildItems( $rows ) {
		$items = [];

		# Merge adjacent edits by one user
		$sorted = [];
		$n = 0;
		foreach ( $rows as $obj ) {
			if ( $obj->rc_type == RC_EXTERNAL ) {
				continue;
			}

			if ( $n > 0 &&
				$obj->rc_type == RC_EDIT &&
				$obj->rc_namespace >= 0 &&
				$obj->rc_cur_id == $sorted[$n - 1]->rc_cur_id &&
				$obj->rc_user_text == $sorted[$n - 1]->rc_user_text ) {
				$sorted[$n - 1]->rc_last_oldid = $obj->rc_last_oldid;
			} else {
				$sorted[$n] = $obj;
				$n++;
			}
		}

		$services = MediaWikiServices::getInstance();
		$commentFormatter = $services->getRowCommentFormatter();
		$formattedComments = $commentFormatter->formatItems(
			$commentFormatter->rows( $rows )
				->commentKey( 'rc_comment' )
				->indexField( 'rc_id' )
		);

		$nsInfo = $services->getNamespaceInfo();
		foreach ( $sorted as $obj ) {
			$title = Title::makeTitle( $obj->rc_namespace, $obj->rc_title );
			$talkpage = $nsInfo->hasTalkNamespace( $obj->rc_namespace ) && $title->canExist()
				? $title->getTalkPage()->getFullURL()
				: '';

			// Skip items with deleted content (avoids partially complete/inconsistent output)
			if ( $obj->rc_deleted ) {
				continue;
			}

			if ( $obj->rc_this_oldid ) {
				$url = $title->getFullURL( [
					'diff' => $obj->rc_this_oldid,
					'oldid' => $obj->rc_last_oldid,
				] );
			} else {
				// log entry or something like that.
				$url = $title->getFullURL();
			}

			$items[] = new FeedItem(
				$title->getPrefixedText(),
				FeedUtils::formatDiff( $obj, $formattedComments[$obj->rc_id] ),
				$url,
				$obj->rc_timestamp,
				( $obj->rc_deleted & RevisionRecord::DELETED_USER )
					? wfMessage( 'rev-deleted-user' )->escaped() : $obj->rc_user_text,
				$talkpage
			);
		}

		return $items;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesFeed::class, 'ChangesFeed' );
