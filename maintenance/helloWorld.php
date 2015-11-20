<?php

/**
 * To the extent possible under law,  I, Mark Hershberger, have waived all copyright
 * and related or neighboring rights to Hello World. This work is published from the
 * United States.
 *
 * @copyright © 2010 CC0 http://creativecommons.org/publicdomain/zero/1.0/
 * @author    Mark A. Hershberger <mah@everybody.org>
 * @ingroup   Maintenance
 *
 * See https://www.mediawiki.org/wiki/Manual:Writing_maintenance_scripts
 */

require_once __DIR__ . "/Maintenance.php";

class HelloWorld extends Maintenance {
	public function execute() {
		echo "Hello, World!\n";
	}
}

$maintClass = 'HelloWorld';

require_once RUN_MAINTENANCE_IF_MAIN;
