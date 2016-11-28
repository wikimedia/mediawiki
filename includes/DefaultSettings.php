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
 * @defgroup Globalsettings Global settings
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
 * wgConf hold the site configuration.
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
 */
$wgVersion = '1.28.0';

/**
 * Name of the site. It must be changed in LocalSettings.php
 */
$wgSitename = 'MediaWiki';

/**
 * When the wiki is running behind a proxy and this is set to true, assumes that the proxy exposes
 * the wiki on the standard ports (443 for https and 80 for http).
 * @var bool
 * @since 1.26
 */
$wgAssumeProxiesUseDefaultProtocolPorts = true;

/**
 * URL of the server.
 *
 * @par Example:
 * @code
 * $wgServer = 'http://example.com';
 * @endcode
 *
 * This is usually detected correctly by MediaWiki. If MediaWiki detects the
 * wrong server, it will redirect incorrectly after you save a page. In that
 * case, set this variable to fix it.
 *
 * If you want to use protocol-relative URLs on your wiki, set this to a
 * protocol-relative URL like '//example.com' and set $wgCanonicalServer
 * to a fully qualified URL.
 */
$wgServer = WebRequest::detectServer();

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

/************************************************************************//**
 * @name   Script path settings
 * @{
 */

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
 * The extension to append to script names by default.
 *
 * Some hosting providers used PHP 4 for *.php files, and PHP 5 for *.php5.
 * This variable was provided to support those providers.
 *
 * @since 1.11
 * @deprecated since 1.25; support for '.php5' has been phased out of MediaWiki
 *  proper. Backward-compatibility can be maintained by configuring your web
 *  server to rewrite URLs. See RELEASE-NOTES for details.
 */
$wgScriptExtension = '.php';

/**@}*/

/************************************************************************//**
 * @name   URLs and file paths
 *
 * These various web and file path variables are set to their defaults
 * in Setup.php if they are not explicitly set from LocalSettings.php.
 *
 * These will relatively rarely need to be set manually, unless you are
 * splitting style sheets or images outside the main document root.
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
 */
$wgLogo = false;

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
 * @since 1.25
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
 * One of 'never', 'default', 'origin', 'always'. Setting it to false just
 * prevents the meta tag from being output.
 * See http://www.w3.org/TR/referrer-policy/ for details.
 *
 * @since 1.25
 */
$wgReferrerPolicy = false;

/**
 * The local filesystem path to a temporary directory. This is not required to
 * be web accessible.
 *
 * When this setting is set to false, its value will be set through a call
 * to wfTempDir(). See that methods implementation for the actual detection
 * logic.
 *
 * Developers should use the global function wfTempDir() instead of this
 * variable.
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

/**@}*/

/************************************************************************//**
 * @name   Files and file uploads
 * @{
 */

/**
 * Uploads have to be specially set up to be secure
 */
$wgEnableUploads = false;

/**
 * The maximum age of temporary (incomplete) uploaded files
 */
$wgUploadStashMaxAge = 6 * 3600; // 6 hours

/**
 * Allows to move images and other media files
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
 *                      FSRepo is also supported for backwards compatibility.
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
 *   - hashLevels       The number of directory levels for hash-based division of files
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
 *   - scriptExtension   Script extension of the MediaWiki installation, equivalent to
 *                       $wgScriptExtension, e.g. ".php5". Defaults to ".php".
 *
 *   - articleUrl        Equivalent to $wgArticlePath, e.g. https://en.wikipedia.org/wiki/$1
 *   - fetchDescription  Fetch the text of the remote file description page. Equivalent to
 *                       $wgFetchCommonsDescriptions.
 *   - abbrvThreshold    File names over this size will use the short form of thumbnail names.
 *                       Short thumbnail names only have the width, parameters, and the extension.
 *
 * ForeignDBRepo:
 *   - dbType, dbServer, dbUser, dbPassword, dbName, dbFlags
 *                       equivalent to the corresponding member of $wgDBservers
 *   - tablePrefix       Table prefix, the foreign wiki's $wgDBprefix
 *   - hasSharedCache    True if the wiki's shared cache is accessible via the local $wgMemc
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
 * @see Setup.php for an example usage and default initialization.
 */
$wgLocalFileRepo = false;

/**
 * @see $wgLocalFileRepo
 */
$wgForeignFileRepos = [];

/**
 * Use Commons as a remote file repository. Essentially a wrapper, when this
 * is enabled $wgForeignFileRepos will point at Commons with a set of default
 * settings
 */
$wgUseInstantCommons = false;

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
 *  - 'name'         : A unique name for the backend
 *  - 'class'        : The file backend class to use
 *  - 'wikiId'       : A unique string that identifies the wiki (container prefix)
 *  - 'lockManager'  : The name of a lock manager (see $wgLockManagers)
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
 *        Setting 'wikiId' forces the backend to be fully qualified by prefixing
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
 *  - 'name'        : A unique name for the lock manager
 *  - 'class'       : The lock manger class to use
 *
 * See LockManager::__construct() for more details.
 * Additional parameters are specific to the lock manager class used.
 * These settings should be global to all wikis.
 *
 * When using DBLockManager, the 'dbsByBucket' map can reference 'localDBMaster' as
 * a peer database in each bucket. This will result in an extra connection to the domain
 * that the LockManager services, which must also be a valid wiki ID.
 */
$wgLockManagers = [];

/**
 * Show Exif data, on by default if available.
 * Requires PHP's Exif extension: http://www.php.net/manual/en/ref.exif.php
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
 * If you operate multiple wikis, you can define a shared upload path here.
 * Uploads to this wiki will NOT be put there - they will be put into
 * $wgUploadDirectory.
 * If $wgUseSharedUploads is set, the wiki will look in the shared repository if
 * no file of the given name is found in the local repository (for [[File:..]],
 * [[Media:..]] links). Thumbnails will also be looked for and generated in this
 * directory.
 *
 * Note that these configuration settings can now be defined on a per-
 * repository basis for an arbitrary number of file repositories, using the
 * $wgForeignFileRepos variable.
 */
$wgUseSharedUploads = false;

/**
 * Full path on the web server where shared uploads can be found
 */
$wgSharedUploadPath = null;

/**
 * Fetch commons image description pages and display them on the local wiki?
 */
$wgFetchCommonsDescriptions = false;

/**
 * Path on the file system where shared uploads can be found.
 */
$wgSharedUploadDirectory = null;

/**
 * DB name with metadata about shared directory.
 * Set this to false if the uploads do not come from a wiki.
 */
$wgSharedUploadDBname = false;

/**
 * Optional table prefix used in database.
 */
$wgSharedUploadDBprefix = '';

/**
 * Cache shared metadata in memcached.
 * Don't do this if the commons wiki is in a different memcached domain
 */
$wgCacheSharedUploads = true;

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
 * @since 1.22
 */
$wgCopyUploadTimeout = false;

/**
 * Max size for uploads, in bytes. If not set to an array, applies to all
 * uploads. If set to an array, per upload type maximums can be set, using the
 * file and url keys. If the * key is set this value will be used as maximum
 * for non-specified types.
 *
 * @par Example:
 * @code
 * $wgMaxUploadSize = [
 *     '*' => 250 * 1024,
 *     'url' => 500 * 1024,
 * ];
 * @endcode
 * Sets the maximum for all uploads to 250 kB except for upload-by-url, which
 * will have a maximum of 500 kB.
 */
$wgMaxUploadSize = 1024 * 1024 * 100; # 100MB

/**
 * Minimum upload chunk size, in bytes. When using chunked upload, non-final
 * chunks smaller than this will be rejected. May be reduced based on the
 * 'upload_max_filesize' or 'post_max_size' PHP settings.
 * @since 1.26
 */
$wgMinUploadChunkSize = 1024; # 1KB

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
 * @see $wgThumbnailScriptPath
 */
$wgSharedThumbnailScriptPath = false;

/**
 * Set this to false if you do not want MediaWiki to divide your images
 * directory into many subdirectories, for improved performance.
 *
 * It's almost always good to leave this enabled. In previous versions of
 * MediaWiki, some users set this to false to allow images to be added to the
 * wiki by simply copying them into $wgUploadDirectory and then running
 * maintenance/rebuildImages.php to register them in the database. This is no
 * longer recommended, use maintenance/importImages.php instead.
 *
 * @note That this variable may be ignored if $wgLocalFileRepo is set.
 * @todo Deprecate the setting and ultimately remove it from Core.
 */
$wgHashedUploadDirectory = true;

/**
 * Set the following to false especially if you have a set of files that need to
 * be accessible by all wikis, and you do not want to use the hash (path/a/aa/)
 * directory layout.
 */
$wgHashedSharedUploadDirectory = true;

/**
 * Base URL for a repository wiki. Leave this blank if uploads are just stored
 * in a shared directory and not meant to be accessible through a separate wiki.
 * Otherwise the image description pages on the local wiki will link to the
 * image description page on this wiki.
 *
 * Please specify the namespace, as in the example below.
 */
$wgRepositoryBaseUrl = "https://commons.wikimedia.org/wiki/File:";

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
	'php', 'phtml', 'php3', 'php4', 'php5', 'phps',
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
 * Plugins for page content model handling.
 * Each entry in the array maps a model id to a class name or callback
 * that creates an instance of the appropriate ContentHandler subclass.
 *
 * @since 1.21
 */
