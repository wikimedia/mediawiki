<?php
/**
 * This file contains schema declarations for all configuration variables
 * known to MediaWiki core.
 *
 * @file
 * @ingroup Config
 */

// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase
// phpcs:disable Generic.Files.LineLength.TooLong
namespace MediaWiki;

use DateTime;
use DateTimeZone;
use Generator;
use InvalidArgumentException;
use LocalisationCache;
use MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider;
use MediaWiki\Auth\EmailNotificationSecondaryAuthenticationProvider;
use MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;
use MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider;
use MediaWiki\Auth\ThrottlePreAuthenticationProvider;
use MediaWiki\Config\ConfigException;
use MediaWiki\Content\CssContentHandler;
use MediaWiki\Content\FallbackContentHandler;
use MediaWiki\Content\JavaScriptContentHandler;
use MediaWiki\Content\JsonContentHandler;
use MediaWiki\Content\TextContentHandler;
use MediaWiki\Content\WikitextContentHandler;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\JobQueue\JobQueueDB;
use MediaWiki\JobQueue\Jobs\AssembleUploadChunksJob;
use MediaWiki\JobQueue\Jobs\CategoryMembershipChangeJob;
use MediaWiki\JobQueue\Jobs\CdnPurgeJob;
use MediaWiki\JobQueue\Jobs\DeleteLinksJob;
use MediaWiki\JobQueue\Jobs\DeletePageJob;
use MediaWiki\JobQueue\Jobs\DoubleRedirectJob;
use MediaWiki\JobQueue\Jobs\HTMLCacheUpdateJob;
use MediaWiki\JobQueue\Jobs\NullJob;
use MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob;
use MediaWiki\JobQueue\Jobs\PublishStashedFileJob;
use MediaWiki\JobQueue\Jobs\RefreshLinksJob;
use MediaWiki\JobQueue\Jobs\RevertedTagUpdateJob;
use MediaWiki\JobQueue\Jobs\ThumbnailRenderJob;
use MediaWiki\JobQueue\Jobs\UploadFromUrlJob;
use MediaWiki\Logging\BlockLogFormatter;
use MediaWiki\Logging\ContentModelLogFormatter;
use MediaWiki\Logging\DeleteLogFormatter;
use MediaWiki\Logging\ImportLogFormatter;
use MediaWiki\Logging\InterwikiLogFormatter;
use MediaWiki\Logging\LogFormatter;
use MediaWiki\Logging\MergeLogFormatter;
use MediaWiki\Logging\MoveLogFormatter;
use MediaWiki\Logging\PatrolLogFormatter;
use MediaWiki\Logging\ProtectLogFormatter;
use MediaWiki\Logging\RenameuserLogFormatter;
use MediaWiki\Logging\RightsLogFormatter;
use MediaWiki\Logging\TagLogFormatter;
use MediaWiki\Logging\UploadLogFormatter;
use MediaWiki\Mail\EmaillingJob;
use MediaWiki\Password\Argon2Password;
use MediaWiki\Password\BcryptPassword;
use MediaWiki\Password\LayeredParameterizedPassword;
use MediaWiki\Password\MWOldPassword;
use MediaWiki\Password\MWSaltedPassword;
use MediaWiki\Password\PasswordPolicyChecks;
use MediaWiki\Password\Pbkdf2PasswordUsingOpenSSL;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\RCFeed\RedisPubSubFeedEngine;
use MediaWiki\RCFeed\UDPRCFeedEngine;
use MediaWiki\RecentChanges\RecentChangeNotifyJob;
use MediaWiki\RecentChanges\RecentChangesUpdateJob;
use MediaWiki\RenameUser\Job\RenameUserDerivedJob;
use MediaWiki\RenameUser\Job\RenameUserTableJob;
use MediaWiki\Request\WebRequest;
use MediaWiki\Settings\Source\JsonSchemaTrait;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\CentralId\LocalIdLookup;
use MediaWiki\User\Registration\LocalUserRegistrationProvider;
use MediaWiki\Watchlist\ActivityUpdateJob;
use MediaWiki\Watchlist\ClearUserWatchlistJob;
use MediaWiki\Watchlist\ClearWatchlistNotificationsJob;
use MediaWiki\Watchlist\WatchlistExpiryJob;
use ReflectionClass;
use SqlBagOStuff;
use UserEditCountInitJob;
use UserGroupExpiryJob;
use UserOptionsUpdateJob;
use Wikimedia\EventRelayer\EventRelayerNull;
use Wikimedia\ObjectCache\APCUBagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\MemcachedPeclBagOStuff;
use Wikimedia\ObjectCache\MemcachedPhpBagOStuff;

/**
 * This class contains schema declarations for all configuration variables
 * known to MediaWiki core. The schema definitions follow the JSON Schema
 * specification.
 *
 * @see https://json-schema.org/learn/getting-started-step-by-step.html
 * @see https://json-schema.org/understanding-json-schema/
 *
 * The following JSON schema keys are used by MediaWiki:
 * - default: the configuration variable's default value.
 * - type: identifies the allowed value type or types. In addition to JSON Schema types,
 *         PHPDoc style type definitions are supported for convenience.
 *         Note that 'array' must not be used for associative arrays.
 *         To avoid confusion, use 'list' for sequential arrays and 'map' for associative arrays
 *         with uniform values. The 'object' type should be used for structures that have a known
 *         set of meaningful properties, especially if each property may have a different kind
 *         of value.
 *         See {@link \MediaWiki\Settings\Source\JsonTypeHelper} for details.
 *
 * The following additional keys are used by MediaWiki:
 * - mergeStrategy: see the {@link \MediaWiki\Settings\Config\MergeStrategy}.
 * - dynamicDefault: Specified a callback that computes the effective default at runtime, based
 *   on the value of other config variables or on the system environment.
 *   See {@link \MediaWiki\Settings\Source\ReflectionSchemaSource}
 *   and {@link \MediaWiki\Settings\DynamicDefaultValues} for details.
 *
 * @note After changing this file, run maintenance/generateConfigSchema.php to update
 *       all the files derived from the information in MainConfigSchema.
 *
 * @since 1.39
 */
class MainConfigSchema {
	use JsonSchemaTrait;

	/**
	 * Returns a generator for iterating over all config settings and their default values.
	 * The primary use of this method is to import default values into local scope.
	 * @code
	 *   foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $var => $value ) {
	 *       $$var = $value;
	 *   }
	 * @endcode
	 *
	 * There should be no reason for application logic to do this.
	 *
	 * @note This method is relatively slow, it should not be used by
	 *       performance critical code. Application logic should generally
	 *       use ConfigSchema instead
	 *
	 * @param string $prefix A prefix to prepend to each setting name.
	 *        Typically, this will be "wg" when constructing global
	 *        variable names.
	 *
	 * @return Generator<string,mixed> $settingName => $defaultValue
	 */
	public static function listDefaultValues( string $prefix = '' ): Generator {
		$class = new ReflectionClass( self::class );
		foreach ( $class->getReflectionConstants() as $const ) {
			if ( !$const->isPublic() ) {
				continue;
			}

			$value = $const->getValue();

			if ( !is_array( $value ) ) {
				// Just in case we end up having some other kind of constant on this class.
				continue;
			}

			if ( isset( $value['obsolete'] ) ) {
				continue;
			}

			$name = $const->getName();
			yield "$prefix$name" => self::getDefaultFromJsonSchema( $value );
		}
	}

	/**
	 * Returns the default value of the given config setting.
	 *
	 * @note This method is relatively slow, it should not be used by
	 *       performance critical code. Application logic should generally
	 *       use ConfigSchema instead
	 *
	 * @param string $name The config setting name.
	 *
	 * @return mixed The given config setting's default value, or null
	 *         if no default value is specified in the schema.
	 */
	public static function getDefaultValue( string $name ) {
		$class = new ReflectionClass( self::class );
		if ( !$class->hasConstant( $name ) ) {
			throw new InvalidArgumentException( "Unknown setting: $name" );
		}
		$value = $class->getConstant( $name );

		if ( !is_array( $value ) ) {
			// Might happen if we end up having other kinds of constants on this class.
			throw new InvalidArgumentException( "Unknown setting: $name" );
		}

		return self::getDefaultFromJsonSchema( $value );
	}

	/***************************************************************************/
	/**
	 * Registry of factory functions to create config objects:
	 * The 'main' key must be set, and the value should be a valid
	 * callable.
	 *
	 * @since 1.23
	 */
	public const ConfigRegistry = [
		'default' => [
			'main' => 'GlobalVarConfig::newInstance',
		],
		'type' => 'map',
	];

	/**
	 * Name of the site. It must be changed in LocalSettings.php
	 */
	public const Sitename = [
		'default' => 'MediaWiki',
	];

	/***************************************************************************/
	// region   Server URLs and file paths
	/** @name   Server URLs and file paths
	 *
	 * In this section, a "path" is usually a host-relative URL, i.e. a URL without
	 * the host part, that starts with a slash. In most cases a full URL is also
	 * acceptable. A "directory" is a local file path.
	 *
	 * In both paths and directories, trailing slashes should not be included.
	 */

	/**
	 * URL of the server.
	 *
	 * **Example:**
	 * ```
	 * $wgServer = 'http://example.com';
	 * ```
	 *
	 * This must be set in LocalSettings.php. The MediaWiki installer does this
	 * automatically since 1.18.
	 *
	 * If you want to use protocol-relative URLs on your wiki, set this to a
	 * protocol-relative URL like '//example.com' and set $wgCanonicalServer
	 * to a fully qualified URL.
	 */
	public const Server = [
		'default' => false,
	];

	/**
	 * Canonical URL of the server, to use in IRC feeds and notification e-mails.
	 *
	 * Must be fully qualified, even if $wgServer is protocol-relative.
	 *
	 * Defaults to $wgServer, expanded to a fully qualified http:// URL if needed.
	 *
	 * @since 1.18
	 */
	public const CanonicalServer = [
		'default' => false,
	];

	/**
	 * Server name. This is automatically computed by parsing the bare
	 * hostname out of $wgCanonicalServer. It should not be customized.
	 *
	 * @since 1.24
	 */
	public const ServerName = [
		'default' => false,
	];

	/**
	 * When the wiki is running behind a proxy and this is set to true, assumes that the proxy
	 * exposes the wiki on the standard ports (443 for https and 80 for http).
	 *
	 * @since 1.26
	 */
	public const AssumeProxiesUseDefaultProtocolPorts = [
		'default' => true,
		'type' => 'boolean',
	];

	/**
	 * For installations where the canonical server is HTTP but HTTPS is optionally
	 * supported, you can specify a non-standard HTTPS port here. $wgServer should
	 * be a protocol-relative URL.
	 *
	 * If HTTPS is always used, just specify the port number in $wgServer.
	 *
	 * @see https://phabricator.wikimedia.org/T67184
	 * @since 1.24
	 */
	public const HttpsPort = [
		'default' => 443,
	];

	/**
	 * If this is true, when an insecure HTTP request is received, always redirect
	 * to HTTPS. This overrides and disables the preferhttps user preference, and it
	 * overrides $wgSecureLogin.
	 *
	 * $wgServer may be either https or protocol-relative. If $wgServer starts with
	 * "http://", an exception will be thrown.
	 *
	 * If a reverse proxy or CDN is used to forward requests from HTTPS to HTTP,
	 * the request header "X-Forwarded-Proto: https" should be sent to suppress
	 * the redirect.
	 *
	 * In addition to setting this to true, for optimal security, the web server
	 * should also be configured to send Strict-Transport-Security response headers.
	 *
	 * @since 1.35
	 */
	public const ForceHTTPS = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * The path we should point to.
	 *
	 * It might be a virtual path in case with use apache mod_rewrite for example.
	 *
	 * This *needs* to be set correctly.
	 *
	 * Other paths will be set to defaults based on it unless they are directly
	 * set in LocalSettings.php
	 */
	public const ScriptPath = [
		'default' => '/wiki',
	];

	/**
	 * Whether to support URLs like index.php/Page_title.
	 * The effective default value is determined at runtime:
	 * it will be enabled in environments where it is expected to be safe.
	 *
	 * Override this to false if $_SERVER['PATH_INFO'] contains unexpectedly
	 * incorrect garbage, or to true if it is really correct.
	 *
	 * The default $wgArticlePath will be set based on this value at runtime, but if
	 * you have customized it, having this incorrectly set to true can cause
	 * redirect loops when "pretty URLs" are used.
	 *
	 * @since 1.2.1
	 */
	public const UsePathInfo = [
		'dynamicDefault' => true,
	];

	public static function getDefaultUsePathInfo(): bool {
		// These often break when PHP is set up in CGI mode.
		// PATH_INFO *may* be correct if cgi.fix_pathinfo is set, but then again it may not;
		// lighttpd converts incoming path data to lowercase on systems
		// with case-insensitive filesystems, and there have been reports of
		// problems on Apache as well.
		return !str_contains( PHP_SAPI, 'cgi' ) && !str_contains( PHP_SAPI, 'apache2filter' ) &&
			!str_contains( PHP_SAPI, 'isapi' );
	}

	/**
	 * The URL path to index.php.
	 *
	 * Defaults to "{$wgScriptPath}/index.php".
	 */
	public const Script = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ScriptPath' ] ]
	];

	/**
	 * @param mixed $scriptPath Value of ScriptPath
	 * @return string
	 */
	public static function getDefaultScript( $scriptPath ): string {
		return "$scriptPath/index.php";
	}

	/**
	 * The URL path to load.php.
	 *
	 * Defaults to "{$wgScriptPath}/load.php".
	 *
	 * @since 1.17
	 */
	public const LoadScript = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ScriptPath' ] ]
	];

	/**
	 * @param mixed $scriptPath Value of ScriptPath
	 * @return string
	 */
	public static function getDefaultLoadScript( $scriptPath ): string {
		return "$scriptPath/load.php";
	}

	/**
	 * The URL path to the REST API.
	 * Defaults to "{$wgScriptPath}/rest.php"
	 *
	 * @since 1.34
	 */
	public const RestPath = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ScriptPath' ] ]
	];

	/**
	 * @param mixed $scriptPath Value of ScriptPath
	 * @return string
	 */
	public static function getDefaultRestPath( $scriptPath ): string {
		return "$scriptPath/rest.php";
	}

	/**
	 * The URL path of the skins directory.
	 *
	 * Defaults to "{$wgResourceBasePath}/skins".
	 *
	 * @since 1.3
	 */
	public const StylePath = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ResourceBasePath' ] ]
	];

	/**
	 * @param mixed $resourceBasePath Value of ResourceBasePath
	 * @return string
	 */
	public static function getDefaultStylePath( $resourceBasePath ): string {
		return "$resourceBasePath/skins";
	}

	/**
	 * The URL path of the skins directory. Should not point to an external domain.
	 *
	 * Defaults to "{$wgScriptPath}/skins".
	 *
	 * @since 1.17
	 */
	public const LocalStylePath = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ScriptPath' ] ]
	];

	/**
	 * @param mixed $scriptPath Value of ScriptPath
	 * @return string
	 */
	public static function getDefaultLocalStylePath( $scriptPath ): string {
		// Avoid ResourceBasePath here since that may point to a different domain (e.g. CDN)
		return "$scriptPath/skins";
	}

	/**
	 * The URL path of the extensions directory.
	 *
	 * Defaults to "{$wgResourceBasePath}/extensions".
	 *
	 * @since 1.16
	 */
	public const ExtensionAssetsPath = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ResourceBasePath' ] ]
	];

	/**
	 * @param mixed $resourceBasePath Value of ResourceBasePath
	 * @return string
	 */
	public static function getDefaultExtensionAssetsPath( $resourceBasePath ): string {
		return "$resourceBasePath/extensions";
	}

	/**
	 * Extensions directory in the file system.
	 *
	 * Defaults to "{$IP}/extensions" in Setup.php
	 *
	 * @note This configuration variable is used to locate extensions while loading settings.
	 * @since 1.25
	 */
	public const ExtensionDirectory = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Skins directory in the file system.
	 *
	 * Defaults to "{$IP}/skins" in Setup.php.
	 *
	 * @note This configuration variable is used to locate skins while loading settings.
	 * @since 1.3
	 */
	public const StyleDirectory = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * The URL path for primary article page views. This path should contain $1,
	 * which is replaced by the article title.
	 *
	 * Defaults to "{$wgScript}/$1" or "{$wgScript}?title=$1",
	 * depending on $wgUsePathInfo.
	 */
	public const ArticlePath = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'Script', 'UsePathInfo' ] ]
	];

	/**
	 * @param string $script Value of Script
	 * @param mixed $usePathInfo Value of UsePathInfo
	 * @return string
	 */
	public static function getDefaultArticlePath( string $script, $usePathInfo ): string {
		if ( $usePathInfo ) {
			return "$script/$1";
		}
		return "$script?title=$1";
	}

	/**
	 * The URL path for the images directory.
	 *
	 * Defaults to "{$wgScriptPath}/images".
	 */
	public const UploadPath = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ScriptPath' ] ]
	];

	/**
	 * @param mixed $scriptPath Value of ScriptPath
	 * @return string
	 */
	public static function getDefaultUploadPath( $scriptPath ): string {
		return "$scriptPath/images";
	}

	/**
	 * The base path for img_auth.php. This is used to interpret the request URL
	 * for requests to img_auth.php that do not match the base upload path. If
	 * false, "{$wgScriptPath}/img_auth.php" is used.
	 *
	 * Normally, requests to img_auth.php have a REQUEST_URI which matches
	 * $wgUploadPath, and in that case, setting this should not be necessary.
	 * This variable is used in case img_auth.php is accessed via a different path
	 * than $wgUploadPath.
	 *
	 * @since 1.35
	 */
	public const ImgAuthPath = [
		'default' => false,
	];

	/**
	 * The base path for thumb_handler.php. This is used to interpret the request URL
	 * for requests to thumb_handler.php that do not match the base upload path.
	 *
	 * @since 1.36
	 */
	public const ThumbPath = [
		'default' => false,
	];

	/**
	 * The filesystem path of the images directory.
	 *
	 * Defaults to "{$IP}/images" in Setup.php.
	 */
	public const UploadDirectory = [
		'default' => false,
		'type' => '?string|false',
	];

	/**
	 * Directory where the cached page will be saved.
	 *
	 * Defaults to "{$wgUploadDirectory}/cache".
	 */
	public const FileCacheDirectory = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'UploadDirectory' ] ]
	];

	/**
	 * @param mixed $uploadDirectory Value of UploadDirectory
	 * @return string
	 */
	public static function getDefaultFileCacheDirectory( $uploadDirectory ): string {
		return "$uploadDirectory/cache";
	}

	/**
	 * The URL path of the wiki logo. The logo size should be 135x135 pixels.
	 *
	 * Defaults to "$wgResourceBasePath/resources/assets/change-your-logo.svg".
	 * Developers should retrieve this logo (and other variants) using
	 * the static function MediaWiki\ResourceLoader\SkinModule::getAvailableLogos
	 * Ignored if $wgLogos is set.
	 */
	public const Logo = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'ResourceBasePath' ] ]
	];

	/**
	 * @param mixed $resourceBasePath Value of ResourceBasePath
	 * @return string
	 */
	public static function getDefaultLogo( $resourceBasePath ): string {
		return "$resourceBasePath/resources/assets/change-your-logo.svg";
	}

	/**
	 * Specification for different versions of the wiki logo.
	 *
	 * This is an array which should have the following k/v pairs:
	 * All path values can be either absolute or relative URIs
	 *
	 * The `1x` key is a path to the 1x version of square logo (should be 135x135 pixels)
	 * The `1.5x` key is a path to the 1.5x version of square logo
	 * The `2x` key is a path to the 2x version of square logo
	 * The `svg` key is a path to the svg version of square logo
	 * The `icon` key is a path to the version of the logo without wordmark and tagline
	 * The `wordmark` key may be null or an array with the following fields
	 *  - `src` path to wordmark version
	 *  - `1x` path to svg wordmark version (if you want to
	 *     support browsers with SVG support with an SVG logo)
	 *  - `width` width of the logo in pixels
	 *  - `height` height of the logo in pixels
	 * The `tagline` key may be null or array with the following fields
	 *  - `src` path to tagline image
	 *  - `width` width of the tagline in pixels
	 *  - `height` height of the tagline in pixels
	 *
	 *
	 * @par Example:
	 * @code
	 * $wgLogos = [
	 *    '1x' => 'path/to/1x_version.png',
	 *    '1.5x' => 'path/to/1.5x_version.png',
	 *    '2x' => 'path/to/2x_version.png',
	 *    'svg' => 'path/to/svg_version.svg',
	 *    'icon' => 'path/to/icon.png',
	 *    'wordmark' => [
	 *      'src' => 'path/to/wordmark_version.png',
	 *      '1x' => 'path/to/wordmark_version.svg',
	 *      'width' => 135,
	 *      'height' => 20,
	 *    ],
	 *    'tagline' => [
	 *      'src' => 'path/to/tagline_version.png',
	 *      'width' => 135,
	 *      'height' => 15,
	 *    ]
	 * ];
	 * @endcode
	 *
	 * Defaults to [ "1x" => $wgLogo ],
	 *   or [ "1x" => "$wgResourceBasePath/resources/assets/change-your-logo.svg" ] if $wgLogo is not set.
	 * @since 1.35
	 */
	public const Logos = [
		'default' => false,
		'type' => 'map|false',
	];

	/**
	 * The URL path of the icon.
	 *
	 * @since 1.6
	 */
	public const Favicon = [
		'default' => '/favicon.ico',
	];

	/**
	 * The URL path of the icon for iPhone and iPod Touch web app bookmarks.
	 *
	 * Defaults to no icon.
	 *
	 * @since 1.12
	 */
	public const AppleTouchIcon = [
		'default' => false,
	];

	/**
	 * Value for the referrer policy meta tag.
	 *
	 * One or more of the values defined in the Referrer Policy specification:
	 * https://w3c.github.io/webappsec-referrer-policy/
	 * ('no-referrer', 'no-referrer-when-downgrade', 'same-origin',
	 * 'origin', 'strict-origin', 'origin-when-cross-origin',
	 * 'strict-origin-when-cross-origin', or 'unsafe-url')
	 * Setting it to false prevents the meta tag from being output
	 * (which results in falling back to the Referrer-Policy header,
	 * or 'no-referrer-when-downgrade' if that's not set either.)
	 * Setting it to an array (supported since 1.31) will create a meta tag for
	 * each value, in the reverse of the order (meaning that the first array element
	 * will be the default and the others used as fallbacks for browsers which do not
	 * understand it).
	 *
	 * @since 1.25
	 */
	public const ReferrerPolicy = [
		'default' => false,
		'type' => 'list|string|false',
	];

	/**
	 * The local filesystem path to a temporary directory. This must not be web-accessible.
	 *
	 * When this setting is set to false, its value will automatically be decided
	 * through the first call to wfTempDir(). See that method's implementation for
	 * the actual detection logic.
	 *
	 * To find the temporary path for the current wiki, developers must not use
	 * this variable directly. Use the global function wfTempDir() instead.
	 *
	 * The temporary directory is expected to be shared with other applications,
	 * including other MediaWiki instances (which might not run the same version
	 * or configuration). When storing files here, take care to avoid conflicts
	 * with other instances of MediaWiki. For example, when caching the result
	 * of a computation, the file name should incorporate the input of the
	 * computation so that it cannot be confused for the result of a similar
	 * computation by another MediaWiki instance.
	 *
	 * @see \wfTempDir()
	 * @note Default changed to false in MediaWiki 1.20.
	 */
	public const TmpDirectory = [
		'default' => false,
	];

	/**
	 * If set, this URL is added to the start of $wgUploadPath to form a complete
	 * upload URL.
	 *
	 * @since 1.4
	 */
	public const UploadBaseUrl = [
		'default' => '',
	];

	/**
	 * To enable remote on-demand scaling, set this to the thumbnail base URL.
	 *
	 * Full thumbnail URL will be like $wgUploadStashScalerBaseUrl/e/e6/Foo.jpg/123px-Foo.jpg
	 * where 'e6' are the first two characters of the MD5 hash of the file name.
	 *
	 * @deprecated since 1.36 Use thumbProxyUrl in $wgLocalFileRepo
	 *
	 * If $wgUploadStashScalerBaseUrl and thumbProxyUrl are both false, thumbs are
	 * rendered locally as needed.
	 * @since 1.17
	 */
	public const UploadStashScalerBaseUrl = [
		'default' => false,
		'deprecated' => 'since 1.36 Use thumbProxyUrl in $wgLocalFileRepo',
	];

	/**
	 * To set 'pretty' URL paths for actions other than
	 * plain page views, add to this array.
	 *
	 * **Example:**
	 * Set pretty URL for the edit action:
	 *
	 * ```
	 *   'edit' => "$wgScriptPath/edit/$1"
	 * ```
	 * There must be an appropriate script or rewrite rule in place to handle these URLs.
	 *
	 * @since 1.5
	 */
	public const ActionPaths = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * When enabled, the domain root will show the wiki's main page,
	 * instead of redirecting to the main page.
	 *
	 * @since 1.34
	 */
	public const MainPageIsDomainRoot = [
		'default' => false,
		'type' => 'boolean',
	];

	// endregion -- end of server URLs and file paths

	/***************************************************************************/
	// region   Files and file uploads
	/** @name   Files and file uploads */

	/**
	 * Allow users to upload files.
	 *
	 * Use $wgLocalFileRepo to control how and where uploads are stored.
	 * Disabled by default as for security reasons.
	 * See <https://www.mediawiki.org/wiki/Manual:Configuring_file_uploads>.
	 *
	 * @since 1.5
	 */
	public const EnableUploads = [
		'default' => false,
	];

	/**
	 * The maximum age of temporary (incomplete) uploaded files
	 */
	public const UploadStashMaxAge = [
		'default' => 6 * 3600, // 6 hours
	];

	/**
	 * Enable deferred upload tasks that use the job queue.
	 *
	 * Only enable this if job runners are set up for both the
	 * 'AssembleUploadChunks','PublishStashedFile' and 'UploadFromUrl' job types.
	 */
	public const EnableAsyncUploads = [
		'default' => false,
	];

	/**
	 * Enable the async processing of upload by url in Special:Upload.
	 *
	 * Only works if EnableAsyncUploads is also enabled
	 */
	public const EnableAsyncUploadsByURL = [
		'default' => false,
	];

	/**
	 * To disable file delete/restore temporarily
	 */
	public const UploadMaintenance = [
		'default' => false,
	];

	/**
	 * Additional characters that are not allowed in filenames. They are replaced with '-' when
	 * uploading. Like $wgLegalTitleChars, this is a regexp character class.
	 *
	 * Slashes and backslashes are disallowed regardless of this setting, but included here for
	 * completeness.
	 *
	 * @deprecated since 1.41; no longer customizable
	 */
	public const IllegalFileChars = [
		'default' => ':\\/\\\\',
		'deprecated' => 'since 1.41; no longer customizable',
	];

	/**
	 * What directory to place deleted uploads in.
	 *
	 * Defaults to "{$wgUploadDirectory}/deleted".
	 */
	public const DeletedDirectory = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'UploadDirectory' ] ]
	];

	/**
	 * @param mixed $uploadDirectory Value of UploadDirectory
	 * @return string
	 */
	public static function getDefaultDeletedDirectory( $uploadDirectory ): string {
		return "$uploadDirectory/deleted";
	}

	/**
	 * Set this to true if you use img_auth and want the user to see details on why access failed.
	 */
	public const ImgAuthDetails = [
		'default' => false,
	];

	/**
	 * Map of relative URL directories to match to internal mwstore:// base storage paths.
	 *
	 * For img_auth.php requests, everything after "img_auth.php/" is checked to see
	 * if starts with any of the prefixes defined here. The prefixes should not overlap.
	 * The prefix that matches has a corresponding storage path, which the rest of the URL
	 * is assumed to be relative to. The file at that path (or a 404) is send to the client.
	 *
	 * Example:
	 * $wgImgAuthUrlPathMap['/timeline/'] = 'mwstore://local-fs/timeline-render/';
	 * The above maps ".../img_auth.php/timeline/X" to "mwstore://local-fs/timeline-render/".
	 * The name "local-fs" should correspond by name to an entry in $wgFileBackends.
	 *
	 * @see self::FileBackends
	 */
	public const ImgAuthUrlPathMap = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * File repository structures
	 *
	 * $wgLocalFileRepo is a single repository structure, and $wgForeignFileRepos is
	 * an array of such structures. Each repository structure is an associative
	 * array of properties configuring the repository.
	 *
	 * Properties required for all repos:
	 *   - class            The class name for the repository. May come from the core or an extension.
	 *                      The core repository classes are FileRepo, LocalRepo, ForeignDBRepo.
	 *
	 *   - name             A unique name for the repository (but $wgLocalFileRepo should be 'local').
	 *                      The name should consist of alpha-numeric characters.
	 *
	 * Optional common properties:
	 *   - backend          A file backend name (see $wgFileBackends). If not specified, or
	 *                      if the name is not present in $wgFileBackends, an FSFileBackend
	 *                      will automatically be configured.
	 *   - lockManager      If a file backend is automatically configured, this will be lock
	 *                      manager name used. A lock manager named in $wgLockManagers, or one of
	 *                      the default lock managers "fsLockManager" or "nullLockManager". Default
	 *                      "fsLockManager".
	 *   - favicon          URL to a favicon. This is exposed via FileRepo::getInfo and
	 *                      ApiQueryFileRepoInfo. Originally for use by MediaViewer (T77093).
	 *
	 * For most core repos:
	 *   - zones            Associative array of zone names that each map to an array with:
	 *                          container  : backend container name the zone is in
	 *                          directory  : root path within container for the zone
	 *                          url        : base URL to the root of the zone
	 *                          urlsByExt  : map of file extension types to base URLs
	 *                                       (useful for using a different cache for videos)
	 *                      Zones default to using "<repo name>-<zone name>" as the container name
	 *                      and default to using the container root as the zone's root directory.
	 *                      Nesting of zone locations within other zones should be avoided.
	 *   - url              Public zone URL. The 'zones' settings take precedence.
	 *   - hashLevels       The number of directory levels for hash-based division of files.
	 *
	 *                      Set this to 0 if you do not want MediaWiki to divide your images
	 *                      directory into many subdirectories.
	 *
	 *                      It is recommended to leave this enabled. In previous versions of
	 *                      MediaWiki, some users set this to false to allow images to be added to
	 *                      the wiki by copying them into $wgUploadDirectory and then running
	 *                      maintenance/rebuildImages.php to register them in the database.
	 *                      This is no longer supported, use maintenance/importImages.php instead.
	 *
	 *                      Default: 2.
	 *   - deletedHashLevels
	 *                      Optional 'hashLevels' override for the 'deleted' zone.
	 *   - thumbScriptUrl   The URL for thumb.php (optional, not recommended)
	 *   - transformVia404  Whether to skip media file transformation on parse and rely on a 404
	 *                      handler instead.
	 *   - thumbProxyUrl    Optional. URL of where to proxy thumb.php requests to. This is
	 *                      also used internally for remote thumbnailing of upload stash files.
	 *                      Example: http://127.0.0.1:8888/wiki/dev/thumb/
	 *   - thumbProxySecret Optional value of the X-Swift-Secret header to use in requests to
	 *                      thumbProxyUrl
	 *   - disableLocalTransform
	 *                      If present and true, local image scaling will be disabled. If attempted,
	 *                      it will show an error to the user and log an error message. To avoid an
	 *                      error, thumbProxyUrl must be set, as well as either transformVia404
	 *                      (preferred) or thumbScriptUrl.
	 *   - initialCapital   Equivalent to $wgCapitalLinks (or $wgCapitalLinkOverrides[NS_FILE],
	 *                      determines whether filenames implicitly start with a capital letter.
	 *                      The current implementation may give incorrect description page links
	 *                      when the local $wgCapitalLinks and initialCapital are mismatched.
	 *   - pathDisclosureProtection
	 *                      May be 'paranoid' to remove all parameters from error messages, 'none' to
	 *                      leave the paths in unchanged, or 'simple' to replace paths with
	 *                      placeholders. Default for LocalRepo is 'simple'.
	 *   - fileMode         This allows wikis to set the file mode when uploading/moving files. Default
	 *                      is 0644.
	 *   - directory        The local filesystem directory where public files are stored. Not used for
	 *                      some remote repos.
	 *   - thumbDir         The base thumbnail directory. Defaults to "<directory>/thumb".
	 *   - thumbUrl         The base thumbnail URL. Defaults to "<url>/thumb".
	 *   - isPrivate        Set this if measures should always be taken to keep the files private.
	 *                      One should not trust this to assure that the files are not web readable;
	 *                      the server configuration should be done manually depending on the backend.
	 *   - useJsonMetadata  Whether handler metadata should be stored in JSON format. Default: true.
	 *   - useSplitMetadata Whether handler metadata should be split up and stored in the text table.
	 *                      Default: false.
	 *   - splitMetadataThreshold
	 *                      If the media handler opts in, large metadata items will be split into a
	 *                      separate blob in the database if the item is larger than this threshold.
	 *                      Default: 1000
	 *   - updateCompatibleMetadata
	 *                      When true, image metadata will be upgraded by reloading it from the original
	 *                      file, if the handler indicates that it is out of date.
	 *
	 *                      By default, when purging a file or otherwise refreshing file metadata, it
	 *                      is only reloaded when the metadata is invalid. Valid data originally loaded
	 *                      by a current or older compatible version is left unchanged. Enable this
	 *                      to also reload and upgrade metadata that was stored by an older compatible
	 *                      version. See also MediaHandler::isMetadataValid, and RefreshImageMetadata.
	 *
	 *                      Default: false.
	 *
	 *   - reserializeMetadata
	 *                      If true, image metadata will be automatically rewritten to the database
	 *                      if its serialization format is out of date. Default: false
	 *
	 * These settings describe a foreign MediaWiki installation. They are optional, and will be ignored
	 * for local repositories:
	 *   - descBaseUrl       URL of image description pages, e.g. https://en.wikipedia.org/wiki/File:
	 *   - scriptDirUrl      URL of the MediaWiki installation, equivalent to $wgScriptPath, e.g.
	 *                       https://en.wikipedia.org/w
	 *   - articleUrl        Equivalent to $wgArticlePath, e.g. https://en.wikipedia.org/wiki/$1
	 *   - fetchDescription  Fetch the text of the remote file description page and display them
	 *                       on the local wiki.
	 *   - abbrvThreshold    File names over this size will use the short form of thumbnail names.
	 *                       Short thumbnail names only have the width, parameters, and the extension.
	 *
	 * ForeignDBRepo:
	 *   - dbType, dbServer, dbUser, dbPassword, dbName, dbFlags
	 *                       equivalent to the corresponding member of $wgDBservers
	 *   - tablePrefix       Table prefix, the foreign wiki's $wgDBprefix
	 *   - hasSharedCache    Set to true if the foreign wiki's $wgMainCacheType is identical to,
	 *                       and accessible from, this wiki.
	 *
	 * ForeignAPIRepo:
	 *   - apibase              Use for the foreign API's URL
	 *   - apiThumbCacheExpiry  How long to locally cache thumbs for
	 *
	 * If you leave $wgLocalFileRepo set to false, Setup will fill in appropriate values.
	 * Otherwise, set $wgLocalFileRepo to a repository structure as described above.
	 * If you set $wgUseInstantCommons to true, it will add an entry for Commons.
	 * If you set $wgForeignFileRepos to an array of repository structures, those will
	 * be searched after the local file repo.
	 * Otherwise, you will only have access to local media files.
	 *
	 * @see \FileRepo::__construct for the default options.
	 * @see Setup.php for an example usage and default initialization.
	 */
	public const LocalFileRepo = [
		'default' => false,
		'type' => 'map|false',
		'dynamicDefault' => [ 'use' => [ 'UploadDirectory', 'ScriptPath', 'Favicon', 'UploadBaseUrl',
			'UploadPath', 'HashedUploadDirectory', 'ThumbnailScriptPath',
			'GenerateThumbnailOnParse', 'DeletedDirectory', 'UpdateCompatibleMetadata' ] ],
	];

	public static function getDefaultLocalFileRepo(
		string $uploadDirectory, string $scriptPath, string $favicon, string $uploadBaseUrl, string $uploadPath,
		bool $hashedUploadDirectory, string|false $thumbnailScriptPath, bool $generateThumbnailOnParse, string $deletedDirectory,
		bool $updateCompatibleMetadata
	): array {
		return [
			'class' => LocalRepo::class,
			'name' => 'local',
			'directory' => $uploadDirectory,
			'scriptDirUrl' => $scriptPath,
			'favicon' => $favicon,
			'url' => $uploadBaseUrl ? $uploadBaseUrl . $uploadPath : $uploadPath,
			'hashLevels' => $hashedUploadDirectory ? 2 : 0,
			'thumbScriptUrl' => $thumbnailScriptPath,
			'transformVia404' => !$generateThumbnailOnParse,
			'deletedDir' => $deletedDirectory,
			'deletedHashLevels' => $hashedUploadDirectory ? 3 : 0,
			'updateCompatibleMetadata' => $updateCompatibleMetadata,
			'reserializeMetadata' => $updateCompatibleMetadata,
		];
	}

	/**
	 * Enable the use of files from one or more other wikis.
	 *
	 * If you operate multiple wikis, you can declare a shared upload path here.
	 * Uploads to the local wiki will NOT be stored here - See $wgLocalFileRepo
	 * and $wgUploadDirectory for that.
	 *
	 * The wiki will only consider the foreign repository if no file of the given name
	 * is found in the local repository (e.g. via `[[File:..]]` syntax).
	 *
	 * @since 1.11
	 * @see self::LocalFileRepo
	 */
	public const ForeignFileRepos = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Use Wikimedia Commons as a foreign file repository.
	 *
	 * This is a shortcut for adding an entry to $wgForeignFileRepos
	 * for https://commons.wikimedia.org, using ForeignAPIRepo with the
	 * default settings.
	 *
	 * @since 1.16
	 */
	public const UseInstantCommons = [
		'default' => false,
	];

	/**
	 * Shortcut for adding an entry to $wgForeignFileRepos.
	 *
	 * Uses the following variables:
	 *
	 * - directory: $wgSharedUploadDirectory.
	 * - url: $wgSharedUploadPath.
	 * - hashLevels: Based on $wgHashedSharedUploadDirectory.
	 * - thumbScriptUrl: $wgSharedThumbnailScriptPath.
	 * - transformVia404: Based on $wgGenerateThumbnailOnParse.
	 * - descBaseUrl: $wgRepositoryBaseUrl.
	 * - fetchDescription: $wgFetchCommonsDescriptions.
	 *
	 * If $wgSharedUploadDBname is set, it uses the ForeignDBRepo
	 * class, with also the following variables:
	 *
	 * - dbName: $wgSharedUploadDBname.
	 * - dbType: $wgDBtype.
	 * - dbServer: $wgDBserver.
	 * - dbUser: $wgDBuser.
	 * - dbPassword: $wgDBpassword.
	 * - dbFlags: Based on $wgDebugDumpSql.
	 * - tablePrefix: $wgSharedUploadDBprefix,
	 * - hasSharedCache: $wgCacheSharedUploads.
	 *
	 * @since 1.3
	 */
	public const UseSharedUploads = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Shortcut for the 'directory' setting of $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.3
	 */
	public const SharedUploadDirectory = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Shortcut for the 'url' setting of $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.3
	 */
	public const SharedUploadPath = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Shortcut for the 'hashLevels' setting of $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.3
	 */
	public const HashedSharedUploadDirectory = [
		'default' => true,
		'type' => 'boolean',
	];

	/**
	 * Shortcut for the 'descBaseUrl' setting of $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.5
	 */
	public const RepositoryBaseUrl = [
		'default' => 'https://commons.wikimedia.org/wiki/File:',
	];

	/**
	 * Shortcut for the 'fetchDescription' setting of $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.5
	 */
	public const FetchCommonsDescriptions = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Shortcut for the ForeignDBRepo 'dbName' setting in $wgForeignFileRepos.
	 *
	 * Set this to false if the uploads do not come from a wiki.
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.4
	 */
	public const SharedUploadDBname = [
		'default' => false,
		'type' => 'false|string',
	];

	/**
	 * Shortcut for the ForeignDBRepo 'tablePrefix' setting in $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.5
	 */
	public const SharedUploadDBprefix = [
		'default' => '',
		'type' => 'string',
	];

	/**
	 * Shortcut for the ForeignDBRepo 'hasSharedCache' setting in $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.5
	 */
	public const CacheSharedUploads = [
		'default' => true,
		'type' => 'boolean',
	];

	/**
	 * Array of foreign file repo names (set in $wgForeignFileRepos above) that
	 * are allowable upload targets. These wikis must have some method of
	 * authentication (i.e. CentralAuth), and be CORS-enabled for this wiki.
	 *
	 * The string 'local' signifies the default local file repository.
	 *
	 * Example:
	 * $wgForeignUploadTargets = [ 'shared' ];
	 */
	public const ForeignUploadTargets = [
		'default' => [ 'local', ],
		'type' => 'list',
	];

	/**
	 * Configuration for file uploads using the embeddable upload dialog
	 * (https://www.mediawiki.org/wiki/Upload_dialog).
	 *
	 * This applies also to foreign uploads to this wiki (the configuration is loaded by remote
	 * wikis using the action=query&meta=siteinfo API).
	 *
	 * See below for documentation of each property. None of the properties may be omitted.
	 */
	public const UploadDialog = [
		'default' =>
			[
				'fields' =>
					[
						'description' => true,
						'date' => false,
						'categories' => false,
					],
				'licensemessages' =>
					[
						'local' => 'generic-local',
						'foreign' => 'generic-foreign',
					],
				'comment' =>
					[
						'local' => '',
						'foreign' => '',
					],
				'format' =>
					[
						'filepage' => '$DESCRIPTION',
						'description' => '$TEXT',
						'ownwork' => '',
						'license' => '',
						'uncategorized' => '',
					],
			],
		'type' => 'map',
	];

	/**
	 * File backend structure configuration.
	 *
	 * This is an array of file backend configuration arrays.
	 * Each backend configuration has the following parameters:
	 *  - name        : A unique name for the backend
	 *  - class       : The file backend class to use
	 *  - wikiId      : A unique string that identifies the wiki (container prefix)
	 *  - lockManager : The name of a lock manager (see $wgLockManagers) [optional]
	 *
	 * See FileBackend::__construct() for more details.
	 * Additional parameters are specific to the file backend class used.
	 * These settings should be global to all wikis when possible.
	 *
	 * FileBackendMultiWrite::__construct() is augmented with a 'template' option that
	 * can be used in any of the values of the 'backends' array. Its value is the name of
	 * another backend in $wgFileBackends. When set, it pre-fills the array with all of the
	 * configuration of the named backend. Explicitly set values in the array take precedence.
	 *
	 * There are two particularly important aspects about each backend:
	 *   - a) Whether it is fully qualified or wiki-relative.
	 *        By default, the paths of files are relative to the current wiki,
	 *        which works via prefixing them with the current wiki ID when accessed.
	 *        Setting 'domainId' forces the backend to be fully qualified by prefixing
	 *        all paths with the specified value instead. This can be useful if
	 *        multiple wikis need to share the same data. Note that 'name' is *not*
	 *        part of any prefix and thus should not be relied upon for namespacing.
	 *   - b) Whether it is only defined for some wikis or is defined on all
	 *        wikis in the wiki farm. Defining a backend globally is useful
	 *        if multiple wikis need to share the same data.
	 * One should be aware of these aspects when configuring a backend for use with
	 * any basic feature or plugin. For example, suppose an extension stores data for
	 * different wikis in different directories and sometimes needs to access data from
	 * a foreign wiki's directory in order to render a page on given wiki. The extension
	 * would need a fully qualified backend that is defined on all wikis in the wiki farm.
	 */
	public const FileBackends = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * List of lock manager backend configurations.
	 *
	 * Each backend configuration has the following parameters:
	 *  - name  : A unique name for the lock manager
	 *  - class : The lock manager class to use
	 *
	 * See LockManager::__construct() for more details.
	 * Additional parameters are specific to the lock manager class used.
	 * These settings should be global to all wikis.
	 */
	public const LockManagers = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Whether to show Exif data.
	 * The effective default value is determined at runtime:
	 * enabled if PHP's EXIF extension module is loaded.
	 *
	 * Requires PHP's Exif extension: https://www.php.net/manual/en/ref.exif.php
	 *
	 * @note FOR WINDOWS USERS:
	 * To enable Exif functions, add the following line to the "Windows
	 * extensions" section of php.ini:
	 *
	 * ```{.ini}
	 * extension=extensions/php_exif.dll
	 * ```
	 */
	public const ShowEXIF = [
		'dynamicDefault' => [ 'callback' => [ self::class, 'getDefaultShowEXIF' ] ],
	];

	public static function getDefaultShowEXIF(): bool {
		return function_exists( 'exif_read_data' );
	}

	/**
	 * Shortcut for the 'updateCompatibleMetadata' setting of $wgLocalFileRepo.
	 */
	public const UpdateCompatibleMetadata = [
		'default' => false,
	];

	/**
	 * Allow for upload to be copied from an URL.
	 *
	 * The timeout for copy uploads is set by $wgCopyUploadTimeout.
	 * You have to assign the user right 'upload_by_url' to a user group, to use this.
	 */
	public const AllowCopyUploads = [
		'default' => false,
	];

	/**
	 * A list of domains copy uploads can come from
	 *
	 * @since 1.20
	 */
	public const CopyUploadsDomains = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Enable copy uploads from Special:Upload. $wgAllowCopyUploads must also be
	 * true. If $wgAllowCopyUploads is true, but this is false, you will only be
	 * able to perform copy uploads from the API or extensions (e.g. UploadWizard).
	 */
	public const CopyUploadsFromSpecialUpload = [
		'default' => false,
	];

	/**
	 * Proxy to use for copy upload requests.
	 *
	 * @since 1.20
	 */
	public const CopyUploadProxy = [
		'default' => false,
	];

	/**
	 * Different timeout for upload by url
	 * This could be useful since when fetching large files, you may want a
	 * timeout longer than the default $wgHTTPTimeout. False means fallback
	 * to default.
	 *
	 * @since 1.22
	 */
	public const CopyUploadTimeout = [
		'default' => false,
		'type' => 'false|integer',
	];

	/**
	 * If true, the value of $wgCopyUploadsDomains will be merged with the
	 * contents of MediaWiki:Copyupload-allowed-domains.
	 *
	 * @since 1.39
	 */
	public const CopyUploadAllowOnWikiDomainConfig = [
		'default' => false,
	];

	/**
	 * Max size for uploads, in bytes.
	 *
	 * If not set to an array, applies to all uploads. If set to an array, per upload
	 * type maximums can be set, using the file and url keys. If the `*` key is set
	 * this value will be used as maximum for non-specified types.
	 *
	 * The below example would set the maximum for all uploads to 250 KiB except,
	 * for upload-by-url, which would have a maximum of 500 KiB.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgMaxUploadSize = [
	 *     '*' => 250 * 1024,
	 *     'url' => 500 * 1024,
	 * ];
	 * ```
	 * Default: 100 MiB.
	 */
	public const MaxUploadSize = [
		'default' => 1024 * 1024 * 100,
	];

	/**
	 * Minimum upload chunk size, in bytes.
	 *
	 * When using chunked upload, non-final chunks smaller than this will be rejected.
	 *
	 * Note that this may be further reduced by the `upload_max_filesize` and
	 * `post_max_size` PHP settings. Use ApiUpload::getMinUploadChunkSize to
	 * get the effective minimum chunk size used by MediaWiki.
	 *
	 * Default: 1 KiB.
	 *
	 * @since 1.26
	 * @see \ApiUpload::getMinUploadChunkSize
	 */
	public const MinUploadChunkSize = [
		'default' => 1024,
	];

	/**
	 * Point the upload navigation link to an external URL
	 * Useful if you want to use a shared repository by default
	 * without disabling local uploads (use $wgEnableUploads = false for that).
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgUploadNavigationUrl = 'https://commons.wikimedia.org/wiki/Special:Upload';
	 * ```
	 */
	public const UploadNavigationUrl = [
		'default' => false,
	];

	/**
	 * Point the upload link for missing files to an external URL, as with
	 * $wgUploadNavigationUrl. The URL will get "(?|&)wpDestFile=<filename>"
	 * appended to it as appropriate.
	 */
	public const UploadMissingFileUrl = [
		'default' => false,
	];

	/**
	 * Give a path here to use thumb.php for thumbnail generation on client
	 * request, instead of generating them on render and outputting a static URL.
	 *
	 * This is necessary if some of your apache servers don't have read/write
	 * access to the thumbnail path.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgThumbnailScriptPath = "{$wgScriptPath}/thumb.php";
	 * ```
	 */
	public const ThumbnailScriptPath = [
		'default' => false,
	];

	/**
	 * Shortcut for the 'thumbScriptUrl' setting of $wgForeignFileRepos.
	 *
	 * Only used if $wgUseSharedUploads is enabled.
	 *
	 * @since 1.3
	 */
	public const SharedThumbnailScriptPath = [
		'default' => false,
		'type' => 'string|false',
	];

	/**
	 * Shortcut for setting `hashLevels=2` in $wgLocalFileRepo.
	 *
	 * @note Only used if $wgLocalFileRepo is not set.
	 */
	public const HashedUploadDirectory = [
		'default' => true,
		'type' => 'boolean',
	];

	/**
	 * This is the list of preferred extensions for uploading files. Uploading files
	 * with extensions not in this list will trigger a warning.
	 *
	 * @warning If you add any OpenOffice or Microsoft Office file formats here,
	 * such as odt or doc, and untrusted users are allowed to upload files, then
	 * your wiki will be vulnerable to cross-site request forgery (CSRF).
	 */
	public const FileExtensions = [
		'default' => [ 'png', 'gif', 'jpg', 'jpeg', 'webp', ],
		'type' => 'list',
	];

	/**
	 * Files with these extensions will never be allowed as uploads.
	 *
	 * An array of file extensions to prevent being uploaded. You should
	 * append to this array if you want to prevent additional file extensions.
	 *
	 * @since 1.37; previously $wgFileBlacklist
	 */
	public const ProhibitedFileExtensions = [
		'default' => [
			# HTML may contain cookie-stealing JavaScript and web bugs
			'html', 'htm', 'js', 'jsb', 'mhtml', 'mht', 'xhtml', 'xht',
			# PHP scripts may execute arbitrary code on the server
			'php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'phar',
			# Other types that may be interpreted by some servers
			'shtml', 'jhtml', 'pl', 'py', 'cgi',
			# May contain harmful executables for Windows victims
			'exe', 'scr', 'dll', 'msi', 'vbs', 'bat', 'com', 'pif', 'cmd', 'vxd', 'cpl',
			# T341565
			'xml',
		],
		'type' => 'list',
	];

	/**
	 * Files with these MIME types will never be allowed as uploads
	 * if $wgVerifyMimeType is enabled.
	 *
	 * @since 1.37; previously $wgMimeTypeBlacklist
	 */
	public const MimeTypeExclusions = [
		'default' => [
			# HTML may contain cookie-stealing JavaScript and web bugs
			'text/html',
			# Similarly with JavaScript itself
			'application/javascript', 'text/javascript', 'text/x-javascript', 'application/x-shellscript',
			# PHP scripts may execute arbitrary code on the server
			'application/x-php', 'text/x-php',
			# Other types that may be interpreted by some servers
			'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh',
			# Client-side hazards on Internet Explorer
			'text/scriptlet', 'application/x-msdownload',
			# Windows metafile, client-side vulnerability on some systems
			'application/x-msmetafile',
			# Files that look like java files
			'application/java',
			# XML files generally - T341565
			'application/xml', 'text/xml',
		],
		'type' => 'list',
	];

	/**
	 * This is a flag to determine whether or not to check file extensions on upload.
	 *
	 * @warning Setting this to false is insecure for public wikis.
	 */
	public const CheckFileExtensions = [
		'default' => true,
	];

	/**
	 * If this is turned off, users may override the warning for files not covered
	 * by $wgFileExtensions.
	 *
	 * @warning Setting this to false is insecure for public wikis.
	 */
	public const StrictFileExtensions = [
		'default' => true,
	];

	/**
	 * Setting this to true will disable the upload system's checks for HTML/JavaScript.
	 *
	 * @warning THIS IS VERY DANGEROUS on a publicly editable site, so USE
	 * $wgGroupPermissions TO RESTRICT UPLOADING to only those that you trust
	 */
	public const DisableUploadScriptChecks = [
		'default' => false,
	];

	/**
	 * Warn if uploaded files are larger than this (in bytes), or false to disable
	 */
	public const UploadSizeWarning = [
		'default' => false,
	];

	/**
	 * list of trusted media-types and MIME types.
	 *
	 * Use the MEDIATYPE_xxx constants to represent media types.
	 * This list is used by File::isSafeFile
	 *
	 * Types not listed here will have a warning about unsafe content
	 * displayed on the images description page. It would also be possible
	 * to use this for further restrictions, like disabling direct
	 * [[media:...]] links for non-trusted formats.
	 */
	public const TrustedMediaFormats = [
		'default' => [
			MEDIATYPE_BITMAP, // all bitmap formats
			MEDIATYPE_AUDIO, // all audio formats
			MEDIATYPE_VIDEO, // all plain video formats
			"image/svg+xml", // svg (only needed if inline rendering of svg is not supported)
			"application/pdf", // PDF files
			# "application/x-shockwave-flash", //flash/shockwave movie
		],
		'type' => 'list',
	];

	/**
	 * Plugins for media file type handling.
	 *
	 * Each entry in the array maps a MIME type to a class name
	 *
	 * Core media handlers are listed in MediaHandlerFactory,
	 * and extensions should use extension.json.
	 */
	public const MediaHandlers = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Toggles native image lazy loading, via the "loading" attribute.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.34
	 */
	public const NativeImageLazyLoading = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Media handler overrides for parser tests (they don't need to generate actual
	 * thumbnails, so a mock will do)
	 */
	public const ParserTestMediaHandlers = [
		'default' => [
			'image/jpeg' => 'MockBitmapHandler',
			'image/png' => 'MockBitmapHandler',
			'image/gif' => 'MockBitmapHandler',
			'image/tiff' => 'MockBitmapHandler',
			'image/webp' => 'MockBitmapHandler',
			'image/x-ms-bmp' => 'MockBitmapHandler',
			'image/x-bmp' => 'MockBitmapHandler',
			'image/x-xcf' => 'MockBitmapHandler',
			'image/svg+xml' => 'MockSvgHandler',
			'image/vnd.djvu' => 'MockDjVuHandler',
		],
		'type' => 'map',
	];

	/**
	 * Whether to enable server-side image thumbnailing. If false, images will
	 * always be sent to the client in full resolution, with appropriate width= and
	 * height= attributes on the <img> tag for the client to do its own scaling.
	 */
	public const UseImageResize = [
		'default' => true,
	];

	/**
	 * Resizing can be done using PHP's internal image libraries or using
	 * ImageMagick or another third-party converter, e.g. GraphicMagick.
	 *
	 * These support more file formats than PHP, which only supports PNG,
	 * GIF, JPG, XBM and WBMP.
	 *
	 * Use Image Magick instead of PHP builtin functions.
	 */
	public const UseImageMagick = [
		'default' => false,
	];

	/**
	 * The convert command shipped with ImageMagick
	 */
	public const ImageMagickConvertCommand = [
		'default' => '/usr/bin/convert',
	];

	/**
	 * Array of max pixel areas for interlacing per MIME type
	 *
	 * @since 1.27
	 */
	public const MaxInterlacingAreas = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Sharpening parameter to ImageMagick
	 */
	public const SharpenParameter = [
		'default' => '0x0.4',
	];

	/**
	 * Reduction in linear dimensions below which sharpening will be enabled
	 */
	public const SharpenReductionThreshold = [
		'default' => 0.85,
	];

	/**
	 * Temporary directory used for ImageMagick. The directory must exist. Leave
	 * this set to false to let ImageMagick decide for itself.
	 */
	public const ImageMagickTempDir = [
		'default' => false,
	];

	/**
	 * Use another resizing converter, e.g. GraphicMagick
	 * %s will be replaced with the source path, %d with the destination
	 * %w and %h will be replaced with the width and height.
	 *
	 * **Example for GraphicMagick:**
	 *
	 * ```
	 * $wgCustomConvertCommand = "gm convert %s -resize %wx%h %d"
	 * ```
	 * Leave as false to skip this.
	 */
	public const CustomConvertCommand = [
		'default' => false,
	];

	/**
	 * used for lossless jpeg rotation
	 *
	 * @since 1.21
	 */
	public const JpegTran = [
		'default' => '/usr/bin/jpegtran',
	];

	/**
	 * At default setting of 'yuv420', JPEG thumbnails will use 4:2:0 chroma
	 * subsampling to reduce file size, at the cost of possible color fringing
	 * at sharp edges.
	 *
	 * See https://en.wikipedia.org/wiki/Chroma_subsampling
	 *
	 * Supported values:
	 *   false - use scaling system's default (same as pre-1.27 behavior)
	 *   'yuv444' - luma and chroma at same resolution
	 *   'yuv422' - chroma at 1/2 resolution horizontally, full vertically
	 *   'yuv420' - chroma at 1/2 resolution in both dimensions
	 *
	 * This setting is currently supported only for the ImageMagick backend;
	 * others may default to 4:2:0 or 4:4:4 or maintaining the source file's
	 * sampling in the thumbnail.
	 *
	 * @since 1.27
	 */
	public const JpegPixelFormat = [
		'default' => 'yuv420',
	];

	/**
	 * When scaling a JPEG thumbnail, this is the quality we request
	 * from the backend. It should be an integer between 1 and 100,
	 * with 100 indicating 100% quality.
	 *
	 * @since 1.32
	 */
	public const JpegQuality = [
		'default' => 80,
	];

	/**
	 * Some tests and extensions use exiv2 to manipulate the Exif metadata in some
	 * image formats.
	 */
	public const Exiv2Command = [
		'default' => '/usr/bin/exiv2',
	];

	/**
	 * Path to exiftool binary. Used for lossless ICC profile swapping.
	 *
	 * @since 1.26
	 */
	public const Exiftool = [
		'default' => '/usr/bin/exiftool',
	];

	/**
	 * Scalable Vector Graphics (SVG) may be uploaded as images.
	 *
	 * Since SVG support is not yet standard in browsers, it is
	 * necessary to rasterize SVGs to PNG as a fallback format.
	 *
	 * An external program is required to perform this conversion.
	 * If set to an array, the first item is a PHP callable and any further items
	 * are passed as parameters after $srcPath, $dstPath, $width, $height
	 */
	public const SVGConverters = [
		'default' => [
			'ImageMagick' => '$path/convert -background "#ffffff00" -thumbnail $widthx$height\\! $input PNG:$output',
			'sodipodi' => '$path/sodipodi -z -w $width -f $input -e $output',
			'inkscape' => '$path/inkscape -z -w $width -f $input -e $output',
			'batik' => 'java -Djava.awt.headless=true -jar $path/batik-rasterizer.jar -w $width -d $output $input',
			'rsvg' => '$path/rsvg-convert -w $width -h $height -o $output $input',
			'imgserv' => '$path/imgserv-wrapper -i svg -o png -w$width $input $output',
			'ImagickExt' => [ 'SvgHandler::rasterizeImagickExt', ],
		],
		'type' => 'map',
	];

	/**
	 * Pick a converter defined in $wgSVGConverters
	 */
	public const SVGConverter = [
		'default' => 'ImageMagick',
	];

	/**
	 * If not in the executable PATH, specify the SVG converter path.
	 */
	public const SVGConverterPath = [
		'default' => '',
	];

	/**
	 * Don't scale a SVG larger than this
	 */
	public const SVGMaxSize = [
		'default' => 5120,
	];

	/**
	 * Don't read SVG metadata beyond this point.
	 *
	 * Default is 5 MiB
	 */
	public const SVGMetadataCutoff = [
		'default' => 1024 * 1024 * 5,
	];

	/**
	 * Whether native rendering by the browser agent is allowed
	 *
	 * Default is false. Setting it to true disables all SVG conversion.
	 * Setting to the string 'partial' will only allow native rendering
	 * when the filesize is below SVGNativeRenderingSizeLimit and if the
	 * file contains at most 1 language.
	 *
	 * @since 1.41
	 */
	public const SVGNativeRendering = [
		'default' => false,
		'type' => 'string|boolean',
	];

	/**
	 * Filesize limit for allowing SVGs to render natively by the browser agent
	 *
	 * Default is 50kB.
	 *
	 * @since 1.41
	 */
	public const SVGNativeRenderingSizeLimit = [
		'default' => 50 * 1024,
	];

	/**
	 * Whether thumbnails should be generated in target language (usually, same as
	 * page language), if available.
	 *
	 * Currently, applies only to SVG images that use the systemLanguage attribute
	 * to specify text language.
	 *
	 * @since 1.33
	 */
	public const MediaInTargetLanguage = [
		'default' => true,
	];

	/**
	 * The maximum number of pixels a source image can have if it is to be scaled
	 * down by a scaler that requires the full source image to be decompressed
	 * and stored in decompressed form, before the thumbnail is generated.
	 *
	 * This provides a limit on memory usage for the decompression side of the
	 * image scaler. The limit is used when scaling PNGs with any of the
	 * built-in image scalers, such as ImageMagick or GD. It is ignored for
	 * JPEGs with ImageMagick, and when using the VipsScaler extension.
	 *
	 * If set to false, MediaWiki will not check the size of the image before
	 * attempting to scale it. Extensions may still override this setting by
	 * using the BitmapHandlerCheckImageArea hook.
	 *
	 * The default is 50 MB if decompressed to RGBA form, which corresponds to
	 * 12.5 million pixels or 3500x3500.
	 */
	public const MaxImageArea = [
		'default' => 12_500_000,
		'type' => 'string|integer|false',
	];

	/**
	 * Force thumbnailing of animated GIFs above this size to a single
	 * frame instead of an animated thumbnail.  As of MW 1.17 this limit
	 * is checked against the total size of all frames in the animation.
	 *
	 *
	 * It probably makes sense to keep this equal to $wgMaxImageArea.
	 */
	public const MaxAnimatedGifArea = [
		'default' => 12_500_000,
	];

	/**
	 * Browsers don't support TIFF inline generally...
	 * For inline display, we need to convert to PNG or JPEG.
	 *
	 * Note scaling should work with ImageMagick, but may not with GD scaling.
	 *
	 * **Example:**
	 *
	 * ```
	 * // PNG is lossless, but inefficient for photos
	 * $wgTiffThumbnailType = [ 'png', 'image/png' ];
	 * // JPEG is good for photos, but has no transparency support. Bad for diagrams.
	 * $wgTiffThumbnailType = [ 'jpg', 'image/jpeg' ];
	 * ```
	 */
	public const TiffThumbnailType = [
		'default' => [],
		'type' => 'list',
		'mergeStrategy' => 'replace',
	];

	/**
	 * If rendered thumbnail files are older than this timestamp, they
	 * will be rerendered on demand as if the file didn't already exist.
	 *
	 * Update if there is some need to force thumbs and SVG rasterizations
	 * to rerender, such as fixes to rendering bugs.
	 */
	public const ThumbnailEpoch = [
		'default' => '20030516000000',
	];

	/**
	 * Certain operations are avoided if there were too many recent failures,
	 * for example, thumbnail generation. Bump this value to invalidate all
	 * memory of failed operations and thus allow further attempts to resume.
	 *
	 * This is useful when a cause for the failures has been found and fixed.
	 */
	public const AttemptFailureEpoch = [
		'default' => 1,
	];

	/**
	 * If set, inline scaled images will still produce "<img>" tags ready for
	 * output instead of showing an error message.
	 *
	 * This may be useful if errors are transitory, especially if the site
	 * is configured to automatically render thumbnails on request.
	 *
	 * On the other hand, it may obscure error conditions from debugging.
	 * Enable the debug log or the 'thumbnail' log group to make sure errors
	 * are logged to a file for review.
	 */
	public const IgnoreImageErrors = [
		'default' => false,
	];

	/**
	 * Render thumbnails while parsing wikitext.
	 *
	 * If set to false, then the Parser will output valid thumbnail URLs without
	 * generating or storing the thumbnail files. This can significantly speed up
	 * processing on the web server. The site admin needs to configure a 404 handler
	 * in order for the URLs in question to regenerate the thumbnails in question
	 * on-demand. This can enable concurrency and also save computing resources
	 * as not every resolution of every image on every page is accessed between
	 * re-parses of the article. For example, re-parses triggered by bot edits,
	 * or cascading updates from template edits.
	 *
	 * If you use $wgLocalFileRepo, then you will also need to set the following:
	 *
	 * ```
	 * $wgLocalFileRepo['transformVia404'] = true;
	 * ```
	 *
	 * @since 1.7.0
	 */
	public const GenerateThumbnailOnParse = [
		'default' => true,
		'type' => 'boolean',
	];

	/**
	 * Show thumbnails for old images on the image description page
	 */
	public const ShowArchiveThumbnails = [
		'default' => true,
	];

	/**
	 * If set to true, images that contain certain the exif orientation tag will
	 * be rotated accordingly. If set to null, try to auto-detect whether a scaler
	 * is available that can rotate.
	 */
	public const EnableAutoRotation = [
		'default' => null,
		'type' => '?boolean',
	];

	/**
	 * Internal name of virus scanner. This serves as a key to the
	 * $wgAntivirusSetup array. Set this to NULL to disable virus scanning. If not
	 * null, every file uploaded will be scanned for viruses.
	 */
	public const Antivirus = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Configuration for different virus scanners. This an associative array of
	 * associative arrays. It contains one setup array per known scanner type.
	 *
	 * The entry is selected by $wgAntivirus, i.e.
	 * valid values for $wgAntivirus are the keys defined in this array.
	 *
	 * The configuration array for each scanner contains the following keys:
	 * "command", "codemap", "messagepattern":
	 *
	 * "command" is the full command to call the virus scanner - %f will be
	 * replaced with the name of the file to scan. If not present, the filename
	 * will be appended to the command. Note that this must be overwritten if the
	 * scanner is not in the system path; in that case, please set
	 * $wgAntivirusSetup[$wgAntivirus]['command'] to the desired command with full
	 * path.
	 *
	 * "codemap" is a mapping of exit code to return codes of the detectVirus
	 * function in SpecialUpload.
	 *   - An exit code mapped to AV_SCAN_FAILED causes the function to consider
	 *     the scan to be failed. This will pass the file if $wgAntivirusRequired
	 *     is not set.
	 *   - An exit code mapped to AV_SCAN_ABORTED causes the function to consider
	 *     the file to have an unsupported format, which is probably immune to
	 *     viruses. This causes the file to pass.
	 *   - An exit code mapped to AV_NO_VIRUS will cause the file to pass, meaning
	 *     no virus was found.
	 *   - All other codes (like AV_VIRUS_FOUND) will cause the function to report
	 *     a virus.
	 *   - You may use "*" as a key in the array to catch all exit codes not mapped otherwise.
	 *
	 * "messagepattern" is a perl regular expression to extract the meaningful part of the scanners
	 * output. The relevant part should be matched as group one (\1).
	 * If not defined or the pattern does not match, the full message is shown to the user.
	 */
	public const AntivirusSetup = [
		'default' => [
			# setup for clamav
			'clamav' => [
				'command' => 'clamscan --no-summary ',
				'codemap' => [
					"0" => AV_NO_VIRUS, # no virus
					"1" => AV_VIRUS_FOUND, # virus found
					"52" => AV_SCAN_ABORTED, # unsupported file format (probably immune)
					"*" => AV_SCAN_FAILED, # else scan failed
				],
				'messagepattern' => '/.*?:(.*)/sim',
			],
		],
		'type' => 'map',
	];

	/**
	 * Determines if a failed virus scan (AV_SCAN_FAILED) will cause the file to be rejected.
	 */
	public const AntivirusRequired = [
		'default' => true,
	];

	/**
	 * Determines if the MIME type of uploaded files should be checked
	 */
	public const VerifyMimeType = [
		'default' => true,
	];

	/**
	 * Sets the MIME type definition file to use by includes/libs/mime/MimeAnalyzer.php.
	 *
	 * When this is set to the path of a mime.types file, MediaWiki will use this
	 * file to map MIME types to file extensions and vice versa, in lieu of its
	 * internal MIME map. Note that some MIME mappings are considered "baked in"
	 * and cannot be overridden. See includes/libs/mime/MimeMapMinimal.php for a
	 * full list.
	 * example: $wgMimeTypeFile = '/etc/mime.types';
	 */
	public const MimeTypeFile = [
		'default' => 'internal',
	];

	/**
	 * Sets the MIME type info file to use by includes/libs/mime/MimeAnalyzer.php.
	 *
	 * Set to null to use the minimum set of built-in defaults only.
	 */
	public const MimeInfoFile = [
		'default' => 'internal',
	];

	/**
	 * Sets an external MIME detector program. The command must print only
	 * the MIME type to standard output.
	 *
	 * The name of the file to process will be appended to the command given here.
	 * If not set or NULL, PHP's mime_content_type function will be used.
	 *
	 * **Example:**
	 *
	 * ```
	 * #$wgMimeDetectorCommand = "file -bi"; // use external MIME detector (Linux)
	 * ```
	 */
	public const MimeDetectorCommand = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Switch for trivial MIME detection. Used by thumb.php to disable all fancy
	 * things, because only a few types of images are needed and file extensions
	 * can be trusted.
	 */
	public const TrivialMimeDetection = [
		'default' => false,
	];

	/**
	 * Additional XML types we can allow via MIME-detection.
	 *
	 * array = [ 'rootElement' => 'associatedMimeType' ]
	 */
	public const XMLMimeTypes = [
		'default' => [
			'http://www.w3.org/2000/svg:svg' => 'image/svg+xml',
			'svg' => 'image/svg+xml',
			'http://www.lysator.liu.se/~alla/dia/:diagram' => 'application/x-dia-diagram',
			'http://www.w3.org/1999/xhtml:html' => 'text/html',
			'html' => 'text/html',
		],
		'type' => 'map',
	];

	/**
	 * Limit images on image description pages to a user-selectable limit.
	 *
	 * In order to reduce disk usage, limits can only be selected from this list.
	 * The user preference is saved as an array offset in the database, by default
	 * the offset is set with $wgDefaultUserOptions['imagesize']. Make sure you
	 * change it if you alter the array (see T10858).
	 *
	 * This list is also used by ImagePage for alternate size links.
	 */
	public const ImageLimits = [
		'default' => [
			[ 320, 240 ],
			[ 640, 480 ],
			[ 800, 600 ],
			[ 1024, 768 ],
			[ 1280, 1024 ],
			[ 2560, 2048 ],
		],
		'type' => 'list',
	];

	/**
	 * Adjust thumbnails on image pages according to a user setting. In order to
	 * reduce disk usage, the values can only be selected from a list. This is the
	 * list of settings the user can choose from:
	 */
	public const ThumbLimits = [
		'default' => [
			120,
			150,
			180,
			200,
			250,
			300
		],
		'type' => 'list',
	];

	/**
	 * Defines what namespaces thumbnails will be displayed for in Special:Search.
	 * This is the list of namespaces for which thumbnails (or a placeholder in
	 * the absence of a thumbnail) will be shown:
	 */
	public const ThumbnailNamespaces = [
		'default' => [ NS_FILE ],
		'type' => 'list',
		'items' => [ 'type' => 'integer', ],
	];

	/**
	 * When defined, is an array of image widths used as steps for thumbnail sizes.
	 *
	 * The thumbnail with smallest step that has larger value than requested will be shown
	 * but it will be downsized via HTML values.
	 *
	 * It increases the bandwidth to the users by serving slightly large thumbnail sizes they
	 * have requested but it will save resources by de-duplicating thumbnail generation and storage.
	 *
	 * Note that these steps are "best effort" and MediaWiki might decide to use the requested size
	 * for any reason.
	 */
	public const ThumbnailSteps = [
		'default' => null,
		'type' => '?list',
	];

	/**
	 * Ratio of images that will use the thumbnail steps
	 *
	 * This is to allow for gradual roll out of thumbnail steps. It should be a number between 0 and 1.
	 *
	 * The precision of this value is up to 0.001, anything below that will be ignored.
	 */
	public const ThumbnailStepsRatio = [
		'default' => null,
		'type' => '?float',
	];

	/**
	 * When defined, is an array of image widths used as buckets for thumbnail generation.
	 *
	 * The goal is to save resources by generating thumbnails based on reference buckets instead of
	 * always using the original. This will incur a speed gain but cause a quality loss.
	 *
	 * The buckets generation is chained, with each bucket generated based on the above bucket
	 * when possible. File handlers have to opt into using that feature. For now only BitmapHandler
	 * supports it.
	 */
	public const ThumbnailBuckets = [
		'default' => null,
		'type' => '?list',
	];

	/**
	 * When using thumbnail buckets as defined above, this sets the minimum distance to the bucket
	 * above the requested size. The distance represents how many extra pixels of width the bucket
	 * needs in order to be used as the reference for a given thumbnail. For example, with the
	 * following buckets:
	 *
	 * $wgThumbnailBuckets = [ 128, 256, 512 ];
	 *
	 * and a distance of 50:
	 *
	 * $wgThumbnailMinimumBucketDistance = 50;
	 *
	 * If we want to render a thumbnail of width 220px, the 512px bucket will be used,
	 * because 220 + 50 = 270 and the closest bucket bigger than 270px is 512.
	 */
	public const ThumbnailMinimumBucketDistance = [
		'default' => 50,
	];

	/**
	 * When defined, is an array of thumbnail widths to be rendered at upload time. The idea is to
	 * prerender common thumbnail sizes, in order to avoid the necessity to render them on demand,
	 * which has a performance impact for the first client to view a certain size.
	 *
	 * This obviously means that more disk space is needed per upload upfront.
	 *
	 * @since 1.25
	 */
	public const UploadThumbnailRenderMap = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * The method through which the thumbnails will be prerendered for the entries in
	 * $wgUploadThumbnailRenderMap
	 *
	 * The method can be either "http" or "jobqueue". The former uses an http request to hit the
	 * thumbnail's URL.
	 * This method only works if thumbnails are configured to be rendered by a 404 handler. The
	 * latter option uses the job queue to render the thumbnail.
	 *
	 * @since 1.25
	 */
	public const UploadThumbnailRenderMethod = [
		'default' => 'jobqueue',
	];

	/**
	 * When using the "http" $wgUploadThumbnailRenderMethod, lets one specify a custom Host HTTP
	 * header.
	 *
	 * @since 1.25
	 */
	public const UploadThumbnailRenderHttpCustomHost = [
		'default' => false,
	];

	/**
	 * When using the "http" $wgUploadThumbnailRenderMethod, lets one specify a custom domain to
	 * send the HTTP request to.
	 *
	 * @since 1.25
	 */
	public const UploadThumbnailRenderHttpCustomDomain = [
		'default' => false,
	];

	/**
	 * When this variable is true and JPGs use the sRGB ICC profile, swaps it for the more
	 * lightweight
	 * (and free) TinyRGB profile when generating thumbnails.
	 *
	 * @since 1.26
	 */
	public const UseTinyRGBForJPGThumbnails = [
		'default' => false,
	];

	/**
	 * Parameters for the "<gallery>" tag.
	 *
	 * Fields are:
	 * - imagesPerRow:   Default number of images per-row in the gallery. 0 -> Adapt to screensize
	 * - imageWidth:     Width of the cells containing images in galleries (in "px")
	 * - imageHeight:    Height of the cells containing images in galleries (in "px")
	 * - captionLength:  Length to truncate filename to in caption when using "showfilename".
	 *                   A value of 'true' will truncate the filename to one line using CSS
	 *                   and will be the behaviour after deprecation.
	 *                   @deprecated since 1.28
	 * - showBytes:      Show the filesize in bytes in categories
	 * - showDimensions: Show the dimensions (width x height) in categories
	 * - mode:           Gallery mode
	 */
	public const GalleryOptions = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Adjust width of upright images when parameter 'upright' is used
	 * This allows a nicer look for upright images without the need to fix the width
	 * by hardcoded px in wiki sourcecode.
	 */
	public const ThumbUpright = [
		'default' => 0.75,
	];

	/**
	 * Default value for chmod-ing of new directories.
	 */
	public const DirectoryMode = [
		'default' => 0777, // octal!
	];

	/**
	 * Generate and use thumbnails suitable for screens with 1.5 and 2.0 pixel densities.
	 *
	 * This means a 320x240 use of an image on the wiki will also generate 480x360 and 640x480
	 * thumbnails, output via the srcset attribute.
	 */
	public const ResponsiveImages = [
		'default' => true,
	];

	/**
	 * Add a preconnect link for browsers to a remote FileRepo host.
	 *
	 * This is an optional performance enhancement designed for wiki farm where
	 * $wgForeignFileRepos or $wgLocalFileRepo is set to serve thumbnails from a
	 * separate hostname (e.g. not local `/w/images`). The feature expects at most
	 * a single remote hostname to be used.
	 *
	 * If multiple foreign repos are registered that serve images from different hostnames,
	 * only the first will be preconnected.
	 *
	 * This may cause unneeded HTTP connections in browsers on wikis where a foreign repo is
	 * enabled but where a local repo is more commonly used.
	 *
	 * @since 1.35
	 */
	public const ImagePreconnect = [
		'default' => false,
	];

	/***************************************************************************/
	// region   DJVU settings
	/** @name   DJVU settings */

	/**
	 * Whether to use BoxedCommand or not.
	 *
	 * @unstable Temporary feature flag for T352515
	 * @since 1.42
	 */
	public const DjvuUseBoxedCommand = [
		'default' => false,
	];

	/**
	 * Path of the djvudump executable
	 * Enable this and $wgDjvuRenderer to enable djvu rendering
	 * example: $wgDjvuDump = 'djvudump';
	 *
	 * If this is set, {@link self::ShellboxShell} must be set to the correct
	 * shell path.
	 */
	public const DjvuDump = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Path of the ddjvu DJVU renderer
	 * Enable this and $wgDjvuDump to enable djvu rendering
	 * example: $wgDjvuRenderer = 'ddjvu';
	 */
	public const DjvuRenderer = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Path of the djvutxt DJVU text extraction utility
	 * Enable this and $wgDjvuDump to enable text layer extraction from djvu files
	 * example: $wgDjvuTxt = 'djvutxt';
	 *
	 * If this is set, {@link self::ShellboxShell} must be set to the correct
	 *  shell path.
	 */
	public const DjvuTxt = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Shell command for the DJVU post processor
	 * Default: pnmtojpeg, since ddjvu generates ppm output
	 * Set this to false to output the ppm file directly.
	 */
	public const DjvuPostProcessor = [
		'default' => 'pnmtojpeg',
		'type' => '?string',
	];

	/**
	 * File extension for the DJVU post processor output
	 */
	public const DjvuOutputExtension = [
		'default' => 'jpg',
	];

	// endregion -- end of DJvu

	// endregion -- end of file uploads

	/***************************************************************************/
	// region   Email settings
	/** @name   Email settings */

	/**
	 * Site admin email address.
	 *
	 * Defaults to "wikiadmin@$wgServerName" (in Setup.php).
	 */
	public const EmergencyContact = [
		'default' => false,
	];

	/**
	 * Sender email address for e-mail notifications.
	 *
	 * The address we use as sender when a user requests a password reminder,
	 * as well as other e-mail notifications.
	 *
	 * Defaults to "apache@$wgServerName" (in Setup.php).
	 */
	public const PasswordSender = [
		'default' => false,
	];

	/**
	 * Reply-To address for e-mail notifications.
	 *
	 * Defaults to $wgPasswordSender (in Setup.php).
	 */
	public const NoReplyAddress = [
		'default' => false,
	];

	/**
	 * Set to true to enable the e-mail basic features:
	 * Password reminders, etc. If sending e-mail on your
	 * server doesn't work, you might want to disable this.
	 */
	public const EnableEmail = [
		'default' => true,
	];

	/**
	 * Set to true to enable user-to-user e-mail.
	 *
	 * This can potentially be abused, as it's hard to track.
	 */
	public const EnableUserEmail = [
		'default' => true,
	];

	/**
	 * Set to true to enable the Special Mute page. This allows users
	 * to mute unwanted communications from other users, and is linked
	 * to from emails originating from Special:Email.
	 *
	 * @since 1.34
	 */
	public const EnableSpecialMute = [
		'default' => false,
	];

	/**
	 * Set to true to enable user-to-user e-mail mutelist.
	 *
	 * @since 1.37; previously $wgEnableUserEmailBlacklist
	 */
	public const EnableUserEmailMuteList = [
		'default' => false,
	];

	/**
	 * If true put the sending user's email in a Reply-To header
	 * instead of From (false). ($wgPasswordSender will be used as From.)
	 *
	 * Some mailers (eg SMTP) set the SMTP envelope sender to the From value,
	 * which can cause problems with SPF validation and leak recipient addresses
	 * when bounces are sent to the sender. In addition, DMARC restrictions
	 * can cause emails to fail to be received when false.
	 */
	public const UserEmailUseReplyTo = [
		'default' => true,
	];

	/**
	 * Minimum time, in hours, which must elapse between password reminder
	 * emails for a given account. This is to prevent abuse by mail flooding.
	 */
	public const PasswordReminderResendTime = [
		'default' => 24,
	];

	/**
	 * The time, in seconds, when an emailed temporary password expires.
	 */
	public const NewPasswordExpiry = [
		'default' => 3600 * 24 * 7,
	];

	/**
	 * The time, in seconds, when an email confirmation email expires
	 */
	public const UserEmailConfirmationTokenExpiry = [
		'default' => 7 * 24 * 60 * 60,
	];

	/**
	 * The number of days that a user's password is good for. After this number of days, the
	 * user will be asked to reset their password. Set to false to disable password expiration.
	 */
	public const PasswordExpirationDays = [
		'default' => false,
	];

	/**
	 * If a user's password is expired, the number of seconds when they can still login,
	 * and cancel their password change, but are sent to the password change form on each login.
	 */
	public const PasswordExpireGrace = [
		'default' => 3600 * 24 * 7,
	];

	/**
	 * SMTP Mode.
	 *
	 * For using a direct (authenticated) SMTP server connection.
	 * Default to false or fill an array :
	 *
	 * ```
	 * $wgSMTP = [
	 *     'host'     => 'SMTP domain',
	 *     'IDHost'   => 'domain for MessageID',
	 *     'port'     => '25',
	 *     'auth'     => [true|false],
	 *     'username' => [SMTP username],
	 *     'password' => [SMTP password],
	 * ];
	 * ```
	 */
	public const SMTP = [
		'default' => false,
		'type' => 'false|map',
	];

	/**
	 * Additional email parameters, will be passed as the last argument to mail() call.
	 */
	public const AdditionalMailParams = [
		'default' => null,
	];

	/**
	 * For parts of the system that have been updated to provide HTML email content, send
	 * both text and HTML parts as the body of the email
	 */
	public const AllowHTMLEmail = [
		'default' => false,
	];

	/**
	 * Allow sending of e-mail notifications with the editor's address as sender.
	 *
	 * This setting depends on $wgEnotifRevealEditorAddress also being enabled.
	 * If both are enabled, notifications for actions from users that have opted-in,
	 * will be sent to other users with their address as "From" instead of "Reply-To".
	 *
	 * If disabled, or not opted-in, notifications come from $wgPasswordSender.
	 */
	public const EnotifFromEditor = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Require email authentication before sending mail to an email address.
	 *
	 * This is highly recommended. It prevents MediaWiki from being used as an open
	 * spam relay.
	 */
	public const EmailAuthentication = [
		'default' => true,
	];

	/**
	 * Allow users to enable email notification ("enotif") on watchlist changes.
	 */
	public const EnotifWatchlist = [
		'default' => false,
	];

	/**
	 * Allow users to enable email notification ("enotif") when someone edits their
	 * user talk page.
	 *
	 * The owner of the user talk page must also have the 'enotifusertalkpages' user
	 * preference set to true.
	 */
	public const EnotifUserTalk = [
		'default' => false,
	];

	/**
	 * Allow sending of e-mail notifications with the editor's address in "Reply-To".
	 *
	 * Note, enabling this only actually uses it in notification e-mails if the user
	 * opted-in to this feature. This feature flag also controls visibility of the
	 * 'enotifrevealaddr' preference, which, if users opt into, will make e-mail
	 * notifications about their actions use their address as "Reply-To".
	 *
	 * To set the address as "From" instead of "Reply-To", also enable $wgEnotifFromEditor.
	 *
	 * If disabled, or not opted-in, notifications come from $wgPasswordSender.
	 */
	public const EnotifRevealEditorAddress = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Potentially send notification mails on minor edits to pages. This is enabled
	 * by default.  If this is false, users will never be notified on minor edits.
	 *
	 * If it is true, editors with the 'nominornewtalk' right (typically bots) will still not
	 * trigger notifications for minor edits they make (to any page, not just user talk).
	 *
	 * Finally, if the watcher/recipient has the 'enotifminoredits' user preference set to
	 * false, they will not receive notifications for minor edits.
	 *
	 * User talk notifications are also affected by $wgEnotifMinorEdits, the above settings,
	 * $wgEnotifUserTalk, and the preference described there.
	 */
	public const EnotifMinorEdits = [
		'default' => true,
	];

	/**
	 * Use real name instead of username in e-mail "from" field.
	 */
	public const EnotifUseRealName = [
		'default' => false,
	];

	/**
	 * Array of usernames who will be sent a notification email for every change
	 * which occurs on a wiki. Users will not be notified of their own changes.
	 */
	public const UsersNotifiedOnAllChanges = [
		'default' => [],
		'type' => 'map',
	];

	// endregion -- end of email settings

	/***************************************************************************/
	// region  Database settings
	/** @name   Database settings */

	/**
	 * Current wiki database name
	 *
	 * This should only contain alphanumeric and underscore characters ([A-Za-z0-9_]+).
	 * Spaces, quotes, backticks, dots, and hyphens are likely to be problematic.
	 *
	 * This is used to determine the current/local wiki ID (WikiMap::getCurrentWikiDbDomain).
	 *
	 * This should still be set even if $wgLBFactoryConf is configured.
	 */
	public const DBname = [
		'default' => 'my_wiki',
	];

	/**
	 * Current wiki database schema name
	 *
	 * This should only contain alphanumeric and underscore characters ([A-Za-z0-9_]+).
	 * Spaces, quotes, backticks, dots, and hyphens are likely to be problematic.
	 *
	 * This is used to determine the current/local wiki ID (WikiMap::getCurrentWikiDbDomain).
	 *
	 * This should still be set even if $wgLBFactoryConf is configured.
	 */
	public const DBmwschema = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Current wiki database table name prefix
	 *
	 * This should only contain alphanumeric and underscore characters ([A-Za-z0-9_]+).
	 * If it's a non-empty string, then it preferably should end with an underscore.
	 * Spaces, quotes, backticks, dots, and hyphens are especially likely to be problematic.
	 *
	 * This is used to determine the current/local wiki ID (WikiMap::getCurrentWikiDbDomain).
	 *
	 * This should still be set even if $wgLBFactoryConf is configured.
	 */
	public const DBprefix = [
		'default' => '',
	];

	/**
	 * Database host name or IP address
	 */
	public const DBserver = [
		'default' => 'localhost',
	];

	/**
	 * Database port number
	 */
	public const DBport = [
		'default' => 5432,
	];

	/**
	 * Database username
	 */
	public const DBuser = [
		'default' => 'wikiuser',
	];

	/**
	 * Database user's password
	 */
	public const DBpassword = [
		'default' => '',
	];

	/**
	 * Database type
	 */
	public const DBtype = [
		'default' => 'mysql',
	];

	/**
	 * Whether to use SSL in DB connection.
	 *
	 * This setting is only used if $wgLBFactoryConf['class'] is set to
	 * '\Wikimedia\Rdbms\LBFactorySimple' and $wgDBservers is an empty array; otherwise
	 * the 'ssl' parameter of the server array must be set to achieve the same functionality.
	 */
	public const DBssl = [
		'default' => false,
	];

	/**
	 * Whether to use compression in DB connection.
	 *
	 * This setting is only used $wgLBFactoryConf['class'] is set to
	 * '\Wikimedia\Rdbms\LBFactorySimple' and $wgDBservers is an empty array; otherwise
	 * the DBO_COMPRESS flag must be set in the 'flags' option of the database
	 * connection to achieve the same functionality.
	 */
	public const DBcompress = [
		'default' => false,
	];

	/**
	 * Check for warnings after DB queries and throw an exception if an
	 * unacceptable warning is detected.
	 *
	 * This setting is only used if $wgLBFactoryConf['class'] is set to
	 * '\Wikimedia\Rdbms\LBFactorySimple' and $wgDBservers is an empty array.
	 * Otherwise, the 'strictWarnings' parameter of the server array must be set
	 * to achieve the same functionality.
	 *
	 * @since 1.42
	 */
	public const DBStrictWarnings = [
		'default' => false,
	];

	/**
	 * Separate username for maintenance tasks. Leave as null to use the default.
	 */
	public const DBadminuser = [
		'default' => null,
	];

	/**
	 * Separate password for maintenance tasks. Leave as null to use the default.
	 */
	public const DBadminpassword = [
		'default' => null,
	];

	/**
	 * Search type.
	 *
	 * Leave as null to select the default search engine for the
	 * selected database type (eg SearchMySQL), or set to a class
	 * name to override to a custom search engine.
	 *
	 * If the canonical name for the search engine doesn't match the class name
	 * (because it's namespaced for example), you can add a mapping for this in
	 * SearchMappings in extension.json.
	 */
	public const SearchType = [
		'default' => null,
	];

	/**
	 * Alternative search types
	 *
	 * Sometimes you want to support multiple search engines for testing. This
	 * allows users to select their search engine of choice via url parameters
	 * to Special:Search and the action=search API. If using this, there's no
	 * need to add $wgSearchType to it, that is handled automatically.
	 *
	 * If the canonical name for the search engine doesn't match the class name
	 * (because it's namespaced for example), you can add a mapping for this in
	 * SearchMappings in extension.json.
	 */
	public const SearchTypeAlternatives = [
		'default' => null,
	];

	/**
	 * MySQL table options to use during installation or update
	 */
	public const DBTableOptions = [
		'default' => 'ENGINE=InnoDB, DEFAULT CHARSET=binary',
	];

	/**
	 * SQL Mode - default is turning off all modes, including strict, if set.
	 *
	 * null can be used to skip the setting for performance reasons and assume
	 * the DBA has done their best job.
	 * String override can be used for some additional fun :-)
	 */
	public const SQLMode = [
		'default' => '',
	];

	/**
	 * Default group to use when getting database connections.
	 *
	 * Will be used as default query group in ILoadBalancer::getConnection.
	 *
	 * @since 1.32
	 */
	public const DBDefaultGroup = [
		'default' => null,
	];

	/**
	 * To override default SQLite data directory ($docroot/../data)
	 */
	public const SQLiteDataDir = [
		'default' => '',
	];

	/**
	 * Shared database for multiple wikis. Commonly used for storing a user table
	 * for single sign-on. The server for this database must be the same as for the
	 * main database.
	 *
	 * For backwards compatibility the shared prefix is set to the same as the local
	 * prefix, and the user table is listed in the default list of shared tables.
	 * The user_properties table is also added so that users will continue to have their
	 * preferences shared (preferences were stored in the user table prior to 1.16)
	 *
	 * $wgSharedTables may be customized with a list of tables to share in the shared
	 * database. However it is advised to limit what tables you do share as many of
	 * MediaWiki's tables may have side effects if you try to share them.
	 *
	 * $wgSharedPrefix is the table prefix for the shared database. It defaults to
	 * $wgDBprefix.
	 *
	 * $wgSharedSchema is the table schema for the shared database. It defaults to
	 * $wgDBmwschema.
	 */
	public const SharedDB = [
		'default' => null,
	];

	/**
	 * @see self::SharedDB
	 */
	public const SharedPrefix = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'DBprefix' ] ]
	];

	/**
	 * @param mixed $dbPrefix Value of DBprefix
	 * @return mixed
	 */
	public static function getDefaultSharedPrefix( $dbPrefix ) {
		return $dbPrefix;
	}

	/**
	 * @see self::SharedDB
	 * The installer will add 'actor' to this list for all new wikis.
	 */
	public const SharedTables = [
		'default' => [
			'user',
			'user_properties',
			'user_autocreate_serial',
		],
		'type' => 'list',
	];

	/**
	 * @see self::SharedDB
	 * @since 1.23
	 */
	public const SharedSchema = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'DBmwschema' ] ]
	];

	/**
	 * @param mixed $dbMwschema Value of DBmwschema
	 * @return mixed
	 */
	public static function getDefaultSharedSchema( $dbMwschema ) {
		return $dbMwschema;
	}

	/**
	 * Database load balancer
	 * This is a two-dimensional array, a list of server info structures
	 * Fields are:
	 *   - host:        Host name
	 *   - dbname:      Default database name
	 *   - user:        DB user
	 *   - password:    DB password
	 *   - type:        DB type
	 *   - driver:      DB driver (when there are multiple drivers)
	 *
	 *   - load:        Ratio of DB_REPLICA load, must be >=0, the sum of all loads must be >0.
	 *                  If this is zero for any given server, no normal query traffic will be
	 *                  sent to it. It will be excluded from lag checks in maintenance scripts.
	 *                  The only way it can receive traffic is if groupLoads is used.
	 *
	 *   - groupLoads:  (optional) Array of load ratios, the key is the query group name. A query
	 *                  may belong to several groups, the most specific group defined here is used.
	 *
	 *   - flags:       (optional) Bit field of properties:
	 *                  - DBO_DEFAULT:    Transactional-ize web requests and use autocommit otherwise
	 *                  - DBO_DEBUG:      Equivalent of $wgDebugDumpSql
	 *                  - DBO_SSL:        Use TLS connection encryption if available (deprecated)
	 *                  - DBO_COMPRESS:   Use protocol compression with database connections
	 *                  - DBO_PERSISTENT: Enables persistent database connections
	 *
	 *   - ssl:         (optional) Boolean, whether to use TLS encryption. Overrides DBO_SSL.
	 *   - max lag:     (optional) Maximum replication lag before a replica DB goes out of rotation
	 *   - is static:   (optional) Set to true if the dataset is static and no replication is used.
	 *   - cliMode:     (optional) Connection handles will not assume that requests are short-lived
	 *                  nor that INSERT..SELECT can be rewritten into a buffered SELECT and INSERT.
	 *                  This is what DBO_DEFAULT uses to determine when a web request is present.
	 *                  [Default: true if MW_ENTRY_POINT is 'cli', otherwise false]
	 *
	 *   These and any other user-defined properties will be assigned to the mLBInfo member
	 *   variable of the Database object.
	 *
	 * Leave at false to use the single-server variables above. If you set this
	 * variable, the single-server variables will generally be ignored (except
	 * perhaps in some command-line scripts).
	 *
	 * The first server listed in this array (with key 0) will be the primary. The
	 * rest of the servers will be replica DBs. To prevent writes to your replica DBs due to
	 * accidental misconfiguration or MediaWiki bugs, set read_only=1 on all your
	 * replica DBs in my.cnf. You can set read_only mode at runtime using:
	 *
	 * ```
	 *     SET @@read_only=1;
	 * ```
	 *
	 * Since the effect of writing to a replica DB is so damaging and difficult to clean
	 * up, we at Wikimedia set read_only=1 in my.cnf on all our DB servers, even
	 * our primaries, and then set read_only=0 on primaries at runtime.
	 */
	public const DBservers = [
		'default' => false,
		'type' => 'false|list',
	];

	/**
	 * Configuration for the ILBFactory service
	 *
	 * The "class" setting must point to a LBFactory subclass, which is also responsible
	 * for reading $wgDBservers, $wgDBserver, etc.
	 *
	 * To set up a wiki farm with multiple database clusters, set the "class" to
	 * LBFactoryMulti. See {@link Wikimedia::Rdbms::LBFactoryMulti LBFactoryMulti} docs for
	 * information on how to configure the rest of the $wgLBFactoryConf array.
	 */
	public const LBFactoryConf = [
		'default' => [
			'class' => 'Wikimedia\\Rdbms\\LBFactorySimple',
		],
		'type' => 'map',
		'mergeStrategy' => 'replace',
	];

	/**
	 * After a state-changing request is done by a client, this determines
	 * how many seconds that client should keep using the primary datacenter.
	 *
	 * This avoids unexpected stale or 404 responses due to replication lag.
	 *
	 * This must be greater than or equal to
	 * Wikimedia\Rdbms\ChronologyProtector::POSITION_COOKIE_TTL.
	 *
	 * @since 1.27
	 */
	public const DataCenterUpdateStickTTL = [
		'default' => 10,
	];

	/**
	 * File to log database errors to
	 */
	public const DBerrorLog = [
		'default' => false,
	];

	/**
	 * Timezone to use in the error log.
	 *
	 * Defaults to the wiki timezone ($wgLocaltimezone).
	 *
	 * A list of usable timezones can found at:
	 * https://www.php.net/manual/en/timezones.php
	 *
	 * **Examples:**
	 *
	 * ```
	 * $wgDBerrorLogTZ = 'UTC';
	 * $wgDBerrorLogTZ = 'GMT';
	 * $wgDBerrorLogTZ = 'PST8PDT';
	 * $wgDBerrorLogTZ = 'Europe/Sweden';
	 * $wgDBerrorLogTZ = 'CET';
	 * ```
	 *
	 * @since 1.20
	 */
	public const DBerrorLogTZ = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'Localtimezone' ] ]
	];

	public static function getDefaultDBerrorLogTZ( ?string $localtimezone ): string {
		// NOTE: Extra fallback, in case $localtimezone is ''.
		//       Many extsing LocalSettings files have $wgLocaltimezone = ''
		//       in them, erroneously generated by the installer.
		return $localtimezone ?: self::getDefaultLocaltimezone();
	}

	/**
	 * List of all wiki IDs that reside on the current wiki farm.
	 *
	 * The wikis listed here must meet the following requirements in order to
	 * be be considered part of the same wiki farm:
	 *
	 * - reachable for cross-wiki database queries, via \Wikimedia\Rdbms\IConnectionProvider,
	 *   as configured by $wgLBFactoryConf
	 * - share the same $wgMainCacheType backend (e.g. the same Memcached cluster),
	 *   so that cache updates and purges via BagOStuff::makeGlobalKey and
	 *   WANObjectCache work correctly.
	 *
	 * Examples of cross-wiki features enabled through this setting:
	 *
	 * - SpecialUserRights, to assign a local user group from a central wiki.
	 * - JobQueueGroup::push, to queue a job for another wiki
	 *   (e.g. GlobalUsage, MassMessage, and Wikibase extensions).
	 * - RenameUser (when using $wgSharedDB), to globally apply the rename to revisions
	 *   logging tables on all wikis.
	 *
	 * Each wiki ID must consist of 1-3 hyphen-delimited alphanumeric components (each with no
	 * hyphens nor spaces) of any of the forms:
	 *
	 * - "<DB NAME>"
	 * - "<DB NAME>-<TABLE PREFIX>"
	 * - "<DB NAME>-<DB SCHEMA>-<TABLE PREFIX>"
	 *
	 * If hyphens appear in any of the components, then the domain ID parsing may not work
	 * and site functionality might be affected. If the schema ($wgDBmwschema) is set to the
	 * default of "mediawiki" on all wikis, then the schema should be omitted from wiki IDs.
	 *
	 * @see WikiMap::getWikiIdFromDbDomain
	 * @see SiteConfiguration::getLocalDatabases
	 * @see self::LocalVirtualHosts
	 */
	public const LocalDatabases = [
		'default' => [],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	/**
	 * If lag is higher than $wgDatabaseReplicaLagWarning, show a warning in some special
	 * pages (like watchlist). If the lag is higher than $wgDatabaseReplicaLagCritical,
	 * show a more obvious warning.
	 *
	 * @since 1.36
	 */
	public const DatabaseReplicaLagWarning = [
		'default' => 10,
	];

	/**
	 * @see self::DatabaseReplicaLagWarning
	 * @since 1.36
	 */
	public const DatabaseReplicaLagCritical = [
		'default' => 30,
	];

	/**
	 * Max execution time for queries of several expensive special pages such as RecentChanges
	 * in milliseconds.
	 *
	 * @since 1.38
	 */
	public const MaxExecutionTimeForExpensiveQueries = [
		'default' => 0,
	];

	/**
	 * Mapping of virtual domain to external cluster db.
	 *
	 * If no entry is set, the code assumes local database.
	 * For example, for routing queries of virtual domain 'vdomain'
	 * to 'wikishared' database in 'extension1' cluster. The config should be like this:
	 *  [ 'vdomain' => [ 'cluster' => 'extension1', 'db' => 'wikishared' ] ]
	 *
	 * If the database needs to be the local domain, just set the 'db' to false.
	 *
	 * If you want to get another db in the main cluster, just omit 'cluster'. For example:
	 *  [ 'centralauth' => [ 'db' => 'centralauth' ] ]
	 *
	 * @since 1.41
	 */
	public const VirtualDomainsMapping = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Migration stage for file tables
	 *
	 * Use the SCHEMA_COMPAT_XXX flags. Supported values:
	 *
	 *   - SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_OLD (SCHEMA_COMPAT_OLD)
	 *   - SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD
	 *
	 * History:
	 *   - 1.44: Added
	 */
	public const FileSchemaMigrationStage = [
		'default' => SCHEMA_COMPAT_OLD,
		'type' => 'integer',
	];

	/**
	 * Migration stage for categorylinks tables
	 *
	 * Use the SCHEMA_COMPAT_XXX flags. Supported values:
	 *
	 *   - SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_OLD (SCHEMA_COMPAT_OLD)
	 *   - SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD
	 *   - SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW
	 *   - SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_NEW (SCHEMA_COMPAT_NEW)
	 *
	 * History:
	 *   - 1.44: Added
	 *   - 1.45: Added support for _READ_NEW,
	 *      changed default to SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW.
	 */
	public const CategoryLinksSchemaMigrationStage = [
		'default' => SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
		'type' => 'integer',
	];

	/**
	 * Gaps in the externallinks table for certain domains.
	 *
	 * If you have identified certain domains for which externallinks searches are slow,
	 * you can use this setting to make MediaWiki skip large el_id ranges,
	 * rather than having the database scan through them fruitlessly.
	 *
	 * Each key in the array is a domain name in el_to_domain_index form,
	 * e.g. 'https://com.example.'.
	 * The value is an array with integer keys and values,
	 * where each entry is a range (from => to, both inclusive)
	 * of el_id values where this domain is known to have no entries.
	 * (Subdomains are included, i.e., configuring an entry here guarantees to MediaWiki
	 * that there are no rows where the el_to_domain_index starts with this value.)
	 *
	 * History:
	 *   - 1.41: Added
	 */
	public const ExternalLinksDomainGaps = [
		'default' => [],
		'type' => 'map',
	];

	// endregion -- End of DB settings

	/***************************************************************************/
	// region   Content handlers and storage
	/** @name   Content handlers and storage */

	/**
	 * Plugins for page content model handling.
	 *
	 * Each entry in the array maps a model id to an ObjectFactory specification
	 * that creates an instance of the appropriate ContentHandler subclass.
	 *
	 * @since 1.21
	 */
	public const ContentHandlers = [
		'default' =>
			[
				// the usual case
				CONTENT_MODEL_WIKITEXT => [
					'class' => WikitextContentHandler::class,
					'services' => [
						'TitleFactory',
						'ParserFactory',
						'GlobalIdGenerator',
						'LanguageNameUtils',
						'LinkRenderer',
						'MagicWordFactory',
						'ParsoidParserFactory',
					],
				],
				// dumb version, no syntax highlighting
				CONTENT_MODEL_JAVASCRIPT => [
					'class' => JavaScriptContentHandler::class,
					'services' => [
						'MainConfig',
						'ParserFactory',
						'UserOptionsLookup',
					],
				],
				// simple implementation, for use by extensions, etc.
				CONTENT_MODEL_JSON => [
					'class' => JsonContentHandler::class,
					'services' => [
						'ParsoidParserFactory',
						'TitleFactory',
					],
				],
				// dumb version, no syntax highlighting
				CONTENT_MODEL_CSS => [
					'class' => CssContentHandler::class,
					'services' => [
						'MainConfig',
						'ParserFactory',
						'UserOptionsLookup',
					],
				],
				// plain text, for use by extensions, etc.
				CONTENT_MODEL_TEXT => TextContentHandler::class,
				// fallback for unknown models, from imports or extensions that were removed
				CONTENT_MODEL_UNKNOWN => FallbackContentHandler::class,
			],
		'type' => 'map',
	];

	/**
	 * Associative array mapping namespace IDs to the name of the content model pages in that
	 * namespace should have by default (use the CONTENT_MODEL_XXX constants). If no special
	 * content type is defined for a given namespace, pages in that namespace will use the
	 * CONTENT_MODEL_WIKITEXT
	 * (except for the special case of JS and CS pages).
	 *
	 * @note To determine the default model for a new page's main slot, or any slot in general,
	 * use SlotRoleHandler::getDefaultModel() together with SlotRoleRegistry::getRoleHandler().
	 * @since 1.21
	 */
	public const NamespaceContentModels = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Determines which types of text are parsed as wikitext. This does not imply that these kinds
	 * of texts are also rendered as wikitext, it only means that links, magic words, etc will have
	 * the effect on the database they would have on a wikitext page.
	 *
	 * Note that table of contents information will be *suppressed* for all
	 * text models in this list other than wikitext.
	 *
	 * @todo Make the ToC suppression configurable by the content model
	 * (T313455), not a side effect of inclusion here.
	 *
	 * @todo On the long run, it would be nice to put categories etc into a separate structure,
	 * or at least parse only the contents of comments in the scripts.
	 * @since 1.21
	 */
	public const TextModelsToParse = [
		'default' => [
			CONTENT_MODEL_WIKITEXT, // Just for completeness, wikitext will always be parsed.
			CONTENT_MODEL_JAVASCRIPT, // Make categories etc work, people put them into comments.
			CONTENT_MODEL_CSS, // Make categories etc work, people put them into comments.
		],
		'type' => 'list',
	];

	/**
	 * We can also compress text stored in the 'text' table. If this is set on, new
	 * revisions will be compressed on page save if zlib support is available. Any
	 * compressed revisions will be decompressed on load regardless of this setting,
	 * but will not be readable at all if zlib support is not available.
	 */
	public const CompressRevisions = [
		'default' => false,
	];

	/**
	 * List of enabled ExternalStore protocols.
	 *
	 * @see \ExternalStoreAccess
	 *
	 * ```
	 * $wgExternalStores = [ "DB" ];
	 * ```
	 */
	public const ExternalStores = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Shortcut for setting `$wgLBFactoryConf["externalClusters"]`.
	 *
	 * This is only applicable when using the default LBFactory
	 * of {@link Wikimedia::Rdbms::LBFactorySimple LBFactorySimple}.
	 * It is ignored if a different LBFactory is set, or if `externalClusters`
	 * is already set explicitly.
	 *
	 * @see \ExternalStoreAccess
	 *
	 * **Example:**
	 * Create a cluster named 'blobs_cluster1':
	 *
	 * ```
	 * $wgExternalServers = [
	 *     'blobs_cluster1' => <array in the same format as $wgDBservers>
	 * ];
	 * ```
	 */
	public const ExternalServers = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * The place to put new text blobs or false to put them in the text table
	 * of the local wiki database.
	 *
	 * @see \ExternalStoreAccess
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgDefaultExternalStore = [ 'DB://cluster1', 'DB://cluster2' ];
	 * ```
	 */
	public const DefaultExternalStore = [
		'default' => false,
		'type' => 'list|false',
	];

	/**
	 * Revision text may be cached in the main WAN cache to reduce load on external
	 * storage servers and object extraction overhead for frequently-loaded revisions.
	 *
	 * Set to 0 to disable, or number of seconds before cache expiry.
	 */
	public const RevisionCacheExpiry = [
		'default' => SqlBlobStore::DEFAULT_TTL,
		'type' => 'integer',
	];

	/**
	 * Enable page language feature
	 * Allows setting page language in database
	 *
	 * @since 1.24
	 */
	public const PageLanguageUseDB = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Specify the difference engine to use.
	 *
	 * Supported values:
	 * - 'external': Use an external diff engine, which must be specified via $wgExternalDiffEngine
	 * - 'wikidiff2': Use the wikidiff2 PHP extension
	 * - 'php': PHP implementations included in MediaWiki
	 *
	 * The default (null) is to use the first engine that's available.
	 *
	 * @since 1.35
	 */
	public const DiffEngine = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Name of the external diff engine to use.
	 */
	public const ExternalDiffEngine = [
		'default' => false,
		'type' => 'string|false',
	];

	/**
	 * Options for wikidiff2:
	 *   - useMultiFormat: (bool) Whether to use wikidiff2_multi_format_diff()
	 *     if it is available. This temporarily defaults to false, during
	 *     migration to the new code. It is available in wikidiff2 1.14.0+.
	 *
	 * The following options are only effective if wikidiff2_multi_format_diff()
	 * is enabled. See README.md in wikidiff2 for details:
	 *
	 *   - numContextLines
	 *   - changeThreshold
	 *   - movedLineThreshold
	 *   - maxMovedLines
	 *   - maxWordLevelDiffComplexity
	 *   - maxSplitSize
	 *   - initialSplitThreshold
	 *   - finalSplitThreshold
	 *
	 * Also:
	 *   - formatOptions: An array of format-specific overrides. The key may
	 *     be "inline" or "table" and the value is an array with keys
	 *     numContextLines, changeThreshold, etc.
	 * @since 1.41
	 */
	public const Wikidiff2Options = [
		'default' => [],
		'type' => 'map'
	];

	// endregion -- end of Content handlers and storage

	/***************************************************************************/
	// region   Performance hacks and limits
	/** @name   Performance hacks and limits */

	/**
	 * Set a limit on server request wall clock time.
	 *
	 * If the Excimer extension is enabled, setting this will cause an exception
	 * to be thrown after the specified number of seconds. If the extension is
	 * not available, set_time_limit() will be called instead.
	 *
	 * @since 1.36
	 */
	public const RequestTimeLimit = [
		'default' => null,
		'type' => '?integer',
	];

	/**
	 * The request time limit for "slow" write requests that should not be
	 * interrupted due to the risk of data corruption.
	 *
	 * The limit will only be raised. If the pre-existing time limit is larger,
	 * then this will have no effect.
	 *
	 * @since 1.26
	 */
	public const TransactionalTimeLimit = [
		'default' => 120,
	];

	/**
	 * The maximum time critical sections are allowed to stay open. Critical
	 * sections are used to defer Excimer request timeouts. If Excimer is available
	 * and this time limit is exceeded, an exception will be thrown at the next
	 * opportunity, typically after a long-running function like a DB query returns.
	 *
	 * Critical sections may wrap long-running queries, and it's generally better
	 * for the timeout to be handled a few milliseconds later when the critical
	 * section exits, so this should be a large number.
	 *
	 * This limit is ignored in command-line mode.
	 *
	 * @since 1.36
	 */
	public const CriticalSectionTimeLimit = [
		'default' => 180.0,
		'type' => 'float',
	];

	/**
	 * Disable database-intensive features
	 */
	public const MiserMode = [
		'default' => false,
	];

	/**
	 * Disable all query pages if miser mode is on, not just some
	 */
	public const DisableQueryPages = [
		'default' => false,
	];

	/**
	 * Number of rows to cache in 'querycache' table when miser mode is on
	 */
	public const QueryCacheLimit = [
		'default' => 1000,
	];

	/**
	 * Number of links to a page required before it is deemed "wanted"
	 */
	public const WantedPagesThreshold = [
		'default' => 1,
	];

	/**
	 * Enable slow parser functions
	 */
	public const AllowSlowParserFunctions = [
		'default' => false,
	];

	/**
	 * Allow schema updates
	 */
	public const AllowSchemaUpdates = [
		'default' => true,
	];

	/**
	 * Maximum article size in kibibytes
	 */
	public const MaxArticleSize = [
		'default' => 2048,
	];

	/**
	 * The minimum amount of memory that MediaWiki "needs"; MediaWiki will try to
	 * raise PHP's memory limit if it's below this amount.
	 */
	public const MemoryLimit = [
		'default' => '50M',
	];

	/**
	 * Configuration for processing pool control, for use in high-traffic wikis.
	 *
	 * An implementation is provided in the PoolCounter extension.
	 *
	 * This configuration array maps pool types to an associative array. The only
	 * defined key in the associative array is "class", which gives the class name.
	 * The remaining elements are passed through to the class as constructor
	 * parameters.
	 *
	 * **Processing pools used in MediaWiki core:**
	 * - ArticleView: parsing caused by users viewing a wiki page (per page and revision)
	 * - HtmlRestApi: parsing caused by requests to the REST API (per page and revision)
	 * - ApiParser: parsing caused by action=parse (per requesting user)
	 * - FileRender: thumbnail generation (per file name)
	 * - FileRenderExpensive: expensive thumbnail generation (per file name)
	 * - GetLocalFileCopy: expensive thumbnail generation (per file name)
	 * - diff: revision diff (per content hash)
	 * - SpecialContributions: list user contributions (per requesting user)
	 *
	 * **Example using local redis instance:**
	 *
	 * ```
	 * $wgPoolCounterConf = [ 'ArticleView' => [
	 *   'class' => PoolCounterRedis::class,
	 *   'timeout' => 15, // wait timeout in seconds
	 *   'workers' => 1, // maximum number of active threads in each pool
	 *   'maxqueue' => 5, // maximum number of total threads in each pool
	 *   'servers' => [ '127.0.0.1' ],
	 *   'redisConfig' => []
	 * ] ];
	 * ```
	 *
	 * **Example using C daemon from <https://gerrit.wikimedia.org/g/mediawiki/services/poolcounter>**
	 *
	 * ```
	 * $wgPoolCountClientConf = [
	 *   'servers' => [ '127.0.0.1' ],
	 *   'timeout' => 0.5,
	 *   'connect_timeout' => 0.01,
	 * ];
	 *
	 * $wgPoolCounterConf = [ 'ArticleView' => [
	 *   'class' => 'PoolCounter_Client',
	 *   'timeout' => 15, // wait timeout in seconds
	 *   'workers' => 5, // maximum number of active threads in each pool
	 *   'maxqueue' => 50, // maximum number of total threads in each pool
	 *   ... any extension-specific options...
	 * ] ];
	 * ```
	 *
	 * @since 1.16
	 */
	public const PoolCounterConf = [
		'default' => null,
		'type' => '?map',
	];

	/**
	 * Configuration array for the PoolCounter client.
	 *
	 * - servers: Array of hostnames, or hostname:port. The default port is 7531.
	 * - timeout: Connection timeout.
	 * - connect_timeout: [Since 1.28] Alternative connection timeout. If set, it is used
	 *   instead of `timeout` and will be retried once if a connection fails
	 *   to be established. Background: https://phabricator.wikimedia.org/T105378.
	 *
	 * @see \MediaWiki\PoolCounter\PoolCounterClient
	 * @since 1.16
	 */
	public const PoolCountClientConf = [
		'default' => [
			'servers' => [
				'127.0.0.1'
			],
			'timeout' => 0.1,
		],
		'type' => 'map',
	];

	/**
	 * Max time (in seconds) a user-generated transaction can spend in writes.
	 *
	 * If exceeded, the transaction is rolled back with an error instead of being committed.
	 *
	 * @since 1.27
	 */
	public const MaxUserDBWriteDuration = [
		'default' => false,
		'type' => 'integer|false',
	];

	/**
	 * Max time (in seconds) a job-generated transaction can spend in writes.
	 *
	 * If exceeded, the transaction is rolled back with an error instead of being committed.
	 *
	 * @since 1.30
	 */
	public const MaxJobDBWriteDuration = [
		'default' => false,
		'type' => 'integer|false',
	];

	/**
	 * LinkHolderArray batch size
	 * For debugging
	 */
	public const LinkHolderBatchSize = [
		'default' => 1000,
	];

	/**
	 * Maximum number of pages to move at once when moving subpages with a page.
	 */
	public const MaximumMovedPages = [
		'default' => 100,
	];

	/**
	 * Force deferred updates to be run before sending a response to the client,
	 * instead of attempting to run them after sending the response. Setting this
	 * to true is useful for end-to-end testing, to ensure that the effects of a
	 * request are visible to any subsequent requests, even if they are made
	 * immediately after the first one. Note however that this does not ensure
	 * that database replication is complete, nor does it execute any jobs
	 * enqueued for later.
	 * There should be no reason to set this in a normal production environment.
	 *
	 * @since 1.38
	 */
	public const ForceDeferredUpdatesPreSend = [
		'default' => false,
	];

	/**
	 * Whether site_stats table should have multiple rows. If set to true, in each update,
	 * one of ten rows gets updated at random to reduce lock wait time in wikis
	 * that have lots of concurrent edits.
	 * It should be set to true in really large wikis with big flow of edits,
	 * Otherwise it can cause inaccuracy in data.
	 *
	 * @since 1.39
	 */
	public const MultiShardSiteStats = [
		'default' => false,
		'type' => 'boolean',
	];

	// endregion -- end performance hacks

	/***************************************************************************/
	// region   Cache settings
	/** @name   Cache settings */

	/**
	 * Directory for caching data in the local filesystem. Should not be accessible
	 * from the web.
	 *
	 * Note: if multiple wikis share the same localisation cache directory, they
	 * must all have the same set of extensions. You can set a directory just for
	 * the localisation cache using $wgLocalisationCacheConf['storeDirectory'].
	 */
	public const CacheDirectory = [
		'default' => false,
	];

	/**
	 * Main cache type. This should be a cache with fast access, but it may have
	 * limited space. By default, it is disabled, since the stock database cache
	 * is not fast enough to make it worthwhile.
	 *
	 * The options are:
	 *
	 * - CACHE_ANYTHING:   Use anything, as long as it works
	 * - CACHE_NONE:       Do not cache
	 * - CACHE_DB:         Store cache objects in the DB
	 * - CACHE_MEMCACHED:  MemCached, must specify servers in $wgMemCachedServers
	 * - CACHE_ACCEL:      APC or APCu
	 * - (other):          A string may be used which identifies a cache
	 *                     configuration in $wgObjectCaches.
	 *
	 * For a multi-datacenter setup, the underlying service should be configured
	 * to broadcast operations by WANObjectCache using Mcrouter or Dynomite.
	 * See @ref wanobjectcache-deployment "Deploying WANObjectCache".
	 * To configure the `broadcastRoutingPrefix` WANObjectCache parameter,
	 * use $wgWANObjectCache.
	 *
	 * @see self::MessageCacheType
	 * @see self::ParserCacheType
	 */
	public const MainCacheType = [
		'default' => CACHE_NONE,
	];

	/**
	 * The cache type for storing the contents of the MediaWiki namespace. This
	 * cache is used for a small amount of data which is expensive to regenerate.
	 *
	 * For available types see $wgMainCacheType.
	 */
	public const MessageCacheType = [
		'default' => CACHE_ANYTHING,
	];

	/**
	 * The cache type for storing page content HTML (e.g. parsed from wikitext).
	 *
	 * Parsing wikitext is considered an expensive operation. It is recommended
	 * to give your parser cache plenty of storage space, such that long tail cache
	 * hits are possible.
	 *
	 * The default parser cache backend (when MainCacheType is left to CACHE_NONE)
	 * is effectively CACHE_DB (SqlBagOStuff). If you set up a main cache type
	 * such as memcached, it is recommended to set this explicitly to CACHE_DB.
	 *
	 * Advice for large wiki farms:
	 * - Consider allocating a dedicated database to ParserCache.
	 *   Register it in $wgObjectCaches and point $wgParserCacheType to it.
	 * - Consider using MultiWriteBagOStuff to add a higher tier with Memcached
	 *   in front of the lower database tier.
	 * - Consider setting `'purgePeriod' => 0` in the dedicated SqlBagOStuff
	 *   entry in $wgObjectCaches. This disables the automatic purging of
	 *   expired rows (which would normally happen in the background of
	 *   write requests). You can then schedule the purgeParserCache.php script
	 *   to e.g. once a day prune expired rows from the a dedicated maintenance
	 *   server.
	 *
	 * For available types see $wgMainCacheType.
	 */
	public const ParserCacheType = [
		'default' => CACHE_ANYTHING,
	];

	/**
	 * The cache backend for storing session data.
	 *
	 * Used by MediaWiki\Session\SessionManager. See $wgMainCacheType for available types.
	 *
	 * See [SessionManager Storage expectations](@ref SessionManager-storage-expectations).
	 */
	public const SessionCacheType = [
		'default' => CACHE_ANYTHING,
	];

	/**
	 * The cache type for storing language conversion tables,
	 * which are used when parsing certain text and interface messages.
	 *
	 * For available types see $wgMainCacheType.
	 *
	 * @since 1.20
	 */
	public const LanguageConverterCacheType = [
		'default' => CACHE_ANYTHING,
	];

	/**
	 * Advanced object cache configuration.
	 *
	 * Use this to define the class names and constructor parameters which are used
	 * for the various cache types. Custom cache types may be defined here and
	 * referenced from $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType,
	 * or $wgLanguageConverterCacheType.
	 *
	 * The format is an associative array where the key is a cache identifier, and
	 * the value is an associative array of parameters. One of the following
	 * parameters specifying the class must be given:
	 *
	 *   - class: The class name which will be used.
	 *   - factory: A callable function which will generate a suitable cache object.
	 *
	 * The following parameters are shared and understood by most classes:
	 *
	 *   - loggroup: The log channel to use.
	 *
	 * For SqlBagOStuff, the main configured database will be used, unless one of the following
	 * three parameters is given:
	 *
	 *   - server: Server config map for Database::factory() that describes the database to
	 *      use for all key operations in the current region. This is overridden by "servers".
	 *   - servers: Map of tag strings to server config maps, each for Database::factory(),
	 *      describing the set of database servers on which to distribute key operations in the
	 *      current region. Data is distributed among the servers via key hashing based on the
	 *      server tags. Therefore, each tag represents a shard of the dataset. Tags are useful
	 *      for failover using cold-standby servers and for managing shards with replica servers
	 *      in multiple regions (each having different hostnames).
	 *   - cluster: The ExternalStore cluster name to use.
	 *
	 * SqlBagOStuff also accepts the following optional parameters:
	 *
	 *   - dbDomain: The database name to pass to the LoadBalancer.
	 *   - multiPrimaryMode: Whether the portion of the dataset belonging to each tag/shard is
	 *      replicated among one or more regions, with one "co-primary" server in each region.
	 *      Queries are issued in a manner that provides Last-Write-Wins eventual consistency.
	 *      This option requires the "server" or "servers" options. Only MySQL, with statement
	 *      based replication (log_bin='ON' and binlog_format='STATEMENT') is supported. Also,
	 *      the `modtoken` column must exist on the `objectcache` table(s).
	 *   - purgePeriod: The average number of object cache writes in between garbage collection
	 *      operations, where expired entries are removed from the database. Or in other words,
	 *      the probability of performing a purge is one in every this number. If set to zero,
	 *      purging will never be done at runtime (for use with PurgeParserCache).
	 *   - purgeLimit: Maximum number of rows to purge at once.
	 *   - tableName: The table name to use, default is "objectcache".
	 *   - shards: The number of tables to use for data storage on each server. If greater than
	 *      1, table names are formed in the style objectcacheNNN where NNN is the shard index,
	 *      between 0 and shards-1. The number of digits used in the suffix is the minimum number
	 *      required to hold the largest shard index. Data is distributed among the tables via
	 *      key hashing. This helps mitigate MySQL bugs 61735 and 61736.
	 *   - writeBatchSize: Default maximum number of rows to change in each query for write
	 *      operations that can be chunked into a set of smaller writes.
	 *   - dataRedundancy: When set to a number higher than one, instead of sharding values,
	 *     it writes to that many servers (out of all servers) and reads from all of them too.
	 *     In case of inconsistency between servers, it picks the value with the highest exptime.
	 *     Mostly useful for stronger consistency such as mainstash.
	 *     This option has many limitations (for example when TTL is set to indef or changes)
	 *     and it shouldn't be used to handle race conditions nor canonical data.
	 *     The main point of data redundancy is to allow depool of a cluster for maintenance
	 *     without displacing too many keys.
	 *
	 * For MemcachedPhpBagOStuff parameters see {@link MemcachedPhpBagOStuff::__construct}
	 *
	 * For MemcachedPeclBagOStuff parameters see {@link MemcachedPeclBagOStuff::__construct}
	 *
	 * For RedisBagOStuff parameters see {@link Wikimedia\ObjectCache\RedisBagOStuff::__construct}
	 */
	public const ObjectCaches = [
		'default' => [
			CACHE_NONE => [ 'class' => EmptyBagOStuff::class, 'reportDupes' => false ],
			CACHE_DB => [ 'class' => SqlBagOStuff::class, 'loggroup' => 'SQLBagOStuff' ],

			'memcached-php' => [ 'class' => MemcachedPhpBagOStuff::class, 'loggroup' => 'memcached' ],
			'memcached-pecl' => [ 'class' => MemcachedPeclBagOStuff::class, 'loggroup' => 'memcached' ],
			'hash' => [ 'class' => HashBagOStuff::class, 'reportDupes' => false ],

			// Deprecated since 1.35.
			// - To configure a wg*CacheType variable to use the local server cache,
			//   use CACHE_ACCEL instead, which will select these automatically.
			// - To access the object for the local server cache at run-time,
			//   use MediaWikiServices::getLocalServerObjectCache()
			//   instead of e.g. ObjectCache::getInstance( 'apcu' ).
			// - To instantiate a new one of these explicitly, do so directly
			//   by using `new APCUBagOStuff( [ … ] )`
			// - To instantiate a new one of these including auto-detection and fallback,
			//   use ObjectCache::makeLocalServerCache().
			'apc' => [ 'class' => APCUBagOStuff::class, 'reportDupes' => false ],
			'apcu' => [ 'class' => APCUBagOStuff::class, 'reportDupes' => false ],
		],
		'type' => 'map',
	];

	/**
	 * Extra parameters to the WANObjectCache constructor.
	 *
	 * See @ref wanobjectcache-deployment "Deploying WANObjectCache".
	 *
	 * @since 1.40
	 */
	public const WANObjectCache = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * The stash store backend for MicroStash.
	 *
	 * This store should be optimized for ephemeral data, and should be able to handle
	 * a high volume of writes and reads. The dataset access scope should be across
	 * all servers that serve the application.
	 *
	 * Note that the TTL of the data written to this store must be respected completely
	 * before the data gets evicted from the store (whether the data is used or not).
	 * The store must not evict data based on LRU or popularity before the TTL expires.
	 *
	 * Expectations for sysadmins:
	 *
	 * 1. The data written to this store is generally short-lived (seconds/minutes),
	 * 2. This store must reliably persist and should not evict data until the TTL expires,
	 * 3. The same store must be accessed by all application servers (i.e. no visible lag or
	 *    split reality),
	 * 4. This store should handle a high volume of both writes and reads,
	 *    with reads completing with consistently low latencies.
	 *
	 * Examples users:
	 *
	 * - {@link MediaWiki::Permissions::RateLimiter RateLimiter} (via RStatsFactory)
	 * - {@link Wikimedia::Rdbms::ChronologyProtector ChronologyProtector}
	 *    See also [ChronologyProtector requirements](@ref ChronologyProtector-storage-requirements),
	 *    for more detailed system administrator requirements for multi-DC operations.
	 *
	 * Valid options are the keys of {@link $wgObjectCaches}, e.g. CACHE_* constants.
	 *
	 * @see \Wikimedia\ObjectCache\BagOStuff
	 * @since 1.42
	 */
	public const MicroStashType = [
		'default' => CACHE_ANYTHING,
		'type' => 'string|int',
	];

	/**
	 * The object store type of the main stash.
	 *
	 * This should be a fast storage system optimized for lightweight data, both ephemeral and
	 * permanent, for things like counters, tokens, and blobs. The dataset access scope should
	 * include all the application servers in all datacenters. Thus, the data must be replicated
	 * among all datacenters. The store should have "Last Write Wins" eventual consistency. Per
	 * https://en.wikipedia.org/wiki/PACELC_theorem, the store should act as a PA/EL distributed
	 * system for these operations.
	 *
	 * The multi-datacenter strategy for MediaWiki is to have CDN route HTTP POST requests to the
	 * primary datacenter and HTTP GET/HEAD/OPTIONS requests to the closest datacenter to the
	 * client. The stash accepts write operations from any datacenter, but cross-datacenter
	 * replication is asynchronous.
	 *
	 * Modules that use the main stash can expect race conditions to occur if a key can receive
	 * write operations originating from multiple datacenters. To improve consistency, callers
	 * should avoid main stash updates during non-POST requests. In any case, callers should
	 * gracefully tolerate occasional key evictions, temporary inconsistencies among datacenters,
	 * and violations of linearizability (e.g. during timeouts). Modules that can never handle
	 * these kinds of anomalies should use other storage mediums.
	 *
	 * Valid options are the keys of {@link $wgObjectCaches}, e.g. CACHE_* constants.
	 *
	 * @see \Wikimedia\ObjectCache\BagOStuff
	 * @since 1.26
	 */
	public const MainStash = [
		'default' => CACHE_DB,
	];

	/**
	 * Configuration for the caching related to parsoid output. The configuration contains the
	 * following keys:
	 *
	 * - StashType: The type of object store to be used by the ParsoidOutputStash service,
	 *       which stores the base state of HTML based edits.
	 *       Valid options are the keys of {@link $wgObjectCaches}, e.g. CACHE_* constants.
	 *       By default, the value of the MainStash setting will be used.
	 *       This should be an object store that provides fairly solid persistence guarantees,
	 *       since losing an entry from the stash may mean that the user can't save their edit.
	 *       If null, the value of the MainStash configuration setting will be used.
	 *
	 * - StashDuration: The number of seconds for which an entry in the stash should be kept.
	 *       Should be long enough for users to finish editing,
	 *       since losing an entry from the stash may mean that the user can't save their edit.
	 *       This is set to one day by default.
	 *
	 * - WarmParsoidParserCache: Setting this to true will pre-populate the parsoid parser cache
	 *       with parsoid outputs on page edits. This speeds up loading HTML into Visual Editor.
	 *
	 * @since 1.39
	 * @unstable Per MediaWiki 1.39, the structure of this configuration is still subject to
	 *           change.
	 */
	public const ParsoidCacheConfig = [
		'type' => 'object',
		'properties' => [
			'StashType' => [ 'type' => 'int|string|null', 'default' => null ],
			'StashDuration' => [ 'type' => 'int', 'default' => 24 * 60 * 60 ],
			'WarmParsoidParserCache' => [ 'type' => 'bool', 'default' => false ],
		]
	];

	/**
	 * Sample rate for collecting statistics on Parsoid selective update.
	 *
	 * Zero disables collection; 1000 means "1 in every 1000 parses will
	 * be sampled".
	 *
	 * @warning This is EXPERIMENTAL and will disappear once analysis is
	 * complete.
	 */
	public const ParsoidSelectiveUpdateSampleRate = [
		'type' => 'integer',
		'default' => 0,
	];

	/**
	 * Per-namespace configuration for the ParserCache filter.
	 *
	 * There is one top level key for each cache name supported in ParserCacheFactory.
	 * The per-namespace configuration is given separately for each cache.
	 *
	 * For each namespace, this defines a set of filter options, which are represented
	 * as an associative array. The following keys are supported in this array:
	 *
	 * - minCpuTime: causes the parser cache to not save any output that took fewer
	 *   than the given number of seconds of CPU time to generate, according to
	 *   ParserOutput::getTimeProfile(). Set to 0 to always cache, or to
	 *   PHP_INT_MAX to disable caching for this namespace.
	 *
	 * If no filter options are defined for a given namespace, the filter options
	 * under the "default" key will be used for pages in that namespace.
	 *
	 * @since 1.42
	 */
	public const ParserCacheFilterConfig = [
		'type' => 'map',
		'default' => [ // default value
			'pcache' => [ // old parser cache
				'default' => [ // all namespaces
					// 0 means no threshold.
					// Use PHP_INT_MAX to disable cache.
					'minCpuTime' => 0
				],
			],
			'parsoid-pcache' => [ // parsoid output cache
				'default' => [ // all namespaces
					// 0 means no threshold.
					// Use PHP_INT_MAX to disable cache.
					'minCpuTime' => 0
				],
			],
		],
		'additionalProperties' => [ // caches
			'type' => 'map',
			'description' => 'A map of namespace IDs to filter definitions.',
			'additionalProperties' => [ // namespaces
				'type' => 'map',
				'description' => 'A map of filter names to values.',
				'properties' => [ // filters
					'minCpuTime' => [ 'type' => 'float' ]
				]
			],
		],
	];

	/**
	 * Secret string for HMAC hashing in ChronologyProtector [optional]
	 *
	 * @since 1.41
	 */
	public const ChronologyProtectorSecret = [
		'default' => '',
		'type' => 'string',
	];

	/**
	 * The expiry time for the parser cache, in seconds.
	 *
	 * The default is 86400 (one day).
	 */
	public const ParserCacheExpireTime = [
		'default' => 60 * 60 * 24,
	];

	/**
	 * The expiry time for "not ready" asynchronous content in the parser
	 * cache, in seconds.  This should be rather short, to allow the
	 * "not ready" content to be replaced by "ready" content.
	 *
	 * The default is 60 (one minute).
	 *
	 * @since 1.44
	 */
	public const ParserCacheAsyncExpireTime = [
		'default' => 60,
	];

	/**
	 * Whether to re-run the refresh links jobs when asynchronous content
	 * becomes ready.  This is needed if the asynchronous content can affect
	 * categories or other page metadata.
	 *
	 * @since 1.44
	 */
	public const ParserCacheAsyncRefreshJobs = [
		'default' => true,
	];

	/**
	 * The expiry time for the parser cache for old revisions, in seconds.
	 *
	 * The default is 3600 (cache disabled).
	 */
	public const OldRevisionParserCacheExpireTime = [
		'default' => 60 * 60,
	];

	/**
	 * The expiry time to use for session storage, in seconds.
	 */
	public const ObjectCacheSessionExpiry = [
		'default' => 60 * 60,
	];

	/**
	 * Whether to use PHP session handling ($_SESSION and session_*() functions)
	 *
	 * If the constant MW_NO_SESSION is defined, this is forced to 'disable'.
	 *
	 * If the constant MW_NO_SESSION_HANDLER is defined, this is ignored and PHP
	 * session handling will function independently of SessionHandler.
	 * SessionHandler and PHP's session handling may attempt to override each
	 * others' cookies.
	 *
	 * @since 1.27
	 */
	public const PHPSessionHandling = [
		'default' => 'enable',
		'type' => 'string',
	];

	/**
	 * Time in seconds to remember IPs for, for the purposes of logging IP changes within the
	 * same session. This is meant more for debugging errors in the authentication system than
	 * for detecting abuse.
	 *
	 * @since 1.36
	 */
	public const SuspiciousIpExpiry = [
		'default' => false,
		'type' => 'integer|false',
	];

	/**
	 * Number of internal PBKDF2 iterations to use when deriving session secrets.
	 *
	 * @since 1.28
	 */
	public const SessionPbkdf2Iterations = [
		'default' => 10001,
	];

	/**
	 * The list of MemCached servers and port numbers
	 */
	public const MemCachedServers = [
		'default' => [ '127.0.0.1:11211', ],
		'type' => 'list',
	];

	/**
	 * Use persistent connections to MemCached, which are shared across multiple
	 * requests.
	 */
	public const MemCachedPersistent = [
		'default' => false,
	];

	/**
	 * Read/write timeout for MemCached server communication, in microseconds.
	 */
	public const MemCachedTimeout = [
		'default' => 500_000,
	];

	/**
	 * Set this to true to maintain a copy of the message cache on the local server.
	 *
	 * This layer of message cache is in addition to the one configured by $wgMessageCacheType.
	 *
	 * The local copy is put in APC. If APC is not installed, this setting does nothing.
	 *
	 * Note that this is about the message cache, which stores interface messages
	 * maintained as wiki pages. This is separate from the localisation cache for interface
	 * messages provided by the software, which is configured by $wgLocalisationCacheConf.
	 */
	public const UseLocalMessageCache = [
		'default' => false,
	];

	/**
	 * Instead of caching everything, only cache those messages which have
	 * been customised in the site content language. This means that
	 * MediaWiki:Foo/ja is ignored if MediaWiki:Foo doesn't exist.
	 *
	 * This option is probably only useful for translatewiki.net.
	 */
	public const AdaptiveMessageCache = [
		'default' => false,
	];

	/**
	 * Localisation cache configuration.
	 *
	 * Used by service wiring to decide how to construct the
	 * LocalisationCache instance. Associative array with keys:
	 *
	 * class:       The class to use for constructing the LocalisationCache object.
	 *              This may be overridden by extensions to a subclass of LocalisationCache.
	 *              Sub classes are expected to still honor the 'storeClass', 'storeDirectory'
	 *              and 'manualRecache' options where applicable.
	 *
	 * storeClass:  Which LCStore class implementation to use. This is optional.
	 *              The default LocalisationCache class offers the 'store' option
	 *              as abstraction for this.
	 *
	 * store:       How and where to store localisation cache data.
	 *              This option is ignored if 'storeClass' is explicitly set to a class name.
	 *              Must be one of:
	 *              - 'detect' (default): Automatically select 'files' if 'storeDirectory'
	 *                 or $wgCacheDirectory is set, and fall back to 'db' otherwise.
	 *              - 'files': Store in $wgCacheDirectory as CDB files.
	 *              - 'array': Store in $wgCacheDirectory as PHP static array files.
	 *              - 'db': Store in the l10n_cache database table.
	 *
	 * storeDirectory: If the selected LCStore class puts its data in files, then it
	 *                 will use this directory. If set to false (default), then
	 *                 $wgCacheDirectory is used instead.
	 *
	 * manualRecache: Set this to true to disable cache updates on web requests.
	 *                Use maintenance/rebuildLocalisationCache.php instead.
	 */
	public const LocalisationCacheConf = [
		'properties' => [
			'class' => [ 'type' => 'string', 'default' => LocalisationCache::class ],
			'store' => [ 'type' => 'string', 'default' => 'detect' ],
			'storeClass' => [ 'type' => 'false|string', 'default' => false ],
			'storeDirectory' => [ 'type' => 'false|string', 'default' => false ],
			'storeServer' => [ 'type' => 'object', 'default' => [] ],
			'forceRecache' => [ 'type' => 'bool', 'default' => false ],
			'manualRecache' => [ 'type' => 'bool', 'default' => false ],
		],
		'type' => 'object',
	];

	/**
	 * Allow client-side caching of pages
	 */
	public const CachePages = [
		'default' => true,
	];

	/**
	 * Set this to current time to invalidate all prior cached pages. Affects both
	 * client-side and server-side caching.
	 *
	 * You can get the current date on your server by using the command:
	 *
	 * @verbatim date +%Y%m%d%H%M%S
	 * @endverbatim
	 */
	public const CacheEpoch = [
		'default' => '20030516000000',
	];

	/**
	 * Directory where GitInfo will look for pre-computed cache files. If false,
	 * $wgCacheDirectory/gitinfo will be used.
	 */
	public const GitInfoCacheDirectory = [
		'default' => false,
	];

	/**
	 * This will cache static pages for non-logged-in users to reduce
	 * database traffic on public sites. ResourceLoader requests to default
	 * language and skins are cached as well as single module requests.
	 */
	public const UseFileCache = [
		'default' => false,
	];

	/**
	 * Depth of the subdirectory hierarchy to be created under
	 * $wgFileCacheDirectory.  The subdirectories will be named based on
	 * the MD5 hash of the title.  A value of 0 means all cache files will
	 * be put directly into the main file cache directory.
	 */
	public const FileCacheDepth = [
		'default' => 2,
	];

	/**
	 * Append a configured value to the parser cache and the sitenotice key so
	 * that they can be kept separate for some class of activity.
	 */
	public const RenderHashAppend = [
		'default' => '',
	];

	/**
	 * If on, the sidebar navigation links are cached for users with the
	 * current language set. This can save a touch of load on a busy site
	 * by shaving off extra message lookups.
	 *
	 * However it is also fragile: changing the site configuration, or
	 * having a variable $wgArticlePath, can produce broken links that
	 * don't update as expected.
	 */
	public const EnableSidebarCache = [
		'default' => false,
	];

	/**
	 * Expiry time for the sidebar cache, in seconds
	 */
	public const SidebarCacheExpiry = [
		'default' => 86400,
	];

	/**
	 * When using the file cache, we can store the cached HTML gzipped to save disk
	 * space. Pages will then also be served compressed to clients that support it.
	 *
	 * Requires zlib support enabled in PHP.
	 */
	public const UseGzip = [
		'default' => false,
	];

	/**
	 * Invalidate various caches when LocalSettings.php changes. This is equivalent
	 * to setting $wgCacheEpoch to the modification time of LocalSettings.php, as
	 * was previously done in the default LocalSettings.php file.
	 *
	 * On high-traffic wikis, this should be set to false, to avoid the need to
	 * check the file modification time, and to avoid the performance impact of
	 * unnecessary cache invalidations.
	 */
	public const InvalidateCacheOnLocalSettingsChange = [
		'default' => true,
	];

	/**
	 * When loading extensions through the extension registration system, this
	 * can be used to invalidate the cache. A good idea would be to set this to
	 * one file, you can just `touch` that one to invalidate the cache
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgExtensionInfoMTime = filemtime( "$IP/LocalSettings.php" );
	 * ```
	 *
	 * If set to false, the mtime for each individual JSON file will be checked,
	 * which can be slow if a large number of extensions are being loaded.
	 */
	public const ExtensionInfoMTime = [
		'default' => false,
		'type' => 'integer|false',
	];

	/**
	 * If this is set to true, phpunit will run integration tests against remote
	 * caches defined in $wgObjectCaches.
	 *
	 * @since 1.38
	 */
	public const EnableRemoteBagOStuffTests = [
		'default' => false,
	];

	// endregion -- end of cache settings

	/***************************************************************************/
	// region   HTTP proxy (CDN) settings
	/** @name   HTTP proxy (CDN) settings
	 *
	 * Many of these settings apply to any HTTP proxy used in front of MediaWiki,
	 * although they are sometimes still referred to as Squid settings for
	 * historical reasons.
	 *
	 * Achieving a high hit ratio with an HTTP proxy requires special configuration.
	 * See https://www.mediawiki.org/wiki/Manual:Performance_tuning#Page_view_caching
	 * for more details.
	 */

	/**
	 * Enable/disable CDN.
	 *
	 * See https://www.mediawiki.org/wiki/Manual:Performance_tuning#Page_view_caching
	 *
	 * @since 1.34 Renamed from $wgUseSquid.
	 */
	public const UseCdn = [
		'default' => false,
	];

	/**
	 * Add X-Forwarded-Proto to the Vary and Key headers for API requests and
	 * RSS/Atom feeds. Use this if you have an SSL termination setup
	 * and need to split the cache between HTTP and HTTPS for API requests,
	 * feed requests and HTTP redirect responses in order to prevent cache
	 * pollution. This does not affect 'normal' requests to index.php other than
	 * HTTP redirects.
	 */
	public const VaryOnXFP = [
		'default' => false,
	];

	/**
	 * Internal server name as known to CDN, if different.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgInternalServer = 'http://yourinternal.tld:8000';
	 * ```
	 */
	public const InternalServer = [
		'default' => false,
	];

	/**
	 * Cache TTL for the CDN sent as s-maxage (without ESI) or
	 * Surrogate-Control (with ESI). Without ESI, you should strip
	 * out s-maxage in the CDN config.
	 *
	 * 18000 seconds = 5 hours, more cache hits with 2678400 = 31 days.
	 *
	 * @since 1.34 Renamed from $wgSquidMaxage
	 */
	public const CdnMaxAge = [
		'default' => 18000,
	];

	/**
	 * Cache timeout for the CDN when DB replica DB lag is high
	 *
	 * @see self::CdnMaxAge
	 * @since 1.27
	 */
	public const CdnMaxageLagged = [
		'default' => 30,
	];

	/**
	 * Cache timeout when delivering a stale ParserCache response due to PoolCounter
	 * contention.
	 *
	 * @since 1.35
	 */
	public const CdnMaxageStale = [
		'default' => 10,
	];

	/**
	 * If set, any SquidPurge call on a URL or URLs will send a second purge no less than
	 * this many seconds later via the job queue. This requires delayed job support.
	 *
	 * This should be safely higher than the 'max lag' value in $wgLBFactoryConf, so that
	 * replica DB lag does not cause page to be stuck in stales states in CDN.
	 *
	 * This also fixes race conditions in two-tiered CDN setups (e.g. cdn2 => cdn1 => MediaWiki).
	 * If a purge for a URL reaches cdn2 before cdn1 and a request reaches cdn2 for that URL,
	 * it will populate the response from the stale cdn1 value. When cdn1 gets the purge, cdn2
	 * will still be stale. If the rebound purge delay is safely higher than the time to relay
	 * a purge to all nodes, then the rebound purge will clear cdn2 after cdn1 was cleared.
	 *
	 * @since 1.27
	 */
	public const CdnReboundPurgeDelay = [
		'default' => 0,
	];

	/**
	 * Cache timeout for the CDN when a response is known to be wrong or incomplete (due to load)
	 *
	 * @see self::CdnMaxAge
	 * @since 1.27
	 */
	public const CdnMaxageSubstitute = [
		'default' => 60,
	];

	/**
	 * Default maximum age for raw CSS/JS accesses
	 *
	 * 300 seconds = 5 minutes.
	 */
	public const ForcedRawSMaxage = [
		'default' => 300,
	];

	/**
	 * List of proxy servers to purge on changes; default port is 80. Use IP addresses.
	 *
	 * When MediaWiki is running behind a proxy, it will trust X-Forwarded-For
	 * headers sent/modified from these proxies when obtaining the remote IP address
	 *
	 * For a list of trusted servers which *aren't* purged, see $wgSquidServersNoPurge.
	 *
	 * @since 1.34 Renamed from $wgSquidServers.
	 */
	public const CdnServers = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * As with $wgCdnServers, except these servers aren't purged on page changes;
	 * use to set a list of trusted proxies, etc. Supports both individual IP
	 * addresses and CIDR blocks.
	 *
	 * @since 1.23 Supports CIDR ranges
	 * @since 1.34 Renamed from $wgSquidServersNoPurge
	 */
	public const CdnServersNoPurge = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Routing configuration for HTCP multicast purging. Add elements here to
	 * enable HTCP and determine which purges are sent where. If set to an empty
	 * array, HTCP is disabled.
	 *
	 * Each key in this array is a regular expression to match against the purged
	 * URL, or an empty string to match all URLs. The purged URL is matched against
	 * the regexes in the order specified, and the first rule whose regex matches
	 * is used, all remaining rules will thus be ignored.
	 *
	 * **Example configuration to send purges for upload.wikimedia.org to one**
	 * multicast group and all other purges to another:
	 *
	 * ```
	 * $wgHTCPRouting = [
	 *         '|^https?://upload\.wikimedia\.org|' => [
	 *                 'host' => '239.128.0.113',
	 *                 'port' => 4827,
	 *         ],
	 *         '' => [
	 *                 'host' => '239.128.0.112',
	 *                 'port' => 4827,
	 *         ],
	 * ];
	 * ```
	 *
	 * You can also pass an array of hosts to send purges too. This is useful when
	 * you have several multicast groups or unicast address that should receive a
	 * given purge.  Multiple hosts support was introduced in MediaWiki 1.22.
	 *
	 * **Example of sending purges to multiple hosts:**
	 *
	 * ```
	 * $wgHTCPRouting = [
	 *     '' => [
	 *         // Purges to text caches using multicast
	 *         [ 'host' => '239.128.0.114', 'port' => '4827' ],
	 *         // Purges to a hardcoded list of caches
	 *         [ 'host' => '10.88.66.1', 'port' => '4827' ],
	 *         [ 'host' => '10.88.66.2', 'port' => '4827' ],
	 *         [ 'host' => '10.88.66.3', 'port' => '4827' ],
	 *     ],
	 * ];
	 * ```
	 *
	 * @since 1.22
	 * @see self::HTCPMulticastTTL
	 */
	public const HTCPRouting = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * HTCP multicast TTL.
	 *
	 * @see self::HTCPRouting
	 */
	public const HTCPMulticastTTL = [
		'default' => 1,
	];

	/**
	 * Should forwarded Private IPs be accepted?
	 */
	public const UsePrivateIPs = [
		'default' => false,
	];

	/**
	 * Set this to false if MediaWiki is behind a CDN that re-orders query
	 * parameters on incoming requests.
	 *
	 * MediaWiki sets a large 'Cache-Control: s-maxage=' directive on page
	 * views only if the request URL matches one of the normal CDN URL forms.
	 * When 'CdnMatchParameterOrder' is false, the matching algorithm ignores
	 * the order of URL parameters.
	 *
	 * @since 1.39
	 */
	public const CdnMatchParameterOrder = [
		'default' => true,
	];

	// endregion -- end of HTTP proxy settings

	/***************************************************************************/
	// region   Language, regional and character encoding settings
	/** @name   Language, regional and character encoding settings */

	/**
	 * Site language code. See includes/languages/data/Names.php for languages
	 * supported by MediaWiki out of the box. Not all languages listed there have
	 * translations, see languages/messages/ for the list of languages with some
	 * localisation.
	 *
	 * Warning: Don't use any of MediaWiki's deprecated language codes listed in
	 * LanguageCode::getDeprecatedCodeMapping or $wgDummyLanguageCodes, like "no"
	 * for Norwegian (use "nb" instead). If you do, things will break unexpectedly.
	 *
	 * This defines the default interface language for all users, but users can
	 * change it in their preferences.
	 *
	 * This also defines the language of pages in the wiki. The content is wrapped
	 * in a html element with lang=XX attribute. This behavior can be overridden
	 * via hooks, see Title::getPageLanguage.
	 */
	public const LanguageCode = [
		'default' => 'en',
	];

	/**
	 * Some languages need different word forms, usually for different cases.
	 *
	 * Used in Language::convertGrammar().
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgGrammarForms['en']['genitive']['car'] = 'car\'s';
	 * ```
	 */
	public const GrammarForms = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Treat language links as magic connectors, not inline links
	 */
	public const InterwikiMagic = [
		'default' => true,
	];

	/**
	 * Hide interlanguage links from the sidebar
	 */
	public const HideInterlanguageLinks = [
		'default' => false,
	];

	/**
	 * List of additional interwiki prefixes that should be treated as
	 * interlanguage links (i.e. placed in the sidebar).
	 *
	 * Notes:
	 * - This will not do anything unless the prefixes are defined in the interwiki
	 *   map.
	 * - The display text for these custom interlanguage links will be fetched from
	 *   the system message "interlanguage-link-xyz" where xyz is the prefix in
	 *   this array.
	 * - A friendly name for each site, used for tooltip text, may optionally be
	 *   placed in the system message "interlanguage-link-sitename-xyz" where xyz is
	 *   the prefix in this array.
	 * - This should be a list of "interwiki prefixes" (ie, what appears in
	 *   wikitext), and you probably want to add an entry to
	 *   InterlanguageLinkCodeMap as well to specify which mediawiki internal
	 *   (or custom) language code this prefix corresponds to, and perhaps
	 *   then map that custom language code to a language name in
	 *   ExtraLanguageNames.
	 */
	public const ExtraInterlanguageLinkPrefixes = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Map of interlanguage link codes to language codes. This is useful to override
	 * what is shown as the language name when the interwiki code does not match it
	 * exactly
	 *
	 * @since 1.35
	 */
	public const InterlanguageLinkCodeMap = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * List of language names or overrides for default names in Names.php
	 */
	public const ExtraLanguageNames = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * List of mappings from one language code to another.
	 *
	 * This array makes the codes not appear as a selectable language on the
	 * installer.
	 *
	 * In Setup.php, the variable $wgDummyLanguageCodes is created by combining
	 * these codes with a list of "deprecated" codes, which are mostly leftovers
	 * from renames or other legacy things, and the internal codes 'qqq' and 'qqx'.
	 * If a mapping in $wgExtraLanguageCodes collide with a built-in mapping, the
	 * value in $wgExtraLanguageCodes will be used.
	 *
	 * @since 1.29
	 */
	public const ExtraLanguageCodes = [
		'default' => [
			'bh' => 'bho',
			'no' => 'nb',
			'simple' => 'en',
		],
		'type' => 'map',
	];

	/**
	 * Functionally the same as $wgExtraLanguageCodes, but deprecated. Instead of
	 * appending values to this array, append them to $wgExtraLanguageCodes.
	 *
	 * @note Since 1.29, this should not be set directly in LocalSettings,
	 *       ExtraLanguageCodes should be set instead. However, DummyLanguageCodes
	 *       will be initialized and can be read internally.
	 */
	public const DummyLanguageCodes = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Set this to always convert certain Unicode sequences to modern ones
	 * regardless of the content language. This has a small performance
	 * impact.
	 *
	 * @since 1.17
	 */
	public const AllUnicodeFixes = [
		'default' => false,
	];

	/**
	 * Set this to eg 'ISO-8859-1' to perform character set conversion when
	 * loading old revisions not marked with "utf-8" flag. Use this when
	 * converting a wiki from MediaWiki 1.4 or earlier to UTF-8 without the
	 * burdensome mass conversion of old text data.
	 *
	 * @note This DOES NOT touch any fields other than old_text. Titles, comments,
	 * user names, etc still must be converted en masse in the database before
	 * continuing as a UTF-8 wiki.
	 */
	public const LegacyEncoding = [
		'default' => false,
	];

	/**
	 * Enable dates like 'May 12' instead of '12 May', if the default date format
	 * is 'dmy or mdy'.
	 */
	public const AmericanDates = [
		'default' => false,
	];

	/**
	 * For Hindi and Arabic use local numerals instead of Western style (0-9)
	 * numerals in interface.
	 */
	public const TranslateNumerals = [
		'default' => true,
	];

	/**
	 * Translation using MediaWiki: namespace.
	 *
	 * Interface messages will be loaded from the database.
	 */
	public const UseDatabaseMessages = [
		'default' => true,
	];

	/**
	 * Maximum entry size in the message cache, in bytes
	 */
	public const MaxMsgCacheEntrySize = [
		'default' => 10000,
	];

	/**
	 * Whether to enable language variant conversion.
	 */
	public const DisableLangConversion = [
		'default' => false,
	];

	/**
	 * Whether to enable language variant conversion for links.
	 * Note that this option is slightly misnamed.
	 */
	public const DisableTitleConversion = [
		'default' => false,
	];

	/**
	 * Default variant code. If false, the default will be the static default
	 * variant of the language.
	 */
	public const DefaultLanguageVariant = [
		'default' => false,
	];

	/**
	 * Whether to enable the pig Latin variant of English (en-x-piglatin),
	 * used to ease variant development work.
	 */
	public const UsePigLatinVariant = [
		'default' => false,
	];

	/**
	 * Disabled variants array of language variant conversion.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgDisabledVariants[] = 'zh-mo';
	 * $wgDisabledVariants[] = 'zh-my';
	 * ```
	 */
	public const DisabledVariants = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Like $wgArticlePath, but on multi-variant wikis, this provides a
	 * path format that describes which parts of the URL contain the
	 * language variant.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgLanguageCode = 'sr';
	 * $wgVariantArticlePath = '/$2/$1';
	 * $wgArticlePath = '/wiki/$1';
	 * ```
	 *
	 * A link to /wiki/ would be redirected to /sr/Главна_страна
	 *
	 * It is important that $wgArticlePath not overlap with possible values
	 * of $wgVariantArticlePath.
	 */
	public const VariantArticlePath = [
		'default' => false,
	];

	/**
	 * Whether to enable the 'x-xss' language code, used for development.
	 *
	 * When enabled, the language code 'x-xss' (e.g. via ?uselang=x-xss) can
	 * be used to test correct message escaping at scale, to prevent
	 * cross-site scripting. In this "language", every message becomes an HTML
	 * snippet which attempts to alert the message key. Well-written code will
	 * correctly escape all of these messages. If any alerts are actually
	 * fired in the browser, the message is not being escaped correctly;
	 * either the offending code should be fixed, or the message should be
	 * added to {@link self::RawHtmlMessages}.
	 *
	 * @see https://www.mediawiki.org/wiki/Special:MyLanguage/Cross-site_scripting
	 * @since 1.41
	 */
	public const UseXssLanguage = [
		'default' => false,
	];

	/**
	 * Show a bar of language selection links in the user login and user
	 * registration forms; edit the "loginlanguagelinks" message to
	 * customise these.
	 */
	public const LoginLanguageSelector = [
		'default' => false,
	];

	/**
	 * When translating messages with wfMessage(), it is not always clear what
	 * should be considered UI messages and what should be content messages.
	 *
	 * For example, for the English Wikipedia, there should be only one 'mainpage',
	 * so when getting the link for 'mainpage', we should treat it as site content
	 * and call ->inContentLanguage()->text(), but for rendering the text of the
	 * link, we call ->text(). The code behaves this way by default. However,
	 * sites like the Wikimedia Commons do offer different versions of 'mainpage'
	 * and the like for different languages. This array provides a way to override
	 * the default behavior.
	 *
	 * **Example:**
	 * To allow language-specific main page and community
	 * portal:
	 *
	 * ```
	 * $wgForceUIMsgAsContentMsg = [ 'mainpage', 'portal-url' ];
	 * ```
	 */
	public const ForceUIMsgAsContentMsg = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * List of messages which might contain raw HTML.
	 *
	 * Extensions should add their insecure raw HTML messages to extension.json.
	 * The list is used for access control:
	 * changing messages listed here will require editsitecss and editsitejs rights.
	 *
	 * Message names must be given with underscores rather than spaces and with lowercase first
	 * letter.
	 *
	 * @since 1.32
	 */
	public const RawHtmlMessages = [
		'default' => [],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	/**
	 * Fake out the timezone that the server thinks it's in. This will be used for
	 * date display and not for what's stored in the DB. Leave to null to retain
	 * your server's OS-based timezone value.
	 *
	 * This variable is currently used only for signature formatting and for local
	 * time/date parser variables ({{LOCALTIME}} etc.)
	 *
	 * Timezones can be translated by editing MediaWiki messages of type
	 * timezone-nameinlowercase like timezone-utc.
	 *
	 * A list of usable timezones can found at:
	 * https://www.php.net/manual/en/timezones.php
	 *
	 * **Examples:**
	 *
	 * ```
	 * $wgLocaltimezone = 'UTC';
	 * $wgLocaltimezone = 'GMT';
	 * $wgLocaltimezone = 'PST8PDT';
	 * $wgLocaltimezone = 'Europe/Sweden';
	 * $wgLocaltimezone = 'CET';
	 * ```
	 */
	public const Localtimezone = [
		'dynamicDefault' => true,
	];

	public static function getDefaultLocaltimezone(): string {
		// This defaults to the `date.timezone` value of the PHP INI option. If this option is not set,
		// it falls back to UTC.
		$localtimezone = date_default_timezone_get();
		if ( !$localtimezone ) {
			// Make doubly sure we have a valid time zone, even if date_default_timezone_get()
			// returned garbage.
			$localtimezone = 'UTC';
		}

		return $localtimezone;
	}

	/**
	 * Set an offset from UTC in minutes to use for the default timezone setting
	 * for anonymous users and new user accounts.
	 *
	 * This setting is used for most date/time displays in the software, and is
	 * overridable in user preferences. It is *not* used for signature timestamps.
	 *
	 * By default, this will be set to match $wgLocaltimezone.
	 */
	public const LocalTZoffset = [
		'dynamicDefault' => [ 'use' => [ 'Localtimezone' ] ]
	];

	public static function getDefaultLocalTZoffset( ?string $localtimezone ): int {
		// NOTE: Extra fallback, in case $localtimezone is ''.
		//       Many extsing LocalSettings files have $wgLocaltimezone = ''
		//       in them, erroneously generated by the installer.
		$localtimezone = $localtimezone ?: self::getDefaultLocaltimezone();

		try {
			$timezone = new DateTimeZone( $localtimezone );
		} catch ( \Exception $e ) {
			throw new ConfigException(
				sprintf( "Invalid timezone '%s'. Please set a valid timezone in '$%s' in LocalSettings.php. Refer to the list of valid timezones at https://www.php.net/timezones. Error: %s",
					$localtimezone,
					"wgLocaltimezone",
					$e->getMessage() ),
			);
		}

		$offset = $timezone->getOffset( new DateTime() );

		return (int)( $offset / 60 );
	}

	/**
	 * Map of Unicode characters for which capitalization is overridden in
	 * Language::ucfirst. The characters should be
	 * represented as char_to_convert => conversion_override. See T219279 for details
	 * on why this is useful during php version transitions.
	 *
	 * @since 1.34
	 */
	public const OverrideUcfirstCharacters = [
		'default' => [],
		'type' => 'map',
	];

	// endregion -- End of language/charset settings

	/***************************************************************************/
	// region   Output format and skin settings
	/** @name   Output format and skin settings */

	/**
	 * The default Content-Type header.
	 */
	public const MimeType = [
		'default' => 'text/html',
	];

	/**
	 * Defines the value of the version attribute in the &lt;html&gt; tag, if any.
	 *
	 * If your wiki uses RDFa, set it to the correct value for RDFa+HTML5.
	 * Correct current values are 'HTML+RDFa 1.0' or 'XHTML+RDFa 1.0'.
	 * See also https://www.w3.org/TR/rdfa-in-html/#document-conformance
	 *
	 * @since 1.16
	 */
	public const Html5Version = [
		'default' => null,
	];

	/**
	 * Whether to label the store-to-database-and-show-to-others button in the editor
	 * as "Save page"/"Save changes" if false (the default) or, if true, instead as
	 * "Publish page"/"Publish changes".
	 *
	 * @since 1.28
	 */
	public const EditSubmitButtonLabelPublish = [
		'default' => false,
	];

	/**
	 * Permit other namespaces in addition to the w3.org default.
	 *
	 * Use the prefix for the key and the namespace for the value.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgXhtmlNamespaces['svg'] = 'http://www.w3.org/2000/svg';
	 * ```
	 *
	 * Normally we wouldn't have to define this in the root "<html>"
	 * element, but IE needs it there in some circumstances.
	 *
	 * This is ignored if $wgMimeType is set to a non-XML MIME type.
	 */
	public const XhtmlNamespaces = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Site notice shown at the top of each page
	 *
	 * MediaWiki:Sitenotice page, which will override this. You can also
	 * provide a separate message for logged-out users using the
	 * MediaWiki:Anonnotice page.
	 */
	public const SiteNotice = [
		'default' => '',
	];

	/**
	 * Override the ability of certain browsers to attempt to autodetect dataformats in pages.
	 *
	 * This is a default feature of many mobile browsers, but can have a lot of false positives,
	 * where for instance, year ranges are confused with phone numbers.
	 * The default of this setting is to disable telephone number data detection.
	 * Set BrowserFormatDetection to false to fallback to the browser defaults.
	 *
	 * @since 1.37
	 * @see https://developer.apple.com/library/archive/documentation/AppleApplications/Reference/SafariHTMLRef/Articles/MetaTags.html
	 */
	public const BrowserFormatDetection = [
		'default' => 'telephone=no',
		'type' => 'string',
	];

	/**
	 * An array of open graph tags which should be added by all skins.
	 *
	 * Accepted values are "og:site_name", "og:title", "og:type" and "twitter:card".
	 * Since some of these fields can be provided by extensions it defaults to an empty array.
	 *
	 * @since 1.36
	 */
	public const SkinMetaTags = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Default skin, for new users and anonymous visitors. Registered users may
	 * change this to any one of the other available skins in their preferences.
	 */
	public const DefaultSkin = [
		'default' => 'vector-2022',
	];

	/**
	 * Fallback skin used when the skin defined by $wgDefaultSkin can't be found.
	 *
	 * @since 1.24
	 */
	public const FallbackSkin = [
		'default' => 'fallback',
	];

	/**
	 * Specify the names of skins that should not be presented in the list of
	 * available skins in user preferences.
	 *
	 * NOTE: This does not uninstall the skin, and it will still be accessible
	 * via the `useskin` query parameter. To uninstall a skin, remove its inclusion
	 * from LocalSettings.php.
	 *
	 * @see \SkinFactory::getAllowedSkins
	 */
	public const SkipSkins = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Disable output compression (enabled by default if zlib is available)
	 */
	public const DisableOutputCompression = [
		'default' => false,
	];

	/**
	 * How should section IDs be encoded?
	 * This array can contain 1 or 2 elements, each of them can be one of:
	 * - 'html5'  is modern HTML5 style encoding with minimal escaping. Displays Unicode
	 *            characters in most browsers' address bars.
	 *
	 * - 'legacy' is old MediaWiki-style encoding, e.g. 啤酒 turns into .E5.95.A4.E9.85.92
	 *
	 * The first element of this array specifies the primary mode of escaping IDs. This
	 * is what users will see when they e.g. follow an [[#internal link]] to a section of
	 * a page.
	 *
	 * The optional second element defines a fallback mode, useful for migrations.
	 * If present, it will direct MediaWiki to add empty <span>s to every section with its
	 * id attribute set to fallback encoded title so that links using the previous encoding
	 * would still work.
	 *
	 * Example: you want to migrate your wiki from 'legacy' to 'html5'
	 *
	 * On the first step, set this variable to [ 'legacy', 'html5' ]. After a while, when
	 * all caches (parser, HTTP, etc.) contain only pages generated with this setting,
	 * flip the value to [ 'html5', 'legacy' ]. This will result in all internal links being
	 * generated in the new encoding while old links (both external and cached internal) will
	 * still work. After a long time, you might want to ditch backwards compatibility and
	 * set it to [ 'html5' ]. After all, pages get edited, breaking incoming links no matter which
	 * fragment mode is used.
	 *
	 * @since 1.30
	 */
	public const FragmentMode = [
		'default' => [ 'html5', 'legacy', ],
		'type' => 'list',
	];

	/**
	 * Which ID escaping mode should be used for external interwiki links? See documentation
	 * for $wgFragmentMode above for details of each mode. Because you can't control external sites,
	 * this setting should probably always be 'legacy', unless every wiki you link to has converted
	 * to 'html5'.
	 *
	 * @since 1.30
	 */
	public const ExternalInterwikiFragmentMode = [
		'default' => 'legacy',
	];

	/**
	 * Abstract list of footer icons for skins in place of old copyrightico and poweredbyico code
	 * You can add new icons to the built in copyright or poweredby, or you can create
	 * a new block. Though note that you may need to add some custom css to get good styling
	 * of new blocks in monobook. vector and modern should work without any special css.
	 *
	 * $wgFooterIcons itself is a key/value array.
	 * The key is the name of a block that the icons will be wrapped in. The final id varies
	 * by skin; Monobook and Vector will turn poweredby into f-poweredbyico while Modern
	 * turns it into mw_poweredby.
	 * The value is either key/value array of icons or a string.
	 * In the key/value array the key may or may not be used by the skin but it can
	 * be used to find the icon and unset it or change the icon if needed.
	 * This is useful for disabling icons that are set by extensions.
	 * The value should be either a string or an array. If it is a string it will be output
	 * directly as html, however some skins may choose to ignore it. An array is the preferred
	 * format for the icon, the following keys are used:
	 * - src: An absolute url to the image to use for the icon, this is recommended
	 *        but not required, however some skins will ignore icons without an image
	 * - srcset: optional additional-resolution images; see HTML5 specs
	 * - url: The url to use in the a element around the text or icon, if not set an a element will
	 *        not be outputted
	 * - alt: This is the text form of the icon, it will be displayed without an image in
	 *        skins like Modern or if src is not set, and will otherwise be used as
	 *        the alt="" for the image. This key is required.
	 * - width and height: If the icon specified by src is not of the standard size
	 *                     you can specify the size of image to use with these keys.
	 *                     Otherwise they will default to the standard 88x31.
	 *
	 * @todo Reformat documentation.
	 */
	public const FooterIcons = [
		'default' => [
			"copyright" => [
				"copyright" => [], // placeholder for the built in copyright icon
			],
			"poweredby" => [
				"mediawiki" => [
					// Defaults to point at
					// "$wgResourceBasePath/resources/assets/poweredby_mediawiki_88x31.png"
					// plus srcset for 1.5x, 2x resolution variants.
					"src" => null,
					"url" => "https://www.mediawiki.org/",
					"alt" => "Powered by MediaWiki",
					"lang" => "en",
				]
			],
		],
		'type' => 'map',
	];

	/**
	 * Login / create account link behavior when it's possible for anonymous users
	 * to create an account.
	 *
	 * - true = use a combined login / create account link
	 * - false = split login and create account into two separate links
	 */
	public const UseCombinedLoginLink = [
		'default' => false,
	];

	/**
	 * Display user edit counts in various prominent places.
	 */
	public const Edititis = [
		'default' => false,
	];

	/**
	 * Some web hosts attempt to rewrite all responses with a 404 (not found)
	 * status code, mangling or hiding MediaWiki's output. If you are using such a
	 * host, you should start looking for a better one. While you're doing that,
	 * set this to false to convert some of MediaWiki's 404 responses to 200 so
	 * that the generated error pages can be seen.
	 *
	 * In cases where for technical reasons it is more important for MediaWiki to
	 * send the correct status code than for the body to be transmitted intact,
	 * this configuration variable is ignored.
	 */
	public const Send404Code = [
		'default' => true,
	];

	/**
	 * The $wgShowRollbackEditCount variable is used to show how many edits can be rolled back.
	 *
	 * The numeric value of the variable controls how many edits MediaWiki will look back to
	 * determine whether a rollback is allowed (by checking that they are all from the same author).
	 * If the value is false or 0, the edits are not counted. Disabling this will prevent MediaWiki
	 * from hiding some useless rollback links.
	 *
	 * @since 1.20
	 */
	public const ShowRollbackEditCount = [
		'default' => 10,
	];

	/**
	 * Output a <link rel="canonical"> tag on every page indicating the canonical
	 * server which should be used, i.e. $wgServer or $wgCanonicalServer. Since
	 * detection of the current server is unreliable, the link is sent
	 * unconditionally.
	 */
	public const EnableCanonicalServerLink = [
		'default' => false,
	];

	/**
	 * List of interwiki logos overrides.
	 * This is used by the sister project sidebar. This list accept a key equal to the
	 * interwiki ID (as defined in the interwiki links), and accept a Codex icon name
	 * (https://doc.wikimedia.org/codex/latest/icons/all-icons.html) or a base URL for
	 * the given interwiki.
	 *
	 * Example :
	 * $wgInterwikiLogoOverride = [
	 *     'c' => 'logoWikimediaCommons',
	 *     'wikit' => 'https://mySpecialWiki.com'
	 * ];
	 */
	public const InterwikiLogoOverride = [
		'default' => [],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	// endregion -- End of output format settings

	/***************************************************************************/
	// region   ResourceLoader settings
	/** @name   ResourceLoader settings */

	/**
	 * Formerly a workaround for a security vulnerability caused by installation
	 * of Flash as a browser extension.
	 *
	 * @since 1.25
	 * @deprecated since 1.39
	 */
	public const MangleFlashPolicy = [
		'default' => true,
		'obsolete' => 'Since 1.39; no longer has any effect.',
		'description' => 'Has been emitting warnings since 1.39 (LTS). ' .
			'Can be removed completely in 1.44, assuming 1.43 is an LTS release.'
	];

	/**
	 * Define extra client-side modules to be registered with ResourceLoader.
	 *
	 * @note It is recommended to define modules using the `ResourceModule` attribute
	 * in `extension.json` or `skin.json` when possible (instead of via PHP global variables).
	 *
	 * Registration is internally handled by ResourceLoader::register.
	 *
	 * ## Available modules
	 *
	 * Modules that ship with %MediaWiki core are registered via
	 * resources/Resources.php. For a full list with documentation, see:
	 * [ResourceLoader/Core_modules](https://www.mediawiki.org/wiki/ResourceLoader/Core_modules).
	 *
	 * ## Options
	 *
	 * - class `{string}`:
	 *   By default a module is assumed to bundle file resources
	 *   as handled by the MediaWiki\ResourceLoader\FileModule class. Use this option
	 *   to use a different implementation of MediaWiki\ResourceLoader\Module instead.
	 *
	 *   Default: `\MediaWiki\ResourceLoader\FileModule`
	 *
	 * - factory `{string}`:
	 *   Override the instantiation of the MediaWiki\ResourceLoader\Module
	 *   class using a PHP callback. This allows dependency injection to be used.
	 *   This option cannot be combined with the `class` option.
	 *
	 *   Since: MW 1.30
	 *
	 * - dependencies `{string[]|string}`:
	 *   Modules that must be executed before this module.
	 *   Module name string or list of module name strings.
	 *
	 *   Default: `[]`
	 *
	 * - deprecated `{boolean|string}`:
	 *   Whether the module is deprecated and usage is discouraged.
	 *   Set to boolean true, or a string to include in the warning message.
	 *
	 *   Default: `false`
	 *
	 * - group `{string}`:
	 *   Optional request group to override which modules may be downloaded
	 *   together in an HTTP batch request. By default, any two modules may be
	 *   loaded together in the same batch request. Set this option to a
	 *   descriptive string to give the module its own HTTP request. To allow
	 *   other modules to join this new request, give those the same request group.
	 *
	 *   Use this option with caution. The default behaviour is well-tuned already,
	 *   and setting this often does more harm than good. For more about request
	 *   balancing optimisations, see
	 *   [ResourceLoader/Architecture#Balance](https://www.mediawiki.org/wiki/ResourceLoader/Architecture#Balance).
	 *
	 * - skipFunction `{string}`:
	 *   Allow this module to be satisfied as dependency without actually loading
	 *   or executing scripts from the server, if the specified JavaScript function
	 *   returns true.
	 *
	 *   Use this to provide polyfills that are natively available in newer browsers.
	 *   Specify the relative path to a JavaScript file containing a top-level return
	 *   statement. The contents of the file should not contain any wrapping function,
	 *   it will be wrapped by %ResourceLoader in an anonymous function and invoked
	 *   when the module is considered for loading.
	 *
	 * ## FileModule options
	 *
	 * - localBasePath `{string}`:
	 *   Base file path to prepend to relative file paths specified in other options.
	 *
	 *   Default: `$IP`
	 *
	 * - remoteBasePath `{string}`:
	 *   Base URL path to prepend to relative file paths specified in other options.
	 *   This is used to form URLs for files, such as when referencing images in
	 *   stylesheets, or in debug mode to serve JavaScript files directly.
	 *
	 *   Default: @ref $wgResourceBasePath (which defaults to @ref $wgScriptPath)
	 *
	 * - remoteExtPath `{string}`:
	 *   Shortcut for `remoteBasePath` that is relative to $wgExtensionAssetsPath.
	 *   Use this when defining modules from an extension, so as to avoid hardcoding
	 *   the script path of the %MediaWiki install or the location of the extensions
	 *   directory.
	 *
	 *   This option is mutually exclusive with `remoteBasePath`.
	 *
	 * - remoteSkinPath `{string}`: Like `remoteExtPath`, but relative to $wgStylePath.
	 *
	 * - styles `{string[]|string|array<string,array>}`:
	 *   Styles to always include in the module.
	 *   %File path or list of file paths, relative to `localBasePath`.
	 *   The stylesheet can be automatically wrapped in a `@media` query by specifying
	 *   the file path as the key in an object (instead of the value), with the value
	 *   specifying a `media` query.
	 *
	 *   See @ref wgResourceModules-example-stylesheet "Stylesheet examples" below.
	 *
	 *   See also @ref $wgResourceModuleSkinStyles.
	 *
	 *   Extended options:
	 *
	 *   - skinStyles `{string[]|string}`: Styles to include in specific skin contexts.
	 *     Array keyed is by skin name with file path or list of file paths as value,
	 *     relative to `localBasePath`.
	 *
	 *   Default: `[]`
	 *
	 * - noflip `{boolean}`:
	 *   By default, CSSJanus will be used automatically to perform LTR-to-RTL flipping
	 *   when loaded in a right-to-left (RTL) interface language context.
	 *   Use this option to skip CSSJanus LTR-to-RTL flipping for this module, for example
	 *   when registering an external library that already handles RTL styles.
	 *
	 *   Default: `false`
	 *
	 * - packageFiles `{string[]|array[]}`
	 *   Specify script files and (virtual) data files to include in the module.
	 *   Each internal JavaScript file retains its own local module scope and its
	 *   private exports can be accessed separately by other client-side code in the
	 *   same module, via the local `require()` function.
	 *
	 *   Modules that use package files should export any public API methods using
	 *   `module.exports`.
	 *
	 *   See examples at
	 *     [ResourceLoader/Package_files](https://www.mediawiki.org/wiki/ResourceLoader/Package_files)
	 *     on mediawiki.org.
	 *
	 *   The `packageFiles` feature cannot be combined with legacy scripts that use
	 *   the `scripts` option, including its extended variants `languageScripts`,
	 *   `skinScripts`, and `debugScripts`.
	 *
	 *   Since: MW 1.33
	 *
	 *   Default: `[]`
	 *
	 * - scripts `{string[]|string|array[]}`:
	 *   Scripts to always include in the module.
	 *   %File path or list of file paths, relative to `localBasePath`.
	 *
	 *   These files are concatenated blindly and executed as a single client-side script.
	 *   Modules using this option are sometimes referred to as "legacy scripts" to
	 *   distinguish them from those that use the `packageFiles` option.
	 *
	 *   Modules that use legacy scripts usually attach any public APIs they have
	 *   to the `mw` global variable. If a module contains just one file, it is also
	 *   supported to use the newer `module.exports` mechanism, though if the module
	 *   contains more than one legacy script, it is considered unsafe and unsupported
	 *   to use this mechanism (use `packageFiles` instead). See also
	 *   [Coding
	 *     conventions/JavaScript](https://www.mediawiki.org/wiki/Manual:Coding_conventions/JavaScript#Exporting).
	 *
	 *   Since MW 1.41, an element of `scripts` may be an array in the same format as
	 *   packageFiles, giving a callback to call for content generation.
	 *
	 *   Default: `[]`
	 *
	 *   Extended options, concatenated in this order:
	 *
	 *   - languageScripts `{string[]|string|array[]}`: Scripts to include in specific
	 *     language contexts. Array is keyed by language code with file path or list of
	 *     file path.
	 *   - skinScripts `{string[]|string|array[]}`: Scripts to include in specific skin contexts.
	 *     Array keyed is by skin name with file path or list of file paths.
	 *   - debugScripts `{string[]|string|array[]}`: Scripts to include in debug contexts.
	 *     %File path or list of file paths.
	 *
	 * - messages `{string[]}`
	 *   Localisation messages to bundle with this module, for client-side use
	 *   via `mw.msg()` and `mw.message()`. List of message keys.
	 *
	 *   Default: `[]`
	 *
	 * - templates `{string[]}`
	 *   List of template files to be loaded for client-side usage via `mw.templates`.
	 *
	 *   Default: `[]`
	 *
	 * - es6 `{boolean}`:
	 *   Since: MW 1.36; ignored since MW 1.41.
	 *
	 *   Default: `true`
	 *
	 *  - skipStructureTest `{boolean}`:
	 *   Whether to skip ResourcesTest::testRespond(). Since MW 1.42.
	 *
	 *   Default: `false`.
	 *
	 * ## Examples
	 *
	 * **Example: Using an alternate subclass**
	 *
	 * ```
	 * $wgResourceModules['ext.myExtension'] = [
	 *   'class' => \MediaWiki\ResourceLoader\WikiModule::class,
	 * ];
	 * ```
	 *
	 * **Example: Deprecated module**
	 *
	 * ```
	 * $wgResourceModules['ext.myExtension'] = [
	 *   'deprecated' => 'You should use ext.myExtension2 instead',
	 * ];
	 * ```
	 *
	 * **Example: Base paths in extension.json**
	 *
	 * ```
	 * "ext.myExtension": {
	 *   "localBasePath": "modules/ext.MyExtension",
	 *   "remoteExtPath": "MyExtension/modules/ext.MyExtension"
	 * }
	 * ```
	 *
	 * **Example: Base paths in core with PHP**
	 *
	 * ```
	 * $wgResourceModules['mediawiki.example'] = [
	 *   'localBasePath' => "$IP/resources/src/mediawiki.example",
	 *   'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.example",
	 * ];
	 * ```
	 *
	 * **Example: Define a skip function**
	 *
	 * ```
	 * $wgResourceModules['ext.myExtension.SomeWebAPI'] = [
	 *   'skipFunction' => 'skip-SomeWebAPI.js',
	 * ];
	 * ```
	 *
	 * **Example: Contents of skip function file**
	 *
	 * ```
	 * return typeof SomeWebAPI === 'function' && SomeWebAPI.prototype.duckMethod;
	 * ```
	 * @anchor wgResourceModules-example-stylesheet
	 *
	 * **Example: Stylesheets**
	 *
	 * ```
	 * $wgResourceModules['example'] = [
	 *   'styles' => [
	 *     'foo.css',
	 *     'bar.css',
	 *   ],
	 * ];
	 * $wgResourceModules['example.media'] = [
	 *   'styles' => [
	 *     'foo.css' => [ 'media' => 'print' ],
	 * ];
	 * $wgResourceModules['example.mixed'] = [
	 *   'styles' => [
	 *     'foo.css',
	 *     'bar.css' => [ 'media' => 'print' ],
	 *   ],
	 * ];
	 * ```
	 *
	 * **Example: Package files**
	 *
	 * ```
	 * "ext.myExtension": {
	 *     "localBasePath": "modules/ext.MyExtension",
	 *     "remoteExtPath": "MyExtension/modules/ext.MyExtension",
	 *     "packageFiles": [
	 *       "index.js",
	 *       "utils.js",
	 *       "data.json"
	 *     ]
	 *   }
	 * }
	 * ```
	 *
	 * **Example: Legacy scripts**
	 *
	 * ```
	 * $wgResourceModules['ext.myExtension'] = [
	 *   'scripts' => [
	 *     'modules/ext.myExtension/utils.js',
	 *     'modules/ext.myExtension/myExtension.js',
	 *   ],
	 *   'languageScripts' => [
	 *     'bs' => 'modules/ext.myExtension/languages/bs.js',
	 *     'fi' => 'modules/ext.myExtension/languages/fi.js',
	 *   ],
	 *   'skinScripts' => [
	 *     'default' => 'modules/ext.myExtension/skin-default.js',
	 *   ],
	 *   'debugScripts' => [
	 *     'modules/ext.myExtension/debug.js',
	 *   ],
	 * ];
	 * ```
	 *
	 * **Example: Template files**
	 *
	 * ```
	 * $wgResourceModules['ext.myExtension'] = [
	 *   'templates' => [
	 *     'templates/template.html',
	 *     'templates/template2.html',
	 *   ],
	 * ];
	 * ```
	 * @since 1.17
	 */
	public const ResourceModules = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Add extra skin-specific styles to a resource module.
	 *
	 * These are automatically added by ResourceLoader to the 'skinStyles' list of
	 * the existing module. The 'styles' list cannot be modified or disabled.
	 *
	 * For example, below a module "bar" is defined and skin Foo provides additional
	 * styles for it:
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgResourceModules['bar'] = [
	 *   'scripts' => 'resources/bar/bar.js',
	 *   'styles' => 'resources/bar/main.css',
	 * ];
	 *
	 * $wgResourceModuleSkinStyles['foo'] = [
	 *   'bar' => 'skins/Foo/styles/bar.css',
	 * ];
	 * ```
	 *
	 * This is effectively equivalent to:
	 *
	 * **Equivalent:**
	 *
	 * ```
	 * $wgResourceModules['bar'] = [
	 *   'scripts' => 'resources/bar/bar.js',
	 *   'styles' => 'resources/bar/main.css',
	 *   'skinStyles' => [
	 *     'foo' => skins/Foo/styles/bar.css',
	 *   ],
	 * ];
	 * ```
	 *
	 * If the module already defines its own entry in `skinStyles` for a given skin, then
	 * $wgResourceModuleSkinStyles is ignored.
	 *
	 * If a module defines a `skinStyles['default']` the skin may want to extend that instead
	 * of replacing it. This can be done using the `+` prefix.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgResourceModules['bar'] = [
	 *   'scripts' => 'resources/bar/bar.js',
	 *   'styles' => 'resources/bar/basic.css',
	 *   'skinStyles' => [
	 *    'default' => 'resources/bar/additional.css',
	 *   ],
	 * ];
	 * // Note the '+' character:
	 * $wgResourceModuleSkinStyles['foo'] = [
	 *   '+bar' => 'skins/Foo/styles/bar.css',
	 * ];
	 * ```
	 *
	 * This is effectively equivalent to:
	 *
	 * **Equivalent:**
	 *
	 * ```
	 * $wgResourceModules['bar'] = [
	 *   'scripts' => 'resources/bar/bar.js',
	 *   'styles' => 'resources/bar/basic.css',
	 *   'skinStyles' => [
	 *     'default' => 'resources/bar/additional.css',
	 *     'foo' => [
	 *       'resources/bar/additional.css',
	 *       'skins/Foo/styles/bar.css',
	 *     ],
	 *   ],
	 * ];
	 * ```
	 *
	 * In other words, as a module author, use the `styles` list for stylesheets that may not be
	 * disabled by a skin. To provide default styles that may be extended or replaced,
	 * use `skinStyles['default']`.
	 *
	 * As with $wgResourceModules, always set the localBasePath and remoteBasePath
	 * keys (or one of remoteExtPath/remoteSkinPath).
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgResourceModuleSkinStyles['foo'] = [
	 *   'bar' => 'bar.css',
	 *   'quux' => 'quux.css',
	 *   'remoteSkinPath' => 'Foo/styles',
	 *   'localBasePath' => __DIR__ . '/styles',
	 * ];
	 * ```
	 */
	public const ResourceModuleSkinStyles = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Extensions should register foreign module sources here. 'local' is a
	 * built-in source that is not in this array, but defined by
	 * ResourceLoader::__construct() so that it cannot be unset.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgResourceLoaderSources['foo'] = 'http://example.org/w/load.php';
	 * ```
	 */
	public const ResourceLoaderSources = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * The default 'remoteBasePath' value for instances of MediaWiki\ResourceLoader\FileModule.
	 *
	 * Defaults to $wgScriptPath.
	 */
	public const ResourceBasePath = [
		'default' => null,
		'dynamicDefault' => [ 'use' => [ 'ScriptPath' ] ]
	];

	/**
	 * @param mixed $scriptPath Value of ScriptPath
	 * @return string
	 */
	public static function getDefaultResourceBasePath( $scriptPath ): string {
		return $scriptPath;
	}

	/**
	 * Override how long a CDN or browser may cache a ResourceLoader HTTP response.
	 *
	 * Maximum time in seconds. Used for the `max-age` and `s-maxage` Cache-Control headers.
	 *
	 * Valid keys:
	 *   - versioned
	 *   - unversioned
	 *
	 * @see \MediaWiki\ResourceLoader\ResourceLoader::__construct
	 * @since 1.35
	 */
	public const ResourceLoaderMaxage = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * The default debug mode (on/off) for of ResourceLoader requests.
	 *
	 * This will still be overridden when the debug URL parameter is used.
	 */
	public const ResourceLoaderDebug = [
		'default' => false,
	];

	/**
	 * ResourceLoader will not generate URLs whose query string is more than
	 * this many characters long, and will instead use multiple requests with
	 * shorter query strings. Using multiple requests may degrade performance,
	 * but may be needed based on the query string limit supported by your web
	 * server and/or your user's web browsers.
	 *
	 * Default: `2000`.
	 *
	 * @see \MediaWiki\ResourceLoader\StartUpModule::getMaxQueryLength
	 * @since 1.17
	 */
	public const ResourceLoaderMaxQueryLength = [
		'default' => false,
		'type' => 'integer|false',
	];

	/**
	 * Validate JavaScript code loaded from wiki pages.
	 *
	 * If a syntax error is found, the script is replaced with a warning
	 * logged to the browser console. This ensures errors are found early and
	 * consistently (independent of the editor's own browser), and prevents
	 * breaking other modules loaded in the same batch from load.php.
	 *
	 * @see \MediaWiki\ResourceLoader\Module::validateScriptFile
	 */
	public const ResourceLoaderValidateJS = [
		'default' => true,
	];

	/**
	 * When enabled, execution of JavaScript modules is profiled client-side.
	 *
	 * Instrumentation happens in mw.loader.profiler.
	 * Use `mw.inspect('time')` from the browser console to display the data.
	 *
	 * @since 1.32
	 */
	public const ResourceLoaderEnableJSProfiler = [
		'default' => false,
	];

	/**
	 * Whether ResourceLoader should attempt to persist modules in localStorage on
	 * browsers that support the Web Storage API.
	 */
	public const ResourceLoaderStorageEnabled = [
		'default' => true,
	];

	/**
	 * Cache version for client-side ResourceLoader module storage. You can trigger
	 * invalidation of the contents of the module store by incrementing this value.
	 *
	 * @since 1.23
	 */
	public const ResourceLoaderStorageVersion = [
		'default' => 1,
	];

	/**
	 * Whether to include a SourceMap header in ResourceLoader responses
	 * for JavaScript modules.
	 *
	 * @since 1.41
	 */
	public const ResourceLoaderEnableSourceMapLinks = [
		'default' => true,
	];

	/**
	 * Whether to allow site-wide CSS (MediaWiki:Common.css and friends) on
	 * restricted pages like Special:UserLogin or Special:Preferences where
	 * JavaScript is disabled for security reasons. As it is possible to
	 * execute JavaScript through CSS, setting this to true opens up a
	 * potential security hole. Some sites may "skin" their wiki by using
	 * site-wide CSS, causing restricted pages to look unstyled and different
	 * from the rest of the site.
	 *
	 * @since 1.25
	 */
	public const AllowSiteCSSOnRestrictedPages = [
		'default' => false,
	];

	/**
	 * Whether to use the development version of Vue.js. This should be disabled
	 * for production installations. For development installations, enabling this
	 * provides useful additional warnings and checks.
	 *
	 * Even when this is disabled, using ResourceLoader's debug mode (?debug=true)
	 * will cause the development version to be loaded.
	 *
	 * @since 1.35
	 */
	public const VueDevelopmentMode = [
		'default' => false,
	];

	/**
	 * If this is set, MediaWiki will look for Codex files in this directory
	 * instead of in resources/lib/codex/ and friends.
	 *
	 * To use a local development version of Codex, set this to the full file
	 * path of the root directory of a local clone of the Codex repository, and
	 * run `npm run build-all` in the Codex root directory. Rerun this command
	 * after making any changes.
	 *
	 * This should be disabled for production installations.
	 *
	 * @since 1.43
	 */
	public const CodexDevelopmentDir = [
		'default' => null,
	];

	// endregion -- End of ResourceLoader settings

	/***************************************************************************/
	// region   Page titles and redirects
	/** @name   Page titles and redirects */

	/**
	 * Name of the project namespace. If left set to false, $wgSitename will be
	 * used instead.
	 */
	public const MetaNamespace = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'Sitename' ] ]
	];

	/**
	 * @param mixed $sitename Value of Sitename
	 * @return string
	 */
	public static function getDefaultMetaNamespace( $sitename ): string {
		return str_replace( ' ', '_', $sitename );
	}

	/**
	 * Name of the project talk namespace.
	 *
	 * Normally you can ignore this and it will be something like
	 * $wgMetaNamespace . "_talk". In some languages, you may want to set this
	 * manually for grammatical reasons.
	 */
	public const MetaNamespaceTalk = [
		'default' => false,
	];

	/**
	 * Canonical namespace names.
	 *
	 * Must not be changed directly in configuration or by extensions, use $wgExtraNamespaces
	 * instead.
	 */
	public const CanonicalNamespaceNames = [
		'default' => NamespaceInfo::CANONICAL_NAMES,
		'type' => 'map',
	];

	/**
	 * Additional namespaces. If the namespaces defined in Language.php and
	 * Namespace.php are insufficient, you can create new ones here, for example,
	 * to import Help files in other languages. You can also override the namespace
	 * names of existing namespaces. Extensions should use the CanonicalNamespaces
	 * hook or extension.json.
	 *
	 * @warning Once you delete a namespace, the pages in that namespace will
	 * no longer be accessible. If you rename it, then you can access them through
	 * the new namespace name.
	 *
	 * Custom namespaces should start at 100 to avoid conflicting with standard
	 * namespaces, and should always follow the even/odd main/talk pattern.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgExtraNamespaces = [
	 *    100 => "Hilfe",
	 *    101 => "Hilfe_Diskussion",
	 *    102 => "Aide",
	 *    103 => "Discussion_Aide"
	 * ];
	 * ```
	 * @todo Add a note about maintenance/namespaceDupes.php
	 */
	public const ExtraNamespaces = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Same as above, but for namespaces with gender distinction.
	 *
	 * Note: the default form for the namespace should also be set
	 * using $wgExtraNamespaces for the same index.
	 *
	 * @since 1.18
	 */
	public const ExtraGenderNamespaces = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Define extra namespace aliases.
	 *
	 * These are alternate names for the primary localised namespace names, which
	 * are defined by $wgExtraNamespaces and the language file. If a page is
	 * requested with such a prefix, the request will be redirected to the primary
	 * name.
	 *
	 * Set this to a map from namespace names to IDs.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgNamespaceAliases = [
	 *     'Wikipedian' => NS_USER,
	 *     'Help' => 100,
	 * ];
	 * ```
	 *
	 * @see \MediaWiki\Language\Language::getNamespaceAliases for accessing the full list of aliases,
	 * including those defined by other means.
	 */
	public const NamespaceAliases = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Allowed title characters -- regex character class
	 * Don't change this unless you know what you're doing
	 *
	 * Problematic punctuation:
	 *   -  []}|#     Are needed for link syntax, never enable these
	 *   -  <>        Causes problems with HTML escaping, don't use
	 *   -  %         Enabled by default, minor problems with path to query rewrite rules, see below
	 *   -  +         Enabled by default, but doesn't work with path to query rewrite rules,
	 *                corrupted by apache
	 *   -  ?         Enabled by default, but doesn't work with path to PATH_INFO rewrites
	 *
	 * All three of these punctuation problems can be avoided by using an alias,
	 * instead of a rewrite rule of either variety.
	 *
	 * The problem with % is that when using a path to query rewrite rule, URLs are
	 * double-unescaped: once by Apache's path conversion code, and again by PHP. So
	 * %253F, for example, becomes "?". Our code does not double-escape to compensate
	 * for this, indeed double escaping would break if the double-escaped title was
	 * passed in the query string rather than the path. This is a minor security issue
	 * because articles can be created such that they are hard to view or edit.
	 *
	 * In some rare cases you may wish to remove + for compatibility with old links.
	 * @deprecated since 1.41; use Extension:TitleBlacklist or (soon)
	 * Extension:AbuseFilter to customize this set.
	 */
	public const LegalTitleChars = [
		'default' => ' %!"$&\'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF+',
		'deprecated' => 'since 1.41; use Extension:TitleBlacklist to customize',
	];

	/**
	 * Set this to false to avoid forcing the first letter of links to capitals.
	 *
	 * @warning may break links! This makes links COMPLETELY case-sensitive. Links
	 * appearing with a capital at the beginning of a sentence will *not* go to the
	 * same place as links in the middle of a sentence using a lowercase initial.
	 */
	public const CapitalLinks = [
		'default' => true,
	];

	/**
	 * @since 1.16 - This can now be set per-namespace. Some special namespaces (such as Special,
	 *     see NamespaceInfo::ALWAYS_CAPITALIZED_NAMESPACES for the full list) must be true by
	 *     default (and setting them has no effect), due to various things that require them to be
	 *     so. Also, since Talk namespaces need to directly mirror their associated content
	 *     namespaces, the values for those are ignored in favor of the subject namespace's
	 *     setting. Setting for NS_MEDIA is taken automatically from NS_FILE.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgCapitalLinkOverrides[ NS_FILE ] = false;
	 * ```
	 */
	public const CapitalLinkOverrides = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Which namespaces should support subpages?
	 * See Language.php for a list of namespaces.
	 */
	public const NamespacesWithSubpages = [
		'default' => [
			NS_TALK => true,
			NS_USER => true,
			NS_USER_TALK => true,
			NS_PROJECT => true,
			NS_PROJECT_TALK => true,
			NS_FILE_TALK => true,
			NS_MEDIAWIKI => true,
			NS_MEDIAWIKI_TALK => true,
			NS_TEMPLATE => true,
			NS_TEMPLATE_TALK => true,
			NS_HELP => true,
			NS_HELP_TALK => true,
			NS_CATEGORY_TALK => true
		],
		'type' => 'map',
	];

	/**
	 * Array of namespaces which can be deemed to contain valid "content", as far
	 * as the site statistics are concerned. Useful if additional namespaces also
	 * contain "content" which should be considered when generating a count of the
	 * number of articles in the wiki.
	 */
	public const ContentNamespaces = [
		'default' => [ NS_MAIN ],
		'type' => 'list',
	];

	/**
	 * Optional array of namespaces which should be excluded from Special:ShortPages.
	 *
	 * Only pages inside $wgContentNamespaces but not $wgShortPagesNamespaceExclusions will
	 * be shown on that page.
	 *
	 * @since 1.37; previously $wgShortPagesNamespaceBlacklist
	 */
	public const ShortPagesNamespaceExclusions = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Array of namespaces, in addition to the talk namespaces, where signatures
	 * (~~~~) are likely to be used. This determines whether to display the
	 * Signature button on the edit toolbar, and may also be used by extensions.
	 *
	 * For example, "traditional" style wikis, where content and discussion are
	 * intermixed, could place NS_MAIN and NS_PROJECT namespaces in this array.
	 */
	public const ExtraSignatureNamespaces = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Array of invalid page redirect targets.
	 *
	 * Attempting to create a redirect to any of the pages in this array
	 * will make the redirect fail.
	 * Userlogout is hard-coded, so it does not need to be listed here.
	 * (T12569) Disallow Mypage and Mytalk as well.
	 *
	 * As of now, this only checks special pages. Redirects to pages in
	 * other namespaces cannot be invalidated by this variable.
	 */
	public const InvalidRedirectTargets = [
		'default' => [ 'Filepath', 'Mypage', 'Mytalk', 'Redirect', 'Mylog' ],
		'type' => 'list',
	];

	/**
	 * Disable redirects to special pages and interwiki redirects, which use a 302
	 * and have no "redirected from" link.
	 *
	 * @note This is only for articles with #REDIRECT in them. URL's containing a
	 * local interwiki prefix (or a non-canonical special page name) are still hard
	 * redirected regardless of this setting.
	 */
	public const DisableHardRedirects = [
		'default' => false,
	];

	/**
	 * Fix double redirects after a page move.
	 *
	 * Tends to conflict with page move vandalism, use only on a private wiki.
	 */
	public const FixDoubleRedirects = [
		'default' => false,
	];

	// endregion -- End of title and interwiki settings

	/***************************************************************************/
	// region   Interwiki links and sites
	/** @name   Interwiki links and sites */

	/**
	 * Array for local interwiki values, for each of the interwiki prefixes that point to
	 * the current wiki.
	 *
	 * Note, recent changes feeds use only the first entry in this array. See $wgRCFeeds.
	 */
	public const LocalInterwikis = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Expiry time for cache of interwiki table
	 */
	public const InterwikiExpiry = [
		'default' => 10800,
	];

	/**
	 * Interwiki cache as an associative array.
	 *
	 * When set, the InterwikiLookup service will not use the built-in `interwiki` database table,
	 * but instead use this static array as its source.
	 *
	 * This cache data structure can be generated by the `dumpInterwiki.php` maintenance
	 * script (which lives in the WikimediaMaintenance repository) and has key
	 * formats such as the following:
	 *
	 *  - dbname:key - a simple key (e.g. enwiki:meta)
	 *  - _sitename:key - site-scope key (e.g. wiktionary:meta)
	 *  - __global:key - global-scope key (e.g. __global:meta)
	 *  - __sites:dbname - site mapping (e.g. __sites:enwiki)
	 *
	 * Sites mapping just specifies site name, other keys provide "local url"
	 * data layout.
	 *
	 * @see \MediaWiki\Interwiki\ClassicInterwikiLookup
	 */
	public const InterwikiCache = [
		'default' => false,
		'type' => 'false|map',
		'mergeStrategy' => 'replace',
	];

	/**
	 * Specify number of domains to check for messages.
	 *
	 * - 1: Just wiki(db)-level
	 * - 2: wiki and global levels
	 * - 3: site levels
	 */
	public const InterwikiScopes = [
		'default' => 3,
	];

	/**
	 * Fallback site, if unable to resolve from cache
	 */
	public const InterwikiFallbackSite = [
		'default' => 'wiki',
	];

	/**
	 * If local interwikis are set up which allow redirects,
	 * set this regexp to restrict URLs which will be displayed
	 * as 'redirected from' links.
	 *
	 * **Example:**
	 * It might look something like this:
	 *
	 * ```
	 * $wgRedirectSources = '!^https?://[a-z-]+\.wikipedia\.org/!';
	 * ```
	 *
	 * Leave at false to avoid displaying any incoming redirect markers.
	 * This does not affect intra-wiki redirects, which don't change
	 * the URL.
	 */
	public const RedirectSources = [
		'default' => false,
	];

	/**
	 * Register handlers for specific types of sites.
	 *
	 * @since 1.21
	 */
	public const SiteTypes = [
		'default' => [ 'mediawiki' => MediaWikiSite::class, ],
		'type' => 'map',
	];

	// endregion -- Interwiki links and sites

	/***************************************************************************/
	// region   Parser settings
	/** @name   Parser settings
	 *  These settings configure the transformation from wikitext to HTML.
	 */

	/**
	 * Maximum indent level of toc.
	 */
	public const MaxTocLevel = [
		'default' => 999,
	];

	/**
	 * A complexity limit on template expansion: the maximum number of nodes visited
	 * by PPFrame::expand()
	 */
	public const MaxPPNodeCount = [
		'default' => 1_000_000,
	];

	/**
	 * Maximum recursion depth for templates within templates.
	 *
	 * The current parser adds two levels to the PHP call stack for each template,
	 * and xdebug limits the call stack to 256 by default. So this should hopefully
	 * stop the parser before it hits the xdebug limit.
	 */
	public const MaxTemplateDepth = [
		'default' => 100,
	];

	/**
	 * @see self::MaxTemplateDepth
	 */
	public const MaxPPExpandDepth = [
		'default' => 100,
	];

	/**
	 * URL schemes that should be recognized as valid by UrlUtils::parse().
	 *
	 * WARNING: Do not add 'file:' to this or internal file links will be broken.
	 * Instead, if you want to support file links, add 'file://'. The same applies
	 * to any other protocols with the same name as a namespace. See task T46011 for
	 * more information.
	 *
	 * @see \MediaWiki\Utils\UrlUtils::parse()
	 */
	public const UrlProtocols = [
		'default' => [
			'bitcoin:', 'ftp://', 'ftps://', 'geo:', 'git://', 'gopher://', 'http://',
			'https://', 'irc://', 'ircs://', 'magnet:', 'mailto:', 'matrix:', 'mms://',
			'news:', 'nntp://', 'redis://', 'sftp://', 'sip:', 'sips:', 'sms:',
			'ssh://', 'svn://', 'tel:', 'telnet://', 'urn:', 'wikipedia://', 'worldwind://',
			'xmpp:', '//',
		],
		'type' => 'list',
	];

	/**
	 * If true, removes (by substituting) templates in signatures.
	 */
	public const CleanSignatures = [
		'default' => true,
	];

	/**
	 * Whether to allow inline image pointing to other websites
	 */
	public const AllowExternalImages = [
		'default' => false,
	];

	/**
	 * If the above is false, you can specify an exception here. Image URLs
	 * that start with this string are then rendered, while all others are not.
	 *
	 * You can use this to set up a trusted, simple repository of images.
	 * You may also specify an array of strings to allow multiple sites
	 *
	 * **Examples:**
	 *
	 * ```
	 * $wgAllowExternalImagesFrom = 'http://127.0.0.1/';
	 * $wgAllowExternalImagesFrom = [ 'http://127.0.0.1/', 'http://example.com' ];
	 * ```
	 */
	public const AllowExternalImagesFrom = [
		'default' => '',
	];

	/**
	 * If $wgAllowExternalImages is false, you can allow an on-wiki
	 * allow list of regular expression fragments to match the image URL
	 * against. If the image matches one of the regular expression fragments,
	 * the image will be displayed.
	 *
	 * Set this to true to enable the on-wiki allow list (MediaWiki:External image whitelist)
	 * Or false to disable it
	 *
	 * @since 1.14
	 */
	public const EnableImageWhitelist = [
		'default' => false,
	];

	/**
	 * Configuration for HTML postprocessing tool. Set this to a configuration
	 * array to enable an external tool. By default, we now use the RemexHtml
	 * library; historically, other postprocessors were used.
	 *
	 * Setting this to null will use default settings.
	 *
	 * Keys include:
	 *  - treeMutationTrace: a boolean to turn on Remex tracing
	 *  - serializerTrace: a boolean to turn on Remex tracing
	 *  - mungerTrace: a boolean to turn on Remex tracing
	 *  - pwrap: whether <p> wrapping should be done (default true)
	 *
	 * See includes/tidy/RemexDriver.php for detail on configuration.
	 *
	 * Overriding the default configuration is strongly discouraged in
	 * production.
	 */
	public const TidyConfig = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Default Parsoid configuration.
	 *
	 * Overriding the default configuration is strongly discouraged in
	 * production.
	 *
	 * @since 1.39
	 */
	public const ParsoidSettings = [
		'default' => [
			'useSelser' => true,
		],
		'type' => 'map',
	];

	/**
	 * If set, Parsoid's HTML output for parser functions will be different
	 * from Parsoid HTML spec 2.x.x and lets us experiment with a better
	 * output that might be rolled out in a future 3.x Parsoid HTML version.
	 * Parsoid will start generating this output for wikifunctions parser function
	 * whenever that code is rolled out to production and will let us experiment
	 * with this new format and tweak it now. This also lets Parsoid developers
	 * experiment with it locally.
	 *
	 * This is an experimental flag and might be removed without notice.
	 *
	 * @unstable EXPERIMENTAL
	 */
	public const ParsoidExperimentalParserFunctionOutput = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Disable shipping the styles for the legacy media HTML structure.
	 * This is to give time for templates and extensions that mimic the
	 * legacy output to be migrated away.
	 *
	 * @internal Temporary feature flag for T318433.
	 * @since 1.41
	 */
	public const UseLegacyMediaStyles = [
		'default' => false,
	];

	/**
	 * Allow raw, unchecked HTML in "<html>...</html>" sections.
	 *
	 * THIS IS VERY DANGEROUS on a publicly editable site, so USE $wgGroupPermissions
	 * TO RESTRICT EDITING to only those that you trust
	 */
	public const RawHtml = [
		'default' => false,
	];

	/**
	 * Set a default target for external links, e.g. _blank to pop up a new window.
	 *
	 * This will also set the "noreferrer" and "noopener" link rel to prevent the
	 * attack described at https://mathiasbynens.github.io/rel-noopener/ .
	 * Some older browsers may not support these link attributes, hence
	 * setting $wgExternalLinkTarget to _blank may represent a security risk
	 * to some of your users.
	 */
	public const ExternalLinkTarget = [
		'default' => false,
	];

	/**
	 * If true, external URL links in wiki text will be given the
	 * rel="nofollow" attribute as a hint to search engines that
	 * they should not be followed for ranking purposes as they
	 * are user-supplied and thus subject to spamming.
	 */
	public const NoFollowLinks = [
		'default' => true,
	];

	/**
	 * Namespaces in which $wgNoFollowLinks doesn't apply.
	 *
	 * See Language.php for a list of namespaces.
	 */
	public const NoFollowNsExceptions = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * If this is set to an array of domains, external links to these domain names
	 * (or any subdomains) will not be set to rel="nofollow" regardless of the
	 * value of $wgNoFollowLinks.  For instance:
	 *
	 * $wgNoFollowDomainExceptions = [ 'en.wikipedia.org', 'wiktionary.org', 'mediawiki.org' ];
	 *
	 * This would add rel="nofollow" to links to de.wikipedia.org, but not
	 * en.wikipedia.org, wiktionary.org, en.wiktionary.org, us.en.wikipedia.org,
	 * etc.
	 *
	 * Defaults to mediawiki.org for the links included in the software by default.
	 */
	public const NoFollowDomainExceptions = [
		'default' => [ 'mediawiki.org', ],
		'type' => 'list',
	];

	/**
	 * By default MediaWiki does not register links pointing to same server in
	 * externallinks dataset, use this value to override:
	 */
	public const RegisterInternalExternals = [
		'default' => false,
	];

	/**
	 * Allow DISPLAYTITLE to change title display
	 */
	public const AllowDisplayTitle = [
		'default' => true,
	];

	/**
	 * For consistency, restrict DISPLAYTITLE to text that normalizes to the same
	 * canonical DB key. Also disallow some inline CSS rules like display: none;
	 * which can cause the text to be hidden or unselectable.
	 */
	public const RestrictDisplayTitle = [
		'default' => true,
	];

	/**
	 * Maximum number of calls per parse to expensive parser functions such as
	 * PAGESINCATEGORY.
	 */
	public const ExpensiveParserFunctionLimit = [
		'default' => 100,
	];

	/**
	 * Preprocessor caching threshold
	 * Setting it to 'false' will disable the preprocessor cache.
	 */
	public const PreprocessorCacheThreshold = [
		'default' => 1000,
	];

	/**
	 * Enable interwiki transcluding.  Only when iw_trans=1 in the interwiki table.
	 */
	public const EnableScaryTranscluding = [
		'default' => false,
	];

	/**
	 * Expiry time for transcluded templates cached in object cache.
	 *
	 * Only used $wgEnableInterwikiTranscluding is set to true.
	 */
	public const TranscludeCacheExpiry = [
		'default' => 3600,
	];

	/**
	 * Enable the magic links feature of automatically turning ISBN xxx,
	 * PMID xxx, RFC xxx into links
	 *
	 * @since 1.28
	 */
	public const EnableMagicLinks = [
		'default' => [
			'ISBN' => false,
			'PMID' => false,
			'RFC' => false,
		],
		'type' => 'map',
	];

	/**
	 * Set this to true to allow the {{USERLANGUAGE}} magic word to return the
	 * actual user language. If it is false, {{USERLANGUAGE}} will return the
	 * page language. Setting this to true is discouraged since the page
	 * language should typically be used in the content area. Accessing the user
	 * language using this feature reduces the efficiency of the parser cache.
	 *
	 * @since 1.43
	 */
	public const ParserEnableUserLanguage = [
		'default' => false,
	];

	// endregion -- end of parser settings

	/***************************************************************************/
	// region   Statistics and content analysis
	/** @name   Statistics and content analysis */

	/**
	 * Method used to determine if a page in a content namespace should be counted
	 * as a valid article.
	 *
	 * Redirect pages will never be counted as valid articles.
	 *
	 * This variable can have the following values:
	 * - 'any': all pages as considered as valid articles
	 * - 'link': the page must contain a [[wiki link]] to be considered valid
	 *
	 * See also See https://www.mediawiki.org/wiki/Manual:Article_count
	 *
	 * Retroactively changing this variable will not affect the existing count,
	 * to update it, you will need to run the maintenance/updateArticleCount.php
	 * script.
	 */
	public const ArticleCountMethod = [
		'default' => 'link',
	];

	/**
	 * How many days user must be idle before they are considered inactive. Will affect
	 * the number shown on Special:Statistics, Special:ActiveUsers, and the
	 * {{NUMBEROFACTIVEUSERS}} magic word in wikitext.
	 *
	 * You might want to leave this as the default value, to provide comparable
	 * numbers between different wikis.
	 */
	public const ActiveUserDays = [
		'default' => 30,
	];

	/**
	 * The following variables define 3 user experience levels:
	 *
	 * - newcomer: has not yet reached the 'learner' level
	 *
	 * - learner: has at least $wgLearnerEdits and has been
	 *            a member for $wgLearnerMemberSince days
	 *            but has not yet reached the 'experienced' level.
	 *
	 * - experienced: has at least $wgExperiencedUserEdits edits and
	 *                has been a member for $wgExperiencedUserMemberSince days.
	 */
	public const LearnerEdits = [
		'default' => 10,
	];

	/**
	 * Number of days the user must exist before becoming a learner.
	 *
	 * @see self::LearnerEdits
	 */
	public const LearnerMemberSince = [
		'default' => 4,
	];

	/**
	 * Number of edits the user must have before becoming "experienced".
	 *
	 * @see self::LearnerEdits
	 */
	public const ExperiencedUserEdits = [
		'default' => 500,
	];

	/**
	 * Number of days the user must exist before becoming "experienced".
	 *
	 * @see self::LearnerEdits
	 */
	public const ExperiencedUserMemberSince = [
		'default' => 30,
	];

	/**
	 * Maximum number of revisions of a page that will be checked against every new edit
	 * made to determine whether the edit was a manual revert.
	 *
	 * Computational time required increases roughly linearly with this configuration
	 * variable.
	 *
	 * Larger values will let you detect very deep reverts, but at the same time can give
	 * unexpected results (such as marking large amounts of edits as reverts) and may slow
	 * down the wiki slightly when saving new edits.
	 *
	 * Setting this to 0 will disable the manual revert detection feature entirely.
	 *
	 * See this document for a discussion on this topic:
	 * https://meta.wikimedia.org/wiki/Research:Revert
	 *
	 * @since 1.36
	 */
	public const ManualRevertSearchRadius = [
		'default' => 15,
		'type' => 'integer',
	];

	/**
	 * Maximum depth (revision count) of reverts that will have their reverted edits marked
	 * with the mw-reverted change tag. Reverts deeper than that will not have any edits
	 * marked as reverted at all.
	 *
	 * Large values can lead to lots of revisions being marked as "reverted", which may appear
	 * confusing to users.
	 *
	 * Setting this to 0 will disable the reverted tag entirely.
	 *
	 * @since 1.36
	 */
	public const RevertedTagMaxDepth = [
		'default' => 15,
		'type' => 'integer',
	];

	// endregion -- End of statistics and content analysis

	/***************************************************************************/
	// region   User accounts, authentication
	/** @name   User accounts, authentication */

	/**
	 * Central ID lookup providers
	 * Key is the provider ID, value is a specification for ObjectFactory
	 *
	 * @since 1.27
	 */
	public const CentralIdLookupProviders = [
		'default' => [
			'local' => [
				'class' => LocalIdLookup::class,
				'services' => [
					'MainConfig',
					'DBLoadBalancerFactory',
					'HideUserUtils',
				]
			]
		],
		'type' => 'map',
	];

	/**
	 * Central ID lookup provider to use by default
	 */
	public const CentralIdLookupProvider = [
		'default' => 'local',
		'type' => 'string',
	];

	/**
	 * User registration timestamp provider classes
	 * @since 1.41
	 */
	public const UserRegistrationProviders = [
		'default' => [
			LocalUserRegistrationProvider::TYPE => [
				'class' => LocalUserRegistrationProvider::class,
				'services' => [
					'UserFactory',
					'ConnectionProvider',
				]
			]
		],
		'type' => 'map',
	];

	/**
	 * Password policy for the wiki.
	 *
	 * Structured as
	 * ```
	 * [
	 *     'policies' => [ <group> => [ <policy> => <settings>, ... ], ... ],
	 *     'checks' => [ <policy> => <callback>, ... ],
	 * ]
	 * ```
	 * where <group> is a user group, <policy> is a password policy name
	 * (arbitrary string) defined in the 'checks' part, <callback> is the
	 * PHP callable implementing the policy check, <settings> is an array
	 * of options with the following keys:
	 * - value: (number, boolean or null) the value to pass to the callback
	 * - forceChange: (boolean, default false) if the password is invalid, do
	 *   not let the user log in without changing the password
	 * - suggestChangeOnLogin: (boolean, default false) if true and the password is
	 *   invalid, suggest a password change if logging in. If all the failing policies
	 *   that apply to the user have this set to false, the password change
	 *   screen will not be shown. 'forceChange' takes precedence over
	 *   'suggestChangeOnLogin' if they are both present.
	 * As a shorthand for [ 'value' => <value> ], simply <value> can be written.
	 * When multiple password policies are defined for a user, the settings
	 * arrays are merged, and for fields which are set in both arrays, the
	 * larger value (as understood by PHP's 'max' method) is taken.
	 *
	 * A user's effective policy is the superset of all policy statements
	 * from the policies for the groups where the user is a member. If more
	 * than one group policy include the same policy statement, the value is
	 * the max() of the values. Note true > false. The 'default' policy group
	 * is required, and serves as the minimum policy for all users.
	 *
	 * Callbacks receive three arguments: the policy value, the User object
	 * and the password; and must return a StatusValue. A non-good status
	 * means the password will not be accepted for new accounts, and existing
	 * accounts will be prompted for password change or barred from logging in
	 * (depending on whether the status is a fatal or merely error/warning).
	 *
	 * The checks supported by core are:
	 * - MinimalPasswordLength - Minimum length a user can set.
	 * - MinimumPasswordLengthToLogin - Passwords shorter than this will
	 *    not be allowed to login, or offered a chance to reset their password
	 *    as part of the login workflow, regardless if it is correct.
	 * - MaximalPasswordLength - maximum length password a user is allowed
	 *    to attempt. Prevents DoS attacks with pbkdf2.
	 * - PasswordCannotBeSubstringInUsername - Password cannot be a substring
	 *    (contained within) the username.
	 * - PasswordCannotMatchDefaults - Username/password combination cannot
	 *    match a list of default passwords used by MediaWiki in the past.
	 * - PasswordNotInCommonList - Password not in best practices list of
	 *    100,000 commonly used passwords. Due to the size of the list this
	 *    is a probabilistic test.
	 *
	 * If you add custom checks, for Special:PasswordPolicies to display them correctly,
	 * every check should have a corresponding passwordpolicies-policy-<check> message,
	 * and every settings field other than 'value' should have a corresponding
	 * passwordpolicies-policyflag-<flag> message (<check> and <flag> are in lowercase).
	 * The check message receives the policy value as a parameter, the flag message
	 * receives the flag value (or values if it's an array).
	 *
	 * @since 1.26
	 * @see \MediaWiki\Password\PasswordPolicyChecks
	 * @see \MediaWiki\User\User::checkPasswordValidity()
	 */
	public const PasswordPolicy = [
		'default' => [
			'policies' => [
				'bureaucrat' => [
					'MinimalPasswordLength' => 10,
					'MinimumPasswordLengthToLogin' => 1,
				],
				'sysop' => [
					'MinimalPasswordLength' => 10,
					'MinimumPasswordLengthToLogin' => 1,
				],
				'interface-admin' => [
					'MinimalPasswordLength' => 10,
					'MinimumPasswordLengthToLogin' => 1,
				],
				'bot' => [
					'MinimalPasswordLength' => 10,
					'MinimumPasswordLengthToLogin' => 1,
				],
				'default' => [
					'MinimalPasswordLength' => [ 'value' => 8, 'suggestChangeOnLogin' => true ],
					'PasswordCannotBeSubstringInUsername' => [
						'value' => true,
						'suggestChangeOnLogin' => true
					],
					'PasswordCannotMatchDefaults' => [ 'value' => true, 'suggestChangeOnLogin' => true ],
					'MaximalPasswordLength' => [ 'value' => 4096, 'suggestChangeOnLogin' => true ],
					'PasswordNotInCommonList' => [ 'value' => true, 'suggestChangeOnLogin' => true ],
				],
			],
			'checks' => [
				'MinimalPasswordLength' => [ PasswordPolicyChecks::class, 'checkMinimalPasswordLength' ],
				'MinimumPasswordLengthToLogin' => [ PasswordPolicyChecks::class, 'checkMinimumPasswordLengthToLogin' ],
				'PasswordCannotBeSubstringInUsername' => [ PasswordPolicyChecks::class, 'checkPasswordCannotBeSubstringInUsername' ],
				'PasswordCannotMatchDefaults' => [ PasswordPolicyChecks::class, 'checkPasswordCannotMatchDefaults' ],
				'MaximalPasswordLength' => [ PasswordPolicyChecks::class, 'checkMaximalPasswordLength' ],
				'PasswordNotInCommonList' => [ PasswordPolicyChecks::class, 'checkPasswordNotInCommonList' ],
			],
		],
		'type' => 'map',
		'mergeStrategy' => 'array_replace_recursive',
	];

	/**
	 * Configure AuthManager
	 *
	 * All providers are constructed using ObjectFactory, see that for the general
	 * structure. The array may also contain a key "sort" used to order providers:
	 * providers are stably sorted by this value, which should be an integer
	 * (default is 0).
	 *
	 * Elements are:
	 * - preauth: Array (keys ignored) of specifications for PreAuthenticationProviders
	 * - primaryauth: Array (keys ignored) of specifications for PrimaryAuthenticationProviders
	 * - secondaryauth: Array (keys ignored) of specifications for SecondaryAuthenticationProviders
	 *
	 * @since 1.27
	 * @note If this is null or empty, the value from $wgAuthManagerAutoConfig is
	 * used instead. Local customization should generally set this variable from
	 * scratch to the desired configuration. Extensions that want to
	 * auto-configure themselves should use $wgAuthManagerAutoConfig instead.
	 */
	public const AuthManagerConfig = [
		'default' => null,
		'type' => '?map',
	];

	/**
	 * @see self::AuthManagerConfig
	 * @since 1.27
	 */
	public const AuthManagerAutoConfig = [
		'default' => [
			'preauth' => [
				ThrottlePreAuthenticationProvider::class => [
					'class' => ThrottlePreAuthenticationProvider::class,
					'sort' => 0,
				],
			],
			'primaryauth' => [
				// TemporaryPasswordPrimaryAuthenticationProvider should come before
				// any other PasswordAuthenticationRequest-based
				// PrimaryAuthenticationProvider (or at least any that might return
				// FAIL rather than ABSTAIN for a wrong password), or password reset
				// won't work right. Do not remove this (or change the key) or
				// auto-configuration of other such providers in extensions will
				// probably auto-insert themselves in the wrong place.
				TemporaryPasswordPrimaryAuthenticationProvider::class => [
					'class' => TemporaryPasswordPrimaryAuthenticationProvider::class,
					'services' => [
						'DBLoadBalancerFactory',
						'UserOptionsLookup',
					],
					'args' => [ [
						// Fall through to LocalPasswordPrimaryAuthenticationProvider
						'authoritative' => false,
					] ],
					'sort' => 0,
				],
				LocalPasswordPrimaryAuthenticationProvider::class => [
					'class' => LocalPasswordPrimaryAuthenticationProvider::class,
					'services' => [
						'DBLoadBalancerFactory',
					],
					'args' => [ [
						// Last one should be authoritative, or else the user will get
						// a less-than-helpful error message (something like "supplied
						// authentication info not supported" rather than "wrong
						// password") if it too fails.
						'authoritative' => true,
					] ],
					'sort' => 100,
				],
			],
			'secondaryauth' => [
				CheckBlocksSecondaryAuthenticationProvider::class => [
					'class' => CheckBlocksSecondaryAuthenticationProvider::class,
					'sort' => 0,
				],
				ResetPasswordSecondaryAuthenticationProvider::class => [
					'class' => ResetPasswordSecondaryAuthenticationProvider::class,
					'sort' => 100,
				],
				// Linking during login is experimental, enable at your own risk - T134952
				// MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider::class => [
				//   'class' => MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider::class,
				//   'sort' => 100,
				// ],
				EmailNotificationSecondaryAuthenticationProvider::class => [
					'class' => EmailNotificationSecondaryAuthenticationProvider::class,
					'services' => [
						'DBLoadBalancerFactory',
					],
					'sort' => 200,
				],
			],
		],
		'type' => 'map',
		'mergeStrategy' => 'array_plus_2d',
	];

	/**
	 * Configures RememberMe authentication request added by AuthManager. It can show a "remember
	 * me" checkbox that, when checked, will cause it to take more time for the authenticated
	 * session to expire. It can also be configured to always or to never extend the authentication
	 * session.
	 *
	 * Valid values are listed in RememberMeAuthenticationRequest::ALLOWED_FLAGS.
	 *
	 * @since 1.36
	 */
	public const RememberMe = [
		'default' => 'choose',
		'type' => 'string',
	];

	/**
	 * Time frame for re-authentication.
	 *
	 * With only password-based authentication, you'd just ask the user to re-enter
	 * their password to verify certain operations like changing the password or
	 * changing the account's email address. But under AuthManager, the user might
	 * not have a password (you might even have to redirect the browser to a
	 * third-party service or something complex like that), you might want to have
	 * both factors of a two-factor authentication, and so on. So, the options are:
	 * - Incorporate the whole multi-step authentication flow within everything
	 *   that needs to do this.
	 * - Consider it good if they used Special:UserLogin during this session within
	 *   the last X seconds.
	 * - Come up with a third option.
	 *
	 * MediaWiki currently takes the second option. This setting configures the
	 * "X seconds".
	 *
	 * This allows for configuring different time frames for different
	 * "operations". The operations used in MediaWiki core include:
	 * - LinkAccounts
	 * - UnlinkAccount
	 * - ChangeCredentials
	 * - RemoveCredentials
	 * - ChangeEmail
	 *
	 * Additional operations may be used by extensions, either explicitly by
	 * calling AuthManager::securitySensitiveOperationStatus(),
	 * ApiAuthManagerHelper::securitySensitiveOperation() or
	 * SpecialPage::checkLoginSecurityLevel(), or implicitly by overriding
	 * SpecialPage::getLoginSecurityLevel() or by subclassing
	 * AuthManagerSpecialPage.
	 *
	 * The key 'default' is used if a requested operation isn't defined in the array.
	 *
	 * @since 1.27
	 */
	public const ReauthenticateTime = [
		'default' => [ 'default' => 300, ],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'integer', ],
	];

	/**
	 * Whether to allow security-sensitive operations when re-authentication is not possible.
	 *
	 * If AuthManager::canAuthenticateNow() is false (e.g. the current
	 * SessionProvider is not able to change users, such as when OAuth is in use),
	 * AuthManager::securitySensitiveOperationStatus() cannot sensibly return
	 * SEC_REAUTH. Setting an operation true here will have it return SEC_OK in
	 * that case, while setting it false will have it return SEC_FAIL.
	 *
	 * The key 'default' is used if a requested operation isn't defined in the array.
	 *
	 * @since 1.27
	 * @see self::ReauthenticateTime
	 */
	public const AllowSecuritySensitiveOperationIfCannotReauthenticate = [
		'default' => [ 'default' => true, ],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'boolean', ],
	];

	/**
	 * List of AuthenticationRequest class names which are not changeable through
	 * Special:ChangeCredentials and the changeauthenticationdata API.
	 *
	 * This is only enforced on the client level; AuthManager itself (e.g.
	 * AuthManager::allowsAuthenticationDataChange calls) is not affected.
	 * Class names are checked for exact match (not for subclasses).
	 *
	 * @since 1.27
	 */
	public const ChangeCredentialsBlacklist = [
		'default' => [
			TemporaryPasswordAuthenticationRequest::class,
		],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	/**
	 * List of AuthenticationRequest class names which are not removable through
	 * Special:RemoveCredentials and the removeauthenticationdata API.
	 *
	 * This is only enforced on the client level; AuthManager itself (e.g.
	 * AuthManager::allowsAuthenticationDataChange calls) is not affected.
	 * Class names are checked for exact match (not for subclasses).
	 *
	 * @since 1.27
	 */
	public const RemoveCredentialsBlacklist = [
		'default' => [
			PasswordAuthenticationRequest::class,
		],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	/**
	 * Specifies if users should be sent to a password-reset form on login, if their
	 * password doesn't meet the requirements of User::isValidPassword().
	 *
	 * @since 1.23
	 */
	public const InvalidPasswordReset = [
		'default' => true,
	];

	/**
	 * Default password type to use when hashing user passwords.
	 *
	 * Must be set to a type defined in $wgPasswordConfig, or a type that
	 * is registered by default in PasswordFactory.php.
	 *
	 * @since 1.24
	 */
	public const PasswordDefault = [
		'default' => 'pbkdf2',
	];

	/**
	 * Configuration for built-in password types.
	 *
	 * Maps the password type to an array of options:
	 *
	 * - class: The Password class to use.
	 * - factory (since 1.40): A function that creates and returns a suitable Password object.
	 *   This option is intended only for internal use; the function signature is unstable and
	 *   subject to change in future versions.
	 *
	 * All other options are class-dependent.
	 *
	 * An advanced example:
	 *
	 * ```
	 * $wgPasswordConfig['bcrypt-peppered'] = [
	 *     'class' => EncryptedPassword::class,
	 *     'underlying' => 'bcrypt',
	 *     'secrets' => [
	 *         hash( 'sha256', 'secret', true ),
	 *     ],
	 *     'cipher' => 'aes-256-cbc',
	 * ];
	 * ```
	 *
	 * @since 1.24
	 */
	public const PasswordConfig = [
		'default' => [
			'A' => [
				'class' => MWOldPassword::class,
			],
			'B' => [
				'class' => MWSaltedPassword::class,
			],
			'pbkdf2-legacyA' => [
				'class' => LayeredParameterizedPassword::class,
				'types' => [
					'A',
					'pbkdf2',
				],
			],
			'pbkdf2-legacyB' => [
				'class' => LayeredParameterizedPassword::class,
				'types' => [
					'B',
					'pbkdf2',
				],
			],
			'bcrypt' => [
				'class' => BcryptPassword::class,
				'cost' => 9,
			],
			'pbkdf2' => [
				'class' => Pbkdf2PasswordUsingOpenSSL::class,
				'algo' => 'sha512',
				'cost' => '30000',
				'length' => '64',
			],
			'argon2' => [
				'class' => Argon2Password::class,

				// Algorithm used:
				// * 'argon2i' is optimized against side-channel attacks
				// * 'argon2id' is optimized against both side-channel and GPU cracking
				// * 'auto' to use the best available algorithm. If you're using more than one server, be
				//   careful when you're mixing PHP versions because newer PHP might generate hashes that
				//   older versions would not understand.
				'algo' => 'auto',

				// The parameters below are the same as options accepted by password_hash().
				// Set them to override that function's defaults.
				//
				// 'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
				// 'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
				// 'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
			],
		],
		'type' => 'map',
	];

	/**
	 * Whether to allow password resets ("enter some identifying data, and we'll send an email
	 * with a temporary password you can use to get back into the account") identified by
	 * various bits of data.  Setting all of these to false (or the whole variable to false)
	 * has the effect of disabling password resets entirely
	 */
	public const PasswordResetRoutes = [
		'default' => [
			'username' => true,
			'email' => true,
		],
		'type' => 'map',
	];

	/**
	 * Maximum number of Unicode characters in signature
	 */
	public const MaxSigChars = [
		'default' => 255,
	];

	/**
	 * Behavior of signature validation. Allowed values are:
	 *  - 'warning' - invalid signatures cause a warning to be displayed on the preferences page,
	 * but they are still used when signing comments; new invalid signatures can still be saved as
	 * normal
	 *  - 'new' - existing invalid signatures behave as above; new invalid signatures can't be
	 * saved
	 *  - 'disallow' - existing invalid signatures are no longer used when signing comments; new
	 * invalid signatures can't be saved
	 *
	 * @since 1.35
	 */
	public const SignatureValidation = [
		'default' => 'warning',
	];

	/**
	 * List of lint error codes which don't cause signature validation to fail.
	 *
	 * @see https://www.mediawiki.org/wiki/Help:Lint_errors
	 * @since 1.35
	 */
	public const SignatureAllowedLintErrors = [
		'default' => [ 'obsolete-tag', ],
		'type' => 'list',
	];

	/**
	 * Maximum number of bytes in username. You want to run the maintenance
	 * script ./maintenance/checkUsernames.php once you have changed this value.
	 */
	public const MaxNameChars = [
		'default' => 255,
	];

	/**
	 * Array of usernames which may not be registered or logged in from
	 * Maintenance scripts can still use these
	 *
	 * @see \MediaWiki\User\User::MAINTENANCE_SCRIPT_USER
	 */
	public const ReservedUsernames = [
		'default' => [
			'MediaWiki default', // Default 'Main Page' and MediaWiki: message pages
			'Conversion script', // Used for the old Wikipedia software upgrade
			'Maintenance script', // Maintenance scripts which perform editing, image import script
			'Template namespace initialisation script', // Used in 1.2->1.3 upgrade
			'ScriptImporter', // Default user name used by maintenance/importSiteScripts.php
			'Delete page script', // Default user name used by maintenance/deleteBatch.php
			'Move page script', // Default user name used by maintenance/deleteBatch.php
			'Command line script', // Default user name used by maintenance/undelete.php
			'Unknown user', // Used in WikiImporter & RevisionStore for revisions with no author and in User for invalid user id
			'msg:double-redirect-fixer', // Automatic double redirect fix
			'msg:usermessage-editor', // Default user for leaving user messages
			'msg:proxyblocker', // For $wgProxyList and Special:Blockme (removed in 1.22)
			'msg:sorbs', // For $wgEnableDnsBlacklist etc.
			'msg:spambot_username', // Used by cleanupSpam.php
			'msg:autochange-username', // Used by anon category RC entries (removed in 1.44)
		],
		'type' => 'list',
	];

	/**
	 * Settings added to this array will override the default globals for the user
	 * preferences used by anonymous visitors and newly created accounts.
	 *
	 * For instance, to disable editing on double clicks:
	 * $wgDefaultUserOptions ['editondblclick'] = 0;
	 *
	 * To save storage space, no user_properties row will be stored for users with the
	 * default setting for a given option, even if the user manually selects that option.
	 * This means that a change to the defaults will change the setting for all users who
	 * have been using the default setting; there is no way for users to opt out of this.
	 * $wgConditionalUserOptions can be used to change the default value for future users
	 * only.
	 *
	 * @see self::ConditionalUserOptions
	 */
	public const DefaultUserOptions = [
		'default' =>
			// This array should be sorted by key
			[
				'ccmeonemails' => 0,
				'date' => 'default',
				'diffonly' => 0,
				'diff-type' => 'table',
				'disablemail' => 0,
				'editfont' => 'monospace',
				'editondblclick' => 0,
				'editrecovery' => 0,
				'editsectiononrightclick' => 0,
				'email-allow-new-users' => 1,
				'enotifminoredits' => 0,
				'enotifrevealaddr' => 0,
				'enotifusertalkpages' => 1,
				'enotifwatchlistpages' => 1,
				'extendwatchlist' => 1,
				'fancysig' => 0,
				'forceeditsummary' => 0,
				'forcesafemode' => 0,
				'gender' => 'unknown',
				'hidecategorization' => 1,
				'hideminor' => 0,
				'hidepatrolled' => 0,
				'imagesize' => 2,
				'minordefault' => 0,
				'newpageshidepatrolled' => 0,
				'nickname' => '',
				'norollbackdiff' => 0,
				'prefershttps' => 1,
				'previewonfirst' => 0,
				'previewontop' => 1,
				'pst-cssjs' => 1,
				'rcdays' => 7,
				'rcenhancedfilters-disable' => 0,
				'rclimit' => 50,
				'requireemail' => 0,
				'search-match-redirect' => true,
				'search-special-page' => 'Search',
				'search-thumbnail-extra-namespaces' => true,
				'searchlimit' => 20,
				'showhiddencats' => 0,
				'shownumberswatching' => 1,
				'showrollbackconfirmation' => 0,
				'skin' => false,
				'skin-responsive' => 1,
				'thumbsize' => 5,
				'underline' => 2,
				'useeditwarning' => 1,
				'uselivepreview' => 0,
				'usenewrc' => 1,
				'watchcreations' => 1,
				'watchcreations-expiry' => 'infinite',
				'watchdefault' => 1,
				'watchdefault-expiry' => 'infinite',
				'watchdeletion' => 0,
				'watchlistdays' => 7,
				'watchlisthideanons' => 0,
				'watchlisthidebots' => 0,
				'watchlisthidecategorization' => 1,
				'watchlisthideliu' => 0,
				'watchlisthideminor' => 0,
				'watchlisthideown' => 0,
				'watchlisthidepatrolled' => 0,
				'watchlistreloadautomatically' => 0,
				'watchlistunwatchlinks' => 0,
				'watchmoves' => 0,
				'watchrollback' => 0,
				'watchuploads' => 1,
				'watchrollback-expiry' => 'infinite',
				'watchstar-expiry' => 'infinite',
				'wlenhancedfilters-disable' => 0,
				'wllimit' => 250,
			],
		'type' => 'map',
	];

	/**
	 * Conditional defaults for user options
	 *
	 * Map of user options to conditional defaults descriptors, which is an array
	 * of conditional cases [ VALUE, CONDITION1, CONDITION2 ], where VALUE is the default value for
	 * all users that meet ALL conditions, and each CONDITION is either a:
	 *     (a) a CUDCOND_* constant (when condition does not take any arguments), or
	 *     (b) an array [ CUDCOND_*, argument1, argument1, ... ] (when chosen condition takes at
	 *         least one argument).
	 *
	 * When `null` is used as the VALUE, it is interpreted as "no conditional default for this
	 * condition". In other words, `null` and $wgDefaultUserOptions['user-option'] can be used
	 * interchangeably as the VALUE.
	 *
	 * All conditions are evaluated in order. When no condition matches.
	 * $wgDefaultUserOptions is used instead.
	 *
	 * Example of valid configuration:
	 *   $wgConditionalUserOptions['user-option'] = [
	 *       [ 'registered in 2024', [ CUDCOND_AFTER, '20240101000000' ] ]
	 *   ];
	 *
	 * List of valid conditions:
	 *   * CUDCOND_AFTER: user registered after given timestamp (args: string $timestamp)
	 *   * CUDCOND_ANON: allows specifying a default for anonymous (logged-out, non-temporary) users
	 *   * CUDCOND_NAMED: allows specifying a default for named (registered, non-temporary) users
	 *   * CUDCOND_USERGROUP: users with a specific user group
	 *
	 * @since 1.42
	 * @see self::DefaultUserOptions
	 */
	public const ConditionalUserOptions = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * An array of preferences to not show for the user
	 */
	public const HiddenPrefs = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Characters to prevent during new account creations.
	 *
	 * This is used in a regular expression character class during
	 * registration (regex metacharacters like / are escaped).
	 */
	public const InvalidUsernameCharacters = [
		'default' => '@:>=',
	];

	/**
	 * Character used as a delimiter when testing for interwiki userrights
	 * (In Special:UserRights, it is possible to modify users on different
	 * databases if the delimiter is used, e.g. "Someuser@enwiki").
	 *
	 * It is recommended that you have this delimiter in
	 * $wgInvalidUsernameCharacters above, or you will not be able to
	 * modify the user rights of those users via Special:UserRights
	 */
	public const UserrightsInterwikiDelimiter = [
		'default' => '@',
	];

	/**
	 * This is to let user authenticate using https when they come from http.
	 *
	 * Based on an idea by George Herbert on wikitech-l:
	 * https://lists.wikimedia.org/pipermail/wikitech-l/2010-October/050039.html
	 *
	 * @since 1.17
	 */
	public const SecureLogin = [
		'default' => false,
	];

	/**
	 * Versioning for authentication tokens.
	 *
	 * If non-null, this is combined with the user's secret (the user_token field
	 * in the DB) to generate the token cookie. Changing this will invalidate all
	 * active sessions (i.e. it will log everyone out).
	 *
	 * @since 1.27
	 */
	public const AuthenticationTokenVersion = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * MediaWiki\Session\SessionProvider configuration.
	 *
	 * Values are ObjectFactory specifications for the SessionProviders to be
	 * used. Keys in the array are ignored; the class name is conventionally
	 * used as the key to avoid collisions. Order is not significant.
	 *
	 * @since 1.27
	 */
	public const SessionProviders = [
		'type' => 'map',
		'default' => [
			\MediaWiki\Session\CookieSessionProvider::class => [
				'class' => \MediaWiki\Session\CookieSessionProvider::class,
				'args' => [ [
					'priority' => 30,
				] ],
			],
			\MediaWiki\Session\BotPasswordSessionProvider::class => [
				'class' => \MediaWiki\Session\BotPasswordSessionProvider::class,
				'args' => [ [
					'priority' => 75,
				] ],
				'services' => [
					'GrantsInfo'
				],
			],
		],
	];

	/**
	 * Configuration for automatic creation of temporary accounts on page save.
	 * This can be enabled to avoid exposing the IP addresses of casual editors who
	 * do not explicitly create an account.
	 *
	 * @warning This is EXPERIMENTAL, enabling may break extensions.
	 *
	 * An associative array with the following keys:
	 *
	 *   - known: (bool) Whether auto-creation is known about. Set this to 'true' if
	 *     temp accounts have been created on this wiki already. This setting allows
	 *     temp users to be recognized even if auto-creation is currently disabled.
	 *     If auto-creation is enabled via the 'enabled' property, then 'known' is
	 *     overriden to true.
	 *   - enabled: (bool) Whether auto-creation is enabled. If changing this
	 *     value from 'true' to 'false', you should also set 'known' to true, so
	 *     that relevant code can continue to identify temporary accounts as
	 *     visually and conceptually distinct from anonymous accounts and named accounts.
	 *   - actions: (array) A list of actions for which the feature is enabled.
	 *     Currently only "edit" is supported.
	 *   - genPattern: (string) The pattern used when generating new usernames.
	 *     This should have "$1" indicating the place where the serial string will
	 *     be substituted.
	 *   - matchPattern: (string|string[]|null) The pattern used when determining whether a
	 *     username is a temporary user. This affects the rights of the user
	 *     and also prevents explicit creation of users with matching names.
	 *     This is ignored if "enabled" is false. If the value is null, the
	 *     the genPattern value is used as the matchPattern.
	 *   - reservedPattern: (string) A pattern used to determine whether a
	 *     username should be denied for explicit creation, in addition to
	 *     matchPattern. This is used even if "enabled" is false.
	 *   - serialProvider: (array) Configuration for generation of unique integer
	 *     indexes which are used to make temporary usernames.
	 *       - type: (string) May be "local" to allocate indexes using the local
	 *         database. If the CentralAuth extension is enabled, it may be
	 *         "centralauth". Extensions may plug in additional types using the
	 *         TempUserSerialProviders attribute.
	 *       - numShards (int, default 1): A small integer. This can be set to a
	 *         value greater than 1 to avoid acquiring a global lock when
	 *         allocating IDs, at the expense of making the IDs be non-monotonic.
	 *       - useYear: (bool) Restart at 1 each time the year changes (in UTC).
	 *         To avoid naming conflicts, the year is included in the name after
	 *         the prefix, in the form 'YYYY-'.
	 *   - serialMapping: (array) Configuration for mapping integer indexes to strings
	 *     to substitute into genPattern.
	 *       - type: (string) May be
	 *         - "readable-numeric" to use ASCII decimal numbers broken up with hyphens
	 *         - "plain-numeric" to use ASCII decimal numbers
	 *         - "localized-numeric" to use numbers localized using a specific language
	 *         - "filtered-radix" to use numbers in an arbitrary base between 2 and 36,
	 *           with an optional list of "bad" IDs to skip over.
	 *         - "scramble": to use ASCII decimal numbers that are short but
	 *           non-consecutive.
	 *       - language: (string) With "localized-numeric", the language code
	 *       - radix: (int) With "filtered-radix", the base
	 *       - badIndexes: (array) With "filtered-radix", an array with the bad unmapped
	 *         indexes in the values. The integers must be sorted and the list
	 *         must never change after the indexes have been allocated. The keys must
	 *         be zero-based array indexes.
	 *       - uppercase: (bool) With "filtered-radix", whether to use uppercase
	 *         letters, default false.
	 *       - offset: (int) With "plain-numeric" and "readable-numeric", a constant to add to the
	 *         stored index.
	 *    - expireAfterDays: (int|null, default 90) If not null, how many days should the temporary
	 *      accounts expire? You should run expireTemporaryAccounts.php periodically to expire
	 *      temporary accounts. Otherwise they are expired when they try to edit.
	 *    - notifyBeforeExpirationDays: (int|null, default 10) If not null, how many days before the
	 *      expiration of a temporary account should it be notified that their account is to be expired.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.39
	 */
	public const AutoCreateTempUser = [
		'properties' => [
			'known' => [ 'type' => 'bool', 'default' => false ],
			'enabled' => [ 'type' => 'bool', 'default' => false ],
			'actions' => [ 'type' => 'list', 'default' => [ 'edit' ] ],
			'genPattern' => [ 'type' => 'string', 'default' => '~$1' ],
			'matchPattern' => [ 'type' => 'string|array|null', 'default' => null ],
			'reservedPattern' => [ 'type' => 'string|null', 'default' => '~$1' ],
			'serialProvider' => [ 'type' => 'object', 'default' => [ 'type' => 'local', 'useYear' => true ] ],
			'serialMapping' => [ 'type' => 'object', 'default' => [ 'type' => 'readable-numeric' ] ],
			'expireAfterDays' => [ 'type' => 'int|null', 'default' => 90 ],
			'notifyBeforeExpirationDays' => [ 'type' => 'int|null', 'default' => 10 ],
		],
		'type' => 'object',
	];

	// endregion -- end user accounts

	/***************************************************************************/
	// region   User rights, access control and monitoring
	/** @name   User rights, access control and monitoring */

	/** List of IP addresses or CIDR ranges that are exempt from autoblocks. */
	public const AutoblockExemptions = [
		'default' => [],
		'type' => 'array',
	];

	/**
	 * Number of seconds before autoblock entries expire. Default 86400 = 1 day.
	 */
	public const AutoblockExpiry = [
		'default' => 86400,
	];

	/**
	 * Set this to true to allow blocked users to edit their own user talk page.
	 *
	 * This only applies to sitewide blocks. Partial blocks always allow users to
	 * edit their own user talk page unless otherwise specified in the block
	 * restrictions.
	 */
	public const BlockAllowsUTEdit = [
		'default' => true,
	];

	/**
	 * Limits on the possible sizes of range blocks.
	 *
	 * CIDR notation is hard to understand, it's easy to mistakenly assume that a
	 * /1 is a small range and a /31 is a large range. For IPv4, setting a limit of
	 * half the number of bits avoids such errors, and allows entire ISPs to be
	 * blocked using a small number of range blocks.
	 *
	 * For IPv6, RFC 3177 recommends that a /48 be allocated to every residential
	 * customer, so range blocks larger than /64 (half the number of bits) will
	 * plainly be required. RFC 4692 implies that a very large ISP may be
	 * allocated a /19 if a generous HD-Ratio of 0.8 is used, so we will use that
	 * as our limit. As of 2012, blocking the whole world would require a /4 range.
	 */
	public const BlockCIDRLimit = [
		'default' => [
			'IPv4' => 16,
			'IPv6' => 19,
		],
		'type' => 'map',
	];

	/**
	 * If true, sitewide blocked users will not be allowed to login. (Direct
	 * blocks only; IP blocks are ignored.) This can be used to remove users'
	 * read access on a private wiki.
	 */
	public const BlockDisablesLogin = [
		'default' => false,
	];

	/**
	 * Flag to enable partial blocks against performing certain actions.
	 *
	 * @unstable Temporary feature flag, T280532
	 * @since 1.37
	 */
	public const EnablePartialActionBlocks = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * If this is false, the number of blocks of a given target is limited to only 1.
	 *
	 * @since 1.42
	 */
	public const EnableMultiBlocks = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 *  Ipblocks table schema migration stage, for normalizing ipb_address field and
	 * 	adding the block_target table.
	 *
	 * Use the SCHEMA_COMPAT_XXX flags. Supported values:
	 *
	 *   - SCHEMA_COMPAT_OLD
	 *   - SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD
	 *   - SCHEMA_COMPAT_NEW
	 *
	 * History:
	 *   - 1.42: Added
	 *   - 1.43: Default changed from SCHEMA_COMPAT_OLD to SCHEMA_COMPAT_NEW
	 *   - 1.43: Deprecated, ignored, SCHEMA_COMPAT_NEW is implied
	 *
	 * @deprecated since 1.43
	 */
	public const BlockTargetMigrationStage = [
		'default' => SCHEMA_COMPAT_NEW,
		'type' => 'integer',
	];

	/**
	 * Pages anonymous user may see, set as an array of pages titles.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgWhitelistRead = [ "Main Page", "Wikipedia:Help" ];
	 * ```
	 *
	 * Special:Userlogin and Special:ChangePassword are always allowed.
	 *
	 * @note This will only work if $wgGroupPermissions['*']['read'] is false --
	 * see below. Otherwise, ALL pages are accessible, regardless of this setting.
	 * @note Also that this will only protect _pages in the wiki_. Uploaded files
	 * will remain readable. You can use img_auth.php to protect uploaded files,
	 * see https://www.mediawiki.org/wiki/Manual:Image_Authorization
	 * @note Extensions should not modify this, but use the TitleReadWhitelist
	 * hook instead.
	 */
	public const WhitelistRead = [
		'default' => false,
	];

	/**
	 * Pages anonymous user may see, set as an array of regular expressions.
	 *
	 * This function will match the regexp against the title name, which
	 * is without underscore.
	 *
	 * **Example:**
	 * To whitelist [[Main Page]]:
	 *
	 * ```
	 * $wgWhitelistReadRegexp = [ "/Main Page/" ];
	 * ```
	 * @note Unless ^ and/or $ is specified, a regular expression might match
	 * pages not intended to be allowed.  The above example will also
	 * allow a page named 'Security Main Page'.
	 *
	 * **Example:**
	 * To allow reading any page starting with 'User' regardless of the case:
	 *
	 * ```
	 * $wgWhitelistReadRegexp = [ "@^UsEr.*@i" ];
	 * ```
	 *
	 * Will allow both [[User is banned]] and [[User:JohnDoe]]
	 * @note This will only work if $wgGroupPermissions['*']['read'] is false --
	 * see below. Otherwise, ALL pages are accessible, regardless of this setting.
	 */
	public const WhitelistReadRegexp = [
		'default' => false,
	];

	/**
	 * Should editors be required to have a validated e-mail
	 * address before being allowed to edit?
	 */
	public const EmailConfirmToEdit = [
		'default' => false,
	];

	/**
	 * Should MediaWiki attempt to protect user's privacy when doing redirects?
	 * Keep this true if access counts to articles are made public.
	 */
	public const HideIdentifiableRedirects = [
		'default' => true,
	];

	/**
	 * Permission keys given to users in each group.
	 *
	 * This is an array where the keys are all groups and each value is an
	 * array of the format (right => boolean).
	 *
	 * The second format is used to support per-namespace permissions.
	 * Note that this feature does not fully work for all permission types.
	 *
	 * All users are implicitly in the '*' group including anonymous visitors;
	 * logged-in users are all implicitly in the 'user' group. These will be
	 * combined with the permissions of all groups that a given user is listed
	 * in the user_groups table.
	 *
	 * Note: Don't set $wgGroupPermissions = []; unless you know what you're
	 * doing! This will wipe all permissions, and may mean that your users are
	 * unable to perform certain essential tasks or access new functionality
	 * when new permissions are introduced and default grants established.
	 *
	 * Functionality to make pages inaccessible has not been extensively tested
	 * for security. Use at your own risk!
	 *
	 * This replaces $wgWhitelistAccount and $wgWhitelistEdit
	 */
	public const GroupPermissions = [
		'type' => 'map',
		'additionalProperties' => [
			'type' => 'map',
			'additionalProperties' => [ 'type' => 'boolean', ],
		],
		'mergeStrategy' => 'array_plus_2d',
		'default' => [
			'*' => [
				'createaccount' => true,
				'read' => true,
				'edit' => true,
				'createpage' => true,
				'createtalk' => true,
				'viewmyprivateinfo' => true,
				'editmyprivateinfo' => true,
				'editmyoptions' => true,
			],
			'user' => [
				'move' => true,
				'move-subpages' => true,
				'move-rootuserpages' => true,
				'move-categorypages' => true,
				'movefile' => true,
				'read' => true,
				'edit' => true,
				'createpage' => true,
				'createtalk' => true,
				'upload' => true,
				'reupload' => true,
				'reupload-shared' => true,
				'minoredit' => true,
				'editmyusercss' => true,
				'editmyuserjson' => true,
				'editmyuserjs' => true,
				'editmyuserjsredirect' => true,
				'sendemail' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'viewmywatchlist' => true,
				'editmywatchlist' => true,
			],
			'autoconfirmed' => [
				'autoconfirmed' => true,
				'editsemiprotected' => true,
			],
			'bot' => [
				'bot' => true,
				'autoconfirmed' => true,
				'editsemiprotected' => true,
				'nominornewtalk' => true,
				'autopatrol' => true,
				'suppressredirect' => true,
				'apihighlimits' => true,
			],
			'sysop' => [
				'block' => true,
				'createaccount' => true,
				'delete' => true,
				'bigdelete' => true,
				'deletedhistory' => true,
				'deletedtext' => true,
				'undelete' => true,
				'editinterface' => true,
				'editsitejson' => true,
				'edituserjson' => true,
				'import' => true,
				'importupload' => true,
				'move' => true,
				'move-subpages' => true,
				'move-rootuserpages' => true,
				'move-categorypages' => true,
				'patrol' => true,
				'autopatrol' => true,
				'protect' => true,
				'editprotected' => true,
				'rollback' => true,
				'upload' => true,
				'reupload' => true,
				'reupload-shared' => true,
				'unwatchedpages' => true,
				'autoconfirmed' => true,
				'editsemiprotected' => true,
				'ipblock-exempt' => true,
				'blockemail' => true,
				'markbotedits' => true,
				'apihighlimits' => true,
				'browsearchive' => true,
				'noratelimit' => true,
				'movefile' => true,
				'unblockself' => true,
				'suppressredirect' => true,
				'mergehistory' => true,
				'managechangetags' => true,
				'deletechangetags' => true,
			],
			'interface-admin' => [
				'editinterface' => true,
				'editsitecss' => true,
				'editsitejson' => true,
				'editsitejs' => true,
				'editusercss' => true,
				'edituserjson' => true,
				'edituserjs' => true,
			],
			'bureaucrat' => [
				'userrights' => true,
				'noratelimit' => true,
				'renameuser' => true,
			],
			'suppress' => [
				'hideuser' => true,
				'suppressrevision' => true,
				'viewsuppressed' => true,
				'suppressionlog' => true,
				'deleterevision' => true,
				'deletelogentry' => true,
			],
		],
	];

	/**
	 * List of groups which should be considered privileged (user accounts
	 * belonging in these groups can be abused in dangerous ways).
	 * This is used for some security checks, mainly logging.
	 * @since 1.41
	 * @see \MediaWiki\User\UserGroupManager::getUserPrivilegedGroups()
	 */
	public const PrivilegedGroups = [
		'default' => [
			'bureaucrat',
			'interface-admin',
			'suppress',
			'sysop',
		],
		'type' => 'list',
	];

	/**
	 * Permission keys revoked from users in each group.
	 *
	 * This acts the same way as $wgGroupPermissions above, except that
	 * if the user is in a group here, the permission will be removed from them.
	 *
	 * Improperly setting this could mean that your users will be unable to perform
	 * certain essential tasks, so use at your own risk!
	 */
	public const RevokePermissions = [
		'default' => [],
		'type' => 'map',
		'mergeStrategy' => 'array_plus_2d',
	];

	/**
	 * Groups that should inherit permissions from another group
	 *
	 * This allows defining a group that inherits its permissions
	 * from another group without having to copy all the permission
	 * grants over. For example, if you wanted a manual "confirmed"
	 * group that had the same permissions as "autoconfirmed":
	 *
	 * ```
	 * $wgGroupInheritsPermissions['confirmed'] = 'autoconfirmed';
	 * ```
	 *
	 * Recursive inheritance is currently not supported. In the above
	 * example, confirmed will only gain the permissions explicitly
	 * granted (or revoked) from autoconfirmed, not any permissions
	 * that autoconfirmed might inherit.
	 *
	 * @since 1.38
	 */
	public const GroupInheritsPermissions = [
		'default' => [],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'string', ],
	];

	/**
	 * Implicit groups, aren't shown on Special:Listusers or somewhere else
	 */
	public const ImplicitGroups = [
		'default' => [ '*', 'user', 'autoconfirmed' ],
		'type' => 'list',
	];

	/**
	 * A map of group names that the user is in, to group names that those users
	 * are allowed to add or revoke.
	 *
	 * Setting the list of groups to add or revoke to true is equivalent to "any
	 * group".
	 *
	 * **Example:**
	 * To allow sysops to add themselves to the "bot" group:
	 *
	 * ```
	 * $wgGroupsAddToSelf = [ 'sysop' => [ 'bot' ] ];
	 * ```
	 *
	 * **Example:**
	 * Implicit groups may be used for the source group, for instance:
	 *
	 * ```
	 * $wgGroupsRemoveFromSelf = [ '*' => true ];
	 * ```
	 *
	 * This allows users in the '*' group (i.e. any user) to remove themselves from
	 * any group that they happen to be in.
	 */
	public const GroupsAddToSelf = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * @see self::GroupsAddToSelf
	 */
	public const GroupsRemoveFromSelf = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Set of available actions that can be restricted via action=protect
	 * You probably shouldn't change this.
	 *
	 * Translated through restriction-* messages.
	 * RestrictionStore::listApplicableRestrictionTypes() will remove restrictions that are not
	 * applicable to a specific title (create and upload)
	 */
	public const RestrictionTypes = [
		'default' => [ 'create', 'edit', 'move', 'upload' ],
		'type' => 'list',
	];

	/**
	 * Rights which can be required for each protection level (via action=protect)
	 *
	 * You can add a new protection level that requires a specific
	 * permission by manipulating this array. The ordering of elements
	 * dictates the order on the protection form's lists.
	 *
	 *   - '' will be ignored (i.e. unprotected)
	 *   - 'autoconfirmed' is quietly rewritten to 'editsemiprotected' for backwards compatibility
	 *   - 'sysop' is quietly rewritten to 'editprotected' for backwards compatibility
	 */
	public const RestrictionLevels = [
		'default' => [ '', 'autoconfirmed', 'sysop' ],
		'type' => 'list',
	];

	/**
	 * Restriction levels that can be used with cascading protection
	 *
	 * A page can only be protected with cascading protection if the
	 * requested restriction level is included in this array.
	 *
	 * 'autoconfirmed' is quietly rewritten to 'editsemiprotected' for backwards compatibility.
	 * 'sysop' is quietly rewritten to 'editprotected' for backwards compatibility.
	 */
	public const CascadingRestrictionLevels = [
		'default' => [ 'sysop', ],
		'type' => 'list',
	];

	/**
	 * Restriction levels that should be considered "semiprotected"
	 *
	 * Certain places in the interface recognize a dichotomy between "protected"
	 * and "semiprotected", without further distinguishing the specific levels. In
	 * general, if anyone can be eligible to edit a protection level merely by
	 * reaching some condition in $wgAutopromote, it should probably be considered
	 * "semiprotected".
	 *
	 * 'autoconfirmed' is quietly rewritten to 'editsemiprotected' for backwards compatibility.
	 * 'sysop' is not changed, since it really shouldn't be here.
	 */
	public const SemiprotectedRestrictionLevels = [
		'default' => [ 'autoconfirmed', ],
		'type' => 'list',
	];

	/**
	 * Set the minimum permissions required to edit pages in each
	 * namespace.  If you list more than one permission, a user must
	 * have all of them to edit pages in that namespace.
	 *
	 * @note NS_MEDIAWIKI is implicitly restricted to 'editinterface'.
	 */
	public const NamespaceProtection = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Pages in namespaces in this array can not be used as templates.
	 *
	 * Elements MUST be numeric namespace ids, you can safely use the MediaWiki
	 * namespaces constants (NS_USER, NS_MAIN...).
	 *
	 * Among other things, this may be useful to enforce read-restrictions
	 * which may otherwise be bypassed by using the template mechanism.
	 */
	public const NonincludableNamespaces = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Number of seconds an account is required to age before it's given the
	 * implicit 'autoconfirm' group membership. This can be used to limit
	 * privileges of new accounts.
	 *
	 * Accounts created by earlier versions of the software may not have a
	 * recorded creation date, and will always be considered to pass the age test.
	 *
	 * When left at 0, all registered accounts will pass.
	 *
	 * **Example:**
	 * Set automatic confirmation to 10 minutes (which is 600 seconds):
	 *
	 * ```
	 * $wgAutoConfirmAge = 600;     // ten minutes
	 * ```
	 *
	 * Set age to one day:
	 *
	 * ```
	 * $wgAutoConfirmAge = 3600*24; // one day
	 * ```
	 */
	public const AutoConfirmAge = [
		'default' => 0,
	];

	/**
	 * Number of edits an account requires before it is autoconfirmed.
	 *
	 * Passing both this AND the time requirement is needed. Example:
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgAutoConfirmCount = 50;
	 * ```
	 */
	public const AutoConfirmCount = [
		'default' => 0,
	];

	/**
	 * Array containing the conditions of automatic promotion of a user to specific groups.
	 *
	 * The basic syntax for `$wgAutopromote` is:
	 *
	 *     $wgAutopromote = [
	 *         'groupname' => cond,
	 *         'group2' => cond2,
	 *     ];
	 *
	 * A `cond` may be:
	 *  - a single condition without arguments:
	 *      Note that Autopromote wraps a single non-array value into an array
	 *      e.g. `APCOND_EMAILCONFIRMED` OR
	 *           [ `APCOND_EMAILCONFIRMED` ]
	 *  - a single condition with arguments:
	 *      e.g. `[ APCOND_EDITCOUNT, 100 ]`
	 *  - a set of conditions:
	 *      e.g. `[ 'operand', cond1, cond2, ... ]`
	 *
	 * When constructing a set of conditions, the following conditions are available:
	 *  - `&` (**AND**):
	 *      promote if user matches **ALL** conditions
	 *  - `|` (**OR**):
	 *      promote if user matches **ANY** condition
	 *  - `^` (**XOR**):
	 *      promote if user matches **ONLY ONE OF THE CONDITIONS**
	 *  - `!` (**NOT**):
	 *      promote if user matces **NO** condition
	 *  - [ APCOND_EMAILCONFIRMED ]:
	 *      true if user has a confirmed e-mail
	 *  - [ APCOND_EDITCOUNT, number of edits (if null or missing $wgAutoConfirmCount will be used)]:
	 *      true if user has the at least the number of edits as the passed parameter
	 *  - [ APCOND_AGE, seconds since registration (if null or missing $wgAutoConfirmAge will be used)]:
	 *      true if the length of time since the user created their account
	 *      is at least the same length of time as the passed parameter
	 *  - [ APCOND_AGE_FROM_EDIT, seconds since first edit ]:
	 *      true if the length of time since the user made their first edit
	 *      is at least the same length of time as the passed parameter
	 *  - [ APCOND_INGROUPS, group1, group2, ... ]:
	 *      true if the user is a member of each of the passed groups
	 *  - [ APCOND_ISIP, ip ]:
	 *      true if the user has the passed IP address
	 *  - [ APCOND_IPINRANGE, range ]:
	 *      true if the user has an IP address in the range of the passed parameter
	 *  - [ APCOND_BLOCKED ]:
	 *      true if the user is sitewide blocked
	 *  - [ APCOND_ISBOT ]:
	 *      true if the user is a bot
	 *  - similar constructs can be defined by extensions
	 *
	 * The sets of conditions are evaluated recursively, so you can use nested sets of conditions
	 * linked by operands.
	 *
	 * Note that if $wgEmailAuthentication is disabled, APCOND_EMAILCONFIRMED will be true for any
	 * user who has provided an e-mail address.
	 */
	public const Autopromote = [
		'default' => [
			'autoconfirmed' => [ '&',
				[ APCOND_EDITCOUNT, null ], // NOTE: null means $wgAutoConfirmCount
				[ APCOND_AGE, null ], // NOTE: null means AutoConfirmAge
			],
		],
		'type' => 'map',
	];

	/**
	 * Automatically add a usergroup to any user who matches certain conditions.
	 *
	 * Does not add the user to the group again if it has been removed.
	 * Also, does not remove the group if the user no longer meets the criteria.
	 *
	 * The format is:
	 *
	 * ```
	 * [ event => criteria, ... ]
	 * ```
	 *
	 * The only recognised value for event is 'onEdit' (when the user edits).
	 *
	 * Criteria has the same format as $wgAutopromote
	 *
	 * @see self::Autopromote
	 * @since 1.18
	 */
	public const AutopromoteOnce = [
		'default' => [ 'onEdit' => [], ],
		'type' => 'map',
	];

	/**
	 * Put user rights log entries for autopromotion in recent changes?
	 *
	 * @since 1.18
	 */
	public const AutopromoteOnceLogInRC = [
		'default' => true,
	];

	/**
	 * Defines a denylist of group names. One-shot autopromotions into these groups will not cause a
	 * RecentChanges entry to be inserted even if AutopromoteOnceLogInRC is set, as long as they are the
	 * only new groups the user was autopromoted to.
	 *
	 * @since 1.44
	 */
	public const AutopromoteOnceRCExcludedGroups = [
		'default' => [],
		'type' => 'array',
	];

	/**
	 * $wgAddGroups and $wgRemoveGroups can be used to give finer control over who
	 * can assign which groups at Special:Userrights.
	 *
	 * **Example:**
	 * Bureaucrats can add any group:
	 *
	 * ```
	 * $wgAddGroups['bureaucrat'] = true;
	 * ```
	 *
	 * Bureaucrats can only remove bots and sysops:
	 *
	 * ```
	 * $wgRemoveGroups['bureaucrat'] = [ 'bot', 'sysop' ];
	 * ```
	 *
	 * Sysops can make bots:
	 *
	 * ```
	 * $wgAddGroups['sysop'] = [ 'bot' ];
	 * ```
	 *
	 * Sysops can disable other sysops in an emergency, and disable bots:
	 *
	 * ```
	 * $wgRemoveGroups['sysop'] = [ 'sysop', 'bot' ];
	 * ```
	 */
	public const AddGroups = [
		'default' => [],
		'type' => 'map',
		'mergeStrategy' => 'array_merge_recursive',
	];

	/**
	 * @see self::AddGroups
	 */
	public const RemoveGroups = [
		'default' => [],
		'type' => 'map',
		'mergeStrategy' => 'array_merge_recursive',
	];

	/**
	 * A list of available rights, in addition to the ones defined by the core.
	 * Rights in this list are denied unless explicitly granted, typically
	 * using GroupPermissions.
	 *
	 * For extensions only.
	 *
	 * @see self::GroupPermissions
	 * @see self::ImplicitRights
	 */
	public const AvailableRights = [
		'default' => [],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	/**
	 * A list of implicit rights, in addition to the ones defined by the core.
	 * Rights in this list are granted implicitly to all users, but rate limits
	 * may apply to them.
	 *
	 * Extensions that define rate limits should add the corresponding right to
	 * either ImplicitRights or AvailableRights, depending on whether the right
	 * should be granted to everyone.
	 *
	 * @since 1.41
	 * @see self::RateLimits
	 * @see self::AvailableRights
	 */
	public const ImplicitRights = [
		'default' => [],
		'type' => 'list',
		'items' => [ 'type' => 'string', ]
	];

	/**
	 * Optional to restrict deletion of pages with higher revision counts
	 * to users with the 'bigdelete' permission. (Default given to sysops.)
	 */
	public const DeleteRevisionsLimit = [
		'default' => 0,
	];

	/**
	 * Page deletions with > this number of revisions will use the job queue.
	 *
	 * Revisions will be archived in batches of (at most) this size, one batch per job.
	 */
	public const DeleteRevisionsBatchSize = [
		'default' => 1000,
	];

	/**
	 * The maximum number of edits a user can have and
	 * can still be hidden by users with the hideuser permission.
	 *
	 * This is limited for performance reason.
	 * Set to false to disable the limit.
	 *
	 * @since 1.23
	 */
	public const HideUserContribLimit = [
		'default' => 1000,
	];

	/**
	 * Number of accounts each IP address may create per specified period(s).
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgAccountCreationThrottle = [
	 *  // no more than 100 per month
	 *  [
	 *   'count' => 100,
	 *   'seconds' => 30*86400,
	 *  ],
	 *  // no more than 10 per day
	 *  [
	 *   'count' => 10,
	 *   'seconds' => 86400,
	 *  ],
	 * ];
	 * ```
	 *
	 * @note For backwards compatibility reasons, this may also be given as a single
	 *       integer, representing the number of account creations per day.
	 * @see self::TempAccountCreationThrottle for the temporary accounts version of
	 *       this throttle
	 * @warning Requires $wgMainCacheType to be enabled
	 */
	public const AccountCreationThrottle = [
		'default' => [ [
			'count' => 0,
			'seconds' => 86400,
		] ],
		'type' => 'int|list',
	];

	/**
	 * Number of temporary accounts each IP address may create per specified period(s).
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgTempAccountCreationThrottle = [
	 *  // no more than 100 per month
	 *  [
	 *   'count' => 100,
	 *   'seconds' => 30*86400,
	 *  ],
	 *  // no more than 6 per day
	 *  [
	 *   'count' => 6,
	 *   'seconds' => 86400,
	 *  ],
	 * ];
	 * ```
	 *
	 * @see self::AccountCreationThrottle for the regular account version of this throttle.
	 * @warning Requires $wgMainCacheType to be enabled
	 *
	 * @since 1.42
	 */
	public const TempAccountCreationThrottle = [
		'default' => [ [
			'count' => 6,
			'seconds' => 86400,
		] ],
		'type' => 'list',
	];

	/**
	 * Number of temporary accounts usernames each IP address may acquire per specified period(s).
	 *
	 * This should be set to a higher value than TempAccountCreationThrottle.
	 *
	 * On editing, we first attempt to acquire a temp username before proceeding with saving an edit
	 * and potentially creating a temp account if the edit save is successful.
	 *
	 * Some edits may fail (due to core or extensions denying an edit); this throttle ensures that
	 * there are limits to the number of temporary account names that can be acquired and stored in
	 * the database.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgTempAccountNameAcquisitionThrottle = [
	 *  // no more than 100 per month
	 *  [
	 *   'count' => 100,
	 *   'seconds' => 30*86400,
	 *  ],
	 *  // no more than 60 per day
	 *  [
	 *   'count' => 60,
	 *   'seconds' => 86400,
	 *  ],
	 * ];
	 * ```
	 *
	 * @see self::TempAccountCreationThrottle Make sure that TempAccountNameAcquisitionThrottle is greater than or
	 *   equal to TempAccountCreationThrottle
	 * @warning Requires $wgMainCacheType to be enabled
	 *
	 * @since 1.42
	 */
	public const TempAccountNameAcquisitionThrottle = [
		'default' => [ [
			'count' => 60,
			'seconds' => 86400,
		] ],
		'type' => 'list',
	];

	/**
	 * Edits matching these regular expressions in body text
	 * will be recognised as spam and rejected automatically.
	 *
	 * There's no administrator override on-wiki, so be careful what you set. :)
	 * May be an array of regexes or a single string for backwards compatibility.
	 *
	 * @see https://en.wikipedia.org/wiki/Regular_expression
	 * @note Each regex needs a beginning/end delimiter, eg: # or /
	 */
	public const SpamRegex = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Same as SpamRegex except for edit summaries
	 */
	public const SummarySpamRegex = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Whether to use DNS blacklists in $wgDnsBlacklistUrls to check for open
	 * proxies
	 *
	 * @since 1.16
	 */
	public const EnableDnsBlacklist = [
		'default' => false,
	];

	/**
	 * List of DNS blacklists to use, if $wgEnableDnsBlacklist is true.
	 *
	 * This is an array of either a URL or an array with the URL and a key (should
	 * the blacklist require a key).
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgDnsBlacklistUrls = [
	 *   // String containing URL
	 *   'http.dnsbl.sorbs.net.',
	 *   // Array with URL and key, for services that require a key
	 *   [ 'dnsbl.httpbl.net.', 'mykey' ],
	 *   // Array with just the URL. While this works, it is recommended that you
	 *   // just use a string as shown above
	 *   [ 'opm.tornevall.org.' ]
	 * ];
	 * ```
	 *
	 * @note You should end the domain name with a . to avoid searching your
	 * eventual domain search suffixes.
	 * @since 1.16
	 */
	public const DnsBlacklistUrls = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * List of banned IP addresses.
	 *
	 * This can have the following formats:
	 * - An array of addresses
	 * - A string, in which case this is the path to a file
	 *   containing the list of IP addresses, one per line
	 */
	public const ProxyList = [
		'default' => [],
		'type' => 'string|list',
	];

	/**
	 * Proxy whitelist, list of addresses that are assumed to be non-proxy despite
	 * what the other methods might say.
	 */
	public const ProxyWhitelist = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * IP ranges that should be considered soft-blocked (anon-only, account
	 * creation allowed). The intent is to use this to prevent anonymous edits from
	 * shared resources such as Wikimedia Labs.
	 *
	 * @since 1.29
	 */
	public const SoftBlockRanges = [
		'default' => [],
		'type' => 'list',
		'items' => [ 'type' => 'string', ],
	];

	/**
	 * Whether to look at the X-Forwarded-For header's list of (potentially spoofed)
	 * IPs and apply IP blocks to them. This allows for IP blocks to work with correctly-configured
	 * (transparent) proxies without needing to block the proxies themselves.
	 */
	public const ApplyIpBlocksToXff = [
		'default' => false,
	];

	/**
	 * Simple rate limiter options to brake edit floods.
	 *
	 * Maximum number actions allowed in the given number of seconds; after that
	 * the violating client receives HTTP 500 error pages until the period
	 * elapses.
	 *
	 * **Example:**
	 * Limits per configured per action and then type of users.
	 *
	 * ```
	 * $wgRateLimits = [
	 *     'edit' => [
	 *         'anon' => [ x, y ], // any and all anonymous edits (aggregate)
	 *         'user' => [ x, y ], // each logged-in user
	 *         'user-global' => [ x, y ], // per username, across all sites (assumes names are
	 * global)
	 *         'newbie' => [ x, y ], // each new autoconfirmed accounts; overrides 'user'
	 *         'ip' => [ x, y ], // each anon and recent account, across all sites
	 *         'subnet' => [ x, y ], // ... within a /24 subnet in IPv4 or /64 in IPv6
	 *         'ip-all' => [ x, y ], // per ip, across all sites
	 *         'subnet-all' => [ x, y ], // ... within a /24 subnet in IPv4 or /64 in IPv6
	 *         'groupName' => [ x, y ], // by group membership
	 *     ]
	 * ];
	 * ```
	 *
	 * **Normally, the 'noratelimit' right allows a user to bypass any rate**
	 * limit checks. This can be disabled on a per-action basis by setting the
	 * special '&can-bypass' key to false in that action's configuration.
	 *
	 * ```
	 * $wgRateLimits = [
	 *     'some-action' => [
	 *         '&can-bypass' => false,
	 *         'user' => [ x, y ],
	 * ];
	 * ```
	 *
	 * @see self::ImplicitRights
	 * @warning Requires that $wgMainCacheType is set to something persistent
	 */
	public const RateLimits = [
		'default' => [
			// Page edits
			'edit' => [
				'ip' => [ 8, 60 ],
				'newbie' => [ 8, 60 ],
				'user' => [ 90, 60 ],
			],
			// Page moves
			'move' => [
				'newbie' => [ 2, 120 ],
				'user' => [ 8, 60 ],
			],
			// File uploads
			'upload' => [
				'ip' => [ 8, 60 ],
				'newbie' => [ 8, 60 ],
			],
			// Page rollbacks
			'rollback' => [
				'user' => [ 10, 60 ],
				'newbie' => [ 5, 120 ]
			],
			// Triggering password resets emails
			'mailpassword' => [
				'ip' => [ 5, 3600 ],
			],
			// Emailing other users using MediaWiki
			'sendemail' => [
				'ip' => [ 5, 86400 ],
				'newbie' => [ 5, 86400 ],
				'user' => [ 20, 86400 ],
			],
			'changeemail' => [
				'ip-all' => [ 10, 3600 ],
				'user' => [ 4, 86400 ]
			],
			// since 1.33 - rate limit email confirmations
			'confirmemail' => [
				'ip-all' => [ 10, 3600 ],
				'user' => [ 4, 86400 ]
			],
			// Purging pages
			'purge' => [
				'ip' => [ 30, 60 ],
				'user' => [ 30, 60 ],
			],
			// Purges of link tables
			'linkpurge' => [
				'ip' => [ 30, 60 ],
				'user' => [ 30, 60 ],
			],
			// Files rendered via thumb.php or thumb_handler.php
			'renderfile' => [
				'ip' => [ 700, 30 ],
				'user' => [ 700, 30 ],
			],
			// Same as above but for non-standard thumbnails
			'renderfile-nonstandard' => [
				'ip' => [ 70, 30 ],
				'user' => [ 70, 30 ],
			],
			// Stashing edits into cache before save
			'stashedit' => [
				'ip' => [ 30, 60 ],
				'newbie' => [ 30, 60 ],
			],
			// Stash base HTML for VE edits
			'stashbasehtml' => [
				'ip' => [ 5, 60 ],
				'newbie' => [ 5, 60 ],
			],
			// Adding or removing change tags
			'changetags' => [
				'ip' => [ 8, 60 ],
				'newbie' => [ 8, 60 ],
			],
			// Changing the content model of a page
			'editcontentmodel' => [
				'newbie' => [ 2, 120 ],
				'user' => [ 8, 60 ],
			],
		],
		'type' => 'map',
		'mergeStrategy' => 'array_plus_2d',
	];

	/**
	 * Array of IPs / CIDR ranges which should be excluded from rate limits.
	 *
	 * This may be useful for allowing NAT gateways for conferences, etc.
	 */
	public const RateLimitsExcludedIPs = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Log IP addresses in the recentchanges table; can be accessed only by
	 * extensions (e.g. CheckUser) or a DB admin
	 * Used for retroactive autoblocks
	 */
	public const PutIPinRC = [
		'default' => true,
	];

	/**
	 * Integer defining default number of entries to show on
	 * special pages which are query-pages such as Special:Whatlinkshere.
	 */
	public const QueryPageDefaultLimit = [
		'default' => 50,
	];

	/**
	 * Limit password attempts to X attempts per Y seconds per IP per account.
	 *
	 * Value is an array of arrays. Each sub-array must have a key for count
	 * (ie count of how many attempts before throttle) and a key for seconds.
	 * If the key 'allIPs' (case sensitive) is present, then the limit is
	 * just per account instead of per IP per account.
	 *
	 * @since 1.27 allIps support and multiple limits added in 1.27. Prior
	 * to 1.27 this only supported having a single throttle.
	 * @warning Requires $wgMainCacheType to be enabled
	 */
	public const PasswordAttemptThrottle = [
		'default' => [
			// Short term limit
			[ 'count' => 5, 'seconds' => 300 ],
			// Long term limit. We need to balance the risk
			// of somebody using this as a DoS attack to lock someone
			// out of their account, and someone doing a brute force attack.
			[ 'count' => 150, 'seconds' => 60 * 60 * 48 ],
		],
		'type' => 'list',
	];

	/**
	 * Users authorize consumers (like Apps) to act on their behalf but only with
	 * a subset of the user's normal account rights (signed off on by the user).
	 * The possible rights to grant to a consumer are bundled into groups called
	 * "grants". Each grant defines some rights it lets consumers inherit from the
	 * account they may act on behalf of. Note that a user granting a right does
	 * nothing if that user does not actually have that right to begin with.
	 *
	 * @since 1.27
	 */
	public const GrantPermissions = [
		'default' => [
			'basic' => [
				'autocreateaccount' => true,
				'autoconfirmed' => true,
				'autopatrol' => true,
				'editsemiprotected' => true,
				'ipblock-exempt' => true,
				'nominornewtalk' => true,
				'patrolmarks' => true,
				'read' => true,
				'unwatchedpages' => true,
			],
			'highvolume' => [
				'bot' => true,
				'apihighlimits' => true,
				'noratelimit' => true,
				'markbotedits' => true,
			],
			'import' => [
				'import' => true,
				'importupload' => true,
			],
			'editpage' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'pagelang' => true,
			],
			'editprotected' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'editprotected' => true,
			],
			'editmycssjs' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'editmyusercss' => true,
				'editmyuserjson' => true,
				'editmyuserjs' => true,
			],
			'editmyoptions' => [
				'editmyoptions' => true,
				'editmyuserjson' => true,
			],
			'editinterface' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'editinterface' => true,
				'edituserjson' => true,
				'editsitejson' => true,
			],
			'editsiteconfig' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'editinterface' => true,
				'edituserjson' => true,
				'editsitejson' => true,
				'editusercss' => true,
				'edituserjs' => true,
				'editsitecss' => true,
				'editsitejs' => true,
			],
			'createeditmovepage' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'createpage' => true,
				'createtalk' => true,
				'delete-redirect' => true,
				'move' => true,
				'move-rootuserpages' => true,
				'move-subpages' => true,
				'move-categorypages' => true,
				'suppressredirect' => true,
			],
			'uploadfile' => [
				'upload' => true,
				'reupload-own' => true,
			],
			'uploadeditmovefile' => [
				'upload' => true,
				'reupload-own' => true,
				'reupload' => true,
				'reupload-shared' => true,
				'upload_by_url' => true,
				'movefile' => true,
				'suppressredirect' => true,
			],
			'patrol' => [
				'patrol' => true,
			],
			'rollback' => [
				'rollback' => true,
			],
			'blockusers' => [
				'block' => true,
				'blockemail' => true,
			],
			'viewdeleted' => [
				'browsearchive' => true,
				'deletedhistory' => true,
				'deletedtext' => true,
			],
			'viewrestrictedlogs' => [
				'suppressionlog' => true,
			],
			'delete' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'browsearchive' => true,
				'deletedhistory' => true,
				'deletedtext' => true,
				'delete' => true,
				'bigdelete' => true,
				'deletelogentry' => true,
				'deleterevision' => true,
				'undelete' => true,
			],
			'oversight' => [
				'suppressrevision' => true,
				'viewsuppressed' => true,
			],
			'protect' => [
				'edit' => true,
				'minoredit' => true,
				'applychangetags' => true,
				'changetags' => true,
				'editcontentmodel' => true,
				'editprotected' => true,
				'protect' => true,
			],
			'viewmywatchlist' => [
				'viewmywatchlist' => true,
			],
			'editmywatchlist' => [
				'editmywatchlist' => true,
			],
			'sendemail' => [
				'sendemail' => true,
			],
			'createaccount' => [
				'createaccount' => true,
			],
			'privateinfo' => [
				'viewmyprivateinfo' => true,
			],
			'mergehistory' => [
				'mergehistory' => true,
			],
		],
		'type' => 'map',
		'mergeStrategy' => 'array_plus_2d',
		'additionalProperties' => [
			'type' => 'map',
			'additionalProperties' => [ 'type' => 'boolean', ],
		],
	];

	/**
	 * Grant groups are used on some user interfaces to display conceptually
	 * similar grants together.
	 *
	 * This configuration value should usually be set by extensions, not
	 * site administrators.
	 *
	 * @see self::GrantPermissions
	 * @since 1.27
	 */
	public const GrantPermissionGroups = [
		'default' =>
			[
				// Hidden grants are implicitly present
				'basic'               => 'hidden',

				'editpage'            => 'page-interaction',
				'createeditmovepage'  => 'page-interaction',
				'editprotected'       => 'page-interaction',
				'patrol'              => 'page-interaction',

				'uploadfile'          => 'file-interaction',
				'uploadeditmovefile'  => 'file-interaction',

				'sendemail'           => 'email',

				'viewmywatchlist'     => 'watchlist-interaction',
				'editviewmywatchlist' => 'watchlist-interaction',

				'editmycssjs'         => 'customization',
				'editmyoptions'       => 'customization',

				'editinterface'       => 'administration',
				'editsiteconfig'      => 'administration',
				'rollback'            => 'administration',
				'blockusers'          => 'administration',
				'delete'              => 'administration',
				'viewdeleted'         => 'administration',
				'viewrestrictedlogs'  => 'administration',
				'protect'             => 'administration',
				'oversight'           => 'administration',
				'createaccount'       => 'administration',
				'mergehistory'        => 'administration',
				'import'              => 'administration',

				'highvolume'          => 'high-volume',

				'privateinfo'         => 'private-information',
			],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'string', ],
	];

	/**
	 * Group grants by risk level. Keys are grant names (i.e. keys from GrantPermissions),
	 * values are GrantsInfo::RISK_* constants.
	 *
	 * Note that this classification is only informative; merely applying 'security' or 'internal'
	 * to a grant won't prevent it from being available. It's used to give guidance to users
	 * in various interfaces about the riskiness of the various grants.
	 *
	 * @since 1.42
	 */
	public const GrantRiskGroups = [
		'default' => [
			'basic'               => GrantsInfo::RISK_LOW,
			'editpage'            => GrantsInfo::RISK_LOW,
			'createeditmovepage'  => GrantsInfo::RISK_LOW,
			'editprotected'       => GrantsInfo::RISK_VANDALISM,
			'patrol'              => GrantsInfo::RISK_LOW,
			'uploadfile'          => GrantsInfo::RISK_LOW,
			'uploadeditmovefile'  => GrantsInfo::RISK_LOW,
			'sendemail'           => GrantsInfo::RISK_SECURITY,
			'viewmywatchlist'     => GrantsInfo::RISK_LOW,
			'editviewmywatchlist' => GrantsInfo::RISK_LOW,
			'editmycssjs'         => GrantsInfo::RISK_SECURITY,
			'editmyoptions'       => GrantsInfo::RISK_SECURITY,
			'editinterface'       => GrantsInfo::RISK_VANDALISM,
			'editsiteconfig'      => GrantsInfo::RISK_SECURITY,
			'rollback'            => GrantsInfo::RISK_LOW,
			'blockusers'          => GrantsInfo::RISK_VANDALISM,
			'delete'              => GrantsInfo::RISK_VANDALISM,
			'viewdeleted'         => GrantsInfo::RISK_VANDALISM,
			'viewrestrictedlogs'  => GrantsInfo::RISK_SECURITY,
			'protect'             => GrantsInfo::RISK_VANDALISM,
			'oversight'           => GrantsInfo::RISK_SECURITY,
			'createaccount'       => GrantsInfo::RISK_LOW,
			'mergehistory'        => GrantsInfo::RISK_VANDALISM,
			'import'              => GrantsInfo::RISK_SECURITY,
			'highvolume'          => GrantsInfo::RISK_LOW,
			'privateinfo'         => GrantsInfo::RISK_LOW,
		],
		'type' => 'map',
	];

	/**
	 * @since 1.27
	 */
	public const EnableBotPasswords = [
		'default' => true,
		'type' => 'boolean',
	];

	/**
	 * Cluster for the bot_passwords table
	 *
	 * @since 1.27
	 * @deprecated since 1.42 Use $wgVirtualDomainsMapping instead.
	 */
	public const BotPasswordsCluster = [
		'default' => false,
		'type' => 'string|false',
	];

	/**
	 * Database name for the bot_passwords table
	 *
	 * To use a database with a table prefix, set this variable to
	 * "{$database}-{$prefix}".
	 *
	 * @since 1.27
	 * @deprecated since 1.42 Use $wgVirtualDomainsMapping instead.
	 */
	public const BotPasswordsDatabase = [
		'default' => false,
		'type' => 'string|false',
	];

	// endregion -- end of user rights settings

	/***************************************************************************/
	// region   Security
	/** @name   Security */

	/**
	 * This should always be customised in LocalSettings.php
	 */
	public const SecretKey = [
		'default' => false,
	];

	/**
	 * Allow user Javascript page?
	 * This enables a lot of neat customizations, but may
	 * increase security risk to users and server load.
	 */
	public const AllowUserJs = [
		'default' => false,
	];

	/**
	 * Allow user Cascading Style Sheets (CSS)?
	 * This enables a lot of neat customizations, but may
	 * increase security risk to users and server load.
	 */
	public const AllowUserCss = [
		'default' => false,
	];

	/**
	 * Allow style-related user-preferences?
	 *
	 * This controls whether the `editfont` and `underline` preferences
	 * are available to users.
	 */
	public const AllowUserCssPrefs = [
		'default' => true,
	];

	/**
	 * Use the site's Javascript page?
	 */
	public const UseSiteJs = [
		'default' => true,
	];

	/**
	 * Use the site's Cascading Style Sheets (CSS)?
	 */
	public const UseSiteCss = [
		'default' => true,
	];

	/**
	 * Break out of framesets. This can be used to prevent clickjacking attacks,
	 * or to prevent external sites from framing your site with ads.
	 */
	public const BreakFrames = [
		'default' => false,
	];

	/**
	 * The X-Frame-Options header to send on pages sensitive to clickjacking
	 * attacks, such as edit pages. This prevents those pages from being displayed
	 * in a frame or iframe. The options are:
	 *
	 *   - 'DENY': Do not allow framing. This is recommended for most wikis.
	 *
	 *   - 'SAMEORIGIN': Allow framing by pages on the same domain. This can be used
	 *         to allow framing within a trusted domain. This is insecure if there
	 *         is a page on the same domain which allows framing of arbitrary URLs.
	 *
	 *   - false: Allow all framing. This opens up the wiki to XSS attacks and thus
	 *         full compromise of local user accounts. Private wikis behind a
	 *         corporate firewall are especially vulnerable. This is not
	 *         recommended.
	 *
	 * For extra safety, set $wgBreakFrames = true, to prevent framing on all pages,
	 * not just edit pages.
	 */
	public const EditPageFrameOptions = [
		'default' => 'DENY',
	];

	/**
	 * Disallow framing of API pages directly, by setting the X-Frame-Options
	 * header. Since the API returns CSRF tokens, allowing the results to be
	 * framed can compromise your user's account security.
	 *
	 * Options are:
	 *   - 'DENY': Do not allow framing. This is recommended for most wikis.
	 *   - 'SAMEORIGIN': Allow framing by pages on the same domain.
	 *   - false: Allow all framing.
	 * Note: $wgBreakFrames will override this for human formatted API output.
	 */
	public const ApiFrameOptions = [
		'default' => 'DENY',
	];

	/**
	 * Controls Content-Security-Policy header
	 *
	 * @warning May cause slowness on Windows due to slow random number generator.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.32
	 * @see https://www.w3.org/TR/CSP2/
	 */
	public const CSPHeader = [
		'default' => false,
		'type' => 'false|object',
	];

	/**
	 * Controls Content-Security-Policy-Report-Only header
	 *
	 * @since 1.32
	 */
	public const CSPReportOnlyHeader = [
		'default' => false,
		'type' => 'false|object',
	];

	/**
	 * List of urls which appear often to be triggering CSP reports
	 * but do not appear to be caused by actual content, but by client
	 * software inserting scripts (i.e. Ad-Ware).
	 *
	 * List based on results from Wikimedia logs.
	 *
	 * @since 1.28
	 */
	public const CSPFalsePositiveUrls = [
		'default' => [
			'https://3hub.co' => true,
			'https://morepro.info' => true,
			'https://p.ato.mx' => true,
			'https://s.ato.mx' => true,
			'https://adserver.adtech.de' => true,
			'https://ums.adtechus.com' => true,
			'https://cas.criteo.com' => true,
			'https://cat.nl.eu.criteo.com' => true,
			'https://atpixel.alephd.com' => true,
			'https://rtb.metrigo.com' => true,
			'https://d5p.de17a.com' => true,
			'https://ad.lkqd.net/vpaid/vpaid.js' => true,
			'https://ad.lkqd.net/vpaid/vpaid.js?fusion=1.0' => true,
			'https://t.lkqd.net/t' => true,
			'chrome-extension' => true,
		],
		'type' => 'map',
	];

	/**
	 * Allow anonymous cross origin requests to the REST API.
	 *
	 * This should be disabled for intranet sites (sites behind a firewall).
	 *
	 * @since 1.36
	 */
	public const AllowCrossOrigin = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Allows authenticated cross-origin requests to the REST API with session cookies.
	 *
	 * With this option enabled, any origin specified in $wgCrossSiteAJAXdomains may send session
	 * cookies for authorization in the REST API.
	 *
	 * There is a performance impact by enabling this option. Therefore, it should be left disabled
	 * for most wikis and clients should instead use OAuth to make cross-origin authenticated
	 * requests.
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Credentials
	 * @since 1.36
	 */
	public const RestAllowCrossOriginCookieAuth = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Secret for session storage.
	 *
	 * This should be set in LocalSettings.php, otherwise $wgSecretKey will
	 * be used.
	 *
	 * @since 1.27
	 */
	public const SessionSecret = [
		'default' => false,
	];

	// endregion -- end of security

	/***************************************************************************/
	// region   Cookie settings
	/** @name   Cookie settings */

	/**
	 * Default cookie lifetime, in seconds. Setting to 0 makes all cookies session-only.
	 */
	public const CookieExpiration = [
		'default' => 30 * 86400,
	];

	/**
	 * Default login cookie lifetime, in seconds. Setting
	 * $wgExtendLoginCookieExpiration to null will use $wgCookieExpiration to
	 * calculate the cookie lifetime. As with $wgCookieExpiration, 0 will make
	 * login cookies session-only.
	 */
	public const ExtendedLoginCookieExpiration = [
		'default' => 180 * 86400,
	];

	/**
	 * Set to set an explicit domain on the login cookies eg, "justthis.domain.org"
	 * or ".any.subdomain.net"
	 */
	public const CookieDomain = [
		'default' => '',
	];

	/**
	 * Set this variable if you want to restrict cookies to a certain path within
	 * the domain specified by $wgCookieDomain.
	 */
	public const CookiePath = [
		'default' => '/',
	];

	/**
	 * Whether the "secure" flag should be set on the cookie. This can be:
	 *   - true:      Set secure flag
	 *   - false:     Don't set secure flag
	 *   - "detect":  Set the secure flag if $wgServer is set to an HTTPS URL,
	 *                or if $wgForceHTTPS is true.
	 *
	 * If $wgForceHTTPS is true, session cookies will be secure regardless of this
	 * setting. However, other cookies will still be affected.
	 */
	public const CookieSecure = [
		'default' => 'detect',
		'dynamicDefault' => [ 'use' => [ 'ForceHTTPS' ] ]
	];

	public static function getDefaultCookieSecure( bool $forceHTTPS ): bool {
		return $forceHTTPS || ( WebRequest::detectProtocol() === 'https' );
	}

	/**
	 * Cookies generated by MediaWiki have names starting with this prefix. Set it
	 * to a string to use a custom prefix. Setting it to false causes the database
	 * name to be used as a prefix.
	 */
	public const CookiePrefix = [
		'default' => false,
		'dynamicDefault' => [
			'use' => [ 'SharedDB', 'SharedPrefix', 'SharedTables', 'DBname', 'DBprefix' ]
		],
	];

	public static function getDefaultCookiePrefix(
		?string $sharedDB, ?string $sharedPrefix, array $sharedTables, string $dbName, string $dbPrefix
	): string {
		if ( $sharedDB && in_array( 'user', $sharedTables ) ) {
			return $sharedDB . ( $sharedPrefix ? "_$sharedPrefix" : '' );
		}
		return $dbName . ( $dbPrefix ? "_$dbPrefix" : '' );
	}

	/**
	 * Set authentication cookies to HttpOnly to prevent access by JavaScript,
	 * in browsers that support this feature. This can mitigates some classes of
	 * XSS attack.
	 */
	public const CookieHttpOnly = [
		'default' => true,
	];

	/**
	 * The SameSite cookie attribute used for login cookies. This can be "Lax",
	 * "Strict", "None" or empty/null to omit the attribute.
	 *
	 * This only applies to login cookies, since the correct value for other
	 * cookies depends on what kind of cookie it is.
	 *
	 * @since 1.35
	 */
	public const CookieSameSite = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * A list of cookies that vary the cache (for use by extensions)
	 */
	public const CacheVaryCookies = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Override to customise the session name
	 */
	public const SessionName = [
		'default' => false,
	];

	/**
	 * Whether to set a cookie when a user is autoblocked. Doing so means that a blocked user, even
	 * after logging out and moving to a new IP address, will still be blocked. This cookie will
	 * contain an authentication code if $wgSecretKey is set, or otherwise will just be the block
	 * ID (in which case there is a possibility of an attacker discovering the names of revdeleted
	 * users, so it is best to use this in conjunction with $wgSecretKey being set).
	 */
	public const CookieSetOnAutoblock = [
		'default' => true,
	];

	/**
	 * Whether to set a cookie when a logged-out user is blocked. Doing so means that a blocked
	 * user, even after moving to a new IP address, will still be blocked. This cookie will contain
	 * an authentication code if $wgSecretKey is set, or otherwise will just be the block ID (in
	 * which case there is a possibility of an attacker discovering the names of revdeleted users,
	 * so it is best to use this in conjunction with $wgSecretKey being set).
	 */
	public const CookieSetOnIpBlock = [
		'default' => true,
	];

	// endregion -- end of cookie settings

	/***************************************************************************/
	// region   Profiling, testing and debugging
	/** @name   Profiling, testing and debugging */
	// See $wgProfiler for how to enable profiling.

	/**
	 * Enable verbose debug logging for all channels and log levels.
	 *
	 * See https://www.mediawiki.org/wiki/How_to_debug
	 *
	 * For static requests, this enables all channels and warning-level and
	 * above only. Use $wgDebugRawPage to make those verbose as well.
	 *
	 * The debug log file should be not be web-accessible if it is used in
	 * a production environment, as may contain private data.
	 */
	public const DebugLogFile = [
		'default' => '',
	];

	/**
	 * Prefix for debug log lines
	 */
	public const DebugLogPrefix = [
		'default' => '',
	];

	/**
	 * If true, instead of redirecting, show a page with a link to the redirect
	 * destination. This allows for the inspection of PHP error messages, and easy
	 * resubmission of form data. For developer use only.
	 */
	public const DebugRedirects = [
		'default' => false,
	];

	/**
	 * If true, debug logging is also enabled for load.php and action=raw requests.
	 *
	 * By default, $wgDebugLogFile enables all channels and warning-level and
	 * above for static requests.
	 *
	 * This ensures that the debug log is likely a chronological record of
	 * of specific web request you are debugging, instead of overlapping with
	 * messages from static requests, which would make it unclear which message
	 * originated from what request.
	 *
	 * Also, during development this can make browsing and JavaScript testing
	 * considerably slower (T85805).
	 */
	public const DebugRawPage = [
		'default' => false,
	];

	/**
	 * Send debug data to an HTML comment in the output.
	 *
	 * This may occasionally be useful when supporting a non-technical end-user.
	 * It's more secure than exposing the debug log file to the web, since the
	 * output only contains private data for the current user. But it's not ideal
	 * for development use since data is lost on fatal errors and redirects.
	 */
	public const DebugComments = [
		'default' => false,
	];

	/**
	 * Write SQL queries to the debug log.
	 *
	 * This setting is only used $wgLBFactoryConf['class'] is set to
	 * '\Wikimedia\Rdbms\LBFactorySimple'; otherwise the DBO_DEBUG flag must be set in
	 * the 'flags' option of the database connection to achieve the same functionality.
	 */
	public const DebugDumpSql = [
		'default' => false,
	];

	/**
	 * Performance expectations for DB usage
	 *
	 * @since 1.26
	 */
	public const TrxProfilerLimits = [
		'default' => [
			// HTTP GET/HEAD requests.
			// Primary queries should not happen on GET requests
			'GET' => [
				'masterConns' => 0,
				'writes' => 0,
				'readQueryTime' => 5,
				'readQueryRows' => 10000
			],
			// HTTP POST requests.
			// Primary reads and writes will happen for a subset of these.
			'POST' => [
				'readQueryTime' => 5,
				'writeQueryTime' => 1,
				'readQueryRows' => 100_000,
				'maxAffected' => 1000
			],
			'POST-nonwrite' => [
				'writes' => 0,
				'readQueryTime' => 5,
				'readQueryRows' => 10000
			],
			// Deferred updates that run after HTTP response is sent for GET requests
			'PostSend-GET' => [
				'readQueryTime' => 5,
				'writeQueryTime' => 1,
				'readQueryRows' => 10000,
				'maxAffected' => 1000,
				// Log primary queries under the post-send entry point as they are discouraged
				'masterConns' => 0,
				'writes' => 0,
			],
			// Deferred updates that run after HTTP response is sent for POST requests
			'PostSend-POST' => [
				'readQueryTime' => 5,
				'writeQueryTime' => 1,
				'readQueryRows' => 100_000,
				'maxAffected' => 1000
			],
			// Background job runner
			'JobRunner' => [
				'readQueryTime' => 30,
				'writeQueryTime' => 5,
				'readQueryRows' => 100_000,
				'maxAffected' => 500 // ballpark of $wgUpdateRowsPerQuery
			],
			// Command-line scripts
			'Maintenance' => [
				'writeQueryTime' => 5,
				'maxAffected' => 1000
			]
		],
		'type' => 'map',
	];

	/**
	 * Map of string log group names to log destinations.
	 *
	 * If set, wfDebugLog() output for that group will go to that file instead
	 * of the regular $wgDebugLogFile. Useful for enabling selective logging
	 * in production.
	 *
	 * Log destinations may be one of the following:
	 * - false to completely remove from the output, including from $wgDebugLogFile.
	 * - string values specifying a filename or URI.
	 * - associative array with keys:
	 *   - 'destination' desired filename or URI.
	 *   - 'sample' an integer value, specifying a sampling factor (optional)
	 *   - 'level' A \Psr\Log\LogLevel constant, indicating the minimum level
	 *             to log (optional, since 1.25)
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgDebugLogGroups['redis'] = '/var/log/mediawiki/redis.log';
	 * ```
	 *
	 * **Advanced example:**
	 *
	 * ```
	 * $wgDebugLogGroups['memcached'] = [
	 *     'destination' => '/var/log/mediawiki/memcached.log',
	 *     'sample' => 1000,  // log 1 message out of every 1,000.
	 *     'level' => \Psr\Log\LogLevel::WARNING
	 * ];
	 * ```
	 */
	public const DebugLogGroups = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Default service provider for creating Psr\Log\LoggerInterface instances.
	 *
	 * The value should be an array suitable for use with
	 * ObjectFactory::getObjectFromSpec(). The created object is expected to
	 * implement the MediaWiki\Logger\Spi interface. See ObjectFactory for additional
	 * details.
	 *
	 * Alternately the MediaWiki\Logger\LoggerFactory::registerProvider method can
	 * be called to inject an MediaWiki\Logger\Spi instance into the LoggerFactory
	 * and bypass the use of this configuration variable entirely.
	 *
	 * **To completely disable logging:**
	 *
	 * ```
	 * $wgMWLoggerDefaultSpi = [ 'class' => \MediaWiki\Logger\NullSpi::class ];
	 * ```
	 *
	 * @since 1.25
	 * @see \MwLogger
	 */
	public const MWLoggerDefaultSpi = [
		'default' => [ 'class' => 'MediaWiki\\Logger\\LegacySpi', ],
		'mergeStrategy' => 'replace',
		'type' => 'map',
	];

	/**
	 * Display debug data at the bottom of the main content area.
	 *
	 * Useful for developers and technical users trying to working on a closed wiki.
	 */
	public const ShowDebug = [
		'default' => false,
	];

	/**
	 * Show the contents of $wgHooks in Special:Version
	 */
	public const SpecialVersionShowHooks = [
		'default' => false,
	];

	/**
	 * Show exception message and stack trace when printing details about uncaught exceptions
	 * in web response output.
	 *
	 * This may reveal private information in error messages or function parameters.
	 * If set to false, only the exception type or class name will be exposed.
	 */
	public const ShowExceptionDetails = [
		'default' => false,
	];

	/**
	 * If true, send the exception backtrace to the error log
	 */
	public const LogExceptionBacktrace = [
		'default' => true,
	];

	/**
	 * If true, the MediaWiki error handler passes errors/warnings to the default error handler
	 * after logging them. The setting is ignored when the track_errors php.ini flag is true.
	 */
	public const PropagateErrors = [
		'default' => true,
	];

	/**
	 * Expose backend server host names through the API and various HTML comments
	 */
	public const ShowHostnames = [
		'default' => false,
	];

	/**
	 * Override server hostname detection with a hardcoded value.
	 *
	 * Should be a string, default false.
	 *
	 * @since 1.20
	 */
	public const OverrideHostname = [
		'default' => false,
	];

	/**
	 * If set to true MediaWiki will throw notices for some possible error
	 * conditions and for deprecated functions.
	 */
	public const DevelopmentWarnings = [
		'default' => false,
	];

	/**
	 * Release limitation to wfDeprecated warnings, if set to a release number
	 * development warnings will not be generated for deprecations added in releases
	 * after the limit.
	 */
	public const DeprecationReleaseLimit = [
		'default' => false,
	];

	/**
	 * Profiler configuration.
	 *
	 * To use a profiler, set $wgProfiler in LocalSettings.php.
	 *
	 * Options:
	 *
	 * - 'class' (`string`): The Profiler subclass to use.
	 *   Default: ProfilerStub.
	 * - 'sampling' (`integer`): Only enable the profiler on one in this many requests.
	 *   For requests that are not in the sampling,
	 *   the 'class' option will be replaced with ProfilerStub.
	 *   Default: `1`.
	 * - 'threshold' (`float`): Only process the recorded data if the total elapsed
	 *   time for a request is more than this number of seconds.
	 *   Default: `0.0`.
	 * - 'output' (`string|string[]`):  ProfilerOutput subclass or subclasess to use.
	 *   Default: `[]`.
	 *
	 * The options array is passed in its entirety to the specified
	 * Profiler `class`. Check individual Profiler subclasses for additional
	 * options that may be available.
	 *
	 * Profiler subclasses available in MediaWiki core:
	 *
	 * - ProfilerXhprof: Based on XHProf or Tideways-XHProf.
	 * - ProfilerExcimer: Based on Excimer.
	 * - ProfilerSectionOnly
	 *
	 * Profiler output classes available in MediaWiki:
	 *
	 * - ProfilerOutputText: outputs profiling data in the web page body as
	 *   a comment.  You can make the profiling data in HTML render visibly
	 *   instead by setting the 'visible' configuration flag.
	 *
	 * - ProfilerOutputStats: outputs profiling data in a format as configured
	 *   by $wgStatsFormat. It expects that $wgStatsTarget is set.
	 *
	 * - ProfilerOutputDump: outputs dump files that are compatible
	 *   with the XHProf gui. It expects that `$wgProfiler['outputDir']`
	 *   is set as well.
	 *
	 * Examples:
	 *
	 * ```
	 * $wgProfiler = [
	 *   'class' => ProfilerXhprof::class,
	 *   'output' => ProfilerOutputText::class,
	 * ];
	 * ```
	 *
	 * ```
	 * $wgProfiler = [
	 *   'class' => ProfilerXhprof::class,
	 *   'output' => [ ProfilerOutputText::class ],
	 *   'sampling' => 50, // one in every 50 requests
	 * ];
	 * ```
	 *
	 * For performance, the profiler is always disabled for CLI scripts as they
	 * could be long running and the data would accumulate. Use the `--profiler`
	 * parameter of maintenance scripts to override this.
	 *
	 * @since 1.17.0
	 */
	public const Profiler = [
		'default' => [],
		'type' => 'map',
		'mergeStrategy' => 'replace',
	];

	/**
	 * Destination of statsd metrics.
	 *
	 * A host or host:port of a statsd server. Port defaults to 8125.
	 *
	 * If not set, statsd metrics will not be collected.
	 *
	 * @see MediaWiki::emitBufferedStats()
	 * @since 1.25
	 */
	public const StatsdServer = [
		'default' => false,
	];

	/**
	 * Prefix for metric names sent to $wgStatsdServer.
	 *
	 * @see \MediaWiki\MediaWikiServices::getInstance()->getStatsdDataFactory
	 * @see \Wikimedia\Stats\BufferingStatsdDataFactory
	 * @since 1.25
	 */
	public const StatsdMetricPrefix = [
		'default' => 'MediaWiki',
	];

	/**
	 * Stats output target URI e.g. udp://127.0.0.1:8125
	 *
	 * If null, metrics will not be sent.
	 * Note: this only affects metrics instantiated by the StatsFactory service
	 *
	 * @since 1.38
	 */
	public const StatsTarget = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Stats output format
	 *
	 * If null, metrics will not be rendered nor sent.
	 * Note: this only affects metrics instantiated by the StatsFactory service
	 *
	 * @see \Wikimedia\Stats\OutputFormats::SUPPORTED_FORMATS
	 * @since 1.41
	 */
	public const StatsFormat = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * Stats service name prefix
	 *
	 * Required.  Must not be zero-length.
	 * Defaults to: 'mediawiki'
	 * Note: this only affects metrics instantiated by the StatsFactory service
	 *
	 * @since 1.41
	 */
	public const StatsPrefix = [
		'default' => 'mediawiki',
		'type' => 'string',
	];

	/**
	 * Configuration for OpenTelemetry instrumentation, or `null` to disable it.
	 * Possible keys:
	 * - `samplingProbability`: probability in % of sampling a trace for which no sampling decision has been
	 * taken yet. Must be between 0 and 100.
	 * - `serviceName`: name of the service being instrumented.
	 * - `endpoint`: URL of the OpenTelemetry collector to send trace data to.
	 * This has to be an endpoint accepting OTLP data over HTTP (not gRPC).
	 *
	 * An example config to send data to a local OpenTelemetry or Jaeger collector instance:
	 * ```
	 * $wgOpenTelemetryConfig = [
	 *  'samplingProbability' => 0.1,
	 *  'serviceName' => 'mediawiki-local',
	 *  'endpoint' => 'http://127.0.0.1:4318/v1/traces',
	 * ];
	 * ```
	 * @since 1.43
	 */
	public const OpenTelemetryConfig = [
		'default' => null,
		'type' => 'map|null'
	];

	/**
	 * InfoAction retrieves a list of transclusion links (both to and from).
	 *
	 * This number puts a limit on that query in the case of highly transcluded
	 * templates.
	 */
	public const PageInfoTransclusionLimit = [
		'default' => 50,
	];

	/**
	 * Allow running of QUnit tests via [[Special:JavaScriptTest]].
	 */
	public const EnableJavaScriptTest = [
		'default' => false,
	];

	/**
	 * Overwrite the caching key prefix with custom value.
	 *
	 * @since 1.19
	 */
	public const CachePrefix = [
		'default' => false,
	];

	/**
	 * Display the new debugging toolbar. This also enables profiling on database
	 * queries and other useful output.
	 *
	 * Will be ignored if $wgUseFileCache or $wgUseCdn is enabled.
	 *
	 * @since 1.19
	 */
	public const DebugToolbar = [
		'default' => false,
	];

	// endregion -- end of profiling, testing and debugging

	/***************************************************************************/
	// region   Search
	/** @name   Search */

	/**
	 * Set this to true to disable the full text search feature.
	 */
	public const DisableTextSearch = [
		'default' => false,
	];

	/**
	 * Set to true to have nicer highlighted text in search results,
	 * by default off due to execution overhead
	 */
	public const AdvancedSearchHighlighting = [
		'default' => false,
	];

	/**
	 * Regexp to match word boundaries, defaults for non-CJK languages
	 * should be empty for CJK since the words are not separate
	 */
	public const SearchHighlightBoundaries = [
		'default' => '[\\p{Z}\\p{P}\\p{C}]',
	];

	/**
	 * Templates for OpenSearch suggestions, defaults to API action=opensearch
	 *
	 * Sites with heavy load would typically have these point to a custom
	 * PHP wrapper to avoid firing up mediawiki for every keystroke
	 *
	 * Placeholders: {searchTerms}
	 */
	public const OpenSearchTemplates = [
		'default' => [
			'application/x-suggestions+json' => false,
			'application/x-suggestions+xml' => false,
		],
		'type' => 'map',
	];

	/**
	 * This was previously a used to force empty responses from ApiOpenSearch
	 * with the 'suggest' parameter set.
	 *
	 * @deprecated since 1.35 No longer used
	 */
	public const EnableOpenSearchSuggest = [
		'default' => true,
		'obsolete' => 'Since 1.35, no longer used',
		'description' => 'Has been emitting warnings since 1.39 (LTS). ' .
			'Can be removed completely in 1.44, assuming 1.43 is an LTS release.'
	];

	/**
	 * Integer defining default number of entries to show on
	 * OpenSearch call.
	 */
	public const OpenSearchDefaultLimit = [
		'default' => 10,
	];

	/**
	 * Minimum length of extract in <Description>. Actual extracts will last until the end of
	 * sentence.
	 */
	public const OpenSearchDescriptionLength = [
		'default' => 100,
	];

	/**
	 * Expiry time for search suggestion responses
	 */
	public const SearchSuggestCacheExpiry = [
		'default' => 1200,
	];

	/**
	 * If you've disabled search semi-permanently, this also disables updates to the
	 * table. If you ever re-enable, be sure to rebuild the search table.
	 */
	public const DisableSearchUpdate = [
		'default' => false,
	];

	/**
	 * List of namespaces which are searched by default.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgNamespacesToBeSearchedDefault[NS_MAIN] = true;
	 * $wgNamespacesToBeSearchedDefault[NS_PROJECT] = true;
	 * ```
	 */
	public const NamespacesToBeSearchedDefault = [
		'default' => [ NS_MAIN => true, ],
		'type' => 'map',
	];

	/**
	 * Disable the internal MySQL-based search, to allow it to be
	 * implemented by an extension instead.
	 */
	public const DisableInternalSearch = [
		'default' => false,
	];

	/**
	 * Set this to a URL to forward search requests to some external location.
	 *
	 * If the URL includes '$1', this will be replaced with the URL-encoded
	 * search term. Before using this, $wgDisableTextSearch must be set to true.
	 *
	 * **Example:**
	 * To forward to Google you'd have something like:
	 *
	 * ```
	 * $wgSearchForwardUrl =
	 * 'https://www.google.com/search?q=$1' .
	 * '&domains=https://example.com' .
	 * '&sitesearch=https://example.com' .
	 * '&ie=utf-8&oe=utf-8';
	 * ```
	 */
	public const SearchForwardUrl = [
		'default' => null,
	];

	/**
	 * Array of namespaces to generate a Google sitemap for when the
	 * maintenance/generateSitemap.php script is run, or false if one is to be
	 * generated for all namespaces.
	 */
	public const SitemapNamespaces = [
		'default' => false,
		'type' => 'false|list',
	];

	/**
	 * Custom namespace priorities for sitemaps. Setting this will allow you to
	 * set custom priorities to namespaces when sitemaps are generated using the
	 * maintenance/generateSitemap.php script.
	 *
	 * This should be a map of namespace IDs to priority
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgSitemapNamespacesPriorities = [
	 *     NS_USER => '0.9',
	 *     NS_HELP => '0.0',
	 * ];
	 * ```
	 */
	public const SitemapNamespacesPriorities = [
		'default' => false,
		'type' => 'false|map',
	];

	/**
	 * If true, searches for IP addresses will be redirected to that IP's
	 * contributions page. E.g. searching for "1.2.3.4" will redirect to
	 * [[Special:Contributions/1.2.3.4]]
	 */
	public const EnableSearchContributorsByIP = [
		'default' => true,
	];

	/**
	 * Options for Special:Search completion widget form created by SearchFormWidget class.
	 *
	 * Settings that can be used:
	 * - showDescriptions: true/false - whether to show opensearch description results
	 * - performSearchOnClick:  true/false - whether to perform search on click
	 * See also TitleWidget.js UI widget.
	 *
	 * @since 1.34
	 */
	public const SpecialSearchFormOptions = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Set true to allow logged-in users to set a preference whether or not matches in
	 * search results should force redirection to that page. If false, the preference is
	 * not exposed and cannot be altered from site default. To change your site's default
	 * preference, set via $wgDefaultUserOptions['search-match-redirect'].
	 *
	 * @since 1.35
	 */
	public const SearchMatchRedirectPreference = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Controls whether zero-result search queries with suggestions should display results for
	 * these suggestions.
	 *
	 * @since 1.26
	 */
	public const SearchRunSuggestedQuery = [
		'default' => true,
		'type' => 'boolean',
	];

	// endregion -- end of search settings

	/***************************************************************************/
	// region   Edit user interface
	/** @name   Edit user interface */

	/**
	 * Path to the GNU diff3 utility. If the file doesn't exist, edit conflicts will
	 * fall back to the old behavior (no merging).
	 */
	public const Diff3 = [
		'default' => '/usr/bin/diff3',
	];

	/**
	 * Path to the GNU diff utility.
	 */
	public const Diff = [
		'default' => '/usr/bin/diff',
	];

	/**
	 * Which namespaces have special treatment where they should be preview-on-open
	 * Internally only Category: pages apply, but using this extensions (e.g. Semantic MediaWiki)
	 * can specify namespaces of pages they have special treatment for
	 */
	public const PreviewOnOpenNamespaces = [
		'default' => [
			NS_CATEGORY => true
		],
		'type' => 'map',
	];

	/**
	 * Enable the UniversalEditButton for browsers that support it
	 * (currently only Firefox with an extension)
	 * See http://universaleditbutton.org for more background information
	 */
	public const UniversalEditButton = [
		'default' => true,
	];

	/**
	 * If user doesn't specify any edit summary when making a an edit, MediaWiki
	 * will try to automatically create one. This feature can be disabled by set-
	 * ting this variable false.
	 */
	public const UseAutomaticEditSummaries = [
		'default' => true,
	];

	// endregion -- end edit UI

	/***************************************************************************/
	// region   Maintenance
	/** @name   Maintenance */
	// See also $wgSiteNotice

	/**
	 * For colorized maintenance script output, is your terminal background dark ?
	 */
	public const CommandLineDarkBg = [
		'default' => false,
	];

	/**
	 * Set this to a string to put the wiki into read-only mode. The text will be
	 * used as an explanation to users.
	 *
	 * This prevents most write operations via the web interface. Cache updates may
	 * still be possible. To prevent database writes completely, use the read_only
	 * option in MySQL.
	 */
	public const ReadOnly = [
		'default' => null,
	];

	/**
	 * Set this to true to put the wiki watchlists into read-only mode.
	 *
	 * @since 1.31
	 */
	public const ReadOnlyWatchedItemStore = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * If this lock file exists (size > 0), the wiki will be forced into read-only mode.
	 *
	 * Its contents will be shown to users as part of the read-only warning
	 * message.
	 *
	 * Will default to "{$wgUploadDirectory}/lock_yBgMBwiR" in Setup.php
	 */
	public const ReadOnlyFile = [
		'default' => false,
		'dynamicDefault' => [ 'use' => [ 'UploadDirectory' ] ]
	];

	/**
	 * @param mixed $uploadDirectory Value of UploadDirectory
	 * @return string
	 */
	public static function getDefaultReadOnlyFile( $uploadDirectory ): string {
		return "$uploadDirectory/lock_yBgMBwiR";
	}

	/**
	 * When you run the web-based upgrade utility, it will tell you what to set
	 * this to in order to authorize the upgrade process. It will subsequently be
	 * used as a password, to authorize further upgrades.
	 *
	 * For security, do not set this to a guessable string. Use the value supplied
	 * by the install/upgrade process. To cause the upgrader to generate a new key,
	 * delete the old key from LocalSettings.php.
	 */
	public const UpgradeKey = [
		'default' => false,
	];

	/**
	 * Fully specified path to git binary
	 */
	public const GitBin = [
		'default' => '/usr/bin/git',
	];

	/**
	 * Map GIT repository URLs to viewer URLs to provide links in Special:Version
	 *
	 * Key is a pattern passed to preg_match() and preg_replace(),
	 * without the delimiters (which are #) and must match the whole URL.
	 * The value is the replacement for the key (it can contain $1, etc.)
	 * %h will be replaced by the short SHA-1 (7 first chars) and %H by the
	 * full SHA-1 of the HEAD revision.
	 * %r will be replaced with a URL-encoded version of $1.
	 * %R will be replaced with $1 and no URL-encoding
	 *
	 * @since 1.20
	 */
	public const GitRepositoryViewers = [
		'default' => [
			'https://(?:[a-z0-9_]+@)?gerrit.wikimedia.org/r/(?:p/)?(.*)' => 'https://gerrit.wikimedia.org/g/%R/+/%H',
			'ssh://(?:[a-z0-9_]+@)?gerrit.wikimedia.org:29418/(.*)' => 'https://gerrit.wikimedia.org/g/%R/+/%H',
		],
		'type' => 'map',
	];

	/**
	 * Initial content to create when installing a wiki. An array of page info
	 * arrays. Each must contain at least one of:
	 *   - title: The title to create (string)
	 *   - titlemsg: The name of a message to read the title from
	 *
	 * And one of:
	 *   - text: The text to write to the page (string)
	 *   - textmsg: The name of a message to read the page contents from
	 *
	 * The text may contain
	 *   - {{InstallerOption:<name>}}: This will be replaced with the named option value
	 *   - {{InstallerConfig:<name>}}: This will be replaced with the named config value
	 *
	 * @see \InstallPreConfigured
	 * @since 1.44
	 */
	public const InstallerInitialPages = [
		'default' => [
			[
				'titlemsg' => 'mainpage',
				'text' => "{{subst:int:mainpagetext}}\n\n{{subst:int:mainpagedocfooter}}",
			]
		],
		'type' => 'list'
	];

	// endregion -- End of maintenance

	/***************************************************************************/
	// region   Recent changes, new pages, watchlist and history
	/** @name   Recent changes, new pages, watchlist and history */

	/**
	 * Recentchanges items are periodically purged; entries older than this many
	 * seconds will go.
	 *
	 * Default: 90 days = about three months
	 */
	public const RCMaxAge = [
		'default' => 90 * 24 * 3600,
	];

	/**
	 * Page watchers inactive for more than this many seconds are considered inactive.
	 *
	 * Used mainly by action=info. Default: 180 days = about six months.
	 *
	 * @since 1.26
	 */
	public const WatchersMaxAge = [
		'default' => 180 * 24 * 3600,
	];

	/**
	 * If active watchers (per above) are this number or less, do not disclose it.
	 *
	 * Left to 1, prevents unprivileged users from knowing for sure that there are 0.
	 * Set to -1 if you want to always complement watchers count with this info.
	 *
	 * @since 1.26
	 */
	public const UnwatchedPageSecret = [
		'default' => 1,
	];

	/**
	 * Filter $wgRCLinkDays by $wgRCMaxAge to avoid showing links for numbers
	 * higher than what will be stored. Note that this is disabled by default
	 * because we sometimes do have RC data which is beyond the limit for some
	 * reason, and some users may use the high numbers to display that data which
	 * is still there.
	 */
	public const RCFilterByAge = [
		'default' => false,
	];

	/**
	 * List of Limits options to list in the Special:Recentchanges and
	 * Special:Recentchangeslinked pages.
	 */
	public const RCLinkLimits = [
		'default' => [ 50, 100, 250, 500 ],
		'type' => 'list',
	];

	/**
	 * List of Days options to list in the Special:Recentchanges and
	 * Special:Recentchangeslinked pages.
	 *
	 * @see \MediaWiki\SpecialPage\ChangesListSpecialPage::getLinkDays
	 */
	public const RCLinkDays = [
		'default' => [ 1, 3, 7, 14, 30 ],
		'type' => 'list',
	];

	/**
	 * Configuration for feeds to which notifications about recent changes will be sent.
	 *
	 * The following feed classes are available by default:
	 * - 'UDPRCFeedEngine' - sends recent changes over UDP to the specified server.
	 * - 'RedisPubSubFeedEngine' - send recent changes to Redis.
	 *
	 * Only 'class' or 'uri' is required. If 'uri' is set instead of 'class', then
	 * RecentChange::getEngine() is used to determine the class. All options are
	 * passed to the constructor.
	 *
	 * Common options:
	 * - 'class' -- The class to use for this feed (must implement RCFeed).
	 * - 'omit_bots' -- Exclude bot edits from the feed. (default: false)
	 * - 'omit_anon' -- Exclude anonymous edits from the feed. (default: false)
	 * - 'omit_user' -- Exclude edits by registered users from the feed. (default: false)
	 * - 'omit_minor' -- Exclude minor edits from the feed. (default: false)
	 * - 'omit_patrolled' -- Exclude patrolled edits from the feed. (default: false)
	 *
	 * FormattedRCFeed-specific options:
	 * - 'uri' -- [required] The address to which the messages are sent.
	 *   The uri scheme of this string will be looked up in $wgRCEngines
	 *   to determine which FormattedRCFeed class to use.
	 * - 'formatter' -- [required] The class (implementing RCFeedFormatter) which will
	 *   produce the text to send. This can also be an object of the class.
	 *   Formatters available by default: JSONRCFeedFormatter, XMLRCFeedFormatter,
	 *   IRCColourfulRCFeedFormatter.
	 *
	 * IRCColourfulRCFeedFormatter-specific options:
	 * - 'add_interwiki_prefix' -- whether the titles should be prefixed with
	 *   the first entry in the $wgLocalInterwikis array
	 *
	 * JSONRCFeedFormatter-specific options:
	 * - 'channel' -- if set, the 'channel' parameter is also set in JSON values.
	 *
	 * **Examples:**
	 *
	 * ```
	 * $wgRCFeeds['example'] = [
	 *     'uri' => 'udp://localhost:1336',
	 *     'formatter' => 'JSONRCFeedFormatter',
	 *     'add_interwiki_prefix' => false,
	 *     'omit_bots' => true,
	 * ];
	 * ```
	 *
	 * ```
	 * $wgRCFeeds['example'] = [
	 *     'uri' => 'udp://localhost:1338',
	 *     'formatter' => 'IRCColourfulRCFeedFormatter',
	 *     'add_interwiki_prefix' => false,
	 *     'omit_bots' => true,
	 * ];
	 * ```
	 *
	 * ```
	 * $wgRCFeeds['example'] = [
	 *     'class' => ExampleRCFeed::class,
	 * ];
	 * ```
	 *
	 * @since 1.22
	 */
	public const RCFeeds = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Used by RecentChange::getEngine to find the correct engine for a given URI scheme.
	 *
	 * Keys are scheme names, values are names of FormattedRCFeed sub classes.
	 *
	 * @since 1.22
	 */
	public const RCEngines = [
		'default' => [
			'redis' => RedisPubSubFeedEngine::class,
			'udp' => UDPRCFeedEngine::class,
		],
		'type' => 'map',
	];

	/**
	 * Treat category membership changes as a RecentChange.
	 *
	 * Changes are mentioned in RC for page actions as follows:
	 * - creation: pages created with categories are mentioned
	 * - edit: category additions/removals to existing pages are mentioned
	 * - move: nothing is mentioned (unless templates used depend on the title)
	 * - deletion: nothing is mentioned
	 * - undeletion: nothing is mentioned
	 *
	 * @since 1.27
	 */
	public const RCWatchCategoryMembership = [
		'default' => false,
	];

	/**
	 * Use RC Patrolling to check for vandalism (from recent changes and watchlists)
	 * New pages and new files are included.
	 *
	 * @note If you disable all patrolling features, you probably also want to
	 * remove 'patrol' from $wgFilterLogTypes so a show/hide link isn't shown on
	 * Special:Log.
	 */
	public const UseRCPatrol = [
		'default' => true,
	];

	/**
	 * Polling rate, in seconds, used by the 'live update' and 'view newest' features
	 * of the RCFilters app on SpecialRecentChanges and Special:Watchlist.
	 *
	 * 0 to disable completely.
	 */
	public const StructuredChangeFiltersLiveUpdatePollingRate = [
		'default' => 3,
	];

	/**
	 * Use new page patrolling to check new pages on Special:Newpages
	 *
	 * @note If you disable all patrolling features, you probably also want to
	 * remove 'patrol' from $wgFilterLogTypes so a show/hide link isn't shown on
	 * Special:Log.
	 */
	public const UseNPPatrol = [
		'default' => true,
	];

	/**
	 * Use file patrolling to check new files on Special:Newfiles
	 *
	 * @note If you disable all patrolling features, you probably also want to
	 * remove 'patrol' from $wgFilterLogTypes so a show/hide link isn't shown on
	 * Special:Log.
	 * @since 1.27
	 */
	public const UseFilePatrol = [
		'default' => true,
	];

	/**
	 * Provide syndication feeds (RSS, Atom) for, e.g., Recentchanges, Newpages
	 */
	public const Feed = [
		'default' => true,
	];

	/**
	 * Set maximum number of results to return in syndication feeds (RSS, Atom) for
	 * eg Recentchanges, Newpages.
	 */
	public const FeedLimit = [
		'default' => 50,
	];

	/**
	 * _Minimum_ timeout for cached Recentchanges feed, in seconds.
	 *
	 * A cached version will continue to be served out even if changes
	 * are made, until this many seconds runs out since the last render.
	 *
	 * If set to 0, feed caching is disabled. Use this for debugging only;
	 * feed generation can be pretty slow with diffs.
	 */
	public const FeedCacheTimeout = [
		'default' => 60,
	];

	/**
	 * When generating Recentchanges RSS/Atom feed, diffs will not be generated for
	 * pages larger than this size.
	 */
	public const FeedDiffCutoff = [
		'default' => 32768,
	];

	/**
	 * Override the site's default RSS/ATOM feed for recentchanges that appears on
	 * every page. Some sites might have a different feed they'd like to promote
	 * instead of the RC feed (maybe like a "Recent New Articles" or "Breaking news" one).
	 *
	 * Should be a format as key (either 'rss' or 'atom') and an URL to the feed
	 * as value.
	 *
	 * **Example:**
	 * Configure the 'atom' feed to https://example.com/somefeed.xml
	 *
	 * ```
	 * $wgSiteFeed['atom'] = "https://example.com/somefeed.xml";
	 * ```
	 */
	public const OverrideSiteFeed = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Available feeds objects.
	 *
	 * Should probably only be defined when a page is syndicated ie when
	 * $wgOut->isSyndicated() is true.
	 */
	public const FeedClasses = [
		'default' => [
			'rss' => \MediaWiki\Feed\RSSFeed::class,
			'atom' => \MediaWiki\Feed\AtomFeed::class,
		],
		'type' => 'map',
	];

	/**
	 * Which feed types should we provide by default?  This can include 'rss',
	 * 'atom', neither, or both.
	 */
	public const AdvertisedFeedTypes = [
		'default' => [ 'atom', ],
		'type' => 'list',
	];

	/**
	 * Show watching users in recent changes, watchlist and page history views
	 */
	public const RCShowWatchingUsers = [
		'default' => false,
	];

	/**
	 * Show the amount of changed characters in recent changes
	 */
	public const RCShowChangedSize = [
		'default' => true,
	];

	/**
	 * If the difference between the character counts of the text
	 * before and after the edit is below that value, the value will be
	 * highlighted on the RC page.
	 */
	public const RCChangedSizeThreshold = [
		'default' => 500,
	];

	/**
	 * Show "Updated (since my last visit)" marker in RC view, watchlist and history
	 * view for watched pages with new changes
	 */
	public const ShowUpdatedMarker = [
		'default' => true,
	];

	/**
	 * Disable links to talk pages of anonymous users (IPs) in listings on special
	 * pages like page history, Special:Recentchanges, etc.
	 */
	public const DisableAnonTalk = [
		'default' => false,
	];

	/**
	 * Allow filtering by change tag in recentchanges, history, etc
	 * Has no effect if no tags are defined.
	 */
	public const UseTagFilter = [
		'default' => true,
	];

	/**
	 * List of core tags to enable.
	 *
	 * @since 1.31
	 * @since 1.36 Added 'mw-manual-revert' and 'mw-reverted'
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_CONTENT_MODEL_CHANGE
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_NEW_REDIRECT
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_REMOVED_REDIRECT
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_CHANGED_REDIRECT_TARGET
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_BLANK
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_REPLACE
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_RECREATE
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_ROLLBACK
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_UNDO
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_MANUAL_REVERT
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_REVERTED
	 * @see \MediaWiki\ChangeTags\ChangeTags::TAG_SERVER_SIDE_UPLOAD
	 */
	public const SoftwareTags = [
		'default' => [
			'mw-contentmodelchange' => true,
			'mw-new-redirect' => true,
			'mw-removed-redirect' => true,
			'mw-changed-redirect-target' => true,
			'mw-blank' => true,
			'mw-replace' => true,
			'mw-recreated' => true,
			'mw-rollback' => true,
			'mw-undo' => true,
			'mw-manual-revert' => true,
			'mw-reverted' => true,
			'mw-server-side-upload' => true,
		],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'boolean', ],
	];

	/**
	 * If set to an integer, pages that are watched by this many users or more
	 * will not require the unwatchedpages permission to view the number of
	 * watchers.
	 *
	 * @since 1.21
	 */
	public const UnwatchedPageThreshold = [
		'default' => false,
	];

	/**
	 * Flags (letter symbols) shown in recent changes and watchlist to indicate
	 * certain types of edits.
	 *
	 * To register a new one:
	 *
	 * ```
	 * $wgRecentChangesFlags['flag'] => [
	 *   // message for the letter displayed next to rows on changes lists
	 *   'letter' => 'letter-msg',
	 *   // message for the tooltip of the letter
	 *   'title' => 'tooltip-msg',
	 *   // optional (defaults to 'tooltip-msg'), message to use in the legend box
	 *   'legend' => 'legend-msg',
	 *   // optional (defaults to 'flag'), CSS class to put on changes lists rows
	 *   'class' => 'css-class',
	 *   // optional (defaults to 'any'), how top-level flag is determined.  'any'
	 *   // will set the top-level flag if any line contains the flag, 'all' will
	 *   // only be set if all lines contain the flag.
	 *   'grouping' => 'any',
	 * ];
	 * ```
	 *
	 * @since 1.22
	 */
	public const RecentChangesFlags = [
		'default' => [
			'newpage' => [
				'letter' => 'newpageletter',
				'title' => 'recentchanges-label-newpage',
				'legend' => 'recentchanges-legend-newpage',
				'grouping' => 'any',
			],
			'minor' => [
				'letter' => 'minoreditletter',
				'title' => 'recentchanges-label-minor',
				'legend' => 'recentchanges-legend-minor',
				'class' => 'minoredit',
				'grouping' => 'all',
			],
			'bot' => [
				'letter' => 'boteditletter',
				'title' => 'recentchanges-label-bot',
				'legend' => 'recentchanges-legend-bot',
				'class' => 'botedit',
				'grouping' => 'all',
			],
			'unpatrolled' => [
				'letter' => 'unpatrolledletter',
				'title' => 'recentchanges-label-unpatrolled',
				'legend' => 'recentchanges-legend-unpatrolled',
				'grouping' => 'any',
			],
		],
		'type' => 'map',
	];

	/**
	 * Whether to enable the watchlist expiry feature.
	 *
	 * @since 1.35
	 */
	public const WatchlistExpiry = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Chance of expired watchlist items being purged on any page edit.
	 *
	 * Only has effect if $wgWatchlistExpiry is true.
	 *
	 * If this is zero, expired watchlist items will not be removed
	 * and the purgeExpiredWatchlistItems.php maintenance script should be run periodically.
	 *
	 * @since 1.35
	 */
	public const WatchlistPurgeRate = [
		'default' => 0.1,
		'type' => 'float',
	];

	/**
	 * Relative maximum duration for watchlist expiries, as accepted by strtotime().
	 *
	 * This relates to finite watchlist expiries only. Pages can be watched indefinitely
	 * regardless of what this is set to.
	 *
	 * This is used to ensure the watchlist_expiry table doesn't grow to be too big.
	 *
	 * Only has effect if $wgWatchlistExpiry is true.
	 *
	 * Set to null to allow expiries of any duration.
	 *
	 * @since 1.35
	 */
	public const WatchlistExpiryMaxDuration = [
		'default' => '1 year',
		'type' => '?string',
	];

	// endregion -- end RC/watchlist

	/***************************************************************************/
	// region   Copyright and credits settings
	/** @name   Copyright and credits settings */

	/**
	 * Override for copyright metadata.
	 *
	 * This is the name of the page containing information about the wiki's copyright status,
	 * which will be added as a link in the footer if it is specified. It overrides
	 * $wgRightsUrl if both are specified.
	 */
	public const RightsPage = [
		'default' => null,
	];

	/**
	 * Set this to specify an external URL containing details about the content license used on your
	 * wiki.
	 *
	 * If $wgRightsPage is set then this setting is ignored.
	 */
	public const RightsUrl = [
		'default' => null,
	];

	/**
	 * If either $wgRightsUrl or $wgRightsPage is specified then this variable gives the text for
	 * the link. Otherwise, it will be treated as raw HTML.
	 *
	 * If using $wgRightsUrl then this value must be specified. If using $wgRightsPage then the
	 * name
	 * of the page will also be used as the link text if this variable is not set.
	 */
	public const RightsText = [
		'default' => null,
	];

	/**
	 * Override for copyright metadata.
	 */
	public const RightsIcon = [
		'default' => null,
	];

	/**
	 * Set this to true if you want detailed copyright information forms on Upload.
	 */
	public const UseCopyrightUpload = [
		'default' => false,
	];

	/**
	 * Set this to the number of authors that you want to be credited below an
	 * article text. Set it to zero to hide the attribution block, and a negative
	 * number (like -1) to show all authors. Note that this will require 2-3 extra
	 * database hits, which can have a not insignificant impact on performance for
	 * large wikis.
	 */
	public const MaxCredits = [
		'default' => 0,
	];

	/**
	 * If there are more than $wgMaxCredits authors, show $wgMaxCredits of them.
	 *
	 * Otherwise, link to a separate credits page.
	 */
	public const ShowCreditsIfMax = [
		'default' => true,
	];

	// endregion -- end of copyright and credits settings

	/***************************************************************************/
	// region   Import / Export
	/** @name   Import / Export */

	/**
	 * List of interwiki prefixes for wikis we'll accept as sources for
	 * Special:Import and API action=import. Since complete page history can be
	 * imported, these should be 'trusted'.
	 *
	 * This can either be a regular array, or an associative map specifying
	 * subprojects on the interwiki map of the target wiki, or a mix of the two,
	 * e.g.
	 *
	 * ```
	 * $wgImportSources = [
	 *     'wikipedia' => [ 'cs', 'en', 'fr', 'zh' ],
	 *     'wikispecies',
	 *     'wikia' => [ 'animanga', 'brickipedia', 'desserts' ],
	 * ];
	 * ```
	 *
	 * If you have a very complex import sources setup, you can lazy-load it using
	 * the ImportSources hook.
	 *
	 * If a user has the 'import' permission but not the 'importupload' permission,
	 * they will only be able to run imports through this transwiki interface.
	 */
	public const ImportSources = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Optional default target namespace for interwiki imports.
	 *
	 * Can use this to create an incoming "transwiki"-style queue.
	 * Set to numeric key, not the name.
	 *
	 * Users may override this in the Special:Import dialog.
	 */
	public const ImportTargetNamespace = [
		'default' => null,
	];

	/**
	 * If set to false, disables the full-history option on Special:Export.
	 *
	 * This is currently poorly optimized for long edit histories, so is
	 * disabled on Wikimedia's sites.
	 */
	public const ExportAllowHistory = [
		'default' => true,
	];

	/**
	 * If set nonzero, Special:Export requests for history of pages with
	 * more revisions than this will be rejected. On some big sites things
	 * could get bogged down by very very long pages.
	 */
	public const ExportMaxHistory = [
		'default' => 0,
	];

	/**
	 * Return distinct author list (when not returning full history)
	 */
	public const ExportAllowListContributors = [
		'default' => false,
	];

	/**
	 * If non-zero, Special:Export accepts a "pagelink-depth" parameter
	 * up to this specified level, which will cause it to include all
	 * pages linked to from the pages you specify. Since this number
	 * can become *really really large* and could easily break your wiki,
	 * it's disabled by default for now.
	 *
	 * @warning There's a HARD CODED limit of 5 levels of recursion to prevent a
	 * crazy-big export from being done by someone setting the depth number too
	 * high. In other words, last resort safety net.
	 */
	public const ExportMaxLinkDepth = [
		'default' => 0,
	];

	/**
	 * Whether to allow the "export all pages in namespace" option
	 */
	public const ExportFromNamespaces = [
		'default' => false,
	];

	/**
	 * Whether to allow exporting the entire wiki into a single file
	 */
	public const ExportAllowAll = [
		'default' => false,
	];

	/**
	 * Maximum number of pages returned by the GetPagesFromCategory and
	 * GetPagesFromNamespace functions.
	 *
	 * @since 1.27
	 */
	public const ExportPagelistLimit = [
		'default' => 5000,
	];

	/**
	 * The schema to use by default when generating XML dumps. This allows sites to control
	 * explicitly when to make breaking changes to their export and dump format.
	 */
	public const XmlDumpSchemaVersion = [
		'default' => XML_DUMP_SCHEMA_VERSION_11,
	];

	// endregion -- end of import/export

	/***************************************************************************/
	// region   Wiki Farm
	/** @name   Wiki Farm */

	/**
	 * A directory that contains site-specific configuration files.
	 *
	 * Setting this will enable multi-tenant ("wiki farm") mode, causing
	 * site-specific settings to be loaded based on information from the web request.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.38
	 */
	public const WikiFarmSettingsDirectory = [
		'default' => null
	];

	/**
	 * The file extension to be used when looking up site-specific settings files in
	 * $wgWikiFarmSettingsDirectory, such as 'json' or 'yaml'.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.38
	 */
	public const WikiFarmSettingsExtension = [
		'default' => 'yaml'
	];

	// endregion -- End Wiki Farm

	/***************************************************************************/
	// region   Extensions
	/** @name   Extensions */

	/**
	 * A list of callback functions which are called once MediaWiki is fully
	 * initialised
	 */
	public const ExtensionFunctions = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Extension messages files.
	 *
	 * Associative array mapping extension name to the filename where messages can be
	 * found. The file should contain variable assignments. Any of the variables
	 * present in languages/messages/MessagesEn.php may be defined, but $messages
	 * is the most common.
	 *
	 * Variables defined in extensions will override conflicting variables defined
	 * in the core.
	 *
	 * Since MediaWiki 1.23, use of this variable to define messages is discouraged; instead, store
	 * messages in JSON format and use $wgMessagesDirs. For setting other variables than
	 * $messages, $wgExtensionMessagesFiles should still be used. Use a DIFFERENT key because
	 * any entry having a key that also exists in $wgMessagesDirs will be ignored.
	 *
	 * Extensions using the JSON message format can preserve backward compatibility with
	 * earlier versions of MediaWiki by using a compatibility shim, such as one generated
	 * by the generateJsonI18n.php maintenance script, listing it under the SAME key
	 * as for the $wgMessagesDirs entry.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgExtensionMessagesFiles['ConfirmEdit'] = __DIR__.'/ConfirmEdit.i18n.php';
	 * ```
	 */
	public const ExtensionMessagesFiles = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Extension messages directories.
	 *
	 * Associative array mapping extension name to the path of the directory where message files can
	 * be found. The message files are expected to be JSON files named for their language code, e.g.
	 * en.json, de.json, etc. Extensions with messages in multiple places may specify an array of
	 * message directories.
	 *
	 * Message directories in core should be added to LocalisationCache::getMessagesDirs()
	 *
	 * **Simple example:**
	 *
	 * ```
	 * $wgMessagesDirs['Example'] = __DIR__ . '/i18n';
	 * ```
	 *
	 * **Complex example:**
	 *
	 * ```
	 * $wgMessagesDirs['Example'] = [
	 *     __DIR__ . '/lib/ve/i18n',
	 *     __DIR__ . '/lib/ooui/i18n',
	 *     __DIR__ . '/i18n',
	 * ]
	 * ```
	 *
	 * @since 1.23
	 */
	public const MessagesDirs = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Message directories containing JSON files for localisation of special page aliases.
	 *
	 * Associative array mapping extension name to the directory where configurations can be
	 * found. The directory is expected to contain JSON files corresponding to each language code.
	 *
	 * Variables defined in extensions will override conflicting variables defined
	 * in the core. We recommend using this configuration to set variables that require localisation:
	 * special page aliases, and in the future namespace aliases and magic words.
	 *
	 * **Simple example**: extension.json
	 * ```
	 * "TranslationAliasesDirs": {
	 *   "TranslationNotificationsAlias": "i18n/aliases"
	 * }
	 * ```
	 * **Complex example**: extension.json
	 *  ```
	 *  "TranslationAliasesDirs": {
	 *    "TranslationNotificationsAlias": [ "i18n/special-page-aliases", "i18n/magic-words", "i18n/namespaces" ]
	 *  }
	 *  ```
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.42
	 */
	public const TranslationAliasesDirs = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Array of files with list(s) of extension entry points to be used in
	 * maintenance/mergeMessageFileList.php
	 *
	 * @since 1.22
	 */
	public const ExtensionEntryPointListFiles = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Whether to include the NewPP limit report as a HTML comment
	 */
	public const EnableParserLimitReporting = [
		'default' => true,
	];

	/**
	 * List of valid skin names
	 *
	 * The key should be the name in all lower case.
	 *
	 * As of 1.35, the value should be a an array in the form of the ObjectFactory specification.
	 *
	 * For example for 'foobarskin' where the PHP class is 'MediaWiki\Skins\FooBar\FooBarSkin' set:
	 *
	 * **skin.json Example:**
	 *
	 * ```
	 * "ValidSkinNames": {
	 *    "foobarskin": {
	 *        "displayname": "FooBarSkin",
	 *        "class": "MediaWiki\\Skins\\FooBar\\FooBarSkin"
	 *    }
	 * }
	 * ```
	 *
	 * Historically, the value was a properly cased name for the skin (and is still currently
	 * supported). This value will be prefixed with "Skin" to create the class name of the
	 * skin to load. Use Skin::getSkinNames() as an accessor if you wish to have access to the
	 * full list.
	 */
	public const ValidSkinNames = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Special page list. This is an associative array mapping the (canonical) names of
	 * special pages to either a class name or a ObjectFactory spec to be instantiated, or a callback to use for
	 * creating the special page object. In all cases, the result must be an instance of SpecialPage.
	 */
	public const SpecialPages = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Obsolete switch that controlled legacy case-insensitive classloading.
	 *
	 * Case-insensitive classloading was needed for loading data that had
	 * been serialized by PHP 4 with the class names converted to lowercase.
	 * It is no longer necessary since 1.31; the lowercase forms in question
	 * are now listed in autoload.php (T166759).
	 *
	 * @deprecated since 1.35
	 */
	public const AutoloadAttemptLowercase = [
		'default' => false,
		'obsolete' => 'Since 1.40; no longer has any effect.',
		'description' => 'Has been emitting warnings since 1.39 (LTS). ' .
			'Can be removed completely in 1.44, assuming 1.43 is an LTS release.'
	];

	/**
	 * Add information about an installed extension, keyed by its type.
	 *
	 * This is for use from LocalSettings.php and legacy PHP-entrypoint
	 * extensions. In general, extensions should (only) declare this
	 * information in their extension.json file.
	 *
	 * The 'name', 'path' and 'author' keys are required.
	 *
	 * ```
	 * $wgExtensionCredits['other'][] = [
	 *     'path' => __FILE__,
	 *     'name' => 'Example extension',
	 *     'namemsg' => 'exampleextension-name',
	 *     'author' => [
	 *         'Foo Barstein',
	 *     ],
	 *     'version' => '0.0.1',
	 *     'url' => 'https://example.org/example-extension/',
	 *     'descriptionmsg' => 'exampleextension-desc',
	 *     'license-name' => 'GPL-2.0-or-later',
	 * ];
	 * ```
	 *
	 * The extensions are listed on Special:Version. This page also looks for a file
	 * named COPYING or LICENSE (optional .txt extension) and provides a link to
	 * view said file. When the 'license-name' key is specified, this file is
	 * interpreted as wikitext.
	 *
	 * - $type: One of 'specialpage', 'parserhook', 'variable', 'media', 'antispam',
	 *    'skin', 'api', or 'other', or any additional types as specified through the
	 *    ExtensionTypes hook as used in SpecialVersion::getExtensionTypes().
	 *
	 * - name: Name of extension as an inline string instead of localizable message.
	 *    Do not omit this even if 'namemsg' is provided, as it is used to override
	 *    the path Special:Version uses to find extension's license info, and is
	 *    required for backwards-compatibility with MediaWiki 1.23 and older.
	 *
	 * - namemsg (since MW 1.24): A message key for a message containing the
	 *    extension's name, if the name is localizable. (For example, skin names
	 *    usually are.)
	 *
	 * - author: A string or an array of strings. Authors can be linked using
	 *    the regular wikitext link syntax. To have an internationalized version of
	 *    "and others" show, add an element "...". This element can also be linked,
	 *    for instance "[https://example ...]".
	 *
	 * - descriptionmsg: A message key or an array with message key and parameters:
	 *    `'descriptionmsg' => 'exampleextension-desc',`
	 *
	 * - description: Description of extension as an inline string instead of
	 *    localizable message (omit in favour of 'descriptionmsg').
	 *
	 * - license-name: Short name of the license (used as label for the link), such
	 *   as "GPL-2.0-or-later" or "MIT" (https://spdx.org/licenses/ for a list of identifiers).
	 *
	 * @see \MediaWiki\Specials\SpecialVersion::getCredits
	 */
	public const ExtensionCredits = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Global list of hooks.
	 *
	 * The key is one of the events made available by MediaWiki, you can find
	 * a description for most of them in their respective hook interfaces. For
	 * overview of the hook system see docs/Hooks.md. The array is used internally
	 * by HookContainer::run().
	 *
	 * The value can be one of:
	 *
	 * - A function name: `$wgHooks['event_name'][] = $function;`
	 *
	 * - A function with some data: `$wgHooks['event_name'][] = [ $function, $data ];`
	 *
	 *
	 * - A an object method: `$wgHooks['event_name'][] = [ $object, 'method' ];`
	 *
	 * - A closure:
	 * ```
	 * $wgHooks['event_name'][] = function ( $hookParam ) {
	 *     // Handler code goes here.
	 * };
	 * ```
	 *
	 * @warning You should always append to an event array or you will end up
	 * deleting a previous registered hook.
	 * @warning Hook handlers should be registered at file scope. Registering
	 * handlers after file scope can lead to unexpected results due to caching.
	 */
	public const Hooks = [
		'default' => [],
		'type' => 'map',
		'mergeStrategy' => 'array_merge_recursive',
	];

	/**
	 * List of service wiring files to be loaded by the default instance of MediaWikiServices. Each
	 * file listed here is expected to return an associative array mapping service names to
	 * instantiator functions. Extensions may add wiring files to define their own services.
	 * However, this cannot be used to replace existing services - use the MediaWikiServices hook
	 * for that.
	 *
	 * @note the default wiring file will be added automatically by Setup.php
	 * @see \MediaWiki\MediaWikiServices
	 * @see \Wikimedia\Services\ServiceContainer::loadWiringFiles() for details on loading
	 *   service instantiator functions.
	 * @see docs/Injection.md for an overview of dependency
	 *   injection in MediaWiki.
	 */
	public const ServiceWiringFiles = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Maps jobs to their handlers; extensions
	 * can add to this to provide custom jobs.
	 *
	 * Since 1.40, job handlers can be specified as object specs
	 * for use with ObjectFactory, using an array, a plain class name,
	 * or a callback.
	 *
	 * @note The constructor signature of job classes has to follow one of two patterns:
	 * Either it takes a parameter array as the first argument, followed by any services it
	 * needs to have injected: ( array $params, ... ).
	 * Or it takes a PageReference as the first parameter, followed by the parameter array,
	 * followed by any services: ( PageReference $page, array $params, ... ).
	 * In order to signal to the JobFactory that the $page parameter should be omitted from
	 * the constructor arguments, the job class has to be a subclass of GenericParameterJob,
	 * or the object specification for the job has to set the 'needsPage' key to false.
	 * If a callback is used, its signature follows the same rules.
	 */
	public const JobClasses = [
		'default' => [
			'deletePage' => DeletePageJob::class,
			'refreshLinks' => RefreshLinksJob::class,
			'deleteLinks' => DeleteLinksJob::class,
			'htmlCacheUpdate' => HTMLCacheUpdateJob::class,
			'sendMail' => [
				'class' => EmaillingJob::class,
				'services' => [
					0 => 'Emailer'
				]
			],
			'enotifNotify' => RecentChangeNotifyJob::class,
			'fixDoubleRedirect' => [
				'class' => DoubleRedirectJob::class,
				'services' => [
					'RevisionLookup',
					'MagicWordFactory',
					'WikiPageFactory',
				],
				// This job requires a title
				'needsPage' => true,
			],
			'AssembleUploadChunks' => AssembleUploadChunksJob::class,
			'PublishStashedFile' => PublishStashedFileJob::class,
			'ThumbnailRender' => ThumbnailRenderJob::class,
			'UploadFromUrl' => UploadFromUrlJob::class,
			'recentChangesUpdate' => RecentChangesUpdateJob::class,
			'refreshLinksPrioritized' => RefreshLinksJob::class,
			'refreshLinksDynamic' => RefreshLinksJob::class,
			'activityUpdateJob' => ActivityUpdateJob::class,
			'categoryMembershipChange' => CategoryMembershipChangeJob::class,
			'clearUserWatchlist' => ClearUserWatchlistJob::class,
			'watchlistExpiry' => WatchlistExpiryJob::class,
			'cdnPurge' => CdnPurgeJob::class,
			'userGroupExpiry' => UserGroupExpiryJob::class,
			'clearWatchlistNotifications' => ClearWatchlistNotificationsJob::class,
			'userOptionsUpdate' => UserOptionsUpdateJob::class,
			'revertedTagUpdate' => RevertedTagUpdateJob::class,
			'null' => NullJob::class,
			'userEditCountInit' => UserEditCountInitJob::class,
			'parsoidCachePrewarm' => [
				'class' => ParsoidCachePrewarmJob::class,
				'services' => [
					'ParserOutputAccess',
					'PageStore',
					'RevisionLookup',
					'ParsoidSiteConfig',
				],
				// tell the JobFactory not to include the $page parameter in the constructor call
				'needsPage' => false
			],
			'renameUserTable' => [
				'class' => RenameUserTableJob::class,
				'services' => [
					'MainConfig',
					'DBLoadBalancerFactory'
				]
			],
			'renameUserDerived' => [
				'class' => RenameUserDerivedJob::class,
				'services' => [
					'RenameUserFactory',
					'UserFactory'
				]
			],
			// 'renameUser' is a alias for backward compatibility
			// it should be removed in the future releases
			'renameUser' => [
				'class' => RenameUserTableJob::class,
				'services' => [
					'MainConfig',
					'DBLoadBalancerFactory'
				]
			],
		],
		'type' => 'map',
	];

	/**
	 * Jobs that must be explicitly requested, i.e. aren't run by job runners unless
	 * special flags are set. The values here are keys of $wgJobClasses.
	 *
	 * These can be:
	 * - Very long-running jobs.
	 * - Jobs that you would never want to run as part of a page rendering request.
	 * - Jobs that you want to run on specialized machines ( like transcoding, or a particular
	 *   machine on your cluster has 'outside' web access you could restrict uploadFromUrl )
	 * These settings should be global to all wikis.
	 */
	public const JobTypesExcludedFromDefaultQueue = [
		'default' => [ 'AssembleUploadChunks', 'PublishStashedFile', 'UploadFromUrl' ],
		'type' => 'list',
	];

	/**
	 * Map of job types to how many job "work items" should be run per second
	 * on each job runner process. The meaning of "work items" varies per job,
	 * but typically would be something like "pages to update". A single job
	 * may have a variable number of work items, as is the case with batch jobs.
	 *
	 * This is used by runJobs.php and not jobs run via $wgJobRunRate.
	 * These settings should be global to all wikis.
	 */
	public const JobBackoffThrottling = [
		'default' => [],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'float', ],
	];

	/**
	 * Map of job types to configuration arrays.
	 *
	 * This determines which queue class and storage system is used for each job type.
	 * Job types that do not have explicit configuration will use the 'default' config.
	 * These settings should be global to all wikis.
	 */
	public const JobTypeConf = [
		'default' => [
			'default' => [
				'class' => JobQueueDB::class,
				'order' => 'random',
				'claimTTL' => 3600
			],
		],
		'additionalProperties' => [
			'type' => 'object',
			'properties' => [
				'class' => [ 'type' => 'string' ],
				'order' => [ 'type' => 'string' ],
				'claimTTL' => [ 'type' => 'int' ]
			],
		],
		'type' => 'map',
	];

	/**
	 * Whether to include the number of jobs that are queued
	 * for the API's maxlag parameter.
	 *
	 * The total number of jobs will be divided by this to get an
	 * estimated second of maxlag. Typically bots backoff at maxlag=5,
	 * so setting this to the max number of jobs that should be in your
	 * queue divided by 5 should have the effect of stopping bots once
	 * that limit is hit.
	 *
	 * @since 1.29
	 */
	public const JobQueueIncludeInMaxLagFactor = [
		'default' => false,
	];

	/**
	 * Additional functions to be performed with updateSpecialPages.
	 *
	 * Expensive Querypages are already updated.
	 */
	public const SpecialPageCacheUpdates = [
		'default' => [
			'Statistics' => [ SiteStatsUpdate::class, 'cacheUpdate' ]
		],
		'type' => 'map',
	];

	/**
	 * Page property link table invalidation lists. When a page property
	 * changes, this may require other link tables to be updated (eg
	 * adding __HIDDENCAT__ means the hiddencat tracking category will
	 * have been added, so the categorylinks table needs to be rebuilt).
	 *
	 * This array can be added to by extensions.
	 */
	public const PagePropLinkInvalidations = [
		'default' => [ 'hiddencat' => 'categorylinks', ],
		'type' => 'map',
	];

	// endregion -- End extensions

	/***************************************************************************/
	// region   Categories
	/** @name   Categories */

	/**
	 * On category pages, show thumbnail gallery for images belonging to that
	 * category instead of listing them as articles.
	 */
	public const CategoryMagicGallery = [
		'default' => true,
	];

	/**
	 * Paging limit for categories
	 */
	public const CategoryPagingLimit = [
		'default' => 200,
	];

	/**
	 * Specify how category names should be sorted, when listed on a category page.
	 *
	 * A sorting scheme is also known as a collation.
	 *
	 * Available values are:
	 *
	 *   - uppercase: Converts the category name to upper case, and sorts by that.
	 *
	 *   - identity: Does no conversion. Sorts by binary value of the string.
	 *
	 *   - uca-default: Provides access to the Unicode Collation Algorithm with
	 *     the default element table. This is a compromise collation which sorts
	 *     all languages in a mediocre way. However, it is better than "uppercase".
	 *
	 * To use the uca-default collation, you must have PHP's intl extension
	 * installed. See https://www.php.net/manual/en/intl.setup.php . The details of the
	 * resulting collation will depend on the version of ICU installed on the
	 * server.
	 *
	 * After you change this, you must run maintenance/updateCollation.php to fix
	 * the sort keys in the database.
	 *
	 * Extensions can define there own collations by subclassing Collation
	 * and using the Collation::factory hook.
	 */
	public const CategoryCollation = [
		'default' => 'uppercase',
	];

	/**
	 * Additional category collations to store during LinksUpdate. This can be used
	 * to perform online migration of categories from one collation to another. An
	 * array of associative arrays each having the following keys:
	 *
	 * - table: (string) The table name
	 * - collation: (string) The collation to use for cl_sortkey
	 * - fakeCollation: (string) The collation name to insert into cl_collation
	 *
	 * @since 1.38
	 */
	public const TempCategoryCollations = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Whether to sort categories in OutputPage for display.
	 *
	 * The value of CategoryCollation is used for sorting.
	 *
	 * @unstable EXPERIMENTAL This feature is used for Parsoid development,
	 * but its future as a core feature will depend on community uptake.
	 */
	public const SortedCategories = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Array holding default tracking category names.
	 *
	 * Array contains the system messages for each tracking category.
	 * Tracking categories allow pages with certain characteristics to be tracked.
	 * It works by adding any such page to a category automatically.
	 *
	 * A message with the suffix '-desc' should be added as a description message
	 * to have extra information on Special:TrackingCategories.
	 *
	 * @deprecated since 1.25 Extensions should now register tracking categories using
	 * the new extension registration system.
	 * @since 1.23
	 */
	public const TrackingCategories = [
		'default' => [],
		'type' => 'list',
		'deprecated' => 'since 1.25 Extensions should now register tracking categories using ' .
			'the new extension registration system.',
	];

	// endregion -- End categories

	/***************************************************************************/
	// region   Logging
	/** @name   Logging */

	/**
	 * The logging system has two levels: an event type, which describes the
	 * general category and can be viewed as a named subset of all logs; and
	 * an action, which is a specific kind of event that can exist in that
	 * log type.
	 *
	 * Note that code should call LogPage::validTypes() to get a list of valid
	 * log types instead of checking the global variable.
	 */
	public const LogTypes = [
		'default' => [
			'',
			'block',
			'protect',
			'rights',
			'delete',
			'upload',
			'move',
			'import',
			'interwiki',
			'patrol',
			'merge',
			'suppress',
			'tag',
			'managetags',
			'contentmodel',
			'renameuser',
		],
		'type' => 'list',
	];

	/**
	 * This restricts log access to those who have a certain right
	 * Users without this will not see it in the option menu and can not view it
	 * Restricted logs are not added to recent changes
	 * Logs should remain non-transcludable
	 * Format: logtype => permissiontype
	 */
	public const LogRestrictions = [
		'default' => [ 'suppress' => 'suppressionlog', ],
		'type' => 'map',
	];

	/**
	 * Show/hide links on Special:Log will be shown for these log types.
	 *
	 * This is associative array of log type => boolean "hide by default"
	 *
	 * See $wgLogTypes for a list of available log types.
	 *
	 * **Example:**
	 *
	 * `$wgFilterLogTypes = [ 'move' => true, 'import' => false ];`
	 *
	 * Will display show/hide links for the move and import logs. Move logs will be
	 * hidden by default unless the link is clicked. Import logs will be shown by
	 * default, and hidden when the link is clicked.
	 *
	 * A message of the form logeventslist-[type]-log should be added, and will be
	 * used for the link text.
	 */
	public const FilterLogTypes = [
		'default' => [
			'patrol' => true,
			'tag' => true,
			'newusers' => false,
		],
		'type' => 'map',
	];

	/**
	 * Lists the message key string for each log type. The localized messages
	 * will be listed in the user interface.
	 *
	 * Extensions with custom log types may add to this array.
	 *
	 * @since 1.19, if you follow the naming convention log-name-TYPE,
	 * where TYPE is your log type, you don't need to use this array.
	 */
	public const LogNames = [
		'default' => [
			'' => 'all-logs-page',
			'block' => 'blocklogpage',
			'protect' => 'protectlogpage',
			'rights' => 'rightslog',
			'delete' => 'dellogpage',
			'upload' => 'uploadlogpage',
			'move' => 'movelogpage',
			'import' => 'importlogpage',
			'patrol' => 'patrol-log-page',
			'merge' => 'mergelog',
			'suppress' => 'suppressionlog',
		],
		'type' => 'map',
	];

	/**
	 * Lists the message key string for descriptive text to be shown at the
	 * top of each log type.
	 *
	 * Extensions with custom log types may add to this array.
	 *
	 * @since 1.19, if you follow the naming convention log-description-TYPE,
	 * where TYPE is your log type, yoy don't need to use this array.
	 */
	public const LogHeaders = [
		'default' => [
			'' => 'alllogstext',
			'block' => 'blocklogtext',
			'delete' => 'dellogpagetext',
			'import' => 'importlogpagetext',
			'merge' => 'mergelogpagetext',
			'move' => 'movelogpagetext',
			'patrol' => 'patrol-log-header',
			'protect' => 'protectlogtext',
			'rights' => 'rightslogtext',
			'suppress' => 'suppressionlogtext',
			'upload' => 'uploadlogpagetext',
		],
		'type' => 'map',
	];

	/**
	 * Maps log actions to message keys, for formatting log entries of each type
	 * and action when displaying logs to the user.
	 * The array keys are composed as "$type/$action".
	 *
	 * Extensions with custom log types may add to this array.
	 */
	public const LogActions = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * The same as above, but here values are class names or ObjectFactory specifications,
	 * not messages. The specification must resolve to a LogFormatter subclass,
	 * and will receive the LogEntry object as its first constructor argument.
	 * The type can be specified as '*' (e.g. 'block/*') to handle all types.
	 *
	 * @see \MediaWiki\Logging\LogPage::actionText
	 * @see \MediaWiki\Logging\LogFormatter
	 */
	public const LogActionsHandlers = [
		'default' => [
			'block/block' => [
				'class' => BlockLogFormatter::class,
				'services' => [
					'TitleParser',
					'NamespaceInfo',
				]
			],
			'block/reblock' => [
				'class' => BlockLogFormatter::class,
				'services' => [
					'TitleParser',
					'NamespaceInfo',
				]
			],
			'block/unblock' => [
				'class' => BlockLogFormatter::class,
				'services' => [
					'TitleParser',
					'NamespaceInfo',
				]
			],
			'contentmodel/change' => ContentModelLogFormatter::class,
			'contentmodel/new' => ContentModelLogFormatter::class,
			'delete/delete' => DeleteLogFormatter::class,
			'delete/delete_redir' => DeleteLogFormatter::class,
			'delete/delete_redir2' => DeleteLogFormatter::class,
			'delete/event' => DeleteLogFormatter::class,
			'delete/restore' => DeleteLogFormatter::class,
			'delete/revision' => DeleteLogFormatter::class,
			'import/interwiki' => ImportLogFormatter::class,
			'import/upload' => ImportLogFormatter::class,
			'interwiki/iw_add' => InterwikiLogFormatter::class,
			'interwiki/iw_delete' => InterwikiLogFormatter::class,
			'interwiki/iw_edit' => InterwikiLogFormatter::class,
			'managetags/activate' => LogFormatter::class,
			'managetags/create' => LogFormatter::class,
			'managetags/deactivate' => LogFormatter::class,
			'managetags/delete' => LogFormatter::class,
			'merge/merge' => [
				'class' => MergeLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'merge/merge-into' => [
				'class' => MergeLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'move/move' => [
				'class' => MoveLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'move/move_redir' => [
				'class' => MoveLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'patrol/patrol' => PatrolLogFormatter::class,
			'patrol/autopatrol' => PatrolLogFormatter::class,
			'protect/modify' => [
				'class' => ProtectLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'protect/move_prot' => [
				'class' => ProtectLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'protect/protect' => [
				'class' => ProtectLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'protect/unprotect' => [
				'class' => ProtectLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'renameuser/renameuser' => [
				'class' => RenameuserLogFormatter::class,
				'services' => [
					'TitleParser',
				]
			],
			'rights/autopromote' => RightsLogFormatter::class,
			'rights/rights' => RightsLogFormatter::class,
			'suppress/block' => [
				'class' => BlockLogFormatter::class,
				'services' => [
					'TitleParser',
					'NamespaceInfo',
				]
			],
			'suppress/delete' => DeleteLogFormatter::class,
			'suppress/event' => DeleteLogFormatter::class,
			'suppress/reblock' => [
				'class' => BlockLogFormatter::class,
				'services' => [
					'TitleParser',
					'NamespaceInfo',
				]
			],
			'suppress/revision' => DeleteLogFormatter::class,
			'tag/update' => TagLogFormatter::class,
			'upload/overwrite' => UploadLogFormatter::class,
			'upload/revert' => UploadLogFormatter::class,
			'upload/upload' => UploadLogFormatter::class,
		],
		'type' => 'map',
	];

	/**
	 * List of log types that can be filtered by action types
	 *
	 * To each action is associated the list of log_action
	 * subtypes to search for, usually one, but not necessarily so
	 * Extensions may append to this array
	 *
	 * @since 1.27
	 */
	public const ActionFilteredLogs = [
		'default' => [
			'block' => [
				'block' => [ 'block' ],
				'reblock' => [ 'reblock' ],
				'unblock' => [ 'unblock' ],
			],
			'contentmodel' => [
				'change' => [ 'change' ],
				'new' => [ 'new' ],
			],
			'delete' => [
				'delete' => [ 'delete' ],
				'delete_redir' => [ 'delete_redir', 'delete_redir2' ],
				'restore' => [ 'restore' ],
				'event' => [ 'event' ],
				'revision' => [ 'revision' ],
			],
			'import' => [
				'interwiki' => [ 'interwiki' ],
				'upload' => [ 'upload' ],
			],
			'managetags' => [
				'create' => [ 'create' ],
				'delete' => [ 'delete' ],
				'activate' => [ 'activate' ],
				'deactivate' => [ 'deactivate' ],
			],
			'move' => [
				'move' => [ 'move' ],
				'move_redir' => [ 'move_redir' ],
			],
			'newusers' => [
				'create' => [ 'create', 'newusers' ],
				'create2' => [ 'create2' ],
				'autocreate' => [ 'autocreate' ],
				'byemail' => [ 'byemail' ],
			],
			'protect' => [
				'protect' => [ 'protect' ],
				'modify' => [ 'modify' ],
				'unprotect' => [ 'unprotect' ],
				'move_prot' => [ 'move_prot' ],
			],
			'rights' => [
				'rights' => [ 'rights' ],
				'autopromote' => [ 'autopromote' ],
			],
			'suppress' => [
				'event' => [ 'event' ],
				'revision' => [ 'revision' ],
				'delete' => [ 'delete' ],
				'block' => [ 'block' ],
				'reblock' => [ 'reblock' ],
			],
			'upload' => [
				'upload' => [ 'upload' ],
				'overwrite' => [ 'overwrite' ],
				'revert' => [ 'revert' ],
			],
		],
		'type' => 'map',
	];

	/**
	 * Maintain a log of newusers at Special:Log/newusers?
	 */
	public const NewUserLog = [
		'default' => true,
	];

	/**
	 * Maintain a log of page creations at Special:Log/create?
	 *
	 * @since 1.32
	 */
	public const PageCreationLog = [
		'default' => true,
	];

	// endregion -- end logging

	/***************************************************************************/
	// region   Special pages (general and miscellaneous)
	/** @name   Special pages (general and miscellaneous) */

	/**
	 * Allow special page inclusions such as {{Special:Allpages}}
	 */
	public const AllowSpecialInclusion = [
		'default' => true,
	];

	/**
	 * Set this to an array of special page names to prevent
	 * maintenance/updateSpecialPages.php from updating those pages.
	 *
	 * Mapping each special page name to a run mode like 'periodical' if a cronjob is set up.
	 */
	public const DisableQueryPageUpdate = [
		'default' => false,
	];

	/**
	 * On Special:Unusedimages, consider images "used", if they are put
	 * into a category. Default (false) is not to count those as used.
	 */
	public const CountCategorizedImagesAsUsed = [
		'default' => false,
	];

	/**
	 * Maximum number of links to a redirect page listed on
	 * Special:Whatlinkshere/RedirectDestination
	 */
	public const MaxRedirectLinksRetrieved = [
		'default' => 500,
	];

	/**
	 * Shortest CIDR limits that can be checked in any individual range check
	 * at Special:Contributions.
	 *
	 * @since 1.30
	 */
	public const RangeContributionsCIDRLimit = [
		'default' => [
			'IPv4' => 16,
			'IPv6' => 32,
		],
		'type' => 'map',
		'additionalProperties' => [ 'type' => 'integer', ],
	];

	// endregion -- end special pages

	/***************************************************************************/
	// region   Actions
	/** @name   Actions */

	/**
	 * Map of allowed values for the "title=foo&action=<action>" parameter.
	 * to the corresponding handler code.
	 * See ActionFactory for the syntax. Core defaults are in ActionFactory::CORE_ACTIONS,
	 * anything here overrides that.
	 */
	public const Actions = [
		'default' => [],
		'type' => 'map',
	];

	// endregion -- end actions

	/***************************************************************************/
	// region   Robot (search engine crawler) policy
	/** @name   Robot (search engine crawler) policy */
	// See also $wgNoFollowLinks.

	/**
	 * Default robot policy.  The default policy is to encourage indexing and fol-
	 * lowing of links.  It may be overridden on a per-namespace and/or per-page
	 * basis.
	 */
	public const DefaultRobotPolicy = [
		'default' => 'index,follow',
	];

	/**
	 * Robot policies per namespaces. The default policy is given above, the array
	 * is made of namespace constants as defined in includes/Defines.php.  You can-
	 * not specify a different default policy for NS_SPECIAL: it is always noindex,
	 * nofollow.  This is because a number of special pages (e.g., ListPages) have
	 * many permutations of options that display the same data under redundant
	 * URLs, so search engine spiders risk getting lost in a maze of twisty special
	 * pages, all alike, and never reaching your actual content.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgNamespaceRobotPolicies = [ NS_TALK => 'noindex' ];
	 * ```
	 */
	public const NamespaceRobotPolicies = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Robot policies per article. These override the per-namespace robot policies.
	 *
	 * Must be in the form of an array where the key part is a properly canonicalised
	 * text form title and the value is a robot policy.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgArticleRobotPolicies = [
	 *         'Main Page' => 'noindex,follow',
	 *         'User:Bob' => 'index,follow',
	 * ];
	 * ```
	 *
	 * **Example that DOES NOT WORK because the names are not canonical text**
	 * forms:
	 *
	 * ```
	 * $wgArticleRobotPolicies = [
	 *   // Underscore, not space!
	 *   'Main_Page' => 'noindex,follow',
	 *   // "Project", not the actual project name!
	 *   'Project:X' => 'index,follow',
	 *   // Needs to be "Abc", not "abc" (unless $wgCapitalLinks is false for that namespace)!
	 *   'abc' => 'noindex,nofollow'
	 * ];
	 * ```
	 */
	public const ArticleRobotPolicies = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * An array of namespace keys in which the __INDEX__/__NOINDEX__ magic words
	 * will not function, so users can't decide whether pages in that namespace are
	 * indexed by search engines.  If set to null, default to $wgContentNamespaces.
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgExemptFromUserRobotsControl = [ NS_MAIN, NS_TALK, NS_PROJECT ];
	 * ```
	 */
	public const ExemptFromUserRobotsControl = [
		'default' => null,
		'type' => '?list',
	];

	// endregion End robot policy

	/***************************************************************************/
	// region   Action API and REST API
	/** @name   Action API and REST API
	 */

	/**
	 * WARNING: SECURITY THREAT - debug use only
	 *
	 * Disables many security checks in the API for debugging purposes.
	 * This flag should never be used on the production servers, as it introduces
	 * a number of potential security holes. Even when enabled, the validation
	 * will still be performed, but instead of failing, API will return a warning.
	 * Also, there will always be a warning notifying that this flag is set.
	 * At this point, the flag allows GET requests to go through for modules
	 * requiring POST.
	 *
	 * @since 1.21
	 */
	public const DebugAPI = [
		'default' => false,
	];

	/**
	 * API module extensions.
	 *
	 * Associative array mapping module name to modules specs;
	 * Each module spec is an associative array containing at least
	 * the 'class' key for the module's class, and optionally a
	 * 'factory' key for the factory function to use for the module.
	 *
	 * That factory function will be called with two parameters,
	 * the parent module (an instance of ApiBase, usually ApiMain)
	 * and the name the module was registered under. The return
	 * value must be an instance of the class given in the 'class'
	 * field.
	 *
	 * For backward compatibility, the module spec may also be a
	 * simple string containing the module's class name. In that
	 * case, the class' constructor will be called with the parent
	 * module and module name as parameters, as described above.
	 *
	 * Examples for registering API modules:
	 *
	 * ```
	 * $wgAPIModules['foo'] = 'ApiFoo';
	 * $wgAPIModules['bar'] = [
	 *   'class' => ApiBar::class,
	 *   'factory' => function( $main, $name ) { ... }
	 * ];
	 * $wgAPIModules['xyzzy'] = [
	 *   'class' => ApiXyzzy::class,
	 *   'factory' => [ XyzzyFactory::class, 'newApiModule' ]
	 * ];
	 * ```
	 * Extension modules may override the core modules.
	 * See ApiMain::MODULES for a list of the core modules.
	 */
	public const APIModules = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * API format module extensions.
	 *
	 * Associative array mapping format module name to module specs (see $wgAPIModules).
	 * Extension modules may override the core modules.
	 *
	 * See ApiMain::FORMATS for a list of the core format modules.
	 */
	public const APIFormatModules = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * API Query meta module extensions.
	 *
	 * Associative array mapping meta module name to module specs (see $wgAPIModules).
	 * Extension modules may override the core modules.
	 *
	 * See ApiQuery::QUERY_META_MODULES for a list of the core meta modules.
	 */
	public const APIMetaModules = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * API Query prop module extensions.
	 *
	 * Associative array mapping prop module name to module specs (see $wgAPIModules).
	 * Extension modules may override the core modules.
	 *
	 * See ApiQuery::QUERY_PROP_MODULES for a list of the core prop modules.
	 */
	public const APIPropModules = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * API Query list module extensions.
	 *
	 * Associative array mapping list module name to module specs (see $wgAPIModules).
	 * Extension modules may override the core modules.
	 *
	 * See ApiQuery::QUERY_LIST_MODULES for a list of the core list modules.
	 */
	public const APIListModules = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Maximum amount of rows to scan in a DB query in the API
	 * The default value is generally fine
	 */
	public const APIMaxDBRows = [
		'default' => 5000,
	];

	/**
	 * The maximum size (in bytes) of an API result.
	 *
	 * @warning Do not set this lower than $wgMaxArticleSize*1024
	 */
	public const APIMaxResultSize = [
		'default' => 8_388_608,
	];

	/**
	 * The maximum number of uncached diffs that can be retrieved in one API
	 * request. Set this to 0 to disable API diffs altogether
	 */
	public const APIMaxUncachedDiffs = [
		'default' => 1,
	];

	/**
	 * Maximum amount of DB lag on a majority of DB replica DBs to tolerate
	 * before forcing bots to retry any write requests via API errors.
	 *
	 * This should be lower than the 'max lag' value in $wgLBFactoryConf.
	 */
	public const APIMaxLagThreshold = [
		'default' => 7,
	];

	/**
	 * Log file or URL (TCP or UDP) to log API requests to, or false to disable
	 * API request logging
	 */
	public const APIRequestLog = [
		'default' => false,
		'deprecated' => 'since 1.43; use api or api-request $wgDebugLogGroups channel',
	];

	/**
	 * Set the timeout for the API help text cache. If set to 0, caching disabled
	 */
	public const APICacheHelpTimeout = [
		'default' => 60 * 60,
	];

	/**
	 * The ApiQueryQueryPages module should skip pages that are redundant to true
	 * API queries.
	 */
	public const APIUselessQueryPages = [
		'default' => [
			'MIMEsearch',
			'LinkSearch',
		],
		'type' => 'list',
	];

	/**
	 * Enable previewing licences via AJAX.
	 */
	public const AjaxLicensePreview = [
		'default' => true,
	];

	/**
	 * Settings for incoming cross-site AJAX requests:
	 * Newer browsers support cross-site AJAX when the target resource allows requests
	 * from the origin domain by the Access-Control-Allow-Origin header.
	 *
	 * This is currently only used by the API (requests to api.php)
	 * $wgCrossSiteAJAXdomains can be set using a wildcard syntax:
	 *
	 * - '*' matches any number of characters
	 * - '?' matches any 1 character
	 *
	 * **Example:**
	 *
	 * ```
	 * $wgCrossSiteAJAXdomains = [
	 *     'www.mediawiki.org',
	 *     '*.wikipedia.org',
	 *     '*.wikimedia.org',
	 *     '*.wiktionary.org',
	 * ];
	 * ```
	 */
	public const CrossSiteAJAXdomains = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Domains that should not be allowed to make AJAX requests,
	 * even if they match one of the domains allowed by $wgCrossSiteAJAXdomains
	 * Uses the same syntax as $wgCrossSiteAJAXdomains
	 */
	public const CrossSiteAJAXdomainExceptions = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * List of allowed headers for cross-origin API requests.
	 */
	public const AllowedCorsHeaders = [
		'default' => [
			/* simple headers (see spec) */
			'Accept',
			'Accept-Language',
			'Content-Language',
			'Content-Type',
			/* non-authorable headers in XHR, which are however requested by some UAs */
			'Accept-Encoding',
			'DNT',
			'Origin',
			/* MediaWiki whitelist */
			'User-Agent',
			'Api-User-Agent',
			/* Allowing caching preflight requests, see T269636 */
			'Access-Control-Max-Age',
			/* OAuth 2.0, see T322944 */
			'Authorization',
		],
		'type' => 'list',
	];

	/**
	 * Additional REST API Route files.
	 *
	 * A common usage is to enable development/experimental endpoints only on test wikis.
	 */
	public const RestAPIAdditionalRouteFiles = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * A list of OpenAPI specs to be made available for exploration on
	 * Special:RestSandbox. If none are given, Special:RestSandbox is disabled.
	 *
	 * This is an associative array, arbitrary spec IDs to spec descriptions.
	 * Each spec description is an array with the following keys:
	 * - url: the URL that will return the OpenAPI spec.
	 * - name: the name of the API, to be shown on Special:RestSandbox.
	 *   Ignored if msg is given.
	 * - msg: a message key for the name of the API, to be shown on
	 *   Special:RestSandbox.
	 *
	 * @unstable Introduced in 1.43. We may want to rename or change this to
	 * accommodate the need to list external APIs in a central discovery
	 * document.
	 */
	public const RestSandboxSpecs = [
		'default' => [],
		'type' => 'map',
		'additionalProperties' => [
			'type' => 'object',
			'properties' => [
				'url' => [ 'type' => 'string', 'format' => 'url' ],
				'name' => [ 'type' => 'string' ],
				'msg' => [ 'type' => 'string', 'description' => 'a message key' ]
			],
			'required' => [ 'url' ]
		]
	];

	// endregion -- End AJAX and API

	/***************************************************************************/
	// region   Shell and process control
	/** @name   Shell and process control */

	/**
	 * Maximum amount of virtual memory available to shell processes under linux, in KiB.
	 */
	public const MaxShellMemory = [
		'default' => 307_200,
	];

	/**
	 * Maximum file size created by shell processes under linux, in KiB
	 * ImageMagick convert for example can be fairly hungry for scratch space
	 */
	public const MaxShellFileSize = [
		'default' => 102_400,
	];

	/**
	 * Maximum CPU time in seconds for shell processes under Linux
	 */
	public const MaxShellTime = [
		'default' => 180,
	];

	/**
	 * Maximum wall clock time (i.e. real time, of the kind the clock on the wall
	 * would measure) in seconds for shell processes under Linux
	 */
	public const MaxShellWallClockTime = [
		'default' => 180,
	];

	/**
	 * Under Linux: a cgroup directory used to constrain memory usage of shell
	 * commands. The directory must be writable by the user which runs MediaWiki.
	 *
	 * If specified, this is used instead of ulimit, which is inaccurate, and
	 * causes malloc() to return NULL, which exposes bugs in C applications, making
	 * them segfault or deadlock.
	 *
	 * A wrapper script will create a cgroup for each shell command that runs, as
	 * a subgroup of the specified cgroup. If the memory limit is exceeded, the
	 * kernel will send a SIGKILL signal to a process in the subgroup.
	 *
	 * **Example:**
	 *
	 * ```
	 * mkdir -p /sys/fs/cgroup/memory/mediawiki
	 * mkdir -m 0777 /sys/fs/cgroup/memory/mediawiki/job
	 * echo '$wgShellCgroup = "/sys/fs/cgroup/memory/mediawiki/job";' >> LocalSettings.php
	 * ```
	 * The reliability of cgroup cleanup can be improved by installing a
	 * notify_on_release script in the root cgroup, see e.g.
	 * https://gerrit.wikimedia.org/r/#/c/40784
	 */
	public const ShellCgroup = [
		'default' => false,
	];

	/**
	 * Executable path of the PHP cli binary. Should be set up on install.
	 */
	public const PhpCli = [
		'default' => '/usr/bin/php',
	];

	/**
	 * Method to use to restrict shell commands
	 *
	 * Supported options:
	 * - 'autodetect': Autodetect if any restriction methods are available
	 * - 'firejail': Use firejail <https://firejail.wordpress.com/>
	 * - false: Don't use any restrictions
	 *
	 * @note If using firejail with MediaWiki running in a home directory different
	 * from the webserver user, firejail 0.9.44+ is required.
	 * @since 1.31
	 */
	public const ShellRestrictionMethod = [
		'default' => 'autodetect',
		'type' => 'string|false',
	];

	/**
	 * Shell commands can be run on a remote server using Shellbox. To use this
	 * feature, set this to the URLs mapped by the service, and also configure $wgShellboxSecretKey.
	 *
	 * You can also disable a certain service by setting it to false or null.
	 *
	 * 'default' would be the default URL if no URL is defined for that service.
	 *
	 * For more information about installing Shellbox, see
	 * https://www.mediawiki.org/wiki/Shellbox
	 *
	 * @since 1.37
	 */
	public const ShellboxUrls = [
		'default' => [ 'default' => null, ],
		'type' => 'map',
		'additionalProperties' => [
			'type' => 'string|false|null',
		],
	];

	/**
	 * The secret key for HMAC verification of Shellbox requests. Set this to
	 * a long random string.
	 *
	 * @since 1.36
	 */
	public const ShellboxSecretKey = [
		'default' => null,
		'type' => '?string',
	];

	/**
	 * The POSIX-compatible shell to use when running scripts. This is used by
	 * some media handling shell commands.
	 *
	 * If ShellboxUrls is configured, this path should exist on the remote side.
	 * On Windows this should be the full path to bash.exe, not git-bash.exe.
	 *
	 * @since 1.42
	 */
	public const ShellboxShell = [
		'default' => '/bin/sh',
		'type' => '?string',
	];

	// endregion -- end Shell and process control

	/***************************************************************************/
	// region   HTTP client
	/** @name   HTTP client */

	/**
	 * Timeout for HTTP requests done internally, in seconds.
	 *
	 * @since 1.5
	 */
	public const HTTPTimeout = [
		'default' => 25,
		'type' => 'float',
	];

	/**
	 * Timeout for connections done internally (in seconds).
	 *
	 * Only supported if cURL is installed, ignored otherwise.
	 *
	 * @since 1.22
	 */
	public const HTTPConnectTimeout = [
		'default' => 5.0,
		'type' => 'float',
	];

	/**
	 * The maximum HTTP request timeout in seconds. If any specified or configured
	 * request timeout is larger than this, then this value will be used instead.
	 * Zero is interpreted as "no limit".
	 *
	 * @since 1.35
	 */
	public const HTTPMaxTimeout = [
		'default' => 0,
		'type' => 'float',
	];

	/**
	 * The maximum HTTP connect timeout in seconds. If any specified or configured
	 * connect timeout is larger than this, then this value will be used instead.
	 * Zero is interpreted as "no limit".
	 *
	 * @since 1.35
	 */
	public const HTTPMaxConnectTimeout = [
		'default' => 0,
		'type' => 'float',
	];

	/**
	 * Timeout for HTTP requests done internally for transwiki imports, in seconds.
	 *
	 * @since 1.29
	 */
	public const HTTPImportTimeout = [
		'default' => 25,
	];

	/**
	 * Timeout for Asynchronous (background) HTTP requests, in seconds.
	 */
	public const AsyncHTTPTimeout = [
		'default' => 25,
	];

	/**
	 * Proxy to use for CURL requests.
	 */
	public const HTTPProxy = [
		'default' => '',
	];

	/**
	 * A list of URL domains that will be routed to the proxy specified by
	 * $wgLocalHTTPProxy.
	 *
	 * @since 1.25
	 */
	public const LocalVirtualHosts = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Proxy to use for requests to domains in $wgLocalVirtualHosts
	 *
	 * If set to false, no reverse proxy will be used for local requests.
	 *
	 * @since 1.38
	 */
	public const LocalHTTPProxy = [
		'default' => false,
		'type' => 'string|false',
	];

	/**
	 * Whether to respect/honour
	 *  - request ID provided by the incoming request via the `X-Request-Id`
	 *  - trace context provided by the incoming request via the `tracestate` and `traceparent`
	 *
	 * Set to `true` if the entity sitting in front of MediaWiki sanitises external requests.
	 *
	 * Default: `false`.
	 */
	public const AllowExternalReqID = [
		'default' => false,
	];

	// endregion -- End HTTP client

	/***************************************************************************/
	// region   Job queue
	/** @name   Job queue */

	/**
	 * Number of jobs to perform per request. May be less than one in which case jobs are
	 * performed probabilistically. If this is zero, jobs will not be done during ordinary
	 * apache requests. In this case, maintenance/runJobs.php should be run in loop every
	 * few seconds via a service or cron job. If using a cron job, be sure to handle the
	 * case where the script is already running (e.g. via `/usr/bin/flock -n <lock_file>`).
	 *
	 * If this is set to a non-zero number, then it is highly recommended that PHP run in
	 * fastcgi mode (php_fpm). When using a standard Apache PHP handler (mod_php), it is
	 * recommended that output_buffering and zlib.output_compression both be set to "Off",
	 * allowing MediaWiki to install an unlimited size output buffer on the fly. Setting
	 * output_buffering to an integer (e.g. 4096) or enabling zlib.output_compression can
	 * cause user-visible slowness as background tasks execute during web requests.
	 *
	 * Regardless of the web server engine in use, be sure to configure a sufficient number
	 * processes/threads in order to avoid exhaustion (which will cause user-visible slowness).
	 */
	public const JobRunRate = [
		'default' => 1,
	];

	/**
	 * When $wgJobRunRate > 0, try to run jobs asynchronously, spawning a new process
	 * to handle the job execution, instead of blocking the request until the job
	 * execution finishes.
	 *
	 * @since 1.23
	 */
	public const RunJobsAsync = [
		'default' => false,
	];

	/**
	 * Number of rows to update per job
	 */
	public const UpdateRowsPerJob = [
		'default' => 300,
	];

	/**
	 * Number of rows to update per query
	 */
	public const UpdateRowsPerQuery = [
		'default' => 100,
	];

	// endregion -- End job queue

	/***************************************************************************/
	// region   Miscellaneous
	/** @name   Miscellaneous */

	/**
	 * Allow redirection to another page when a user logs in.
	 *
	 * To enable, set to a string like 'Main Page'
	 */
	public const RedirectOnLogin = [
		'default' => null,
	];

	/**
	 * Global configuration variable for Virtual REST Services.
	 *
	 * Use the 'path' key to define automatically mounted services. The value for this
	 * key is a map of path prefixes to service configuration. The latter is an array of:
	 *   - class : the fully qualified class name
	 *   - options : map of arguments to the class constructor
	 * Such services will be available to handle queries under their path from the VRS
	 * singleton, e.g. MediaWikiServices::getInstance()->getVirtualRESTServiceClient();
	 *
	 * Auto-mounting example for Parsoid:
	 *
	 * $wgVirtualRestConfig['paths']['/parsoid/'] = [
	 *     'class' => ParsoidVirtualRESTService::class,
	 *     'options' => [
	 *         'url' => 'http://localhost:8000',
	 *         'prefix' => 'enwiki',
	 *         'domain' => 'en.wikipedia.org'
	 *     ]
	 * ];
	 *
	 * Parameters for different services can also be declared inside the 'modules' value,
	 * which is to be treated as an associative array. The parameters in 'global' will be
	 * merged with service-specific ones. The result will then be passed to
	 * VirtualRESTService::__construct() in the module.
	 *
	 * Example config for Parsoid:
	 *
	 *   $wgVirtualRestConfig['modules']['parsoid'] = [
	 *     'url' => 'http://localhost:8000',
	 *     'prefix' => 'enwiki',
	 *     'domain' => 'en.wikipedia.org',
	 *   ];
	 *
	 * @since 1.25
	 */
	public const VirtualRestConfig = [
		'default' => [
			'paths' => [],
			'modules' => [],
			'global' => [
				# Timeout in seconds
				'timeout' => 360,
				# 'domain' is set to $wgCanonicalServer in Setup.php
				'forwardCookies' => false,
				'HTTPProxy' => null
			]
		],
		'mergeStrategy' => 'array_plus_2d',
		'type' => 'map',
	];

	/**
	 * Mapping of event channels (or channel categories) to EventRelayer configuration.
	 *
	 * By setting up a PubSub system (like Kafka) and enabling a corresponding EventRelayer class
	 * that uses it, MediaWiki can broadcast events to all subscribers. Certain features like WAN
	 * cache purging and CDN cache purging will emit events to this system. Appropriate listeners
	 * can subscribe to the channel and take actions based on the events. For example, a local daemon
	 * can run on each CDN cache node and perform local purges based on the URL purge channel
	 * events.
	 *
	 * Some extensions may want to use "channel categories" so that different channels can also
	 * share the same custom relayer instance (e.g. when it's likely to be overridden). They can use
	 * EventRelayerGroup::getRelayer() based on the category but call notify() on various different
	 * actual channels. One reason for this would be that some systems have very different
	 * performance vs durability needs, so one system (e.g. Kafka) may not be suitable for all
	 * uses.
	 *
	 * The 'default' key is for all channels (or channel categories) without an explicit entry
	 * here.
	 *
	 * @since 1.27
	 */
	public const EventRelayerConfig = [
		'default' => [
			'default' => [ 'class' => EventRelayerNull::class, ],
		],
		'type' => 'map',
	];

	/**
	 * Share data about this installation with MediaWiki developers
	 *
	 * When set to true, MediaWiki will periodically ping https://www.mediawiki.org/ with basic
	 * data about this MediaWiki instance. This data includes, for example, the type of system,
	 * PHP version, and chosen database backend. The Wikimedia Foundation shares this data with
	 * MediaWiki developers to help guide future development efforts.
	 *
	 * For details about what data is sent, see: https://www.mediawiki.org/wiki/Manual:$wgPingback
	 *
	 * For the pingback privacy policy, see:
	 * https://wikimediafoundation.org/wiki/MediaWiki_Pingback_Privacy_Statement
	 *
	 * Aggregate pingback data is available at: https://pingback.wmcloud.org/
	 *
	 * @since 1.28
	 */
	public const Pingback = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Origin Trials tokens.
	 *
	 * @since 1.33
	 */
	public const OriginTrials = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Expiry of the endpoint definition for the Reporting API.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.34
	 */
	public const ReportToExpiry = [
		'default' => 86400,
		'type' => 'integer',
	];

	/**
	 * List of endpoints for the Reporting API.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.34
	 */
	public const ReportToEndpoints = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * List of Feature Policy Reporting types to enable.
	 *
	 * Each entry is turned into a Feature-Policy-Report-Only header.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.34
	 */
	public const FeaturePolicyReportOnly = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * List of preferred skins to be listed higher in Special:Preferences
	 *
	 * @since 1.38
	 */
	public const SkinsPreferred = [
		'default' => [ 'vector-2022', 'vector' ],
		'type' => 'list',
	];

	/**
	 * List of skins that show a link to the Special:Contribute page
	 *
	 * @since 1.40
	 */
	public const SpecialContributeSkinsEnabled = [
		'default' => [],
		'type' => 'list',
	];

	/**
	 * Whether to enable the client-side edit recovery feature.
	 *
	 * @unstable Temporary feature flag, T341844
	 * @since 1.41
	 */
	public const EnableEditRecovery = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Number of seconds to keep edit recovery data after the edit is stored.
	 */
	public const EditRecoveryExpiry = [
		'default' => 30 * 24 * 3600,
		'type' => 'integer',
	];

	/**
	 * Whether to use Codex in Special:Block form.
	 *
	 * @unstable Temporary feature flag, T358153
	 * @since 1.42
	 */
	public const UseCodexSpecialBlock = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Whether to display a confirmation screen during user log out.
	 *
	 * @unstable Temporary feature flag, T357484
	 * @since 1.42
	 */
	public const ShowLogoutConfirmation = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * Whether to show indicators on a page when it is protected.
	 *
	 * @since 1.43
	 */
	public const EnableProtectionIndicators = [
		'default' => false,
		'type' => 'boolean',
	];

	/**
	 * OutputPipelineStages to add to the DefaultOutputPipeline.
	 *
	 * @unstable EXPERIMENTAL
	 * @since 1.43
	 */
	public const OutputPipelineStages = [
		'default' => [],
		'type' => 'map',
	];

	/**
	 * Allow temporarily disabling use of certain features. Useful for
	 * large site operators to push people to newer APIs while still
	 * keeping the breakage short and contained.
	 *
	 * This should be an array, where a key is a feature name and the value
	 * is an array of shutdown arrays, each containing the following keys:
	 * 	'start' => Shutdown start, parsed with strtotime(). (required)
	 * 	'end' => Shutdown end, parsed with strtotime(). (required)
	 * 	'percentage' => Number between 0 and 100. If set, only a certain
	 * 	  percentage of requests will be blocked.
	 *
	 * For example:
	 * @code
	 * $wgFeatureShutdown = [
	 *   'pre-1.24-tokens' => [
	 *     [
	 *       'start' => '2021-07-01T00:00 +00:00',
	 *       'end' => '2021-08-01T00:00 +00:00',
	 *       'percentage' => 50,
	 *     ],
	 *   ],
	 * ];
	 * @encdode
	 *
	 * @since 1.44
	 */
	public const FeatureShutdown = [
		'default' => [],
		'type' => 'list',
	];
	// endregion -- End Miscellaneous

}
