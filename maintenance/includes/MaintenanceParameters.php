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

namespace MediaWiki\Maintenance;

use UnexpectedValueException;

/**
 * Command line parameter handling for maintenance scripts.
 *
 * @since 1.39
 * @ingroup Maintenance
 */
class MaintenanceParameters {

	/**
	 * Array of desired/allowed params
	 * @var array<string,array>
	 * @phan-var array<string,array{desc:string,require:bool,withArg:string,shortName:string|bool,multiOccurrence:bool}>
	 */
	private $mOptDefs = [];

	/** @var array<string,string> Mapping short options to long ones */
	private $mShortOptionMap = [];

	/** @var array<int,array> Desired/allowed args */
	private $mArgDefs = [];

	/** @var array<string,int> Map of arg names to offsets */
	private $mArgOffsets = [];

	/** @var bool Allow arbitrary options to be passed, or only specified ones? */
	private $mAllowUnregisteredOptions = false;

	/** @var string|null Name of the script currently running */
	private $mName = null;

	/** @var string|null A description of the script, children should change this via addDescription() */
	private $mDescription = null;

	/** @var array<string,string> This is the list of options that were actually passed */
	private $mOptions = [];

	/** @var array<int,string> This is the list of arguments that were actually passed */
	private $mArgs = [];

	/** @var array<string,array> maps group names to lists of option names */
	private $mOptionGroups = [];

	/**
	 * Used to read the options in the order they were passed.
	 * This is an array of arrays where
	 * 0 => the option name and 1 => option value.
	 *
	 * @var array
	 */
	private $optionsSequence = [];

	/** @var string[] */
	private $errors = [];

	/** @var string */
	private $usagePrefix = 'php';

	/**
	 * Returns a reference to a member field.
	 * This is a backwards compatibility hack, it should be removed as soon as possible!
	 *
	 * @param string $fieldName
	 *
	 * @return mixed A reference to a member field
	 * @internal For use by the Maintenance class, for backwards compatibility support.
	 */
	public function &getFieldReference( string $fieldName ) {
		return $this->$fieldName;
	}

	/**
	 * Assigns a list of options to the given group.
	 * The given options will be shown as part of the given group
	 * in the help message.
	 *
	 * @param string $groupName
	 * @param array $paramNames
	 */
	public function assignGroup( string $groupName, array $paramNames ) {
		$this->mOptionGroups[ $groupName ] = array_merge(
			$this->mOptionGroups[ $groupName ] ?? [],
			$paramNames
		);
	}

	/**
	 * Checks to see if a particular option in supported. Normally this means it
	 * has been registered by the script via addOption.
	 * @param string $name The name of the option<string,string>
	 * @return bool true if the option exists, false otherwise
	 */
	public function supportsOption( string $name ) {
		return isset( $this->mOptDefs[$name] );
	}

	/**
	 * Add a option to the script. Will be displayed on --help
	 * with the associated description
	 *
	 * @param string $name The name of the param (help, version, etc)
	 * @param string $description The description of the param to show on --help
	 * @param bool $required Is the param required?
	 * @param bool $withArg Is an argument required with this option?
	 * @param string|bool $shortName Character to use as short name
	 * @param bool $multiOccurrence Can this option be passed multiple times?
	 */
	public function addOption( string $name, string $description, bool $required = false,
		bool $withArg = false, $shortName = false, bool $multiOccurrence = false
	) {
		$this->mOptDefs[$name] = [
			'desc' => $description,
			'require' => $required,
			'withArg' => $withArg,
			'shortName' => $shortName,
			'multiOccurrence' => $multiOccurrence
		];

		if ( $shortName !== false ) {
			$this->mShortOptionMap[$shortName] = $name;
		}
	}

	/**
	 * Checks to see if a particular option was set.
	 *
	 * @param string $name The name of the option
	 * @return bool
	 */
	public function hasOption( string $name ): bool {
		return isset( $this->mOptions[$name] );
	}

