<?php

require_once( './includes/ProfilerStub.php' );

/**
 * To use a profiler, delete the line above and add something like this:
 *
 *   require_once( './includes/Profiler.php' );
 *   $wgProfiler = new Profiler;
 *
 * Or for a sampling profiler:
 *   if ( !mt_rand( 0, 100 ) ) {
 *       require_once( './includes/Profiler.php' );
 *       $wgProfiler = new Profiler;
 *   } else {
 *       require_once( './includes/ProfilerStub.php' );
 *   }
 * 
 * Configuration of the profiler output can be done in LocalSettings.php
 */

?>
