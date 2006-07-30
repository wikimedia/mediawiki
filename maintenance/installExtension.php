<?php
/**
 * Copyright (C) 2006 Daniel Kinzler, brightbyte.de
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
 * @package MediaWiki
 * @subpackage Maintenance
 */

$optionsWithArgs = array( 'target' );

require_once( 'commandLine.inc' );

class ExtensionInstaller {
	var $source;
	var $target;
	var $name;
	var $dir;

	function ExtensionInstaller( $name, $source, $target ) {
		$this->name = $name;
		$this->source = $source;
		$this->target = realpath( $target );
		$this->extdir = "$target/extensions";
		$this->dir = "{$this->extdir}/$name";
		$this->incpath = "extensions/$name";
		
		#TODO: allow a subdir different from "extensions"
		#TODO: allow a config file different from "LocalSettings.php"
	}

	function note( $msg ) {
		print "$msg\n";
	}

	function warn( $msg ) {
		print "WARNING: $msg\n";
	}

	function error( $msg ) {
		print "ERROR: $msg\n";
	}

	function prompt( $msg ) {
		if ( function_exists( 'readline' ) ) {
			$s = readline( $msg );
		}
		else {
			if ( !@$this->stdin ) $this->stdin = fopen( 'php://stdin', 'r' );
			if ( !$this->stdin ) die( "Failed to open stdin for user interaction!\n" );
			
			print $msg;
			flush();
			
			$s = fgets( $this->stdin );
		}
		
		$s = trim( $s );
		return $s;                
	}

	function confirm( $msg ) {
		while ( true ) {        
			$s = $this->prompt( $msg . " [yes/no]: ");
			$s = strtolower( trim($s) );
			
			if ( $s == 'yes' || $s == 'y' ) return true;
			else if ( $s == 'no' || $s == 'n' ) return false;
			else print "bad response: $s\n";                        
		}
	}

	function deleteContents( $dir ) {
		$ff = glob( $dir . "/*" );
		if ( !$ff ) return;

		foreach ( $ff as $f ) {
			if ( is_dir( $f ) ) $this->deleteContents( $f );
			unlink( $f );
		}
	}
        
	function copyDir( $dir, $tgt ) {
		$d = $tgt . '/' . basename( $dir );
		
		if ( !file_exists( $d ) ) {
			$ok = mkdir( $d );
			if ( !$ok ) {
				$this->error( "failed to create director $d" );
				return false;
			}
		}

		$ff = glob( $dir . "/*" );
		if ( $ff === false || $ff === NULL ) return false;

		foreach ( $ff as $f ) {
			if ( is_dir( $f ) ) {
				$ok = $this->copyDir( $f, $d );
				if ( !$ok ) return false;
			}
			else {
				$t = $d . '/' . basename( $f );
				$ok = copy( $f, $t );

				if ( !$ok ) {
					$this->error( "failed to copy $f to $t" );
					return false;
				}
			}
		}
		
		return true;
	}
        
	function fetchExtension( ) {
		if ( file_exists( $this->dir ) && glob( $this->dir . "/*" ) 
		     && realpath( $this->source ) != $this->dir ) {
			
			if ( $this->confirm( "{$this->dir} exists and is not empty.\nDelete all files in that directory?" ) ) {
				$this->deleteContents( $this->dir );
			}                        
			else {
				return false;
			}                        
		}

		preg_match( '!([-\w]+://)?.*?(\.[-\w\d.]+)?$!', $this->source, $m );
		$proto = @$m[1];
		$ext = @$m[2];
		if ( $ext ) $ext = strtolower( $ext );
		
		$src = $this->source;
		
		if ( $proto && $ext ) { #remote file
			$tmp = wfTempDir() . '/' . basename( $src );
			
			$this->note( "fetching {$this->source}..." );
			$ok = copy( $src, $tmp );
			
			if ( !$ok ) {
				$this->error( "failed to download {$src}" );
				return false;
			}
			
			$src = $tmp;
			$proto = NULL;
		}

		if ( $proto ) { #assume SVN repository
			$this->note( "SVN checkout of $src..." );
			wfShellExec( 'svn co ' . escapeshellarg( $src ) . ' ' . escapeshellarg( $this->dir ), $code );

			if ( $code !== 0 ) {
				$this->error( "checkout failed for $src!" );
				return false;
			}
		} 
		else { #local file or directory
			$src = realpath ( $src );
			
			if ( !file_exists( $src ) ) {
				$this->error( "file not found: {$this->source}" );
				return false;
			}
			
			if ( $ext === NULL || $ext === '') { #local dir
				if ( $src == $this->dir ) {
					$this->note( "files are already in the extension dir" );
					return true;
				}
				
				$this->copyDir( $src, $this->extdir );
			}
			else if ( $ext == '.tgz' || $ext == '.tar.gz' ) { #tgz file
				$this->note( "extracting $src..." );
				wfShellExec( 'tar zxvf ' . escapeshellarg( $src ) . ' -C ' . escapeshellarg( $this->extdir ), $code );
				
				if ( $code !== 0 ) {
					$this->error( "failed to extract $src!" );
					return false;
				}
			}
			else if ( $ext == '.zip' ) { #zip file
				$this->note( "extracting $src..." );
				wfShellExec( 'unzip ' . escapeshellarg( $src ) . ' -d ' . escapeshellarg( $this->extdir ) , $code );
				
				if ( $code !== 0 ) {
					$this->error( "failed to extract $src!" );
					return false;
				}
			}
			else {
				$this->error( "unknown file extension: $ext" );
				return false;
			}
		}

		if ( !file_exists( $this->dir ) && glob( $this->dir . "/*" ) ) {
			$this->error( "{$this->dir} does not exist or is empty. Something went wrong, sorry." );
			return false;
		}

		#TODO: set permissions.... somehow. Copy from extension dir??

		$this->note( "fetched extension to {$this->dir}" );
		return true;
	}

