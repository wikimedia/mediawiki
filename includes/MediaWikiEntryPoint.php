<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki;

use LogicException;
use MediaWiki\Block\BlockManager;
use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\TransactionRoundDefiningUpdate;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\JobQueue\JobRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Specials\SpecialRunJobs;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\WikiMap\WikiMap;
use MessageCache;
use Profiler;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Http\HttpStatus;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\SpanInterface;
use Wikimedia\Telemetry\TracerState;

/**
 * @defgroup entrypoint Entry points
 *
 * Web entry points reside in top-level MediaWiki directory (i.e. installation path).
 * These entry points handle web requests to interact with the wiki. Other PHP files
 * in the repository are not accessed directly from the web, but instead included by
 * an entry point.
 */

/**
 * Base class for entry point handlers.
 *
 * @note: This is not stable to extend by extensions, because MediaWiki does not
 * allow extensions to define new entry points.
 *
 * @ingroup entrypoint
 * @since 1.42, factored out of the previously existing MediaWiki class.
 */
abstract class MediaWikiEntryPoint {
	use ProtectedHookAccessorTrait;

	private IContextSource $context;
	private Config $config;
	private ?int $outputCaptureLevel = null;

	private bool $postSendMode = false;

	/** @var int Class DEFER_* constant; how non-blocking post-response tasks should run */
	private int $postSendStrategy;

	/** Call fastcgi_finish_request() to make post-send updates async */
	private const DEFER_FASTCGI_FINISH_REQUEST = 1;

	/** Set Content-Length and call ob_end_flush()/flush() to make post-send updates async */
	private const DEFER_SET_LENGTH_AND_FLUSH = 2;

	/** Do not try to make post-send updates async (e.g. for CLI mode) */
	private const DEFER_CLI_MODE = 3;

	private bool $preparedForOutput = false;

	protected EntryPointEnvironment $environment;

	private MediaWikiServices $mediaWikiServices;

	/**
	 * @param IContextSource $context
	 * @param EntryPointEnvironment $environment
	 * @param MediaWikiServices $mediaWikiServices
	 */
	public function __construct(
		IContextSource $context,
		EntryPointEnvironment $environment,
		MediaWikiServices $mediaWikiServices
	) {
		$this->context = $context;
		$this->config = $this->context->getConfig();
		$this->environment = $environment;
		$this->mediaWikiServices = $mediaWikiServices;

		if ( $environment->isCli() ) {
			$this->postSendStrategy = self::DEFER_CLI_MODE;
		} elseif ( $environment->hasFastCgi() ) {
			$this->postSendStrategy = self::DEFER_FASTCGI_FINISH_REQUEST;
		} else {
			$this->postSendStrategy = self::DEFER_SET_LENGTH_AND_FLUSH;
		}
	}

	/**
	 * Perform any setup needed before execute() is called.
	 * Final wrapper function for setup().
	 * Will be called by doRun().
	 */
	final protected function setup() {
		// Much of the functionality in WebStart.php and Setup.php should be moved here eventually.
		// As of MW 1.41, a lot of it still wants to run in file scope.

		// TODO: move define( 'MW_ENTRY_POINT' here )
		// TODO: move ProfilingContext::singleton()->init( ... ) here.

		$this->doSetup();
	}

	/**
	 * Perform any setup needed before execute() is called.
	 * Called by doRun() via doSetup().
	 */
	protected function doSetup() {
		// no-op
		// TODO: move ob_start( [ MediaWiki\Output\OutputHandler::class, 'handle' ] ) here
		// TODO: move MW_NO_OUTPUT_COMPRESSION handling here.
		// TODO: move HeaderCallback::register() here
		// TODO: move SessionManager::getGlobalSession() here (from Setup.php)
		// TODO: move AuthManager::autoCreateUser here (from Setup.php)
		// TODO: move pingback here (from Setup.php)
	}

	/**
	 * Prepare for sending the output. Should be called by entry points before
	 * sending the response.
	 * Final wrapper function for doPrepareForOutput().
	 * Will be called automatically at the end of doRun(), but will do nothing if it was
	 * already called from execute().
	 */
	final protected function prepareForOutput() {
		if ( $this->preparedForOutput ) {
			// only do this once.
			return;
		}

		$this->preparedForOutput = true;

		$this->doPrepareForOutput();
	}

	/**
	 * Prepare for sending the output. Should be called by entry points before
	 * sending the response.
	 * Will be called automatically by run() via prepareForOutput().
	 * Subclasses may override this method, but should not call it directly.
	 *
	 * @note arc-lamp profiling relies on the name of this method,
	 *        it's hard coded in the arclamp-generate-svgs script!
	 */
	protected function doPrepareForOutput() {
		// Commit any changes in the current transaction round so that:
		// a) the transaction is not rolled back after success output was already sent
		// b) error output is not jumbled together with success output in the response
		// TODO: split this up and pull out stuff like spreading cookie blocks
		$this->commitMainTransaction();
	}