$wgContentHandlers = [
	// the usual case
	CONTENT_MODEL_WIKITEXT => 'WikitextContentHandler',
	// dumb version, no syntax highlighting
	CONTENT_MODEL_JAVASCRIPT => 'JavaScriptContentHandler',
	// simple implementation, for use by extensions, etc.
	CONTENT_MODEL_JSON => 'JsonContentHandler',
	// dumb version, no syntax highlighting
	CONTENT_MODEL_CSS => 'CssContentHandler',
	// plain text, for use by extensions, etc.
	CONTENT_MODEL_TEXT => 'TextContentHandler',
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
 * Disallow <title> element in SVG files.
 *
 * MediaWiki will reject HTMLesque tags in uploaded files due to idiotic
 * browsers which can not perform basic stuff like MIME detection and which are
 * vulnerable to further idiots uploading crap files as images.
 *
 * When this directive is on, "<title>" will be allowed in files with an
 * "image/svg+xml" MIME type. You should leave this disabled if your web server
 * is misconfigured and doesn't send appropriate MIME types for SVG images.
 */
$wgAllowTitlesInSVG = false;

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
$wgTiffThumbnailType = false;

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
 * Allow thumbnail rendering on page view. If this is false, a valid
 * thumbnail URL is still output, but no file will be created at
 * the target location. This may save some time if you have a
 * thumb.php or 404 handler set up which is faster than the regular
 * webserver(s).
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
 * Sets the MIME type definition file to use by MimeMagic.php.
 * Set to null, to use built-in defaults only.
 * example: $wgMimeTypeFile = '/etc/mime.types';
 */
$wgMimeTypeFile = 'includes/mime.types';

/**
 * Sets the MIME type info file to use by MimeMagic.php.
 * Set to null, to use built-in defaults only.
 */
$wgMimeInfoFile = 'includes/mime.info';

/**
 * Sets an external MIME detector program. The command must print only
 * the MIME type to standard output.
 * The name of the file to process will be appended to the command given here.
 * If not set or NULL, PHP's fileinfo extension will be used if available.
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
 * change it if you alter the array (see bug 8858).
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
 * When using the "http" wgUploadThumbnailRenderMethod, lets one specify a custom Host HTTP header.
 *
 * @since 1.25
 */
$wgUploadThumbnailRenderHttpCustomHost = false;

/**
 * When using the "http" wgUploadThumbnailRenderMethod, lets one specify a custom domain to send the
 * HTTP request to.
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
 * Default parameters for the "<gallery>" tag
 */
$wgGalleryOptions = [
	'imagesPerRow' => 0, // Default number of images per-row in the gallery. 0 -> Adapt to screensize
	'imageWidth' => 120, // Width of the cells containing images in galleries (in "px")
	'imageHeight' => 120, // Height of the cells containing images in galleries (in "px")
	'captionLength' => true, // Deprecated @since 1.28
	                         // Length to truncate filename to in caption when using "showfilename".
	                         // A value of 'true' will truncate the filename to one line using CSS
	                         // and will be the behaviour after deprecation.
	'showBytes' => true, // Show the filesize in bytes in categories
	'mode' => 'traditional',
];

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
 *
 * On older browsers, a JavaScript polyfill switches the appropriate images in after loading
 * the original low-resolution versions depending on the reported window.devicePixelRatio.
 * The polyfill can be found in the jquery.hidpi module.
 */
$wgResponsiveImages = true;

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
 * http://sourceforge.net/tracker/index.php?func=detail&aid=1704049&group_id=32953&atid=406583
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
 * Defaults to "wikiadmin@$wgServerName".
 */
$wgEmergencyContact = false;

/**
 * Sender email address for e-mail notifications.
 *
 * The address we use as sender when a user requests a password reminder.
 *
 * Defaults to "apache@$wgServerName".
 */
$wgPasswordSender = false;

/**
 * Sender name for e-mail notifications.
 *
 * @deprecated since 1.23; use the system message 'emailsender' instead.
 */
$wgPasswordSenderName = 'MediaWiki Mail';

/**
 * Reply-To address for e-mail notifications.
 *
 * Defaults to $wgPasswordSender.
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
 * Set to true to put the sending user's email in a Reply-To header
 * instead of From. ($wgEmergencyContact will be used as From.)
 *
 * Some mailers (eg SMTP) set the SMTP envelope sender to the From value,
 * which can cause problems with SPF validation and leak recipient addresses
 * when bounces are sent to the sender.
 */
$wgUserEmailUseReplyTo = false;

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
 * True: from page editor if s/he opted-in. False: Enotif mails appear to come
 * from $wgEmergencyContact
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
 * Set the Reply-to address in notifications to the editor's address, if user
 * allowed this in the preferences.
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
 * Database host name or IP address
 */
$wgDBserver = 'localhost';

/**
 * Database port number (for PostgreSQL and Microsoft SQL Server).
 */
$wgDBport = 5432;

/**
 * Name of the database
 */
$wgDBname = 'my_wiki';

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
 * This setting is only used $wgLBFactoryConf['class'] is set to
 * 'LBFactorySimple' and $wgDBservers is an empty array; otherwise
 * the DBO_SSL flag must be set in the 'flags' option of the database
 * connection to achieve the same functionality.
 */
$wgDBssl = false;

/**
 * Whether to use compression in DB connection.
 *
 * This setting is only used $wgLBFactoryConf['class'] is set to
 * 'LBFactorySimple' and $wgDBservers is an empty array; otherwise
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
 * Leave as null to select the default search engine for the
 * selected database type (eg SearchMySQL), or set to a class
 * name to override to a custom search engine.
 */
$wgSearchType = null;

/**
 * Alternative search types
 * Sometimes you want to support multiple search engines for testing. This
 * allows users to select their search engine of choice via url parameters
 * to Special:Search and the action=search API. If using this, there's no
 * need to add $wgSearchType to it, that is handled automatically.
 */
$wgSearchTypeAlternatives = null;

/**
 * Table name prefix
 */
$wgDBprefix = '';

/**
 * MySQL table options to use during installation or update
 */
$wgDBTableOptions = 'ENGINE=InnoDB';

/**
 * SQL Mode - default is turning off all modes, including strict, if set.
 * null can be used to skip the setting for performance reasons and assume
 * DBA has done his best job.
 * String override can be used for some additional fun :-)
 */
$wgSQLMode = '';

/**
 * Mediawiki schema
 */
$wgDBmwschema = null;

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
 * @deprecated since 1.21 In new code, use the $wiki parameter to wfGetLB() to
 *   access remote databases. Using wfGetLB() allows the shared database to
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
 *
 *   - load:        Ratio of DB_REPLICA load, must be >=0, the sum of all loads must be >0.
 *                  If this is zero for any given server, no normal query traffic will be
 *                  sent to it. It will be excluded from lag checks in maintenance scripts.
 *                  The only way it can receive traffic is if groupLoads is used.
 *
 *   - groupLoads:  array of load ratios, the key is the query group name. A query may belong
 *                  to several groups, the most specific group defined here is used.
 *
 *   - flags:       bit field
 *                  - DBO_DEFAULT -- turns on DBO_TRX only if "cliMode" is off (recommended)
 *                  - DBO_DEBUG -- equivalent of $wgDebugDumpSql
 *                  - DBO_TRX -- wrap entire request in a transaction
 *                  - DBO_NOBUFFER -- turn off buffering (not useful in LocalSettings.php)
 *                  - DBO_PERSISTENT -- enables persistent database connections
 *                  - DBO_SSL -- uses SSL/TLS encryption in database connections, if available
 *                  - DBO_COMPRESS -- uses internal compression in database connections,
 *                                    if available
 *
 *   - max lag:     (optional) Maximum replication lag before a replica DB goes out of rotation
 *   - is static:   (optional) Set to true if the dataset is static and no replication is used.
 *   - cliMode:     (optional) Connection handles will not assume that requests are short-lived
 *                  nor that INSERT..SELECT can be rewritten into a buffered SELECT and INSERT.
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
$wgLBFactoryConf = [ 'class' => 'LBFactorySimple' ];

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
 * http://php.net/manual/en/timezones.php
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
 * Set to true to engage MySQL 4.1/5.0 charset-related features;
 * for now will just cause sending of 'SET NAMES=utf8' on connect.
 *
 * @warning THIS IS EXPERIMENTAL!
 *
 * May break if you're not using the table defs from mysql5/tables.sql.
 * May break if you're upgrading an existing wiki if set differently.
 * Broken symptoms likely to include incorrect behavior with page titles,
 * usernames, comments etc containing non-ASCII characters.
 * Might also cause failures on the object cache and other things.
 *
 * Even correct usage may cause failures with Unicode supplementary
 * characters (those not in the Basic Multilingual Plane) unless MySQL
 * has enhanced their Unicode support.
 */
$wgDBmysql5 = false;

/**
 * Set true to enable Oracle DCRP (supported from 11gR1 onward)
 *
 * To use this feature set to true and use a datasource defined as
 * POOLED (i.e. in tnsnames definition set server=pooled in connect_data
 * block).
 *
 * Starting from 11gR1 you can use DCRP (Database Resident Connection
 * Pool) that maintains established sessions and reuses them on new
 * connections.
 *
 * Not completely tested, but it should fall back on normal connection
 * in case the pool is full or the datasource is not configured as
 * pooled.
 * And the other way around; using oci_pconnect on a non pooled
 * datasource should produce a normal connection.
 *
 * When it comes to frequent shortlived DB connections like with MW
 * Oracle tends to s***. The problem is the driver connects to the
 * database reasonably fast, but establishing a session takes time and
 * resources. MW does not rely on session state (as it does not use
 * features such as package variables) so establishing a valid session
 * is in this case an unwanted overhead that just slows things down.
 *
 * @warning EXPERIMENTAL!
 *
 */
$wgDBOracleDRCP = false;

/**
 * Other wikis on this site, can be administered from a single developer
 * account.
 * Array numeric key => database name
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

/**
 * Use Windows Authentication instead of $wgDBuser / $wgDBpassword for MS SQL Server
 */
$wgDBWindowsAuthentication = false;

/**@}*/ # End of DB settings }

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
 * Used by LBFactorySimple, may be ignored if $wgLBFactoryConf is set to
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
 *   - CACHE_ACCEL:      APC, APCU, XCache or WinCache
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
	CACHE_NONE => [ 'class' => 'EmptyBagOStuff', 'reportDupes' => false ],
	CACHE_DB => [ 'class' => 'SqlBagOStuff', 'loggroup' => 'SQLBagOStuff' ],

	CACHE_ANYTHING => [ 'factory' => 'ObjectCache::newAnything' ],
	CACHE_ACCEL => [ 'factory' => 'ObjectCache::getLocalServerInstance' ],
	CACHE_MEMCACHED => [ 'class' => 'MemcachedPhpBagOStuff', 'loggroup' => 'memcached' ],

	'db-replicated' => [
		'class'       => 'ReplicatedBagOStuff',
		'readFactory' => [
			'class' => 'SqlBagOStuff',
			'args'  => [ [ 'slaveOnly' => true ] ]
		],
		'writeFactory' => [
			'class' => 'SqlBagOStuff',
			'args'  => [ [ 'slaveOnly' => false ] ]
		],
		'loggroup'  => 'SQLBagOStuff',
		'reportDupes' => false
	],

	'apc' => [ 'class' => 'APCBagOStuff', 'reportDupes' => false ],
	'apcu' => [ 'class' => 'APCUBagOStuff', 'reportDupes' => false ],
	'xcache' => [ 'class' => 'XCacheBagOStuff', 'reportDupes' => false ],
	'wincache' => [ 'class' => 'WinCacheBagOStuff', 'reportDupes' => false ],
	'memcached-php' => [ 'class' => 'MemcachedPhpBagOStuff', 'loggroup' => 'memcached' ],
	'memcached-pecl' => [ 'class' => 'MemcachedPeclBagOStuff', 'loggroup' => 'memcached' ],
	'hash' => [ 'class' => 'HashBagOStuff', 'reportDupes' => false ],
];

