const MWBot = require( 'mwbot' ),
	Page = require( './Page' ),
	FRONTPAGE_REQUESTS_MAX_RUNS = 10; // (arbitrary) safe-guard against endless execution

/**
 * Trigger the execution of jobs
 *
 * @see https://www.mediawiki.org/wiki/Manual:Job_queue/For_developers#Execution_of_jobs
 *
 * Use RunJobs.run() to ensure that jobs are executed before making assertions that depend on it.
 *
 * Systems that are selenium-tested are usually provisioned for that purpose, see no organic
 * traffic, consequently typical post-send job queue processing rarely happens. Additionally,
 * test set-up is often done through the API, requests to which do not trigger job queue
 * processing at all.
 *
 * This can lead to an accumulation of unprocessed jobs, which in turn would render certain
 * assertions impossible - e.g. checking a page is listed on Special:RecentChanges right
 * after creating it.
 *
 * This class will try to trigger job execution through
 * repeated blunt requests against the wiki's home page to trigger them at a rate
 * of $wgJobRunRate per request.
 */
class RunJobs {

	static run() {
		browser.call( () => {
			return this.runThroughFrontPageRequests();
		} );
	}

	static getJobCount() {
		let bot = new MWBot( {
			apiUrl: `${browser.options.baseUrl}/api.php`
		} );
		return new Promise( ( resolve ) => {
			return bot.request( {
				action: 'query',
				meta: 'siteinfo',
				siprop: 'statistics'
			} ).then( ( response ) => {
				resolve( response.query.statistics.jobs );
			} );
		} );
	}

	static runThroughFrontPageRequests( runCount = 1 ) {
		let page = new Page();
		this.log( `through requests to the front page (run ${runCount}).` );

		page.openTitle( '' );

		return this.getJobCount().then( ( jobCount ) => {
			if ( jobCount === 0 ) {
				this.log( 'found no more queued jobs.' );
				return;
			}
			this.log( `detected ${jobCount} more queued job(s).` );
			if ( runCount >= FRONTPAGE_REQUESTS_MAX_RUNS ) {
				this.log( 'stopping requests to the front page due to reached limit.' );
				return;
			}
			return this.runThroughFrontPageRequests( ++runCount );
		} );
	}

	static log( message ) {
		process.stdout.write( `RunJobs ${message}\n` );
	}
}

module.exports = RunJobs;