	/**
	 * Main app life cycle: Calls doSetup(), execute(),
	 * prepareForOutput(), and postOutputShutdown().
	 */
	final public function run() {
		$this->setup();

		try {
			$this->execute();

			// Prepare for flushing the output. Will do nothing if it was already called by execute().
			$this->prepareForOutput();
		} catch ( Throwable $e ) {
			$this->status( 500 );
			$this->handleTopLevelError( $e );
		}

		$this->postOutputShutdown();
	}

	/**
	 * Report a top level error.
	 * Subclasses in core may override this to handle errors according
	 * to the expected output format.
	 * This method is not safe to override for extensions.
	 */
	protected function handleTopLevelError( Throwable $e ) {
		// Type errors and such: at least handle it now and clean up the LBFactory state
		MWExceptionHandler::handleException( $e, MWExceptionHandler::CAUGHT_BY_ENTRYPOINT );
	}

	/**
	 * Subclasses implement the entry point's functionality by overriding this method.
	 * This method is not safe to override for extensions.
	 */
	abstract protected function execute();

	/**
	 * If enabled, after everything specific to this request is done, occasionally run jobs
	 */
	protected function schedulePostSendJobs() {
		$jobRunRate = $this->config->get( MainConfigNames::JobRunRate );
		if (
			// Post-send job running disabled
			$jobRunRate <= 0 ||
			// Jobs cannot run due to site read-only mode
			$this->getReadOnlyMode()->isReadOnly() ||
			// HTTP response body and Content-Length headers likely to not match,
			// causing post-send updates to block the client when using mod_php
			$this->context->getRequest()->getMethod() === 'HEAD' ||
			$this->context->getRequest()->getHeader( 'If-Modified-Since' ) ||
			$this->context->getRequest()->getHeader( 'If-None-Match' )
		) {
			return;
		}

		if ( $jobRunRate < 1 ) {
			$max = mt_getrandmax();
			if ( mt_rand( 0, $max ) > $max * $jobRunRate ) {
				return; // the higher the job run rate, the less likely we return here
			}
			$n = 1;
		} else {
			$n = intval( $jobRunRate );
		}

		// Note that DeferredUpdates will catch and log any errors (T88312)
		DeferredUpdates::addUpdate( new TransactionRoundDefiningUpdate( function () use ( $n ) {
			$logger = LoggerFactory::getInstance( 'runJobs' );
			if ( $this->config->get( MainConfigNames::RunJobsAsync ) ) {
				// Send an HTTP request to the job RPC entry point if possible
				$invokedWithSuccess = $this->triggerAsyncJobs( $n, $logger );
				if ( !$invokedWithSuccess ) {
					// Fall back to blocking on running the job(s)
					$logger->warning( "Jobs switched to blocking; Special:RunJobs disabled" );
					$this->triggerSyncJobs( $n );
				}
			} else {
				$this->triggerSyncJobs( $n );
			}
		}, __METHOD__ ) );
	}

