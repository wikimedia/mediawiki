<?php
/**
 * DjVu image handler.
 *
 * Copyright © 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;
use Wikimedia\AtEase\AtEase;

/**
 * Support for detecting/validating DjVu image files and getting
 * some basic file metadata (resolution etc)
 *
 * File format docs are available in source package for DjVuLibre:
 * http://djvulibre.djvuzone.org/
 *
 * @ingroup Media
 */
class DjVuImage {

	/**
	 * Memory limit for the DjVu description software
	 */
	private const DJVUTXT_MEMORY_LIMIT = 300_000_000;

	/** @var string */
	private $mFilename;

	/**
	 * @param string $filename The DjVu file name.
	 */
	public function __construct( $filename ) {
		$this->mFilename = $filename;
	}

	/**
	 * Check if the given file is indeed a valid DjVu image file
	 * @return bool
	 */
	public function isValid() {
		$info = $this->getInfo();

		return $info !== false;
	}

	/**
	 * Return width and height
	 * @return array An array with "width" and "height" keys, or an empty array on failure.
	 */
	public function getImageSize() {
		$data = $this->getInfo();

		if ( $data !== false ) {
			return [
				'width' => $data['width'],
				'height' => $data['height']
			];
		}
		return [];
	}

	// ---------

	/**
	 * For debugging; dump the IFF chunk structure
	 */
	public function dump() {
		$file = fopen( $this->mFilename, 'rb' );
		$header = fread( $file, 12 );
		$arr = unpack( 'a4magic/a4chunk/NchunkLength', $header );
		$chunk = $arr['chunk'];
		$chunkLength = $arr['chunkLength'];
		echo "$chunk $chunkLength\n";
		$this->dumpForm( $file, $chunkLength, 1 );
		fclose( $file );
	}

	/**
	 * @param resource $file
	 * @param int $length
	 * @param int $indent
	 */
	private function dumpForm( $file, int $length, int $indent ) {
		$start = ftell( $file );
		$secondary = fread( $file, 4 );
		echo str_repeat( ' ', $indent * 4 ) . "($secondary)\n";
		while ( ftell( $file ) - $start < $length ) {
			$chunkHeader = fread( $file, 8 );
			if ( $chunkHeader == '' ) {
				break;
			}
			$arr = unpack( 'a4chunk/NchunkLength', $chunkHeader );
			$chunk = $arr['chunk'];
			$chunkLength = $arr['chunkLength'];
			echo str_repeat( ' ', $indent * 4 ) . "$chunk $chunkLength\n";

			if ( $chunk === 'FORM' ) {
				$this->dumpForm( $file, $chunkLength, $indent + 1 );
			} else {
				fseek( $file, $chunkLength, SEEK_CUR );
				if ( $chunkLength & 1 ) {
					// Padding byte between chunks
					fseek( $file, 1, SEEK_CUR );
				}
			}
		}
	}

	/** @return array|false */
	private function getInfo() {
		AtEase::suppressWarnings();
		$file = fopen( $this->mFilename, 'rb' );
		AtEase::restoreWarnings();
		if ( $file === false ) {
			wfDebug( __METHOD__ . ": missing or failed file read" );

			return false;
		}

		$header = fread( $file, 16 );
		$info = false;

		if ( strlen( $header ) < 16 ) {
			wfDebug( __METHOD__ . ": too short file header" );
		} else {
			$arr = unpack( 'a4magic/a4form/NformLength/a4subtype', $header );

			$subtype = $arr['subtype'];
			if ( $arr['magic'] !== 'AT&T' ) {
				wfDebug( __METHOD__ . ": not a DjVu file" );
			} elseif ( $subtype === 'DJVU' ) {
				// Single-page document
				$info = $this->getPageInfo( $file );
			} elseif ( $subtype === 'DJVM' ) {
				// Multi-page document
				$info = $this->getMultiPageInfo( $file, $arr['formLength'] );
			} else {
				wfDebug( __METHOD__ . ": unrecognized DJVU file type '{$arr['subtype']}'" );
			}
		}
		fclose( $file );

		return $info;
	}

	/**
	 * @param resource $file
	 */
	private function readChunk( $file ): array {
		$header = fread( $file, 8 );
		if ( strlen( $header ) < 8 ) {
			return [ false, 0 ];
		}
		$arr = unpack( 'a4chunk/Nlength', $header );

		return [ $arr['chunk'], $arr['length'] ];
	}

	/**
	 * @param resource $file
	 * @param int $chunkLength
	 */
	private function skipChunk( $file, int $chunkLength ) {
		fseek( $file, $chunkLength, SEEK_CUR );

		if ( ( $chunkLength & 1 ) && !feof( $file ) ) {
			// padding byte
			fseek( $file, 1, SEEK_CUR );
		}
	}

