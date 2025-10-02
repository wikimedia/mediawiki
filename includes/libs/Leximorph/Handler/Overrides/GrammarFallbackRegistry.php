<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Handler\Overrides;

use Psr\Log\LoggerInterface;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarCu;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarFi;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarGa;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarHy;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarKaa;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarKk;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarKk_cyrl;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarKsh;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarOs;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarTyv;
use Wikimedia\Leximorph\Traits\SpecBasedFactoryTrait;

/**
 * GrammarFallbackRegistry
 *
 * Manages the registration and retrieval of language-specific grammar
 * transformers, which can be used to apply procedural grammar fallbacks
 * for specific languages.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarFallbackRegistry {
	use SpecBasedFactoryTrait;

	/**
	 * Grammar processor specifications, keyed by language code.
	 *
	 * @var array<string, array{class: class-string<IGrammarTransformer>, args: array<int, mixed>}>
	 */
	private const PROCESSOR_SPECS = [
		'cu' => [
			'class' => GrammarCu::class,
			'args' => [],
		],
		'fi' => [
			'class' => GrammarFi::class,
			'args' => [],
		],
		'ga' => [
			'class' => GrammarGa::class,
			'args' => [],
		],
		'hy' => [
			'class' => GrammarHy::class,
			'args' => [],
		],
		'kaa' => [
			'class' => GrammarKaa::class,
			'args' => [],
		],
		'kk' => [
			'class' => GrammarKk::class,
			'args' => [],
		],
		'kk-cyrl' => [
			'class' => GrammarKk_cyrl::class,
			'args' => [],
		],
		'ksh' => [
			'class' => GrammarKsh::class,
			'args' => [],
		],
		'os' => [
			'class' => GrammarOs::class,
			'args' => [],
		],
		'tyv' => [
			'class' => GrammarTyv::class,
			'args' => [],
		],
	];

	/**
	 * @inheritDoc
	 */
	protected function getSpecMap(): array {
		$map = [];
		foreach ( self::PROCESSOR_SPECS as $spec ) {
			$class = $spec['class'];
			$args = $spec['args'];
			$map[$class] = [ 'args' => $args ];
		}

		return $map;
	}

	/**
	 * @inheritDoc
	 */
	protected function getSpecArgs( array $spec, LoggerInterface $logger ): array {
		return [];
	}

	/**
	 * Retrieves the grammar transformer for the given language, if defined.
	 *
	 * @param string $langCode Language code.
	 *
	 * @since 1.45
	 * @return ?IGrammarTransformer
	 */
	public function getProcessor( string $langCode ): ?IGrammarTransformer {
		$spec = self::PROCESSOR_SPECS[$langCode] ?? null;
		if ( !$spec ) {
			return null;
		}

		return $this->createFromSpec( $spec['class'] );
	}

	/**
	 * Applies a grammar transformation using the transformer for the given language.
	 *
	 * @param string $langCode Language code.
	 * @param string $word Word or phrase to transform.
	 * @param string $case Grammatical case (e.g., 'genitive', 'instrumental').
	 *
	 * @since 1.45
	 * @return string Transformed word if processor exists, otherwise original.
	 */
	public function apply( string $langCode, string $word, string $case ): string {
		$processor = $this->getProcessor( $langCode );

		return $processor ? $processor->process( $word, $case ) : $word;
	}
}