	/**
	 * This function commits all DB and session changes as needed *before* the
	 * client can receive a response (in case DB commit fails) and thus also before
	 * the response can trigger a subsequent related request by the client
	 */
	protected function commitMainTransaction() {
		$context = $this->context;

		$config = $context->getConfig();
		$request = $context->getRequest();
		$output = $context->getOutput();

		// Try to make sure that all RDBMs, session, and other storage updates complete
		ignore_user_abort( true );

		$lbFactory = $this->getDBLoadBalancerFactory();

		// Commit all RDBMs changes from the main transaction round
		$lbFactory->commitPrimaryChanges(
			__METHOD__,
			// Abort if any transaction was too big
			$config->get( MainConfigNames::MaxUserDBWriteDuration )
		);
		wfDebug( __METHOD__ . ': primary transaction round committed' );

		// Run updates that need to block the client or affect output (this is the last chance)
		DeferredUpdates::doUpdates(
			$config->get( MainConfigNames::ForceDeferredUpdatesPreSend )
				? DeferredUpdates::ALL
				: DeferredUpdates::PRESEND
		);

		wfDebug( __METHOD__ . ': pre-send deferred updates completed' );

		if ( !defined( 'MW_NO_SESSION' ) ) {
			// Persist the session to avoid race conditions on subsequent requests by the client
			$request->getSession()->save(); // T214471
			wfDebug( __METHOD__ . ': session changes committed' );
		}

		// Subsequent requests by the client should see the DB replication positions, as written
		// to ChronologyProtector during the shutdown() call below.
		// Setting the cpPosIndex cookie is normally enough. However, this will not work for
		// cross-wiki redirects within the same wiki farm, so set the ?cpPoxIndex in that case.
		$isCrossWikiRedirect = (
			$output->getRedirect() &&
			$lbFactory->hasOrMadeRecentPrimaryChanges( INF ) &&
			self::getUrlDomainDistance( $output->getRedirect() ) === 'remote'
		);

		// Persist replication positions for DBs modified by this request (at this point).
		// These help provide "session consistency" for the client on their next requests.
		$cpIndex = null;
		$cpClientId = null;
		$lbFactory->shutdown(
			$lbFactory::SHUTDOWN_NORMAL,
			null,
			$cpIndex,
			$cpClientId
		);
		$now = time();

		$allowHeaders = !( $output->isDisabled() || $this->getResponse()->headersSent() );

		if ( $cpIndex > 0 ) {
			if ( $allowHeaders ) {
				$expires = $now + ChronologyProtector::POSITION_COOKIE_TTL;
				$options = [ 'prefix' => '' ];
				$value = ChronologyProtector::makeCookieValueFromCPIndex( $cpIndex, $now, $cpClientId );
				$request->response()->setCookie( 'cpPosIndex', $value, $expires, $options );
			}

			if ( $isCrossWikiRedirect ) {
				if ( $output->getRedirect() ) {
					$url = $output->getRedirect();
					if ( $lbFactory->hasStreamingReplicaServers() ) {
						$url = strpos( $url, '?' ) === false
							? "$url?cpPosIndex=$cpIndex" : "$url&cpPosIndex=$cpIndex";
					}
					$output->redirect( $url );
				} else {
					MWExceptionHandler::logException(
						new LogicException( "No redirect; cannot append cpPosIndex parameter." ),
						MWExceptionHandler::CAUGHT_BY_ENTRYPOINT
					);
				}
			}
		}

		if ( $allowHeaders ) {
			// Set a cookie to tell all CDN edge nodes to "stick" the user to the DC that
			// handles this POST request (e.g. the "primary" data center). Also have the user
			// briefly bypass CDN so ChronologyProtector works for cacheable URLs.
			if ( $request->wasPosted() && $lbFactory->hasOrMadeRecentPrimaryChanges() ) {
				$expires = $now + max(
						ChronologyProtector::POSITION_COOKIE_TTL,
						$config->get( MainConfigNames::DataCenterUpdateStickTTL )
					);
				$options = [ 'prefix' => '' ];
				$request->response()->setCookie( 'UseDC', 'master', $expires, $options );
			}

			// Avoid letting a few seconds of replica DB lag cause a month of stale data.
			// This logic is also intimately related to the value of $wgCdnReboundPurgeDelay.
			if ( $lbFactory->laggedReplicaUsed() ) {
				$maxAge = $config->get( MainConfigNames::CdnMaxageLagged );
				$output->lowerCdnMaxage( $maxAge );
				$request->response()->header( "X-Database-Lagged: true" );
				wfDebugLog( 'replication',
					"Lagged DB used; CDN cache TTL limited to $maxAge seconds" );
			}

			// Avoid long-term cache pollution due to message cache rebuild timeouts (T133069)
			if ( $this->getMessageCache()->isDisabled() ) {
				$maxAge = $config->get( MainConfigNames::CdnMaxageSubstitute );
				$output->lowerCdnMaxage( $maxAge );
				$request->response()->header( "X-Response-Substitute: true" );
			}

			if ( !$output->couldBePublicCached() || $output->haveCacheVaryCookies() ) {
				// Autoblocks: If this user is autoblocked (and the cookie block feature is enabled
				// for autoblocks), then set a cookie to track this block.
				// This has to be done on all logged-in page loads (not just upon saving edits),
				// because an autoblocked editor might not edit again from the same IP address.
				//
				// IP blocks: For anons, if their IP is blocked (and cookie block feature is enabled
				// for IP blocks), we also want to set the cookie whenever it is safe to do.
				// Basically from any url that are definitely not publicly cacheable (like viewing
				// EditPage), or when the HTTP response is personalised for other reasons (e.g. viewing
				// articles within the same browsing session after making an edit).
				$user = $context->getUser();
				$this->getBlockManager()->trackBlockWithCookie( $user, $request->response() );
			}
		}
	}

	/**
	 * @param string $url
	 * @return string Either "local", "remote" if in the farm, "external" otherwise
	 */
	private static function getUrlDomainDistance( $url ) {
		$clusterWiki = WikiMap::getWikiFromUrl( $url );
		if ( WikiMap::isCurrentWikiId( $clusterWiki ) ) {
			return 'local'; // the current wiki
		}
		if ( $clusterWiki !== false ) {
			return 'remote'; // another wiki in this cluster/farm
		}

		return 'external';
	}

	/**
	 * If the request URL matches a given base path, extract the path part of
	 * the request URL after that base, and decode escape sequences in it.
	 *
	 * If the request URL does not match, false is returned.
	 *
	 * @internal Should be protected, made public for backwards
	 *           compatibility code in WebRequest.
	 * @param string $basePath
	 *
	 * @return false|string
	 */
	public function getRequestPathSuffix( $basePath ) {
		return WebRequest::getRequestPathSuffix(
			$basePath,
			$this->getRequestURL()
		);
	}

	/**
	 * Forces the response to be sent to the client and then
	 * does work that can be done *after* the
	 * user gets the HTTP response, so they don't block on it.
	 */
	final protected function postOutputShutdown() {
		$this->doPostOutputShutdown();

		// Just in case doPostOutputShutdown() was overwritten...
		if ( !$this->inPostSendMode() ) {
			$this->flushOutputBuffer();
			$this->enterPostSendMode();
		}
	}