	function patchLocalSettings( ) {
		$f = $this->dir . '/install.settings';
		$t = $this->target . '/LocalSettings.php';
		
		#TODO: assert version ?!
		#TODO: allow custom installer scripts                
		
		if ( !file_exists( $f ) ) {
			$this->warn( "No install.settings file provided! Please read the instructions and edit LocalSettings.php manually." );
			return '?';
		}
		
		$settings = file_get_contents( $f );
		                
		if ( !$settings ) {
			$this->error( "failed to read settings from $f!" );
			return false;
		}
		                
		$settings = str_replace( '{{path}}', $this->incpath, $settings );
		                
		#NOTE: keep php extension for backup file!
		$bak = $this->target . '/LocalSettings.install-' . $this->name . '-' . wfTimestamp(TS_MW) . '.bak.php';
		                
		$ok = copy( $t, $bak );
		                
		if ( !$ok ) {
			$this->warn( "failed to create backup of LocalSettings.php!" );
			return false;
		}
		else {
			$this->note( "created backup of LocalSettings.php at $bak" );
		}
		                
		$localsettings = file_get_contents( $t );
		                
		if ( !$settings ) {
			$this->error( "failed to read $t for patching!" );
			return false;
		}
		                
		$marker = "<@< extension {$this->name} >@>";
		$blockpattern = "/\n\s*#\s*BEGIN\s*$marker.*END\s*$marker\s*/smi";
		
		if ( preg_match( $blockpattern, $localsettings ) ) {
			$localsettings = preg_replace( $blockpattern, "\n", $localsettings );
			$this->warn( "removed old configuration block for extension {$this->name}!" );
		}
		
		$newblock= "\n# BEGIN $marker\n$settings\n# END $marker\n";
		
		$localsettings = preg_replace( "/\?>\s*$/si", "$newblock?>", $localsettings );
		
		$ok = file_put_contents( $t, $localsettings );
		
		if ( !$ok ) {
			$this->error( "failed to patch $t!" );
			return false;
		}
		else {
			$this->note( "successfully patched LocalSettings.php" );
		}
		
		return true;
	}

	function printNotices( ) {
		$files = array();
		
		if ( file_exists( $this->dir . '/README' ) ) $files[] = 'README';
		if ( file_exists( $this->dir . '/INSTALL' ) ) $files[] = 'INSTALL';
		
		if ( !$files ) {
			$this->note( "no information files found in {$this->dir}" );
		}
		else {
			$this->note( "" );
			
			$this->note( "Please have a look at the following files in {$this->dir}," );
			$this->note( "they may contain important information about {$this->name}." );
			
			$this->note( "" );
			   
			foreach ( $files as $f ) {
				$this->note ( "\t* $f" );
			}
			
			$this->note( "" );
		}
		
		return true;
	}
}

if( !isset( $args[0] ) ) {
	die( "USAGE: installExtension.php [options] name [source]\n" .
		"OPTIONS: \n" . 
		"    --target <dir>    mediawiki installation directory\n" .
		"SOURCE: \n" . 
		"    May be a local file (tgz or zip) or directory.\n" .
		"    May be the URL of a remote file (tgz or zip).\n" .
		"    May be a SVN repository\n" 
                );
}

$name = $args[0];

# Default to SVN trunk. Perhaps change that to use the version of the present install,
# and/or use bundles at an official download location.
# Also, perhaps use the local systems versioin to select the right branch
$defsrc = "http://svn.wikimedia.org/svnroot/mediawiki/trunk/extensions/" . urlencode($name);

$src = isset ( $args[1] ) ? $args[1] : $defsrc;

$tgt = isset ( $options['target'] ) ? $options['target'] : $IP;

if ( !file_exists( "$tgt/LocalSettings.php" ) ) {
	die("can't find $tgt/LocalSettings.php\n");
}

if ( !is_writable( "$tgt/LocalSettings.php" ) ) {
	die("can't write to  $tgt/LocalSettings.php\n");
}

if ( !file_exists( "$tgt/extensions" ) ) {
	die("can't find $tgt/extensions\n");
}

if ( !is_writable( "$tgt/extensions" ) ) {
	die("can't write to  $tgt/extensions\n");
}

$installer = new ExtensionInstaller( $name, $src, $tgt );

$installer->note( "Installing extension {$installer->name} from {$installer->source} to {$installer->dir}" );

print "\n";
print "\tTHIS TOOL IS EXPERIMENTAL!\n";
print "\tEXPECT THE UNEXPECTED!\n";
print "\n";

if ( !$installer->confirm("continue") ) die("aborted\n");

$ok = $installer->fetchExtension();
if ( $ok ) $ok = $installer->patchLocalSettings();
if ( $ok ) $ok = $installer->printNotices();
if ( $ok ) $installer->note( "$name extension was installed successfully" );
?>
