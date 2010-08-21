<?php
/**
 * Contain log classes
 *
 * Copyright © 2002, 2004 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
	/* @access private */
	var $type, $action, $comment, $params, $target, $doer;
	/* @acess public */
	var $updateRecentChanges, $sendToUDP;

	/**
	 * Constructor
	 *
	 * @param $type String: one of '', 'block', 'protect', 'rights', 'delete',
	 *               'upload', 'move'
	 * @param $rc Boolean: whether to update recent changes as well as the logging table
	 * @param $udp String: pass 'UDP' to send to the UDP feed if NOT sent to RC
	 */
	public function __construct( $type, $rc = true, $udp = 'skipUDP' ) {
		$this->type = $type;
		$this->updateRecentChanges = $rc;
		$this->sendToUDP = ( $udp == 'UDP' );
	}

	protected function saveContent() {
		global $wgLogRestrictions;

		$dbw = wfGetDB( DB_MASTER );
		$log_id = $dbw->nextSequenceValue( 'logging_log_id_seq' );

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
			'log_page' => $this->target->getArticleId(),
			'log_comment' => $this->comment,
			'log_params' => $this->params
		);
		$dbw->insert( 'logging', $data, __METHOD__ );
		$newId = !is_null( $log_id ) ? $log_id : $dbw->insertId();

		# And update recentchanges
		if( $this->updateRecentChanges ) {
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );
			RecentChange::notifyLog(
				$now, $titleObj, $this->doer, $this->getRcComment(), '',
				$this->type, $this->action, $this->target, $this->comment,
				$this->params, $newId
			);
		} elseif( $this->sendToUDP ) {
			# Don't send private logs to UDP
			if( isset( $wgLogRestrictions[$this->type] ) && $wgLogRestrictions[$this->type] != '*' ) {
				return true;
			}
			# Notify external application via UDP.
			# We send this to IRC but do not want to add it the RC table.
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );
			$rc = RecentChange::newLogEntry(
				$now, $titleObj, $this->doer, $this->getRcComment(), '',
				$this->type, $this->action, $this->target, $this->comment,
				$this->params, $newId
			);
			$rc->notifyRC2UDP();
		}
		return $newId;
	}

	/**
	 * Get the RC comment from the last addEntry() call
	 */
	public function getRcComment() {
		$rcComment = $this->actionText;
		if( $this->comment != '' ) {
			if ( $rcComment == '' ) {
				$rcComment = $this->comment;
			} else {
				$rcComment .= wfMsgForContent( 'colon-separator' ) . $this->comment;
			}
		}
		return $rcComment;
	}

	/**
	 * Get the comment from the last addEntry() call
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * Get the list of valid log types
	 *
	 * @return Array of strings
	 */
	public static function validTypes() {
		global $wgLogTypes;
		return $wgLogTypes;
	}

	/**
	 * Is $type a valid log type
	 *
	 * @param $type String: log type to check
	 * @return Boolean
	 */
	public static function isLogType( $type ) {
		return in_array( $type, LogPage::validTypes() );
	}

	/**
	 * Get the name for the given log type
	 *
	 * @param $type String: logtype
	 * @return String: log name
	 */
	public static function logName( $type ) {
		global $wgLogNames;

		if( isset( $wgLogNames[$type] ) ) {
			return str_replace( '_', ' ', wfMsg( $wgLogNames[$type] ) );
		} else {
			// Bogus log types? Perhaps an extension was removed.
			return $type;
		}
	}

	/**
	 * Get the log header for the given log type
	 *
	 * @todo handle missing log types
	 * @param $type String: logtype
	 * @return String: headertext of this logtype
	 */
	public static function logHeader( $type ) {
		global $wgLogHeaders;
		return wfMsgExt( $wgLogHeaders[$type], array( 'parseinline' ) );
	}

	/**
	 * Generate text for a log entry
	 *
	 * @param $type String: log type
	 * @param $action String: log action
	 * @param $title Mixed: Title object or null
	 * @param $skin Mixed: Skin object or null. If null, we want to use the wiki
	 *              content language, since that will go to the IRC feed.
	 * @param $params Array: parameters
	 * @param $filterWikilinks Boolean: whether to filter wiki links
	 * @return HTML string
	 */
	public static function actionText( $type, $action, $title = null, $skin = null,
		$params = array(), $filterWikilinks = false )
	{
		global $wgLang, $wgContLang, $wgLogActions;

		$key = "$type/$action";
		# Defer patrol log to PatrolLog class
		if( $key == 'patrol/patrol' ) {
			return PatrolLog::makeActionText( $title, $params, $skin );
		}
		if( isset( $wgLogActions[$key] ) ) {
			if( is_null( $title ) ) {
				$rv = wfMsgHtml( $wgLogActions[$key] );
			} else {
				$titleLink = self::getTitleLink( $type, $skin, $title, $params );
				if( $key == 'rights/rights' ) {
					if( $skin ) {
						$rightsnone = wfMsg( 'rightsnone' );
						foreach ( $params as &$param ) {
							$groupArray = array_map( 'trim', explode( ',', $param ) );
							$groupArray = array_map( array( 'User', 'getGroupMember' ), $groupArray );
							$param = $wgLang->listToText( $groupArray );
						}
					} else {
						$rightsnone = wfMsgForContent( 'rightsnone' );
					}
					if( !isset( $params[0] ) || trim( $params[0] ) == '' ) {
						$params[0] = $rightsnone;
					}
					if( !isset( $params[1] ) || trim( $params[1] ) == '' ) {
						$params[1] = $rightsnone;
					}
				}
				if( count( $params ) == 0 ) {
					if ( $skin ) {
						$rv = wfMsgHtml( $wgLogActions[$key], $titleLink );
					} else {
						$rv = wfMsgExt( $wgLogActions[$key], array( 'parsemag', 'escape', 'replaceafter', 'content' ), $titleLink );
					}
				} else {
					$details = '';
					array_unshift( $params, $titleLink );
					// User suppression
					if ( preg_match( '/^(block|suppress)\/(block|reblock)$/', $key ) ) {
						if ( $skin ) {
							$params[1] = '<span title="' . htmlspecialchars( $params[1] ). '">' .
								$wgLang->translateBlockExpiry( $params[1] ) . '</span>';
						} else {
							$params[1] = $wgContLang->translateBlockExpiry( $params[1] );
						}
						$params[2] = isset( $params[2] ) ?
							self::formatBlockFlags( $params[2], is_null( $skin ) ) : '';

					// Page protections
					} elseif ( $type == 'protect' && count($params) == 3 ) {
						// Restrictions and expiries
						if( $skin ) {
							$details .= htmlspecialchars( " {$params[1]}" );
						} else {
							$details .= " {$params[1]}";
						}
						// Cascading flag...
						if( $params[2] ) {
							if ( $skin ) {
								$details .= ' [' . wfMsg( 'protect-summary-cascade' ) . ']';
							} else {
								$details .= ' [' . wfMsgForContent( 'protect-summary-cascade' ) . ']';
							}
						}

					// Page moves
					} elseif ( $type == 'move' && count( $params ) == 3 && $action != 'move_rev' ) {
						if( $params[2] ) {
							if ( $skin ) {
								$details .= ' [' . wfMsg( 'move-redirect-suppressed' ) . ']';
							} else {
								$details .= ' [' . wfMsgForContent( 'move-redirect-suppressed' ) . ']';
							}
						}

					// Revision deletion
					} elseif ( preg_match( '/^(delete|suppress)\/revision$/', $key ) && count( $params ) == 5 ) {
						$count = substr_count( $params[2], ',' ) + 1; // revisions
						$ofield = intval( substr( $params[3], 7 ) ); // <ofield=x>
						$nfield = intval( substr( $params[4], 7 ) ); // <nfield=x>
						$details .= ': ' . RevisionDeleter::getLogMessage( $count, $nfield, $ofield, false, is_null( $skin ) );

					// Log deletion
					} elseif ( preg_match( '/^(delete|suppress)\/event$/', $key ) && count( $params ) == 4 ) {
						$count = substr_count( $params[1], ',' ) + 1; // log items
						$ofield = intval( substr( $params[2], 7 ) ); // <ofield=x>
						$nfield = intval( substr( $params[3], 7 ) ); // <nfield=x>
						$details .= ': ' . RevisionDeleter::getLogMessage( $count, $nfield, $ofield, true, is_null( $skin ) );
					}

					if ( $skin ) {
						$rv = wfMsgHtml( $wgLogActions[$key], $params ) . $details;
					} else {
						$rv = wfMsgExt( $wgLogActions[$key], array( 'parsemag', 'escape', 'replaceafter', 'content' ), $params ) . $details;
					}
				}
			}
		} else {
			global $wgLogActionsHandlers;
			if( isset( $wgLogActionsHandlers[$key] ) ) {
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
		if( $filterWikilinks ) {
			$rv = str_replace( '[[', '', $rv );
			$rv = str_replace( ']]', '', $rv );
		}
		return $rv;
	}

	protected static function getTitleLink( $type, $skin, $title, &$params ) {
		global $wgLang, $wgContLang, $wgUserrightsInterwikiDelimiter;
		if( !$skin ) {
			return $title->getPrefixedText();
		}
		switch( $type ) {
			case 'move':
				$titleLink = $skin->link(
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
					$params[0] = $skin->link(
						$targetTitle,
						htmlspecialchars( $params[0] )
					);
				}
				break;
			case 'block':
				if( substr( $title->getText(), 0, 1 ) == '#' ) {
					$titleLink = $title->getText();
				} else {
					// TODO: Store the user identifier in the parameters
					// to make this faster for future log entries
					$id = User::idFromName( $title->getText() );
					$titleLink = $skin->userLink( $id, $title->getText() )
						. $skin->userToolLinks( $id, $title->getText(), false, Linker::TOOL_LINKS_NOBLOCK );
				}
				break;
			case 'rights':
				$text = $wgContLang->ucfirst( $title->getText() );
				$parts = explode( $wgUserrightsInterwikiDelimiter, $text, 2 );
				if ( count( $parts ) == 2 ) {
					$titleLink = WikiMap::foreignUserLink( $parts[1], $parts[0],
						htmlspecialchars( $title->getPrefixedText() ) );
					if ( $titleLink !== false ) {
						break;
					}
				}
				$titleLink = $skin->link( Title::makeTitle( NS_USER, $text ) );
				break;
			case 'merge':
				$titleLink = $skin->link(
					$title,
					$title->getPrefixedText(),
					array(),
					array( 'redirect' => 'no' )
				);
				$params[0] = $skin->link(
					Title::newFromText( $params[0] ),
					htmlspecialchars( $params[0] )
				);
				$params[1] = $wgLang->timeanddate( $params[1] );
				break;
			default:
				if( $title->getNamespace() == NS_SPECIAL ) {
					list( $name, $par ) = SpecialPage::resolveAliasWithSubpage( $title->getDBkey() );
					# Use the language name for log titles, rather than Log/X
					if( $name == 'Log' ) {
						$titleLink = '(' . $skin->link( $title, LogPage::logName( $par ) ) . ')';
					} else {
						$titleLink = $skin->link( $title );
					}
				} else {
					$titleLink = $skin->link( $title );
				}
		}
		return $titleLink;
	}

	/**
	 * Add a log entry
	 *
	 * @param $action String: one of '', 'block', 'protect', 'rights', 'delete', 'upload', 'move', 'move_redir'
	 * @param $target Title object
	 * @param $comment String: description associated
	 * @param $params Array: parameters passed later to wfMsg.* functions
	 * @param $doer User object: the user doing the action
	 */
	public function addEntry( $action, $target, $comment, $params = array(), $doer = null ) {
		if ( !is_array( $params ) ) {
			$params = array( $params );
		}

		if ( $comment === null ) {
			$comment = '';
		}

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

		$this->actionText = LogPage::actionText( $this->type, $action, $target, null, $params );

		return $this->saveContent();
	}

	/**
	 * Add relations to log_search table
	 *
	 * @param $field String
	 * @param $values Array
	 * @param $logid Integer
	 * @return Boolean
	 */
	public function addRelations( $field, $values, $logid ) {
		if( !strlen( $field ) || empty( $values ) ) {
			return false; // nothing
		}
		$data = array();
		foreach( $values as $value ) {
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
	 * @param $params Array
	 * @return String
	 */
	public static function makeParamBlob( $params ) {
		return implode( "\n", $params );
	}

	/**
	 * Extract a parameter array from a blob
	 *
	 * @param $blob String
	 * @return Array
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
	 * @param $flags Flags to format
	 * @param $forContent Whether to localize the message depending of the user
	 *                    language
	 * @return String
	 */
	public static function formatBlockFlags( $flags, $forContent = false ) {
		global $wgLang;

		$flags = explode( ',', trim( $flags ) );
		if( count( $flags ) > 0 ) {
			for( $i = 0; $i < count( $flags ); $i++ ) {
				$flags[$i] = self::formatBlockFlag( $flags[$i], $forContent );
			}
			return '(' . $wgLang->commaList( $flags ) . ')';
		} else {
			return '';
		}
	}

	/**
	 * Translate a block log flag if possible
	 *
	 * @param $flag Flag to translate
	 * @param $forContent Whether to localize the message depending of the user
	 *                    language
	 * @return String
	 */
	public static function formatBlockFlag( $flag, $forContent = false ) {
		static $messages = array();
		if( !isset( $messages[$flag] ) ) {
			$k = 'block-log-flags-' . $flag;
			if( $forContent ) {
				$msg = wfMsgForContent( $k );
			} else {
				$msg = wfMsg( $k );
			}
			$messages[$flag] = htmlspecialchars( wfEmptyMsg( $k, $msg ) ? $flag : $msg );
		}
		return $messages[$flag];
	}
}

/**
 * Aliases for backwards compatibility with 1.6
 */
define( 'MW_LOG_DELETED_ACTION', LogPage::DELETED_ACTION );
define( 'MW_LOG_DELETED_USER', LogPage::DELETED_USER );
define( 'MW_LOG_DELETED_COMMENT', LogPage::DELETED_COMMENT );
define( 'MW_LOG_DELETED_RESTRICTED', LogPage::DELETED_RESTRICTED );
