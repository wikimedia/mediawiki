<?php
/**
 * Copyright © 2008 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Context\RequestContext;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Parser;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\UserFactory;
use MediaWiki\Utils\ExtensionInfo;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @ingroup API
 */
class ApiParamInfo extends ApiBase {

	/** @var string */
	private $helpFormat;

	/** @var RequestContext */
	private $context;

	/** @var UserFactory */
	private $userFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		UserFactory $userFactory
	) {
		parent::__construct( $main, $action );
		$this->userFactory = $userFactory;
	}

	public function execute() {
		// Get parameters
		$params = $this->extractRequestParams();

		$this->helpFormat = $params['helpformat'];
		$this->context = new RequestContext;
		$this->context->setUser( $this->userFactory->newAnonymous() ); // anon to avoid caching issues
		$this->context->setLanguage( $this->getMain()->getLanguage() );

		if ( is_array( $params['modules'] ) ) {
			$modules = [];
			foreach ( $params['modules'] as $path ) {
				if ( $path === '*' || $path === '**' ) {
					$path = "main+$path";
				}
				if ( str_ends_with( $path, '+*' ) || str_ends_with( $path, ' *' ) ) {
					$submodules = true;
					$path = substr( $path, 0, -2 );
					$recursive = false;
				} elseif ( str_ends_with( $path, '+**' ) || str_ends_with( $path, ' **' ) ) {
					$submodules = true;
					$path = substr( $path, 0, -3 );
					$recursive = true;
				} else {
					$submodules = false;
				}

				if ( $submodules ) {
					try {
						$module = $this->getModuleFromPath( $path );
					} catch ( ApiUsageException $ex ) {
						foreach ( $ex->getStatusValue()->getMessages() as $error ) {
							$this->addWarning( $error );
						}
						continue;
					}
					// @phan-suppress-next-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable
					// recursive is set when used
					$submodules = $this->listAllSubmodules( $module, $recursive );
					if ( $submodules ) {
						$modules = array_merge( $modules, $submodules );
					} else {
						$this->addWarning( [ 'apierror-badmodule-nosubmodules', $path ], 'badmodule' );
					}
				} else {
					$modules[] = $path;
				}
			}
		} else {
			$modules = [];
		}

		if ( is_array( $params['querymodules'] ) ) {
			$queryModules = $params['querymodules'];
			foreach ( $queryModules as $m ) {
				$modules[] = 'query+' . $m;
			}
		} else {
			$queryModules = [];
		}

		if ( is_array( $params['formatmodules'] ) ) {
			$formatModules = $params['formatmodules'];
			foreach ( $formatModules as $m ) {
				$modules[] = $m;
			}
		} else {
			$formatModules = [];
		}

		$modules = array_unique( $modules );

		$res = [];

		foreach ( $modules as $m ) {
			try {
				$module = $this->getModuleFromPath( $m );
			} catch ( ApiUsageException $ex ) {
				foreach ( $ex->getStatusValue()->getMessages() as $error ) {
					$this->addWarning( $error );
				}
				continue;
			}
			$key = 'modules';

			// Back compat
			$isBCQuery = false;
			if ( $module->getParent() && $module->getParent()->getModuleName() == 'query' &&
				in_array( $module->getModuleName(), $queryModules )
			) {
				$isBCQuery = true;
				$key = 'querymodules';
			}
			if ( in_array( $module->getModuleName(), $formatModules ) ) {
				$key = 'formatmodules';
			}

			$item = $this->getModuleInfo( $module );
			if ( $isBCQuery ) {
				$item['querytype'] = $item['group'];
			}
			$res[$key][] = $item;
		}

		$result = $this->getResult();
		$result->addValue( [ $this->getModuleName() ], 'helpformat', $this->helpFormat );

		foreach ( $res as $key => $stuff ) {
			ApiResult::setIndexedTagName( $res[$key], 'module' );
		}

		if ( $params['mainmodule'] ) {
			$res['mainmodule'] = $this->getModuleInfo( $this->getMain() );
		}

		if ( $params['pagesetmodule'] ) {
			$pageSet = new ApiPageSet( $this->getMain()->getModuleManager()->getModule( 'query' ) );
			$res['pagesetmodule'] = $this->getModuleInfo( $pageSet );
			unset( $res['pagesetmodule']['name'] );
			unset( $res['pagesetmodule']['path'] );
			unset( $res['pagesetmodule']['group'] );
		}

		$result->addValue( null, $this->getModuleName(), $res );
	}

	/**
	 * List all submodules of a module
	 * @param ApiBase $module
	 * @param bool $recursive
	 * @return string[]
	 */
	private function listAllSubmodules( ApiBase $module, $recursive ) {
		$paths = [];
		$manager = $module->getModuleManager();
		if ( $manager ) {
			$names = $manager->getNames();
			sort( $names );
			foreach ( $names as $name ) {
				$submodule = $manager->getModule( $name );
				$paths[] = $submodule->getModulePath();
				if ( $recursive && $submodule->getModuleManager() ) {
					$paths = array_merge( $paths, $this->listAllSubmodules( $submodule, $recursive ) );
				}
			}
		}
		return $paths;
	}

	/**
	 * @param array &$res Result array
	 * @param string $key Result key
	 * @param Message[] $msgs
	 * @param bool $joinLists
	 */
	protected function formatHelpMessages( array &$res, $key, array $msgs, $joinLists = false ) {
		switch ( $this->helpFormat ) {
			case 'none':
				break;

			case 'wikitext':
				$ret = [];
				foreach ( $msgs as $m ) {
					$ret[] = $m->setContext( $this->context )->text();
				}
				$res[$key] = implode( "\n\n", $ret );
				if ( $joinLists ) {
					$res[$key] = preg_replace( '!^(([*#:;])[^\n]*)\n\n(?=\2)!m', "$1\n", $res[$key] );
				}
				break;

			case 'html':
				$ret = [];
				foreach ( $msgs as $m ) {
					$ret[] = $m->setContext( $this->context )->parseAsBlock();
				}
				$ret = implode( "\n", $ret );
				if ( $joinLists ) {
					$ret = preg_replace( '!\s*</([oud]l)>\s*<\1>\s*!', "\n", $ret );
				}
				$res[$key] = Parser::stripOuterParagraph( $ret );
				break;

			case 'raw':
				$res[$key] = [];
				foreach ( $msgs as $m ) {
					$a = [
						'key' => $m->getKey(),
						'params' => $m->getParams(),
					];
					ApiResult::setIndexedTagName( $a['params'], 'param' );
					if ( $m instanceof ApiHelpParamValueMessage ) {
						$a['forvalue'] = $m->getParamValue();
					}
					$res[$key][] = $a;
				}
				ApiResult::setIndexedTagName( $res[$key], 'msg' );
				break;
		}
	}

	/**
	 * @param ApiBase $module
	 * @return array
	 */
	private function getModuleInfo( $module ) {
		$ret = [];
		$path = $module->getModulePath();
		$paramValidator = $module->getMain()->getParamValidator();

		$ret['name'] = $module->getModuleName();
		$ret['classname'] = get_class( $module );
		$ret['path'] = $path;
		if ( !$module->isMain() ) {
			$ret['group'] = $module->getParent()->getModuleManager()->getModuleGroup(
				$module->getModuleName()
			);
		}
		$ret['prefix'] = $module->getModulePrefix();

		$sourceInfo = $module->getModuleSourceInfo();
		if ( $sourceInfo ) {
			$ret['source'] = $sourceInfo['name'];
			if ( isset( $sourceInfo['namemsg'] ) ) {
				$ret['sourcename'] = $this->context->msg( $sourceInfo['namemsg'] )->text();
			} else {
				$ret['sourcename'] = $ret['source'];
			}

			$link = SpecialPage::getTitleFor( 'Version', 'License/' . $sourceInfo['name'] )->getFullURL();
			if ( isset( $sourceInfo['license-name'] ) ) {
				$ret['licensetag'] = $sourceInfo['license-name'];
				$ret['licenselink'] = (string)$link;
			} elseif ( ExtensionInfo::getLicenseFileNames( dirname( $sourceInfo['path'] ) ) ) {
				$ret['licenselink'] = (string)$link;
			}
		}

		$this->formatHelpMessages( $ret, 'description', $module->getFinalDescription() );

		foreach ( $module->getHelpFlags() as $flag ) {
			$ret[$flag] = true;
		}

		$ret['helpurls'] = (array)$module->getHelpUrls();
		if ( isset( $ret['helpurls'][0] ) && $ret['helpurls'][0] === false ) {
			$ret['helpurls'] = [];
		}
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
		ApiResult::setIndexedTagName( $ret['helpurls'], 'helpurl' );

		if ( $this->helpFormat !== 'none' ) {
			$ret['examples'] = [];
			$examples = $module->getExamplesMessages();
			foreach ( $examples as $qs => $msg ) {
				$item = [
					'query' => $qs
				];
				$msg = $this->msg(
					Message::newFromSpecifier( $msg ),
					$module->getModulePrefix(),
					$module->getModuleName(),
					$module->getModulePath()
				);
				$this->formatHelpMessages( $item, 'description', [ $msg ] );
				if ( isset( $item['description'] ) ) {
					if ( is_array( $item['description'] ) ) {
						$item['description'] = $item['description'][0];
					} else {
						ApiResult::setSubelementsList( $item, 'description' );
					}
				}
				$ret['examples'][] = $item;
			}
			ApiResult::setIndexedTagName( $ret['examples'], 'example' );
		}

		$ret['parameters'] = [];
		$ret['templatedparameters'] = [];
		$params = $module->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );
		$paramDesc = $module->getFinalParamDescription();
		$index = 0;
		foreach ( $params as $name => $settings ) {
			$settings = $paramValidator->normalizeSettings( $settings );

			$item = [
				'index' => ++$index,
				'name' => $name,
			];

			if ( !empty( $settings[ApiBase::PARAM_TEMPLATE_VARS] ) ) {
				$item['templatevars'] = $settings[ApiBase::PARAM_TEMPLATE_VARS];
				ApiResult::setIndexedTagName( $item['templatevars'], 'var' );
			}

			if ( isset( $paramDesc[$name] ) ) {
				$this->formatHelpMessages( $item, 'description', $paramDesc[$name], true );
			}

			foreach ( $paramValidator->getParamInfo( $module, $name, $settings, [] ) as $k => $v ) {
				$item[$k] = $v;
			}

			if ( $name === 'token' && $module->needsToken() ) {
				$item['tokentype'] = $module->needsToken();
			}

			if ( $item['type'] === 'NULL' ) {
				// Munge "NULL" to "string" for historical reasons
				$item['type'] = 'string';
			} elseif ( is_array( $item['type'] ) ) {
				// Set indexed tag name, for historical reasons
				ApiResult::setIndexedTagName( $item['type'], 't' );
			}

			if ( !empty( $settings[ApiBase::PARAM_HELP_MSG_INFO] ) ) {
				$item['info'] = [];
				foreach ( $settings[ApiBase::PARAM_HELP_MSG_INFO] as $i ) {
					$tag = array_shift( $i );
					$info = [
						'name' => $tag,
					];
					if ( count( $i ) ) {
						$info['values'] = $i;
						ApiResult::setIndexedTagName( $info['values'], 'v' );
					}
					$this->formatHelpMessages( $info, 'text', [
						$this->context->msg( "apihelp-{$path}-paraminfo-{$tag}" )
							->numParams( count( $i ) )
							->params( $this->context->getLanguage()->commaList( $i ) )
							->params( $module->getModulePrefix() )
					] );
					ApiResult::setSubelementsList( $info, 'text' );
					$item['info'][] = $info;
				}
				ApiResult::setIndexedTagName( $item['info'], 'i' );
			}

			$key = empty( $settings[ApiBase::PARAM_TEMPLATE_VARS] ) ? 'parameters' : 'templatedparameters';
			$ret[$key][] = $item;
		}
		ApiResult::setIndexedTagName( $ret['parameters'], 'param' );
		ApiResult::setIndexedTagName( $ret['templatedparameters'], 'param' );

		$dynamicParams = $module->dynamicParameterDocumentation();
		if ( $dynamicParams !== null ) {
			if ( $this->helpFormat === 'none' ) {
				$ret['dynamicparameters'] = true;
			} else {
				$dynamicParams = $this->msg(
					Message::newFromSpecifier( $dynamicParams ),
					$module->getModulePrefix(),
					$module->getModuleName(),
					$module->getModulePath()
				);
				$this->formatHelpMessages( $ret, 'dynamicparameters', [ $dynamicParams ] );
			}
		}

		return $ret;
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		// back compat
		$querymodules = $this->getMain()->getModuleManager()
			->getModule( 'query' )->getModuleManager()->getNames();
		sort( $querymodules );
		$formatmodules = $this->getMain()->getModuleManager()->getNames( 'format' );
		sort( $formatmodules );

		return [
			'modules' => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'helpformat' => [
				ParamValidator::PARAM_DEFAULT => 'none',
				ParamValidator::PARAM_TYPE => [ 'html', 'wikitext', 'raw', 'none' ],
			],

			'querymodules' => [
				ParamValidator::PARAM_DEPRECATED => true,
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => $querymodules,
			],
			'mainmodule' => [
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'pagesetmodule' => [
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'formatmodules' => [
				ParamValidator::PARAM_DEPRECATED => true,
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => $formatmodules,
			]
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=paraminfo&modules=parse|phpfm|query%2Ballpages|query%2Bsiteinfo'
				=> 'apihelp-paraminfo-example-1',
			'action=paraminfo&modules=query%2B*'
				=> 'apihelp-paraminfo-example-2',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Parameter_information';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiParamInfo::class, 'ApiParamInfo' );
