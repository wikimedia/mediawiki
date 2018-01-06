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

use ExecutableFinder;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Factory facilitating dependency injection for Command
 *
 * @since 1.30
 */
class CommandFactory {
	use LoggerAwareTrait;

	/** @var array */
	private $limits;

	/** @var string|bool */
	private $cgroup;

	/** @var bool */
	private $doLogStderr = false;

	/**
	 * @var string|bool
	 */
	private $restrictionMethod;

	/**
	 * @var string|bool
	 */
	private $firejail;

	/**
	 * Constructor
	 *
	 * @param array $limits See {@see Command::limits()}
	 * @param string|bool $cgroup See {@see Command::cgroup()}
	 * @param string|bool $restrictionMethod
	 */
	public function __construct( array $limits, $cgroup, $restrictionMethod ) {
		$this->limits = $limits;
		$this->cgroup = $cgroup;
		if ( $restrictionMethod === 'autodetect' ) {
			// On Linux systems check for firejail
			if ( PHP_OS === 'Linux' && $this->findFirejail() !== false ) {
				$this->restrictionMethod = 'firejail';
			} else {
				$this->restrictionMethod = false;
			}
		} else {
			$this->restrictionMethod = $restrictionMethod;
		}
		$this->setLogger( new NullLogger() );
	}

	private function findFirejail() {
		if ( $this->firejail === null ) {
			$this->firejail = ExecutableFinder::findInDefaultPaths( 'firejail' );
		}

		return $this->firejail;
	}

	/**
	 * When enabled, text sent to stderr will be logged with a level of 'error'.
	 *
	 * @param bool $yesno
	 * @see Command::logStderr
	 */
	public function logStderr( $yesno = true ) {
		$this->doLogStderr = $yesno;
	}

	/**
	 * Instantiates a new Command
	 *
	 * @return Command
	 */
	public function create() {
		if ( $this->restrictionMethod === 'firejail' ) {
			$command = new FirejailCommand( $this->findFirejail() );
			$command->restrict( Shell::RESTRICT_DEFAULT );
		} else {
			$command = new Command();
		}
		$command->setLogger( $this->logger );

		return $command
			->limits( $this->limits )
			->cgroup( $this->cgroup )
			->logStderr( $this->doLogStderr );
	}
}