	/**
	 * @param resource $file
	 * @param int $formLength
	 * @return array|false
	 */
	private function getMultiPageInfo( $file, int $formLength ) {
		// For now, we'll just look for the first page in the file
		// and report its information, hoping others are the same size.
		$start = ftell( $file );
		do {
			[ $chunk, $length ] = $this->readChunk( $file );
			if ( !$chunk ) {
				break;
			}

			if ( $chunk === 'FORM' ) {
				$subtype = fread( $file, 4 );
				if ( $subtype === 'DJVU' ) {
					wfDebug( __METHOD__ . ": found first subpage" );

					return $this->getPageInfo( $file );
				}
				$this->skipChunk( $file, $length - 4 );
			} else {
				wfDebug( __METHOD__ . ": skipping '$chunk' chunk" );
				$this->skipChunk( $file, $length );
			}
		} while ( $length != 0 && !feof( $file ) && ftell( $file ) - $start < $formLength );

		wfDebug( __METHOD__ . ": multi-page DJVU file contained no pages" );

		return false;
	}

	/**
	 * @param resource $file
	 * @return array|false
	 */
	private function getPageInfo( $file ) {
		[ $chunk, $length ] = $this->readChunk( $file );
		if ( $chunk !== 'INFO' ) {
			wfDebug( __METHOD__ . ": expected INFO chunk, got '$chunk'" );

			return false;
		}

		if ( $length < 9 ) {
			wfDebug( __METHOD__ . ": INFO should be 9 or 10 bytes, found $length" );

			return false;
		}
		$data = fread( $file, $length );
		if ( strlen( $data ) < $length ) {
			wfDebug( __METHOD__ . ": INFO chunk cut off" );

			return false;
		}

		$arr = unpack(
			'nwidth/' .
			'nheight/' .
			'Cminor/' .
			'Cmajor/' .
			'vresolution/' .
			'Cgamma', $data );

		# Newer files have rotation info in byte 10, but we don't use it yet.

		return [
			'width' => $arr['width'],
			'height' => $arr['height'],
			'version' => "{$arr['major']}.{$arr['minor']}",
			'resolution' => $arr['resolution'],
			'gamma' => $arr['gamma'] / 10.0 ];
	}

	/**
	 * Return an array describing the DjVu image
	 * @return array|null|false
	 */
	public function retrieveMetaData() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$djvuDump = $config->get( MainConfigNames::DjvuDump );
		$djvuTxt = $config->get( MainConfigNames::DjvuTxt );
		$djvuUseBoxedCommand = $config->get( MainConfigNames::DjvuUseBoxedCommand );
		$shell = $config->get( MainConfigNames::ShellboxShell );
		if ( !$this->isValid() ) {
			return false;
		}

		if ( $djvuTxt === null && $djvuDump === null ) {
			return [];
		}

		$txt = null;
		$dump = null;

		if ( $djvuUseBoxedCommand ) {
			$command = MediaWikiServices::getInstance()->getShellCommandFactory()
				->createBoxed( 'djvu' )
				->disableNetwork()
				->firejailDefaultSeccomp()
				->routeName( 'djvu-metadata' )
				->params( $shell, 'scripts/retrieveDjvuMetaData.sh' )
				->inputFileFromFile(
					'scripts/retrieveDjvuMetaData.sh',
					__DIR__ . '/scripts/retrieveDjvuMetaData.sh' )
				->inputFileFromFile( 'file.djvu', $this->mFilename )
				->memoryLimit( self::DJVUTXT_MEMORY_LIMIT );
			$env = [];
			if ( $djvuDump !== null ) {
				$env['DJVU_DUMP'] = $djvuDump;
				$command->outputFileToString( 'dump' );
			}
			if ( $djvuTxt !== null ) {
				$env['DJVU_TXT'] = $djvuTxt;
				$command->outputFileToString( 'txt' );
			}

			$result = $command
				->environment( $env )
				->execute();
			if ( $result->getExitCode() !== 0 ) {
				wfDebug( 'retrieveDjvuMetaData failed with exit code ' . $result->getExitCode() );
				return false;
			}
			if ( $djvuDump !== null ) {
				if ( $result->wasReceived( 'dump' ) ) {
					$dump = $result->getFileContents( 'dump' );
				} else {
					wfDebug( __METHOD__ . ": did not receive dump file" );
				}
			}

			if ( $djvuTxt !== null ) {
				if ( $result->wasReceived( 'txt' ) ) {
					$txt = $result->getFileContents( 'txt' );
				} else {
					wfDebug( __METHOD__ . ": did not receive text file" );
				}
			}
		} else { // No boxedcommand
			if ( $djvuDump !== null ) {
				# djvudump is faster than djvutoxml (now abandoned) as of version 3.5
				# https://sourceforge.net/p/djvu/bugs/71/
				$cmd = Shell::escape( $djvuDump ) . ' ' . Shell::escape( $this->mFilename );
				$dump = wfShellExec( $cmd );
			}
			if ( $djvuTxt !== null ) {
				$cmd = Shell::escape( $djvuTxt ) . ' --detail=page ' . Shell::escape( $this->mFilename );
				wfDebug( __METHOD__ . ": $cmd" );
				$retval = 0;
				$txt = wfShellExec( $cmd, $retval, [], [ 'memory' => self::DJVUTXT_MEMORY_LIMIT ] );
				if ( $retval !== 0 ) {
					$txt = null;
				}
			}
		}

