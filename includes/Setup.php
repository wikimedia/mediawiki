<?php
/**
 * The setup for all MediaWiki processes (both web-based and CLI).
 *
 * The entry point (such as WebStart.php and doMaintenance.php) has these responsibilities:
 * - The entry point MUST:
 *   - define the 'MEDIAWIKI' constant.
 * - The entry point SHOULD:
 *   - define the 'MW_ENTRY_POINT' constant.
 *   - display an error if MW_CONFIG_CALLBACK is not defined and the
 *     file specified in MW_CONFIG_FILE (or the LocalSettings.php default location)
 *     does not exist. The error should either be sent before and instead
 *     of the Setup.php inclusion, or (if it needs classes and dependencies
 *     from core) the error can be displayed via a MW_CONFIG_CALLBACK,
 *     which must then abort the process to prevent the rest of Setup.php
 *     from executing.
 *
 * This file does:
 * - run-time environment checks,
 * - define MW_INSTALL_PATH, $IP, and $wgBaseDirectory,
 * - load autoloaders, constants, default settings, and global functions,
 * - load the site configuration (e.g. LocalSettings.php),
 * - load the enabled extensions (via ExtensionRegistry),
 * - trivial expansion of site configuration defaults and shortcuts
 *   (no calls to MediaWikiServices or other parts of MediaWiki),
 * - initialization of:
 *   - PHP run-time (setlocale, memory limit, default date timezone)
 *   - the debug logger (MWDebug)
 *   - the service container (MediaWikiServices)
 *   - the exception handler (MWExceptionHandler)
 *   - the session manager (SessionManager)
 * - complex expansion of site configuration defaults (those that require
 *   calling into MediaWikiServices, global functions, or other classes.).
 *
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

// phpcs:disable MediaWiki.Usage.DeprecatedGlobalVariables
use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Context\RequestContext;
use MediaWiki\Debug\MWDebug;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\FauxGlobalHookArray;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Registration\MissingExtensionException;
use MediaWiki\Request\HeaderCallback;
use MediaWiki\Settings\DynamicDefaultValues;
use MediaWiki\Settings\LocalSettingsLoader;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\PhpSettingsSource;
use MediaWiki\Settings\Source\ReflectionSchemaSource;
use MediaWiki\Settings\WikiFarmSettingsLoader;
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\StubObject\StubUserLang;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Psr\Log\LoggerInterface;
use Wikimedia\RequestTimeout\RequestTimeout;

/**
 * Environment checks
 *
 * These are inline checks done before we include any source files,
 * and thus these conditions may be assumed by all source code.
 */

// This file must be included from a valid entry point (e.g. WebStart.php, Maintenance.php)
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

// PHP must not be configured to overload mbstring functions. (T5782, T122807)
// This was deprecated by upstream in PHP 7.2 and was removed in PHP 8.0.
if ( ini_get( 'mbstring.func_overload' ) ) {
	die( 'MediaWiki does not support installations where mbstring.func_overload is non-zero.' );
}

// The MW_ENTRY_POINT constant must always exists, to make it safe to access.
// For compat, we do support older and custom MW entrypoints that don't set this,
// in which case we assign a default here.
if ( !defined( 'MW_ENTRY_POINT' ) ) {
	/**
	 * The entry point, which may be either the script filename without the
	 * file extension, or "cli" for maintenance scripts, or "unknown" for any
	 * entry point that does not set the constant.
	 */
	define( 'MW_ENTRY_POINT', 'unknown' );
}

// The $IP variable is defined for use by LocalSettings.php.
// It is made available as a global variable for backwards compatibility.
//
// Source code should instead use the MW_INSTALL_PATH constant, or the
// MainConfigNames::BaseDirectory setting. The BaseDirectory setting is set further
// down in Setup.php to the value of MW_INSTALL_PATH.
global $IP;
$IP = wfDetectInstallPath(); // ensure MW_INSTALL_PATH is defined

/**
 * Pre-config setup: Before loading LocalSettings.php
 *
 * These are changes and additions to runtime that don't vary on site configuration.
 */
