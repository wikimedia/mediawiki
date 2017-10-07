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

	/**
	 * Constructor
	 *
	 * @param array $limits See {@see Command::limits()}
	 * @param string|bool $cgroup See {@see Command::cgroup()}
	 */
	public function __construct( array $limits, $cgroup ) {
		$this->limits = $limits;
		$this->cgroup = $cgroup;
		$this->setLogger( new NullLogger() );
	}

	/**
	 * Instantiates a new Command
	 *
	 * @return Command
	 */
	public function create() {
		$command = new Command();
		$command->setLogger( $this->logger );

		return $command
			->limits( $this->limits )
			->cgroup( $this->cgroup );
	}
}
