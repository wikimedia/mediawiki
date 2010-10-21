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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

defined( 'MEDIAWIKI' ) || die( 1 );

/**
 * ResourceLoader module based on local JavaScript/CSS files.
 */
class ResourceLoaderFileModule extends ResourceLoaderModule {

	/* Protected Members */

	/**
	 * @var {array} List of paths to JavaScript files to always include
	 * @format array( [file-path], [file-path], ... )
	 */
	protected $scripts = array();
	/**
	 * @var {array} List of JavaScript files to include when using a specific language
	 * @format array( [language-code] => array( [file-path], [file-path], ... ), ... )
	 */
	protected $languageScripts = array();
	/**
	 * @var {array} List of JavaScript files to include when using a specific skin
	 * @format array( [skin-name] => array( [file-path], [file-path], ... ), ... )
	 */
	protected $skinScripts = array();
	/**
	 * @var {array} List of paths to JavaScript files to include in debug mode
	 * @format array( [skin-name] => array( [file-path], [file-path], ... ), ... )
	 */
	protected $debugScripts = array();
	/**
	 * @var {array} List of paths to JavaScript files to include in the startup module
	 * @format array( [file-path], [file-path], ... )
	 */
	protected $loaderScripts = array();
	/**
	 * @var {array} List of paths to CSS files to always include
	 * @format array( [file-path], [file-path], ... )
	 */
	protected $styles = array();
	/**
	 * @var {array} List of paths to CSS files to include when using specific skins
	 * @format array( [file-path], [file-path], ... )
	 */
	protected $skinStyles = array();
	/**
	 * @var {array} List of modules this module depends on
	 * @format array( [file-path], [file-path], ... )
	 */
	protected $dependencies = array();
	/**
	 * @var {array} List of message keys used by this module
	 * @format array( [module-name], [module-name], ... )
	 */
	protected $messages = array();
	/**
	 * @var {string} Name of group this module should be loaded in
	 * @format array( [message-key], [message-key], ... )
	 */
	protected $group;
	/** @var {boolean} Link to raw files in debug mode */
	protected $debugRaw = true;
	/**
	 * @var {array}  Cache for mtime
	 * @format array( [hash] => [mtime], [hash] => [mtime], ... )
	 */
	protected $modifiedTime = array();

	/* Methods */

	/**
	 * Constructs a new module from an options array.
	 * 
	 * @param {array} $options Options array. If not given or empty, an empty module will be constructed
	 * @param {string} $basePath base path to prepend to all paths in $options
	 * 
	 * @format $options
	 * 	array(
	 * 		// Scripts to always include
	 * 		'scripts' => [file path string or array of file path strings],
	 * 		// Scripts to include in specific language contexts
	 * 		'languageScripts' => array(
	 * 			[language code] => [file path string or array of file path strings],
	 * 		),
	 * 		// Scripts to include in specific skin contexts
	 * 		'skinScripts' => array(
	 * 			[skin name] => [file path string or array of file path strings],
	 * 		),
	 * 		// Scripts to include in debug contexts
	 * 		'debugScripts' => [file path string or array of file path strings],
	 * 		// Scripts to include in the startup module
	 * 		'loaderScripts' => [file path string or array of file path strings],
	 * 		// Modules which must be loaded before this module
	 * 		'dependencies' => [modile name string or array of module name strings],
	 * 		// Styles to always load
	 * 		'styles' => [file path string or array of file path strings],
	 * 		// Styles to include in specific skin contexts
	 * 		'skinStyles' => array(
	 * 			[skin name] => [file path string or array of file path strings],
	 * 		),
	 * 		// Messages to always load
	 * 		'messages' => [array of message key strings],
	 * 		// Group which this module should be loaded together with
	 * 		'group' => [group name string],
	 * 	)
	 */
	public function __construct( $options = array(), $basePath = null ) {
		foreach ( $options as $member => $option ) {
			switch ( $member ) {
				// Lists of file paths
				case 'scripts':
				case 'debugScripts':
				case 'loaderScripts':
				case 'styles':
					$this->{$member} = self::prefixFilePathList( (array) $option, $basePath );
					break;
				// Collated lists of file paths
				case 'languageScripts':
				case 'skinScripts':
				case 'skinStyles':
					foreach ( (array) $option as $key => $value ) {
						$this->{$member}[$key] = self::prefixFilePathList( (array) $value, $basePath );
					}
				// Lists of strings
				case 'dependencies':
				case 'messages':
					$this->{$member} = (array) $option;
					break;
				// Single strings
				case 'group':
					$this->{$member} = (string) $option;
					break;
				// Single booleans
				case 'debugRaw':
					$this->{$member} = (bool) $option;
					break;
			}
		}
	}

