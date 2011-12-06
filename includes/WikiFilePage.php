<?php
/**
 * Special handling for file pages
 *
 * @ingroup Media
 */
class WikiFilePage extends WikiPage {
	/**
	 * @var File
	 */
	protected $mFile = false; 				// !< File object
	protected $mRepo = null;			    // !<
	protected $mFileLoaded = false;		    // !<
	protected $mDupes = null;				// !<

	public function __construct( $title ) {
		parent::__construct( $title );
		$this->mDupes = null;
		$this->mRepo = null;
	}

	public function getActionOverrides() {
		return array( 'revert' => 'RevertFileAction' );
	}

	/**
	 * @param $file File:
	 */
	public function setFile( $file ) {
		$this->mFile = $file;
		$this->mFileLoaded = true;
	}

	/**
	 * @return bool
	 */
	protected function loadFile() {
		if ( $this->mFileLoaded ) {
			return true;
		}
		$this->mFileLoaded = true;

		$this->mFile = false;
		if ( !$this->mFile ) {
			$this->mFile = wfFindFile( $this->mTitle );
			if ( !$this->mFile ) {
				$this->mFile = wfLocalFile( $this->mTitle ); // always a File
			}
		}
		$this->mRepo = $this->mFile->getRepo();
		return true;
	}

	/**
	 * @return mixed|null|Title
	 */
	public function getRedirectTarget() {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::getRedirectTarget();
		}
		// Foreign image page
		$from = $this->mFile->getRedirected();
		$to = $this->mFile->getName();
		if ( $from == $to ) {
			return null;
		}
		return $this->mRedirectTarget = Title::makeTitle( NS_FILE, $to );
	}

	/**
	 * @return bool|mixed|Title
	 */
	public function followRedirect() {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::followRedirect();
		}
		$from = $this->mFile->getRedirected();
		$to = $this->mFile->getName();
		if ( $from == $to ) {
			return false;
		}
		return Title::makeTitle( NS_FILE, $to );
	}

	/**
	 * @param bool $text
	 * @return bool
	 */
	public function isRedirect( $text = false ) {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::isRedirect( $text );
		}

		return (bool)$this->mFile->getRedirected();
	}

	/**
	 * @return bool
	 */
	public function isLocal() {
		$this->loadFile();
		return $this->mFile->isLocal();
	}

	/**
	 * @return bool|File
	 */
	public function getFile() {
		$this->loadFile();
		return $this->mFile;
	}

	/**
	 * @return array|null
	 */
	public function getDuplicates() {
		$this->loadFile();
		if ( !is_null( $this->mDupes ) ) {
			return $this->mDupes;
		}
		$hash = $this->mFile->getSha1();
		if ( !( $hash ) ) {
			return $this->mDupes = array();
		}
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		// Remove duplicates with self and non matching file sizes
		$self = $this->mFile->getRepoName() . ':' . $this->mFile->getName();
		$size = $this->mFile->getSize();

		/**
		 * @var $file File
		 */
		foreach ( $dupes as $index => $file ) {
			$key = $file->getRepoName() . ':' . $file->getName();
			if ( $key == $self ) {
				unset( $dupes[$index] );
			}
			if ( $file->getSize() != $size ) {
				unset( $dupes[$index] );
			}
		}
		$this->mDupes = $dupes;
		return $this->mDupes;
	}

	/**
	 * Override handling of action=purge
	 */
	public function doPurge() {
		$this->loadFile();
		if ( $this->mFile->exists() ) {
			wfDebug( 'ImagePage::doPurge purging ' . $this->mFile->getName() . "\n" );
			$update = new HTMLCacheUpdate( $this->mTitle, 'imagelinks' );
			$update->doUpdate();
			$this->mFile->upgradeRow();
			$this->mFile->purgeCache( array( 'forRefresh' => true ) );
		} else {
			wfDebug( 'ImagePage::doPurge no image for ' . $this->mFile->getName() . "; limiting purge to cache only\n" );
			// even if the file supposedly doesn't exist, force any cached information
			// to be updated (in case the cached information is wrong)
			$this->mFile->purgeCache( array( 'forRefresh' => true ) );
		}
		return parent::doPurge();
	}
}
