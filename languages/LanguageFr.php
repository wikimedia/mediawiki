<?php

// $Id$

// The names of the namespaces can be set here, but the numbers
// are magical, so don't change or move them!  The Namespace class
// encapsulates some of the magic-ness.
require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesFr = array(
	NS_MEDIA		=> "Media",
	NS_SPECIAL		=> "Special",
	NS_MAIN			=> "",
	NS_TALK			=> "Discuter",
	NS_USER			=> "Utilisateur",
	NS_USER_TALK		=> "Discussion_Utilisateur",
	NS_WIKIPEDIA		=> $wgMetaNamespace,
	NS_WIKIPEDIA_TALK	=> "Discussion_".$wgMetaNamespace,
	NS_IMAGE		=> "Image",
	NS_IMAGE_TALK		=> "Discussion_Image",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK	=> "Discussion_MediaWiki",
	NS_TEMPLATE		=> "Modèle",
	NS_TEMPLATE_TALK	=> "Discussion_Modèle",
	NS_HELP			=> "Aide",
	NS_HELP_TALK		=> "Discussion_Aide",
	NS_CATEGORY		=> "Catégorie",
	NS_CATEGORY_TALK	=> "Discussion_Catégorie"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFr = array(
	'Aucune', 'Gauche', 'Droite', 'Flottante à gauche'
);

/* private */ $wgSkinNamesFr = array(
	'standard'		=> 'Standard',
	'nostalgia'		=> 'Nostalgie',
	'cologneblue'	=> 'Cologne Blue',
	'smarty'		=> 'Paddington',
	'montparnasse'	=> 'Montparnasse',
	'davinci'		=> 'DaVinci',
	'mono'			=> 'Mono',
	'monobook'		=> 'MonoBook',
	'myskin'		=> 'MySkin'
);



/* private */ $wgBookstoreListFr = array(
	'Amazon.fr'		=> "http://www.amazon.fr/exec/obidos/ISBN=$1",
	'alapage.fr'	=> "http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&",
	'fnac.com'		=> "http://www3.fnac.com/advanced/book.do?isbn=$1",
	'chapitre.com'	=> "http://www.chapitre.com/frame_rec.asp?isbn=$1",
);


// All special pages have to be listed here: a description of ""
// will make them not show up on the "Special Pages" page, which
// is the right thing for some of them (such as the "targeted" ones).

/* private */ $wgValidSpecialPagesFr = array(
	'Userlogin'     => '',
	'Userlogout'    => '',
	'Preferences'   => 'Préférences',
	'Watchlist'     => 'Liste de suivi',
	'Recentchanges' => 'Modifications récentes',
	'Upload'        => 'Copier un fichier',
	'Imagelist'     => 'Liste des images',
	'Listusers'     => 'Liste des participants',
	'Statistics'    => 'Statistiques',
	'Randompage'    => 'Une page au hasard',

	'Lonelypages'   => 'Pages orphelines',
	'Unusedimages'  => 'Images orphelines',
	'Popularpages'  => 'Les plus populaires',
	'Wantedpages'   => 'Les plus demandées',
	'Shortpages'    => 'Articles courts',
	'Longpages'     => 'Articles longs',
	'Newpages'      => 'Nouvelles pages',
	'Ancientpages'	=> 'Anciennes pages',
	'Allpages'      => 'Toutes les pages',

	"Ipblocklist"   => "Adresses IP bloquées",
	"Maintenance"   => "Page de maintenance",
	"Specialpages"  => "", // ces pages doivent rester vides !
	"Contributions" => "",
	"Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"      => "",
	"Booksources"   => "Librairies en ligne",
	"Categories"	=> "Page des catégories",
	"Export"	=> "Exporter par XML",
	"Version"	=> "Version",
	"Allmessages"	=> "Messages système"
);

/* private */ $wgSysopSpecialPagesFr = array(
	"Blockip"       => "Bloquer une adresse IP",
	"Asksql"        => "Accès SQL",
	"Makesysop"		=> "Donner les droits d'administrateur",

	"Undelete"      => "Gérer les pages effacées",
	"Import"		=> "Importer une page avec l'historique"
);

/* private */ $wgDeveloperSpecialPagesFr = array(
	"Lockdb"        => "Bloquer la base de données",
	"Unlockdb"      => "Débloquer la base de données",
);

/* private */ $wgAllMessagesFr = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles

"tog-hover" => "Afficher des info-bulles sur les liens wiki",
"tog-underline" => "Liens soulignés",
"tog-highlightbroken" => "Liens vers les sujets non existants en rouge",
"tog-justify" => "Paragraphes justifiés",
"tog-hideminor" => "Cacher les <i>Modifications récentes</i> mineures",
"tog-usenewrc" => "Modifications récentes améliorées<br /> (certains navigateurs seulement)",
"tog-numberheadings" => "Numérotation automatique des titres",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "Double cliquer pour éditer une page (JavaScript)",
"tog-editsection"	=> "Éditer une section via les liens [éditer]",
"tog-editsectiononrightclick"	=> "Éditer une section en cliquant à droite<br /> sur le titre de la section",
"tog-showtoc"	=> "Afficher la table des matières<br /> (pour les articles ayant plus de 3 sections)",
"tog-rememberpassword" => "Se souvenir de mon mot de passe (cookie)",
"tog-editwidth" => "La fenêtre d'édition s'affiche en pleine largeur",
"tog-watchdefault" => "Suivre les articles que je crée ou modifie",
"tog-minordefault" => "Mes modifications sont considérées<br /> comme mineures par défaut",
"tog-previewontop" => "La prévisualisation s'affiche au<br /> dessus de la boite de rédaction",
"tog-nocache" => "Désactiver le cache des pages",
# Dates

'sunday' => "dimanche",
'monday' => "lundi",
'tuesday' => "mardi",
'wednesday' => "mercredi",
'thursday' => "jeudi",
'friday' => "vendredi",
'saturday' => "samedi",
'january' => "janvier",
'february' => "février",
'march' => "mars",
'april' => "avril",
'may_long' => "mai",
'june' => "juin",
'july' => "juillet",
'august' => "août",
'september' => "septembre",
'october' => "octobre",
'november' => "novembre",
'december' => "décembre",
'jan' => "jan",
'feb' => "fév",
'mar' => "mar",
'apr' => "avr",
'may' => "mai",
'jun' => "jun",
'jul' => "jul",
'aug' => "aoû",
'sep' => "sep",
'oct' => "oct",
'nov' => "nov",
'dec' => "déc",


# Bits of text used by many pages:
#
"categories"	=> "Catégories de la page", // Looxix "Page categories",
"category"	=> "catégorie",			// Looxix "category",
"category_header" => "Articles dans la catégorie \"$1\"", // Looxix "Articles in category \"$1\"",
"subcategories"	=> "Sous-catégories", // Looxix "Subcategories",


"linktrail"     => "/^([a-zàâçéèêîôû]+)(.*)\$/sD",
"mainpage"      => "Accueil",
"mainpagetext"	=> "Logiciel {{SITENAME}} installé.",
"portal"	=> "Accueil communauté",
"portal-url"	=> "{{ns:4}}:Accueil",
"about"         => "À propos",
"aboutsite"      => "À propos de {{SITENAME}}",
"aboutpage"     => "{{ns:4}}:À propos",
"article"	=> "Article",
"help"          => "Aide",
"helppage"      => "{{ns:4}}:Aide",
"wikititlesuffix" => "{{SITENAME}}",
"bugreports"    => "Rapport d'erreurs",
"bugreportspage" => "{{ns:4}}:Rapport d'erreurs",
"sitesupport"	=> "Participer en faisant un don",
"sitesupportpage"	=> "{{ns:4}}:Dons",
"faq"           => "FAQ",
"faqpage"       => "{{ns:4}}:FAQ",
"edithelp"      => "Aide",
"edithelppage"  => "{{ns:4}}:Comment éditer une page",
"cancel"        => "Annuler",
"qbfind"        => "Rechercher",
"qbbrowse"      => "Défiler",
"qbedit"        => "Éditer",
"qbpageoptions" => "Page d'option",
"qbpageinfo"    => "Page d'information",
"qbmyoptions"   => "Mes options",
"qbspecialpages"	=> "Pages spéciales",
"moredotdotdot"	=> "Et plus...",
"mypage"        => "Ma page",
"mytalk"        => "Ma page de discussion",
"anontalk"	=> "Discussion avec cette adresse ip",
"navigation"	=> "Navigation",
"currentevents" => "Actualités",
"disclaimers"	=> "Avertissements",
"disclaimerpage" => "{{ns:4}}:Avertissements généraux",
"errorpagetitle" => "Erreur",
"returnto"      => "Revenir à la page $1.",
"tagline"       => "Un article de {{SITENAME}}, l'encyclopéde libre.",
"whatlinkshere" => "Références à cette page",
"help"          => "Aide",
"search"        => "Rechercher",
"history"       => "Historique",
"printableversion" => "Version imprimable",
'edit'		=> 'éditer',
"editthispage"  => "Modifier cette page",
'delete'	=> 'supprimer',
'deletethispage' => 'Supprimer cette page',
'undelete_short' => 'Restaurer',
'protect' => 'Protéger',
'protectthispage' => 'Protéger cette page',
"unprotect" => "Déprotéger",
"unprotectthispage" => "Déprotéger cette page",
"newpage"       => "Nouvelle page",
"talkpage"      => "Page de discussion",
'specialpage'	=> 'Page Spéciale',
'personaltools'	=> 'Outils personels',
"postcomment"	=> "Ajouter un commentaire",
'addsection'   => '+',
"articlepage"	=> "Voir l'article",
"subjectpage"   => "Page sujet",
'talk'		=> 'Discussion',
'toolbox'	=> 'Boîte à outils',
"userpage"      => "Page utilisateur",
"wikipediapage" => "Page méta",
"imagepage"     => "Page image",
"viewtalkpage"  => "Page de discussion",
"otherlanguages" => "Autres langues",
"redirectedfrom" => "(Redirigé depuis $1)",
"lastmodified"  => "Dernière modification de cette page : $1.",
"viewcount"     => "Cette page a été consultée $1 fois.",
'copyright'	=> 'Contenu disponible sous $1.',
"gnunote"       => "Tous les textes sont disponibles sous les termes de la <a href='/wiki/GFDL'>Licence de documentation libre GNU</a>.",
"printsubtitle" => "(de http://$wgServer)",
"protectedpage" => "Page protégée",
"administrators" => "{{ns:4}}:Administrateurs",
"sysoptitle"    => "Accès administrateur requis",
"sysoptext"     => "L'action que vous avez tentée ne peut être effectuée que par un utilisateur ayant le statut d'\"administrateur\".
Voir $1.",
"developertitle" => "Accès développeur requis",
"developertext" => "L'action que vous avez tentée ne peut être effectuée que par un utilisateur ayant le statut de \"développeur\".
Voir $1.",
"bureaucrattitle"	=> "Un accès de 'Bureaucrate' est requis",
"bureaucrattext"	=> "Cette action ne peut être réalisée que par des administrateurs ayant le statut de 'Bureaucrate'.",
"nbytes"        => "$1 octets",
"go"            => "Consulter",
"ok"            => "OK",
'pagetitle'	=> '$1 - {{SITENAME}}',
"history"	=> "Historique de la page",
"history_short" => "Historique",
"sitetitle"     => "{{SITENAME}}",
"sitesubtitle"  => "L'encyclopédie libre",
"retrievedfrom" => "Récupérée de \"$1\"",
"newmessages"   => "Vous avez des $1.",
"newmessageslink" => "nouveaux messages",
"editsection"	=> "modifier",
"toc"		=> "Sommaire",
"showtoc"	=> "montrer",
"hidetoc"	=> "cacher",
"thisisdeleted" => "Afficher ou restaurer $1?",
"restorelink"	=> "$1 modifications effacées",
'feedlinks'	=> 'Flux:',
'sitenotice'	=> '', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Article',
'nstab-user' => 'Page utilisateur',
'nstab-media' => 'Média',
'nstab-special' => 'Spécial',
'nstab-wp' => 'A propos',
'nstab-image' => 'Image',
'nstab-mediawiki' => 'Message',
'nstab-template' => 'Modèle',
'nstab-help' => 'Aide',
'nstab-category' => 'Catégorie',

# Main script and global functions
#
"nosuchaction"	=> "Action inconnue",
"nosuchactiontext" => "L'action spécifiée dans l'Url n'est pas reconnue par le wiki.",
"nosuchspecialpage" => "Page spéciale inexistante",
"nospecialpagetext" => "Vous avez demandé une page spéciale qui n'est pas reconnue par le wiki.",

# General errors
#
"error"		=> "Erreur",
"databaseerror" => "Erreur base de données",
"dberrortext"	=> "Erreur de syntaxe dans la base de données. La dernière requête traitée par la base de données était :
<blockquote><tt>$1</tt></blockquote>
depuis la fonction \"<tt>$2</tt>\".
MySQL a renvoyé l'erreur \"<tt>$3: $4</tt>\".",
"noconnect"	=> "Désolé! Suite à des problèmes techniques, il est impossible de se connecter à la base de données pour le moment.", //"Connexion impossible à la base de données sur $1",
"nodb"		=> "Sélection impossible de la base de données $1",
"cachederror"	=> "Ceci est une copie de la page demandée et peut ne pas être à jour",
"readonly"	=> "Mises à jour bloquées sur la base de données",
"enterlockreason" => "Indiquez la raison du blocage, ainsi qu'une estimation de la durée de blocage ",
"readonlytext"	=> "Les ajouts et mises à jour sur la base de donnée {{SITENAME}} sont actuellement bloqués, probablement pour permettre la maintenance de la base, après quoi, tout rentrera dans l'ordre. Voici la raison pour laquelle l'administrateur a bloqué la base :
<p>$1",
"missingarticle" => "La base de données n'a pas pu trouver le texte d'une page existante, dont le titre est \"$1\".
Ce n'est pas une erreur de la base de données, mais plus probablement un bogue du wiki.
Veuillez rapporter cette erreur à un administrateur, en lui indiquant l'adresse de la page fautive.",
"internalerror" => "Erreur interne",
"filecopyerror" => "Impossible de copier \"$1\" vers \"$2\".",
"filerenameerror" => "Impossible de renommer \"$1\" en \"$2\".",
"filedeleteerror" => "Impossible de supprimer \"$1\".",
"filenotfound"	=> "Fichier \"$1\" introuvable.",
"unexpected"	=> "Valeur inattendue : \"$1\"=\"$2\".",
"formerror"	=> "Erreur: Impossible de soumettre le formulaire",
"badarticleerror" => "Cette action ne peut pas être effectuée sur cette page.",
"cannotdelete"	=> "Impossible de supprimer la page ou l'image indiquée.",
"badtitle"	=> "Mauvais titre",
"badtitletext"	=> "Le titre de la page demandée est invalide, vide ou le lien interlangue est invalide",
"perfdisabled" => "Désolé! Cette fonctionnalité est temporairement désactivée
car elle ralentit la base de données à un point tel que plus personne
ne peut utiliser le wiki.",
"perfdisabledsub" => "Ceci est une copie de sauvegarde de $1:",
"viewsource"	=> "Voir le texte source",
"protectedtext"	=> "Cette page a été bloquée pour empêcher sa modification. Consulter $wgSitename:Page protégée]] pour voir les différentes raisons possibles.",

# Login and logout pages
#
"logouttitle"	=> "Déconnexion",
"logouttext"	=> "Vous êtes à présent déconnecté(e).
Vous pouvez continuer à utiliser {{SITENAME}} de façon anonyme, ou vous reconnecter, éventuellement sous un autre nom.\n",

"welcomecreation" => "<h2>Bienvenue, $1!</h2><p>Votre compte d'utilisateur a été créé.
N'oubliez pas de personnaliser votre {{SITENAME}} en consultant la page Préférences.",

"loginpagetitle"     => "Votre identifiant",
"yourname"           => "Votre nom d'utilisateur",
"yourpassword"       => "Votre mot de passe",
"yourpasswordagain"  => "Entrez à nouveau votre mot de passe",
"newusersonly"       => " (nouveaux utilisateurs uniquement)",
"remembermypassword" => "Se souvenir de mon mot de passe (cookie)",
"loginproblem"       => "<b>Problème d'identification.</b><br />Essayez à nouveau !",
"alreadyloggedin"    => "<font color=red><b>Utilisateur $1, vous êtes déjà identifié!</b></font><br />\n",

'login'         => 'Identification',
'loginprompt'	=> 'Vous devez activer les cookies pour vous connecter à {{SITENAME}}.',
"userlogin"     => "Identification",
"logout"        => "Déconnexion",
"userlogout"    => "Déconnexion",
"notloggedin"	=> "Non connecté",
"createaccount" => "Créer un nouveau compte",
"createaccountmail"	=> "par courriel", // Looxix "by eMail",
"badretype"     => "Les deux mots de passe que vous avez saisis ne sont pas identiques.",
"userexists"    => "Le nom d'utilisateur que vous avez saisi est déjà utilisé. Veuillez en choisir un autre.",
"youremail"     => "Mon adresse électronique",
"yournick"      => "Mon surnom (pour les signatures)",
"yourrealname"	=> "Votre nom réél*",
"emailforlost"  => "Si vous égarez votre mot de passe, vous pouvez demander à ce qu'un nouveau vous soit envoyé à votre adresse électronique.",
"loginerror"    => "Problème d'identification",
"nocookiesnew"	=> "Le compte utilisateur a été créé, mais vous n'êtes pas connecté. {{SITENAME}} utilise des cookies pour la connexion mais vous avez les cookies désactives. Merci de les activer et de vous reconnecter.",
"nocookieslogin" => "{{SITENAME}} utilise des cookies pour la connexion mais vous avez les cookies désactives. Merci de les activer et de vous reconnecter.",
"noname"        => "Vous n'avez pas saisi de nom d'utilisateur.",
"loginsuccesstitle" => "Identification réussie.",
"loginsuccess"  => "Vous êtes actuellement connecté sur {{SITENAME}} en tant que \"$1\".",
"nosuchuser"    => "L'utilisateur \"$1\" n'existe pas.
Vérifiez que vous avez bien orthographié le nom, ou utilisez le formulaire ci-dessous pour créer un nouveau compte utilisateur.",
"wrongpassword" => "Le mot de passe est incorrect. Essayez à nouveau.",
"mailmypassword" => "Envoyez-moi un nouveau mot de passe",
"passwordremindertitle" => "Votre nouveau mot de passe sur {{SITENAME}}",
"passwordremindertext" => "Quelqu'un (probablement vous) ayant l'adresse IP $1 a demandé à ce qu'un nouveau mot de passe vous soit envoyé pour votre accès au wiki.
Le mot de passe de l'utilisateur \"$2\" est à présent \"$3\".
Nous vous conseillons de vous connecter et de modifier ce mot de passe dès que possible.",
"noemail"  => "Aucune adresse électronique n'a été enregistrée pour l'utilisateur \"$1\".",
"passwordsent" => "Un nouveau mot de passe a été envoyé à l'adresse électronique de l'utilisateur \"$1\".
Veuillez vous identifier dès que vous l'aurez reçu.",
'loginend'	=> '&nbsp;',
'mailerror'	=> 'Erreur lors de l\'envoi du mail: $1',
'acct_creation_throttle_hit' => 'Désolé, vous avez déjà créé $1 compte(s). Vous ne pouvez pas en créer de nouveaux.',

# Edit page toolbar
"bold_sample"=>"Texte gras",
"bold_tip"=>"Texte gras",
"italic_sample"=>"Texte italique",
"italic_tip"=>"Texte italique",
"link_sample"=>"Lien titre",
"link_tip"=>"Lien interne",
"extlink_sample"=>"http://www.example.com lien titre",
"extlink_tip"=>"Lien externe (n'oubliez pas http://)",
"headline_sample"=>"Texte de sous-titre",
"headline_tip"=>"Sous-titre niveau 2",
"math_sample"=>"Entrez votre formule ici",
"math_tip"=>"Formule mathématique (LaTeX)",
"nowiki_sample"=>"Entrez le texte non formatté ici",
"nowiki_tip"=>"Ignorer la syntaxe wiki",
"image_sample"=>"Exemple.jpg",
"image_tip"=>"Image insérée",
"media_sample"=>"Exemple.ogg",
"media_tip"=>"Lien fichier média",
"sig_tip"=>"Votre signature avec la date",
"hr_tip"=>"Lien horizontale (ne pas en abuser)",
"infobox"=>"Cliquez ce bouton pour avoir un exemple de texte",
"infobox_alert"	=> "Veuillez entrer le texte que vous voulez formater.\\n Il sera affiché dans la boîte pour être copié et collé.\\nExemple\\n$1\\ndeviendra:\\n$2",

# Edit pages
#
"summary"      => "Résumé",
"subject"	=> "Sujet/titre",
"minoredit"    => "Modification mineure.",
"watchthis"    => "Suivre cet article",
"savearticle"  => "Sauvegarder",
"preview"      => "Prévisualiser",
"showpreview"  => "Prévisualisation",
"blockedtitle" => "Utilisateur bloqué",
"blockedtext"  => "Votre compte utilisateur ou votre adresse IP ont été bloqués par $1 pour la raison suivante :<br />$2<p>Vous pouvez contacter $1 ou un des autres [[{{ns:4}}:Administrateurs|administateurs]] pour en discuter.",
"whitelistedittitle" => "Login requis pour rédiger",
"whitelistedittext" => "Vous devez être [[Special:Userlogin|connecté]] pour pouvoir rédiger",
"whitelistreadtitle" => "Login requis pour lire",
"whitelistreadtext" => "Vous devez être [[Special:Userlogin|connecté]] pour pouvoir lire les articles",
"whitelistacctitle" => "Vous n'êtes pas autorisé à créer un compte",
"whitelistacctext" => "Pour pouvoir créer un compte sur ce Wiki vous devez être [[Special:Userlogin|connecté]] et avoir les permissions appropriées", // Looxix 
'loginreqtitle'	=> 'Nom d\'utilisateur nécessaire',
'loginreqtext'	=> "Vous devez vous [[Special:Userlogin|connecter]] pour voir les autres pages.",
"accmailtitle" => "Mot de passe envoyé.",
"accmailtext" => "Le mot de passe de « $1 » a été envoyé à $2.",

"newarticle"   => "(Nouveau)",
"newarticletext" => "Saisissez ici le texte de votre article.",
"anontalkpagetext" => "---- ''Ceci est la page de discussion pour un utilisateur anonyme qui n'a pas encore créé un compte ou qui ne l'utilise pas. Pour cette raison, nous devons utiliser l'[[adresse IP]] numérique pour l'identifier. Une adresse de ce type peut être partagée entre plusieurs utilisateurs. Si vous êtes un utilisateur anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont été adressés, vous pouvez [[Special:Userlogin|créer un compte ou vous connecter]] afin d'éviter toute future confusion à l'avenir.", 
"noarticletext" => "(Il n'y a pour l'instant aucun texte sur cette page)",
"updated"      => "(Mis à jour)",
"note"         => "<strong>Note :</strong> ",
"previewnote"  => "Attention, ce texte n'est qu'une prévisualisation et n'a pas encore été sauvegardé!",
"previewconflict" => "La prévisualisation montre le texte de cette page tel qu'il apparaîtra une fois sauvegardé.",
"editing"      => "modification de $1",
"sectionedit"	=> " (section)",
"commentedit"	=> " (commentaire)",
"editconflict" => "Conflit de modification : $1",
"explainconflict" => "<b>Cette page a été sauvegardée après que vous avez commencé à la modifier.
La zone d'édition supérieure contient le texte tel qu'il est enregistré actuellement dans la base de données. Vos modifications apparaissent dans la zone d'édition inférieure. Vous allez devoir apporter vos modifications au texte existant. Seul le texte de la zone supérieure sera sauvegardé.\n<p>",
"yourtext"     => "Votre texte",
"storedversion" => "Version enregistrée",
"editingold"   => "<strong>Attention : vous êtes en train de modifier une version obsolète de cette page. Si vous sauvegardez, toutes les modifications effectuées depuis cette version seront perdues.</strong>\n",
"yourdiff"  => "Différences",
"copyrightwarning" => "Toutes les contributions à {{SITENAME}} sont considérées comme publiées sous les termes de la GNU Free Documentation Licence, une licence de documentation libre (Voir $1 pour plus de détails). Si vous ne désirez pas que vos écrits soient édités et distribués à volonté, ne les envoyez pas. De même, merci de ne contribuer qu'en apportant vos propres écrits ou des écrits issus d'une source libre de droits. <b>N'UTILISEZ PAS DE TRAVAUX SOUS COPYRIGHT SANS AUTORISATION EXPRESSE!</b>",
"longpagewarning" => "AVERTISSEMENT : cette page a une longueur de $1 ko;
quelques navigateurs gèrent mal les pages approchant ou dépassant 32 ko lors de leur rédaction.
Peut-être serait-il mieux que vous divisiez la page en sections plus petites.",
"readonlywarning" => "AVERTISSEMENT : cette page a été bloquée pour maintenance,
vous ne pourrez donc pas sauvegarder vos modifications maintenant. Vous pouvez copier le texte dans un fichier et le sauver pour plus tard.",
"protectedpagewarning" => "AVERTISSEMENT : cette page a été bloquée.
Seuls les utilisateurs ayant le statut d'administrateur peuvent la modifier. Soyez certain que
vous suivez les <a href='$wgScript/{{ns:4}}:Page_protégée'>directives concernant les pages protégées</a>.",

# History pages
#
"revhistory"   => "Versions précédentes",
"nohistory"    => "Il n'existe pas d'historique pour cette page.",
"revnotfound"  => "Version introuvable",
"revnotfoundtext" => "La version précédente de cette page n'a pas pu être retrouvée. Vérifiez l'URL que vous avez utilisée pour accéder à cette page.\n",

"loadhist"     => "Chargement de l'historique de la page",
"currentrev"   => "Version actuelle",
"revisionasof" => "Version du $1",
"cur"    => "actu",
"next"   => "suiv",
"last"   => "dern",
"orig"   => "orig",
"histlegend" => "Légende : (actu) = différence avec la version actuelle ,
(dern) = différence avec la version précédente, M = modification mineure",

#  Diffs
#
"difference" => "(Différences entre les versions)",
"loadingrev" => "chargement de l'ancienne version pour comparaison",
"lineno"  => "Ligne $1:",
"editcurrent" => "Modifier la version actuelle de cette page",


# Search results
#
"searchresults" => "Résultat de la recherche",
"searchresulttext" => "Pour plus d'informations sur la recherche dans {{SITENAME}}, voir [[Project:Recherche|Chercher dans {{SITENAME}}]].",
"searchquery" => "Pour la requête \"$1\"",
"badquery"  => "Requête mal formulée",
"badquerytext" => "Nous n'avons pas pu traiter votre requête.
Vous avez probablement recherché un mot d'une longueur inférieure
à trois lettres, ce qui n'est pas encore possible. Vous avez
aussi pu faire une erreur de syntaxe, telle que \"poisson et
et écailles\".
Veuillez essayer une autre requête.",
"matchtotals" => "La requête \"$1\" correspond à $2 titre(s)
d'article et au texte de $3 article(s).",
"nogomatch" => "Aucune page avec ce titre n'existe, essai avec la recherche complète.",
"titlematches" => "Correspondances dans les titres",
"notitlematches" => "Aucun titre d'article ne contient le(s) mot(s) demandé(s)",
"textmatches" => "Correspondances dans les textes",
"notextmatches" => "Aucun texte d'article ne contient le(s) mot(s) demandé(s)",
"prevn"   => "$1 précédents",
"nextn"   => "$1 suivants",
"viewprevnext" => "Voir ($1) ($2) ($3).",
"showingresults" => "Affichage de <b>$1</b> résultats à partir du #<b>$2</b>.",
"showingresultsnum" => "Affichage de <b>$3</b> résultats à partir du #<b>$2</b>.",
"nonefound"  => "<strong>Note</strong>: l'absence de résultat est souvent due à l'emploi de termes de recherche trop courants, comme \"à\" ou \"de\",
qui ne sont pas indexés, ou à l'emploi de plusieurs termes de recherche (seules les pages
contenant tous les termes apparaissent dans les résultats).",
"powersearch" => "Recherche",
"powersearchtext" => "
Rechercher dans les espaces :<br />
$1<br />
$2 Inclure les page de redirections &nbsp; Rechercher $3 $9",
"searchdisabled" => "<p>La fonction de recherche sur l'entièreté du texte a été temporairement désactivée à cause de la grande charge que cela impose au serveur. Nous espérons la rétablir prochainement lorsque nous disposerons d'un serveur plus puissant. En attendant, vous pouvez faire la recherche avec Google:</p>
                                                                                                                                                        
