<?php

$optionsWithArgs = array( 'fake-job', 'procs' );
require( dirname( __FILE__ ) . '/../commandLine.inc' );
require( dirname( __FILE__ ) . '/gearman.inc' );

ini_set( 'memory_limit', '150M' );

if ( isset( $options['procs'] ) ) {
	$procs = $options['procs'];
	if ( $procs < 1 || $procs > 1000 ) {
		echo "Invalid number of processes, please specify a number between 1 and 1000\n";
		exit( 1 );
	}
	$fc = new ForkController( $procs, ForkController::RESTART_ON_ERROR );
	if ( $fc->start() != 'child' ) {
		exit( 0 );
	}
}

if ( !$args ) {
	$args = array( 'localhost' );
}

if ( isset( $options['fake-job'] ) ) {
	$params = unserialize( $options['fake-job'] );
	MWGearmanJob::runNoSwitch( $params );
}

$worker = new NonScaryGearmanWorker( $args );
$worker->addAbility( 'mw_job' );
$worker->beginWork( 'wfGearmanMonitor' );

function wfGearmanMonitor( $idle, $lastJob ) {
	static $lastSleep = 0;
	$interval = 5;
	$now = time();
	if ( $now - $lastSleep >= $interval ) {
		wfWaitForSlaves();
		$lastSleep = $now;
	}
	return false;
}
