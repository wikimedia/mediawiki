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

declare( strict_types = 1 );

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

	/** @var string|null */
	private $stderr;

	/**
	 * @param int $exitCode
	 * @param string $stdout
	 * @param string|null $stderr
	 */
	public function __construct( int $exitCode, string $stdout, ?string $stderr ) {
		$this->exitCode = $exitCode;
		$this->stdout = $stdout;
		$this->stderr = $stderr;
	}

	/**
	 * Returns exit code of the process
	 *
	 * @return int
	 */
	public function getExitCode() : int {
		return $this->exitCode;
	}

	/**
	 * Returns stdout of the process
	 *
	 * @return string
	 */
	public function getStdout() : string {
		return $this->stdout;
	}

	/**
	 * Returns stderr of the process or null if the Command was configured to add stderr to stdout
	 * with includeStderr( true )
	 *
	 * @return string|null
	 */
	public function getStderr() : ?string {
		return $this->stderr;
	}
}