",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br /><input type=radio
name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> {$wgServer} <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",
"blanknamespace" => "(Principal)",	// FIXME FvdP: trad de "(Main)"

# Preferences page
#
"preferences"       => "Préférences",
"prefsnologin"      => "Non connecté",
"prefsnologintext"  => "Vous devez être <a href=\"{{localurl:Special:Userlogin}}\">connecté</a>
pour modifier vos préférences d'utilisateur.",
"prefslogintext" => "Je suis connecté(e) en tant que $1 avec le numéro d'utilisateur $2.

Voir [[{{ns:4}}:Aide pour les préférences]] pour les explications concernant les options.",
"prefsreset"        => "Les préférences ont été rétablies à partir de la version enregistrée.",
"qbsettings"        => "Personnalisation de la barre outils",
'qbsettingsnote'	=> "Cette préférence ne fonctionne que pour les skins 'Standard' et 'CologneBlue'.",
"changepassword"    => "Modification du mot de passe",
"skin"              => "Apparence",
"math"				=> "Rendu des maths",	// Looxix "Rendering math",
"dateformat"		=> "Format de date",
"math_failure"		=> "Erreur math",	// Looxix "Failure toparse",
"math_unknown_error"	=> "erreur indéterminée",   // FvdP+Looxix "unknown error",
"math_unknown_function"	=> "fonction inconnue",
"math_lexing_error"	=> "erreur lexicale",   // Looxxi "lexing error",
"math_syntax_error"	=> "erreur de syntaxe",
"math_image_error"	=> "La conversion en PNG a échouée, vérifiez l'installation de Latex, dvips, gs et convert",
"math_bad_tmpdir"	=> "Ne peux pas crééer ou écrire dans le répertoire temporaire",
"math_bad_output"	=> "Ne peux pas crééer ou écrire dans le répertoire de sortie",
"math_notexvc"		=> "L'éxécutable 'texvc' est in trouvable. Lisez math/README pour le configurer.",
"saveprefs"         => "Enregistrer les préférences",
"resetprefs"        => "Rétablir les préférences",
"oldpassword"       => "Ancien mot de passe",
"newpassword"       => "Nouveau mot de passe",
"retypenew"         => "Confirmer le nouveau mot de passe",
"textboxsize"       => "Taille de la fenêtre d'édition",
"rows"              => "Rangées",
"columns"           => "Colonnes",
"searchresultshead" => "Affichage des résultats de recherche",
"resultsperpage"    => "Nombre de réponses par page",
"contextlines"      => "Nombre de lignes par réponse",
"contextchars"      => "Nombre de caractères de contexte par ligne",
"stubthreshold"     => "Taille minimale des articles courts",
"recentchangescount" => "Nombre de titres dans les modifications récentes",
"savedprefs"        => "Les préférences ont été sauvegardées.",
"timezonetext"      => "Si vous ne précisez pas de décalage horaire, c'est l'heure de l'Europe de l'ouest qui sera utilisée.",
"localtime"         => "Heure locale",
"timezoneoffset"    => "Décalage horaire",
"servertime"	    => "Heure du serveur",
"guesstimezone"     => "Utiliser la valeur du navigateur",
"emailflag"         => "Ne pas recevoir de courrier électronique<br /> des autres utilisateurs",
"defaultns"         => "Par défaut, rechercher dans ces espaces :",

