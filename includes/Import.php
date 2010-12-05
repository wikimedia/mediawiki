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
