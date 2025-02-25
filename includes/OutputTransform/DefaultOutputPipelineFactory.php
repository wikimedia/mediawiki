<?php

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\OutputTransform\Stages\AddRedirectHeader;
use MediaWiki\OutputTransform\Stages\AddWrapperDivClass;
use MediaWiki\OutputTransform\Stages\DeduplicateStyles;
use MediaWiki\OutputTransform\Stages\ExecutePostCacheTransformHooks;
use MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls;
use MediaWiki\OutputTransform\Stages\ExtractBody;
use MediaWiki\OutputTransform\Stages\HandleParsoidSectionLinks;
use MediaWiki\OutputTransform\Stages\HandleSectionLinks;
use MediaWiki\OutputTransform\Stages\HandleTOCMarkers;
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
			'optional_services' => [
				'MobileFrontend.Context',
			],
		],
		'AddRedirectHeader' => [
			'class' => AddRedirectHeader::class,
		],
		'RenderDebugInfo' => [
			'class' => RenderDebugInfo::class,
			'services' => [
				'HookContainer',
			],
		],
		'ParsoidLocalization' => [
			'class' => ParsoidLocalization::class,
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
		'HandleSectionLinks' => [
			'class' => HandleSectionLinks::class,
			'services' => [
				'TitleFactory',
			],
		],
		'HandleParsoidSectionLinks' => [
			'class' => HandleParsoidSectionLinks::class,
			'services' => [
				'TitleFactory',
			],
		],
		'HandleTOCMarkers' => [
			'class' => HandleTOCMarkers::class,
			'services' => [
				'Tidy',
			],
		],
		'DeduplicateStyles' => [
			'class' => DeduplicateStyles::class,
		],
		'ExpandToAbsoluteUrls' => [
			'class' => ExpandToAbsoluteUrls::class,
		],
		'HydrateHeaderPlaceholders' => [
			'class' => HydrateHeaderPlaceholders::class,
		],
		# This should be last, in order to ensure final output is hardened
		'HardenNFC' => [
			'class' => HardenNFC::class,
		],
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
			$class = $spec['class'];
			$svcOptions = new ServiceOptions(
				$class::CONSTRUCTOR_OPTIONS, $this->config
			);
			$transform = $this->objectFactory->createObject(
				$spec,
				[
					'assertClass' => OutputTransformStage::class,
					'extraArgs' => [ $svcOptions, $this->logger ],
				]
			);
			$otp->addStage( $transform );
		}
		return $otp;
	}
}
