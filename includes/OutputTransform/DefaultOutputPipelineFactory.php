<?php

namespace MediaWiki\OutputTransform;

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

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParserEnableLegacyHeadingDOM, // For HandleSectionLinks
	];

	private ServiceOptions $options;
	private LoggerInterface $logger;
	private ObjectFactory $objectFactory;

	private const CORE_LIST = [
		'ExtractBody' => [
			'class' => ExtractBody::class,
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
	];

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ObjectFactory $objectFactory
	) {
		$this->options = $options;
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
		$otp = new OutputTransformPipeline();
		foreach ( self::CORE_LIST as $spec ) {
			// @phan-suppress-next-line PhanTypeInvalidCallableArraySize
			$transform = $this->objectFactory->createObject(
				$spec,
				[
					'assertClass' => OutputTransformStage::class,
					'extraArgs' => [ $this->options, $this->logger ],
				]
			);
			$otp->addStage( $transform );
		}
		return $otp;
	}
}