		# Convert dump to array
		$json = [];
		if ( $dump !== null ) {
			$data = $this->convertDumpToJSON( $dump );
			if ( $data !== false ) {
				$json = [ 'data' => $data ];
			}
		}

		# Text layer
		if ( $txt !== null ) {
			# Strip some control characters
			# Ignore carriage returns
			$txt = preg_replace( "/\\\\013/", "", $txt );
			# Replace runs of OCR region separators with a single extra line break
			$txt = preg_replace( "/(?:\\\\(035|037))+/", "\n", $txt );

			$reg = <<<EOR
				/\(page\s[\d-]*\s[\d-]*\s[\d-]*\s[\d-]*\s*"
				((?>    # Text to match is composed of atoms of either:
					\\\\. # - any escaped character
					|     # - any character different from " and \
					[^"\\\\]+
				)*?)
				"\s*\)
				| # Or page can be empty ; in this case, djvutxt dumps ()
				\(\s*()\)/sx
EOR;
			$matches = [];
			preg_match_all( $reg, $txt, $matches );
			$json['text'] = array_map( $this->pageTextCallback( ... ), $matches[1] );
		} else {
			$json['text'] = [];
		}

		return $json;
	}

	private function pageTextCallback( string $match ): string {
		# Get rid of invalid UTF-8
		$val = UtfNormal\Validator::cleanUp( stripcslashes( $match ) );
		return str_replace( '�', '', $val );
	}

	/**
	 * @param string $dump
	 * @return array|false
	 */
	private function convertDumpToJSON( $dump ) {
		if ( strval( $dump ) == '' ) {
			return false;
		}

		$dump = str_replace( "\r", '', $dump );
		$line = strtok( $dump, "\n" );
		$m = false;
		$good = false;
		$result = [];
		if ( preg_match( '/^( *)FORM:DJVU/', $line, $m ) ) {
			# Single-page
			$parsed = $this->parseFormDjvu( $line );
			if ( $parsed ) {
				$good = true;
			} else {
				return false;
			}
			$result['pages'] = [ $parsed ];
		} elseif ( preg_match( '/^( *)FORM:DJVM/', $line, $m ) ) {
			# Multi-page
			$parentLevel = strlen( $m[1] );
			# Find DIRM
			$line = strtok( "\n" );
			$result['pages'] = [];
			while ( $line !== false ) {
				$childLevel = strspn( $line, ' ' );
				if ( $childLevel <= $parentLevel ) {
					# End of chunk
					break;
				}

				if ( preg_match( '/^ *DIRM.*indirect/', $line ) ) {
					wfDebug( "Indirect multi-page DjVu document, bad for server!" );

					return false;
				}

				if ( preg_match( '/^ *FORM:DJVU/', $line ) ) {
					# Found page
					$parsed = $this->parseFormDjvu( $line );
					if ( $parsed ) {
						$good = true;
					} else {
						return false;
					}
					$result['pages'][] = $parsed;
				}
				$line = strtok( "\n" );
			}
		}
		if ( !$good ) {
			return false;
		}

		return $result;
	}

	/** @return array|false */
	private function parseFormDjvu( string $line ) {
		$parentLevel = strspn( $line, ' ' );
		$line = strtok( "\n" );
		# Find INFO
		while ( $line !== false ) {
			$childLevel = strspn( $line, ' ' );
			if ( $childLevel <= $parentLevel ) {
				# End of chunk
				break;
			}

			if ( preg_match(
				'/^ *INFO *\[\d*] *DjVu *(\d+)x(\d+), *\w*, *(\d+) *dpi, *gamma=([0-9.-]+)/',
				$line,
				$m
			) ) {
				return [
					'height' => (int)$m[2],
					'width' => (int)$m[1],
					'dpi' => (float)$m[3],
					'gamma' => (float)$m[4],
				];
			}
			$line = strtok( "\n" );
		}

		# Not found
		return false;
	}
}
