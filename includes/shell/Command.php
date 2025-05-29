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

use Exception;
use MediaWiki\Exception\ShellDisabledError;
use Profiler;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shellbox\Command\UnboxedCommand;
use Shellbox\Command\UnboxedExecutor;
use Shellbox\Command\UnboxedResult;
use Stringable;
use Wikimedia\ScopedCallback;

/**
 * Class used for executing shell commands
 *
 * @since 1.30
 */
class Command extends UnboxedCommand implements Stringable {
	private bool $everExecuted = false;

	/** @var string */
	private $method;

	protected LoggerInterface $logger;

	/**
	 * Don't call directly, instead use Shell::command()
	 *
	 * @param UnboxedExecutor $executor
	 * @throws ShellDisabledError
	 */
	public function __construct( UnboxedExecutor $executor ) {
		if ( Shell::isDisabled() ) {
			throw new ShellDisabledError();
		}
		parent::__construct( $executor );
		$this->setLogger( new NullLogger() );
	}

	/**
	 * Makes sure the programmer didn't forget to execute the command after all
	 */
	public function __destruct() {
		if ( !$this->everExecuted ) {
			$context = [ 'command' => $this->getCommandString() ];
			$message = __CLASS__ . " was instantiated, but execute() was never called.";
			if ( $this->method ) {
				$message .= ' Calling method: {method}.';
				$context['method'] = $this->method;
			}
			$message .= ' Command: {command}';
			$this->logger->warning( $message, $context );
		}
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
		if ( $this->executor ) {
			$this->executor->setLogger( $logger );
		}
	}

	/**
	 * Sets execution limits
	 *
	 * @param array $limits Associative array of limits. Keys (all optional):
	 *   filesize (for ulimit -f), memory, time, walltime.
	 * @return $this
	 */
	public function limits( array $limits ): Command {
		if ( !isset( $limits['walltime'] ) && isset( $limits['time'] ) ) {
			// Emulate the behavior of old wfShellExec() where walltime fell back on time
			// if the latter was overridden and the former wasn't
			$limits['walltime'] = $limits['time'];
		}
		if ( isset( $limits['filesize'] ) ) {
			$this->fileSizeLimit( $limits['filesize'] * 1024 );
		}
		if ( isset( $limits['memory'] ) ) {
			$this->memoryLimit( $limits['memory'] * 1024 );
		}
		if ( isset( $limits['time'] ) ) {
			$this->cpuTimeLimit( $limits['time'] );
		}
		if ( isset( $limits['walltime'] ) ) {
			$this->wallTimeLimit( $limits['walltime'] );
		}

		return $this;
	}

	/**
	 * Sets calling function for profiler. By default, the caller for execute() will be used.
	 *
	 * @param string $method
	 * @return $this
	 */
	public function profileMethod( string $method ): Command {
		$this->method = $method;

		return $this;
	}

	/**
	 * Sends the provided input to the command. Defaults to an empty string.
	 * If you want to pass stdin through to the command instead, use
	 * passStdin().
	 *
	 * @param string $inputString
	 * @return $this
	 */
	public function input( string $inputString ): Command {
		return $this->stdin( $inputString );
	}

	/**
	 * Set restrictions for this request, overwriting any previously set restrictions.
	 *
	 * Add the "no network" restriction:
	 * @code
	 * 	$command->restrict( Shell::RESTRICT_DEFAULT | Shell::NO_NETWORK );
	 * @endcode
	 *
	 * Allow LocalSettings.php access:
	 * @code
	 * 	$command->restrict( Shell::RESTRICT_DEFAULT & ~Shell::NO_LOCALSETTINGS );
	 * @endcode
	 *
	 * Disable all restrictions:
	 * @code
	 *  $command->restrict( Shell::RESTRICT_NONE );
	 * @endcode
	 *
	 * @deprecated since 1.36 Set the options using their separate accessors
	 *
	 * @since 1.31
	 * @param int $restrictions
	 * @return $this
	 */
	public function restrict( int $restrictions ): Command {
		$this->privateUserNamespace( (bool)( $restrictions & Shell::NO_ROOT ) );
		$this->firejailDefaultSeccomp( (bool)( $restrictions & Shell::SECCOMP ) );
		$this->noNewPrivs( (bool)( $restrictions & Shell::SECCOMP ) );
		$this->privateDev( (bool)( $restrictions & Shell::PRIVATE_DEV ) );
		$this->disableNetwork( (bool)( $restrictions & Shell::NO_NETWORK ) );
		if ( $restrictions & Shell::NO_EXECVE ) {
			$this->disabledSyscalls( [ 'execve' ] );
		} else {
			$this->disabledSyscalls( [] );
		}
		if ( $restrictions & Shell::NO_LOCALSETTINGS ) {
			$this->disallowedPaths( [ realpath( MW_CONFIG_FILE ) ] );
		} else {
			$this->disallowedPaths( [] );
		}
		if ( $restrictions === 0 ) {
			$this->disableSandbox();
		}

		return $this;
	}

	/**
	 * Executes command. Afterwards, getExitCode() and getOutput() can be used to access execution
	 * results.
	 *
	 * @return UnboxedResult
	 * @throws Exception
	 */
	public function execute(): UnboxedResult {
		$this->everExecuted = true;
		$profileMethod = $this->method ?: wfGetCaller();
		$scoped = Profiler::instance()->scopedProfileIn( __FUNCTION__ . '-' . $profileMethod );
		$result = parent::execute();
		ScopedCallback::consume( $scoped );
		return $result;
	}

	/**
	 * Returns the final command line before environment/limiting, etc are applied.
	 * Use string conversion only for debugging, don't try to pass this to
	 * some other execution medium.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return '#Command: ' . $this->getCommandString();
	}
}
