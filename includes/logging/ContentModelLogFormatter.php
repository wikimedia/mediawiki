<?php

use MediaWiki\MediaWikiServices;

class ContentModelLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		$lang = $this->context->getLanguage();
		$params = parent::getMessageParameters();
		$params[3] = ContentHandler::getLocalizedName( $params[3], $lang );
		$params[4] = ContentHandler::getLocalizedName( $params[4], $lang );
		return $params;
	}

	public function getActionLinks() {
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| $this->entry->getSubtype() !== 'change'
			|| !MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $this->context->getUser(), 'editcontentmodel' )
		) {
			return '';
		}

		$params = $this->extractParameters();
		$revert = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'ChangeContentModel' ),
			$this->msg( 'logentry-contentmodel-change-revertlink' )->text(),
			[],
			[
				'pagetitle' => $this->entry->getTarget()->getPrefixedText(),
				'model' => $params[3],
				'reason' => $this->msg( 'logentry-contentmodel-change-revert' )->inContentLanguage()->text(),
			]
		);

		return $this->msg( 'parentheses' )->rawParams( $revert )->escaped();
	}
}