	/**
	 * Get the value of an option, or return the default.
	 *
	 * If the option was defined to support multiple occurrences,
	 * this will return an array.
	 *
	 * @param string $name The name of the param
	 * @param mixed|null $default Anything you want, default null
	 * @return mixed
	 * @return-taint none
	 */
	public function getOption( string $name, $default = null ) {
		if ( $this->hasOption( $name ) ) {
			return $this->mOptions[$name];
		} else {
			return $default;
		}
	}

	/**
	 * Define a positional argument. getArg() can later be used to get the value given
	 * for the argument, by index or by name.
	 *
	 * @param string $arg Name of the arg, like 'start'
	 * @param string $description Short description of the arg
	 * @param bool $required Is this required?
	 * @param bool $multi Does it allow multiple values? (Last arg only)
	 * @return int the offset of the argument
	 */
	public function addArg( string $arg, string $description, bool $required = true, bool $multi = false ): int {
		if ( isset( $this->mArgOffsets[$arg] ) ) {
			throw new UnexpectedValueException( "Argument already defined: $arg" );
		}

		$argCount = count( $this->mArgDefs );
		if ( $argCount ) {
			$prevArg = $this->mArgDefs[ $argCount - 1 ];
			if ( !$prevArg['require'] && $required ) {
				throw new UnexpectedValueException(
					"Required argument {$arg} cannot follow an optional argument {$prevArg['name']}"
				);
			}

			if ( $prevArg['multi'] ) {
				throw new UnexpectedValueException(
					"Argument {$arg} cannot follow multi-value argument {$prevArg['name']}"
				);
			}
		}

		$this->mArgDefs[] = [
			'name' => $arg,
			'desc' => $description,
			'require' => $required,
			'multi' => $multi,
		];

		$ofs = count( $this->mArgDefs ) - 1;
		$this->mArgOffsets[$arg] = $ofs;
		return $ofs;
	}

	/**
	 * Remove an option. Useful for removing options that won't be used in your script.
	 * @param string $name The option to remove.
	 */
	public function deleteOption( string $name ) {
		unset( $this->mOptDefs[$name] );
		unset( $this->mOptions[$name] );

		foreach ( $this->optionsSequence as $i => [ $opt, ] ) {
			if ( $opt === $name ) {
				unset( $this->optionsSequence[$i] );
				break;
			}
		}
	}

	/**
	 * Sets whether to allow unknown options to be passed to the script.
	 * Per default, unknown options cause an error.
	 * @param bool $allow Should we allow?
	 */
	public function setAllowUnregisteredOptions( bool $allow ) {
		$this->mAllowUnregisteredOptions = $allow;
	}

	/**
	 * Set a short description of what the script does.
	 * @param string $text
	 */
	public function setDescription( string $text ) {
		$this->mDescription = $text;
	}

	/**
	 * Was a value for the given argument provided?
	 * @param int|string $argId The index (from zero) of the argument, or
	 *                   the name declared for the argument by addArg().
	 * @return bool
	 */
	public function hasArg( $argId ): bool {
		// arg lookup by name
		if ( is_string( $argId ) && isset( $this->mArgOffsets[$argId] ) ) {
			$argId = $this->mArgOffsets[$argId];
		}

		return isset( $this->mArgs[$argId] );
	}

	/**
	 * Get an argument.
	 * @param int|string $argId The index (from zero) of the argument, or
	 *                   the name declared for the argument by addArg().
	 * @param string|null $default The default if it doesn't exist
	 * @return string|null
	 * @return-taint none
	 */
	public function getArg( $argId, ?string $default = null ): ?string {
		// arg lookup by name
		if ( is_string( $argId ) && isset( $this->mArgOffsets[$argId] ) ) {
			$argId = $this->mArgOffsets[$argId];
		}

		return $this->mArgs[$argId] ?? $default;
	}