require_once MW_INSTALL_PATH . '/includes/AutoLoader.php';
require_once MW_INSTALL_PATH . '/includes/Defines.php';

// Assert that composer dependencies were successfully loaded
if ( !interface_exists( LoggerInterface::class ) ) {
	$message = (
		'<strong>Error:</strong> MediaWiki requires the <a href="https://github.com/php-fig/log">PSR-3 logging ' .
		"library</a> to be present. This library is not embedded directly in MediaWiki's " .
		"git repository and must be installed separately by the end user.\n\n" .
		'Please see the <a href="https://www.mediawiki.org/wiki/Download_from_Git' .
		'#Fetch_external_libraries">instructions for installing libraries</a> on mediawiki.org ' .
		'for help on installing the required components.'
	);
	http_response_code( 500 );
	echo $message;
	error_log( $message );
	exit( 1 );
}

// Deprecated global variable for backwards-compatibility.
// New code should check MW_ENTRY_POINT directly.
$wgCommandLineMode = MW_ENTRY_POINT === 'cli';

/**
 * $wgConf hold the site configuration.
 * Not used for much in a default install.
 * @since 1.5
 */
$wgConf = new SiteConfiguration;

$wgAutoloadClasses ??= [];

$wgSettings = SettingsBuilder::getInstance();

if ( defined( 'MW_USE_CONFIG_SCHEMA_CLASS' ) ) {
	// Load config schema from MainConfigSchema. Useful for running scripts that
	// generate other representations of the config schema. This is slow, so it
	// should not be used for serving web traffic.
	$wgSettings->load( new ReflectionSchemaSource( MainConfigSchema::class ) );
} else {
	$wgSettings->load( new PhpSettingsSource( MW_INSTALL_PATH . '/includes/config-schema.php' ) );
}

require_once MW_INSTALL_PATH . '/includes/GlobalFunctions.php';

HeaderCallback::register();

// Set the encoding used by PHP for reading HTTP input, and writing output.
// This is also the default for mbstring functions.
mb_internal_encoding( 'UTF-8' );

/**
 * Load LocalSettings.php
 */

// Initialize some config settings with dynamic defaults, and
// make default settings available in globals for use in LocalSettings.php.
$wgSettings->putConfigValues( [
	MainConfigNames::BaseDirectory => MW_INSTALL_PATH,
	MainConfigNames::ExtensionDirectory => MW_INSTALL_PATH . '/extensions',
	MainConfigNames::StyleDirectory => MW_INSTALL_PATH . '/skins',
	MainConfigNames::ServiceWiringFiles => [ MW_INSTALL_PATH . '/includes/ServiceWiring.php' ],
	'Version' => MW_VERSION,
] );
$wgSettings->apply();

// $wgSettings->apply() puts all configuration into global variables.
// If we are not in global scope, make all relevant globals available
// in this file's scope as well.
$wgScopeTest = 'MediaWiki Setup.php scope test';
if ( !isset( $GLOBALS['wgScopeTest'] ) || $GLOBALS['wgScopeTest'] !== $wgScopeTest ) {
	foreach ( $wgSettings->getConfigSchema()->getDefinedKeys() as $key ) {
		$var = "wg$key";
		// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
		global $$var;
	}
	unset( $key, $var );
}
unset( $wgScopeTest );

try {
	if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
		call_user_func( MW_CONFIG_CALLBACK, $wgSettings );
	} else {
		wfDetectLocalSettingsFile( MW_INSTALL_PATH );

		if ( getenv( 'MW_USE_LOCAL_SETTINGS_LOADER' ) ) {
			// NOTE: This will not work for configuration variables that use a prefix
			//       other than "wg".
			$localSettingsLoader = new LocalSettingsLoader( $wgSettings, MW_INSTALL_PATH );
			$localSettingsLoader->loadLocalSettingsFile( MW_CONFIG_FILE );
			unset( $localSettingsLoader );
		} else {
			if ( str_ends_with( MW_CONFIG_FILE, '.php' ) ) {
				// make defaults available as globals
				$wgSettings->apply();
				require_once MW_CONFIG_FILE;
			} else {
				$wgSettings->loadFile( MW_CONFIG_FILE );
			}
		}
	}

	// Make settings loaded by LocalSettings.php available in globals for use here
	$wgSettings->apply();
} catch ( MissingExtensionException $e ) {
	// Make a common mistake give a friendly error
	$e->render();
}

