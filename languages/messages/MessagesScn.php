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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Discussioni_Utenti' => NS_USER_TALK,
	'Mmàggini' => NS_FILE,
	'Discussioni_mmàggini' => NS_FILE_TALK,
	'Discussioni_Template' => NS_TEMPLATE_TALK,
	'Discussioni_Aiutu' => NS_HELP_TALK,
	'Discussioni_Catigurìa' => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'UtentiAttivi' ],
	'Allmessages'               => [ 'Messaggi' ],
	'Allpages'                  => [ 'TutteLePagine' ],
	'Ancientpages'              => [ 'PagineMenoRecenti' ],
	'Badtitle'                  => [ 'TitoloErrato' ],
	'Blankpage'                 => [ 'PaginaVuota' ],
	'Block'                     => [ 'Blocca' ],
	'Booksources'               => [ 'RicercaISBN' ],
	'BrokenRedirects'           => [ 'RedirectErrati' ],
	'Categories'                => [ 'Categorie' ],
	'ChangeEmail'               => [ 'CambiaEmail' ],
	'ChangePassword'            => [ 'CambiaPassword' ],
	'ComparePages'              => [ 'ComparaPagine' ],
	'Confirmemail'              => [ 'ConfermaEMail' ],
	'Contributions'             => [ 'Contributi', 'ContributiUtente', 'Edit' ],
	'CreateAccount'             => [ 'CreaAccount' ],
	'Deadendpages'              => [ 'PagineSenzaUscita' ],
	'DeletedContributions'      => [ 'ContributiCancellati' ],
	'DoubleRedirects'           => [ 'RedirectDoppi' ],
	'EditWatchlist'             => [ 'ModificaOsservati', 'ModificaOsservatiSpeciali', 'ModificaListaSeguiti' ],
	'Emailuser'                 => [ 'InviaEMail' ],
	'ExpandTemplates'           => [ 'EspandiTemplate' ],
	'Export'                    => [ 'Esporta' ],
	'Fewestrevisions'           => [ 'PagineConMenoRevisioni' ],
	'FileDuplicateSearch'       => [ 'CercaFileDuplicati' ],
	'Filepath'                  => [ 'Percorso' ],
	'Import'                    => [ 'Importa' ],
	'Invalidateemail'           => [ 'InvalidaEMail' ],
	'JavaScriptTest'            => [ 'TestJavaScript' ],
	'BlockList'                 => [ 'IPBloccati', 'ElencoBlocchi', 'Blocchi' ],
	'LinkSearch'                => [ 'CercaCollegamenti', 'CercaLink' ],
	'Listadmins'                => [ 'Amministratori', 'ElencoAmministratori', 'Admin', 'Sysop', 'Cricca' ],
	'Listbots'                  => [ 'Bot', 'ElencoBot' ],
	'Listfiles'                 => [ 'File', 'Immagini' ],
	'Listgrouprights'           => [ 'ElencoPermessiGruppi', 'Privilegi' ],
	'Listredirects'             => [ 'Redirect', 'ElencoRedirect' ],
	'Listusers'                 => [ 'Utenti', 'ElencoUtenti' ],
	'Lockdb'                    => [ 'BloccaDB' ],
	'Log'                       => [ 'Registri', 'Registro' ],
	'Lonelypages'               => [ 'PagineOrfane' ],
	'Longpages'                 => [ 'PaginePiùLunghe' ],
	'MergeHistory'              => [ 'FondiCronologia', 'UnificaCronologia' ],
	'MIMEsearch'                => [ 'RicercaMIME' ],
	'Mostcategories'            => [ 'PagineConPiùCategorie' ],
	'Mostimages'                => [ 'ImmaginiPiùRichiamate' ],
	'Mostinterwikis'            => [ 'InterwikiPiùRichiamati' ],
	'Mostlinked'                => [ 'PaginePiùRichiamate' ],
	'Mostlinkedcategories'      => [ 'CategoriePiùRichiamate' ],
	'Mostlinkedtemplates'       => [ 'TemplatePiùRichiamati' ],
	'Mostrevisions'             => [ 'PagineConPiùRevisioni' ],
	'Movepage'                  => [ 'Sposta', 'Rinomina' ],
	'Mycontributions'           => [ 'MieiContributi' ],
	'MyLanguage'                => [ 'MiaLingua' ],
	'Mypage'                    => [ 'MiaPaginaUtente', 'MiaPagina' ],
	'Mytalk'                    => [ 'MieDiscussioni' ],
	'Myuploads'                 => [ 'MieiUpload', 'MieiEdit' ],
	'Newimages'                 => [ 'ImmaginiRecenti' ],
	'Newpages'                  => [ 'PaginePiùRecenti' ],
	'PasswordReset'             => [ 'ReimpostaPassword' ],
	'PermanentLink'             => [ 'LinkPermanente' ],
	'Preferences'               => [ 'Preferenze' ],
	'Prefixindex'               => [ 'Prefissi' ],
	'Protectedpages'            => [ 'PagineProtette' ],
	'Protectedtitles'           => [ 'TitoliProtetti' ],
	'Randompage'                => [ 'PaginaCasuale' ],
	'RandomInCategory'          => [ 'CasualeInCategoria' ],
	'Randomredirect'            => [ 'RedirectCasuale' ],
	'Recentchanges'             => [ 'UltimeModifiche' ],
	'Recentchangeslinked'       => [ 'ModificheCorrelate' ],
	'Revisiondelete'            => [ 'CancellaRevisione' ],
	'Search'                    => [ 'Arriscedi', 'Cerca', 'Trova' ],
	'Shortpages'                => [ 'PaginePiùCorte' ],
	'Specialpages'              => [ 'PagineSpeciali' ],
	'Statistics'                => [ 'Statistiche' ],
	'Tags'                      => [ 'Etichette', 'Tag' ],
	'Unblock'                   => [ 'ElencoSblocchi', 'Sblocchi' ],
	'Uncategorizedcategories'   => [ 'CategorieSenzaCategorie' ],
	'Uncategorizedimages'       => [ 'ImmaginiSenzaCategorie' ],
	'Uncategorizedpages'        => [ 'PagineSenzaCategorie' ],
	'Uncategorizedtemplates'    => [ 'TemplateSenzaCategorie' ],
	'Undelete'                  => [ 'Ripristina' ],
	'Unlockdb'                  => [ 'SbloccaDB' ],
	'Unusedcategories'          => [ 'CategorieNonUsate', 'CategorieVuote' ],
	'Unusedimages'              => [ 'ImmaginiNonUsate' ],
	'Unusedtemplates'           => [ 'TemplateNonUsati' ],
	'Unwatchedpages'            => [ 'PagineNonOsservate' ],
	'Upload'                    => [ 'Carica' ],
	'Userlogin'                 => [ 'Entra' ],
	'Userlogout'                => [ 'Esci' ],
	'Userrights'                => [ 'PermessiUtente' ],
	'Version'                   => [ 'Versione' ],
	'Wantedcategories'          => [ 'CategorieRichieste' ],
	'Wantedfiles'               => [ 'FileRichiesti' ],
	'Wantedpages'               => [ 'PagineRichieste' ],
	'Wantedtemplates'           => [ 'TemplateRichiesti' ],
	'Watchlist'                 => [ 'OsservatiSpeciali' ],
	'Whatlinkshere'             => [ 'PuntanoQui' ],
	'Withoutinterwiki'          => [ 'PagineSenzaInterwiki' ],
];

