<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brion Vibber <brion@pobox.com>
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

use MediaWiki\MediaWikiServices;

/**
 * XML file reader for the page data importer.
 *
 * implements Special:Import
 * @ingroup SpecialPage
 */
class WikiImporter {
	/** @var XMLReader */
	private $reader;
	private $foreignNamespaces = null;
	private $mLogItemCallback, $mUploadCallback, $mRevisionCallback, $mPageCallback;
	private $mSiteInfoCallback, $mPageOutCallback;
	private $mNoticeCallback, $mDebug;
	private $mImportUploads, $mImageBasePath;
	private $mNoUpdates = false;
	private $pageOffset = 0;
	/** @var Config */
	private $config;
	/** @var ImportTitleFactory */
	private $importTitleFactory;
	/** @var array */
	private $countableCache = [];
	/** @var bool */
	private $disableStatisticsUpdate = false;
	/** @var ExternalUserNames */
	private $externalUserNames;

	/**
	 * Creates an ImportXMLReader drawing from the source provided
	 * @param ImportSource $source
	 * @param Config $config
	 * @throws Exception
	 */
	function __construct( ImportSource $source, Config $config ) {
		if ( !class_exists( 'XMLReader' ) ) {
			throw new Exception( 'Import requires PHP to have been compiled with libxml support' );
		}

		$this->reader = new XMLReader();
		$this->config = $config;

		if ( !in_array( 'uploadsource', stream_get_wrappers() ) ) {
			stream_wrapper_register( 'uploadsource', UploadSourceAdapter::class );
		}
		$id = UploadSourceAdapter::registerSource( $source );

		// Enable the entity loader, as it is needed for loading external URLs via
		// XMLReader::open (T86036)
		$oldDisable = libxml_disable_entity_loader( false );
		if ( defined( 'LIBXML_PARSEHUGE' ) ) {
			$status = $this->reader->open( "uploadsource://$id", null, LIBXML_PARSEHUGE );
		} else {
			$status = $this->reader->open( "uploadsource://$id" );
		}
		if ( !$status ) {
			$error = libxml_get_last_error();
			libxml_disable_entity_loader( $oldDisable );
			throw new MWException( 'Encountered an internal error while initializing WikiImporter object: ' .
				$error->message );
		}
		libxml_disable_entity_loader( $oldDisable );

		// Default callbacks
		$this->setPageCallback( [ $this, 'beforeImportPage' ] );
		$this->setRevisionCallback( [ $this, "importRevision" ] );
		$this->setUploadCallback( [ $this, 'importUpload' ] );
		$this->setLogItemCallback( [ $this, 'importLogItem' ] );
		$this->setPageOutCallback( [ $this, 'finishImportPage' ] );

		$this->importTitleFactory = new NaiveImportTitleFactory();
		$this->externalUserNames = new ExternalUserNames( 'imported', false );
	}

	/**
	 * @return null|XMLReader
	 */
	public function getReader() {
		return $this->reader;
	}

	public function throwXmlError( $err ) {
		$this->debug( "FAILURE: $err" );
		wfDebug( "WikiImporter XML error: $err\n" );
	}

	public function debug( $data ) {
		if ( $this->mDebug ) {
			wfDebug( "IMPORT: $data\n" );
		}
	}

	public function warn( $data ) {
		wfDebug( "IMPORT: $data\n" );
	}

	public function notice( $msg, ...$params ) {
		if ( is_callable( $this->mNoticeCallback ) ) {
			call_user_func( $this->mNoticeCallback, $msg, $params );
		} else { # No ImportReporter -> CLI
			// T177997: the command line importers should call setNoticeCallback()
			// for their own custom callback to echo the notice
			wfDebug( wfMessage( $msg, $params )->text() . "\n" );
		}
	}

	/**
	 * Set debug mode...
	 * @param bool $debug
	 */
	function setDebug( $debug ) {
		$this->mDebug = $debug;
	}

