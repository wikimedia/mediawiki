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

	/** @var User */
	public $userObj = null;

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
	 * @param User $user
	 */
	function setUserObj( $user ) {
		$this->userObj = $user;
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
	 * @return User
	 */
	function getUserObj() {
		return $this->userObj;
	}

	/**
	 * @return string
	 *
	 * @deprecated Since 1.21, use getContent() instead.
	 */
	function getText() {
		wfDeprecated( __METHOD__, '1.21' );

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
			return Wikimedia\base_convert( $this->sha1base36, 36, 16 );
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

	function importLogItem() {
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