	/**
	 * Get arguments.
	 * @param int|string $offset The index (from zero) of the first argument, or
	 *                   the name declared for the argument by addArg().
	 * @return string[]
	 */
	public function getArgs( $offset = 0 ): array {
		if ( is_string( $offset ) && isset( $this->mArgOffsets[$offset] ) ) {
			$offset = $this->mArgOffsets[$offset];
		}

		return array_slice( $this->mArgs, $offset );
	}

	/**
	 * Get the name of an argument at the given index.
	 *
	 * @param int $argIndex The integer value (from zero) for the arg
	 *
	 * @return ?string the name of the argument, or null if no name is defined for that argument
	 */
	public function getArgName( int $argIndex ): ?string {
		return $this->mArgDefs[ $argIndex ]['name'] ?? null;
	}

	/**
	 * Programmatically set the value of the given option.
	 * Useful for setting up child scripts, see runChild().
	 *
	 * @param string $name
	 * @param mixed|null $value
	 */
	public function setOption( string $name, $value ): void {
		$this->mOptions[$name] = $value;
	}

	/**
	 * Programmatically set the value of the given argument.
	 * Useful for setting up child scripts, see runChild().
	 *
	 * @param string|int $argId
	 * @param string $value
	 */
	public function setArg( $argId, $value ): void {
		// arg lookup by name
		if ( is_string( $argId ) && isset( $this->mArgOffsets[$argId] ) ) {
			$argId = $this->mArgOffsets[$argId];
		}
		$this->mArgs[$argId] = $value;
	}

	/**
	 * Clear all parameter values.
	 * Note that all parameter definitions remain intact.
	 */
	public function clear() {
		$this->mOptions = [];
		$this->mArgs = [];
		$this->optionsSequence = [];
		$this->errors = [];
	}

	/**
	 * Merge options declarations from $other into this instance.
	 *
	 * @param MaintenanceParameters $other
	 */
	public function mergeOptions( MaintenanceParameters $other ) {
		$this->mOptDefs = $other->mOptDefs + $this->mOptDefs;
		$this->mShortOptionMap = $other->mShortOptionMap + $this->mShortOptionMap;

		$this->mOptionGroups = array_merge_recursive( $this->mOptionGroups, $other->mOptionGroups );

		$this->clear();
	}

	/**
	 * Load params and arguments from a given array
	 * of command-line arguments
	 *
	 * @param array $argv The argument array.
	 * @param int $skip Skip that many elements at the beginning of $argv.
	 */
	public function loadWithArgv( array $argv, int $skip = 0 ) {
		$this->clear();

		$options = [];
		$args = [];
		$this->optionsSequence = [];

		// Ignore a number of arguments at the beginning of the array.
		// Typically used to ignore the script name at index 0.
		$argv = array_slice( $argv, $skip );

		# Parse arguments
		for ( $arg = reset( $argv ); $arg !== false; $arg = next( $argv ) ) {
			if ( $arg == '--' ) {
				# End of options, remainder should be considered arguments
				$arg = next( $argv );
				while ( $arg !== false ) {
					$args[] = $arg;
					$arg = next( $argv );
				}
				break;
			} elseif ( substr( $arg, 0, 2 ) == '--' ) {
				# Long options
				$option = substr( $arg, 2 );
				if ( isset( $this->mOptDefs[$option] ) && $this->mOptDefs[$option]['withArg'] ) {
					$param = next( $argv );
					if ( $param === false ) {
						$this->error( "Option --$option needs a value after it!" );
					}

					$this->setOptionValue( $options, $option, $param );
				} else {
					$bits = explode( '=', $option, 2 );
					$this->setOptionValue( $options, $bits[0], $bits[1] ?? 1 );
				}
			} elseif ( $arg == '-' ) {
				# Lonely "-", often used to indicate stdin or stdout.
				$args[] = $arg;
			} elseif ( substr( $arg, 0, 1 ) == '-' ) {
				# Short options
				$argLength = strlen( $arg );
				for ( $p = 1; $p < $argLength; $p++ ) {
					$option = $arg[$p];
					if ( !isset( $this->mOptDefs[$option] ) && isset( $this->mShortOptionMap[$option] ) ) {
						$option = $this->mShortOptionMap[$option];
					}

					if ( isset( $this->mOptDefs[$option]['withArg'] ) && $this->mOptDefs[$option]['withArg'] ) {
						$param = next( $argv );
						if ( $param === false ) {
							$this->error( "Option --$option needs a value after it!" );
						}
						$this->setOptionValue( $options, $option, $param );
					} else {
						$this->setOptionValue( $options, $option, 1 );
					}
				}
			} else {
				$args[] = $arg;
			}
		}

		$this->mOptions = $options;
		$this->mArgs = $args;
	}

