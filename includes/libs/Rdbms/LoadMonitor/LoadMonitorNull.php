<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Rdbms;

/**
 * @ingroup Database
 */
class LoadMonitorNull extends LoadMonitor {
	public function scaleLoads( array &$weightByServer ) {
	}
}
