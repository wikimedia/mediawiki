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

class FirejailCommandTest extends PHPUnit_Framework_TestCase {
	public function provideBuildFinalCommand() {
		global $IP;
		// @codingStandardsIgnoreStart
		$env = "'MW_INCLUDE_STDERR=;MW_CPU_LIMIT=180; MW_CGROUP='\'''\''; MW_MEM_LIMIT=307200; MW_FILE_SIZE_LIMIT=102400; MW_WALL_CLOCK_LIMIT=180; MW_USE_LOG_PIPE=yes'";
		// @codingStandardsIgnoreEnd
		$limit = "$IP/includes/shell/limit.sh";
		$profile = "--profile=$IP/includes/shell/firejail.profile";
		return [
			[
				'No restrictions',
				'ls', 0, "/bin/bash '$limit' ''\''ls'\''' $env"
			],
			[
				'no system access',
				'ls', Shell::NO_SYSTEM_ACCESS,
				"firejail --quiet $profile  -- /bin/bash '$limit' ''\''ls'\''' $env"
			],
			[
				'no network',
				'ls', Shell::NO_NETWORK,
				"firejail --quiet --noprofile --net=none  -- /bin/bash '$limit' ''\''ls'\''' $env"
			],
			[
				'no system access & no network',
				'ls', Shell::NO_SYSTEM_ACCESS | Shell::NO_NETWORK,
				"firejail --quiet $profile --net=none  -- /bin/bash '$limit' ''\''ls'\''' $env"
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
		$output = $wrapper->buildFinalCommand();
		$this->assertEquals( $expected, $output[0], $desc );
	}

}
