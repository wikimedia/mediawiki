<?php
/**
 * MediaWiki page data importer
 *
 * Copyright © 2003,2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
	private $mLogItemCallback, $mUploadCallback, $mRevisionCallback, $mPageCallback;
	private $mSiteInfoCallback, $mTargetNamespace, $mPageOutCallback;
	private $mDebug;

	/**
	 * Creates an ImportXMLReader drawing from the source provided
	*/
	function __construct( $source ) {
		$this->reader = new XMLReader();

		stream_wrapper_register( 'uploadsource', 'UploadSourceAdapter' );
		$id = UploadSourceAdapter::registerSource( $source );
		$this->reader->open( "uploadsource://$id" );

		// Default callbacks
		$this->setRevisionCallback( array( $this, "importRevision" ) );
		$this->setUploadCallback( array( $this, 'importUpload' ) );
		$this->setLogItemCallback( array( $this, 'importLogItem' ) );
		$this->setPageOutCallback( array( $this, 'finishImportPage' ) );
	}

	private function throwXmlError( $err ) {
		$this->debug( "FAILURE: $err" );
		wfDebug( "WikiImporter XML error: $err\n" );
	}

	private function debug( $data ) {
		if( $this->mDebug ) {
			wfDebug( "IMPORT: $data\n" );
		}
	}

	private function warn( $data ) {
		wfDebug( "IMPORT: $data\n" );
	}

	private function notice( $data ) {
		global $wgCommandLineMode;
		if( $wgCommandLineMode ) {
			print "$data\n";
		} else {
			global $wgOut;
			$wgOut->addHTML( "<li>" . htmlspecialchars( $data ) . "</li>\n" );
		}
	}

	/**
	 * Set debug mode...
	 */
	function setDebug( $debug ) {
		$this->mDebug = $debug;
	}

	/**
	 * Sets the action to perform as each new page in the stream is reached.
	 * @param $callback callback
	 * @return callback
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
	 * @param $callback callback
	 * @return callback
	 */
	public function setPageOutCallback( $callback ) {
		$previous = $this->mPageOutCallback;
		$this->mPageOutCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each page revision is reached.
	 * @param $callback callback
	 * @return callback
	 */
	public function setRevisionCallback( $callback ) {
		$previous = $this->mRevisionCallback;
		$this->mRevisionCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each file upload version is reached.
	 * @param $callback callback
	 * @return callback
	 */
	public function setUploadCallback( $callback ) {
		$previous = $this->mUploadCallback;
		$this->mUploadCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform as each log item reached.
	 * @param $callback callback
	 * @return callback
	 */
	public function setLogItemCallback( $callback ) {
		$previous = $this->mLogItemCallback;
		$this->mLogItemCallback = $callback;
		return $previous;
	}

	/**
	 * Sets the action to perform when site info is encountered
	 * @param $callback callback
	 * @return callback
	 */
	public function setSiteInfoCallback( $callback ) {
		$previous = $this->mSiteInfoCallback;
		$this->mSiteInfoCallback = $callback;
		return $previous;
	}

	/**
	 * Set a target namespace to override the defaults
	 */
	public function setTargetNamespace( $namespace ) {
		if( is_null( $namespace ) ) {
			// Don't override namespaces
			$this->mTargetNamespace = null;
		} elseif( $namespace >= 0 ) {
			// FIXME: Check for validity
			$this->mTargetNamespace = intval( $namespace );
		} else {
			return false;
		}
	}

	/**
	 * Default per-revision callback, performs the import.
	 * @param $revision WikiRevision
	 */
	public function importRevision( $revision ) {
		$dbw = wfGetDB( DB_MASTER );
		return $dbw->deadlockLoop( array( $revision, 'importOldRevision' ) );
	}

	/**
	 * Default per-revision callback, performs the import.
	 * @param $rev WikiRevision
	 */
	public function importLogItem( $rev ) {
		$dbw = wfGetDB( DB_MASTER );
		return $dbw->deadlockLoop( array( $rev, 'importLogItem' ) );
	}

	/**
	 * Dummy for now...
	 */
	public function importUpload( $revision ) {
		//$dbw = wfGetDB( DB_MASTER );
		//return $dbw->deadlockLoop( array( $revision, 'importUpload' ) );
		return false;
	}

	/**
	 * Mostly for hook use
	 */
	public function finishImportPage( $title, $origTitle, $revCount, $sRevCount, $pageInfo ) {
		$args = func_get_args();
		return wfRunHooks( 'AfterImportPage', $args );
	}

	/**
	 * Alternate per-revision callback, for debugging.
	 * @param $revision WikiRevision
	 */
	public function debugRevisionHandler( &$revision ) {
		$this->debug( "Got revision:" );
		if( is_object( $revision->title ) ) {
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
	 * Notify the callback function when a new <page> is reached.
	 * @param $title Title
	 */
	function pageCallback( $title ) {
		if( isset( $this->mPageCallback ) ) {
			call_user_func( $this->mPageCallback, $title );
		}
	}

	/**
	 * Notify the callback function when a </page> is closed.
	 * @param $title Title
	 * @param $origTitle Title
	 * @param $revCount Integer
	 * @param $sucCount Int: number of revisions for which callback returned true
	 * @param $pageInfo Array: associative array of page information
	 */
	private function pageOutCallback( $title, $origTitle, $revCount, $sucCount, $pageInfo ) {
		if( isset( $this->mPageOutCallback ) ) {
			$args = func_get_args();
			call_user_func_array( $this->mPageOutCallback, $args );
		}
	}

	/**
	 * Notify the callback function of a revision
	 * @param $revision A WikiRevision object
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
	 * @param $revision A WikiRevision object
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
	 * Shouldn't something like this be built-in to XMLReader?
	 * Fetches text contents of the current element, assuming
	 * no sub-elements or such scary things.
	 * @return string
	 * @access private
	 */
	private function nodeContents() {
		if( $this->reader->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while( $this->reader->read() ) {
			switch( $this->reader->nodeType ) {
			case XmlReader::TEXT:
			case XmlReader::SIGNIFICANT_WHITESPACE:
				$buffer .= $this->reader->value;
				break;
			case XmlReader::END_ELEMENT:
				return $buffer;
			}
		}
		
		$this->reader->close();
		return '';
	}

	# --------------

	/** Left in for debugging */
	private function dumpElement() {
		static $lookup = null;
		if (!$lookup) {
			$xmlReaderConstants = array(
				"NONE",
				"ELEMENT",
				"ATTRIBUTE",
				"TEXT",
				"CDATA",
				"ENTITY_REF",
				"ENTITY",
				"PI",
				"COMMENT",
				"DOC",
				"DOC_TYPE",
				"DOC_FRAGMENT",
				"NOTATION",
				"WHITESPACE",
				"SIGNIFICANT_WHITESPACE",
				"END_ELEMENT",
				"END_ENTITY",
				"XML_DECLARATION",
				);
			$lookup = array();

			foreach( $xmlReaderConstants as $name ) {
				$lookup[constant("XmlReader::$name")] = $name;
			}
		}

		print( var_dump(
			$lookup[$this->reader->nodeType],
			$this->reader->name,
			$this->reader->value
		)."\n\n" );
	}

	/**
	 * Primary entry point
	 */
	public function doImport() {
		$this->reader->read();

		if ( $this->reader->name != 'mediawiki' ) {
			throw new MWException( "Expected <mediawiki> tag, got ".
				$this->reader->name );
		}
		$this->debug( "<mediawiki> tag is correct." );

		$this->debug( "Starting primary dump processing loop." );

		$keepReading = $this->reader->read();
		$skip = false;
		while ( $keepReading ) {
			$tag = $this->reader->name;
			$type = $this->reader->nodeType;

			if ( !wfRunHooks( 'ImportHandleToplevelXMLTag', $this ) ) {
				// Do nothing
			} elseif ( $tag == 'mediawiki' && $type == XmlReader::END_ELEMENT ) {
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

			if ($skip) {
				$keepReading = $this->reader->next();
				$skip = false;
				$this->debug( "Skip" );
			} else {
				$keepReading = $this->reader->read();
			}
		}

		return true;
	}

	private function handleSiteInfo() {
		// Site info is useful, but not actually used for dump imports.
		// Includes a quick short-circuit to save performance.
		if ( ! $this->mSiteInfoCallback ) {
			$this->reader->next();
			return true;
		}
		throw new MWException( "SiteInfo tag is not yet handled, do not set mSiteInfoCallback" );
	}

	private function handleLogItem() {
		$this->debug( "Enter log item handler." );
		$logInfo = array();

		// Fields that can just be stuffed in the pageInfo object
		$normalFields = array( 'id', 'comment', 'type', 'action', 'timestamp',
					'logtitle', 'params' );

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XmlReader::END_ELEMENT &&
					$this->reader->name == 'logitem') {
				break;
			}

			$tag = $this->reader->name;

			if ( !wfRunHooks( 'ImportHandleLogItemXMLTag',
						$this, $logInfo ) ) {
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

	private function processLogItem( $logInfo ) {
		$revision = new WikiRevision;

		$revision->setID( $logInfo['id'] );
		$revision->setType( $logInfo['type'] );
		$revision->setAction( $logInfo['action'] );
		$revision->setTimestamp( $logInfo['timestamp'] );
		$revision->setParams( $logInfo['params'] );
		$revision->setTitle( Title::newFromText( $logInfo['logtitle'] ) );

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
		$normalFields = array( 'title', 'id', 'redirect', 'restrictions' );

		$skip = false;
		$badTitle = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XmlReader::END_ELEMENT &&
					$this->reader->name == 'page') {
				break;
			}

			$tag = $this->reader->name;

			if ( $badTitle ) {
				// The title is invalid, bail out of this page
				$skip = true;
			} elseif ( !wfRunHooks( 'ImportHandlePageXMLTag', array( $this,
						&$pageInfo ) ) ) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$pageInfo[$tag] = $this->nodeContents();
				if ( $tag == 'title' ) {
					$title = $this->processTitle( $pageInfo['title'] );

					if ( !$title ) {
						$badTitle = true;
						$skip = true;
					}

					$this->pageCallback( $title );
					list( $pageInfo['_title'], $origTitle ) = $title;
				}
			} elseif ( $tag == 'revision' ) {
				$this->handleRevision( $pageInfo );
			} elseif ( $tag == 'upload' ) {
				$this->handleUpload( $pageInfo );
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled page XML tag $tag" );
				$skip = true;
			}
		}

		$this->pageOutCallback( $pageInfo['_title'], $origTitle,
					$pageInfo['revisionCount'],
					$pageInfo['successfulRevisionCount'],
					$pageInfo );
	}

	private function handleRevision( &$pageInfo ) {
		$this->debug( "Enter revision handler" );
		$revisionInfo = array();

		$normalFields = array( 'id', 'timestamp', 'comment', 'minor', 'text' );

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XmlReader::END_ELEMENT &&
					$this->reader->name == 'revision') {
				break;
			}

			$tag = $this->reader->name;

			if ( !wfRunHooks( 'ImportHandleRevisionXMLTag', $this,
						$pageInfo, $revisionInfo ) ) {
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

	private function processRevision( $pageInfo, $revisionInfo ) {
		$revision = new WikiRevision;

		$revision->setID( $revisionInfo['id'] );
		$revision->setText( $revisionInfo['text'] );
		$revision->setTitle( $pageInfo['_title'] );
		$revision->setTimestamp( $revisionInfo['timestamp'] );

		if ( isset( $revisionInfo['comment'] ) ) {
			$revision->setComment( $revisionInfo['comment'] );
		}

		if ( isset( $revisionInfo['minor'] ) )
			$revision->setMinor( true );

		if ( isset( $revisionInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $revisionInfo['contributor']['ip'] );
		}
		if ( isset( $revisionInfo['contributor']['username'] ) ) {
			$revision->setUserName( $revisionInfo['contributor']['username'] );
		}

		return $this->revisionCallback( $revision );
	}

	private function handleUpload( &$pageInfo ) {
		$this->debug( "Enter upload handler" );
		$uploadInfo = array();

		$normalFields = array( 'timestamp', 'comment', 'filename', 'text',
					'src', 'size' );

		$skip = false;

		while ( $skip ? $this->reader->next() : $this->reader->read() ) {
			if ( $this->reader->nodeType == XmlReader::END_ELEMENT &&
					$this->reader->name == 'upload') {
				break;
			}

			$tag = $this->reader->name;

			if ( !wfRunHooks( 'ImportHandleUploadXMLTag', $this,
						$pageInfo ) ) {
				// Do nothing
			} elseif ( in_array( $tag, $normalFields ) ) {
				$uploadInfo[$tag] = $this->nodeContents();
			} elseif ( $tag == 'contributor' ) {
				$uploadInfo['contributor'] = $this->handleContributor();
			} elseif ( $tag != '#text' ) {
				$this->warn( "Unhandled upload XML tag $tag" );
				$skip = true;
			}
		}

		return $this->processUpload( $pageInfo, $uploadInfo );
	}

	private function processUpload( $pageInfo, $uploadInfo ) {
		$revision = new WikiRevision;

		$revision->setTitle( $pageInfo['_title'] );
		$revision->setID( $uploadInfo['id'] );
		$revision->setTimestamp( $uploadInfo['timestamp'] );
		$revision->setText( $uploadInfo['text'] );
		$revision->setFilename( $uploadInfo['filename'] );
		$revision->setSrc( $uploadInfo['src'] );
		$revision->setSize( intval( $uploadInfo['size'] ) );
		$revision->setComment( $uploadInfo['comment'] );

		if ( isset( $uploadInfo['contributor']['ip'] ) ) {
			$revision->setUserIP( $uploadInfo['contributor']['ip'] );
		}
		if ( isset( $uploadInfo['contributor']['username'] ) ) {
			$revision->setUserName( $uploadInfo['contributor']['username'] );
		}

		return $this->uploadCallback( $revision );
	}

	private function handleContributor() {
		$fields = array( 'id', 'ip', 'username' );
		$info = array();

		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XmlReader::END_ELEMENT &&
					$this->reader->name == 'contributor') {
				break;
			}

			$tag = $this->reader->name;

			if ( in_array( $tag, $fields ) ) {
				$info[$tag] = $this->nodeContents();
			}
		}

		return $info;
	}

	private function processTitle( $text ) {
		$workTitle = $text;
		$origTitle = Title::newFromText( $workTitle );

		if( !is_null( $this->mTargetNamespace ) && !is_null( $origTitle ) ) {
			$title = Title::makeTitle( $this->mTargetNamespace,
				$origTitle->getDBkey() );
		} else {
			$title = Title::newFromText( $workTitle );
		}

		if( is_null( $title ) ) {
			// Invalid page title? Ignore the page
			$this->notice( "Skipping invalid page title '$workTitle'" );
			return false;
		} elseif( $title->getInterwiki() != '' ) {
			$this->notice( "Skipping interwiki page title '$workTitle'" );
			return false;
		}

		return array( $origTitle, $title );
	}
}

/** This is a horrible hack used to keep source compatibility */
class UploadSourceAdapter {
	static $sourceRegistrations = array();

	private $mSource;
	private $mBuffer;
	private $mPosition;

	static function registerSource( $source ) {
		$id = wfGenerateToken();

		self::$sourceRegistrations[$id] = $source;

		return $id;
	}

	function stream_open( $path, $mode, $options, &$opened_path ) {
		$url = parse_url($path);
		$id = $url['host'];

		if ( !isset( self::$sourceRegistrations[$id] ) ) {
			return false;
		}

		$this->mSource = self::$sourceRegistrations[$id];

		return true;
	}

	function stream_read( $count ) {
		$return = '';
		$leave = false;

		while ( !$leave && !$this->mSource->atEnd() &&
				strlen($this->mBuffer) < $count ) {
			$read = $this->mSource->readChunk();

			if ( !strlen($read) ) {
				$leave = true;
			}

			$this->mBuffer .= $read;
		}

		if ( strlen($this->mBuffer) ) {
			$return = substr( $this->mBuffer, 0, $count );
			$this->mBuffer = substr( $this->mBuffer, $count );
		}

		$this->mPosition += strlen($return);

		return $return;
	}

	function stream_write( $data ) {
		return false;
	}

	function stream_tell() {
		return $this->mPosition;
	}

	function stream_eof() {
		return $this->mSource->atEnd();
	}

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

class XMLReader2 extends XMLReader {
	function nodeContents() {
		if( $this->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while( $this->read() ) {
			switch( $this->nodeType ) {
			case XmlReader::TEXT:
			case XmlReader::SIGNIFICANT_WHITESPACE:
				$buffer .= $this->value;
				break;
			case XmlReader::END_ELEMENT:
				return $buffer;
			}
		}
		return $this->close();
	}
}

/**
 * @todo document (e.g. one-sentence class description).
 * @ingroup SpecialPage
 */
class WikiRevision {
	var $title = null;
	var $id = 0;
	var $timestamp = "20010115000000";
	var $user = 0;
	var $user_text = "";
	var $text = "";
	var $comment = "";
	var $minor = false;
	var $type = "";
	var $action = "";
	var $params = "";

	function setTitle( $title ) {
		if( is_object( $title ) ) {
			$this->title = $title;
		} elseif( is_null( $title ) ) {
			throw new MWException( "WikiRevision given a null title in import. You may need to adjust \$wgLegalTitleChars." );
		} else {
			throw new MWException( "WikiRevision given non-object title in import." );
		}
	}

	function setID( $id ) {
		$this->id = $id;
	}

	function setTimestamp( $ts ) {
		# 2003-08-05T18:30:02Z
		$this->timestamp = wfTimestamp( TS_MW, $ts );
	}

	function setUsername( $user ) {
		$this->user_text = $user;
	}

	function setUserIP( $ip ) {
		$this->user_text = $ip;
	}

	function setText( $text ) {
		$this->text = $text;
	}

	function setComment( $text ) {
		$this->comment = $text;
	}

	function setMinor( $minor ) {
		$this->minor = (bool)$minor;
	}

	function setSrc( $src ) {
		$this->src = $src;
	}

	function setFilename( $filename ) {
		$this->filename = $filename;
	}

	function setSize( $size ) {
		$this->size = intval( $size );
	}
	
	function setType( $type ) {
		$this->type = $type;
	}
	
	function setAction( $action ) {
		$this->action = $action;
	}
	
	function setParams( $params ) {
		$this->params = $params;
	}

	function getTitle() {
		return $this->title;
	}

	function getID() {
		return $this->id;
	}

	function getTimestamp() {
		return $this->timestamp;
	}

	function getUser() {
		return $this->user_text;
	}

	function getText() {
		return $this->text;
	}

	function getComment() {
		return $this->comment;
	}

	function getMinor() {
		return $this->minor;
	}

	function getSrc() {
		return $this->src;
	}

	function getFilename() {
		return $this->filename;
	}

	function getSize() {
		return $this->size;
	}
	
	function getType() {
		return $this->type;
	}
	
	function getAction() {
		return $this->action;
	}
	
	function getParams() {
		return $this->params;
	}

	function importOldRevision() {
		$dbw = wfGetDB( DB_MASTER );

		# Sneak a single revision into place
		$user = User::newFromName( $this->getUser() );
		if( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $this->getUser();
		}

		// avoid memory leak...?
		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		$article = new Article( $this->title );
		$pageId = $article->getId();
		if( $pageId == 0 ) {
			# must create the page...
			$pageId = $article->insertOn( $dbw );
			$created = true;
		} else {
			$created = false;

			$prior = $dbw->selectField( 'revision', '1',
				array( 'rev_page' => $pageId,
					'rev_timestamp' => $dbw->timestamp( $this->timestamp ),
					'rev_user_text' => $userText,
					'rev_comment'   => $this->getComment() ),
				__METHOD__
			);
			if( $prior ) {
				// FIXME: this could fail slightly for multiple matches :P
				wfDebug( __METHOD__ . ": skipping existing revision for [[" .
					$this->title->getPrefixedText() . "]], timestamp " . $this->timestamp . "\n" );
				return false;
			}
		}

		# FIXME: Use original rev_id optionally (better for backups)
		# Insert the row
		$revision = new Revision( array(
			'page'       => $pageId,
			'text'       => $this->getText(),
			'comment'    => $this->getComment(),
			'user'       => $userId,
			'user_text'  => $userText,
			'timestamp'  => $this->timestamp,
			'minor_edit' => $this->minor,
			) );
		$revId = $revision->insertOn( $dbw );
		$changed = $article->updateIfNewerOn( $dbw, $revision );
		
		# To be on the safe side...
		$tempTitle = $GLOBALS['wgTitle'];
		$GLOBALS['wgTitle'] = $this->title;

		if( $created ) {
			wfDebug( __METHOD__ . ": running onArticleCreate\n" );
			Article::onArticleCreate( $this->title );

			wfDebug( __METHOD__ . ": running create updates\n" );
			$article->createUpdates( $revision );

		} elseif( $changed ) {
			wfDebug( __METHOD__ . ": running onArticleEdit\n" );
			Article::onArticleEdit( $this->title );

			wfDebug( __METHOD__ . ": running edit updates\n" );
			$article->editUpdates(
				$this->getText(),
				$this->getComment(),
				$this->minor,
				$this->timestamp,
				$revId );
		}
		$GLOBALS['wgTitle'] = $tempTitle;

		return true;
	}
	
	function importLogItem() {
		$dbw = wfGetDB( DB_MASTER );
		# FIXME: this will not record autoblocks
		if( !$this->getTitle() ) {
			wfDebug( __METHOD__ . ": skipping invalid {$this->type}/{$this->action} log time, timestamp " . 
				$this->timestamp . "\n" );
			return;
		}
		# Check if it exists already
		// FIXME: use original log ID (better for backups)
		$prior = $dbw->selectField( 'logging', '1',
			array( 'log_type' => $this->getType(),
				'log_action'    => $this->getAction(),
				'log_timestamp' => $dbw->timestamp( $this->timestamp ),
				'log_namespace' => $this->getTitle()->getNamespace(),
				'log_title'     => $this->getTitle()->getDBkey(),
				'log_comment'   => $this->getComment(),
				#'log_user_text' => $this->user_text,
				'log_params'    => $this->params ),
			__METHOD__
		);
		// FIXME: this could fail slightly for multiple matches :P
		if( $prior ) {
			wfDebug( __METHOD__ . ": skipping existing item for Log:{$this->type}/{$this->action}, timestamp " . 
				$this->timestamp . "\n" );
			return false;
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

	function importUpload() {
		wfDebug( __METHOD__ . ": STUB\n" );

		/**
			// from file revert...
			$source = $this->file->getArchiveVirtualUrl( $this->oldimage );
			$comment = $wgRequest->getText( 'wpComment' );
			// TODO: Preserve file properties from database instead of reloading from file
			$status = $this->file->upload( $source, $comment, $comment );
			if( $status->isGood() ) {
		*/

		/**
			// from file upload...
		$this->mLocalFile = wfLocalFile( $nt );
		$this->mDestName = $this->mLocalFile->getName();
		//....
			$status = $this->mLocalFile->upload( $this->mTempPath, $this->mComment, $pageText,
			File::DELETE_SOURCE, $this->mFileProps );
			if ( !$status->isGood() ) {
				$resultDetails = array( 'internal' => $status->getWikiText() );
		*/

		// @todo Fixme: upload() uses $wgUser, which is wrong here
		// it may also create a page without our desire, also wrong potentially.
		// and, it will record a *current* upload, but we might want an archive version here

		$file = wfLocalFile( $this->getTitle() );
		if( !$file ) {
			wfDebug( "IMPORT: Bad file. :(\n" );
			return false;
		}

		$source = $this->downloadSource();
		if( !$source ) {
			wfDebug( "IMPORT: Could not fetch remote file. :(\n" );
			return false;
		}

		$status = $file->upload( $source,
			$this->getComment(),
			$this->getComment(), // Initial page, if none present...
			File::DELETE_SOURCE,
			false, // props...
			$this->getTimestamp() );

		if( $status->isGood() ) {
			// yay?
			wfDebug( "IMPORT: is ok?\n" );
			return true;
		}

		wfDebug( "IMPORT: is bad? " . $status->getXml() . "\n" );
		return false;

	}

	function downloadSource() {
		global $wgEnableUploads;
		if( !$wgEnableUploads ) {
			return false;
		}

		$tempo = tempnam( wfTempDir(), 'download' );
		$f = fopen( $tempo, 'wb' );
		if( !$f ) {
			wfDebug( "IMPORT: couldn't write to temp file $tempo\n" );
			return false;
		}

		// @todo Fixme!
		$src = $this->getSrc();
		$data = Http::get( $src );
		if( !$data ) {
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
 * @todo document (e.g. one-sentence class description).
 * @ingroup SpecialPage
 */
class ImportStringSource {
	function __construct( $string ) {
		$this->mString = $string;
		$this->mRead = false;
	}

	function atEnd() {
		return $this->mRead;
	}

	function readChunk() {
		if( $this->atEnd() ) {
			return false;
		} else {
			$this->mRead = true;
			return $this->mString;
		}
	}
}

/**
 * @todo document (e.g. one-sentence class description).
 * @ingroup SpecialPage
 */
class ImportStreamSource {
	function __construct( $handle ) {
		$this->mHandle = $handle;
	}

	function atEnd() {
		return feof( $this->mHandle );
	}

	function readChunk() {
		return fread( $this->mHandle, 32768 );
	}

	static function newFromFile( $filename ) {
		$file = @fopen( $filename, 'rt' );
		if( !$file ) {
			return Status::newFatal( "importcantopen" );
		}
		return Status::newGood( new ImportStreamSource( $file ) );
	}

	static function newFromUpload( $fieldname = "xmlimport" ) {
		$upload =& $_FILES[$fieldname];

		if( !isset( $upload ) || !$upload['name'] ) {
			return Status::newFatal( 'importnofile' );
		}
		if( !empty( $upload['error'] ) ) {
			switch($upload['error']){
				case 1: # The uploaded file exceeds the upload_max_filesize directive in php.ini.
					return Status::newFatal( 'importuploaderrorsize' );
				case 2: # The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
					return Status::newFatal( 'importuploaderrorsize' );
				case 3: # The uploaded file was only partially uploaded
					return Status::newFatal( 'importuploaderrorpartial' );
				case 6: #Missing a temporary folder.
					return Status::newFatal( 'importuploaderrortemp' );
				# case else: # Currently impossible
			}

		}
		$fname = $upload['tmp_name'];
		if( is_uploaded_file( $fname ) ) {
			return ImportStreamSource::newFromFile( $fname );
		} else {
			return Status::newFatal( 'importnofile' );
		}
	}

	static function newFromURL( $url, $method = 'GET' ) {
		wfDebug( __METHOD__ . ": opening $url\n" );
		# Use the standard HTTP fetch function; it times out
		# quicker and sorts out user-agent problems which might
		# otherwise prevent importing from large sites, such
		# as the Wikimedia cluster, etc.
		$data = Http::request( $method, $url );
		if( $data !== false ) {
			$file = tmpfile();
			fwrite( $file, $data );
			fflush( $file );
			fseek( $file, 0 );
			return Status::newGood( new ImportStreamSource( $file ) );
		} else {
			return Status::newFatal( 'importcantopen' );
		}
	}

	public static function newFromInterwiki( $interwiki, $page, $history = false, $templates = false, $pageLinkDepth = 0 ) {
		if( $page == '' ) {
			return Status::newFatal( 'import-noarticle' );
		}
		$link = Title::newFromText( "$interwiki:Special:Export/$page" );
		if( is_null( $link ) || $link->getInterwiki() == '' ) {
			return Status::newFatal( 'importbadinterwiki' );
		} else {
			$params = array();
			if ( $history ) $params['history'] = 1;
			if ( $templates ) $params['templates'] = 1;
			if ( $pageLinkDepth ) $params['pagelink-depth'] = $pageLinkDepth;
			$url = $link->getFullUrl( $params );
			# For interwikis, use POST to avoid redirects.
			return ImportStreamSource::newFromURL( $url, "POST" );
		}
	}
}
