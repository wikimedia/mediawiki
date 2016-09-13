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
 * @license GPL-2.0+
 */
namespace MediaWiki\Linker;

use FileRepo;
use RepoGroup;
use Title;

/**
 * Lookup for files, which are special!
 *
 * @since 1.28
 */
class FileExistenceLookup implements LinkTargetExistenceLookup {

	/**
	 * @var Title[]
	 */
	private $queue = [];

	private $found = [];

	public function add( LinkTarget $linkTarget ) {
		$this->queue[] = Title::newFromLinkTarget( $linkTarget );
		return self::HANDLE_ONLY;
	}

	public function lookup() {
		$repoGroup = RepoGroup::singleton();
		$found = $repoGroup->findFiles( $this->queue, FileRepo::NAME_AND_TIME_ONLY );
		$this->queue = [];
		// This is a mapping of dbkey => [ 'title' => ..., 'timestamp' => '...' ]
		// but all we care about is the dbkey, so get rid of the rest.
		foreach ( $found as $name => &$info ) {
			$info = true;
		}

		unset( $info );
		$this->found += $found;
	}

	public function exists( LinkTarget $linkTarget ) {
		return isset( $this->found[$linkTarget->getDBkey()] );
	}
}