// If in a wiki-farm, load site-specific settings
if ( $wgSettings->getConfig()->get( MainConfigNames::WikiFarmSettingsDirectory ) ) {
	$wikiFarmSettingsLoader = new WikiFarmSettingsLoader( $wgSettings );
	$wikiFarmSettingsLoader->loadWikiFarmSettings();
	unset( $wikiFarmSettingsLoader );
}

// All settings should be loaded now.
$wgSettings->enterRegistrationStage();

/**
 * Customization point after most things are loaded (constants, functions, classes,
 * LocalSettings.
 * Note that this runs before extensions are registered, and before most singletons become
 * available, and before MediaWikiServices is initialized.
 */

if ( defined( 'MW_SETUP_CALLBACK' ) ) {
	call_user_func( MW_SETUP_CALLBACK, $wgSettings );
	// Make any additional settings available in globals for use here
	$wgSettings->apply();
}

// Apply dynamic defaults declared in config schema callbacks.
$dynamicDefaults = new DynamicDefaultValues( $wgSettings->getConfigSchema() );
$dynamicDefaults->applyDynamicDefaults( $wgSettings->getConfigBuilder() );

// Make updated config available in global scope.
$wgSettings->apply();

// Apply dynamic defaults implemented in SetupDynamicConfig.php.
// Ideally, all logic in SetupDynamicConfig would be converted to
// callbacks in the config schema.
require __DIR__ . '/SetupDynamicConfig.php';

if ( defined( 'MW_AUTOLOAD_TEST_CLASSES' ) ) {
	require_once __DIR__ . '/../tests/common/TestsAutoLoader.php';
}

if ( $wgBaseDirectory !== MW_INSTALL_PATH ) {
	throw new FatalError(
		'$wgBaseDirectory must not be modified in settings files! ' .
		'Use the MW_INSTALL_PATH environment variable to override the installation root directory.'
	);
}

// Start time limit
if ( $wgRequestTimeLimit && MW_ENTRY_POINT !== 'cli' ) {
	RequestTimeout::singleton()->setWallTimeLimit( $wgRequestTimeLimit );
}

/**
 * Load queued extensions
 */
if ( defined( 'MW_AUTOLOAD_TEST_CLASSES' ) ) {
	ExtensionRegistry::getInstance()->setLoadTestClassesAndNamespaces( true );
}

ExtensionRegistry::getInstance()->setSettingsBuilder( $wgSettings );
ExtensionRegistry::getInstance()->loadFromQueue();
// Don't let any other extensions load
ExtensionRegistry::getInstance()->finish();

/**
 * Customization point after ALL loading (constants, functions, classes,
 * LocalSettings, extensions, dynamic defaults).
 * Note that this runs before MediaWikiServices is initialized.
 */
if ( defined( 'MW_FINAL_SETUP_CALLBACK' ) ) {
	call_user_func( MW_FINAL_SETUP_CALLBACK, $wgSettings );
	// Make any additional settings available in globals for use below
	$wgSettings->apply();
}

// Config can no longer be changed.
$wgSettings->enterReadOnlyStage();

// Set an appropriate locale (T291234)
// setlocale() will return the locale name actually set.
// The putenv() is meant to propagate the choice of locale to shell commands
// so that they will interpret UTF-8 correctly. If you have a problem with a
// shell command and need to send a special locale, you can override the locale
// with Command::environment().
putenv( "LC_ALL=" . setlocale( LC_ALL, 'C.UTF-8', 'C' ) );

// Set PHP runtime to the desired timezone
date_default_timezone_set( $wgLocaltimezone );

MWDebug::setup();

// Enable the global service locator.
// Trivial expansion of site configuration should go before this point.
// Any non-trivial expansion that requires calling into MediaWikiServices or other parts of MW.
MediaWikiServices::allowGlobalInstance();

