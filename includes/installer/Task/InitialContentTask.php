<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
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

	/** @inheritDoc */
	public function getName() {
		return 'mainpage';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'services', 'extension-tables' ];
	}

	public function execute(): Status {
		$this->initServices( $this->getServices() );
		$pages = $this->getConfigVar( MainConfigNames::InstallerInitialPages );
		$status = Status::newGood();
		foreach ( $pages as $pageInfo ) {
			$status->merge( $this->createPage( $pageInfo ) );
		}
		return $status;
	}

	/**
	 * Create a page using an associative array of information
	 *
	 * @see \MediaWiki\MainConfigSchema::InstallerInitialPages
	 *
	 * @param array $pageInfo Associative array of page info
	 * @return Status
	 */
	private function createPage( array $pageInfo ) {
		if ( isset( $pageInfo['title'] ) ) {
			$titleText = $pageInfo['title'];
		} elseif ( isset( $pageInfo['titlemsg'] ) ) {
			$titleText = wfMessage( $pageInfo['titlemsg'] )->inContentLanguage()->text();
		} else {
			throw new \InvalidArgumentException(
				'InstallerInitialPages is missing title/titlemsg' );
		}
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

		if ( isset( $pageInfo['text'] ) ) {
			$text = $pageInfo['text'];
		} elseif ( isset( $pageInfo['textmsg'] ) ) {
			$text = wfMessage( $pageInfo['textmsg'] )->inContentLanguage()->text();
		} else {
			throw new \InvalidArgumentException(
				'InstallerInitialPages is missing text/textmsg' );
		}
		$text = $this->replaceVariables( $text );

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
			$status->fatal( 'config-install-mainpage-failed', $e->__toString() );
		}

		return $status;
	}

	/**
	 * Get services from the restored service container
	 */
	private function initServices( MediaWikiServices $services ) {
		$this->wikiPageFactory = $services->getWikiPageFactory();
		$this->pageStore = $services->getPageStore();
	}

	/**
	 * Replace {{InstallerOption:}} and {{InstallerConfig:}} pseudo-parser functions
	 *
	 * @param string $text
	 * @return string
	 */
	private function replaceVariables( string $text ): string {
		$text = preg_replace_callback(
			'/\{\{ *InstallerOption: *(\w+) *}}/',
			function ( $match ) {
				return (string)$this->getOption( $match[1] );
			},
			$text
		);
		$text = preg_replace_callback(
			'/\{\{ *InstallerConfig: *(\w+) *}}/',
			function ( $match ) {
				return (string)$this->getConfigVar( $match[1] );
			},
			$text
		);
		return $text;
	}

}
