<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\OutputTransform\Stages\AddRedirectHeader;
use MediaWiki\OutputTransform\Stages\AddWrapperDivClass;
use MediaWiki\OutputTransform\Stages\DeduplicateStyles;
use MediaWiki\OutputTransform\Stages\DeduplicateStylesDOM;
use MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks;
use MediaWiki\OutputTransform\Stages\ExpandRelativeAttrs;
use MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls;
use MediaWiki\OutputTransform\Stages\ExtractBody;
use MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks;
use MediaWiki\OutputTransform\Stages\HandleSectionLinks;
use MediaWiki\OutputTransform\Stages\HandleTOCMarkersDOM;
use MediaWiki\OutputTransform\Stages\HandleTOCMarkersText;
use MediaWiki\OutputTransform\Stages\HardenNFC;
use MediaWiki\OutputTransform\Stages\HydrateHeaderPlaceholders;
use MediaWiki\OutputTransform\Stages\ParsoidLocalization;
use MediaWiki\OutputTransform\Stages\RenderDebugInfo;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * This class contains the default output transformation pipeline factory for wikitext. It is a postprocessor for
 * ParserOutput objects either directly resulting from a parse or fetched from ParserCache.
 * @unstable
 */
class DefaultOutputPipelineFactory {

	private ServiceOptions $options;
	private Config $config;
	private LoggerInterface $logger;
	private ObjectFactory $objectFactory;

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::OutputPipelineStages,
	];

	private const CORE_LIST = [
		'ExtractBody' => [
			'class' => ExtractBody::class,
			'services' => [
				'UrlUtils',
			],
		],
		'AddRedirectHeader' =>
			AddRedirectHeader::class,

		'RenderDebugInfo' => [
			'class' => RenderDebugInfo::class,
			'services' => [
				'HookContainer',
			],
		],
		'ExecutePostCacheTransformHooks' => [
			'class' => ExecutePostCacheTransformHooks::class,
			'services' => [
				'HookContainer',
			],
		],
		'AddWrapperDivClass' => [
			'class' => AddWrapperDivClass::class,
			'services' => [
				'LanguageFactory',
				'ContentLanguage',
			],
		],
		// The next five stages are all DOM-based passes. They are adjacent to each
		// other to be able to skip unnecessary intermediate DOM->text->DOM transformations.
		'ExpandRelativeAttrs' => [
			'class' => ExpandRelativeAttrs::class,
			'services' => [
				'UrlUtils',
				'ParsoidSiteConfig',
			],
			'optional_services' => [
				'MobileFrontend.Context',
			],
		],
		'HandleSectionLinks' => [
			'textStage' => [
				'class' => HandleSectionLinks::class,
				'services' => [
					'TitleFactory',
				],
			],
			'domStage' => [
				'class' => HandleParsoidSectionLinks::class,
				'services' => [
					'TitleFactory',
				],
			],
			'exclusive' => true
		],
		// This should be before DeduplicateStyles because some system messages may use TemplateStyles (so we
		// want to expand them before deduplication).
		'ParsoidLocalization' => [
			'class' => ParsoidLocalization::class,
			'services' => [
				'TitleFactory',
				'LanguageFactory',
			]
		],
		'HandleTOCMarkers' => [
			'textStage' => [
				'class' => HandleTOCMarkersText::class,
				'services' => [
					'Tidy',
				],
			],
			'domStage' => [
				'class' => HandleTOCMarkersDOM::class
			],
			'exclusive' => false
		],
		'DeduplicateStyles' => [
			'textStage' => [
				'class' => DeduplicateStyles::class,
			],
			'domStage' => [
				'class' => DeduplicateStylesDOM::class,
			],
			'exclusive' => false
		],

		'ExpandToAbsoluteUrls' =>
			ExpandToAbsoluteUrls::class,

		'HydrateHeaderPlaceholders' =>
			HydrateHeaderPlaceholders::class,

		# This should be last, in order to ensure final output is hardened
		'HardenNFC' =>
			HardenNFC::class,
	];

	public function __construct(
		ServiceOptions $options,
		Config $config,
		LoggerInterface $logger,
		ObjectFactory $objectFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->config = $config;
		$this->logger = $logger;
		$this->objectFactory = $objectFactory;
	}

	/**
	 * Creates a pipeline of transformations to transform the content of the ParserOutput object from "parsed HTML"
	 * to "output HTML" and returns it.
	 * @internal
	 * @return OutputTransformPipeline
	 */
	public function buildPipeline(): OutputTransformPipeline {
		// Add extension stages
		$list = array_merge(
			self::CORE_LIST,
			$this->options->get( MainConfigNames::OutputPipelineStages )
		);

		$otp = new OutputTransformPipeline();
		foreach ( $list as $spec ) {
			if ( is_array( $spec ) &&
				array_key_exists( 'domStage', $spec ) &&
				array_key_exists( 'textStage', $spec )
			) {
				$args = [
					$this->objectFactory->createObject( $spec['textStage'],
					[
						'assertClass' => ContentTextTransformStage::class,
						'allowClassName' => true,
					] + $this->makeExtraArgs( $spec['textStage'] ) ),
					$this->objectFactory->createObject( $spec['domStage'],
						[
							'assertClass' => ContentDOMTransformStage::class,
							'allowClassName' => true,
						] + $this->makeExtraArgs( $spec['domStage'] ) ),
					$spec['exclusive'] ?? false
				];
				$spec = [
					'class' => ContentHolderTransformStage::class,
					'args' => $args
				];
			}

			// ObjectFactory::createObject accepts an array, not just a callable (phan bug)
			// @phan-suppress-next-line PhanTypeInvalidCallableArrayKey
			$transform = $this->objectFactory->createObject(
				$spec,
				[
					'assertClass' => OutputTransformStage::class,
					'allowClassName' => true,
				] + $this->makeExtraArgs( $spec )
			);
			$otp->addStage( $transform );
		}
		return $otp;
	}

	/**
	 * Add appropriate ServiceOptions and a logger to the args array.
	 * @param mixed $spec
	 * @return array{extraArgs:array{0:ServiceOptions,1:LoggerInterface}}
	 */
	private function makeExtraArgs( $spec ): array {
		// If the handler is specified as a class, use the CONSTRUCTOR_OPTIONS
		// for that class.
		$class = is_string( $spec ) ? $spec : ( $spec['class'] ?? null );
		$svcOptions = new ServiceOptions(
			$class ? $class::CONSTRUCTOR_OPTIONS : [],
			$this->config
		);
		return [ 'extraArgs' => [ $svcOptions, $this->logger ] ];
	}
}
