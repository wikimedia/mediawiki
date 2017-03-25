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
 * Represents a revision, log entry or upload during the import process.
 * This class sticks closely to the structure of the XML dump.
 *
 * @since 1.2
 *
 * @ingroup SpecialPage
 */
class WikiRevision {

	/**
	 * @since 1.17
	 * @deprecated in 1.29. Unused.
	 * @note Introduced in 9b3128eb2b654761f21fd4ca1d5a1a4b796dc912, unused there, unused now.
	 */
	public $importer = null;

	/**
	 * @since 1.2
	 * @var Title
	 */
	public $title = null;

	/**
	 * @since 1.6.4
	 * @var int
	 */
	public $id = 0;

	/**
	 * @since 1.2
	 * @var string
	 */
	public $timestamp = "20010115000000";

	/**
	 * @since 1.2
	 * @var int
	 * @deprecated in 1.29. Unused.
	 * @note Introduced in 436a028086fb3f01c4605c5ad2964d56f9306aca, unused there, unused now.
	 */
	public $user = 0;

	/**
	 * @since 1.2
	 * @var string
	 */
	public $user_text = "";

	/**
	 * @since 1.27
	 * @var User
	 */
	public $userObj = null;

	/**
	 * @since 1.21
	 * @var string
	 */
	public $model = null;

	/**
	 * @since 1.21
	 * @var string
	 */
	public $format = null;

	/**
	 * @since 1.2
	 * @var string
	 */
	public $text = "";

	/**
	 * @since 1.12.2
	 * @var int
	 */
	protected $size;

	/**
	 * @since 1.21
	 * @var Content
	 */
	public $content = null;

	/**
	 * @since 1.24
	 * @var ContentHandler
	 */
	protected $contentHandler = null;

	/**
	 * @since 1.2.6
	 * @var string
	 */
	public $comment = "";

	/**
	 * @since 1.5.7
	 * @var bool
	 */
	public $minor = false;

	/**
	 * @since 1.12.2
	 * @var string
	 */
	public $type = "";

	/**
	 * @since 1.12.2
	 * @var string
	 */
	public $action = "";

	/**
	 * @since 1.12.2
	 * @var string
	 */
	public $params = "";

	/**
	 * @since 1.17
	 * @var string
	 */
	public $fileSrc = '';

	/**
	 * @since 1.17
	 * @var bool|string
	 */
	public $sha1base36 = false;

	/**
	 * @since 1.17
	 * @var string
	 */
	public $archiveName = '';

	/**
	 * @since 1.12.2
	 */
	protected $filename;

	/**
	 * @since 1.12.2
	 * @var mixed
	 */
	protected $src;

	/**
	 * @since 1.18
	 * @var bool
	 * @todo Unused?
	 */
	public $isTemp = false;

	/**
	 * @since 1.18
	 * @deprecated 1.29 use Wikirevision::isTempSrc()
	 * First written to in 43d5d3b682cc1733ad01a837d11af4a402d57e6a
	 * Actually introduced in 52cd34acf590e5be946b7885ffdc13a157c1c6cf
	 */
	public $fileIsTemp;

	/** @var bool */
	private $mNoUpdates = false;

