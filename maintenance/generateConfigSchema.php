<?php

use MediaWiki\MainConfigSchema;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Source\ReflectionSchemaSource;
use Symfony\Component\Yaml\Yaml;
use Wikimedia\StaticArrayWriter;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';

// Tell Setup.php to load the config schema from MainConfigSchema rather than
// any generated file, so we can use this script to re-generate a broken schema file.
define( 'MW_USE_CONFIG_SCHEMA_CLASS', 1 );
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that generates configuration schema files:
 * - includes/MainConfigNames.php: name constants for config settings
 * - docs/config-vars.php: dummy variable declarations for config settings
 * - includes/config-schema.php: an optimized config schema for use by Setup.php
 * - docs/config-schema.yaml: a JSON Schema of the config settings
 *
 * @ingroup Maintenance
 */
class GenerateConfigSchema extends Maintenance {

	private const DEFAULT_NAMES_PATH = __DIR__ . '/../includes/MainConfigNames.php';
	private const DEFAULT_VARS_PATH = __DIR__ . '/../docs/config-vars.php';
	private const DEFAULT_ARRAY_PATH = __DIR__ . '/../includes/config-schema.php';
	private const DEFAULT_SCHEMA_PATH = __DIR__ . '/../docs/config-schema.yaml';
	private const STDOUT = 'php://stdout';

	/** @var array */
	private $settingsArray;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Generates various config schema files.' );

		$this->addOption(
			'vars',
			'Path to output variable stubs to. ' .
				'Default if none of the options is given: ' .
				self::DEFAULT_VARS_PATH,
			false,
			true
		);

		$this->addOption(
			'schema',
			'Path to output the schema array to. ' .
				'Default if none of the options is given: ' .
				self::DEFAULT_ARRAY_PATH,
			false,
			true
		);

		$this->addOption(
			'names',
			'Path to output the name constants to. ' .
				'Default if none of the options is given: ' .
				self::DEFAULT_NAMES_PATH,
			false,
			true
		);

