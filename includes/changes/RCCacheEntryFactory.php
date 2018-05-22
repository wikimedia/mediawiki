<?php
/**
 * Creates a RCCacheEntry from a RecentChange to use in EnhancedChangesList
 *
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
use MediaWiki\Linker\LinkRenderer;

class RCCacheEntryFactory {

	/* @var IContextSource */
	private $context;

	/* @var string[] */
	private $messages;

	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	/**
	 * @param IContextSource $context
	 * @param string[] $messages
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct(
		IContextSource $context, $messages, LinkRenderer $linkRenderer
	) {
		$this->context = $context;
		$this->messages = $messages;
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * @param RecentChange $baseRC
	 * @param bool $watched
	 *
	 * @return RCCacheEntry
	 */
	public function newFromRecentChange( RecentChange $baseRC, $watched ) {
		$user = $this->context->getUser();
		$counter = $baseRC->counter;

		$cacheEntry = RCCacheEntry::newFromParent( $baseRC );

		// Should patrol-related stuff be shown?
		$cacheEntry->unpatrolled = ChangesList::isUnpatrolled( $baseRC, $user );

		$cacheEntry->watched = $cacheEntry->mAttribs['rc_type'] == RC_LOG ? false : $watched;
		$cacheEntry->numberofWatchingusers = $baseRC->numberofWatchingusers;

		$cacheEntry->link = $this->buildCLink( $cacheEntry );
		$cacheEntry->timestamp = $this->buildTimestamp( $cacheEntry );

		// Make "cur" and "diff" links.  Do not use link(), it is too slow if
		// called too many times (50% of CPU time on RecentChanges!).
		$showDiffLinks = $this->showDiffLinks( $cacheEntry, $user );

		$cacheEntry->difflink = $this->buildDiffLink( $cacheEntry, $showDiffLinks, $counter );
		$cacheEntry->curlink = $this->buildCurLink( $cacheEntry, $showDiffLinks, $counter );
		$cacheEntry->lastlink = $this->buildLastLink( $cacheEntry, $showDiffLinks );

		// Make user links
		$cacheEntry->userlink = $this->getUserLink( $cacheEntry );

		if ( !ChangesList::isDeleted( $cacheEntry, Revision::DELETED_USER ) ) {
			$cacheEntry->usertalklink = Linker::userToolLinks(
				$cacheEntry->mAttribs['rc_user'],
				$cacheEntry->mAttribs['rc_user_text']
			);
		}

		return $cacheEntry;
	}

	/**
	 * @param RecentChange $cacheEntry
	 * @param User $user
	 *
	 * @return bool
	 */
	private function showDiffLinks( RecentChange $cacheEntry, User $user ) {
		return ChangesList::userCan( $cacheEntry, Revision::DELETED_TEXT, $user );
	}

	/**
	 * @param RecentChange $cacheEntry
	 *
	 * @return string
	 */
	private function buildCLink( RecentChange $cacheEntry ) {
		$type = $cacheEntry->mAttribs['rc_type'];

		// New unpatrolled pages
		if ( $cacheEntry->unpatrolled && $type == RC_NEW ) {
			$clink = $this->linkRenderer->makeKnownLink( $cacheEntry->getTitle() );
		// Log entries
		} elseif ( $type == RC_LOG ) {
			$logType = $cacheEntry->mAttribs['rc_log_type'];

			if ( $logType ) {
				$clink = $this->getLogLink( $logType );
			} else {
				wfDebugLog( 'recentchanges', 'Unexpected log entry with no log type in recent changes' );
				$clink = $this->linkRenderer->makeLink( $cacheEntry->getTitle() );
			}
		// Log entries (old format) and special pages
		} elseif ( $cacheEntry->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			wfDebugLog( 'recentchanges', 'Unexpected special page in recentchanges' );
			$clink = '';
		// Edits
		} else {
			$clink = $this->linkRenderer->makeKnownLink( $cacheEntry->getTitle() );
		}

		return $clink;
	}

	private function getLogLink( $logType ) {
		$logtitle = SpecialPage::getTitleFor( 'Log', $logType );
		$logpage = new LogPage( $logType );
		$logname = $logpage->getName()->text();

		$logLink = $this->context->msg( 'parentheses' )
			->rawParams(
				$this->linkRenderer->makeKnownLink( $logtitle, $logname )
			)->escaped();

		return $logLink;
	}

	/**
	 * @param RecentChange $cacheEntry
	 *
	 * @return string
	 */
	private function buildTimestamp( RecentChange $cacheEntry ) {
		return $this->context->getLanguage()->userTime(
			$cacheEntry->mAttribs['rc_timestamp'],
			$this->context->getUser()
		);
	}

	/**
	 * @param RecentChange $recentChange
	 *
	 * @return array
	 */
	private function buildCurQueryParams( RecentChange $recentChange ) {
		return [
			'curid' => $recentChange->mAttribs['rc_cur_id'],
			'diff' => 0,
			'oldid' => $recentChange->mAttribs['rc_this_oldid']
		];
	}

	/**
	 * @param RecentChange $cacheEntry
	 * @param bool $showDiffLinks
	 * @param int $counter
	 *
	 * @return string
	 */
	private function buildCurLink( RecentChange $cacheEntry, $showDiffLinks, $counter ) {
		$queryParams = $this->buildCurQueryParams( $cacheEntry );
		$curMessage = $this->getMessage( 'cur' );
		$logTypes = [ RC_LOG ];

		if ( !$showDiffLinks || in_array( $cacheEntry->mAttribs['rc_type'], $logTypes ) ) {
			$curLink = $curMessage;
		} else {
			$curUrl = htmlspecialchars( $cacheEntry->getTitle()->getLinkURL( $queryParams ) );
			$curLink = "<a class=\"mw-changeslist-diff-cur\" href=\"$curUrl\">$curMessage</a>";
		}

		return $curLink;
	}

	/**
	 * @param RecentChange $recentChange
	 *
	 * @return array
	 */
	private function buildDiffQueryParams( RecentChange $recentChange ) {
		return [
			'curid' => $recentChange->mAttribs['rc_cur_id'],
			'diff' => $recentChange->mAttribs['rc_this_oldid'],
			'oldid' => $recentChange->mAttribs['rc_last_oldid']
		];
	}

	/**
	 * @param RecentChange $cacheEntry
	 * @param bool $showDiffLinks
	 * @param int $counter
	 *
	 * @return string
	 */
	private function buildDiffLink( RecentChange $cacheEntry, $showDiffLinks, $counter ) {
		$queryParams = $this->buildDiffQueryParams( $cacheEntry );
		$diffMessage = $this->getMessage( 'diff' );
		$logTypes = [ RC_NEW, RC_LOG ];

		if ( !$showDiffLinks ) {
			$diffLink = $diffMessage;
		} elseif ( in_array( $cacheEntry->mAttribs['rc_type'], $logTypes ) ) {
			$diffLink = $diffMessage;
		} elseif ( $cacheEntry->getAttribute( 'rc_type' ) == RC_CATEGORIZE ) {
			$rcCurId = $cacheEntry->getAttribute( 'rc_cur_id' );
			$pageTitle = Title::newFromID( $rcCurId );
			if ( $pageTitle === null ) {
				wfDebugLog( 'RCCacheEntryFactory', 'Could not get Title for rc_cur_id: ' . $rcCurId );
				return $diffMessage;
			}
			$diffUrl = htmlspecialchars( $pageTitle->getLinkURL( $queryParams ) );
			$diffLink = "<a class=\"mw-changeslist-diff\" href=\"$diffUrl\">$diffMessage</a>";
		} else {
			$diffUrl = htmlspecialchars( $cacheEntry->getTitle()->getLinkURL( $queryParams ) );
			$diffLink = "<a class=\"mw-changeslist-diff\" href=\"$diffUrl\">$diffMessage</a>";
		}

		return $diffLink;
	}

	/**
	 * Builds the link to the previous version
	 *
	 * @param RecentChange $cacheEntry
	 * @param bool $showDiffLinks
	 *
	 * @return string
	 */
	private function buildLastLink( RecentChange $cacheEntry, $showDiffLinks ) {
		$lastOldid = $cacheEntry->mAttribs['rc_last_oldid'];
		$lastMessage = $this->getMessage( 'last' );
		$type = $cacheEntry->mAttribs['rc_type'];
		$logTypes = [ RC_LOG ];

		// Make "last" link
		if ( !$showDiffLinks || !$lastOldid || in_array( $type, $logTypes ) ) {
			$lastLink = $lastMessage;
		} else {
			$lastLink = $this->linkRenderer->makeKnownLink(
				$cacheEntry->getTitle(),
				new HtmlArmor( $lastMessage ),
				[ 'class' => 'mw-changeslist-diff' ],
				$this->buildDiffQueryParams( $cacheEntry )
			);
		}

		return $lastLink;
	}

	/**
	 * @param RecentChange $cacheEntry
	 *
	 * @return string
	 */
	private function getUserLink( RecentChange $cacheEntry ) {
		if ( ChangesList::isDeleted( $cacheEntry, Revision::DELETED_USER ) ) {
			$userLink = ' <span class="history-deleted">' .
				$this->context->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			$userLink = Linker::userLink(
				$cacheEntry->mAttribs['rc_user'],
				$cacheEntry->mAttribs['rc_user_text'],
				ExternalUserNames::getLocal( $cacheEntry->mAttribs['rc_user_text'] )
			);
		}

		return $userLink;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	private function getMessage( $key ) {
		return $this->messages[$key];
	}

}
