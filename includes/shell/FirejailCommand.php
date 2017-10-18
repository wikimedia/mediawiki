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

/**
 * Restricts execution of shell commands using firejail
 *
 * @see https://firejail.wordpress.com/
 * @since 1.31
 */
class FirejailCommand extends Command {

	/**
	 * Whether firejail can be used
	 *
	 * @return bool
	 */
	public static function isUsable() {
		return is_executable( '/usr/bin/firejail' );
	}

	protected function buildFinalCommand() {
		$cmd = 'firejail --quiet --noprofile ';
		foreach ( $this->env as $k => $v ) {
			$cmd .= "--env=$k=" . escapeshellarg( $v );
		}


		if ( $this->cgroup !== false ) {
			$cmd .= " --cgroup={$this->cgroup}/tasks";
		}

		$time = intval( $this->limits['time'] );
		$wallTime = intval( $this->limits['walltime'] );
		$mem = intval( $this->limits['memory'] );
		$filesize = intval( $this->limits['filesize'] );

		/*if ( $mem > 0 ) {
			$cmd .= " --rlimit-as=$mem";
		}*/

		if ( $filesize > 0 ) {
			$bytes = $filesize * 1024;
			$cmd .= " --rlimit-fsize=$bytes";
		}

		$fullCommand = "$cmd {$this->command}";

		// todo time limits?
		if ( $this->useStderr ) {
			$fullCommand .= " 2>&1";
			$this->useLogPipe = false;
		}

		return $fullCommand;
	}

}