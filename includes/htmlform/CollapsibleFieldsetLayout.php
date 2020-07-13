<?php

/*
 * @stable to extend
 */
class CollapsibleFieldsetLayout extends OOUI\FieldsetLayout {
	/*
	 * @stable to call
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		$this->addClasses( [ 'mw-collapsible' ] );
		if ( isset( $config[ 'collapsed' ] ) && $config[ 'collapsed' ] ) {
			$this->addClasses( [ 'mw-collapsed' ] );
		}
		$this->header->addClasses( [ 'mw-collapsible-toggle' ] );
		$this->group->addClasses( [ 'mw-collapsible-content' ] );

		$this->header->appendContent(
			new OOUI\IconWidget( [
				'icon' => 'expand',
				'label' => wfMessage( 'collapsible-expand' )->text(),
			] ),
			new OOUI\IconWidget( [
				'icon' => 'collapse',
				'label' => wfMessage( 'collapsible-collapse' )->text(),
			] )
		);

		$this->header->setAttributes( [
			'role' => 'button',
		] );
	}
}
