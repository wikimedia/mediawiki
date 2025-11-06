<?php

namespace MediaWiki\Installer;

/**
 * @internal
 */
abstract class DatabaseForm {
	protected WebInstaller $webInstaller;
	protected DatabaseInstaller $dbInstaller;

	public function __construct( WebInstaller $webInstaller, DatabaseInstaller $dbInstaller ) {
		$this->webInstaller = $webInstaller;
		$this->dbInstaller = $dbInstaller;
	}

	/**
	 * Get a variable, taking local defaults into account.
	 * @param string $var
	 * @param mixed|null $default
	 * @return mixed
	 */
	protected function getVar( $var, $default = null ) {
		return $this->dbInstaller->getVar( $var, $default );
	}

	/**
	 * Set a variable
	 * @param string $name
	 * @param mixed $value
	 */
	protected function setVar( $name, $value ) {
		$this->dbInstaller->setVar( $name, $value );
	}

	/**
	 * Return the internal name, e.g. 'mysql', or 'sqlite'.
	 * @return string
	 */
	protected function getName() {
		return $this->dbInstaller->getName();
	}

	/**
	 * Get a labelled text box to configure a local variable.
	 *
	 * @param string $var
	 * @param string $label
	 * @param array $attribs
	 * @param string $helpData HTML
	 * @return string HTML
	 * @return-taint escaped
	 */
	protected function getTextBox( $var, $label, $attribs = [], $helpData = "" ) {
		$name = $this->getName() . '_' . $var;
		$value = $this->getVar( $var );
		if ( $attribs === null ) {
			$attribs = [];
		}

		return $this->webInstaller->getTextBox( [
			'var' => $var,
			'label' => $label,
			'attribs' => $attribs,
			'controlName' => $name,
			'value' => $value,
			'help' => $helpData
		] );
	}

	/**
	 * Get a labelled password box to configure a local variable.
	 * Implements password hiding.
	 *
	 * @param string $var
	 * @param string $label
	 * @param array $attribs
	 * @param string $helpData HTML
	 * @return string HTML
	 * @return-taint escaped
	 */
	protected function getPasswordBox( $var, $label, $attribs = [], $helpData = "" ) {
		$name = $this->getName() . '_' . $var;
		$value = $this->getVar( $var );
		if ( $attribs === null ) {
			$attribs = [];
		}

		return $this->webInstaller->getPasswordBox( [
			'var' => $var,
			'label' => $label,
			'attribs' => $attribs,
			'controlName' => $name,
			'value' => $value,
			'help' => $helpData
		] );
	}

	/**
	 * Get a labelled checkbox to configure a local boolean variable.
	 *
	 * @param string $var
	 * @param string $label
	 * @param array $attribs Optional.
	 * @param string $helpData Optional.
	 * @return string
	 */
	protected function getCheckBox( $var, $label, $attribs = [], $helpData = "" ) {
		$name = $this->getName() . '_' . $var;
		$value = $this->getVar( $var );

		return $this->webInstaller->getCheckBox( [
			'var' => $var,
			'label' => $label,
			'attribs' => $attribs,
			'controlName' => $name,
			'value' => $value,
			'help' => $helpData
		] );
	}

	/**
	 * Get a set of labelled radio buttons.
	 *
	 * @param array $params Parameters are:
	 *      var:            The variable to be configured (required)
	 *      label:          The message name for the label (required)
	 *      itemLabelPrefix: The message name prefix for the item labels (required)
	 *      values:         List of allowed values (required)
	 *      itemAttribs     Array of attribute arrays, outer key is the value name (optional)
	 *
	 * @return string
	 */
	protected function getRadioSet( $params ) {
		$params['controlName'] = $this->getName() . '_' . $params['var'];
		$params['value'] = $this->getVar( $params['var'] );

		return $this->webInstaller->getRadioSet( $params );
	}

	/**
	 * Convenience function to set variables based on form data.
	 * Assumes that variables containing "password" in the name are (potentially
	 * fake) passwords.
	 * @param array $varNames
	 * @return array
	 */
	protected function setVarsFromRequest( $varNames ) {
		return $this->webInstaller->setVarsFromRequest( $varNames, $this->getName() . '_' );
	}

}
