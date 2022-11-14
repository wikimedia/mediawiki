<?php
/** Venetian (vèneto)
 *
 * @file
 * @ingroup Languages
 *
 * @author Alunardon90
 * @author BrokenArrow
 * @author Candalua
 * @author Frigotoni
 * @author GatoSelvadego
 * @author Kaganer
 * @author Malafaya
 * @author Nemo bis
 * @author Nick1915
 * @author Omnipaedista
 * @author OrbiliusMagister
 * @author Reedy
 * @author Shirayuki
 * @author Urhixidur
 * @author Vajotwo
 * @author לערי ריינהארט
 * @author Fierodelveneto
 */

$fallback = 'it';

/** @phpcs-require-sorted-array */
$magicWords = [
	'redirect' => [ '0', '#VARDA', '#RINVIA', '#RINVIO', '#RIMANDO', '#REDIRECT' ],
];

$namespaceNames = [
	NS_MEDIA            => 'Mèdia',
	NS_SPECIAL          => 'Speçałe',
	NS_TALK             => 'Discusion',
	NS_USER             => 'Utensa',
	NS_USER_TALK        => 'Discusion_Utensa',
	NS_PROJECT_TALK     => 'Discusion_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Discusion_File',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discusion_MediaWiki',
	NS_TEMPLATE         => 'Modeło',
	NS_TEMPLATE_TALK    => 'Discusion_Modeło',
	NS_HELP             => 'Juto',
	NS_HELP_TALK        => 'Discusion_Juto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discusion_Categoria',
];