# Recent changes
#
"changes"	=> "modifications",
"recentchanges" => "Modifications récentes",
"recentchangestext" => "Suivez sur cette page les dernières modifications de {{SITENAME}}.
[[{{ns:4}}:Bienvenue|Bienvenue]] aux nouveaux participants!
Jetez un coup d'&oelig;il sur ces pages&nbsp;: [[{{ns:4}}:FAQ|foire aux questions]],
[[{{ns:4}}:Recommandations et règles à suivre|recommandations et règles à suivre]]
(notamment [[{{ns:4}}:Règles de nommage|conventions de nommage]],
[[{{ns:4}}:La neutralité de point de vue|la neutralité de point de vue]]),
et [[{{ns:4}}:Les faux-pas les plus courants|les faux-pas les plus courants]].

Si vous voulez que {{SITENAME}} connaisse le succès, merci de ne pas y inclure pas de matériaux protégés par des [[{{ns:4}}:Copyright|copyrights]]. La responsabilité juridique pourrait en effet compromettre le projet. ",
"rcloaderr"  => "Chargement des dernières modifications",
"rcnote"  => "Voici les <strong>$1</strong> dernières modifications effectuées au cours des <strong>$2</strong> derniers jours.",
"rcnotefrom"	=> "Voici les modifications effectuées depuis le <strong>$2</strong> (<b>$1</b> au maximum).",
"rclistfrom"	=> "Afficher les nouvelles modifications depuis le $1.",
# "rclinks"  => "Afficher les $1 dernières modifications effectuées au cours des $2 dernières heures / $3 derniers jours",
# "rclinks"  => "Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours.",
"showhideminor" => "$1 modifications mineures | $2 robots | $3 utilisateurs enregistrés",
"rclinks"	=> "Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours; $3 modifications mineures.",	// Looxix
"rchide"  => "in $4 form; $1 modifications mineures; $2 espaces secondaires; $3 modifications multiples.", // FIXME
"rcliu"		=> "; $1 modifications par des contributeurs connectés",
"diff"            => "diff",
"hist"            => "hist",
"hide"            => "cacher",
"show"            => "montrer",
"tableform"       => "table",
"listform"        => "liste",
"nchanges"        => "$1 modification(s)",
"minoreditletter" => "M",
"newpageletter"   => "N",

