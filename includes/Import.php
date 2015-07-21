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

/**
 * XML file reader for the page data importer
 *
 * implements Special:Import
 * @ingroup SpecialPage
 */
class WikiImporter {
	private $reader = null;
	private $foreignNamespaces = null;
	private $mLogItemCallback, $mUploadCallback, $mRevisionCallback, $mPageCallback;
	private $mSiteInfoCallback, $mTargetNamespace, $mPageOutCallback;
	private $mNoticeCallback, $mDebug;
	private $mImportUploads, $mImageBasePath;
	private $mNoUpdates = false;
	/** @var Config */
	private $config;
	/** @var ImportTitleFactory */
	private $importTitleFactory;
	/** @var array */
	private $countableCache = array();

	/**
	 * Creates an ImportXMLReader drawing from the source provided
	 * @param ImportSource $source
	 * @param Config $config
	 */
	function __construct( ImportSource $source, Config $config = null ) {
		$this->reader = new XMLReader();
		if ( !$config ) {
			wfDeprecated( __METHOD__ . ' without a Config instance', '1.25' );
			$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}
		$this->config = $config;

		if ( !in_array( 'uploadsource', stream_get_wrappers() ) ) {
			stream_wrapper_register( 'uploadsource', 'UploadSourceAdapter' );
		}
		$id = UploadSourceAdapter::registerSource( $source );
		if ( defined( 'LIBXML_PARSEHUGE' ) ) {
			$this->reader->open( "uploadsource://$id", null, LIBXML_PARSEHUGE );
		} else {
			$this->reader->open( "uploadsource://$id" );
		}

		// Default callbacks
		$this->setPageCallback( array( $this, 'beforeImportPage' ) );
		$this->setRevisionCallback( array( $this, "importRevision" ) );
		$this->setUploadCallback( array( $this, 'importUpload' ) );
		$this->setLogItemCallback( array( $this, 'importLogItem' ) );
		$this->setPageOutCallback( array( $this, 'finishImportPage' ) );

		$this->importTitleFactory = new NaiveImportTitleFactory();
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

	public function notice( $msg /*, $param, ...*/ ) {
		$params = func_get_args();
		array_shift( $params );

		if ( is_callable( $this->mNoticeCallback ) ) {
			call_user_func( $this->mNoticeCallback, $msg, $params );
		} else { # No ImportReporter -> CLI
			echo wfMessage( $msg, $params )->text() . "\n";
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
			$this->mTargetNamespace = null;
			$this->setImportTitleFactory( new NaiveImportTitleFactory() );
			return true;
		} elseif (
			$namespace >= 0 &&
			MWNamespace::exists( intval( $namespace ) )
		) {
			$namespace = intval( $namespace );
			$this->mTargetNamespace = $namespace;
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
			$rootpage = rtrim( $rootpage, '/' ); //avoid double slashes
			$title = Title::newFromText( $rootpage, !is_null( $this->mTargetNamespace )
				? $this->mTargetNamespace
				: NS_MAIN
			);

			if ( !$title || $title->isExternal() ) {
				$status->fatal( 'import-rootpage-invalid' );
			} else {
				if ( !MWNamespace::hasSubpages( $title->getNamespace() ) ) {
					global $wgContLang;

					$displayNSText = $title->getNamespace() == NS_MAIN
						? wfMessage( 'blanknamespace' )->text()
						: $wgContLang->getNsText( $title->getNamespace() );
					$status->fatal( 'import-rootpage-nosubpage', $displayNSText );
				} else {
					// set namespace to 'all', so the namespace check in processTitle() can pass
					$this->setTargetNamespace( null );
					$this->setImportTitleFactory( new SubpageImportTitleFactory( $title ) );
				}
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
			$dbw = wfGetDB( DB_MASTER );
			return $dbw->deadlockLoop( array( $revision, 'importOldRevision' ) );
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
		$dbw = wfGetDB( DB_MASTER );
		return $dbw->deadlockLoop( array( $revision, 'importLogItem' ) );
	}

	/**
	 * Dummy for now...
	 * @param WikiRevision $revision
	 * @return bool
	 */
	public function importUpload( $revision ) {
		$dbw = wfGetDB( DB_MASTER );
		return $dbw->deadlockLoop( array( $revision, 'importUpload' ) );
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
			$sRevCount, $pageInfo ) {

		// Update article count statistics (T42009)
		// The normal counting logic in WikiPage->doEditUpdates() is designed for
		// one-revision-at-a-time editing, not bulk imports. In this situation it
		// suffers from issues of slave lag. We let WikiPage handle the total page
		// and revision count, and we implement our own custom logic for the
		// article (content page) count.
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
				$countable != $this->countableCache[ $countKey ] ) {
				DeferredUpdates::addUpdate( SiteStatsUpdate::factory( array(
					'articles' => ( (int)$countable - (int)$this->countableCache[ $countKey ] )
				) ) );
			}
		}

		$args = func_get_args();
		return Hooks::run( 'AfterImportPage', $args );
	}

	/**
	 * Alternate per-revision callback, for debugging.
	 * @param WikiRevision $revision
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
					array( $siteInfo, $this ) );
		} else {
			return false;
		}
	}

	/**
	 * Notify the callback function when a new "<page>" is reached.
	 * @param Title $title
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
			$args = func_get_args();
			call_user_func_array( $this->mPageOutCallback, $args );
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
					array( $revision, $this ) );
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
					array( $revision, $this ) );
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
	 * @access private
	 */
	public function nodeContents() {
		if ( $this->reader->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while ( $this->reader->read() ) {
			switch ( $this->reader->nodeType ) {
			case XMLReader::TEXT:
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
	 * @throws MWException
	 * @return bool
	 */
	public function doImport() {
		// Calls to reader->read need to be wrapped in calls to
		// libxml_disable_entity_loader() to avoid local file
		// inclusion attacks (bug 46932).
		$oldDisable = libxml_disable_entity_loader( true );
		$this->reader->read();

		if ( $this->reader->name != 'mediawiki' ) {
			libxml_disable_entity_loader( $oldDisable );
			throw new MWException( "Expected <mediawiki> tag, got " .
				$this->reader->name );
		}
		$this->debug( "<mediawiki> tag is correct." );

		$this->debug( "Starting primary dump processing loop." );

		$keepReading = $this->reader->read();
		$skip = false;
		$rethrow = null;
		try {
			while ( $keepReading ) {
				$tag = $this->reader->name;
				$type = $this->reader->nodeType;

				if ( !Hooks::run( 'ImportHandleToplevelXMLTag', array( $this ) ) ) {
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
		$siteInfo = array();

		// Fields that can just be stuffed in the siteInfo object
		$normalFields = array( 'sitename', 'base', 'generator', 'case' );

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XmlReader::END_ELEMENT &&
					$this->reader->name == 'siteinfo' ) {
				break;
			}

			$tag = $this->reader->name;

			if ( $tag == 'namespace' ) {
				$this->foreignNamespaces[ $this->nodeAttribute( 'key' ) ] =
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
		$logInfo = array();

		// Fields that can just be stuffed in the pageInfo object
		$normalFields = array( 'id', 'comment', 'type', 'action', 'timestamp',
					'logtitle', 'params' );

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->name == 'logitem' ) {
				break;
			}

			$tag = $this->reader->name;

			if ( !Hooks::run( 'ImportHandleLogItemXMLTag', array(
				$this, $logInfo
			) ) ) {
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

		$revision->setID( $logInfo['id'] );
		$revision->setType( $logInfo['type'] );
		$revision->setAction( $logInfo['action'] );
		$revision->setTimestamp( $logInfo['timestamp'] );
		$revision->setParams( $logInfo['params'] );
		$revision->setTitle( Title::newFromText( $logInfo['logtitle'] ) );
		$revision->setNoUpdates( $this->mNoUpdates );

		if ( isset( $logInfo['comment'] ) ) {
			$revision->setComment( $logInfo['comment'] );
		}

		if ( isset( $logInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $logInfo['contributor']['ip'] );
		}
		if ( isset( $logInfo['contributor']['username'] ) ) {
			$revision->setUserName( $logInfo['contributor']['username'] );
		}

		return $this->logItemCallback( $revision );
	}

	private function handlePage() {
		// Handle page data.
		$this->debug( "Enter page handler." );
		$pageInfo = array( 'revisionCount' => 0, 'successfulRevisionCount' => 0 );

		// Fields that can just be stuffed in the pageInfo object
		$normalFields = array( 'title', 'ns', 'id', 'redirect', 'restrictions' );

		$skip = false;
		$badTitle = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->name == 'page' ) {
				break;
			}

			$skip = false;

			$tag = $this->reader->name;

			if ( $badTitle ) {
				// The title is invalid, bail out of this page
				$skip = true;
			} elseif ( !Hooks::run( 'ImportHandlePageXMLTag', array( $this,
						&$pageInfo ) ) ) {
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
						isset( $pageInfo['ns'] ) ? $pageInfo['ns'] : null );

					if ( !$title ) {
						$badTitle = true;
						$skip = true;
					}

					$this->pageCallback( $title );
					list( $pageInfo['_title'], $foreignTitle ) = $title;
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

		$this->pageOutCallback( $pageInfo['_title'], $foreignTitle,
					$pageInfo['revisionCount'],
					$pageInfo['successfulRevisionCount'],
					$pageInfo );
	}

	/**
	 * @param array $pageInfo
	 */
	private function handleRevision( &$pageInfo ) {
		$this->debug( "Enter revision handler" );
		$revisionInfo = array();

		$normalFields = array( 'id', 'timestamp', 'comment', 'minor', 'model', 'format', 'text' );

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->name == 'revision' ) {
				break;
			}

			$tag = $this->reader->name;

			if ( !Hooks::run( 'ImportHandleRevisionXMLTag', array(
				$this, $pageInfo, $revisionInfo
			) ) ) {
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
	 * @return bool|mixed
	 */
	private function processRevision( $pageInfo, $revisionInfo ) {
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
		if ( isset( $revisionInfo['timestamp'] ) ) {
			$revision->setTimestamp( $revisionInfo['timestamp'] );
		} else {
			$revision->setTimestamp( wfTimestampNow() );
		}

		if ( isset( $revisionInfo['comment'] ) ) {
			$revision->setComment( $revisionInfo['comment'] );
		}

		if ( isset( $revisionInfo['minor'] ) ) {
			$revision->setMinor( true );
		}
		if ( isset( $revisionInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $revisionInfo['contributor']['ip'] );
		}
		if ( isset( $revisionInfo['contributor']['username'] ) ) {
			$revision->setUserName( $revisionInfo['contributor']['username'] );
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
		$uploadInfo = array();

		$normalFields = array( 'timestamp', 'comment', 'filename', 'text',
					'src', 'size', 'sha1base36', 'archivename', 'rel' );

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->name == 'upload' ) {
				break;
			}

			$tag = $this->reader->name;

			if ( !Hooks::run( 'ImportHandleUploadXMLTag', array(
				$this, $pageInfo
			) ) ) {
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
		$text = isset( $uploadInfo['text'] ) ? $uploadInfo['text'] : '';

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
			$revision->setUserName( $uploadInfo['contributor']['username'] );
		}
		$revision->setNoUpdates( $this->mNoUpdates );

		return call_user_func( $this->mUploadCallback, $revision );
	}

	/**
	 * @return array
	 */
	private function handleContributor() {
		$fields = array( 'id', 'ip', 'username' );
		$info = array();

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
					$this->reader->name == 'contributor' ) {
				break;
			}

			$tag = $this->reader->name;

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
		} elseif ( !$title->userCan( 'edit' ) && !$commandLineMode ) {
			# Do not import if the importing wiki user cannot edit this page
			$this->notice( 'import-error-edit', $title->getPrefixedText() );
			return false;
		} elseif ( !$title->exists() && !$title->userCan( 'create' ) && !$commandLineMode ) {
			# Do not import if the importing wiki user cannot create this page
			$this->notice( 'import-error-create', $title->getPrefixedText() );
			return false;
		}

		return array( $title, $foreignTitle );
	}
}

/** This is a horrible hack used to keep source compatibility */
class UploadSourceAdapter {
	/** @var array */
	public static $sourceRegistrations = array();

	/** @var string */
	private $mSource;

	/** @var string */
	private $mBuffer;

	/** @var int */
	private $mPosition;

	/**
	 * @param ImportSource $source
	 * @return string
	 */
	static function registerSource( ImportSource $source ) {
		$id = wfRandomString();

		self::$sourceRegistrations[$id] = $source;

		return $id;
	}

	/**
	 * @param string $path
	 * @param string $mode
	 * @param array $options
	 * @param string $opened_path
	 * @return bool
	 */
	function stream_open( $path, $mode, $options, &$opened_path ) {
		$url = parse_url( $path );
		$id = $url['host'];

		if ( !isset( self::$sourceRegistrations[$id] ) ) {
			return false;
		}

		$this->mSource = self::$sourceRegistrations[$id];

		return true;
	}

	/**
	 * @param int $count
	 * @return string
	 */
	function stream_read( $count ) {
		$return = '';
		$leave = false;

		while ( !$leave && !$this->mSource->atEnd() &&
				strlen( $this->mBuffer ) < $count ) {
			$read = $this->mSource->readChunk();

			if ( !strlen( $read ) ) {
				$leave = true;
			}

			$this->mBuffer .= $read;
		}

		if ( strlen( $this->mBuffer ) ) {
			$return = substr( $this->mBuffer, 0, $count );
			$this->mBuffer = substr( $this->mBuffer, $count );
		}

		$this->mPosition += strlen( $return );

		return $return;
	}

	/**
	 * @param string $data
	 * @return bool
	 */
	function stream_write( $data ) {
		return false;
	}

	/**
	 * @return mixed
	 */
	function stream_tell() {
		return $this->mPosition;
	}

	/**
	 * @return bool
	 */
	function stream_eof() {
		return $this->mSource->atEnd();
	}

	/**
	 * @return array
	 */
	function url_stat() {
		$result = array();

		$result['dev'] = $result[0] = 0;
		$result['ino'] = $result[1] = 0;
		$result['mode'] = $result[2] = 0;
		$result['nlink'] = $result[3] = 0;
		$result['uid'] = $result[4] = 0;
		$result['gid'] = $result[5] = 0;
		$result['rdev'] = $result[6] = 0;
		$result['size'] = $result[7] = 0;
		$result['atime'] = $result[8] = 0;
		$result['mtime'] = $result[9] = 0;
		$result['ctime'] = $result[10] = 0;
		$result['blksize'] = $result[11] = 0;
		$result['blocks'] = $result[12] = 0;

		return $result;
	}
}

/**
 * @todo document (e.g. one-sentence class description).
 * @ingroup SpecialPage
 */
class WikiRevision {
	/** @todo Unused? */
	public $importer = null;

	/** @var Title */
	public $title = null;

	/** @var int */
	public $id = 0;

	/** @var string */
	public $timestamp = "20010115000000";

	/**
	 * @var int
	 * @todo Can't find any uses. Public, because that's suspicious. Get clarity. */
	public $user = 0;

	/** @var string */
	public $user_text = "";

	/** @var string */
	public $model = null;

	/** @var string */
	public $format = null;

	/** @var string */
	public $text = "";

	/** @var int */
	protected $size;

	/** @var Content */
	public $content = null;

	/** @var ContentHandler */
	protected $contentHandler = null;

	/** @var string */
	public $comment = "";

	/** @var bool */
	public $minor = false;

	/** @var string */
	public $type = "";

	/** @var string */
	public $action = "";

	/** @var string */
	public $params = "";

	/** @var string */
	public $fileSrc = '';

	/** @var bool|string */
	public $sha1base36 = false;

	/**
	 * @var bool
	 * @todo Unused?
	 */
	public $isTemp = false;

	/** @var string */
	public $archiveName = '';

	protected $filename;

	/** @var mixed */
	protected $src;

	/** @todo Unused? */
	public $fileIsTemp;

	/** @var bool */
	private $mNoUpdates = false;

	/** @var Config $config */
	private $config;

	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @param Title $title
	 * @throws MWException
	 */
	function setTitle( $title ) {
		if ( is_object( $title ) ) {
			$this->title = $title;
		} elseif ( is_null( $title ) ) {
			throw new MWException( "WikiRevision given a null title in import. "
				. "You may need to adjust \$wgLegalTitleChars." );
		} else {
			throw new MWException( "WikiRevision given non-object title in import." );
		}
	}

	/**
	 * @param int $id
	 */
	function setID( $id ) {
		$this->id = $id;
	}

	/**
	 * @param string $ts
	 */
	function setTimestamp( $ts ) {
		# 2003-08-05T18:30:02Z
		$this->timestamp = wfTimestamp( TS_MW, $ts );
	}

	/**
	 * @param string $user
	 */
	function setUsername( $user ) {
		$this->user_text = $user;
	}

	/**
	 * @param string $ip
	 */
	function setUserIP( $ip ) {
		$this->user_text = $ip;
	}

	/**
	 * @param string $model
	 */
	function setModel( $model ) {
		$this->model = $model;
	}

	/**
	 * @param string $format
	 */
	function setFormat( $format ) {
		$this->format = $format;
	}

	/**
	 * @param string $text
	 */
	function setText( $text ) {
		$this->text = $text;
	}

	/**
	 * @param string $text
	 */
	function setComment( $text ) {
		$this->comment = $text;
	}

	/**
	 * @param bool $minor
	 */
	function setMinor( $minor ) {
		$this->minor = (bool)$minor;
	}

	/**
	 * @param mixed $src
	 */
	function setSrc( $src ) {
		$this->src = $src;
	}

	/**
	 * @param string $src
	 * @param bool $isTemp
	 */
	function setFileSrc( $src, $isTemp ) {
		$this->fileSrc = $src;
		$this->fileIsTemp = $isTemp;
	}

	/**
	 * @param string $sha1base36
	 */
	function setSha1Base36( $sha1base36 ) {
		$this->sha1base36 = $sha1base36;
	}

	/**
	 * @param string $filename
	 */
	function setFilename( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * @param string $archiveName
	 */
	function setArchiveName( $archiveName ) {
		$this->archiveName = $archiveName;
	}

	/**
	 * @param int $size
	 */
	function setSize( $size ) {
		$this->size = intval( $size );
	}

	/**
	 * @param string $type
	 */
	function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * @param string $action
	 */
	function setAction( $action ) {
		$this->action = $action;
	}

	/**
	 * @param array $params
	 */
	function setParams( $params ) {
		$this->params = $params;
	}

	/**
	 * @param bool $noupdates
	 */
	public function setNoUpdates( $noupdates ) {
		$this->mNoUpdates = $noupdates;
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return $this->title;
	}

	/**
	 * @return int
	 */
	function getID() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @return string
	 */
	function getUser() {
		return $this->user_text;
	}

	/**
	 * @return string
	 *
	 * @deprecated Since 1.21, use getContent() instead.
	 */
	function getText() {
		ContentHandler::deprecated( __METHOD__, '1.21' );

		return $this->text;
	}

	/**
	 * @return ContentHandler
	 */
	function getContentHandler() {
		if ( is_null( $this->contentHandler ) ) {
			$this->contentHandler = ContentHandler::getForModelID( $this->getModel() );
		}

		return $this->contentHandler;
	}

	/**
	 * @return Content
	 */
	function getContent() {
		if ( is_null( $this->content ) ) {
			$handler = $this->getContentHandler();
			$this->content = $handler->unserializeContent( $this->text, $this->getFormat() );
		}

		return $this->content;
	}

	/**
	 * @return string
	 */
	function getModel() {
		if ( is_null( $this->model ) ) {
			$this->model = $this->getTitle()->getContentModel();
		}

		return $this->model;
	}

	/**
	 * @return string
	 */
	function getFormat() {
		if ( is_null( $this->format ) ) {
			$this->format = $this->getContentHandler()->getDefaultFormat();
		}

		return $this->format;
	}

	/**
	 * @return string
	 */
	function getComment() {
		return $this->comment;
	}

	/**
	 * @return bool
	 */
	function getMinor() {
		return $this->minor;
	}

	/**
	 * @return mixed
	 */
	function getSrc() {
		return $this->src;
	}

	/**
	 * @return bool|string
	 */
	function getSha1() {
		if ( $this->sha1base36 ) {
			return wfBaseConvert( $this->sha1base36, 36, 16 );
		}
		return false;
	}

	/**
	 * @return string
	 */
	function getFileSrc() {
		return $this->fileSrc;
	}

	/**
	 * @return bool
	 */
	function isTempSrc() {
		return $this->isTemp;
	}

	/**
	 * @return mixed
	 */
	function getFilename() {
		return $this->filename;
	}

	/**
	 * @return string
	 */
	function getArchiveName() {
		return $this->archiveName;
	}

	/**
	 * @return mixed
	 */
	function getSize() {
		return $this->size;
	}

	/**
	 * @return string
	 */
	function getType() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	function getAction() {
		return $this->action;
	}

	/**
	 * @return string
	 */
	function getParams() {
		return $this->params;
	}

	/**
	 * @return bool
	 */
	function importOldRevision() {
		$dbw = wfGetDB( DB_MASTER );

		# Sneak a single revision into place
		$user = User::newFromName( $this->getUser() );
		if ( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
			$userObj = $user;
		} else {
			$userId = 0;
			$userText = $this->getUser();
			$userObj = new User;
		}

		// avoid memory leak...?
		Title::clearCaches();

		$page = WikiPage::factory( $this->title );
		$page->loadPageData( 'fromdbmaster' );
		if ( !$page->exists() ) {
			# must create the page...
			$pageId = $page->insertOn( $dbw );
			$created = true;
			$oldcountable = null;
		} else {
			$pageId = $page->getId();
			$created = false;

			$prior = $dbw->selectField( 'revision', '1',
				array( 'rev_page' => $pageId,
					'rev_timestamp' => $dbw->timestamp( $this->timestamp ),
					'rev_user_text' => $userText,
					'rev_comment' => $this->getComment() ),
				__METHOD__
			);
			if ( $prior ) {
				// @todo FIXME: This could fail slightly for multiple matches :P
				wfDebug( __METHOD__ . ": skipping existing revision for [[" .
					$this->title->getPrefixedText() . "]], timestamp " . $this->timestamp . "\n" );
				return false;
			}
		}

		# @todo FIXME: Use original rev_id optionally (better for backups)
		# Insert the row
		$revision = new Revision( array(
			'title' => $this->title,
			'page' => $pageId,
			'content_model' => $this->getModel(),
			'content_format' => $this->getFormat(),
			//XXX: just set 'content' => $this->getContent()?
			'text' => $this->getContent()->serialize( $this->getFormat() ),
			'comment' => $this->getComment(),
			'user' => $userId,
			'user_text' => $userText,
			'timestamp' => $this->timestamp,
			'minor_edit' => $this->minor,
			) );
		$revision->insertOn( $dbw );
		$changed = $page->updateIfNewerOn( $dbw, $revision );

		if ( $changed !== false && !$this->mNoUpdates ) {
			wfDebug( __METHOD__ . ": running updates\n" );
			// countable/oldcountable stuff is handled in WikiImporter::finishImportPage
			$page->doEditUpdates(
				$revision,
				$userObj,
				array( 'created' => $created, 'oldcountable' => 'no-change' )
			);
		}

		return true;
	}

	function importLogItem() {
		$dbw = wfGetDB( DB_MASTER );
		# @todo FIXME: This will not record autoblocks
		if ( !$this->getTitle() ) {
			wfDebug( __METHOD__ . ": skipping invalid {$this->type}/{$this->action} log time, timestamp " .
				$this->timestamp . "\n" );
			return;
		}
		# Check if it exists already
		// @todo FIXME: Use original log ID (better for backups)
		$prior = $dbw->selectField( 'logging', '1',
			array( 'log_type' => $this->getType(),
				'log_action' => $this->getAction(),
				'log_timestamp' => $dbw->timestamp( $this->timestamp ),
				'log_namespace' => $this->getTitle()->getNamespace(),
				'log_title' => $this->getTitle()->getDBkey(),
				'log_comment' => $this->getComment(),
				#'log_user_text' => $this->user_text,
				'log_params' => $this->params ),
			__METHOD__
		);
		// @todo FIXME: This could fail slightly for multiple matches :P
		if ( $prior ) {
			wfDebug( __METHOD__
				. ": skipping existing item for Log:{$this->type}/{$this->action}, timestamp "
				. $this->timestamp . "\n" );
			return;
		}
		$log_id = $dbw->nextSequenceValue( 'logging_log_id_seq' );
		$data = array(
			'log_id' => $log_id,
			'log_type' => $this->type,
			'log_action' => $this->action,
			'log_timestamp' => $dbw->timestamp( $this->timestamp ),
			'log_user' => User::idFromName( $this->user_text ),
			#'log_user_text' => $this->user_text,
			'log_namespace' => $this->getTitle()->getNamespace(),
			'log_title' => $this->getTitle()->getDBkey(),
			'log_comment' => $this->getComment(),
			'log_params' => $this->params
		);
		$dbw->insert( 'logging', $data, __METHOD__ );
	}

	/**
	 * @return bool
	 */
	function importUpload() {
		# Construct a file
		$archiveName = $this->getArchiveName();
		if ( $archiveName ) {
			wfDebug( __METHOD__ . "Importing archived file as $archiveName\n" );
			$file = OldLocalFile::newFromArchiveName( $this->getTitle(),
				RepoGroup::singleton()->getLocalRepo(), $archiveName );
		} else {
			$file = wfLocalFile( $this->getTitle() );
			$file->load( File::READ_LATEST );
			wfDebug( __METHOD__ . 'Importing new file as ' . $file->getName() . "\n" );
			if ( $file->exists() && $file->getTimestamp() > $this->getTimestamp() ) {
				$archiveName = $file->getTimestamp() . '!' . $file->getName();
				$file = OldLocalFile::newFromArchiveName( $this->getTitle(),
					RepoGroup::singleton()->getLocalRepo(), $archiveName );
				wfDebug( __METHOD__ . "File already exists; importing as $archiveName\n" );
			}
		}
		if ( !$file ) {
			wfDebug( __METHOD__ . ': Bad file for ' . $this->getTitle() . "\n" );
			return false;
		}

		# Get the file source or download if necessary
		$source = $this->getFileSrc();
		$flags = $this->isTempSrc() ? File::DELETE_SOURCE : 0;
		if ( !$source ) {
			$source = $this->downloadSource();
			$flags |= File::DELETE_SOURCE;
		}
		if ( !$source ) {
			wfDebug( __METHOD__ . ": Could not fetch remote file.\n" );
			return false;
		}
		$sha1 = $this->getSha1();
		if ( $sha1 && ( $sha1 !== sha1_file( $source ) ) ) {
			if ( $flags & File::DELETE_SOURCE ) {
				# Broken file; delete it if it is a temporary file
				unlink( $source );
			}
			wfDebug( __METHOD__ . ": Corrupt file $source.\n" );
			return false;
		}

		$user = User::newFromName( $this->user_text );

		# Do the actual upload
		if ( $archiveName ) {
			$status = $file->uploadOld( $source, $archiveName,
				$this->getTimestamp(), $this->getComment(), $user, $flags );
		} else {
			$status = $file->upload( $source, $this->getComment(), $this->getComment(),
				$flags, false, $this->getTimestamp(), $user );
		}

		if ( $status->isGood() ) {
			wfDebug( __METHOD__ . ": Successful\n" );
			return true;
		} else {
			wfDebug( __METHOD__ . ': failed: ' . $status->getHTML() . "\n" );
			return false;
		}
	}

	/**
	 * @return bool|string
	 */
	function downloadSource() {
		if ( !$this->config->get( 'EnableUploads' ) ) {
			return false;
		}

		$tempo = tempnam( wfTempDir(), 'download' );
		$f = fopen( $tempo, 'wb' );
		if ( !$f ) {
			wfDebug( "IMPORT: couldn't write to temp file $tempo\n" );
			return false;
		}

		// @todo FIXME!
		$src = $this->getSrc();
		$data = Http::get( $src, array(), __METHOD__ );
		if ( !$data ) {
			wfDebug( "IMPORT: couldn't fetch source $src\n" );
			fclose( $f );
			unlink( $tempo );
			return false;
		}

		fwrite( $f, $data );
		fclose( $f );

		return $tempo;
	}

}

/**
 * Source interface for XML import.
 */
interface ImportSource {

	/**
	 * Indicates whether the end of the input has been reached.
	 * Will return true after a finite number of calls to readChunk.
	 *
	 * @return bool true if there is no more input, false otherwise.
	 */
	function atEnd();

	/**
	 * Return a chunk of the input, as a (possibly empty) string.
	 * When the end of input is reached, readChunk() returns false.
	 * If atEnd() returns false, readChunk() will return a string.
	 * If atEnd() returns true, readChunk() will return false.
	 *
	 * @return bool|string
	 */
	function readChunk();
}

/**
 * Used for importing XML dumps where the content of the dump is in a string.
 * This class is ineffecient, and should only be used for small dumps.
 * For larger dumps, ImportStreamSource should be used instead.
 *
 * @ingroup SpecialPage
 */
class ImportStringSource implements ImportSource {
	function __construct( $string ) {
		$this->mString = $string;
		$this->mRead = false;
	}

	/**
	 * @return bool
	 */
	function atEnd() {
		return $this->mRead;
	}

	/**
	 * @return bool|string
	 */
	function readChunk() {
		if ( $this->atEnd() ) {
			return false;
		}
		$this->mRead = true;
		return $this->mString;
	}
}

/**
 * Imports a XML dump from a file (either from file upload, files on disk, or HTTP)
 * @ingroup SpecialPage
 */
class ImportStreamSource implements ImportSource {
	function __construct( $handle ) {
		$this->mHandle = $handle;
	}

	/**
	 * @return bool
	 */
	function atEnd() {
		return feof( $this->mHandle );
	}

	/**
	 * @return string
	 */
	function readChunk() {
		return fread( $this->mHandle, 32768 );
	}

	/**
	 * @param string $filename
	 * @return Status
	 */
	static function newFromFile( $filename ) {
		wfSuppressWarnings();
		$file = fopen( $filename, 'rt' );
		wfRestoreWarnings();
		if ( !$file ) {
			return Status::newFatal( "importcantopen" );
		}
		return Status::newGood( new ImportStreamSource( $file ) );
	}

	/**
	 * @param string $fieldname
	 * @return Status
	 */
	static function newFromUpload( $fieldname = "xmlimport" ) {
		$upload =& $_FILES[$fieldname];

		if ( $upload === null || !$upload['name'] ) {
			return Status::newFatal( 'importnofile' );
		}
		if ( !empty( $upload['error'] ) ) {
			switch ( $upload['error'] ) {
				case 1:
					# The uploaded file exceeds the upload_max_filesize directive in php.ini.
					return Status::newFatal( 'importuploaderrorsize' );
				case 2:
					# The uploaded file exceeds the MAX_FILE_SIZE directive that
					# was specified in the HTML form.
					return Status::newFatal( 'importuploaderrorsize' );
				case 3:
					# The uploaded file was only partially uploaded
					return Status::newFatal( 'importuploaderrorpartial' );
				case 6:
					# Missing a temporary folder.
					return Status::newFatal( 'importuploaderrortemp' );
				# case else: # Currently impossible
			}

		}
		$fname = $upload['tmp_name'];
		if ( is_uploaded_file( $fname ) ) {
			return ImportStreamSource::newFromFile( $fname );
		} else {
			return Status::newFatal( 'importnofile' );
		}
	}

	/**
	 * @param string $url
	 * @param string $method
	 * @return Status
	 */
	static function newFromURL( $url, $method = 'GET' ) {
		wfDebug( __METHOD__ . ": opening $url\n" );
		# Use the standard HTTP fetch function; it times out
		# quicker and sorts out user-agent problems which might
		# otherwise prevent importing from large sites, such
		# as the Wikimedia cluster, etc.
		$data = Http::request( $method, $url, array( 'followRedirects' => true ), __METHOD__ );
		if ( $data !== false ) {
			$file = tmpfile();
			fwrite( $file, $data );
			fflush( $file );
			fseek( $file, 0 );
			return Status::newGood( new ImportStreamSource( $file ) );
		} else {
			return Status::newFatal( 'importcantopen' );
		}
	}

	/**
	 * @param string $interwiki
	 * @param string $page
	 * @param bool $history
	 * @param bool $templates
	 * @param int $pageLinkDepth
	 * @return Status
	 */
	public static function newFromInterwiki( $interwiki, $page, $history = false,
		$templates = false, $pageLinkDepth = 0
	) {
		if ( $page == '' ) {
			return Status::newFatal( 'import-noarticle' );
		}
		$link = Title::newFromText( "$interwiki:Special:Export/$page" );
		if ( is_null( $link ) || !$link->isExternal() ) {
			return Status::newFatal( 'importbadinterwiki' );
		} else {
			$params = array();
			if ( $history ) {
				$params['history'] = 1;
			}
			if ( $templates ) {
				$params['templates'] = 1;
			}
			if ( $pageLinkDepth ) {
				$params['pagelink-depth'] = $pageLinkDepth;
			}
			$url = $link->getFullURL( $params );
			# For interwikis, use POST to avoid redirects.
			return ImportStreamSource::newFromURL( $url, "POST" );
		}
	}
}
