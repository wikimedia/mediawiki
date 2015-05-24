<?php
/**
 * Generator for LocalSettings.php file.
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
 * @ingroup Deployment
 */

/**
 * Class for generating LocalSettings.php file.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class LocalSettingsGenerator {

	protected $extensions = array();
	protected $values = array();
	protected $groupPermissions = array();
	protected $dbSettings = '';
	protected $safeMode = false;
	protected $IP;

	/**
	 * @var Installer
	 */
	protected $installer;

	/**
	 * Constructor.
	 *
	 * @param Installer $installer
	 */
	public function __construct( Installer $installer ) {
		$this->installer = $installer;

		$this->extensions = $installer->getVar( '_Extensions' );
		$this->skins = $installer->getVar( '_Skins' );
		$this->IP = $installer->getVar( 'IP' );

		$db = $installer->getDBInstaller( $installer->getVar( 'wgDBtype' ) );

		$confItems = array_merge(
			array(
				'wgServer', 'wgScriptPath', 'wgScriptExtension',
				'wgPasswordSender', 'wgImageMagickConvertCommand', 'wgShellLocale',
				'wgLanguageCode', 'wgEnableEmail', 'wgEnableUserEmail', 'wgDiff3',
				'wgEnotifUserTalk', 'wgEnotifWatchlist', 'wgEmailAuthentication',
				'wgDBtype', 'wgSecretKey', 'wgRightsUrl', 'wgSitename', 'wgRightsIcon',
				'wgRightsText', 'wgMainCacheType', 'wgEnableUploads',
				'wgMainCacheType', '_MemCachedServers', 'wgDBserver', 'wgDBuser',
				'wgDBpassword', 'wgUseInstantCommons', 'wgUpgradeKey', 'wgDefaultSkin',
				'wgMetaNamespace', 'wgLogo',
			),
			$db->getGlobalNames()
		);

		$unescaped = array( 'wgRightsIcon', 'wgLogo' );
		$boolItems = array(
			'wgEnableEmail', 'wgEnableUserEmail', 'wgEnotifUserTalk',
			'wgEnotifWatchlist', 'wgEmailAuthentication', 'wgEnableUploads', 'wgUseInstantCommons'
		);

		foreach ( $confItems as $c ) {
			$val = $installer->getVar( $c );

			if ( in_array( $c, $boolItems ) ) {
				$val = wfBoolToStr( $val );
			}

			if ( !in_array( $c, $unescaped ) && $val !== null ) {
				$val = self::escapePhpString( $val );
			}

			$this->values[$c] = $val;
		}

		$this->dbSettings = $db->getLocalSettings();
		$this->safeMode = $installer->getVar( '_SafeMode' );
		$this->values['wgEmergencyContact'] = $this->values['wgPasswordSender'];
	}

	/**
	 * For $wgGroupPermissions, set a given ['group']['permission'] value.
	 * @param string $group Group name
	 * @param array $rightsArr An array of permissions, in the form of:
	 *   array( 'right' => true, 'right2' => false )
	 */
	public function setGroupRights( $group, $rightsArr ) {
		$this->groupPermissions[$group] = $rightsArr;
	}

	/**
	 * Returns the escaped version of a string of php code.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function escapePhpString( $string ) {
		if ( is_array( $string ) || is_object( $string ) ) {
			return false;
		}

		return strtr(
			$string,
			array(
				"\n" => "\\n",
				"\r" => "\\r",
				"\t" => "\\t",
				"\\" => "\\\\",
				"\$" => "\\\$",
				"\"" => "\\\""
			)
		);
	}

	/**
	 * Return the full text of the generated LocalSettings.php file,
	 * including the extensions and skins.
	 *
	 * @return string
	 */
	public function getText() {
		$localSettings = $this->getDefaultText();

		if ( count( $this->skins ) ) {
			$localSettings .= "
# Enabled skins.
# The following skins were automatically enabled:\n";

			foreach ( $this->skins as $skinName ) {
				$localSettings .= $this->generateExtEnableLine( 'skins', $skinName );
			}

			$localSettings .= "\n";
		}

		if ( count( $this->extensions ) ) {
			$localSettings .= "
# Enabled Extensions. Most extensions are enabled by including the base extension file here
# but check specific extension documentation for more details
# The following extensions were automatically enabled:\n";

			foreach ( $this->extensions as $extName ) {
				$localSettings .= $this->generateExtEnableLine( 'extensions', $extName );
			}

			$localSettings .= "\n";
		}

		$localSettings .= "
# End of automatically generated settings.
# Add more configuration options below.\n\n";

		return $localSettings;
	}

	/**
	 * Generate the appropriate line to enable the given extension or skin
	 *
	 * @param string $dir Either "extensions" or "skins"
	 * @param string $name Name of extension/skin
	 * @throws InvalidArgumentException
	 * @return string
	 */
	private function generateExtEnableLine( $dir, $name ) {
		if ( $dir === 'extensions' ) {
			$jsonFile = 'extension.json';
			$function = 'wfLoadExtension';
		} elseif ( $dir === 'skins' ) {
			$jsonFile = 'skin.json';
			$function = 'wfLoadSkin';
		} else {
			throw new InvalidArgumentException( '$dir was not "extensions" or "skins' );
		}

		$encName = self::escapePhpString( $name );

		if ( file_exists( "{$this->IP}/$dir/$encName/$jsonFile" ) ) {
			return "$function( '$encName' );\n";
		} else {
			return "require_once \"\$IP/$dir/$encName/$encName.php\";\n";
		}
	}

	/**
	 * Write the generated LocalSettings to a file
	 *
	 * @param string $fileName Full path to filename to write to
	 */
	public function writeFile( $fileName ) {
		file_put_contents( $fileName, $this->getText() );
	}

	/**
	 * @return string
	 */
	protected function buildMemcachedServerList() {
		$servers = $this->values['_MemCachedServers'];

		if ( !$servers ) {
			return 'array()';
		} else {
			$ret = 'array( ';
			$servers = explode( ',', $servers );

			foreach ( $servers as $srv ) {
				$srv = trim( $srv );
				$ret .= "'$srv', ";
			}

			return rtrim( $ret, ', ' ) . ' )';
		}
	}

	/**
	 * @return string
	 */
	protected function getDefaultText() {
		if ( !$this->values['wgImageMagickConvertCommand'] ) {
			$this->values['wgImageMagickConvertCommand'] = '/usr/bin/convert';
			$magic = '#';
		} else {
			$magic = '';
		}

		if ( !$this->values['wgShellLocale'] ) {
			$this->values['wgShellLocale'] = 'en_US.UTF-8';
			$locale = '#';
		} else {
			$locale = '';
		}

		$hashedUploads = $this->safeMode ? '' : '#';
		$metaNamespace = '';
		if ( $this->values['wgMetaNamespace'] !== $this->values['wgSitename'] ) {
			$metaNamespace = "\$wgMetaNamespace = \"{$this->values['wgMetaNamespace']}\";\n";
		}

		$groupRights = '';
		$noFollow = '';
		if ( $this->groupPermissions ) {
			$groupRights .= "# The following permissions were set based on your choice in the installer\n";
			foreach ( $this->groupPermissions as $group => $rightArr ) {
				$group = self::escapePhpString( $group );
				foreach ( $rightArr as $right => $perm ) {
					$right = self::escapePhpString( $right );
					$groupRights .= "\$wgGroupPermissions['$group']['$right'] = " .
						wfBoolToStr( $perm ) . ";\n";
				}
			}
			$groupRights .= "\n";

			if ( ( isset( $this->groupPermissions['*']['edit'] ) &&
					$this->groupPermissions['*']['edit'] === false )
				&& ( isset( $this->groupPermissions['*']['createaccount'] ) &&
					$this->groupPermissions['*']['createaccount'] === false )
				&& ( isset( $this->groupPermissions['*']['read'] ) &&
					$this->groupPermissions['*']['read'] !== false )
			) {
				$noFollow = "# Set \$wgNoFollowLinks to true if you open up your wiki to editing by\n"
					. "# the general public and wish to apply nofollow to external links as a\n"
					. "# deterrent to spammers. Nofollow is not a comprehensive anti-spam solution\n"
					. "# and open wikis will generally require other anti-spam measures; for more\n"
					. "# information, see https://www.mediawiki.org/wiki/Manual:Combating_spam\n"
					. "\$wgNoFollowLinks = false;\n\n";
			}
		}

		$serverSetting = "";
		if ( array_key_exists( 'wgServer', $this->values ) && $this->values['wgServer'] !== null ) {
			$serverSetting = "\n## The protocol and server name to use in fully-qualified URLs\n";
			$serverSetting .= "\$wgServer = \"{$this->values['wgServer']}\";\n";
		}

		switch ( $this->values['wgMainCacheType'] ) {
			case 'anything':
			case 'db':
			case 'memcached':
			case 'accel':
				$cacheType = 'CACHE_' . strtoupper( $this->values['wgMainCacheType'] );
				break;
			case 'none':
			default:
				$cacheType = 'CACHE_NONE';
		}

		$mcservers = $this->buildMemcachedServerList();

		return "<?php
# This file was automatically generated by the MediaWiki {$GLOBALS['wgVersion']}
# installer. If you make manual changes, please keep track in case you
# need to recreate them later.
#
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

## Uncomment this to disable output compression
# \$wgDisableOutputCompression = true;

\$wgSitename = \"{$this->values['wgSitename']}\";
{$metaNamespace}
## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## https://www.mediawiki.org/wiki/Manual:Short_URL
\$wgScriptPath = \"{$this->values['wgScriptPath']}\";
\$wgScriptExtension = \"{$this->values['wgScriptExtension']}\";
${serverSetting}
## The relative URL path to the skins directory
\$wgStylePath = \"\$wgScriptPath/skins\";
\$wgResourceBasePath = \$wgScriptPath;

## The relative URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
\$wgLogo = \"{$this->values['wgLogo']}\";

## UPO means: this is also a user preference option

\$wgEnableEmail = {$this->values['wgEnableEmail']};
\$wgEnableUserEmail = {$this->values['wgEnableUserEmail']}; # UPO

\$wgEmergencyContact = \"{$this->values['wgEmergencyContact']}\";
\$wgPasswordSender = \"{$this->values['wgPasswordSender']}\";

\$wgEnotifUserTalk = {$this->values['wgEnotifUserTalk']}; # UPO
\$wgEnotifWatchlist = {$this->values['wgEnotifWatchlist']}; # UPO
\$wgEmailAuthentication = {$this->values['wgEmailAuthentication']};

## Database settings
\$wgDBtype = \"{$this->values['wgDBtype']}\";
\$wgDBserver = \"{$this->values['wgDBserver']}\";
\$wgDBname = \"{$this->values['wgDBname']}\";
\$wgDBuser = \"{$this->values['wgDBuser']}\";
\$wgDBpassword = \"{$this->values['wgDBpassword']}\";

{$this->dbSettings}

## Shared memory settings
\$wgMainCacheType = $cacheType;
\$wgMemCachedServers = $mcservers;

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
\$wgEnableUploads = {$this->values['wgEnableUploads']};
{$magic}\$wgUseImageMagick = true;
{$magic}\$wgImageMagickConvertCommand = \"{$this->values['wgImageMagickConvertCommand']}\";

# InstantCommons allows wiki to use images from http://commons.wikimedia.org
\$wgUseInstantCommons = {$this->values['wgUseInstantCommons']};

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
{$locale}\$wgShellLocale = \"{$this->values['wgShellLocale']}\";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
{$hashedUploads}\$wgHashedUploadDirectory = false;

## Set \$wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
#\$wgCacheDirectory = \"\$IP/cache\";

# Site language code, should be one of the list in ./languages/Names.php
\$wgLanguageCode = \"{$this->values['wgLanguageCode']}\";

\$wgSecretKey = \"{$this->values['wgSecretKey']}\";

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
\$wgUpgradeKey = \"{$this->values['wgUpgradeKey']}\";

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
\$wgRightsPage = \"\"; # Set to the title of a wiki page that describes your license/copyright
\$wgRightsUrl = \"{$this->values['wgRightsUrl']}\";
\$wgRightsText = \"{$this->values['wgRightsText']}\";
\$wgRightsIcon = \"{$this->values['wgRightsIcon']}\";

# Path to the GNU diff3 utility. Used for conflict resolution.
\$wgDiff3 = \"{$this->values['wgDiff3']}\";

{$groupRights}{$noFollow}## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'vector', 'monobook':
\$wgDefaultSkin = \"{$this->values['wgDefaultSkin']}\";
";
	}
}
