<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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
 * @ingroup SpecialPage
 */

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Config\Config;
use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Status\Status;
use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\ImportTitleFactory;
use MediaWiki\Title\NaiveForeignTitleFactory;
use MediaWiki\Title\NaiveImportTitleFactory;
use MediaWiki\Title\NamespaceAwareForeignTitleFactory;
use MediaWiki\Title\NamespaceImportTitleFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\SubpageImportTitleFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\ExternalUserNames;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\NormalizedException\NormalizedException;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * XML file reader for the page data importer.
 *
 * implements Special:Import
 * @ingroup SpecialPage
 */
class WikiImporter {
	/** @var XMLReader|null */
	private $reader;

	/** @var string */
	private $sourceAdapterId;

	/** @var array|null */
	private $foreignNamespaces = null;

	/** @var callable|null */
	private $mLogItemCallback;

	/** @var callable */
	private $mUploadCallback;

	/** @var callable|null */
	private $mRevisionCallback;

	/** @var callable|null */
	private $mPageCallback;

	/** @var callable|null */
	private $mSiteInfoCallback;

	/** @var callable|null */
	private $mPageOutCallback;

	/** @var callable|null */
	private $mNoticeCallback;

	/** @var bool|null */
	private $mDebug;

	/** @var bool|null */
	private $mImportUploads;

	/** @var string|null */
	private $mImageBasePath;

	/** @var bool */
	private $mNoUpdates = false;

	/** @var int */
	private $pageOffset = 0;

	private ImportTitleFactory $importTitleFactory;
	private ExternalUserNames $externalUserNames;

	/** @var array */
	private $countableCache = [];

	/** @var bool */
	private $disableStatisticsUpdate = false;

	/**
	 * Authority used for permission checks only (to ensure that the user performing the import is
	 * allowed to edit the pages they're importing). To skip the checks, use UltimateAuthority.
	 *
	 * If you want to also log the import actions, see ImportReporter.
	 */
	private Authority $performer;

	private Config $config;
	private HookRunner $hookRunner;
	private Language $contentLanguage;
	private NamespaceInfo $namespaceInfo;
	private TitleFactory $titleFactory;
	private WikiPageFactory $wikiPageFactory;
	private UploadRevisionImporter $uploadRevisionImporter;
	private IContentHandlerFactory $contentHandlerFactory;
	private SlotRoleRegistry $slotRoleRegistry;

	/**
	 * Creates an ImportXMLReader drawing from the source provided
	 */
	public function __construct(
		ImportSource $source,
		Authority $performer,
		Config $config,
		HookContainer $hookContainer,
		Language $contentLanguage,
		NamespaceInfo $namespaceInfo,
		TitleFactory $titleFactory,
		WikiPageFactory $wikiPageFactory,
		UploadRevisionImporter $uploadRevisionImporter,
		IContentHandlerFactory $contentHandlerFactory,
		SlotRoleRegistry $slotRoleRegistry
	) {
		$this->performer = $performer;
		$this->config = $config;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->contentLanguage = $contentLanguage;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleFactory = $titleFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->uploadRevisionImporter = $uploadRevisionImporter;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->slotRoleRegistry = $slotRoleRegistry;

		if ( !in_array( 'uploadsource', stream_get_wrappers() ) ) {
			stream_wrapper_register( 'uploadsource', UploadSourceAdapter::class );
		}
		$this->sourceAdapterId = UploadSourceAdapter::registerSource( $source );

		$this->openReader();

		// Default callbacks
		$this->setPageCallback( [ $this, 'beforeImportPage' ] );
		$this->setRevisionCallback( [ $this, "importRevision" ] );
		$this->setUploadCallback( [ $this, 'importUpload' ] );
		$this->setLogItemCallback( [ $this, 'importLogItem' ] );
		$this->setPageOutCallback( [ $this, 'finishImportPage' ] );

		$this->importTitleFactory = new NaiveImportTitleFactory(
			$this->contentLanguage,
			$this->namespaceInfo,
			$this->titleFactory
		);
		$this->externalUserNames = new ExternalUserNames( 'imported', false );
	}

	/**
	 * @return null|XMLReader
	 */
	public function getReader() {
		return $this->reader;
	}

	/**
	 * @param string $err
	 */
	public function throwXmlError( $err ) {
		$this->debug( "FAILURE: $err" );
		wfDebug( "WikiImporter XML error: $err" );
	}

	/**
	 * @param string $data
	 */
	public function debug( $data ) {
		if ( $this->mDebug ) {
			wfDebug( "IMPORT: $data" );
		}
	}

	/**
	 * @param string $data
	 */
	public function warn( $data ) {
		wfDebug( "IMPORT: $data" );
	}