# Upload
#
"upload"       => "Copier sur le serveur",
"uploadbtn"    => "Copier un fichier",
"uploadlink"   => "Copier des images",
"reupload"     => "Copier à nouveau",
"reuploaddesc" => "Retour au formulaire.",

"uploadnologin" => "Non connecté(e)",
"uploadnologintext" => "Vous devez être <a href=\"{{localurl:Special:Userlogin}}\">connecté</a>
pour copier des fichiers sur le serveur.",
"uploadfile"   => "Copier un fichier",
"uploaderror"  => "Erreur",
"uploadtext"   => "'''STOP !''' Avant de copier votre fichier sur le serveur,
prenez connaissance des [[Project:règles d'utilisation des images|règles d'utilisation des images]] de {{SITENAME}} et assurez-vous que vous les respectez.<br />
N'oubliez pas de remplir la [[Project:Page de description d'une image|page de description de l'image]] quand celle-ci sera sur le serveur.

Pour voir les images déjà placées sur le serveur ou pour effectuer une recherche parmi celles-ci,
allez à la [[Special:Imagelist|liste des images]].
Les uploads et les suppressions sont listés dans le [[Project:Journal_des_uploads|journal des uploads]].

Utilisez le formulaire ci-dessous pour copier sur le serveur de nouvelles images destinées à illustrer vos articles.
Sur la plupart des navigateurs, vous verrez un bouton \"Browse...\" qui ouvre la fenêtre de dialogue standard de votre système d'exploitation pour l'ouverture des fichiers.
Sélectionnez un fichier, son nom apparaîtra dans le champ situé à côté du bouton.
Vous devez également confirmer, en cochant la case prévue à cet effet, que la copie de ce fichier ne viole aucun copyright.
Cliquez sur le bouton \"Envoyer\" pour terminer.
Si votre connexion est lente, l'opération peut prendre un certain temps.

Les formats recommandés sont JPEG pour les photos, PNG
pour les dessins et les autres images, et OGG pour les fichiers sonores.
Donnez à vos fichiers des noms descriptifs clairs, afin d'éviter toute confusion.
Pour incorporer l'image dans un article, placez dans celui-ci un lien rédigé comme suit:
'''<nowiki>[[image:nom_du_fichier.jpg]]</nowiki>''' ou
'''<nowiki>[[image:nom_du_fichier.png|autre texte]]</nowiki>''' ou
'''<nowiki>[[media:nom_du_fichier.ogg]]</nowiki>''' pour les sons.