	/**
	 * Forces the response to be sent to the client and then
	 * does work that can be done *after* the
	 * user gets the HTTP response, so they don't block on it.
	 *
	 * @since 1.26 (formerly on the MediaWiki class)
	 *
	 * @note arc-lamp profiling relies on the name of this method,
	 *        it's hard coded in the arclamp-generate-svgs script!
	 */
	protected function doPostOutputShutdown() {
		// Record backend request timing
		$timing = $this->context->getTiming();
		$timing->mark( 'requestShutdown' );

		// Defer everything else if possible...
		if ( $this->postSendStrategy === self::DEFER_FASTCGI_FINISH_REQUEST ) {
			// Flush the output to the client, continue processing, and avoid further output
			$this->fastCgiFinishRequest();
		} elseif ( $this->postSendStrategy === self::DEFER_SET_LENGTH_AND_FLUSH ) {
			// Flush the output to the client, continue processing, and avoid further output
			$this->flushOutputBuffer();
		}

		// Since the headers and output where already flushed, disable WebResponse setters
		// during post-send processing to warnings and unexpected behavior (T191537)
		$this->enterPostSendMode();

		// Run post-send updates while preventing further output...
		$this->startOutputBuffer( static function () {
			return ''; // do not output uncaught exceptions
		} );
		try {
			$this->restInPeace();
		} catch ( Throwable $e ) {
			MWExceptionHandler::rollbackPrimaryChangesAndLog(
				$e,
				MWExceptionHandler::CAUGHT_BY_ENTRYPOINT
			);
		}
		$length = $this->getOutputBufferLength();
		if ( $length > 0 ) {
			$this->triggerError(
				__METHOD__ . ": suppressed $length byte(s)",
				E_USER_NOTICE
			);
		}
		$this->discardOutputBuffer();
	}

	/**
	 * Check if an HTTP->HTTPS redirect should be done. It may still be aborted
	 * by a hook, so this is not the final word.
	 *
	 * @return bool
	 */
	protected function shouldDoHttpRedirect() {
		$request = $this->context->getRequest();

		// Don't redirect if we're already on HTTPS
		if ( $request->getProtocol() !== 'http' ) {
			return false;
		}

		$force = $this->config->get( MainConfigNames::ForceHTTPS );

		// Don't redirect if $wgServer is explicitly HTTP. We test for this here
		// by checking whether UrlUtils::expand() is able to force HTTPS.
		if (
			!preg_match(
				'#^https://#',
				(string)$this->getUrlUtils()->expand(
					$request->getRequestURL(),
					PROTO_HTTPS
				)
			)
		) {
			if ( $force ) {
				throw new RuntimeException( '$wgForceHTTPS is true but the server is not HTTPS' );
			}
			return false;
		}

		// Configured $wgForceHTTPS overrides the remaining conditions
		if ( $force ) {
			return true;
		}

		// Check if HTTPS is required by the session or user preferences
		return $request->getSession()->shouldForceHTTPS() ||
			// Check the cookie manually, for paranoia
			$request->getCookie( 'forceHTTPS', '' ) ||
			$this->context->getUser()->requiresHTTPS();
	}

	/**
	 * Print a response body to the current buffer (if there is one) or the server (otherwise)
	 *
	 * This method should be called after commitMainTransaction() and before doPostOutputShutdown()
	 *
	 * Any accompanying Content-Type header is assumed to have already been set
	 *
	 * @param string $content Response content, usually from OutputPage::output()
	 */
	protected function outputResponsePayload( $content ) {
		// Append any visible profiling data in a manner appropriate for the Content-Type
		$this->startOutputBuffer();
		try {
			Profiler::instance()->logDataPageOutputOnly();
		} finally {
			$content .= $this->drainOutputBuffer();
		}

		// By default, usually one output buffer is active now, either the internal PHP buffer
		// started by "output_buffering" in php.ini or the buffer started by MW_SETUP_CALLBACK.
		// The MW_SETUP_CALLBACK buffer has an unlimited chunk size, while the internal PHP
		// buffer only has an unlimited chunk size if output_buffering="On". If the buffer was
		// filled up to the chunk size with printed data, then HTTP headers will have already
		// been sent. Also, if the entry point had to stream content to the client, then HTTP
		// headers will have already been sent as well, regardless of chunk size.

		// Disable mod_deflate compression since it interferes with the output buffer set
		// by MW_SETUP_CALLBACK and can also cause the client to wait on deferred updates
		$this->disableModDeflate();

		if ( $this->inPostSendMode() ) {
			// Output already sent. This may happen for actions or special pages
			// that generate raw output and disable OutputPage. In that case,
			// we should just exit, but we should log an error if $content
			// was not empty.
			if ( $content !== '' ) {
				$length = strlen( $content );
				$this->triggerError(
					__METHOD__ . ": discarded $length byte(s) of output",
					E_USER_NOTICE
				);
			}
			return;
		}

		if (
			// "Content-Length" is used to prevent clients from waiting on deferred updates
			$this->postSendStrategy === self::DEFER_SET_LENGTH_AND_FLUSH &&
			!$this->getResponse()->headersSent() &&
			// The HTTP response code clearly allows for a meaningful body
			in_array( $this->getStatusCode(), [ 200, 404 ], true ) &&
			// The queue of (post-send) deferred updates is non-empty
			DeferredUpdates::pendingUpdatesCount() &&
			// Any buffered output is not spread out across multiple output buffers
			$this->getOutputBufferLevel() <= 1
		) {
			$response = $this->context->getRequest()->response();

			$obStatus = $this->getOutputBufferStatus();
			if ( !isset( $obStatus['name'] ) ) {
				// No output buffer is active
				$response->header( 'Content-Length: ' . strlen( $content ) );
			} elseif ( $obStatus['name'] === 'default output handler' ) {
				// Internal PHP "output_buffering" output buffer (note that the internal PHP
				// "zlib.output_compression" output buffer is named "zlib output compression")
				$response->header( 'Content-Length: ' .
					( $this->getOutputBufferLength() + strlen( $content ) ) );
			}

			// The MW_SETUP_CALLBACK output buffer ("MediaWiki\OutputHandler::handle") sets
			// "Content-Length" where applicable. Other output buffer types might not set this
			// header, and since they might mangle or compress the payload, it is not possible
			// to determine the final payload size here.

			// Tell the client to immediately end the connection as soon as the response payload
			// has been read (informed by any "Content-Length" header). This prevents the client
			// from waiting on deferred updates.
			// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Connection
			if ( $this->getServerInfo( 'SERVER_PROTOCOL' ) === 'HTTP/1.1' ) {
				$response->header( 'Connection: close' );
			}
		}

		// Print the content *after* adjusting HTTP headers and disabling mod_deflate since
		// calling "print" will send the output to the client if there is no output buffer or
		// if the output buffer chunk size is reached
		$this->print( $content );
	}

