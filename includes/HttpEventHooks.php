<?php
/**
 * Utility class for creating and accessing recent change entries.
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
 * @author Eric Evans
 */

class HttpEventHooks {

	// XXX: No.
	private static $eventServiceUrl = 'http://localhost:8085/v1/topics';

	/** HTTP POST to event service */
	private static function send( $event ) {
		wfErrorLog( FormatJson::encode( $event ), "/var/www/data/debug.log" );

		/* $http = new MultiHttpClient( array() ); */
		/* $http->run( */
		/*	array( */
		/*		'url'	  => $eventServiceUrl, */
		/*		'method'  => 'POST', */
		/*		'body'	  => FormatJson::encode( $event ), */
		/*		'headers' => array( 'content-type' =>  'application/json' ) */
		/*	) */
		/* ); */
	}

	/** Event object stub */
	private static function createEvent() {
		return array(
			'meta' => array(
				'uri' => '/default/uri',
				'request_id' => UIDGenerator::newUUIDv4(),
				'id' => UIDGenerator::newUUIDv4(),
				'dt' => date('c', time()),
				'domain' => 'unset',
			),
		);
	}

	/**
	 * Occurs after the save page request has been processed.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/PageContentSaveComplete
	 *
	 * @param WikiPage $article
	 * @param User	   $user
	 * @param Content  $content
	 * @param string   $summary
	 * @param boolean  $isMinor
	 * @param boolean  $isWatch
	 * @param $section Deprecated
	 * @param integer  $flags
	 * @param {Revision|null} $revision
	 * @param Status   $status
	 * @param integer  $baseRevId
	 */
	public static function onPageContentSaveComplete( $article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId ) {
		$event = self::createEvent();
		$event['meta']['uri'] = '/edit/uri';
		$event['title'] = $article->getTitle()->getText();
		$event['page_id'] = $article->getId();
		$event['namespace'] = $article->getTitle()->getNamespace();
		$event['revision'] = $revision->getId();
		$event['base_revision'] = $baseRevId ? $baseRevId : $revision->getParentId();	 // XXX: ??
		$event['save_dt'] = wfTimestamp( TS_ISO_8601, $revision->getTimestamp() );
		$event['user_id'] = $user->getId();
		$event['user_text'] = $user->getName();
		$event['summary'] = $summary;

		self::send( $event );
	}

	/**
	 * Occurs after the delete article request has been processed.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ArticleDeleteComplete
	 *
	 * @param WikiPage $article	 the article that was deleted
	 * @param User	   $user	 the user that deleted the article
	 * @param string   $reason	 the reason the article was deleted
	 * @param int	   $id		 the ID of the article that was deleted
	 * @param		   $content	 the content of the deleted article, or null in case of error
	 * @param		   $logEntry the log entry used to record the deletion
	 */
	public static function onArticleDeleteComplete( $article, $user, $reason, $id, $content, $logEntry ) {
		$event = self::createEvent();
		$event['meta']['uri'] = '/delete/uri';
		$event['title'] = $article->getTitle()->getText();
		$event['page_id'] = $id;
		$event['user_id'] = $user->getId();
		$event['user_text'] = $user->getName();
		$event['summary'] = $reason;

		self::send( $event );
	}

	/**
	 * When one or more revisions of an article are restored.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ArticleUndelete
	 *
	 * @param Title	 $title		 title corresponding to the article restored
	 * @param		 $create	 whether or not the restoration caused the page to be created (i.e. it
								 didn't exist before)
	 * @param		 $comment	 comment explaining the undeletion
	 * @param int	 $oldPageId	 ID of page previously deleted (from archive table)
	 */
	public static function onArticleUndelete( Title $title, $create, $comment, $oldPageId ) {
		$event = self::createEvent();
		$event['meta']['uri'] = '/undelete/uri';
		$event['title'] = $article->getTitle()->getText();
		$event['new_page_id'] = $title->getArticleID();	   // XXX: ???
		$event['old_page_id'] = $oldPageId;
		$event['namespace'] = $article->getTitle()->getNamespace();
		$event['user_id'] = $user->getId();
		$event['user_text'] = $user->getName();
		$event['summary'] = $comment;

		self::send( $event );
	}

	/**
	 * Occurs whenever a request to move an article is completed.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/TitleMoveComplete
	 *
	 * @param Title	  $title	 the old title
	 * @param Title	  $newtitle	 the new title
	 * @param User	  $user		 User who did the move
	 * @param int	  $oldid	 database page_id of the page that's been moved
	 * @param int	  $newid	 database page_id of the created redirect, or 0 if suppressed
	 * @param string  $reason	 reason for the move
	 */
	public static function onTitleMoveComplete(	 Title $title, Title $newtitle, User $user, $oldid, $newid, $reason = null ) {
		$event = self::createEvent();
		$event['meta']['uri'] = '/move/uri';
		$event['new_title'] = $newtitle->getText();
		$event['old_title'] = $title->getText();
		$event['old_revision'] = $title->getLatestRevID();		// XXX: ???
		$event['new_revision'] = $newtitle->getLatestRevID();	// XXX: ???
		$event['user_id'] = $user->getId();
		$event['user_text'] = $user->getName();
		$event['summary'] = $reason;

		self::send( $event );
	}


	public static function onArticleRevisionVisibilitySet( $title, $revs ) {
		wfErrorLog( print_r( $title, true ), "/var/www/data/debug.log" );
		wfErrorLog( print_r( $revs, true ), "/var/www/data/debug.log" );
	}
}