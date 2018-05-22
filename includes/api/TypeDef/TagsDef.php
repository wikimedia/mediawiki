<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ChangeTags;
use IContextSource;
use MediaWiki\Api\TypeDef;

/**
 * API type definition for tags types
 * @since 1.32
 * @ingroup API
 */
class TagsDef extends TypeDef {

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( isset( $settings['values-list'] ) ) {
			$tagsStatus = ChangeTags::canAddTagsAccompanyingChange( $settings['values-list'] );
		} else {
			// The 'tags' type always returns an array.
			$value = [ $value ];
			$tagsStatus = ChangeTags::canAddTagsAccompanyingChange( $value );
		}
		if ( !$tagsStatus->isGood() ) {
			$module->dieStatus( $tagsStatus );
		}
		return $value;
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$tags = ChangeTags::listExplicitlyDefinedTags();
		return [
			$context->msg( 'api-help-param-list' )
				->params( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ? 2 : 1 )
				->params( $context->getLanguage()->commaList( $tags ) )
				->parse(),
		];
	}

	public function getEnumValues( $name, array $settings, ApiBase $module ) {
		return ChangeTags::listExplicitlyDefinedTags();
	}

	public function needsHelpParamMultiSeparate() {
		return false;
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		$info['type'] = ChangeTags::listExplicitlyDefinedTags();

		return $info;
	}

}