/**
 * Main Wide-Area-Network cache type. This should be a cache with fast access,
 * but it may have limited space. By default, it is disabled, since the basic stock
 * cache is not fast enough to make it worthwhile. For single data-center setups, this can
 * simply be pointed to a cache in $wgWANObjectCaches that uses a local $wgObjectCaches
 * cache with a relayer of type EventRelayerNull.
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
 * Each WAN cache wraps a registered object cache (for the local cluster)
 * and it must also be configured to point to a PubSub instance. Subscribers
 * must be configured to relay purges to the actual cache servers.
 *
 * The format is an associative array where the key is a cache identifier, and
 * the value is an associative array of parameters. The "cacheId" parameter is
 * a cache identifier from $wgObjectCaches. The "channels" parameter is a map of
 * actions ('purge') to PubSub channels defined in $wgEventRelayerConfig.
 * The "loggroup" parameter controls where log events are sent.
 *
 * @since 1.26
 */
$wgWANObjectCaches = [
	CACHE_NONE => [
		'class'    => 'WANObjectCache',
		'cacheId'  => CACHE_NONE,
		'channels' => []
	]
	/* Example of a simple single data-center cache:
	'memcached-php' => [
		'class'    => 'WANObjectCache',
		'cacheId'  => 'memcached-php',
		'channels' => [ 'purge' => 'wancache-main-memcached-purge' ]
	]
	*/
];

/**
 * Main object stash type. This should be a fast storage system for storing
 * lightweight data like hit counters and user activity. Sites with multiple
 * data-centers should have this use a store that replicates all writes. The
 * store should have enough consistency for CAS operations to be usable.
 * Reads outside of those needed for merge() may be eventually consistent.
 *
 * The options are:
 *   - db:      Store cache objects in the DB
 *   - (other): A string may be used which identifies a cache
 *              configuration in $wgObjectCaches
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
 * Deprecated alias for $wgSessionsInObjectCache.
 *
 * @deprecated since 1.20; Use $wgSessionsInObjectCache
 */
$wgSessionsInMemcached = true;

/**
 * @deprecated since 1.27, session data is always stored in object cache.
 */
$wgSessionsInObjectCache = true;

/**
 * The expiry time to use for session storage, in seconds.
 */
$wgObjectCacheSessionExpiry = 3600;

/**
 * @deprecated since 1.27, MediaWiki\Session\SessionManager doesn't use PHP session storage.
 */
$wgSessionHandler = null;

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
 * If enabled, will send MemCached debugging information to $wgDebugLogFile
 */
$wgMemCachedDebug = false;

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
 * Localisation cache configuration. Associative array with keys:
 * class:       The class to use. May be overridden by extensions.
 *
 * store:       The location to store cache data. May be 'files', 'array', 'db' or
 *              'detect'. If set to "files", data will be in CDB files. If set
 *              to "db", data will be stored to the database. If set to
 *              "detect", files will be used if $wgCacheDirectory is set,
 *              otherwise the database will be used.
 *              "array" is an experimental option that uses PHP files that
 *              store static arrays.
 *
 * storeClass:  The class name for the underlying storage. If set to a class
 *              name, it overrides the "store" setting.
 *
 * storeDirectory:  If the store class puts its data in files, this is the
 *                  directory it will use. If this is false, $wgCacheDirectory
 *                  will be used.
 *
 * manualRecache:   Set this to true to disable cache updates on web requests.
 *                  Use maintenance/rebuildLocalisationCache.php instead.
 */
