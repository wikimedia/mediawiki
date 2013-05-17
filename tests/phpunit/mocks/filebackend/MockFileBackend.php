<?php
/**
 * Simulation (mock) of a backend storage.
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
 * @ingroup FileBackend
 * @author Antoine Musso <hashar@free.fr>
 */

/**
 * Class simulating a backend store.
 *
 * @ingroup FileBackend
 * @since 1.22
 */
class MockFileBackend extends FileBackendStore {

	protected $mocked = array();

	/** Poor man debugging */
	protected function debug( $msg = '' ) {
		wfDebug( wfGetCaller() . "$msg\n" );
	}

	public function isPathUsableInternal( $storagePath ) {
		return true;
	}

	protected function doCreateInternal( array $params ) {
		if ( isset( $params['content'] ) ) {
			$content = $params['content'];
		} else {
			$content = 'Default mocked file content';
		}
		$this->debug( serialize( $params ) );
		$dst = $params['dst'];
		$this->mocked[$dst] = $content;
		return Status::newGood();
	}

	protected function doStoreInternal( array $params ) {
		$this->debug( serialize( $params ) );
		return $this->doCreateInternal( $params );
	}

	protected function doCopyInternal( array $params ) {
		$this->debug( serialize( $params ) );
		$src = $params['src'];
		$dst = $params['dst'];
		$this->mocked[$dst] = $this->mocked[$src];
		return Status::newGood();
	}

	protected function doDeleteInternal( array $params ) {
		$this->debug( serialize( $params ) );
		$src = $params['src'];
		unset( $this->mocked[$src] );
		return Status::newGood();
	}

	protected function doGetFileStat( array $params ) {
		$src = $params['src'];
		if ( array_key_exists( $src, $this->mocked ) ) {
			$this->debug( "('$src') found" );
			return array(
				'mtime' => wfTimestamp( TS_MW ),
				'size' => strlen( $this->mocked[$src] ),
				# No sha1, stat does not need it.
			);
		} else {
			$this->debug( "('$src') not found" );
			return false;
		}
	}

	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = array(); // (path => MockFSFile)

		$this->debug( '(' . serialize( $params ) . ')' );
		foreach ( $params['srcs'] as $src ) {
			$tmpFiles[$src] = new MockFSFile(
				wfTempDir() . '/' . wfRandomString( 32 )
			);
		}
		return $tmpFiles;
	}

	protected function doDirectoryExists( $container, $dir, array $params ) {
		$this->debug();
		return true;
	}

	public function getDirectoryListInternal( $container, $dir, array $params ) {
		$this->debug();
		return array();
	}

	public function getFileListInternal( $container, $dir, array $params ) {
		$this->debug();
		return array();
	}

	protected function directoriesAreVirtual() {
		$this->debug();
		return true;
	}
}
