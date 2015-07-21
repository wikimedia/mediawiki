<?php
/**
 * Contains classes for formatting log entries
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
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.19
 */

/**
 * Implements the default log formatting.
 *
 * Can be overridden by subclassing and setting:
 *
 *     $wgLogActionsHandlers['type/subtype'] = 'class'; or
 *     $wgLogActionsHandlers['type/*'] = 'class';
 *
 * @since 1.19
 */
class LogFormatter {
	// Audience options for viewing usernames, comments, and actions
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;

	// Static->

	/**
	 * Constructs a new formatter suitable for given entry.
	 * @param LogEntry $entry
	 * @return LogFormatter
	 */
	public static function newFromEntry( LogEntry $entry ) {
		global $wgLogActionsHandlers;
		$fulltype = $entry->getFullType();
		$wildcard = $entry->getType() . '/*';
		$handler = '';

		if ( isset( $wgLogActionsHandlers[$fulltype] ) ) {
			$handler = $wgLogActionsHandlers[$fulltype];
		} elseif ( isset( $wgLogActionsHandlers[$wildcard] ) ) {
			$handler = $wgLogActionsHandlers[$wildcard];
		}

		if ( $handler !== '' && is_string( $handler ) && class_exists( $handler ) ) {
			return new $handler( $entry );
		}

		return new LegacyLogFormatter( $entry );
	}

	/**
	 * Handy shortcut for constructing a formatter directly from
	 * database row.
	 * @param object $row
	 * @see DatabaseLogEntry::getSelectQueryData
	 * @return LogFormatter
	 */
	public static function newFromRow( $row ) {
		return self::newFromEntry( DatabaseLogEntry::newFromRow( $row ) );
	}

	// Nonstatic->

	/** @var LogEntryBase */
	protected $entry;

	/** @var int Constant for handling log_deleted */
	protected $audience = self::FOR_PUBLIC;

	/** @var IContextSource Context for logging */
	public $context;

	/** @var bool Whether to output user tool links */
	protected $linkFlood = false;

	/**
	 * Set to true if we are constructing a message text that is going to
	 * be included in page history or send to IRC feed. Links are replaced
	 * with plaintext or with [[pagename]] kind of syntax, that is parsed
	 * by page histories and IRC feeds.
	 * @var string
	 */
	protected $plaintext = false;

	/** @var string */
	protected $irctext = false;

	protected function __construct( LogEntry $entry ) {
		$this->entry = $entry;
		$this->context = RequestContext::getMain();
	}

