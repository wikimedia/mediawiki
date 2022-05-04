<?php

require_once __DIR__ . '/Maintenance.php';
require_once __DIR__ . '/includes/ConfigSchemaDerivativeTrait.php';

/**
 * Maintenance script that generates a PHP class containing constants for all variables
 * defined in the config-schema.yaml file.
 *
 * @ingroup Maintenance
 */
class GenerateConfigNames extends Maintenance {
	use ConfigSchemaDerivativeTrait;

	/** @var string */
	private const DEFAULT_OUTPUT_PATH = 'includes/MainConfigNames.php';

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Generate config name constants in the MainConfigNames class.' );

		$this->addOption(
			'output',
			'Path to output relative to $IP. Default: ' . self::DEFAULT_OUTPUT_PATH,
			false,
			true
		);
	}

	public function execute() {
		$input = $this->loadSettingsSource();
		$code = '';

		// Details about each config variable
		foreach ( $input['config-schema'] as $configKey => $configSchema ) {
			$code .= "\n";
			$code .= $this->getConstantDeclaration( $configKey, $configSchema );
		}

		$newContent =
			$this->processTemplate( MW_INSTALL_PATH . '/includes/MainConfigNames.template', $code );

		$this->writeOutput( self::DEFAULT_OUTPUT_PATH, $newContent );
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
}

$maintClass = GenerateConfigNames::class;
require_once RUN_MAINTENANCE_IF_MAIN;
