<?php
/**
 * Contains a class for formatting log legacy entries
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.19
 */

namespace MediaWiki\Logging;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;

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

	private HookRunner $hookRunner;

	public function __construct(
		LogEntry $entry,
		HookContainer $hookContainer
	) {
		parent::__construct( $entry );
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/** @inheritDoc */
	public function getComment() {
		$this->comment ??= parent::getComment();

		// Make sure we execute the LogLine hook so that we immediately return
		// the correct value.
		if ( $this->revert === null ) {
			$this->getActionLinks();
		}

		return $this->comment;
	}

	/**
	 * @return string
	 * @return-taint onlysafefor_html
	 */
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

	/** @inheritDoc */
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

		// Do nothing. The implementation is handled by the hook modifying the
		// passed-by-ref parameters. This also changes the default value so that
		// getComment() and getActionLinks() do not call them indefinitely.
		$this->revert = '';

		// This is to populate the $comment member of this instance so that it
		// can be modified when calling the hook just below.
		if ( $this->comment === null ) {
			$this->getComment();
		}

		$params = $this->entry->getParameters();

		$this->hookRunner->onLogLine(
			$type, $subtype, $title, $params, $this->comment, $this->revert, $this->entry->getTimestamp() );

		return $this->revert;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LegacyLogFormatter::class, 'LegacyLogFormatter' );
