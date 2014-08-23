<?php
/**
 * Contain log classes
 *
 * Copyright © 2002, 2004 Brion Vibber <brion@pobox.com>
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

/**
 * Class to simplify the use of log pages.
 * The logs are now kept in a table which is easier to manage and trim
 * than ever-growing wiki pages.
 *
 */
class LogPage {
	const DELETED_ACTION = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;

	// Convenience fields
	const SUPPRESSED_USER = 12;
	const SUPPRESSED_ACTION = 9;

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
	 *   'upload', 'move', 'move_redir' */
	private $action;

	/** @var string Comment associated with action */
	private $comment;

	/** @var string Blob made of a parameters array */
	private $params;

	/** @var User The user doing the action */
	private $doer;

	/** @var Title */
	private $target;

	/**
	 * Constructor
	 *
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
		global $wgLogRestrictions;

		$dbw = wfGetDB( DB_MASTER );
		$log_id = $dbw->nextSequenceValue( 'logging_log_id_seq' );

		// @todo FIXME private/protected/public property?
		$this->timestamp = $now = wfTimestampNow();
		$data = array(
			'log_id' => $log_id,
			'log_type' => $this->type,
			'log_action' => $this->action,
			'log_timestamp' => $dbw->timestamp( $now ),
			'log_user' => $this->doer->getId(),
			'log_user_text' => $this->doer->getName(),
			'log_namespace' => $this->target->getNamespace(),
			'log_title' => $this->target->getDBkey(),
			'log_page' => $this->target->getArticleID(),
			'log_comment' => $this->comment,
			'log_params' => $this->params
		);
		$dbw->insert( 'logging', $data, __METHOD__ );
		$newId = !is_null( $log_id ) ? $log_id : $dbw->insertId();

		# And update recentchanges
		if ( $this->updateRecentChanges ) {
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );

			RecentChange::notifyLog(
				$now, $titleObj, $this->doer, $this->getRcComment(), '',
				$this->type, $this->action, $this->target, $this->comment,
				$this->params, $newId, $this->getRcCommentIRC()
			);
		} elseif ( $this->sendToUDP ) {
			# Don't send private logs to UDP
			if ( isset( $wgLogRestrictions[$this->type] ) && $wgLogRestrictions[$this->type] != '*' ) {
				return $newId;
			}

			# Notify external application via UDP.
			# We send this to IRC but do not want to add it the RC table.
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );
			$rc = RecentChange::newLogEntry(
				$now, $titleObj, $this->doer, $this->getRcComment(), '',
				$this->type, $this->action, $this->target, $this->comment,
				$this->params, $newId, $this->getRcCommentIRC()
			);
			$rc->notifyRCFeeds();
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
	 * @return array Array of strings
	 */
	public static function validTypes() {
		global $wgLogTypes;

		return $wgLogTypes;
	}

	/**
	 * Is $type a valid log type
	 *
	 * @param string $type Log type to check
	 * @return bool
	 */
	public static function isLogType( $type ) {
		return in_array( $type, LogPage::validTypes() );
	}

	/**
	 * Get the name for the given log type
	 *
	 * @param string $type Log type
	 * @return string Log name
	 * @deprecated since 1.19, warnings in 1.21. Use getName()
	 */
	public static function logName( $type ) {
		global $wgLogNames;

		wfDeprecated( __METHOD__, '1.21' );

		if ( isset( $wgLogNames[$type] ) ) {
			return str_replace( '_', ' ', wfMessage( $wgLogNames[$type] )->text() );
		} else {
			// Bogus log types? Perhaps an extension was removed.
			return $type;
		}
	}

	/**
	 * Get the log header for the given log type
	 *
	 * @todo handle missing log types
	 * @param string $type Logtype
	 * @return string Header text of this logtype
	 * @deprecated since 1.19, warnings in 1.21. Use getDescription()
	 */
	public static function logHeader( $type ) {
		global $wgLogHeaders;

		wfDeprecated( __METHOD__, '1.21' );

		return wfMessage( $wgLogHeaders[$type] )->parse();
	}

