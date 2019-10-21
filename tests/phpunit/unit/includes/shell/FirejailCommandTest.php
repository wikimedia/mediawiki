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

use MediaWiki\Shell\FirejailCommand;
use MediaWiki\Shell\Shell;
use Wikimedia\TestingAccessWrapper;

class FirejailCommandTest extends MediaWikiUnitTestCase {

	public function provideBuildFinalCommand() {
		global $IP;
		// phpcs:ignore Generic.Files.LineLength
		$env = "'MW_INCLUDE_STDERR=;MW_CPU_LIMIT=180; MW_CGROUP='\'''\''; MW_MEM_LIMIT=307200; MW_FILE_SIZE_LIMIT=102400; MW_WALL_CLOCK_LIMIT=180; MW_USE_LOG_PIPE=yes'";
		$limit = "/bin/bash '$IP/includes/shell/limit.sh'";
		$profile = "--profile=$IP/includes/shell/firejail.profile";
		$blacklist = '--blacklist=' . realpath( MW_CONFIG_FILE );
		$default = "$blacklist --noroot --seccomp --private-dev";
		return [
			[
				'No restrictions',
				'ls', 0, "$limit ''\''ls'\''' $env"
			],
			[
				'default restriction',
				'ls', Shell::RESTRICT_DEFAULT,
				"$limit 'firejail --quiet $profile $default -- '\''ls'\''' $env"
			],
			[
				'no network',
				'ls', Shell::NO_NETWORK,
				"$limit 'firejail --quiet $profile --net=none -- '\''ls'\''' $env"
			],
			[
				'default restriction & no network',
				'ls', Shell::RESTRICT_DEFAULT | Shell::NO_NETWORK,
				"$limit 'firejail --quiet $profile $default --net=none -- '\''ls'\''' $env"
			],
			[
				'seccomp',
				'ls', Shell::SECCOMP,
				"$limit 'firejail --quiet $profile --seccomp -- '\''ls'\''' $env"
			],
			[
				'seccomp & no execve',
				'ls', Shell::SECCOMP | Shell::NO_EXECVE,
				"$limit 'firejail --quiet $profile --shell=none --seccomp=execve -- '\''ls'\''' $env"
			],
		];
	}

	/**
	 * @covers \MediaWiki\Shell\FirejailCommand::buildFinalCommand()
	 * @dataProvider provideBuildFinalCommand
	 */
	public function testBuildFinalCommand( $desc, $params, $flags, $expected ) {
		$command = new FirejailCommand( 'firejail' );
		$command
			->params( $params )
			->restrict( $flags );
		$wrapper = TestingAccessWrapper::newFromObject( $command );
		$output = $wrapper->buildFinalCommand( $wrapper->command );
		$this->assertEquals( $expected, $output[0], $desc );
	}

}