N'oubliez pas que, comme toutes les pages de {{SITENAME}}, les fichiers que vous copiez peuvent être modifiés ou supprimés par les autres utilisateurs s'ils estiment que cela est dans l'intérêt de l'encyclopédie. Sachez aussi que votre accès au serveur peut être bloqué si vous faites un mauvais usage du système.",
"uploadlog"  => "log d'upload",		// FIXME
"uploadlogpage" => "Log_d'upload",	// FIXME
"uploadlogpagetext" => "Voici la liste des derniers fichiers copiés sur le serveur.
L'heure indiquée est celle du serveur (UTC).
<ul>
</ul>
",
"filename"	=> "Nom",
"filedesc"	=> "Description",
"filestatus"	=> "Statut du copyright",
"filesource"	=> "Source",	
"affirmation"	=> "Je déclare que le détenteur du copyright de ce fichier accepte de le diffuser selon les termes de la $1.",
"copyrightpage" => "{{ns:4}}:Copyright",
"copyrightpagename" => "licence {{SITENAME}}",
"uploadedfiles" => "Fichiers copiés",
"noaffirmation" => "Vous devez confirmer que la copie de ce fichier ne viole aucun copyright.",
"ignorewarning" => "Ignorer l'avertissement et copier le fichier quand même.",
"minlength"	=> "Les noms des images doivent comporter au moins trois lettres.",
'illegalfilename'	=> 'Le fichier "$1" contient des caractères qui ne sont pas autorisés dans le titre d\'une page. Veuillez renommer le fichier et le réenvoyer.',
"badfilename" => "L'image a été renommée \"$1\".",
"badfiletype" => "\".$1\" n'est pas un format recommandé pour les fichiers images.",
"largefile"  => "La taille maximale conseillée pour les images est de 100Ko.",
"successfulupload" => "Copie réussie",
"fileuploaded" => "Le fichier \"$1\" a été copié sur le serveur.
Suivez ce lien: ($2) pour accéder à la page de description, et donner des informations sur le fichier, par exemple son origine, sa date de création, son auteur, ou tout autre renseignement en votre possession.",
"uploadwarning" => "Attention !",
"savefile"  => "Sauvegarder le fichier",
"uploadedimage" => " \"$1\" copié sur le serveur",
"uploaddisabled" => "Désolé, l'envoi de fichier est désactivé.",

# Image list
#
"imagelist"  => "Liste des images",
"imagelisttext" => "Voici une liste de $1 images classées $2.",
"getimagelist" => "Récupération de la liste des images",
"ilshowmatch" => "Afficher toutes les images dont le nom contient ",
"ilsubmit"  => "Chercher",
"showlast"  => "Afficher les $1 dernières images classées $2.",
"all"   => "toutes",
"byname"  => "par nom",
"bydate"  => "par date",
"bysize"  => "par taille",
"imgdelete"  => "suppr",
"imgdesc"  => "descr",
"imglegend"  => "Légende: (descr) = afficher/modifier la description de l'image.",
"imghistory" => "Historique de l'image",
"revertimg"  => "rétab",
"deleteimg"  => "suppr",
"deleteimgcompletely"  => "suppr",
"imghistlegend" => "Légende: (actu) = ceci est l'image actuelle, (suppr) = supprimer
cette ancienne version, (rétab) = rétablir cette ancienne version.
<br /><i>Cliquez sur la date pour voir l'image copiée à cette date</i>.",
"imagelinks" => "Liens vers l'image",
"linkstoimage" => "Les pages ci-dessous comportent un lien vers cette image:",
"nolinkstoimage" => "Aucune page ne comporte de lien vers cette image.",

# Statistics

"statistics" => "Statistiques",
"sitestats"  => "Statistiques du site",
"userstats"  => "Statistiques utilisateur",
"sitestatstext" => "La base de données contient actuellement <b>$1</b> pages.

Ce chiffre inclut les pages \"discussion\", les pages relatives à {{SITENAME}}, les pages minimales (\"bouchons\"),  les pages de redirection, ainsi que d'autres pages qui ne peuvent sans doute pas être considérées comme des articles.
Si l'on exclut ces pages, il reste <b>$2</b> pages qui sont probablement de véritables articles.<p>
<b>$3</b> pages ont été consultées et <b>$4</b> pages modifiées

depuis la mise à jour du logiciel (31 octobre 2002).
Cela représente une moyenne de <b>$5</b> modifications par page et de <b>$6</b> consultations pour une modification.",
"userstatstext" => "Il y a <b>$1</b> utilisateurs enregistrés.
Parmi ceux-ci, <b>$2</b> ont le statut d'administrateur (voir $3).",


# Maintenance Page
#
"maintenance"		=> "Page de maintenance",
"maintnancepagetext"	=> "Cette page inclut plusieurs utilitaires pour la maintenance quotidienne. Certains de ces outils ont tendance à charger la base de données; ne rechargez pas la page a chaque modification.",
"maintenancebacklink"	=> "Retour à la page de maintenance",
"disambiguations"	=> "Pages d'homonymie",
"disambiguationspage"	=> "{{ns:4}}:Liens_aux_pages_d'homonymie",
"disambiguationstext"	=> "Les articles suivants sont liés à une <i>page d'homonymie</i>. Or, ils devraient être liés au sujet.<br />Une page est considérée comme page d'homonymie si elle est liée à partir de $1.<br />Les liens à partir d'autres <i>espaces</i> ne sont pas pris en compte.",
"doubleredirects"	=> "Double redirection",
"doubleredirectstext"	=> "<b>Attention:</b> cette liste peut contenir des \"faux positifs\". Dans ce cas, c'est probablement la page du premier #REDIRECT contient aussi du texte.<br />Chaque ligne contient les liens à la 1re et 2e page de redirection, ainsi que la première ligne de cette dernière, qui donne normalement la \"vraie\" destination. Le premier #REDIRECT devrait lier vers cette destination.",
"brokenredirects"	=> "Redirections cassées", 
"brokenredirectstext"	=> "Ces redirections mènent a une page qui n'existe pas.",
"selflinks"		=> "Page avec un lien circulaire",
"selflinkstext"		=> "Les pages suivantes contiennent un lien vers elles-mêmes, ce qui n'est pas permis.",
"mispeelings"           => "Pages avec fautes d'orthographe",
"mispeelingstext"               => "Les pages suivantes contiennent une faute d'orthographe courante (la liste de celles-ci est sur $1). L'orthographe correcte est peut-être (ceci).",
"mispeelingspage"       => "Liste de fautes d'orthographe courantes",

# les 3 messages suivants ne sont plus utilisés (plus de page Special:Intl)
"missinglanguagelinks"  => "Liens inter-langues manquants",
"missinglanguagelinksbutton"    => "Je n'ai pas trouvé de lien/langue pour cette page",
"missinglanguagelinkstext"      => "Ces articles ne lient pas à leur 'contrepartie' in $1. Les redirections et les liens ne sont pas affichés.",


# Miscellaneous special pages
#
"orphans"       => "Pages orphelines",
"lonelypages"   => "Pages orphelines",
"unusedimages"  => "Images orphelines",
"popularpages"  => "Pages les plus consultées",
"nviews"        => "$1 consultations",
"wantedpages"   => "Pages les plus demandées",
"nlinks"        => "$1 références",
"allpages"      => "Toutes les pages",
"randompage"    => "Une page au hasard",
"shortpages"    => "Articles courts",
"longpages"     => "Articles longs",
"listusers"     => "Liste des participants",
"specialpages"  => "Pages spéciales",
"spheading"     => "Pages spéciales",
"sysopspheading" => "Pages spéciales à l'usage des administrateurs",
"developerspheading" => "Pages spéciales à l'usage des développeurs",
"protectpage"   => "Protéger la page",
"recentchangeslinked" => "Suivi des liens",
"rclsub"        => "(des pages liées à \"$1\")",
"debug"         => "Déboguer",
"newpages"      => "Nouvelles pages",
"ancientpages"	=> "Articles les plus anciens",
'move'		=> 'déplacer',
'movethispage'  => 'Déplacer la page',
"unusedimagestext" => "<p>N'oubliez pas que d'autres sites, comme certains Wikipédias non francophones, peuvent contenir un lien direct vers cette image, et que celle-ci peut être placée dans cette liste alors qu'elle est en réalité utilisée.",
"booksources"   => "Ouvrages de référence",
"booksourcetext" => "Voici une liste de liens vers d'autres sites qui vendent des livres neufs et d'occasion et sur lesquels vous trouverez peut-être des informations sur les ouvrages que vous cherchez. {{SITENAME}} n'étant liée à aucune de ces sociétés, elle n'a aucunement l'intention d'en faire la promotion.",
"alphaindexline" => "$1 à $2",
"version" => "Version",

# Email this user
#
"mailnologin" => "Pas d'adresse",
"mailnologintext" => "Vous devez être <a href=\"{{localurl:Special:Userlogin}}\">connecté</a>
et avoir indiqué une adresse électronique valide dans vos <a href=\"{{localurl:Special:Preferences}}\">préférences</a>
pour pouvoir envoyer un message à un autre utilisateur.",
"emailuser"  => "Envoyer un message à cet utilisateur",
"emailpage"  => "Email user",
"emailpagetext" => "Si cet utilisateur a indiqué une adresse électronique valide dans ses préférences, le formulaire ci-dessous lui enverra un message.
L'adresse électronique que vous avez indiquée dans vos préférences apparaîtra dans le champ \"Expéditeur\" de votre message, afin que le destinataire puisse vous répondre.",
"noemailtitle" => "Pas d'adresse électronique",
"noemailtext" => "Cet utilisateur n'a pas spécifié d'adresse électronique valide ou a choisi de ne pas recevoir de courrier électronique des autres utilisateurs.",

