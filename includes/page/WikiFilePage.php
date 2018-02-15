<?php
/**
 * Special handling for file pages.
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
 */

use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * Special handling for file pages
 *
 * @ingroup Media
 */
class WikiFilePage extends WikiPage {
	/** @var File */
	protected $mFile = false;
	/** @var LocalRepo */
	protected $mRepo = null;
	/** @var bool */
	protected $mFileLoaded = false;
	/** @var array */
	protected $mDupes = null;

	public function __construct( $title ) {
		parent::__construct( $title );
		$this->mDupes = null;
		$this->mRepo = null;
	}

	/**
	 * @param File $file
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

		$this->mFile = wfFindFile( $this->mTitle );
		if ( !$this->mFile ) {
			$this->mFile = wfLocalFile( $this->mTitle ); // always a File
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
		$this->mRedirectTarget = Title::makeTitle( NS_FILE, $to );
		return $this->mRedirectTarget;
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
	 * @return bool
	 */
	public function isRedirect() {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::isRedirect();
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
			$this->mDupes = [];
			return $this->mDupes;
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
	 * @return bool
	 */
	public function doPurge() {
		$this->loadFile();

		if ( $this->mFile->exists() ) {
			wfDebug( 'ImagePage::doPurge purging ' . $this->mFile->getName() . "\n" );
			DeferredUpdates::addUpdate(
				new HTMLCacheUpdate( $this->mTitle, 'imagelinks', 'file-purge' )
			);
		} else {
			wfDebug( 'ImagePage::doPurge no image for '
				. $this->mFile->getName() . "; limiting purge to cache only\n" );
		}

		// even if the file supposedly doesn't exist, force any cached information
		// to be updated (in case the cached information is wrong)

		// Purge current version and its thumbnails
		$this->mFile->purgeCache( [ 'forThumbRefresh' => true ] );

		// Purge the old versions and their thumbnails
		foreach ( $this->mFile->getHistory() as $oldFile ) {
			$oldFile->purgeCache( [ 'forThumbRefresh' => true ] );
		}

		if ( $this->mRepo ) {
			// Purge redirect cache
			$this->mRepo->invalidateImageRedirect( $this->mTitle );
		}

		return parent::doPurge();
	}

	/**
	 * Get the categories this file is a member of on the wiki where it was uploaded.
	 * For local files, this is the same as getCategories().
	 * For foreign API files (InstantCommons), this is not supported currently.
	 * Results will include hidden categories.
	 *
	 * @return TitleArray|Title[]
	 * @since 1.23
	 */
	public function getForeignCategories() {
		$this->loadFile();
		$title = $this->mTitle;
		$file = $this->mFile;

		if ( !$file instanceof LocalFile ) {
			wfDebug( __CLASS__ . '::' . __METHOD__ . " is not supported for this file\n" );
			return TitleArray::newFromResult( new FakeResultWrapper( [] ) );
		}

		/** @var LocalRepo $repo */
		$repo = $file->getRepo();
		$dbr = $repo->getReplicaDB();

		$res = $dbr->select(
			[ 'page', 'categorylinks' ],
			[
				'page_title' => 'cl_to',
				'page_namespace' => NS_CATEGORY,
			],
			[
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
			],
			__METHOD__,
			[],
			[ 'categorylinks' => [ 'INNER JOIN', 'page_id = cl_from' ] ]
		);

		return TitleArray::newFromResult( $res );
	}

	/**
	 * @since 1.28
	 * @return string
	 */
	public function getWikiDisplayName() {
		return $this->getFile()->getRepo()->getDisplayName();
	}

	/**
	 * @since 1.28
	 * @return string
	 */
	public function getSourceURL() {
		return $this->getFile()->getDescriptionUrl();
	}
}
