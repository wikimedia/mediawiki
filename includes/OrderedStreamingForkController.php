<?php

/**
 * Reads lines of work from an input stream and farms them out to multiple
 * child streams. Each child has exactly one piece of work in flight at a given
 * moment. Writes the result of child's work to an output stream. If numProcs
 * <= zero the work will be performed in process.
 *
 * This class amends ForkController with the requirement that the output is
 * produced in the same exact order as input values were.
 *
 * Currently used by CirrusSearch extension to implement CLI search script.
 *
 * @ingroup Maintenance
 * @since 1.30
 *
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
 */
class OrderedStreamingForkController extends ForkController {
	/** @var callable */
	protected $workCallback;
	/** @var resource */
	protected $input;
	/** @var resource */
	protected $output;
	/** @var int */
	protected $nextOutputId;
	/** @var string[] Int key indicates order, value is data */
	protected $delayedOutputData = [];

	/**
	 * @param int $numProcs The number of worker processes to fork
	 * @param callable $workCallback A callback to call in the child process
	 *  once for each line of work to process.
	 * @param resource $input A socket to read work lines from
	 * @param resource $output A socket to write the result of work to.
	 */
	public function __construct( $numProcs, $workCallback, $input, $output ) {
		parent::__construct( $numProcs );
		$this->workCallback = $workCallback;
		$this->input = $input;
		$this->output = $output;
	}

	/**
	 * @inheritDoc
	 */
	public function start() {
		if ( $this->procsToStart > 0 ) {
			$status = parent::start();
			if ( $status === 'child' ) {
				$this->consume();
			}
		} else {
			$status = 'parent';
			$this->consumeNoFork();
		}
		return $status;
	}

	/**
	 * @param int $numProcs
	 * @return string
	 */
	protected function forkWorkers( $numProcs ) {
		$this->prepareEnvironment();

		$childSockets = [];
		// Create the child processes
		for ( $i = 0; $i < $numProcs; $i++ ) {
			$sockets = stream_socket_pair( STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP );
			// Do the fork
			$pid = pcntl_fork();
			if ( $pid === -1 || $pid === false ) {
				echo "Error creating child processes\n";
				exit( 1 );
			}

			if ( !$pid ) {
				$this->initChild();
				$this->childNumber = $i;
				$this->input = $sockets[0];
				$this->output = $sockets[0];
				fclose( $sockets[1] );
				return 'child';
			} else {
				// This is the parent process
				$this->children[$pid] = true;
				fclose( $sockets[0] );
				$childSockets[] = $sockets[1];
			}
		}
		$this->feedChildren( $childSockets );
		foreach ( $childSockets as $socket ) {
			// if a child has already shutdown the sockets will be closed,
			// closing a second time would raise a warning.
			if ( is_resource( $socket ) ) {
				fclose( $socket );
			}
		}
		return 'parent';
	}

	/**
	 * Child worker process. Reads work from $this->input and writes the
	 * result of that work to $this->output when completed.
	 */
	protected function consume() {
		while ( !feof( $this->input ) ) {
			$line = trim( fgets( $this->input ) );
			if ( $line ) {
				list( $id, $data ) = json_decode( $line );
				$result = call_user_func( $this->workCallback, $data );
				fwrite( $this->output, json_encode( [ $id, $result ] ) . "\n" );
			}
		}
	}

	/**
	 * Special cased version of self::consume() when no forking occurs
	 */
	protected function consumeNoFork() {
		while ( !feof( $this->input ) ) {
			$data = fgets( $this->input );
			if ( substr( $data, -1 ) === "\n" ) {
				// Strip any final new line used to delimit lines of input.
				// The last line of input might not have it, though.
				$data = substr( $data, 0, -1 );
			}
			if ( $data === '' ) {
				continue;
			}
			$result = call_user_func( $this->workCallback, $data );
			fwrite( $this->output, "$result\n" );
		}
	}

	/**
	 * Reads lines of work from $this->input and farms them out to
	 * the provided socket.
	 *
	 * @param resource[] $sockets
	 */
	protected function feedChildren( array $sockets ) {
		$used = [];
		$id = 0;
		$this->nextOutputId = 0;

		while ( !feof( $this->input ) ) {
			$data = fgets( $this->input );
			if ( $used ) {
				do {
					$this->updateAvailableSockets( $sockets, $used, $sockets ? 0 : 5 );
				} while ( !$sockets );
			}
			if ( substr( $data, - 1 ) === "\n" ) {
				// Strip any final new line used to delimit lines of input.
				// The last line of input might not have it, though.
				$data = substr( $data, 0, -1 );
			}
			if ( $data === '' ) {
				continue;
			}
			$socket = array_pop( $sockets );
			fwrite( $socket, json_encode( [ $id++, $data ] ) . "\n" );
			$used[] = $socket;
		}
		while ( $used ) {
			$this->updateAvailableSockets( $sockets, $used, 5 );
		}
	}

	/**
	 * Moves sockets from $used to $sockets when they are available
	 * for more work
	 *
	 * @param resource[] &$sockets List of sockets that are waiting for work
	 * @param resource[] &$used List of sockets currently performing work
	 * @param int $timeout The number of seconds to block waiting. 0 for
	 *  non-blocking operation.
	 */
	protected function updateAvailableSockets( &$sockets, &$used, $timeout ) {
		$read = $used;
		$write = $except = [];
		stream_select( $read, $write, $except, $timeout );
		foreach ( $read as $socket ) {
			$line = fgets( $socket );
			list( $id, $data ) = json_decode( trim( $line ) );
			$this->receive( (int)$id, $data );
			$sockets[] = $socket;
			$idx = array_search( $socket, $used );
			unset( $used[$idx] );
		}
	}

	/**
	 * @param int $id
	 * @param string $data
	 */
	protected function receive( $id, $data ) {
		if ( $id !== $this->nextOutputId ) {
			$this->delayedOutputData[$id] = $data;
			return;
		}
		fwrite( $this->output, $data . "\n" );
		$this->nextOutputId = $id + 1;
		while ( isset( $this->delayedOutputData[$this->nextOutputId] ) ) {
			fwrite( $this->output, $this->delayedOutputData[$this->nextOutputId] . "\n" );
			unset( $this->delayedOutputData[$this->nextOutputId] );
			$this->nextOutputId++;
		}
	}
}