"emailfrom"  => "Expéditeur",
"emailto"  => "Destinataire",
"emailsubject" => "Objet",
"emailmessage" => "Message",
"emailsend"  => "Envoyer",
"emailsent"  => "Message envoyé",
"emailsenttext" => "Votre message a été envoyé.",
"usermailererror" => "L'objet Mail a renvoyé une erreur: ",
"defemailsubject" => "e-mail envoyé depuis {{SITENAME}}",

# Watchlist
#
"watchlist"	=> "Liste de suivi",
"watchlistsub"	=> "(pour l'utilisateur \"$1\")",
"nowatchlist"	=> "Votre liste de suivi ne contient aucun article.",
"watchnologin"	=> "Non connecté",
"watchnologintext" => "Vous devez être <a href=\"{{localurl:Special:Userlogin}}\">connecté</a>
pour modifier votre liste.",
"addedwatch"	=> "Ajouté à la liste",
"addedwatchtext" => "<p>La page \"$1\" a été ajoutée à votre <a href=\"{{localurl:Special:Watchlist}}\">liste de suivi</a>.
Les prochaines modifications de cette page et de la page discussion associée seront répertoriées ici, et la page apparaîtra <b>en gras</b> dans la <a href=\"{{localurl:Special:Recentchanges}}\">liste des modifications récentes</a> pour être repérée plus facilement.</p>

<p>Pour supprimer cette page de votre liste de suivi, cliquez sur \"Ne plus suivre\" dans le cadre de navigation.</p>",
"removedwatch"	=> "Supprimée de la liste de suivi",
"removedwatchtext" => "La page \"$1\" a été supprimée de votre liste de suivi.",
'watch'		=> 'Suivre',
"watchthispage"	=> "Suivre cette page",
'unwatch'	=> 'ne plus suivre',
"unwatchthispage" => "Ne plus suivre",
"notanarticle"	=> "Aucun article",
"watchnochange" => "Aucune des pages que vous suivez n'a été modifiée pendant la période affichée",
// "watchdetails" => "($1 pages suivies, sans compter les pages de discussion; $2 pages en total modifiées depuis la limite; $3...  <a href='$4'>afficher et modifier la liste complète</a>.)", // Looxix 
"watchdetails" => "Vous suivez $1 pages, sans compter les pages de discussion.  <a href='$4'>Afficher et modifier la liste complète</a>.", // Looxix 
"watchmethod-recent" => "vérification des modifications récentes des pages suivies", // Looxix 
"watchmethod-list" => "vérification des pages suivies pour des modifications récentes", // Looxix 
"removechecked" => "Retirer de la liste de suivi les articles sélectionnés",
"watchlistcontains" => "Votre liste de suivi contient $1 pages",
"watcheditlist" => "Ceci est votre liste de suivi par ordre alphabétique. Sélectionnez les pages que vous souhaitez retirer de la liste et cliquez le bouton \"retirer de la liste de suivi\" en bas de l'écran.",
"removingchecked" => "Les articles sélectionnés sont retirés de votre liste de suivi...",
"couldntremove" => "Impossible de retirer l'article '$1'...",
"iteminvalidname" => "Problème avec l'article '$1': le nom est invalide...",
"wlnote" => "Ci-dessous se trouve les $1 dernières modifications depuis les <b>$2</b> dernières heures.", // Looxix 
"wlshowlast" => "Montrer les dernières $1 heures $2 jours $3",
"wlsaved" => "La liste de suivi n'est remise à jour qu'une fois par heure pour alléger la charge sur le serveur.",

# Delete/protect/revert
#
"deletepage"	=> "Supprimer une page",
"confirm"	=> "Confirmer",
"excontent"	=> "contenant",
"exbeforeblank" => "le contenu avant effacement était :",
"exblank"	=> "page vide",
"confirmdelete" => "Confirmer la suppression",
"deletesub"	=> "(Suppression de \"$1\")",
"historywarning" => "Attention: La page que vous êtes sur le point de supprimer à un historique: ",
"confirmdeletetext" => "Vous êtes sur le point de supprimer définitivement de la base de données une page
ou une image, ainsi que toutes ses versions antérieures.
Veuillez confirmer que c'est bien là ce que vous voulez faire, que vous en comprenez les conséquences et que vous faites cela en accord avec les [[{{ns:4}}:Recommandations Et Règles à  Suivre|recommandations et règles à suivre]].",
"confirmcheck"	=> "Oui, je confirme la suppression.",
"actioncomplete" => "Suppression effectuée",
"deletedtext"	=> "\"$1\" a été supprimé.
Voir $2 pour une liste des suppressions récentes.",
"deletedarticle" => "effacement de \"$1\"",
"dellogpage"	=> "Historique des effacements",
"dellogpagetext" => "Voici la liste des suppressions récentes.
L'heure indiquée est celle du serveur (UTC).
<ul>
</ul>
",
"deletionlog"	=> "historique des effacements",
"reverted"	=> "Rétablissement de la version précédente",
"deletecomment" => "Motif de la suppression",
"imagereverted" => "La version précédente a été rétablie.",
"rollback"	=> "révoquer modifications",
"rollback_short" => "Révoquer",
"rollbacklink"	=> "révoquer",
"rollbackfailed" => "La révocation a échoué",
"cantrollback"	=> "Impossible de révoquer: dernier auteur est le seul à avoir modifié cet article",
"alreadyrolled"	=> "Impossible de révoquer la dernière modification de [[$1]]
par  [[User:$2|$2]] ([[User talk:$2|Talk]]); quelqu'un d'autre à déjà modifer ou révoquer l'article. 

La dernière modificaion était de [[User:$3|$3]] ([[User talk:$3|Talk]]). ", //Looxix 
#   only shown if there is an edit comment
"editcomment" => "Le résumé de la modification était: \"<i>$1</i>\".", //Looxix 
"revertpage"	=> "restitution de la dernière modification de $1",
"protectlogpage" => "Log_de_protection",
"protectlogtext" => "Voir les [[{{ns:4}}:Page protégée|directives concernant les pages protégées]].",
"protectedarticle" => "a protégée [[$1]]",
"unprotectedarticle" => "a déprotégé [[$1]]",

"protectsub" => "(Bloque \"$1\")",
"confirmprotect" => "Confimer le bloquage",
"confirmprotecttext" => "Voulez vous vraiment bloquer cette page ?",
"protectcomment" => "Raison du bloquage",

"unprotectsub" => "(Débloque \"$1\")",
"confirmunprotecttext" => "Vous les vous vraiment débloquer cette page ?",
"confirmunprotect" => "Raison du débloquage",
"unprotectcomment" => "Raison du débloquage",
"protectreason" => "(indiquez une raison)",

# Undelete
#
"undelete"	=> "Restaurer la page effacée",
"undeletepage"	=> "Voir et restaurer la page effacée",
"undeletepagetext" => "Ces pages ont été effacées et se trouvent dans la corbeille, elles sont toujours dans la base de donnée et peuvent être restaurées.
La corbeille peut être effacée périodiquement.",

"undeletearticle" => "Restaurer les articles effacés",
"undeleterevisions" => "$1 révisions archivées",
"undeletehistory" => "Si vous restaurez la page, toutes les révisions seront restaurées dans l'historique.
Si une nouvelle page avec le même nom a été crée depuis la suppression,
les révisions restaurées apparaîtront dans l'historique antérieur et la version courante ne sera pas automatiquement remplacée.",
"undeleterevision" => "Version effacée ($1)",
"undeletebtn"	=> "Restaurer!",
"undeletedarticle" => "restauré \"$1\"",
"undeletedtext"   => "L'article [[$1]] a été restauré avec succès.
Voir [[{{ns:4}}:Trace des effacements]] pour la liste des suppressions et des restaurations récentes.",
# Contributions
#
"contributions"	=> "Contributions",
"mycontris"	=> "Mes contributions",
"contribsub"	=> "Pour $1",
"nocontribs"	=> "Aucune modification correspondant à ces critères n'a été trouvée.",
"ucnote"	=> "Voici les <b>$1</b> dernières modifications effectuées par cet utilisateur au cours des <b>$2</b> derniers jours.",
"uclinks"	=> "Afficher les $1 dernières modifications; afficher les $2 derniers jours.",
"uctop"		=> " (dernière)",

# What links here
#
"whatlinkshere" => "Pages liées",
"notargettitle" => "Pas de cible",
"notargettext"	=> "Indiquez une page cible ou un utilisateur cible.",
"linklistsub"	=> "(Liste de liens)",
"linkshere"	=> "Les pages ci-dessous contiennent un lien vers celle-ci:",
"nolinkshere"	=> "Aucune page ne contient de lien vers celle-ci.",
"isredirect"	=> "page de redirection",

