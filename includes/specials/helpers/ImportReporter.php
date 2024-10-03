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
 */

use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Status\Status;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Title\ForeignTitle;

/**
 * Reporting callback
 * @ingroup SpecialPage
 */
class ImportReporter extends ContextSource {
	use ProtectedHookAccessorTrait;

	/** @var string */
	private $reason;
	/** @var string[] */
	private $logTags = [];
	/** @var callable|null */
	private $mOriginalLogCallback;
	/** @var callable|null */
	private $mOriginalPageOutCallback;
	/** @var int */
	private $mLogItemCount = 0;
	/** @var int */
	private $mPageCount = 0;
	/** @var bool */
	private $mIsUpload;
	/** @var string */
	private $mInterwiki;

	/**
	 * @param WikiImporter $importer
	 * @param bool $upload
	 * @param string $interwiki
	 * @param string|bool $reason
	 * @param IContextSource|null $context
	 */
	public function __construct( $importer, $upload, $interwiki, $reason = "", ?IContextSource $context = null ) {
		if ( $context ) {
			$this->setContext( $context );
		} else {
			wfDeprecated( __METHOD__ . ' without $context', '1.42' );
		}
		$this->mOriginalPageOutCallback =
			$importer->setPageOutCallback( [ $this, 'reportPage' ] );
		$this->mOriginalLogCallback =
			$importer->setLogItemCallback( [ $this, 'reportLogItem' ] );
		$importer->setNoticeCallback( [ $this, 'reportNotice' ] );
		$this->mIsUpload = $upload;
		$this->mInterwiki = $interwiki;
		$this->reason = is_string( $reason ) ? $reason : "";
	}

	/**
	 * Sets change tags to apply to the import log entry and null revision.
	 *
	 * @param string[] $tags
	 * @since 1.29
	 */
	public function setChangeTags( array $tags ) {
		$this->logTags = $tags;
	}

	public function open() {
		$this->getOutput()->addHTML( "<ul>\n" );
	}

	public function reportNotice( $msg, array $params ) {
		$this->getOutput()->addHTML(
			Html::element( 'li', [], $this->msg( $msg, $params )->text() )
		);
	}

	public function reportLogItem( ...$args ) {
		$this->mLogItemCount++;
		if ( is_callable( $this->mOriginalLogCallback ) ) {
			( $this->mOriginalLogCallback )( ...$args );
		}
	}

	/**
	 * @param ?PageIdentity $pageIdentity
	 * @param ForeignTitle $foreignTitle
	 * @param int $revisionCount
	 * @param int $successCount
	 * @param array $pageInfo
	 * @return void
	 */
	public function reportPage( ?PageIdentity $pageIdentity, $foreignTitle, $revisionCount,
			$successCount, $pageInfo ) {
		( $this->mOriginalPageOutCallback )( $pageIdentity, $foreignTitle, $revisionCount, $successCount, $pageInfo );

		if ( $pageIdentity === null ) {
			# Invalid or non-importable title; a notice is already displayed
			return;
		}

		$this->mPageCount++;
		$services = MediaWikiServices::getInstance();
		$linkRenderer = $services->getLinkRenderer();
		if ( $successCount > 0 ) {
			// <bdi> prevents jumbling of the versions count
			// in RTL wikis in case the page title is LTR
			$this->getOutput()->addHTML(
				"<li>" . $linkRenderer->makeLink( $pageIdentity ) . " " .
				"<bdi>" .
				$this->msg( 'import-revision-count' )->numParams( $successCount )->escaped() .
				"</bdi>" .
				"</li>\n"
			);

			$logParams = [ '4:number:count' => $successCount ];
			if ( $this->mIsUpload ) {
				$detail = $this->msg( 'import-logentry-upload-detail' )->numParams(
					$successCount )->inContentLanguage()->text();
				$action = 'upload';
			} else {
				$pageTitle = $foreignTitle->getFullText();
				$fullInterwikiPrefix = $this->mInterwiki;
				$this->getHookRunner()->onImportLogInterwikiLink(
					$fullInterwikiPrefix, $pageTitle );

				$interwikiTitleStr = $fullInterwikiPrefix . ':' . $pageTitle;
				$interwiki = '[[:' . $interwikiTitleStr . ']]';
				$detail = $this->msg( 'import-logentry-interwiki-detail' )->numParams(
					$successCount )->params( $interwiki )->inContentLanguage()->text();
				$action = 'interwiki';
				$logParams['5:title-link:interwiki'] = $interwikiTitleStr;
			}
			if ( $this->reason ) {
				$detail .= $this->msg( 'colon-separator' )->inContentLanguage()->text()
					. $this->reason;
			}

			$wikiPage = $services->getWikiPageFactory()->newFromTitle( $pageIdentity );
			$dummyRevRecord = $wikiPage->newPageUpdater( $this->getUser() )
				->setCause( PageUpdater::CAUSE_IMPORT )
				->saveDummyRevision( $detail, EDIT_SILENT | EDIT_MINOR );

			// Create the import log entry
			$logEntry = new ManualLogEntry( 'import', $action );
			$logEntry->setTarget( $pageIdentity );
			$logEntry->setComment( $this->reason );
			$logEntry->setPerformer( $this->getUser() );
			$logEntry->setParameters( $logParams );
			// Make sure the null revision will be tagged as well
			$logEntry->setAssociatedRevId( $dummyRevRecord->getId() );
			if ( count( $this->logTags ) ) {
				$logEntry->addTags( $this->logTags );
			}
			$logid = $logEntry->insert();
			$logEntry->publish( $logid );
		} else {
			$this->getOutput()->addHTML( "<li>" . $linkRenderer->makeKnownLink( $pageIdentity ) . " " .
				$this->msg( 'import-nonewrevisions' )->escaped() . "</li>\n" );
		}
	}

	public function close() {
		$out = $this->getOutput();
		if ( $this->mLogItemCount > 0 ) {
			$msg = $this->msg( 'imported-log-entries' )->numParams( $this->mLogItemCount )->parse();
			$out->addHTML( Html::rawElement( 'li', [], $msg ) );
		} elseif ( $this->mPageCount == 0 && $this->mLogItemCount == 0 ) {
			$out->addHTML( "</ul>\n" );

			return Status::newFatal( 'importnopages' );
		}
		$out->addHTML( "</ul>\n" );

		return Status::newGood( $this->mPageCount );
	}
}
