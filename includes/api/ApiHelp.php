<?php
/**
 *
 *
 * Created on Aug 29, 2014
 *
 * Copyright © 2014 Brad Jorsch <bjorsch@wikimedia.org>
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

use HtmlFormatter\HtmlFormatter;

/**
 * Class to output help for an API module
 *
 * @since 1.25 completely rewritten
 * @ingroup API
 */
class ApiHelp extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		$modules = [];

		foreach ( $params['modules'] as $path ) {
			$modules[] = $this->getModuleFromPath( $path );
		}

		// Get the help
		$context = new DerivativeContext( $this->getMain()->getContext() );
		$context->setSkin( SkinFactory::getDefaultInstance()->makeSkin( 'apioutput' ) );
		$context->setLanguage( $this->getMain()->getLanguage() );
		$context->setTitle( SpecialPage::getTitleFor( 'ApiHelp' ) );
		$out = new OutputPage( $context );
		$out->setCopyrightUrl( 'https://www.mediawiki.org/wiki/Special:MyLanguage/Copyright' );
		$context->setOutput( $out );

		self::getHelp( $context, $modules, $params );

		// Grab the output from the skin
		ob_start();
		$context->getOutput()->output();
		$html = ob_get_clean();

		$result = $this->getResult();
		if ( $params['wrap'] ) {
			$data = [
				'mime' => 'text/html',
				'help' => $html,
			];
			ApiResult::setSubelementsList( $data, 'help' );
			$result->addValue( null, $this->getModuleName(), $data );
		} else {
			$result->reset();
			$result->addValue( null, 'text', $html, ApiResult::NO_SIZE_CHECK );
			$result->addValue( null, 'mime', 'text/html', ApiResult::NO_SIZE_CHECK );
		}
	}

	/**
	 * Generate help for the specified modules
	 *
	 * Help is placed into the OutputPage object returned by
	 * $context->getOutput().
	 *
	 * Recognized options include:
	 *  - headerlevel: (int) Header tag level
	 *  - nolead: (bool) Skip the inclusion of api-help-lead
	 *  - noheader: (bool) Skip the inclusion of the top-level section headers
	 *  - submodules: (bool) Include help for submodules of the current module
	 *  - recursivesubmodules: (bool) Include help for submodules recursively
	 *  - helptitle: (string) Title to link for additional modules' help. Should contain $1.
	 *  - toc: (bool) Include a table of contents
	 *
	 * @param IContextSource $context
	 * @param ApiBase[]|ApiBase $modules
	 * @param array $options Formatting options (described above)
	 * @return string
	 */
	public static function getHelp( IContextSource $context, $modules, array $options ) {
		global $wgContLang;

		if ( !is_array( $modules ) ) {
			$modules = [ $modules ];
		}

		$out = $context->getOutput();
		$out->addModuleStyles( 'mediawiki.hlist' );
		$out->addModuleStyles( 'mediawiki.apihelp' );
		if ( !empty( $options['toc'] ) ) {
			$out->addModules( 'mediawiki.toc' );
		}
		$out->setPageTitle( $context->msg( 'api-help-title' ) );

		$cache = ObjectCache::getMainWANInstance();
		$cacheKey = null;
		if ( count( $modules ) == 1 && $modules[0] instanceof ApiMain &&
			$options['recursivesubmodules'] && $context->getLanguage() === $wgContLang
		) {
			$cacheHelpTimeout = $context->getConfig()->get( 'APICacheHelpTimeout' );
			if ( $cacheHelpTimeout > 0 ) {
				// Get help text from cache if present
				$cacheKey = wfMemcKey( 'apihelp', $modules[0]->getModulePath(),
					(int)!empty( $options['toc'] ),
					str_replace( ' ', '_', SpecialVersion::getVersion( 'nodb' ) ) );
				$cached = $cache->get( $cacheKey );
				if ( $cached ) {
					$out->addHTML( $cached );
					return;
				}
			}
		}
		if ( $out->getHTML() !== '' ) {
			// Don't save to cache, there's someone else's content in the page
			// already
			$cacheKey = null;
		}

		$options['recursivesubmodules'] = !empty( $options['recursivesubmodules'] );
		$options['submodules'] = $options['recursivesubmodules'] || !empty( $options['submodules'] );

		// Prepend lead
		if ( empty( $options['nolead'] ) ) {
			$msg = $context->msg( 'api-help-lead' );
			if ( !$msg->isDisabled() ) {
				$out->addHTML( $msg->parseAsBlock() );
			}
		}

		$haveModules = [];
		$html = self::getHelpInternal( $context, $modules, $options, $haveModules );
		if ( !empty( $options['toc'] ) && $haveModules ) {
			$out->addHTML( Linker::generateTOC( $haveModules, $context->getLanguage() ) );
		}
		$out->addHTML( $html );

		$helptitle = isset( $options['helptitle'] ) ? $options['helptitle'] : null;
		$html = self::fixHelpLinks( $out->getHTML(), $helptitle, $haveModules );
		$out->clearHTML();
		$out->addHTML( $html );

		if ( $cacheKey !== null ) {
			$cache->set( $cacheKey, $out->getHTML(), $cacheHelpTimeout );
		}
	}

	/**
	 * Replace Special:ApiHelp links with links to api.php
	 *
	 * @param string $html
	 * @param string|null $helptitle Title to link to rather than api.php, must contain '$1'
	 * @param array $localModules Keys are modules to link within the current page, values are ignored
	 * @return string
	 */
	public static function fixHelpLinks( $html, $helptitle = null, $localModules = [] ) {
		$formatter = new HtmlFormatter( $html );
		$doc = $formatter->getDoc();
		$xpath = new DOMXPath( $doc );
		$nodes = $xpath->query( '//a[@href][not(contains(@class,\'apihelp-linktrail\'))]' );
		foreach ( $nodes as $node ) {
			$href = $node->getAttribute( 'href' );
			do {
				$old = $href;
				$href = rawurldecode( $href );
			} while ( $old !== $href );
			if ( preg_match( '!Special:ApiHelp/([^&/|#]+)((?:#.*)?)!', $href, $m ) ) {
				if ( isset( $localModules[$m[1]] ) ) {
					$href = $m[2] === '' ? '#' . $m[1] : $m[2];
				} elseif ( $helptitle !== null ) {
					$href = Title::newFromText( str_replace( '$1', $m[1], $helptitle ) . $m[2] )
						->getFullURL();
				} else {
					$href = wfAppendQuery( wfScript( 'api' ), [
						'action' => 'help',
						'modules' => $m[1],
					] ) . $m[2];
				}
				$node->setAttribute( 'href', $href );
				$node->removeAttribute( 'title' );
			}
		}

		return $formatter->getText();
	}

	/**
	 * Wrap a message in HTML with a class.
	 *
	 * @param Message $msg
	 * @param string $class
	 * @param string $tag
	 * @return string
	 */
	private static function wrap( Message $msg, $class, $tag = 'span' ) {
		return Html::rawElement( $tag, [ 'class' => $class ],
			$msg->parse()
		);
	}

	/**
	 * Recursively-called function to actually construct the help
	 *
	 * @param IContextSource $context
	 * @param ApiBase[] $modules
	 * @param array $options
	 * @param array &$haveModules
	 * @return string
	 */
	private static function getHelpInternal( IContextSource $context, array $modules,
		array $options, &$haveModules
	) {
		$out = '';

		$level = empty( $options['headerlevel'] ) ? 2 : $options['headerlevel'];
		if ( empty( $options['tocnumber'] ) ) {
			$tocnumber = [ 2 => 0 ];
		} else {
			$tocnumber = &$options['tocnumber'];
		}

		foreach ( $modules as $module ) {
			$tocnumber[$level]++;
			$path = $module->getModulePath();
			$module->setContext( $context );
			$help = [
				'header' => '',
				'flags' => '',
				'description' => '',
				'help-urls' => '',
				'parameters' => '',
				'examples' => '',
				'submodules' => '',
			];

			if ( empty( $options['noheader'] ) || !empty( $options['toc'] ) ) {
				$anchor = $path;
				$i = 1;
				while ( isset( $haveModules[$anchor] ) ) {
					$anchor = $path . '|' . ++$i;
				}

				if ( $module->isMain() ) {
					$headerContent = $context->msg( 'api-help-main-header' )->parse();
					$headerAttr = [
						'class' => 'apihelp-header',
					];
				} else {
					$name = $module->getModuleName();
					$headerContent = $module->getParent()->getModuleManager()->getModuleGroup( $name ) .
						"=$name";
					if ( $module->getModulePrefix() !== '' ) {
						$headerContent .= ' ' .
							$context->msg( 'parentheses', $module->getModulePrefix() )->parse();
					}
					// Module names are always in English and not localized,
					// so English language and direction must be set explicitly,
					// otherwise parentheses will get broken in RTL wikis
					$headerAttr = [
						'class' => 'apihelp-header apihelp-module-name',
						'dir' => 'ltr',
						'lang' => 'en',
					];
				}

				$headerAttr['id'] = $anchor;

				$haveModules[$anchor] = [
					'toclevel' => count( $tocnumber ),
					'level' => $level,
					'anchor' => $anchor,
					'line' => $headerContent,
					'number' => implode( '.', $tocnumber ),
					'index' => false,
				];
				if ( empty( $options['noheader'] ) ) {
					$help['header'] .= Html::element(
						'h' . min( 6, $level ),
						$headerAttr,
						$headerContent
					);
				}
			} else {
				$haveModules[$path] = true;
			}

			$links = [];
			$any = false;
			for ( $m = $module; $m !== null; $m = $m->getParent() ) {
				$name = $m->getModuleName();
				if ( $name === 'main_int' ) {
					$name = 'main';
				}

				if ( count( $modules ) === 1 && $m === $modules[0] &&
					!( !empty( $options['submodules'] ) && $m->getModuleManager() )
				) {
					$link = Html::element( 'b', null, $name );
				} else {
					$link = SpecialPage::getTitleFor( 'ApiHelp', $m->getModulePath() )->getLocalURL();
					$link = Html::element( 'a',
						[ 'href' => $link, 'class' => 'apihelp-linktrail' ],
						$name
					);
					$any = true;
				}
				array_unshift( $links, $link );
			}
			if ( $any ) {
				$help['header'] .= self::wrap(
					$context->msg( 'parentheses' )
						->rawParams( $context->getLanguage()->pipeList( $links ) ),
					'apihelp-linktrail', 'div'
				);
			}

			$flags = $module->getHelpFlags();
			$help['flags'] .= Html::openElement( 'div',
				[ 'class' => 'apihelp-block apihelp-flags' ] );
			$msg = $context->msg( 'api-help-flags' );
			if ( !$msg->isDisabled() ) {
				$help['flags'] .= self::wrap(
					$msg->numParams( count( $flags ) ), 'apihelp-block-head', 'div'
				);
			}
			$help['flags'] .= Html::openElement( 'ul' );
			foreach ( $flags as $flag ) {
				$help['flags'] .= Html::rawElement( 'li', null,
					self::wrap( $context->msg( "api-help-flag-$flag" ), "apihelp-flag-$flag" )
				);
			}
			$sourceInfo = $module->getModuleSourceInfo();
			if ( $sourceInfo ) {
				if ( isset( $sourceInfo['namemsg'] ) ) {
					$extname = $context->msg( $sourceInfo['namemsg'] )->text();
				} else {
					$extname = $sourceInfo['name'];
				}
				$help['flags'] .= Html::rawElement( 'li', null,
					self::wrap(
						$context->msg( 'api-help-source', $extname, $sourceInfo['name'] ),
						'apihelp-source'
					)
				);

				$link = SpecialPage::getTitleFor( 'Version', 'License/' . $sourceInfo['name'] );
				if ( isset( $sourceInfo['license-name'] ) ) {
					$msg = $context->msg( 'api-help-license', $link, $sourceInfo['license-name'] );
				} elseif ( SpecialVersion::getExtLicenseFileName( dirname( $sourceInfo['path'] ) ) ) {
					$msg = $context->msg( 'api-help-license-noname', $link );
				} else {
					$msg = $context->msg( 'api-help-license-unknown' );
				}
				$help['flags'] .= Html::rawElement( 'li', null,
					self::wrap( $msg, 'apihelp-license' )
				);
			} else {
				$help['flags'] .= Html::rawElement( 'li', null,
					self::wrap( $context->msg( 'api-help-source-unknown' ), 'apihelp-source' )
				);
				$help['flags'] .= Html::rawElement( 'li', null,
					self::wrap( $context->msg( 'api-help-license-unknown' ), 'apihelp-license' )
				);
			}
			$help['flags'] .= Html::closeElement( 'ul' );
			$help['flags'] .= Html::closeElement( 'div' );

			foreach ( $module->getFinalDescription() as $msg ) {
				$msg->setContext( $context );
				$help['description'] .= $msg->parseAsBlock();
			}

			$urls = $module->getHelpUrls();
			if ( $urls ) {
				$help['help-urls'] .= Html::openElement( 'div',
					[ 'class' => 'apihelp-block apihelp-help-urls' ]
				);
				$msg = $context->msg( 'api-help-help-urls' );
				if ( !$msg->isDisabled() ) {
					$help['help-urls'] .= self::wrap(
						$msg->numParams( count( $urls ) ), 'apihelp-block-head', 'div'
					);
				}
				if ( !is_array( $urls ) ) {
					$urls = [ $urls ];
				}
				$help['help-urls'] .= Html::openElement( 'ul' );
				foreach ( $urls as $url ) {
					$help['help-urls'] .= Html::rawElement( 'li', null,
						Html::element( 'a', [ 'href' => $url ], $url )
					);
				}
				$help['help-urls'] .= Html::closeElement( 'ul' );
				$help['help-urls'] .= Html::closeElement( 'div' );
			}

			$params = $module->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );
			$dynamicParams = $module->dynamicParameterDocumentation();
			$groups = [];
			if ( $params || $dynamicParams !== null ) {
				$help['parameters'] .= Html::openElement( 'div',
					[ 'class' => 'apihelp-block apihelp-parameters' ]
				);
				$msg = $context->msg( 'api-help-parameters' );
				if ( !$msg->isDisabled() ) {
					$help['parameters'] .= self::wrap(
						$msg->numParams( count( $params ) ), 'apihelp-block-head', 'div'
					);
				}
				$help['parameters'] .= Html::openElement( 'dl' );

				$descriptions = $module->getFinalParamDescription();

				foreach ( $params as $name => $settings ) {
					if ( !is_array( $settings ) ) {
						$settings = [ ApiBase::PARAM_DFLT => $settings ];
					}

					$help['parameters'] .= Html::element( 'dt', null,
						$module->encodeParamName( $name ) );

					// Add description
					$description = [];
					if ( isset( $descriptions[$name] ) ) {
						foreach ( $descriptions[$name] as $msg ) {
							$msg->setContext( $context );
							$description[] = $msg->parseAsBlock();
						}
					}

					// Add usage info
					$info = [];

					// Required?
					if ( !empty( $settings[ApiBase::PARAM_REQUIRED] ) ) {
						$info[] = $context->msg( 'api-help-param-required' )->parse();
					}

					// Custom info?
					if ( !empty( $settings[ApiBase::PARAM_HELP_MSG_INFO] ) ) {
						foreach ( $settings[ApiBase::PARAM_HELP_MSG_INFO] as $i ) {
							$tag = array_shift( $i );
							$info[] = $context->msg( "apihelp-{$path}-paraminfo-{$tag}" )
								->numParams( count( $i ) )
								->params( $context->getLanguage()->commaList( $i ) )
								->params( $module->getModulePrefix() )
								->parse();
						}
					}

					// Type documentation
					if ( !isset( $settings[ApiBase::PARAM_TYPE] ) ) {
						$dflt = isset( $settings[ApiBase::PARAM_DFLT] )
							? $settings[ApiBase::PARAM_DFLT]
							: null;
						if ( is_bool( $dflt ) ) {
							$settings[ApiBase::PARAM_TYPE] = 'boolean';
						} elseif ( is_string( $dflt ) || is_null( $dflt ) ) {
							$settings[ApiBase::PARAM_TYPE] = 'string';
						} elseif ( is_int( $dflt ) ) {
							$settings[ApiBase::PARAM_TYPE] = 'integer';
						}
					}
					if ( isset( $settings[ApiBase::PARAM_TYPE] ) ) {
						$type = $settings[ApiBase::PARAM_TYPE];
						$multi = !empty( $settings[ApiBase::PARAM_ISMULTI] );
						$hintPipeSeparated = true;
						$count = ApiBase::LIMIT_SML2 + 1;

						if ( is_array( $type ) ) {
							$count = count( $type );
							$links = isset( $settings[ApiBase::PARAM_VALUE_LINKS] )
								? $settings[ApiBase::PARAM_VALUE_LINKS]
								: [];
							$type = array_map( function ( $v ) use ( $links ) {
								$ret = wfEscapeWikiText( $v );
								if ( isset( $links[$v] ) ) {
									$ret = "[[{$links[$v]}|$ret]]";
								}
								return $ret;
							}, $type );
							$i = array_search( '', $type, true );
							if ( $i === false ) {
								$type = $context->getLanguage()->commaList( $type );
							} else {
								unset( $type[$i] );
								$type = $context->msg( 'api-help-param-list-can-be-empty' )
									->numParams( count( $type ) )
									->params( $context->getLanguage()->commaList( $type ) )
									->parse();
							}
							$info[] = $context->msg( 'api-help-param-list' )
								->params( $multi ? 2 : 1 )
								->params( $type )
								->parse();
							$hintPipeSeparated = false;
						} else {
							switch ( $type ) {
								case 'submodule':
									$groups[] = $name;
									if ( isset( $settings[ApiBase::PARAM_SUBMODULE_MAP] ) ) {
										$map = $settings[ApiBase::PARAM_SUBMODULE_MAP];
										ksort( $map );
										$submodules = [];
										foreach ( $map as $v => $m ) {
											$submodules[] = "[[Special:ApiHelp/{$m}|{$v}]]";
										}
									} else {
										$submodules = $module->getModuleManager()->getNames( $name );
										sort( $submodules );
										$prefix = $module->isMain()
											? '' : ( $module->getModulePath() . '+' );
										$submodules = array_map( function ( $name ) use ( $prefix ) {
											return "[[Special:ApiHelp/{$prefix}{$name}|{$name}]]";
										}, $submodules );
									}
									$count = count( $submodules );
									$info[] = $context->msg( 'api-help-param-list' )
										->params( $multi ? 2 : 1 )
										->params( $context->getLanguage()->commaList( $submodules ) )
										->parse();
									$hintPipeSeparated = false;
									// No type message necessary, we have a list of values.
									$type = null;
									break;

								case 'namespace':
									$namespaces = MWNamespace::getValidNamespaces();
									$count = count( $namespaces );
									$info[] = $context->msg( 'api-help-param-list' )
										->params( $multi ? 2 : 1 )
										->params( $context->getLanguage()->commaList( $namespaces ) )
										->parse();
									$hintPipeSeparated = false;
									// No type message necessary, we have a list of values.
									$type = null;
									break;

								case 'tags':
									$tags = ChangeTags::listExplicitlyDefinedTags();
									$count = count( $tags );
									$info[] = $context->msg( 'api-help-param-list' )
										->params( $multi ? 2 : 1 )
										->params( $context->getLanguage()->commaList( $tags ) )
										->parse();
									$hintPipeSeparated = false;
									$type = null;
									break;

								case 'limit':
									if ( isset( $settings[ApiBase::PARAM_MAX2] ) ) {
										$info[] = $context->msg( 'api-help-param-limit2' )
											->numParams( $settings[ApiBase::PARAM_MAX] )
											->numParams( $settings[ApiBase::PARAM_MAX2] )
											->parse();
									} else {
										$info[] = $context->msg( 'api-help-param-limit' )
											->numParams( $settings[ApiBase::PARAM_MAX] )
											->parse();
									}
									break;

								case 'integer':
									// Possible messages:
									// api-help-param-integer-min,
									// api-help-param-integer-max,
									// api-help-param-integer-minmax
									$suffix = '';
									$min = $max = 0;
									if ( isset( $settings[ApiBase::PARAM_MIN] ) ) {
										$suffix .= 'min';
										$min = $settings[ApiBase::PARAM_MIN];
									}
									if ( isset( $settings[ApiBase::PARAM_MAX] ) ) {
										$suffix .= 'max';
										$max = $settings[ApiBase::PARAM_MAX];
									}
									if ( $suffix !== '' ) {
										$info[] =
											$context->msg( "api-help-param-integer-$suffix" )
												->params( $multi ? 2 : 1 )
												->numParams( $min, $max )
												->parse();
									}
									break;

								case 'upload':
									$info[] = $context->msg( 'api-help-param-upload' )
										->parse();
									// No type message necessary, api-help-param-upload should handle it.
									$type = null;
									break;

								case 'string':
								case 'text':
									// Displaying a type message here would be useless.
									$type = null;
									break;
							}
						}

						// Add type. Messages for grep: api-help-param-type-limit
						// api-help-param-type-integer api-help-param-type-boolean
						// api-help-param-type-timestamp api-help-param-type-user
						// api-help-param-type-password
						if ( is_string( $type ) ) {
							$msg = $context->msg( "api-help-param-type-$type" );
							if ( !$msg->isDisabled() ) {
								$info[] = $msg->params( $multi ? 2 : 1 )->parse();
							}
						}

						if ( $multi ) {
							$extra = [];
							if ( $hintPipeSeparated ) {
								$extra[] = $context->msg( 'api-help-param-multi-separate' )->parse();
							}
							if ( $count > ApiBase::LIMIT_SML1 ) {
								$extra[] = $context->msg( 'api-help-param-multi-max' )
									->numParams( ApiBase::LIMIT_SML1, ApiBase::LIMIT_SML2 )
									->parse();
							}
							if ( $extra ) {
								$info[] = implode( ' ', $extra );
							}
						}
					}

					// Add default
					$default = isset( $settings[ApiBase::PARAM_DFLT] )
						? $settings[ApiBase::PARAM_DFLT]
						: null;
					if ( $default === '' ) {
						$info[] = $context->msg( 'api-help-param-default-empty' )
							->parse();
					} elseif ( $default !== null && $default !== false ) {
						$info[] = $context->msg( 'api-help-param-default' )
							->params( wfEscapeWikiText( $default ) )
							->parse();
					}

					if ( !array_filter( $description ) ) {
						$description = [ self::wrap(
							$context->msg( 'api-help-param-no-description' ),
							'apihelp-empty'
						) ];
					}

					// Add "deprecated" flag
					if ( !empty( $settings[ApiBase::PARAM_DEPRECATED] ) ) {
						$help['parameters'] .= Html::openElement( 'dd',
							[ 'class' => 'info' ] );
						$help['parameters'] .= self::wrap(
							$context->msg( 'api-help-param-deprecated' ),
							'apihelp-deprecated', 'strong'
						);
						$help['parameters'] .= Html::closeElement( 'dd' );
					}

					if ( $description ) {
						$description = implode( '', $description );
						$description = preg_replace( '!\s*</([oud]l)>\s*<\1>\s*!', "\n", $description );
						$help['parameters'] .= Html::rawElement( 'dd',
							[ 'class' => 'description' ], $description );
					}

					foreach ( $info as $i ) {
						$help['parameters'] .= Html::rawElement( 'dd', [ 'class' => 'info' ], $i );
					}
				}

				if ( $dynamicParams !== null ) {
					$dynamicParams = ApiBase::makeMessage( $dynamicParams, $context, [
						$module->getModulePrefix(),
						$module->getModuleName(),
						$module->getModulePath()
					] );
					$help['parameters'] .= Html::element( 'dt', null, '*' );
					$help['parameters'] .= Html::rawElement( 'dd',
						[ 'class' => 'description' ], $dynamicParams->parse() );
				}

				$help['parameters'] .= Html::closeElement( 'dl' );
				$help['parameters'] .= Html::closeElement( 'div' );
			}

			$examples = $module->getExamplesMessages();
			if ( $examples ) {
				$help['examples'] .= Html::openElement( 'div',
					[ 'class' => 'apihelp-block apihelp-examples' ] );
				$msg = $context->msg( 'api-help-examples' );
				if ( !$msg->isDisabled() ) {
					$help['examples'] .= self::wrap(
						$msg->numParams( count( $examples ) ), 'apihelp-block-head', 'div'
					);
				}

				$help['examples'] .= Html::openElement( 'dl' );
				foreach ( $examples as $qs => $msg ) {
					$msg = ApiBase::makeMessage( $msg, $context, [
						$module->getModulePrefix(),
						$module->getModuleName(),
						$module->getModulePath()
					] );

					$link = wfAppendQuery( wfScript( 'api' ), $qs );
					$sandbox = SpecialPage::getTitleFor( 'ApiSandbox' )->getLocalURL() . '#' . $qs;
					$help['examples'] .= Html::rawElement( 'dt', null, $msg->parse() );
					$help['examples'] .= Html::rawElement( 'dd', null,
						Html::element( 'a', [ 'href' => $link ], "api.php?$qs" ) . ' ' .
						Html::rawElement( 'a', [ 'href' => $sandbox ],
							$context->msg( 'api-help-open-in-apisandbox' )->parse() )
					);
				}
				$help['examples'] .= Html::closeElement( 'dl' );
				$help['examples'] .= Html::closeElement( 'div' );
			}

			$subtocnumber = $tocnumber;
			$subtocnumber[$level + 1] = 0;
			$suboptions = [
				'submodules' => $options['recursivesubmodules'],
				'headerlevel' => $level + 1,
				'tocnumber' => &$subtocnumber,
				'noheader' => false,
			] + $options;

			if ( $options['submodules'] && $module->getModuleManager() ) {
				$manager = $module->getModuleManager();
				$submodules = [];
				foreach ( $groups as $group ) {
					$names = $manager->getNames( $group );
					sort( $names );
					foreach ( $names as $name ) {
						$submodules[] = $manager->getModule( $name );
					}
				}
				$help['submodules'] .= self::getHelpInternal(
					$context,
					$submodules,
					$suboptions,
					$haveModules
				);
			}

			$module->modifyHelp( $help, $suboptions, $haveModules );

			Hooks::run( 'APIHelpModifyOutput', [ $module, &$help, $suboptions, &$haveModules ] );

			$out .= implode( "\n", $help );
		}

		return $out;
	}

	public function shouldCheckMaxlag() {
		return false;
	}

	public function isReadMode() {
		return false;
	}

	public function getCustomPrinter() {
		$params = $this->extractRequestParams();
		if ( $params['wrap'] ) {
			return null;
		}

		$main = $this->getMain();
		$errorPrinter = $main->createPrinterByName( $main->getParameter( 'format' ) );
		return new ApiFormatRaw( $main, $errorPrinter );
	}

	public function getAllowedParams() {
		return [
			'modules' => [
				ApiBase::PARAM_DFLT => 'main',
				ApiBase::PARAM_ISMULTI => true,
			],
			'submodules' => false,
			'recursivesubmodules' => false,
			'wrap' => false,
			'toc' => false,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=help'
				=> 'apihelp-help-example-main',
			'action=help&modules=query&submodules=1'
				=> 'apihelp-help-example-submodules',
			'action=help&recursivesubmodules=1'
				=> 'apihelp-help-example-recursive',
			'action=help&modules=help'
				=> 'apihelp-help-example-help',
			'action=help&modules=query+info|query+categorymembers'
				=> 'apihelp-help-example-query',
		];
	}

	public function getHelpUrls() {
		return [
			'https://www.mediawiki.org/wiki/API:Main_page',
			'https://www.mediawiki.org/wiki/API:FAQ',
			'https://www.mediawiki.org/wiki/API:Quick_start_guide',
		];
	}
}
