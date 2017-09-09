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

use Psr\Log\LoggerInterface;

/**
 * Factory facilitating dependency injection for Command
 *
 * @since 1.30
 */
class CommandFactory {
	/** @var LoggerInterface */
	private $logger;

	/** @var array */
	private $limits;

	private $cgroup;

	/**
	 * Constructor
	 *
	 * @param LoggerInterface $logger
	 * @param array $limits
	 * @param string|false $cgroup
	 */
	public function __construct( LoggerInterface $logger, array $limits, $cgroup ) {
		$this->logger = $logger;
		$this->limits = $limits;
		$this->cgroup = $cgroup;
	}

	/**
	 * Instantiates a new Command
	 *
	 * @return Command
	 */
	public function create() {
		$command = new Command( $this->logger );

		return $command
			->limits( $this->limits )
			->cGroup( $this->cgroup );
	}
}