	/**
	 * Ends this task peacefully.
	 * Called after the response has been sent to the client.
	 * Subclasses in core may override this to add end-of-request code,
	 * but should always call the parent method.
	 * This method is not safe to override by extensions.
	 */
	protected function restInPeace() {
		// Either all DB and deferred updates should happen or none.
		// The latter should not be cancelled due to client disconnect.
		ignore_user_abort( true );

		// Assure deferred updates are not in the main transaction
		$lbFactory = $this->getDBLoadBalancerFactory();
		$lbFactory->commitPrimaryChanges( __METHOD__ );

		// Loosen DB query expectations since the HTTP client is unblocked
		$profiler = Profiler::instance();
		$trxProfiler = $profiler->getTransactionProfiler();
		$trxProfiler->redefineExpectations(
			$this->context->getRequest()->hasSafeMethod()
				? $this->config->get( MainConfigNames::TrxProfilerLimits )['PostSend-GET']
				: $this->config->get( MainConfigNames::TrxProfilerLimits )['PostSend-POST'],
			__METHOD__
		);

		// Do any deferred jobs; preferring to run them now if a client will not wait on them
		DeferredUpdates::doUpdates();

		// Handle external profiler outputs.
		// Any embedded profiler outputs were already processed in outputResponsePayload().
		$profiler->logData();

		self::emitBufferedStats( $this->getStatsFactory() );

		// Commit and close up!
		$lbFactory->commitPrimaryChanges( __METHOD__ );
		$lbFactory->shutdown( $lbFactory::SHUTDOWN_NO_CHRONPROT );

		// End the root span of this request or process and export trace data.
		$isServerError = $this->getStatusCode() >= 500 && $this->getStatusCode() < 600;
		// This is too generic a place to determine if the request was truly successful.
		// Err on the side of unset.
		$spanStatus = $isServerError ? SpanInterface::SPAN_STATUS_ERROR : SpanInterface::SPAN_STATUS_UNSET;
		TracerState::getInstance()->endRootSpan( $spanStatus );
		$this->mediaWikiServices->getTracer()->shutdown();

		wfDebug( "Request ended normally" );
	}

	/**
	 * Send out any buffered stats according to sampling rules
	 *
	 * For web requests, this is called once by MediaWiki::restInPeace(),
	 * which is post-send (after the response is sent to the client).
	 *
	 * For maintenance scripts, especially long-running CLI scripts, it is called
	 * more often, to avoid OOM, since we buffer stats (T181385), based on the
	 * following heuristics:
	 *
	 * - Long-running scripts that involve database writes often use transactions
	 *   to commit chunks of work. We flush from Maintenance::commitTransaction and
	 *   Maintenance::commitTransactionRound().
	 *
	 * - Long-running scripts that involve database writes but don't need any
	 *   transactions will still periodically wait for replication to be
	 *   graceful to the databases. We flush from Maintenance::waitForReplication().
	 *
	 * - Any other long-running scripts will probably report progress to stdout
	 *   in some way. We also flush from Maintenance::output().
	 *
	 * @param StatsFactory $statsFactory
	 * @since 1.31 (formerly one the MediaWiki class)
	 */
	public static function emitBufferedStats(
		StatsFactory $statsFactory
	) {
		// Send metrics gathered by StatsFactory
		$statsFactory->flush();
	}

