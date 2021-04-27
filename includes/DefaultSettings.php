<?php
/**
 * Default values for MediaWiki configuration settings.
 *
 *
 *                 NEVER EDIT THIS FILE
 *
 *
 * To customize your installation, edit "LocalSettings.php". If you make
 * changes here, they will be lost on next upgrade of MediaWiki!
 *
 * In this file, variables whose default values depend on other
 * variables are set to false. The actual default value of these variables
 * will only be set in Setup.php, taking into account any custom settings
 * performed in LocalSettings.php.
 *
 * Documentation is in the source and on:
 * https://www.mediawiki.org/wiki/Manual:Configuration_settings
 *
 * @warning  Note: this (and other things) will break if the autoloader is not
 * enabled. Please include includes/AutoLoader.php before including this file.
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

/**
 * @cond file_level_code
 * This is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is part of MediaWiki and is not a valid entry point\n";
	die( 1 );
}

/** @endcond */

/**
 * $wgConf hold the site configuration.
 * Not used for much in a default install.
 * @since 1.5
 */
$wgConf = new SiteConfiguration;

/**
 * Registry of factory functions to create config objects:
 * The 'main' key must be set, and the value should be a valid
 * callable.
 * @since 1.23
 */
$wgConfigRegistry = [
	'main' => 'GlobalVarConfig::newInstance'
];

/**
 * MediaWiki version number
 * @since 1.2
 * @deprecated since 1.35; use the MW_VERSION constant instead
 */
$wgVersion = MW_VERSION;

/**
 * Name of the site. It must be changed in LocalSettings.php
 */
$wgSitename = 'MediaWiki';

/** @} */

/************************************************************************//**
 * @name   Server URLs and file paths
 *
 * In this section, a "path" is usually a host-relative URL, i.e. a URL without
 * the host part, that starts with a slash. In most cases a full URL is also
 * acceptable. A "directory" is a local file path.
 *
 * In both paths and directories, trailing slashes should not be included.
 *
 * @{
 */

/**
 * URL of the server.
 *
 * @par Example:
 * @code
 * $wgServer = 'http://example.com';
 * @endcode
 *
 * This must be set in LocalSettings.php. The MediaWiki installer does this
 * automatically since 1.18.
 *
 * If you want to use protocol-relative URLs on your wiki, set this to a
 * protocol-relative URL like '//example.com' and set $wgCanonicalServer
 * to a fully qualified URL.
 */
$wgServer = false;

/**
 * Canonical URL of the server, to use in IRC feeds and notification e-mails.
 * Must be fully qualified, even if $wgServer is protocol-relative.
 *
 * Defaults to $wgServer, expanded to a fully qualified http:// URL if needed.
 * @since 1.18
 */
$wgCanonicalServer = false;

/**
 * Server name. This is automatically computed by parsing the bare
 * hostname out of $wgCanonicalServer. It should not be customized.
 * @since 1.24
 */
$wgServerName = false;

/**
 * When the wiki is running behind a proxy and this is set to true, assumes that the proxy exposes
 * the wiki on the standard ports (443 for https and 80 for http).
 * @var bool
 * @since 1.26
 */
$wgAssumeProxiesUseDefaultProtocolPorts = true;

/**
 * For installations where the canonical server is HTTP but HTTPS is optionally
 * supported, you can specify a non-standard HTTPS port here. $wgServer should
 * be a protocol-relative URL.
 *
 * If HTTPS is always used, just specify the port number in $wgServer.
 *
 * @see https://phabricator.wikimedia.org/T67184
 *
 * @since 1.24
 */
$wgHttpsPort = 443;

/**
 * If this is true, when an insecure HTTP request is received, always redirect
 * to HTTPS. This overrides and disables the preferhttps user preference, and it
 * overrides $wgSecureLogin and the CanIPUseHTTPS hook.
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
 * @var bool
 * @since 1.35
 */
$wgForceHTTPS = false;

/**
 * The path we should point to.
 * It might be a virtual path in case with use apache mod_rewrite for example.
 *
 * This *needs* to be set correctly.
 *
 * Other paths will be set to defaults based on it unless they are directly
 * set in LocalSettings.php
 */
$wgScriptPath = '/wiki';

/**
 * Whether to support URLs like index.php/Page_title These often break when PHP
 * is set up in CGI mode. PATH_INFO *may* be correct if cgi.fix_pathinfo is set,
 * but then again it may not; lighttpd converts incoming path data to lowercase
 * on systems with case-insensitive filesystems, and there have been reports of
 * problems on Apache as well.
 *
 * To be safe we'll continue to keep it off by default.
 *
 * Override this to false if $_SERVER['PATH_INFO'] contains unexpectedly
 * incorrect garbage, or to true if it is really correct.
 *
 * The default $wgArticlePath will be set based on this value at runtime, but if
 * you have customized it, having this incorrectly set to true can cause
 * redirect loops when "pretty URLs" are used.
 * @since 1.2.1
 */
$wgUsePathInfo = ( strpos( PHP_SAPI, 'cgi' ) === false ) &&
	( strpos( PHP_SAPI, 'apache2filter' ) === false ) &&
	( strpos( PHP_SAPI, 'isapi' ) === false );

/**
 * The URL path to index.php.
 *
 * Defaults to "{$wgScriptPath}/index.php".
 */
$wgScript = false;

/**
 * The URL path to load.php.
 *
 * Defaults to "{$wgScriptPath}/load.php".
 * @since 1.17
 */
$wgLoadScript = false;

/**
 * The URL path to the REST API
 * Defaults to "{$wgScriptPath}/rest.php"
 * @since 1.34
 */
$wgRestPath = false;

/**
 * The URL path of the skins directory.
 * Defaults to "{$wgResourceBasePath}/skins".
 * @since 1.3
 */
$wgStylePath = false;
$wgStyleSheetPath = &$wgStylePath;

/**
 * The URL path of the skins directory. Should not point to an external domain.
 * Defaults to "{$wgScriptPath}/skins".
 * @since 1.17
 */
$wgLocalStylePath = false;

/**
 * The URL path of the extensions directory.
 * Defaults to "{$wgResourceBasePath}/extensions".
 * @since 1.16
 */
$wgExtensionAssetsPath = false;

/**
 * Filesystem extensions directory.
 * Defaults to "{$IP}/extensions".
 * @since 1.25
 */
$wgExtensionDirectory = "{$IP}/extensions";

/**
 * Filesystem stylesheets directory.
 * Defaults to "{$IP}/skins".
 * @since 1.3
 */
$wgStyleDirectory = "{$IP}/skins";

/**
 * The URL path for primary article page views. This path should contain $1,
 * which is replaced by the article title.
 *
 * Defaults to "{$wgScript}/$1" or "{$wgScript}?title=$1",
 * depending on $wgUsePathInfo.
 */
$wgArticlePath = false;

/**
 * The URL path for the images directory.
 * Defaults to "{$wgScriptPath}/images".
 */
$wgUploadPath = false;

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
$wgImgAuthPath = false;

/**
 * The base path for thumb_handler.php. This is used to interpret the request URL
 * for requests to thumb_handler.php that do not match the base upload path.
 *
 * @since 1.35.1
 */
$wgThumbPath = false;

/**
 * The filesystem path of the images directory. Defaults to "{$IP}/images".
 */
$wgUploadDirectory = false;

/**
 * Directory where the cached page will be saved.
 * Defaults to "{$wgUploadDirectory}/cache".
 */
$wgFileCacheDirectory = false;

/**
 * The URL path of the wiki logo. The logo size should be 135x135 pixels.
 * Defaults to "$wgResourceBasePath/resources/assets/wiki.png".
 * Developers should retrieve this logo (and other variants) using
 *   the static function ResourceLoaderSkinModule::getAvailableLogos
 * Ignored if $wgLogos is set.
 */
$wgLogo = false;

/**
 * The URL path to various wiki logos.
 * The `1x` logo size should be 135x135 pixels.
 * The `1.5x` 1.5x version of square logo
 * The `2x` 2x version of square logo
 * The `svg` version of square logo
 * The `wordmark` key should point to an array with the following fields
 *  - `src` relative or absolute path to a landscape logo
 *  - `width` defining the width of the logo in pixels.
 *  - `height` defining the height of the logo in pixels.
 * All values can be either an absolute or relative URI
 * Configuration is optional provided $wgLogo is used instead.
 * Defaults to [ "1x" => $wgLogo ],
 *   or [ "1x" => "$wgResourceBasePath/resources/assets/wiki.png" ] if $wgLogo is not set.
 * @since 1.35
 * @var array|false
 */
$wgLogos = false;

/**
 * Array with URL paths to HD versions of the wiki logo. The scaled logo size
 * should be under 135x155 pixels.
 * Only 1.5x and 2x versions are supported.
 *
 * @par Example:
 * @code
 * $wgLogoHD = [
 *	"1.5x" => "path/to/1.5x_version.png",
 *	"2x" => "path/to/2x_version.png"
 * ];
 * @endcode
 *
 * SVG is also supported but when enabled, it
 * disables 1.5x and 2x as svg will already
 * be optimised for screen resolution.
 *
 * @par Example:
 * @code
 * $wgLogoHD = [
 *	"svg" => "path/to/svg_version.svg",
 * ];
 * @endcode
 *
 * @var array|false
 * @since 1.25
 * @deprecated since 1.35. Developers should retrieve this logo (and other variants) using
 *   the static function ResourceLoaderSkinModule::getAvailableLogos. $wgLogos should be used
 *   instead.
 */
$wgLogoHD = false;

/**
 * The URL path of the shortcut icon.
 * @since 1.6
 */
$wgFavicon = '/favicon.ico';

/**
 * The URL path of the icon for iPhone and iPod Touch web app bookmarks.
 * Defaults to no icon.
 * @since 1.12
 */
$wgAppleTouchIcon = false;

/**
 * Value for the referrer policy meta tag.
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
 * @var array|string|bool
 * @since 1.25
 */
$wgReferrerPolicy = false;

/**
 * The local filesystem path to a temporary directory. This must not be web accessible.
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
 * or configution). When storing files here, take care to avoid conflicts
 * with other instances of MediaWiki. For example, when caching the result
 * of a computation, the file name should incorporate the input of the
 * computation so that it cannot be confused for the result of a similar
 * computation by another MediaWiki instance.
 *
 * @see wfTempDir()
 * @note Default changed to false in MediaWiki 1.20.
 */
$wgTmpDirectory = false;

/**
 * If set, this URL is added to the start of $wgUploadPath to form a complete
 * upload URL.
 * @since 1.4
 */
$wgUploadBaseUrl = '';

/**
 * To enable remote on-demand scaling, set this to the thumbnail base URL.
 * Full thumbnail URL will be like $wgUploadStashScalerBaseUrl/e/e6/Foo.jpg/123px-Foo.jpg
 * where 'e6' are the first two characters of the MD5 hash of the file name.
 * If $wgUploadStashScalerBaseUrl is set to false, thumbs are rendered locally as needed.
 * @since 1.17
 */
$wgUploadStashScalerBaseUrl = false;

/**
 * To set 'pretty' URL paths for actions other than
 * plain page views, add to this array.
 *
 * @par Example:
 * Set pretty URL for the edit action:
 * @code
 *   'edit' => "$wgScriptPath/edit/$1"
 * @endcode
 *
 * There must be an appropriate script or rewrite rule in place to handle these
 * URLs.
 * @since 1.5
 */
$wgActionPaths = [];

/** @} */

/************************************************************************//**
 * @name   Files and file uploads
 * @{
 */

/**
 * Allow users to upload files.
 *
 * Use $wgLocalFileRepo to control how and where uploads are stored.
 * Disabled by default as for security reasons.
 * See <https://www.mediawiki.org/wiki/Manual:Configuring_file_uploads>.
 *
 * @since 1.5
 */
$wgEnableUploads = false;

/**
 * The maximum age of temporary (incomplete) uploaded files
 */
$wgUploadStashMaxAge = 6 * 3600; // 6 hours

/**
 * Allows to move images and other media files
 *
 * @deprecated since 1.35, use group permission settings instead.
 * (eg $wgGroupPermissions['sysop']['movefile'] = false; to revoke the
 * ability from sysops)
 */
$wgAllowImageMoving = true;

/**
 * Enable deferred upload tasks that use the job queue.
 * Only enable this if job runners are set up for both the
 * 'AssembleUploadChunks' and 'PublishStashedFile' job types.
 *
 * @note If you use suhosin, this setting is incompatible with
 *   suhosin.session.encrypt.
 */
$wgEnableAsyncUploads = false;

/**
 * Additional characters that are not allowed in filenames. They are replaced with '-' when
 * uploading. Like $wgLegalTitleChars, this is a regexp character class.
 *
 * Slashes and backslashes are disallowed regardless of this setting, but included here for
 * completeness.
 */
$wgIllegalFileChars = ":\\/\\\\";

/**
 * What directory to place deleted uploads in.
 * Defaults to "{$wgUploadDirectory}/deleted".
 */
$wgDeletedDirectory = false;

/**
 * Set this to true if you use img_auth and want the user to see details on why access failed.
 */
$wgImgAuthDetails = false;

/**
 * Map of relative URL directories to match to internal mwstore:// base storage paths.
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
 * @see $wgFileBackends
 */
$wgImgAuthUrlPathMap = [];

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
 *   - backend          A file backend name (see $wgFileBackends).
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
 * @see FileRepo::__construct for the default options.
 * @see Setup.php for an example usage and default initialization.
 */
$wgLocalFileRepo = false;

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
 * @see $wgLocalFileRepo
 */
$wgForeignFileRepos = [];

/**
 * Use Wikimedia Commons as a foreign file repository.
 *
 * This is a shortcut for adding an entry to to $wgForeignFileRepos
 * for https://commons.wikimedia.org, using ForeignAPIRepo with the
 * default settings.
 *
 * @since 1.16
 */
$wgUseInstantCommons = false;

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
 * @var bool
 * @since 1.3
 */
$wgUseSharedUploads = false;

/**
 * Shortcut for the 'directory' setting of $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var string
 * @since 1.3
 */
$wgSharedUploadDirectory = null;

/**
 * Shortcut for the 'url' setting of $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var string
 * @since 1.3
 */
$wgSharedUploadPath = null;

/**
 * Shortcut for the 'hashLevels' setting of $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var bool
 * @since 1.3
 */
$wgHashedSharedUploadDirectory = true;

/**
 * Shortcut for the 'descBaseUrl' setting of $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @since 1.5
 */
$wgRepositoryBaseUrl = 'https://commons.wikimedia.org/wiki/File:';

/**
 * Shortcut for the 'fetchDescription' setting of $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var bool
 * @since 1.5
 */
$wgFetchCommonsDescriptions = false;

/**
 * Shortcut for the ForeignDBRepo 'dbName' setting in $wgForeignFileRepos.
 * Set this to false if the uploads do not come from a wiki.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var bool|string
 * @since 1.4
 */
$wgSharedUploadDBname = false;

/**
 * Shortcut for the ForeignDBRepo 'tablePrefix' setting in $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var string
 * @since 1.5
 */
$wgSharedUploadDBprefix = '';

/**
 * Shortcut for the ForeignDBRepo 'hasSharedCache' setting in $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var bool
 * @since 1.5
 */
$wgCacheSharedUploads = true;

/**
 * Array of foreign file repo names (set in $wgForeignFileRepos above) that
 * are allowable upload targets. These wikis must have some method of
 * authentication (i.e. CentralAuth), and be CORS-enabled for this wiki.
 * The string 'local' signifies the default local file repository.
 *
 * Example:
 * $wgForeignUploadTargets = [ 'shared' ];
 */
$wgForeignUploadTargets = [ 'local' ];

/**
 * Configuration for file uploads using the embeddable upload dialog
 * (https://www.mediawiki.org/wiki/Upload_dialog).
 *
 * This applies also to foreign uploads to this wiki (the configuration is loaded by remote wikis
 * using the action=query&meta=siteinfo API).
 *
 * See below for documentation of each property. None of the properties may be omitted.
 */