	/**
	 * @param string $msg
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 */
	public function notice( $msg, ...$params ) {
		if ( is_callable( $this->mNoticeCallback ) ) {
			( $this->mNoticeCallback )( $msg, $params );
		} else { # No ImportReporter -> CLI
			// T177997: the command line importers should call setNoticeCallback()
			// for their own custom callback to echo the notice
			wfDebug( wfMessage( $msg, $params )->text() );
		}
	}

	/**
	 * Set debug mode...
	 * @param bool $debug
	 */
	public function setDebug( $debug ) {
		$this->mDebug = $debug;
	}

	/**
	 * Set 'no updates' mode. In this mode, the link tables will not be updated by the importer
	 * @param bool $noupdates
	 */
	public function setNoUpdates( $noupdates ) {
		$this->mNoUpdates = $noupdates;
	}

	/**
	 * Sets 'pageOffset' value. So it will skip the first n-1 pages
	 * and start from the nth page. It's 1-based indexing.
	 * @param int $nthPage
	 * @since 1.29
	 */
	public function setPageOffset( $nthPage ) {
		$this->pageOffset = $nthPage;
	}

	/**
	 * Set a callback that displays notice messages
	 *
	 * @param callable $callback
	 * @return callable
	 */
	public function setNoticeCallback( $callback ) {
		return wfSetVar( $this->mNoticeCallback, $callback );
	}

