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
 */

namespace MediaWiki\Shell;

/**
 * Returned by MediaWiki\Shell\Command::execute()
 *
 * @since 1.30
 */
class Result {
	/** @var int */
	private $exitCode;

	/** @var string */
	private $stdout;

	/**
	 * @param int $exitCode
	 * @param string $stdout
	 */
	public function __construct( $exitCode, $stdout ) {
		$this->exitCode = $exitCode;
		$this->stdout = $stdout;
	}

	/**
	 * Returns exit code of the process
	 *
	 * @return int
	 */
	public function getExitCode() {
		return $this->exitCode;
	}

	/**
	 * Returns stdout of the process
	 *
	 * @return string
	 */
	public function getStdout() {
		return $this->stdout;
	}
}