	/**
	 * Replace the default context
	 * @param IContextSource $context
	 */
	public function setContext( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * Set the visibility restrictions for displaying content.
	 * If set to public, and an item is deleted, then it will be replaced
	 * with a placeholder even if the context user is allowed to view it.
	 * @param int $audience Const self::FOR_THIS_USER or self::FOR_PUBLIC
	 */
	public function setAudience( $audience ) {
		$this->audience = ( $audience == self::FOR_THIS_USER )
			? self::FOR_THIS_USER
			: self::FOR_PUBLIC;
	}

	/**
	 * Check if a log item can be displayed
	 * @param int $field LogPage::DELETED_* constant
	 * @return bool
	 */
	protected function canView( $field ) {
		if ( $this->audience == self::FOR_THIS_USER ) {
			return LogEventsList::userCanBitfield(
				$this->entry->getDeleted(), $field, $this->context->getUser() );
		} else {
			return !$this->entry->isDeleted( $field );
		}
	}

	/**
	 * If set to true, will produce user tool links after
	 * the user name. This should be replaced with generic
	 * CSS/JS solution.
	 * @param bool $value
	 */
	public function setShowUserToolLinks( $value ) {
		$this->linkFlood = $value;
	}

	/**
	 * Ugly hack to produce plaintext version of the message.
	 * Usually you also want to set extraneous request context
	 * to avoid formatting for any particular user.
	 * @see getActionText()
	 * @return string Plain text
	 */
	public function getPlainActionText() {
		$this->plaintext = true;
		$text = $this->getActionText();
		$this->plaintext = false;

		return $text;
	}

	/**
	 * Even uglier hack to maintain backwards compatibilty with IRC bots
	 * (bug 34508).
	 * @see getActionText()
	 * @return string Text
	 */
	public function getIRCActionComment() {
		$actionComment = $this->getIRCActionText();
		$comment = $this->entry->getComment();

		if ( $comment != '' ) {
			if ( $actionComment == '' ) {
				$actionComment = $comment;
			} else {
				$actionComment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $comment;
			}
		}

		return $actionComment;
	}

	/**
	 * Even uglier hack to maintain backwards compatibilty with IRC bots
	 * (bug 34508).
	 * @see getActionText()
	 * @return string Text
	 */
	public function getIRCActionText() {
		$this->plaintext = true;
		$this->irctext = true;

		$entry = $this->entry;
		$parameters = $entry->getParameters();
		// @see LogPage::actionText()
		// Text of title the action is aimed at.
		$target = $entry->getTarget()->getPrefixedText();
		$text = null;
		switch ( $entry->getType() ) {
			case 'move':
				switch ( $entry->getSubtype() ) {
					case 'move':
						$movesource = $parameters['4::target'];
						$text = wfMessage( '1movedto2' )
							->rawParams( $target, $movesource )->inContentLanguage()->escaped();
						break;
					case 'move_redir':
						$movesource = $parameters['4::target'];
						$text = wfMessage( '1movedto2_redir' )
							->rawParams( $target, $movesource )->inContentLanguage()->escaped();
						break;
					case 'move-noredirect':
						break;
					case 'move_redir-noredirect':
						break;
				}
				break;

			case 'delete':
				switch ( $entry->getSubtype() ) {
					case 'delete':
						$text = wfMessage( 'deletedarticle' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					case 'restore':
						$text = wfMessage( 'undeletedarticle' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					// @codingStandardsIgnoreStart Long line
					//case 'revision': // Revision deletion
					//case 'event': // Log deletion
					// see https://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/LogPage.php?&pathrev=97044&r1=97043&r2=97044
					//default:
					// @codingStandardsIgnoreEnd
				}
				break;

			case 'patrol':
				// @codingStandardsIgnoreStart Long line
				// https://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/PatrolLog.php?&pathrev=97495&r1=97494&r2=97495
				// @codingStandardsIgnoreEnd
				// Create a diff link to the patrolled revision
				if ( $entry->getSubtype() === 'patrol' ) {
					$diffLink = htmlspecialchars(
						wfMessage( 'patrol-log-diff', $parameters['4::curid'] )
							->inContentLanguage()->text() );
					$text = wfMessage( 'patrol-log-line', $diffLink, "[[$target]]", "" )
						->inContentLanguage()->text();
				} else {
					// broken??
				}
				break;

			case 'protect':
				switch ( $entry->getSubtype() ) {
					case 'protect':
						$text = wfMessage( 'protectedarticle' )
							->rawParams( $target . ' ' . $parameters[0] )->inContentLanguage()->escaped();
						break;
					case 'unprotect':
						$text = wfMessage( 'unprotectedarticle' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					case 'modify':
						$text = wfMessage( 'modifiedarticleprotection' )
							->rawParams( $target . ' ' . $parameters[0] )->inContentLanguage()->escaped();
						break;
				}
				break;

			case 'newusers':
				switch ( $entry->getSubtype() ) {
					case 'newusers':
					case 'create':
						$text = wfMessage( 'newuserlog-create-entry' )
							->inContentLanguage()->escaped();
						break;
					case 'create2':
					case 'byemail':
						$text = wfMessage( 'newuserlog-create2-entry' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					case 'autocreate':
						$text = wfMessage( 'newuserlog-autocreate-entry' )
							->inContentLanguage()->escaped();
						break;
				}
				break;

			case 'upload':
				switch ( $entry->getSubtype() ) {
					case 'upload':
						$text = wfMessage( 'uploadedimage' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					case 'overwrite':
						$text = wfMessage( 'overwroteimage' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
				}
				break;

			case 'rights':
				if ( count( $parameters['4::oldgroups'] ) ) {
					$oldgroups = implode( ', ', $parameters['4::oldgroups'] );
				} else {
					$oldgroups = wfMessage( 'rightsnone' )->inContentLanguage()->escaped();
				}
				if ( count( $parameters['5::newgroups'] ) ) {
					$newgroups = implode( ', ', $parameters['5::newgroups'] );
				} else {
					$newgroups = wfMessage( 'rightsnone' )->inContentLanguage()->escaped();
				}
				switch ( $entry->getSubtype() ) {
					case 'rights':
						$text = wfMessage( 'rightslogentry' )
							->rawParams( $target, $oldgroups, $newgroups )->inContentLanguage()->escaped();
						break;
					case 'autopromote':
						$text = wfMessage( 'rightslogentry-autopromote' )
							->rawParams( $target, $oldgroups, $newgroups )->inContentLanguage()->escaped();
						break;
				}
				break;

			case 'merge':
				$text = wfMessage( 'pagemerge-logentry' )
					->rawParams( $target, $parameters['4::dest'], $parameters['5::mergepoint'] )
					->inContentLanguage()->escaped();
				break;

			case 'block':
				switch ( $entry->getSubtype() ) {
					case 'block':
						global $wgContLang;
						// Keep compatibility with extensions by checking for
						// new key (5::duration/6::flags) or old key (0/optional 1)
						if ( $entry->isLegacy() ) {
							$rawDuration = $parameters[0];
							$rawFlags = isset( $parameters[1] ) ? $parameters[1] : '';
						} else {
							$rawDuration = $parameters['5::duration'];
							$rawFlags = $parameters['6::flags'];
						}
						$duration = $wgContLang->translateBlockExpiry( $rawDuration );
						$flags = BlockLogFormatter::formatBlockFlags( $rawFlags, $wgContLang );
						$text = wfMessage( 'blocklogentry' )
							->rawParams( $target, $duration, $flags )->inContentLanguage()->escaped();
						break;
					case 'unblock':
						$text = wfMessage( 'unblocklogentry' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					case 'reblock':
						global $wgContLang;
						$duration = $wgContLang->translateBlockExpiry( $parameters['5::duration'] );
						$flags = BlockLogFormatter::formatBlockFlags( $parameters['6::flags'], $wgContLang );
						$text = wfMessage( 'reblock-logentry' )
							->rawParams( $target, $duration, $flags )->inContentLanguage()->escaped();
						break;
				}
				break;

			case 'import':
				switch ( $entry->getSubtype() ) {
					case 'upload':
						$text = wfMessage( 'import-logentry-upload' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
					case 'interwiki':
						$text = wfMessage( 'import-logentry-interwiki' )
							->rawParams( $target )->inContentLanguage()->escaped();
						break;
				}
				break;
			// case 'suppress' --private log -- aaron  (so we know who to blame in a few years :-D)
			// default:
		}
		if ( is_null( $text ) ) {
			$text = $this->getPlainActionText();
		}

		$this->plaintext = false;
		$this->irctext = false;

		return $text;
	}

	/**
	 * Gets the log action, including username.
	 * @return string HTML
	 */
	public function getActionText() {
		if ( $this->canView( LogPage::DELETED_ACTION ) ) {
			$element = $this->getActionMessage();
			if ( $element instanceof Message ) {
				$element = $this->plaintext ? $element->text() : $element->escaped();
			}
			if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) ) {
				$element = $this->styleRestricedElement( $element );
			}
		} else {
			$sep = $this->msg( 'word-separator' );
			$sep = $this->plaintext ? $sep->text() : $sep->escaped();
			$performer = $this->getPerformerElement();
			$element = $performer . $sep . $this->getRestrictedElement( 'rev-deleted-event' );
		}

		return $element;
	}

	/**
	 * Returns a sentence describing the log action. Usually
	 * a Message object is returned, but old style log types
	 * and entries might return pre-escaped HTML string.
	 * @return Message|string Pre-escaped HTML
	 */
	protected function getActionMessage() {
		$message = $this->msg( $this->getMessageKey() );
		$message->params( $this->getMessageParameters() );

		return $message;
	}

	/**
	 * Returns a key to be used for formatting the action sentence.
	 * Default is logentry-TYPE-SUBTYPE for modern logs. Legacy log
	 * types will use custom keys, and subclasses can also alter the
	 * key depending on the entry itself.
	 * @return string Message key
	 */
	protected function getMessageKey() {
		$type = $this->entry->getType();
		$subtype = $this->entry->getSubtype();

		return "logentry-$type-$subtype";
	}

	/**
	 * Returns extra links that comes after the action text, like "revert", etc.
	 *
	 * @return string
	 */
	public function getActionLinks() {
		return '';
	}

	/**
	 * Extracts the optional extra parameters for use in action messages.
	 * The array indexes start from number 3.
	 * @return array
	 */
	protected function extractParameters() {
		$entry = $this->entry;
		$params = array();

		if ( $entry->isLegacy() ) {
			foreach ( $entry->getParameters() as $index => $value ) {
				$params[$index + 3] = $value;
			}
		}

		// Filter out parameters which are not in format #:foo
		foreach ( $entry->getParameters() as $key => $value ) {
			if ( strpos( $key, ':' ) === false ) {
				continue;
			}
			list( $index, $type, ) = explode( ':', $key, 3 );
			if ( ctype_digit( $index ) ) {
				$params[$index - 1] = $this->formatParameterValue( $type, $value );
			}
		}

		/* Message class doesn't like non consecutive numbering.
		 * Fill in missing indexes with empty strings to avoid
		 * incorrect renumbering.
		 */
		if ( count( $params ) ) {
			$max = max( array_keys( $params ) );
			// index 0 to 2 are added in getMessageParameters
			for ( $i = 3; $i < $max; $i++ ) {
				if ( !isset( $params[$i] ) ) {
					$params[$i] = '';
				}
			}
		}

		return $params;
	}

	/**
	 * Formats parameters intented for action message from
	 * array of all parameters. There are three hardcoded
	 * parameters (array is zero-indexed, this list not):
	 *  - 1: user name with premade link
	 *  - 2: usable for gender magic function
	 *  - 3: target page with premade link
	 * @return array
	 */
	protected function getMessageParameters() {
		if ( isset( $this->parsedParameters ) ) {
			return $this->parsedParameters;
		}

		$entry = $this->entry;
		$params = $this->extractParameters();
		$params[0] = Message::rawParam( $this->getPerformerElement() );
		$params[1] = $this->canView( LogPage::DELETED_USER ) ? $entry->getPerformer()->getName() : '';
		$params[2] = Message::rawParam( $this->makePageLink( $entry->getTarget() ) );

		// Bad things happens if the numbers are not in correct order
		ksort( $params );

		$this->parsedParameters = $params;
		return $this->parsedParameters;
	}

	/**
	 * Formats parameters values dependent to their type
	 * @param string $type The type of the value.
	 *   Valid are currently:
	 *     * - (empty) or plain: The value is returned as-is
	 *     * raw: The value will be added to the log message
	 *            as raw parameter (e.g. no escaping)
	 *            Use this only if there is no other working
	 *            type like user-link or title-link
	 *     * msg: The value is a message-key, the output is
	 *            the message in user language
	 *     * msg-content: The value is a message-key, the output
	 *                    is the message in content language
	 *     * user: The value is a user name, e.g. for GENDER
	 *     * user-link: The value is a user name, returns a
	 *                  link for the user
	 *     * title: The value is a page title,
	 *              returns name of page
	 *     * title-link: The value is a page title,
	 *                   returns link to this page
	 *     * number: Format value as number
	 *     * list: Format value as a comma-separated list
	 * @param mixed $value The parameter value that should be formatted
	 * @return string|array Formated value
	 * @since 1.21
	 */
	protected function formatParameterValue( $type, $value ) {
		$saveLinkFlood = $this->linkFlood;

		switch ( strtolower( trim( $type ) ) ) {
			case 'raw':
				$value = Message::rawParam( $value );
				break;
			case 'list':
				$value = $this->context->getLanguage()->commaList( $value );
				break;
			case 'msg':
				$value = $this->msg( $value )->text();
				break;
			case 'msg-content':
				$value = $this->msg( $value )->inContentLanguage()->text();
				break;
			case 'number':
				$value = Message::numParam( $value );
				break;
			case 'user':
				$user = User::newFromName( $value );
				$value = $user->getName();
				break;
			case 'user-link':
				$this->setShowUserToolLinks( false );

				$user = User::newFromName( $value );
				$value = Message::rawParam( $this->makeUserLink( $user ) );

				$this->setShowUserToolLinks( $saveLinkFlood );
				break;
			case 'title':
				$title = Title::newFromText( $value );
				$value = $title->getPrefixedText();
				break;
			case 'title-link':
				$title = Title::newFromText( $value );
				$value = Message::rawParam( $this->makePageLink( $title ) );
				break;
			case 'plain':
				// Plain text, nothing to do
			default:
				// Catch other types and use the old behavior (return as-is)
		}

		return $value;
	}

	/**
	 * Helper to make a link to the page, taking the plaintext
	 * value in consideration.
	 * @param Title $title The page
	 * @param array $parameters Query parameters
	 * @throws MWException
	 * @return string
	 */
	protected function makePageLink( Title $title = null, $parameters = array() ) {
		if ( !$this->plaintext ) {
			$link = Linker::link( $title, null, array(), $parameters );
		} else {
			if ( !$title instanceof Title ) {
				throw new MWException( "Expected title, got null" );
			}
			$link = '[[' . $title->getPrefixedText() . ']]';
		}

		return $link;
	}

	/**
	 * Provides the name of the user who performed the log action.
	 * Used as part of log action message or standalone, depending
	 * which parts of the log entry has been hidden.
	 * @return string
	 */
	public function getPerformerElement() {
		if ( $this->canView( LogPage::DELETED_USER ) ) {
			$performer = $this->entry->getPerformer();
			$element = $this->makeUserLink( $performer );
			if ( $this->entry->isDeleted( LogPage::DELETED_USER ) ) {
				$element = $this->styleRestricedElement( $element );
			}
		} else {
			$element = $this->getRestrictedElement( 'rev-deleted-user' );
		}

		return $element;
	}

	/**
	 * Gets the user provided comment
	 * @return string HTML
	 */
	public function getComment() {
		if ( $this->canView( LogPage::DELETED_COMMENT ) ) {
			$comment = Linker::commentBlock( $this->entry->getComment() );
			// No hard coded spaces thanx
			$element = ltrim( $comment );
			if ( $this->entry->isDeleted( LogPage::DELETED_COMMENT ) ) {
				$element = $this->styleRestricedElement( $element );
			}
		} else {
			$element = $this->getRestrictedElement( 'rev-deleted-comment' );
		}

		return $element;
	}

	/**
	 * Helper method for displaying restricted element.
	 * @param string $message
	 * @return string HTML or wiki text
	 */
	protected function getRestrictedElement( $message ) {
		if ( $this->plaintext ) {
			return $this->msg( $message )->text();
		}

		$content = $this->msg( $message )->escaped();
		$attribs = array( 'class' => 'history-deleted' );

		return Html::rawElement( 'span', $attribs, $content );
	}

	/**
	 * Helper method for styling restricted element.
	 * @param string $content
	 * @return string HTML or wiki text
	 */
	protected function styleRestricedElement( $content ) {
		if ( $this->plaintext ) {
			return $content;
		}
		$attribs = array( 'class' => 'history-deleted' );

		return Html::rawElement( 'span', $attribs, $content );
	}

	/**
	 * Shortcut for wfMessage which honors local context.
	 * @param string $key
	 * @return Message
	 */
	protected function msg( $key ) {
		return $this->context->msg( $key );
	}

	protected function makeUserLink( User $user, $toolFlags = 0 ) {
		if ( $this->plaintext ) {
			$element = $user->getName();
		} else {
			$element = Linker::userLink(
				$user->getId(),
				$user->getName()
			);

			if ( $this->linkFlood ) {
				$element .= Linker::userToolLinks(
					$user->getId(),
					$user->getName(),
					true, // redContribsWhenNoEdits
					$toolFlags,
					$user->getEditCount()
				);
			}
		}

		return $element;
	}

	/**
	 * @return array Array of titles that should be preloaded with LinkBatch
	 */
	public function getPreloadTitles() {
		return array();
	}

	/**
	 * @return array Output of getMessageParameters() for testing
	 */
	public function getMessageParametersForTesting() {
		// This function was added because getMessageParameters() is
		// protected and a change from protected to public caused
		// problems with extensions
		return $this->getMessageParameters();
	}

	/**
	 * Get the array of parameters, converted from legacy format if necessary.
	 * @since 1.25
	 * @return array
	 */
	protected function getParametersForApi() {
		return $this->entry->getParameters();
	}

	/**
	 * Format parameters for API output
	 *
	 * The result array should generally map named keys to values. Index and
	 * type should be omitted, e.g. "4::foo" should be returned as "foo" in the
	 * output. Values should generally be unformatted.
	 *
	 * Renames or removals of keys besides from the legacy numeric format to
	 * modern named style should be avoided. Any renames should be announced to
	 * the mediawiki-api-announce mailing list.
	 *
	 * @since 1.25
	 * @return array
	 */
	public function formatParametersForApi() {
		$logParams = array();
		foreach ( $this->getParametersForApi() as $key => $value ) {
			$vals = explode( ':', $key, 3 );
			if ( count( $vals ) !== 3 ) {
				$logParams[$key] = $value;
				continue;
			}
			$logParams += $this->formatParameterValueForApi( $vals[2], $vals[1], $value );
		}
		ApiResult::setIndexedTagName( $logParams, 'param' );
		ApiResult::setArrayType( $logParams, 'assoc' );

		return $logParams;
	}

	/**
	 * Format a single parameter value for API output
	 *
	 * @since 1.25
	 * @param string $name
	 * @param string $type
	 * @param string $value
	 * @return array
	 */
	protected function formatParameterValueForApi( $name, $type, $value ) {
		$type = strtolower( trim( $type ) );
		switch ( $type ) {
			case 'bool':
				$value = (bool)$value;
				break;

			case 'number':
				if ( ctype_digit( $value ) ) {
					$value = (int)$value;
				} else {
					$value = (float)$value;
				}
				break;

			case 'array':
			case 'assoc':
			case 'kvp':
				if ( is_array( $value ) ) {
					ApiResult::setArrayType( $value, $type );
				}
				break;

			case 'timestamp':
				$value = wfTimestamp( TS_ISO_8601, $value );
				break;

			case 'msg':
			case 'msg-content':
				$msg = $this->msg( $value );
				if ( $type === 'msg-content' ) {
					$msg->inContentLanguage();
				}
				$value = array();
				$value["{$name}_key"] = $msg->getKey();
				if ( $msg->getParams() ) {
					$value["{$name}_params"] = $msg->getParams();
				}
				$value["{$name}_text"] = $msg->text();
				return $value;

			case 'title':
			case 'title-link':
				$title = Title::newFromText( $value );
				if ( $title ) {
					$value = array();
					ApiQueryBase::addTitleInfo( $value, $title, "{$name}_" );
				}
				return $value;

			case 'user':
			case 'user-link':
				$user = User::newFromName( $value );
				if ( $user ) {
					$value = $user->getName();
				}
				break;

			default:
				// do nothing
				break;
		}

		return array( $name => $value );
	}
}

/**
 * This class formats all log entries for log types
 * which have not been converted to the new system.
 * This is not about old log entries which store
 * parameters in a different format - the new
 * LogFormatter classes have code to support formatting
 * those too.
 * @since 1.19
 */
class LegacyLogFormatter extends LogFormatter {
	/**
	 * Backward compatibility for extension changing the comment from
	 * the LogLine hook. This will be set by the first call on getComment(),
	 * then it might be modified by the hook when calling getActionLinks(),
	 * so that the modified value will be returned when calling getComment()
	 * a second time.
	 *
	 * @var string|null
	 */
	private $comment = null;

	/**
	 * Cache for the result of getActionLinks() so that it does not need to
	 * run multiple times depending on the order that getComment() and
	 * getActionLinks() are called.
	 *
	 * @var string|null
	 */
	private $revert = null;

	public function getComment() {
		if ( $this->comment === null ) {
			$this->comment = parent::getComment();
		}

		// Make sure we execute the LogLine hook so that we immediately return
		// the correct value.
		if ( $this->revert === null ) {
			$this->getActionLinks();
		}

		return $this->comment;
	}

	protected function getActionMessage() {
		$entry = $this->entry;
		$action = LogPage::actionText(
			$entry->getType(),
			$entry->getSubtype(),
			$entry->getTarget(),
			$this->plaintext ? null : $this->context->getSkin(),
			(array)$entry->getParameters(),
			!$this->plaintext // whether to filter [[]] links
		);

		$performer = $this->getPerformerElement();
		if ( !$this->irctext ) {
			$sep = $this->msg( 'word-separator' );
			$sep = $this->plaintext ? $sep->text() : $sep->escaped();
			$action = $performer . $sep . $action;
		}

		return $action;
	}

	public function getActionLinks() {
		if ( $this->revert !== null ) {
			return $this->revert;
		}

		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) ) {
			$this->revert = '';
			return $this->revert;
		}

		$title = $this->entry->getTarget();
		$type = $this->entry->getType();
		$subtype = $this->entry->getSubtype();

		if ( $type == 'protect'
			&& ( $subtype == 'protect' || $subtype == 'modify' || $subtype == 'unprotect' )
		) {
			$links = array(
				Linker::link( $title,
					$this->msg( 'hist' )->escaped(),
					array(),
					array(
						'action' => 'history',
						'offset' => $this->entry->getTimestamp()
					)
				)
			);
			if ( $this->context->getUser()->isAllowed( 'protect' ) ) {
				$links[] = Linker::linkKnown(
					$title,
					$this->msg( 'protect_change' )->escaped(),
					array(),
					array( 'action' => 'protect' )
				);
			}

			return $this->msg( 'parentheses' )->rawParams(
				$this->context->getLanguage()->pipeList( $links ) )->escaped();
		}

		// Do nothing. The implementation is handled by the hook modifiying the
		// passed-by-ref parameters. This also changes the default value so that
		// getComment() and getActionLinks() do not call them indefinitely.
		$this->revert = '';

		// This is to populate the $comment member of this instance so that it
		// can be modified when calling the hook just below.
		if ( $this->comment === null ) {
			$this->getComment();
		}

		$params = $this->entry->getParameters();

		Hooks::run( 'LogLine', array( $type, $subtype, $title, $params,
			&$this->comment, &$this->revert, $this->entry->getTimestamp() ) );

		return $this->revert;
	}
}