// Define a constant that indicates that the bootstrapping of the service locator
// is complete.
define( 'MW_SERVICE_BOOTSTRAP_COMPLETE', 1 );

MWExceptionRenderer::setShowExceptionDetails( $wgShowExceptionDetails );
if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	// Never install the handler in PHPUnit tests, otherwise PHPUnit's own handler will be unset and things
	// like convertWarningsToExceptions won't work.
	MWExceptionHandler::installHandler( $wgLogExceptionBacktrace, $wgPropagateErrors );
}
Profiler::init( $wgProfiler );

// Non-trivial validation of: $wgServer
// The FatalError page only renders cleanly after MWExceptionHandler is installed.
if ( $wgServer === false ) {
	// T30798: $wgServer must be explicitly set
	throw new FatalError(
		'$wgServer must be set in LocalSettings.php. ' .
		'See <a href="https://www.mediawiki.org/wiki/Manual:$wgServer">' .
		'https://www.mediawiki.org/wiki/Manual:$wgServer</a>.'
	);
}

// Set up a fake $wgHooks array.
// XXX: It would be nice if we could still get the originally configured hook handlers
//      using the MainConfigNames::Hooks setting, but it's not really needed,
//      since we need the HookContainer to be initialized first anyway.

global $wgHooks;
$wgHooks = new FauxGlobalHookArray(
	MediaWikiServices::getInstance()->getHookContainer(),
	$wgHooks
);

// Non-trivial expansion of: $wgCanonicalServer, $wgServerName.
// These require calling global functions.
// Also here are other settings that further depend on these two.
if ( $wgCanonicalServer === false ) {
	$wgCanonicalServer = MediaWikiServices::getInstance()->getUrlUtils()->getCanonicalServer();
}
$wgVirtualRestConfig['global']['domain'] = $wgCanonicalServer;

if ( $wgServerName !== false ) {
	wfWarn( '$wgServerName should be derived from $wgCanonicalServer, '
		. 'not customized. Overwriting $wgServerName.' );
}
$wgServerName = parse_url( $wgCanonicalServer, PHP_URL_HOST );

// $wgEmergencyContact and $wgPasswordSender may be false or empty string (T104142)
if ( !$wgEmergencyContact ) {
	$wgEmergencyContact = 'wikiadmin@' . $wgServerName;
}
if ( !$wgPasswordSender ) {
	$wgPasswordSender = 'apache@' . $wgServerName;
}
if ( !$wgNoReplyAddress ) {
	$wgNoReplyAddress = $wgPasswordSender;
}

// Non-trivial expansion of: $wgSecureLogin
// (due to calling wfWarn).
if ( $wgSecureLogin && substr( $wgServer, 0, 2 ) !== '//' ) {
	$wgSecureLogin = false;
	wfWarn( 'Secure login was enabled on a server that only supports '
		. 'HTTP or HTTPS. Disabling secure login.' );
}

// Now that GlobalFunctions is loaded, set defaults that depend on it.
if ( $wgTmpDirectory === false ) {
	$wgTmpDirectory = wfTempDir();
}

if ( $wgSharedDB && $wgSharedTables ) {
	// Apply $wgSharedDB table aliases for the local LB (all non-foreign DB connections)
	MediaWikiServices::getInstance()->getDBLoadBalancer()->setTableAliases(
		array_fill_keys(
			$wgSharedTables,
			[
				'dbname' => $wgSharedDB,
				'schema' => $wgSharedSchema,
				'prefix' => $wgSharedPrefix
			]
		)
	);
}

// Raise the memory limit if it's too low
// NOTE: This use wfDebug, and must remain after the MWDebug::setup() call.
wfMemoryLimit( $wgMemoryLimit );

// Explicit globals, so this works with bootstrap.php
global $wgRequest, $wgInitialSessionId;

// Initialize the request object in $wgRequest
$wgRequest = RequestContext::getMain()->getRequest(); // BackCompat

