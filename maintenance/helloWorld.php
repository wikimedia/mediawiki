<?php

/**
 * To the extent possible under law,  I, Mark Hershberger, have waived all copyright and
 * related or neighboring rights to Hello World. This work is published from United States.
 * @copyright CC0 http://creativecommons.org/publicdomain/zero/1.0/
 * @author Mark A. Hershberger <mah@everybody.org>
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . "/Maintenance.php" );

class CommandLineInstaller extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addOption( 'name', 'Who to say Hello to', false, true);
	}

	public function execute() {
		$name = $this->getOption( 'name', 'World' );
		echo "Hello, $name!\n";
	}
}

wfRunMaintenance( "CommandLineInstaller" );

