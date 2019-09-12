<?php
/**
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
 * @ingroup Change tagging
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;

/**
 * Item class for a logging table row with its associated change tags.
 * @todo Abstract out a base class for this and RevDelLogItem, similar to the
 * RevisionItem class but specifically for log items.
 * @since 1.25
 */
class ChangeTagsLogItem extends RevisionItemBase {
	public function getIdField() {
		return 'log_id';
	}

	public function getTimestampField() {
		return 'log_timestamp';
	}

	public function getAuthorIdField() {
		return 'log_user';
	}

	public function getAuthorNameField() {
		return 'log_user_text';
	}

	public function getAuthorActorField() {
		return 'log_actor';
	}

	public function canView() {
		return LogEventsList::userCan(
			$this->row, RevisionRecord::SUPPRESSED_ALL, $this->list->getUser()
		);
	}

	public function canViewContent() {
		return true; // none
	}

	/**
	 * @return string Comma-separated list of tags
	 */
	public function getTags() {
		return $this->row->ts_tags;
	}

	/**
	 * @return string A HTML <li> element representing this revision, showing
	 * change tags and everything
	 */
	public function getHTML() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->row->log_timestamp, $this->list->getUser() ) );
		$title = Title::makeTitle( $this->row->log_namespace, $this->row->log_title );
		$formatter = LogFormatter::newFromRow( $this->row );
		$formatter->setContext( $this->list->getContext() );
		$formatter->setAudience( LogFormatter::FOR_THIS_USER );

		// Log link for this page
		$loglink = MediaWikiServices::getInstance()->getLinkRenderer()->makeLink(
			SpecialPage::getTitleFor( 'Log' ),
			$this->list->msg( 'log' )->text(),
			[],
			[ 'page' => $title->getPrefixedText() ]
		);
		$loglink = $this->list->msg( 'parentheses' )->rawParams( $loglink )->escaped();
		// User links and action text
		$action = $formatter->getActionText();

		$comment = $this->list->getLanguage()->getDirMark() .
			$formatter->getComment();

		if ( LogEventsList::isDeleted( $this->row, LogPage::DELETED_COMMENT ) ) {
			$comment = '<span class="history-deleted">' . $comment . '</span>';
		}

		$content = "$loglink $date $action $comment";
		$attribs = [];
		$tags = $this->getTags();
		if ( $tags ) {
			list( $tagSummary, $classes ) = ChangeTags::formatSummaryRow(
				$tags,
				'edittags',
				$this->list->getContext()
			);
			$content .= " $tagSummary";
			$attribs['class'] = implode( ' ', $classes );
		}
		return Xml::tags( 'li', $attribs, $content );
	}
}
