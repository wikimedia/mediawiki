<?php
/**
 * Contain log classes
 *
 * Copyright © 2002, 2004 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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

namespace MediaWiki\Logging;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\StubObject\StubUserLang;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;

/**
 * Class to simplify the use of log pages.
 * The logs are now kept in a table which is easier to manage and trim
 * than ever-growing wiki pages.
 *
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should become a stateless service, use the command pattern.
 */
class LogPage {
	public const DELETED_ACTION = 1;
	public const DELETED_COMMENT = 2;
	public const DELETED_USER = 4;
	public const DELETED_RESTRICTED = 8;

	// Convenience fields
	public const SUPPRESSED_USER = self::DELETED_USER | self::DELETED_RESTRICTED;
	public const SUPPRESSED_ACTION = self::DELETED_ACTION | self::DELETED_RESTRICTED;

	/** @var bool */
	public $updateRecentChanges;

	/** @var bool */
	public $sendToUDP;

	/** @var string Plaintext version of the message for IRC */
	private $ircActionText;

	/** @var string Plaintext version of the message */
	private $actionText;

	/** @var string One of '', 'block', 'protect', 'rights', 'delete',
	 *    'upload', 'move'
	 */
	private $type;

	/** @var string One of '', 'block', 'protect', 'rights', 'delete',
	 *   'upload', 'move', 'move_redir'
	 */
	private $action;

	/** @var string Comment associated with action */
	private $comment;

	/** @var string Blob made of a parameters array */
	private $params;

	/** @var UserIdentity The user doing the action */
	private $performer;

	/** @var Title */
	private $target;

	/**
	 * @stable to call
	 * @param string $type One of '', 'block', 'protect', 'rights', 'delete',
	 *   'upload', 'move'
	 * @param bool $rc Whether to update recent changes as well as the logging table
	 * @param string $udp Pass 'UDP' to send to the UDP feed if NOT sent to RC
	 */
	public function __construct( $type, $rc = true, $udp = 'skipUDP' ) {
		$this->type = $type;
		$this->updateRecentChanges = $rc;
		$this->sendToUDP = ( $udp == 'UDP' );
	}