	/**
	 * Sets the action to perform as each new page in the stream is reached.
	 * @param callable|null $callback
	 * @return callable|null
	 */
	public function setPageCallback( $callback ) {
		$previous = $this->mPageCallback;
		$this->mPageCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each page in the stream is completed.
	 * Callback accepts the page title (as a Title object), a second object
	 * with the original title form (in case it's been overridden into a
	 * local namespace), and a count of revisions.
	 *
	 * @param callable|null $callback
	 * @return callable|null
	 */
	public function setPageOutCallback( $callback ) {
		$previous = $this->mPageOutCallback;
		$this->mPageOutCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each page revision is reached.
	 * @param callable|null $callback
	 * @return callable|null
	 */
	public function setRevisionCallback( $callback ) {
		$previous = $this->mRevisionCallback;
		$this->mRevisionCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each file upload version is reached.
	 * @param callable $callback
	 * @return callable
	 */
	public function setUploadCallback( $callback ) {
		$previous = $this->mUploadCallback;
		$this->mUploadCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each log item reached.
	 * @param callable $callback
	 * @return callable
	 */
	public function setLogItemCallback( $callback ) {
		$previous = $this->mLogItemCallback;
		$this->mLogItemCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform when site info is encountered
	 * @param callable $callback
	 * @return callable
	 */
	public function setSiteInfoCallback( $callback ) {
		$previous = $this->mSiteInfoCallback;
		$this->mSiteInfoCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the factory object to use to convert ForeignTitle objects into local
	 * Title objects
	 * @param ImportTitleFactory $factory
	 */
	public function setImportTitleFactory( $factory ) {
		$this->importTitleFactory = $factory;
	}

	/**
	 * Set a target namespace to override the defaults
	 * @param null|int $namespace
	 * @return bool
	 */
	public function setTargetNamespace( $namespace ) {
		if ( $namespace === null ) {
			// Don't override namespaces
			$this->setImportTitleFactory(
				new NaiveImportTitleFactory(
					$this->contentLanguage,
					$this->namespaceInfo,
					$this->titleFactory
				)
			);
			return true;
		} elseif (
			$namespace >= 0 &&
			$this->namespaceInfo->exists( intval( $namespace ) )
		) {
			$namespace = intval( $namespace );
			$this->setImportTitleFactory(
				new NamespaceImportTitleFactory(
					$this->namespaceInfo,
					$this->titleFactory,
					$namespace
				)
			);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Set a target root page under which all pages are imported
	 * @param null|string $rootpage
	 * @return Status
	 */
	public function setTargetRootPage( $rootpage ) {
		$status = Status::newGood();
		$nsInfo = $this->namespaceInfo;
		if ( $rootpage === null ) {
			// No rootpage
			$this->setImportTitleFactory(
				new NaiveImportTitleFactory(
					$this->contentLanguage,
					$nsInfo,
					$this->titleFactory
				)
			);
		} elseif ( $rootpage !== '' ) {
			$rootpage = rtrim( $rootpage, '/' ); // avoid double slashes
			$title = Title::newFromText( $rootpage );

			if ( !$title || $title->isExternal() ) {
				$status->fatal( 'import-rootpage-invalid' );
			} elseif ( !$nsInfo->hasSubpages( $title->getNamespace() ) ) {
				$displayNSText = $title->getNamespace() === NS_MAIN
					? wfMessage( 'blanknamespace' )->text()
					: $this->contentLanguage->getNsText( $title->getNamespace() );
				$status->fatal( 'import-rootpage-nosubpage', $displayNSText );
			} else {
				// set namespace to 'all', so the namespace check in processTitle() can pass
				$this->setTargetNamespace( null );
				$this->setImportTitleFactory(
					new SubpageImportTitleFactory(
						$nsInfo,
						$this->titleFactory,
						$title
					)
				);
			}
		}
		return $status;
	}

	/**
	 * @param string $dir
	 */
	public function setImageBasePath( $dir ) {
		$this->mImageBasePath = $dir;
	}

	/**
	 * @param bool $import
	 */
	public function setImportUploads( $import ) {
		$this->mImportUploads = $import;
	}

	/**
	 * @since 1.31
	 * @param string $usernamePrefix Prefix to apply to unknown (and possibly also known) usernames
	 * @param bool $assignKnownUsers Whether to apply the prefix to usernames that exist locally
	 */
	public function setUsernamePrefix( $usernamePrefix, $assignKnownUsers ) {
		$this->externalUserNames = new ExternalUserNames( $usernamePrefix, $assignKnownUsers );
	}

	/**
	 * Statistics update can cause a lot of time
	 * @since 1.29
	 */
	public function disableStatisticsUpdate() {
		$this->disableStatisticsUpdate = true;
	}

	/**
	 * Default per-page callback. Sets up some things related to site statistics
	 * @param array $titleAndForeignTitle Two-element array, with Title object at
	 * index 0 and ForeignTitle object at index 1
	 * @return bool
	 */
	public function beforeImportPage( $titleAndForeignTitle ) {
		$title = $titleAndForeignTitle[0];
		$page = $this->wikiPageFactory->newFromTitle( $title );
		$this->countableCache['title_' . $title->getPrefixedText()] = $page->isCountable();
		return true;
	}

	/**
	 * Default per-revision callback, performs the import.
	 * @param WikiRevision $revision
	 * @return bool
	 */
	public function importRevision( $revision ) {
		if ( !$revision->getContentHandler()->canBeUsedOn( $revision->getTitle() ) ) {
			$this->notice( 'import-error-bad-location',
				$revision->getTitle()->getPrefixedText(),
				$revision->getID(),
				$revision->getModel(),
				$revision->getFormat()
			);

			return false;
		}

		try {
			return $revision->importOldRevision();
		} catch ( MWContentSerializationException $ex ) {
			$this->notice( 'import-error-unserialize',
				$revision->getTitle()->getPrefixedText(),
				$revision->getID(),
				$revision->getModel(),
				$revision->getFormat()
			);
		}

		return false;
	}

	/**
	 * Default per-revision callback, performs the import.
	 * @param WikiRevision $revision
	 * @return bool
	 */
	public function importLogItem( $revision ) {
		return $revision->importLogItem();
	}

	/**
	 * Dummy for now...
	 * @param WikiRevision $revision
	 * @return bool
	 */
	public function importUpload( $revision ) {
		$status = $this->uploadRevisionImporter->import( $revision );
		return $status->isGood();
	}

	/**
	 * Mostly for hook use
	 * @param PageIdentity $pageIdentity
	 * @param ForeignTitle $foreignTitle
	 * @param int $revCount
	 * @param int $sRevCount
	 * @param array $pageInfo
	 * @return bool
	 */
	public function finishImportPage( PageIdentity $pageIdentity, $foreignTitle, $revCount,
		$sRevCount, $pageInfo
	) {
		// Update article count statistics (T42009)
		// The normal counting logic in WikiPage->doEditUpdates() is designed for
		// one-revision-at-a-time editing, not bulk imports. In this situation it
		// suffers from issues of replica DB lag. We let WikiPage handle the total page
		// and revision count, and we implement our own custom logic for the
		// article (content page) count.
		if ( !$this->disableStatisticsUpdate ) {
			$page = $this->wikiPageFactory->newFromTitle( $pageIdentity );

			$page->loadPageData( IDBAccessObject::READ_LATEST );
			$rev = $page->getRevisionRecord();
			if ( $rev === null ) {

				wfDebug( __METHOD__ . ': Skipping article count adjustment for ' . $pageIdentity .
					' because WikiPage::getRevisionRecord() returned null' );
			} else {
				$update = $page->newPageUpdater( $this->performer )->prepareUpdate();
				$countKey = 'title_' . CacheKeyHelper::getKeyForPage( $pageIdentity );
				$countable = $update->isCountable();
				if ( array_key_exists( $countKey, $this->countableCache ) &&
					$countable != $this->countableCache[$countKey] ) {
					DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [
						'articles' => ( (int)$countable - (int)$this->countableCache[$countKey] )
					] ) );
				}
			}
		}

		$title = Title::newFromPageIdentity( $pageIdentity );
		return $this->hookRunner->onAfterImportPage( $title, $foreignTitle,
			$revCount, $sRevCount, $pageInfo );
	}

	/**
	 * Notify the callback function of site info
	 * @param array $siteInfo
	 * @return mixed|false
	 */
	private function siteInfoCallback( $siteInfo ) {
		if ( $this->mSiteInfoCallback ) {
			return ( $this->mSiteInfoCallback )( $siteInfo, $this );
		} else {
			return false;
		}
	}

	/**
	 * Notify the callback function when a new "<page>" is reached.
	 * @param array $title
	 */
	public function pageCallback( $title ) {
		if ( $this->mPageCallback ) {
			( $this->mPageCallback )( $title );
		}
	}

	/**
	 * Notify the callback function when a "</page>" is closed.
	 * @param PageIdentity $pageIdentity
	 * @param ForeignTitle $foreignTitle
	 * @param int $revCount
	 * @param int $sucCount Number of revisions for which callback returned true
	 * @param array $pageInfo Associative array of page information
	 */
	private function pageOutCallback( PageIdentity $pageIdentity, $foreignTitle, $revCount,
			$sucCount, $pageInfo ) {
		if ( $this->mPageOutCallback ) {
			( $this->mPageOutCallback )( $pageIdentity, $foreignTitle, $revCount, $sucCount, $pageInfo );
		}
	}

	/**
	 * Notify the callback function of a revision
	 * @param WikiRevision $revision
	 * @return bool|mixed
	 */
	private function revisionCallback( $revision ) {
		if ( $this->mRevisionCallback ) {
			return ( $this->mRevisionCallback )( $revision, $this );
		} else {
			return false;
		}
	}

	/**
	 * Notify the callback function of a new log item
	 * @param WikiRevision $revision
	 * @return mixed|false
	 */
	private function logItemCallback( $revision ) {
		if ( $this->mLogItemCallback ) {
			return ( $this->mLogItemCallback )( $revision, $this );
		} else {
			return false;
		}
	}

	/**
	 * Retrieves the contents of the named attribute of the current element.
	 * @param string $attr The name of the attribute
	 * @return string The value of the attribute or an empty string if it is not set in the current
	 * element.
	 */
	public function nodeAttribute( $attr ) {
		return $this->reader->getAttribute( $attr ) ?? '';
	}

	/**
	 * Shouldn't something like this be built-in to XMLReader?
	 * Fetches text contents of the current element, assuming
	 * no sub-elements or such scary things.
	 * @return string
	 * @internal
	 */
	public function nodeContents() {
		if ( $this->reader->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while ( $this->reader->read() ) {
			switch ( $this->reader->nodeType ) {
				case XMLReader::TEXT:
				case XMLReader::CDATA:
				case XMLReader::SIGNIFICANT_WHITESPACE:
					$buffer .= $this->reader->value;
					break;
				case XMLReader::END_ELEMENT:
					return $buffer;
			}
		}

		$this->reader->close();
		return '';
	}

	/**
	 * Primary entry point
	 * @throws Exception
	 * @return bool
	 */
	public function doImport() {
		$this->syntaxCheckXML();

		// Calls to reader->read need to be wrapped in calls to
		// libxml_disable_entity_loader() to avoid local file
		// inclusion attacks (T48932).
		// phpcs:ignore Generic.PHP.NoSilencedErrors -- suppress deprecation per T268847
		$oldDisable = @libxml_disable_entity_loader( true );
		try {
			$this->reader->read();

			if ( $this->reader->localName != 'mediawiki' ) {
				// phpcs:ignore Generic.PHP.NoSilencedErrors
				@libxml_disable_entity_loader( $oldDisable );
				$error = libxml_get_last_error();
				if ( $error ) {
					throw new NormalizedException( "XML error at line {line}: {message}", [
						'line' => $error->line,
						'message' => $error->message,
					] );
				} else {
					throw new UnexpectedValueException(
						"Expected '<mediawiki>' tag, got '<{$this->reader->localName}>' tag."
					);
				}
			}
			$this->debug( "<mediawiki> tag is correct." );

			$this->debug( "Starting primary dump processing loop." );

			$keepReading = $this->reader->read();
			$skip = false;
			$pageCount = 0;
			while ( $keepReading ) {
				$tag = $this->reader->localName;
				if ( $this->pageOffset ) {
					if ( $tag === 'page' ) {
						$pageCount++;
					}
					if ( $pageCount < $this->pageOffset ) {
						$keepReading = $this->reader->next();
						continue;
					}
				}
				$type = $this->reader->nodeType;

				if ( !$this->hookRunner->onImportHandleToplevelXMLTag( $this ) ) {
					// Do nothing
				} elseif ( $tag == 'mediawiki' && $type == XMLReader::END_ELEMENT ) {
					break;
				} elseif ( $tag == 'siteinfo' ) {
					$this->handleSiteInfo();
				} elseif ( $tag == 'page' ) {
					$this->handlePage();
				} elseif ( $tag == 'logitem' ) {
					$this->handleLogItem();
				} elseif ( $tag != '#text' ) {
					$this->warn( "Unhandled top-level XML tag $tag" );

					$skip = true;
				}

				if ( $skip ) {
					$keepReading = $this->reader->next();
					$skip = false;
					$this->debug( "Skip" );
				} else {
					$keepReading = $this->reader->read();
				}
			}
		} finally {
			// phpcs:ignore Generic.PHP.NoSilencedErrors
			@libxml_disable_entity_loader( $oldDisable );
			$this->reader->close();
		}

		return true;
	}

	private function handleSiteInfo() {
		$this->debug( "Enter site info handler." );
		$siteInfo = [];

		// Fields that can just be stuffed in the siteInfo object
		$normalFields = [ 'sitename', 'base', 'generator', 'case' ];

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'siteinfo' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( $tag == 'namespace' ) {
				$this->foreignNamespaces[$this->nodeAttribute( 'key' )] =
					$this->nodeContents();
			} elseif ( in_array( $tag, $normalFields ) ) {
				$siteInfo[$tag] = $this->nodeContents();
			}
		}

		$siteInfo['_namespaces'] = $this->foreignNamespaces;
		$this->siteInfoCallback( $siteInfo );
	}

	private function handleLogItem() {
		$this->debug( "Enter log item handler." );
		$logInfo = [];

		// Fields that can just be stuffed in the pageInfo object
		$normalFields = [ 'id', 'comment', 'type', 'action', 'timestamp',
			'logtitle', 'params' ];

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'logitem' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( !$this->hookRunner->onImportHandleLogItemXMLTag( $this, $logInfo ) ) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$logInfo[$tag] = $this->nodeContents();
			} elseif ( $tag == 'contributor' ) {
				$logInfo['contributor'] = $this->handleContributor();
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled log-item XML tag $tag" );
			}
		}

		$this->processLogItem( $logInfo );
	}

	/**
	 * @param array $logInfo
	 * @return mixed|false
	 */
	private function processLogItem( $logInfo ) {
		$revision = new WikiRevision();

		if ( isset( $logInfo['id'] ) ) {
			$revision->setID( $logInfo['id'] );
		}
		$revision->setType( $logInfo['type'] );
		$revision->setAction( $logInfo['action'] );
		if ( isset( $logInfo['timestamp'] ) ) {
			$revision->setTimestamp( $logInfo['timestamp'] );
		}
		if ( isset( $logInfo['params'] ) ) {
			$revision->setParams( $logInfo['params'] );
		}
		if ( isset( $logInfo['logtitle'] ) ) {
			// @todo Using Title for non-local titles is a recipe for disaster.
			// We should use ForeignTitle here instead.
			$revision->setTitle( Title::newFromText( $logInfo['logtitle'] ) );
		}

		$revision->setNoUpdates( $this->mNoUpdates );

		if ( isset( $logInfo['comment'] ) ) {
			$revision->setComment( $logInfo['comment'] );
		}

		if ( isset( $logInfo['contributor']['username'] ) ) {
			$revision->setUsername(
				$this->externalUserNames->applyPrefix( $logInfo['contributor']['username'] )
			);
		} elseif ( isset( $logInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $logInfo['contributor']['ip'] );
		} else {
			$revision->setUsername( $this->externalUserNames->addPrefix( 'Unknown user' ) );
		}

		return $this->logItemCallback( $revision );
	}

	private function handlePage() {
		// Handle page data.
		$this->debug( "Enter page handler." );
		$pageInfo = [ 'revisionCount' => 0, 'successfulRevisionCount' => 0 ];

		// Fields that can just be stuffed in the pageInfo object
		$normalFields = [ 'title', 'ns', 'id', 'redirect', 'restrictions' ];

		$skip = false;
		$badTitle = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'page' ) {
				break;
			}

			$skip = false;

			$tag = $this->reader->localName;

			if ( $badTitle ) {
				// The title is invalid, bail out of this page
				$skip = true;
			} elseif ( !$this->hookRunner->onImportHandlePageXMLTag( $this, $pageInfo ) ) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				// An XML snippet:
				// <page>
				//     <id>123</id>
				//     <title>Page</title>
				//     <redirect title="NewTitle"/>
				//     ...
				// Because the redirect tag is built differently, we need special handling for that case.
				if ( $tag == 'redirect' ) {
					$pageInfo[$tag] = $this->nodeAttribute( 'title' );
				} else {
					$pageInfo[$tag] = $this->nodeContents();
				}
			} elseif ( $tag == 'revision' || $tag == 'upload' ) {
				if ( !isset( $title ) ) {
					$title = $this->processTitle( $pageInfo['title'],
						$pageInfo['ns'] ?? null );

					// $title is either an array of two titles or false.
					if ( is_array( $title ) ) {
						$this->pageCallback( $title );
						[ $pageInfo['_title'], $foreignTitle ] = $title;
					} else {
						$badTitle = true;
						$skip = true;
					}
				}

				if ( $title ) {
					if ( $tag == 'revision' ) {
						$this->handleRevision( $pageInfo );
					} else {
						$this->handleUpload( $pageInfo );
					}
				}
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled page XML tag $tag" );
				$skip = true;
			}
		}

		// @note $pageInfo is only set if a valid $title is processed above with
		//       no error. If we have a valid $title, then pageCallback is called
		//       above, $pageInfo['title'] is set and we do pageOutCallback here.
		//       If $pageInfo['_title'] is not set, then $foreignTitle is also not
		//       set since they both come from $title above.
		if ( array_key_exists( '_title', $pageInfo ) ) {
			/** @var Title $title */
			$title = $pageInfo['_title'];
			$this->pageOutCallback(
				$title,
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable Set together with _title key
				$foreignTitle,
				$pageInfo['revisionCount'],
				$pageInfo['successfulRevisionCount'],
				$pageInfo
			);
		}
	}

	/**
	 * @param array &$pageInfo
	 */
	private function handleRevision( &$pageInfo ) {
		$this->debug( "Enter revision handler" );
		$revisionInfo = [];

		$normalFields = [ 'id', 'parentid', 'timestamp', 'comment', 'minor', 'origin',
			'model', 'format', 'text', 'sha1' ];

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'revision' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( !$this->hookRunner->onImportHandleRevisionXMLTag(
				$this, $pageInfo, $revisionInfo )
			) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$revisionInfo[$tag] = $this->nodeContents();
			} elseif ( $tag == 'content' ) {
				// We can have multiple content tags, so make this an array.
				$revisionInfo[$tag][] = $this->handleContent();
			} elseif ( $tag == 'contributor' ) {
				$revisionInfo['contributor'] = $this->handleContributor();
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled revision XML tag $tag" );
				$skip = true;
			}
		}