// Make sure that object caching does not undermine the ChronologyProtector improvements
if ( $wgRequest->getCookie( 'UseDC', '' ) === 'master' ) {
	// The user is pinned to the primary DC, meaning that they made recent changes which should
	// be reflected in their subsequent web requests. Avoid the use of interim cache keys because
	// they use a blind TTL and could be stale if an object changes twice in a short time span.
	MediaWikiServices::getInstance()->getMainWANObjectCache()->useInterimHoldOffCaching( false );
}

// Useful debug output
( static function () {
	global $wgRequest;

	$logger = LoggerFactory::getInstance( 'wfDebug' );
	if ( MW_ENTRY_POINT === 'cli' ) {
		$self = $_SERVER['PHP_SELF'] ?? '';
		$logger->debug( "\n\nStart command line script $self" );
	} else {
		$debug = "\n\nStart request {$wgRequest->getMethod()} {$wgRequest->getRequestURL()}\n";
		$debug .= "IP: " . $wgRequest->getIP() . "\n";
		$debug .= "HTTP HEADERS:\n";
		foreach ( $wgRequest->getAllHeaders() as $name => $value ) {
			$debug .= "$name: $value\n";
		}
		$debug .= "(end headers)";
		$logger->debug( $debug );
	}
} )();

// Most of the config is out, some might want to run hooks here.
( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )->onSetupAfterCache();

// Now that variant lists may be available, parse any action paths and article paths
// as query parameters.
//
// Skip title interpolation on API queries where it is useless and sometimes harmful (T18019).
//
// Optimization: Skip on load.php and all other entrypoints besides index.php to save time.
//
// TODO: Figure out if this can be safely done after everything else in Setup.php (e.g. any
// hooks or other state that would miss this?). If so, move to wfIndexMain or MediaWiki::run.
if ( MW_ENTRY_POINT === 'index' ) {
	$wgRequest->interpolateTitle();
}

/**
 * @var MediaWiki\Session\SessionId|null $wgInitialSessionId The persistent session ID (if any) loaded at startup
 */
$wgInitialSessionId = null;
if ( !defined( 'MW_NO_SESSION' ) && MW_ENTRY_POINT !== 'cli' ) {
	// If session.auto_start is there, we can't touch session name
	if ( $wgPHPSessionHandling !== 'disable' && !wfIniGetBool( 'session.auto_start' ) ) {
		HeaderCallback::warnIfHeadersSent();
		session_name( $wgSessionName ?: $wgCookiePrefix . '_session' );
	}

	// Create the SessionManager singleton and set up our session handler,
	// unless we're specifically asked not to.
	if ( !defined( 'MW_NO_SESSION_HANDLER' ) ) {
		MediaWiki\Session\PHPSessionHandler::install(
			MediaWiki\Session\SessionManager::singleton()
		);
	}

	$contLang = MediaWikiServices::getInstance()->getContentLanguage();

	// Initialize the session
	try {
		$session = MediaWiki\Session\SessionManager::getGlobalSession();
	} catch ( MediaWiki\Session\SessionOverflowException $ex ) {
		// The exception is because the request had multiple possible
		// sessions tied for top priority. Report this to the user.
		$list = [];
		foreach ( $ex->getSessionInfos() as $info ) {
			$list[] = $info->getProvider()->describe( $contLang );
		}
		$list = $contLang->listToText( $list );
		throw new HttpError( 400,
			Message::newFromKey( 'sessionmanager-tie', $list )->inLanguage( $contLang )
		);
	}

	unset( $contLang );

	if ( $session->isPersistent() ) {
		$wgInitialSessionId = $session->getSessionId();
	}

	$session->renew();
	if ( MediaWiki\Session\PHPSessionHandler::isEnabled() &&
		( $session->isPersistent() || $session->shouldRememberUser() ) &&
		session_id() !== $session->getId()
	) {
		// Start the PHP-session for backwards compatibility
		if ( session_id() !== '' ) {
			wfDebugLog( 'session', 'PHP session {old_id} was already started, changing to {new_id}', 'all', [
				'old_id' => session_id(),
				'new_id' => $session->getId(),
			] );
			session_write_close();
		}
		session_id( $session->getId() );
		session_start();
	}

	unset( $session );
} else {
	// Even if we didn't set up a global Session, still install our session
	// handler unless specifically requested not to.
	if ( !defined( 'MW_NO_SESSION_HANDLER' ) ) {
		MediaWiki\Session\PHPSessionHandler::install(
			MediaWiki\Session\SessionManager::singleton()
		);
	}
}

