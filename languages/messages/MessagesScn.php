<?php
/** Sicilian (sicilianu)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aushulz
 * @author Gmelfi
 * @author Kaganer
 * @author Markos90
 * @author Melos
 * @author Omnipaedista
 * @author Santu
 * @author Sarvaturi
 * @author Tonyfroio
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Mèdia',
	NS_SPECIAL          => 'Spiciali',
	NS_TALK             => 'Discussioni',
	NS_USER             => 'Utenti',
	NS_USER_TALK        => 'Discussioni_utenti',
	NS_PROJECT_TALK     => 'Discussioni_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Discussioni_file',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussioni_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Discussioni_template',
	NS_HELP             => 'Aiutu',
	NS_HELP_TALK        => 'Discussioni_aiutu',
	NS_CATEGORY         => 'Catigurìa',
	NS_CATEGORY_TALK    => 'Discussioni_catigurìa',
);

$namespaceAliases = array(
	'Discussioni_Utenti' => NS_USER_TALK,
	'Mmàggini' => NS_FILE,
	'Discussioni mmàggini' => NS_FILE_TALK,
	'Discussioni_Template' => NS_TEMPLATE_TALK,
	'Discussioni_Aiutu' => NS_HELP_TALK,
	'Discussioni_Catigurìa' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'UtentiAttivi' ),
	'Allmessages'               => array( 'Messaggi' ),
	'Allpages'                  => array( 'TutteLePagine' ),
	'Ancientpages'              => array( 'PagineMenoRecenti' ),
	'Badtitle'                  => array( 'TitoloErrato' ),
	'Blankpage'                 => array( 'PaginaVuota' ),
	'Block'                     => array( 'Blocca' ),
	'Booksources'               => array( 'RicercaISBN' ),
	'BrokenRedirects'           => array( 'RedirectErrati' ),
	'Categories'                => array( 'Categorie' ),
	'ChangeEmail'               => array( 'CambiaEmail' ),
	'ChangePassword'            => array( 'CambiaPassword' ),
	'ComparePages'              => array( 'ComparaPagine' ),
	'Confirmemail'              => array( 'ConfermaEMail' ),
	'Contributions'             => array( 'Contributi', 'ContributiUtente', 'Edit' ),
	'CreateAccount'             => array( 'CreaAccount' ),
	'Deadendpages'              => array( 'PagineSenzaUscita' ),
	'DeletedContributions'      => array( 'ContributiCancellati' ),
	'DoubleRedirects'           => array( 'RedirectDoppi' ),
	'EditWatchlist'             => array( 'ModificaOsservati', 'ModificaOsservatiSpeciali', 'ModificaListaSeguiti' ),
	'Emailuser'                 => array( 'InviaEMail' ),
	'ExpandTemplates'           => array( 'EspandiTemplate' ),
	'Export'                    => array( 'Esporta' ),
	'Fewestrevisions'           => array( 'PagineConMenoRevisioni' ),
	'FileDuplicateSearch'       => array( 'CercaFileDuplicati' ),
	'Filepath'                  => array( 'Percorso' ),
	'Import'                    => array( 'Importa' ),
	'Invalidateemail'           => array( 'InvalidaEMail' ),
	'JavaScriptTest'            => array( 'TestJavaScript' ),
	'BlockList'                 => array( 'IPBloccati', 'ElencoBlocchi', 'Blocchi' ),
	'LinkSearch'                => array( 'CercaCollegamenti', 'CercaLink' ),
	'Listadmins'                => array( 'Amministratori', 'ElencoAmministratori', 'Admin', 'Sysop', 'Cricca' ),
	'Listbots'                  => array( 'Bot', 'ElencoBot' ),
	'Listfiles'                 => array( 'File', 'Immagini' ),
	'Listgrouprights'           => array( 'ElencoPermessiGruppi', 'Privilegi' ),
	'Listredirects'             => array( 'Redirect', 'ElencoRedirect' ),
	'Listusers'                 => array( 'Utenti', 'ElencoUtenti' ),
	'Lockdb'                    => array( 'BloccaDB' ),
	'Log'                       => array( 'Registri', 'Registro' ),
	'Lonelypages'               => array( 'PagineOrfane' ),
	'Longpages'                 => array( 'PaginePiùLunghe' ),
	'MergeHistory'              => array( 'FondiCronologia', 'UnificaCronologia' ),
	'MIMEsearch'                => array( 'RicercaMIME' ),
	'Mostcategories'            => array( 'PagineConPiùCategorie' ),
	'Mostimages'                => array( 'ImmaginiPiùRichiamate' ),
	'Mostinterwikis'            => array( 'InterwikiPiùRichiamati' ),
	'Mostlinked'                => array( 'PaginePiùRichiamate' ),
	'Mostlinkedcategories'      => array( 'CategoriePiùRichiamate' ),
	'Mostlinkedtemplates'       => array( 'TemplatePiùRichiamati' ),
	'Mostrevisions'             => array( 'PagineConPiùRevisioni' ),
	'Movepage'                  => array( 'Sposta', 'Rinomina' ),
	'Mycontributions'           => array( 'MieiContributi' ),
	'MyLanguage'                => array( 'MiaLingua' ),
	'Mypage'                    => array( 'MiaPaginaUtente', 'MiaPagina' ),
	'Mytalk'                    => array( 'MieDiscussioni' ),
	'Myuploads'                 => array( 'MieiUpload', 'MieiEdit' ),
	'Newimages'                 => array( 'ImmaginiRecenti' ),
	'Newpages'                  => array( 'PaginePiùRecenti' ),
	'PasswordReset'             => array( 'ReimpostaPassword' ),
	'PermanentLink'             => array( 'LinkPermanente' ),

	'Preferences'               => array( 'Preferenze' ),
	'Prefixindex'               => array( 'Prefissi' ),
	'Protectedpages'            => array( 'PagineProtette' ),
	'Protectedtitles'           => array( 'TitoliProtetti' ),
	'Randompage'                => array( 'PaginaCasuale' ),
	'RandomInCategory'          => array( 'CasualeInCategoria' ),
	'Randomredirect'            => array( 'RedirectCasuale' ),
	'Recentchanges'             => array( 'UltimeModifiche' ),
	'Recentchangeslinked'       => array( 'ModificheCorrelate' ),
	'Revisiondelete'            => array( 'CancellaRevisione' ),
	'Search'                    => array( 'Arriscedi', 'Cerca', 'Trova' ),
	'Shortpages'                => array( 'PaginePiùCorte' ),
	'Specialpages'              => array( 'PagineSpeciali' ),
	'Statistics'                => array( 'Statistiche' ),
	'Tags'                      => array( 'Etichette', 'Tag' ),
	'Unblock'                   => array( 'ElencoSblocchi', 'Sblocchi' ),
	'Uncategorizedcategories'   => array( 'CategorieSenzaCategorie' ),
	'Uncategorizedimages'       => array( 'ImmaginiSenzaCategorie' ),
	'Uncategorizedpages'        => array( 'PagineSenzaCategorie' ),
	'Uncategorizedtemplates'    => array( 'TemplateSenzaCategorie' ),
	'Undelete'                  => array( 'Ripristina' ),
	'Unlockdb'                  => array( 'SbloccaDB' ),
	'Unusedcategories'          => array( 'CategorieNonUsate', 'CategorieVuote' ),
	'Unusedimages'              => array( 'ImmaginiNonUsate' ),
	'Unusedtemplates'           => array( 'TemplateNonUsati' ),
	'Unwatchedpages'            => array( 'PagineNonOsservate' ),
	'Upload'                    => array( 'Carica' ),
	'Userlogin'                 => array( 'Entra' ),
	'Userlogout'                => array( 'Esci' ),
	'Userrights'                => array( 'PermessiUtente' ),
	'Version'                   => array( 'Versione' ),
	'Wantedcategories'          => array( 'CategorieRichieste' ),
	'Wantedfiles'               => array( 'FileRichiesti' ),
	'Wantedpages'               => array( 'PagineRichieste' ),
	'Wantedtemplates'           => array( 'TemplateRichiesti' ),
	'Watchlist'                 => array( 'OsservatiSpeciali' ),
	'Whatlinkshere'             => array( 'PuntanoQui' ),
	'Withoutinterwiki'          => array( 'PagineSenzaInterwiki' ),
);

