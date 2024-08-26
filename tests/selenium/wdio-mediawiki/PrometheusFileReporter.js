import { readdirSync, readFileSync, writeFileSync, rmSync } from 'fs';
import path from 'path';
import WDIOReporter from '@wdio/reporter';

/**
 * Clean a string into a valid Prometheus tag name.
 * Replaces all non-word characters with underscores and lower-cases the result.
 *
 * @param {string} name - Original tag name.
 * @return {string} A cleaned, lowercase string for Prometheus labels.
 */
const getValidPrometheusTagName = ( name ) => name.replace( /\W+/g, '_' ).toLowerCase();

/**
 * Formats a single Prometheus text format line.
 *
 * @param {string} name - Metric name (must already follow Prometheus naming conventions).
 * @param {number|string} value - The metric value.
 * @param {Object.<string, string>} [labels={}] - Optional key/value map of label names/values.
 * @return {string} A line in the Prometheus text‐based text format, e.g.
 *                   wdio_test_duration_seconds{project="core",name="test_name"} 12
 */
function formatMetric( name, value, labels = {} ) {
	const keys = Object.keys( labels );
	let labelsAsString = '';
	if ( keys.length > 0 ) {
		const parts = keys.map( ( key ) => `${ key }="${ labels[ key ] }"` );
		labelsAsString = `{${ parts.join( ', ' ) }}`;
	}
	return `${ name }${ labelsAsString } ${ value }`;
}

/**
 * Sums up the durations of all suites to produce a spec-level total.
 *
 * @param {Array.<{duration: number}>} suiteMetrics - Array of suites with duration.
 * @return {number} Total duration in seconds across all suites.
 */
function getSpecDuration( suiteMetrics ) {
	let specDuration = 0;
	for ( const { duration } of suiteMetrics ) {
		specDuration += duration;
	}
	return specDuration.toFixed( 3 );
}

/**
 * The PrometheusFileReporter writes webdriver.io test data
 * to a file following the Prometheus text based format:
 * https://github.com/prometheus/docs/blob/main/content/docs/instrumenting/exposition_formats.md
 *
 * The reporter collects data on a project and individual tests level.
 * Metrics are reported as Gauges to be consumed by a Prometheus PushGateway.
 * We use Gauges since counters will just be replaced in the Pushgateway.
 *
 * The Reporter is a custom reporter built upon WDIOReporter.
 *
 */
class PrometheusFileReporter extends WDIOReporter {
	constructor( options = {} ) {
		super( { ...options } );
		this.labels = {};
		// Make sure the tags is ok for Prometheus.
		for ( const [ tagName, tagValue ] of Object.entries( options.tags || {} ) ) {
			const cleanKey = getValidPrometheusTagName( tagName );
			const cleanValue = tagValue !== undefined ? getValidPrometheusTagName( tagValue ) : '';
			this.labels[ cleanKey ] = cleanValue;
		}
		this.testMetrics = {};
		this.suiteMetrics = [];
		this.outputDir = options.outputDir;
		this.getFileName = options.outputFileName;
		this.spec = {
			passed: 0,
			failed: 0,
			skipped: 0,
			retries: 0,
			totalTests: 0
		};
	}

	onSuiteEnd( suite ) {
		// We collect total suite time to make it easier to get
		// correct project run time.
		if ( suite.start && suite.end ) {
			const durationSec = ( suite.end - suite.start ) / 1000;
			const suiteName = getValidPrometheusTagName( suite.title );
			this.suiteMetrics.push( { name: suiteName, duration: durationSec } );
		}
	}

	onTestStart( test ) {
		if ( !this.testMetrics[ test.uid ] ) {
			this.testMetrics[ test.uid ] = {
				name: getValidPrometheusTagName( test.title ),
				suite: test.parent ? getValidPrometheusTagName( test.parent ) : 'unknown',
				passed: 0,
				failed: 0,
				skipped: 0,
				retries: 0,
				maxDuration: 0
			};
		}
	}

	onTestPass( test ) {
		const testDurationInSeconds = ( test.end - test.start ) / 1000;
		const myTest = this.testMetrics[ test.uid ];
		myTest.passed++;
		myTest.maxDuration = Math.max( myTest.maxDuration, testDurationInSeconds );
		this.spec.passed++;
		this.spec.totalTests++;
	}

	onTestFail( test ) {
		const testDurationInSeconds = ( test.end - test.start ) / 1000;
		const myTest = this.testMetrics[ test.uid ];
		myTest.failed++;
		myTest.maxDuration = Math.max( myTest.maxDuration, testDurationInSeconds );
		this.spec.failed++;
		this.spec.totalTests++;
	}

	onTestSkip( test ) {
		if ( !this.testMetrics[ test.uid ] ) {
			this.testMetrics[ test.uid ] = {
				name: getValidPrometheusTagName( test.title ),
				suite: test.parent ? getValidPrometheusTagName( test.parent ) : 'unknown',
				passed: 0,
				failed: 0,
				skipped: 1,
				retries: 0,
				maxDuration: 0
			};
		}
		this.spec.skipped++;
		this.spec.totalTests++;
	}

	onTestRetry( test ) {
		this.testMetrics[ test.uid ].retries++;
		this.spec.retries++;
	}

