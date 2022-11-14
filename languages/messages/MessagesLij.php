<?php
/** Ligure (Ligure)
 *
 * @file
 * @ingroup Languages
 *
 * @author Dario vet
 * @author Dedee
 * @author Gastaz
 * @author Giromin Cangiaxo
 * @author Malafaya
 * @author Urhixidur
 * @author ZeneizeForesto
 */

$fallback = 'it';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speçiale',
	NS_TALK             => 'Discûscion',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discûscioîn_ûtente',
	NS_PROJECT_TALK     => 'Discûscioîn_$1',
	NS_FILE             => 'Immaggine',
	NS_FILE_TALK        => 'Discûscioîn_immaggine',
	NS_MEDIAWIKI_TALK   => 'Discûscioîn_MediaWiki',
	NS_TEMPLATE_TALK    => 'Discûscioîn_template',
	NS_HELP             => 'Agiûtto',
	NS_HELP_TALK        => 'Discûscioîn_agiûtto',
	NS_CATEGORY         => 'Categorîa',
	NS_CATEGORY_TALK    => 'Discûscioîn_categorîa',
];

$namespaceAliases = [
	'Speciale' => NS_SPECIAL,
	'Discussione' => NS_TALK,
	'Discussioni_utente' => NS_USER_TALK,
	'Discussioni_$1' => NS_PROJECT_TALK,
	'Immagine' => NS_FILE,
	'Discussioni_immagine' => NS_FILE_TALK,
	'Discussioni_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Discussioni_template' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussioni_aiuto' => NS_HELP_TALK,
	'Categoria' => NS_CATEGORY,
	'Discussioni_categoria' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Messaggi' ],
	'Allpages'                  => [ 'Tûtte_e_paggine' ],
	'Ancientpages'              => [ 'Paggine_meno_reçenti' ],
	'Block'                     => [ 'Blocca' ],
	'BlockList'                 => [ 'IP_bloccæ' ],
	'Booksources'               => [ 'RiçercaISBN' ],
	'Categories'                => [ 'Categorîe' ],
	'ChangePassword'            => [ 'Rimposta_paròlla_d\'ordine' ],
	'Confirmemail'              => [ 'Comferma_l\'e-mail' ],
	'Contributions'             => [ 'Contribûti' ],
	'Deadendpages'              => [ 'Paggine_sensa_sciortîa' ],
	'Emailuser'                 => [ 'Mandighe_\'n\'e-mail' ],
	'Export'                    => [ 'Esporta' ],
	'Fewestrevisions'           => [ 'Paggine_con_meno_revixoîn' ],
	'Import'                    => [ 'Importa' ],
	'Listadmins'                => [ 'Amministratoî' ],
	'Listbots'                  => [ 'Bot' ],
	'Listfiles'                 => [ 'Immaggini' ],
	'Listredirects'             => [ 'Rediression' ],
	'Listusers'                 => [ 'Utenti' ],
	'Lockdb'                    => [ 'BloccaDB' ],
	'Log'                       => [ 'Registri', 'Registro' ],
	'Lonelypages'               => [ 'Paggine_orfane' ],
	'Longpages'                 => [ 'Paggine_ciû_longhe' ],
	'MIMEsearch'                => [ 'RiçercaMIME' ],
	'Mostcategories'            => [ 'Paggine_con_ciû_categorîe' ],
	'Mostimages'                => [ 'Immaggini_ciû_domandæ' ],
	'Mostlinked'                => [ 'Paggine_ciû_domandæ' ],
	'Mostlinkedcategories'      => [ 'Categorîe_ciû_domandæ' ],
	'Mostlinkedtemplates'       => [ 'Template_ciû_domandæ' ],
	'Mostrevisions'             => [ 'Paggine_con_ciû_revixoîn' ],
	'Movepage'                  => [ 'Sposta' ],
	'Mycontributions'           => [ 'Mæ_Contribûti' ],
	'Mypage'                    => [ 'Mæ_Paggina_Utente' ],
	'Mytalk'                    => [ 'Mæ_Discûscioîn' ],
	'Newimages'                 => [ 'Immaggini_reçenti' ],
	'Newpages'                  => [ 'Paggine_ciû_reçenti' ],
	'Preferences'               => [ 'Preferense' ],
	'Prefixindex'               => [ 'Prefisci' ],
	'Protectedpages'            => [ 'Paggine_protezûe' ],
	'Protectedtitles'           => [ 'Tittoli_protezûi' ],
	'Randompage'                => [ 'Paggina_a_brettio' ],
	'Randomredirect'            => [ 'Rediression_a_brettio' ],
	'Recentchanges'             => [ 'Ûrtime_modiffiche' ],
	'Recentchangeslinked'       => [ 'Modiffiche_correlæ' ],
	'Revisiondelete'            => [ 'Scassa_revixon' ],
	'Search'                    => [ 'Riçerca', 'Çerca' ],
	'Shortpages'                => [ 'Paggine_ciû_cûrte' ],
	'Specialpages'              => [ 'Paggine_speçiali' ],
	'Statistics'                => [ 'Statistighe' ],
	'Uncategorizedcategories'   => [ 'Categorîe_sensa_categorîa' ],
	'Uncategorizedimages'       => [ 'Immaggini_sensa_categorîa' ],
	'Uncategorizedpages'        => [ 'Paggine_sensa_categorîa' ],
	'Uncategorizedtemplates'    => [ 'Template_sensa_categorîa' ],
	'Unlockdb'                  => [ 'SbloccaDB' ],
	'Unusedcategories'          => [ 'Categorîe_sensa_ûso' ],
	'Unusedimages'              => [ 'Immaggini_sensa_ûso' ],
	'Unusedtemplates'           => [ 'Template_sensa_ûso' ],
	'Unwatchedpages'            => [ 'Paggine_no_osservæ' ],
	'Upload'                    => [ 'Carrega' ],
	'Userlogin'                 => [ 'Intra', 'Registrate' ],
	'Userlogout'                => [ 'Sciorti' ],
	'Userrights'                => [ 'Permissi_utente' ],
	'Version'                   => [ 'Verscion' ],
	'Wantedcategories'          => [ 'Categorîe_domandæ' ],
	'Wantedpages'               => [ 'Paggine_domandæ' ],
	'Watchlist'                 => [ 'Osservæ_speçiali' ],
	'Whatlinkshere'             => [ 'Cose_appunta_chì' ],
	'Withoutinterwiki'          => [ 'Sensa_Interwiki' ],
];
