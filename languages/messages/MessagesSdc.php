<?php
/** Sassaresu (Sassaresu)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Antofa
 * @author Cornelia
 * @author Felis
 * @author Jun Misugi
 * @author Kaganer
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Ippiziari',
	NS_TALK             => 'Dischussioni',
	NS_USER             => 'Utenti',
	NS_USER_TALK        => 'Dischussioni_utenti',
	NS_PROJECT_TALK     => 'Dischussioni_$1',
	NS_FILE             => 'Immagina',
	NS_FILE_TALK        => 'Dischussioni_immagina',
	NS_MEDIAWIKI_TALK   => 'Dischussioni_MediaWiki',
	NS_TEMPLATE         => 'Mudellu',
	NS_TEMPLATE_TALK    => 'Dischussioni_mudellu',
	NS_HELP             => 'Aggiuddu',
	NS_HELP_TALK        => 'Dischussioni_aggiuddu',
	NS_CATEGORY         => 'Categuria',
	NS_CATEGORY_TALK    => 'Dischussioni_categuria',
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Imbasciaddi' ),
	'Allpages'                  => array( 'TuttiLiPàgini' ),
	'Ancientpages'              => array( 'PàginiMancuRizzenti' ),
	'Block'                     => array( 'Brocca' ),
	'Booksources'               => array( 'ZirchaISBN' ),
	'BrokenRedirects'           => array( 'RinviiIbbagliaddi' ),
	'Categories'                => array( 'Categuri' ),
	'ChangePassword'            => array( 'RimpusthàParàuraDÓrdhini' ),
	'Contributions'             => array( 'Cuntributi', 'CuntributiUtente' ),
	'Deadendpages'              => array( 'PàginiChenaIscidda' ),
	'DoubleRedirects'           => array( 'RinviiDoppi' ),
	'Emailuser'                 => array( 'InviaPosthaErettrònica' ),
	'Export'                    => array( 'Ippurtha' ),
	'Fewestrevisions'           => array( 'PàginiCunMancuRibisioni' ),
	'Import'                    => array( 'Impurtha' ),
	'BlockList'                 => array( 'IPBroccaddi' ),
	'Listadmins'                => array( 'Amministhradori' ),
	'Listfiles'                 => array( 'Immagini' ),
	'Listredirects'             => array( 'Rinvii' ),
	'Listusers'                 => array( 'Utenti', 'ErencuUtenti' ),
	'Lockdb'                    => array( 'BroccaDB' ),
	'Log'                       => array( 'Rigisthru', 'Rigisthri', 'Registro', 'Registri' ),
	'Lonelypages'               => array( 'PàginaÒiffana' ),
	'Longpages'                 => array( 'PàginiPiùLonghi' ),
	'MIMEsearch'                => array( 'ZirchaMIME' ),
	'Mostcategories'            => array( 'PàginiCunPiùCateguri' ),
	'Mostimages'                => array( 'ImmaginiPiùRiciamaddi' ),
	'Mostlinked'                => array( 'PàginiPiùRiciamaddi' ),
	'Mostlinkedcategories'      => array( 'CateguriPiùRiciamaddi' ),
	'Mostlinkedtemplates'       => array( 'MudelliPiùRiciamaddi' ),
	'Mostrevisions'             => array( 'PàginiCunPiùRibisioni' ),
	'Movepage'                  => array( 'Ippustha', 'Rinumina' ),
	'Mycontributions'           => array( 'MéCuntributi' ),
	'Mypage'                    => array( 'MeaPàginaUtenti' ),
	'Mytalk'                    => array( 'MéDischussioni' ),
	'Newimages'                 => array( 'ImmaginiRizzenti' ),
	'Newpages'                  => array( 'PàginiPiùRizzenti' ),
	'Preferences'               => array( 'Prifirènzi' ),
	'Prefixindex'               => array( 'Prefissi' ),
	'Protectedpages'            => array( 'PàginiPrutiggiddi' ),
	'Randompage'                => array( 'PàginaCasuari' ),
	'Randomredirect'            => array( 'RinviuCasuari' ),
	'Recentchanges'             => array( 'UlthimiMudìfigghi' ),
	'Recentchangeslinked'       => array( 'MudìfigghiLiaddi' ),
	'Revisiondelete'            => array( 'CanzillaRibisioni' ),
	'Search'                    => array( 'Zircha', 'Ricerca' ),
	'Shortpages'                => array( 'PàginiPiùCorthi' ),
	'Specialpages'              => array( 'PàginiIppiziari' ),
	'Statistics'                => array( 'Sthatisthigghi' ),
	'Uncategorizedcategories'   => array( 'CateguriNòCategurizzaddi' ),
	'Uncategorizedimages'       => array( 'ImmaginiChenaCateguri' ),
	'Uncategorizedpages'        => array( 'PàginiChenaCateguri' ),
	'Uncategorizedtemplates'    => array( 'MudelliChenaCateguri' ),
	'Undelete'                  => array( 'TurraChePrimma' ),
	'Unlockdb'                  => array( 'IbbruccaDB' ),
	'Unusedcategories'          => array( 'CateguriInutirizaddi' ),
	'Unusedimages'              => array( 'FileInutirizaddi' ),
	'Unusedtemplates'           => array( 'MudelliInutirizaddi' ),
	'Unwatchedpages'            => array( 'PàginiNòAbbaidaddi' ),
	'Upload'                    => array( 'Carrigga' ),
	'Userlogin'                 => array( 'Intra', 'Accesso' ),
	'Userlogout'                => array( 'Isci', 'Uscita' ),
	'Userrights'                => array( 'PrimmissiUtenti' ),
	'Version'                   => array( 'Versioni' ),
	'Wantedcategories'          => array( 'CateguriDumandaddi' ),
	'Wantedpages'               => array( 'PàginiPiùDumandaddi' ),
	'Watchlist'                 => array( 'AbbaidaddiIppiziari' ),
	'Whatlinkshere'             => array( 'PuntaniInogghi' ),
	'Withoutinterwiki'          => array( 'PàginiChenaInterwiki' ),
);

