<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace MediaWiki\Shell;

use RuntimeException;

/**
 * Restricts execution of shell commands using firejail
 *
 * @see https://firejail.wordpress.com/
 * @since 1.31
 */
class FirejailCommand extends Command {

	/**
	 * @var string Path to firejail
	 */
	private $firejail;

	/**
	 * @var string[]
	 */
	private $whitelistedPaths = [];

	/**
	 * @param string $firejail Path to firejail
	 */
	public function __construct( $firejail ) {
		parent::__construct();
		$this->firejail = $firejail;
	}

	/**
	 * @inheritDoc
	 */
	public function whitelistPaths( array $paths ): Command {
		$this->whitelistedPaths = array_merge( $this->whitelistedPaths, $paths );
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	protected function buildFinalCommand( $command ) {
		// If there are no restrictions, don't use firejail
		if ( $this->restrictions === 0 ) {
			$splitCommand = explode( ' ', $command, 2 );
			$this->logger->debug(
				"firejail: Command {$splitCommand[0]} {params} has no restrictions",
				[ 'params' => $splitCommand[1] ?? '' ]
			);
			return parent::buildFinalCommand( $command );
		}

		if ( $this->firejail === false ) {
			throw new RuntimeException( 'firejail is enabled, but cannot be found' );
		}
		// quiet has to come first to prevent firejail from adding
		// any output.
		$cmd = [ $this->firejail, '--quiet' ];
		// Use a profile that allows people to add local overrides
		// if their system is setup in an incompatible manner. Also it
		// prevents any default profiles from running.
		// FIXME: Doesn't actually override command-line switches?
		$cmd[] = '--profile=' . __DIR__ . '/firejail.profile';

		// By default firejail hides all other user directories, so if
		// MediaWiki is inside a home directory (/home) but not the
		// current user's home directory, pass --allusers to whitelist
		// the home directories again.
		static $useAllUsers = null;
		if ( $useAllUsers === null ) {
			global $IP;
			// In case people are doing funny things with symlinks
			// or relative paths, resolve them all.
			$realIP = realpath( $IP );
			$currentUser = posix_getpwuid( posix_geteuid() );
			$useAllUsers = ( strpos( $realIP, '/home/' ) === 0 )
				&& ( strpos( $realIP, $currentUser['dir'] ) !== 0 );
			if ( $useAllUsers ) {
				$this->logger->warning( 'firejail: MediaWiki is located ' .
					'in a home directory that does not belong to the ' .
					'current user, so allowing access to all home ' .
					'directories (--allusers)' );
			}
		}

		if ( $useAllUsers ) {
			$cmd[] = '--allusers';
		}

		if ( $this->whitelistedPaths ) {
			// Always whitelist limit.sh
			$cmd[] = '--whitelist=' . __DIR__ . '/limit.sh';
			foreach ( $this->whitelistedPaths as $whitelistedPath ) {
				$cmd[] = "--whitelist={$whitelistedPath}";
			}
		}

		if ( $this->hasRestriction( Shell::NO_LOCALSETTINGS ) ) {
			$cmd[] = '--blacklist=' . realpath( MW_CONFIG_FILE );
		}

		if ( $this->hasRestriction( Shell::NO_ROOT ) ) {
			$cmd[] = '--noroot';
		}

		$useSeccomp = $this->hasRestriction( Shell::SECCOMP );
		$extraSeccomp = [];

		if ( $this->hasRestriction( Shell::NO_EXECVE ) ) {
			$extraSeccomp[] = 'execve';
			// Normally firejail will run commands in a bash shell,
			// but that won't work if we ban the execve syscall, so
			// run the command without a shell.
			$cmd[] = '--shell=none';
		}

		if ( $useSeccomp ) {
			$seccomp = '--seccomp';
			if ( $extraSeccomp ) {
				// The "@default" seccomp group will always be enabled
				$seccomp .= '=' . implode( ',', $extraSeccomp );
			}
			$cmd[] = $seccomp;
		}

		if ( $this->hasRestriction( Shell::PRIVATE_DEV ) ) {
			$cmd[] = '--private-dev';
		}

		if ( $this->hasRestriction( Shell::NO_NETWORK ) ) {
			$cmd[] = '--net=none';
		}

		$builtCmd = implode( ' ', $cmd );

		// Prefix the firejail command in front of the wanted command
		return parent::buildFinalCommand( "$builtCmd -- {$command}" );
	}

}