	/**
	 * Set 'no updates' mode. In this mode, the link tables will not be updated by the importer
	 * @param bool $noupdates
	 */
	function setNoUpdates( $noupdates ) {
		$this->mNoUpdates = $noupdates;
	}

	/**
	 * Sets 'pageOffset' value. So it will skip the first n-1 pages
	 * and start from the nth page. It's 1-based indexing.
	 * @param int $nthPage
	 * @since 1.29
	 */
	function setPageOffset( $nthPage ) {
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
	 * @param callable $callback
	 * @return callable
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
	 * @param callable $callback
	 * @return callable
	 */
	public function setPageOutCallback( $callback ) {
		$previous = $this->mPageOutCallback;
		$this->mPageOutCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each page revision is reached.
	 * @param callable $callback
	 * @return callable
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
		if ( is_null( $namespace ) ) {
			// Don't override namespaces
			$this->setImportTitleFactory( new NaiveImportTitleFactory() );
			return true;
		} elseif (
			$namespace >= 0 &&
			MediaWikiServices::getInstance()->getNamespaceInfo()->exists( intval( $namespace ) )
		) {
			$namespace = intval( $namespace );
			$this->setImportTitleFactory( new NamespaceImportTitleFactory( $namespace ) );
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
		if ( is_null( $rootpage ) ) {
			// No rootpage
			$this->setImportTitleFactory( new NaiveImportTitleFactory() );
		} elseif ( $rootpage !== '' ) {
			$rootpage = rtrim( $rootpage, '/' ); // avoid double slashes
			$title = Title::newFromText( $rootpage );

			if ( !$title || $title->isExternal() ) {
				$status->fatal( 'import-rootpage-invalid' );
			} elseif (
				!MediaWikiServices::getInstance()->getNamespaceInfo()->
				hasSubpages( $title->getNamespace() )
			) {
				$displayNSText = $title->getNamespace() == NS_MAIN
					? wfMessage( 'blanknamespace' )->text()
					: MediaWikiServices::getInstance()->getContentLanguage()->
						getNsText( $title->getNamespace() );
				$status->fatal( 'import-rootpage-nosubpage', $displayNSText );
			} else {
				// set namespace to 'all', so the namespace check in processTitle() can pass
				$this->setTargetNamespace( null );
				$this->setImportTitleFactory( new SubpageImportTitleFactory( $title ) );
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
		$page = WikiPage::factory( $title );
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
				$revision->getFormat() );

			return false;
		}

		try {
			return $revision->importOldRevision();
		} catch ( MWContentSerializationException $ex ) {
			$this->notice( 'import-error-unserialize',
				$revision->getTitle()->getPrefixedText(),
				$revision->getID(),
				$revision->getModel(),
				$revision->getFormat() );
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
		return $revision->importUpload();
	}

	/**
	 * Mostly for hook use
	 * @param Title $title
	 * @param ForeignTitle $foreignTitle
	 * @param int $revCount
	 * @param int $sRevCount
	 * @param array $pageInfo
	 * @return bool
	 */
	public function finishImportPage( $title, $foreignTitle, $revCount,
		$sRevCount, $pageInfo
	) {
		// Update article count statistics (T42009)
		// The normal counting logic in WikiPage->doEditUpdates() is designed for
		// one-revision-at-a-time editing, not bulk imports. In this situation it
		// suffers from issues of replica DB lag. We let WikiPage handle the total page
		// and revision count, and we implement our own custom logic for the
		// article (content page) count.
		if ( !$this->disableStatisticsUpdate ) {
			$page = WikiPage::factory( $title );
			$page->loadPageData( 'fromdbmaster' );
			$content = $page->getContent();
			if ( $content === null ) {
				wfDebug( __METHOD__ . ': Skipping article count adjustment for ' . $title .
					' because WikiPage::getContent() returned null' );
			} else {
				$editInfo = $page->prepareContentForEdit( $content );
				$countKey = 'title_' . $title->getPrefixedText();
				$countable = $page->isCountable( $editInfo );
				if ( array_key_exists( $countKey, $this->countableCache ) &&
					$countable != $this->countableCache[$countKey] ) {
					DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [
						'articles' => ( (int)$countable - (int)$this->countableCache[$countKey] )
					] ) );
				}
			}
		}

		return Hooks::run( 'AfterImportPage', func_get_args() );
	}

	/**
	 * Alternate per-revision callback, for debugging.
	 * @param WikiRevision &$revision
	 */
	public function debugRevisionHandler( &$revision ) {
		$this->debug( "Got revision:" );
		if ( is_object( $revision->title ) ) {
			$this->debug( "-- Title: " . $revision->title->getPrefixedText() );
		} else {
			$this->debug( "-- Title: <invalid>" );
		}
		$this->debug( "-- User: " . $revision->user_text );
		$this->debug( "-- Timestamp: " . $revision->timestamp );
		$this->debug( "-- Comment: " . $revision->comment );
		$this->debug( "-- Text: " . $revision->text );
	}

	/**
	 * Notify the callback function of site info
	 * @param array $siteInfo
	 * @return bool|mixed
	 */
	private function siteInfoCallback( $siteInfo ) {
		if ( isset( $this->mSiteInfoCallback ) ) {
			return call_user_func_array( $this->mSiteInfoCallback,
					[ $siteInfo, $this ] );
		} else {
			return false;
		}
	}

	/**
	 * Notify the callback function when a new "<page>" is reached.
	 * @param array $title
	 */
	function pageCallback( $title ) {
		if ( isset( $this->mPageCallback ) ) {
			call_user_func( $this->mPageCallback, $title );
		}
	}

	/**
	 * Notify the callback function when a "</page>" is closed.
	 * @param Title $title
	 * @param ForeignTitle $foreignTitle
	 * @param int $revCount
	 * @param int $sucCount Number of revisions for which callback returned true
	 * @param array $pageInfo Associative array of page information
	 */
	private function pageOutCallback( $title, $foreignTitle, $revCount,
			$sucCount, $pageInfo ) {
		if ( isset( $this->mPageOutCallback ) ) {
			call_user_func_array( $this->mPageOutCallback, func_get_args() );
		}
	}

	/**
	 * Notify the callback function of a revision
	 * @param WikiRevision $revision
	 * @return bool|mixed
	 */
	private function revisionCallback( $revision ) {
		if ( isset( $this->mRevisionCallback ) ) {
			return call_user_func_array( $this->mRevisionCallback,
					[ $revision, $this ] );
		} else {
			return false;
		}
	}

	/**
	 * Notify the callback function of a new log item
	 * @param WikiRevision $revision
	 * @return bool|mixed
	 */
	private function logItemCallback( $revision ) {
		if ( isset( $this->mLogItemCallback ) ) {
			return call_user_func_array( $this->mLogItemCallback,
					[ $revision, $this ] );
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
		return $this->reader->getAttribute( $attr );
	}

	/**
	 * Shouldn't something like this be built-in to XMLReader?
	 * Fetches text contents of the current element, assuming
	 * no sub-elements or such scary things.
	 * @return string
	 * @private
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
	 * @throws MWException
	 * @return bool
	 */
	public function doImport() {
		// Calls to reader->read need to be wrapped in calls to
		// libxml_disable_entity_loader() to avoid local file
		// inclusion attacks (T48932).
		$oldDisable = libxml_disable_entity_loader( true );
		$this->reader->read();

		if ( $this->reader->localName != 'mediawiki' ) {
			libxml_disable_entity_loader( $oldDisable );
			throw new MWException( "Expected <mediawiki> tag, got " .
				$this->reader->localName );
		}
		$this->debug( "<mediawiki> tag is correct." );

		$this->debug( "Starting primary dump processing loop." );

		$keepReading = $this->reader->read();
		$skip = false;
		$rethrow = null;
		$pageCount = 0;
		try {
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

				if ( !Hooks::run( 'ImportHandleToplevelXMLTag', [ $this ] ) ) {
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
		} catch ( Exception $ex ) {
			$rethrow = $ex;
		}

		// finally
		libxml_disable_entity_loader( $oldDisable );
		$this->reader->close();

		if ( $rethrow ) {
			throw $rethrow;
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

			if ( !Hooks::run( 'ImportHandleLogItemXMLTag', [
				$this, $logInfo
			] ) ) {
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
	 * @return bool|mixed
	 */
	private function processLogItem( $logInfo ) {
		$revision = new WikiRevision( $this->config );

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

		if ( isset( $logInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $logInfo['contributor']['ip'] );
		}

		if ( !isset( $logInfo['contributor']['username'] ) ) {
			$revision->setUsername( $this->externalUserNames->addPrefix( 'Unknown user' ) );
		} else {
			$revision->setUsername(
				$this->externalUserNames->applyPrefix( $logInfo['contributor']['username'] )
			);
		}

		return $this->logItemCallback( $revision );
	}

	/**
	 * @suppress PhanTypeInvalidDimOffset Phan not reading the reference inside the hook
	 */
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
			} elseif ( !Hooks::run( 'ImportHandlePageXMLTag', [ $this,
						&$pageInfo ] ) ) {
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
						list( $pageInfo['_title'], $foreignTitle ) = $title;
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
			$this->pageOutCallback( $pageInfo['_title'], $foreignTitle,
					$pageInfo['revisionCount'],
					$pageInfo['successfulRevisionCount'],
					$pageInfo );
		}
	}

	/**
	 * @param array $pageInfo
	 */
	private function handleRevision( &$pageInfo ) {
		$this->debug( "Enter revision handler" );
		$revisionInfo = [];

		$normalFields = [ 'id', 'timestamp', 'comment', 'minor', 'model', 'format', 'text', 'sha1' ];

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->localName == 'revision' ) {
				break;
			}

			$tag = $this->reader->localName;

			if ( !Hooks::run( 'ImportHandleRevisionXMLTag', [
				$this, $pageInfo, $revisionInfo
			] ) ) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$revisionInfo[$tag] = $this->nodeContents();
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

	/**
	 * @param array $pageInfo
	 * @param array $revisionInfo
	 * @throws MWException
	 * @return bool|mixed
	 */
	private function processRevision( $pageInfo, $revisionInfo ) {
		global $wgMaxArticleSize;

		// Make sure revisions won't violate $wgMaxArticleSize, which could lead to
		// database errors and instability. Testing for revisions with only listed
		// content models, as other content models might use serialization formats
		// which aren't checked against $wgMaxArticleSize.
		if ( ( !isset( $revisionInfo['model'] ) ||
			in_array( $revisionInfo['model'], [
				'wikitext',
				'css',
				'json',
				'javascript',
				'text',
				''
			] ) ) &&
			strlen( $revisionInfo['text'] ) > $wgMaxArticleSize * 1024
		) {
			throw new MWException( 'The text of ' .
				( isset( $revisionInfo['id'] ) ?
					"the revision with ID $revisionInfo[id]" :
					'a revision'
				) . " exceeds the maximum allowable size ($wgMaxArticleSize KB)" );
		}

		// FIXME: process schema version 11!
		$revision = new WikiRevision( $this->config );

		if ( isset( $revisionInfo['id'] ) ) {
			$revision->setID( $revisionInfo['id'] );
		}
		if ( isset( $revisionInfo['model'] ) ) {
			$revision->setModel( $revisionInfo['model'] );
		}
		if ( isset( $revisionInfo['format'] ) ) {
			$revision->setFormat( $revisionInfo['format'] );
		}
		$revision->setTitle( $pageInfo['_title'] );

		if ( isset( $revisionInfo['text'] ) ) {
			$handler = $revision->getContentHandler();
			$text = $handler->importTransform(
				$revisionInfo['text'],
				$revision->getFormat() );

			$revision->setText( $text );
		}
		$revision->setTimestamp( $revisionInfo['timestamp'] ?? wfTimestampNow() );

		if ( isset( $revisionInfo['comment'] ) ) {
			$revision->setComment( $revisionInfo['comment'] );
		}

		if ( isset( $revisionInfo['minor'] ) ) {
			$revision->setMinor( true );
		}
		if ( isset( $revisionInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $revisionInfo['contributor']['ip'] );
		} elseif ( isset( $revisionInfo['contributor']['username'] ) ) {
			$revision->setUsername(
				$this->externalUserNames->applyPrefix( $revisionInfo['contributor']['username'] )
			);
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
	 * @param array $pageInfo
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

			if ( !Hooks::run( 'ImportHandleUploadXMLTag', [
				$this, $pageInfo
			] ) ) {
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
		$revision = new WikiRevision( $this->config );
		$text = $uploadInfo['text'] ?? '';

		$revision->setTitle( $pageInfo['_title'] );
		$revision->setID( $pageInfo['id'] );
		$revision->setTimestamp( $uploadInfo['timestamp'] );
		$revision->setText( $text );
		$revision->setFilename( $uploadInfo['filename'] );
		if ( isset( $uploadInfo['archivename'] ) ) {
			$revision->setArchiveName( $uploadInfo['archivename'] );
		}
		$revision->setSrc( $uploadInfo['src'] );
		if ( isset( $uploadInfo['fileSrc'] ) ) {
			$revision->setFileSrc( $uploadInfo['fileSrc'],
				!empty( $uploadInfo['isTempSrc'] ) );
		}
		if ( isset( $uploadInfo['sha1base36'] ) ) {
			$revision->setSha1Base36( $uploadInfo['sha1base36'] );
		}
		$revision->setSize( intval( $uploadInfo['size'] ) );
		$revision->setComment( $uploadInfo['comment'] );

		if ( isset( $uploadInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $uploadInfo['contributor']['ip'] );
		}
		if ( isset( $uploadInfo['contributor']['username'] ) ) {
			$revision->setUsername(
				$this->externalUserNames->applyPrefix( $uploadInfo['contributor']['username'] )
			);
		}
		$revision->setNoUpdates( $this->mNoUpdates );

		return call_user_func( $this->mUploadCallback, $revision );
	}

	/**
	 * @return array
	 */
	private function handleContributor() {
		$fields = [ 'id', 'ip', 'username' ];
		$info = [];

		if ( $this->reader->isEmptyElement ) {
			return $info;
		}
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
	 * @return array|bool
	 */
	private function processTitle( $text, $ns = null ) {
		if ( is_null( $this->foreignNamespaces ) ) {
			$foreignTitleFactory = new NaiveForeignTitleFactory();
		} else {
			$foreignTitleFactory = new NamespaceAwareForeignTitleFactory(
				$this->foreignNamespaces );
		}

		$foreignTitle = $foreignTitleFactory->createForeignTitle( $text,
			intval( $ns ) );

		$title = $this->importTitleFactory->createTitleFromForeignTitle(
			$foreignTitle );

		$commandLineMode = $this->config->get( 'CommandLineMode' );
		if ( is_null( $title ) ) {
			# Invalid page title? Ignore the page
			$this->notice( 'import-error-invalid', $foreignTitle->getFullText() );
			return false;
		} elseif ( $title->isExternal() ) {
			$this->notice( 'import-error-interwiki', $title->getPrefixedText() );
			return false;
		} elseif ( !$title->canExist() ) {
			$this->notice( 'import-error-special', $title->getPrefixedText() );
			return false;
		} elseif ( !$commandLineMode ) {
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			$user = RequestContext::getMain()->getUser();

			if ( !$permissionManager->userCan( 'edit', $user, $title ) ) {
				# Do not import if the importing wiki user cannot edit this page
				$this->notice( 'import-error-edit', $title->getPrefixedText() );

				return false;
			}

			if ( !$title->exists() && !$permissionManager->userCan( 'create', $user, $title ) ) {
				# Do not import if the importing wiki user cannot create this page
				$this->notice( 'import-error-create', $title->getPrefixedText() );

				return false;
			}
		}

		return [ $title, $foreignTitle ];
	}
}