$wgLocalisationCacheConf = [
	'class' => 'LocalisationCache',
	'store' => 'detect',
	'storeClass' => false,
	'storeDirectory' => false,
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
 * Bump this number when changing the global style sheets and JavaScript.
 *
 * It should be appended in the query string of static CSS and JS includes,
 * to ensure that client-side caches do not keep obsolete copies of global
 * styles.
 */
$wgStyleVersion = '303';

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
 * Kept for extension compatibility; see $wgParserCacheType
 * @deprecated since 1.26
 */
$wgEnableParserCache = true;

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
 * When using the file cache, we can store the cached HTML gzipped to save disk
 * space. Pages will then also be served compressed to clients that support it.
 *
 * Requires zlib support enabled in PHP.
 */
$wgUseGzip = false;

/**
 * Clock skew or the one-second resolution of time() can occasionally cause cache
 * problems when the user requests two pages within a short period of time. This
 * variable adds a given number of seconds to vulnerable timestamps, thereby giving
 * a grace period.
 */
$wgClockSkewFudge = 5;

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
 * although they are referred to as Squid settings for historical reasons.
 *
 * Achieving a high hit ratio with an HTTP proxy requires special
 * configuration. See https://www.mediawiki.org/wiki/Manual:Squid_caching for
 * more details.
 *
 * @{
 */

/**
 * Enable/disable CDN.
 * See https://www.mediawiki.org/wiki/Manual:Squid_caching
 */
$wgUseSquid = false;

/**
 * If you run Squid3 with ESI support, enable this (default:false):
 */
$wgUseESI = false;

/**
 * Send the Key HTTP header for better caching.
 * See https://datatracker.ietf.org/doc/draft-fielding-http-key/ for details.
 * @since 1.27
 */
$wgUseKeyHeader = false;

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
 */
$wgSquidMaxage = 18000;

/**
 * Cache timeout for the CDN when DB replica DB lag is high
 * @see $wgSquidMaxage
 * @since 1.27
 */
$wgCdnMaxageLagged = 30;

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
 * a purge to all nodes, then the rebound puge will clear cdn2 after cdn1 was cleared.
 *
 * @since 1.27
 */
$wgCdnReboundPurgeDelay = 0;

/**
 * Cache timeout for the CDN when a response is known to be wrong or incomplete (due to load)
 * @see $wgSquidMaxage
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
 */
$wgSquidServers = [];

/**
 * As above, except these servers aren't purged on page changes; use to set a
 * list of trusted proxies, etc. Supports both individual IP addresses and
 * CIDR blocks.
 * @since 1.23 Supports CIDR ranges
 */
$wgSquidServersNoPurge = [];

/**
 * Whether to use a Host header in purge requests sent to the proxy servers
 * configured in $wgSquidServers. Set this to false to support Squid
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
 *
 * $wgHTCPRouting replaces $wgHTCPMulticastRouting that was introduced in 1.20.
 * For back compatibility purposes, whenever its array is empty
 * $wgHTCPMutlicastRouting will be used as a fallback if it not null.
 *
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
 * Warning: Don't use language codes listed in $wgDummyLanguageCodes like "no"
 * for Norwegian (use "nb" instead), or things will break unexpectedly.
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
 * List of language names or overrides for default names in Names.php
 */
$wgExtraLanguageNames = [];

/**
 * List of language codes that don't correspond to an actual language.
 * These codes are mostly left-offs from renames, or other legacy things.
 * This array makes them not appear as a selectable language on the installer,
 * and excludes them when running the transstat.php script.
 */
$wgDummyLanguageCodes = [
	'als' => 'gsw',
	'bat-smg' => 'sgs',
	'be-x-old' => 'be-tarask',
	'bh' => 'bho',
	'fiu-vro' => 'vro',
	'no' => 'nb',
	'qqq' => 'qqq', # Used for message documentation.
	'qqx' => 'qqx', # Used for viewing message keys.
	'roa-rup' => 'rup',
	'simple' => 'en',
	'zh-classical' => 'lzh',
	'zh-min-nan' => 'nan',
	'zh-yue' => 'yue',
];

/**
 * Set this to true to replace Arabic presentation forms with their standard
 * forms in the U+0600-U+06FF block. This only works if $wgLanguageCode is
 * set to "ar".
 *
 * Note that pages with titles containing presentation forms will become
 * inaccessible, run maintenance/cleanupTitles.php to fix this.
 */
$wgFixArabicUnicode = true;

/**
 * Set this to true to replace ZWJ-based chillu sequences in Malayalam text
 * with their Unicode 5.1 equivalents. This only works if $wgLanguageCode is
 * set to "ml". Note that some clients (even new clients as of 2010) do not
 * support these characters.
 *
 * If you enable this on an existing wiki, run maintenance/cleanupTitles.php to
 * fix any ZWJ sequences in existing page titles.
 */
$wgFixMalayalamUnicode = true;

/**
 * Set this to always convert certain Unicode sequences to modern ones
 * regardless of the content language. This has a small performance
 * impact.
 *
 * See $wgFixArabicUnicode and $wgFixMalayalamUnicode for conversion
 * details.
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
 * Browser Blacklist for unicode non compliant browsers. Contains a list of
 * regexps : "/regexp/"  matching problematic browsers. These browsers will
 * be served encoded unicode in the edit box instead of real unicode.
 */
$wgBrowserBlackList = [
	/**
	 * Netscape 2-4 detection
	 * The minor version may contain strings such as "Gold" or "SGoldC-SGI"
	 * Lots of non-netscape user agents have "compatible", so it's useful to check for that
	 * with a negative assertion. The [UIN] identifier specifies the level of security
	 * in a Netscape/Mozilla browser, checking for it rules out a number of fakers.
	 * The language string is unreliable, it is missing on NS4 Mac.
	 *
	 * Reference: http://www.psychedelix.com/agents/index.shtml
	 */
	'/^Mozilla\/2\.[^ ]+ [^(]*?\((?!compatible).*; [UIN]/',
	'/^Mozilla\/3\.[^ ]+ [^(]*?\((?!compatible).*; [UIN]/',
	'/^Mozilla\/4\.[^ ]+ [^(]*?\((?!compatible).*; [UIN]/',

	/**
	 * MSIE on Mac OS 9 is teh sux0r, converts þ to <thorn>, ð to <eth>,
	 * Þ to <THORN> and Ð to <ETH>
	 *
	 * Known useragents:
	 * - Mozilla/4.0 (compatible; MSIE 5.0; Mac_PowerPC)
	 * - Mozilla/4.0 (compatible; MSIE 5.15; Mac_PowerPC)
	 * - Mozilla/4.0 (compatible; MSIE 5.23; Mac_PowerPC)
	 * - [...]
	 *
	 * @link https://en.wikipedia.org/w/index.php?diff=12356041&oldid=12355864
	 * @link https://en.wikipedia.org/wiki/Template%3AOS9
	 */
	'/^Mozilla\/4\.0 \(compatible; MSIE \d+\.\d+; Mac_PowerPC\)/',

	/**
	 * Google wireless transcoder, seems to eat a lot of chars alive
	 * https://it.wikipedia.org/w/index.php?title=Luciano_Ligabue&diff=prev&oldid=8857361
	 */
	'/^Mozilla\/4\.0 \(compatible; MSIE 6.0; Windows NT 5.0; Google Wireless Transcoder;\)/'
];

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
 * Expiry time for the message cache key
 */
$wgMsgCacheExpiry = 86400;

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
 * http://php.net/manual/en/timezones.php
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
 * Previously used as content type in HTML script tags. This is now ignored since
 * HTML5 doesn't require a MIME type for script tags (javascript is the default).
 * It was also previously used by RawAction to determine the ctype query parameter
 * value that will result in a javascript response.
 * @deprecated since 1.22
 */
$wgJsMimeType = null;

/**
 * The default xmlns attribute. The option to define this has been removed.
 * The value of this variable is no longer used by core and is set to a fixed
 * value in Setup.php for compatibility with extensions that depend on the value
 * of this variable being set. Such a dependency however is deprecated.
 * @deprecated since 1.22
 */
$wgXhtmlDefaultNamespace = null;

/**
 * Previously used to determine if we should output an HTML5 doctype.
 * This is no longer used as we always output HTML5 now. For compatibility with
 * extensions that still check the value of this config it's value is now forced
 * to true by Setup.php.
 * @deprecated since 1.22
 */
$wgHtml5 = true;

/**
 * Defines the value of the version attribute in the &lt;html&gt; tag, if any.
 *
 * If your wiki uses RDFa, set it to the correct value for RDFa+HTML5.
 * Correct current values are 'HTML+RDFa 1.0' or 'XHTML+RDFa 1.0'.
 * See also http://www.w3.org/TR/rdfa-in-html/#document-conformance
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
 * If this is set, a "donate" link will appear in the sidebar. Set it to a URL.
 */
$wgSiteSupportPage = '';

/**
 * Validate the overall output using tidy and refuse
 * to display the page if it's not valid.
 */
$wgValidateAllHtml = false;

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
 * available skins in user preferences. If you want to remove a skin entirely,
 * remove it from the skins/ directory and its entry from LocalSettings.php.
 */
$wgSkipSkins = [];

/**
 * @deprecated since 1.23; use $wgSkipSkins instead
 */
$wgSkipSkin = '';

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
 * Allow user-preferences implemented in CSS?
 * This allows users to customise the site appearance to a greater
 * degree; disabling it will improve page load times.
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
 * Should we allow a broader set of characters in id attributes, per HTML5?  If
 * not, use only HTML 4-compatible IDs.  This option is for testing -- when the
 * functionality is ready, it will be on by default with no option.
 *
 * Currently this appears to work fine in all browsers, but it's disabled by
 * default because it normalizes id's a bit too aggressively, breaking preexisting
 * content (particularly Cite).  See bug 27733, bug 27694, bug 27474.
 */
$wgExperimentalHtmlIds = false;

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
			"url" => "//www.mediawiki.org/",
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
 * Client-side resource modules.
 *
 * Extensions should add their ResourceLoader module definitions
 * to the $wgResourceModules variable.
 *
 * @par Example:
 * @code
 *   $wgResourceModules['ext.myExtension'] = [
 *      'scripts' => 'myExtension.js',
 *      'styles' => 'myExtension.css',
 *      'dependencies' => [ 'jquery.cookie', 'jquery.tabIndex' ],
 *      'localBasePath' => __DIR__,
 *      'remoteExtPath' => 'MyExtension',
 *   ];
 * @endcode
 */
$wgResourceModules = [];

/**
 * Skin-specific styles for resource modules.
 *
 * These are later added to the 'skinStyles' list of the existing module. The 'styles' list can
 * not be modified or disabled.
 *
 * For example, here is a module "bar" and how skin Foo would provide additional styles for it.
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
 * This is mostly equivalent to:
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
 * of replacing them. This can be done using the `+` prefix.
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
 * This is mostly equivalent to:
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
 * As with $wgResourceModules, paths default to being relative to the MediaWiki root.
 * You should always provide a localBasePath and remoteBasePath (or remoteExtPath/remoteSkinPath).
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
	'versioned' => [
		'server' => 30 * 24 * 60 * 60, // 30 days
		'client' => 30 * 24 * 60 * 60, // 30 days
	],
	'unversioned' => [
		'server' => 5 * 60, // 5 minutes
		'client' => 5 * 60, // 5 minutes
	],
];

/**
 * The default debug mode (on/off) for of ResourceLoader requests.
 *
 * This will still be overridden when the debug URL parameter is used.
 */
$wgResourceLoaderDebug = false;

/**
 * Put each statement on its own line when minifying JavaScript. This makes
 * debugging in non-debug mode a bit easier.
 *
 * @deprecated since 1.27: Always false; no longer configurable.
 */
$wgResourceLoaderMinifierStatementsOnOwnLine = false;

/**
 * Maximum line length when minifying JavaScript. This is not a hard maximum:
 * the minifier will try not to produce lines longer than this, but may be
 * forced to do so in certain cases.
 *
 * @deprecated since 1.27: Always 1,000; no longer configurable.
 */
$wgResourceLoaderMinifierMaxLineLength = 1000;

/**
 * Whether to ensure the mediawiki.legacy library is loaded before other modules.
 *
 * @deprecated since 1.26: Always declare dependencies.
 */
$wgIncludeLegacyJavaScript = false;

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
$wgLegacyJavaScriptGlobals = true;

/**
 * If set to a positive number, ResourceLoader will not generate URLs whose
 * query string is more than this many characters long, and will instead use
 * multiple requests with shorter query strings. This degrades performance,
 * but may be needed if your web server has a low (less than, say 1024)
 * query string length limit or a low value for suhosin.get.max_value_length
 * that you can't increase.
 *
 * If set to a negative number, ResourceLoader will assume there is no query
 * string length limit.
 *
 * Defaults to a value based on php configuration.
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
 * If set to true, statically-sourced (file-backed) JavaScript resources will
 * be parsed for validity before being bundled up into ResourceLoader modules.
 *
 * This can be helpful for development by providing better error messages in
 * default (non-debug) mode, but JavaScript parsing is slow and memory hungry
 * and may fail on large pre-bundled frameworks.
 */
$wgResourceLoaderValidateStaticJS = false;

/**
 * Global LESS variables. An associative array binding variable names to
 * LESS code snippets representing their values.
 *
 * Adding an item here is equivalent to writing `@variable: value;`
 * at the beginning of all your .less files, with all the consequences.
 * In particular, string values must be escaped and quoted.
 *
 * Changes to LESS variables do not trigger cache invalidation.
 *
 * If the LESS variables need to be dynamic, you can use the
 * ResourceLoaderGetLessVars hook (since 1.25).
 *
 * @par Example:
 * @code
 *   $wgResourceLoaderLESSVars = [
 *     'baseFontSize'  => '1em',
 *     'smallFontSize' => '0.75em',
 *     'WikimediaBlue' => '#006699',
 *   ];
 * @endcode
 * @since 1.22
 */
$wgResourceLoaderLESSVars = [
	/**
	 * Minimum available screen width at which a device can be considered a tablet/desktop
	 * The number is currently based on the device width of a Samsung Galaxy S5 mini and is low
	 * enough to cover iPad (768px). Number is prone to change with new information.
	 * @since 1.27
	 */
	'deviceWidthTablet' => '720px',
];

/**
 * Default import paths for LESS modules. LESS files referenced in @import
 * statements will be looked up here first, and relative to the importing file
 * second. To avoid collisions, it's important for the LESS files in these
 * directories to have a common, predictable file name prefix.
 *
 * Extensions need not (and should not) register paths in
 * $wgResourceLoaderLESSImportPaths. The import path includes the path of the
 * currently compiling LESS file, which allows each extension to freely import
 * files from its own tree.
 *
 * @since 1.22
 */
$wgResourceLoaderLESSImportPaths = [
	"$IP/resources/src/mediawiki.less/",
];

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
 * Namespace aliases.
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
 *
 * Theoretically 0x80-0x9F of ISO 8859-1 should be disallowed, but
 * this breaks interlanguage links
 */
$wgLegalTitleChars = " %!\"$&'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF+";

/**
 * The interwiki prefix of the current wiki, or false if it doesn't have one.
 *
 * @deprecated since 1.23; use $wgLocalInterwikis instead
 */
$wgLocalInterwiki = false;

/**
 * Array for multiple $wgLocalInterwiki values, in case there are several
 * interwiki prefixes that point to the current wiki. If $wgLocalInterwiki is
 * set, its value is prepended to this array, for backwards compatibility.
 *
 * Note, recent changes feeds use only the first entry in this array (or
 * $wgLocalInterwiki, if it is set). See $wgRCFeeds
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
 * @name SiteStore caching settings.
 * @{
 */

/**
 * Specify the file location for the Sites json cache file.
 */
$wgSitesCacheFile = false;

/** @} */ # end of SiteStore caching settings.

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
 * @since 1.16 - This can now be set per-namespace. Some special namespaces (such
 * as Special, see MWNamespace::$alwaysCapitalizedNamespaces for the full list) must be
 * true by default (and setting them has no effect), due to various things that
 * require them to be so. Also, since Talk namespaces need to directly mirror their
 * associated content namespaces, the values for those are ignored in favor of the
 * subject namespace's setting. Setting for NS_MEDIA is taken automatically from
 * NS_FILE.
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
 * (bug 10569) Disallow Mypage and Mytalk as well.
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
 *  preprocessorClass The preprocessor class. Two classes are currently available:
 *                    Preprocessor_Hash, which uses plain PHP arrays for temporary
 *                    storage, and Preprocessor_DOM, which uses the DOM module for
 *                    temporary storage. Preprocessor_DOM generally uses less memory;
 *                    the speed of the two is roughly the same.
 *
 *                    If this parameter is not given, it uses Preprocessor_DOM if the
 *                    DOM module is available, otherwise it uses Preprocessor_Hash.
 *
 * The entire associative array will be passed through to the constructor as
 * the first parameter. Note that only Setup.php can use this variable --
 * the configuration will change at runtime via $wgParser member functions, so
 * the contents of this variable will be out-of-date. The variable can only be
 * changed during LocalSettings.php, in particular, it can't be changed during
 * an extension setup function.
 */
$wgParserConf = [
	'class' => 'Parser',
	# 'preprocessorClass' => 'Preprocessor_Hash',
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
 * A complexity limit on template expansion: the maximum number of elements
 * generated by Preprocessor::preprocessToObj(). This allows you to limit the
 * amount of memory used by the Preprocessor_DOM node cache: testing indicates
 * that each element uses about 160 bytes of memory on a 64-bit processor, so
 * this default corresponds to about 155 MB.
 *
 * When the limit is exceeded, an exception is thrown.
 */
$wgMaxGeneratedPPNodeCount = 1000000;

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
 * to any other protocols with the same name as a namespace. See bug #44011 for
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
 * whitelist of regular expression fragments to match the image URL
 * against. If the image matches one of the regular expression fragments,
 * The image will be displayed.
 *
 * Set this to true to enable the on-wiki whitelist (MediaWiki:External image whitelist)
 * Or false to disable it
 */
$wgEnableImageWhitelist = true;

/**
 * A different approach to the above: simply allow the "<img>" tag to be used.
 * This allows you to specify alt text and other attributes, copy-paste HTML to
 * your wiki more easily, etc.  However, allowing external images in any manner
 * will allow anyone with editing rights to snoop on your visitors' IP
 * addresses and so forth, if they wanted to, by inserting links to images on
 * sites they control.
 */
$wgAllowImageTag = false;

/**
 * Configuration for HTML postprocessing tool. Set this to a configuration
 * array to enable an external tool. Dave Raggett's "HTML Tidy" is typically
 * used. See http://www.w3.org/People/Raggett/tidy/
 *
 * If this is null and $wgUseTidy is true, the deprecated configuration
 * parameters will be used instead.
 *
 * If this is null and $wgUseTidy is false, a pure PHP fallback will be used.
 *
 * Keys are:
 *  - driver: May be:
 *    - RaggettInternalHHVM: Use the limited-functionality HHVM extension
 *    - RaggettInternalPHP: Use the PECL extension
 *    - RaggettExternal: Shell out to an external binary (tidyBin)
 *    - Html5Depurate: Use external Depurate service
 *    - Html5Internal: Use the built-in HTML5 balancer
 *
 *  - tidyConfigFile: Path to configuration file for any of the Raggett drivers
 *  - debugComment: True to add a comment to the output with warning messages
 *  - tidyBin: For RaggettExternal, the path to the tidy binary.
 *  - tidyCommandLine: For RaggettExternal, additional command line options.
 */
$wgTidyConfig = null;

/**
 * Set this to true to use the deprecated tidy configuration parameters.
 * @deprecated use $wgTidyConfig
 */
$wgUseTidy = false;

/**
 * The path to the tidy binary.
 * @deprecated Use $wgTidyConfig['tidyBin']
 */
$wgTidyBin = 'tidy';

/**
 * The path to the tidy config file
 * @deprecated Use $wgTidyConfig['tidyConfigFile']
 */
$wgTidyConf = $IP . '/includes/tidy/tidy.conf';

/**
 * The command line options to the tidy binary
 * @deprecated Use $wgTidyConfig['tidyCommandLine']
 */
$wgTidyOpts = '';

/**
 * Set this to true to use the tidy extension
 * @deprecated Use $wgTidyConfig['driver']
 */
$wgTidyInternal = extension_loaded( 'tidy' );

/**
 * Put tidy warnings in HTML comments
 * Only works for internal tidy.
 */
$wgDebugTidy = false;

/**
 * Allow raw, unchecked HTML in "<html>...</html>" sections.
 * THIS IS VERY DANGEROUS on a publicly editable site, so USE wgGroupPermissions
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
 * Expiry time for transcluded templates cached in transcache database table.
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
 * - 'comma': the page must contain a comma to be considered valid
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
	'local' => [ 'class' => 'LocalIdLookup' ],
];

/**
 * Central ID lookup provider to use by default
 * @var string
 */
$wgCentralIdLookupProvider = 'local';

/**
 * Password policy for local wiki users. A user's effective policy
 * is the superset of all policy statements from the policies for the
 * groups where the user is a member. If more than one group policy
 * include the same policy statement, the value is the max() of the
 * values. Note true > false. The 'default' policy group is required,
 * and serves as the minimum policy for all users. New statements can
 * be added by appending to $wgPasswordPolicy['checks'].
 * Statements:
 *	- MinimalPasswordLength - minimum length a user can set
 *	- MinimumPasswordLengthToLogin - passwords shorter than this will
 *		not be allowed to login, regardless if it is correct.
 *	- MaximalPasswordLength - maximum length password a user is allowed
 *		to attempt. Prevents DoS attacks with pbkdf2.
 *	- PasswordCannotMatchUsername - Password cannot match username to
 *	- PasswordCannotMatchBlacklist - Username/password combination cannot
 *		match a specific, hardcoded blacklist.
 *	- PasswordCannotBePopular - Blacklist passwords which are known to be
 *		commonly chosen. Set to integer n to ban the top n passwords.
 *		If you want to ban all common passwords on file, use the
 *		PHP_INT_MAX constant.
 * @since 1.26
 */
$wgPasswordPolicy = [
	'policies' => [
		'bureaucrat' => [
			'MinimalPasswordLength' => 8,
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchUsername' => true,
			'PasswordCannotBePopular' => 25,
		],
		'sysop' => [
			'MinimalPasswordLength' => 8,
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchUsername' => true,
			'PasswordCannotBePopular' => 25,
		],
		'bot' => [
			'MinimalPasswordLength' => 8,
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchUsername' => true,
		],
		'default' => [
			'MinimalPasswordLength' => 1,
			'PasswordCannotMatchUsername' => true,
			'PasswordCannotMatchBlacklist' => true,
			'MaximalPasswordLength' => 4096,
		],
	],
	'checks' => [
		'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
		'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
		'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
		'PasswordCannotMatchBlacklist' => 'PasswordPolicyChecks::checkPasswordCannotMatchBlacklist',
		'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
		'PasswordCannotBePopular' => 'PasswordPolicyChecks::checkPopularPasswordBlacklist'
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
		MediaWiki\Auth\LegacyHookPreAuthenticationProvider::class => [
			'class' => MediaWiki\Auth\LegacyHookPreAuthenticationProvider::class,
			'sort' => 0,
		],
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
		// 	'class' => MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider::class,
		// 	'sort' => 100,
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
 * For compatibility with old installations set to false
 * @deprecated since 1.24 will be removed in future
 */
$wgPasswordSalt = true;

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
 * Default password type to use when hashing user passwords
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
 *     'class' => 'EncryptedPassword',
 *     'underlying' => 'bcrypt',
 *     'secrets' => [],
 *     'cipher' => MCRYPT_RIJNDAEL_256,
 *     'mode' => MCRYPT_MODE_CBC,
 *     'cost' => 5,
 * ];
 * @endcode
 *
 * @since 1.24
 */
$wgPasswordConfig = [
	'A' => [
		'class' => 'MWOldPassword',
	],
	'B' => [
		'class' => 'MWSaltedPassword',
	],
	'pbkdf2-legacyA' => [
		'class' => 'LayeredParameterizedPassword',
		'types' => [
			'A',
			'pbkdf2',
		],
	],
	'pbkdf2-legacyB' => [
		'class' => 'LayeredParameterizedPassword',
		'types' => [
			'B',
			'pbkdf2',
		],
	],
	'bcrypt' => [
		'class' => 'BcryptPassword',
		'cost' => 9,
	],
	'pbkdf2' => [
		'class' => 'Pbkdf2Password',
		'algo' => 'sha512',
		'cost' => '30000',
		'length' => '64',
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
	'msg:double-redirect-fixer', // Automatic double redirect fix
	'msg:usermessage-editor', // Default user for leaving user messages
	'msg:proxyblocker', // For $wgProxyList and Special:Blockme (removed in 1.22)
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
	'cols' => 80,
	'date' => 'default',
	'diffonly' => 0,
	'disablemail' => 0,
	'editfont' => 'default',
	'editondblclick' => 0,
	'editsectiononrightclick' => 0,
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
	'math' => 1,
	'minordefault' => 0,
	'newpageshidepatrolled' => 0,
	'nickname' => '',
	'norollbackdiff' => 0,
	'numberheadings' => 0,
	'previewonfirst' => 0,
	'previewontop' => 1,
	'rcdays' => 7,
	'rclimit' => 50,
	'rows' => 25,
	'showhiddencats' => 0,
	'shownumberswatching' => 1,
	'showtoolbar' => 1,
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
	'watchlistdays' => 3.0,
	'watchlisthideanons' => 0,
	'watchlisthidebots' => 0,
	'watchlisthideliu' => 0,
	'watchlisthideminor' => 0,
	'watchlisthideown' => 0,
	'watchlisthidepatrolled' => 0,
	'watchlisthidecategorization' => 1,
	'watchlistreloadautomatically' => 0,
	'watchmoves' => 0,
	'watchrollback' => 0,
	'wllimit' => 250,
	'useeditwarning' => 1,
	'prefershttps' => 1,
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
 */
$wgBlockAllowsUTEdit = true;

/**
 * Allow sysops to ban users from accessing Emailuser
 */
$wgSysopEmailBans = true;

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
 * Special:Userlogin and Special:ChangePassword are always whitelisted.
 *
 * @note This will only work if $wgGroupPermissions['*']['read'] is false --
 * see below. Otherwise, ALL pages are accessible, regardless of this setting.
 *
 * @note Also that this will only protect _pages in the wiki_. Uploaded files
 * will remain readable. You can use img_auth.php to protect uploaded files,
 * see https://www.mediawiki.org/wiki/Manual:Image_Authorization
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
 * pages not intended to be whitelisted.  The above example will also
 * whitelist a page named 'Security Main Page'.
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
$wgGroupPermissions['*']['editmyusercss'] = true;
$wgGroupPermissions['*']['editmyuserjs'] = true;
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
$wgGroupPermissions['sysop']['editusercss'] = true;
$wgGroupPermissions['sysop']['edituserjs'] = true;
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
# $wgGroupPermissions['suppress']['hideuser'] = true;
// To hide revisions/log items from users and Sysops
# $wgGroupPermissions['suppress']['suppressrevision'] = true;
// To view revisions/log items hidden from users and Sysops
# $wgGroupPermissions['suppress']['viewsuppressed'] = true;
// For private suppression log access
# $wgGroupPermissions['suppress']['suppressionlog'] = true;

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
 * This acts the same way as wgGroupPermissions above, except that
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
 * Automatically add a usergroup to any user who matches certain conditions.
 *
 * @todo Redocument $wgAutopromote
 *
 * The format is
 *   [ '&' or '|' or '^' or '!', cond1, cond2, ... ]
 * where cond1, cond2, ... are themselves conditions; *OR*
 *   APCOND_EMAILCONFIRMED, *OR*
 *   [ APCOND_EMAILCONFIRMED ], *OR*
 *   [ APCOND_EDITCOUNT, number of edits ], *OR*
 *   [ APCOND_AGE, seconds since registration ], *OR*
 *   [ APCOND_INGROUPS, group1, group2, ... ], *OR*
 *   [ APCOND_ISIP, ip ], *OR*
 *   [ APCOND_IPINRANGE, range ], *OR*
 *   [ APCOND_AGE_FROM_EDIT, seconds since first edit ], *OR*
 *   [ APCOND_BLOCKED ], *OR*
 *   [ APCOND_ISBOT ], *OR*
 *   similar constructs defined by extensions.
 *
 * If $wgEmailAuthentication is off, APCOND_EMAILCONFIRMED will be true for any
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
 *             'newbie' => [ x, y ], // each new autoconfirmed accounts; overrides 'user'
 *             'ip' => [ x, y ], // each anon and recent account
 *             'subnet' => [ x, y ], // ... within a /24 subnet in IPv4 or /64 in IPv6
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
 * Array of IPs which should be excluded from rate limits.
 * This may be useful for whitelisting NAT gateways for conferences, etc.
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
	[ 'count' => 150, 'seconds' => 60*60*48 ],
];

/**
 * @var Array Map of (grant => right => boolean)
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

$wgGrantPermissions['basic']['autoconfirmed'] = true;
$wgGrantPermissions['basic']['autopatrol'] = true;
$wgGrantPermissions['basic']['editsemiprotected'] = true;
$wgGrantPermissions['basic']['ipblock-exempt'] = true;
$wgGrantPermissions['basic']['nominornewtalk'] = true;
$wgGrantPermissions['basic']['patrolmarks'] = true;
$wgGrantPermissions['basic']['purge'] = true;
$wgGrantPermissions['basic']['read'] = true;
$wgGrantPermissions['basic']['skipcaptcha'] = true;
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

$wgGrantPermissions['editmycssjs'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['editmycssjs']['editmyusercss'] = true;
$wgGrantPermissions['editmycssjs']['editmyuserjs'] = true;

$wgGrantPermissions['editmyoptions']['editmyoptions'] = true;

$wgGrantPermissions['editinterface'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['editinterface']['editinterface'] = true;
$wgGrantPermissions['editinterface']['editusercss'] = true;
$wgGrantPermissions['editinterface']['edituserjs'] = true;

$wgGrantPermissions['createeditmovepage'] = $wgGrantPermissions['editpage'];
$wgGrantPermissions['createeditmovepage']['createpage'] = true;
$wgGrantPermissions['createeditmovepage']['createtalk'] = true;
$wgGrantPermissions['createeditmovepage']['move'] = true;
$wgGrantPermissions['createeditmovepage']['move-rootuserpages'] = true;
$wgGrantPermissions['createeditmovepage']['move-subpages'] = true;
$wgGrantPermissions['createeditmovepage']['move-categorypages'] = true;

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

$wgGrantPermissions['delete'] = $wgGrantPermissions['editpage'] +
	$wgGrantPermissions['viewdeleted'];
$wgGrantPermissions['delete']['delete'] = true;
$wgGrantPermissions['delete']['bigdelete'] = true;
$wgGrantPermissions['delete']['deletelogentry'] = true;
$wgGrantPermissions['delete']['deleterevision'] = true;
$wgGrantPermissions['delete']['undelete'] = true;

$wgGrantPermissions['protect'] = $wgGrantPermissions['editprotected'];
$wgGrantPermissions['protect']['protect'] = true;

$wgGrantPermissions['viewmywatchlist']['viewmywatchlist'] = true;

$wgGrantPermissions['editmywatchlist']['editmywatchlist'] = true;

$wgGrantPermissions['sendemail']['sendemail'] = true;

$wgGrantPermissions['createaccount']['createaccount'] = true;

$wgGrantPermissions['privateinfo']['viewmyprivateinfo'] = true;

/**
 * @var Array Map of grants to their UI grouping
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
	'rollback'            => 'administration',
	'blockusers'          => 'administration',
	'delete'              => 'administration',
	'viewdeleted'         => 'administration',
	'protect'             => 'administration',
	'createaccount'       => 'administration',

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
 * - An array of addresses, either in the values
 *   or the keys (for backward compatibility)
 * - A string, in that case this is the path to a file
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
$wgCookieExpiration = 180 * 86400;

/**
 * Default login cookie lifetime, in seconds. Setting
 * $wgExtendLoginCookieExpiration to null will use $wgCookieExpiration to
 * calculate the cookie lifetime. As with $wgCookieExpiration, 0 will make
 * login cookies session-only.
 */
$wgExtendedLoginCookieExpiration = null;

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
 *   - "detect":  Set the secure flag if $wgServer is set to an HTTPS URL
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
 * A list of cookies that vary the cache (for use by extensions)
 */
$wgCacheVaryCookies = [];

/**
 * Override to customise the session name
 */
$wgSessionName = false;

/** @} */ # end of cookie settings }

/************************************************************************//**
 * @name   LaTeX (mathematical formulas)
 * @{
 */

/**
 * To use inline TeX, you need to compile 'texvc' (in the 'math' subdirectory of
 * the MediaWiki package and have latex, dvips, gs (ghostscript), andconvert
 * (ImageMagick) installed and available in the PATH.
 * Please see math/README for more information.
 */
$wgUseTeX = false;

/** @} */ # end LaTeX }

/************************************************************************//**
 * @name   Profiling, testing and debugging
 *
 * To enable profiling, edit StartProfiler.php
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
 * 'LBFactorySimple' and $wgDBservers is an empty array; otherwise
 * the DBO_DEBUG flag must be set in the 'flags' option of the database
 * connection to achieve the same functionality.
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
		'readQueryTime' => 5
	],
	// HTTP POST requests.
	// Master reads and writes will happen for a subset of these.
	'POST' => [
		'readQueryTime' => 5,
		'writeQueryTime' => 1,
		'maxAffected' => 1000
	],
	'POST-nonwrite' => [
		'masterConns' => 0,
		'writes' => 0,
		'readQueryTime' => 5
	],
	// Deferred updates that run after HTTP response is sent
	'PostSend' => [
		'readQueryTime' => 5,
		'writeQueryTime' => 1,
		'maxAffected' => 1000
	],
	// Background job runner
	'JobRunner' => [
		'readQueryTime' => 30,
		'writeQueryTime' => 5,
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
 * $wgMWLoggerDefaultSpi = [ 'class' => '\\MediaWiki\\Logger\\NullSpi' ];
 * @endcode
 *
 * @since 1.25
 * @var array $wgMWLoggerDefaultSpi
 * @see MwLogger
 */
$wgMWLoggerDefaultSpi = [
	'class' => '\\MediaWiki\\Logger\\LegacySpi',
];

/**
 * Display debug data at the bottom of the main content area.
 *
 * Useful for developers and technical users trying to working on a closed wiki.
 */
$wgShowDebug = false;

/**
 * Prefix debug messages with relative timestamp. Very-poor man's profiler.
 * Since 1.19 also includes memory usage.
 */
$wgDebugTimestamps = false;

/**
 * Print HTTP headers for every request in the debug information.
 */
$wgDebugPrintHttpHeaders = true;

/**
 * Show the contents of $wgHooks in Special:Version
 */
$wgSpecialVersionShowHooks = false;

/**
 * Whether to show "we're sorry, but there has been a database error" pages.
 * Displaying errors aids in debugging, but may display information useful
 * to an attacker.
 */
$wgShowSQLErrors = false;

/**
 * If set to true, uncaught exceptions will print a complete stack trace
 * to output. This should only be used for debugging, as it may reveal
 * private information in function parameters due to PHP's backtrace
 * formatting.
 */
$wgShowExceptionDetails = false;

/**
 * If true, show a backtrace for database errors
 *
 * @note This setting only applies when connection errors and query errors are
 * reported in the normal manner. $wgShowExceptionDetails applies in other cases,
 * including those in which an uncaught exception is thrown from within the
 * exception handler.
 */
$wgShowDBErrorBacktrace = false;

/**
 * If true, send the exception backtrace to the error log
 */
$wgLogExceptionBacktrace = true;

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
 * Only record profiling info for pages that took longer than this
 * @deprecated since 1.25: set $wgProfiler['threshold'] instead.
 */
$wgProfileLimit = 0.0;

/**
 * Don't put non-profiling info into log file
 *
 * @deprecated since 1.23, set the log file in
 *   $wgDebugLogGroups['profileoutput'] instead.
 */
$wgProfileOnly = false;

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
 * @see MediaWikiServices::getStatsdDataFactory
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
 * Set this to an integer to only do synchronous site_stats updates
 * one every *this many* updates. The other requests go into pending
 * delta values in $wgMemc. Make sure that $wgMemc is a global cache.
 * If set to -1, updates *only* go to $wgMemc (useful for daemons).
 */
$wgSiteStatsAsyncFactor = false;

/**
 * Parser test suite files to be run by parserTests.php when no specific
 * filename is passed to it.
 *
 * Extensions may add their own tests to this array, or site-local tests
 * may be added via LocalSettings.php
 *
 * Use full paths.
 */
$wgParserTestFiles = [
	"$IP/tests/parser/parserTests.txt",
	"$IP/tests/parser/extraParserTests.txt"
];

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
 * Will be ignored if $wgUseFileCache or $wgUseSquid is enabled.
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
 * Enable OpenSearch suggestions requested by MediaWiki. Set this to
 * false if you've disabled scripts that use api?action=opensearch and
 * want reduce load caused by cached scripts still pulling suggestions.
 * It will let the API fallback by responding with an empty array.
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
 *     'http://www.google.com/search?q=$1' .
 *     '&domains=http://example.com' .
 *     '&sitesearch=http://example.com' .
 *     '&ie=utf-8&oe=utf-8';
 * @endcode
 */
$wgSearchForwardUrl = null;

/**
 * Search form behavior.
 * - true = use Go & Search buttons
 * - false = use Go button & Advanced search link
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
		'https://phabricator.wikimedia.org/r/revision/%R;%H',
	'ssh://(?:[a-z0-9_]+@)?gerrit.wikimedia.org:29418/(.*)' =>
		'https://phabricator.wikimedia.org/r/revision/%R;%H',
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
 */
$wgRCLinkDays = [ 1, 3, 7, 14, 30 ];

/**
 * Destinations to which notifications about recent changes
 * should be sent.
 *
 * As of MediaWiki 1.22, there are 2 supported 'engine' parameter option in core:
 *   * 'UDPRCFeedEngine', which is used to send recent changes over UDP to the
 *      specified server.
 *   * 'RedisPubSubFeedEngine', which is used to send recent changes to Redis.
 *
 * The common options are:
 *   * 'uri' -- the address to which the notices are to be sent.
 *   * 'formatter' -- the class name (implementing RCFeedFormatter) which will
 *     produce the text to send. This can also be an object of the class.
 *   * 'omit_bots' -- whether the bot edits should be in the feed
 *   * 'omit_anon' -- whether anonymous edits should be in the feed
 *   * 'omit_user' -- whether edits by registered users should be in the feed
 *   * 'omit_minor' -- whether minor edits should be in the feed
 *   * 'omit_patrolled' -- whether patrolled edits should be in the feed
 *
 *  The IRC-specific options are:
 *   * 'add_interwiki_prefix' -- whether the titles should be prefixed with
 *     the first entry in the $wgLocalInterwikis array (or the value of
 *     $wgLocalInterwiki, if set)
 *
 *  The JSON-specific options are:
 *   * 'channel' -- if set, the 'channel' parameter is also set in JSON values.
 *
 * @example $wgRCFeeds['example'] = [
 *		'formatter' => 'JSONRCFeedFormatter',
 *		'uri' => "udp://localhost:1336",
 *		'add_interwiki_prefix' => false,
 *		'omit_bots' => true,
 *	];
 * @example $wgRCFeeds['exampleirc'] = [
 *		'formatter' => 'IRCColourfulRCFeedFormatter',
 *		'uri' => "udp://localhost:1338",
 *		'add_interwiki_prefix' => false,
 *		'omit_bots' => true,
 *	];
 * @since 1.22
 */
$wgRCFeeds = [];

/**
 * Used by RecentChange::getEngine to find the correct engine to use for a given URI scheme.
 * Keys are scheme names, values are names of engine classes.
 */
$wgRCEngines = [
	'redis' => 'RedisPubSubFeedEngine',
	'udp' => 'UDPRCFeedEngine',
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
 */
$wgUseRCPatrol = true;

/**
 * Use new page patrolling to check new pages on Special:Newpages
 */
$wgUseNPPatrol = true;

/**
 * Use file patrolling to check new files on Special:Newfiles
 *
 * @since 1.27
 */
$wgUseFilePatrol = true;

/**
 * Log autopatrol actions to the log table
 */
$wgLogAutopatrol = true;

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
 * Configure the 'atom' feed to http://example.com/somefeed.xml
 * @code
 * $wgSiteFeed['atom'] = "http://example.com/somefeed.xml";
 * @endcode
 */
$wgOverrideSiteFeed = [];

/**
 * Available feeds objects.
 * Should probably only be defined when a page is syndicated ie when
 * $wgOut->isSyndicated() is true.
 */
$wgFeedClasses = [
	'rss' => 'RSSFeed',
	'atom' => 'AtomFeed',
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
 * Enable filtering of categories in Recentchanges
 */
$wgAllowCategorizedRecentChanges = false;

/**
 * Allow filtering by change tag in recentchanges, history, etc
 * Has no effect if no tags are defined in valid_tag.
 */
$wgUseTagFilter = true;

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
 * link.
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
 *        __DIR__ . '/lib/oojs-ui/i18n',
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
 * The key should be the name in all lower case, the value should be a properly
 * cased name for the skin. This value will be prefixed with "Skin" to create
 * the class name of the skin to load. Use Skin::getSkinNames() as an accessor
 * if you wish to have access to the full list.
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
$wgAutoloadClasses = [];

/**
 * Switch controlling legacy case-insensitive classloading.
 * Do not disable if your wiki must support data created by PHP4, or by
 * MediaWiki 1.4 or earlier.
 */
$wgAutoloadAttemptLowercase = true;

/**
 * An array of information about installed extensions keyed by their type.
 *
 * All but 'name', 'path' and 'author' can be omitted.
 *
 * @code
 * $wgExtensionCredits[$type][] = [
 *     'path' => __FILE__,
 *     'name' => 'Example extension',
 *     'namemsg' => 'exampleextension-name',
 *     'author' => [
 *         'Foo Barstein',
 *     ],
 *     'version' => '1.9.0',
 *     'url' => 'http://example.org/example-extension/',
 *     'descriptionmsg' => 'exampleextension-desc',
 *     'license-name' => 'GPL-2.0+',
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
 *    for instance "[http://example ...]".
 *
 * - descriptionmsg: A message key or an an array with message key and parameters:
 *    `'descriptionmsg' => 'exampleextension-desc',`
 *
 * - description: Description of extension as an inline string instead of
 *    localizable message (omit in favour of 'descriptionmsg').
 *
 * - license-name: Short name of the license (used as label for the link), such
 *   as "GPL-2.0+" or "MIT" (https://spdx.org/licenses/ for a list of identifiers).
 */
$wgExtensionCredits = [];

/**
 * Authentication plugin.
 * @var $wgAuth AuthPlugin
 * @deprecated since 1.27 use $wgAuthManagerConfig instead
 */
$wgAuth = null;

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
 * @see docs/injection.txt for an overview of dependency injection in MediaWiki.
 */
$wgServiceWiringFiles = [
	__DIR__ . '/ServiceWiring.php'
];

/**
 * Maps jobs to their handling classes; extensions
 * can add to this to provide custom jobs
 */
$wgJobClasses = [
	'refreshLinks' => 'RefreshLinksJob',
	'deleteLinks' => 'DeleteLinksJob',
	'htmlCacheUpdate' => 'HTMLCacheUpdateJob',
	'sendMail' => 'EmaillingJob',
	'enotifNotify' => 'EnotifNotifyJob',
	'fixDoubleRedirect' => 'DoubleRedirectJob',
	'AssembleUploadChunks' => 'AssembleUploadChunksJob',
	'PublishStashedFile' => 'PublishStashedFileJob',
	'ThumbnailRender' => 'ThumbnailRenderJob',
	'recentChangesUpdate' => 'RecentChangesUpdateJob',
	'refreshLinksPrioritized' => 'RefreshLinksJob',
	'refreshLinksDynamic' => 'RefreshLinksJob',
	'activityUpdateJob' => 'ActivityUpdateJob',
	'categoryMembershipChange' => 'CategoryMembershipChangeJob',
	'cdnPurge' => 'CdnPurgeJob',
	'enqueue' => 'EnqueueJob', // local queue for multi-DC setups
	'null' => 'NullJob'
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
	'default' => [ 'class' => 'JobQueueDB', 'order' => 'random', 'claimTTL' => 3600 ],
];

/**
 * Which aggregator to use for tracking which queues have jobs.
 * These settings should be global to all wikis.
 */
$wgJobQueueAggregator = [
	'class' => 'JobQueueAggregatorNull'
];

/**
 * Additional functions to be performed with updateSpecialPages.
 * Expensive Querypages are already updated.
 */
$wgSpecialPageCacheUpdates = [
	'Statistics' => [ 'SiteStatsUpdate', 'cacheUpdate' ]
];

/**
 * Hooks that are used for outputting exceptions.  Format is:
 *   $wgExceptionHooks[] = $funcname
 * or:
 *   $wgExceptionHooks[] = [ $class, $funcname ]
 * Hooks should return strings or false
 */
$wgExceptionHooks = [];

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
 * installed. See http://php.net/manual/en/intl.setup.php . The details of the
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
 * A message of the form log-show-hide-[type] should be added, and will be used
 * for the link text.
 */
$wgFilterLogTypes = [
	'patrol' => true,
	'tag' => true,
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
	'block/block' => 'BlockLogFormatter',
	'block/reblock' => 'BlockLogFormatter',
	'block/unblock' => 'BlockLogFormatter',
	'contentmodel/change' => 'ContentModelLogFormatter',
	'contentmodel/new' => 'ContentModelLogFormatter',
	'delete/delete' => 'DeleteLogFormatter',
	'delete/delete_redir' => 'DeleteLogFormatter',
	'delete/event' => 'DeleteLogFormatter',
	'delete/restore' => 'DeleteLogFormatter',
	'delete/revision' => 'DeleteLogFormatter',
	'import/interwiki' => 'ImportLogFormatter',
	'import/upload' => 'ImportLogFormatter',
	'managetags/activate' => 'LogFormatter',
	'managetags/create' => 'LogFormatter',
	'managetags/deactivate' => 'LogFormatter',
	'managetags/delete' => 'LogFormatter',
	'merge/merge' => 'MergeLogFormatter',
	'move/move' => 'MoveLogFormatter',
	'move/move_redir' => 'MoveLogFormatter',
	'patrol/patrol' => 'PatrolLogFormatter',
	'patrol/autopatrol' => 'PatrolLogFormatter',
	'protect/modify' => 'ProtectLogFormatter',
	'protect/move_prot' => 'ProtectLogFormatter',
	'protect/protect' => 'ProtectLogFormatter',
	'protect/unprotect' => 'ProtectLogFormatter',
	'rights/autopromote' => 'RightsLogFormatter',
	'rights/rights' => 'RightsLogFormatter',
	'suppress/block' => 'BlockLogFormatter',
	'suppress/delete' => 'DeleteLogFormatter',
	'suppress/event' => 'DeleteLogFormatter',
	'suppress/reblock' => 'BlockLogFormatter',
	'suppress/revision' => 'DeleteLogFormatter',
	'tag/update' => 'TagLogFormatter',
	'upload/overwrite' => 'UploadLogFormatter',
	'upload/revert' => 'UploadLogFormatter',
	'upload/upload' => 'UploadLogFormatter',
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
	'patrol' => [
		'patrol' => [ 'patrol' ],
		'autopatrol' => [ 'autopatrol' ],
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
	],
];

/**
 * Maintain a log of newusers at Log/newusers?
 */
$wgNewUserLog = true;

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
	'editchangetags' => 'SpecialPageAction',
	'history' => true,
	'info' => true,
	'markpatrolled' => true,
	'protect' => true,
	'purge' => true,
	'raw' => true,
	'render' => true,
	'revert' => true,
	'revisiondelete' => 'SpecialPageAction',
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
 * @name   AJAX and API
 * Note: The AJAX entry point which this section refers to is gradually being
 * replaced by the API entry point, api.php. They are essentially equivalent.
 * Both of them are used for dynamic client-side features, via XHR.
 * @{
 */

/**
 * Enable the MediaWiki API for convenient access to
 * machine-readable data via api.php
 *
 * See https://www.mediawiki.org/wiki/API
 */
$wgEnableAPI = true;

/**
 * Allow the API to be used to perform write operations
 * (page edits, rollback, etc.) when an authorised user
 * accesses it
 */
$wgEnableWriteAPI = true;

/**
 *
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
 *    'class' => 'ApiBar',
 *    'factory' => function( $main, $name ) { ... }
 *  ];
 *  $wgAPIModules['xyzzy'] = [
 *    'class' => 'ApiXyzzy',
 *    'factory' => [ 'XyzzyFactory', 'newApiModule' ]
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
 * Enable previewing licences via AJAX. Also requires $wgEnableAPI to be true.
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
 * Executable path of the PHP cli binary (php/php5). Should be set up on install.
 */
$wgPhpCli = '/usr/bin/php';

/**
 * Locale for LC_CTYPE, to work around http://bugs.php.net/bug.php?id=45132
 * For Unix-like operating systems, set this to to a locale that has a UTF-8
 * character set. Only the character set is relevant.
 */
$wgShellLocale = 'en_US.utf8';

/** @} */ # End shell }

/************************************************************************//**
 * @name   HTTP client
 * @{
 */

/**
 * Timeout for HTTP requests done internally, in seconds.
 */
$wgHTTPTimeout = 25;

/**
 * Timeout for Asynchronous (background) HTTP requests, in seconds.
 */
$wgAsyncHTTPTimeout = 25;

/**
 * Proxy to use for CURL requests.
 */
$wgHTTPProxy = false;

/**
 * Local virtual hosts.
 *
 * This lists domains that are configured as virtual hosts on the same machine.
 * If a request is to be made to a domain listed here, or any subdomain thereof,
 * then no proxy will be used.
 * Command-line scripts are not affected by this setting and will always use
 * proxy if it is configured.
 * @since 1.25
 */
$wgLocalVirtualHosts = [];

/**
 * Timeout for connections done internally (in seconds)
 * Only works for curl
 */
$wgHTTPConnectTimeout = 5e0;

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
$wgRunJobsAsync = (
	!function_exists( 'register_postsend_function' ) &&
	!function_exists( 'fastcgi_finish_request' )
);

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
 * Name of the external diff engine to use. Supported values:
 * * string: path to an external diff executable
 * * false: wikidiff2 PHP/HHVM module if installed, otherwise the default PHP implementation
 * * 'wikidiff', 'wikidiff2', and 'wikidiff3' are treated as false for backwards compatibility
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
 * @par Example:
 * @code
 *   $wgPoolCounterConf = [ 'ArticleView' => [
 *     'class' => 'PoolCounter_Client',
 *     'timeout' => 15, // wait timeout in seconds
 *     'workers' => 5, // maximum number of active threads in each pool
 *     'maxqueue' => 50, // maximum number of total threads in each pool
 *     ... any extension-specific options...
 *   ];
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
 * Set to false to disable use of the database fields introduced by the ContentHandler facility.
 * This way, the ContentHandler facility can be used without any additional information in the
 * database. A page's content model is then derived solely from the page's title. This however
 * means that changing a page's default model (e.g. using $wgNamespaceContentModels) will break
 * the page and/or make the content inaccessible. This also means that pages can not be moved to
 * a title that would default to a different content model.
 *
 * Overall, with $wgContentHandlerUseDB = false, no database updates are needed, but content
 * handling is less robust and less flexible.
 *
 * @since 1.21
 */
$wgContentHandlerUseDB = true;

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
 * @since 1.20
 */
$wgSiteTypes = [
	'mediawiki' => 'MediaWikiSite',
];

/**
 * Whether the page_props table has a pp_sortkey column. Set to false in case
 * the respective database schema change was not applied.
 * @since 1.23
 */
$wgPagePropsHaveSortkey = true;

/**
 * Port where you have HTTPS running
 * Supports HTTPS on non-standard ports
 * @see bug 65184
 * @since 1.24
 */
$wgHttpsPort = 443;

/**
 * Secret for session storage.
 * This should be set in LocalSettings.php, otherwise wgSecretKey will
 * be used.
 * @since 1.27
 */
$wgSessionSecret = false;

/**
 * If for some reason you can't install the PHP OpenSSL or mcrypt extensions,
 * you can set this to true to make MediaWiki work again at the cost of storing
 * sensitive session data insecurely. But it would be much more secure to just
 * install the OpenSSL extension.
 * @since 1.27
 */
$wgSessionInsecureSecrets = false;

/**
 * Secret for hmac-based key derivation function (fast,
 * cryptographically secure random numbers).
 * This should be set in LocalSettings.php, otherwise wgSecretKey will
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
 *     'class' => 'ParsoidVirtualRESTService',
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

/**
 * Where popular password file is located.
 *
 * Default in core contains 10,000 most popular. This config
 * allows you to change which file, in case you want to generate
 * a password file with > 10000 entries in it.
 *
 * @see maintenance/createCommonPasswordCdb.php
 * @since 1.27
 * @var string path to file
 */
$wgPopularPasswordFile = __DIR__ . '/../serialized/commonpasswords.cdb';

/*
 * Max time (in seconds) a user-generated transaction can spend in writes.
 * If exceeded, the transaction is rolled back with an error instead of being committed.
 *
 * @var int|bool Disabled if false
 * @since 1.27
 */
$wgMaxUserDBWriteDuration = false;

/**
 * Mapping of event channels (or channel categories) to EventRelayer configuration.
 *
 * By setting up a PubSub system (like Kafka) and enabling a corresponding EventRelayer class
 * that uses it, MediaWiki can broadcast events to all subscribers. Certain features like WAN
 * cache purging and CDN cache purging will emit events to this system. Appropriate listers can
 * subscribe to the channel and take actions based on the events. For example, a local daemon
 * can run on each CDN cache node and perfom local purges based on the URL purge channel events.
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
		'class' => 'EventRelayerNull',
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
];

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 * @}
 */
