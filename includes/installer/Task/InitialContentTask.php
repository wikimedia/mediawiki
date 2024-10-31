<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\User\User;

/**
 * Create initial pages
 *
 * @internal For use by the installer
 */
class InitialContentTask extends Task {
	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var PageStore */
	private $pageStore;

	public function getName() {
		return 'mainpage';
	}

	public function getDependencies() {
		return 'services';
	}

	public function execute(): Status {
		$this->initServices( $this->getServices() );
		$titleText = wfMessage( 'mainpage' )->inContentLanguage()->text();
		$title = $this->pageStore->getPageByText( $titleText );
		$status = Status::newGood();
		if ( !$title ) {
			$status->warning( 'config-install-mainpage-failed', 'invalid title' );
			return $status;
		}
		if ( $title->getId() ) {
			$status->warning( 'config-install-mainpage-exists', $titleText );
			return $status;
		}

		$page = $this->wikiPageFactory->newFromTitle( $title );
		$text = wfMessage( 'mainpagetext' )->inContentLanguage()->text() . "\n\n" .
			wfMessage( 'mainpagedocfooter' )->inContentLanguage()->text();

		$content = new WikitextContent( $text );

		try {
			$updater = $page->newPageUpdater( User::newSystemUser( 'MediaWiki default' ) );
			$updater
				->setContent( SlotRecord::MAIN, $content )
				->saveRevision(
					CommentStoreComment::newUnsavedComment( '' )
				);
			$status = $updater->getStatus();
		} catch ( \Exception $e ) {
			// using raw, because $wgShowExceptionDetails can not be set yet
			$status->fatal( 'config-install-mainpage-failed', $e->getMessage() );
		}

		return $status;
	}

	private function initServices( MediaWikiServices $services ) {
		$this->wikiPageFactory = $services->getWikiPageFactory();
		$this->pageStore = $services->getPageStore();
	}

}