$wgUploadDialog = [
	// Fields to make available in the dialog. `true` means that this field is visible, `false` means
	// that it is hidden. The "Name" field can't be hidden. Note that you also have to add the
	// matching replacement to the 'filepage' format key below to make use of these.
	'fields' => [
		'description' => true,
		'date' => false,
		'categories' => false,
	],
	// Suffix of localisation messages used to describe the license under which the uploaded file will
	// be released. The same value may be set for both 'local' and 'foreign' uploads.
	'licensemessages' => [
		// The 'local' messages are used for local uploads on this wiki:
		// * upload-form-label-own-work-message-generic-local
		// * upload-form-label-not-own-work-message-generic-local
		// * upload-form-label-not-own-work-local-generic-local
		'local' => 'generic-local',
		// The 'foreign' messages are used for cross-wiki uploads from other wikis to this wiki:
		// * upload-form-label-own-work-message-generic-foreign
		// * upload-form-label-not-own-work-message-generic-foreign
		// * upload-form-label-not-own-work-local-generic-foreign
		'foreign' => 'generic-foreign',
	],
	// Upload comments to use for 'local' and 'foreign' uploads. This can also be set to a single
	// string value, in which case it is used for both kinds of uploads. Available replacements:
	// * $HOST - domain name from which a cross-wiki upload originates
	// * $PAGENAME - wiki page name from which an upload originates
	'comment' => [
		'local' => '',
		'foreign' => '',
	],
	// Format of the file page wikitext to be generated from the fields input by the user.
	'format' => [
		// Wrapper for the whole page. Available replacements:
		// * $DESCRIPTION - file description, as input by the user (only if the 'description' field is
		//   enabled), wrapped as defined below in the 'description' key
		// * $DATE - file creation date, as input by the user (only if the 'date' field is enabled)
		// * $SOURCE - as defined below in the 'ownwork' key, may be extended in the future
		// * $AUTHOR - linked user name, may be extended in the future
		// * $LICENSE - as defined below in the 'license' key, may be extended in the future
		// * $CATEGORIES - file categories wikitext, as input by the user (only if the 'categories'
		//   field is enabled), or if no input, as defined below in the 'uncategorized' key
		'filepage' => '$DESCRIPTION',
		// Wrapped for file description. Available replacements:
		// * $LANGUAGE - source wiki's content language
		// * $TEXT - input by the user
		'description' => '$TEXT',
		'ownwork' => '',
		'license' => '',
		'uncategorized' => '',
	],
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
 *  - fileJournal : File journal configuration for FileJournal::__construct() [optional]
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
$wgFileBackends = [];

/**
 * Array of configuration arrays for each lock manager.
 * Each backend configuration has the following parameters:
 *  - name  : A unique name for the lock manager
 *  - class : The lock manger class to use
 *
 * See LockManager::__construct() for more details.
 * Additional parameters are specific to the lock manager class used.
 * These settings should be global to all wikis.
 */
$wgLockManagers = [];

/**
 * Show Exif data, on by default if available.
 * Requires PHP's Exif extension: https://www.php.net/manual/en/ref.exif.php
 *
 * @note FOR WINDOWS USERS:
 * To enable Exif functions, add the following line to the "Windows
 * extensions" section of php.ini:
 * @code{.ini}
 * extension=extensions/php_exif.dll
 * @endcode
 */
$wgShowEXIF = function_exists( 'exif_read_data' );

/**
 * If to automatically update the img_metadata field
 * if the metadata field is outdated but compatible with the current version.
 * Defaults to false.
 */
$wgUpdateCompatibleMetadata = false;

/**
 * Allow for upload to be copied from an URL.
 * The timeout for copy uploads is set by $wgCopyUploadTimeout.
 * You have to assign the user right 'upload_by_url' to a user group, to use this.
 */
$wgAllowCopyUploads = false;

/**
 * A list of domains copy uploads can come from
 *
 * @since 1.20
 */
$wgCopyUploadsDomains = [];

/**
 * Enable copy uploads from Special:Upload. $wgAllowCopyUploads must also be
 * true. If $wgAllowCopyUploads is true, but this is false, you will only be
 * able to perform copy uploads from the API or extensions (e.g. UploadWizard).
 */
$wgCopyUploadsFromSpecialUpload = false;

/**
 * Proxy to use for copy upload requests.
 * @since 1.20
 */
$wgCopyUploadProxy = false;

/**
 * Different timeout for upload by url
 * This could be useful since when fetching large files, you may want a
 * timeout longer than the default $wgHTTPTimeout. False means fallback
 * to default.
 *
 * @var int|bool
 *
 * @since 1.22
 */
$wgCopyUploadTimeout = false;

/**
 * Max size for uploads, in bytes.
 *
 * If not set to an array, applies to all uploads. If set to an array, per upload
 * type maximums can be set, using the file and url keys. If the `*` key is set
 * this value will be used as maximum for non-specified types.
 *
 * The below example would set the maximum for all uploads to 250 kB except,
 * for upload-by-url, which would have a maximum of 500 kB.
 *
 * @par Example:
 * @code
 * $wgMaxUploadSize = [
 *     '*' => 250 * 1024,
 *     'url' => 500 * 1024,
 * ];
 * @endcode
 *
 * Default: 100 MB.
 */
$wgMaxUploadSize = 1024 * 1024 * 100;

/**
 * Minimum upload chunk size, in bytes.
 *
 * When using chunked upload, non-final chunks smaller than this will be rejected.
 *
 * Note that this may be further reduced by the `upload_max_filesize` and
 * `post_max_size` PHP settings. Use ApiUpload::getMinUploadChunkSize to
 * get the effective minimum chunk size used by MediaWiki.
 *
 * Default: 1 KB.
 *
 * @since 1.26
 * @see ApiUpload::getMinUploadChunkSize
 */
$wgMinUploadChunkSize = 1024;

/**
 * Point the upload navigation link to an external URL
 * Useful if you want to use a shared repository by default
 * without disabling local uploads (use $wgEnableUploads = false for that).
 *
 * @par Example:
 * @code
 * $wgUploadNavigationUrl = 'https://commons.wikimedia.org/wiki/Special:Upload';
 * @endcode
 */
$wgUploadNavigationUrl = false;

/**
 * Point the upload link for missing files to an external URL, as with
 * $wgUploadNavigationUrl. The URL will get "(?|&)wpDestFile=<filename>"
 * appended to it as appropriate.
 */
$wgUploadMissingFileUrl = false;

/**
 * Give a path here to use thumb.php for thumbnail generation on client
 * request, instead of generating them on render and outputting a static URL.
 * This is necessary if some of your apache servers don't have read/write
 * access to the thumbnail path.
 *
 * @par Example:
 * @code
 *   $wgThumbnailScriptPath = "{$wgScriptPath}/thumb.php";
 * @endcode
 */
$wgThumbnailScriptPath = false;

/**
 * Shortcut for the 'thumbScriptUrl' setting of $wgForeignFileRepos.
 * Only used if $wgUseSharedUploads is enabled.
 *
 * @var string
 * @since 1.3
 */
$wgSharedThumbnailScriptPath = false;

/**
 * Shortcut for setting `hashLevels=2` in $wgLocalFileRepo.
 *
 * @note Only used if $wgLocalFileRepo is not set.
 * @var bool
 */
$wgHashedUploadDirectory = true;

/**
 * This is the list of preferred extensions for uploading files. Uploading files
 * with extensions not in this list will trigger a warning.
 *
 * @warning If you add any OpenOffice or Microsoft Office file formats here,
 * such as odt or doc, and untrusted users are allowed to upload files, then
 * your wiki will be vulnerable to cross-site request forgery (CSRF).
 */
$wgFileExtensions = [ 'png', 'gif', 'jpg', 'jpeg', 'webp' ];

/**
 * Files with these extensions will never be allowed as uploads.
 * An array of file extensions to blacklist. You should append to this array
 * if you want to blacklist additional files.
 */
$wgFileBlacklist = [
	# HTML may contain cookie-stealing JavaScript and web bugs
	'html', 'htm', 'js', 'jsb', 'mhtml', 'mht', 'xhtml', 'xht',
	# PHP scripts may execute arbitrary code on the server
	'php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'phar',
	# Other types that may be interpreted by some servers
	'shtml', 'jhtml', 'pl', 'py', 'cgi',
	# May contain harmful executables for Windows victims
	'exe', 'scr', 'dll', 'msi', 'vbs', 'bat', 'com', 'pif', 'cmd', 'vxd', 'cpl' ];

/**
 * Files with these MIME types will never be allowed as uploads
 * if $wgVerifyMimeType is enabled.
 */
$wgMimeTypeBlacklist = [
	# HTML may contain cookie-stealing JavaScript and web bugs
	'text/html', 'text/javascript', 'text/x-javascript', 'application/x-shellscript',
	# PHP scripts may execute arbitrary code on the server
	'application/x-php', 'text/x-php',
	# Other types that may be interpreted by some servers
	'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh',
	# Client-side hazards on Internet Explorer
	'text/scriptlet', 'application/x-msdownload',
	# Windows metafile, client-side vulnerability on some systems
	'application/x-msmetafile',
];

/**
 * Allow Java archive uploads.
 * This is not recommended for public wikis since a maliciously-constructed
 * applet running on the same domain as the wiki can steal the user's cookies.
 */
$wgAllowJavaUploads = false;

/**
 * This is a flag to determine whether or not to check file extensions on upload.
 *
 * @warning Setting this to false is insecure for public wikis.
 */
$wgCheckFileExtensions = true;

/**
 * If this is turned off, users may override the warning for files not covered
 * by $wgFileExtensions.
 *
 * @warning Setting this to false is insecure for public wikis.
 */
$wgStrictFileExtensions = true;

/**
 * Setting this to true will disable the upload system's checks for HTML/JavaScript.
 *
 * @warning THIS IS VERY DANGEROUS on a publicly editable site, so USE
 * $wgGroupPermissions TO RESTRICT UPLOADING to only those that you trust
 */
$wgDisableUploadScriptChecks = false;

/**
 * Warn if uploaded files are larger than this (in bytes), or false to disable
 */
$wgUploadSizeWarning = false;

/**
 * list of trusted media-types and MIME types.
 * Use the MEDIATYPE_xxx constants to represent media types.
 * This list is used by File::isSafeFile
 *
 * Types not listed here will have a warning about unsafe content
 * displayed on the images description page. It would also be possible
 * to use this for further restrictions, like disabling direct
 * [[media:...]] links for non-trusted formats.
 */
$wgTrustedMediaFormats = [
	MEDIATYPE_BITMAP, // all bitmap formats
	MEDIATYPE_AUDIO, // all audio formats
	MEDIATYPE_VIDEO, // all plain video formats
	"image/svg+xml", // svg (only needed if inline rendering of svg is not supported)
	"application/pdf", // PDF files
	# "application/x-shockwave-flash", //flash/shockwave movie
];

/**
 * Plugins for media file type handling.
 * Each entry in the array maps a MIME type to a class name
 *
 * Core media handlers are listed in MediaHandlerFactory,
 * and extensions should use extension.json.
 */
$wgMediaHandlers = [];

/**
 * Media handler overrides for parser tests (they don't need to generate actual
 * thumbnails, so a mock will do)
 */
$wgParserTestMediaHandlers = [
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
];

/**
 * Plugins for page content model handling.
 * Each entry in the array maps a model id to a class name or callback
 * that creates an instance of the appropriate ContentHandler subclass.
 *
 * @since 1.21
 */
$wgContentHandlers = [
	// the usual case
	CONTENT_MODEL_WIKITEXT => WikitextContentHandler::class,
	// dumb version, no syntax highlighting
	CONTENT_MODEL_JAVASCRIPT => JavaScriptContentHandler::class,
	// simple implementation, for use by extensions, etc.
	CONTENT_MODEL_JSON => JsonContentHandler::class,
	// dumb version, no syntax highlighting
	CONTENT_MODEL_CSS => CssContentHandler::class,
	// plain text, for use by extensions, etc.
	CONTENT_MODEL_TEXT => TextContentHandler::class,
];

/**
 * Whether to enable server-side image thumbnailing. If false, images will
 * always be sent to the client in full resolution, with appropriate width= and
 * height= attributes on the <img> tag for the client to do its own scaling.
 */
$wgUseImageResize = true;

/**
 * Resizing can be done using PHP's internal image libraries or using
 * ImageMagick or another third-party converter, e.g. GraphicMagick.
 * These support more file formats than PHP, which only supports PNG,
 * GIF, JPG, XBM and WBMP.
 *
 * Use Image Magick instead of PHP builtin functions.
 */
$wgUseImageMagick = false;

/**
 * The convert command shipped with ImageMagick
 */
$wgImageMagickConvertCommand = '/usr/bin/convert';

/**
 * Array of max pixel areas for interlacing per MIME type
 * @since 1.27
 */
$wgMaxInterlacingAreas = [];

/**
 * Sharpening parameter to ImageMagick
 */
$wgSharpenParameter = '0x0.4';

/**
 * Reduction in linear dimensions below which sharpening will be enabled
 */
$wgSharpenReductionThreshold = 0.85;

/**
 * Temporary directory used for ImageMagick. The directory must exist. Leave
 * this set to false to let ImageMagick decide for itself.
 */
$wgImageMagickTempDir = false;

/**
 * Use another resizing converter, e.g. GraphicMagick
 * %s will be replaced with the source path, %d with the destination
 * %w and %h will be replaced with the width and height.
 *
 * @par Example for GraphicMagick:
 * @code
 * $wgCustomConvertCommand = "gm convert %s -resize %wx%h %d"
 * @endcode
 *
 * Leave as false to skip this.
 */
$wgCustomConvertCommand = false;

/**
 * used for lossless jpeg rotation
 *
 * @since 1.21
 */
$wgJpegTran = '/usr/bin/jpegtran';

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
$wgJpegPixelFormat = 'yuv420';

/**
 * When scaling a JPEG thumbnail, this is the quality we request
 * from the backend. It should be an int between 1 and 100,
 * with 100 indicating 100% quality.
 *
 * @since 1.32
 */
$wgJpegQuality = 80;

/**
 * Some tests and extensions use exiv2 to manipulate the Exif metadata in some
 * image formats.
 */
$wgExiv2Command = '/usr/bin/exiv2';

/**
 * Path to exiftool binary. Used for lossless ICC profile swapping.
 *
 * @since 1.26
 */
$wgExiftool = '/usr/bin/exiftool';

/**
 * Scalable Vector Graphics (SVG) may be uploaded as images.
 * Since SVG support is not yet standard in browsers, it is
 * necessary to rasterize SVGs to PNG as a fallback format.
 *
 * An external program is required to perform this conversion.
 * If set to an array, the first item is a PHP callable and any further items
 * are passed as parameters after $srcPath, $dstPath, $width, $height
 */
$wgSVGConverters = [
	'ImageMagick' =>
		'$path/convert -background "#ffffff00" -thumbnail $widthx$height\! $input PNG:$output',
	'sodipodi' => '$path/sodipodi -z -w $width -f $input -e $output',
	'inkscape' => '$path/inkscape -z -w $width -f $input -e $output',
	'batik' => 'java -Djava.awt.headless=true -jar $path/batik-rasterizer.jar -w $width -d '
		. '$output $input',
	'rsvg' => '$path/rsvg-convert -w $width -h $height -o $output $input',
	'imgserv' => '$path/imgserv-wrapper -i svg -o png -w$width $input $output',
	'ImagickExt' => [ 'SvgHandler::rasterizeImagickExt' ],
];

/**
 * Pick a converter defined in $wgSVGConverters
 */
$wgSVGConverter = 'ImageMagick';

/**
 * If not in the executable PATH, specify the SVG converter path.
 */
$wgSVGConverterPath = '';

/**
 * Don't scale a SVG larger than this
 */
$wgSVGMaxSize = 5120;

/**
 * Don't read SVG metadata beyond this point.
 * Default is 1024*256 bytes
 */
$wgSVGMetadataCutoff = 262144;

/**
 * Whether thumbnails should be generated in target language (usually, same as
 * page language), if available.
 * Currently, applies only to SVG images that use the systemLanguage attribute
 * to specify text language.
 *
 * @since 1.33
 */
$wgMediaInTargetLanguage = true;

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
 * The default is 50 MB if decompressed to RGBA form, which corresponds to
 * 12.5 million pixels or 3500x3500.
 */
$wgMaxImageArea = 1.25e7;

/**
 * Force thumbnailing of animated GIFs above this size to a single
 * frame instead of an animated thumbnail.  As of MW 1.17 this limit
 * is checked against the total size of all frames in the animation.
 * It probably makes sense to keep this equal to $wgMaxImageArea.
 */
$wgMaxAnimatedGifArea = 1.25e7;

/**
 * Browsers don't support TIFF inline generally...
 * For inline display, we need to convert to PNG or JPEG.
 * Note scaling should work with ImageMagick, but may not with GD scaling.
 *
 * @par Example:
 * @code
 *  // PNG is lossless, but inefficient for photos
 *  $wgTiffThumbnailType = [ 'png', 'image/png' ];
 *  // JPEG is good for photos, but has no transparency support. Bad for diagrams.
 *  $wgTiffThumbnailType = [ 'jpg', 'image/jpeg' ];
 * @endcode
 */
$wgTiffThumbnailType = [];

/**
 * If rendered thumbnail files are older than this timestamp, they
 * will be rerendered on demand as if the file didn't already exist.
 * Update if there is some need to force thumbs and SVG rasterizations
 * to rerender, such as fixes to rendering bugs.
 */
$wgThumbnailEpoch = '20030516000000';

/**
 * Certain operations are avoided if there were too many recent failures,
 * for example, thumbnail generation. Bump this value to invalidate all
 * memory of failed operations and thus allow further attempts to resume.
 * This is useful when a cause for the failures has been found and fixed.
 */
$wgAttemptFailureEpoch = 1;

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
$wgIgnoreImageErrors = false;

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
 * @code
 *   $wgLocalFileRepo['transformVia404'] = true;
 * @endcode
 * @var bool
 * @since 1.7.0
 */
$wgGenerateThumbnailOnParse = true;

/**
 * Show thumbnails for old images on the image description page
 */
$wgShowArchiveThumbnails = true;

/**
 * If set to true, images that contain certain the exif orientation tag will
 * be rotated accordingly. If set to null, try to auto-detect whether a scaler
 * is available that can rotate.
 */
$wgEnableAutoRotation = null;

/**
 * Internal name of virus scanner. This serves as a key to the
 * $wgAntivirusSetup array. Set this to NULL to disable virus scanning. If not
 * null, every file uploaded will be scanned for viruses.
 */
$wgAntivirus = null;

/**
 * Configuration for different virus scanners. This an associative array of
 * associative arrays. It contains one setup array per known scanner type.
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
$wgAntivirusSetup = [

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
];

/**
 * Determines if a failed virus scan (AV_SCAN_FAILED) will cause the file to be rejected.
 */
$wgAntivirusRequired = true;

/**
 * Determines if the MIME type of uploaded files should be checked
 */
$wgVerifyMimeType = true;

/**
 * Determines whether extra checks for IE type detection should be applied.
 * This is a conservative check for exactly what IE 6 or so checked for,
 * and shouldn't trigger on for instance JPEG files containing links in EXIF
 * metadata.
 *
 * @since 1.34
 */
$wgVerifyMimeTypeIE = true;

/**
 * Sets the MIME type definition file to use by includes/libs/mime/MimeAnalyzer.php.
 * When this is set to the path of a mime.types file, MediaWiki will use this
 * file to map MIME types to file extensions and vice versa, in lieu of its
 * internal MIME map. Note that some MIME mappings are considered "baked in"
 * and cannot be overridden. See includes/libs/mime/MimeMapMinimal.php for a
 * full list.
 * example: $wgMimeTypeFile = '/etc/mime.types';
 */
$wgMimeTypeFile = 'internal';

/**
 * Sets the MIME type info file to use by includes/libs/mime/MimeAnalyzer.php.
 * Set to null to use the minimum set of built-in defaults only.
 */
$wgMimeInfoFile = 'internal';

/**
 * Sets an external MIME detector program. The command must print only
 * the MIME type to standard output.
 * The name of the file to process will be appended to the command given here.
 * If not set or NULL, PHP's mime_content_type function will be used.
 *
 * @par Example:
 * @code
 * #$wgMimeDetectorCommand = "file -bi"; # use external MIME detector (Linux)
 * @endcode
 */
$wgMimeDetectorCommand = null;

/**
 * Switch for trivial MIME detection. Used by thumb.php to disable all fancy
 * things, because only a few types of images are needed and file extensions
 * can be trusted.
 */
$wgTrivialMimeDetection = false;

/**
 * Additional XML types we can allow via MIME-detection.
 * array = [ 'rootElement' => 'associatedMimeType' ]
 */
$wgXMLMimeTypes = [
	'http://www.w3.org/2000/svg:svg' => 'image/svg+xml',
	'svg' => 'image/svg+xml',
	'http://www.lysator.liu.se/~alla/dia/:diagram' => 'application/x-dia-diagram',
	'http://www.w3.org/1999/xhtml:html' => 'text/html', // application/xhtml+xml?
	'html' => 'text/html', // application/xhtml+xml?
];

/**
 * Limit images on image description pages to a user-selectable limit. In order
 * to reduce disk usage, limits can only be selected from a list.
 * The user preference is saved as an array offset in the database, by default
 * the offset is set with $wgDefaultUserOptions['imagesize']. Make sure you
 * change it if you alter the array (see T10858).
 * This is the list of settings the user can choose from:
 */
$wgImageLimits = [
	[ 320, 240 ],
	[ 640, 480 ],
	[ 800, 600 ],
	[ 1024, 768 ],
	[ 1280, 1024 ]
];

/**
 * Adjust thumbnails on image pages according to a user setting. In order to
 * reduce disk usage, the values can only be selected from a list. This is the
 * list of settings the user can choose from:
 */
$wgThumbLimits = [
	120,
	150,
	180,
	200,
	250,
	300
];

/**
 * When defined, is an array of image widths used as buckets for thumbnail generation.
 * The goal is to save resources by generating thumbnails based on reference buckets instead of
 * always using the original. This will incur a speed gain but cause a quality loss.
 *
 * The buckets generation is chained, with each bucket generated based on the above bucket
 * when possible. File handlers have to opt into using that feature. For now only BitmapHandler
 * supports it.
 */
$wgThumbnailBuckets = null;

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
$wgThumbnailMinimumBucketDistance = 50;

/**
 * When defined, is an array of thumbnail widths to be rendered at upload time. The idea is to
 * prerender common thumbnail sizes, in order to avoid the necessity to render them on demand, which
 * has a performance impact for the first client to view a certain size.
 *
 * This obviously means that more disk space is needed per upload upfront.
 *
 * @since 1.25
 */

$wgUploadThumbnailRenderMap = [];

/**
 * The method through which the thumbnails will be prerendered for the entries in
 * $wgUploadThumbnailRenderMap
 *
 * The method can be either "http" or "jobqueue". The former uses an http request to hit the
 * thumbnail's URL.
 * This method only works if thumbnails are configured to be rendered by a 404 handler. The latter
 * option uses the job queue to render the thumbnail.
 *
 * @since 1.25
 */
$wgUploadThumbnailRenderMethod = 'jobqueue';

/**
 * When using the "http" $wgUploadThumbnailRenderMethod, lets one specify a custom Host HTTP header.
 *
 * @since 1.25
 */
$wgUploadThumbnailRenderHttpCustomHost = false;

/**
 * When using the "http" $wgUploadThumbnailRenderMethod, lets one specify a custom domain to send
 * the HTTP request to.
 *
 * @since 1.25
 */
$wgUploadThumbnailRenderHttpCustomDomain = false;

/**
 * When this variable is true and JPGs use the sRGB ICC profile, swaps it for the more lightweight
 * (and free) TinyRGB profile when generating thumbnails.
 *
 * @since 1.26
 */
$wgUseTinyRGBForJPGThumbnails = false;

/**
 * Parameters for the "<gallery>" tag.
 * Fields are:
 *   - imagesPerRow:   Default number of images per-row in the gallery. 0 -> Adapt to screensize
 *   - imageWidth:     Width of the cells containing images in galleries (in "px")
 *   - imageHeight:    Height of the cells containing images in galleries (in "px")
 *   - captionLength:  Length to truncate filename to in caption when using "showfilename".
 *                     A value of 'true' will truncate the filename to one line using CSS
 *                     and will be the behaviour after deprecation.
 *                     @deprecated since 1.28
 *   - showBytes:      Show the filesize in bytes in categories
 *   - showDimensions: Show the dimensions (width x height) in categories
 *   - mode:           Gallery mode
 */
$wgGalleryOptions = [];

/**
 * Adjust width of upright images when parameter 'upright' is used
 * This allows a nicer look for upright images without the need to fix the width
 * by hardcoded px in wiki sourcecode.
 */
$wgThumbUpright = 0.75;

/**
 * Default value for chmoding of new directories.
 */
$wgDirectoryMode = 0777;

/**
 * Generate and use thumbnails suitable for screens with 1.5 and 2.0 pixel densities.
 *
 * This means a 320x240 use of an image on the wiki will also generate 480x360 and 640x480
 * thumbnails, output via the srcset attribute.
 */
$wgResponsiveImages = true;

/**
 * On pages containing images, tell the user agent to pre-connect to hosts from
 * $wgForeignFileRepos.  This speeds up rendering, but may create unwanted
 * traffic if there are many possible URLs from which images are served.
 * @since 1.35
 * @warning EXPERIMENTAL!
 */
$wgImagePreconnect = false;

/**
 * @name DJVU settings
 * @{
 */

/**
 * Path of the djvudump executable
 * Enable this and $wgDjvuRenderer to enable djvu rendering
 * example: $wgDjvuDump = 'djvudump';
 */
$wgDjvuDump = null;

/**
 * Path of the ddjvu DJVU renderer
 * Enable this and $wgDjvuDump to enable djvu rendering
 * example: $wgDjvuRenderer = 'ddjvu';
 */
$wgDjvuRenderer = null;

/**
 * Path of the djvutxt DJVU text extraction utility
 * Enable this and $wgDjvuDump to enable text layer extraction from djvu files
 * example: $wgDjvuTxt = 'djvutxt';
 */
$wgDjvuTxt = null;

/**
 * Path of the djvutoxml executable
 * This works like djvudump except much, much slower as of version 3.5.
 *
 * For now we recommend you use djvudump instead. The djvuxml output is
 * probably more stable, so we'll switch back to it as soon as they fix
 * the efficiency problem.
 * https://sourceforge.net/tracker/index.php?func=detail&aid=1704049&group_id=32953&atid=406583
 *
 * @par Example:
 * @code
 * $wgDjvuToXML = 'djvutoxml';
 * @endcode
 */
$wgDjvuToXML = null;

/**
 * Shell command for the DJVU post processor
 * Default: pnmtojpeg, since ddjvu generates ppm output
 * Set this to false to output the ppm file directly.
 */
$wgDjvuPostProcessor = 'pnmtojpeg';

/**
 * File extension for the DJVU post processor output
 */
$wgDjvuOutputExtension = 'jpg';

/** @} */ # end of DJvu }

/** @} */ # end of file uploads }

/************************************************************************//**
 * @name   Email settings
 * @{
 */

/**
 * Site admin email address.
 *
 * Defaults to "wikiadmin@$wgServerName" (in Setup.php).
 */
$wgEmergencyContact = false;

/**
 * Sender email address for e-mail notifications.
 *
 * The address we use as sender when a user requests a password reminder,
 * as well as other e-mail notifications.
 *
 * Defaults to "apache@$wgServerName" (in Setup.php).
 */
$wgPasswordSender = false;

/**
 * Reply-To address for e-mail notifications.
 *
 * Defaults to $wgPasswordSender (in Setup.php).
 */
$wgNoReplyAddress = false;

/**
 * Set to true to enable the e-mail basic features:
 * Password reminders, etc. If sending e-mail on your
 * server doesn't work, you might want to disable this.
 */
$wgEnableEmail = true;

/**
 * Set to true to enable user-to-user e-mail.
 * This can potentially be abused, as it's hard to track.
 */
$wgEnableUserEmail = true;

/**
 * Set to true to enable the Special Mute page. This allows users
 * to mute unwanted communications from other users, and is linked
 * to from emails originating from Special:Email.
 *
 * @since 1.34
 * @deprecated 1.34
 */
$wgEnableSpecialMute = false;

/**
 * Set to true to enable user-to-user e-mail blacklist.
 *
 * @since 1.30
 */
$wgEnableUserEmailBlacklist = false;

/**
 * If true put the sending user's email in a Reply-To header
 * instead of From (false). ($wgPasswordSender will be used as From.)
 *
 * Some mailers (eg SMTP) set the SMTP envelope sender to the From value,
 * which can cause problems with SPF validation and leak recipient addresses
 * when bounces are sent to the sender. In addition, DMARC restrictions
 * can cause emails to fail to be received when false.
 */
$wgUserEmailUseReplyTo = true;

/**
 * Minimum time, in hours, which must elapse between password reminder
 * emails for a given account. This is to prevent abuse by mail flooding.
 */
$wgPasswordReminderResendTime = 24;

/**
 * The time, in seconds, when an emailed temporary password expires.
 */
$wgNewPasswordExpiry = 3600 * 24 * 7;

/**
 * The time, in seconds, when an email confirmation email expires
 */
$wgUserEmailConfirmationTokenExpiry = 7 * 24 * 60 * 60;

/**
 * The number of days that a user's password is good for. After this number of days, the
 * user will be asked to reset their password. Set to false to disable password expiration.
 */
$wgPasswordExpirationDays = false;

/**
 * If a user's password is expired, the number of seconds when they can still login,
 * and cancel their password change, but are sent to the password change form on each login.
 */
$wgPasswordExpireGrace = 3600 * 24 * 7; // 7 days

/**
 * SMTP Mode.
 *
 * For using a direct (authenticated) SMTP server connection.
 * Default to false or fill an array :
 *
 * @code
 * $wgSMTP = [
 *     'host'     => 'SMTP domain',
 *     'IDHost'   => 'domain for MessageID',
 *     'port'     => '25',
 *     'auth'     => [true|false],
 *     'username' => [SMTP username],
 *     'password' => [SMTP password],
 * ];
 * @endcode
 */
$wgSMTP = false;

/**
 * Additional email parameters, will be passed as the last argument to mail() call.
 */
$wgAdditionalMailParams = null;

/**
 * For parts of the system that have been updated to provide HTML email content, send
 * both text and HTML parts as the body of the email
 */
$wgAllowHTMLEmail = false;

/**
 * Allow sending of e-mail notifications with the editor's address as sender.
 *
 * This setting depends on $wgEnotifRevealEditorAddress also being enabled.
 * If both are enabled, notifications for actions from users that have opted-in,
 * will be sent to other users with their address as "From" instead of "Reply-To".
 *
 * If disabled, or not opted-in, notifications come from $wgPasswordSender.
 *
 * @var bool
 */
$wgEnotifFromEditor = false;

// TODO move UPO to preferences probably ?
# If set to true, users get a corresponding option in their preferences and can choose to
# enable or disable at their discretion
# If set to false, the corresponding input form on the user preference page is suppressed
# It call this to be a "user-preferences-option (UPO)"

/**
 * Require email authentication before sending mail to an email address.
 * This is highly recommended. It prevents MediaWiki from being used as an open
 * spam relay.
 */
$wgEmailAuthentication = true;

/**
 * Allow users to enable email notification ("enotif") on watchlist changes.
 */
$wgEnotifWatchlist = false;

/**
 * Allow users to enable email notification ("enotif") when someone edits their
 * user talk page.
 *
 * The owner of the user talk page must also have the 'enotifusertalkpages' user
 * preference set to true.
 */
$wgEnotifUserTalk = false;

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
 *
 * @var bool
 */
$wgEnotifRevealEditorAddress = false;

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
$wgEnotifMinorEdits = true;

/**
 * Send a generic mail instead of a personalised mail for each user.  This
 * always uses UTC as the time zone, and doesn't include the username.
 *
 * For pages with many users watching, this can significantly reduce mail load.
 * Has no effect when using sendmail rather than SMTP.
 */
$wgEnotifImpersonal = false;

/**
 * Maximum number of users to mail at once when using impersonal mail. Should
 * match the limit on your mail server.
 */
$wgEnotifMaxRecips = 500;

/**
 * Use real name instead of username in e-mail "from" field.
 */
$wgEnotifUseRealName = false;

/**
 * Array of usernames who will be sent a notification email for every change
 * which occurs on a wiki. Users will not be notified of their own changes.
 */
$wgUsersNotifiedOnAllChanges = [];

/** @} */ # end of email settings

/************************************************************************//**
 * @name   Database settings
 * @{
 */

/**
 * Current wiki database name
 *
 * Should be alphanumeric, without spaces nor hyphens.
 * This is used to determine the current/local wiki ID (WikiMap::getCurrentWikiDbDomain).
 *
 * This should still be set even if $wgLBFactoryConf is configured.
 */
$wgDBname = 'my_wiki';

/**
 * Current wiki database schema name
 *
 * Should be alphanumeric, without spaces nor hyphens.
 * This is used to determine the current/local wiki ID (WikiMap::getCurrentWikiDbDomain).
 *
 * This should still be set even if $wgLBFactoryConf is configured.
 */
$wgDBmwschema = null;

/**
 * Current wiki database table name prefix
 *
 * Should be alphanumeric, without spaces nor hyphens, preferably ending in an underscore.
 * This is used to determine the current/local wiki ID (WikiMap::getCurrentWikiDbDomain).
 *
 * This should still be set even if $wgLBFactoryConf is configured.
 */
$wgDBprefix = '';

/**
 * Database host name or IP address
 */
$wgDBserver = 'localhost';

/**
 * Database port number (for PostgreSQL and Microsoft SQL Server).
 */
$wgDBport = 5432;

/**
 * Database username
 */
$wgDBuser = 'wikiuser';

/**
 * Database user's password
 */
$wgDBpassword = '';

/**
 * Database type
 */
$wgDBtype = 'mysql';

/**
 * Whether to use SSL in DB connection.
 *
 * This setting is only used if $wgLBFactoryConf['class'] is set to
 * '\Wikimedia\Rdbms\LBFactorySimple' and $wgDBservers is an empty array; otherwise
 * the DBO_SSL flag must be set in the 'flags' option of the database
 * connection to achieve the same functionality.
 */
$wgDBssl = false;

/**
 * Whether to use compression in DB connection.
 *
 * This setting is only used $wgLBFactoryConf['class'] is set to
 * '\Wikimedia\Rdbms\LBFactorySimple' and $wgDBservers is an empty array; otherwise
 * the DBO_COMPRESS flag must be set in the 'flags' option of the database
 * connection to achieve the same functionality.
 */
$wgDBcompress = false;

/**
 * Separate username for maintenance tasks. Leave as null to use the default.
 */
$wgDBadminuser = null;

/**
 * Separate password for maintenance tasks. Leave as null to use the default.
 */
$wgDBadminpassword = null;

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
$wgSearchType = null;

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
$wgSearchTypeAlternatives = null;

/**
 * MySQL table options to use during installation or update
 */
$wgDBTableOptions = 'ENGINE=InnoDB, DEFAULT CHARSET=binary';

/**
 * SQL Mode - default is turning off all modes, including strict, if set.
 * null can be used to skip the setting for performance reasons and assume
 * DBA has done his best job.
 * String override can be used for some additional fun :-)
 */
$wgSQLMode = '';

/**
 * Default group to use when getting database connections.
 * Will be used as default query group in ILoadBalancer::getConnection.
 * @since 1.32
 */
$wgDBDefaultGroup = null;

/**
 * To override default SQLite data directory ($docroot/../data)
 */
$wgSQLiteDataDir = '';

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
 *
 * @deprecated since 1.21 In new code, use the $wiki parameter to LBFactory::getMainLB() to
 *   access remote databases. Using LBFactory::getMainLB() allows the shared database to
 *   reside on separate servers to the wiki's own database, with suitable
 *   configuration of $wgLBFactoryConf.
 */
$wgSharedDB = null;

/**
 * @see $wgSharedDB
 */
$wgSharedPrefix = false;

/**
 * @see $wgSharedDB
 * The installer will add 'actor' to this list for all new wikis.
 */
$wgSharedTables = [ 'user', 'user_properties' ];

/**
 * @see $wgSharedDB
 * @since 1.23
 */
$wgSharedSchema = false;

/**
 * Database load balancer
 * This is a two-dimensional array, an array of server info structures
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
 *                  - DBO_DEFAULT:    Transactionalize web requests and use autocommit otherwise
 *                  - DBO_DEBUG:      Equivalent of $wgDebugDumpSql
 *                  - DBO_SSL:        Use TLS connection encryption if available
 *                  - DBO_COMPRESS:   Use protocol compression with database connections
 *                  - DBO_PERSISTENT: Enables persistent database connections
 *
 *   - max lag:     (optional) Maximum replication lag before a replica DB goes out of rotation
 *   - is static:   (optional) Set to true if the dataset is static and no replication is used.
 *   - cliMode:     (optional) Connection handles will not assume that requests are short-lived
 *                  nor that INSERT..SELECT can be rewritten into a buffered SELECT and INSERT.
 *                  This is what DBO_DEFAULT uses to determine when a web request is present.
 *                  [Default: uses value of $wgCommandLineMode]
 *
 *   These and any other user-defined properties will be assigned to the mLBInfo member
 *   variable of the Database object.
 *
 * Leave at false to use the single-server variables above. If you set this
 * variable, the single-server variables will generally be ignored (except
 * perhaps in some command-line scripts).
 *
 * The first server listed in this array (with key 0) will be the master. The
 * rest of the servers will be replica DBs. To prevent writes to your replica DBs due to
 * accidental misconfiguration or MediaWiki bugs, set read_only=1 on all your
 * replica DBs in my.cnf. You can set read_only mode at runtime using:
 *
 * @code
 *     SET @@read_only=1;
 * @endcode
 *
 * Since the effect of writing to a replica DB is so damaging and difficult to clean
 * up, we at Wikimedia set read_only=1 in my.cnf on all our DB servers, even
 * our masters, and then set read_only=0 on masters at runtime.
 */
$wgDBservers = false;

/**
 * Load balancer factory configuration
 * To set up a multi-master wiki farm, set the class here to something that
 * can return a LoadBalancer with an appropriate master on a call to getMainLB().
 * The class identified here is responsible for reading $wgDBservers,
 * $wgDBserver, etc., so overriding it may cause those globals to be ignored.
 *
 * The LBFactoryMulti class is provided for this purpose, please see
 * includes/db/LBFactoryMulti.php for configuration information.
 */
$wgLBFactoryConf = [ 'class' => \Wikimedia\Rdbms\LBFactorySimple::class ];

/**
 * After a state-changing request is done by a client, this determines
 * how many seconds that client should keep using the master datacenter.
 * This avoids unexpected stale or 404 responses due to replication lag.
 * @since 1.27
 */
$wgDataCenterUpdateStickTTL = 10;

/**
 * File to log database errors to
 */
$wgDBerrorLog = false;

/**
 * Timezone to use in the error log.
 * Defaults to the wiki timezone ($wgLocaltimezone).
 *
 * A list of usable timezones can found at:
 * https://www.php.net/manual/en/timezones.php
 *
 * @par Examples:
 * @code
 * $wgDBerrorLogTZ = 'UTC';
 * $wgDBerrorLogTZ = 'GMT';
 * $wgDBerrorLogTZ = 'PST8PDT';
 * $wgDBerrorLogTZ = 'Europe/Sweden';
 * $wgDBerrorLogTZ = 'CET';
 * @endcode
 *
 * @since 1.20
 */
$wgDBerrorLogTZ = false;

/**
 * Other wikis on this site, can be administered from a single developer account.
 *
 * @var string[] List of wiki DB domain IDs; the format of each ID consist of 1-3 hyphen
 *   delimited alphanumeric components (each with no hyphens nor spaces) of any of the forms:
 *   - "<DB NAME>-<DB SCHEMA>-<TABLE PREFIX>"
 *   - "<DB NAME>-<TABLE PREFIX>"
 *   - "<DB NAME>"
 * If hyphens appear in any of the components, then the domain ID parsing may not work
 * in all cases and site functionality might be affected. If the schema ($wgDBmwschema)
 * is left to the default "mediawiki" for all wikis, then the schema should be omitted
 * from these IDs.
 */
$wgLocalDatabases = [];

/**
 * If lag is higher than $wgSlaveLagWarning, show a warning in some special
 * pages (like watchlist).  If the lag is higher than $wgSlaveLagCritical,
 * show a more obvious warning.
 */
$wgSlaveLagWarning = 10;

/**
 * @see $wgSlaveLagWarning
 */
$wgSlaveLagCritical = 30;

/** @} */ # End of DB settings }

/************************************************************************//**
 * @name   Text storage
 * @{
 */

/**
 * We can also compress text stored in the 'text' table. If this is set on, new
 * revisions will be compressed on page save if zlib support is available. Any
 * compressed revisions will be decompressed on load regardless of this setting,
 * but will not be readable at all* if zlib support is not available.
 */
$wgCompressRevisions = false;

/**
 * External stores allow including content
 * from non database sources following URL links.
 *
 * Short names of ExternalStore classes may be specified in an array here:
 * @code
 * $wgExternalStores = [ "http","file","custom" ]...
 * @endcode
 *
 * CAUTION: Access to database might lead to code execution
 */
$wgExternalStores = [];

/**
 * An array of external MySQL servers.
 *
 * @par Example:
 * Create a cluster named 'cluster1' containing three servers:
 * @code
 * $wgExternalServers = [
 *     'cluster1' => <array in the same format as $wgDBservers>
 * ];
 * @endcode
 *
 * Used by \Wikimedia\Rdbms\LBFactorySimple, may be ignored if $wgLBFactoryConf is set to
 * another class.
 */
$wgExternalServers = [];

/**
 * The place to put new revisions, false to put them in the local text table.
 * Part of a URL, e.g. DB://cluster1
 *
 * Can be an array instead of a single string, to enable data distribution. Keys
 * must be consecutive integers, starting at zero.
 *
 * @par Example:
 * @code
 * $wgDefaultExternalStore = [ 'DB://cluster1', 'DB://cluster2' ];
 * @endcode
 *
 * @var array
 */
$wgDefaultExternalStore = false;

/**
 * Revision text may be cached in $wgMemc to reduce load on external storage
 * servers and object extraction overhead for frequently-loaded revisions.
 *
 * Set to 0 to disable, or number of seconds before cache expiry.
 */
$wgRevisionCacheExpiry = 86400 * 7;

/** @} */ # end text storage }

/************************************************************************//**
 * @name   Performance hacks and limits
 * @{
 */

/**
 * Disable database-intensive features
 */
$wgMiserMode = false;

/**
 * Disable all query pages if miser mode is on, not just some
 */
$wgDisableQueryPages = false;

/**
 * Number of rows to cache in 'querycache' table when miser mode is on
 */
$wgQueryCacheLimit = 1000;

/**
 * Number of links to a page required before it is deemed "wanted"
 */
$wgWantedPagesThreshold = 1;

/**
 * Enable slow parser functions
 */
$wgAllowSlowParserFunctions = false;

/**
 * Allow schema updates
 */
$wgAllowSchemaUpdates = true;

/**
 * Maximum article size in kilobytes
 */
$wgMaxArticleSize = 2048;

/**
 * The minimum amount of memory that MediaWiki "needs"; MediaWiki will try to
 * raise PHP's memory limit if it's below this amount.
 */
$wgMemoryLimit = "50M";

/**
 * The minimum amount of time that MediaWiki needs for "slow" write request,
 * particularly ones with multiple non-atomic writes that *should* be as
 * transactional as possible; MediaWiki will call set_time_limit() if needed.
 * @since 1.26
 */
$wgTransactionalTimeLimit = 120;

/** @} */ # end performance hacks }

/************************************************************************//**
 * @name   Cache settings
 * @{
 */

/**
 * Directory for caching data in the local filesystem. Should not be accessible
 * from the web.
 *
 * Note: if multiple wikis share the same localisation cache directory, they
 * must all have the same set of extensions. You can set a directory just for
 * the localisation cache using $wgLocalisationCacheConf['storeDirectory'].
 */
$wgCacheDirectory = false;

/**
 * Main cache type. This should be a cache with fast access, but it may have
 * limited space. By default, it is disabled, since the stock database cache
 * is not fast enough to make it worthwhile.
 *
 * The options are:
 *
 *   - CACHE_ANYTHING:   Use anything, as long as it works
 *   - CACHE_NONE:       Do not cache
 *   - CACHE_DB:         Store cache objects in the DB
 *   - CACHE_MEMCACHED:  MemCached, must specify servers in $wgMemCachedServers
 *   - CACHE_ACCEL:      APC, APCU or WinCache
 *   - (other):          A string may be used which identifies a cache
 *                       configuration in $wgObjectCaches.
 *
 * @see $wgMessageCacheType, $wgParserCacheType
 */
$wgMainCacheType = CACHE_NONE;

/**
 * The cache type for storing the contents of the MediaWiki namespace. This
 * cache is used for a small amount of data which is expensive to regenerate.
 *
 * For available types see $wgMainCacheType.
 */
$wgMessageCacheType = CACHE_ANYTHING;

/**
 * The cache type for storing article HTML. This is used to store data which
 * is expensive to regenerate, and benefits from having plenty of storage space.
 *
 * For available types see $wgMainCacheType.
 */
$wgParserCacheType = CACHE_ANYTHING;

/**
 * The cache type for storing session data.
 *
 * For available types see $wgMainCacheType.
 */
$wgSessionCacheType = CACHE_ANYTHING;

/**
 * The cache type for storing language conversion tables,
 * which are used when parsing certain text and interface messages.
 *
 * For available types see $wgMainCacheType.
 *
 * @since 1.20
 */
$wgLanguageConverterCacheType = CACHE_ANYTHING;

/**
 * Advanced object cache configuration.
 *
 * Use this to define the class names and constructor parameters which are used
 * for the various cache types. Custom cache types may be defined here and
 * referenced from $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType,
 * or $wgLanguageConverterCacheType.
 *
 * The format is an associative array where the key is a cache identifier, and
 * the value is an associative array of parameters. The "class" parameter is the
 * class name which will be used. Alternatively, a "factory" parameter may be
 * given, giving a callable function which will generate a suitable cache object.
 */
$wgObjectCaches = [
	CACHE_NONE => [ 'class' => EmptyBagOStuff::class, 'reportDupes' => false ],
	CACHE_DB => [ 'class' => SqlBagOStuff::class, 'loggroup' => 'SQLBagOStuff' ],

	CACHE_ANYTHING => [ 'factory' => 'ObjectCache::newAnything' ],
	CACHE_ACCEL => [ 'factory' => 'ObjectCache::getLocalServerInstance' ],
	CACHE_MEMCACHED => [ 'class' => MemcachedPhpBagOStuff::class, 'loggroup' => 'memcached' ],

	'db-replicated' => [
		'class'        => ReplicatedBagOStuff::class,
		'readFactory'  => [
			'factory' => 'ObjectCache::newFromParams',
			'args'    => [ [ 'class' => SqlBagOStuff::class, 'replicaOnly' => true ] ]
		],
		'writeFactory' => [
			'factory' => 'ObjectCache::newFromParams',
			'args'    => [ [ 'class' => SqlBagOStuff::class, 'replicaOnly' => false ] ]
		],
		'loggroup'     => 'SQLBagOStuff',
		'reportDupes'  => false
	],
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
	'wincache' => [ 'class' => WinCacheBagOStuff::class, 'reportDupes' => false ],
];

/**
 * Main Wide-Area-Network cache type.
 *
 * By default, this will wrap $wgMainCacheType (which is disabled, since the basic
 * stock default of CACHE_DB is not fast enough to make it worthwhile).
 *
 * For single server or single data-center setup, setting $wgMainCacheType
 * is enough.
 *
 * For a multiple data-center setup, WANObjectCache should be configured to
 * broadcast some if its operations using Mcrouter or Dynomite.
 * See @ref wanobjectcache-deployment "Deploying WANObjectCache".
 *
 * The options are:
 *   - false:            Configure the cache using $wgMainCacheType, without using
 *                       a relayer (only matters if there are multiple data-centers)
 *   - CACHE_NONE:       Do not cache
 *   - (other):          A string may be used which identifies a cache
 *                       configuration in $wgWANObjectCaches
 * @since 1.26
 */
$wgMainWANCache = false;

/**
 * Advanced WAN object cache configuration.
 *
 * The format is an associative array where the key is an identifier
 * that may be referenced by $wgMainWANCache, and the value is an array of options:
 *
 *  - class:   (Required) The class to use (must be WANObjectCache or a subclass).
 *  - cacheId: (Required) A cache identifier from $wgObjectCaches.
 *  - secret:  (Optional) Stable secret for hashing long strings in key components.
 *             Default: $wgSecretKey.
 *
 * Any other options are treated as constructor parameters to WANObjectCache,
 * except for 'cache', 'logger', 'stats' and 'asyncHandler' which are
 * unconditionally set by MediaWiki core's ServiceWiring.
 *
 * @par Example:
 * @code
 * $wgWANObjectCaches'memcached-php' => [
 *   'class' => WANObjectCache::class,
 *   'cacheId' => 'memcached-php',
 * ];
 * @endcode
 *
 * @since 1.26
 */
$wgWANObjectCaches = [
	CACHE_NONE => [
		'class' => WANObjectCache::class,
		'cacheId' => CACHE_NONE,
	]
];

/**
 * Verify and enforce WAN cache purges using reliable DB sources as streams.
 *
 * These secondary cache purges are de-duplicated via simple cache mutexes.
 * This improves consistency when cache purges are lost, which becomes more likely
 * as more cache servers are added or if there are multiple datacenters. Only keys
 * related to important mutable content will be checked.
 *
 * @var bool
 * @since 1.29
 */
$wgEnableWANCacheReaper = false;

/**
 * The object store type of the main stash.
 *
 * This store should be a very fast storage system optimized for holding lightweight data
 * like incrementable hit counters and current user activity. The store should replicate the
 * dataset among all data-centers. Any add(), merge(), lock(), and unlock() operations should
 * maintain "best effort" linearizability; as long as connectivity is strong, latency is low,
 * and there is no eviction pressure prompted by low free space, those operations should be
 * linearizable. In terms of PACELC (https://en.wikipedia.org/wiki/PACELC_theorem), the store
 * should act as a PA/EL distributed system for these operations. One optimization for these
 * operations is to route them to a "primary" data-center (e.g. one that serves HTTP POST) for
 * synchronous execution and then replicate to the others asynchronously. This means that at
 * least calls to these operations during HTTP POST requests would quickly return.
 *
 * All other operations, such as get(), set(), delete(), changeTTL(), incr(), and decr(),
 * should be synchronous in the local data-center, replicating asynchronously to the others.
 * This behavior can be overriden by the use of the WRITE_SYNC and READ_LATEST flags.
 *
 * The store should *preferably* have eventual consistency to handle network partitions.
 *
 * Modules that rely on the stash should be prepared for:
 *   - add(), merge(), lock(), and unlock() to be slower than other write operations,
 *     at least in "secondary" data-centers (e.g. one that only serves HTTP GET/HEAD)
 *   - Other write operations to have race conditions accross data-centers
 *   - Read operations to have race conditions accross data-centers
 *   - Consistency to be either eventual (with Last-Write-Wins) or just "best effort"
 *
 * In general, this means avoiding updates during idempotent HTTP requests (GET/HEAD) and
 * avoiding assumptions of true linearizability (e.g. accepting anomalies). Modules that need
 * these kind of guarantees should use other storage mediums.
 *
 * Valid options are the keys of $wgObjectCaches, e.g. CACHE_* constants.
 *
 * @since 1.26
 */
$wgMainStash = 'db-replicated';

/**
 * The expiry time for the parser cache, in seconds.
 * The default is 86400 (one day).
 */
$wgParserCacheExpireTime = 86400;

/**
 * The expiry time to use for session storage, in seconds.
 */
$wgObjectCacheSessionExpiry = 3600;

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
 * @var string
 *  - 'enable': Integrate with PHP's session handling as much as possible.
 *  - 'warn': Integrate but log warnings if anything changes $_SESSION.
 *  - 'disable': Throw exceptions if PHP session handling is used.
 */
$wgPHPSessionHandling = 'enable';

/**
 * Number of internal PBKDF2 iterations to use when deriving session secrets.
 *
 * @since 1.28
 */
$wgSessionPbkdf2Iterations = 10001;

/**
 * The list of MemCached servers and port numbers
 */
$wgMemCachedServers = [ '127.0.0.1:11211' ];

/**
 * Use persistent connections to MemCached, which are shared across multiple
 * requests.
 */
$wgMemCachedPersistent = false;

/**
 * Read/write timeout for MemCached server communication, in microseconds.
 */
$wgMemCachedTimeout = 500000;

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
$wgUseLocalMessageCache = false;

/**
 * Instead of caching everything, only cache those messages which have
 * been customised in the site content language. This means that
 * MediaWiki:Foo/ja is ignored if MediaWiki:Foo doesn't exist.
 * This option is probably only useful for translatewiki.net.
 */
$wgAdaptiveMessageCache = false;

/**
 * Localisation cache configuration.
 *
 * Used by Language::getLocalisationCache() to decide how to construct the
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
$wgLocalisationCacheConf = [
	'class' => LocalisationCache::class,
	'store' => 'detect',
	'storeClass' => false,
	'storeDirectory' => false,
	'storeServer' => [],
	'forceRecache' => false,
	'manualRecache' => false,
];

/**
 * Allow client-side caching of pages
 */
$wgCachePages = true;

/**
 * Set this to current time to invalidate all prior cached pages. Affects both
 * client-side and server-side caching.
 * You can get the current date on your server by using the command:
 * @verbatim
 *   date +%Y%m%d%H%M%S
 * @endverbatim
 */
$wgCacheEpoch = '20030516000000';

/**
 * Directory where GitInfo will look for pre-computed cache files. If false,
 * $wgCacheDirectory/gitinfo will be used.
 */
$wgGitInfoCacheDirectory = false;

/**
 * This will cache static pages for non-logged-in users to reduce
 * database traffic on public sites. ResourceLoader requests to default
 * language and skins are cached as well as single module requests.
 */
$wgUseFileCache = false;

/**
 * Depth of the subdirectory hierarchy to be created under
 * $wgFileCacheDirectory.  The subdirectories will be named based on
 * the MD5 hash of the title.  A value of 0 means all cache files will
 * be put directly into the main file cache directory.
 */
$wgFileCacheDepth = 2;

/**
 * Append a configured value to the parser cache and the sitenotice key so
 * that they can be kept separate for some class of activity.
 */
$wgRenderHashAppend = '';

/**
 * If on, the sidebar navigation links are cached for users with the
 * current language set. This can save a touch of load on a busy site
 * by shaving off extra message lookups.
 *
 * However it is also fragile: changing the site configuration, or
 * having a variable $wgArticlePath, can produce broken links that
 * don't update as expected.
 */
$wgEnableSidebarCache = false;

/**
 * Expiry time for the sidebar cache, in seconds
 */
$wgSidebarCacheExpiry = 86400;

/**
 * Expiry time for the footer link cache, in seconds, or 0 if disabled
 *
 * @since 1.35
 */
$wgFooterLinkCacheExpiry = 0;

/**
 * When using the file cache, we can store the cached HTML gzipped to save disk
 * space. Pages will then also be served compressed to clients that support it.
 *
 * Requires zlib support enabled in PHP.
 */
$wgUseGzip = false;

/**
 * Invalidate various caches when LocalSettings.php changes. This is equivalent
 * to setting $wgCacheEpoch to the modification time of LocalSettings.php, as
 * was previously done in the default LocalSettings.php file.
 *
 * On high-traffic wikis, this should be set to false, to avoid the need to
 * check the file modification time, and to avoid the performance impact of
 * unnecessary cache invalidations.
 */
$wgInvalidateCacheOnLocalSettingsChange = true;

/**
 * When loading extensions through the extension registration system, this
 * can be used to invalidate the cache. A good idea would be to set this to
 * one file, you can just `touch` that one to invalidate the cache
 *
 * @par Example:
 * @code
 * $wgExtensionInfoMtime = filemtime( "$IP/LocalSettings.php" );
 * @endcode
 *
 * If set to false, the mtime for each individual JSON file will be checked,
 * which can be slow if a large number of extensions are being loaded.
 *
 * @var int|bool
 */
$wgExtensionInfoMTime = false;

/** @} */ # end of cache settings

/************************************************************************//**
 * @name   HTTP proxy (CDN) settings
 *
 * Many of these settings apply to any HTTP proxy used in front of MediaWiki,
 * although they are sometimes still referred to as Squid settings for
 * historical reasons.
 *
 * Achieving a high hit ratio with an HTTP proxy requires special configuration.
 * See https://www.mediawiki.org/wiki/Manual:Performance_tuning#Page_view_caching
 * for more details.
 *
 * @{
 */

/**
 * Enable/disable CDN.
 *
 * See https://www.mediawiki.org/wiki/Manual:Performance_tuning#Page_view_caching
 *
 * @since 1.34 Renamed from $wgUseSquid.
 */
$wgUseCdn = false;

/**
 * Add X-Forwarded-Proto to the Vary and Key headers for API requests and
 * RSS/Atom feeds. Use this if you have an SSL termination setup
 * and need to split the cache between HTTP and HTTPS for API requests,
 * feed requests and HTTP redirect responses in order to prevent cache
 * pollution. This does not affect 'normal' requests to index.php other than
 * HTTP redirects.
 */
$wgVaryOnXFP = false;

/**
 * Internal server name as known to CDN, if different.
 *
 * @par Example:
 * @code
 * $wgInternalServer = 'http://yourinternal.tld:8000';
 * @endcode
 */
$wgInternalServer = false;

/**
 * Cache TTL for the CDN sent as s-maxage (without ESI) or
 * Surrogate-Control (with ESI). Without ESI, you should strip
 * out s-maxage in the CDN config.
 *
 * 18000 seconds = 5 hours, more cache hits with 2678400 = 31 days.
 *
 * @since 1.34 Renamed from $wgSquidMaxage
 */
$wgCdnMaxAge = 18000;

/**
 * Cache timeout for the CDN when DB replica DB lag is high
 * @see $wgCdnMaxAge
 *
 * @since 1.27
 */
$wgCdnMaxageLagged = 30;

/**
 * Cache timeout when delivering a stale ParserCache response due to PoolCounter
 * contention.
 *
 * @since 1.35
 */
$wgCdnMaxageStale = 10;

/**
 * Cache TTL for the user agent sent as max-age, for logged out users.
 * Only applies if $wgUseCdn is false.
 * @see $wgUseCdn
 *
 * @since 1.35
 */
$wgLoggedOutMaxAge = 0;

/**
 * If set, any SquidPurge call on a URL or URLs will send a second purge no less than
 * this many seconds later via the job queue. This requires delayed job support.
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
$wgCdnReboundPurgeDelay = 0;

/**
 * Cache timeout for the CDN when a response is known to be wrong or incomplete (due to load)
 * @see $wgCdnMaxAge
 * @since 1.27
 */
$wgCdnMaxageSubstitute = 60;

/**
 * Default maximum age for raw CSS/JS accesses
 *
 * 300 seconds = 5 minutes.
 */
$wgForcedRawSMaxage = 300;

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
$wgCdnServers = [];

/**
 * As with $wgCdnServers, except these servers aren't purged on page changes;
 * use to set a list of trusted proxies, etc. Supports both individual IP
 * addresses and CIDR blocks.
 *
 * @since 1.23 Supports CIDR ranges
 * @since 1.34 Renamed from $wgSquidServersNoPurge
 */
$wgCdnServersNoPurge = [];

/**
 * Whether to use a Host header in purge requests sent to the proxy servers
 * configured in $wgCdnServers. Set this to false to support a CDN
 * configured in forward-proxy mode.
 *
 * If this is set to true, a Host header will be sent, and only the path
 * component of the URL will appear on the request line, as if the request
 * were a non-proxy HTTP 1.1 request. Varnish only supports this style of
 * request. Squid supports this style of request only if reverse-proxy mode
 * (http_port ... accel) is enabled.
 *
 * If this is set to false, no Host header will be sent, and the absolute URL
 * will be sent in the request line, as is the standard for an HTTP proxy
 * request in both HTTP 1.0 and 1.1. This style of request is not supported
 * by Varnish, but is supported by Squid in either configuration (forward or
 * reverse).
 *
 * @since 1.21
 * @deprecated since 1.33, will always be true in a future release.
 */
$wgSquidPurgeUseHostHeader = true;

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
 * @par Example configuration to send purges for upload.wikimedia.org to one
 * multicast group and all other purges to another:
 * @code
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
 * @endcode
 *
 * You can also pass an array of hosts to send purges too. This is useful when
 * you have several multicast groups or unicast address that should receive a
 * given purge.  Multiple hosts support was introduced in MediaWiki 1.22.
 *
 * @par Example of sending purges to multiple hosts:
 * @code
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
 * @endcode
 *
 * @since 1.22
 * @see $wgHTCPMulticastTTL
 */
$wgHTCPRouting = [];

/**
 * HTCP multicast TTL.
 * @see $wgHTCPRouting
 */
$wgHTCPMulticastTTL = 1;

/**
 * Should forwarded Private IPs be accepted?
 */
$wgUsePrivateIPs = false;

/** @} */ # end of HTTP proxy settings

/************************************************************************//**
 * @name   Language, regional and character encoding settings
 * @{
 */

/**
 * Site language code. See languages/data/Names.php for languages supported by
 * MediaWiki out of the box. Not all languages listed there have translations,
 * see languages/messages/ for the list of languages with some localisation.
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
$wgLanguageCode = 'en';

/**
 * Language cache size, or really how many languages can we handle
 * simultaneously without degrading to crawl speed.
 */
$wgLangObjCacheSize = 10;

/**
 * Some languages need different word forms, usually for different cases.
 * Used in Language::convertGrammar().
 *
 * @par Example:
 * @code
 * $wgGrammarForms['en']['genitive']['car'] = 'car\'s';
 * @endcode
 */
$wgGrammarForms = [];

/**
 * Treat language links as magic connectors, not inline links
 */
$wgInterwikiMagic = true;

/**
 * Hide interlanguage links from the sidebar
 */
$wgHideInterlanguageLinks = false;

/**
 * List of additional interwiki prefixes that should be treated as
 * interlanguage links (i.e. placed in the sidebar).
 * Notes:
 * - This will not do anything unless the prefixes are defined in the interwiki
 *   map.
 * - The display text for these custom interlanguage links will be fetched from
 *   the system message "interlanguage-link-xyz" where xyz is the prefix in
 *   this array.
 * - A friendly name for each site, used for tooltip text, may optionally be
 *   placed in the system message "interlanguage-link-sitename-xyz" where xyz is
 *   the prefix in this array.
 */
$wgExtraInterlanguageLinkPrefixes = [];

/**
 * Map of interlanguage link codes to language codes. This is useful to override
 * what is shown as the language name when the interwiki code does not match it
 * exactly
 *
 * @since 1.35
 */
$wgInterlanguageLinkCodeMap = [];

/**
 * List of language names or overrides for default names in Names.php
 */
$wgExtraLanguageNames = [];

/**
 * List of mappings from one language code to another.
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
$wgExtraLanguageCodes = [
	// Language codes of macro languages, which get mapped to the main language
	'bh' => 'bho', // Bihari language family
	'no' => 'nb', // Norwegian language family

	// Language variants which get mapped to the main language
	'simple' => 'en', // Simple English
];

/**
 * Functionally the same as $wgExtraLanguageCodes, but deprecated. Instead of
 * appending values to this array, append them to $wgExtraLanguageCodes.
 *
 * @deprecated since 1.29
 */
$wgDummyLanguageCodes = [];

/**
 * Set this to always convert certain Unicode sequences to modern ones
 * regardless of the content language. This has a small performance
 * impact.
 *
 * @since 1.17
 */
$wgAllUnicodeFixes = false;

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
$wgLegacyEncoding = false;

/**
 * If set to true, the MediaWiki 1.4 to 1.5 schema conversion will
 * create stub reference rows in the text table instead of copying
 * the full text of all current entries from 'cur' to 'text'.
 *
 * This will speed up the conversion step for large sites, but
 * requires that the cur table be kept around for those revisions
 * to remain viewable.
 *
 * This option affects the updaters *only*. Any present cur stub
 * revisions will be readable at runtime regardless of this setting.
 */
$wgLegacySchemaConversion = false;

/**
 * Enable dates like 'May 12' instead of '12 May', if the default date format
 * is 'dmy or mdy'.
 */
$wgAmericanDates = false;

/**
 * For Hindi and Arabic use local numerals instead of Western style (0-9)
 * numerals in interface.
 */
$wgTranslateNumerals = true;

/**
 * Translation using MediaWiki: namespace.
 * Interface messages will be loaded from the database.
 */
$wgUseDatabaseMessages = true;

/**
 * Maximum entry size in the message cache, in bytes
 */
$wgMaxMsgCacheEntrySize = 10000;

/**
 * Whether to enable language variant conversion.
 */
$wgDisableLangConversion = false;

/**
 * Whether to enable language variant conversion for links.
 */
$wgDisableTitleConversion = false;

/**
 * Default variant code, if false, the default will be the language code
 */
$wgDefaultLanguageVariant = false;

/**
 * Whether to enable the pig Latin variant of English (en-x-piglatin),
 * used to ease variant development work.
 */
$wgUsePigLatinVariant = false;

/**
 * Disabled variants array of language variant conversion.
 *
 * @par Example:
 * @code
 *  $wgDisabledVariants[] = 'zh-mo';
 *  $wgDisabledVariants[] = 'zh-my';
 * @endcode
 */
$wgDisabledVariants = [];

/**
 * Like $wgArticlePath, but on multi-variant wikis, this provides a
 * path format that describes which parts of the URL contain the
 * language variant.
 *
 * @par Example:
 * @code
 *     $wgLanguageCode = 'sr';
 *     $wgVariantArticlePath = '/$2/$1';
 *     $wgArticlePath = '/wiki/$1';
 * @endcode
 *
 * A link to /wiki/ would be redirected to /sr/Главна_страна
 *
 * It is important that $wgArticlePath not overlap with possible values
 * of $wgVariantArticlePath.
 */
$wgVariantArticlePath = false;

/**
 * Show a bar of language selection links in the user login and user
 * registration forms; edit the "loginlanguagelinks" message to
 * customise these.
 */
$wgLoginLanguageSelector = false;

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
 * @par Example:
 * To allow language-specific main page and community
 * portal:
 * @code
 *     $wgForceUIMsgAsContentMsg = [ 'mainpage', 'portal-url' ];
 * @endcode
 */
$wgForceUIMsgAsContentMsg = [];

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
 * @par Examples:
 * @code
 * $wgLocaltimezone = 'UTC';
 * $wgLocaltimezone = 'GMT';
 * $wgLocaltimezone = 'PST8PDT';
 * $wgLocaltimezone = 'Europe/Sweden';
 * $wgLocaltimezone = 'CET';
 * @endcode
 */
$wgLocaltimezone = null;

/**
 * Set an offset from UTC in minutes to use for the default timezone setting
 * for anonymous users and new user accounts.
 *
 * This setting is used for most date/time displays in the software, and is
 * overridable in user preferences. It is *not* used for signature timestamps.
 *
 * By default, this will be set to match $wgLocaltimezone.
 */
$wgLocalTZoffset = null;

/**
 * List of Unicode characters for which capitalization is overridden in
 * Language::ucfirst. The characters should be
 * represented as char_to_convert => conversion_override. See T219279 for details
 * on why this is useful during php version transitions.
 *
 * @warning: EXPERIMENTAL!
 *
 * @since 1.34
 * @var array
 */
$wgOverrideUcfirstCharacters = [];

/** @} */ # End of language/charset settings

/*************************************************************************//**
 * @name   Output format and skin settings
 * @{
 */

/**
 * The default Content-Type header.
 */
$wgMimeType = 'text/html';

/**
 * Defines the value of the version attribute in the &lt;html&gt; tag, if any.
 *
 * If your wiki uses RDFa, set it to the correct value for RDFa+HTML5.
 * Correct current values are 'HTML+RDFa 1.0' or 'XHTML+RDFa 1.0'.
 * See also https://www.w3.org/TR/rdfa-in-html/#document-conformance
 * @since 1.16
 */
$wgHtml5Version = null;

/**
 * Temporary variable that allows HTMLForms to be rendered as tables.
 * Table based layouts cause various issues when designing for mobile.
 * This global allows skins or extensions a means to force non-table based rendering.
 * Setting to false forces form components to always render as div elements.
 * @since 1.24
 */
$wgHTMLFormAllowTableFormat = true;

/**
 * Temporary variable that applies MediaWiki UI wherever it can be supported.
 * Temporary variable that should be removed when mediawiki ui is more
 * stable and change has been communicated.
 * @since 1.24
 */
$wgUseMediaWikiUIEverywhere = false;

/**
 * Whether to label the store-to-database-and-show-to-others button in the editor
 * as "Save page"/"Save changes" if false (the default) or, if true, instead as
 * "Publish page"/"Publish changes".
 *
 * @since 1.28
 */
$wgEditSubmitButtonLabelPublish = false;

/**
 * Permit other namespaces in addition to the w3.org default.
 *
 * Use the prefix for the key and the namespace for the value.
 *
 * @par Example:
 * @code
 * $wgXhtmlNamespaces['svg'] = 'http://www.w3.org/2000/svg';
 * @endcode
 * Normally we wouldn't have to define this in the root "<html>"
 * element, but IE needs it there in some circumstances.
 *
 * This is ignored if $wgMimeType is set to a non-XML MIME type.
 */
$wgXhtmlNamespaces = [];

/**
 * Site notice shown at the top of each page
 *
 * MediaWiki:Sitenotice page, which will override this. You can also
 * provide a separate message for logged-out users using the
 * MediaWiki:Anonnotice page.
 */
$wgSiteNotice = '';

/**
 * Default skin, for new users and anonymous visitors. Registered users may
 * change this to any one of the other available skins in their preferences.
 */
$wgDefaultSkin = 'vector';

/**
 * Fallback skin used when the skin defined by $wgDefaultSkin can't be found.
 *
 * @since 1.24
 */
$wgFallbackSkin = 'fallback';

/**
 * Specify the names of skins that should not be presented in the list of
 * available skins in user preferences.
 *
 * NOTE: This does not uninstall the skin, and it will still be accessible
 * via the `useskin` query parameter. To uninstall a skin, remove its inclusion
 * from LocalSettings.php.
 *
 * @see Skin::getAllowedSkins
 */
$wgSkipSkins = [];

/**
 * Allow user Javascript page?
 * This enables a lot of neat customizations, but may
 * increase security risk to users and server load.
 */
$wgAllowUserJs = false;

/**
 * Allow user Cascading Style Sheets (CSS)?
 * This enables a lot of neat customizations, but may
 * increase security risk to users and server load.
 */
$wgAllowUserCss = false;

/**
 * Allow style-related user-preferences?
 *
 * This controls whether the `editfont` and `underline` preferences
 * are available to users.
 */
$wgAllowUserCssPrefs = true;

/**
 * Use the site's Javascript page?
 */
$wgUseSiteJs = true;

/**
 * Use the site's Cascading Style Sheets (CSS)?
 */
$wgUseSiteCss = true;

/**
 * Break out of framesets. This can be used to prevent clickjacking attacks,
 * or to prevent external sites from framing your site with ads.
 */
$wgBreakFrames = false;

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
$wgEditPageFrameOptions = 'DENY';

/**
 * Disallow framing of API pages directly, by setting the X-Frame-Options
 * header. Since the API returns CSRF tokens, allowing the results to be
 * framed can compromise your user's account security.
 * Options are:
 *   - 'DENY': Do not allow framing. This is recommended for most wikis.
 *   - 'SAMEORIGIN': Allow framing by pages on the same domain.
 *   - false: Allow all framing.
 * Note: $wgBreakFrames will override this for human formatted API output.
 */
$wgApiFrameOptions = 'DENY';

/**
 * Disable output compression (enabled by default if zlib is available)
 */
$wgDisableOutputCompression = false;

/**
 * How should section IDs be encoded?
 * This array can contain 1 or 2 elements, each of them can be one of:
 * - 'html5'  is modern HTML5 style encoding with minimal escaping. Displays Unicode
 *            characters in most browsers' address bars.
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
$wgFragmentMode = [ 'legacy', 'html5' ];

/**
 * Which ID escaping mode should be used for external interwiki links? See documentation
 * for $wgFragmentMode above for details of each mode. Because you can't control external sites,
 * this setting should probably always be 'legacy', unless every wiki you link to has converted
 * to 'html5'.
 *
 * @since 1.30
 */
$wgExternalInterwikiFragmentMode = 'legacy';

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
 * directly as html, however some skins may choose to ignore it. An array is the preferred format
 * for the icon, the following keys are used:
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
 * @todo Reformat documentation.
 */
$wgFooterIcons = [
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
		]
	],
];