	/**
	 * @param int $n Number of jobs to try to run
	 */
	protected function triggerSyncJobs( $n ) {
		$scope = Profiler::instance()->getTransactionProfiler()->silenceForScope();
		$this->getJobRunner()->run( [ 'maxJobs' => $n ] );
		ScopedCallback::consume( $scope );
	}

	/**
	 * @param int $n Number of jobs to try to run
	 * @param LoggerInterface $runJobsLogger
	 * @return bool Success
	 */
	protected function triggerAsyncJobs( $n, LoggerInterface $runJobsLogger ) {
		// Do not send request if there are probably no jobs
		$group = $this->getJobQueueGroupFactory()->makeJobQueueGroup();
		if ( !$group->queuesHaveJobs( JobQueueGroup::TYPE_DEFAULT ) ) {
			return true;
		}

		$query = [ 'title' => 'Special:RunJobs',
			'tasks' => 'jobs', 'maxjobs' => $n, 'sigexpiry' => time() + 5 ];
		$query['signature'] = SpecialRunJobs::getQuerySignature(
			$query, $this->config->get( MainConfigNames::SecretKey ) );

		$errno = $errstr = null;
		$info = $this->getUrlUtils()->parse( $this->config->get( MainConfigNames::CanonicalServer ) ) ?? [];
		$https = ( $info['scheme'] ?? null ) === 'https';
		$host = $info['host'] ?? null;
		$port = $info['port'] ?? ( $https ? 443 : 80 );

		AtEase::suppressWarnings();
		$sock = $host ? fsockopen(
			$https ? 'tls://' . $host : $host,
			$port,
			$errno,
			$errstr,
			// If it takes more than 100ms to connect to ourselves there is a problem...
			0.100
		) : false;
		AtEase::restoreWarnings();

		$invokedWithSuccess = true;
		if ( $sock ) {
			$special = $this->getSpecialPageFactory()->getPage( 'RunJobs' );
			$url = $special->getPageTitle()->getCanonicalURL( $query );
			$req = (
				"POST $url HTTP/1.1\r\n" .
				"Host: $host\r\n" .
				"Connection: Close\r\n" .
				"Content-Length: 0\r\n\r\n"
			);

			$runJobsLogger->info( "Running $n job(s) via '$url'" );
			// Send a cron API request to be performed in the background.
			// Give up if this takes too long to send (which should be rare).
			stream_set_timeout( $sock, 2 );
			$bytes = fwrite( $sock, $req );
			if ( $bytes !== strlen( $req ) ) {
				$invokedWithSuccess = false;
				$runJobsLogger->error( "Failed to start cron API (socket write error)" );
			} else {
				// Do not wait for the response (the script should handle client aborts).
				// Make sure that we don't close before that script reaches ignore_user_abort().
				$start = microtime( true );
				$status = fgets( $sock );
				$sec = microtime( true ) - $start;
				if ( !preg_match( '#^HTTP/\d\.\d 202 #', $status ) ) {
					$invokedWithSuccess = false;
					$runJobsLogger->error( "Failed to start cron API: received '$status' ($sec)" );
				}
			}
			fclose( $sock );
		} else {
			$invokedWithSuccess = false;
			$runJobsLogger->error( "Failed to start cron API (socket error $errno): $errstr" );
		}

		return $invokedWithSuccess;
	}

	/**
	 * Returns the main service container.
	 *
	 * This is intended as a stepping stone for migration.
	 * Ideally, individual service objects should be injected
	 * via the constructor.
	 */
	protected function getServiceContainer(): MediaWikiServices {
		return $this->mediaWikiServices;
	}

	protected function getUrlUtils(): UrlUtils {
		return $this->mediaWikiServices->getUrlUtils();
	}

	protected function getReadOnlyMode(): ReadOnlyMode {
		return $this->mediaWikiServices->getReadOnlyMode();
	}

	protected function getJobRunner(): JobRunner {
		return $this->mediaWikiServices->getJobRunner();
	}

	protected function getDBLoadBalancerFactory(): LBFactory {
		return $this->mediaWikiServices->getDBLoadBalancerFactory();
	}

	protected function getMessageCache(): MessageCache {
		return $this->mediaWikiServices->getMessageCache();
	}

	protected function getBlockManager(): BlockManager {
		return $this->mediaWikiServices->getBlockManager();
	}

	protected function getStatsFactory(): StatsFactory {
		return $this->mediaWikiServices->getStatsFactory();
	}

	protected function getStatsdDataFactory(): IBufferingStatsdDataFactory {
		return $this->mediaWikiServices->getStatsdDataFactory();
	}

	protected function getJobQueueGroupFactory(): JobQueueGroupFactory {
		return $this->mediaWikiServices->getJobQueueGroupFactory();
	}

	protected function getSpecialPageFactory(): SpecialPageFactory {
		return $this->mediaWikiServices->getSpecialPageFactory();
	}

	protected function getContext(): IContextSource {
		return $this->context;
	}