	/** @var Config $config */
	private $config;

	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @since 1.7 taking a Title object (string before)
	 * @param Title $title
	 * @throws MWException
	 */
	public function setTitle( $title ) {
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
	 * @since 1.6.4
	 * @param int $id
	 */
	public function setID( $id ) {
		$this->id = $id;
	}

	/**
	 * @since 1.2
	 * @param string $ts
	 */
	public function setTimestamp( $ts ) {
		# 2003-08-05T18:30:02Z
		$this->timestamp = wfTimestamp( TS_MW, $ts );
	}

	/**
	 * @since 1.2
	 * @param string $user
	 */
	public function setUsername( $user ) {
		$this->user_text = $user;
	}

	/**
	 * @since 1.27
	 * @param User $user
	 */
	public function setUserObj( $user ) {
		$this->userObj = $user;
	}

	/**
	 * @since 1.2
	 * @param string $ip
	 */
	public function setUserIP( $ip ) {
		$this->user_text = $ip;
	}

	/**
	 * @since 1.21
	 * @param string $model
	 */
	public function setModel( $model ) {
		$this->model = $model;
	}

	/**
	 * @since 1.21
	 * @param string $format
	 */
	public function setFormat( $format ) {
		$this->format = $format;
	}

	/**
	 * @since 1.2
	 * @param string $text
	 */
	public function setText( $text ) {
		$this->text = $text;
	}

	/**
	 * @since 1.2.6
	 * @param string $text
	 */
	public function setComment( $text ) {
		$this->comment = $text;
	}

	/**
	 * @since 1.5.7
	 * @param bool $minor
	 */
	public function setMinor( $minor ) {
		$this->minor = (bool)$minor;
	}

	/**
	 * @since 1.12.2
	 * @param mixed $src
	 */
	public function setSrc( $src ) {
		$this->src = $src;
	}

	/**
	 * @since 1.17
	 * @param string $src
	 * @param bool $isTemp
	 */
	public function setFileSrc( $src, $isTemp ) {
		$this->fileSrc = $src;
		$this->fileIsTemp = $isTemp;
		$this->isTemp = $isTemp;
	}

	/**
	 * @since 1.17
	 * @param string $sha1base36
	 */
	public function setSha1Base36( $sha1base36 ) {
		$this->sha1base36 = $sha1base36;
	}

	/**
	 * @since 1.12.2
	 * @param string $filename
	 */
	public function setFilename( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * @since 1.17
	 * @param string $archiveName
	 */
	public function setArchiveName( $archiveName ) {
		$this->archiveName = $archiveName;
	}

	/**
	 * @since 1.12.2
	 * @param int $size
	 */
	public function setSize( $size ) {
		$this->size = intval( $size );
	}

	/**
	 * @since 1.12.2
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * @since 1.12.2
	 * @param string $action
	 */
	public function setAction( $action ) {
		$this->action = $action;
	}

	/**
	 * @since 1.12.2
	 * @param array $params
	 */
	public function setParams( $params ) {
		$this->params = $params;
	}

	/**
	 * @since 1.18
	 * @param bool $noupdates
	 */
	public function setNoUpdates( $noupdates ) {
		$this->mNoUpdates = $noupdates;
	}

	/**
	 * @since 1.2
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @since 1.6.4
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * @since 1.2
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @since 1.2
	 * @return string
	 */
	public function getUser() {
		return $this->user_text;
	}

	/**
	 * @since 1.27
	 * @return User
	 */
	public function getUserObj() {
		return $this->userObj;
	}

	/**
	 * @since 1.2
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @since 1.24
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		if ( is_null( $this->contentHandler ) ) {
			$this->contentHandler = ContentHandler::getForModelID( $this->getModel() );
		}

		return $this->contentHandler;
	}

	/**
	 * @since 1.21
	 * @return Content
	 */
	public function getContent() {
		if ( is_null( $this->content ) ) {
			$handler = $this->getContentHandler();
			$this->content = $handler->unserializeContent( $this->text, $this->getFormat() );
		}

		return $this->content;
	}

	/**
	 * @since 1.21
	 * @return string
	 */
	public function getModel() {
		if ( is_null( $this->model ) ) {
			$this->model = $this->getTitle()->getContentModel();
		}

		return $this->model;
	}

	/**
	 * @since 1.21
	 * @return string
	 */
	public function getFormat() {
		if ( is_null( $this->format ) ) {
			$this->format = $this->getContentHandler()->getDefaultFormat();
		}

		return $this->format;
	}

	/**
	 * @since 1.2.6
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @since 1.5.7
	 * @return bool
	 */
	public function getMinor() {
		return $this->minor;
	}

	/**
	 * @since 1.12.2
	 * @return mixed
	 */
	public function getSrc() {
		return $this->src;
	}

	/**
	 * @since 1.17
	 * @return bool|string
	 */
	public function getSha1() {
		if ( $this->sha1base36 ) {
			return Wikimedia\base_convert( $this->sha1base36, 36, 16 );
		}
		return false;
	}

	/**
	 * @since 1.17
	 * @return string
	 */
	public function getFileSrc() {
		return $this->fileSrc;
	}

	/**
	 * @since 1.17
	 * @return bool
	 */
	public function isTempSrc() {
		return $this->isTemp;
	}

	/**
	 * @since 1.12.2
	 * @return mixed
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @since 1.17
	 * @return string
	 */
	public function getArchiveName() {
		return $this->archiveName;
	}

	/**
	 * @since 1.12.2
	 * @return mixed
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @since 1.12.2
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @since 1.12.2
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @since 1.12.2
	 * @return string
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @since 1.4.1
	 * @return bool
	 */
	public function importOldRevision() {
		$dbw = wfGetDB( DB_MASTER );

		# Sneak a single revision into place
		$user = $this->getUserObj() ?: User::newFromName( $this->getUser() );
		if ( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $this->getUser();
			$user = new User;
		}

		// avoid memory leak...?
		Title::clearCaches();

		$page = WikiPage::factory( $this->title );
		$page->loadPageData( 'fromdbmaster' );
		if ( !$page->exists() ) {
			// must create the page...
			$pageId = $page->insertOn( $dbw );
			$created = true;
			$oldcountable = null;
		} else {
			$pageId = $page->getId();
			$created = false;

			$prior = $dbw->selectField( 'revision', '1',
				[ 'rev_page' => $pageId,
					'rev_timestamp' => $dbw->timestamp( $this->timestamp ),
					'rev_user_text' => $userText,
					'rev_comment' => $this->getComment() ],
				__METHOD__
			);
			if ( $prior ) {
				// @todo FIXME: This could fail slightly for multiple matches :P
				wfDebug( __METHOD__ . ": skipping existing revision for [[" .
					$this->title->getPrefixedText() . "]], timestamp " . $this->timestamp . "\n" );
				return false;
			}
		}

		if ( !$pageId ) {
			// This seems to happen if two clients simultaneously try to import the
			// same page
			wfDebug( __METHOD__ . ': got invalid $pageId when importing revision of [[' .
				$this->title->getPrefixedText() . ']], timestamp ' . $this->timestamp . "\n" );
			return false;
		}

		// Select previous version to make size diffs correct
		// @todo This assumes that multiple revisions of the same page are imported
		// in order from oldest to newest.
		$prevId = $dbw->selectField( 'revision', 'rev_id',
			[
				'rev_page' => $pageId,
				'rev_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $this->timestamp ) ),
			],
			__METHOD__,
			[ 'ORDER BY' => [
					'rev_timestamp DESC',
					'rev_id DESC', // timestamp is not unique per page
				]
			]
		);

		# @todo FIXME: Use original rev_id optionally (better for backups)
		# Insert the row
		$revision = new Revision( [
			'title' => $this->title,
			'page' => $pageId,
			'content_model' => $this->getModel(),
			'content_format' => $this->getFormat(),
			// XXX: just set 'content' => $this->getContent()?
			'text' => $this->getContent()->serialize( $this->getFormat() ),
			'comment' => $this->getComment(),
			'user' => $userId,
			'user_text' => $userText,
			'timestamp' => $this->timestamp,
			'minor_edit' => $this->minor,
			'parent_id' => $prevId,
			] );
		$revision->insertOn( $dbw );
		$changed = $page->updateIfNewerOn( $dbw, $revision );

		if ( $changed !== false && !$this->mNoUpdates ) {
			wfDebug( __METHOD__ . ": running updates\n" );
			// countable/oldcountable stuff is handled in WikiImporter::finishImportPage
			$page->doEditUpdates(
				$revision,
				$user,
				[ 'created' => $created, 'oldcountable' => 'no-change' ]
			);
		}

		return true;
	}

	/**
	 * @since 1.12.2
	 * @return bool
	 */
	public function importLogItem() {
		$dbw = wfGetDB( DB_MASTER );

		$user = $this->getUserObj() ?: User::newFromName( $this->getUser() );
		if ( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $this->getUser();
		}

		# @todo FIXME: This will not record autoblocks
		if ( !$this->getTitle() ) {
			wfDebug( __METHOD__ . ": skipping invalid {$this->type}/{$this->action} log time, timestamp " .
				$this->timestamp . "\n" );
			return false;
		}
		# Check if it exists already
		// @todo FIXME: Use original log ID (better for backups)
		$prior = $dbw->selectField( 'logging', '1',
			[ 'log_type' => $this->getType(),
				'log_action' => $this->getAction(),
				'log_timestamp' => $dbw->timestamp( $this->timestamp ),
				'log_namespace' => $this->getTitle()->getNamespace(),
				'log_title' => $this->getTitle()->getDBkey(),
				'log_comment' => $this->getComment(),
				# 'log_user_text' => $this->user_text,
				'log_params' => $this->params ],
			__METHOD__
		);
		// @todo FIXME: This could fail slightly for multiple matches :P
		if ( $prior ) {
			wfDebug( __METHOD__
				. ": skipping existing item for Log:{$this->type}/{$this->action}, timestamp "
				. $this->timestamp . "\n" );
			return false;
		}
		$log_id = $dbw->nextSequenceValue( 'logging_log_id_seq' );
		$data = [
			'log_id' => $log_id,
			'log_type' => $this->type,
			'log_action' => $this->action,
			'log_timestamp' => $dbw->timestamp( $this->timestamp ),
			'log_user' => $userId,
			'log_user_text' => $userText,
			'log_namespace' => $this->getTitle()->getNamespace(),
			'log_title' => $this->getTitle()->getDBkey(),
			'log_comment' => $this->getComment(),
			'log_params' => $this->params
		];
		$dbw->insert( 'logging', $data, __METHOD__ );

		return true;
	}

	/**
	 * @since 1.12.2
	 * @return bool
	 */
	public function importUpload() {
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
		$autoDeleteSource = $this->isTempSrc();
		if ( !strlen( $source ) ) {
			$source = $this->downloadSource();
			$autoDeleteSource = true;
		}
		if ( !strlen( $source ) ) {
			wfDebug( __METHOD__ . ": Could not fetch remote file.\n" );
			return false;
		}

		$tmpFile = new TempFSFile( $source );
		if ( $autoDeleteSource ) {
			$tmpFile->autocollect();
		}

		$sha1File = ltrim( sha1_file( $source ), '0' );
		$sha1 = $this->getSha1();
		if ( $sha1 && ( $sha1 !== $sha1File ) ) {
			wfDebug( __METHOD__ . ": Corrupt file $source.\n" );
			return false;
		}

		$user = $this->getUserObj() ?: User::newFromName( $this->getUser() );

		# Do the actual upload
		if ( $archiveName ) {
			$status = $file->uploadOld( $source, $archiveName,
				$this->getTimestamp(), $this->getComment(), $user );
		} else {
			$flags = 0;
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
	 * @since 1.12.2
	 * @return bool|string
	 */
	public function downloadSource() {
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
		$data = Http::get( $src, [], __METHOD__ );
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
