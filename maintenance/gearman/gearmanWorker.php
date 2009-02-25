<?php

$optionsWithArgs = array( 'fake-job' );
require( dirname(__FILE__).'/../commandLine.inc' );
require( dirname(__FILE__).'/gearman.inc' );

if ( !$args ) {
	$args = array( 'localhost' );
}

if ( isset( $options['fake-job'] ) ) {
	$params = unserialize( $options['fake-job'] );
	MWGearmanJob::runNoSwitch( $params );
}

$worker = new NonScaryGearmanWorker( $args );
$worker->addAbility( 'mw_job' );
$worker->beginWork();