/**
 * Login / create account link behavior when it's possible for anonymous users
 * to create an account.
 *  - true = use a combined login / create account link
 *  - false = split login and create account into two separate links
 */
$wgUseCombinedLoginLink = false;

/**
 * Display user edit counts in various prominent places.
 */
$wgEdititis = false;

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
$wgSend404Code = true;

/**
 * The $wgShowRollbackEditCount variable is used to show how many edits can be rolled back.
 * The numeric value of the variable controls how many edits MediaWiki will look back to
 * determine whether a rollback is allowed (by checking that they are all from the same author).
 * If the value is false or 0, the edits are not counted. Disabling this will prevent MediaWiki
 * from hiding some useless rollback links.
 *
 * @since 1.20
 */
$wgShowRollbackEditCount = 10;

/**
 * Output a <link rel="canonical"> tag on every page indicating the canonical
 * server which should be used, i.e. $wgServer or $wgCanonicalServer. Since
 * detection of the current server is unreliable, the link is sent
 * unconditionally.
 */
$wgEnableCanonicalServerLink = false;

/**
 * When OutputHandler is used, mangle any output that contains
 * <cross-domain-policy>. Without this, an attacker can send their own
 * cross-domain policy unless it is prevented by the crossdomain.xml file at
 * the domain root.
 *
 * @since 1.25
 */
$wgMangleFlashPolicy = true;

/** @} */ # End of output format settings }

/*************************************************************************//**
 * @name   ResourceLoader settings
 * @{
 */