# Block/unblock IP
#
"blockip"	=> "Bloquer une adresse IP",
"blockiptext"	=> "Utilisez le formulaire ci-dessous pour bloquer l'accès en écriture à partir d'une adresse IP donnée.
Une telle mesure ne doit être prise que pour empêcher le vandalisme et en accord avec [[{{ns:4}}:Recommandations et règles à suivre|recommandations et règles à suivre]].
Donnez ci-dessous une raison précise (par exemple en indiquant les pages qui ont été vandalisées).",
"ipaddress"	=> "Adresse IP",
"ipbreason"	=> "Motif",
"ipbsubmit"	=> "Bloquer cette adresse",
"badipaddress"	=> "L'adresse IP n'est pas correcte.",
"noblockreason" => "Vous devez indiquer le motif du blocage.",
"blockipsuccesssub" => "Blocage réussi",
"blockipsuccesstext" => "L'adresse IP \"$1\" a été bloquée.
<br />Vous pouvez consulter sur cette [[Special:Ipblocklist|page]] la liste des adresses IP bloquées.",
"unblockip"	=> "Débloquer une adresse IP",
"unblockiptext" => "Utilisez le formulaire ci-dessous pour rétablir l'accès en écriture
à partir d'une adresse IP précédemment bloquée.",
"ipusubmit"	=> "Débloquer cette adresse",
"ipusuccess"	=> "Adresse IP \"$1\" débloquée",
"ipblocklist"	=> "Liste des adresses IP bloquées",
"blocklistline" => "$1, $2 a bloqué $3",
"blocklink"	=> "bloquer",
"unblocklink"	=> "débloquer",
"contribslink"	=> "contribs",
"autoblocker"	=> "Autobloqué parce que vous partagez une adresse IP avec \"$1\". Raison : \"$2\".",
"blocklogpage"	=> "Log de blocage",
"blocklogentry"	=> 'blocage de "$1"',
"blocklogtext"	=> "Ceci est la trace des blocages et déblocages des utilisateurs. Les adresses IP automatiquement bloquées ne sont pas listées. Consultez la [[Special:Ipblocklist|liste des utilisateurs bloqués]] pour voir qui est actuellement effectivement bloqué.",
"unblocklogentry"	=> 'déblocage de "$1"',
"ipb_expiry_invalid" => "temps d'expiration invalide.",
"ip_range_invalid" => "Bloc IP incorrect.\n",
"proxyblocker" => "Bloqueur de proxy",
"proxyblockreason" => "Votre ip a été bloquée car c'est un proxy ouvert. Merci de contacter votre fournisseur d'accès internet ou votre support technique et de l'informer de ce problème de sécurité.",
"proxyblocksuccess" => "Terminé.\n",

# Developer tools
#
"lockdb"  => "Verrouiller la base",
"unlockdb"  => "Déverrouiller la base",
"lockdbtext" => "Le verrouillage de la base de données empêchera tous les utilisateurs de modifier des pages, de sauvegarder leurs préférences, de modifier leur liste de suivi et d'effectuer toutes les autres opérations nécessitant des modifications dans la base de données.
Veuillez confirmer que c'est bien là ce que vous voulez faire et que vous débloquerez la base dès que votre opération de maintenance sera terminée.",
"unlockdbtext" => "Le déverrouillage de la base de données permettra à nouveau à tous les utilisateurs de modifier des pages, de mettre à jour leurs préférences et leur liste de suivi, ainsi que d'effectuer les autres opérations nécessitant des modifications dans la base de données.
Veuillez confirmer que c'est bien là ce que vous voulez faire.",
"lockconfirm" => "Oui, je confirme que je souhaite verrouiller la base de données.",
"unlockconfirm" => "Oui, je confirme que je souhaite déverrouiller la base de données.",

"lockbtn"  => "Verrouiller la base",
"unlockbtn"  => "Déverrouiller la base",
"locknoconfirm" => "Vous n'avez pas coché la case de confirmation.",
"lockdbsuccesssub" => "Verrouillage de la base réussi.",
"unlockdbsuccesssub" => "Base déverrouillée.",
"lockdbsuccesstext" => "La base de données de {{SITENAME}} est verrouillée.

<br />N'oubliez pas de la déverrouiller lorsque vous aurez terminé votre opération de maintenance.",
"unlockdbsuccesstext" => "La base de données de {{SITENAME}} est déverrouillée.",

# SQL query
#
"asksql"	=> "Requête SQL",
"asksqltext"	=> "Utilisez le formulaire ci-dessous pour faire une requête directe sur la base de données de {{SITENAME}}.
Utilisez des guillemets simples ('comme ceci') pour délimiter les chaînes de caractères.
Cette opération peut surcharger considérablement le serveur, faites en usage
avec modération.",
"sqlislogged"	=> "Veillez noter que toutes les requêtes sont loguées",
"sqlquery"	 => "Saisir la requête",

"querybtn"	=> "Envoyer la requête",
"selectonly"	=> "Les requêtes autres que \"SELECT\" sont réservées aux développeurs du wiki.",
"querysuccessful" => "Requête réussie",

# Make sysop
"bureaucratlog"		=> "Log_bureaucrate",
"bureaucratlogentry"	=> "Droits de l'utilisateur \"$1\" passés à \"$2\"",
"makesysoptitle"	=> "Donne les droits d'adminitrateur.",
"makesysoptext"		=> "Ce formulaire est utilisé par les bureaucrates pour donner les droits d'adminitrateur.
Tapez le nom de l'utilisateur dans la boite et pressez le bouton pour lui donner les droits.",
"makesysopname"		=> "Nom de l'utilisateur:",
"makesysopsubmit"	=> "Donner les droits d'adminitrateur à cet utilisateur",
"makesysopok"		=> "<b>L'utilisateur \"$1\" est maintenant administrateur</b>",
"makesysopfail"		=> "<b>L'utilisateur \"$1\" n'a pas pu recevoir les droits d'adminitrateurs. (Avez vous entré le nom correctement?)</b>",
"rights"		=> "Droits:",
"set_user_rights"	=> "Met les droits de l'utilisateur",
"user_rights_set"	=> "<b>Les droits de l'utilisateur \"$1\" sont mis à jour</b>",
"setbureaucratflag"	=> "Donne les droits bureaucrate",
"set_rights_fail"	=> "<b>Les droits de l'utilisateur \"$1\" n'ont pas pu être mis en place. (Avez vous entré le nom correctement?)</b>",
"makesysop"         => "Donner les droits d'adminitrateur à un utilisateur",

# Validation
'val_clear_old' => 'Supprimer mes données de validation pour $1',
'val_merge_old' => 'Utiliser mes précédents choix pour les choix marqués \'Sans opinion\'',
'val_noop' => 'Sans opinion',
'val_percent' => '<b>$1%</b><br>($2 sur $3 points<br>par $4 utilisateurs)',
'val_percent_single' => '<b>$1%</b><br>($2 sur $3 points<br>par un utilisateur)',
'val_total' => 'Total',
'val_version' => 'Version',
'val_tab' => 'Valider',
'val_this_is_current_version' => 'ceci est la dernière version',
'val_version_of' => "Version de $1" ,
'val_table_header' => "<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Commentaire</th></tr>\n",
'val_stat_link_text' => 'Statistiques de validation pour cet article',
'val_view_version' => 'Voir cette version',
'val_validate_version' => 'Valider cette version',
'val_user_validations' => 'Cet utilisateur a validé $1 pages.',
'val_no_anon_validation' => 'Vous devez être identifié pour valider un article.',
'val_validate_article_namespace_only' => 'Seul les articles peuvent être validés. Cette page n\'est <i>pas</i> un article.',
'val_validated' => 'Validation effectuée.',
'val_article_lists' => 'List d\'articles validés',
'val_page_validation_statistics' => 'Statistiques de validation pour $1',


# Move page
#
"movepage"  => "Déplacer un article",
"movepagetext" => "Utilisez le formulaire ci-dessous pour renommer un article, en déplaçant toutes ses versions antérieures vers le nouveau nom.
Le titre précédent deviendra une page de redirection vers le nouveau titre.
Les liens vers l'ancien titre ne seront pas modifiés et la page discussion, si elle existe, ne sera pas déplacée.

'''ATTENTION!'''
Il peut s'agir d'un changement radical et inattendu pour un article souvent consulté;
assurez-vous que vous en comprenez bien les conséquences avant de procéder.",
"movepagetalktext" => "La page de discussion associé, si présente, sera automatiquement déplacée avec '''sauf si:'''
*Vous déplacez une page vers un autre espace,
*Une page de discussion existe déjà avec le nouveau nom, ou
*Vous avez désélectionné le bouton ci-dessous.

Dans ce cas, vous devrez déplacer ou fusionner la page manuellement si vous le désirez.",

"movearticle"	=> "Déplacer l'article",
"movenologin"	=> "Non connecté",
"movenologintext" => "Pour pouvoir déplacer un article, vous devez être <a href=\"{{localurl:Special:Userlogin}}\">connecté</a> en tant qu'utilisateur enregistré.",
"newtitle"	=> "Nouveau titre",
"movepagebtn"	=> "Déplacer l'article",
"pagemovedsub" => "Déplacement réussi",
"pagemovedtext" => "L'article \"[[$1]]\" a été déplacé vers \"[[$2]]\".",
"articleexists" => "Il existe déjà un article portant ce titre, ou le titre que vous avez choisi n'est pas valide.
Veuillez en choisir un autre.",
"talkexists"	=> "La page elle-même a été déplacée avec succès, mais
la page de discussion n'a pas pu être déplacée car il en existait déjà une
sous le nouveau nom. S'il vous plait, fusionnez les manuellement.",

"movedto"  => "déplacé vers",
"movetalk"  => "Déplacer aussi la page \"discussion\", s'il y a lieu.",
"talkpagemoved" => "La page discussion correspondante a également été déplacée.",
"talkpagenotmoved" => "La page discussion correspondante n'a <strong>pas</strong> été déplacée.",
"1movedto2" => "$1 déplacé vers $2",