	/**
	 * Generate text for a log entry.
	 * Only LogFormatter should call this function.
	 *
	 * @param string $type Log type
	 * @param string $action Log action
	 * @param Title|null $title Title object or null
	 * @param Skin|null $skin Skin object or null. If null, we want to use the wiki
	 *   content language, since that will go to the IRC feed.
	 * @param array $params Parameters
	 * @param bool $filterWikilinks Whether to filter wiki links
	 * @return string HTML
	 */
	public static function actionText( $type, $action, $title = null, $skin = null,
		$params = array(), $filterWikilinks = false
	) {
		global $wgLang, $wgContLang, $wgLogActions;

		if ( is_null( $skin ) ) {
			$langObj = $wgContLang;
			$langObjOrNull = null;
		} else {
			$langObj = $wgLang;
			$langObjOrNull = $wgLang;
		}

		$key = "$type/$action";

		if ( isset( $wgLogActions[$key] ) ) {
			if ( is_null( $title ) ) {
				$rv = wfMessage( $wgLogActions[$key] )->inLanguage( $langObj )->escaped();
			} else {
				$titleLink = self::getTitleLink( $type, $langObjOrNull, $title, $params );

				if ( count( $params ) == 0 ) {
					$rv = wfMessage( $wgLogActions[$key] )->rawParams( $titleLink )
						->inLanguage( $langObj )->escaped();
				} else {
					$details = '';
					array_unshift( $params, $titleLink );

					// User suppression
					if ( preg_match( '/^(block|suppress)\/(block|reblock)$/', $key ) ) {
						if ( $skin ) {
							// Localize the duration, and add a tooltip
							// in English to help visitors from other wikis.
							// The lrm is needed to make sure that the number
							// is shown on the correct side of the tooltip text.
							$durationTooltip = '&lrm;' . htmlspecialchars( $params[1] );
							$params[1] = "<span class='blockExpiry' title='$durationTooltip'>" .
								$wgLang->translateBlockExpiry( $params[1] ) . '</span>';
						} else {
							$params[1] = $wgContLang->translateBlockExpiry( $params[1] );
						}

						$params[2] = isset( $params[2] ) ?
							self::formatBlockFlags( $params[2], $langObj ) : '';
					// Page protections
					} elseif ( $type == 'protect' && count( $params ) == 3 ) {
						// Restrictions and expiries
						if ( $skin ) {
							$details .= $wgLang->getDirMark() . htmlspecialchars( " {$params[1]}" );
						} else {
							$details .= " {$params[1]}";
						}

						// Cascading flag...
						if ( $params[2] ) {
							$text = wfMessage( 'protect-summary-cascade' )
								->inLanguage( $langObj )->text();
							$details .= ' ';
							$details .= wfMessage( 'brackets', $text )->inLanguage( $langObj )->text();

						}
					}

					$rv = wfMessage( $wgLogActions[$key] )->rawParams( $params )
							->inLanguage( $langObj )->escaped() . $details;
				}
			}
		} else {
			global $wgLogActionsHandlers;

			if ( isset( $wgLogActionsHandlers[$key] ) ) {
				$args = func_get_args();
				$rv = call_user_func_array( $wgLogActionsHandlers[$key], $args );
			} else {
				wfDebug( "LogPage::actionText - unknown action $key\n" );
				$rv = "$action";
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
	 * @todo Document
	 * @param string $type
	 * @param Language|null $lang
	 * @param Title $title
	 * @param array $params
	 * @return string
	 */
	protected static function getTitleLink( $type, $lang, $title, &$params ) {
		if ( !$lang ) {
			return $title->getPrefixedText();
		}

		switch ( $type ) {
			case 'move':
				$titleLink = Linker::link(
					$title,
					htmlspecialchars( $title->getPrefixedText() ),
					array(),
					array( 'redirect' => 'no' )
				);

				$targetTitle = Title::newFromText( $params[0] );

				if ( !$targetTitle ) {
					# Workaround for broken database
					$params[0] = htmlspecialchars( $params[0] );
				} else {
					$params[0] = Linker::link(
						$targetTitle,
						htmlspecialchars( $params[0] )
					);
				}
				break;
			case 'block':
				if ( substr( $title->getText(), 0, 1 ) == '#' ) {
					$titleLink = $title->getText();
				} else {
					// @todo Store the user identifier in the parameters
					// to make this faster for future log entries
					$id = User::idFromName( $title->getText() );
					$titleLink = Linker::userLink( $id, $title->getText() )
						. Linker::userToolLinks( $id, $title->getText(), false, Linker::TOOL_LINKS_NOBLOCK );
				}
				break;
			case 'merge':
				$titleLink = Linker::link(
					$title,
					$title->getPrefixedText(),
					array(),
					array( 'redirect' => 'no' )
				);
				$params[0] = Linker::link(
					Title::newFromText( $params[0] ),
					htmlspecialchars( $params[0] )
				);
				$params[1] = $lang->timeanddate( $params[1] );
				break;
			default:
				if ( $title->isSpecialPage() ) {
					list( $name, $par ) = SpecialPageFactory::resolveAlias( $title->getDBkey() );

					# Use the language name for log titles, rather than Log/X
					if ( $name == 'Log' ) {
						$logPage = new LogPage( $par );
						$titleLink = Linker::link( $title, $logPage->getName()->escaped() );
						$titleLink = wfMessage( 'parentheses' )
							->inLanguage( $lang )
							->rawParams( $titleLink )
							->escaped();
					} else {
						$titleLink = Linker::link( $title );
					}
				} else {
					$titleLink = Linker::link( $title );
				}
		}

		return $titleLink;
	}

	/**
	 * Add a log entry
	 *
	 * @param string $action One of '', 'block', 'protect', 'rights', 'delete',
	 *   'upload', 'move', 'move_redir'
	 * @param Title $target Title object
	 * @param string $comment Description associated
	 * @param array $params Parameters passed later to wfMessage function
	 * @param null|int|User $doer The user doing the action. null for $wgUser
	 *
	 * @return int The log_id of the inserted log entry
	 */
	public function addEntry( $action, $target, $comment, $params = array(), $doer = null ) {
		global $wgContLang;

		if ( !is_array( $params ) ) {
			$params = array( $params );
		}

		if ( $comment === null ) {
			$comment = '';
		}

		# Trim spaces on user supplied text
		$comment = trim( $comment );

		# Truncate for whole multibyte characters.
		$comment = $wgContLang->truncate( $comment, 255 );

		$this->action = $action;
		$this->target = $target;
		$this->comment = $comment;
		$this->params = LogPage::makeParamBlob( $params );

		if ( $doer === null ) {
			global $wgUser;
			$doer = $wgUser;
		} elseif ( !is_object( $doer ) ) {
			$doer = User::newFromId( $doer );
		}

		$this->doer = $doer;

		$logEntry = new ManualLogEntry( $this->type, $action );
		$logEntry->setTarget( $target );
		$logEntry->setPerformer( $doer );
		$logEntry->setParameters( $params );

		$formatter = LogFormatter::newFromEntry( $logEntry );
		$context = RequestContext::newExtraneousContext( $target );
		$formatter->setContext( $context );

		$this->actionText = $formatter->getPlainActionText();
		$this->ircActionText = $formatter->getIRCActionText();

		return $this->saveContent();
	}

	/**
	 * Add relations to log_search table
	 *
	 * @param string $field
	 * @param array $values
	 * @param int $logid
	 * @return bool
	 */
	public function addRelations( $field, $values, $logid ) {
		if ( !strlen( $field ) || empty( $values ) ) {
			return false; // nothing
		}

		$data = array();

		foreach ( $values as $value ) {
			$data[] = array(
				'ls_field' => $field,
				'ls_value' => $value,
				'ls_log_id' => $logid
			);
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'log_search', $data, __METHOD__, 'IGNORE' );

		return true;
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
			return array();
		} else {
			return explode( "\n", $blob );
		}
	}

	/**
	 * Convert a comma-delimited list of block log flags
	 * into a more readable (and translated) form
	 *
	 * @param string $flags Flags to format
	 * @param Language $lang
	 * @return string
	 */
	public static function formatBlockFlags( $flags, $lang ) {
		$flags = trim( $flags );
		if ( $flags === '' ) {
			return ''; //nothing to do
		}
		$flags = explode( ',', $flags );
		$flagsCount = count( $flags );

		for ( $i = 0; $i < $flagsCount; $i++ ) {
			$flags[$i] = self::formatBlockFlag( $flags[$i], $lang );
		}

		return wfMessage( 'parentheses' )->inLanguage( $lang )
			->rawParams( $lang->commaList( $flags ) )->escaped();
	}

	/**
	 * Translate a block log flag if possible
	 *
	 * @param int $flag Flag to translate
	 * @param Language $lang Language object to use
	 * @return string
	 */
	public static function formatBlockFlag( $flag, $lang ) {
		static $messages = array();

		if ( !isset( $messages[$flag] ) ) {
			$messages[$flag] = htmlspecialchars( $flag ); // Fallback

			// For grepping. The following core messages can be used here:
			// * block-log-flags-angry-autoblock
			// * block-log-flags-anononly
			// * block-log-flags-hiddenname
			// * block-log-flags-noautoblock
			// * block-log-flags-nocreate
			// * block-log-flags-noemail
			// * block-log-flags-nousertalk
			$msg = wfMessage( 'block-log-flags-' . $flag )->inLanguage( $lang );

			if ( $msg->exists() ) {
				$messages[$flag] = $msg->escaped();
			}
		}

		return $messages[$flag];
	}

	/**
	 * Name of the log.
	 * @return Message
	 * @since 1.19
	 */
	public function getName() {
		global $wgLogNames;

		// BC
		if ( isset( $wgLogNames[$this->type] ) ) {
			$key = $wgLogNames[$this->type];
		} else {
			$key = 'log-name-' . $this->type;
		}

		return wfMessage( $key );
	}

	/**
	 * Description of this log type.
	 * @return Message
	 * @since 1.19
	 */
	public function getDescription() {
		global $wgLogHeaders;
		// BC
		if ( isset( $wgLogHeaders[$this->type] ) ) {
			$key = $wgLogHeaders[$this->type];
		} else {
			$key = 'log-description-' . $this->type;
		}

		return wfMessage( $key );
	}

	/**
	 * Returns the right needed to read this log type.
	 * @return string
	 * @since 1.19
	 */
	public function getRestriction() {
		global $wgLogRestrictions;
		if ( isset( $wgLogRestrictions[$this->type] ) ) {
			$restriction = $wgLogRestrictions[$this->type];
		} else {
			// '' always returns true with $user->isAllowed()
			$restriction = '';
		}

		return $restriction;
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