/**
 * Define extra client-side modules to be registered with ResourceLoader.
 *
 * NOTE: It is recommended to define modules in extension.json or skin.json
 * whenever possible.
 *
 * ## Using resource modules
 *
 * By default modules are registered as an instance of ResourceLoaderFileModule.
 * You find the relevant code in resources/Resources.php. These are the options:
 *
 * ### class
 *
 * Alternate subclass of ResourceLoaderModule (rather than default ResourceLoaderFileModule).
 * If this is used, some of the other properties may not apply, and you can specify your
 * own arguments. Since MediaWiki 1.30, it may now specify a callback function as an
 * alternative to a plain class name, using the factory key in the module description array.
 * This allows dependency injection to be used for %ResourceLoader modules.
 *
 * Class name of alternate subclass
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'class' => ResourceLoaderWikiModule::class,
 *   ];
 * @endcode
 *
 * ### debugScripts
 *
 * Scripts to include in debug contexts.
 *
 * %File path string or array of file path strings.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'debugScripts' => 'resources/MyExtension/debugMyExt.js',
 *   ];
 * @endcode
 *
 * ### dependencies
 *
 * Modules which must be loaded before this module.
 *
 * Module name string or array of module name strings.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'dependencies' => [ 'jquery.cookie', 'mediawiki.util' ],
 *   ];
 * @endcode
 *
 * ### deprecated
 *
 * Whether the module is deprecated and usage is discouraged.
 *
 * Either a boolean, or a string or an object with key message can be used to customise
 * deprecation message.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'deprecated' => 'You should use ext.myExtension2 instead',
 *   ];
 * @endcode
 *
 * ### group
 *
 * Group which this module should be loaded together with.
 *
 * Group name string.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'group' => 'myExtGroup',
 *   ];
 * @endcode
 *
 * See also
 * [Groups](https://www.mediawiki.org/wiki/Special:MyLanguage/ResourceLoader/Features#Groups)
 * on mediawiki.org.
 *
 * ### languageScripts
 *
 * Scripts to include in specific language contexts. See the scripts option below for an
 * example.
 *
 * Array keyed by language code containing file path string or array of file path strings.
 *
 * ### localBasePath
 *
 * Base path to prepend to all local paths in $options. Defaults to $IP.
 *
 * Base path string
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'localBasePath' => __DIR__,
 *   ];
 * @endcode
 *
 * ### messages
 *
 * Messages to always load
 *
 * Array of message key strings.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'messages' => [
 *       'searchsuggest-search',
 *       'searchsuggest-containing',
 *     ],
 *   ];
 * @endcode
 *
 * ### noflip
 *
 * Whether to skip CSSJanus LTR-to-RTL flipping for this module. Recommended for styles
 * imported from libraries that already properly handle their RTL styles. Default is false,
 * meaning CSSJanus will be applied on RTL-mode output.
 *
 * Boolean.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'noflip' => true,
 *   ];
 * @endcode
 *
 * ### packageFiles
 *
 * Package files that can be require()d. 'packageFiles' cannot be combined with any of the
 * scripts options: 'scripts', 'languageScripts' and 'debugScripts'.
 *
 * String or array of package file.
 *
 * ### remoteBasePath
 *
 * Base path to prepend to all remote paths in $options. Defaults to $wgScriptPath.
 * Cannot be combined with remoteExtPath.
 *
 * Base path string
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'remoteBasePath' => '/w/extensions/MyExtension',
 *   ];
 * @endcode
 *
 * ### remoteExtPath
 *
 * Equivalent of remoteBasePath, but relative to $wgExtensionAssetsPath. Cannot be
 * combined with remoteBasePath
 *
 * Base path string
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'remoteExtPath' => 'MyExtension',
 *   ];
 * @endcode
 *
 * ### scripts
 *
 * Scripts to always include.
 *
 * %File path string or array of file path strings.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'languageScripts' => [
 *       'bs' => 'extensions/MyExtension/languages/bs.js',
 *       'fi' => 'extensions/MyExtension/languages/fi.js',
 *     ],
 *     'scripts' => [
 *       'extensions/MyExtension/myExtension.js',
 *       'extensions/MyExtension/utils.js',
 *     ],
 *   ];
 * @endcode
 *
 * ### skinScripts
 *
 * Scripts to include in specific skin contexts.
 *
 * Array keyed by skin name containing file path string or array of file path strings.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'skinScripts' => [
 *       'default' => 'extensions/MyExtension/default-skin-overrides.js',
 *     ],
 *   ];
 * @endcode
 *
 * ### skinStyles
 *
 * Styles to include in specific skin contexts. (mapping of skin name to style(s))
 * See $wgResourceModuleSkinStyles below for an example.
 *
 * Array keyed by skin name containing file path string or array of file path strings.
 *
 * ### skipFunction
 *
 * Function that returns true when the module should be skipped. Intended for when the
 * module provides a polyfill that is not required in modern browsers
 *
 * Filename of a JavaScript file with a top-level return (it should not be wrapped in a
 * function).
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'skipFunction' => 'myext-polyfill-needed.js',
 *   ];
 * @endcode
 *
 * ### styles
 *
 * Styles to always include in the module.
 *
 * %File path string or list of file path strings. The included file can be automatically
 * wrapped in a @media query by specifying the file path as the key in an object, with
 * the value specifying the media query.
 *
 * See $wgResourceModuleSkinStyles below for additional examples.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['example'] = [
 *     'styles' => [
 *       'foo.css',
 *       'bar.css',
 *     ],
 *   ];
 *   $wgResourceModules['example.media'] = [
 *     'styles' => [
 *       'foo.css' => [ 'media' => 'print' ],
 *   ];
 *   $wgResourceModules['example.mixed'] = [
 *     'styles' => [
 *       'foo.css',
 *       'bar.css' => [ 'media' => 'print' ],
 *     ],
 *   ];
 * @endcode
 *
 * ### targets
 *
 * %ResourceLoader target the module can run on.
 *
 * String or array of targets.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'targets' => [ 'desktop', 'mobile' ],
 *   ];
 * @endcode
 *
 * ### templates
 *
 * Templates to be loaded for client-side usage.
 *
 * Object or array of templates.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *     'templates' => [
 *       'templates/template.html',
 *       'templates/template2.html',
 *     ],
 *   ];
 * @endcode
 */
$wgResourceModules = [];

/**
 * Add extra skin-specific styles to a resource module.
 *
 * These are automatically added by ResourceLoader to the 'skinStyles' list of
 * the existing module. The 'styles' list cannot be modified or disabled.
 *
 * For example, below a module "bar" is defined and skin Foo provides additional
 * styles for it:
 *
 * @par Example:
 * @code
 *   $wgResourceModules['bar'] = [
 *     'scripts' => 'resources/bar/bar.js',
 *     'styles' => 'resources/bar/main.css',
 *   ];
 *
 *   $wgResourceModuleSkinStyles['foo'] = [
 *     'bar' => 'skins/Foo/bar.css',
 *   ];
 * @endcode
 *
 * This is effectively equivalent to:
 *
 * @par Equivalent:
 * @code
 *   $wgResourceModules['bar'] = [
 *     'scripts' => 'resources/bar/bar.js',
 *     'styles' => 'resources/bar/main.css',
 *     'skinStyles' => [
 *       'foo' => skins/Foo/bar.css',
 *     ],
 *   ];
 * @endcode
 *
 * If the module already defines its own entry in `skinStyles` for a given skin, then
 * $wgResourceModuleSkinStyles is ignored.
 *
 * If a module defines a `skinStyles['default']` the skin may want to extend that instead
 * of replacing it. This can be done using the `+` prefix.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['bar'] = [
 *     'scripts' => 'resources/bar/bar.js',
 *     'styles' => 'resources/bar/basic.css',
 *     'skinStyles' => [
 *      'default' => 'resources/bar/additional.css',
 *     ],
 *   ];
 *   // Note the '+' character:
 *   $wgResourceModuleSkinStyles['foo'] = [
 *     '+bar' => 'skins/Foo/bar.css',
 *   ];
 * @endcode
 *
 * This is effectively equivalent to:
 *
 * @par Equivalent:
 * @code
 *   $wgResourceModules['bar'] = [
 *     'scripts' => 'resources/bar/bar.js',
 *     'styles' => 'resources/bar/basic.css',
 *     'skinStyles' => [
 *       'default' => 'resources/bar/additional.css',
 *       'foo' => [
 *         'resources/bar/additional.css',
 *         'skins/Foo/bar.css',
 *       ],
 *     ],
 *   ];
 * @endcode
 *
 * In other words, as a module author, use the `styles` list for stylesheets that may not be
 * disabled by a skin. To provide default styles that may be extended or replaced,
 * use `skinStyles['default']`.
 *
 * As with $wgResourceModules, always set the localBasePath and remoteBasePath
 * keys (or one of remoteExtPath/remoteSkinPath).
 *
 * @par Example:
 * @code
 *   $wgResourceModuleSkinStyles['foo'] = [
 *     'bar' => 'bar.css',
 *     'quux' => 'quux.css',
 *     'remoteSkinPath' => 'Foo',
 *     'localBasePath' => __DIR__,
 *   ];
 * @endcode
 */
$wgResourceModuleSkinStyles = [];

/**
 * Extensions should register foreign module sources here. 'local' is a
 * built-in source that is not in this array, but defined by
 * ResourceLoader::__construct() so that it cannot be unset.
 *
 * @par Example:
 * @code
 *   $wgResourceLoaderSources['foo'] = 'http://example.org/w/load.php';
 * @endcode
 */
$wgResourceLoaderSources = [];

/**
 * The default 'remoteBasePath' value for instances of ResourceLoaderFileModule.
 * Defaults to $wgScriptPath.
 */
$wgResourceBasePath = null;

/**
 * Maximum time in seconds to cache resources served by ResourceLoader.
 * Used to set last modified headers (max-age/s-maxage).
 *
 * Following options to distinguish:
 * - versioned: Used for modules with a version, because changing version
 *   numbers causes cache misses. This normally has a long expiry time.
 * - unversioned: Used for modules without a version to propagate changes
 *   quickly to clients. Also used for modules with errors to recover quickly.
 *   This normally has a short expiry time.
 *
 * Expiry time for the options to distinguish:
 * - server: Squid/Varnish but also any other public proxy cache between the
 *   client and MediaWiki.
 * - client: On the client side (e.g. in the browser cache).
 */
$wgResourceLoaderMaxage = [
	'versioned' => 30 * 24 * 60 * 60, // 30 days
	'unversioned' => 5 * 60, // 5 minutes
];

/**
 * Use the main stash instead of the module_deps table for indirect dependency tracking
 *
 * @since 1.35
 * @warning EXPERIMENTAL
 */
$wgResourceLoaderUseObjectCacheForDeps = false;

/**
 * The default debug mode (on/off) for of ResourceLoader requests.
 *
 * This will still be overridden when the debug URL parameter is used.
 */
$wgResourceLoaderDebug = false;

/**
 * Whether to ensure the mediawiki.legacy library is loaded before other modules.
 *
 * @deprecated since 1.26: Always declare dependencies.
 */
$wgIncludeLegacyJavaScript = false;

/**
 * Whether to load the jquery.migrate library.
 *
 * This provides jQuery 1.12 features that were removed in jQuery 3.0.
 * See also <https://jquery.com/upgrade-guide/3.0/> and
 * <https://phabricator.wikimedia.org/T280944>.
 *
 * @since 1.35.3
 */
$wgIncludejQueryMigrate = true;

/**
 * Whether or not to assign configuration variables to the global window object.
 *
 * If this is set to false, old code using deprecated variables will no longer
 * work.
 *
 * @par Example of legacy code:
 * @code{,js}
 *     if ( window.wgRestrictionEdit ) { ... }
 * @endcode
 * or:
 * @code{,js}
 *     if ( wgIsArticle ) { ... }
 * @endcode
 *
 * Instead, one needs to use mw.config.
 * @par Example using mw.config global configuration:
 * @code{,js}
 *     if ( mw.config.exists('wgRestrictionEdit') ) { ... }
 * @endcode
 * or:
 * @code{,js}
 *     if ( mw.config.get('wgIsArticle') ) { ... }
 * @endcode
 */
$wgLegacyJavaScriptGlobals = false;

/**
 * ResourceLoader will not generate URLs whose query string is more than
 * this many characters long, and will instead use multiple requests with
 * shorter query strings. Using multiple requests may degrade performance,
 * but may be needed based on the query string limit supported by your web
 * server and/or your user's web browsers.
 *
 * Default: `2000`.
 *
 * @see ResourceLoaderStartUpModule::getMaxQueryLength
 * @since 1.17
 * @var int
 */
$wgResourceLoaderMaxQueryLength = false;

/**
 * If set to true, JavaScript modules loaded from wiki pages will be parsed
 * prior to minification to validate it.
 *
 * Parse errors will result in a JS exception being thrown during module load,
 * which avoids breaking other modules loaded in the same request.
 */
$wgResourceLoaderValidateJS = true;

/**
 * When enabled, execution of JavaScript modules is profiled client-side.
 *
 * Instrumentation happens in mw.loader.profiler.
 * Use `mw.inspect('time')` from the browser console to display the data.
 *
 * @since 1.32
 */
$wgResourceLoaderEnableJSProfiler = false;

/**
 * Whether ResourceLoader should attempt to persist modules in localStorage on
 * browsers that support the Web Storage API.
 */
$wgResourceLoaderStorageEnabled = true;

/**
 * Cache version for client-side ResourceLoader module storage. You can trigger
 * invalidation of the contents of the module store by incrementing this value.
 *
 * @since 1.23
 */
$wgResourceLoaderStorageVersion = 1;

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
$wgAllowSiteCSSOnRestrictedPages = false;

/**
 * Whether to use the development version of Vue.js. This should be disabled
 * for production installations. For development installations, enabling this
 * provides useful additional warnings and checks.
 *
 * Even when this is disabled, using ResourceLoader's debug mode (?debug=true)
 * will cause the development version to be loaded.
 * @since 1.35
 */
$wgVueDevelopmentMode = false;

/** @} */ # End of ResourceLoader settings }

/*************************************************************************//**
 * @name   Page title and interwiki link settings
 * @{
 */

/**
 * Name of the project namespace. If left set to false, $wgSitename will be
 * used instead.
 */
$wgMetaNamespace = false;

/**
 * Name of the project talk namespace.
 *
 * Normally you can ignore this and it will be something like
 * $wgMetaNamespace . "_talk". In some languages, you may want to set this
 * manually for grammatical reasons.
 */
$wgMetaNamespaceTalk = false;

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
 * @par Example:
 * @code
 * $wgExtraNamespaces = [
 *    100 => "Hilfe",
 *    101 => "Hilfe_Diskussion",
 *    102 => "Aide",
 *    103 => "Discussion_Aide"
 * ];
 * @endcode
 *
 * @todo Add a note about maintenance/namespaceDupes.php
 */
$wgExtraNamespaces = [];

/**
 * Same as above, but for namespaces with gender distinction.
 * Note: the default form for the namespace should also be set
 * using $wgExtraNamespaces for the same index.
 * @since 1.18
 */
$wgExtraGenderNamespaces = [];

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
 * @par Example:
 * @code
 *    $wgNamespaceAliases = [
 *        'Wikipedian' => NS_USER,
 *        'Help' => 100,
 *    ];
 * @endcode
 *
 * @see Language::getNamespaceAliases for accessing the full list of aliases,
 * including those defined by other means.
 */
$wgNamespaceAliases = [];

/**
 * Allowed title characters -- regex character class
 * Don't change this unless you know what you're doing
 *
 * Problematic punctuation:
 *   -  []{}|#    Are needed for link syntax, never enable these
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
 */
$wgLegalTitleChars = " %!\"$&'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF+";

/**
 * Array for local interwiki values, for each of the interwiki prefixes that point to
 * the current wiki.
 *
 * Note, recent changes feeds use only the first entry in this array. See $wgRCFeeds.
 */
$wgLocalInterwikis = [];

/**
 * Expiry time for cache of interwiki table
 */
$wgInterwikiExpiry = 10800;

/**
 * @name Interwiki caching settings.
 * @{
 */

/**
 * Interwiki cache, either as an associative array or a path to a constant
 * database (.cdb) file.
 *
 * This data structure database is generated by the `dumpInterwiki` maintenance
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
 * @var bool|array|string
 */
$wgInterwikiCache = false;

/**
 * Specify number of domains to check for messages.
 *    - 1: Just wiki(db)-level
 *    - 2: wiki and global levels
 *    - 3: site levels
 */
$wgInterwikiScopes = 3;

/**
 * Fallback site, if unable to resolve from cache
 */
$wgInterwikiFallbackSite = 'wiki';

/** @} */ # end of Interwiki caching settings.

/**
 * If local interwikis are set up which allow redirects,
 * set this regexp to restrict URLs which will be displayed
 * as 'redirected from' links.
 *
 * @par Example:
 * It might look something like this:
 * @code
 * $wgRedirectSources = '!^https?://[a-z-]+\.wikipedia\.org/!';
 * @endcode
 *
 * Leave at false to avoid displaying any incoming redirect markers.
 * This does not affect intra-wiki redirects, which don't change
 * the URL.
 */
$wgRedirectSources = false;

/**
 * Set this to false to avoid forcing the first letter of links to capitals.
 *
 * @warning may break links! This makes links COMPLETELY case-sensitive. Links
 * appearing with a capital at the beginning of a sentence will *not* go to the
 * same place as links in the middle of a sentence using a lowercase initial.
 */
$wgCapitalLinks = true;

/**
 * @since 1.16 - This can now be set per-namespace. Some special namespaces (such as Special, see
 * NamespaceInfo::$alwaysCapitalizedNamespaces for the full list) must be true by default (and
 * setting them has no effect), due to various things that require them to be so. Also, since Talk
 * namespaces need to directly mirror their associated content namespaces, the values for those are
 * ignored in favor of the subject namespace's setting. Setting for NS_MEDIA is taken automatically
 * from NS_FILE.
 *
 * @par Example:
 * @code
 *     $wgCapitalLinkOverrides[ NS_FILE ] = false;
 * @endcode
 */
$wgCapitalLinkOverrides = [];

/**
 * Which namespaces should support subpages?
 * See Language.php for a list of namespaces.
 */
$wgNamespacesWithSubpages = [
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
 *                        the new extension registration system.
 *
 * @since 1.23
 */
$wgTrackingCategories = [];

/**
 * Array of namespaces which can be deemed to contain valid "content", as far
 * as the site statistics are concerned. Useful if additional namespaces also
 * contain "content" which should be considered when generating a count of the
 * number of articles in the wiki.
 */
$wgContentNamespaces = [ NS_MAIN ];

/**
 * Optional array of namespaces which should be blacklisted from Special:ShortPages
 * Only pages inside $wgContentNamespaces but not $wgShortPagesNamespaceBlacklist will
 * be shown on that page.
 * @since 1.30
 */
$wgShortPagesNamespaceBlacklist = [];

/**
 * Array of namespaces, in addition to the talk namespaces, where signatures
 * (~~~~) are likely to be used. This determines whether to display the
 * Signature button on the edit toolbar, and may also be used by extensions.
 * For example, "traditional" style wikis, where content and discussion are
 * intermixed, could place NS_MAIN and NS_PROJECT namespaces in this array.
 */
$wgExtraSignatureNamespaces = [];

/**
 * Max number of redirects to follow when resolving redirects.
 * 1 means only the first redirect is followed (default behavior).
 * 0 or less means no redirects are followed.
 */
$wgMaxRedirects = 1;

/**
 * Array of invalid page redirect targets.
 * Attempting to create a redirect to any of the pages in this array
 * will make the redirect fail.
 * Userlogout is hard-coded, so it does not need to be listed here.
 * (T12569) Disallow Mypage and Mytalk as well.
 *
 * As of now, this only checks special pages. Redirects to pages in
 * other namespaces cannot be invalidated by this variable.
 */
$wgInvalidRedirectTargets = [ 'Filepath', 'Mypage', 'Mytalk', 'Redirect' ];

/** @} */ # End of title and interwiki settings }

/************************************************************************//**
 * @name   Parser settings
 * These settings configure the transformation from wikitext to HTML.
 * @{
 */

/**
 * Parser configuration. Associative array with the following members:
 *
 *  class             The class name
 *
 * The entire associative array will be passed through to the constructor as
 * the first parameter. Note that only Setup.php can use this variable --
 * the configuration will change at runtime via Parser member functions, so
 * the contents of this variable will be out-of-date. The variable can only be
 * changed during LocalSettings.php, in particular, it can't be changed during
 * an extension setup function.
 * @deprecated since 1.35.  This has been effectively a constant for a long
 *  time.  Configuring the ParserFactory service is the modern way to tweak
 *  the default parser.
 */
$wgParserConf = [
	'class' => Parser::class,
];

/**
 * Maximum indent level of toc.
 */
$wgMaxTocLevel = 999;

/**
 * A complexity limit on template expansion: the maximum number of nodes visited
 * by PPFrame::expand()
 */
$wgMaxPPNodeCount = 1000000;

/**
 * Maximum recursion depth for templates within templates.
 * The current parser adds two levels to the PHP call stack for each template,
 * and xdebug limits the call stack to 100 by default. So this should hopefully
 * stop the parser before it hits the xdebug limit.
 */
$wgMaxTemplateDepth = 40;

/**
 * @see $wgMaxTemplateDepth
 */
$wgMaxPPExpandDepth = 40;

/**
 * URL schemes that should be recognized as valid by wfParseUrl().
 *
 * WARNING: Do not add 'file:' to this or internal file links will be broken.
 * Instead, if you want to support file links, add 'file://'. The same applies
 * to any other protocols with the same name as a namespace. See task T46011 for
 * more information.
 *
 * @see wfParseUrl
 */
$wgUrlProtocols = [
	'bitcoin:', 'ftp://', 'ftps://', 'geo:', 'git://', 'gopher://', 'http://',
	'https://', 'irc://', 'ircs://', 'magnet:', 'mailto:', 'mms://', 'news:',
	'nntp://', 'redis://', 'sftp://', 'sip:', 'sips:', 'sms:', 'ssh://',
	'svn://', 'tel:', 'telnet://', 'urn:', 'worldwind://', 'xmpp:', '//'
];

/**
 * If true, removes (by substituting) templates in signatures.
 */
$wgCleanSignatures = true;

/**
 * Whether to allow inline image pointing to other websites
 */
$wgAllowExternalImages = false;

/**
 * If the above is false, you can specify an exception here. Image URLs
 * that start with this string are then rendered, while all others are not.
 * You can use this to set up a trusted, simple repository of images.
 * You may also specify an array of strings to allow multiple sites
 *
 * @par Examples:
 * @code
 * $wgAllowExternalImagesFrom = 'http://127.0.0.1/';
 * $wgAllowExternalImagesFrom = [ 'http://127.0.0.1/', 'http://example.com' ];
 * @endcode
 */
$wgAllowExternalImagesFrom = '';

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
$wgEnableImageWhitelist = false;

/**
 * A different approach to the above: simply allow the "<img>" tag to be used.
 * This allows you to specify alt text and other attributes, copy-paste HTML to
 * your wiki more easily, etc.  However, allowing external images in any manner
 * will allow anyone with editing rights to snoop on your visitors' IP
 * addresses and so forth, if they wanted to, by inserting links to images on
 * sites they control.
 * @deprecated since 1.35; register an extension tag named <img> instead.
 */
$wgAllowImageTag = false;

/**
 * Configuration for HTML postprocessing tool. Set this to a configuration
 * array to enable an external tool. By default, we now use the RemexHtml
 * library; historically, other postprocessors were used.
 *
 * Setting this to null will use default settings.
 *
 * Keys include:
 *  - driver: formerly used to select a postprocessor; now ignored.
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
$wgTidyConfig = [ 'driver' => 'RemexHtml' ];

/**
 * Allow raw, unchecked HTML in "<html>...</html>" sections.
 * THIS IS VERY DANGEROUS on a publicly editable site, so USE $wgGroupPermissions
 * TO RESTRICT EDITING to only those that you trust
 */
$wgRawHtml = false;

/**
 * Set a default target for external links, e.g. _blank to pop up a new window.
 *
 * This will also set the "noreferrer" and "noopener" link rel to prevent the
 * attack described at https://mathiasbynens.github.io/rel-noopener/ .
 * Some older browsers may not support these link attributes, hence
 * setting $wgExternalLinkTarget to _blank may represent a security risk
 * to some of your users.
 */
$wgExternalLinkTarget = false;

/**
 * If true, external URL links in wiki text will be given the
 * rel="nofollow" attribute as a hint to search engines that
 * they should not be followed for ranking purposes as they
 * are user-supplied and thus subject to spamming.
 */
$wgNoFollowLinks = true;

/**
 * Namespaces in which $wgNoFollowLinks doesn't apply.
 * See Language.php for a list of namespaces.
 */
$wgNoFollowNsExceptions = [];

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
$wgNoFollowDomainExceptions = [ 'mediawiki.org' ];

/**
 * Allow DISPLAYTITLE to change title display
 */
$wgAllowDisplayTitle = true;

/**
 * For consistency, restrict DISPLAYTITLE to text that normalizes to the same
 * canonical DB key. Also disallow some inline CSS rules like display: none;
 * which can cause the text to be hidden or unselectable.
 */
$wgRestrictDisplayTitle = true;

/**
 * Maximum number of calls per parse to expensive parser functions such as
 * PAGESINCATEGORY.
 */
$wgExpensiveParserFunctionLimit = 100;

/**
 * Preprocessor caching threshold
 * Setting it to 'false' will disable the preprocessor cache.
 */
$wgPreprocessorCacheThreshold = 1000;

/**
 * Enable interwiki transcluding.  Only when iw_trans=1 in the interwiki table.
 */
$wgEnableScaryTranscluding = false;

/**
 * Expiry time for transcluded templates cached in object cache.
 * Only used $wgEnableInterwikiTranscluding is set to true.
 */
$wgTranscludeCacheExpiry = 3600;

/**
 * Enable the magic links feature of automatically turning ISBN xxx,
 * PMID xxx, RFC xxx into links
 *
 * @since 1.28
 */
$wgEnableMagicLinks = [
	'ISBN' => false,
	'PMID' => false,
	'RFC' => false
];

/** @} */ # end of parser settings }

/************************************************************************//**
 * @name   Statistics
 * @{
 */

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
$wgArticleCountMethod = 'link';

/**
 * How many days user must be idle before he is considered inactive. Will affect
 * the number shown on Special:Statistics, Special:ActiveUsers, and the
 * {{NUMBEROFACTIVEUSERS}} magic word in wikitext.
 * You might want to leave this as the default value, to provide comparable
 * numbers between different wikis.
 */
$wgActiveUserDays = 30;

/** @} */ # End of statistics }

/************************************************************************//**
 * @name   User accounts, authentication
 * @{
 */

/**
 * Central ID lookup providers
 * Key is the provider ID, value is a specification for ObjectFactory
 * @since 1.27
 */
$wgCentralIdLookupProviders = [
	'local' => [ 'class' => LocalIdLookup::class ],
];

/**
 * Central ID lookup provider to use by default
 * @var string
 */
$wgCentralIdLookupProvider = 'local';

/**
 * Password policy for the wiki.
 * Structured as
 * [
 *     'policies' => [ <group> => [ <policy> => <settings>, ... ], ... ],
 *     'checks' => [ <policy> => <callback>, ... ],
 * ]
 * where <group> is a user group, <policy> is a password policy name
 * (arbitrary string) defined in the 'checks' part, <callback> is the
 * PHP callable implementing the policy check, <settings> is an array
 * of options with the following keys:
 * - value: (number, boolean or null) the value to pass to the callback
 * - forceChange: (bool, default false) if the password is invalid, do
 *   not let the user log in without changing the password
 * - suggestChangeOnLogin: (bool, default false) if true and the password is
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
 *	- MinimalPasswordLength - Minimum length a user can set.
 *	- MinimumPasswordLengthToLogin - Passwords shorter than this will
 *		not be allowed to login, or offered a chance to reset their password
 *		as part of the login workflow, regardless if it is correct.
 *	- MaximalPasswordLength - maximum length password a user is allowed
 *		to attempt. Prevents DoS attacks with pbkdf2.
 *	- PasswordCannotMatchUsername - Password cannot match the username.
 *	- PasswordCannotBeSubstringInUsername - Password cannot be a substring
 *		(contained within) the username.
 *	- PasswordCannotMatchBlacklist - Username/password combination cannot
 *		match a list of default passwords used by MediaWiki in the past.
 *		Deprecated since 1.35, use PasswordCannotMatchDefaults instead.
 *	- PasswordCannotMatchDefaults - Username/password combination cannot
 *		match a list of default passwords used by MediaWiki in the past.
 *	- PasswordNotInLargeBlacklist - Password not in best practices list of
 *		100,000 commonly used passwords. Due to the size of the list this
 *		is a probabilistic test.
 *		Deprecated since 1.35, use PasswordNotInCommonList instead.
 *	- PasswordNotInCommonList - Password not in best practices list of
 *		100,000 commonly used passwords. Due to the size of the list this
 *		is a probabilistic test.
 *
 * If you add custom checks, for Special:PasswordPolicies to display them correctly,
 * every check should have a corresponding passwordpolicies-policy-<check> message,
 * and every settings field other than 'value' should have a corresponding
 * passwordpolicies-policyflag-<flag> message (<check> and <flag> are in lowercase).
 * The check message receives the policy value as a parameter, the flag message
 * receives the flag value (or values if it's an array).
 *
 * @since 1.26
 * @see PasswordPolicyChecks
 * @see User::checkPasswordValidity()
 */
$wgPasswordPolicy = [
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
			'MinimalPasswordLength' => [ 'value' => 1, 'suggestChangeOnLogin' => true ],
			'PasswordCannotMatchUsername' => [ 'value' => true, 'suggestChangeOnLogin' => true ],
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
		'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
		'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
		'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
		'PasswordCannotBeSubstringInUsername' =>
			'PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername',
		'PasswordCannotMatchBlacklist' => 'PasswordPolicyChecks::checkPasswordCannotMatchDefaults',
		'PasswordCannotMatchDefaults' => 'PasswordPolicyChecks::checkPasswordCannotMatchDefaults',
		'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
		'PasswordNotInLargeBlacklist' => 'PasswordPolicyChecks::checkPasswordNotInCommonList',
		'PasswordNotInCommonList' => 'PasswordPolicyChecks::checkPasswordNotInCommonList',
	],
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
 *  used instead. Local customization should generally set this variable from
 *  scratch to the desired configuration. Extensions that want to
 *  auto-configure themselves should use $wgAuthManagerAutoConfig instead.
 */
$wgAuthManagerConfig = null;

/**
 * @see $wgAuthManagerConfig
 * @since 1.27
 */
$wgAuthManagerAutoConfig = [
	'preauth' => [
		MediaWiki\Auth\ThrottlePreAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\ThrottlePreAuthenticationProvider::class,
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
		MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider::class,
			'args' => [ [
				// Fall through to LocalPasswordPrimaryAuthenticationProvider
				'authoritative' => false,
			] ],
			'sort' => 0,
		],
		MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class,
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
		MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider::class,
			'sort' => 0,
		],
		MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider::class,
			'sort' => 100,
		],
		// Linking during login is experimental, enable at your own risk - T134952
		// MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider::class => [
		//   'class' => MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider::class,
		//   'sort' => 100,
		// ],
		MediaWiki\Auth\EmailNotificationSecondaryAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\EmailNotificationSecondaryAuthenticationProvider::class,
			'sort' => 200,
		],
	],
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
 * @var int[] operation => time in seconds. A 'default' key must always be provided.
 */
