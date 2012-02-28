<?php
/**
 * Contains classes for formatting log entries
 *
 * @file
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.19
 */

/**
 * Implements the default log formatting.
 * Can be overridden by subclassing and setting
 * $wgLogActionsHandlers['type/subtype'] = 'class'; or
 * $wgLogActionsHandlers['type/*'] = 'class';
 * @since 1.19
 */
class LogFormatter {
	// Audience options for viewing usernames, comments, and actions
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;

	// Static->

	/**
	 * Constructs a new formatter suitable for given entry.
	 * @param $entry LogEntry
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
	 * @param $row
	 * @see DatabaseLogEntry::getSelectQueryData
	 * @return LogFormatter
	 */
	public static function newFromRow( $row ) {
		return self::newFromEntry( DatabaseLogEntry::newFromRow( $row ) );
	}

	// Nonstatic->

	/// @var LogEntry
	protected $entry;

	/// Integer constant for handling log_deleted
	protected $audience = self::FOR_PUBLIC;

	/// Whether to output user tool links
	protected $linkFlood = false;

	/**
	 * Set to true if we are constructing a message text that is going to
	 * be included in page history or send to IRC feed. Links are replaced
	 * with plaintext or with [[pagename]] kind of syntax, that is parsed
	 * by page histories and IRC feeds.
	 * @var boolean
	 */
	protected $plaintext = false;

	protected $irctext = false;

	protected function __construct( LogEntry $entry ) {
		$this->entry = $entry;
		$this->context = RequestContext::getMain();
	}

	/**
	 * Replace the default context
	 * @param $context IContextSource
	 */
	public function setContext( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * Set the visibility restrictions for displaying content.
	 * If set to public, and an item is deleted, then it will be replaced 
	 * with a placeholder even if the context user is allowed to view it.
	 * @param $audience integer self::FOR_THIS_USER or self::FOR_PUBLIC
	 */
	public function setAudience( $audience ) {
		$this->audience = ( $audience == self::FOR_THIS_USER )
			? self::FOR_THIS_USER 
			: self::FOR_PUBLIC;
	}

	/**
	 * Check if a log item can be displayed
	 * @param $field integer LogPage::DELETED_* constant
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
	 * @param $value boolean
	 */
	public function setShowUserToolLinks( $value ) {
		$this->linkFlood = $value;
	}

	/**
	 * Ugly hack to produce plaintext version of the message.
	 * Usually you also want to set extraneous request context
	 * to avoid formatting for any particular user.
	 * @see getActionText()
	 * @return string text
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
	 * @return string text
	 */
	public function getIRCActionText() {
		$this->plaintext = true;
		$text = $this->getActionText();

		$entry = $this->entry;
		$parameters = $entry->getParameters();
		// @see LogPage::actionText()
		$msgOpts = array( 'parsemag', 'escape', 'replaceafter', 'content' );
		// Text of title the action is aimed at.
		$target = $entry->getTarget()->getPrefixedText() ;
		$text = null;
		switch( $entry->getType() ) {
			case 'move':
				switch( $entry->getSubtype() ) {
					case 'move':
						$movesource =  $parameters['4::target'];
						$text = wfMsgExt( '1movedto2', $msgOpts, $target, $movesource );
						break;
					case 'move_redir':
						$movesource =  $parameters['4::target'];
						$text = wfMsgExt( '1movedto2_redir', $msgOpts, $target, $movesource );
						break;
					case 'move-noredirect':
						break;
					case 'move_redir-noredirect':
						break;
				}
				break;

			case 'delete':
				switch( $entry->getSubtype() ) {
					case 'delete':
						$text = wfMsgExt( 'deletedarticle', $msgOpts, $target );
						break;
					case 'restore':
						$text = wfMsgExt( 'undeletedarticle', $msgOpts, $target );
						break;
					//case 'revision': // Revision deletion
					//case 'event': // Log deletion
						// see https://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/LogPage.php?&pathrev=97044&r1=97043&r2=97044
					//default:
				}
				break;

			case 'patrol':
				// https://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/PatrolLog.php?&pathrev=97495&r1=97494&r2=97495
				// Create a diff link to the patrolled revision
				if ( $entry->getSubtype() === 'patrol' ) {
					$diffLink = htmlspecialchars(
						wfMsgForContent( 'patrol-log-diff', $parameters['4::curid'] ) );
					$text = wfMsgForContent( 'patrol-log-line', $diffLink, "[[$target]]", "" );
				} else {
					// broken??
				}
				break;

			case 'newusers':
				switch( $entry->getSubtype() ) {
					case 'newusers':
					case 'create':
						$text = wfMsgExt( 'newuserlog-create-entry', $msgOpts /* no params */ );
						break;
					case 'create2':
						$text = wfMsgExt( 'newuserlog-create2-entry', $msgOpts, $target );
						break;
					case 'autocreate':
						$text = wfMsgExt( 'newuserlog-autocreate-entry', $msgOpts /* no params */ );
						break;
				}
				break;

			case 'upload':
				switch( $entry->getSubtype() ) {
					case 'upload':
						$text = wfMsgExt( 'uploadedimage', $msgOpts, $target );
						break;
					case 'overwrite':
						$text = wfMsgExt( 'overwroteimage', $msgOpts, $target );
						break;
				}
				break;

			// case 'suppress' --private log -- aaron  (sign your messages so we know who to blame in a few years :-D)
			// default:
		}
		if( is_null( $text ) ) {
			$text = $this->getPlainActionText();
		}

		$this->plaintext = false;
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
			$performer = $this->getPerformerElement() . $this->msg( 'word-separator' )->text();
			$element = $performer . $this->getRestrictedElement( 'rev-deleted-event' );
		}

		return $element;
	}

