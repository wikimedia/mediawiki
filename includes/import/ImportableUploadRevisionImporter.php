<?php

use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;

/**
 * @since 1.31
 */
class ImportableUploadRevisionImporter implements UploadRevisionImporter {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var bool
	 */
	private $enableUploads;

	/**
	 * @var bool
	 */
	private $shouldCreateNullRevision = true;

	/**
	 * @param bool $enableUploads
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		$enableUploads,
		LoggerInterface $logger
	) {
		$this->enableUploads = $enableUploads;
		$this->logger = $logger;
	}

	/**
	 * Setting this to false will deactivate the creation of a null revision as part of the upload
	 * process logging in LocalFile::recordUpload2, see T193621
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

	public function import( ImportableUploadRevision $importableRevision ) {
		# Construct a file
		$archiveName = $importableRevision->getArchiveName();
		if ( $archiveName ) {
			$this->logger->debug( __METHOD__ . "Importing archived file as $archiveName\n" );
			$file = OldLocalFile::newFromArchiveName( $importableRevision->getTitle(),
				RepoGroup::singleton()->getLocalRepo(), $archiveName );
		} else {
			$file = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
				->newFile( $importableRevision->getTitle() );
			$file->load( File::READ_LATEST );
			$this->logger->debug( __METHOD__ . 'Importing new file as ' . $file->getName() . "\n" );
			if ( $file->exists() && $file->getTimestamp() > $importableRevision->getTimestamp() ) {
				$archiveName = $importableRevision->getTimestamp() . '!' . $file->getName();
				$file = OldLocalFile::newFromArchiveName( $importableRevision->getTitle(),
					RepoGroup::singleton()->getLocalRepo(), $archiveName );
				$this->logger->debug( __METHOD__ . "File already exists; importing as $archiveName\n" );
			}
		}
		if ( !$file ) {
			$this->logger->debug( __METHOD__ . ': Bad file for ' . $importableRevision->getTitle() . "\n" );
			return $this->newNotOkStatus();
		}

		# Get the file source or download if necessary
		$source = $importableRevision->getFileSrc();
		$autoDeleteSource = $importableRevision->isTempSrc();
		if ( !strlen( $source ) ) {
			$source = $this->downloadSource( $importableRevision );
			$autoDeleteSource = true;
		}
		if ( !strlen( $source ) ) {
			$this->logger->debug( __METHOD__ . ": Could not fetch remote file.\n" );
			return $this->newNotOkStatus();
		}

		$tmpFile = new TempFSFile( $source );
		if ( $autoDeleteSource ) {
			$tmpFile->autocollect();
		}

		$sha1File = ltrim( sha1_file( $source ), '0' );
		$sha1 = $importableRevision->getSha1();
		if ( $sha1 && ( $sha1 !== $sha1File ) ) {
			$this->logger->debug( __METHOD__ . ": Corrupt file $source.\n" );
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
			$this->logger->debug( __METHOD__ . ": Successful\n" );
		} else {
			$this->logger->debug( __METHOD__ . ': failed: ' . $status->getHTML() . "\n" );
		}

		return $status;
	}

	/**
	 * @deprecated DO NOT CALL ME.
	 * This method was introduced when factoring (Importable)UploadRevisionImporter out of
	 * WikiRevision. It only has 1 use by the deprecated downloadSource method in WikiRevision.
	 * Do not use this in new code, it will be made private soon.
	 *
	 * @param ImportableUploadRevision $wikiRevision
	 *
	 * @return bool|string
	 */
	public function downloadSource( ImportableUploadRevision $wikiRevision ) {
		if ( !$this->enableUploads ) {
			return false;
		}

		$tempo = tempnam( wfTempDir(), 'download' );
		$f = fopen( $tempo, 'wb' );
		if ( !$f ) {
			$this->logger->debug( "IMPORT: couldn't write to temp file $tempo\n" );
			return false;
		}

		// @todo FIXME!
		$src = $wikiRevision->getSrc();
		$data = MediaWikiServices::getInstance()->getHttpRequestFactory()->
			get( $src, [], __METHOD__ );
		if ( !$data ) {
			$this->logger->debug( "IMPORT: couldn't fetch source $src\n" );
			fclose( $f );
			unlink( $tempo );
			return false;
		}

		fwrite( $f, $data );
		fclose( $f );

		return $tempo;
	}

}