$wgReauthenticateTime = [
	'default' => 300,
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
 * @see $wgReauthenticateTime
 * @var bool[] operation => boolean. A 'default' key must always be provided.
 */
$wgAllowSecuritySensitiveOperationIfCannotReauthenticate = [
	'default' => true,
];

/**
 * List of AuthenticationRequest class names which are not changeable through
 * Special:ChangeCredentials and the changeauthenticationdata API.
 * This is only enforced on the client level; AuthManager itself (e.g.
 * AuthManager::allowsAuthenticationDataChange calls) is not affected.
 * Class names are checked for exact match (not for subclasses).
 * @since 1.27
 * @var string[]
 */
$wgChangeCredentialsBlacklist = [
	\MediaWiki\Auth\TemporaryPasswordAuthenticationRequest::class
];

/**
 * List of AuthenticationRequest class names which are not removable through
 * Special:RemoveCredentials and the removeauthenticationdata API.
 * This is only enforced on the client level; AuthManager itself (e.g.
 * AuthManager::allowsAuthenticationDataChange calls) is not affected.
 * Class names are checked for exact match (not for subclasses).
 * @since 1.27
 * @var string[]
 */
$wgRemoveCredentialsBlacklist = [
	\MediaWiki\Auth\PasswordAuthenticationRequest::class,
];

/**
 * Specifies the minimal length of a user password. If set to 0, empty pass-
 * words are allowed.
 * @deprecated since 1.26, use $wgPasswordPolicy's MinimalPasswordLength.
 */
$wgMinimalPasswordLength = false;

/**
 * Specifies the maximal length of a user password (T64685).
 *
 * It is not recommended to make this greater than the default, as it can
 * allow DoS attacks by users setting really long passwords. In addition,
 * this should not be lowered too much, as it enforces weak passwords.
 *
 * @warning Unlike other password settings, user with passwords greater than
 *      the maximum will not be able to log in.
 * @deprecated since 1.26, use $wgPasswordPolicy's MaximalPasswordLength.
 */
$wgMaximalPasswordLength = false;

/**
 * Specifies if users should be sent to a password-reset form on login, if their
 * password doesn't meet the requirements of User::isValidPassword().
 * @since 1.23
 */
$wgInvalidPasswordReset = true;

/**
 * Default password type to use when hashing user passwords.
 *
 * Must be set to a type defined in $wgPasswordConfig, or a type that
 * is registered by default in PasswordFactory.php.
 *
 * @since 1.24
 */
$wgPasswordDefault = 'pbkdf2';

/**
 * Configuration for built-in password types. Maps the password type
 * to an array of options. The 'class' option is the Password class to
 * use. All other options are class-dependent.
 *
 * An advanced example:
 * @code
 * $wgPasswordConfig['bcrypt-peppered'] = [
 *     'class' => EncryptedPassword::class,
 *     'underlying' => 'bcrypt',
 *     'secrets' => [
 *         hash( 'sha256', 'secret', true ),
 *     ],
 *     'cipher' => 'aes-256-cbc',
 * ];
 * @endcode
 *
 * @since 1.24
 */
$wgPasswordConfig = [
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
		'class' => Pbkdf2Password::class,
		'algo' => 'sha512',
		'cost' => '30000',
		'length' => '64',
	],
	'argon2' => [
		'class' => Argon2Password::class,

		// Algorithm used:
		// * 'argon2i' is optimized against side-channel attacks (PHP 7.2+)
		// * 'argon2id' is optimized against both side-channel and GPU cracking (PHP 7.3+)
		// * 'auto' to use best available algorithm. If you're using more than one server, be
		//   careful when you're mixing PHP versions because newer PHP might generate hashes that
		//   older versions might would not understand.
		'algo' => 'auto',

		// The parameters below are the same as options accepted by password_hash().
		// Set them to override that function's defaults.
		//
		// 'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
		// 'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
		// 'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
	],
];

/**
 * Whether to allow password resets ("enter some identifying data, and we'll send an email
 * with a temporary password you can use to get back into the account") identified by
 * various bits of data.  Setting all of these to false (or the whole variable to false)
 * has the effect of disabling password resets entirely
 */
$wgPasswordResetRoutes = [
	'username' => true,
	'email' => true,
];

/**
 * Maximum number of Unicode characters in signature
 */
$wgMaxSigChars = 255;

/**
 * Behavior of signature validation. Allowed values are:
 *  - 'warning' - invalid signatures cause a warning to be displayed on the preferences page, but
 *    they are still used when signing comments; new invalid signatures can still be saved as normal
 *  - 'new' - existing invalid signatures behave as above; new invalid signatures can't be saved
 *  - 'disallow' - existing invalid signatures are no longer used when signing comments; new invalid
 *    signatures can't be saved
 *
 * @since 1.35
 */
$wgSignatureValidation = 'warning';

/**
 * List of lint error codes which don't cause signature validation to fail.
 *
 * @see https://www.mediawiki.org/wiki/Help:Lint_errors
 * @since 1.35
 */
$wgSignatureAllowedLintErrors = [ 'obsolete-tag' ];

/**
 * Maximum number of bytes in username. You want to run the maintenance
 * script ./maintenance/checkUsernames.php once you have changed this value.
 */
$wgMaxNameChars = 255;

/**
 * Array of usernames which may not be registered or logged in from
 * Maintenance scripts can still use these
 */
$wgReservedUsernames = [
	'MediaWiki default', // Default 'Main Page' and MediaWiki: message pages
	'Conversion script', // Used for the old Wikipedia software upgrade
	'Maintenance script', // Maintenance scripts which perform editing, image import script
	'Template namespace initialisation script', // Used in 1.2->1.3 upgrade
	'ScriptImporter', // Default user name used by maintenance/importSiteScripts.php
	'Unknown user', // Used in WikiImporter and RevisionStore for revisions with no author
	'msg:double-redirect-fixer', // Automatic double redirect fix
	'msg:usermessage-editor', // Default user for leaving user messages
	'msg:proxyblocker', // For $wgProxyList and Special:Blockme (removed in 1.22)
	'msg:sorbs', // For $wgEnableDnsBlacklist etc.
	'msg:spambot_username', // Used by cleanupSpam.php
	'msg:autochange-username', // Used by anon category RC entries (parser functions, Lua & purges)
];

/**
 * Settings added to this array will override the default globals for the user
 * preferences used by anonymous visitors and newly created accounts.
 * For instance, to disable editing on double clicks:
 * $wgDefaultUserOptions ['editondblclick'] = 0;
 */
$wgDefaultUserOptions = [
	'ccmeonemails' => 0,
	'date' => 'default',
	'diffonly' => 0,
	'disablemail' => 0,
	'editfont' => 'monospace',
	'editondblclick' => 0,
	'editsectiononrightclick' => 0,
	'email-allow-new-users' => 1,
	'enotifminoredits' => 0,
	'enotifrevealaddr' => 0,
	'enotifusertalkpages' => 1,
	'enotifwatchlistpages' => 1,
	'extendwatchlist' => 1,
	'fancysig' => 0,
	'forceeditsummary' => 0,
	'gender' => 'unknown',
	'hideminor' => 0,
	'hidepatrolled' => 0,
	'hidecategorization' => 1,
	'imagesize' => 2,
	'minordefault' => 0,
	'newpageshidepatrolled' => 0,
	'nickname' => '',
	'norollbackdiff' => 0,
	'numberheadings' => 0,
	'previewonfirst' => 0,
	'previewontop' => 1,
	'rcdays' => 7,
	'rcenhancedfilters-disable' => 0,
	'rclimit' => 50,
	'search-match-redirect' => true,
	'showhiddencats' => 0,
	'shownumberswatching' => 1,
	'showrollbackconfirmation' => 0,
	'skin' => false,
	'stubthreshold' => 0,
	'thumbsize' => 5,
	'underline' => 2,
	'uselivepreview' => 0,
	'usenewrc' => 1,
	'watchcreations' => 1,
	'watchdefault' => 1,
	'watchdeletion' => 0,
	'watchuploads' => 1,
	'watchlistdays' => 7.0,
	'watchlisthideanons' => 0,
	'watchlisthidebots' => 0,
	'watchlisthideliu' => 0,
	'watchlisthideminor' => 0,
	'watchlisthideown' => 0,
	'watchlisthidepatrolled' => 0,
	'watchlisthidecategorization' => 1,
	'watchlistreloadautomatically' => 0,
	'watchlistunwatchlinks' => 0,
	'watchmoves' => 0,
	'watchrollback' => 0,
	'wlenhancedfilters-disable' => 0,
	'wllimit' => 250,
	'useeditwarning' => 1,
	'prefershttps' => 1,
	'requireemail' => 0,
];

/**
 * An array of preferences to not show for the user
 */
$wgHiddenPrefs = [];

/**
 * Characters to prevent during new account creations.
 * This is used in a regular expression character class during
 * registration (regex metacharacters like / are escaped).
 */
$wgInvalidUsernameCharacters = '@:';

/**
 * Character used as a delimiter when testing for interwiki userrights
 * (In Special:UserRights, it is possible to modify users on different
 * databases if the delimiter is used, e.g. "Someuser@enwiki").
 *
 * It is recommended that you have this delimiter in
 * $wgInvalidUsernameCharacters above, or you will not be able to
 * modify the user rights of those users via Special:UserRights
 */
$wgUserrightsInterwikiDelimiter = '@';

/**
 * This is to let user authenticate using https when they come from http.
 * Based on an idea by George Herbert on wikitech-l:
 * https://lists.wikimedia.org/pipermail/wikitech-l/2010-October/050039.html
 * @since 1.17
 */
$wgSecureLogin = false;

/**
 * Versioning for authentication tokens.
 *
 * If non-null, this is combined with the user's secret (the user_token field
 * in the DB) to generate the token cookie. Changing this will invalidate all
 * active sessions (i.e. it will log everyone out).
 *
 * @since 1.27
 * @var string|null
 */
$wgAuthenticationTokenVersion = null;

/**
 * MediaWiki\Session\SessionProvider configuration.
 *
 * Value is an array of ObjectFactory specifications for the SessionProviders
 * to be used. Keys in the array are ignored. Order is not significant.
 *
 * @since 1.27
 */
$wgSessionProviders = [
	MediaWiki\Session\CookieSessionProvider::class => [
		'class' => MediaWiki\Session\CookieSessionProvider::class,
		'args' => [ [
			'priority' => 30,
			'callUserSetCookiesHook' => true,
		] ],
	],
	MediaWiki\Session\BotPasswordSessionProvider::class => [
		'class' => MediaWiki\Session\BotPasswordSessionProvider::class,
		'args' => [ [
			'priority' => 75,
		] ],
	],
];

/**
 * Temporary feature flag that controls whether users will see a checkbox allowing them to
 * require providing email during password resets.
 *
 * @deprecated This feature is under development, don't assume this flag's existence or function
 *     outside of MediaWiki.
 */
$wgAllowRequiringEmailForResets = false;

/** @} */ # end user accounts }

/************************************************************************//**
 * @name   User rights, access control and monitoring
 * @{
 */

/**
 * Number of seconds before autoblock entries expire. Default 86400 = 1 day.
 */
$wgAutoblockExpiry = 86400;

/**
 * Set this to true to allow blocked users to edit their own user talk page.
 *
 * This only applies to sitewide blocks. Partial blocks always allow users to
 * edit their own user talk page unless otherwise specified in the block
 * restrictions.
 */
$wgBlockAllowsUTEdit = true;

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
$wgBlockCIDRLimit = [
	'IPv4' => 16, # Blocks larger than a /16 (64k addresses) will not be allowed
	'IPv6' => 19,
];

/**
 * If true, blocked users will not be allowed to login. When using this with
 * a public wiki, the effect of logging out blocked users may actually be
 * avers: unless the user's address is also blocked (e.g. auto-block),
 * logging the user out will again allow reading and editing, just as for
 * anonymous visitors.
 */
$wgBlockDisablesLogin = false;

/**
 * Pages anonymous user may see, set as an array of pages titles.
 *
 * @par Example:
 * @code
 * $wgWhitelistRead = array ( "Main Page", "Wikipedia:Help");
 * @endcode
 *
 * Special:Userlogin and Special:ChangePassword are always allowed.
 *
 * @note This will only work if $wgGroupPermissions['*']['read'] is false --
 * see below. Otherwise, ALL pages are accessible, regardless of this setting.
 *
 * @note Also that this will only protect _pages in the wiki_. Uploaded files
 * will remain readable. You can use img_auth.php to protect uploaded files,
 * see https://www.mediawiki.org/wiki/Manual:Image_Authorization
 *
 * @note Extensions should not modify this, but use the TitleReadWhitelist
 * hook instead.
 */
$wgWhitelistRead = false;

/**
 * Pages anonymous user may see, set as an array of regular expressions.
 *
 * This function will match the regexp against the title name, which
 * is without underscore.
 *
 * @par Example:
 * To whitelist [[Main Page]]:
 * @code
 * $wgWhitelistReadRegexp = [ "/Main Page/" ];
 * @endcode
 *
 * @note Unless ^ and/or $ is specified, a regular expression might match
 * pages not intended to be allowed.  The above example will also
 * allow a page named 'Security Main Page'.
 *
 * @par Example:
 * To allow reading any page starting with 'User' regardless of the case:
 * @code
 * $wgWhitelistReadRegexp = [ "@^UsEr.*@i" ];
 * @endcode
 * Will allow both [[User is banned]] and [[User:JohnDoe]]
 *
 * @note This will only work if $wgGroupPermissions['*']['read'] is false --
 * see below. Otherwise, ALL pages are accessible, regardless of this setting.
 */
$wgWhitelistReadRegexp = false;

/**
 * Should editors be required to have a validated e-mail
 * address before being allowed to edit?
 */
$wgEmailConfirmToEdit = false;

/**
 * Should MediaWiki attempt to protect user's privacy when doing redirects?
 * Keep this true if access counts to articles are made public.
 */
$wgHideIdentifiableRedirects = true;

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
 * in in the user_groups table.
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
$wgGroupPermissions = [];

/** @cond file_level_code */
// Implicit group for all visitors
$wgGroupPermissions['*']['createaccount'] = true;
$wgGroupPermissions['*']['read'] = true;
$wgGroupPermissions['*']['edit'] = true;
$wgGroupPermissions['*']['createpage'] = true;
$wgGroupPermissions['*']['createtalk'] = true;
$wgGroupPermissions['*']['writeapi'] = true;
$wgGroupPermissions['*']['viewmywatchlist'] = true;
$wgGroupPermissions['*']['editmywatchlist'] = true;
$wgGroupPermissions['*']['viewmyprivateinfo'] = true;
$wgGroupPermissions['*']['editmyprivateinfo'] = true;
$wgGroupPermissions['*']['editmyoptions'] = true;
# $wgGroupPermissions['*']['patrolmarks'] = false; // let anons see what was patrolled

// Implicit group for all logged-in accounts
$wgGroupPermissions['user']['move'] = true;
$wgGroupPermissions['user']['move-subpages'] = true;
$wgGroupPermissions['user']['move-rootuserpages'] = true; // can move root userpages
$wgGroupPermissions['user']['move-categorypages'] = true;
$wgGroupPermissions['user']['movefile'] = true;
$wgGroupPermissions['user']['read'] = true;
$wgGroupPermissions['user']['edit'] = true;
$wgGroupPermissions['user']['createpage'] = true;
$wgGroupPermissions['user']['createtalk'] = true;
$wgGroupPermissions['user']['writeapi'] = true;
$wgGroupPermissions['user']['upload'] = true;
$wgGroupPermissions['user']['reupload'] = true;
$wgGroupPermissions['user']['reupload-shared'] = true;
$wgGroupPermissions['user']['minoredit'] = true;
$wgGroupPermissions['user']['editmyusercss'] = true;
$wgGroupPermissions['user']['editmyuserjson'] = true;
$wgGroupPermissions['user']['editmyuserjs'] = true;
$wgGroupPermissions['user']['editmyuserjsredirect'] = true;
$wgGroupPermissions['user']['purge'] = true;
$wgGroupPermissions['user']['sendemail'] = true;
$wgGroupPermissions['user']['applychangetags'] = true;
$wgGroupPermissions['user']['changetags'] = true;
$wgGroupPermissions['user']['editcontentmodel'] = true;

// Implicit group for accounts that pass $wgAutoConfirmAge
$wgGroupPermissions['autoconfirmed']['autoconfirmed'] = true;
$wgGroupPermissions['autoconfirmed']['editsemiprotected'] = true;

// Users with bot privilege can have their edits hidden
// from various log pages by default
$wgGroupPermissions['bot']['bot'] = true;
$wgGroupPermissions['bot']['autoconfirmed'] = true;
$wgGroupPermissions['bot']['editsemiprotected'] = true;
$wgGroupPermissions['bot']['nominornewtalk'] = true;
$wgGroupPermissions['bot']['autopatrol'] = true;
$wgGroupPermissions['bot']['suppressredirect'] = true;
$wgGroupPermissions['bot']['apihighlimits'] = true;
$wgGroupPermissions['bot']['writeapi'] = true;

// Most extra permission abilities go to this group
$wgGroupPermissions['sysop']['block'] = true;
$wgGroupPermissions['sysop']['createaccount'] = true;
$wgGroupPermissions['sysop']['delete'] = true;
// can be separately configured for pages with > $wgDeleteRevisionsLimit revs
$wgGroupPermissions['sysop']['bigdelete'] = true;
// can view deleted history entries, but not see or restore the text
$wgGroupPermissions['sysop']['deletedhistory'] = true;
// can view deleted revision text
$wgGroupPermissions['sysop']['deletedtext'] = true;
$wgGroupPermissions['sysop']['undelete'] = true;
$wgGroupPermissions['sysop']['editinterface'] = true;
$wgGroupPermissions['sysop']['editsitejson'] = true;
$wgGroupPermissions['sysop']['edituserjson'] = true;
$wgGroupPermissions['sysop']['import'] = true;
$wgGroupPermissions['sysop']['importupload'] = true;
$wgGroupPermissions['sysop']['move'] = true;
$wgGroupPermissions['sysop']['move-subpages'] = true;
$wgGroupPermissions['sysop']['move-rootuserpages'] = true;
$wgGroupPermissions['sysop']['move-categorypages'] = true;
$wgGroupPermissions['sysop']['patrol'] = true;
$wgGroupPermissions['sysop']['autopatrol'] = true;
$wgGroupPermissions['sysop']['protect'] = true;
$wgGroupPermissions['sysop']['editprotected'] = true;
$wgGroupPermissions['sysop']['rollback'] = true;
$wgGroupPermissions['sysop']['upload'] = true;
$wgGroupPermissions['sysop']['reupload'] = true;
$wgGroupPermissions['sysop']['reupload-shared'] = true;
$wgGroupPermissions['sysop']['unwatchedpages'] = true;
$wgGroupPermissions['sysop']['autoconfirmed'] = true;
$wgGroupPermissions['sysop']['editsemiprotected'] = true;
$wgGroupPermissions['sysop']['ipblock-exempt'] = true;
$wgGroupPermissions['sysop']['blockemail'] = true;
$wgGroupPermissions['sysop']['markbotedits'] = true;
$wgGroupPermissions['sysop']['apihighlimits'] = true;
$wgGroupPermissions['sysop']['browsearchive'] = true;
$wgGroupPermissions['sysop']['noratelimit'] = true;
$wgGroupPermissions['sysop']['movefile'] = true;
$wgGroupPermissions['sysop']['unblockself'] = true;
$wgGroupPermissions['sysop']['suppressredirect'] = true;
# $wgGroupPermissions['sysop']['pagelang'] = true;
# $wgGroupPermissions['sysop']['upload_by_url'] = true;
$wgGroupPermissions['sysop']['mergehistory'] = true;
$wgGroupPermissions['sysop']['managechangetags'] = true;
$wgGroupPermissions['sysop']['deletechangetags'] = true;

$wgGroupPermissions['interface-admin']['editinterface'] = true;
$wgGroupPermissions['interface-admin']['editsitecss'] = true;
$wgGroupPermissions['interface-admin']['editsitejson'] = true;
$wgGroupPermissions['interface-admin']['editsitejs'] = true;
$wgGroupPermissions['interface-admin']['editusercss'] = true;
$wgGroupPermissions['interface-admin']['edituserjson'] = true;
$wgGroupPermissions['interface-admin']['edituserjs'] = true;

// Permission to change users' group assignments
$wgGroupPermissions['bureaucrat']['userrights'] = true;
$wgGroupPermissions['bureaucrat']['noratelimit'] = true;
// Permission to change users' groups assignments across wikis
# $wgGroupPermissions['bureaucrat']['userrights-interwiki'] = true;
// Permission to export pages including linked pages regardless of $wgExportMaxLinkDepth
# $wgGroupPermissions['bureaucrat']['override-export-depth'] = true;

# $wgGroupPermissions['sysop']['deletelogentry'] = true;
# $wgGroupPermissions['sysop']['deleterevision'] = true;
// To hide usernames from users and Sysops
$wgGroupPermissions['suppress']['hideuser'] = true;
// To hide revisions/log items from users and Sysops
$wgGroupPermissions['suppress']['suppressrevision'] = true;
// To view revisions/log items hidden from users and Sysops
$wgGroupPermissions['suppress']['viewsuppressed'] = true;
// For private suppression log access
$wgGroupPermissions['suppress']['suppressionlog'] = true;
// Basic rights for revision delete
$wgGroupPermissions['suppress']['deleterevision'] = true;
$wgGroupPermissions['suppress']['deletelogentry'] = true;

/**
 * The developer group is deprecated, but can be activated if need be
 * to use the 'lockdb' and 'unlockdb' special pages. Those require
 * that a lock file be defined and creatable/removable by the web
 * server.
 */
# $wgGroupPermissions['developer']['siteadmin'] = true;

/** @endcond */

/**
 * Permission keys revoked from users in each group.
 *
 * This acts the same way as $wgGroupPermissions above, except that
 * if the user is in a group here, the permission will be removed from them.
 *
 * Improperly setting this could mean that your users will be unable to perform
 * certain essential tasks, so use at your own risk!
 */
$wgRevokePermissions = [];

/**
 * Implicit groups, aren't shown on Special:Listusers or somewhere else
 */
$wgImplicitGroups = [ '*', 'user', 'autoconfirmed' ];

/**
 * A map of group names that the user is in, to group names that those users
 * are allowed to add or revoke.
 *
 * Setting the list of groups to add or revoke to true is equivalent to "any
 * group".
 *
 * @par Example:
 * To allow sysops to add themselves to the "bot" group:
 * @code
 *    $wgGroupsAddToSelf = [ 'sysop' => [ 'bot' ] ];
 * @endcode
 *
 * @par Example:
 * Implicit groups may be used for the source group, for instance:
 * @code
 *    $wgGroupsRemoveFromSelf = [ '*' => true ];
 * @endcode
 * This allows users in the '*' group (i.e. any user) to remove themselves from
 * any group that they happen to be in.
 */
$wgGroupsAddToSelf = [];

/**
 * @see $wgGroupsAddToSelf
 */
$wgGroupsRemoveFromSelf = [];

/**
 * Set of available actions that can be restricted via action=protect
 * You probably shouldn't change this.
 * Translated through restriction-* messages.
 * Title::getRestrictionTypes() will remove restrictions that are not
 * applicable to a specific title (create and upload)
 */
$wgRestrictionTypes = [ 'create', 'edit', 'move', 'upload' ];

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
$wgRestrictionLevels = [ '', 'autoconfirmed', 'sysop' ];

/**
 * Restriction levels that can be used with cascading protection
 *
 * A page can only be protected with cascading protection if the
 * requested restriction level is included in this array.
 *
 * 'autoconfirmed' is quietly rewritten to 'editsemiprotected' for backwards compatibility.
 * 'sysop' is quietly rewritten to 'editprotected' for backwards compatibility.
 */
$wgCascadingRestrictionLevels = [ 'sysop' ];

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
$wgSemiprotectedRestrictionLevels = [ 'autoconfirmed' ];

/**
 * Set the minimum permissions required to edit pages in each
 * namespace.  If you list more than one permission, a user must
 * have all of them to edit pages in that namespace.
 *
 * @note NS_MEDIAWIKI is implicitly restricted to 'editinterface'.
 */
$wgNamespaceProtection = [];

/**
 * Pages in namespaces in this array can not be used as templates.
 *
 * Elements MUST be numeric namespace ids, you can safely use the MediaWiki
 * namespaces constants (NS_USER, NS_MAIN...).
 *
 * Among other things, this may be useful to enforce read-restrictions
 * which may otherwise be bypassed by using the template mechanism.
 */
$wgNonincludableNamespaces = [];

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
 * @par Example:
 * Set automatic confirmation to 10 minutes (which is 600 seconds):
 * @code
 *  $wgAutoConfirmAge = 600;     // ten minutes
 * @endcode
 * Set age to one day:
 * @code
 *  $wgAutoConfirmAge = 3600*24; // one day
 * @endcode
 */
$wgAutoConfirmAge = 0;

/**
 * Number of edits an account requires before it is autoconfirmed.
 * Passing both this AND the time requirement is needed. Example:
 *
 * @par Example:
 * @code
 * $wgAutoConfirmCount = 50;
 * @endcode
 */
$wgAutoConfirmCount = 0;

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
 *  - [ APCOND_EDITCOUNT, number of edits ]:
 *      true if user has the at least the number of edits as the passed parameter
 *  - [ APCOND_AGE, seconds since registration ]:
 *      true if the length of time since the user created his/her account
 *      is at least the same length of time as the passed parameter
 *  - [ APCOND_AGE_FROM_EDIT, seconds since first edit ]:
 *      true if the length of time since the user made his/her first edit
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
$wgAutopromote = [
	'autoconfirmed' => [ '&',
		[ APCOND_EDITCOUNT, &$wgAutoConfirmCount ],
		[ APCOND_AGE, &$wgAutoConfirmAge ],
	],
];

/**
 * Automatically add a usergroup to any user who matches certain conditions.
 *
 * Does not add the user to the group again if it has been removed.
 * Also, does not remove the group if the user no longer meets the criteria.
 *
 * The format is:
 * @code
 *    [ event => criteria, ... ]
 * @endcode
 * Where event is either:
 *    - 'onEdit' (when user edits)
 *
 * Criteria has the same format as $wgAutopromote
 *
 * @see $wgAutopromote
 * @since 1.18
 */
$wgAutopromoteOnce = [
	'onEdit' => [],
];

/**
 * Put user rights log entries for autopromotion in recent changes?
 * @since 1.18
 */
$wgAutopromoteOnceLogInRC = true;

/**
 * $wgAddGroups and $wgRemoveGroups can be used to give finer control over who
 * can assign which groups at Special:Userrights.
 *
 * @par Example:
 * Bureaucrats can add any group:
 * @code
 * $wgAddGroups['bureaucrat'] = true;
 * @endcode
 * Bureaucrats can only remove bots and sysops:
 * @code
 * $wgRemoveGroups['bureaucrat'] = [ 'bot', 'sysop' ];
 * @endcode
 * Sysops can make bots:
 * @code
 * $wgAddGroups['sysop'] = [ 'bot' ];
 * @endcode
 * Sysops can disable other sysops in an emergency, and disable bots:
 * @code
 * $wgRemoveGroups['sysop'] = [ 'sysop', 'bot' ];
 * @endcode
 */
$wgAddGroups = [];

/**
 * @see $wgAddGroups
 */
$wgRemoveGroups = [];

/**
 * A list of available rights, in addition to the ones defined by the core.
 * For extensions only.
 */
$wgAvailableRights = [];

/**
 * Optional to restrict deletion of pages with higher revision counts
 * to users with the 'bigdelete' permission. (Default given to sysops.)
 */
$wgDeleteRevisionsLimit = 0;

/**
 * Page deletions with > this number of revisions will use the job queue.
 * Revisions will be archived in batches of (at most) this size, one batch per job.
 */
$wgDeleteRevisionsBatchSize = 1000;

/**
 * The maximum number of edits a user can have and
 * can still be hidden by users with the hideuser permission.
 * This is limited for performance reason.
 * Set to false to disable the limit.
 * @since 1.23
 */
$wgHideUserContribLimit = 1000;

/**
 * Number of accounts each IP address may create per specified period(s).
 *
 * @par Example:
 * @code
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
 * @endcode
 *
 * @warning Requires $wgMainCacheType to be enabled
 */
$wgAccountCreationThrottle = [ [
	'count' => 0,
	'seconds' => 86400,
] ];

/**
 * Edits matching these regular expressions in body text
 * will be recognised as spam and rejected automatically.
 *
 * There's no administrator override on-wiki, so be careful what you set. :)
 * May be an array of regexes or a single string for backwards compatibility.
 *
 * @see https://en.wikipedia.org/wiki/Regular_expression
 *
 * @note Each regex needs a beginning/end delimiter, eg: # or /
 */
$wgSpamRegex = [];

/**
 * Same as the above except for edit summaries
 */
$wgSummarySpamRegex = [];

/**
 * Whether to use DNS blacklists in $wgDnsBlacklistUrls to check for open
 * proxies
 * @since 1.16
 */
$wgEnableDnsBlacklist = false;

/**
 * List of DNS blacklists to use, if $wgEnableDnsBlacklist is true.
 *
 * This is an array of either a URL or an array with the URL and a key (should
 * the blacklist require a key).
 *
 * @par Example:
 * @code
 * $wgDnsBlacklistUrls = [
 *   // String containing URL
 *   'http.dnsbl.sorbs.net.',
 *   // Array with URL and key, for services that require a key
 *   [ 'dnsbl.httpbl.net.', 'mykey' ],
 *   // Array with just the URL. While this works, it is recommended that you
 *   // just use a string as shown above
 *   [ 'opm.tornevall.org.' ]
 * ];
 * @endcode
 *
 * @note You should end the domain name with a . to avoid searching your
 * eventual domain search suffixes.
 * @since 1.16
 */