	/**
	 * @return int The log_id of the inserted log entry
	 */
	protected function saveContent() {
		$services = MediaWikiServices::getInstance();
		$logRestrictions = $services->getMainConfig()->get( MainConfigNames::LogRestrictions );
		$recentChangeStore = $services->getRecentChangeStore();
		$recentChangeRCFeedNotifier = $services->getRecentChangeRCFeedNotifier();
		$dbw = $services->getConnectionProvider()->getPrimaryDatabase();

		$now = wfTimestampNow();
		$actorId = $services->getActorNormalization()
			->acquireActorId( $this->performer, $dbw );
		$data = [
			'log_type' => $this->type,
			'log_action' => $this->action,
			'log_timestamp' => $dbw->timestamp( $now ),
			'log_actor' => $actorId,
			'log_namespace' => $this->target->getNamespace(),
			'log_title' => $this->target->getDBkey(),
			'log_page' => $this->target->getArticleID(),
			'log_params' => $this->params
		];
		$data += $services->getCommentStore()->insert(
			$dbw,
			'log_comment',
			$this->comment
		);
		$dbw->newInsertQueryBuilder()
			->insertInto( 'logging' )
			->row( $data )
			->caller( __METHOD__ )->execute();
		$newId = $dbw->insertId();

		// Don't add private logs to RC or send them to UDP
		if ( isset( $logRestrictions[$this->type] ) && $logRestrictions[$this->type] != '*' ) {
			return $newId;
		}

		if ( $this->updateRecentChanges ) {
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );

			$recentChange = $recentChangeStore->createLogRecentChange(
				$now, $titleObj, $this->performer, $this->getRcComment(), '',
				$this->type, $this->action, $this->target, $this->comment,
				$this->params, $newId, $this->getRcCommentIRC()
			);
			$recentChangeStore->insertRecentChange( $recentChange );
		} elseif ( $this->sendToUDP ) {
			// Notify external application via UDP.
			// We send this to IRC but do not want to add it the RC table.
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );
			$recentChange = $recentChangeStore->createLogRecentChange(
				$now, $titleObj, $this->performer, $this->getRcComment(), '',
				$this->type, $this->action, $this->target, $this->comment,
				$this->params, $newId, $this->getRcCommentIRC()
			);
			$recentChangeRCFeedNotifier->notifyRCFeeds( $recentChange );
		}

		return $newId;
	}

	/**
	 * Get the RC comment from the last addEntry() call
	 *
	 * @return string
	 */
	public function getRcComment() {
		$rcComment = $this->actionText;

		if ( $this->comment != '' ) {
			if ( $rcComment == '' ) {
				$rcComment = $this->comment;
			} else {
				$rcComment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() .
					$this->comment;
			}
		}

		return $rcComment;
	}

	/**
	 * Get the RC comment from the last addEntry() call for IRC
	 *
	 * @return string
	 */
	public function getRcCommentIRC() {
		$rcComment = $this->ircActionText;

		if ( $this->comment != '' ) {
			if ( $rcComment == '' ) {
				$rcComment = $this->comment;
			} else {
				$rcComment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() .
					$this->comment;
			}
		}

		return $rcComment;
	}

	/**
	 * Get the comment from the last addEntry() call
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * Get the list of valid log types
	 *
	 * @return string[]
	 */
	public static function validTypes() {
		$logTypes = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LogTypes );

		return $logTypes;
	}

	/**
	 * Is $type a valid log type
	 *
	 * @param string $type Log type to check
	 * @return bool
	 */
	public static function isLogType( $type ) {
		return in_array( $type, self::validTypes() );
	}

	/**
	 * Generate text for a log entry.
	 * Only LogFormatter should call this function.
	 *
	 * @param string $type Log type
	 * @param string $action Log action
	 * @param Title|null $title
	 * @param Skin|null $skin Skin object or null. If null, we want to use the wiki
	 *   content language, since that will go to the IRC feed.
	 * @param array $params
	 * @param bool $filterWikilinks Whether to filter wiki links
	 * @return string HTML
	 */
	public static function actionText( $type, $action, $title = null, $skin = null,
		$params = [], $filterWikilinks = false
	) {
		global $wgLang;
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$key = "$type/$action";

		$logActions = $config->get( MainConfigNames::LogActions );

		if ( isset( $logActions[$key] ) ) {
			$message = $logActions[$key];
		} else {
			wfDebug( "LogPage::actionText - unknown action $key" );
			$message = "log-unknown-action";
			$params = [ $key ];
		}

		if ( $skin === null ) {
			$langObj = MediaWikiServices::getInstance()->getContentLanguage();
			$langObjOrNull = null;
		} else {
			// TODO Is $skin->getLanguage() safe here?
			StubUserLang::unstub( $wgLang );
			$langObj = $wgLang;
			$langObjOrNull = $wgLang;
		}
		if ( $title === null ) {
			$rv = wfMessage( $message )->inLanguage( $langObj )->escaped();
		} else {
			$titleLink = self::getTitleLink( $title, $langObjOrNull );

			if ( count( $params ) == 0 ) {
				$rv = wfMessage( $message )->rawParams( $titleLink )
					->inLanguage( $langObj )->escaped();
			} else {
				array_unshift( $params, $titleLink );

				$rv = wfMessage( $message )->rawParams( $params )
						->inLanguage( $langObj )->escaped();
			}
		}

		// For the perplexed, this feature was added in r7855 by Erik.
		// The feature was added because we liked adding [[$1]] in our log entries
		// but the log entries are parsed as Wikitext on RecentChanges but as HTML
		// on Special:Log. The hack is essentially that [[$1]] represented a link
		// to the title in question. The first parameter to the HTML version (Special:Log)
		// is that link in HTML form, and so this just gets rid of the ugly [[]].
		// However, this is a horrible hack and it doesn't work like you expect if, say,
		// you want to link to something OTHER than the title of the log entry.
		// The real problem, which Erik was trying to fix (and it sort-of works now) is
		// that the same messages are being treated as both wikitext *and* HTML.
		if ( $filterWikilinks ) {
			$rv = str_replace( '[[', '', $rv );
			$rv = str_replace( ']]', '', $rv );
		}

		return $rv;
	}

	/**
	 * @param Title $title
	 * @param ?Language $lang
	 * @return string HTML
	 */
	private static function getTitleLink( Title $title, ?Language $lang ): string {
		if ( !$lang ) {
			return $title->getPrefixedText();
		}

		$services = MediaWikiServices::getInstance();
		$linkRenderer = $services->getLinkRenderer();

		if ( $title->isSpecialPage() ) {
			[ $name, $par ] = $services->getSpecialPageFactory()->resolveAlias( $title->getDBkey() );

			if ( $name === 'Log' ) {
				$logPage = new LogPage( $par );
				return wfMessage( 'parentheses' )
					->rawParams( $linkRenderer->makeLink( $title, $logPage->getName()->text() ) )
					->inLanguage( $lang )
					->escaped();
			}
		}

		return $linkRenderer->makeLink( $title );
	}

	/**
	 * Add a log entry
	 *
	 * @param string $action One of '', 'block', 'protect', 'rights', 'delete',
	 *   'upload', 'move', 'move_redir'
	 * @param Title $target
	 * @param string|null $comment Description associated
	 * @param array $params Parameters passed later to wfMessage function
	 * @param int|UserIdentity $performer The user doing the action, or their user id.
	 *   Calling with user ID is deprecated since 1.36.
	 *
	 * @return int The log_id of the inserted log entry
	 */
	public function addEntry( $action, $target, $comment, $params, $performer ) {
		// FIXME $params is only documented to accept an array
		if ( !is_array( $params ) ) {
			$params = [ $params ];
		}

		# Trim spaces on user supplied text
		$comment = trim( $comment ?? '' );

		$this->action = $action;
		$this->target = $target;
		$this->comment = $comment;
		$this->params = self::makeParamBlob( $params );

		if ( !is_object( $performer ) ) {
			$performer = User::newFromId( $performer );
		}

		$this->performer = $performer;

		$logEntry = new ManualLogEntry( $this->type, $action );
		$logEntry->setTarget( $target );
		$logEntry->setPerformer( $performer );
		$logEntry->setParameters( $params );
		// All log entries using the LogPage to insert into the logging table
		// are using the old logging system and therefore the legacy flag is
		// needed to say the LogFormatter the parameters have numeric keys
		$logEntry->setLegacy( true );

		$formatter = MediaWikiServices::getInstance()->getLogFormatterFactory()->newFromEntry( $logEntry );
		$context = RequestContext::newExtraneousContext( $target );
		$formatter->setContext( $context );

		$this->actionText = $formatter->getPlainActionText();
		$this->ircActionText = $formatter->getIRCActionText();

		return $this->saveContent();
	}

	/**
	 * Create a blob from a parameter array
	 *
	 * @param array $params
	 * @return string
	 */
	public static function makeParamBlob( $params ) {
		return implode( "\n", $params );
	}

	/**
	 * Extract a parameter array from a blob
	 *
	 * @param string $blob
	 * @return array
	 */
	public static function extractParams( $blob ) {
		if ( $blob === '' ) {
			return [];
		} else {
			return explode( "\n", $blob );
		}
	}

	/**
	 * Name of the log.
	 * @return Message
	 * @since 1.19
	 */
	public function getName() {
		$logNames = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LogNames );

		// BC
		$key = $logNames[$this->type] ?? 'log-name-' . $this->type;

		return wfMessage( $key );
	}

	/**
	 * Description of this log type.
	 * @return Message
	 * @since 1.19
	 */
	public function getDescription() {
		$logHeaders = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LogHeaders );
		// BC
		$key = $logHeaders[$this->type] ?? 'log-description-' . $this->type;

		return wfMessage( $key );
	}

	/**
	 * Returns the right needed to read this log type.
	 * @return string
	 * @since 1.19
	 */
	public function getRestriction() {
		$logRestrictions = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LogRestrictions );
		// The empty string fallback will
		// always return true in permission check
		return $logRestrictions[$this->type] ?? '';
	}

	/**
	 * Tells if this log is not viewable by all.
	 * @return bool
	 * @since 1.19
	 */
	public function isRestricted() {
		$restriction = $this->getRestriction();

		return $restriction !== '' && $restriction !== '*';
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LogPage::class, 'LogPage' );