	/**
	 * Gets all scripts for a given context concatenated together.
	 * 
	 * @param {ResourceLoaderContext} $context Context in which to generate script
	 * @return {string} JavaScript code for $context
	 */
	public function getScript( ResourceLoaderContext $context ) {
		global $wgScriptPath;
		
		$files = array_merge(
			$this->scripts,
			self::tryForKey( $this->languageScripts, $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' )
		);
		if ( $context->getDebug() ) {
			$files = array_merge( $files, $this->debugScripts );
			if ( $this->debugRaw ) {
				$tags = '';
				foreach ( $files as $file ) {
					$tags .= "<script type=\"text/javascript\" src=\"$wgScriptPath/$file\"></script>";
				}
				return "\n\tdocument.write( " . FormatJson::encode( $tags ) . ' );';
			}
		}
		return self::readScriptFiles( $files );
	}

	/**
	 * Gets loader script.
	 * 
	 * @return {string} JavaScript code to be added to startup module
	 */
	public function getLoaderScript() {
		if ( count( $this->loaderScripts ) == 0 ) {
			return false;
		}
		return self::readScriptFiles( $this->loaderScripts );
	}

	/**
	 * Gets all styles for a given context concatenated together.
	 * 
	 * @param {ResourceLoaderContext} $context Context in which to generate styles
	 * @return {string} CSS code for $context
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		// Merge general styles and skin specific styles, retaining media type collation
		$styles = self::readStyleFiles( $this->styles );
		$skinStyles = self::readStyleFiles( self::tryForKey( $this->skinStyles, $context->getSkin(), 'default' ) );
		foreach ( $skinStyles as $media => $style ) {
			if ( isset( $styles[$media] ) ) {
				$styles[$media] .= $style;
			} else {
				$styles[$media] = $style;
			}
		}
		// Collect referenced files
		$files = array();
		foreach ( $styles as /* $media => */ $style ) {
			$files = array_merge( $files, CSSMin::getLocalFileReferences( $style ) );
		}
		// If the list has been modified since last time we cached it, update the cache
		if ( $files !== $this->getFileDependencies( $context->getSkin() ) ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->replace( 'module_deps',
				array( array( 'md_module', 'md_skin' ) ), array(
					'md_module' => $this->getName(),
					'md_skin' => $context->getSkin(),
					'md_deps' => FormatJson::encode( $files ),
				)
			);
		}
		return $styles;
	}

	/**
	 * Gets list of message keys used by this module.
	 * 
	 * @return {array} List of message keys
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Gets the name of the group this module should be loaded in.
	 * 
	 * @return {string} Group name
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Gets list of names of modules this module depends on.
	 * 
	 * @return {array} List of module names
	 */
	public function getDependencies() {
		return $this->dependencies;
	}

	/**
	 * Get the last modified timestamp of this module.
	 * 
	 * Last modified timestamps are calculated from the highest last modified timestamp of this module's constituent
	 * files as well as the files it depends on. This function is context-sensitive, only performing calculations on
	 * files relevant to the given language, skin and debug mode.
	 * 
	 * @param {ResourceLoaderContext} $context Context in which to calculate the modified time
	 * @return {integer} UNIX timestamp
	 * @see {ResourceLoaderModule::getFileDependencies}
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		if ( isset( $this->modifiedTime[$context->getHash()] ) ) {
			return $this->modifiedTime[$context->getHash()];
		}
		wfProfileIn( __METHOD__ );
		
		$files = array();
		
		// Flatten style files into $files
		$styles = self::collateFilePathListByOption( $this->styles, 'media', 'all' );
		foreach ( $styles as $styleFiles ) {
			$files = array_merge( $files, $styleFiles );
		}
		$skinFiles = self::tryForKey(
			self::collateFilePathListByOption( $this->skinStyles, 'media', 'all' ), $context->getSkin(), 'default'
		);
		foreach ( $skinFiles as $styleFiles ) {
			$files = array_merge( $files, $styleFiles );
		}
		
		// Final merge, this should result in a master list of dependent files
		$files = array_merge(
			$files,
			$this->scripts,
			$context->getDebug() ? $this->debugScripts : array(),
			self::tryForKey( $this->languageScripts, $context->getLanguage() ),
			self::tryForKey( $this->skinScripts, $context->getSkin(), 'default' ),
			$this->loaderScripts,
			$this->getFileDependencies( $context->getSkin() )
		);
		
		// If a module is nothing but a list of dependencies, we need to avoid giving max() an empty array
		if ( count( $files ) === 0 ) {
			return $this->modifiedTime[$context->getHash()] = 1;
		}
		
		wfProfileIn( __METHOD__.'-filemtime' );
		$filesMtime = max( array_map( 'filemtime', array_map( array( __CLASS__, 'resolveFilePath' ), $files ) ) );
		wfProfileOut( __METHOD__.'-filemtime' );
		$this->modifiedTime[$context->getHash()] = max( $filesMtime, $this->getMsgBlobMtime( $context->getLanguage() ) );
		wfProfileOut( __METHOD__ );
		return $this->modifiedTime[$context->getHash()];
	}

	/* Protected Members */

	/**
	 * Prefixes each file path in a list.
	 * 
	 * @param {array} $list List of file paths in any combination of index/path or path/options pairs
	 * @param {string} $prefix String to prepend to each file path in $list
	 * @return {array} List of prefixed file paths
	 */
	protected static function prefixFilePathList( array $list, $prefix ) {
		$prefixed = array();
		foreach ( $list as $key => $value ) {
			if ( is_array( $value ) ) {
				// array( [path] => array( [options] ) )
				$prefixed[$prefix . $key] = $value;
			} else {
				// array( [path] )
				$prefixed[$key] = $prefix . $value;
			}
		}
		return $prefixed;
	}

	/**
	 * Collates file paths by option (where provided).
	 * 
	 * @param {array} $list List of file paths in any combination of index/path or path/options pairs
	 * @return {array} List of file paths, collated by $option
	 */
	protected static function collateFilePathListByOption( array $list, $option, $default ) {
		$collatedFiles = array();
		foreach ( (array) $list as $key => $value ) {
			if ( is_int( $key ) ) {
				// File name as the value
				if ( !isset( $collatedFiles[$default] ) ) {
					$collatedFiles[$default] = array();
				}
				$collatedFiles[$default][] = $value;
			} else if ( is_array( $value ) ) {
				// File name as the key, options array as the value
				$optionValue = isset( $value[$option] ) ? $value[$option] : $default;
				if ( !isset( $collatedFiles[$optionValue] ) ) {
					$collatedFiles[$optionValue] = array();
				}
				$collatedFiles[$optionValue][] = $key;
			}
		}
		return $collatedFiles;
	}

	/**
	 * Gets a list of element that match a key, optionally using a fallback key.
	 * 
	 * @param {array} $list List of lists to select from
	 * @param {string} $key Key to look for in $map
	 * @param {string} $fallback Key to look for in $list if $key doesn't exist
	 * @return {array} List of elements from $map which matched $key or $fallback, or an empty list in case of no match
	 */
	protected static function tryForKey( array $list, $key, $fallback = null ) {
		if ( isset( $list[$key] ) && is_array( $list[$key] ) ) {
			return $list[$key];
		} else if ( is_string( $fallback ) && isset( $list[$fallback] ) && is_array( $list[$fallback] ) ) {
			return $list[$fallback];
		}
		return array();
	}

	/**
	 * Gets the contents of a list of JavaScript files.
	 * 
	 * @param {array} $scripts List of file paths to scripts to read, remap and concetenate
	 * @return {string} Concatenated and remapped JavaScript data from $scripts
	 */
	protected static function readScriptFiles( array $scripts ) {
		if ( empty( $scripts ) ) {
			return '';
		}
		return implode( "\n", array_map( array( __CLASS__, 'readScriptFile' ), array_unique( $scripts ) ) );
	}

	/**
	 * Gets the contents of a list of CSS files.
	 * 
	 * @param {array} $styles List of file paths to styles to read, remap and concetenate
	 * @return {array} List of concatenated and remapped CSS data from $styles, keyed by media type
	 */
	protected static function readStyleFiles( array $styles ) {
		if ( empty( $styles ) ) {
			return array();
		}
		$styles = self::collateFilePathListByOption( $styles, 'media', 'all' );
		foreach ( $styles as $media => $files ) {
			$styles[$media] = implode(
				"\n", array_map( array( __CLASS__, 'readStyleFile' ), array_unique( $files ) )
			);
		}
		return $styles;
	}

	/**
	 * Reads a script file.
	 * 
	 * This method can be used as a callback for array_map()
	 * 
	 * @param {string} $path File path of script file to read
	 * @return {string} JavaScript data in script file
	 */
	protected static function readScriptFile( $path ) {
		global $IP;
		
		return file_get_contents( "$IP/$path" );
	}

	/**
	 * Reads a style file.
	 * 
	 * This method can be used as a callback for array_map()
	 * 
	 * @param {string} $path File path of script file to read
	 * @return {string} CSS data in script file
	 */
	protected static function readStyleFile( $path ) {
		global $wgScriptPath, $IP;
		
		return CSSMin::remap(
			file_get_contents( "$IP/$path" ), dirname( "$IP/$path" ), $wgScriptPath . '/' . dirname( $path ), true
		);
	}

	/**
	 * Resolves a file name.
	 * 
	 * This method can be used as a callback for array_map()
	 * 
	 * @param {string} $path File path to resolve
	 * @return {string} Absolute file path
	 */
	protected static function resolveFilePath( $path ) {
		global $IP;
		
		return "$IP/$path";
	}
}
