<?php
/**
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
 * @ingroup FileBackend
 */

/**
 * Wrapper around RecursiveDirectoryIterator/DirectoryIterator that
 * catches exception or does any custom behavoir that we may want.
 * Do not use this class from places outside FSFileBackend.
 *
 * @ingroup FileBackend
 */
abstract class FSFileBackendList implements Iterator {
	/** @var Iterator */
	protected $iter;

	/** @var int */
	protected $suffixStart;

	/** @var int */
	protected $pos = 0;

	/** @var array */
	protected $params = [];

	/**
	 * @param string $dir File system directory
	 * @param array $params
	 */
	public function __construct( $dir, array $params ) {
		$path = realpath( $dir ); // normalize
		if ( $path === false ) {
			$path = $dir;
		}
		$this->suffixStart = strlen( $path ) + 1; // size of "path/to/dir/"
		$this->params = $params;

		try {
			$this->iter = $this->initIterator( $path );
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null; // bad permissions? deleted?
		}
	}

	/**
	 * Return an appropriate iterator object to wrap
	 *
	 * @param string $dir File system directory
	 * @return Iterator
	 */
	protected function initIterator( $dir ) {
		if ( !empty( $this->params['topOnly'] ) ) { // non-recursive
			# Get an iterator that will get direct sub-nodes
			return new DirectoryIterator( $dir );
		} else { // recursive
			# Get an iterator that will return leaf nodes (non-directories)
			# RecursiveDirectoryIterator extends FilesystemIterator.
			# FilesystemIterator::SKIP_DOTS default is inconsistent in PHP 5.3.x.
			$flags = FilesystemIterator::CURRENT_AS_SELF | FilesystemIterator::SKIP_DOTS;

			return new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir, $flags ),
				RecursiveIteratorIterator::CHILD_FIRST // include dirs
			);
		}
	}

	/**
	 * @see Iterator::key()
	 * @return int
	 */
	public function key() {
		return $this->pos;
	}

	/**
	 * @see Iterator::current()
	 * @return string|bool String or false
	 */
	public function current() {
		return $this->getRelPath( $this->iter->current()->getPathname() );
	}

	/**
	 * @see Iterator::next()
	 * @throws FileBackendError
	 */
	public function next() {
		try {
			$this->iter->next();
			$this->filterViaNext();
		} catch ( UnexpectedValueException $e ) { // bad permissions? deleted?
			throw new FileBackendError( "File iterator gave UnexpectedValueException." );
		}
		++$this->pos;
	}

	/**
	 * @see Iterator::rewind()
	 * @throws FileBackendError
	 */
	public function rewind() {
		$this->pos = 0;
		try {
			$this->iter->rewind();
			$this->filterViaNext();
		} catch ( UnexpectedValueException $e ) { // bad permissions? deleted?
			throw new FileBackendError( "File iterator gave UnexpectedValueException." );
		}
	}

	/**
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		return $this->iter && $this->iter->valid();
	}

	/**
	 * Filter out items by advancing to the next ones
	 */
	protected function filterViaNext() {
	}

	/**
	 * Return only the relative path and normalize slashes to FileBackend-style.
	 * Uses the "real path" since the suffix is based upon that.
	 *
	 * @param string $dir
	 * @return string
	 */
	protected function getRelPath( $dir ) {
		$path = realpath( $dir );
		if ( $path === false ) {
			$path = $dir;
		}

		return strtr( substr( $path, $this->suffixStart ), '\\', '/' );
	}
}