	protected function getRequest(): WebRequest {
		return $this->context->getRequest();
	}

	protected function getResponse(): WebResponse {
		return $this->getRequest()->response();
	}

	/**
	 * @return mixed
	 */
	protected function getConfig( string $key ) {
		return $this->config->get( $key );
	}

	protected function isCli(): bool {
		return $this->environment->isCli();
	}

	protected function hasFastCgi(): bool {
		return $this->environment->hasFastCgi();
	}

	/**
	 * @param string $key
	 * @param mixed|null $default
	 * @return mixed|null
	 */
	protected function getServerInfo( string $key, $default = null ) {
		return $this->environment->getServerInfo( $key, $default );
	}

	protected function print( string $data ) {
		if ( $this->inPostSendMode() ) {
			throw new RuntimeException( 'Output already sent!' );
		}

		print $data;
	}

	/**
	 * @param int $code
	 *
	 * @return never
	 */
	protected function exit( int $code = 0 ): never {
		$this->environment->exit( $code );
	}

	/**
	 * Adds a new output buffer level.
	 *
	 * @param ?callable $callback
	 *
	 * @see ob_start
	 */
	protected function startOutputBuffer( ?callable $callback = null ): void {
		ob_start( $callback );
	}

	/**
	 * Returns the content of the current output buffer and clears it.
	 *
	 * @see ob_get_clean
	 * @return false|string
	 */
	protected function drainOutputBuffer() {
		// NOTE: The ob_get_clean() would *disable* the current buffer,
		// we don't want that!

		$contents = ob_get_contents();
		ob_clean();
		return $contents;
	}

	/**
	 * Enable capturing of the current output buffer.
	 *
	 * There may be mutiple levels of output buffering. The level
	 * we are currently at, at the time of calling this method,
	 * is the level that will be captured to later retrieve via
	 * getCapturedOutput().
	 *
	 * When capturing is active, flushOutputBuffer() will not actually
	 * write to the real STDOUT, but instead write only to the capture.
	 *
	 * This exists to ease testing.
	 *
	 * @internal For use in PHPUnit tests
	 * @see ob_start()
	 * @see getCapturedOutput();
	 */
	public function enableOutputCapture(): void {
		$level = ob_get_level();

		if ( $level <= 0 ) {
			throw new RuntimeException(
				'No capture buffer available, call ob_start first.'
			);
		}

		$this->outputCaptureLevel = $level;
	}

	/**
	 * Returns the output buffer level.
	 *
	 * If enableOutputCapture() has been called, the capture buffer
	 * level is taking into account by subtracting it from the actual buffer
	 * level.
	 *
	 * @see ob_get_level
	 */
	protected function getOutputBufferLevel(): int {
		return max( 0, ob_get_level() - ( $this->outputCaptureLevel ?? 0 ) );
	}

	/**
	 * Ends the current output buffer, appending its content to the parent
	 * buffer.
	 * @see ob_end_flush
	 */
	protected function commitOutputBuffer(): bool {
		if ( $this->inPostSendMode() ) {
			throw new RuntimeException( 'Output already sent!' );
		}

		$level = $this->getOutputBufferLevel();
		if ( $level === 0 ) {
			return false;
		} else {
			//phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			return @ob_end_flush();
		}
	}

	/**
	 * Stop capturing and return all output
	 *
	 * It flushes and drains all output buffers, but lets it go
	 * to a return value instead of the real STDOUT.
	 *
	 * You must call enableOutputCapture() and run() before getCapturedOutput().
	 *
	 * @internal For use in PHPUnit tests
	 * @see enableOutputCapture();
	 * @see ob_end_clean
	 * @return string HTTP response body
	 */
	public function getCapturedOutput(): string {
		if ( $this->outputCaptureLevel === null ) {
			throw new LogicException(
				'getCapturedOutput() requires enableOutputCapture() to be called first'
			);
		}

		$this->flushOutputBuffer();
		return $this->drainOutputBuffer();
	}

	/**
	 * Flush buffered output to the client.
	 *
	 * If enableOutputCapture() was called, buffered output is committed to
	 * the capture buffer instead.
	 *
	 * If enterPostSendMode() was called before this method, a warning is
	 * triggered and any buffered output is discarded.
	 *
	 * @see ob_end_flush
	 * @see flush
	 */
	protected function flushOutputBuffer(): void {
		// NOTE: Use a for-loop, so we don't loop indefinitely in case
		// we fail to delete a buffer. This will routinely happen for
		// PHP's zlib.compression buffer.
		// See https://www.php.net/manual/en/function.ob-end-flush.php#103387
		$levels = $this->getOutputBufferLevel();

		// If we are in post-send mode, throw away any buffered output.
		// Only complain if there actually is buffered output.
		if ( $this->inPostSendMode() ) {
			for ( $i = 0; $i < $levels; $i++ ) {
				$length = $this->getOutputBufferLength();

				// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
				@ob_end_clean();

				if ( $length > 0 ) {
					$this->triggerError(
						__METHOD__ . ": suppressed $length byte(s)",
						E_USER_NOTICE
					);
				}
			}
			return;
		}

		for ( $i = 0; $i < $levels; $i++ ) {
			// Note that ob_end_flush() will fail for buffers created without
			// the PHP_OUTPUT_HANDLER_FLUSHABLE flag. So we use a for-loop
			// to avoid looping forever when ob_get_level() won't go down.

			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			@ob_end_flush();
		}

		// Flush the system buffer so the response is actually sent to the client,
		// unless we intend to capture the output, for testing or otherwise.
		// Capturing would be enabled by $this->outputCaptureLevel being set.
		// Note that, when not capturing the output, we want to flush response
		// to the client even if the loop above did not result in ob_get_level()
		// to return 0. This would be the case e.g. when zlib.compression
		// is enabled.
		// See https://www.php.net/manual/en/function.ob-end-flush.php#103387
		if ( $this->outputCaptureLevel === null || ob_get_level() === 0 ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			@flush();
		}
		wfDebug( "Output buffer flushed" );
	}

