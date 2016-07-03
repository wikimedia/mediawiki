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

$magicWords = [
	'redirect'                  => [ '0', '#VARDA', '#RINVIA', '#RINVIO', '#RIMANDO', '#REDIRECT' ],
];

$fallback = 'it';

$namespaceNames = [
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
];

$namespaceAliases = [
	'Imagine' => NS_FILE,
	'Discussion_imagine' => NS_FILE_TALK,
	'Discussion_template' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussion_aiuto' => NS_HELP_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'UtentiAtivi' ],
	'Allmessages'               => [ 'Messagi' ],
	'AllMyUploads'              => [ 'TutiIMeCaricamenti' ],
	'Allpages'                  => [ 'TuteLePagine' ],
	'Ancientpages'              => [ 'PagineMancoNove' ],
	'Badtitle'                  => [ 'TitoloSbalià' ],
	'Blankpage'                 => [ 'PaginaVoda' ],
	'Block'                     => [ 'Bloca' ],
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
	'BlockList'                 => [ 'IPBlocài' ],
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

