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
use Shellbox\Command\BoxedCommand;
use Shellbox\Command\RemoteBoxedExecutor;
use Shellbox\Shellbox;

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
	 * @var string|bool|null
	 */
	private $firejail;

	/** @var bool */
	private $useAllUsers;

	/** @var ShellboxClientFactory */
	private $shellboxClientFactory;

	/**
	 * @param ShellboxClientFactory $shellboxClientFactory
	 * @param array $limits See {@see Command::limits()}
	 * @param string|bool $cgroup See {@see Command::cgroup()}
	 * @param string|bool $restrictionMethod
	 */
	public function __construct( ShellboxClientFactory $shellboxClientFactory,
		array $limits, $cgroup, $restrictionMethod
	) {
		$this->shellboxClientFactory = $shellboxClientFactory;
		$this->limits = $limits;
		$this->cgroup = $cgroup;
		if ( $restrictionMethod === 'autodetect' ) {
			// On Linux systems check for firejail
			if ( PHP_OS === 'Linux' && $this->findFirejail() ) {
				$this->restrictionMethod = 'firejail';
			} else {
				$this->restrictionMethod = false;
			}
		} else {
			$this->restrictionMethod = $restrictionMethod;
		}
		$this->setLogger( new NullLogger() );
	}

	/**
	 * @return bool|string
	 */
	protected function findFirejail() {
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
	public function logStderr( bool $yesno = true ): void {
		$this->doLogStderr = $yesno;
	}

	/**
	 * Get the options which will be used for local unboxed execution.
	 * Shellbox should be configured to act in an approximately backwards
	 * compatible way, equivalent to the pre-Shellbox MediaWiki shell classes.
	 *
	 * @return array
	 */
	private function getLocalShellboxOptions() {
		$options = [
			'tempDir' => wfTempDir(),
			'useBashWrapper' => file_exists( '/bin/bash' ),
			'cgroup' => $this->cgroup
		];
		if ( $this->restrictionMethod === 'firejail' ) {
			$firejailPath = $this->findFirejail();
			if ( !$firejailPath ) {
				throw new \RuntimeException( 'firejail is enabled, but cannot be found' );
			}
			$options['useFirejail'] = true;
			$options['firejailPath'] = $firejailPath;
			$options['firejailProfile'] = __DIR__ . '/firejail.profile';
		}
		return $options;
	}

	/**
	 * Instantiates a new Command
	 *
	 * @return Command
	 */
	public function create(): Command {
		$allUsers = false;
		if ( $this->restrictionMethod === 'firejail' ) {
			if ( $this->useAllUsers === null ) {
				global $IP;
				// In case people are doing funny things with symlinks
				// or relative paths, resolve them all.
				$realIP = realpath( $IP );
				$currentUser = posix_getpwuid( posix_geteuid() );
				$this->useAllUsers = ( strpos( $realIP, '/home/' ) === 0 )
					&& ( strpos( $realIP, $currentUser['dir'] ) !== 0 );
				if ( $this->useAllUsers ) {
					$this->logger->warning( 'firejail: MediaWiki is located ' .
						'in a home directory that does not belong to the ' .
						'current user, so allowing access to all home ' .
						'directories (--allusers)' );
				}
			}
			$allUsers = $this->useAllUsers;
		}
		$executor = Shellbox::createUnboxedExecutor(
			$this->getLocalShellboxOptions(), $this->logger );

		$command = new Command( $executor );
		$command->setLogger( $this->logger );
		if ( $allUsers ) {
			$command->allowPath( '/home' );
		}
		return $command
			->limits( $this->limits )
			->logStderr( $this->doLogStderr );
	}

	/**
	 * Instantiates a new BoxedCommand.
	 *
	 * @return BoxedCommand
	 */
	public function createBoxed(): BoxedCommand {
		if ( $this->shellboxClientFactory->isEnabled() ) {
			$client = $this->shellboxClientFactory->getClient( [
				'timeout' => $this->limits['walltime'] + 1
			] );
			$executor = new RemoteBoxedExecutor( $client );
			$executor->setLogger( $this->logger );
		} else {
			$executor = Shellbox::createBoxedExecutor(
				$this->getLocalShellboxOptions(),
				$this->logger );
		}
		return $executor->createCommand()
			->cpuTimeLimit( $this->limits['time'] )
			->wallTimeLimit( $this->limits['walltime'] )
			->memoryLimit( $this->limits['memory'] * 1024 )
			->fileSizeLimit( $this->limits['filesize'] * 1024 )
			->logStderr( $this->doLogStderr );
	}
}
