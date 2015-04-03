<?php

/**
 * To use a profiler, copy this file to StartProfiler.php and add:
 *  $wgProfiler['class'] = 'ProfilerXhprof';
 *
 * For output, set the 'output' key to an array of class names, one for each
 * output type you want the profiler to generate. For example:
 *  $wgProfiler['output'] = array( 'ProfilerOutputText' );
 *
 * The output classes available to you by default are ProfilerOutputDb,
 * ProfilerOutputDump, ProfilerOutputStats, ProfilerOutputText, and
 * ProfilerOutputUdp.
 *
 * ProfilerOutputStats outputs profiling data as StatsD metrics. It expects
 * that you have set the $wgStatsdServer configuration variable to the host (or
 * host:port) of your statsd server.
 *
 * ProfilerOutputText will output profiling data in the page body as a comment.
 * You can make the profiling data in HTML render as part of the page content
 * by setting the 'visible' configuration flag:
 *  $wgProfiler['visible'] = true;
 *
 * 'ProfilerOutputDb' expects a database table that can be created by applying
 * maintenance/archives/patch-profiling.sql to your database.
 *
 * 'ProfilerOutputDump' expects a $wgProfiler['outputDir'] telling it where to
 * write dump files. The files produced are compatible with the XHProf gui.
 * For a rudimentary sampling profiler:
 *   $wgProfiler['class'] = 'ProfilerXhprof';
 *   $wgProfiler['output'] = array( 'ProfilerOutputDb' );
 *   $wgProfiler['sampling'] = 50; // one every 50 requests
 * This will use ProfilerStub for non-sampled cases.
 *
 * For performance, the profiler is always disabled for CLI scripts as they
 * could be long running and the data would accumulate. Use the '--profiler'
 * parameter of maintenance scripts to override this.
 */