	/**
	 * Returns a sentence describing the log action. Usually
	 * a Message object is returned, but old style log types
	 * and entries might return pre-escaped html string.
	 * @return Message|pre-escaped html
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
	 * @return string message key
	 */
	protected function getMessageKey() {
		$type = $this->entry->getType();
		$subtype = $this->entry->getSubtype();

		return "logentry-$type-$subtype";
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
			if ( strpos( $key, ':' ) === false ) continue;
			list( $index, $type, $name ) = explode( ':', $key, 3 );
			$params[$index - 1] = $value;
		}

		/* Message class doesn't like non consecutive numbering.
		 * Fill in missing indexes with empty strings to avoid
		 * incorrect renumbering.
		 */
		if ( count( $params ) ) {
			$max = max( array_keys( $params ) );
			for ( $i = 4; $i < $max; $i++ ) {
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
		$params[1] = $entry->getPerformer()->getName();
		$params[2] = Message::rawParam( $this->makePageLink( $entry->getTarget() ) );

		// Bad things happens if the numbers are not in correct order
		ksort( $params );
		return $this->parsedParameters = $params;
	}

	/**
	 * Helper to make a link to the page, taking the plaintext
	 * value in consideration.
	 * @param $title Title the page
	 * @param $parameters array query parameters
	 * @return String
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
	 * Gets the luser provided comment
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
	 * @param $message string
	 * @return string HTML or wikitext
	 */
	protected function getRestrictedElement( $message ) {
		if ( $this->plaintext ) {
			return $this->msg( $message )->text();
		}

		$content =  $this->msg( $message )->escaped();
		$attribs = array( 'class' => 'history-deleted' );
		return Html::rawElement( 'span', $attribs, $content );
	}

	/**
	 * Helper method for styling restricted element.
	 * @param $content string
	 * @return string HTML or wikitext
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
	 * @todo Would it be better to require replacing the global context instead?
	 * @param $key string
	 * @return Message
	 */
	protected function msg( $key ) {
		return wfMessage( $key )
			->inLanguage( $this->context->getLanguage() )
			->title( $this->context->getTitle() );
	}

	protected function makeUserLink( User $user ) {
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
					true, // Red if no edits
					0, // Flags
					$user->getEditCount()
				);
			}
		}
		return $element;
	}

	/**
	 * @return Array of titles that should be preloaded with LinkBatch.
	 */
	public function getPreloadTitles() {
		return array();
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
		return $performer .  $this->msg( 'word-separator' )->text() . $action;
	}

}

/**
 * This class formats move log entries.
 * @since 1.19
 */
class MoveLogFormatter extends LogFormatter {
	public function getPreloadTitles() {
		$params = $this->extractParameters();
		return array( Title::newFromText( $params[3] ) );
	}

	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->getMessageParameters();
		if ( isset( $params[4] ) && $params[4] === '1' ) {
			$key .= '-noredirect';
		}
		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$oldname = $this->makePageLink( $this->entry->getTarget(), array( 'redirect' => 'no' ) );
		$newname = $this->makePageLink( Title::newFromText( $params[3] ) );
		$params[2] = Message::rawParam( $oldname );
		$params[3] = Message::rawParam( $newname );
		return $params;
	}
}