$namespaceAliases = [
	'Aiuto'                => NS_HELP,
	'Ajuto'                => NS_HELP,
	'Discusion_Imàjine'    => NS_FILE_TALK,
	'Discussion'           => NS_TALK,
	'Discussion_$1'        => NS_PROJECT_TALK,
	'Discussion_aiuto'     => NS_HELP_TALK,
	'Discussion_ajuto'     => NS_HELP_TALK,
	'Discussion_categoria' => NS_CATEGORY_TALK,
	'Discussion_file'      => NS_FILE_TALK,
	'Discussion_imagine'   => NS_FILE_TALK,
	'Discussion_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Discussion_modèl'     => NS_TEMPLATE_TALK,
	'Discussion_template'  => NS_TEMPLATE_TALK,
	'Discussion_utente'    => NS_USER_TALK,
	'Imàjine'              => NS_FILE,
	'Media'                => NS_MEDIA,
	'Modèl'                => NS_TEMPLATE,
	'Speciale'             => NS_SPECIAL,
	'Utente'               => NS_USER,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'UtentiAtivi' ],
	'Allmessages'               => [ 'Messagi' ],
	'AllMyUploads'              => [ 'TutiIMeCaricamenti' ],
	'Allpages'                  => [ 'TuteLePagine' ],
	'Ancientpages'              => [ 'PagineMancoNove' ],
	'Badtitle'                  => [ 'TitoloSbalià' ],
	'Blankpage'                 => [ 'PaginaVoda' ],
	'Block'                     => [ 'Bloca' ],
	'BlockList'                 => [ 'IPBlocài' ],
	'Booksources'               => [ 'SercaISBN' ],
	'BrokenRedirects'           => [ 'RimandiSbalià' ],
	'Categories'                => [ 'Categorie' ],
	'ChangeEmail'               => [ 'CanbiaEmail' ],
	'ChangePassword'            => [ 'CanbiaPassword' ],
	'ComparePages'              => [ 'ConfrontaPagine' ],
	'Confirmemail'              => [ 'ConfermaEMail' ],
	'Contributions'             => [ 'Contributi' ],
	'CreateAccount'             => [ 'CreaUtente' ],
	'Deadendpages'              => [ 'PagineSensaUscita' ],
	'DeletedContributions'      => [ 'ContributiScancelà' ],
	'DoubleRedirects'           => [ 'DópiRimandi' ],
	'EditWatchlist'             => [ 'CanbiaTegnuiDeOcio' ],
	'Emailuser'                 => [ 'MandaEMail' ],
	'ExpandTemplates'           => [ 'EspandiModèi' ],
	'Export'                    => [ 'Esporta' ],
	'Fewestrevisions'           => [ 'PagineConMancoRevision' ],
	'FileDuplicateSearch'       => [ 'SercaDopioniDeiFile' ],
	'Filepath'                  => [ 'PercorsoFile' ],
	'Import'                    => [ 'Inporta' ],
	'Invalidateemail'           => [ 'InvalidaEMail' ],
	'LinkSearch'                => [ 'SercaLigamenti' ],
	'Listadmins'                => [ 'Aministradori' ],
	'Listbots'                  => [ 'ListaDeiBot' ],
	'Listfiles'                 => [ 'ListaFile' ],
	'Listgrouprights'           => [ 'ListaDiritiDeGrupo' ],
	'Listredirects'             => [ 'Rimandi' ],
	'Listusers'                 => [ 'Utenti' ],
	'Lockdb'                    => [ 'BlocaDB' ],
	'Log'                       => [ 'Registri' ],
	'Lonelypages'               => [ 'PagineSolitarie' ],
	'Longpages'                 => [ 'PaginePiLonghe' ],
	'MergeHistory'              => [ 'FondiCronologia' ],
	'MIMEsearch'                => [ 'SercaMIME' ],
	'Mostcategories'            => [ 'PagineConPiassèCategorie' ],
	'Mostimages'                => [ 'FilePiassèDoparà' ],
	'Mostinterwikis'            => [ 'PiassèInterwiki' ],
	'Mostlinked'                => [ 'PaginePiassèRiciamà' ],
	'Mostlinkedcategories'      => [ 'CategoriePiassèDoparà' ],
	'Mostlinkedtemplates'       => [ 'ModèiPiassèDoparà' ],
	'Mostrevisions'             => [ 'PagineConPiassèRevision' ],
	'Movepage'                  => [ 'Sposta' ],
	'Mycontributions'           => [ 'IMeContributi' ],
	'Mypage'                    => [ 'LaMePaginaUtente' ],
	'Mytalk'                    => [ 'LeMeDiscussion' ],
	'Myuploads'                 => [ 'IMeCaricamenti' ],
	'Newimages'                 => [ 'FileNovi' ],
	'Newpages'                  => [ 'PagineNove' ],
	'PasswordReset'             => [ 'ReinpostaPassword' ],
	'PermanentLink'             => [ 'LinkParmanente' ],
	'Preferences'               => [ 'Preferense' ],
	'Prefixindex'               => [ 'Prefissi' ],
	'Protectedpages'            => [ 'PagineProtete' ],
	'Protectedtitles'           => [ 'TitoliProteti' ],
	'Randompage'                => [ 'PaginaAOcio' ],
	'Randomredirect'            => [ 'UnRimandoAOcio' ],
	'Recentchanges'             => [ 'ÙltimiCanbiamenti' ],
	'Recentchangeslinked'       => [ 'CanbiamentiLigà' ],
	'Redirect'                  => [ 'Rimando' ],
	'Revisiondelete'            => [ 'ScancelaRevision' ],
	'Search'                    => [ 'Serca' ],
	'Shortpages'                => [ 'PaginePiCurte' ],
	'Specialpages'              => [ 'PagineSpeciali' ],
	'Statistics'                => [ 'Statìsteghe' ],
	'Tags'                      => [ 'Tag' ],
	'Unblock'                   => [ 'Desbloca' ],
	'Uncategorizedcategories'   => [ 'CategorieSensaCategorie' ],
	'Uncategorizedimages'       => [ 'FileSensaCategorie' ],
	'Uncategorizedpages'        => [ 'PagineSensaCategorie' ],
	'Uncategorizedtemplates'    => [ 'ModèiSensaCategorie' ],
	'Undelete'                  => [ 'Ripristina' ],
	'Unlockdb'                  => [ 'DesblocaDB' ],
	'Unusedcategories'          => [ 'CategorieMiaDoparà' ],
	'Unusedimages'              => [ 'FileMiaDoparà' ],
	'Unusedtemplates'           => [ 'ModèiMiaDoparà' ],
	'Unwatchedpages'            => [ 'PagineMiaTegnùDeOcio' ],
	'Upload'                    => [ 'Carga' ],
	'Userlogin'                 => [ 'Entra' ],
	'Userlogout'                => [ 'VàFora' ],
	'Userrights'                => [ 'ParmessiUtente' ],
	'Wantedcategories'          => [ 'CategorieDomandà' ],
	'Wantedfiles'               => [ 'FileDomandà' ],
	'Wantedpages'               => [ 'PagineDomandà' ],
	'Wantedtemplates'           => [ 'ModèiDomandà' ],
	'Watchlist'                 => [ 'TegnùiDeOcio' ],
	'Whatlinkshere'             => [ 'PuntaQua' ],
	'Withoutinterwiki'          => [ 'PagineSensaInterwiki' ],
];
