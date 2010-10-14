<?php

require( 'Net/Gearman/Client.php' );
require( 'Net/Gearman/Worker.php' );

class MWGearmanJob extends Net_Gearman_Job_Common {
	function switchWiki( $wiki, $params ) {
		echo "Switching to $wiki\n";

		# Pretend that we have completed it right now, because the new process won't do it
		$this->complete( array( 'result' => true ) );
		socket_close( $this->conn );

		# Close some more sockets
		LBFactory::destroyInstance();
		global $wgMemc;
		$wgMemc->disconnect_all();

		# Find PHP
		$php = readlink( '/proc/' . posix_getpid() . '/exe' );
		
		# Run the worker script
		$args = array( $_SERVER['PHP_SELF'], 
			'--wiki', $wiki,
			'--fake-job', serialize( $params ) );
		$args = array_merge( $args, $GLOBALS['args'] );
		pcntl_exec( $php, $args, $_ENV );
		echo "Error running exec\n";
	}

	function run( $params ) {
		if ( wfWikiID() !== $params['wiki'] ) {
			$this->switchWiki( $params['wiki'], $params );
		}
		return self::runNoSwitch( $params );
	}

	static function runNoSwitch( $params ) {
		echo implode( ' ', $params ) . "\n";
		$title = Title::newFromText( $params['title'] );
		$mwJob = Job::factory( $params['command'], $title, $params['params'] );
		return $mwJob->run();
	}
}

class NonScaryGearmanWorker extends Net_Gearman_Worker {
	
	/**
	 * Copied from Net_Gearman_Worker but with the scary "run any PHP file in 
	 * the filesystem" feature removed.
	 */
	protected function doWork($socket) {
		Net_Gearman_Connection::send($socket, 'grab_job');

		$resp = array('function' => 'noop');
		while (count($resp) && $resp['function'] == 'noop') {
			$resp = Net_Gearman_Connection::blockingRead($socket);
		} 

		if (in_array($resp['function'], array('noop', 'no_job'))) {
			return false;
		}

		if ($resp['function'] != 'job_assign') {
			throw new Net_Gearman_Exception('Holy Cow! What are you doing?!');
		}

		$name   = $resp['data']['func'];
		$handle = $resp['data']['handle'];
		$arg    = array();

		if (isset($resp['data']['arg']) && 
			Net_Gearman_Connection::stringLength($resp['data']['arg'])) {
				$arg = json_decode($resp['data']['arg'], true);
		}

		### START MW DIFFERENT BIT
		if ( $name != 'mw_job' ) {
			throw new Net_Gearman_Job_Exception('Invalid function');
		}
		$job = new MWGearmanJob($socket, $handle);
		### END MW DIFFERENT BIT

		try {
			$this->start($handle, $name, $arg);
			$res = $job->run($arg); 
			if (!is_array($res)) {
				$res = array('result' => $res);
			}

			$job->complete($res);
			$this->complete($handle, $name, $res);
		} catch (Net_Gearman_Job_Exception $e) {
			$job->fail(); 
			$this->fail($handle, $name, $e); 
		}

		// Force the job's destructor to run
		$job = null;

		return true;
	}
}

