<?php

namespace MediaWiki\Api\Validator;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\Util\UploadedFile;

/**
 * ParamValidator callbacks for the Action API
 * @since 1.35
 * @ingroup API
 */
class ApiParamValidatorCallbacks implements Callbacks {

	/** @var ApiMain */
	private $apiMain;

	/**
	 * @internal
	 * @param ApiMain $main
	 */
	public function __construct( ApiMain $main ) {
		$this->apiMain = $main;
	}

	public function hasParam( $name, array $options ) {
		return $this->apiMain->getCheck( $name );
	}

	public function getValue( $name, $default, array $options ) {
		$value = $this->apiMain->getVal( $name, $default );
		$request = $this->apiMain->getRequest();
		$rawValue = $request->getRawVal( $name );

		if ( $options['raw'] ?? false ) {
			// Bypass NFC normalization
			return $rawValue;
		}
		if ( is_string( $rawValue ) ) {
			// Preserve U+001F for multi-values
			if ( str_starts_with( $rawValue, "\x1f" ) ) {
				// This loses the potential checkTitleEncoding() transformation done by
				// WebRequest for $_GET. Let's call that a feature.
				$value = implode( "\x1f", $request->normalizeUnicode( explode( "\x1f", $rawValue ) ) );
			}

			// Check for NFC normalization, and warn
			if ( $rawValue !== $value ) {
				$options['module']->handleParamNormalization( $name, $value, $rawValue );
			}
		}

		return $value;
	}

	public function hasUpload( $name, array $options ) {
		return $this->getUploadedFile( $name, $options ) !== null;
	}

	public function getUploadedFile( $name, array $options ) {
		$upload = $this->apiMain->getUpload( $name );
		if ( !$upload->exists() ) {
			return null;
		}
		return new UploadedFile( [
			'error' => $upload->getError(),
			'tmp_name' => $upload->getTempName(),
			'size' => $upload->getSize(),
			'name' => $upload->getName(),
			'type' => $upload->getType(),
		] );
	}

	public function recordCondition(
		DataMessageValue $message, $name, $value, array $settings, array $options
	) {
		/** @var ApiBase $module */
		$module = $options['module'];

		$code = $message->getCode();
		switch ( $code ) {
			case 'param-deprecated': // @codeCoverageIgnore
			case 'deprecated-value': // @codeCoverageIgnore
				if ( $code === 'param-deprecated' ) {
					$feature = $name;
				} else {
					$feature = $name . '=' . $value;
					$data = $message->getData() ?? [];
					if ( isset( $data['💩'] ) ) {
						// This is from an old-style Message. Strip out ParamValidator's added params.
						unset( $data['💩'] );
						$message = DataMessageValue::new(
							$message->getKey(),
							array_slice( $message->getParams(), 2 ),
							$code,
							$data
						);
					}
				}

				$m = $module;
				while ( !$m->isMain() ) {
					$p = $m->getParent();
					$mName = $m->getModuleName();
					$mParam = $p->encodeParamName( $p->getModuleManager()->getModuleGroup( $mName ) );
					$feature = "{$mParam}={$mName}&{$feature}";
					$m = $p;
				}
				$module->addDeprecation(
					$message,
					$feature,
					$message->getData()
				);
				break;

			case 'param-sensitive': // @codeCoverageIgnore
				$module->getMain()->markParamsSensitive( $name );
				break;

			default:
				$module->addWarning(
					$message,
					$message->getCode(),
					$message->getData()
				);
				break;
		}
	}

	public function useHighLimits( array $options ) {
		return $this->apiMain->canApiHighLimits();
	}

}