	onRunnerEnd( runnerStats ) {
		const labels = this.labels;
		const specMetrics = this.spec;
		const workerId = runnerStats.cid;

		specMetrics.duration = getSpecDuration( this.suiteMetrics );
		specMetrics.labels = labels;
		specMetrics.tests = Object.values( this.testMetrics );
		const outputPath = path.join( this.outputDir, 'specs-' + workerId + '.json' );
		writeFileSync( outputPath, JSON.stringify( specMetrics ), { encoding: 'utf-8' } );
	}
}

function writeAllProjectMetrics( metricsDir, fileName ) {
	const projectMetrics = {
		passed: 0,
		failed: 0,
		skipped: 0,
		retries: 0,
		totalTests: 0,
		duration: 0
	};
	const tests = [];

	for ( const file of readdirSync( metricsDir ) ) {
		if ( !file.startsWith( 'specs-' ) || !file.endsWith( '.json' ) ) {
			continue;
		}
		const filePath = path.join( metricsDir, file );
		const data = JSON.parse( readFileSync( filePath, 'utf-8' ) );

		// We have read the raw data, renmove it since we only need the .prom
		rmSync( filePath );

		projectMetrics.passed += data.passed;
		projectMetrics.failed += data.failed;
		projectMetrics.skipped += data.skipped;
		projectMetrics.retries += data.retries;
		projectMetrics.totalTests += data.totalTests;
		projectMetrics.duration += Number( data.duration );
		projectMetrics.labels = data.labels;
		tests.push( ...data.tests );
	}

	projectMetrics.duration.toFixed( 3 );

	const lines = [];
	const labels = projectMetrics.labels;
	// Add Project metrics
	lines.push( '# HELP wdio_project_duration_seconds Total duration of all test suites per project' );
	lines.push( '# TYPE wdio_project_duration_seconds gauge' );
	lines.push( formatMetric( 'wdio_project_duration_seconds', projectMetrics.duration, { ...labels } ) );

	lines.push( '# HELP wdio_project_passed Number of tests passed per project' );
	lines.push( '# TYPE wdio_project_passed gauge' );
	lines.push( formatMetric( 'wdio_project_passed', projectMetrics.passed, { ...labels } ) );

	lines.push( '# HELP wdio_project_failed Number of tests failed per project' );
	lines.push( '# TYPE wdio_project_failed gauge' );
	lines.push( formatMetric( 'wdio_project_failed', projectMetrics.failed, { ...labels } ) );

	lines.push( '# HELP wdio_project_retries Number of test retries per project' );
	lines.push( '# TYPE wdio_project_retries gauge' );
	lines.push( formatMetric( 'wdio_project_retries', projectMetrics.retries, { ...labels } ) );

	lines.push( '# HELP wdio_project_skipped Number of tests skipped per project' );
	lines.push( '# TYPE wdio_project_skipped gauge' );
	lines.push( formatMetric( 'wdio_project_skipped', projectMetrics.skipped, { ...labels } ) );

	lines.push( '# HELP wdio_project_tests Total number of tests per project' );
	lines.push( '# TYPE wdio_project_tests gauge' );
	lines.push( formatMetric( 'wdio_project_tests', projectMetrics.totalTests, { ...labels } ) );

	// Add test metrics

	let addMetaData = true;
	for ( const test of tests ) {
		const testLabels = { ...labels, test: test.name };

		if ( addMetaData ) {
			lines.push( '# HELP wdio_test_passed Number of tests passed (per test)' );
			lines.push( '# TYPE wdio_test_passed gauge' );
			lines.push( formatMetric( 'wdio_test_passed', test.passed, testLabels ) );

			lines.push( '# HELP wdio_test_failed Number of tests failed (per test)' );
			lines.push( '# TYPE wdio_test_failed gauge' );
			lines.push( formatMetric( 'wdio_test_failed', test.failed, testLabels ) );

			lines.push( '# HELP wdio_test_skipped Number of tests skipped (per test)' );
			lines.push( '# TYPE wdio_test_skipped gauge' );
			lines.push( formatMetric( 'wdio_test_skipped', test.skipped, testLabels ) );

			lines.push( '# HELP wdio_test_retries Number of test retries (per test)' );
			lines.push( '# TYPE wdio_test_retries gauge' );
			lines.push( formatMetric( 'wdio_test_retries', test.retries, testLabels ) );

			lines.push( '# HELP wdio_test_duration_max_seconds Max observed test duration (seconds per test)' );
			lines.push( '# TYPE wdio_test_duration_max_seconds gauge' );
			lines.push( formatMetric( 'wdio_test_duration_max_seconds', test.maxDuration.toFixed( 3 ), { ...testLabels } ) );
			addMetaData = false;
		} else {
			lines.push( formatMetric( 'wdio_test_passed', test.passed, testLabels ) );
			lines.push( formatMetric( 'wdio_test_failed', test.failed, testLabels ) );
			lines.push( formatMetric( 'wdio_test_skipped', test.skipped, testLabels ) );
			lines.push( formatMetric( 'wdio_test_retries', test.retries, testLabels ) );
			lines.push( formatMetric( 'wdio_test_duration_max_seconds', test.maxDuration.toFixed( 3 ), { ...testLabels } ) );
		}
	}
	const projectName = projectMetrics.labels.project;
	writeFileSync( path.join( metricsDir, `${ projectName }-${ fileName }.prom` ), `${ lines.join( '\n' ) }\n`, 'utf-8' );
}

export {
	PrometheusFileReporter,
	writeAllProjectMetrics
};