	/**
	 * Helper function used solely by loadWithArgv
	 * to prevent code duplication
	 *
	 * This sets the param in the options array based on
	 * whether or not it can be specified multiple times.
	 *
	 * @param array &$options
	 * @param string $option
	 * @param mixed $value
	 */
	private function setOptionValue( array &$options, string $option, $value ) {
		$this->optionsSequence[] = [ $option, $value ];

		if ( isset( $this->mOptDefs[$option] ) ) {
			$multi = $this->mOptDefs[$option]['multiOccurrence'];
		} else {
			$multi = false;
		}
		$exists = array_key_exists( $option, $options );
		if ( $multi && $exists ) {
			$options[$option][] = $value;
		} elseif ( $multi ) {
			$options[$option] = [ $value ];
		} elseif ( !$exists ) {
			$options[$option] = $value;
		} else {
			$this->error( "Option --$option given twice" );
		}
	}

	private function error( string $msg ) {
		$this->errors[] = $msg;
	}

	/**
	 * Get any errors encountered while processing parameters.
	 *
	 * @return string[]
	 */
	public function getErrors(): array {
		return $this->errors;
	}

	/**
	 * Whether any errors have been recorded so far.
	 *
	 * @return bool
	 */
	public function hasErrors(): bool {
		return (bool)$this->errors;
	}

	/**
	 * Set the script name, for use in the help message
	 *
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->mName = $name;
	}

	/**
	 * Get the script name, as shown in the help message
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->mName;
	}

	/**
	 * Force option and argument values.
	 *
	 * @internal
	 *
	 * @param array $opts
	 * @param array $args
	 */
	public function setOptionsAndArgs( array $opts, array $args ) {
		$this->mOptions = $opts;
		$this->mArgs = $args;

		$this->optionsSequence = [];
		foreach ( $opts as $name => $value ) {
			$array = (array)$value;

			foreach ( $array as $v ) {
				$this->optionsSequence[] = [ $name, $v ];
			}
		}
	}

	/**
	 * Run some validation checks on the params, etc.
	 *
	 * Error details can be obtained via getErrors().
	 *
	 * @return bool
	 */
	public function validate() {
		$valid = true;
		# Check to make sure we've got all the required options
		foreach ( $this->mOptDefs as $opt => $info ) {
			if ( $info['require'] && !$this->hasOption( $opt ) ) {
				$this->error( "Option --$opt is required!" );
				$valid = false;
			}
		}
		# Check arg list too
		foreach ( $this->mArgDefs as $k => $info ) {
			if ( $info['require'] && !$this->hasArg( $k ) ) {
				$this->error( 'Argument <' . $info['name'] . '> is required!' );
				$valid = false;
			}
		}
		if ( !$this->mAllowUnregisteredOptions ) {
			# Check for unexpected options
			foreach ( $this->mOptions as $opt => $val ) {
				if ( !$this->supportsOption( $opt ) ) {
					$this->error( "Unexpected option --$opt!" );
					$valid = false;
				}
			}
		}

		return $valid;
	}

