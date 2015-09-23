<?php
/** vèneto (vèneto)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
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
 */

$magicWords = array(
	'redirect'                  => array( '0', '#VARDA', '#RINVIA', '#RINVIO', '#RIMANDO', '#REDIRECT' ),
);

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discussion_utente',
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Discussion_file',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Modèl',
	NS_TEMPLATE_TALK    => 'Discussion_modèl',
	NS_HELP             => 'Ajuto',
	NS_HELP_TALK        => 'Discussion_ajuto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussion_categoria',
);

$namespaceAliases = array(
	'Imagine' => NS_FILE,
	'Discussion_imagine' => NS_FILE_TALK,
	'Discussion_template' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussion_aiuto' => NS_HELP_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'UtentiAtivi' ),
	'Allmessages'               => array( 'Messagi' ),
	'AllMyUploads'              => array( 'TutiIMeCaricamenti' ),
	'Allpages'                  => array( 'TuteLePagine' ),
	'Ancientpages'              => array( 'PagineMancoNove' ),
	'Badtitle'                  => array( 'TitoloSbalià' ),
	'Blankpage'                 => array( 'PaginaVoda' ),
	'Block'                     => array( 'Bloca' ),
	'Booksources'               => array( 'SercaISBN' ),
	'BrokenRedirects'           => array( 'RimandiSbalià' ),
	'Categories'                => array( 'Categorie' ),
	'ChangeEmail'               => array( 'CanbiaEmail' ),
	'ChangePassword'            => array( 'CanbiaPassword' ),
	'ComparePages'              => array( 'ConfrontaPagine' ),
	'Confirmemail'              => array( 'ConfermaEMail' ),
	'Contributions'             => array( 'Contributi' ),
	'CreateAccount'             => array( 'CreaUtente' ),
	'Deadendpages'              => array( 'PagineSensaUscita' ),
	'DeletedContributions'      => array( 'ContributiScancelà' ),
	'DoubleRedirects'           => array( 'DópiRimandi' ),
	'EditWatchlist'             => array( 'CanbiaTegnuiDeOcio' ),
	'Emailuser'                 => array( 'MandaEMail' ),
	'ExpandTemplates'           => array( 'EspandiModèi' ),
	'Export'                    => array( 'Esporta' ),
	'Fewestrevisions'           => array( 'PagineConMancoRevision' ),
	'FileDuplicateSearch'       => array( 'SercaDopioniDeiFile' ),
	'Filepath'                  => array( 'PercorsoFile' ),
	'Import'                    => array( 'Inporta' ),
	'Invalidateemail'           => array( 'InvalidaEMail' ),
	'BlockList'                 => array( 'IPBlocài' ),
	'LinkSearch'                => array( 'SercaLigamenti' ),
	'Listadmins'                => array( 'Aministradori' ),
	'Listbots'                  => array( 'ListaDeiBot' ),
	'Listfiles'                 => array( 'ListaFile' ),
	'Listgrouprights'           => array( 'ListaDiritiDeGrupo' ),
	'Listredirects'             => array( 'Rimandi' ),
	'Listusers'                 => array( 'Utenti' ),
	'Lockdb'                    => array( 'BlocaDB' ),
	'Log'                       => array( 'Registri' ),
	'Lonelypages'               => array( 'PagineSolitarie' ),
	'Longpages'                 => array( 'PaginePiLonghe' ),
	'MergeHistory'              => array( 'FondiCronologia' ),
	'MIMEsearch'                => array( 'SercaMIME' ),
	'Mostcategories'            => array( 'PagineConPiassèCategorie' ),
	'Mostimages'                => array( 'FilePiassèDoparà' ),
	'Mostinterwikis'            => array( 'PiassèInterwiki' ),
	'Mostlinked'                => array( 'PaginePiassèRiciamà' ),
	'Mostlinkedcategories'      => array( 'CategoriePiassèDoparà' ),
	'Mostlinkedtemplates'       => array( 'ModèiPiassèDoparà' ),
	'Mostrevisions'             => array( 'PagineConPiassèRevision' ),
	'Movepage'                  => array( 'Sposta' ),
	'Mycontributions'           => array( 'IMeContributi' ),
	'Mypage'                    => array( 'LaMePaginaUtente' ),
	'Mytalk'                    => array( 'LeMeDiscussion' ),
	'Myuploads'                 => array( 'IMeCaricamenti' ),
	'Newimages'                 => array( 'FileNovi' ),
	'Newpages'                  => array( 'PagineNove' ),
	'PasswordReset'             => array( 'ReinpostaPassword' ),
	'PermanentLink'             => array( 'LinkParmanente' ),
	'Preferences'               => array( 'Preferense' ),
	'Prefixindex'               => array( 'Prefissi' ),
	'Protectedpages'            => array( 'PagineProtete' ),
	'Protectedtitles'           => array( 'TitoliProteti' ),
	'Randompage'                => array( 'PaginaAOcio' ),
	'Randomredirect'            => array( 'UnRimandoAOcio' ),
	'Recentchanges'             => array( 'ÙltimiCanbiamenti' ),
	'Recentchangeslinked'       => array( 'CanbiamentiLigà' ),
	'Redirect'                  => array( 'Rimando' ),
	'Revisiondelete'            => array( 'ScancelaRevision' ),
	'Search'                    => array( 'Serca' ),
	'Shortpages'                => array( 'PaginePiCurte' ),
	'Specialpages'              => array( 'PagineSpeciali' ),
	'Statistics'                => array( 'Statìsteghe' ),
	'Tags'                      => array( 'Tag' ),
	'Unblock'                   => array( 'Desbloca' ),
	'Uncategorizedcategories'   => array( 'CategorieSensaCategorie' ),
	'Uncategorizedimages'       => array( 'FileSensaCategorie' ),
	'Uncategorizedpages'        => array( 'PagineSensaCategorie' ),
	'Uncategorizedtemplates'    => array( 'ModèiSensaCategorie' ),
	'Undelete'                  => array( 'Ripristina' ),
	'Unlockdb'                  => array( 'DesblocaDB' ),
	'Unusedcategories'          => array( 'CategorieMiaDoparà' ),
	'Unusedimages'              => array( 'FileMiaDoparà' ),
	'Unusedtemplates'           => array( 'ModèiMiaDoparà' ),
	'Unwatchedpages'            => array( 'PagineMiaTegnùDeOcio' ),
	'Upload'                    => array( 'Carga' ),
	'Userlogin'                 => array( 'Entra' ),
	'Userlogout'                => array( 'VàFora' ),
	'Userrights'                => array( 'ParmessiUtente' ),
	'Wantedcategories'          => array( 'CategorieDomandà' ),
	'Wantedfiles'               => array( 'FileDomandà' ),
	'Wantedpages'               => array( 'PagineDomandà' ),
	'Wantedtemplates'           => array( 'ModèiDomandà' ),
	'Watchlist'                 => array( 'TegnùiDeOcio' ),
	'Whatlinkshere'             => array( 'PuntaQua' ),
	'Withoutinterwiki'          => array( 'PagineSensaInterwiki' ),
);

