<?php
/**
 * Legacy log formatting.
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
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;

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

		Hooks::run( 'LogLine', [ $type, $subtype, $title, $params,
			&$this->comment, &$this->revert, $this->entry->getTimestamp() ] );

		return $this->revert;
	}
}
