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

		if ( $handler !== '' && class_exists( $handler ) ) {
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

	protected function __construct( LogEntry $entry ) {
		$this->entry = $entry;
		$this->context = RequestContext::getMain();
	}

	/**
	 * Replace the default context
	 * @param $context RequestContext
	 */
	public function setContext( RequestContext $context ) {
		$this->context = $context;
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
	 * Gets the log action, including username.
	 * @return string HTML
	 */
	public function getActionText() {
		$element = $this->getActionMessage();
		if ( $element instanceof Message ) {
			$element = $this->plaintext ? $element->text() : $element->escaped();
		}

		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) ) {
			$performer = $this->getPerformerElement() . $this->msg( 'word-separator' )->text();
			$element = $performer . self::getRestrictedElement( 'rev-deleted-event' );
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
		$message =  $this->msg( $this->getMessageKey() );
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
		$key = "logentry-$type-$subtype";
		return $key;
	}

	/**
	 * Extract parameters intented for action message from
	 * array of all parameters. The are three hardcoded
	 * parameters (array zero-indexed, this list not):
	 *  - 1: user name with premade link
	 *  - 2: usable for gender magic function
	 *  - 3: target page with premade link
	 * @return array
	 */
	protected function getMessageParameters() {
		$entry = $this->entry;

		$params = array();
		$params[0] = Message::rawParam( $this->getPerformerElement() );
		$params[1] = $entry->getPerformer()->getName();
		$params[2] = Message::rawParam( $this->makePageLink( $entry->getTarget() ) );

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
		$max = max( array_keys( $params ) );
		for ( $i = 4; $i < $max; $i++ ) {
			if ( !isset( $params[$i] ) ) {
				$params[$i] = '';
			}
		}

		return $params;
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
		$performer = $this->entry->getPerformer();

		if ( $this->plaintext ) {
			$element = $performer->getName();
		} else {
			$element = Linker::userLink(
				$performer->getId(),
				$performer->getName()
			);

			if ( $this->linkFlood ) {
				$element .= Linker::userToolLinks(
					$performer->getId(),
					$performer->getName(),
					true, // Red if no edits
					0, // Flags
					$performer->getEditCount()
				);
			}
		}

		if ( $this->entry->isDeleted( LogPage::DELETED_USER ) ) {
			$element = self::getRestrictedElement( 'rev-deleted-user' );
		}

		return $element;
	}

	/**
	 * Gets the luser provided comment
	 * @return string HTML
	 */
	public function getComment() {
		$lang = $this->context->getLang();
		$element = $lang->getDirMark() . Linker::commentBlock( $this->entry->getComment() );

		if ( $this->entry->isDeleted( LogPage::DELETED_COMMENT ) ) {
			$element = self::getRestrictedElement( 'rev-deleted-comment' );
		}

		return $element;
	}

	/**
	 * Helper method for displaying restricted element.
	 * @param $message string
	 * @return string HTML
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
	 * Shortcut for wfMessage which honors local context.
	 * @todo Would it be better to require replacing the global context instead?
	 * @param $key string
	 * @return Message
	 */
	protected function msg( $key ) {
		return wfMessage( $key )
			->inLanguage( $this->context->getLang() )
			->title( $this->context->getTitle() );
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
			$this->context->getSkin(),
			(array)$entry->getParameters(),
			true
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