		$this->addOption(
			'yaml',
			'Path to output the schema YAML to. ' .
				'Default if none of the options is given: ' .
				self::DEFAULT_SCHEMA_PATH,
			false,
			true
		);
	}

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
	}

	/** @inheritDoc */
	public function getDbType() {
		return self::DB_NONE;
	}

	/**
	 * Loads the config schema from the MainConfigSchema class.
	 *
	 * @return array An associative array with a single key, 'config-schema',
	 *         containing the config schema definition.
	 */
	private function getSettings(): array {
		if ( !$this->settingsArray ) {
			$source = new ReflectionSchemaSource( MainConfigSchema::class, true );
			$this->settingsArray = $source->load();
		}

		return $this->settingsArray;
	}

	/**
	 * @param string $path
	 * @param string $content
	 */
	private function writeOutput( $path, $content ) {
		// ensure a single line break at the end of the file
		$content = trim( $content ) . "\n";

		file_put_contents( $path, $content );
	}

	/**
	 * @param string $name The name of the option
	 *
	 * @return ?string
	 */
	private function getOutputPath( string $name ): ?string {
		$outputPath = $this->getOption( $name );
		if ( $outputPath === '-' ) {
			$outputPath = self::STDOUT;
		}
		return $outputPath;
	}

	public function execute() {
		$settings = $this->getSettings();
		$allSchemas = $settings['config-schema'];
		$obsolete = $settings['obsolete-config'] ?? [];

		$schemaPath = $this->getOutputPath( 'schema' );
		$varsPath = $this->getOutputPath( 'vars' );
		$yamlPath = $this->getOutputPath( 'yaml' );
		$namesPath = $this->getOutputPath( 'names' );

		if ( $schemaPath === null && $varsPath === null &&
			$yamlPath === null && $namesPath === null
		) {
			// If no output path is specified explicitly, use the default path for all.
			$schemaPath = self::DEFAULT_ARRAY_PATH;
			$varsPath = self::DEFAULT_VARS_PATH;
			$yamlPath = self::DEFAULT_SCHEMA_PATH;
			$namesPath = self::DEFAULT_NAMES_PATH;
		}

		if ( $schemaPath === self::STDOUT || $varsPath === self::STDOUT ||
			$yamlPath === self::STDOUT || $namesPath === self::STDOUT
		) {
			// If any of the output is stdout, switch to quiet mode.
			$this->mQuiet = true;
		}

		if ( $schemaPath !== null ) {
			$this->output( "Writing schema array to $schemaPath\n" );
			$this->writeOutput( $schemaPath, $this->generateSchemaArray( $allSchemas, $obsolete ) );
		}

		if ( $varsPath !== null ) {
			$this->output( "Writing variable stubs to $varsPath\n" );
			$this->writeOutput( $varsPath, $this->generateVariableStubs( $allSchemas ) );
		}

		if ( $yamlPath !== null ) {
			$this->output( "Writing schema YAML to $yamlPath\n" );
			$this->writeOutput( $yamlPath, $this->generateSchemaYaml( $allSchemas ) );
		}

		if ( $namesPath !== null ) {
			$this->output( "Writing name constants to $namesPath\n" );
			$this->writeOutput( $namesPath, $this->generateNames( $allSchemas ) );
		}
	}

	public function generateSchemaArray( array $allSchemas, array $obsolete ): string {
		$aggregator = new ConfigSchemaAggregator();
		foreach ( $allSchemas as $key => $schema ) {
			$aggregator->addSchema( $key, $schema );
		}
		$schemaInverse = [
			'default' => $aggregator->getDefaults(),
			'type' => $aggregator->getTypes(),
			'mergeStrategy' => $aggregator->getMergeStrategyNames(),
			'dynamicDefault' => $aggregator->getDynamicDefaults(),
		];

		$keyMask = array_flip( [
			'default',
			'type',
			'mergeStrategy',
			'dynamicDefault',
			'description',
			'properties'
		] );

		$schemaExtra = [];
		foreach ( $aggregator->getDefinedKeys() as $key ) {
			$sch = $aggregator->getSchemaFor( $key );
			$sch = array_diff_key( $sch, $keyMask );

			if ( $sch ) {
				$schemaExtra[ $key ] = $sch;
			}
		}

		$content = ( new StaticArrayWriter() )->write(
			[
				'config-schema-inverse' => $schemaInverse,
				'config-schema' => $schemaExtra,
				'obsolete-config' => $obsolete
			],
			"This file is automatically generated using maintenance/generateConfigSchema.php.\n" .
			"Do not modify this file manually, edit includes/MainConfigSchema.php instead.\n" .
			"phpcs:disable Generic.Files.LineLength"
		);

		return $content;
	}

	public function generateNames( array $allSchemas ): string {
		$code = "<?php\n";
		$code .= "/**\n" .
			" * This file is automatically generated using maintenance/generateConfigSchema.php.\n" .
			" * Do not modify this file manually, edit includes/MainConfigSchema.php instead.\n" .
			" * @file\n" .
			" * @ingroup Config\n" .
			" */\n\n";

		$code .= "// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase\n";
		$code .= "// phpcs:disable Generic.Files.LineLength.TooLong\n";
		$code .= "namespace MediaWiki;\n\n";

		$code .= "/**\n" .
			" * A class containing constants representing the names of configuration variables.\n" .
			" * These constants can be used in calls to Config::get() or with ServiceOptions,\n" .
			" * to protect against typos and to make it easier to discover documentation about\n" .
			" * the respective config setting.\n" .
			" *\n" .
			" * @note this class is generated automatically by maintenance/generateConfigSchema.php\n" .
			" * @since 1.39\n" .
			" */\n";

		$code .= "class MainConfigNames {\n";

		// Details about each config variable
		foreach ( $allSchemas as $configKey => $configSchema ) {
			$code .= "\n";
			$code .= $this->getConstantDeclaration( $configKey, $configSchema );
		}

		$code .= "\n}\n";

		return $code;
	}

	/**
	 * @param string $name
	 * @param array $schema
	 *
	 * @return string
	 */
	private function getConstantDeclaration( string $name, array $schema ): string {
		$chunks = [];

		$chunks[] = "Name constant for the $name setting, for use with Config::get()";
		$chunks[] = "@see MainConfigSchema::$name";

		if ( isset( $schema['since'] ) ) {
			$chunks[] = "@since {$schema['since']}";
		}

		if ( isset( $schema['deprecated'] ) ) {
			$deprecated = str_replace( "\n", "\n\t *    ", wordwrap( $schema['deprecated'] ) );
			$chunks[] = "@deprecated {$deprecated}";
		}

		$code = "\t/**\n\t * ";
		$code .= implode( "\n\t * ", $chunks );
		$code .= "\n\t */\n";

		$code .= "\tpublic const $name = '$name';\n";
		return $code;
	}

	public function generateSchemaYaml( array $allSchemas ): string {
		foreach ( $allSchemas as &$sch ) {
			// Cast empty arrays to objects if they are declared to be of type object.
			// This ensures they get represented in yaml as {} rather than [].
			if ( isset( $sch['default'] ) && isset( $sch['type'] ) ) {
				$types = (array)$sch['type'];
				if ( $sch['default'] === [] && in_array( 'object', $types ) ) {
					$sch['default'] = new stdClass();
				}
			}

			// Wrap long deprecation messages
			if ( isset( $sch['deprecated'] ) ) {
				$sch['deprecated'] = wordwrap( $sch['deprecated'] );
			}
		}

		// Dynamic defaults are not relevant to yaml consumers
		unset( $sch['dynamicDefault'] );

		$yamlFlags = Yaml::DUMP_OBJECT_AS_MAP
			| Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
			| Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE;

		$array = [ 'config-schema' => $allSchemas ];
		$yaml = Yaml::dump( $array, 4, 4, $yamlFlags );

		$header = "# This file is automatically generated using maintenance/generateConfigSchema.php.\n";
		$header .= "# Do not modify this file manually, edit includes/MainConfigSchema.php instead.\n";

		return $header . $yaml;
	}

	public function generateVariableStubs( array $allSchemas ): string {
		$content = "<?php\n";
		$content .= "/**\n" .
			" * This file is automatically generated using maintenance/generateConfigSchema.php.\n" .
			" * Do not modify this file manually, edit includes/MainConfigSchema.php instead.\n" .
			" */\n";

		$content .= "// phpcs:disable\n";
		$content .= "throw new LogicException( 'Do not load config-vars.php, " .
			"it exists as a documentation stub only' );\n";

		foreach ( $allSchemas as $name => $schema ) {
			$content .= "\n";
			$content .= $this->getVariableDeclaration( $name, $schema );
		}

		return $content;
	}

	/**
	 * @param string $name
	 * @param array $schema
	 *
	 * @return string
	 */
	private function getVariableDeclaration( string $name, array $schema ): string {
		$chunks = [];
		$chunks[] = "Config variable stub for the $name setting, for use by phpdoc and IDEs.";
		$chunks[] = "@see MediaWiki\\MainConfigSchema::$name";

		if ( isset( $schema['since'] ) ) {
			$chunks[] = "@since {$schema['since']}";
		}

		if ( isset( $schema['deprecated'] ) ) {
			$deprecated = str_replace( "\n", "\n *    ", wordwrap( $schema['deprecated'] ) );
			$chunks[] = "@deprecated {$deprecated}";
		}

		$code = "/**\n * ";
		$code .= implode( "\n * ", $chunks );
		$code .= "\n */\n";

		$code .= "\$wg{$name} = null;\n";
		return $code;
	}
}

// @codeCoverageIgnoreStart
$maintClass = GenerateConfigSchema::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