// Explicit globals, so this works with bootstrap.php
global $wgUser, $wgLang, $wgOut, $wgTitle;

/**
 * @var User $wgUser
 * @deprecated since 1.35, use an available context source when possible, or, as a backup,
 * RequestContext::getMain()
 */
$wgUser = new StubGlobalUser( RequestContext::getMain()->getUser() ); // BackCompat
register_shutdown_function( static function () {
	StubGlobalUser::$destructorDeprecationDisarmed = true;
} );

/**
 * @var Language|StubUserLang $wgLang
 */
$wgLang = new StubUserLang;

/**
 * @var MediaWiki\Output\OutputPage $wgOut
 */
$wgOut = RequestContext::getMain()->getOutput(); // BackCompat

/**
 * @var Title|null $wgTitle
 */
$wgTitle = null;

// Explicit globals, so this works with bootstrap.php
global $wgFullyInitialised, $wgExtensionFunctions;

// Extension setup functions
// Entries should be added to this variable during the inclusion
// of the extension file. This allows the extension to perform
// any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	call_user_func( $func );
}
unset( $func ); // no global pollution; destroy reference

// If the session user has a 0 id but a valid name, that means we need to
// autocreate it.
if ( !defined( 'MW_NO_SESSION' ) && MW_ENTRY_POINT !== 'cli' ) {
	$sessionUser = MediaWiki\Session\SessionManager::getGlobalSession()->getUser();
	if ( $sessionUser->getId() === 0 &&
		MediaWikiServices::getInstance()->getUserNameUtils()->isValid( $sessionUser->getName() )
	) {
		$res = MediaWikiServices::getInstance()->getAuthManager()->autoCreateUser(
			$sessionUser,
			MediaWiki\Auth\AuthManager::AUTOCREATE_SOURCE_SESSION,
			true,
			true,
			$sessionUser
		);
		$firstMessage = $res->getMessages( 'error' )[0] ?? $res->getMessages( 'warning' )[0] ?? null;
		\MediaWiki\Logger\LoggerFactory::getInstance( 'authevents' )->info( 'Autocreation attempt', [
			'event' => 'autocreate',
			'successful' => $res->isGood(),
			'status' => $firstMessage ? $firstMessage->getKey() : '-',
		] );
		unset( $res );
		unset( $firstMessage );
	}
	unset( $sessionUser );
}

// Optimization: Avoid overhead from DeferredUpdates and Pingback deps when turned off.
if ( MW_ENTRY_POINT !== 'cli' && $wgPingback ) {
	// NOTE: Do not refactor to inject Config or otherwise make unconditional service call.
	//
	// On a plain install of MediaWiki, Pingback is likely the *only* feature
	// involving DeferredUpdates or DB_PRIMARY on a regular page view.
	// To allow for error recovery and fault isolation, let admins turn this
	// off completely. (T269516)
	DeferredUpdates::addCallableUpdate( static function () {
		MediaWikiServices::getInstance()->getPingback()->run();
	} );
}

$settingsWarnings = $wgSettings->getWarnings();
if ( $settingsWarnings ) {
	$logger = LoggerFactory::getInstance( 'Settings' );
	foreach ( $settingsWarnings as $msg ) {
		$logger->warning( $msg );
	}
	unset( $logger );
}

unset( $settingsWarnings );

// Explicit globals, so this works with bootstrap.php
global $wgFullyInitialised;
$wgFullyInitialised = true;

// T264370
if ( !defined( 'MW_NO_SESSION' ) && MW_ENTRY_POINT !== 'cli' ) {
	MediaWiki\Session\SessionManager::singleton()->logPotentialSessionLeakage();
}
