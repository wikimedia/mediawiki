<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Html\Html;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatter;
use MediaWiki\Logging\LogPage;
use MediaWiki\MediaWikiServices;
use MediaWiki\RevisionList\RevisionItemBase;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;

/**
 * Item class for a logging table row with its associated change tags.
 *
 * @todo Abstract out a base class for this and RevDelLogItem, similar to the
 * RevisionItem class but specifically for log items.
 *
 * @since 1.25
 * @ingroup ChangeTags
 */
class ChangeTagsLogItem extends RevisionItemBase {
	/** @inheritDoc */
	public function getIdField() {
		return 'log_id';
	}

	/** @inheritDoc */
	public function getTimestampField() {
		return 'log_timestamp';
	}

	/** @inheritDoc */
	public function getAuthorIdField() {
		return 'log_user';
	}

	/** @inheritDoc */
	public function getAuthorNameField() {
		return 'log_user_text';
	}

	/** @inheritDoc */
	public function getAuthorActorField() {
		return 'log_actor';
	}

	/** @inheritDoc */
	public function canView() {
		return LogEventsList::userCan(
			$this->row, LogPage::DELETED_RESTRICTED, $this->list->getAuthority()
		);
	}

	/** @inheritDoc */
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
		$services = MediaWikiServices::getInstance();
		$formatter = $services->getLogFormatterFactory()->newFromRow( $this->row );
		$formatter->setContext( $this->list->getContext() );
		$formatter->setAudience( LogFormatter::FOR_THIS_USER );

		// Log link for this page
		$loglink = $services->getLinkRenderer()->makeLink(
			SpecialPage::getTitleFor( 'Log' ),
			$this->list->msg( 'log' )->text(),
			[],
			[ 'page' => $title->getPrefixedText() ]
		);
		$loglink = $this->list->msg( 'parentheses' )->rawParams( $loglink )->escaped();
		// User links and action text
		$action = $formatter->getActionText();

		$dir = $this->list->getLanguage()->getDir();
		$comment = Html::rawElement( 'bdi', [ 'dir' => $dir ], $formatter->getComment() );

		$content = "$loglink $date $action $comment";
		$attribs = [];
		$tags = $this->getTags();
		if ( $tags ) {
			[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
				$tags,
				'edittags',
				$this->list->getContext()
			);
			$content .= " $tagSummary";
			$attribs['class'] = $classes;
		}
		return Html::rawElement( 'li', $attribs, $content );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangeTagsLogItem::class, 'ChangeTagsLogItem' );