/**
 * This class formats delete log entries.
 * @since 1.19
 */
class DeleteLogFormatter extends LogFormatter {
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		if ( in_array( $this->entry->getSubtype(), array( 'event', 'revision' ) ) ) {
			if ( count( $this->getMessageParameters() ) < 5 ) {
				return "$key-legacy";
			}
		}
		return $key;
	}

	protected function getMessageParameters() {
		if ( isset( $this->parsedParametersDeleteLog ) ) {
			return $this->parsedParametersDeleteLog;
		}

		$params = parent::getMessageParameters();
		$subtype = $this->entry->getSubtype();
		if ( in_array( $subtype, array( 'event', 'revision' ) ) ) {
			if (
				($subtype === 'event' && count( $params ) === 6 ) ||
				($subtype === 'revision' && isset( $params[3] ) && $params[3] === 'revision' )
			) {
				$paramStart = $subtype === 'revision' ? 4 : 3;

				$old = $this->parseBitField( $params[$paramStart+1] );
				$new = $this->parseBitField( $params[$paramStart+2] );
				list( $hid, $unhid, $extra ) = RevisionDeleter::getChanges( $new, $old );
				$changes = array();
				foreach ( $hid as $v ) {
					$changes[] = $this->msg( "$v-hid" )->plain();
				}
				foreach ( $unhid as $v ) {
					$changes[] = $this->msg( "$v-unhid" )->plain();
				}
				foreach ( $extra as $v ) {
					$changes[] = $this->msg( $v )->plain();
				}
				$changeText =  $this->context->getLanguage()->listToText( $changes );


				$newParams = array_slice( $params, 0, 3 );
				$newParams[3] = $changeText;
				$count = count( explode( ',', $params[$paramStart] ) );
				$newParams[4] = $this->context->getLanguage()->formatNum( $count );
				return $this->parsedParametersDeleteLog = $newParams;
			} else {
				return $this->parsedParametersDeleteLog = array_slice( $params, 0, 3 );
			}
		}

		return $this->parsedParametersDeleteLog = $params;
	}

	protected function parseBitField( $string ) {
		// Input is like ofield=2134 or just the number
		if ( strpos( $string, 'field=' ) === 1 ) {
			list( , $field ) = explode( '=', $string );
			return (int) $field;
		} else {
			return (int) $string;
		}
	}
}

/**
 * This class formats patrol log entries.
 * @since 1.19
 */
class PatrolLogFormatter extends LogFormatter {
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->getMessageParameters();
		if ( isset( $params[5] ) && $params[5] ) {
			$key .= '-auto';
		}
		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$newParams = array_slice( $params, 0, 3 );

		$target = $this->entry->getTarget();
		$oldid = $params[3];
		$revision = $this->context->getLanguage()->formatNum( $oldid, true );

		if ( $this->plaintext ) {
			$revlink = $revision;
		} elseif ( $target->exists() ) {
			$query = array(
				'oldid' => $oldid,
				'diff' => 'prev'
			);
			$revlink = Linker::link( $target, htmlspecialchars( $revision ), array(), $query );
		} else {
			$revlink = htmlspecialchars( $revision );
		}

		$newParams[3] = Message::rawParam( $revlink );
		return $newParams;
	}
}

/**
 * This class formats new user log entries.
 * @since 1.19
 */
class NewUsersLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		if ( $this->entry->getSubtype() === 'create2' ) {
			if ( isset( $params[3] ) ) {
				$target = User::newFromId( $params[3] );
			} else {
				$target = User::newFromName( $this->entry->getTarget()->getText(), false );
			}
			$params[2] = Message::rawParam( $this->makeUserLink( $target ) );
			$params[3] = $target->getName();
		}
		return $params;
	}

	public function getComment() {
		$timestamp = wfTimestamp( TS_MW, $this->entry->getTimestamp() );
		if ( $timestamp < '20080129000000' ) {
			# Suppress $comment from old entries (before 2008-01-29),
			# not needed and can contain incorrect links
			return '';
		}
		return parent::getComment();
	}
}
