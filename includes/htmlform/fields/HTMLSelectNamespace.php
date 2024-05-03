<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;

/**
 * Wrapper for Html::namespaceSelector to use in HTMLForm
 *
 * @stable to extend
 */
class HTMLSelectNamespace extends HTMLFormField {

	/** @var string|null */
	protected $mAllValue;
	/** @var bool */
	protected $mUserLang;

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->mAllValue = array_key_exists( 'all', $params )
			? $params['all']
			: 'all';
		$this->mUserLang = array_key_exists( 'in-user-lang', $params )
			? $params['in-user-lang']
			: false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputHTML( $value ) {
		return Html::namespaceSelector(
			[
				'selected' => $value,
				'all' => $this->mAllValue,
				'in-user-lang' => $this->mUserLang,
			], [
				'name' => $this->mName,
				'id' => $this->mID,
				'class' => 'namespaceselector',
			]
		);
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputOOUI( $value ) {
		return new \MediaWiki\Widget\NamespaceInputWidget( [
			'value' => $value,
			'name' => $this->mName,
			'id' => $this->mID,
			'includeAllValue' => $this->mAllValue,
			'userLang' => $this->mUserLang,
		] );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputCodex( $value, $hasErrors ) {
		$optionParams = [
			'all' => $this->mAllValue,
			'in-user-lang' => $this->mUserLang
		];
		$select = new HTMLSelectField( [
			'options' => array_flip( Html::namespaceSelectorOptions( $optionParams ) )
		] + $this->mParams );
		return $select->getInputCodex( $value, $hasErrors );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function getOOUIModules() {
		// FIXME: NamespaceInputWidget should be in its own module (probably?)
		return [ 'mediawiki.widgets' ];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSelectNamespace::class, 'HTMLSelectNamespace' );
