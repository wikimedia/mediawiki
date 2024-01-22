<?php

namespace MediaWiki\Output;

use DomainException;
use IContextSource;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Specials\SpecialIframeError;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserFactory;
use RequestContext;
use SkinFactory;
use Wikimedia\Assert\Assert;

/**
 * Generates HTML for an iframe sandbox, within which Javascript can be executed safely, without
 * access to the user's session.
 *
 * Code running inside the sandbox cannot access the user's information stored in MediaWiki
 * (including who the user is), cannot take actions on the user's behalf and cannot store
 * information (such as cookies or localStorage) in the user's browser. It can access standard
 * personal information embedded in web requests (such as the user's IP address) but cannot leak
 * this information to an external website (as long as CSP settings aren't relaxed to allow this).
 *
 * TODO rethink CSP handling. Per https://w3c.github.io/webappsec-csp/#security-inherit-csp
 *   an srcdoc-based iframe cannot relax the parent CSP, only restrict it. But the current interface
 *   only allows relaxing it.
 * TODO provide a JS module that asserts that the sandbox works (document.cookies blocked, null origin)
 * TODO we need to prevent the JS from making anonymous edits which  could be used for vandalism
 *   or for leaking information, but need to allow read API requests.
 * TODO add integration test to ensure CSP is there
 * TODO add browser test
 * TODO test and document performance (how does network state partitioning work in an iframe?)
 * TODO consider disabling MediaWiki hooks that relax CSP restrictions
 *
 * Security assumptions:
 * - The iframe sandbox flag prevents Javascript from any data the browser stores for the site
 *   (such as cookies or localStorage) which in turn prevents making any authenticated requests.
 * - All major browser version that support srcdoc also support sandbox, so the iframe shouldn't
 *   load on browsers which don't prevent site data access:
 *   https://caniuse.com/iframe-sandbox
 *   https://caniuse.com/iframe-srcdoc
 * - Exfiltrating user data (such as IP addresses) using third-party domains is prevented by CSP.
 *
 * @since 1.42
 * @internal This approach is still being verified and not ready for general use.
 */
class IframeSandbox {

	private TitleFactory $titleFactory;

	private RequestContext $iframeContext;
	private OutputPage $iframeOutput;

	/**
	 * Flags to use in the iframe's sandbox attribute. For now this is not exposed for changing.
	 * In the future it might be, but must never include 'allow-same-origin' as that would break
	 * the security assumptions completely.
	 * @var string[]
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe#sandbox
	 */
	private $sandboxFlags = [ 'allow-scripts' ];

	private int $width = 300;
	private int $height = 150;
	private array $iframeClasses = [];

	public function __construct(
		TitleFactory $titleFactory,
		SkinFactory $skinFactory,
		UserFactory $userFactory,
		IContextSource $outerContext
	) {
		$this->titleFactory = $titleFactory;

		// Use an empty request. There are three security goals:
		// - prevent the Session object in the outer request from being accessed
		// - prevent OutputPage writing any headers or cookies (we rely on FauxRequest::response()
		//   returning a FauxResponse)
		// - prevent access to cookies or auth headers (nothing should be using them, but just in case)
		$innerRequest = new FauxRequest(
			// request data, was posted?, session, protocol
			[], false, null, $outerContext->getRequest()->getProtocol()
		);

		$outerTitle = $outerContext->getTitle();
		// Use a fake special page for logging purposes
		$innerTitle = $titleFactory->makeTitle( NS_SPECIAL, 'IframeSandbox/'
			. ( $outerTitle ? $outerTitle->getPrefixedDBkey() : '' ) );

		$configOverride = new HashConfig( [
			MainConfigNames::BreakFrames => false,
		] );
		$iframeConfig = new MultiConfig( [ $configOverride, $outerContext->getConfig() ] );

		$this->iframeContext = new RequestContext();
		$this->iframeContext->setConfig( $iframeConfig );
		$this->iframeContext->setUser( $userFactory->newAnonymous() );
		$this->iframeContext->setLanguage( $outerContext->getLanguage() );
		$this->iframeContext->setRequest( $innerRequest );
		$this->iframeContext->setTitle( $innerTitle );
		$this->iframeContext->setActionName( 'view' );
		// Use ApiSkin as an empty skin
		$this->iframeContext->setSkin( $skinFactory->makeSkin( 'apioutput' ) );

		$outerOutput = $outerContext->getOutput();
		$this->iframeOutput = $this->iframeContext->getOutput();
		$this->iframeOutput->copyContentOverridesFrom( $outerOutput );
		$this->iframeOutput->disallowUserJs();
		$this->iframeOutput->setPreventClickjacking( false );
		$this->iframeOutput->setCspOutputMode( OutputPage::CSP_META );
		// Set the <h1> title to empty, so it gets hidden in SkinApi.
		$this->iframeOutput->setPageTitle( '' );

		// sanity checks
		Assert::postcondition( $this->iframeOutput !== $outerContext->getOutput(),
			'IframeSandbox needs a clean OutputPage' );
		Assert::postcondition( $this->iframeOutput->getRequest()->response() instanceof FauxResponse,
			'IframeSandbox must not use a real WebResponse' );
		Assert::postcondition( $this->iframeOutput->getRlClientContext()->getUserIdentity() === null,
			'IframeSandbox needs a clean UserIdentity' );
	}