# Export page
"export"	=> "Exporter des pages",
"exporttext"	=> "Vous pouvez exporter en XML le texte et l'historique d'une page ou d'un ensemble de pages; le résultat peut alores être importé dans un autre wiki fonctionnant avec le logiciel MediaWiki, transformé ou sauvegardé pour votre usage personnel.",
"exportcuronly"	=> "Exporter uniquement la version courante sans l'historique",

# Namespace 8 related

"allmessages"	=> "Tous les messages",
"allmessagestext"	=> "Ceci est la liste de tous les messages disponibles dans l'espace MediaWiki",

# Thumbnails

"thumbnail-more"	=> "Agrandir",
"missingimage"		=> "<b>Image manquante</b><br /><i>$1</i>\n",

# Special:Import
"import"	=> "Importer des pages",
"importfailed"	=> "L'import a échoué: $1",
"importhistoryconflict" => "Des révisions dans l'historique existent et sont en conflits (cette page à peut être déjà été importée avant)",
"importnotext"	=> "Vide ou sans texte",
"importsuccess"	=> "L'import à réussi!",
"importtext"	=> "Exportez un fichier depuis le wiki source en utilisant la fonction Special:Export, sauvez la page sur votre disque puis envoyez là ici.",

# Keyboard access keys for power users
'accesskey-anontalk'		=> 'n',
'accesskey-anonuserpage'	=> '.',
'accesskey-article'			=> 'a',
'accesskey-compareselectedversions' => 'v',
'accesskey-contributions'	=> '',
'accesskey-currentevents'	=> '',
'accesskey-delete'			=> 'd',
'accesskey-edit'			=> 'e',
'accesskey-emailuser'		=> '',
'accesskey-help'			=> '',
'accesskey-history'			=> 'h',
'accesskey-login'			=> 'o',
'accesskey-logout'			=> 'o',
'accesskey-mainpage'		=> 'z',
'accesskey-minoredit'		=> 'i',
'accesskey-move'			=> 'm',
'accesskey-mycontris'		=> 'y',
'accesskey-mytalk'			=> 'n',
'accesskey-portal'			=> '',
'accesskey-preferences'		=> '',
'accesskey-preview'			=> 'p',
'accesskey-protect'			=> '-',
'accesskey-randompage'		=> 'x',
'accesskey-recentchanges'	=> 'r',
'accesskey-recentchangeslinked' => 'c',
'accesskey-save'			=> 's',
'accesskey-search'			=> 'f',
'accesskey-sitesupport'		=> '',
'accesskey-specialpage'		=> '',
'accesskey-specialpages'	=> 'q',
'accesskey-talk'			=> 't',
'accesskey-undelete'		=> 'd',
'accesskey-unwatch'			=> 'w',
'accesskey-upload'			=> 'u',
'accesskey-userpage'		=> '.',
'accesskey-viewsource'		=> 'e',
'accesskey-watch'			=> 'w',
'accesskey-watchlist'		=> 'l',
'accesskey-whatlinkshere'	=> 'b',

# tooltip help for the main actions
'tooltip-anontalk' => 'Discussion des éditions faites à partir de cette adresse ip [alt-n]',
'tooltip-anonuserpage' => 'La page d\'utilisateur pour l\'adresse ip depuis laquelle vous éditez [alt-.]',
'tooltip-article' => 'Voir l\'article [alt-a]',
'tooltip-atom' => 'Flux Atom pour cette page',
'tooltip-compareselectedversions' => 'Voir les différences entre les deux versions séléctionnées de cette page. [alt-v]',
'tooltip-contributions' => 'Voir la liste des contributions de cet utilisateur',
'tooltip-currentevents' => 'Trouver des informations sur les évenements actuels',
'tooltip-delete' => 'Supprimer cette page [alt-d]',
'tooltip-edit' => 'Vous pouvez éditer cette page. Merci d\'utiliser le bouton prévisualisation avant d\'enregistrer. [alt-e]',
'tooltip-emailuser' => 'Envoyer un mail à cet utilisateur',
'tooltip-help' => 'L\'endroit pour découvrir.',
'tooltip-history' => 'Versions précédentes de cette page, [alt-h]',
'tooltip-login' => 'Vous êtes encouragés à vous identifier avant d\'éditer, ce n\'est toutefois pas nécessaire.',
'tooltip-logout' => 'Le bouton démarrer [alt-o]',
'tooltip-mainpage' => 'Aller à la page principale',
'tooltip-minoredit' => 'Marquer cette modification comme mineur [alt-i]',
'tooltip-move' => 'Déplacer cette page [alt-m]',
'tooltip-mycontris' => 'Liste de mes contributions',
'tooltip-mytalk' => 'Ma page de discussion',
'tooltip-nomove' => 'Vous n\'avez pas la permission de déplacer cette page',
'tooltip-portal' => 'A propos de ce projet, ce que vous pouvez faire, où trouver les choses',
'tooltip-preferences' => 'Mes préférences',
'tooltip-preview' => 'Prévisualiser les changements, merci de l\'utiliser avant de sauvegarder! [alt-p]',
'tooltip-protect' => 'Protéger cette page [alt-"-"]',
'tooltip-randompage' => 'Aller à une page au hasard [alt-x]',
'tooltip-recentchanges' => 'La liste des modifications récentes dans le wiki. [alt-r]',
'tooltip-recentchangeslinked' => 'Modifications récentes des pages liant à cette page [alt-c]',
'tooltip-rss' => 'Flux RSS pour cette page',
'tooltip-save' => 'Sauvegarder vos modifications [alt-s]',
'tooltip-search' => 'Rechercher dans ce wiki',
'tooltip-sitesupport' => 'Aider {{SITENAME}}',
'tooltip-specialpage' => 'Ceci est une page spéciale, vous ne pouvez pas l\'éditer.',
'tooltip-specialpages' => 'Liste de toutes les pages spéciales',
'tooltip-talk' => 'Discussion sur l\'article [alt-t]',
'tooltip-undelete' => 'Restaurer $1 éditions supprimées de cette page [alt-d]',
'tooltip-unwatch' => 'Retirer cette page de votre liste de suivi',
'tooltip-upload' => 'Copier sur le serveur des fichiers [alt-u]',
'tooltip-userpage' => 'Ma page personelle',
'tooltip-viewsource' => 'Cette page est protégée. Vous pouvez voir la source. [alt-e]',
'tooltip-watch' => 'Ajouter cette page à votre liste de suivi',
'tooltip-watchlist' => 'La liste de suivi est la liste des pages que vous monitorez. [alt-l]',
'tooltip-whatlinkshere' => 'Liste de toutes les pages qui lient à ici [alt-b]',

# Metadata
"nocreativecommons" => "Les données méta 'Creative Commons RDF' sont désactivées sur ce serveur.",
"nodublincore" => "Les données méta 'Dublin Core RDF' sont désactivées sur ce serveur.",
"notacceptable" => "Ce serveur wiki ne peut pas fournir les données dans un format que votre client est capable de lire.",

# Attribution
"anonymous"	=> "Utilisateur(s) anonyme(s) de {{SITENAME}}",
"siteuser"	=> "Utilisateur $1 de {{SITENAME}}",
"lastmodifiedby" => "Cette page a été modifiée pour la dernière fois le $1 par $2",
"and"	=> "et",
"contributions" => "Basé sur le travail de $1.",
"siteusers"	=> "Utilisateur(s) $1 de {{SITENAME}}",

# Math
'mw_math_png' => "Toujours produire une image PNG",  
'mw_math_simple' => "HTML si très simple, autrement PNG", 
'mw_math_html' => "HTML si possible, autrement PNG", 
'mw_math_source' => "Laisser le code TeX original",
'mw_math_modern' => "Pour les navigateurs modernes",
'mw_math_mathml' => 'MathML',
);

class LanguageFr extends LanguageUtf8
{

	function getDefaultUserOptions()
	{
	        $opt = Language::getDefaultUserOptions();
                return $opt;
	}

        function getBookstoreList () {
                global $wgBookstoreListFr ;
                return $wgBookstoreListFr ;
        }

	function getNamespaces()
	{
		global $wgNamespaceNamesFr;
		return $wgNamespaceNamesFr;
	}


	function getNsText( $index )
	{
		global $wgNamespaceNamesFr;
		return $wgNamespaceNamesFr[$index];
	}

	function getNsIndex( $text ) 
	{
		global $wgNamespaceNamesFr, $wgSitename;

		foreach ( $wgNamespaceNamesFr as $i => $n ) 
		{
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == "Wikipédia" ) {
			if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
			if( 0 == strcasecmp( "Discussion_Wikipedia", $text ) ) return 5;
		}
		return false;
	}

	function specialPage( $name ) 
	{
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() 
	{
		global $wgQuickbarSettingsFr;
		return $wgQuickbarSettingsFr;
	}

	function getSkinNames()
	{
		global $wgSkinNamesFr;
		return $wgSkinNamesFr;
	}


	// Inherit userAdjust()

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->date( $ts, $adj ) . " à " . $this->time( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFr;
		return $wgValidSpecialPagesFr;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFr;
		return $wgSysopSpecialPagesFr;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFr;
		return $wgDeveloperSpecialPagesFr;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesFr, $wgAllMessagesEn;
		if( isset( $wgAllMessagesFr[$key] ) ) {
			return $wgAllMessagesFr[$key];
		} else {
			return $wgAllMessagesEn[$key];
		}
	}
	
	function isRTL() { return false; }
}

?>
