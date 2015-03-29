<?php

class ContentModelLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$params[3] = ContentHandler::getLocalizedName( $params[3] );
		$params[4] = ContentHandler::getLocalizedName( $params[4] );
		return $params;
	}

	public function getActionLinks() {
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| $this->entry->getSubtype() !== 'change'
			|| !$this->context->getUser()->isAllowed( 'editcontentmodel' )
		) {
			return '';
		}

		$params = $this->extractParameters();
		$revert = Linker::linkKnown(
			SpecialPage::getTitleFor( 'ChangeContentModel' ),
			$this->msg( 'logentry-contentmodel-change-revertlink' )->escaped(),
			array(),
			array(
				'name' => $this->entry->getTarget()->getPrefixedText(),
				'model' => $params[3],
				'reason' => $this->msg( 'logentry-contentmodel-change-revert' )->inContentLanguage()->text(),
			)
		);

		return $this->msg( 'parentheses' )->rawParams( $revert )->escaped();
	}
}