	/**
	 * Get help text.
	 *
	 * @return string
	 */
	public function getHelp(): string {
		$screenWidth = 80; // TODO: Calculate this!
		$tab = "    ";
		$descWidth = $screenWidth - ( 2 * strlen( $tab ) );

		ksort( $this->mOptDefs );

		$output = [];

		// Description ...
		if ( $this->mDescription ) {
			$output[] = "\n" . wordwrap( $this->mDescription, $screenWidth ) . "\n";
		}
		$output[] = "\nUsage: {$this->usagePrefix} " . basename( $this->mName );

		// ... append options ...
		if ( $this->mOptDefs ) {
			$output[] = ' [OPTION]...';

			foreach ( $this->mOptDefs as $name => $opt ) {
				if ( $opt['require'] ) {
					$output[] = " --$name";

					if ( $opt['withArg'] ) {
						$vname = strtoupper( $name );
						$output[] = " <$vname>";
					}
				}
			}
		}

		// ... and append arguments.
		if ( $this->mArgDefs ) {
			$args = '';
			foreach ( $this->mArgDefs as $arg ) {
				$argRepr = $this->getArgRepresentation( $arg );

				$args .= ' ';
				$args .= $argRepr;
			}
			$output[] = $args;
		}
		$output[] = "\n\n";

		// Go through the declared groups and output the options for each group separately.
		// Maintain the remaining options in $params.
		$params = $this->mOptDefs;
		foreach ( $this->mOptionGroups as $groupName => $groupOptions ) {
			$output[] = $this->formatHelpItems(
				array_intersect_key( $params, array_flip( $groupOptions ) ),
				$groupName,
				$descWidth, $tab
			);
			$params = array_diff_key( $params, array_flip( $groupOptions ) );
		}

		$output[] = $this->formatHelpItems(
			$params,
			'Script specific options',
			$descWidth, $tab
		);

		// Print arguments
		if ( count( $this->mArgDefs ) > 0 ) {
			$output[] = "Arguments:\n";
			// Arguments description
			foreach ( $this->mArgDefs as $info ) {
				$argRepr = $this->getArgRepresentation( $info );
				$output[] =
					wordwrap(
						"$tab$argRepr: " . $info['desc'],
						$descWidth,
						"\n$tab$tab"
					) . "\n";
			}
			$output[] = "\n";
		}

		return implode( '', $output );
	}

	private function formatHelpItems( array $items, $heading, $descWidth, $tab ) {
		if ( $items === [] ) {
			return '';
		}

		$output = [];

		$output[] = "$heading:\n";

		foreach ( $items as $name => $info ) {
			if ( $info['shortName'] !== false ) {
				$name .= ' (-' . $info['shortName'] . ')';
			}
			if ( $info['withArg'] ) {
				$vname = strtoupper( $name );
				$name .= " <$vname>";
			}

			$output[] =
				wordwrap(
					"$tab--$name: " . strtr( $info['desc'], [ "\n" => "\n$tab$tab" ] ),
					$descWidth,
					"\n$tab$tab"
				) . "\n";
		}

		$output[] = "\n";

		return implode( '', $output );
	}

	/**
	 * Returns the names of defined options
	 * @return string[]
	 */
	public function getOptionNames(): array {
		return array_keys( $this->mOptDefs );
	}

	/**
	 * Returns any option values
	 * @return array
	 */
	public function getOptions(): array {
		return $this->mOptions;
	}

	/**
	 * Returns option values as an ordered sequence.
	 * Useful for option chaining (Ex. dumpBackup.php).
	 * @return array[] a list of pairs of like [ $option, $value ]
	 */
	public function getOptionsSequence(): array {
		return $this->optionsSequence;
	}

	/**
	 * @param string $usagePrefix
	 */
	public function setUsagePrefix( string $usagePrefix ) {
		$this->usagePrefix = $usagePrefix;
	}

	/**
	 * @param array $argInfo
	 *
	 * @return string
	 */
	private function getArgRepresentation( array $argInfo ): string {
		if ( $argInfo['require'] ) {
			$rep = '<' . $argInfo['name'] . '>';
		} else {
			$rep = '[' . $argInfo['name'] . ']';
		}

		if ( $argInfo['multi'] ) {
			$rep .= '...';
		}

		return $rep;
	}

}
