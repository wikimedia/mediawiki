<?php

use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\User;
use Psr\Log\LoggerInterface;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @since 1.31
 */
class ImportableUploadRevisionImporter implements UploadRevisionImporter {

	private bool $enableUploads;
	private LoggerInterface $logger;

	private bool $shouldCreateNullRevision = true;

	public function __construct(
		bool $enableUploads,
		LoggerInterface $logger
	) {
		$this->enableUploads = $enableUploads;
		$this->logger = $logger;
	}

	/**
	 * Setting this to false will deactivate the creation of a null revision as part of the upload
	 * process logging in LocalFile::recordUpload3, see T193621
	 *
	 * @param bool $shouldCreateNullRevision
	 */
	public function setNullRevisionCreation( $shouldCreateNullRevision ) {
		$this->shouldCreateNullRevision = $shouldCreateNullRevision;
	}

	/**
	 * @return StatusValue
	 */
	private function newNotOkStatus() {
		$statusValue = new StatusValue();
		$statusValue->setOK( false );
		return $statusValue;
	}

	/** @inheritDoc */
	public function import( ImportableUploadRevision $importableRevision ) {
		# Construct a file
		$archiveName = $importableRevision->getArchiveName();
		$localRepo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		if ( $archiveName ) {
			$this->logger->debug( __METHOD__ . ": Importing archived file as $archiveName" );
			$file = OldLocalFile::newFromArchiveName( $importableRevision->getTitle(),
				$localRepo, $archiveName );
		} else {
			$file = $localRepo->newFile( $importableRevision->getTitle() );
			$file->load( IDBAccessObject::READ_LATEST );
			$this->logger->debug( __METHOD__ . ': Importing new file as ' . $file->getName() );
			if ( $file->exists() && $file->getTimestamp() > $importableRevision->getTimestamp() ) {
				$archiveName = $importableRevision->getTimestamp() . '!' . $file->getName();
				$file = OldLocalFile::newFromArchiveName( $importableRevision->getTitle(),
					$localRepo, $archiveName );
				$this->logger->debug( __METHOD__ . ": File already exists; importing as $archiveName" );
			}
		}
		if ( !$file ) {
			$this->logger->debug( __METHOD__ . ': Bad file for ' . $importableRevision->getTitle() );
			return $this->newNotOkStatus();
		}

		# Get the file source or download if necessary
		$source = $importableRevision->getFileSrc();
		$autoDeleteSource = $importableRevision->isTempSrc();
		if ( $source === '' ) {
			$source = $this->downloadSource( $importableRevision );
			$autoDeleteSource = true;
		}
		if ( $source === '' ) {
			$this->logger->debug( __METHOD__ . ": Could not fetch remote file." );
			return $this->newNotOkStatus();
		}

		$tmpFile = new TempFSFile( $source );
		if ( $autoDeleteSource ) {
			$tmpFile->autocollect();
		}

		$sha1File = ltrim( sha1_file( $source ), '0' );
		$sha1 = $importableRevision->getSha1();
		if ( $sha1 && ( $sha1 !== $sha1File ) ) {
			$this->logger->debug( __METHOD__ . ": Corrupt file $source." );
			return $this->newNotOkStatus();
		}

		$user = $importableRevision->getUserObj()
			?: User::newFromName( $importableRevision->getUser(), false );

		# Do the actual upload
		if ( $file instanceof OldLocalFile ) {
			$status = $file->uploadOld(
				$source,
				$importableRevision->getTimestamp(),
				$importableRevision->getComment(),
				$user
			);
		} else {
			$flags = 0;
			$status = $file->upload(
				$source,
				$importableRevision->getComment(),
				$importableRevision->getComment(),
				$flags,
				false,
				$importableRevision->getTimestamp(),
				$user,
				[],
				$this->shouldCreateNullRevision
			);
		}

		if ( $status->isGood() ) {
			$this->logger->debug( __METHOD__ . ": Successful" );
		} else {
			$this->logger->debug( __METHOD__ . ': failed: ' . $status->getHTML() );
		}

		return $status;
	}

	/**
	 * @param ImportableUploadRevision $wikiRevision
	 *
	 * @return string|false
	 */
	private function downloadSource( ImportableUploadRevision $wikiRevision ) {
		if ( !$this->enableUploads ) {
			return false;
		}

		$tempo = tempnam( wfTempDir(), 'download' );
		$f = fopen( $tempo, 'wb' );
		if ( !$f ) {
			$this->logger->debug( "IMPORT: couldn't write to temp file $tempo" );
			return false;
		}

		// @todo FIXME!
		$src = $wikiRevision->getSrc();
		$data = MediaWikiServices::getInstance()->getHttpRequestFactory()->
			get( $src, [], __METHOD__ );
		if ( !$data ) {
			$this->logger->debug( "IMPORT: couldn't fetch source $src" );
			fclose( $f );
			unlink( $tempo );
			return false;
		}

		fwrite( $f, $data );
		fclose( $f );

		return $tempo;
	}

}
