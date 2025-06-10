<?php
/**
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

use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Specials\SpecialUpload;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Sub class of HTMLForm that provides the form section of SpecialUpload
 */
class UploadForm extends HTMLForm {
	/** @var bool */
	protected $mWatch;
	/** @var bool */
	protected $mForReUpload;
	/** @var string */
	protected $mSessionKey;
	/** @var bool */
	protected $mHideIgnoreWarning;
	/** @var bool */
	protected $mDestWarningAck;
	/** @var string */
	protected $mDestFile;

	/** @var string */
	protected $mComment;
	/** @var string raw html */
	protected $mTextTop;
	/** @var string raw html */
	protected $mTextAfterSummary;

	/** @var string[] */
	protected $mSourceIds;

	/** @var array */
	protected $mMaxUploadSize = [];

	private LocalRepo $localRepo;
	private Language $contentLanguage;
	private NamespaceInfo $nsInfo;
	private HookRunner $hookRunner;

	public function __construct(
		array $options = [],
		?IContextSource $context = null,
		?LinkRenderer $linkRenderer = null,
		?LocalRepo $localRepo = null,
		?Language $contentLanguage = null,
		?NamespaceInfo $nsInfo = null,
		?HookContainer $hookContainer = null
	) {
		if ( $context instanceof IContextSource ) {
			$this->setContext( $context );
		}

		$services = MediaWikiServices::getInstance();
		if ( !$linkRenderer ) {
			$linkRenderer = $services->getLinkRenderer();
		}
		if ( !$localRepo ) {
			$localRepo = $services->getRepoGroup()->getLocalRepo();
		}
		if ( !$contentLanguage ) {
			$contentLanguage = $services->getContentLanguage();
		}
		if ( !$nsInfo ) {
			$nsInfo = $services->getNamespaceInfo();
		}
		$this->localRepo = $localRepo;
		$this->contentLanguage = $contentLanguage;
		$this->nsInfo = $nsInfo;
		$this->hookRunner = new HookRunner( $hookContainer ?? $services->getHookContainer() );

		$this->mWatch = !empty( $options['watch'] );
		$this->mForReUpload = !empty( $options['forreupload'] );
		$this->mSessionKey = $options['sessionkey'] ?? '';
		$this->mHideIgnoreWarning = !empty( $options['hideignorewarning'] );
		$this->mDestWarningAck = !empty( $options['destwarningack'] );
		$this->mDestFile = $options['destfile'] ?? '';

		$this->mComment = $options['description'] ?? '';

		$this->mTextTop = $options['texttop'] ?? '';

		$this->mTextAfterSummary = $options['textaftersummary'] ?? '';

		$sourceDescriptor = $this->getSourceSection();
		$descriptor = $sourceDescriptor
			+ $this->getDescriptionSection()
			+ $this->getOptionsSection();

		$this->hookRunner->onUploadFormInitDescriptor( $descriptor );
		parent::__construct( $descriptor, $this->getContext(), 'upload' );

		# Add a link to edit MediaWiki:Licenses
		if ( $this->getAuthority()->isAllowed( 'editinterface' ) ) {
			$this->getOutput()->addModuleStyles( 'mediawiki.special' );
			$licensesLink = $linkRenderer->makeKnownLink(
				$this->msg( 'licenses' )->inContentLanguage()->getTitle(),
				$this->msg( 'licenses-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$editLicenses = '<p class="mw-upload-editlicenses">' . $licensesLink . '</p>';
			$this->addFooterHtml( $editLicenses, 'description' );
		}

		# Set some form properties
		$this->setSubmitTextMsg( 'uploadbtn' );
		$this->setSubmitName( 'wpUpload' );
		# Used message keys: 'accesskey-upload', 'tooltip-upload'
		$this->setSubmitTooltip( 'upload' );
		$this->setId( 'mw-upload-form' );

		# Build a list of IDs for javascript insertion
		$this->mSourceIds = [];
		foreach ( $sourceDescriptor as $field ) {
			if ( !empty( $field['id'] ) ) {
				$this->mSourceIds[] = $field['id'];
			}
		}
	}

	/**
	 * Get the descriptor of the fieldset that contains the file source
	 * selection. The section is 'source'
	 *
	 * @return array Descriptor array
	 */
	protected function getSourceSection() {
		if ( $this->mSessionKey ) {
			return [
				'SessionKey' => [
					'type' => 'hidden',
					'default' => $this->mSessionKey,
				],
				'SourceType' => [
					'type' => 'hidden',
					'default' => 'Stash',
				],
			];
		}

		$canUploadByUrl = UploadFromUrl::isEnabled()
			&& ( UploadFromUrl::isAllowed( $this->getAuthority() ) === true )
			&& $this->getConfig()->get( MainConfigNames::CopyUploadsFromSpecialUpload );
		$radio = $canUploadByUrl;
		$selectedSourceType = strtolower( $this->getRequest()->getText( 'wpSourceType', 'File' ) );

		$descriptor = [];
		if ( $this->mTextTop ) {
			$descriptor['UploadFormTextTop'] = [
				'type' => 'info',
				'section' => 'source',
				'default' => $this->mTextTop,
				'raw' => true,
			];
		}

		$this->mMaxUploadSize['file'] = min(
			UploadBase::getMaxUploadSize( 'file' ),
			UploadBase::getMaxPhpUploadSize()
		);

		$help = $this->msg( 'upload-maxfilesize' )->sizeParams( $this->mMaxUploadSize['file'] )->parse();

		// If the user can also upload by URL, there are 2 different file size limits.
		// This extra message helps stress which limit corresponds to what.
		if ( $canUploadByUrl ) {
			$help .= $this->msg( 'word-separator' )->escaped();
			$help .= $this->msg( 'upload_source_file' )->parse();
		}

		$descriptor['UploadFile'] = [
			'class' => UploadSourceField::class,
			'section' => 'source',
			'type' => 'file',
			'id' => 'wpUploadFile',
			'radio-id' => 'wpSourceTypeFile',
			'label-message' => 'sourcefilename',
			'upload-type' => 'File',
			'radio' => &$radio,
			'help-raw' => $help,
			'checked' => $selectedSourceType == 'file',
		];

		if ( $canUploadByUrl ) {
			$this->mMaxUploadSize['url'] = UploadBase::getMaxUploadSize( 'url' );
			$descriptor['UploadFileURL'] = [
				'class' => UploadSourceField::class,
				'section' => 'source',
				'id' => 'wpUploadFileURL',
				'radio-id' => 'wpSourceTypeurl',
				'label-message' => 'sourceurl',
				'upload-type' => 'url',
				'radio' => &$radio,
				'help-raw' => $this->msg( 'upload-maxfilesize' )->sizeParams( $this->mMaxUploadSize['url'] )->parse() .
					$this->msg( 'word-separator' )->escaped() .
					$this->msg( 'upload_source_url' )->parse(),
				'checked' => $selectedSourceType == 'url',
			];
		}
		$this->hookRunner->onUploadFormSourceDescriptors(
			$descriptor, $radio, $selectedSourceType );

		$descriptor['Extensions'] = [
			'type' => 'info',
			'section' => 'source',
			'default' => $this->getExtensionsMessage(),
			'raw' => true,
		];

		return $descriptor;
	}

	/**
	 * Get the messages indicating which extensions are preferred and prohibited.
	 *
	 * @return string HTML string containing the message
	 */
	protected function getExtensionsMessage() {
		# Print a list of allowed file extensions, if so configured.  We ignore
		# MIME type here, it's incomprehensible to most people and too long.
		$config = $this->getConfig();

		if ( $config->get( MainConfigNames::CheckFileExtensions ) ) {
			$fileExtensions = array_unique( $config->get( MainConfigNames::FileExtensions ) );
			if ( $config->get( MainConfigNames::StrictFileExtensions ) ) {
				# Everything not permitted is banned
				$extensionsList =
					'<div id="mw-upload-permitted">' .
					$this->msg( 'upload-permitted' )
						->params( $this->getLanguage()->commaList( $fileExtensions ) )
						->numParams( count( $fileExtensions ) )
						->parseAsBlock() .
					"</div>\n";
			} else {
				# We have to list both preferred and prohibited
				$prohibitedExtensions =
					array_unique( $config->get( MainConfigNames::ProhibitedFileExtensions ) );
				$extensionsList =
					'<div id="mw-upload-preferred">' .
						$this->msg( 'upload-preferred' )
							->params( $this->getLanguage()->commaList( $fileExtensions ) )
							->numParams( count( $fileExtensions ) )
							->parseAsBlock() .
					"</div>\n" .
					'<div id="mw-upload-prohibited">' .
						$this->msg( 'upload-prohibited' )
							->params( $this->getLanguage()->commaList( $prohibitedExtensions ) )
							->numParams( count( $prohibitedExtensions ) )
							->parseAsBlock() .
					"</div>\n";
			}
		} else {
			# Everything is permitted.
			$extensionsList = '';
		}

		return $extensionsList;
	}

	/**
	 * Get the descriptor of the fieldset that contains the file description
	 * input. The section is 'description'
	 *
	 * @return array Descriptor array
	 */
	protected function getDescriptionSection() {
		$config = $this->getConfig();
		if ( $this->mSessionKey ) {
			$stash = $this->localRepo->getUploadStash( $this->getUser() );
			try {
				$file = $stash->getFile( $this->mSessionKey );
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception ) {
				$file = null;
			}
			if ( $file ) {
				$mto = $file->transform( [ 'width' => 120 ] );
				if ( $mto ) {
					$this->addHeaderHtml(
						'<div class="thumb t' .
						$this->contentLanguage->alignEnd() . '">' .
						Html::element( 'img', [
							'src' => $mto->getUrl(),
							'class' => 'thumbimage',
						] ) . '</div>', 'description' );
				}
			}
		}

		$descriptor = [
			'DestFile' => [
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpDestFile',
				'label-message' => 'destfilename',
				'size' => 60,
				'default' => $this->mDestFile,
				# @todo FIXME: Hack to work around poor handling of the 'default' option in HTMLForm
				'nodata' => strval( $this->mDestFile ) !== '',
			],
			'UploadDescription' => [
				'type' => $this->mForReUpload
					? 'text'
					: 'textarea',
				'section' => 'description',
				'id' => 'wpUploadDescription',
				'label-message' => $this->mForReUpload
					? 'filereuploadsummary'
					: 'fileuploadsummary',
				'default' => $this->mComment,
			]
		];
		if ( $this->mTextAfterSummary ) {
			$descriptor['UploadFormTextAfterSummary'] = [
				'type' => 'info',
				'section' => 'description',
				'default' => $this->mTextAfterSummary,
				'raw' => true,
			];
		}

		$descriptor += [
			'EditTools' => [
				'type' => 'edittools',
				'section' => 'description',
				'message' => 'edittools-upload',
			]
		];

		if ( $this->mForReUpload ) {
			$descriptor['DestFile']['readonly'] = true;
			$descriptor['UploadDescription']['size'] = 60;
		} else {
			$descriptor['License'] = [
				'type' => 'select',
				'class' => Licenses::class,
				'section' => 'description',
				'id' => 'wpLicense',
				'label-message' => 'license',
			];
			$descriptor['UploadDescription']['rows'] = 8;
		}

		if ( $config->get( MainConfigNames::UseCopyrightUpload ) ) {
			$descriptor['UploadCopyStatus'] = [
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpUploadCopyStatus',
				'label-message' => 'filestatus',
			];
			$descriptor['UploadSource'] = [
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpUploadSource',
				'label-message' => 'filesource',
			];
		}

		return $descriptor;
	}

	/**
	 * Get the descriptor of the fieldset that contains the upload options,
	 * such as "watch this file". The section is 'options'
	 *
	 * @return array Descriptor array
	 */
	protected function getOptionsSection() {
		$user = $this->getUser();
		if ( $user->isRegistered() ) {
			$descriptor = [
				'Watchthis' => [
					'type' => 'check',
					'id' => 'wpWatchthis',
					'label-message' => 'watchthisupload',
					'section' => 'options',
					'default' => $this->mWatch,
				]
			];
		}
		if ( !$this->mHideIgnoreWarning ) {
			$descriptor['IgnoreWarning'] = [
				'type' => 'check',
				'id' => 'wpIgnoreWarning',
				'label-message' => 'ignorewarnings',
				'section' => 'options',
			];
		}

		$descriptor['DestFileWarningAck'] = [
			'type' => 'hidden',
			'id' => 'wpDestFileWarningAck',
			'default' => $this->mDestWarningAck ? '1' : '',
		];

		if ( $this->mForReUpload ) {
			$descriptor['ForReUpload'] = [
				'type' => 'hidden',
				'id' => 'wpForReUpload',
				'default' => '1',
			];
		}

		return $descriptor;
	}

	/**
	 * Add the upload JS and show the form.
	 * @return bool|Status
	 */
	public function show() {
		$this->addUploadJS();
		return parent::show();
	}

	/**
	 * Add upload JS to the OutputPage
	 */
	protected function addUploadJS() {
		$config = $this->getConfig();

		$this->mMaxUploadSize['*'] = UploadBase::getMaxUploadSize();

		$scriptVars = [
			'wgAjaxLicensePreview' => $config->get( MainConfigNames::AjaxLicensePreview ),
			'wgUploadAutoFill' => !$this->mForReUpload &&
				// If we received mDestFile from the request, don't autofill
				// the wpDestFile textbox
				$this->mDestFile === '',
			'wgUploadSourceIds' => $this->mSourceIds,
			'wgCheckFileExtensions' => $config->get( MainConfigNames::CheckFileExtensions ),
			'wgStrictFileExtensions' => $config->get( MainConfigNames::StrictFileExtensions ),
			'wgFileExtensions' =>
				array_values( array_unique( $config->get( MainConfigNames::FileExtensions ) ) ),
			'wgMaxUploadSize' => $this->mMaxUploadSize,
			'wgFileCanRotate' => SpecialUpload::rotationEnabled(),
		];

		$out = $this->getOutput();
		$out->addJsConfigVars( $scriptVars );

		$out->addModules( [
			'mediawiki.special.upload', // Extras for thumbnail and license preview.
		] );
	}

	/**
	 * Empty function; submission is handled elsewhere.
	 *
	 * @return bool False
	 */
	public function trySubmit() {
		return false;
	}
}