$wgDnsBlacklistUrls = [ 'http.dnsbl.sorbs.net.' ];

/**
 * Proxy whitelist, list of addresses that are assumed to be non-proxy despite
 * what the other methods might say.
 */
$wgProxyWhitelist = [];

/**
 * IP ranges that should be considered soft-blocked (anon-only, account
 * creation allowed). The intent is to use this to prevent anonymous edits from
 * shared resources such as Wikimedia Labs.
 * @since 1.29
 * @var string[]
 */
$wgSoftBlockRanges = [];

/**
 * Whether to look at the X-Forwarded-For header's list of (potentially spoofed)
 * IPs and apply IP blocks to them. This allows for IP blocks to work with correctly-configured
 * (transparent) proxies without needing to block the proxies themselves.
 */
$wgApplyIpBlocksToXff = false;

/**
 * Simple rate limiter options to brake edit floods.
 *
 * Maximum number actions allowed in the given number of seconds; after that
 * the violating client receives HTTP 500 error pages until the period
 * elapses.
 *
 * @par Example:
 * Limits per configured per action and then type of users.
 * @code
 *     $wgRateLimits = [
 *         'edit' => [
 *             'anon' => [ x, y ], // any and all anonymous edits (aggregate)
 *             'user' => [ x, y ], // each logged-in user
 *             'user-global' => [ x, y ], // per username, across all sites (assumes names are global)
 *             'newbie' => [ x, y ], // each new autoconfirmed accounts; overrides 'user'
 *             'ip' => [ x, y ], // each anon and recent account, across all sites
 *             'subnet' => [ x, y ], // ... within a /24 subnet in IPv4 or /64 in IPv6
 *             'ip-all' => [ x, y ], // per ip, across all sites
 *             'subnet-all' => [ x, y ], // ... within a /24 subnet in IPv4 or /64 in IPv6
 *             'groupName' => [ x, y ], // by group membership
 *         ]
 *     ];
 * @endcode
 *
 * @par Normally, the 'noratelimit' right allows a user to bypass any rate
 * limit checks. This can be disabled on a per-action basis by setting the
 * special '&can-bypass' key to false in that action's configuration.
 * @code
 *     $wgRateLimits = [
 *         'some-action' => [
 *             '&can-bypass' => false,
 *             'user' => [ x, y ],
 *     ];
 * @endcode
 *
 * @warning Requires that $wgMainCacheType is set to something persistent
 */