		$pageInfo['revisionCount']++;
		if ( $this->processRevision( $pageInfo, $revisionInfo ) ) {
			$pageInfo['successfulRevisionCount']++;
		}
	}

	private function handleContent(): array {
		$this->debug( "Enter content handler" );
		$contentInfo = [];

		$normalFields = [ 'role', 'origin', 'model', 'format', 'text' ];

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
				$this->reader->localName == 'content' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( !$this->hookRunner->onImportHandleContentXMLTag(
				$this, $contentInfo )
			) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$contentInfo[$tag] = $this->nodeContents();
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled content XML tag $tag" );
				$skip = true;
			}
		}

		return $contentInfo;
	}

	/**
	 * @param PageIdentity $page
	 * @param int $revisionId
	 * @param array $contentInfo
	 *
	 * @return Content
	 */
	private function makeContent( PageIdentity $page, $revisionId, $contentInfo ) {
		$maxArticleSize = $this->config->get( MainConfigNames::MaxArticleSize );

		if ( !isset( $contentInfo['text'] ) ) {
			throw new InvalidArgumentException( 'Missing text field in import.' );
		}

		// Make sure revisions won't violate $wgMaxArticleSize, which could lead to
		// database errors and instability. Testing for revisions with only listed
		// content models, as other content models might use serialization formats
		// which aren't checked against $wgMaxArticleSize.
		if ( ( !isset( $contentInfo['model'] ) ||
				in_array( $contentInfo['model'], [
					'wikitext',
					'css',
					'json',
					'javascript',
					'text',
					''
				] ) ) &&
			strlen( $contentInfo['text'] ) > $maxArticleSize * 1024
		) {
			throw new RuntimeException( 'The text of ' .
				( $revisionId ?
					"the revision with ID $revisionId" :
					'a revision'
				) . " exceeds the maximum allowable size ({$maxArticleSize} KiB)" );
		}

		$role = $contentInfo['role'] ?? SlotRecord::MAIN;
		$model = $contentInfo['model'] ?? $this->slotRoleRegistry
			->getRoleHandler( $role )
			->getDefaultModel( $page );
		$handler = $this->contentHandlerFactory->getContentHandler( $model );

		$text = $handler->importTransform( $contentInfo['text'] );

		return $handler->unserializeContent( $text );
	}

	/**
	 * @param array $pageInfo
	 * @param array $revisionInfo
	 * @return mixed|false
	 */
	private function processRevision( $pageInfo, $revisionInfo ) {
		$revision = new WikiRevision();

		$revId = $revisionInfo['id'] ?? 0;
		if ( $revId ) {
			$revision->setID( $revisionInfo['id'] );
		}

		$title = $pageInfo['_title'];
		$revision->setTitle( $title );

		$content = $this->makeContent( $title, $revId, $revisionInfo );
		$revision->setContent( SlotRecord::MAIN, $content );

		foreach ( $revisionInfo['content'] ?? [] as $slotInfo ) {
			if ( !isset( $slotInfo['role'] ) ) {
				throw new RuntimeException( "Missing role for imported slot." );
			}

			$content = $this->makeContent( $title, $revId, $slotInfo );
			$revision->setContent( $slotInfo['role'], $content );
		}
		$revision->setTimestamp( $revisionInfo['timestamp'] ?? wfTimestampNow() );

		if ( isset( $revisionInfo['comment'] ) ) {
			$revision->setComment( $revisionInfo['comment'] );
		}

		if ( isset( $revisionInfo['minor'] ) ) {
			$revision->setMinor( true );
		}
		if ( isset( $revisionInfo['contributor']['username'] ) ) {
			$revision->setUsername(
				$this->externalUserNames->applyPrefix( $revisionInfo['contributor']['username'] )
			);
		} elseif ( isset( $revisionInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $revisionInfo['contributor']['ip'] );
		} else {
			$revision->setUsername( $this->externalUserNames->addPrefix( 'Unknown user' ) );
		}
		if ( isset( $revisionInfo['sha1'] ) ) {
			$revision->setSha1Base36( $revisionInfo['sha1'] );
		}
		$revision->setNoUpdates( $this->mNoUpdates );

		return $this->revisionCallback( $revision );
	}

	/**
	 * @param array &$pageInfo
	 * @return mixed
	 */
	private function handleUpload( &$pageInfo ) {
		$this->debug( "Enter upload handler" );
		$uploadInfo = [];

		$normalFields = [ 'timestamp', 'comment', 'filename', 'text',
			'src', 'size', 'sha1base36', 'archivename', 'rel' ];

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'upload' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( !$this->hookRunner->onImportHandleUploadXMLTag( $this, $pageInfo ) ) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$uploadInfo[$tag] = $this->nodeContents();
			} elseif ( $tag == 'contributor' ) {
				$uploadInfo['contributor'] = $this->handleContributor();
			} elseif ( $tag == 'contents' ) {
				$contents = $this->nodeContents();
				$encoding = $this->reader->getAttribute( 'encoding' );
				if ( $encoding === 'base64' ) {
					$uploadInfo['fileSrc'] = $this->dumpTemp( base64_decode( $contents ) );
					$uploadInfo['isTempSrc'] = true;
				}
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled upload XML tag $tag" );
				$skip = true;
			}
		}

		if ( $this->mImageBasePath && isset( $uploadInfo['rel'] ) ) {
			$path = "{$this->mImageBasePath}/{$uploadInfo['rel']}";
			if ( file_exists( $path ) ) {
				$uploadInfo['fileSrc'] = $path;
				$uploadInfo['isTempSrc'] = false;
			}
		}

		if ( $this->mImportUploads ) {
			return $this->processUpload( $pageInfo, $uploadInfo );
		}
	}

	/**
	 * @param string $contents
	 * @return string
	 */
	private function dumpTemp( $contents ) {
		$filename = tempnam( wfTempDir(), 'importupload' );
		file_put_contents( $filename, $contents );
		return $filename;
	}

	/**
	 * @param array $pageInfo
	 * @param array $uploadInfo
	 * @return mixed
	 */
	private function processUpload( $pageInfo, $uploadInfo ) {
		$revision = new WikiRevision();
		$revId = $pageInfo['id'];
		$title = $pageInfo['_title'];
		// T292348: text key may be absent, force addition if null
		$uploadInfo['text'] ??= '';
		$content = $this->makeContent( $title, $revId, $uploadInfo );

		$revision->setTitle( $title );
		$revision->setID( $revId );
		$revision->setTimestamp( $uploadInfo['timestamp'] );
		$revision->setContent( SlotRecord::MAIN, $content );
		$revision->setFilename( $uploadInfo['filename'] );
		if ( isset( $uploadInfo['archivename'] ) ) {
			$revision->setArchiveName( $uploadInfo['archivename'] );
		}
		$revision->setSrc( $uploadInfo['src'] );
		if ( isset( $uploadInfo['fileSrc'] ) ) {
			$revision->setFileSrc( $uploadInfo['fileSrc'],
				!empty( $uploadInfo['isTempSrc'] )
			);
		}
		if ( isset( $uploadInfo['sha1base36'] ) ) {
			$revision->setSha1Base36( $uploadInfo['sha1base36'] );
		}
		$revision->setSize( intval( $uploadInfo['size'] ) );
		$revision->setComment( $uploadInfo['comment'] );

		if ( isset( $uploadInfo['contributor']['username'] ) ) {
			$revision->setUsername(
				$this->externalUserNames->applyPrefix( $uploadInfo['contributor']['username'] )
			);
		} elseif ( isset( $uploadInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $uploadInfo['contributor']['ip'] );
		}
		$revision->setNoUpdates( $this->mNoUpdates );

		return ( $this->mUploadCallback )( $revision );
	}

	/**
	 * @return array
	 */
	private function handleContributor() {
		$this->debug( "Enter contributor handler." );

		if ( $this->reader->isEmptyElement ) {
			return [];
		}

		$fields = [ 'id', 'ip', 'username' ];
		$info = [];

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'contributor' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( in_array( $tag, $fields ) ) {
				$info[$tag] = $this->nodeContents();
			}
		}

		return $info;
	}

	/**
	 * @param string $text
	 * @param string|null $ns
	 * @return array|false
	 */
	private function processTitle( $text, $ns = null ) {
		if ( $this->foreignNamespaces === null ) {
			$foreignTitleFactory = new NaiveForeignTitleFactory(
				$this->contentLanguage
			);
		} else {
			$foreignTitleFactory = new NamespaceAwareForeignTitleFactory(
				$this->foreignNamespaces );
		}

		$foreignTitle = $foreignTitleFactory->createForeignTitle( $text,
			intval( $ns ) );

		$title = $this->importTitleFactory->createTitleFromForeignTitle(
			$foreignTitle );

		if ( $title === null ) {
			# Invalid page title? Ignore the page
			$this->notice( 'import-error-invalid', $foreignTitle->getFullText() );
			return false;
		} elseif ( $title->isExternal() ) {
			$this->notice( 'import-error-interwiki', $title->getPrefixedText() );
			return false;
		} elseif ( !$title->canExist() ) {
			$this->notice( 'import-error-special', $title->getPrefixedText() );
			return false;
		} elseif ( !$this->performer->definitelyCan( 'edit', $title ) ) {
			# Do not import if the importing wiki user cannot edit this page
			$this->notice( 'import-error-edit', $title->getPrefixedText() );
			return false;
		}

		return [ $title, $foreignTitle ];
	}

	/**
	 * Open the XMLReader connected to the source adapter id
	 */
	private function openReader() {
		// Enable the entity loader, as it is needed for loading external URLs via
		// XMLReader::open (T86036)
		// phpcs:ignore Generic.PHP.NoSilencedErrors -- suppress deprecation per T268847
		$oldDisable = @libxml_disable_entity_loader( false );

		// A static call, to avoid https://github.com/php/php-src/issues/11548
		$reader = XMLReader::open(
			'uploadsource://' . $this->sourceAdapterId, null, LIBXML_PARSEHUGE );
		if ( $reader instanceof XMLReader ) {
			$this->reader = $reader;
			$status = true;
		} else {
			$status = false;
		}
		if ( !$status ) {
			$error = libxml_get_last_error();
			// phpcs:ignore Generic.PHP.NoSilencedErrors
			@libxml_disable_entity_loader( $oldDisable );
			throw new RuntimeException(
				'Encountered an internal error while initializing WikiImporter object: ' . $error->message
			);
		}
		// phpcs:ignore Generic.PHP.NoSilencedErrors
		@libxml_disable_entity_loader( $oldDisable );
	}

	/**
	 * Check the syntax of the given xml
	 */
	private function syntaxCheckXML() {
		if ( !UploadSourceAdapter::isSeekableSource( $this->sourceAdapterId ) ) {
			return;
		}
		AtEase::suppressWarnings();
		$oldDisable = libxml_disable_entity_loader( false );
		try {
			while ( $this->reader->read() );
			$error = libxml_get_last_error();
			if ( $error ) {
				$errorMessage = 'XML error at line ' . $error->line . ': ' . $error->message;
				wfDebug( __METHOD__ . ': Invalid xml found - ' . $errorMessage );
				throw new RuntimeException( $errorMessage );
			}
		} finally {
			libxml_disable_entity_loader( $oldDisable );
			AtEase::restoreWarnings();
			$this->reader->close();
		}

		// Reopen for the real import
		UploadSourceAdapter::seekSource( $this->sourceAdapterId, 0 );
		$this->openReader();
	}
}