	/**
	 * Load one or more ResourceLoader modules in the iframe.
	 *
	 * For embedded modules, addEmbeddedModules() must be used instead.
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 * @see addEmbeddedModules()
	 * @see OutputPage::addModules()
	 */
	public function addModules( $modules ): void {
		$rlContext = $this->iframeOutput->getRlClientContext();
		foreach ( (array)$modules as $moduleName ) {
			$module = $this->iframeOutput->getResourceLoader()->getModule( $moduleName );
			Assert::invariant( $module !== null, "ResourceLoader module $moduleName does not exist" );
			if ( $module->shouldEmbedModule( $rlContext ) ) {
				// Force use of a dedicated method for embedded modules to make sure coders/reviers think about it
				throw new DomainException( "Use addEmbeddedModules() for embedded modules" );
			}
		}
		$this->iframeOutput->addModules( $modules );
	}

	/**
	 * Load one or more embedded ResourceLoader modules in the iframe.
	 *
	 * Embedded modules are generated in a non-anonymous global context; the ResourceLoaderContext
	 * will be anonymous, but e.g. module code using RequestContext::getMain() will have access
	 * to user state. Care should be taken to ensure there are no privacy leaks.
	 *
	 * For non-embedded modules, addModules() must be used instead.
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 * @see addModules()
	 */
	public function addEmbeddedModules( $modules ): void {
		$rlContext = $this->iframeOutput->getRlClientContext();
		foreach ( (array)$modules as $moduleName ) {
			$module = $this->iframeOutput->getResourceLoader()->getModule( $moduleName );
			Assert::invariant( $module !== null, "ResourceLoader module $moduleName does not exist" );
			if ( !$module->shouldEmbedModule( $rlContext ) ) {
				throw new DomainException( "Use addModules() for non-embedded modules" );
			}
		}
		$this->iframeOutput->addModules( $modules );
	}

	/**
	 * Load the styles of one or more style-only ResourceLoader modules in this iframe.
	 *
	 * Module styles added through this function will be loaded as a stylesheet,
	 * using a standard `<link rel=stylesheet>` HTML tag, rather than loaded via Javascript
	 * on DOM ready.
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 * @see OutputPage::addModuleStyles()
	 */
	public function addModuleStyles( $modules ): void {
		$this->iframeOutput->addModuleStyles( $modules );
	}

	/**
	 * Add one or more variables to be set in mw.config in JavaScript
	 *
	 * @param string|array $keys Key or array of key/value pairs
	 * @param mixed|null $value [optional] Value of the configuration variable
	 */
	public function addJsConfigVars( $keys, $value = null ): void {
		$this->iframeOutput->addJsConfigVars( $keys, $value );
	}

	/**
	 * Append some HTML to the iframe's body.
	 * @param string $html
	 */
	public function addHTML( string $html ): void {
		$this->iframeOutput->addHTML( $html );
	}

	/**
	 * Add a default-src URL to the iframe's Content-Security-Policy header.
	 * @param string $source Source to add.
	 *   e.g. blob:, *.example.com, %https://example.com, example.com/foo
	 * @see ContentSecurityPolicy::addDefaultSrc()
	 */
	public function addDefaultSrc( string $source ): void {
		$this->iframeOutput->getCSP()->addDefaultSrc( $source );
	}

	/**
	 * Add a script-src URL to the iframe's Content-Security-Policy header.
	 * @param string $source Source to add.
	 *   e.g. blob:, *.example.com, %https://example.com, example.com/foo
	 * @see ContentSecurityPolicy::addScriptSrc()
	 */
	public function addScriptSrc( string $source ): void {
		$this->iframeOutput->getCSP()->addScriptSrc( $source );
	}

	/**
	 * Add a style-src URL to the iframe's Content-Security-Policy header.
	 * @param string $source Source to add.
	 *   e.g. blob:, *.example.com, %https://example.com, example.com/foo
	 * @see ContentSecurityPolicy::addStyleSrc()
	 */
	public function addStyleSrc( string $source ): void {
		$this->iframeOutput->getCSP()->addStyleSrc( $source );
	}

	/**
	 * Set the iframe's dimensions.
	 * @param int $width
	 * @param int $height
	 */
	public function setDimensions( int $width, int $height ): void {
		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * @param string|array $classes
	 */
	public function addIframeClasses( $classes ): void {
		if ( is_string( $classes ) ) {
			$classes = explode( ' ', $classes );
		}
		$this->iframeClasses = array_merge( $this->iframeClasses, $classes );
	}

	/**
	 * Get the iframe HTML. This includes the enclosing <iframe> tag.
	 */
	public function getHtml(): string {
		// @phan-suppress-next-line SecurityCheck-DoubleEscaped
		return Html::element( 'iframe', [
			'sandbox' => implode( ' ', $this->sandboxFlags ),
			'srcdoc' => $this->iframeOutput->output( true ),
			// Show an error message to browsers which don't support srcdoc.
			'src' => $this->titleFactory->makeTitle( NS_SPECIAL, SpecialIframeError::NAME )
				->getFullURL( 'useskin=apioutput' ),
			'class' => array_merge( [ 'mw-iframe-sandbox' ], $this->iframeClasses ),
			'width' => $this->width,
			'height' => $this->height,
			'role' => 'none',
			'loading' => 'lazy',
			'frameborder' => 0,
		] );
	}

}
