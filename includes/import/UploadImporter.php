<?php

use Psr\Log\LoggerInterface;

/**
 * @since 1.30
 */
class UploadImporter {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @return StatusValue
	 */
	private function newNotOkStatus() {
		$statusValue = new StatusValue();
		$statusValue->setOK( false );
		return $statusValue;
	}

	/**
	 * @since 1.30
	 *
	 * @param WikiRevisionUpload $wikiRevision
	 *
	 * @return StatusValue On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	public function import( WikiRevisionUpload $wikiRevision ) {
		# Construct a file
		$archiveName = $wikiRevision->getArchiveName();
		if ( $archiveName ) {
			$this->logger->debug( __METHOD__ . "Importing archived file as $archiveName\n" );
			$file = OldLocalFile::newFromArchiveName( $wikiRevision->getTitle(),
				RepoGroup::singleton()->getLocalRepo(), $archiveName );
		} else {
			$file = wfLocalFile( $wikiRevision->getTitle() );
			$file->load( File::READ_LATEST );
			$this->logger->debug( __METHOD__ . 'Importing new file as ' . $file->getName() . "\n" );
			if ( $file->exists() && $file->getTimestamp() > $wikiRevision->getTimestamp() ) {
				$archiveName = $file->getTimestamp() . '!' . $file->getName();
				$file = OldLocalFile::newFromArchiveName( $wikiRevision->getTitle(),
					RepoGroup::singleton()->getLocalRepo(), $archiveName );
				$this->logger->debug( __METHOD__ . "File already exists; importing as $archiveName\n" );
			}
		}
		if ( !$file ) {
			$this->logger->debug( __METHOD__ . ': Bad file for ' . $wikiRevision->getTitle() . "\n" );
			return $this->newNotOkStatus();
		}

		# Get the file source or download if necessary
		$source = $wikiRevision->getFileSrc();
		$autoDeleteSource = $wikiRevision->isTempSrc();
		if ( !strlen( $source ) ) {
			$source = $wikiRevision->downloadSource();
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
		$sha1 = $wikiRevision->getSha1();
		if ( $sha1 && ( $sha1 !== $sha1File ) ) {
			$this->logger->debug( __METHOD__ . ": Corrupt file $source.\n" );
			return $this->newNotOkStatus();
		}

		$user = $wikiRevision->getUserObj() ?: User::newFromName( $wikiRevision->getUser() );

		# Do the actual upload
		if ( $archiveName ) {
			$status = $file->uploadOld( $source, $archiveName,
				$wikiRevision->getTimestamp(), $wikiRevision->getComment(), $user );
		} else {
			$flags = 0;
			$status = $file->upload( $source, $wikiRevision->getComment(), $wikiRevision->getComment(),
				$flags, false, $wikiRevision->getTimestamp(), $user );
		}

		if ( $status->isGood() ) {
			$this->logger->debug( __METHOD__ . ": Successful\n" );
		} else {
			$this->logger->debug( __METHOD__ . ': failed: ' . $status->getHTML() . "\n" );
		}

		return $status;
	}

}