	/**
	 * Discards all buffered output, down to the capture buffer level.
	 */
	protected function discardAllOutput() {
		// NOTE: use a for-loop, in case one of the buffers is non-removable.
		// In that case, getOutputBufferLevel() will never return 0.
		$levels = $this->getOutputBufferLevel();
		for ( $i = 0; $i < $levels; $i++ ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			@ob_end_clean();
		}
	}

	/**
	 * @see ob_get_length
	 * @return false|int
	 */
	protected function getOutputBufferLength() {
		return ob_get_length();
	}

	/**
	 * @see ob_get_status
	 */
	protected function getOutputBufferStatus(): array {
		return ob_get_status();
	}

	/**
	 * @see ob_end_clean
	 */
	protected function discardOutputBuffer(): bool {
		return ob_end_clean();
	}

	protected function disableModDeflate(): void {
		$this->environment->disableModDeflate();
	}

	/**
	 * @see http_response_code
	 * @return int|bool
	 */
	protected function getStatusCode() {
		return $this->getResponse()->getStatusCode();
	}

	/**
	 * Whether enterPostSendMode() has been called.
	 * Indicates whether more data can be sent to the client.
	 * To determine whether more headers can be sent, use
	 * $this->getResponse()->headersSent().
	 */
	protected function inPostSendMode(): bool {
		return $this->postSendMode;
	}

	/**
	 * Triggers a PHP runtime error
	 *
	 * @see trigger_error
	 */
	protected function triggerError( string $message, int $level = E_USER_NOTICE ): bool {
		return $this->environment->triggerError( $message, $level );
	}

	/**
	 * Returns the value of an environment variable.
	 *
	 * @see getenv
	 *
	 * @param string $name
	 *
	 * @return array|false|string
	 */
	protected function getEnv( string $name ) {
		return $this->environment->getEnv( $name );
	}

	/**
	 * Returns the value of an ini option.
	 *
	 * @see ini_get
	 *
	 * @param string $name
	 *
	 * @return false|string
	 */
	protected function getIni( string $name ) {
		return $this->environment->getIni( $name );
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return false|string
	 */
	protected function setIniOption( string $name, $value ) {
		return $this->environment->setIniOption( $name, $value );
	}

	/**
	 * @see header() function
	 */
	protected function header( string $header, bool $replace = true, int $status = 0 ): void {
		$this->getResponse()->header( $header, $replace, $status );
	}

	/**
	 * @see HttpStatus
	 */
	protected function status( int $code ): void {
		$this->header( HttpStatus::getHeader( $code ), true, $code );
	}

	/**
	 * Calls fastcgi_finish_request if possible. Reasons for not calling
	 * fastcgi_finish_request include the fastcgi extension not being loaded
	 * and the capture buffer level being different from 0.
	 *
	 * @see fastcgi_finish_request
	 * @return bool true if fastcgi_finish_request was called and successful.
	 */
	protected function fastCgiFinishRequest(): bool {
		if ( !$this->inPostSendMode() ) {
			$this->flushOutputBuffer();
		}

		// Don't mess with fastcgi on CLI mode.
		if ( $this->isCli() ) {
			return false;
		}

		// Only mess with fastcgi if we really have no buffers left.
		if ( ob_get_level() > 0 ) {
			return false;
		}

		$success = $this->environment->fastCgiFinishRequest();
		wfDebug( $success ? 'FastCGI request finished' : 'FastCGI request finish failed' );
		return $success;
	}

	/**
	 * Returns the current request's path and query string (not a full URL),
	 * like PHP's built-in $_SERVER['REQUEST_URI'].
	 *
	 * @see WebRequest::getRequestURL()
	 * @see WebRequest::getGlobalRequestURL()
	 */
	protected function getRequestURL(): string {
		// Despite the name, this just returns the path and query string
		return $this->getRequest()->getRequestURL();
	}

	/**
	 * Disables all output to the client.
	 * After this, calling any output methods on this object will fail.
	 */
	protected function enterPostSendMode() {
		$this->postSendMode = true;

		$this->getResponse()->disableForPostSend();
	}

}