$wgRateLimits = [
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
	'emailuser' => [
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
	// Adding or removing change tags
	'changetag' => [
		'ip' => [ 8, 60 ],
		'newbie' => [ 8, 60 ],
	],
	// Changing the content model of a page
	'editcontentmodel' => [
		'newbie' => [ 2, 120 ],
		'user' => [ 8, 60 ],
	],
];

/**
 * Array of IPs / CIDR ranges which should be excluded from rate limits.
 * This may be useful for allowing NAT gateways for conferences, etc.
 */
$wgRateLimitsExcludedIPs = [];

/**
 * Log IP addresses in the recentchanges table; can be accessed only by
 * extensions (e.g. CheckUser) or a DB admin
 * Used for retroactive autoblocks
 */
$wgPutIPinRC = true;

/**
 * Integer defining default number of entries to show on
 * special pages which are query-pages such as Special:Whatlinkshere.
 */
$wgQueryPageDefaultLimit = 50;

/**
 * Limit password attempts to X attempts per Y seconds per IP per account.
 *
 * Value is an array of arrays. Each sub-array must have a key for count
 * (ie count of how many attempts before throttle) and a key for seconds.
 * If the key 'allIPs' (case sensitive) is present, then the limit is
 * just per account instead of per IP per account.
 *
 * @since 1.27 allIps support and multiple limits added in 1.27. Prior
 *   to 1.27 this only supported having a single throttle.
 * @warning Requires $wgMainCacheType to be enabled
 */
$wgPasswordAttemptThrottle = [
	// Short term limit
	[ 'count' => 5, 'seconds' => 300 ],
	// Long term limit. We need to balance the risk
	// of somebody using this as a DoS attack to lock someone
	// out of their account, and someone doing a brute force attack.
	[ 'count' => 150, 'seconds' => 60 * 60 * 48 ],
];

/**
 * @var array Map of (grant => right => boolean)
 * Users authorize consumers (like Apps) to act on their behalf but only with
 * a subset of the user's normal account rights (signed off on by the user).
 * The possible rights to grant to a consumer are bundled into groups called
 * "grants". Each grant defines some rights it lets consumers inherit from the
 * account they may act on behalf of. Note that a user granting a right does
 * nothing if that user does not actually have that right to begin with.
 * @since 1.27
 */
$wgGrantPermissions = [];

// @TODO: clean up grants
// @TODO: auto-include read/editsemiprotected rights?

$wgGrantPermissions['basic']['autocreateaccount'] = true;
$wgGrantPermissions['basic']['autoconfirmed'] = true;
$wgGrantPermissions['basic']['autopatrol'] = true;
$wgGrantPermissions['basic']['editsemiprotected'] = true;
$wgGrantPermissions['basic']['ipblock-exempt'] = true;
$wgGrantPermissions['basic']['nominornewtalk'] = true;
$wgGrantPermissions['basic']['patrolmarks'] = true;
$wgGrantPermissions['basic']['purge'] = true;
$wgGrantPermissions['basic']['read'] = true;
$wgGrantPermissions['basic']['writeapi'] = true;

$wgGrantPermissions['highvolume']['bot'] = true;
$wgGrantPermissions['highvolume']['apihighlimits'] = true;
$wgGrantPermissions['highvolume']['noratelimit'] = true;
$wgGrantPermissions['highvolume']['markbotedits'] = true;

$wgGrantPermissions['editpage']['edit'] = true;
$wgGrantPermissions['editpage']['minoredit'] = true;
$wgGrantPermissions['editpage']['applychangetags'] = true;
$wgGrantPermissions['editpage']['changetags'] = true;

$wgGrantPermissions['editprotected'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['editprotected']['editprotected'] = true;

// FIXME: Rename editmycssjs to editmyconfig
$wgGrantPermissions['editmycssjs'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['editmycssjs']['editmyusercss'] = true;
$wgGrantPermissions['editmycssjs']['editmyuserjson'] = true;
$wgGrantPermissions['editmycssjs']['editmyuserjs'] = true;

$wgGrantPermissions['editmyoptions']['editmyoptions'] = true;
$wgGrantPermissions['editmyoptions']['editmyuserjson'] = true;

$wgGrantPermissions['editinterface'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['editinterface']['editinterface'] = true;
$wgGrantPermissions['editinterface']['edituserjson'] = true;
$wgGrantPermissions['editinterface']['editsitejson'] = true;

$wgGrantPermissions['editsiteconfig'] = $wgGrantPermissions['editinterface'];
$wgGrantPermissions['editsiteconfig']['editusercss'] = true;
$wgGrantPermissions['editsiteconfig']['edituserjs'] = true;
$wgGrantPermissions['editsiteconfig']['editsitecss'] = true;
$wgGrantPermissions['editsiteconfig']['editsitejs'] = true;

$wgGrantPermissions['createeditmovepage'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['createeditmovepage']['createpage'] = true;
$wgGrantPermissions['createeditmovepage']['createtalk'] = true;
$wgGrantPermissions['createeditmovepage']['move'] = true;
$wgGrantPermissions['createeditmovepage']['move-rootuserpages'] = true;
$wgGrantPermissions['createeditmovepage']['move-subpages'] = true;
$wgGrantPermissions['createeditmovepage']['move-categorypages'] = true;
$wgGrantPermissions['createeditmovepage']['suppressredirect'] = true;

$wgGrantPermissions['uploadfile']['upload'] = true;
$wgGrantPermissions['uploadfile']['reupload-own'] = true;

$wgGrantPermissions['uploadeditmovefile'] = $wgGrantPermissions['uploadfile'];
$wgGrantPermissions['uploadeditmovefile']['reupload'] = true;
$wgGrantPermissions['uploadeditmovefile']['reupload-shared'] = true;
$wgGrantPermissions['uploadeditmovefile']['upload_by_url'] = true;
$wgGrantPermissions['uploadeditmovefile']['movefile'] = true;
$wgGrantPermissions['uploadeditmovefile']['suppressredirect'] = true;

$wgGrantPermissions['patrol']['patrol'] = true;

$wgGrantPermissions['rollback']['rollback'] = true;

$wgGrantPermissions['blockusers']['block'] = true;
$wgGrantPermissions['blockusers']['blockemail'] = true;

$wgGrantPermissions['viewdeleted']['browsearchive'] = true;
$wgGrantPermissions['viewdeleted']['deletedhistory'] = true;
$wgGrantPermissions['viewdeleted']['deletedtext'] = true;

$wgGrantPermissions['viewrestrictedlogs']['suppressionlog'] = true;

$wgGrantPermissions['delete'] = $wgGrantPermissions['editpage'] +
	$wgGrantPermissions['viewdeleted'];
$wgGrantPermissions['delete']['delete'] = true;
$wgGrantPermissions['delete']['bigdelete'] = true;
$wgGrantPermissions['delete']['deletelogentry'] = true;
$wgGrantPermissions['delete']['deleterevision'] = true;
$wgGrantPermissions['delete']['undelete'] = true;

$wgGrantPermissions['oversight']['suppressrevision'] = true;

$wgGrantPermissions['protect'] = $wgGrantPermissions['editprotected'];
$wgGrantPermissions['protect']['protect'] = true;

$wgGrantPermissions['viewmywatchlist']['viewmywatchlist'] = true;

$wgGrantPermissions['editmywatchlist']['editmywatchlist'] = true;

$wgGrantPermissions['sendemail']['sendemail'] = true;

$wgGrantPermissions['createaccount']['createaccount'] = true;

$wgGrantPermissions['privateinfo']['viewmyprivateinfo'] = true;

$wgGrantPermissions['mergehistory']['mergehistory'] = true;

/**
 * @var array Map of grants to their UI grouping
 * @since 1.27
 */
$wgGrantPermissionGroups = [
	// Hidden grants are implicitly present
	'basic'            => 'hidden',

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

	'highvolume'          => 'high-volume',

	'privateinfo'         => 'private-information',
];

/**
 * @var bool Whether to enable bot passwords
 * @since 1.27
 */
$wgEnableBotPasswords = true;

/**
 * Cluster for the bot_passwords table
 * @var string|bool If false, the normal cluster will be used
 * @since 1.27
 */
$wgBotPasswordsCluster = false;

/**
 * Database name for the bot_passwords table
 *
 * To use a database with a table prefix, set this variable to
 * "{$database}-{$prefix}".
 * @var string|bool If false, the normal database will be used
 * @since 1.27
 */
$wgBotPasswordsDatabase = false;

/** @} */ # end of user rights settings

/************************************************************************//**
 * @name   Proxy scanner settings
 * @{
 */

/**
 * This should always be customised in LocalSettings.php
 */
$wgSecretKey = false;

/**
 * Big list of banned IP addresses.
 *
 * This can have the following formats:
 * - An array of addresses
 * - A string, in which case this is the path to a file
 *   containing the list of IP addresses, one per line
 */
$wgProxyList = [];

/** @} */ # end of proxy scanner settings

/************************************************************************//**
 * @name   Cookie settings
 * @{
 */

/**
 * Default cookie lifetime, in seconds. Setting to 0 makes all cookies session-only.
 */
$wgCookieExpiration = 30 * 86400;

/**
 * Default login cookie lifetime, in seconds. Setting
 * $wgExtendLoginCookieExpiration to null will use $wgCookieExpiration to
 * calculate the cookie lifetime. As with $wgCookieExpiration, 0 will make
 * login cookies session-only.
 */
$wgExtendedLoginCookieExpiration = 180 * 86400;

/**
 * Set to set an explicit domain on the login cookies eg, "justthis.domain.org"
 * or ".any.subdomain.net"
 */
$wgCookieDomain = '';

/**
 * Set this variable if you want to restrict cookies to a certain path within
 * the domain specified by $wgCookieDomain.
 */
$wgCookiePath = '/';

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
$wgCookieSecure = 'detect';

/**
 * By default, MediaWiki checks if the client supports cookies during the
 * login process, so that it can display an informative error message if
 * cookies are disabled. Set this to true if you want to disable this cookie
 * check.
 */
$wgDisableCookieCheck = false;

/**
 * Cookies generated by MediaWiki have names starting with this prefix. Set it
 * to a string to use a custom prefix. Setting it to false causes the database
 * name to be used as a prefix.
 */
$wgCookiePrefix = false;

/**
 * Set authentication cookies to HttpOnly to prevent access by JavaScript,
 * in browsers that support this feature. This can mitigates some classes of
 * XSS attack.
 */
$wgCookieHttpOnly = true;

/**
 * The SameSite cookie attribute used for login cookies. This can be "Lax",
 * "Strict", "None" or empty/null to omit the attribute.
 *
 * This only applies to login cookies, since the correct value for other
 * cookies depends on what kind of cookie it is.
 *
 * @since 1.35
 * @var string|null
 */
$wgCookieSameSite = null;

/**
 * If true, when a cross-site cookie with SameSite=None is sent, a legacy
 * cookie with an "ss0" prefix will also be sent, without SameSite=None. This
 * is a workaround for broken behaviour in Chrome 51-66 and similar browsers.
 *
 * @since 1.35
 * @var bool
 */
$wgUseSameSiteLegacyCookies = false;

/**
 * A list of cookies that vary the cache (for use by extensions)
 */
$wgCacheVaryCookies = [];

/**
 * Override to customise the session name
 */
$wgSessionName = false;

/**
 * Whether to set a cookie when a user is autoblocked. Doing so means that a blocked user, even
 * after logging out and moving to a new IP address, will still be blocked. This cookie will contain
 * an authentication code if $wgSecretKey is set, or otherwise will just be the block ID (in
 * which case there is a possibility of an attacker discovering the names of revdeleted users, so
 * it is best to use this in conjunction with $wgSecretKey being set).
 */
$wgCookieSetOnAutoblock = true;

/**
 * Whether to set a cookie when a logged-out user is blocked. Doing so means that a blocked user,
 * even after moving to a new IP address, will still be blocked. This cookie will contain an
 * authentication code if $wgSecretKey is set, or otherwise will just be the block ID (in which
 * case there is a possibility of an attacker discovering the names of revdeleted users, so it
 * is best to use this in conjunction with $wgSecretKey being set).
 */
$wgCookieSetOnIpBlock = true;

/** @} */ # end of cookie settings }

/************************************************************************//**
 * @name   Profiling, testing and debugging
 *
 * See $wgProfiler for how to enable profiling.
 *
 * @{
 */

/**
 * Filename for debug logging. See https://www.mediawiki.org/wiki/How_to_debug
 * The debug log file should be not be publicly accessible if it is used, as it
 * may contain private data.
 */
$wgDebugLogFile = '';

/**
 * Prefix for debug log lines
 */
$wgDebugLogPrefix = '';

/**
 * If true, instead of redirecting, show a page with a link to the redirect
 * destination. This allows for the inspection of PHP error messages, and easy
 * resubmission of form data. For developer use only.
 */
$wgDebugRedirects = false;

/**
 * If true, log debugging data from action=raw and load.php.
 * This is normally false to avoid overlapping debug entries due to gen=css
 * and gen=js requests.
 */
$wgDebugRawPage = false;

/**
 * Send debug data to an HTML comment in the output.
 *
 * This may occasionally be useful when supporting a non-technical end-user.
 * It's more secure than exposing the debug log file to the web, since the
 * output only contains private data for the current user. But it's not ideal
 * for development use since data is lost on fatal errors and redirects.
 */
$wgDebugComments = false;

/**
 * Write SQL queries to the debug log.
 *
 * This setting is only used $wgLBFactoryConf['class'] is set to
 * '\Wikimedia\Rdbms\LBFactorySimple'; otherwise the DBO_DEBUG flag must be set in
 * the 'flags' option of the database connection to achieve the same functionality.
 */
$wgDebugDumpSql = false;

/**
 * Performance expectations for DB usage
 *
 * @since 1.26
 */
$wgTrxProfilerLimits = [
	// HTTP GET/HEAD requests.
	// Master queries should not happen on GET requests
	'GET' => [
		'masterConns' => 0,
		'writes' => 0,
		'readQueryTime' => 5,
		'readQueryRows' => 10000
	],
	// HTTP POST requests.
	// Master reads and writes will happen for a subset of these.
	'POST' => [
		'readQueryTime' => 5,
		'writeQueryTime' => 1,
		'readQueryRows' => 100000,
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
		// Log master queries under the post-send entry point as they are discouraged
		'masterConns' => 0,
		'writes' => 0,
	],
	// Deferred updates that run after HTTP response is sent for POST requests
	'PostSend-POST' => [
		'readQueryTime' => 5,
		'writeQueryTime' => 1,
		'readQueryRows' => 100000,
		'maxAffected' => 1000
	],
	// Background job runner
	'JobRunner' => [
		'readQueryTime' => 30,
		'writeQueryTime' => 5,
		'readQueryRows' => 100000,
		'maxAffected' => 500 // ballpark of $wgUpdateRowsPerQuery
	],
	// Command-line scripts
	'Maintenance' => [
		'writeQueryTime' => 5,
		'maxAffected' => 1000
	]
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
 * @par Example:
 * @code
 * $wgDebugLogGroups['redis'] = '/var/log/mediawiki/redis.log';
 * @endcode
 *
 * @par Advanced example:
 * @code
 * $wgDebugLogGroups['memcached'] = [
 *     'destination' => '/var/log/mediawiki/memcached.log',
 *     'sample' => 1000,  // log 1 message out of every 1,000.
 *     'level' => \Psr\Log\LogLevel::WARNING
 * ];
 * @endcode
 */
$wgDebugLogGroups = [];

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
 * @par To completely disable logging:
 * @code
 * $wgMWLoggerDefaultSpi = [ 'class' => \MediaWiki\Logger\NullSpi::class ];
 * @endcode
 *
 * @since 1.25
 * @var array $wgMWLoggerDefaultSpi
 * @see MwLogger
 */
$wgMWLoggerDefaultSpi = [
	'class' => \MediaWiki\Logger\LegacySpi::class,
];

/**
 * Display debug data at the bottom of the main content area.
 *
 * Useful for developers and technical users trying to working on a closed wiki.
 */
$wgShowDebug = false;

/**
 * Show the contents of $wgHooks in Special:Version
 */
$wgSpecialVersionShowHooks = false;

/**
 * Whether to show "we're sorry, but there has been a database error" pages.
 * Displaying errors aids in debugging, but may display information useful
 * to an attacker.
 *
 * @deprecated and nonfunctional since 1.32: set $wgShowExceptionDetails and/or
 * $wgShowHostnames instead.
 */
$wgShowSQLErrors = false;

/**
 * If set to true, uncaught exceptions will print the exception message and a
 * complete stack trace to output. This should only be used for debugging, as it
 * may reveal private information in function parameters due to PHP's backtrace
 * formatting.  If set to false, only the exception's class will be shown.
 */
$wgShowExceptionDetails = false;

/**
 * If true, show a backtrace for database errors
 *
 * @note This setting only applies when connection errors and query errors are
 * reported in the normal manner. $wgShowExceptionDetails applies in other cases,
 * including those in which an uncaught exception is thrown from within the
 * exception handler.
 *
 * @deprecated and nonfunctional since 1.32: set $wgShowExceptionDetails instead.
 */
$wgShowDBErrorBacktrace = false;

/**
 * If true, send the exception backtrace to the error log
 */
$wgLogExceptionBacktrace = true;

/**
 * If true, the MediaWiki error handler passes errors/warnings to the default error handler
 * after logging them. The setting is ignored when the track_errors php.ini flag is true.
 */
$wgPropagateErrors = true;

/**
 * Expose backend server host names through the API and various HTML comments
 */
$wgShowHostnames = false;

/**
 * Override server hostname detection with a hardcoded value.
 * Should be a string, default false.
 * @since 1.20
 */
$wgOverrideHostname = false;

/**
 * If set to true MediaWiki will throw notices for some possible error
 * conditions and for deprecated functions.
 */
$wgDevelopmentWarnings = false;

/**
 * Release limitation to wfDeprecated warnings, if set to a release number
 * development warnings will not be generated for deprecations added in releases
 * after the limit.
 */
$wgDeprecationReleaseLimit = false;

/**
 * Profiler configuration.
 *
 * To use a profiler, set $wgProfiler in LocalSetings.php.
 *
 * Options:
 *
 * - 'class' (`string`): The Profiler subclass to use.
 *   Default: ProfilerStub.
 * - 'sampling' (`int`): Only enable the profiler on one in this many requests.
 *   For requests that are not in the sampling,
 *   the 'class' option will be replaced with ProfilerStub.
 *   Default: `1`.
 * - 'threshold' (`float`): Only process the recorded data if the total ellapsed
 *   time for a request is more than this number of seconds.
 *   Default: `0.0`.
 * - 'output' (`string|string[]`):  ProfilerOutput subclass or subclasess to use.
 *   Default: `[]`.
 *
 * The output classes available in MediaWiki core are:
 * ProfilerOutputText, ProfilerOutputStats, and ProfilerOutputDump.
 *
 * - ProfilerOutputText: outputs profiling data in the web page body as
 *   a comment.  You can make the profiling data in HTML render visibly
 *   instead by setting the 'visible' configuration flag.
 *
 * - ProfilerOutputStats: outputs profiling data as StatsD metrics.
 *   It expects that $wgStatsdServer is set to the host (or host:port)
 *   of a statsd server.
 *
 * - ProfilerOutputDump: outputs dump files that are compatible
 *   with the XHProf gui. It expects that `$wgProfiler['outputDir']`
 *   is set as well.
 *
 * Examples:
 *
 * @code
 *  $wgProfiler['class'] = ProfilerXhprof::class;
 *  $wgProfiler['output'] = ProfilerOutputText::class;
 * @endcode
 *
 * @code
 *   $wgProfiler['class'] = ProfilerXhprof:class;
 *   $wgProfiler['output'] = [ ProfilerOutputText::class ];
 *   $wgProfiler['sampling'] = 50; // one every 50 requests
 * @endcode
 *
 * For performance, the profiler is always disabled for CLI scripts as they
 * could be long running and the data would accumulate. Use the '--profiler'
 * parameter of maintenance scripts to override this.
 *
 * @since 1.17.0
 */
$wgProfiler = [];

/**
 * Destination of statsd metrics.
 *
 * A host or host:port of a statsd server. Port defaults to 8125.
 *
 * If not set, statsd metrics will not be collected.
 *
 * @see wfLogProfilingData
 * @since 1.25
 */
$wgStatsdServer = false;

/**
 * Prefix for metric names sent to $wgStatsdServer.
 *
 * @see MediaWikiServices::getInstance()->getStatsdDataFactory
 * @see BufferingStatsdDataFactory
 * @since 1.25
 */
$wgStatsdMetricPrefix = 'MediaWiki';

/**
 * Sampling rate for statsd metrics as an associative array of patterns and rates.
 * Patterns are Unix shell patterns (e.g. 'MediaWiki.api.*').
 * Rates are sampling probabilities (e.g. 0.1 means 1 in 10 events are sampled).
 * @since 1.28
 */
$wgStatsdSamplingRates = [];

/**
 * InfoAction retrieves a list of transclusion links (both to and from).
 * This number puts a limit on that query in the case of highly transcluded
 * templates.
 */
$wgPageInfoTransclusionLimit = 50;

/**
 * Parser test suite files to be run by parserTests.php when no specific
 * filename is passed to it.
 *
 * Extensions using extension.json will have any *.txt file in a
 * tests/parser/ directory automatically run.
 *
 * Core tests can be added to ParserTestRunner::$coreTestFiles.
 *
 * Use full paths.
 *
 * @deprecated since 1.30
 */
$wgParserTestFiles = [];

/**
 * Allow running of javascript test suites via [[Special:JavaScriptTest]] (such as QUnit).
 */
$wgEnableJavaScriptTest = false;

/**
 * Overwrite the caching key prefix with custom value.
 * @since 1.19
 */
$wgCachePrefix = false;

/**
 * Display the new debugging toolbar. This also enables profiling on database
 * queries and other useful output.
 * Will be ignored if $wgUseFileCache or $wgUseCdn is enabled.
 *
 * @since 1.19
 */
$wgDebugToolbar = false;

/** @} */ # end of profiling, testing and debugging }

/************************************************************************//**
 * @name   Search
 * @{
 */

/**
 * Set this to true to disable the full text search feature.
 */
$wgDisableTextSearch = false;

/**
 * Set to true to have nicer highlighted text in search results,
 * by default off due to execution overhead
 */
$wgAdvancedSearchHighlighting = false;

/**
 * Regexp to match word boundaries, defaults for non-CJK languages
 * should be empty for CJK since the words are not separate
 */
$wgSearchHighlightBoundaries = '[\p{Z}\p{P}\p{C}]';

/**
 * Template for OpenSearch suggestions, defaults to API action=opensearch
 *
 * Sites with heavy load would typically have these point to a custom
 * PHP wrapper to avoid firing up mediawiki for every keystroke
 *
 * Placeholders: {searchTerms}
 *
 * @deprecated since 1.25 Use $wgOpenSearchTemplates['application/x-suggestions+json'] instead
 */
$wgOpenSearchTemplate = false;

/**
 * Templates for OpenSearch suggestions, defaults to API action=opensearch
 *
 * Sites with heavy load would typically have these point to a custom
 * PHP wrapper to avoid firing up mediawiki for every keystroke
 *
 * Placeholders: {searchTerms}
 */
$wgOpenSearchTemplates = [
	'application/x-suggestions+json' => false,
	'application/x-suggestions+xml' => false,
];

/**
 * This was previously a used to force empty responses from ApiOpenSearch
 * with the 'suggest' parameter set.
 *
 * @deprecated since 1.35 No longer used
 */
$wgEnableOpenSearchSuggest = true;

/**
 * Integer defining default number of entries to show on
 * OpenSearch call.
 */
$wgOpenSearchDefaultLimit = 10;

/**
 * Minimum length of extract in <Description>. Actual extracts will last until the end of sentence.
 */
$wgOpenSearchDescriptionLength = 100;

/**
 * Expiry time for search suggestion responses
 */
$wgSearchSuggestCacheExpiry = 1200;

/**
 * If you've disabled search semi-permanently, this also disables updates to the
 * table. If you ever re-enable, be sure to rebuild the search table.
 */
$wgDisableSearchUpdate = false;

/**
 * List of namespaces which are searched by default.
 *
 * @par Example:
 * @code
 * $wgNamespacesToBeSearchedDefault[NS_MAIN] = true;
 * $wgNamespacesToBeSearchedDefault[NS_PROJECT] = true;
 * @endcode
 */
$wgNamespacesToBeSearchedDefault = [
	NS_MAIN => true,
];

/**
 * Disable the internal MySQL-based search, to allow it to be
 * implemented by an extension instead.
 */
$wgDisableInternalSearch = false;

/**
 * Set this to a URL to forward search requests to some external location.
 * If the URL includes '$1', this will be replaced with the URL-encoded
 * search term.
 *
 * @par Example:
 * To forward to Google you'd have something like:
 * @code
 * $wgSearchForwardUrl =
 *     'https://www.google.com/search?q=$1' .
 *     '&domains=https://example.com' .
 *     '&sitesearch=https://example.com' .
 *     '&ie=utf-8&oe=utf-8';
 * @endcode
 */
$wgSearchForwardUrl = null;

/**
 * Search form behavior.
 * - true = use Go & Search buttons
 * - false = use Go button & Advanced search link
 *
 * @deprecated since 1.35. Individual skin may optionally continue
 * supporting it as a local skin config variable.
 */
$wgUseTwoButtonsSearchForm = true;

/**
 * Array of namespaces to generate a Google sitemap for when the
 * maintenance/generateSitemap.php script is run, or false if one is to be
 * generated for all namespaces.
 */
$wgSitemapNamespaces = false;

/**
 * Custom namespace priorities for sitemaps. Setting this will allow you to
 * set custom priorities to namespaces when sitemaps are generated using the
 * maintenance/generateSitemap.php script.
 *
 * This should be a map of namespace IDs to priority
 * @par Example:
 * @code
 *  $wgSitemapNamespacesPriorities = [
 *      NS_USER => '0.9',
 *      NS_HELP => '0.0',
 *  ];
 * @endcode
 */
$wgSitemapNamespacesPriorities = false;

/**
 * If true, searches for IP addresses will be redirected to that IP's
 * contributions page. E.g. searching for "1.2.3.4" will redirect to
 * [[Special:Contributions/1.2.3.4]]
 */
$wgEnableSearchContributorsByIP = true;

/** @} */ # end of search settings

/************************************************************************//**
 * @name   Edit user interface
 * @{
 */

/**
 * Path to the GNU diff3 utility. If the file doesn't exist, edit conflicts will
 * fall back to the old behavior (no merging).
 */
$wgDiff3 = '/usr/bin/diff3';

/**
 * Path to the GNU diff utility.
 */
$wgDiff = '/usr/bin/diff';

/**
 * Which namespaces have special treatment where they should be preview-on-open
 * Internally only Category: pages apply, but using this extensions (e.g. Semantic MediaWiki)
 * can specify namespaces of pages they have special treatment for
 */
$wgPreviewOnOpenNamespaces = [
	NS_CATEGORY => true
];

/**
 * Enable the UniversalEditButton for browsers that support it
 * (currently only Firefox with an extension)
 * See http://universaleditbutton.org for more background information
 */
$wgUniversalEditButton = true;

/**
 * If user doesn't specify any edit summary when making a an edit, MediaWiki
 * will try to automatically create one. This feature can be disabled by set-
 * ting this variable false.
 */
$wgUseAutomaticEditSummaries = true;

/** @} */ # end edit UI }

/************************************************************************//**
 * @name   Maintenance
 * See also $wgSiteNotice
 * @{
 */

/**
 * @cond file_level_code
 * Set $wgCommandLineMode if it's not set already, to avoid notices
 */
if ( !isset( $wgCommandLineMode ) ) {
	$wgCommandLineMode = false;
}
/** @endcond */

/**
 * For colorized maintenance script output, is your terminal background dark ?
 */
$wgCommandLineDarkBg = false;

/**
 * Set this to a string to put the wiki into read-only mode. The text will be
 * used as an explanation to users.
 *
 * This prevents most write operations via the web interface. Cache updates may
 * still be possible. To prevent database writes completely, use the read_only
 * option in MySQL.
 */
$wgReadOnly = null;

/**
 * Set this to true to put the wiki watchlists into read-only mode.
 * @var bool
 * @since 1.31
 */
$wgReadOnlyWatchedItemStore = false;

/**
 * If this lock file exists (size > 0), the wiki will be forced into read-only mode.
 * Its contents will be shown to users as part of the read-only warning
 * message.
 *
 * Will default to "{$wgUploadDirectory}/lock_yBgMBwiR" in Setup.php
 */
$wgReadOnlyFile = false;

/**
 * When you run the web-based upgrade utility, it will tell you what to set
 * this to in order to authorize the upgrade process. It will subsequently be
 * used as a password, to authorize further upgrades.
 *
 * For security, do not set this to a guessable string. Use the value supplied
 * by the install/upgrade process. To cause the upgrader to generate a new key,
 * delete the old key from LocalSettings.php.
 */
$wgUpgradeKey = false;

/**
 * Fully specified path to git binary
 */
$wgGitBin = '/usr/bin/git';

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
$wgGitRepositoryViewers = [
	'https://(?:[a-z0-9_]+@)?gerrit.wikimedia.org/r/(?:p/)?(.*)' =>
		'https://gerrit.wikimedia.org/g/%R/+/%H',
	'ssh://(?:[a-z0-9_]+@)?gerrit.wikimedia.org:29418/(.*)' =>
		'https://gerrit.wikimedia.org/g/%R/+/%H',
];

/** @} */ # End of maintenance }

/************************************************************************//**
 * @name   Recent changes, new pages, watchlist and history
 * @{
 */

/**
 * Recentchanges items are periodically purged; entries older than this many
 * seconds will go.
 * Default: 90 days = about three months
 */
$wgRCMaxAge = 90 * 24 * 3600;

/**
 * Page watchers inactive for more than this many seconds are considered inactive.
 * Used mainly by action=info. Default: 180 days = about six months.
 * @since 1.26
 */
$wgWatchersMaxAge = 180 * 24 * 3600;

/**
 * If active watchers (per above) are this number or less, do not disclose it.
 * Left to 1, prevents unprivileged users from knowing for sure that there are 0.
 * Set to -1 if you want to always complement watchers count with this info.
 * @since 1.26
 */
$wgUnwatchedPageSecret = 1;

/**
 * Filter $wgRCLinkDays by $wgRCMaxAge to avoid showing links for numbers
 * higher than what will be stored. Note that this is disabled by default
 * because we sometimes do have RC data which is beyond the limit for some
 * reason, and some users may use the high numbers to display that data which
 * is still there.
 */
$wgRCFilterByAge = false;

/**
 * List of Limits options to list in the Special:Recentchanges and
 * Special:Recentchangeslinked pages.
 */
$wgRCLinkLimits = [ 50, 100, 250, 500 ];

/**
 * List of Days options to list in the Special:Recentchanges and
 * Special:Recentchangeslinked pages.
 *
 * @see ChangesListSpecialPage::getLinkDays
 */
$wgRCLinkDays = [ 1, 3, 7, 14, 30 ];

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
 * @par Examples:
 * @code
 *  $wgRCFeeds['example'] = [
 *      'uri' => 'udp://localhost:1336',
 *      'formatter' => 'JSONRCFeedFormatter',
 *      'add_interwiki_prefix' => false,
 *      'omit_bots' => true,
 *  ];
 * @endcode
 * @code
 *  $wgRCFeeds['example'] = [
 *      'uri' => 'udp://localhost:1338',
 *      'formatter' => 'IRCColourfulRCFeedFormatter',
 *      'add_interwiki_prefix' => false,
 *      'omit_bots' => true,
 *  ];
 * @endcode
 * @code
 *  $wgRCFeeds['example'] = [
 *      'class' => ExampleRCFeed::class,
 *  ];
 * @endcode
 *
 * @since 1.22
 */
$wgRCFeeds = [];

/**
 * Used by RecentChange::getEngine to find the correct engine for a given URI scheme.
 * Keys are scheme names, values are names of FormattedRCFeed sub classes.
 * @since 1.22
 */
$wgRCEngines = [
	'redis' => RedisPubSubFeedEngine::class,
	'udp' => UDPRCFeedEngine::class,
];

/**
 * Treat category membership changes as a RecentChange.
 * Changes are mentioned in RC for page actions as follows:
 *   - creation: pages created with categories are mentioned
 *   - edit: category additions/removals to existing pages are mentioned
 *   - move: nothing is mentioned (unless templates used depend on the title)
 *   - deletion: nothing is mentioned
 *   - undeletion: nothing is mentioned
 *
 * @since 1.27
 */
$wgRCWatchCategoryMembership = false;

/**
 * Use RC Patrolling to check for vandalism (from recent changes and watchlists)
 * New pages and new files are included.
 *
 * @note If you disable all patrolling features, you probably also want to
 *  remove 'patrol' from $wgFilterLogTypes so a show/hide link isn't shown on
 *  Special:Log.
 */
$wgUseRCPatrol = true;

/**
 * Polling rate, in seconds, used by the 'live update' and 'view newest' features
 * of the RCFilters app on SpecialRecentChanges and Special:Watchlist.
 * 0 to disable completely.
 */
$wgStructuredChangeFiltersLiveUpdatePollingRate = 3;

/**
 * Use new page patrolling to check new pages on Special:Newpages
 *
 * @note If you disable all patrolling features, you probably also want to
 *  remove 'patrol' from $wgFilterLogTypes so a show/hide link isn't shown on
 *  Special:Log.
 */
$wgUseNPPatrol = true;

/**
 * Use file patrolling to check new files on Special:Newfiles
 *
 * @note If you disable all patrolling features, you probably also want to
 *  remove 'patrol' from $wgFilterLogTypes so a show/hide link isn't shown on
 *  Special:Log.
 *
 * @since 1.27
 */
$wgUseFilePatrol = true;

/**
 * Provide syndication feeds (RSS, Atom) for, e.g., Recentchanges, Newpages
 */
$wgFeed = true;

/**
 * Set maximum number of results to return in syndication feeds (RSS, Atom) for
 * eg Recentchanges, Newpages.
 */
$wgFeedLimit = 50;

/**
 * _Minimum_ timeout for cached Recentchanges feed, in seconds.
 * A cached version will continue to be served out even if changes
 * are made, until this many seconds runs out since the last render.
 *
 * If set to 0, feed caching is disabled. Use this for debugging only;
 * feed generation can be pretty slow with diffs.
 */
$wgFeedCacheTimeout = 60;

/**
 * When generating Recentchanges RSS/Atom feed, diffs will not be generated for
 * pages larger than this size.
 */
$wgFeedDiffCutoff = 32768;

/**
 * Override the site's default RSS/ATOM feed for recentchanges that appears on
 * every page. Some sites might have a different feed they'd like to promote
 * instead of the RC feed (maybe like a "Recent New Articles" or "Breaking news" one).
 * Should be a format as key (either 'rss' or 'atom') and an URL to the feed
 * as value.
 * @par Example:
 * Configure the 'atom' feed to https://example.com/somefeed.xml
 * @code
 * $wgSiteFeed['atom'] = "https://example.com/somefeed.xml";
 * @endcode
 */
$wgOverrideSiteFeed = [];

/**
 * Available feeds objects.
 * Should probably only be defined when a page is syndicated ie when
 * $wgOut->isSyndicated() is true.
 */
$wgFeedClasses = [
	'rss' => RSSFeed::class,
	'atom' => AtomFeed::class,
];

/**
 * Which feed types should we provide by default?  This can include 'rss',
 * 'atom', neither, or both.
 */
$wgAdvertisedFeedTypes = [ 'atom' ];

/**
 * Show watching users in recent changes, watchlist and page history views
 */
$wgRCShowWatchingUsers = false; # UPO

/**
 * Show the amount of changed characters in recent changes
 */
$wgRCShowChangedSize = true;

/**
 * If the difference between the character counts of the text
 * before and after the edit is below that value, the value will be
 * highlighted on the RC page.
 */
$wgRCChangedSizeThreshold = 500;

/**
 * Show "Updated (since my last visit)" marker in RC view, watchlist and history
 * view for watched pages with new changes
 */
$wgShowUpdatedMarker = true;

/**
 * Disable links to talk pages of anonymous users (IPs) in listings on special
 * pages like page history, Special:Recentchanges, etc.
 */
$wgDisableAnonTalk = false;

/**
 * Allow filtering by change tag in recentchanges, history, etc
 * Has no effect if no tags are defined in valid_tag.
 */
$wgUseTagFilter = true;

/**
 * List of core tags to enable. Available tags are:
 * - 'mw-contentmodelchange': Edit changes content model of a page
 * - 'mw-new-redirect': Edit makes new redirect page (new page or by changing content page)
 * - 'mw-removed-redirect': Edit changes an existing redirect into a non-redirect
 * - 'mw-changed-redirect-target': Edit changes redirect target
 * - 'mw-blank': Edit completely blanks the page
 * - 'mw-replace': Edit removes more than 90% of the content
 * - 'mw-rollback': Edit is a rollback, made through the rollback link or rollback API
 * - 'mw-undo': Edit made through an undo link
 *
 * @var array
 * @since 1.31
 */
$wgSoftwareTags = [
	'mw-contentmodelchange' => true,
	'mw-new-redirect' => true,
	'mw-removed-redirect' => true,
	'mw-changed-redirect-target' => true,
	'mw-blank' => true,
	'mw-replace' => true,
	'mw-rollback' => true,
	'mw-undo' => true,
];

/**
 * If set to an integer, pages that are watched by this many users or more
 * will not require the unwatchedpages permission to view the number of
 * watchers.
 *
 * @since 1.21
 */
$wgUnwatchedPageThreshold = false;

/**
 * Flags (letter symbols) shown in recent changes and watchlist to indicate
 * certain types of edits.
 *
 * To register a new one:
 * @code
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
 * @endcode
 *
 * @since 1.22
 */
$wgRecentChangesFlags = [
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
];

/** @} */ # end RC/watchlist }

/************************************************************************//**
 * @name   Copyright and credits settings
 * @{
 */

/**
 * Override for copyright metadata.
 *
 * This is the name of the page containing information about the wiki's copyright status,
 * which will be added as a link in the footer if it is specified. It overrides
 * $wgRightsUrl if both are specified.
 */
$wgRightsPage = null;

/**
 * Set this to specify an external URL containing details about the content license used on your
 * wiki.
 * If $wgRightsPage is set then this setting is ignored.
 */
$wgRightsUrl = null;

/**
 * If either $wgRightsUrl or $wgRightsPage is specified then this variable gives the text for the
 * link. Otherwise, it will be treated as raw HTML.
 * If using $wgRightsUrl then this value must be specified. If using $wgRightsPage then the name
 * of the page will also be used as the link if this variable is not set.
 */
$wgRightsText = null;

/**
 * Override for copyright metadata.
 */
$wgRightsIcon = null;

/**
 * Set this to true if you want detailed copyright information forms on Upload.
 */
$wgUseCopyrightUpload = false;

/**
 * Set this to the number of authors that you want to be credited below an
 * article text. Set it to zero to hide the attribution block, and a negative
 * number (like -1) to show all authors. Note that this will require 2-3 extra
 * database hits, which can have a not insignificant impact on performance for
 * large wikis.
 */
$wgMaxCredits = 0;

/**
 * If there are more than $wgMaxCredits authors, show $wgMaxCredits of them.
 * Otherwise, link to a separate credits page.
 */
$wgShowCreditsIfMax = true;

/** @} */ # end of copyright and credits settings }

/************************************************************************//**
 * @name   Import / Export
 * @{
 */

/**
 * List of interwiki prefixes for wikis we'll accept as sources for
 * Special:Import and API action=import. Since complete page history can be
 * imported, these should be 'trusted'.
 *
 * This can either be a regular array, or an associative map specifying
 * subprojects on the interwiki map of the target wiki, or a mix of the two,
 * e.g.
 * @code
 *     $wgImportSources = [
 *         'wikipedia' => [ 'cs', 'en', 'fr', 'zh' ],
 *         'wikispecies',
 *         'wikia' => [ 'animanga', 'brickipedia', 'desserts' ],
 *     ];
 * @endcode
 *
 * If you have a very complex import sources setup, you can lazy-load it using
 * the ImportSources hook.
 *
 * If a user has the 'import' permission but not the 'importupload' permission,
 * they will only be able to run imports through this transwiki interface.
 */
$wgImportSources = [];

/**
 * Optional default target namespace for interwiki imports.
 * Can use this to create an incoming "transwiki"-style queue.
 * Set to numeric key, not the name.
 *
 * Users may override this in the Special:Import dialog.
 */
$wgImportTargetNamespace = null;

/**
 * If set to false, disables the full-history option on Special:Export.
 * This is currently poorly optimized for long edit histories, so is
 * disabled on Wikimedia's sites.
 */
$wgExportAllowHistory = true;

/**
 * If set nonzero, Special:Export requests for history of pages with
 * more revisions than this will be rejected. On some big sites things
 * could get bogged down by very very long pages.
 */
$wgExportMaxHistory = 0;

/**
 * Return distinct author list (when not returning full history)
 */
$wgExportAllowListContributors = false;

/**
 * If non-zero, Special:Export accepts a "pagelink-depth" parameter
 * up to this specified level, which will cause it to include all
 * pages linked to from the pages you specify. Since this number
 * can become *insanely large* and could easily break your wiki,
 * it's disabled by default for now.
 *
 * @warning There's a HARD CODED limit of 5 levels of recursion to prevent a
 * crazy-big export from being done by someone setting the depth number too
 * high. In other words, last resort safety net.
 */
$wgExportMaxLinkDepth = 0;

/**
 * Whether to allow the "export all pages in namespace" option
 */
$wgExportFromNamespaces = false;

/**
 * Whether to allow exporting the entire wiki into a single file
 */
$wgExportAllowAll = false;

/**
 * Maximum number of pages returned by the GetPagesFromCategory and
 * GetPagesFromNamespace functions.
 *
 * @since 1.27
 */
$wgExportPagelistLimit = 5000;

/** @} */ # end of import/export }

/*************************************************************************//**
 * @name   Extensions
 * @{
 */

/**
 * A list of callback functions which are called once MediaWiki is fully
 * initialised
 */
$wgExtensionFunctions = [];

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
 * @par Example:
 * @code
 *    $wgExtensionMessagesFiles['ConfirmEdit'] = __DIR__.'/ConfirmEdit.i18n.php';
 * @endcode
 */
$wgExtensionMessagesFiles = [];

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
 * @par Simple example:
 * @code
 *    $wgMessagesDirs['Example'] = __DIR__ . '/i18n';
 * @endcode
 *
 * @par Complex example:
 * @code
 *    $wgMessagesDirs['Example'] = [
 *        __DIR__ . '/lib/ve/i18n',
 *        __DIR__ . '/lib/ooui/i18n',
 *        __DIR__ . '/i18n',
 *    ]
 * @endcode
 * @since 1.23
 */
$wgMessagesDirs = [];

/**
 * Array of files with list(s) of extension entry points to be used in
 * maintenance/mergeMessageFileList.php
 * @since 1.22
 */
$wgExtensionEntryPointListFiles = [];

/**
 * Parser output hooks.
 * This is an associative array where the key is an extension-defined tag
 * (typically the extension name), and the value is a PHP callback.
 * These will be called as an OutputPageParserOutput hook, if the relevant
 * tag has been registered with the parser output object.
 *
 * Registration is done with $pout->addOutputHook( $tag, $data ).
 *
 * The callback has the form:
 * @code
 *    function outputHook( $outputPage, $parserOutput, $data ) { ... }
 * @endcode
 */
$wgParserOutputHooks = [];

/**
 * Whether to include the NewPP limit report as a HTML comment
 */
$wgEnableParserLimitReporting = true;

/**
 * List of valid skin names
 *
 * The key should be the name in all lower case.
 *
 * As of 1.35, the value should be a an array in the form of the ObjectFactory specification.
 *
 * For example for 'foobarskin' where the PHP class is 'MediaWiki\Skins\FooBar\FooBarSkin' set:
 *
 * @par skin.json Example:
 * @code
 * "ValidSkinNames": {
 * 	"foobarskin": {
 * 		"displayname": "FooBarSkin",
 * 		"class": "MediaWiki\\Skins\\FooBar\\FooBarSkin"
 * 	}
 * }
 * @endcode
 *
 * Historically, the value was a properly cased name for the skin (and is still currently
 * supported). This value will be prefixed with "Skin" to create the class name of the
 * skin to load. Use Skin::getSkinNames() as an accessor if you wish to have access to the
 * full list.
 */
$wgValidSkinNames = [];

/**
 * Special page list. This is an associative array mapping the (canonical) names of
 * special pages to either a class name to be instantiated, or a callback to use for
 * creating the special page object. In both cases, the result must be an instance of
 * SpecialPage.
 */
$wgSpecialPages = [];

/**
 * Array mapping class names to filenames, for autoloading.
 */
$wgAutoloadClasses = $wgAutoloadClasses ?? [];

/**
 * Switch controlling legacy case-insensitive classloading.
 * Do not disable if your wiki must support data created by PHP4, or by
 * MediaWiki 1.4 or earlier.
 *
 * @deprecated since 1.35
 */
$wgAutoloadAttemptLowercase = false;

/**
 * Add information about an installed extension, keyed by its type.
 *
 * This is for use from LocalSettings.php and legacy PHP-entrypoint
 * extensions. In general, extensions should (only) declare this
 * information in their extension.json file.
 *
 * The 'name', 'path' and 'author' keys are required.
 *
 * @code
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
 * @endcode
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
 * - descriptionmsg: A message key or an an array with message key and parameters:
 *    `'descriptionmsg' => 'exampleextension-desc',`
 *
 * - description: Description of extension as an inline string instead of
 *    localizable message (omit in favour of 'descriptionmsg').
 *
 * - license-name: Short name of the license (used as label for the link), such
 *   as "GPL-2.0-or-later" or "MIT" (https://spdx.org/licenses/ for a list of identifiers).
 *
 * @see SpecialVersion::getCredits
 */
$wgExtensionCredits = [];

/**
 * Global list of hooks.
 *
 * The key is one of the events made available by MediaWiki, you can find
 * a description for most of them in docs/hooks.txt. The array is used
 * internally by Hook:run().
 *
 * The value can be one of:
 *
 * - A function name:
 * @code
 *     $wgHooks['event_name'][] = $function;
 * @endcode
 * - A function with some data:
 * @code
 *     $wgHooks['event_name'][] = [ $function, $data ];
 * @endcode
 * - A an object method:
 * @code
 *     $wgHooks['event_name'][] = [ $object, 'method' ];
 * @endcode
 * - A closure:
 * @code
 *     $wgHooks['event_name'][] = function ( $hookParam ) {
 *         // Handler code goes here.
 *     };
 * @endcode
 *
 * @warning You should always append to an event array or you will end up
 * deleting a previous registered hook.
 *
 * @warning Hook handlers should be registered at file scope. Registering
 * handlers after file scope can lead to unexpected results due to caching.
 */
$wgHooks = [];

/**
 * List of service wiring files to be loaded by the default instance of MediaWikiServices.
 * Each file listed here is expected to return an associative array mapping service names
 * to instantiator functions. Extensions may add wiring files to define their own services.
 * However, this cannot be used to replace existing services - use the MediaWikiServices
 * hook for that.
 *
 * @see MediaWikiServices
 * @see ServiceContainer::loadWiringFiles() for details on loading service instantiator functions.
 * @see docs/Injection.md for an overview of dependency injection in MediaWiki.
 */
$wgServiceWiringFiles = [
	__DIR__ . '/ServiceWiring.php'
];

/**
 * Maps jobs to their handlers; extensions
 * can add to this to provide custom jobs.
 * A job handler should either be a class name to be instantiated,
 * or (since 1.30) a callback to use for creating the job object.
 * The callback takes (Title, array map of parameters) as arguments.
 */
$wgJobClasses = [
	'deletePage' => DeletePageJob::class,
	'refreshLinks' => RefreshLinksJob::class,
	'deleteLinks' => DeleteLinksJob::class,
	'htmlCacheUpdate' => HTMLCacheUpdateJob::class,
	'sendMail' => EmaillingJob::class,
	'enotifNotify' => EnotifNotifyJob::class,
	'fixDoubleRedirect' => DoubleRedirectJob::class,
	'AssembleUploadChunks' => AssembleUploadChunksJob::class,
	'PublishStashedFile' => PublishStashedFileJob::class,
	'ThumbnailRender' => ThumbnailRenderJob::class,
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
	'enqueue' => EnqueueJob::class, // local queue for multi-DC setups
	'null' => NullJob::class,
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
$wgJobTypesExcludedFromDefaultQueue = [ 'AssembleUploadChunks', 'PublishStashedFile' ];

/**
 * Map of job types to how many job "work items" should be run per second
 * on each job runner process. The meaning of "work items" varies per job,
 * but typically would be something like "pages to update". A single job
 * may have a variable number of work items, as is the case with batch jobs.
 * This is used by runJobs.php and not jobs run via $wgJobRunRate.
 * These settings should be global to all wikis.
 * @var float[]
 */
$wgJobBackoffThrottling = [];

/**
 * Make job runners commit changes for replica DB-lag prone jobs one job at a time.
 * This is useful if there are many job workers that race on replica DB lag checks.
 * If set, jobs taking this many seconds of DB write time have serialized commits.
 *
 * Note that affected jobs may have worse lock contention. Also, if they affect
 * several DBs at once they may have a smaller chance of being atomic due to the
 * possibility of connection loss while queueing up to commit. Affected jobs may
 * also fail due to the commit lock acquisition timeout.
 *
 * @var float|bool
 * @since 1.26
 */
$wgJobSerialCommitThreshold = false;

/**
 * Map of job types to configuration arrays.
 * This determines which queue class and storage system is used for each job type.
 * Job types that do not have explicit configuration will use the 'default' config.
 * These settings should be global to all wikis.
 */
$wgJobTypeConf = [
	'default' => [ 'class' => JobQueueDB::class, 'order' => 'random', 'claimTTL' => 3600 ],
];

/**
 * Whether to include the number of jobs that are queued
 * for the API's maxlag parameter.
 * The total number of jobs will be divided by this to get an
 * estimated second of maxlag. Typically bots backoff at maxlag=5,
 * so setting this to the max number of jobs that should be in your
 * queue divided by 5 should have the effect of stopping bots once
 * that limit is hit.
 *
 * @since 1.29
 */
$wgJobQueueIncludeInMaxLagFactor = false;

/**
 * Additional functions to be performed with updateSpecialPages.
 * Expensive Querypages are already updated.
 */
$wgSpecialPageCacheUpdates = [
	'Statistics' => [ SiteStatsUpdate::class, 'cacheUpdate' ]
];

/**
 * Page property link table invalidation lists. When a page property
 * changes, this may require other link tables to be updated (eg
 * adding __HIDDENCAT__ means the hiddencat tracking category will
 * have been added, so the categorylinks table needs to be rebuilt).
 * This array can be added to by extensions.
 */
$wgPagePropLinkInvalidations = [
	'hiddencat' => 'categorylinks',
];

/** @} */ # End extensions }

/*************************************************************************//**
 * @name   Categories
 * @{
 */

/**
 * Use experimental, DMOZ-like category browser
 */
$wgUseCategoryBrowser = false;

/**
 *  On  category pages, show thumbnail gallery for images belonging to that
 * category instead of listing them as articles.
 */
$wgCategoryMagicGallery = true;

/**
 * Paging limit for categories
 */
$wgCategoryPagingLimit = 200;

/**
 * Specify how category names should be sorted, when listed on a category page.
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
$wgCategoryCollation = 'uppercase';

/** @} */ # End categories }

/*************************************************************************//**
 * @name   Logging
 * @{
 */

/**
 * The logging system has two levels: an event type, which describes the
 * general category and can be viewed as a named subset of all logs; and
 * an action, which is a specific kind of event that can exist in that
 * log type.
 *
 * Note that code should call LogPage::validTypes() to get a list of valid
 * log types instead of checking the global variable.
 */
$wgLogTypes = [
	'',
	'block',
	'protect',
	'rights',
	'delete',
	'upload',
	'move',
	'import',
	'patrol',
	'merge',
	'suppress',
	'tag',
	'managetags',
	'contentmodel',
];

/**
 * This restricts log access to those who have a certain right
 * Users without this will not see it in the option menu and can not view it
 * Restricted logs are not added to recent changes
 * Logs should remain non-transcludable
 * Format: logtype => permissiontype
 */
$wgLogRestrictions = [
	'suppress' => 'suppressionlog'
];

/**
 * Show/hide links on Special:Log will be shown for these log types.
 *
 * This is associative array of log type => boolean "hide by default"
 *
 * See $wgLogTypes for a list of available log types.
 *
 * @par Example:
 * @code
 *   $wgFilterLogTypes = [ 'move' => true, 'import' => false ];
 * @endcode
 *
 * Will display show/hide links for the move and import logs. Move logs will be
 * hidden by default unless the link is clicked. Import logs will be shown by
 * default, and hidden when the link is clicked.
 *
 * A message of the form logeventslist-[type]-log should be added, and will be
 * used for the link text.
 */
$wgFilterLogTypes = [
	'patrol' => true,
	'tag' => true,
	'newusers' => false,
];

/**
 * Lists the message key string for each log type. The localized messages
 * will be listed in the user interface.
 *
 * Extensions with custom log types may add to this array.
 *
 * @since 1.19, if you follow the naming convention log-name-TYPE,
 * where TYPE is your log type, yoy don't need to use this array.
 */
$wgLogNames = [
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
$wgLogHeaders = [
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
];

/**
 * Lists the message key string for formatting individual events of each
 * type and action when listed in the logs.
 *
 * Extensions with custom log types may add to this array.
 */
$wgLogActions = [];

/**
 * The same as above, but here values are names of classes,
 * not messages.
 * @see LogPage::actionText
 * @see LogFormatter
 */
$wgLogActionsHandlers = [
	'block/block' => BlockLogFormatter::class,
	'block/reblock' => BlockLogFormatter::class,
	'block/unblock' => BlockLogFormatter::class,
	'contentmodel/change' => ContentModelLogFormatter::class,
	'contentmodel/new' => ContentModelLogFormatter::class,
	'delete/delete' => DeleteLogFormatter::class,
	'delete/delete_redir' => DeleteLogFormatter::class,
	'delete/event' => DeleteLogFormatter::class,
	'delete/restore' => DeleteLogFormatter::class,
	'delete/revision' => DeleteLogFormatter::class,
	'import/interwiki' => ImportLogFormatter::class,
	'import/upload' => ImportLogFormatter::class,
	'managetags/activate' => LogFormatter::class,
	'managetags/create' => LogFormatter::class,
	'managetags/deactivate' => LogFormatter::class,
	'managetags/delete' => LogFormatter::class,
	'merge/merge' => MergeLogFormatter::class,
	'move/move' => MoveLogFormatter::class,
	'move/move_redir' => MoveLogFormatter::class,
	'patrol/patrol' => PatrolLogFormatter::class,
	'patrol/autopatrol' => PatrolLogFormatter::class,
	'protect/modify' => ProtectLogFormatter::class,
	'protect/move_prot' => ProtectLogFormatter::class,
	'protect/protect' => ProtectLogFormatter::class,
	'protect/unprotect' => ProtectLogFormatter::class,
	'rights/autopromote' => RightsLogFormatter::class,
	'rights/rights' => RightsLogFormatter::class,
	'suppress/block' => BlockLogFormatter::class,
	'suppress/delete' => DeleteLogFormatter::class,
	'suppress/event' => DeleteLogFormatter::class,
	'suppress/reblock' => BlockLogFormatter::class,
	'suppress/revision' => DeleteLogFormatter::class,
	'tag/update' => TagLogFormatter::class,
	'upload/overwrite' => UploadLogFormatter::class,
	'upload/revert' => UploadLogFormatter::class,
	'upload/upload' => UploadLogFormatter::class,
];

/**
 * List of log types that can be filtered by action types
 *
 * To each action is associated the list of log_action
 * subtypes to search for, usually one, but not necessarily so
 * Extensions may append to this array
 * @since 1.27
 */
$wgActionFilteredLogs = [
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
		'delete_redir' => [ 'delete_redir' ],
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
];

/**
 * Maintain a log of newusers at Special:Log/newusers?
 */
$wgNewUserLog = true;

/**
 * Maintain a log of page creations at Special:Log/create?
 * @since 1.32
 */
$wgPageCreationLog = true;

/** @} */ # end logging }

/*************************************************************************//**
 * @name   Special pages (general and miscellaneous)
 * @{
 */

/**
 * Allow special page inclusions such as {{Special:Allpages}}
 */
$wgAllowSpecialInclusion = true;

/**
 * Set this to an array of special page names to prevent
 * maintenance/updateSpecialPages.php from updating those pages.
 * Mapping each special page name to an run mode like 'periodical' if a cronjob is set up.
 */
$wgDisableQueryPageUpdate = false;

/**
 * On Special:Unusedimages, consider images "used", if they are put
 * into a category. Default (false) is not to count those as used.
 */
$wgCountCategorizedImagesAsUsed = false;

/**
 * Maximum number of links to a redirect page listed on
 * Special:Whatlinkshere/RedirectDestination
 */
$wgMaxRedirectLinksRetrieved = 500;

/** @} */ # end special pages }

/*************************************************************************//**
 * @name   Actions
 * @{
 */

/**
 * Array of allowed values for the "title=foo&action=<action>" parameter. Syntax is:
 *     'foo' => 'ClassName'    Load the specified class which subclasses Action
 *     'foo' => true           Load the class FooAction which subclasses Action
 *                             If something is specified in the getActionOverrides()
 *                             of the relevant Page object it will be used
 *                             instead of the default class.
 *     'foo' => false          The action is disabled; show an error message
 * Unsetting core actions will probably cause things to complain loudly.
 */
$wgActions = [
	'credits' => true,
	'delete' => true,
	'edit' => true,
	'editchangetags' => SpecialPageAction::class,
	'history' => true,
	'info' => true,
	'markpatrolled' => true,
	'mcrundo' => McrUndoAction::class,
	'mcrrestore' => McrRestoreAction::class,
	'protect' => true,
	'purge' => true,
	'raw' => true,
	'render' => true,
	'revert' => true,
	'revisiondelete' => SpecialPageAction::class,
	'rollback' => true,
	'submit' => true,
	'unprotect' => true,
	'unwatch' => true,
	'view' => true,
	'watch' => true,
];

/** @} */ # end actions }

/*************************************************************************//**
 * @name   Robot (search engine crawler) policy
 * See also $wgNoFollowLinks.
 * @{
 */

/**
 * Default robot policy.  The default policy is to encourage indexing and fol-
 * lowing of links.  It may be overridden on a per-namespace and/or per-page
 * basis.
 */
$wgDefaultRobotPolicy = 'index,follow';

/**
 * Robot policies per namespaces. The default policy is given above, the array
 * is made of namespace constants as defined in includes/Defines.php.  You can-
 * not specify a different default policy for NS_SPECIAL: it is always noindex,
 * nofollow.  This is because a number of special pages (e.g., ListPages) have
 * many permutations of options that display the same data under redundant
 * URLs, so search engine spiders risk getting lost in a maze of twisty special
 * pages, all alike, and never reaching your actual content.
 *
 * @par Example:
 * @code
 *   $wgNamespaceRobotPolicies = [ NS_TALK => 'noindex' ];
 * @endcode
 */
$wgNamespaceRobotPolicies = [];

/**
 * Robot policies per article. These override the per-namespace robot policies.
 * Must be in the form of an array where the key part is a properly canonicalised
 * text form title and the value is a robot policy.
 *
 * @par Example:
 * @code
 * $wgArticleRobotPolicies = [
 *         'Main Page' => 'noindex,follow',
 *         'User:Bob' => 'index,follow',
 * ];
 * @endcode
 *
 * @par Example that DOES NOT WORK because the names are not canonical text
 * forms:
 * @code
 *   $wgArticleRobotPolicies = [
 *     # Underscore, not space!
 *     'Main_Page' => 'noindex,follow',
 *     # "Project", not the actual project name!
 *     'Project:X' => 'index,follow',
 *     # Needs to be "Abc", not "abc" (unless $wgCapitalLinks is false for that namespace)!
 *     'abc' => 'noindex,nofollow'
 *   ];
 * @endcode
 */
$wgArticleRobotPolicies = [];

/**
 * An array of namespace keys in which the __INDEX__/__NOINDEX__ magic words
 * will not function, so users can't decide whether pages in that namespace are
 * indexed by search engines.  If set to null, default to $wgContentNamespaces.
 *
 * @par Example:
 * @code
 *   $wgExemptFromUserRobotsControl = [ NS_MAIN, NS_TALK, NS_PROJECT ];
 * @endcode
 */
$wgExemptFromUserRobotsControl = null;

/** @} */ # End robot policy }

/************************************************************************//**
 * @name   AJAX, Action API and REST API
 * Note: The AJAX entry point which this section refers to is gradually being
 * replaced by the Action API entry point, api.php. They are essentially
 * equivalent. Both of them are used for dynamic client-side features, via XHR.
 * @{
 */

/**
 *     WARNING: SECURITY THREAT - debug use only
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
$wgDebugAPI = false;

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
 * @code
 *  $wgAPIModules['foo'] = 'ApiFoo';
 *  $wgAPIModules['bar'] = [
 *    'class' => ApiBar::class,
 *    'factory' => function( $main, $name ) { ... }
 *  ];
 *  $wgAPIModules['xyzzy'] = [
 *    'class' => ApiXyzzy::class,
 *    'factory' => [ XyzzyFactory::class, 'newApiModule' ]
 *  ];
 * @endcode
 *
 * Extension modules may override the core modules.
 * See ApiMain::$Modules for a list of the core modules.
 */
$wgAPIModules = [];

/**
 * API format module extensions.
 * Associative array mapping format module name to module specs (see $wgAPIModules).
 * Extension modules may override the core modules.
 *
 * See ApiMain::$Formats for a list of the core format modules.
 */
$wgAPIFormatModules = [];

/**
 * API Query meta module extensions.
 * Associative array mapping meta module name to module specs (see $wgAPIModules).
 * Extension modules may override the core modules.
 *
 * See ApiQuery::$QueryMetaModules for a list of the core meta modules.
 */
$wgAPIMetaModules = [];

/**
 * API Query prop module extensions.
 * Associative array mapping prop module name to module specs (see $wgAPIModules).
 * Extension modules may override the core modules.
 *
 * See ApiQuery::$QueryPropModules for a list of the core prop modules.
 */
$wgAPIPropModules = [];

/**
 * API Query list module extensions.
 * Associative array mapping list module name to module specs (see $wgAPIModules).
 * Extension modules may override the core modules.
 *
 * See ApiQuery::$QueryListModules for a list of the core list modules.
 */
$wgAPIListModules = [];

/**
 * Maximum amount of rows to scan in a DB query in the API
 * The default value is generally fine
 */
$wgAPIMaxDBRows = 5000;

/**
 * The maximum size (in bytes) of an API result.
 * @warning Do not set this lower than $wgMaxArticleSize*1024
 */
$wgAPIMaxResultSize = 8388608;

/**
 * The maximum number of uncached diffs that can be retrieved in one API
 * request. Set this to 0 to disable API diffs altogether
 */
$wgAPIMaxUncachedDiffs = 1;

/**
 * Maximum amount of DB lag on a majority of DB replica DBs to tolerate
 * before forcing bots to retry any write requests via API errors.
 * This should be lower than the 'max lag' value in $wgLBFactoryConf.
 */
$wgAPIMaxLagThreshold = 7;

/**
 * Log file or URL (TCP or UDP) to log API requests to, or false to disable
 * API request logging
 */
$wgAPIRequestLog = false;

/**
 * Set the timeout for the API help text cache. If set to 0, caching disabled
 */
$wgAPICacheHelpTimeout = 60 * 60;

/**
 * The ApiQueryQueryPages module should skip pages that are redundant to true
 * API queries.
 */
$wgAPIUselessQueryPages = [
	'MIMEsearch', // aiprop=mime
	'LinkSearch', // list=exturlusage
	'FileDuplicateSearch', // prop=duplicatefiles
];

/**
 * Enable AJAX framework
 *
 * @deprecated (officially) since MediaWiki 1.31 and ignored since 1.32
 */
$wgUseAjax = true;

/**
 * List of Ajax-callable functions.
 * Extensions acting as Ajax callbacks must register here
 * @deprecated (officially) since 1.27; use the API instead
 */
$wgAjaxExportList = [];

/**
 * Enable AJAX check for file overwrite, pre-upload
 */
$wgAjaxUploadDestCheck = true;

/**
 * Enable previewing licences via AJAX.
 */
$wgAjaxLicensePreview = true;

/**
 * Have clients send edits to be prepared when filling in edit summaries.
 * This gives the server a head start on the expensive parsing operation.
 */
$wgAjaxEditStash = true;

/**
 * Settings for incoming cross-site AJAX requests:
 * Newer browsers support cross-site AJAX when the target resource allows requests
 * from the origin domain by the Access-Control-Allow-Origin header.
 * This is currently only used by the API (requests to api.php)
 * $wgCrossSiteAJAXdomains can be set using a wildcard syntax:
 *
 * - '*' matches any number of characters
 * - '?' matches any 1 character
 *
 * @par Example:
 * @code
 * $wgCrossSiteAJAXdomains = [
 *     'www.mediawiki.org',
 *     '*.wikipedia.org',
 *     '*.wikimedia.org',
 *     '*.wiktionary.org',
 * ];
 * @endcode
 */
$wgCrossSiteAJAXdomains = [];

/**
 * Domains that should not be allowed to make AJAX requests,
 * even if they match one of the domains allowed by $wgCrossSiteAJAXdomains
 * Uses the same syntax as $wgCrossSiteAJAXdomains
 */
$wgCrossSiteAJAXdomainExceptions = [];

/**
 * List of allowed headers for cross-origin API requests.
 */
$wgAllowedCorsHeaders = [
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
];

/**
 * Enable the experimental REST API.
 *
 * @deprecated Since 1.35, defaults to true and is ignored by MediaWiki core itself.
 *             No longer functions as a setting. Will be removed in 1.36.
 */
$wgEnableRestAPI = true;

/**
 * Additional REST API Route files.
 *
 * A common usage is to enable development/experimental endpoints only on test wikis.
 */
$wgRestAPIAdditionalRouteFiles = [];

/** @} */ # End AJAX and API }

/************************************************************************//**
 * @name   Shell and process control
 * @{
 */

/**
 * Maximum amount of virtual memory available to shell processes under linux, in KB.
 */
$wgMaxShellMemory = 307200;

/**
 * Maximum file size created by shell processes under linux, in KB
 * ImageMagick convert for example can be fairly hungry for scratch space
 */
$wgMaxShellFileSize = 102400;

/**
 * Maximum CPU time in seconds for shell processes under Linux
 */
$wgMaxShellTime = 180;

/**
 * Maximum wall clock time (i.e. real time, of the kind the clock on the wall
 * would measure) in seconds for shell processes under Linux
 */
$wgMaxShellWallClockTime = 180;

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
 * @par Example:
 * @code
 *    mkdir -p /sys/fs/cgroup/memory/mediawiki
 *    mkdir -m 0777 /sys/fs/cgroup/memory/mediawiki/job
 *    echo '$wgShellCgroup = "/sys/fs/cgroup/memory/mediawiki/job";' >> LocalSettings.php
 * @endcode
 *
 * The reliability of cgroup cleanup can be improved by installing a
 * notify_on_release script in the root cgroup, see e.g.
 * https://gerrit.wikimedia.org/r/#/c/40784
 */
$wgShellCgroup = false;

/**
 * Executable path of the PHP cli binary. Should be set up on install.
 */
$wgPhpCli = '/usr/bin/php';

/**
 * Locale for LC_ALL, to provide a known environment for locale-sensitive operations
 *
 * For Unix-like operating systems, this should be set to C.UTF-8 or an
 * equivalent to provide the most consistent behavior for locale-sensitive
 * C library operations across different-language wikis. If that locale is not
 * available, use another locale that has a UTF-8 character set.
 *
 * This setting mainly affects the behavior of C library functions, including:
 *  - String collation (order when sorting using locale-sensitive comparison)
 *    - For example, whether "Å" and "A" are considered to be the same letter or
 *      different letters and if different whether it comes after "A" or after
 *      "Z", and whether sorting is case sensitive.
 *  - String character set (how characters beyond basic ASCII are represented)
 *    - We need this to be a UTF-8 character set to work around
 *      https://bugs.php.net/bug.php?id=45132
 *  - Language used for low-level error messages.
 *  - Formatting of date/time and numeric values (e.g. '.' versus ',' as the
 *    decimal separator)
 *
 * MediaWiki provides its own methods and classes to perform many
 * locale-sensitive operations, which are designed to be able to vary locale
 * based on wiki language or user preference:
 *  - MediaWiki's Collation class should generally be used instead of the C
 *    library collation functions when locale-sensitive sorting is needed.
 *  - MediaWiki's Message class should be used for localization of messages
 *    displayed to the user.
 *  - MediaWiki's Language class should be used for formatting numeric and
 *    date/time values.
 *
 * @note If multiple wikis are being served from the same process (e.g. the
 *  same fastCGI or Apache server), this setting must be the same on all those
 *  wikis.
 */
$wgShellLocale = 'C.UTF-8';

/**
 * Method to use to restrict shell commands
 *
 * Supported options:
 * - 'autodetect': Autodetect if any restriction methods are available
 * - 'firejail': Use firejail <https://firejail.wordpress.com/>
 * - false: Don't use any restrictions
 *
 * @note If using firejail with MediaWiki running in a home directory different
 *  from the webserver user, firejail 0.9.44+ is required.
 *
 * @since 1.31
 * @var string|bool
 */
$wgShellRestrictionMethod = 'autodetect';

/** @} */ # End shell }

/************************************************************************//**
 * @name   HTTP client
 * @{
 */

/**
 * Timeout for HTTP requests done internally, in seconds.
 *
 * @since 1.5
 * @var float|int
 */
$wgHTTPTimeout = 25;

/**
 * Timeout for connections done internally (in seconds).
 *
 * Only supported if cURL is installed, ignored otherwise.
 *
 * @since 1.22
 * @var float|int
 */
$wgHTTPConnectTimeout = 5.0;

/**
 * The maximum HTTP request timeout in seconds. If any specified or configured
 * request timeout is larger than this, then this value will be used instead.
 *
 * @since 1.35
 * @var float|int
 */
$wgHTTPMaxTimeout = INF;

/**
 * The maximum HTTP connect timeout in seconds. If any specified or configured
 * connect timeout is larger than this, then this value will be used instead.
 *
 * @since 1.35
 * @var float|int
 */
$wgHTTPMaxConnectTimeout = INF;

/**
 * Timeout for HTTP requests done internally for transwiki imports, in seconds.
 * @since 1.29
 */
$wgHTTPImportTimeout = 25;

/**
 * Timeout for Asynchronous (background) HTTP requests, in seconds.
 */
$wgAsyncHTTPTimeout = 25;

/**
 * Proxy to use for CURL requests.
 */
$wgHTTPProxy = '';

/**
 * Local virtual hosts.
 *
 * This lists domains that are configured as virtual hosts on the same machine.
 *
 * This affects the following:
 * - MWHttpRequest: If a request is to be made to a domain listed here, or any
 *   subdomain thereof, then no proxy will be used.
 *   Command-line scripts are not affected by this setting and will always use
 *   the proxy if it is configured.
 *
 * @since 1.25
 */
$wgLocalVirtualHosts = [];

/**
 * Whether to respect/honour the request ID provided by the incoming request
 * via the `X-Request-Id` header. Set to `true` if the entity sitting in front
 * of Mediawiki sanitises external requests. Default: `false`.
 */
$wgAllowExternalReqID = false;

/** @} */ # End HTTP client }

/************************************************************************//**
 * @name   Job queue
 * @{
 */

/**
 * Number of jobs to perform per request. May be less than one in which case
 * jobs are performed probabalistically. If this is zero, jobs will not be done
 * during ordinary apache requests. In this case, maintenance/runJobs.php should
 * be run periodically.
 */
$wgJobRunRate = 1;

/**
 * When $wgJobRunRate > 0, try to run jobs asynchronously, spawning a new process
 * to handle the job execution, instead of blocking the request until the job
 * execution finishes.
 *
 * @since 1.23
 */
$wgRunJobsAsync = false;

/**
 * Number of rows to update per job
 */
$wgUpdateRowsPerJob = 300;

/**
 * Number of rows to update per query
 */
$wgUpdateRowsPerQuery = 100;

/** @} */ # End job queue }

/************************************************************************//**
 * @name   Miscellaneous
 * @{
 */

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
 * @var string|null
 */
$wgDiffEngine = null;

/**
 * Name of the external diff engine to use.
 * @var string|false Path to an external diff executable
 */
$wgExternalDiffEngine = false;

/**
 * Disable redirects to special pages and interwiki redirects, which use a 302
 * and have no "redirected from" link.
 *
 * @note This is only for articles with #REDIRECT in them. URL's containing a
 * local interwiki prefix (or a non-canonical special page name) are still hard
 * redirected regardless of this setting.
 */
$wgDisableHardRedirects = false;

/**
 * LinkHolderArray batch size
 * For debugging
 */
$wgLinkHolderBatchSize = 1000;

/**
 * By default MediaWiki does not register links pointing to same server in
 * externallinks dataset, use this value to override:
 */
$wgRegisterInternalExternals = false;

/**
 * Maximum number of pages to move at once when moving subpages with a page.
 */
$wgMaximumMovedPages = 100;

/**
 * Fix double redirects after a page move.
 * Tends to conflict with page move vandalism, use only on a private wiki.
 */
$wgFixDoubleRedirects = false;

/**
 * Allow redirection to another page when a user logs in.
 * To enable, set to a string like 'Main Page'
 */
$wgRedirectOnLogin = null;

/**
 * Configuration for processing pool control, for use in high-traffic wikis.
 * An implementation is provided in the PoolCounter extension.
 *
 * This configuration array maps pool types to an associative array. The only
 * defined key in the associative array is "class", which gives the class name.
 * The remaining elements are passed through to the class as constructor
 * parameters.
 *
 * @par Example using local redis instance:
 * @code
 *   $wgPoolCounterConf = [ 'ArticleView' => [
 *     'class' => PoolCounterRedis::class,
 *     'timeout' => 15, // wait timeout in seconds
 *     'workers' => 1, // maximum number of active threads in each pool
 *     'maxqueue' => 5, // maximum number of total threads in each pool
 *     'servers' => [ '127.0.0.1' ],
 *     'redisConfig' => []
 *   ] ];
 * @endcode
 *
 * @par Example using C daemon from https://www.mediawiki.org/wiki/Extension:PoolCounter:
 * @code
 *   $wgPoolCounterConf = [ 'ArticleView' => [
 *     'class' => PoolCounter_Client::class,
 *     'timeout' => 15, // wait timeout in seconds
 *     'workers' => 5, // maximum number of active threads in each pool
 *     'maxqueue' => 50, // maximum number of total threads in each pool
 *     ... any extension-specific options...
 *   ] ];
 * @endcode
 */
$wgPoolCounterConf = null;

/**
 * To disable file delete/restore temporarily
 */
$wgUploadMaintenance = false;

/**
 * Associative array mapping namespace IDs to the name of the content model pages in that namespace
 * should have by default (use the CONTENT_MODEL_XXX constants). If no special content type is
 * defined for a given namespace, pages in that namespace will use the CONTENT_MODEL_WIKITEXT
 * (except for the special case of JS and CS pages).
 *
 * @note To determine the default model for a new page's main slot, or any slot in general,
 * use SlotRoleHandler::getDefaultModel() together with SlotRoleRegistry::getRoleHandler().
 *
 * @since 1.21
 */
$wgNamespaceContentModels = [];

/**
 * How to react if a plain text version of a non-text Content object is requested using
 * ContentHandler::getContentText():
 *
 * * 'ignore': return null
 * * 'fail': throw an MWException
 * * 'serialize': serialize to default format
 *
 * @since 1.21
 */
$wgContentHandlerTextFallback = 'ignore';

/**
 * Determines which types of text are parsed as wikitext. This does not imply that these kinds
 * of texts are also rendered as wikitext, it only means that links, magic words, etc will have
 * the effect on the database they would have on a wikitext page.
 *
 * @todo On the long run, it would be nice to put categories etc into a separate structure,
 * or at least parse only the contents of comments in the scripts.
 *
 * @since 1.21
 */
$wgTextModelsToParse = [
	CONTENT_MODEL_WIKITEXT, // Just for completeness, wikitext will always be parsed.
	CONTENT_MODEL_JAVASCRIPT, // Make categories etc work, people put them into comments.
	CONTENT_MODEL_CSS, // Make categories etc work, people put them into comments.
];

/**
 * Register handlers for specific types of sites.
 *
 * @since 1.21
 */
$wgSiteTypes = [
	'mediawiki' => MediaWikiSite::class,
];

/**
 * Whether the page_props table has a pp_sortkey column. Set to false in case
 * the respective database schema change was not applied.
 * @since 1.23
 */
$wgPagePropsHaveSortkey = true;

/**
 * Secret for session storage.
 * This should be set in LocalSettings.php, otherwise $wgSecretKey will
 * be used.
 * @since 1.27
 */
$wgSessionSecret = false;

/**
 * If for some reason you can't install the PHP OpenSSL extension,
 * you can set this to true to make MediaWiki work again at the cost of storing
 * sensitive session data insecurely. But it would be much more secure to just
 * install the OpenSSL extension.
 * @since 1.27
 */
$wgSessionInsecureSecrets = false;

/**
 * Secret for hmac-based key derivation function (fast,
 * cryptographically secure random numbers).
 * This should be set in LocalSettings.php, otherwise $wgSecretKey will
 * be used.
 * See also: $wgHKDFAlgorithm
 * @since 1.24
 */
$wgHKDFSecret = false;

/**
 * Algorithm for hmac-based key derivation function (fast,
 * cryptographically secure random numbers).
 * See also: $wgHKDFSecret
 * @since 1.24
 */
$wgHKDFAlgorithm = 'sha256';

/**
 * Enable page language feature
 * Allows setting page language in database
 * @var bool
 * @since 1.24
 */
$wgPageLanguageUseDB = false;

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
 * @var array
 * @since 1.25
 */
$wgVirtualRestConfig = [
	'paths' => [],
	'modules' => [],
	'global' => [
		# Timeout in seconds
		'timeout' => 360,
		# 'domain' is set to $wgCanonicalServer in Setup.php
		'forwardCookies' => false,
		'HTTPProxy' => null
	]
];

/**
 * Controls whether zero-result search queries with suggestions should display results for
 * these suggestions.
 *
 * @var bool
 * @since 1.26
 */
$wgSearchRunSuggestedQuery = true;

/*
 * Max time (in seconds) a user-generated transaction can spend in writes.
 * If exceeded, the transaction is rolled back with an error instead of being committed.
 *
 * @var int|bool Disabled if false
 * @since 1.27
 */
$wgMaxUserDBWriteDuration = false;

/*
 * Max time (in seconds) a job-generated transaction can spend in writes.
 * If exceeded, the transaction is rolled back with an error instead of being committed.
 *
 * @var int|bool Disabled if false
 * @since 1.30
 */
$wgMaxJobDBWriteDuration = false;

/**
 * Controls Content-Security-Policy header [Experimental]
 *
 * @see https://www.w3.org/TR/CSP2/
 * @since 1.32
 * @var bool|array true to send default version, false to not send.
 *  If an array, can have parameters:
 *  'default-src' If true or array (of additional urls) will set a default-src
 *    directive, which limits what places things can load from. If false or not
 *    set, will send a default-src directive allowing all sources.
 *  'includeCORS' If true or not set, will include urls from
 *    $wgCrossSiteAJAXdomains as an allowed load sources.
 *  'unsafeFallback' Add unsafe-inline as a script source, as a fallback for
 *    browsers that do not understand nonce-sources [default on].
 *  'useNonces' Require nonces on all inline scripts. If disabled and 'unsafeFallback'
 *    is on, then all inline scripts will be allowed [default true].
 *  'script-src' Array of additional places that are allowed to have JS be loaded from.
 *  'object-src' Array or string of where to load objects from. unset/true means 'none'.
 *    False means omit. (Since 1.35)
 *  'report-uri' true to use MW api [default], false to disable, string for alternate uri
 * @warning May cause slowness on windows due to slow random number generator.
 */
$wgCSPHeader = false;

/**
 * Controls Content-Security-Policy-Report-Only header
 *
 * @since 1.32
 * @var bool|array Same as $wgCSPHeader
 */
$wgCSPReportOnlyHeader = false;

/**
 * List of messages which might contain raw HTML.
 * Extensions should add their insecure raw HTML messages to extension.json.
 * The list is used for access control:
 * changing messages listed here will require editsitecss and editsitejs rights.
 *
 * Message names must be given with underscores rather than spaces and with lowercase first letter.
 *
 * @since 1.32
 * @var string[]
 */
$wgRawHtmlMessages = [
	'copyright',
	'history_copyright',
	'googlesearch',
	'feedback-terms',
	'feedback-termsofuse',
];

/**
 * Mapping of event channels (or channel categories) to EventRelayer configuration.
 *
 * By setting up a PubSub system (like Kafka) and enabling a corresponding EventRelayer class
 * that uses it, MediaWiki can broadcast events to all subscribers. Certain features like WAN
 * cache purging and CDN cache purging will emit events to this system. Appropriate listers can
 * subscribe to the channel and take actions based on the events. For example, a local daemon
 * can run on each CDN cache node and perform local purges based on the URL purge channel events.
 *
 * Some extensions may want to use "channel categories" so that different channels can also share
 * the same custom relayer instance (e.g. when it's likely to be overriden). They can use
 * EventRelayerGroup::getRelayer() based on the category but call notify() on various different
 * actual channels. One reason for this would be that some system have very different performance
 * vs durability needs, so one system (e.g. Kafka) may not be suitable for all uses.
 *
 * The 'default' key is for all channels (or channel categories) without an explicit entry here.
 *
 * @since 1.27
 */
$wgEventRelayerConfig = [
	'default' => [
		'class' => EventRelayerNull::class,
	]
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
 * For the pingback privacy policy, see: https://wikimediafoundation.org/wiki/MediaWiki_Pingback_Privacy_Statement
 *
 * Aggregate pingback data is available at: https://pingback.wmflabs.org/
 *
 * @var bool
 * @since 1.28
 */
$wgPingback = false;

/**
 * List of urls which appear often to be triggering CSP reports
 * but do not appear to be caused by actual content, but by client
 * software inserting scripts (i.e. Ad-Ware).
 * List based on results from Wikimedia logs.
 *
 * @since 1.28
 */
$wgCSPFalsePositiveUrls = [
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
];

/**
 * Shortest CIDR limits that can be checked in any individual range check
 * at Special:Contributions.
 *
 * @var array
 * @since 1.30
 */
$wgRangeContributionsCIDRLimit = [
	'IPv4' => 16,
	'IPv6' => 32,
];

/**
 * The following variables define 3 user experience levels:
 *
 *  - newcomer: has not yet reached the 'learner' level
 *
 *  - learner: has at least $wgLearnerEdits and has been
 *             a member for $wgLearnerMemberSince days
 *             but has not yet reached the 'experienced' level.
 *
 *  - experienced: has at least $wgExperiencedUserEdits edits and
 *                 has been a member for $wgExperiencedUserMemberSince days.
 */
$wgLearnerEdits = 10;
$wgLearnerMemberSince = 4; # days
$wgExperiencedUserEdits = 500;
$wgExperiencedUserMemberSince = 30; # days

/**
 * Mapping of interwiki index prefixes to descriptors that
 * can be used to change the display of interwiki search results.
 *
 * Descriptors are appended to CSS classes of interwiki results
 * which using InterwikiSearchResultWidget.
 *
 * Predefined descriptors include the following words:
 * definition, textbook, news, quotation, book, travel, course
 *
 * @par Example:
 * @code
 * $wgInterwikiPrefixDisplayTypes = [
 *	'iwprefix' => 'definition'
 * ];
 * @endcode
 */
$wgInterwikiPrefixDisplayTypes = [];

/**
 * RevisionStore table schema migration stage (content, slots, content_models & slot_roles tables).
 * Use the SCHEMA_COMPAT_XXX flags. Supported values:
 *
 * - SCHEMA_COMPAT_OLD
 * - SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD
 * - SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW
 * - SCHEMA_COMPAT_OLD
 *
 * Note that reading the old and new schema at the same time is not supported.
 * Attempting to set both read bits in $wgMultiContentRevisionSchemaMigrationStage
 * will result in an InvalidArgumentException.
 *
 * @see Task: https://phabricator.wikimedia.org/T174028
 * @see Commit: https://gerrit.wikimedia.org/r/#/c/378724/
 *
 * @since 1.32
 * @deprecated Since 1.35, the only accepted value is is SCHEMA_COMPAT_NEW.
 *             No longer functions as a setting. Will be removed in 1.36.
 * @var int An appropriate combination of SCHEMA_COMPAT_XXX flags.
 */
$wgMultiContentRevisionSchemaMigrationStage = SCHEMA_COMPAT_NEW;

/**
 * The schema to use per default when generating XML dumps. This allows sites to control
 * explicitly when to make breaking changes to their export and dump format.
 */
$wgXmlDumpSchemaVersion = XML_DUMP_SCHEMA_VERSION_11;

/**
 * Origin Trials tokens.
 *
 * @since 1.33
 * @var array
 */
$wgOriginTrials = [];

/**
 * Enable client-side Priority Hints.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.33
 * @var bool
 */
$wgPriorityHints = false;

/**
 * Ratio of requests that should get Priority Hints when the feature is enabled.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.34
 * @var float
 */
$wgPriorityHintsRatio = 1.0;

/**
 * Enable Element Timing.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.33
 * @var bool
 */
$wgElementTiming = false;

/**
 * Expiry of the endpoint definition for the Reporting API.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.34
 * @var int
 */
$wgReportToExpiry = 86400;

/**
 * List of endpoints for the Reporting API.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.34
 * @var array
 */
$wgReportToEndpoints = [];

/**
 * List of Feature Policy Reporting types to enable.
 * Each entry is turned into a Feature-Policy-Report-Only header.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.34
 * @var array
 */
$wgFeaturePolicyReportOnly = [];

/**
 * Options for Special:Search completion widget form created by SearchFormWidget class.
 * Settings that can be used:
 * - showDescriptions: true/false - whether to show opensearch description results
 * - performSearchOnClick:  true/false - whether to perform search on click
 * See also TitleWidget.js UI widget.
 * @since 1.34
 * @var array
 */
$wgSpecialSearchFormOptions = [];

/**
 * Set true to allow logged-in users to set a preference whether or not matches in
 * search results should force redirection to that page. If false, the preference is
 * not exposed and cannot be altered from site default. To change your site's default
 * preference, set via $wgDefaultUserOptions['search-match-redirect'].
 *
 * @since 1.35
 * @var bool
 */
$wgSearchMatchRedirectPreference = false;

/**
 * Toggles native image lazy loading, via the "loading" attribute.
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.34
 * @var array
 */
$wgNativeImageLazyLoading = false;

/**
 * Option to whether serve the main page as the domain root
 *
 * @warning EXPERIMENTAL!
 *
 * @since 1.34
 * @var bool
 */
$wgMainPageIsDomainRoot = false;

/**
 * Whether to enable the watchlist expiry feature.
 *
 * @since 1.35
 * @var bool
 */
$wgWatchlistExpiry = false;

/**
 * Chance of expired watchlist items being purged on any page edit.
 *
 * Only has effect if $wgWatchlistExpiry is true.
 *
 * If this is zero, expired watchlist items will not be removed
 * and the purgeExpiredWatchlistItems.php maintenance script should be run periodically.
 *
 * @since 1.35
 * @var float
 */
$wgWatchlistPurgeRate = 0.1;

/**
 * Relative maximum duration for watchlist expiries, as accepted by strtotime().
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
 * @var string|null
 */
$wgWatchlistExpiryMaxDuration = '6 months';

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 * @}
 */
