<?php
/** French (Français)
 *
 * @addtogroup Language
 *
 * @author Hégésippe Cormier
 * @author Seb35
 * @author Korg
 * @author JeanVoisin
 * @author Cedric31
 * @author Urhixidur
 * @author Sherbrooke
 * @author Siebrand
 * @author ChrisPtDe
 * @author Горан Анђелковић
 * @author Grondin
 * @author Nike
 * @author לערי ריינהארט
 * @author Dereckson
 */

$skinNames = array(
	'standard'  => 'Standard',
	'nostalgia' => 'Nostalgie',
);

$bookstoreList = array(
	'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Special',
	NS_MAIN           => '',
	NS_TALK           => 'Discuter',
	NS_USER           => 'Utilisateur',
	NS_USER_TALK      => 'Discussion_Utilisateur',
	NS_PROJECT        => '$1',
	NS_PROJECT_TALK   => 'Discussion_$1',
	NS_IMAGE          => 'Image',
	NS_IMAGE_TALK     => 'Discussion_Image',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
	NS_TEMPLATE       => 'Modèle',
	NS_TEMPLATE_TALK  => 'Discussion_Modèle',
	NS_HELP           => 'Aide',
	NS_HELP_TALK      => 'Discussion_Aide',
	NS_CATEGORY       => 'Catégorie',
	NS_CATEGORY_TALK  => 'Discussion_Catégorie'
);
$linkTrail = '/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sDu';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'F j, Y à H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y à H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'Y F j à H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Souligner les liens :',
'tog-highlightbroken'         => 'Afficher <a href="" class="new">en rouge</a> les liens vers les pages inexistantes (sinon :  comme ceci<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Justifier les paragraphes',
'tog-hideminor'               => 'Cacher les modifications récentes mineures',
'tog-extendwatchlist'         => 'Utiliser la liste de suivi améliorée',
'tog-usenewrc'                => 'Utiliser les modifications récentes améliorées (JavaScript)',
'tog-numberheadings'          => 'Numéroter automatiquement les titres',
'tog-showtoolbar'             => 'Montrer la barre de menu de modification (JavaScript)',
'tog-editondblclick'          => 'Double-cliquer pour modifier une page (JavaScript)',
'tog-editsection'             => 'Modifier une section via les liens [modifier]',
'tog-editsectiononrightclick' => 'Modifier une section en faisant un clic droit sur son titre (JavaScript)',
'tog-showtoc'                 => 'Afficher la table des matières (pour les pages ayant plus de 3 sections)',
'tog-rememberpassword'        => "Se souvenir de mon mot de passe (témoin (''cookie''))",
'tog-editwidth'               => 'Afficher la fenêtre d’édition en pleine largeur',
'tog-watchcreations'          => 'Ajouter les pages que je crée à ma liste de suivi',
'tog-watchdefault'            => 'Ajouter les pages que je modifie à ma liste de suivi',
'tog-watchmoves'              => 'Ajouter les pages que je renomme à ma liste de suivi',
'tog-watchdeletion'           => 'Ajouter les pages que je supprime à ma liste de suivi',
'tog-minordefault'            => 'Considérer mes modifications comme mineures par défaut',
'tog-previewontop'            => 'Montrer la prévisualisation au-dessus de la zone de modification',
'tog-previewonfirst'          => 'Montrer la prévisualisation lors de la première édition',
'tog-nocache'                 => 'Désactiver le cache des pages',
'tog-enotifwatchlistpages'    => 'Autoriser l’envoi de courriel lorsqu’une page de votre liste de suivi est modifiée',
'tog-enotifusertalkpages'     => 'M’avertir par courriel en cas de modification de ma page de discussion',
'tog-enotifminoredits'        => 'M’avertir par courriel même en cas de modification mineure',
'tog-enotifrevealaddr'        => 'Afficher mon adresse électronique dans les courriels d’avertissement',
'tog-shownumberswatching'     => 'Afficher le nombre d’utilisateurs qui suivent cette page',
'tog-fancysig'                => 'Signature brute (sans lien automatique)',
'tog-externaleditor'          => 'Utiliser un éditeur externe par défaut',
'tog-externaldiff'            => 'Utiliser un comparateur externe par défaut',
'tog-showjumplinks'           => 'Activer les liens « navigation » et « recherche » en haut de page (habillages Myskin et autres)',
'tog-uselivepreview'          => 'Utiliser l’aperçu rapide (JavaScript) (expérimental)',
'tog-forceeditsummary'        => 'M’avertir lorsque je n’ai pas complété le contenu de la boîte de commentaires',
'tog-watchlisthideown'        => 'Masquer mes propres modifications dans la liste de suivi',
'tog-watchlisthidebots'       => 'Masquer les modifications faites par les bots dans la liste de suivi',
'tog-watchlisthideminor'      => 'Masquer les modifications mineures dans la liste de suivi',
'tog-nolangconversion'        => 'Désactiver la conversion des variantes de langue',
'tog-ccmeonemails'            => 'M’envoyer une copie des courriels que j’envoie aux autres utilisateurs',
'tog-diffonly'                => 'Ne pas montrer le contenu des pages sous les diffs',

'underline-always'  => 'Toujours',
'underline-never'   => 'Jamais',
'underline-default' => 'Selon le navigateur',

'skinpreview' => '(Prévisualiser)',

# Dates
'sunday'        => 'dimanche',
'monday'        => 'lundi',
'tuesday'       => 'mardi',
'wednesday'     => 'mercredi',
'thursday'      => 'jeudi',
'friday'        => 'vendredi',
'saturday'      => 'samedi',
'sun'           => 'dim',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mer',
'thu'           => 'jeu',
'fri'           => 'ven',
'sat'           => 'sam',
'january'       => 'janvier',
'february'      => 'février',
'march'         => 'mars',
'april'         => 'avril',
'may_long'      => 'mai',
'june'          => 'juin',
'july'          => 'juillet',
'august'        => 'août',
'september'     => 'septembre',
'october'       => 'octobre',
'november'      => 'novembre',
'december'      => 'décembre',
'january-gen'   => 'janvier',
'february-gen'  => 'février',
'march-gen'     => 'mars',
'april-gen'     => 'avril',
'may-gen'       => 'mai',
'june-gen'      => 'juin',
'july-gen'      => 'juillet',
'august-gen'    => 'août',
'september-gen' => 'septembre',
'october-gen'   => 'octobre',
'november-gen'  => 'novembre',
'december-gen'  => 'décembre',
'jan'           => 'jan',
'feb'           => 'fév',
'mar'           => 'mar',
'apr'           => 'avr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aoû',
'sep'           => 'sep',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'déc',

# Bits of text used by many pages
'categories'            => 'Catégories',
'pagecategories'        => '{{PLURAL:$1|Catégorie |Catégories }}',
'category_header'       => 'Pages dans la catégorie « $1 »',
'subcategories'         => 'Sous-catégories',
'category-media-header' => 'Fichiers multimédia dans la catégorie « $1 »',
'category-empty'        => "''Cette catégorie ne contient aucun article, sous-catégorie ou fichier multimédia.''",

'mainpagetext'      => "<big>'''MediaWiki a été installé avec succès.'''</big>",
'mainpagedocfooter' => 'Consultez le [http://meta.wikimedia.org/wiki/Aide:Contenu Guide de l’utilisateur] pour plus d’informations sur l’utilisation de ce logiciel.

== Démarrer avec MediaWiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste des paramètres de configuration]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Liste de discussion des parutions de MediaWiki]',

'about'          => 'À propos',
'article'        => 'Article',
'newwindow'      => '(ouvre une nouvelle fenêtre)',
'cancel'         => 'Annuler',
'qbfind'         => 'Rechercher',
'qbbrowse'       => 'Défiler',
'qbedit'         => 'Modifier',
'qbpageoptions'  => 'Page d’option',
'qbpageinfo'     => 'Page d’information',
'qbmyoptions'    => 'Mes options',
'qbspecialpages' => 'Pages spéciales',
'moredotdotdot'  => 'Et plus …',
'mypage'         => 'Page perso',
'mytalk'         => 'Page de discussion',
'anontalk'       => 'Discussion avec cette adresse IP',
'navigation'     => 'Navigation',

# Metadata in edit box
'metadata_help' => 'Métadonnées :',

'errorpagetitle'    => 'Erreur de titre',
'returnto'          => 'Revenir à la page $1.',
'tagline'           => 'Un article de {{SITENAME}}.',
'help'              => 'Aide',
'search'            => 'Rechercher',
'searchbutton'      => 'Rechercher',
'go'                => 'Consulter',
'searcharticle'     => 'Consulter',
'history'           => 'hist',
'history_short'     => 'Historique',
'updatedmarker'     => 'modifié depuis ma dernière visite',
'info_short'        => 'Informations',
'printableversion'  => 'Version imprimable',
'permalink'         => 'Lien permanent',
'print'             => 'Imprimer',
'edit'              => 'Modifier',
'editthispage'      => 'Modifier cette page',
'delete'            => 'Supprimer',
'deletethispage'    => 'Supprimer cette page',
'undelete_short'    => 'Restaurer {{PLURAL:$1|1 modification| $1 modifications}}',
'protect'           => 'Protéger',
'protect_change'    => 'modifier',
'protectthispage'   => 'Protéger cette page',
'unprotect'         => 'Déprotéger',
'unprotectthispage' => 'Déprotéger cette page',
'newpage'           => 'Nouvelle page',
'talkpage'          => 'Page de discussion',
'talkpagelinktext'  => 'Discuter',
'specialpage'       => 'Page spéciale',
'personaltools'     => 'Outils personnels',
'postcomment'       => 'Ajouter un commentaire',
'articlepage'       => 'Voir l’article',
'talk'              => 'Discussion',
'views'             => 'Affichages',
'toolbox'           => 'Boîte à outils',
'userpage'          => 'Page utilisateur',
'projectpage'       => 'Page méta',
'imagepage'         => 'Page image',
'mediawikipage'     => 'Voir la page des messages',
'templatepage'      => 'Voir la page du modèle',
'viewhelppage'      => 'Voir la page d’aide',
'categorypage'      => 'Voir la page des catégories',
'viewtalkpage'      => 'Page de discussion',
'otherlanguages'    => 'Autres langues',
'redirectedfrom'    => '(Redirigé depuis $1)',
'redirectpagesub'   => 'Page de redirection',
'lastmodifiedat'    => 'Dernière modification de cette page le $1 à $2.<br />', # $1 date, $2 time
'viewcount'         => 'Cette page a été consultée {{PLURAL:$1|$1 fois|$1 fois}}.',
'protectedpage'     => 'Page protégée',
'jumpto'            => 'Aller à :',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Rechercher',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'À propos de {{SITENAME}}',
'aboutpage'         => 'Project:À propos',
'bugreports'        => 'Rapport d’erreurs',
'bugreportspage'    => 'Project:Rapport d’erreurs',
'copyright'         => 'Contenu disponible sous $1.',
'copyrightpagename' => 'licence {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Copyright',
'currentevents'     => 'Actualités',
'currentevents-url' => 'Project:Actualités',
'disclaimers'       => 'Avertissements',
'disclaimerpage'    => 'Project:Avertissements généraux',
'edithelp'          => 'Aide',
'edithelppage'      => 'Help:Comment modifier une page',
'faq'               => 'FAQ',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Aide',
'mainpage'          => 'Accueil',
'policy-url'        => 'Project:policy',
'portal'            => 'Communauté',
'portal-url'        => 'Project:Accueil',
'privacy'           => 'Politique de confidentialité',
'privacypage'       => 'Project:Confidentialité',
'sitesupport'       => 'Faire un don',
'sitesupport-url'   => 'Project:Faire un don',

'badaccess'        => 'Erreur de permission',
'badaccess-group0' => 'Vous n’avez pas les droits suffisants pour réaliser l’action que vous demandez.',
'badaccess-group1' => 'L’action que vous essayez de réaliser n’est accessible qu’aux utilisateurs du groupe $1.',
'badaccess-group2' => 'L’action que vous essayez de réaliser n’est accessible qu’aux utilisateurs des groupes $1.',
'badaccess-groups' => 'L’action que vous essayez de réaliser n’est accessible qu’aux utilisateurs des groupes $1.',

'versionrequired'     => 'Version $1 de MediaWiki nécessaire',
'versionrequiredtext' => 'La version $1 de MediaWiki est nécessaire pour utiliser cette page. Consultez [[Special:Version]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Récupérée de « $1 »',
'youhavenewmessages'      => 'Vous avez $1 ($2).',
'newmessageslink'         => 'des nouveaux messages',
'newmessagesdifflink'     => 'dernière modification',
'youhavenewmessagesmulti' => 'Vous avez de nouveaux messages sur $1.',
'editsection'             => 'modifier',
'editold'                 => 'modifier',
'editsectionhint'         => 'Modifier la section : $1',
'toc'                     => 'Sommaire',
'showtoc'                 => 'afficher',
'hidetoc'                 => 'masquer',
'thisisdeleted'           => 'Désirez-vous afficher ou restaurer $1 ?',
'viewdeleted'             => 'Voir $1 ?',
'restorelink'             => '{{PLURAL:$1|1 modification effacée|$1 modifications effacées}}',
'feedlinks'               => 'Flux',
'feed-invalid'            => 'Type de flux invalide.',
'site-rss-feed'           => 'Flux RSS de $1',
'site-atom-feed'          => 'Flux Atom de $1',
'page-rss-feed'           => 'Flux RSS de "$1"',
'page-atom-feed'          => 'Flux Atom de "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Article',
'nstab-user'      => 'Page utilisateur',
'nstab-media'     => 'Média',
'nstab-special'   => 'Spécial',
'nstab-project'   => 'À propos',
'nstab-image'     => 'Fichier',
'nstab-mediawiki' => 'Message',
'nstab-template'  => 'Modèle',
'nstab-help'      => 'Aide',
'nstab-category'  => 'Catégorie',

# Main script and global functions
'nosuchaction'      => 'Action inconnue',
'nosuchactiontext'  => 'L’action spécifiée dans l’URL n’est pas reconnue par le wiki.',
'nosuchspecialpage' => 'Page spéciale inexistante',
'nospecialpagetext' => "<big>'''Vous avez demandé une page spéciale qui n’est pas reconnue par le wiki.'''</big>

Une liste des pages spéciales peut être trouvée sur [[{{ns:special}}:Specialpages]]",

# General errors
'error'                => 'Erreur',
'databaseerror'        => 'Erreur de la base de données',
'dberrortext'          => 'Erreur de syntaxe dans la base de données. Cette erreur est peut-être due à une requête de recherche incorrecte (voir $5) ou une erreur dans le logiciel. La dernière requête traitée par la base de données était :
<blockquote><tt>$1</tt></blockquote>
depuis la fonction « <tt>$2</tt> ». MySQL a renvoyé l’erreur « <tt>$3 : $4</tt> ».',
'dberrortextcl'        => 'Une requête à la base de données comporte une erreur de syntaxe. La dernière requête envoyée était : « $1 » effectuée par la fonction « $2 ». MySQL a retourné l’erreur « $3 : $4 ».',
'noconnect'            => 'Désolé ! À la suite de problèmes techniques, il est impossible de se connecter à la base de données pour le moment. <br />
$1',
'nodb'                 => 'Impossible de sélectionner la base de données $1',
'cachederror'          => 'Cette page est une version en cache et peut ne pas être à jour.',
'laggedslavemode'      => 'Attention, cette page peut ne pas contenir les toutes dernières modifications effectuées',
'readonly'             => 'Base de données verrouillée',
'enterlockreason'      => 'Indiquez la raison du verrouillage ainsi qu’une estimation de sa durée',
'readonlytext'         => 'Les ajouts et mises à jour de la base de données sont actuellement bloqués, probablement pour permettre la maintenance de la base, après quoi, tout rentrera dans l’ordre. L’administrateur ayant verrouillé la base de données a donné l’explication suivante : $1',
'missingarticle'       => 'La base de données n’a pas pu trouver le texte d’une page qui existe pourtant, et dont le nom est « $1 ».

Cela est généralement dû à un diff désuet ou un lien vers l’historique d’une page effacée.

Si ce n’est pas le cas, vous avez peut-être trouvé un bogue du logiciel.

Veuillez rapporter cette erreur à un administrateur, en lui indiquant l’adresse de la page fautive.',
'readonly_lag'         => 'La base de donnée a été automatiquement verrouillée pendant que les serveurs secondaires rattrapent leur retard sur le serveur principal.',
'internalerror'        => 'Erreur interne',
'internalerror_info'   => 'Erreur interne : $1',
'filecopyerror'        => 'Impossible de copier le fichier « $1 » vers « $2 ».',
'filerenameerror'      => 'Impossible de renommer le fichier « $1 » en « $2 ».',
'filedeleteerror'      => 'Impossible de supprimer le fichier « $1 ».',
'directorycreateerror' => 'Impossible de créer le dossier « $1 ».',
'filenotfound'         => 'Impossible de trouver le fichier « $1 ».',
'fileexistserror'      => 'Impossible d’écrire dans le dossier « $1 » : le fichier existe',
'unexpected'           => 'Valeur inattendue : « $1 » = « $2 ».',
'formerror'            => 'Erreur : Impossible de soumettre le formulaire',
'badarticleerror'      => 'Cette action ne peut pas être effectuée sur cette page.',
'cannotdelete'         => 'Impossible de supprimer la page ou le fichier indiqué. (La suppression a peut-être déjà été effectuée par quelqu’un d’autre.)',
'badtitle'             => 'Mauvais titre',
'badtitletext'         => 'Le titre de la page demandée est invalide, vide, ou il s’agit d’un titre inter-langue ou inter-projet mal lié. Il contient peut-être un ou plusieurs caractères qui ne peuvent pas être utilisés dans les titres.',
'perfdisabled'         => 'Désolé ! Cette fonctionnalité est temporairement désactivée car elle ralentit la base de données à tel point que plus personne ne peut utiliser le wiki.',
'perfcached'           => 'Ceci est une version en cache et n’est peut-être pas à jour.',
'perfcachedts'         => 'Les données suivantes sont en cache, elles ne sont donc pas obligatoirement à jour. La dernière actualisation date du $1.',
'querypage-no-updates' => 'Les mises à jour pour cette page sont actuellement désactivées. Les données ci-dessous ne sont pas mises à jour.',
'wrong_wfQuery_params' => 'Paramètres incorrects sur wfQuery()<br />
Fonction : $1<br />
Requête : $2',
'viewsource'           => 'Voir le texte source',
'viewsourcefor'        => 'pour $1',
'actionthrottled'      => 'Action limitée',
'actionthrottledtext'  => "Pour lutter contre le spam, l’utilisation de cette action est limitée à un certain nombre de fois dans un laps temps assez court. Il s'avère que vous avez dépassé cette limite. Essayez à nouveau dans quelques minutes.",
'protectedpagetext'    => 'Cette page a été protégée pour empêcher sa modification.',
'viewsourcetext'       => 'Vous pouvez voir et copier le contenu de l’article pour pouvoir travailler dessus :',
'protectedinterface'   => 'Cette page fournit du texte d’interface pour le logiciel et est protégée pour éviter les abus.',
'editinginterface'     => "'''Attention :''' vous êtes en train d’éditer une page utilisée pour créer le texte de l’interface du logiciel. Les changements se répercuteront, selon le contexte, sur toutes ou certaines pages visibles par les autres utilisateurs. Pour les traductions, nous vous invitons à utiliser le projet Mediawiki d'internationalisation des messages [http://translatewiki.net/wiki/Translating:Intro Betawiki].",
'sqlhidden'            => '(Requête SQL cachée)',
'cascadeprotected'     => 'Cette page est actuellement protégée car incluse dans {{PLURAL:$1|la page suivante|les pages suivantes}}, ayant été protégée{{PLURAL:$1||s}} avec l’option « protection en cascade » activée :
$2',
'namespaceprotected'   => "Vous n’avez pas la permission de modifier les pages de l’espace de noms « '''$1''' ».",
'customcssjsprotected' => "Vous n’avez pas la permission d'éditer cette page parce qu’elle contient des préférences d’autres utilisateurs.",
'ns-specialprotected'  => 'Les pages dans l’espace de noms « {{ns:special}} » ne peuvent pas être modifiées.',
'titleprotected'       => 'Ce titre a été protégé à la création par [[User:$1|$1]]. Le motif avancé est <i>« $2 »</i>.',

# Login and logout pages
'logouttitle'                => 'Déconnexion',
'logouttext'                 => "'''Vous êtes à présent déconnecté(e).'''<br />
Vous pouvez continuer à utiliser {{SITENAME}} de façon anonyme, vous reconnecter sous le même nom ou un autre.",
'welcomecreation'            => '== Bienvenue, $1 ! ==

Votre compte a été créé. N’oubliez pas de personnaliser vos préférences sur {{SITENAME}}.',
'loginpagetitle'             => 'Connexion',
'yourname'                   => 'Votre nom d’utilisateur :',
'yourpassword'               => 'Votre mot de passe :',
'yourpasswordagain'          => 'Entrez à nouveau votre mot de passe :',
'remembermypassword'         => "Se souvenir de mon mot de passe (témoin (''cookie''))",
'yourdomainname'             => 'Votre domaine',
'externaldberror'            => 'Soit une erreur s’est produite avec la base de données d’authentification externe, soit vous n’êtes pas autorisé à mettre à jour votre compte externe.',
'loginproblem'               => '<b>Problème d’identification.</b><br />Essayez à nouveau !',
'login'                      => 'Identification',
'loginprompt'                => "Vous devez activer les témoins (''cookies'') pour vous connecter à {{SITENAME}}.",
'userlogin'                  => 'Créer un compte ou se connecter',
'logout'                     => 'Se déconnecter',
'userlogout'                 => 'Déconnexion',
'notloggedin'                => 'Non connecté',
'nologin'                    => 'Vous n’avez pas de compte ? $1.',
'nologinlink'                => 'Créez un compte',
'createaccount'              => 'Créer un compte',
'gotaccount'                 => 'Vous avez déjà un compte ? $1.',
'gotaccountlink'             => 'Identifiez-vous',
'createaccountmail'          => 'par courriel',
'badretype'                  => 'Les mots de passe que vous avez saisis ne sont pas identiques.',
'userexists'                 => 'Le nom d’utilisateur que vous avez saisi est déjà utilisé. Veuillez en choisir un autre.',
'youremail'                  => 'Adresse de courriel :',
'username'                   => 'Nom de l’utilisateur :',
'uid'                        => 'Numéro de l’utilisateur :',
'yourrealname'               => 'Nom réel',
'yourlanguage'               => 'Langue de l’interface :',
'yourvariant'                => 'Variante',
'yournick'                   => 'Signature pour les discussions :',
'badsig'                     => 'Signature brute incorrecte ; Vérifiez vos balises HTML.',
'badsiglength'               => 'Votre signature est trop longue : la taille maximale est de $1 caractères.',
'email'                      => 'Courriel',
'prefs-help-realname'        => '(facultatif) : si vous le spécifiez, il sera utilisé pour vous attribuer vos contributions.',
'loginerror'                 => 'Erreur d’identification',
'prefs-help-email'           => '(facultatif) : permet aux autres utilisateurs de vous contacter par courriel (lien sur vos pages utilisateur) sans que votre courriel soit visible, et de vous envoyer un nouveau mot de passe si vous l’oubliez.',
'prefs-help-email-required'  => 'Une adresse de courriel est requise.',
'nocookiesnew'               => "Le compte utilisateur a été créé, mais vous n’êtes pas connecté. {{SITENAME}} utilise des témoins (''cookies'') pour la connexion mais vous les avez désactivés. Veuillez les activer et vous reconnecter avec le même nom et le même mot de passe.",
'nocookieslogin'             => "{{SITENAME}} utilise des témoins (''cookies'') pour la connexion mais vous les avez désactivés. Veuillez les activer et vous reconnecter.",
'noname'                     => 'Vous n’avez pas saisi un nom d’utilisateur valide.',
'loginsuccesstitle'          => 'Identification réussie',
'loginsuccess'               => 'Vous êtes maintenant connecté à {{SITENAME}} en tant que « $1 ».',
'nosuchuser'                 => 'L’utilisateur « $1 » n’existe pas.
Vérifiez que vous avez bien orthographié le nom, ou utilisez le formulaire ci-dessous pour créer un nouveau compte utilisateur.',
'nosuchusershort'            => 'Il n’y a pas de contributeur avec le nom « $1 ». Veuillez vérifier l’orthographe.',
'nouserspecified'            => 'Vous devez saisir un nom d’utilisateur.',
'wrongpassword'              => 'Le mot de passe est incorrect. Veuillez essayer à nouveau.',
'wrongpasswordempty'         => 'Vous n’avez pas entré de mot de passe. Veuillez essayer à nouveau.',
'passwordtooshort'           => 'Votre mot de passe est trop court. Il doit contenir au moins $1 caractères et être différent de votre nom d’utilisateur.',
'mailmypassword'             => 'Envoyez-moi un nouveau mot de passe',
'passwordremindertitle'      => 'Nouveau mot de passe temporaire sur {{SITENAME}}',
'passwordremindertext'       => 'Quelqu’un (probablement vous) ayant l’adresse IP $1 a demandé à ce qu’un nouveau mot de passe vous soit envoyé pour {{SITENAME}} ($4).
Le mot de passe de l’utilisateur « $2 » est à présent « $3 ».
Nous vous conseillons de vous connecter et de modifier ce mot de passe dès que possible.

Si vous n’êtes pas l’auteur de cette demande, ou si vous vous souvenez à présent de votre ancien mot de passe et que vous ne souhaitez plus en changer, vous pouvez ignorer ce message et continuer à utiliser votre ancien mot de passe.',
'noemail'                    => 'Aucune adresse de courriel n’a été enregistrée pour l’utilisateur « $1 ».',
'passwordsent'               => 'Un nouveau mot de passe a été envoyé à l’adresse de courriel de l’utilisateur « $1 ». Veuillez vous reconnecter après l’avoir reçu.',
'blocked-mailpassword'       => 'Votre adresse IP est bloquée en édition, la fonction de rappel du mot de passe est donc désactivée pour éviter les abus.',
'eauthentsent'               => 'Un courriel de confirmation a été envoyé à l’adresse indiquée.
Avant qu’un autre courriel ne soit envoyé à ce compte, vous devrez suivre les instructions du courriel et confirmer que le compte est bien le vôtre.',
'throttled-mailpassword'     => 'Un courriel de rappel de votre mot de passe a déjà été envoyé durant les $1 dernières heures. Afin d’éviter les abus, un seul courriel de rappel sera envoyé en $1 heures.',
'mailerror'                  => 'Erreur en envoyant le courriel : $1',
'acct_creation_throttle_hit' => 'Désolé, vous avez déjà créé {{PLURAL:$1|$1 compte|$1 comptes}}. Vous ne pouvez pas en créer de nouveaux.',
'emailauthenticated'         => 'Votre adresse de courriel a été authentifiée le $1.',
'emailnotauthenticated'      => 'Votre adresse de courriel n’est <strong>pas encore authentifiée</strong>. Aucun courriel ne sera envoyé pour chacune des fonctions suivantes.',
'noemailprefs'               => '<strong>Aucune adresse électronique n’a été indiquée,</strong> les fonctions suivantes ne seront pas disponibles.',
'emailconfirmlink'           => 'Confirmez votre adresse de courriel',
'invalidemailaddress'        => 'Cette adresse de courriel ne peut pas être acceptée car elle semble avoir un format invalide. Veuillez entrer une adresse valide ou laisser ce champ vide.',
'accountcreated'             => 'Compte créé.',
'accountcreatedtext'         => 'Le compte utilisateur pour $1 a été créé.',
'createaccount-title'        => "Création d'un compte pour {{SITENAME}}",
'createaccount-text'         => "Quelqu'un ($1) a créé un compte pour $2 sur {{SITENAME}}
($4). Le mot de passe pour « $2 » est « $3 ». Vous devriez ouvrir une session et changer dès à présent ce mot de passe.

Ignorez ce message si ce compte a été créé par erreur.",
'loginlanguagelabel'         => 'Langue : $1',

# Password reset dialog
'resetpass'               => 'Remise à zéro du mot de passe',
'resetpass_announce'      => 'Vous vous êtes enregistré avec un mot de passe temporaire envoyé par courriel. Pour terminer l’enregistrement, vous devez entrer un nouveau mot de passe ici :',
'resetpass_text'          => '<!-- Ajoutez le texte ici -->',
'resetpass_header'        => 'Remise à zéro du mot de passe',
'resetpass_submit'        => 'Changer le mot de passe et s’enregistrer',
'resetpass_success'       => 'Votre mot de passe a été changé avec succès ! Enregistrement en cours...',
'resetpass_bad_temporary' => 'Mot de passe temporaire invalide. Vous avez peut-être déjà changé votre mot de passe avec succès, ou demandé un nouveau mot de passe temporaire.',
'resetpass_forbidden'     => 'Les mots de passe ne peuvent pas être changés sur ce wiki',
'resetpass_missing'       => 'Aucune donnée entrée.',

# Edit page toolbar
'bold_sample'     => 'Texte gras',
'bold_tip'        => 'Texte gras',
'italic_sample'   => 'Texte italique',
'italic_tip'      => 'Texte italique',
'link_sample'     => 'Titre du lien',
'link_tip'        => 'Lien interne',
'extlink_sample'  => 'http://www.example.com titre du lien',
'extlink_tip'     => 'Lien externe (n’oubliez pas le préfixe http://)',
'headline_sample' => 'Texte de sous-titre',
'headline_tip'    => 'Sous-titre niveau 2',
'math_sample'     => 'Entrez votre formule ici',
'math_tip'        => 'Formule mathématique (LaTeX)',
'nowiki_sample'   => 'Entrez le texte non formaté ici',
'nowiki_tip'      => 'Ignorer la syntaxe wiki',
'image_sample'    => 'Exemple.jpg',
'image_tip'       => 'Image insérée',
'media_sample'    => 'Exemple.ogg',
'media_tip'       => 'Lien vers un fichier média',
'sig_tip'         => 'Votre signature avec la date',
'hr_tip'          => 'Ligne horizontale (ne pas en abuser)',

# Edit pages
'summary'                   => 'Résumé&nbsp;',
'subject'                   => 'Sujet/titre',
'minoredit'                 => 'Modification mineure',
'watchthis'                 => 'Suivre cette page',
'savearticle'               => 'Sauvegarder',
'preview'                   => 'Prévisualisation',
'showpreview'               => 'Prévisualisation',
'showlivepreview'           => 'Aperçu rapide',
'showdiff'                  => 'Changements en cours',
'anoneditwarning'           => "'''Attention :''' vous n’êtes pas identifié. Votre adresse IP sera enregistrée dans l’historique de cette page.",
'missingsummary'            => "'''Attention :''' vous n'avez pas modifié le résumé de votre modification. Si vous cliquez de nouveau sur le bouton « Sauvegarder », la sauvegarde sera faite sans nouvel avertissement.",
'missingcommenttext'        => 'Veuillez faire votre commentaire ci-dessous.',
'missingcommentheader'      => "'''Rappel :''' Vous n’avez pas fourni de sujet/titre à ce commentaire. Si vous cliquez à nouveau sur « Sauvegarder », votre édition sera enregistrée sans titre.",
'summary-preview'           => 'Prévisualisation du résumé',
'subject-preview'           => 'Prévisualisation du sujet/titre',
'blockedtitle'              => 'L’utilisateur est bloqué.',
'blockedtext'               => "<big>'''Votre compte utilisateur (ou votre adresse IP) a été bloqué.'''</big>

Le blocage a été effectué par $1 pour la raison suivante : ''$2''.

Vous pouvez contacter $1 ou un autre [[{{MediaWiki:Grouppage-sysop}}|administrateur]] pour en discuter. Vous ne pouvez utiliser la fonction « Envoyer un courriel à cet utilisateur » que si une adresse de courriel valide est spécifiée dans vos [[Special:Preferences|préférences]]. Votre adresse IP actuelle est $3 et votre identifiant de blocage est #$5. Veuillez inclure cette adresse dans toute requête.
* Début du blocage : $8
* Expiration du blocage : $6
* Compte bloqué : $7.",
'autoblockedtext'           => "Votre adresse IP a été bloquée automatiquement car elle a été utilisée par un autre utilisateur, lui-même bloqué par $1.
La raison donnée est :

:''$2''

* Début du blocage : $8
* Expiration du blocage : $6

Vous pouvez contacter $1 ou l’un des autres [[{{MediaWiki:Grouppage-sysop}}|administrateurs]] pour discuter de ce blocage. 

Si vous avez donné une adresse de courriel valide dans vos [[Special:Preferences|préférences]] et que son utilisation ne vous est pas interdite, vous pouvez utiliser la fonction « Envoyer un message à cet utilisateur » pour contacter un administrateur.

Votre adresse IP est $3 et votre identifiant de blocage est #$5. Veuillez les préciser dans toute requête.",
'blockednoreason'           => 'Aucune raison donnée',
'blockedoriginalsource'     => "Le code source de  '''$1''' est indiqué ci-dessous :",
'blockededitsource'         => "Le contenu de '''vos modifications''' apportées à '''$1''' est indiqué ci-dessous :",
'whitelistedittitle'        => 'Connexion nécessaire pour modifier le contenu',
'whitelistedittext'         => 'Vous devez être $1 pour avoir la permission de modifier le contenu.',
'whitelistreadtitle'        => 'Connexion nécessaire pour lire le contenu',
'whitelistreadtext'         => 'Vous devez être [[Special:Userlogin|connecté]] pour lire le contenu.',
'whitelistacctitle'         => 'Vous n’êtes pas autorisé à créer un compte.',
'whitelistacctext'          => 'Pour pouvoir créer un compte sur ce Wiki, vous devez être [[Special:Userlogin|connecté]] et avoir les permissions appropriées.',
'confirmedittitle'          => 'Validation de l’adresse de courriel nécessaire pour modifier le contenu',
'confirmedittext'           => 'Vous devez confirmer votre adresse courriel avant de modifier {{SITENAME}}. Veuillez entrer et valider votre adresse électronique à l’aide de la page [[Special:Preferences|préférences]].',
'nosuchsectiontitle'        => 'Section manquante',
'nosuchsectiontext'         => "Vous avez essayé de modifier une section qui n’existe pas. Puisqu’il n’y a pas de section $1, il n’y a pas d'endroit où sauvegarder vos modifications.",
'loginreqtitle'             => 'Connexion nécessaire',
'loginreqlink'              => 'connecter',
'loginreqpagetext'          => 'Vous devez vous $1 pour voir les autres pages.',
'accmailtitle'              => 'Mot de passe envoyé.',
'accmailtext'               => 'Le mot de passe de « $1 » a été envoyé à l’adresse $2.',
'newarticle'                => '(Nouveau)',
'newarticletext'            => "Vous avez suivi un lien vers une page qui n’existe pas encore. Pour créer cette page, entrez votre texte dans la boîte ci-dessous (vous pouvez consulter [[{{MediaWiki:Helppage}}|la page d’aide]] pour plus d’information). Si vous êtes arrivé ici par erreur, cliquez sur le bouton '''retour''' de votre navigateur.",
'anontalkpagetext'          => "---- ''Vous êtes sur la page de discussion d’un utilisateur anonyme qui n’a pas encore créé de compte ou qui n’en utilise pas. Pour cette raison, nous devons utiliser son adresse IP pour l’identifier. Une adresse IP peut être partagée par plusieurs utilisateurs. Si vous êtes un utilisateur anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont été adressés, vous pouvez [[Special:Userlogin|créer un compte ou vous connecter]] afin d’éviter toute confusion future avec d’autres contributeurs anonymes.''",
'noarticletext'             => 'Il n’y a pour l’instant aucun texte sur cette page ; vous pouvez [[{{ns:special}}:Search/{{PAGENAME}}|lancer une recherche sur le titre de cette page]] ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} modifier cette page].',
'userpage-userdoesnotexist' => "Le compte utilisateur « $1 » n'est pas enregistré. Indiquez si vous voulez créer ou éditer cette page.",
'clearyourcache'            => "'''Note :''' Après avoir sauvegardé la page, vous devrez forcer son rechargement pour voir les changements : '''Mozilla / Konqueror / Firefox :''' ''Shift-Ctrl-R'', '''Internet Explorer / Opera :''' ''Ctrl-F5'', '''Safari :''' ''Cmd-R''.",
'usercssjsyoucanpreview'    => "'''Astuce :''' utilisez le bouton « Prévisualisation » pour tester votre nouvelle feuille CSS/JS avant de l’enregistrer.",
'usercsspreview'            => "'''Rappelez-vous que vous êtes en train de prévisualiser votre propre feuille CSS et qu’elle n’a pas encore été enregistrée !'''",
'userjspreview'             => "'''Rappelez-vous que vous êtes en train de visualiser ou de tester votre code JavaScript et qu’il n’a pas encore été enregistré !'''",
'userinvalidcssjstitle'     => "'''Attention :''' il n’existe pas de style « $1 ». Rappelez-vous que les pages personnelles avec extensions .css et .js utilisent des titres en minuscules après le nom d'utilisateur et la barre de fraction /.<br />Ainsi, {{ns:user}}:Foo/monobook.css est valide, alors que {{ns:user}}:Foo/Monobook.css sera une feuille de style invalide.",
'updated'                   => '(Mis à jour)',
'note'                      => '<strong>Note :</strong>',
'previewnote'               => 'Attention, ce texte n’est qu’une prévisualisation et n’a pas encore été sauvegardé !',
'previewconflict'           => 'Cette prévisualisation montre le texte de la boîte de modification supérieure tel qu’il apparaîtra si vous choisissez de le sauvegarder.',
'session_fail_preview'      => '<strong>Désolé ! Nous ne pouvons enregistrer votre modification à cause d’une perte d’informations concernant votre session. Veuillez réessayer. Si cela échoue à nouveau, veuillez vous déconnecter, puis vous reconnecter.</strong>',
'session_fail_preview_html' => "<strong>Désolé ! Nous ne pouvons enregistrer votre modification à cause d’une perte d’informations concernant votre session.</strong>

''L’HTML brut étant activé sur ce wiki, la prévisualisation a été masquée afin de prévenir une attaque par JavaScript.''

<strong>Si la tentative de modification était légitime, veuillez réessayer. Si cela échoue à nouveau, veuillez vous déconnecter, puis vous reconnecter.</strong>",
'token_suffix_mismatch'     => '<strong>Votre modification n’a pas été acceptée car votre navigateur Web a mélangé les caractères de ponctuation dans l’identifiant d’édition. La modification a été rejetée afin d’empêcher la corruption du texte de l’article. Ce problème se produit lorsque vous utilisez un mandataire anonyme problématique.</strong>',
'editing'                   => 'Modification de $1',
'editinguser'               => 'Modification de $1',
'editingsection'            => 'Modification de $1 (section)',
'editingcomment'            => 'Modification de $1 (commentaire)',
'editconflict'              => 'Conflit de modification : $1',
'explainconflict'           => '<b>Cette page a été sauvegardée après que vous avez commencé à la modifier. La zone de modification supérieure contient le texte tel qu’il est enregistré actuellement dans la base de données. Vos modifications apparaissent dans la zone de modification inférieure. Vous allez devoir apporter vos modifications au texte existant. Seul le texte de la zone supérieure sera sauvegardé.</b><br />',
'yourtext'                  => 'Votre texte',
'storedversion'             => 'Version enregistrée',
'nonunicodebrowser'         => '<strong>Attention : Votre navigateur ne supporte pas l’Unicode. Une solution temporaire a été trouvée pour vous permettre de modifier en toute sûreté un article : les caractères non-ASCII apparaîtront dans votre boîte de modification en tant que codes hexadécimaux. Vous devriez utiliser un navigateur plus récent.</strong>',
'editingold'                => '<strong>Attention : vous êtes en train de modifier une version désuète de cette page. Si vous sauvegardez, toutes les modifications effectuées depuis cette version seront perdues.</strong>',
'yourdiff'                  => 'Différences',
'copyrightwarning'          => "Toutes les contributions à {{SITENAME}} sont considérées comme publiées sous les termes de la $2 (voir $1 pour plus de détails). Si vous ne désirez pas que vos écrits soient modifiés et distribués à volonté, merci de ne pas les soumettre ici.<br />
Vous nous promettez aussi que vous avez écrit ceci vous-même, ou que vous l’avez copié d’une source provenant du domaine public, ou d’une ressource libre. <strong>N’UTILISEZ PAS DE TRAVAUX SOUS DROIT D'AUTEUR SANS AUTORISATION EXPRESSE !</strong>",
'copyrightwarning2'         => "Toutes les contributions à {{SITENAME}} peuvent être modifiées ou supprimées par d’autres utilisateurs. Si vous ne désirez pas que vos écrits soient modifiés et distribués à volonté, merci de ne pas les soumettre ici.<br />
Vous nous promettez aussi que vous avez écrit ceci vous-même, ou que vous l’avez copié d’une source provenant du domaine public, ou d’une ressource libre. (voir $1 pour plus de détails).
<strong>N’UTILISEZ PAS DE TRAVAUX SOUS DROIT D'AUTEUR SANS AUTORISATION EXPRESSE !</strong>",
'longpagewarning'           => "'''AVERTISSEMENT : cette page a une longueur de $1 kio ;
certains navigateurs Web gèrent mal la modification des pages approchant ou dépassant 32 kio. Peut-être devriez-vous diviser la page en sections plus petites.'''",
'longpageerror'             => '<strong>ERREUR : Le texte que vous avez soumis fait $1 kio, ce qui dépasse la limite fixée à $2 kio. Le texte ne peut pas être sauvegardé.</strong>',
'readonlywarning'           => "'''AVERTISSEMENT : La base de données a été verrouillée pour maintenance,
vous ne pourrez donc pas sauvegarder vos modifications maintenant. Vous pouvez copier le texte dans un fichier texte et le sauver pour plus tard.'''",
'protectedpagewarning'      => "'''AVERTISSEMENT : cette page est protégée.
Seuls les utilisateurs ayant le statut d’administrateur peuvent la modifier..'''",
'semiprotectedpagewarning'  => "'''Note :''' Cette page a été protégée de telle façon que seuls les contributeurs enregistrés puissent la modifier.",
'cascadeprotectedwarning'   => '<strong>ATTENTION : Cette page a été protégée de manière à ce que seuls les [[{{MediaWiki:Grouppage-sysop}}|administrateurs]] puissent l’éditer. Cette protection a été faite car cette page est incluse dans {{PLURAL:$1|une page protégée|des pages protégées}} avec la « protection en cascade » activée.</strong>',
'templatesused'             => 'Modèles utilisés sur cette page :',
'templatesusedpreview'      => 'Modèles utilisés dans cette prévisualisation :',
'templatesusedsection'      => 'Modèles utilisés dans cette section :',
'template-protected'        => '(protégé)',
'template-semiprotected'    => '(semi-protégé)',
'edittools'                 => '<!-- Tout texte entré ici sera affiché sous les boîtes de modification ou d’import de fichier. -->',
'nocreatetitle'             => 'Création de page limitée',
'nocreatetext'              => 'Ce site a restreint la possibilité de créer de nouvelles pages. Vous pouvez revenir en arrière et modifier une page existante, [[Special:Userlogin|vous connecter ou créer un compte]].',
'nocreate-loggedin'         => 'Vous n’avez pas la permission de créer de nouvelles pages sur ce wiki.',
'permissionserrors'         => 'Erreur de permissions',
'permissionserrorstext'     => 'Vous n’avez pas la permission d’effectuer l’opération demandée pour {{PLURAL:$1|la raison suivante|les raisons suivantes}} :',
'recreate-deleted-warn'     => "'''Attention : Vous êtes en train de recréer une page qui a été précédemment supprimée.'''

Demandez-vous s’il est réellement approprié de la recréer en vous référant au journal des suppressions affiché ci-dessous :",

# "Undo" feature
'undo-success' => 'Cette modification va être défaite. Veuillez confirmer les changements (visibles en bas de cette page), puis sauvegarder si vous êtes d’accord. Merci de motiver l’annulation dans la boîte de résumé.',
'undo-failure' => 'Cette modification ne peut pas être défaite : cela entrerait en conflit avec les modifications intermédiaires.',
'undo-summary' => 'Annulation des modifications $1 de [[Special:Contributions/$2|$2]]',

# Account creation failure
'cantcreateaccounttitle' => 'Vous ne pouvez pas créer de compte.',
'cantcreateaccount-text' => "La création de compte depuis cette adresse IP (<b>$1</b>) a été bloquée par [[User:$3|$3]].

La raison donnée par $3 était ''$2''.",

# History pages
'viewpagelogs'        => 'Voir le journal de cette page',
'nohistory'           => 'Il n’existe pas d’historique pour cette page.',
'revnotfound'         => 'Version introuvable',
'revnotfoundtext'     => 'La version précédente de cette page n’a pas pu être retrouvée. Veuillez vérifier l’URL que vous avez utilisée pour accéder à cette page.',
'loadhist'            => 'Chargement de l’historique de la page',
'currentrev'          => 'Version actuelle',
'revisionasof'        => 'Version du $1',
'revision-info'       => 'Version du $1 par $2',
'previousrevision'    => '← Version précédente',
'nextrevision'        => 'Version suivante →',
'currentrevisionlink' => 'voir la version courante',
'cur'                 => 'actu',
'next'                => 'suiv',
'last'                => 'diff',
'orig'                => 'orig',
'page_first'          => 'prem',
'page_last'           => 'dern',
'histlegend'          => 'Légende : ({{MediaWiki:Cur}}) = différence avec la version actuelle, ({{MediaWiki:Last}}) = différence avec la version précédente, <b>m</b> = modification mineure',
'deletedrev'          => '[supprimé]',
'histfirst'           => 'Premières contributions',
'histlast'            => 'Dernières contributions',
'historysize'         => '({{PLURAL:$1|$1 octet|$1 octets}})',
'historyempty'        => '(vide)',

# Revision feed
'history-feed-title'          => 'Historique des versions',
'history-feed-description'    => 'Historique pour cette page sur le wiki',
'history-feed-item-nocomment' => '$1 le $2', # user at time
'history-feed-empty'          => 'La page demandée n’existe pas. Elle a peut-être été supprimée du wiki ou renommée. Vous pouvez essayer de [[Special:Search|rechercher dans le wiki]] des pages pertinentes récentes.',

# Revision deletion
'rev-deleted-comment'         => '(commentaire supprimé)',
'rev-deleted-user'            => '(nom d’utilisateur supprimé)',
'rev-deleted-event'           => '(entrée supprimée)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Cette version de la page a été retirée des archives publiques. Il peut y avoir des détails dans le [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} journal des suppressions]. </div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks"> Cette version de la page a été retirée des archives publiques. En tant qu’administrateur de ce site, vous pouvez la visualiser ; il peut y avoir des détails dans le [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} journal des suppressions]. </div>',
'rev-delundel'                => 'afficher/masquer',
'revisiondelete'              => 'Supprimer/Restaurer des versions',
'revdelete-nooldid-title'     => 'Pas de cible pour la révision',
'revdelete-nooldid-text'      => 'Vous n’avez pas précisé la ou les révision(s) cible(s) pour utiliser cette fonction.',
'revdelete-selected'          => "{{PLURAL:$2|Version sélectionnée|Versions sélectionnées}} de '''$1''' :",
'logdelete-selected'          => "{{PLURAL:$2|Événement de journal sélectionné|Événements de journal sélectionnés}} pour '''$1''' :",
'revdelete-text'              => 'Les versions supprimées apparaîtront encore dans l’historique de l’article, mais leur contenu textuel sera inaccessible au public.

D’autres administrateurs sur ce wiki pourront toujours accéder au contenu caché et le restaurer à nouveau à travers cette même interface, à moins qu’une restriction supplémentaire ne soit mise en place par les opérateurs du site.',
'revdelete-legend'            => 'Mettre en place des restrictions de version :',
'revdelete-hide-text'         => 'Masquer le texte de la version',
'revdelete-hide-name'         => 'Masquer l’action et la cible',
'revdelete-hide-comment'      => 'Masquer le commentaire de modification',
'revdelete-hide-user'         => 'Masquer le pseudo ou l’adresse IP du contributeur.',
'revdelete-hide-restricted'   => 'Appliquer ces restrictions aux administrateurs ainsi qu’aux autres utilisateurs',
'revdelete-suppress'          => 'Supprimer les données des administrateurs et des autres',
'revdelete-hide-image'        => 'Masquer le contenu du fichier',
'revdelete-unsuppress'        => 'Enlever les restrictions sur les versions restaurées',
'revdelete-log'               => 'Commentaire pour le journal :',
'revdelete-submit'            => 'Appliquer à la version sélectionnée',
'revdelete-logentry'          => 'La visibilité de la version a été modifiée pour [[$1]]',
'logdelete-logentry'          => 'La visibilité de l’événement a été modifiée pour [[$1]]',
'revdelete-logaction'         => '$1 {{plural:$1|version changée|versions changées}} en mode $2',
'logdelete-logaction'         => '$1 {{plural:$1|événement de [[$3]] changé|événements de [[$3]] changés}} en mode $2',
'revdelete-success'           => 'Visibilité des versions changées avec succès.',
'logdelete-success'           => 'Visibilité des événements changée avec succès.',

# Oversight log
'oversightlog'    => "Journal d'oversight",
'overlogpagetext' => 'la liste ci-dessous montre les suppressions et blocages récents dont le contenu est masqué même pour les administrateurs.
Consulter la [[Special:Ipblocklist|liste des comptes bloqués]] pour la liste des blocages en cours.',

# History merging
'mergehistory'                     => "Fusion des historiques d'une page",
'mergehistory-header'              => "Cette page vous permet de fusionner les révisions de l'historique d'une page d'origine vers une nouvelle.
Assurez vous que ce changement puisse conserver la continuité de l'historique.

'''Enfin, la version en cours doit être conservée.'''",
'mergehistory-box'                 => 'Fusionner les versions de deux pages :',
'mergehistory-from'                => "Page d'origine :",
'mergehistory-into'                => 'Page de destination :',
'mergehistory-list'                => 'Édition des historiques fusionnables',
'mergehistory-merge'               => "Les versions suivantes de [[:$1]] peuvent être fusionnées avec [[:$2]]. Utilisez le bouton radio  de la colonne pour fusionner uniquement les versions créées du début jusqu'à la date indiquée. Notez bien que l'utilisation des liens de navigation réinitialisera la colonne.",
'mergehistory-go'                  => 'Voir les éditions fusionnables',
'mergehistory-submit'              => 'Fusionner les révisions',
'mergehistory-empty'               => 'Aucune révision ne peut être fusionnée',
'mergehistory-success'             => '$3 {{PLURAL:$3|revision|révisions}} de [[:$1]] {{PLURAL:$3|fusionnée|fusionnées}} avec succès avec [[:$2]].',
'mergehistory-fail'                => 'Impossible de procéder à la fusion des historiques. Resélectionner la page ainsi que les paramètres de date.',
'mergehistory-no-source'           => "La page d'origine $1 n’existe pas.",
'mergehistory-no-destination'      => 'La page de destination $1 n’existe pas.',
'mergehistory-invalid-source'      => 'La page d’origine doit avoir un titre valide.',
'mergehistory-invalid-destination' => 'La page de destination doit avoir un titre valide.',

# Merge log
'mergelog'           => 'Journal des fusions',
'pagemerge-logentry' => "[[$1]] fusionnée avec [[$2]] (révisions jusqu'au $3)",
'revertmerge'        => 'Séparer',
'mergelogpagetext'   => "Voici, ci-dessous, la liste des fusions les plus récentes de l'historique d'une page avec une autre.",

# Diffs
'history-title'           => 'Historique des versions de « $1 »',
'difference'              => '(Différences entre les versions)',
'lineno'                  => 'Ligne $1 :',
'compareselectedversions' => 'Comparer les versions sélectionnées',
'editundo'                => 'défaire',
'diff-multi'              => '({{plural:$1|Une révision intermédiaire masquée|$1 révisions intermédiaires masquées}})',

# Search results
'searchresults'         => 'Résultats de la recherche',
'searchresulttext'      => 'Pour plus d’informations sur la recherche dans {{SITENAME}}, voir [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Vous avez recherché « [[:$1]] »',
'searchsubtitleinvalid' => 'Vous avez recherché « $1 »',
'noexactmatch'          => "'''Aucune page intitulée « $1 » n’existe.''' Vous pouvez [[:$1|créer cet article]].",
'noexactmatch-nocreate' => "'''Il n'existe aucune page intitulée « $1 ».'''",
'titlematches'          => 'Correspondances dans les titres d’articles',
'notitlematches'        => 'Aucun titre d’article ne correspond à la recherche.',
'textmatches'           => 'Correspondances dans le texte d’articles',
'notextmatches'         => 'Aucun texte d’article ne correspond à la recherche.',
'prevn'                 => '$1 précédents',
'nextn'                 => '$1 suivants',
'viewprevnext'          => 'Voir ($1) ($2) ($3).',
'showingresults'        => 'Affichage de <b>$1</b> {{plural:$1|résultat|résultats}} à partir du #<b>$2</b>.',
'showingresultsnum'     => 'Affichage de <b>$3</b> {{plural:$3|résultat|résultats}} à partir du #<b>$2</b>.',
'nonefound'             => '<strong>Note</strong> : l’absence de résultat est souvent due à l’emploi de termes de recherche trop courants, comme « à » ou « de », qui ne sont pas indexés, ou à l’emploi de plusieurs termes de recherche (seules les pages contenant tous les termes apparaissent dans les résultats).',
'powersearch'           => 'Rechercher',
'powersearchtext'       => 'Rechercher dans les espaces de noms :<br />
$1<br />
$2 Inclure les pages de redirection<br /> Rechercher $3 $9',
'searchdisabled'        => 'La recherche sur {{SITENAME}} est désactivée. En attendant la réactivation, vous pouvez effectuer une recherche via Google. Attention, leur indexation du contenu {{SITENAME}} peut ne pas être à jour.',

# Preferences page
'preferences'              => 'Préférences',
'mypreferences'            => 'Préférences',
'prefs-edits'              => 'Nombre d’éditions :',
'prefsnologin'             => 'Non connecté',
'prefsnologintext'         => 'Vous devez être [[Special:Userlogin|connecté]] pour modifier vos préférences d’utilisateur.',
'prefsreset'               => 'Les préférences ont été rétablies à partir de la version enregistrée.',
'qbsettings'               => 'Barre d’outils',
'qbsettings-none'          => 'Aucune',
'qbsettings-fixedleft'     => 'Gauche',
'qbsettings-fixedright'    => 'Droite',
'qbsettings-floatingleft'  => 'Flottante à gauche',
'qbsettings-floatingright' => 'Flottante à droite',
'changepassword'           => 'Modification du mot de passe',
'skin'                     => 'Habillage',
'math'                     => 'Rendu des maths',
'dateformat'               => 'Format de date',
'datedefault'              => 'Aucune préférence',
'datetime'                 => 'Date et heure',
'math_failure'             => 'Erreur math',
'math_unknown_error'       => 'erreur indéterminée',
'math_unknown_function'    => 'fonction inconnue',
'math_lexing_error'        => 'erreur lexicale',
'math_syntax_error'        => 'erreur de syntaxe',
'math_image_error'         => 'La conversion en PNG a échoué ; vérifiez l’installation de Latex, dvips, gs et convert',
'math_bad_tmpdir'          => 'Impossible de créer ou d’écrire dans le répertoire math temporaire',
'math_bad_output'          => 'Impossible de créer ou d’écrire dans le répertoire math de sortie',
'math_notexvc'             => 'L’exécutable « texvc » est introuvable. Lisez math/README pour le configurer.',
'prefs-personal'           => 'Informations personnelles',
'prefs-rc'                 => 'Modifications récentes',
'prefs-watchlist'          => 'Liste de suivi',
'prefs-watchlist-days'     => 'Nombre de jours à afficher dans la liste de suivi :',
'prefs-watchlist-edits'    => 'Nombre de modifications à afficher dans la liste de suivi étendue :',
'prefs-misc'               => 'Préférences diverses',
'saveprefs'                => 'Enregistrer les préférences',
'resetprefs'               => 'Rétablir les préférences',
'oldpassword'              => 'Ancien mot de passe :',
'newpassword'              => 'Nouveau mot de passe :',
'retypenew'                => 'Confirmer le nouveau mot de passe :',
'textboxsize'              => 'Fenêtre de modification',
'rows'                     => 'Rangées :',
'columns'                  => 'Colonnes :',
'searchresultshead'        => 'Recherche',
'resultsperpage'           => 'Nombre de réponses par page :',
'contextlines'             => 'Nombre de lignes par réponse :',
'contextchars'             => 'Nombre de caractères de contexte par ligne :',
'stub-threshold'           => 'Limite supérieure pour les <a href="#" class="stub">liens vers les ébauches</a> (octets) :',
'recentchangesdays'        => 'Nombre de jours à afficher dans les modifications récentes :',
'recentchangescount'       => 'Nombre de modifications à afficher dans les modifications récentes :',
'savedprefs'               => 'Les préférences ont été sauvegardées.',
'timezonelegend'           => 'Fuseau horaire',
'timezonetext'             => 'Nombre d’heures de décalage entre votre heure locale et l’heure du serveur (UTC).',
'localtime'                => 'Heure locale :',
'timezoneoffset'           => 'Décalage horaire¹ :',
'servertime'               => 'Heure du serveur :',
'guesstimezone'            => 'Utiliser la valeur du navigateur',
'allowemail'               => 'Autoriser l’envoi de courriel venant d’autres utilisateurs',
'defaultns'                => 'Rechercher par défaut dans ces espaces de noms',
'default'                  => 'défaut',
'files'                    => 'Fichiers',

# User rights
'userrights-lookup-user'      => 'Gestion des droits utilisateur',
'userrights-user-editname'    => 'Entrez un nom d’utilisateur :',
'editusergroup'               => 'Modification des groupes utilisateurs',
'userrights-editusergroup'    => 'Modifier les groupes de l’utilisateur',
'saveusergroups'              => 'Sauvegarder les groupes utilisateur',
'userrights-groupsmember'     => 'Membre de :',
'userrights-groupsavailable'  => 'Groupes disponibles :',
'userrights-groupshelp'       => 'Choisissez les groupes desquels vous voulez retirer ou rajouter l’utilisateur. Les groupes non sélectionnés ne seront pas modifiés. Vous pouvez désélectionner un groupe avec CTRL + clic gauche.',
'userrights-reason'           => 'Motif du changement :',
'userrights-available-none'   => 'Vous ne pouvez pas changer l’appartenance aux différents groupes.',
'userrights-available-add'    => 'Vous pouvez ajouter des utilisateurs à $1.',
'userrights-available-remove' => 'Vous pouvez enlever des utilisateurs de $1.',
'userrights-no-interwiki'     => "Vous n'êtes pas habilité pour modifier les droits des utilisateurs sur d'autres wikis.",
'userrights-nodatabase'       => "La base de donnée « $1 » n'existe pas ou n'est pas en local.",

# Groups
'group'               => 'Groupe :',
'group-autoconfirmed' => 'Utilisateurs enregistrés',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administrateurs',
'group-bureaucrat'    => 'Bureaucrates',
'group-all'           => 'Tous',

'group-autoconfirmed-member' => 'Utilisateur enregistré',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrateur',
'group-bureaucrat-member'    => 'Bureaucrate',

'grouppage-autoconfirmed' => '{{ns:project}}:Utilisateurs enregistrés',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administrateurs',
'grouppage-bureaucrat'    => '{{ns:project}}:Bureaucrates',

# User rights log
'rightslog'      => 'Historique des modifications de statut',
'rightslogtext'  => 'Ceci est un journal des modifications de statut d’utilisateur.',
'rightslogentry' => 'a modifié les droits de l’utilisateur « $1 » de $2 à $3',
'rightsnone'     => '(aucun)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modification|modifications}}',
'recentchanges'                     => 'Modifications récentes',
'recentchangestext'                 => 'Suivez sur cette page les dernières modifications de {{SITENAME}}.',
'recentchanges-feed-description'    => 'Suivez les dernières modifications de ce wiki dans un flux.',
'rcnote'                            => 'Voici {{PLURAL:$1|la dernière modification|les $1 dernières modifications}} depuis {{PLURAL:$2|le dernier jour|les <b>$2</b> derniers jours}}, déterminée{{PLURAL:$1||s}} ce $3.',
'rcnotefrom'                        => 'Voici les modifications effectuées depuis le <strong>$2</strong> (<b>$1</b> au maximum).',
'rclistfrom'                        => 'Afficher les nouvelles modifications depuis le $1.',
'rcshowhideminor'                   => '$1 modifications mineures',
'rcshowhidebots'                    => '$1 robots',
'rcshowhideliu'                     => '$1 utilisateurs enregistrés',
'rcshowhideanons'                   => '$1 contributions d’IP',
'rcshowhidepatr'                    => '$1 éditions surveillées',
'rcshowhidemine'                    => '$1 mes contributions',
'rclinks'                           => 'Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours<br />$3.',
'diff'                              => 'diff',
'hist'                              => 'hist',
'hide'                              => 'masquer',
'show'                              => 'afficher',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilisateur suivant|utilisateurs suivants}}]',
'rc_categories'                     => 'Limite des catégories (séparation avec « | »)',
'rc_categories_any'                 => 'Toutes',
'newsectionsummary'                 => '/* $1 */ nouvelle section',

# Recent changes linked
'recentchangeslinked'          => 'Suivi des liens',
'recentchangeslinked-title'    => 'Suivi des liens associés à $1',
'recentchangeslinked-noresult' => 'Aucun changement sur les pages liées pendant la période choisie.',
'recentchangeslinked-summary'  => "Cette page spéciale montre les modifications récentes sur les pages qui sont liées. Les pages de votre liste de suivi sont '''en gras'''.",

# Upload
'upload'                      => 'Importer le fichier',
'uploadbtn'                   => 'Importer le fichier',
'reupload'                    => 'Copier à nouveau',
'reuploaddesc'                => 'Retour au formulaire.',
'uploadnologin'               => 'Non connecté(e)',
'uploadnologintext'           => 'Vous devez être [[Special:Userlogin|connecté]] pour copier des fichiers sur le serveur.',
'upload_directory_read_only'  => 'Le serveur Web ne peut écrire dans le dossier cible ($1).',
'uploaderror'                 => 'Erreur',
'uploadtext'                  => 'Utilisez ce formulaire pour copier des fichiers, pour voir ou rechercher des images précédemment copiées consultez la [[Special:Imagelist|liste de fichiers copiés]], les copies et suppressions sont aussi enregistrées dans le [[Special:Log/upload|journal des copies]].

Pour inclure une image dans une page, utilisez un lien de la forme
<b><nowiki>[[</nowiki>{{ns:image}}<nowiki>:fichier.jpg]]</nowiki></b>,
<b><nowiki>[[</nowiki>{{ns:image}}<nowiki>:fichier.png|texte alternatif]]</nowiki></b> or
<b><nowiki>[[</nowiki>{{ns:media}}<nowiki>:fichier.ogg]]</nowiki></b> pour lier directement vers le fichier.',
'upload-permitted'            => 'Formats de fichiers autorisés : $1.',
'upload-preferred'            => 'Formats de fichiers préférés : $1.',
'upload-prohibited'           => 'Formats de fichiers interdits : $1.',
'uploadlog'                   => 'Historique des importations',
'uploadlogpage'               => 'Historique des importations de fichiers multimédia',
'uploadlogpagetext'           => 'Voici la liste des derniers fichiers copiés sur le serveur.',
'filename'                    => 'Nom du fichier',
'filedesc'                    => 'Description',
'fileuploadsummary'           => 'Description :',
'filestatus'                  => "Statut du droit d'auteur",
'filesource'                  => 'Source',
'uploadedfiles'               => 'Fichiers copiés',
'ignorewarning'               => 'Ignorer l’avertissement et sauvegarder le fichier.',
'ignorewarnings'              => 'Ignorer les avertissements lors de l’import',
'minlength1'                  => 'Le noms de fichiers doivent comprendre au moins une lettre.',
'illegalfilename'             => 'Le nom de fichier « $1 » contient des caractères interdits dans les titres de pages. Merci de le renommer et de le copier à nouveau.',
'badfilename'                 => 'L’image a été renommée « $1 ».',
'filetype-badmime'            => 'Les fichiers du type MIME « $1 » ne peuvent pas être importés.',
'filetype-unwanted-type'      => "'''\".\$1\"''' est d'un format non désiré.  Ceux qui sont préférés sont \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' est dans un format non admis.  Ceux qui sont acceptés sont \$2.",
'filetype-missing'            => 'Le fichier n’a aucune extension (comme « .jpg » par exemple).',
'large-file'                  => 'Les fichiers importés ne devraient pas être plus gros que $1 ; ce fichier fait $2.',
'largefileserver'             => 'La taille de ce fichier est supérieure au maximum autorisé.',
'emptyfile'                   => 'Le fichier que vous voulez importer semble vide. Ceci peut-être dû à une erreur dans le nom du fichier. Veuillez vérifier que vous désirez vraiment copier ce fichier.',
'fileexists'                  => 'Un fichier avec ce nom existe déjà. Merci de vérifier $1. Êtes-vous certain de vouloir modifier ce fichier ?',
'fileexists-extension'        => 'Un fichier avec un nom similaire existe déjà :<br />
Nom du fichier à importer : <strong><tt>$1</tt></strong><br />
Nom du fichier existant : <strong><tt>$2</tt></strong><br />
la seule différence est la casse (majuscules / minuscules) de l’extension. Veuillez vérifier que le fichier est différent et changer son nom.',
'fileexists-thumb'            => "<center>'''Image existante'''</center>",
'fileexists-thumbnail-yes'    => 'Le fichier semble être une image en taille réduite <i>(vignette)</i>. Veuillez vérifier le fichier <strong><tt>$1</tt></strong>.<br />
Si le fichier vérifié est la même image (dans une meilleure résolution), il n’y a pas besoin d’importer une version réduite.',
'file-thumbnail-no'           => 'Le nom du fichier commence par <strong><tt>$1</tt></strong>. Il est possible qu’il s’agisse d’une version réduite <i>(vignette)</i>.
Si vous disposez du fichier en haute résolution, importez-le, sinon veuillez changer le nom du fichier.',
'fileexists-forbidden'        => 'Un fichier avec ce nom existe déjà ; merci de retourner en arrière et de copier le fichier sous un nouveau nom. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fichier portant le même nom existe déjà dans la base de données commune ; veuillez revenir en arrière et le renvoyer sous un autre nom. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Copie réussie',
'uploadwarning'               => 'Attention !',
'savefile'                    => 'Sauvegarder le fichier',
'uploadedimage'               => 'a importé « [[$1]] »',
'overwroteimage'              => 'a importé une nouvelle version de « [[$1]] »',
'uploaddisabled'              => 'Désolé, l’import de fichier est désactivé.',
'uploaddisabledtext'          => 'La copie de fichiers est désactivée sur ce wiki.',
'uploadscripted'              => 'Ce fichier contient du code HTML ou un script qui pourrait être interprété de façon incorrecte par un navigateur Internet.',
'uploadcorrupt'               => 'Ce fichier est corrompu, a une taille nulle ou possède une extension invalide.
Veuillez vérifer le fichier.',
'uploadvirus'                 => 'Ce fichier contient un virus ! Pour plus de détails, consultez : $1',
'sourcefilename'              => 'Nom du fichier à envoyer',
'destfilename'                => 'Nom sous lequel le fichier sera enregistré',
'watchthisupload'             => 'Suivre ce fichier',
'filewasdeleted'              => 'Un fichier avec ce nom a déjà été copié, puis supprimé. Vous devriez vérifier le $1 avant de procéder à une nouvelle copie.',
'upload-wasdeleted'           => "'''Attention : Vous êtes en train d'importer un fichier qui a déjà été supprimé auparavant.'''

Vous devriez considérer s'il est opportun de continuer l'import de ce fichier. Le journal des suppressions vous donnera les éléments d'information.",
'filename-bad-prefix'         => 'Le nom du fichier que vous importez commence par <strong>"$1"</strong> qui est un nom généralement donné par les appareils photo numérique et qui ne décrit pas le fichier. Veuillez choisir un nom de fichier décrivant votre fichier.',

'upload-proto-error'      => 'Protocole incorrect',
'upload-proto-error-text' => 'L’import requiert des URLs commençant par <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error'       => 'Erreur interne',
'upload-file-error-text'  => 'Une erreur interne est survenue en voulant créer un fichier temporaire sur le serveur. Veuillez contacter un administrateur système.',
'upload-misc-error'       => 'Erreur d’import inconnue',
'upload-misc-error-text'  => 'Une erreur inconnue est survenue pendant l’import. Veuillez vérifier que l’URL est valide et accessible, puis essayer à nouveau. Si le problème persiste, contactez à un administrateur système.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ne peut pas atteindre l’URL',
'upload-curl-error6-text'  => 'L’URL fournie ne peut pas être atteinte. Veuillez vérifier que l’URL est correcte et que le site est en ligne.',
'upload-curl-error28'      => 'Dépassement du délai lors de l’import',
'upload-curl-error28-text' => 'Le site a mis trop longtemps à répondre. Vérifiez que le site est en ligne, attendez un peu et réessayez. Vous pouvez aussi réessayer à une heure de moindre affluence.',

'license'            => 'Licence',
'nolicense'          => 'Aucune licence sélectionnée',
'license-nopreview'  => '(Prévisualisation impossible)',
'upload_source_url'  => ' (une URL valide et accessible publiquement)',
'upload_source_file' => ' (un fichier sur votre ordinateur)',

# Image list
'imagelist'                 => 'Liste des images',
'imagelisttext'             => "Voici une liste de '''$1''' {{PLURAL:$1|fichier|fichiers}} classée $2.",
'getimagelist'              => 'Récupération de la liste des images',
'ilsubmit'                  => 'Chercher',
'showlast'                  => 'Afficher les $1 dernières images classées $2.',
'byname'                    => 'par nom',
'bydate'                    => 'par date',
'bysize'                    => 'par taille',
'imgdelete'                 => 'suppr',
'imgdesc'                   => 'page de l’image',
'imgfile'                   => 'fichier',
'filehist'                  => 'Historique du fichier',
'filehist-help'             => 'Cliquer sur une date et une heure pour voir le fichier tel qu’il était à ce moment-là',
'filehist-deleteall'        => 'tout supprimer',
'filehist-deleteone'        => 'supprimer ceci',
'filehist-revert'           => 'révoquer',
'filehist-current'          => 'actuel',
'filehist-datetime'         => 'Date et heure',
'filehist-user'             => 'Utilisateur',
'filehist-dimensions'       => 'Dimensions',
'filehist-filesize'         => 'Taille du fichier',
'filehist-comment'          => 'Commentaire',
'imagelinks'                => 'Pages contenant l’image',
'linkstoimage'              => 'Les pages ci-dessous contiennent cette image :',
'nolinkstoimage'            => 'Aucune page ne contient cette image.',
'sharedupload'              => 'Ce fichier est partagé et peut-être utilisé par d’autres projets.',
'shareduploadwiki'          => 'Reportez-vous à la [$1 page de description] pour plus d’informations.',
'shareduploadwiki-linktext' => 'Page de description du fichier',
'noimage'                   => 'Aucun fichier possèdant ce nom n’existe, vous pouvez $1.',
'noimage-linktext'          => 'en importer un',
'uploadnewversion-linktext' => 'Copier une nouvelle version de ce fichier',
'imagelist_date'            => 'Date',
'imagelist_name'            => 'Nom',
'imagelist_user'            => 'Utilisateur',
'imagelist_size'            => 'Octets',
'imagelist_description'     => 'Description',
'imagelist_search_for'      => 'Recherche pour l’image nommée :',

# File reversion
'filerevert'                => 'Révoquer $1',
'filerevert-legend'         => 'Révoquer le fichier',
'filerevert-intro'          => '<span class="plainlinks">Vous allez révoquer \'\'\'[[Media:$1|$1]]\'\'\' jusqu\'à [$4 la version du $2 à $3].</span>',
'filerevert-comment'        => 'Commentaire :',
'filerevert-defaultcomment' => 'Révoqué jusqu’à la version du $1 à $2',
'filerevert-submit'         => 'Révoquer',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' a été révoqué jusqu\'à [$4 la version du $2 à $3].</span>',
'filerevert-badversion'     => 'Il n’y a pas de version plus ancienne du fichier avec le Timestamp donné.',

# File deletion
'filedelete'             => 'Supprime $1',
'filedelete-legend'      => 'Suprimer le fichier',
'filedelete-intro'       => "Vous êtes en train de supprimer '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Vous êtes en train d\'effacer la version de \'\'\'[[Media:$1|$1]]\'\'\' du [$4 $2 à $3].</span>',
'filedelete-comment'     => 'Commentaire :',
'filedelete-submit'      => 'Supprimer',
'filedelete-success'     => "'''$1''' a été supprimé.",
'filedelete-success-old' => '<span class="plainlinks">La version de \'\'\'[[Media:$1|$1]]\'\'\' du $2 à $3 a été supprimée.</span>',
'filedelete-nofile'      => "'''$1''' n'existe pas sur ce site.",
'filedelete-nofile-old'  => "Il n'existe aucune version archivée de '''$1''' avec les attributs indiqués.",
'filedelete-iscurrent'   => 'Vous êtes en train d’essayer de supprimer la version la plus récente de ce fichier. Vous devez, au préalable, rétablir une ancienne version de celui-ci.',

# MIME search
'mimesearch'         => 'Recherche par type MIME',
'mimesearch-summary' => 'Cette page spéciale permet de chercher des fichiers en fonction de leur type MIME. Entrée : type/sous-type, par exemple <tt>image/jpeg</tt>.',
'mimetype'           => 'Type MIME :',
'download'           => 'Téléchargement',

# Unwatched pages
'unwatchedpages' => 'Pages non suivies',

# List redirects
'listredirects' => 'Liste des redirections',

# Unused templates
'unusedtemplates'     => 'Modèles inutilisés',
'unusedtemplatestext' => 'Cette page liste toutes les pages de l’espace de noms « Modèle » qui ne sont inclus dans aucune autre page. N’oubliez pas de vérifier s’il n’y a pas d’autre lien vers les modèles avant de les supprimer.',
'unusedtemplateswlh'  => 'autres liens',

# Random page
'randompage'         => 'Une page au hasard',
'randompage-nopages' => 'Il n’y a aucune page dans cet espace de nom.',

# Random redirect
'randomredirect'         => 'Une page de redirection au hasard',
'randomredirect-nopages' => 'Il n’y a aucune page de redirection dans cet espace de nom.',

# Statistics
'statistics'             => 'Statistiques',
'sitestats'              => 'Statistiques de {{SITENAME}}',
'userstats'              => 'Statistiques utilisateur',
'sitestatstext'          => "La base de données contient actuellement {{PLURAL:$1|'''$1''' page|'''$1''' pages}}.

Ce chiffre inclut les pages « discussion », les pages relatives à {{SITENAME}}, les pages minimales (« ébauches »), les pages de redirection, ainsi que d’autres pages qui ne sont pas considérées comme des articles.
Si l’on exclut ces pages, il reste {{PLURAL:$2|'''$2''' page qui est probablement un véritable article|'''$2''' pages qui sont probablement de véritables articles}}.

{{PLURAL:$8|'''$8''' fichier a été téléchargé|'''$8''' fichiers ont été téléchargés}}.

{{PLURAL:$3|'''$3''' page a été consultée|'''$3''' pages ont été consultées}} et {{PLURAL:$4|'''$4''' page modifiée|'''$4''' pages modifiées}}.

Cela représente une moyenne de {{PLURAL:$5|'''$5''' modification|'''$5''' modifications}} par page et de {{PLURAL:$6|'''$6''' consultation|'''$6''' consultations}} pour une modification.

Il y a {{PLURAL:$7|'''$7''' article|'''$7''' articles}} dans [[meta:Help:Job_queue|la file de tâche]].",
'userstatstext'          => "Il y a {{PLURAL:$1|'''$1''' [[Special:Listusers|utilisateur enregistré]]. Il y a '''$2''' (ou '''$4%''') qui est $5 (voir $3).|'''$1''' [[Special:Listusers|utilisateurs enregistrés]]. Parmi ceux-ci, '''$2''' (ou '''$4%''') sont $5 (voir $3).}}",
'statistics-mostpopular' => 'Pages les plus consultées',

'disambiguations'      => 'Pages d’homonymie',
'disambiguationspage'  => 'Template:Homonymie',
'disambiguations-text' => 'Les pages suivantes lient vers une <i>page d’homonymie</i>. Elles devraient plutôt lier vers une page pertinente.<br /> Une page est traitée comme une page d’homonymie si elle est liée depuis $1.<br /> Les liens depuis d’autres espaces de noms <i>ne sont pas</i> listés ici.',

'doubleredirects'     => 'Doubles redirections',
'doubleredirectstext' => 'Chaque case contient des liens vers la première et la seconde redirection, ainsi que la première ligne de texte de la seconde page, ce qui fournit habituellement la « vraie » page cible, vers laquelle la première redirection devrait rediriger.',

'brokenredirects'        => 'Redirections cassées',
'brokenredirectstext'    => 'Ces redirections mènent vers des pages qui n’existent pas :',
'brokenredirects-edit'   => '(modifier)',
'brokenredirects-delete' => '(supprimer)',

'withoutinterwiki'        => 'Pages sans liens interlangues',
'withoutinterwiki-header' => 'Les pages suivantes ne possèdent pas de liens vers d’autres langues :',

'fewestrevisions' => 'Articles les moins modifiés',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|octet|octets}}',
'ncategories'             => '$1 {{PLURAL:$1|catégorie|catégories}}',
'nlinks'                  => '$1 {{PLURAL:$1|lien|liens}}',
'nmembers'                => '$1 {{PLURAL:$1|page|pages}} à l’intérieur',
'nrevisions'              => '$1 {{PLURAL:$1|révision|révisions}}',
'nviews'                  => '$1 {{PLURAL:$1|consultation|consultations}}',
'specialpage-empty'       => 'Cette page est vide.',
'lonelypages'             => 'Pages orphelines',
'lonelypagestext'         => 'Les pages suivantes ne sont pas liées à partir d’autres pages du wiki.',
'uncategorizedpages'      => 'Pages sans catégorie',
'uncategorizedcategories' => 'Catégories sans catégorie',
'uncategorizedimages'     => 'Images sans catégorie',
'uncategorizedtemplates'  => 'Modèles sans catégorie',
'unusedcategories'        => 'Catégories inutilisées',
'unusedimages'            => 'Images orphelines',
'popularpages'            => 'Pages les plus consultées',
'wantedcategories'        => 'Catégories les plus demandées',
'wantedpages'             => 'Pages les plus demandées',
'mostlinked'              => 'Pages les plus liées',
'mostlinkedcategories'    => 'Catégories les plus utilisées',
'mostlinkedtemplates'     => 'Modèles les plus utilisés',
'mostcategories'          => 'Articles utilisant le plus de catégories',
'mostimages'              => 'Images les plus utilisées',
'mostrevisions'           => 'Articles les plus modifiés',
'allpages'                => 'Toutes les pages',
'prefixindex'             => 'Toutes les pages par premières lettres',
'shortpages'              => 'Pages courtes',
'longpages'               => 'Pages longues',
'deadendpages'            => 'Pages en impasse',
'deadendpagestext'        => 'Les pages suivantes ne contiennent aucun lien vers d’autres pages du wiki.',
'protectedpages'          => 'Pages protégées',
'protectedpagestext'      => 'Les pages suivantes sont protégées contre les modifications et/ou le renommage :',
'protectedpagesempty'     => 'Aucune page n’est protégée actuellement.',
'protectedtitles'         => 'Titres protégés',
'protectedtitlestext'     => 'Les titres suivants sont protégés à la création',
'protectedtitlesempty'    => "Aucun titre n'est actuellement protégé avec ces paramètres.",
'listusers'               => 'Liste des participants',
'specialpages'            => 'Pages spéciales',
'spheading'               => 'Pages spéciales',
'restrictedpheading'      => 'Pages spéciales réservées',
'newpages'                => 'Nouvelles pages',
'newpages-username'       => 'Utilisateur :',
'ancientpages'            => 'Articles les moins récemment modifiés',
'intl'                    => 'Liens interlangues',
'move'                    => 'Renommer',
'movethispage'            => 'Renommer la page',
'unusedimagestext'        => '<p>N’oubliez pas que d’autres sites peuvent contenir un lien direct vers cette image, et que celle-ci peut être placée dans cette liste alors qu’elle est en réalité utilisée.</p>',
'unusedcategoriestext'    => 'Les catégories suivantes existent mais aucun article ou catégorie ne les utilisent.',
'notargettitle'           => 'Pas de cible',
'notargettext'            => 'Indiquez une page cible ou un utilisateur cible.',
'pager-newer-n'           => '{{PLURAL:$1|1 plus récente|$1 plus récentes}}',
'pager-older-n'           => '{{PLURAL:$1|1 plus ancienne|$1 plus anciennes}}',

# Book sources
'booksources'               => 'Ouvrages de référence',
'booksources-search-legend' => 'Rechercher parmi des ouvrages de référence',
'booksources-go'            => 'Valider',
'booksources-text'          => 'Voici une liste de liens vers d’autres sites qui vendent des livres neufs et d’occasion et sur lesquels vous trouverez peut-être des informations sur les ouvrages que vous cherchez. {{SITENAME}} n’étant liée à aucune de ces sociétés, elle n’a aucunement l’intention d’en faire la promotion.',

'categoriespagetext' => 'Les catégories suivantes existent dans le wiki.',
'data'               => 'Données',
'userrights'         => 'Gestion des droits utilisateur',
'groups'             => 'Groupes utilisateurs',
'alphaindexline'     => '$1 à $2',
'version'            => 'Version',

# Special:Log
'specialloguserlabel'  => 'Utilisateur :',
'speciallogtitlelabel' => 'Titre :',
'log'                  => 'Journaux',
'all-logs-page'        => 'Tous les journaux',
'log-search-legend'    => 'Chercher dans les journaux',
'log-search-submit'    => 'OK',
'alllogstext'          => 'Affichage combiné des journaux de copie, suppression, protection, blocage, et administrateur. Vous pouvez restreindre la vue en sélectionnant un type de journal, un nom d’utilisateur ou la page concernée.',
'logempty'             => 'Il n’y a rien dans l’historique pour cette page.',
'log-title-wildcard'   => 'Chercher les titres commençant par le texte suivant',

# Special:Allpages
'nextpage'          => 'Page suivante ($1)',
'prevpage'          => 'Page précédente ($1)',
'allpagesfrom'      => 'Afficher les pages à partir de :',
'allarticles'       => 'Tous les articles',
'allinnamespace'    => 'Toutes les pages (espace de noms $1)',
'allnotinnamespace' => 'Toutes les pages (n’étant pas dans l’espace de noms $1)',
'allpagesprev'      => 'Précédent',
'allpagesnext'      => 'Suivant',
'allpagessubmit'    => 'Valider',
'allpagesprefix'    => 'Afficher les pages commençant par le préfixe :',
'allpagesbadtitle'  => 'Le titre renseigné pour la page est incorrect ou possède un préfixe réservé. Il contient certainement un ou plusieurs caractères spéciaux ne pouvant être utilisés dans les titres.',
'allpages-bad-ns'   => '{{SITENAME}} n’a pas d’espace de noms « $1 ».',

# Special:Listusers
'listusersfrom'      => 'Afficher les utilisateurs à partir de :',
'listusers-submit'   => 'Montrer',
'listusers-noresult' => 'Aucun utilisateur trouvé. Vérifiez aussi les variantes en majuscules / minuscules.',

# E-mail user
'mailnologin'     => 'Pas d’adresse',
'mailnologintext' => 'Vous devez être [[Special:Userlogin|connecté]]
et avoir indiqué une adresse électronique valide dans vos [[Special:Preferences|préférences]]
pour avoir la permission d’envoyer un message à un autre utilisateur.',
'emailuser'       => 'Envoyer un message à cet utilisateur',
'emailpage'       => 'Envoyer un courriel à l’utilisateur',
'emailpagetext'   => 'Si cet utilisateur a indiqué une adresse électronique valide dans ses préférences, le formulaire ci-dessous lui enverra un message.
L’adresse électronique que vous avez indiquée dans vos préférences apparaîtra dans le champ « Expéditeur » de votre message afin que le destinataire puisse vous répondre.',
'usermailererror' => 'Erreur dans le sujet du courriel :',
'defemailsubject' => 'Courriel envoyé depuis {{SITENAME}}',
'noemailtitle'    => 'Pas d’adresse électronique',
'noemailtext'     => 'Vous ne pouvez joindre cet utilisateur par courrier électronique :
* soit parce qu’il n’a pas spécifié d’adresse électronique valide (et authentifiée),
* soit parce qu’il a choisi, dans ses préférences utilisateur, de ne pas recevoir de courrier électronique des autres utilisateurs.',
'emailfrom'       => 'Expéditeur&nbsp;',
'emailto'         => 'Destinataire&nbsp;',
'emailsubject'    => 'Objet&nbsp;',
'emailmessage'    => 'Message&nbsp;',
'emailsend'       => 'Envoyer',
'emailccme'       => 'M’envoyer par courriel une copie de mon message.',
'emailccsubject'  => 'Copie de votre message à $1 : $2',
'emailsent'       => 'Message envoyé',
'emailsenttext'   => 'Votre message a été envoyé.',

# Watchlist
'watchlist'            => 'Liste de suivi',
'mywatchlist'          => 'Liste de suivi',
'watchlistfor'         => "(pour l’utilisateur '''$1''')",
'nowatchlist'          => 'Votre liste de suivi ne contient aucun article.',
'watchlistanontext'    => 'Pour pouvoir afficher ou éditer les éléments de votre liste de suivi, vous devez vous $1.',
'watchnologin'         => 'Non connecté',
'watchnologintext'     => 'Vous devez être [[Special:Userlogin|connecté]] pour modifier votre liste.',
'addedwatch'           => 'Ajouté à la liste de suivi',
'addedwatchtext'       => 'La page « $1 » a été ajoutée à votre [[Special:Watchlist|liste de suivi]].
Les prochaines modifications de cette page et de la page de discussion associée y seront répertoriées, et la page apparaîtra <b>en gras</b> dans les [[Special:Recentchanges|modifications récentes]] pour être repérée plus facilement.',
'removedwatch'         => 'Retirée de la liste de suivi',
'removedwatchtext'     => 'La page « [[:$1]] » a été retirée de votre [[Special:Watchlist|liste de suivi]].',
'watch'                => 'Suivre',
'watchthispage'        => 'Suivre cette page',
'unwatch'              => 'Ne plus suivre',
'unwatchthispage'      => 'Ne plus suivre',
'notanarticle'         => 'Pas un article',
'watchnochange'        => 'Aucune des pages que vous suivez n’a été modifiée pendant la période affichée',
'watchlist-details'    => 'Vous suivez <b>$1</b> {{PLURAL:$1|page|pages}}, sans compter les pages de discussion.',
'wlheader-enotif'      => '* La notification par courriel est activée.',
'wlheader-showupdated' => '* Les pages qui ont été modifiées depuis votre dernière visite sont montrées en <b>gras</b>',
'watchmethod-recent'   => 'vérification des modifications récentes des pages suivies',
'watchmethod-list'     => 'vérification des pages suivies pour des modifications récentes',
'watchlistcontains'    => "Votre liste de suivi contient '''$1''' {{PLURAL:$1|page|pages}}.",
'iteminvalidname'      => 'Problème avec l’article « $1 » : le nom est invalide.',
'wlnote'               => 'Ci-dessous se {{PLURAL:$1|trouve la dernière modification|trouvent les $1 dernières modifications}} depuis {{PLURAL:$2|la dernière heure|les <b>$2</b> dernières heures}}.',
'wlshowlast'           => 'Montrer les dernières $1 heures, les derniers $2 jours, ou $3.',
'watchlist-show-bots'  => 'Afficher les contributions de bots',
'watchlist-hide-bots'  => 'Masquer les contributions de bots',
'watchlist-show-own'   => 'Afficher mes modifications',
'watchlist-hide-own'   => 'Masquer mes modifications',
'watchlist-show-minor' => 'Afficher les modifications mineures',
'watchlist-hide-minor' => 'Masquer les modifications mineures',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Suivi...',
'unwatching' => 'Fin du suivi...',

'enotif_mailer'                => 'Système d’expédition de notification de {{SITENAME}}',
'enotif_reset'                 => 'Marquer toutes les pages comme visitées',
'enotif_newpagetext'           => 'Ceci est une nouvelle page.',
'enotif_impersonal_salutation' => 'Utilisateur de {{SITENAME}}',
'changed'                      => 'modifiée',
'created'                      => 'créée',
'enotif_subject'               => 'La page $PAGETITLE de {{SITENAME}} a été $CHANGEDORCREATED par $PAGEEDITOR',
'enotif_lastvisited'           => 'Consultez $1 pour tous les changements depuis votre dernière visite.',
'enotif_lastdiff'              => 'Consultez $1 pour voir cette modification.',
'enotif_anon_editor'           => 'utilisateur non-enregistré $1',
'enotif_body'                  => 'Cher $WATCHINGUSERNAME,

la page de {{SITENAME}} $PAGETITLE a été $CHANGEDORCREATED le $PAGEEDITDATE par $PAGEEDITOR, voyez $PAGETITLE_URL pour la version actuelle.

$NEWPAGE

Résumé de l’éditeur : $PAGESUMMARY $PAGEMINOREDIT

Contactez l’éditeur :
courriel : $PAGEEDITOR_EMAIL
wiki : $PAGEEDITOR_WIKI

Il n’y aura pas de nouvelles notifications en cas d’autres modifications à moins que vous ne visitiez cette page. Vous pouvez aussi remettre à zéro le notificateur pour toutes les pages de votre liste de suivi.

             Votre {{SITENAME}} système de notification

--
Pour modifier les paramètres de votre liste de suivi, visitez
{{fullurl:Special:Watchlist/edit}}

Retour et assistance :
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Supprimer une page',
'confirm'                     => 'Confirmer',
'excontent'                   => 'contenant « $1 »',
'excontentauthor'             => 'Le contenu était : « $1 » et l’unique contributeur en était « [[Utilisateur:$2|$2]] » ([[Special:Contributions/$2|Contributions]])',
'exbeforeblank'               => 'Contenait avant blanchiment : $1',
'exblank'                     => 'page vide',
'confirmdelete'               => 'Confirmer la suppression',
'deletesub'                   => '(Suppression de « $1 »)',
'historywarning'              => 'Attention, la page que vous êtes sur le point de supprimer a un historique :',
'confirmdeletetext'           => 'Vous êtes sur le point de supprimer définitivement de la base de données une page ou une image, ainsi que toutes ses versions antérieures. Veuillez confirmer que c’est bien là ce que vous voulez faire, que vous en comprenez les conséquences et que vous faites cela en accord avec les [[{{MediaWiki:Policy-url}}|règles internes]].',
'actioncomplete'              => 'Action effectuée',
'deletedtext'                 => '« $1 » a été supprimé.
Voir $2 pour une liste des suppressions récentes.',
'deletedarticle'              => 'a effacé « [[$1]] »',
'dellogpage'                  => 'Historique des suppressions',
'dellogpagetext'              => 'Voici la liste des suppressions récentes.
L’heure indiquée est celle du serveur (UTC).',
'deletionlog'                 => 'journal',
'reverted'                    => 'Rétablissement de la version précédente',
'deletecomment'               => 'Motif de la suppression',
'deleteotherreason'           => 'Motifs supplémentaires ou autres :',
'deletereasonotherlist'       => 'Autre motif',
'deletereason-dropdown'       => "*Motifs de suppression les plus courants
** Demande de l'auteur
** Violation des droits d'auteur
** Vandalisme",
'rollback'                    => 'révoquer modifications',
'rollback_short'              => 'Révoquer',
'rollbacklink'                => 'révoquer',
'rollbackfailed'              => 'La révocation a échoué',
'cantrollback'                => 'Impossible de révoquer : l’auteur est la seule personne à avoir effectué des modifications sur cette page.',
'alreadyrolled'               => 'Impossible de révoquer la dernière modification de l’article « [[$1]] » effectuée par [[User:$2|$2]] ([[User talk:$2|Discussion]]) ; quelqu’un d’autre a déjà modifié ou révoqué l’article. La dernière modification a été effectuée par [[User:$3|$3]] ([[User talk:$3|Discussion]]).',
'editcomment'                 => 'Le résumé de la modification était: <i>« $1 »</i>.', # only shown if there is an edit comment
'revertpage'                  => 'Révocation des modifications de [[Special:Contributions/$2|$2]] (retour à la version précédente de [[Utilisateur:$1|$1]])',
'rollback-success'            => 'Révocation des modifications de $1 ; retour à la version de $2.',
'sessionfailure'              => 'Votre session de connexion semble avoir des problèmes ; cette action a été annulée en prévention d’un piratage de session. Cliquez sur « Précédent » et rechargez la page d’où vous venez, puis réessayez.',
'protectlogpage'              => 'Historique des protections',
'protectlogtext'              => 'Voir les [[{{MediaWiki:Policy-url}}|directives]] pour plus d’information.',
'protectedarticle'            => 'a protégé « $1 »',
'modifiedarticleprotection'   => 'a modifié le niveau de protection de « [[$1]] »',
'unprotectedarticle'          => 'a déprotégé « $1 »',
'protectsub'                  => '(Protéger « $1 »)',
'confirmprotect'              => 'Confirmer la protection',
'protectcomment'              => 'Raison de la protection',
'protectexpiry'               => 'Expiration (n’expire pas par défaut)',
'protect_expiry_invalid'      => 'Le temps d’expiration est invalide',
'protect_expiry_old'          => 'Le temps d’expiration est déjà passé.',
'unprotectsub'                => '(Déprotéger « $1 »)',
'protect-unchain'             => 'Débloquer les permissions de renommage',
'protect-text'                => 'Vous pouvez consulter et modifier le niveau de protection de la page <strong>$1</strong>.
Veuillez vous assurez que vous suivez les [[{{MediaWiki:Policy-url}}|règles internes]].',
'protect-locked-blocked'      => 'Vous ne pouvez pas modifier le niveau de protection tant que vous êtes bloqué. 
Voici les réglages actuels de la page <strong>$1</strong> :',
'protect-locked-dblock'       => 'Le niveau de protection ne peut pas être modifié car la base de données est bloquée.
Voici les réglages actuels de la page <strong>$1</strong> :',
'protect-locked-access'       => 'Vous n’avez pas les droits nécessaires pour modifier la protection de la page.
Voici les réglages actuels de la page <strong>$1</strong> :',
'protect-cascadeon'           => 'Cette page est actuellement protégée car incluse dans {{PLURAL:$1|la page suivante|les pages suivantes}}, {{PLURAL:$1|laquelle a été protégée|lesquelles ont été protégées}} avec l’option « protection en cascade » activée. Vous pouvez changer le niveau de protection de cette page sans que cela n’affecte la protection en cascade.',
'protect-default'             => 'Pas de protection',
'protect-fallback'            => 'Nécessite l’habilitation "$1"',
'protect-level-autoconfirmed' => 'Semi-protection',
'protect-level-sysop'         => 'Administrateurs uniquement',
'protect-summary-cascade'     => 'protection en cascade',
'protect-expiring'            => 'expire le $1',
'protect-cascade'             => 'Protection en cascade - Protège toutes les pages incluses dans celle-ci.',
'protect-cantedit'            => "Vous ne pouvez pas modifier les niveaux de protection de cette page car vous n'avez pas la permission de l'éditer.",
'restriction-type'            => 'Permission :',
'restriction-level'           => 'Niveau de restriction :',
'minimum-size'                => 'Taille minimum',
'maximum-size'                => 'Taille maximum',
'pagesize'                    => '(octets)',

# Restrictions (nouns)
'restriction-edit'   => 'Modification',
'restriction-move'   => 'Renommage',
'restriction-create' => 'Créer',

# Restriction levels
'restriction-level-sysop'         => 'Protection complète',
'restriction-level-autoconfirmed' => 'Semi-protection',
'restriction-level-all'           => 'Tous',

# Undelete
'undelete'                     => 'Voir les pages supprimées',
'undeletepage'                 => 'Voir et restaurer la page supprimée',
'viewdeletedpage'              => 'Historique de la page supprimée',
'undeletepagetext'             => 'Ces pages ont été supprimées et se trouvent dans l’archive. Elles figurent toujours dans la base de données et peuvent être restaurées.
L’archive peut être effacée périodiquement.',
'undeleteextrahelp'            => "Pour restaurer toutes les versions de cette page, laissez vierges toutes les cases à cocher, puis cliquez sur '''''Procéder à la restauration'''''.<br />Pour procéder à une restauration sélective, cochez les cases correspondant aux versions qui sont à restaurer, puis cliquez sur '''''Procéder à la restauration'''''.<br />En cliquant sur le bouton '''''Réinitialiser''''', la boîte de résumé et les cases cochées seront remises à zéro.",
'undeleterevisions'            => '$1 {{PLURAL:$1|révision archivée|révisions archivées}}',
'undeletehistory'              => 'Si vous restaurez la page, toutes les révisions seront replacées dans l’historique.

Si une nouvelle page avec le même nom a été créée depuis la suppression, les révisions restaurées apparaîtront dans l’historique antérieur et la version courante ne sera pas automatiquement remplacée.',
'undeleterevdel'               => 'La restauration ne sera pas effectuée si, au final, la version la plus récente de la page sera partiellement supprimée. Dans ce cas, vous devez déselectionner les versions les plus récentes (en haut). Les versions des fichiers auxquelles vous n’avez pas accès ne seront pas restaurées.',
'undeletehistorynoadmin'       => 'Cet article a été supprimé. Le motif de la suppression est indiqué dans le résumé ci-dessous, avec les détails des utilisateurs qui l’ont modifié avant sa suppression. Le contenu de ces versions n’est accessible qu’aux administrateurs.',
'undelete-revision'            => 'Version supprimée de $1, (révision du $2) par $3 :',
'undeleterevision-missing'     => 'Version invalide ou manquante. Vous avez peut-être un mauvais lien, ou la version a été restaurée ou supprimée de l’archive.',
'undelete-nodiff'              => 'Aucune version précédente trouvée.',
'undeletebtn'                  => 'Restaurer',
'undeletereset'                => 'Réinitialiser',
'undeletecomment'              => 'Résumé :',
'undeletedarticle'             => 'a restauré « [[$1]] »',
'undeletedrevisions'           => '$1 {{PLURAL:$1|version restaurée|versions restaurées}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|version|versions}} et $2 {{PLURAL:$2|fichier|fichiers}} restaurés',
'undeletedfiles'               => '$1 {{PLURAL:$1|fichier restauré|fichiers restaurés}}',
'cannotundelete'               => 'La restauration a échoué. Un autre utilisateur a probablement restauré la page avant.',
'undeletedpage'                => "<big>'''La page $1 a été restaurée.'''</big>

Consultez l’[[Special:Log/delete|historique des effacements]] pour voir la liste des récents effacements et restaurations de pages.",
'undelete-header'              => 'Consultez l’[[Special:Log/delete|historique des suppressions]] pour voir les pages récemment supprimées.',
'undelete-search-box'          => 'Chercher une page supprimée',
'undelete-search-prefix'       => 'Montrer les pages commençant par :',
'undelete-search-submit'       => 'Chercher',
'undelete-no-results'          => 'Aucune page correspondant à la recherche n’a été trouvée dans les archives.',
'undelete-filename-mismatch'   => 'Impossible de restaurer le fichier avec le timestamp $1 : fichier introuvable',
'undelete-bad-store-key'       => 'Impossible de restaurer le fichier avec le timestamp $1 : le fichier était absent avant la suppression.',
'undelete-cleanup-error'       => 'Erreur lors de la suppression de l’archive inutilisée « $1 ».',
'undelete-missing-filearchive' => 'Impossible de restaurer le fichier avec l’ID $1 parce qu’il n’est pas dans la base de données. Il a peut-être déjà été restauré.',
'undelete-error-short'         => 'Erreur lors de la restauration du fichier : $1',
'undelete-error-long'          => 'Des erreurs ont été rencontrées lors de la restauration du fichier :

$1',

# Namespace form on various pages
'namespace'      => 'Espace de noms :',
'invert'         => 'Inverser la sélection',
'blanknamespace' => '(principal)',

# Contributions
'contributions' => 'Contributions de cet utilisateur',
'mycontris'     => 'Contributions',
'contribsub2'   => 'Liste des contributions de $1 ($2). Les pages qui ont été effacées ne sont pas affichées.',
'nocontribs'    => 'Aucune modification correspondant à ces critères n’a été trouvée.',
'ucnote'        => 'Voici les <b>$1</b> dernières modifications effectuées par cet utilisateur au cours des <b>$2</b> derniers jours.',
'uclinks'       => 'Afficher les $1 dernières modifications ; afficher les $2 derniers jours.',
'uctop'         => ' (dernière)',
'month'         => 'À partir du mois (et précédents) :',
'year'          => 'À partir de l’année (et précédentes) :',

'sp-contributions-newbies'     => 'Ne montrer que les contributions des nouveaux utilisateurs',
'sp-contributions-newbies-sub' => 'Liste des contributions des nouveaux utilisateurs. Les pages qui ont été supprimées ne sont pas affichées.',
'sp-contributions-blocklog'    => 'Journal des blocages',
'sp-contributions-search'      => 'Chercher les contributions',
'sp-contributions-username'    => 'Adresse IP ou nom d’utilisateur:',
'sp-contributions-submit'      => 'Chercher',

'sp-newimages-showfrom' => 'Afficher les images importées depuis le $1',

# What links here
'whatlinkshere'       => 'Pages liées',
'whatlinkshere-title' => 'Pages ayant des liens pointant vers $1',
'whatlinkshere-page'  => 'Page :',
'linklistsub'         => '(Liste de liens)',
'linkshere'           => 'Les pages ci-dessous contiennent un lien vers <b>[[:$1]]</b> :',
'nolinkshere'         => 'Aucune page ne contient de lien vers <b>[[:$1]]</b>.',
'nolinkshere-ns'      => "Aucune page ne contient de lien vers '''[[:$1]]''' dans l’espace de nom choisi.",
'isredirect'          => 'page de redirection',
'istemplate'          => 'inclusion',
'whatlinkshere-prev'  => '{{PLURAL:$1|précédent|$1 précédents}}',
'whatlinkshere-next'  => '{{PLURAL:$1|suivant|$1 suivants}}',
'whatlinkshere-links' => '← liens',

# Block/unblock
'blockip'                     => 'Bloquer une adresse IP ou un utilisateur',
'blockiptext'                 => 'Utilisez le formulaire ci-dessous pour bloquer l’accès en écriture à partir d’une adresse IP donnée ou d’un nom d’utilisateur.

Une telle mesure ne doit être prise que pour empêcher le vandalisme et en accord avec les [[{{MediaWiki:Policy-url}}|règles internes]].
Donnez ci-dessous une raison précise (par exemple en indiquant les pages qui ont été vandalisées).',
'ipaddress'                   => 'Adresse IP',
'ipadressorusername'          => 'Adresse IP ou nom d’utilisateur',
'ipbexpiry'                   => 'Durée du blocage',
'ipbreason'                   => 'Motif',
'ipbreasonotherlist'          => 'Autre motif',
'ipbreason-dropdown'          => '
* Motifs de blocage les plus fréquents
** Vandalisme
** Insertion d’informations fausses
** Suppression de contenu sans justification
** Insertion répétée de liens externes publicitaires (spam)
** Insertion de contenu sans aucun sens
** Tentative d’intimidation ou harcèlement
** Abus d’utilisation de comptes multiples
** Nom d’utilisateur inacceptable, injurieux ou diffamant',
'ipbanononly'                 => 'Bloquer uniquement les utilisateurs anonymes',
'ipbcreateaccount'            => 'Empêcher la création de compte',
'ipbemailban'                 => 'Empêcher l’utilisateur d’envoyer des courriels',
'ipbenableautoblock'          => 'Bloquer automatiquement les adresses IP utilisées par cet utilisateur',
'ipbsubmit'                   => 'Bloquer cet utilisateur',
'ipbother'                    => 'Autre durée',
'ipboptions'                  => '2 heures:2 hours,1 jour:1 day,3 jours:3 days,1 semaine:1 week,2 semaines:2 weeks,1 mois:1 month,3 mois:3 months,6 mois:6 months,1 an:1 year,indéfiniment:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'autre',
'ipbotherreason'              => 'Motif différent ou supplémentaire',
'ipbhidename'                 => 'Masquer le nom d’utilisateur ou l’IP du log de blocage, de la liste des blocages actifs et de la liste des utilisateurs',
'badipaddress'                => 'L’adresse IP n’est pas correcte.',
'blockipsuccesssub'           => 'Blocage réussi',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] a été bloqué.<br />Vous pouvez consulter la [[Special:Ipblocklist|liste des comptes et des adresses IP bloqués]].',
'ipb-edit-dropdown'           => 'Modifier les motifs de blocage par défaut',
'ipb-unblock-addr'            => 'Débloquer $1',
'ipb-unblock'                 => 'Débloquer un compte utilisateur ou une adresse IP',
'ipb-blocklist-addr'          => 'Voir les blocages existants pour $1',
'ipb-blocklist'               => 'Voir les blocages existants',
'unblockip'                   => 'Débloquer un utilisateur ou une adresse IP',
'unblockiptext'               => 'Utilisez le formulaire ci-dessous pour rétablir l’accès en écriture
d’une adresse IP précédemment bloquée.',
'ipusubmit'                   => 'Débloquer cette adresse',
'unblocked'                   => '[[User:$1|$1]] a été débloqué',
'unblocked-id'                => 'Le blocage $1 a été enlevé',
'ipblocklist'                 => 'Liste des utilisateurs bloqués',
'ipblocklist-legend'          => 'Chercher un utilisateur bloqué',
'ipblocklist-username'        => 'Nom de l’utilisateur ou adresse IP :',
'ipblocklist-summary'         => 'La liste ci-dessous montre tous les utilisateurs et adresses IP bloqués, par ordre anti-chronologique. Consulter le [[Special:Log/block|journal de blocage]] pour voir les dernières actions de blocage et déblocage effectuées.',
'ipblocklist-submit'          => 'Chercher',
'blocklistline'               => '$1 ($4) : $2 a bloqué $3',
'infiniteblock'               => 'permanent',
'expiringblock'               => 'expire le $1',
'anononlyblock'               => 'utilisateur non enregistré uniquement',
'noautoblockblock'            => 'blocage automatique désactivé',
'createaccountblock'          => 'création de compte bloquée',
'emailblock'                  => 'e-mail bloqué',
'ipblocklist-empty'           => 'La liste des adresses bloquées est actuellement vide.',
'ipblocklist-no-results'      => 'L’adresse IP ou l’utilisateur n’a pas été bloqué.',
'blocklink'                   => 'Bloquer',
'unblocklink'                 => 'débloquer',
'contribslink'                => 'Contributions',
'autoblocker'                 => 'Vous avez été bloqué automatiquement parce que votre adresse IP a été récemment utilisée par « $1 ». La raison fournie pour le blocage de $1 est : « $2 ».',
'blocklogpage'                => 'Historique des blocages',
'blocklogentry'               => 'a bloqué « [[$1]] » - durée : $2 $3',
'blocklogtext'                => 'Ceci est la trace des blocages et déblocages des utilisateurs. Les adresses IP automatiquement bloquées ne sont pas listées. Consultez la [[Special:Ipblocklist|liste des utilisateurs bloqués]] pour voir qui est actuellement effectivement bloqué.',
'unblocklogentry'             => 'a débloqué « $1 »',
'block-log-flags-anononly'    => 'utilisateurs anonymes seulement',
'block-log-flags-nocreate'    => 'création de compte interdite',
'block-log-flags-noautoblock' => 'autoblocage des IP désactivé',
'block-log-flags-noemail'     => 'envoi de courriel interdit',
'range_block_disabled'        => 'Le blocage de plages d’IP a été désactivé',
'ipb_expiry_invalid'          => 'temps d’expiration invalide.',
'ipb_already_blocked'         => '« $1 » est déjà bloqué',
'ipb_cant_unblock'            => 'Erreur : Le blocage d’ID $1 n’existe pas. Il est possible qu’un déblocage ait déjà été effectué.',
'ipb_blocked_as_range'        => "Erreur : L'adresse IP $1 n'a pas été bloquée directement et ne peut donc être débloquée. Elle a été, cependant, bloquée par la plage $2 laquelle peut être débloquée.",
'ip_range_invalid'            => 'Bloc IP incorrect.',
'blockme'                     => 'Bloquez moi',
'proxyblocker'                => 'Bloqueur de proxy',
'proxyblocker-disabled'       => 'Cette fonction est désactivée.',
'proxyblockreason'            => 'Votre adresse IP a été bloquée car il s’agit d’un proxy ouvert. Merci de contacter votre fournisseur d’accès internet ou votre support technique et de l’informer de ce problème de sécurité.',
'proxyblocksuccess'           => 'Terminé.',
'sorbsreason'                 => 'Votre adresse IP est listée en tant que proxy ouvert DNSBL.',
'sorbs_create_account_reason' => 'Votre adresse IP est listée en tant que proxy ouvert DNSBL. Vous ne pouvez créer un compte',

# Developer tools
'lockdb'              => 'Verrouiller la base',
'unlockdb'            => 'Déverrouiller la base',
'lockdbtext'          => 'Le verrouillage de la base de données empêchera tous les utilisateurs de modifier des pages, de sauvegarder leurs préférences, de modifier leur liste de suivi et d’effectuer toutes les autres opérations nécessitant des modifications dans la base de données.
Veuillez confirmer que c’est bien là ce que vous voulez faire et que vous débloquerez la base dès que votre opération de maintenance sera terminée.',
'unlockdbtext'        => 'Le déverrouillage de la base de données permettra à nouveau à tous les utilisateurs de modifier des pages, de mettre à jour leurs préférences et leur liste de suivi, ainsi que d’effectuer les autres opérations nécessitant des modifications dans la base de données.

Veuillez confirmer que c’est bien là ce que vous voulez faire.',
'lockconfirm'         => 'Oui, je confirme que je souhaite verrouiller la base de données.',
'unlockconfirm'       => 'Oui, je confirme que je souhaite déverrouiller la base de données.',
'lockbtn'             => 'Verrouiller la base',
'unlockbtn'           => 'Déverrouiller la base',
'locknoconfirm'       => 'Vous n’avez pas coché la case de confirmation.',
'lockdbsuccesssub'    => 'Verrouillage de la base réussi.',
'unlockdbsuccesssub'  => 'Base déverrouillée.',
'lockdbsuccesstext'   => 'La base de données de {{SITENAME}} est verrouillée.

N’oubliez pas de la déverrouiller lorsque vous aurez terminé votre opération de maintenance.',
'unlockdbsuccesstext' => 'La base de données de {{SITENAME}} est déverrouillée.',
'lockfilenotwritable' => 'Le fichier de blocage de la base de données n’est pas inscriptible. Pour bloquer ou débloquer la base de données, vous devez pouvoir écrire sur le serveur web.',
'databasenotlocked'   => 'La base de données n’est pas verrouillée.',

# Move page
'movepage'                => 'Renommer une page',
'movepagetext'            => "Utilisez le formulaire ci-dessous pour renommer une page, en déplaçant tout son historique vers le nouveau nom.
L’ancien titre deviendra une page de redirection vers le nouveau titre. Les liens vers le titre de l’ancienne page ne seront pas changés ; veuillez vérifier que ce déplacement n’a pas créé de double redirect. Vous devez vous assurez que les liens continuent de pointer vers leur destination supposée.

Une page ne sera pas déplacée si la page du nouveau titre existe déjà, à moins que cette dernière soit vide ou en redirection, et qu’elle n’ait pas d’historique. Ce qui veut dire que vous pouvez renommer une page vers sa position d’origine si vous avez commis une erreur, mais que vous ne pouvez effacer une page déjà existante par ce procédé.

<b>ATTENTION ! </b>
Ceci peut provoquer un changement radical et imprévu pour une page souvent consultée. Assurez-vous d'en avoir compris les conséquences avant de continuer.",
'movepagetalktext'        => 'La page de discussion associée, si présente, sera automatiquement renommée avec <b>sauf si :</b>
*Vous renommez une page vers un autre espace,
*Une page de discussion existe déjà avec le nouveau nom, ou
*Vous avez désélectionné le bouton ci-dessous.

Dans ce cas, vous devrez renommer ou fusionner la page manuellement si vous le désirez.',
'movearticle'             => 'Renommer l’article',
'movenologin'             => 'Non connecté',
'movenologintext'         => 'Pour pouvoir renommer une page, vous devez être [[Special:Userlogin|connecté]] en tant qu’utilisateur enregistré et votre compte doit avoir une ancienneté suffisante.',
'movenotallowed'          => 'Vous n’avez pas la permission de renommer des pages sur ce wiki.',
'newtitle'                => 'Nouveau titre',
'move-watch'              => 'Suivre cette page',
'movepagebtn'             => 'Renommer l’article',
'pagemovedsub'            => 'Renommage réussi',
'movepage-moved'          => 'La page « $1 » <small>([[Special:Whatlinkshere/$3|liens]])</small> a été renommée en « $2 » <small>([[Special:Whatlinkshere/$4|liens]])</small>. 

Veuillez vérifier qu’il n’existe aucune double redirection, et corrigez celles-ci si nécessaire.', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Il existe déjà un article portant ce titre, ou le titre que vous avez choisi n’est pas valide. Veuillez en choisir un autre.',
'cantmove-titleprotected' => "Vous n'avez pas la possibilité de déplacer une page vers cet emplacement car le nouveau titre a été protégé à la création.",
'talkexists'              => 'La page elle-même a été déplacée avec succès, mais la page de discussion n’a pas pu être déplacée car il en existait déjà une sous le nouveau nom. Veuillez les fusionner manuellement.',
'movedto'                 => 'renommé en',
'movetalk'                => 'Renommer aussi la page de discussion associée',
'talkpagemoved'           => 'La page de discussion correspondante a également été déplacée.',
'talkpagenotmoved'        => 'La page de discussion correspondante n’a <strong>pas</strong> été déplacée.',
'1movedto2'               => 'a renommé [[$1]] en [[$2]]',
'1movedto2_redir'         => 'a redirigé [[$1]] vers [[$2]]',
'movelogpage'             => 'Historique des renommages',
'movelogpagetext'         => 'Voici la liste des dernières pages renommées.',
'movereason'              => 'Raison du renommage',
'revertmove'              => 'annuler',
'delete_and_move'         => 'Supprimer et renommer',
'delete_and_move_text'    => '==Suppression requise==

L’article de destination « [[$1]] » existe déjà. Voulez-vous le supprimer pour permettre le renommage ?',
'delete_and_move_confirm' => 'Oui, j’accepte de supprimer la page de destination pour permettre le renommage.',
'delete_and_move_reason'  => 'Page supprimée pour permettre un renommage',
'selfmove'                => 'Les titres d’origine et de destination sont les mêmes : impossible de renommer une page sur elle-même.',
'immobile_namespace'      => 'Le titre de destination est d’un type spécial ; il est impossible de renommer des pages vers cet espace de noms.',

# Export
'export'            => 'Exporter des pages',
'exporttext'        => 'Vous pouvez exporter en XML le texte et l’historique d’une page ou d’un ensemble de pages; le résultat peut alors être importé dans un autre wiki fonctionnant avec le logiciel MediaWiki.

Pour exporter des pages, entrez leurs titres dans la boîte de texte ci-dessous, à raison d’un titre par ligne. Sélectionnez, si vous désirez ou non, la version actuelle avec toutes les anciennes versions, avec la page d’historique, ou simplement la page actuelle avec des informations sur la dernière modification.

Dans ce dernier cas, vous pouvez aussi utiliser un lien, comme [[{{ns:special}}:Export/{{Mediawiki:mainpage}}]] pour la page {{Mediawiki:mainpage}}.',
'exportcuronly'     => 'Exporter uniquement la version courante sans l’historique complet',
'exportnohistory'   => "---- 
'''Note :''' l’exportation complète de l’historique des pages à l’aide de ce formulaire a été désactivée pour des raisons de performances.",
'export-submit'     => 'Exporter',
'export-addcattext' => 'Ajouter les pages de la catégorie :',
'export-addcat'     => 'Ajouter',
'export-download'   => 'Sauvegarder en tant que fichier',

# Namespace 8 related
'allmessages'               => 'Liste des messages système',
'allmessagesname'           => 'Nom du champ',
'allmessagesdefault'        => 'Message par défaut',
'allmessagescurrent'        => 'Message actuel',
'allmessagestext'           => 'Ceci est la liste de tous les messages disponibles dans l’espace MediaWiki',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' n’est pas disponible car '''\$wgUseDatabaseMessages''' est désactivé.",
'allmessagesfilter'         => 'Filtre d’expression rationnelle :',
'allmessagesmodified'       => 'N’afficher que les modifications',

# Thumbnails
'thumbnail-more'           => 'Agrandir',
'missingimage'             => '<b>Image manquante</b><br /><i>$1</i>',
'filemissing'              => 'Fichier absent',
'thumbnail_error'          => 'Erreur lors de la création de la miniature : $1',
'djvu_page_error'          => 'Page DjVu hors limites',
'djvu_no_xml'              => 'Impossible d’obtenir le XML pour le fichier DjVu',
'thumbnail_invalid_params' => 'Paramètres de la miniature invalides',
'thumbnail_dest_directory' => 'Impossible de créer le répertoire de destination',

# Special:Import
'import'                     => 'Importer des pages',
'importinterwiki'            => 'Import inter-wiki',
'import-interwiki-text'      => 'Sélectionnez un wiki et un titre de page à importer.
Les dates des versions et les noms des éditeurs seront préservés.
Toutes les actions d’importation interwiki sont conservées dans le [[Special:Log/import|journal d’import]].',
'import-interwiki-history'   => 'Copier toutes les versions de l’historique de cette page',
'import-interwiki-submit'    => 'Importer',
'import-interwiki-namespace' => 'Transférer les pages dans l’espace de nom :',
'importtext'                 => 'Veuillez exporter le fichier depuis le wiki d’origine en utilisant l’outil Special:Export, le sauvegarder sur votre disque dur et le copier ici.',
'importstart'                => 'Import des pages...',
'import-revision-count'      => '$1 {{PLURAL:$1|version|versions}}',
'importnopages'              => 'Aucune page à importer.',
'importfailed'               => 'Échec de l’import : $1',
'importunknownsource'        => 'Type de la source d’import inconnue',
'importcantopen'             => 'Impossible d’ouvrir le fichier à importer',
'importbadinterwiki'         => 'Mauvais lien interwiki',
'importnotext'               => 'Vide ou sans texte',
'importsuccess'              => 'L’import a réussi !',
'importhistoryconflict'      => 'Un conflit a été détecté dans l’historique des versions (cette page a pu être importée auparavant).',
'importnosources'            => 'Aucune source inter-wiki n’a été définie et la copie directe d’historique est désactivée.',
'importnofile'               => 'Aucun fichier n’a été importé.',
'importuploaderror'          => 'L’import du fichier a échoué : il est possible que celui-ci dépasse la taille autorisée.',

# Import log
'importlogpage'                    => 'Historique des importations de pages',
'importlogpagetext'                => 'Imports administratifs de pages avec l’historique à partir des autres wikis.',
'import-logentry-upload'           => 'a importé (téléchargement) $1',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|version|versions}}',
'import-logentry-interwiki'        => 'a importé (transwiki) $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|version|versions}} depuis $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ma page utilisateur',
'tooltip-pt-anonuserpage'         => 'La page utilisateur de l’IP avec laquelle vous contribuez',
'tooltip-pt-mytalk'               => 'Ma page de discussion',
'tooltip-pt-anontalk'             => 'La page de discussion pour cette adresse IP',
'tooltip-pt-preferences'          => 'Mes préférences',
'tooltip-pt-watchlist'            => 'La liste des pages que vous suivez',
'tooltip-pt-mycontris'            => 'Liste de mes contributions',
'tooltip-pt-login'                => 'Vous êtes invité à vous identifier, mais ce n’est pas obligatoire.',
'tooltip-pt-anonlogin'            => 'Vous êtes invité à vous identifier, mais ce n’est pas obligatoire.',
'tooltip-pt-logout'               => 'Se déconnecter',
'tooltip-ca-talk'                 => 'Discussion à propos de cette page',
'tooltip-ca-edit'                 => 'Vous pouvez modifier cette page. Merci de prévisualiser avant d’enregistrer.',
'tooltip-ca-addsection'           => 'Ajouter un commentaire à cette discussion.',
'tooltip-ca-viewsource'           => 'Cette page est protégée. Vous pouvez toutefois en voir le contenu.',
'tooltip-ca-history'              => 'Les auteurs et versions précédentes de cette page.',
'tooltip-ca-protect'              => 'Protéger cette page',
'tooltip-ca-delete'               => 'Supprimer cette page',
'tooltip-ca-undelete'             => 'Restaurer cette page',
'tooltip-ca-move'                 => 'Renommer cette page',
'tooltip-ca-watch'                => 'Ajoutez cette page à votre liste de suivi',
'tooltip-ca-unwatch'              => 'Retirez cette page de votre liste de suivi',
'tooltip-search'                  => 'Chercher dans ce wiki',
'tooltip-search-go'               => 'Aller vers une page portant exactement ce nom si elle existe.',
'tooltip-search-fulltext'         => 'Rechercher les pages comportant ce texte.',
'tooltip-p-logo'                  => 'Page principale',
'tooltip-n-mainpage'              => 'Visitez la page principale',
'tooltip-n-portal'                => 'À propos du projet',
'tooltip-n-currentevents'         => 'Trouver des informations sur les évènements actuels',
'tooltip-n-recentchanges'         => 'Liste des modifications récentes sur le wiki',
'tooltip-n-randompage'            => 'Afficher une page au hasard',
'tooltip-n-help'                  => 'Aide',
'tooltip-n-sitesupport'           => 'Soutenez le projet',
'tooltip-t-whatlinkshere'         => 'Liste des pages liées à celle-ci',
'tooltip-t-recentchangeslinked'   => 'Liste des modifications récentes des pages liées à celle-ci',
'tooltip-feed-rss'                => 'Flux RSS pour cette page',
'tooltip-feed-atom'               => 'Flux Atom pour cette page',
'tooltip-t-contributions'         => 'Voir la liste des contributions de cet utilisateur',
'tooltip-t-emailuser'             => 'Envoyer un courriel à cet utilisateur',
'tooltip-t-upload'                => 'Importer une image ou fichier média sur le serveur',
'tooltip-t-specialpages'          => 'Liste de toutes les pages spéciales',
'tooltip-t-print'                 => 'Version imprimable de cette page',
'tooltip-t-permalink'             => 'Lien permanent vers cette version de la page',
'tooltip-ca-nstab-main'           => 'Voir l’article',
'tooltip-ca-nstab-user'           => 'Voir la page utilisateur',
'tooltip-ca-nstab-media'          => 'Voir la page du média',
'tooltip-ca-nstab-special'        => 'Ceci est une page spéciale, vous ne pouvez pas la modifier.',
'tooltip-ca-nstab-project'        => 'Voir la page du projet',
'tooltip-ca-nstab-image'          => 'Voir la page de l’image',
'tooltip-ca-nstab-mediawiki'      => 'Voir le message système',
'tooltip-ca-nstab-template'       => 'Voir le modèle',
'tooltip-ca-nstab-help'           => 'Voir la page d’aide',
'tooltip-ca-nstab-category'       => 'Voir la page de la catégorie',
'tooltip-minoredit'               => 'Marquer mes modifications comme mineures',
'tooltip-save'                    => 'Sauvegarder vos modifications',
'tooltip-preview'                 => 'Merci de prévisualiser vos modifications avant de sauvegarder',
'tooltip-diff'                    => 'Permet de visualiser les changements que vous avez effectués',
'tooltip-compareselectedversions' => 'Afficher les différences entre deux versions de cette page',
'tooltip-watch'                   => 'Ajouter cette page à votre liste de suivi',
'tooltip-recreate'                => 'Recréer la page même si celle-ci a été effacée',
'tooltip-upload'                  => 'Lancer l’import',

# Stylesheets
'common.css'   => '/** Le CSS placé ici sera appliqué à toutes les apparences. */',
'monobook.css' => '/* Le CSS placé ici affectera les utilisateurs du skin Monobook */',

# Scripts
'common.js'   => '/* N’importe quel JavaScript ici sera chargé pour n’importe quel utilisateur et pour chaque page accédée. */',
'monobook.js' => '/* Périmé : utilisez [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Les métadonnées « Dublin Core RDF » sont désactivées sur ce serveur.',
'nocreativecommons' => 'Les données méta « Creative Commons RDF » sont désactivées sur ce serveur.',
'notacceptable'     => 'Ce serveur wiki ne peut pas fournir les données dans un format que votre client est capable de lire.',

# Attribution
'anonymous'        => 'Utilisateur(s) non enregistré(s) de {{SITENAME}}',
'siteuser'         => 'Utilisateur $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Cette page a été modifiée pour la dernière fois le $1 à $2 par $3.', # $1 date, $2 time, $3 user
'and'              => 'et',
'othercontribs'    => 'Basé sur le travail de $1.',
'others'           => 'autres',
'siteusers'        => 'Utilisateur(s) $1',
'creditspage'      => 'Page de crédits',
'nocredits'        => 'Il n’y a pas d’informations d’attribution disponibles pour cette page.',

# Spam protection
'spamprotectiontitle'    => 'Page automatiquement protégée pour cause de pourriel',
'spamprotectiontext'     => 'La page que vous avez tenté de sauvegarder a été bloquée par le filtre anti-pourriel. Ceci est probablement causé par un lien vers un site externe.',
'spamprotectionmatch'    => "La chaîne de caractères « '''$1''' » a déclenché le détecteur de pourriel.",
'subcategorycount'       => '{{PLURAL:$1|Une sous-catégorie est listée |$1 sous-catégories sont listées}} ci-dessous. Si un lien « (200 précédents) » ou « (200 suivants) » est présent ci-dessus, il peut mener à d’autres sous-catégories.',
'categoryarticlecount'   => 'Il y a {{PLURAL:$1|un article|$1 articles}} dans cette catégorie.',
'category-media-count'   => 'Il y a {{plural:$1|un fichier|$1 fichiers}} multimédia dans cette catégorie.',
'listingcontinuesabbrev' => '(suite)',
'spambot_username'       => 'Nettoyage de pourriels par MediaWiki',
'spam_reverting'         => 'Restauration de la dernière version ne contenant pas de lien vers $1',
'spam_blanking'          => 'Toutes les versions contenant des liens vers $1 sont blanchies',

# Info page
'infosubtitle'   => 'Informations pour la page',
'numedits'       => 'Nombre de modifications : $1',
'numtalkedits'   => 'Nombre de modifications (page de discussion) : $1',
'numwatchers'    => 'Nombre de contributeurs ayant la page dans leur liste de suivi : $1',
'numauthors'     => 'Nombre d’auteurs distincts : $1',
'numtalkauthors' => 'Nombre d’auteurs distincts (page de discussion) : $1',

# Math options
'mw_math_png'    => 'Toujours produire une image PNG',
'mw_math_simple' => 'HTML si très simple, autrement PNG',
'mw_math_html'   => 'HTML si possible, autrement PNG',
'mw_math_source' => 'Laisser le code TeX original',
'mw_math_modern' => 'Pour les navigateurs modernes',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Marquer comme n’étant pas un vandalisme',
'markaspatrolledtext'                 => 'Marquer cet article comme non vandalisé',
'markedaspatrolled'                   => 'Marqué comme non vandalisé',
'markedaspatrolledtext'               => 'La version sélectionnée a été marquée comme non vandalisée.',
'rcpatroldisabled'                    => 'La fonction de patrouille des modifications récentes n’est pas activée.',
'rcpatroldisabledtext'                => 'La fonctionnalité de surveillance des modifications récentes n’est pas activée.',
'markedaspatrollederror'              => 'Ne peut être marqué comme non vandalisé',
'markedaspatrollederrortext'          => 'Vous devez sélectionner une version pour pouvoir la marquer comme non vandalisée.',
'markedaspatrollederror-noautopatrol' => 'Vous n’avez pas le droit de marquer vos propres modifications comme surveillées.',

# Patrol log
'patrol-log-page' => 'Historique des versions patrouillées',
'patrol-log-line' => 'a marqué la version $1 de $2 comme vérifiée $3',
'patrol-log-auto' => '(automatique)',
'patrol-log-diff' => '$1',

# Image deletion
'deletedrevision'                 => 'L’ancienne version $1 a été supprimée',
'filedeleteerror-short'           => 'Erreur lors de la suppression du fichier : $1',
'filedeleteerror-long'            => 'Des erreurs ont été rencontrées lors de la suppression du fichier :\n\n$1\n',
'filedelete-missing'              => 'Le fichier « $1 » ne peut pas être supprimé parce qu’il n’existe pas.',
'filedelete-old-unregistered'     => 'La révision du fichier spécifié « $1 » n’est pas dans la base de données.',
'filedelete-current-unregistered' => 'Le fichier spécifié « $1 » n’est pas dans la base de données.',
'filedelete-archive-read-only'    => 'Le dossier d’archivage « $1 » n’est pas modifiable par le serveur.',

# Browsing diffs
'previousdiff' => '← Différence précédente',
'nextdiff'     => 'Différence suivante →',

# Media information
'mediawarning'         => '<b>Attention</b>: Ce fichier peut contenir du code malveillant, votre système pouvant être mis en danger par son exécution.
<hr />',
'imagemaxsize'         => 'Format maximal pour les images dans les pages de description d’images :',
'thumbsize'            => 'Taille de la miniature :',
'widthheightpage'      => '$1×$2, $3 pages',
'file-info'            => 'Taille du fichier : $1, type MIME : $2',
'file-info-size'       => '($1 × $2 pixels, taille du fichier : $3, type MIME : $4)',
'file-nohires'         => '<small>Pas de plus haute résolution disponible.</small>',
'svg-long-desc'        => '(Fichier SVG, résolution de $1 × $2 pixels, taille : $3)',
'show-big-image'       => 'Image en plus haute résolution',
'show-big-image-thumb' => '<small>Taille de cet aperçu : $1 × $2 pixels</small>',

# Special:Newimages
'newimages'    => 'Galerie des nouveaux fichiers',
'showhidebots' => '($1 bots)',
'noimages'     => 'Aucune image à afficher.',

# Bad image list
'bad_image_list' => "Le format est le suivant :

Seulement les lignes commençant par * sont prises en compte. Le premier lien de la ligne est celui vers une mauvaise image.
Les autres liens sur la même ligne sont considérés comme des exceptions, par exemple des articles sur lesquels l'image doit apparaître.",

# Metadata
'metadata'          => 'Métadonnées',
'metadata-help'     => 'Ce fichier contient des informations supplémentaires probablement ajoutées par l’appareil photo ou le scanner qui l’a produite. Si le fichier a été modifié, certains détails peuvent ne pas refléter l’image modifiée.',
'metadata-expand'   => 'Montrer les informations détaillées',
'metadata-collapse' => 'Cacher les informations détaillées',
'metadata-fields'   => 'Les champs de métadonnées d’EXIF listés dans ce message seront inclus dans la page de description de l’image quand la table de métadonnées sera réduite. Les autres champs seront cachés par défaut.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Largeur',
'exif-imagelength'                 => 'Hauteur',
'exif-bitspersample'               => 'Bits par échantillon',
'exif-compression'                 => 'Type de compression',
'exif-photometricinterpretation'   => 'Modèle colorimétrique',
'exif-orientation'                 => 'Orientation',
'exif-samplesperpixel'             => 'Composantes par pixel',
'exif-planarconfiguration'         => 'Arrangement des données',
'exif-ycbcrsubsampling'            => 'Taux d’échantillonnage des composantes de la chrominance',
'exif-ycbcrpositioning'            => 'Positionnement YCbCr',
'exif-xresolution'                 => 'Résolution horizontale',
'exif-yresolution'                 => 'Résolution verticale',
'exif-resolutionunit'              => 'Unité de résolution',
'exif-stripoffsets'                => 'Emplacement des données de l’image',
'exif-rowsperstrip'                => 'Nombre de lignes par bande',
'exif-stripbytecounts'             => 'Taille en octets par bande',
'exif-jpeginterchangeformat'       => 'Position du SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Taille en octets des données JPEG',
'exif-transferfunction'            => 'Fonction de transfert',
'exif-whitepoint'                  => 'Chromaticité du point blanc',
'exif-primarychromaticities'       => 'Chromaticité des primaires',
'exif-ycbcrcoefficients'           => 'Coefficients YCbCr',
'exif-referenceblackwhite'         => 'Valeurs de référence noir et blanc',
'exif-datetime'                    => 'Date et heure de changement du fichier',
'exif-imagedescription'            => 'Description de l’image',
'exif-make'                        => 'Fabricant de l’appareil',
'exif-model'                       => 'Modèle de l’appareil',
'exif-software'                    => 'Logiciel utilisé',
'exif-artist'                      => 'Auteur',
'exif-copyright'                   => "Détenteur du droit d'auteur",
'exif-exifversion'                 => 'Version EXIF',
'exif-flashpixversion'             => 'Version FlashPix',
'exif-colorspace'                  => 'Espace colorimétrique',
'exif-componentsconfiguration'     => 'Configuration des composantes',
'exif-compressedbitsperpixel'      => 'Taux de compression de l’image',
'exif-pixelydimension'             => 'Hauteur d’image valide',
'exif-pixelxdimension'             => 'Largeur d’image valide',
'exif-makernote'                   => 'Notes du fabricant',
'exif-usercomment'                 => 'Commentaires de l’utilisateur',
'exif-relatedsoundfile'            => 'Fichier audio associé',
'exif-datetimeoriginal'            => 'Date et heure de la génération de données',
'exif-datetimedigitized'           => 'Date et heure de numérisation',
'exif-subsectime'                  => 'Date de dernière modification',
'exif-subsectimeoriginal'          => 'Date de la prise originelle',
'exif-subsectimedigitized'         => 'Date de la numérisation',
'exif-exposuretime'                => 'Temps d’exposition',
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => 'Nombre f',
'exif-exposureprogram'             => 'Programme d’exposition',
'exif-spectralsensitivity'         => 'Sensitivité spectrale',
'exif-isospeedratings'             => 'Sensibilité ISO',
'exif-oecf'                        => 'Fonction de conversion opto-électronique',
'exif-shutterspeedvalue'           => 'Vitesse d’obturation',
'exif-aperturevalue'               => 'Ouverture',
'exif-brightnessvalue'             => 'Luminosité',
'exif-exposurebiasvalue'           => 'Correction d’exposition',
'exif-maxaperturevalue'            => 'Ouverture maximale',
'exif-subjectdistance'             => 'Distance du sujet',
'exif-meteringmode'                => 'Mode de mesure',
'exif-lightsource'                 => 'Source de lumière',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Longueur focale',
'exif-subjectarea'                 => 'Emplacement du sujet',
'exif-flashenergy'                 => 'Énergie du flash',
'exif-spatialfrequencyresponse'    => 'Fréquence spatiale',
'exif-focalplanexresolution'       => 'Résolution X focale plane',
'exif-focalplaneyresolution'       => 'Résolution Y focale plane',
'exif-focalplaneresolutionunit'    => 'Unité de résolution de focale plane',
'exif-subjectlocation'             => 'Position du sujet',
'exif-exposureindex'               => 'Index d’exposition',
'exif-sensingmethod'               => 'Type de capteur',
'exif-filesource'                  => 'Source du fichier',
'exif-scenetype'                   => 'Type de scène',
'exif-cfapattern'                  => 'Matrice de filtrage de couleur',
'exif-customrendered'              => 'Rendu personnalisé',
'exif-exposuremode'                => 'Mode d’exposition',
'exif-whitebalance'                => 'Balance des blancs',
'exif-digitalzoomratio'            => 'Taux d’agrandissement numérique (zoom)',
'exif-focallengthin35mmfilm'       => 'Longueur de focale pour un film 35 mm',
'exif-scenecapturetype'            => 'Type de capture de la scène',
'exif-gaincontrol'                 => 'Contrôle de luminosité',
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturation',
'exif-sharpness'                   => 'Netteté',
'exif-devicesettingdescription'    => 'Description de la configuration du dispositif',
'exif-subjectdistancerange'        => 'Distance du sujet',
'exif-imageuniqueid'               => 'Identifiant unique de l’image',
'exif-gpsversionid'                => 'Version du tag GPS',
'exif-gpslatituderef'              => 'Référence pour la latitude',
'exif-gpslatitude'                 => 'Latitude',
'exif-gpslongituderef'             => 'Référence pour la longitude',
'exif-gpslongitude'                => 'Longitude',
'exif-gpsaltituderef'              => 'Référence d’altitude',
'exif-gpsaltitude'                 => 'Altitude',
'exif-gpstimestamp'                => 'Heure GPS (horloge atomique)',
'exif-gpssatellites'               => 'Satellites utilisés pour la mesure',
'exif-gpsstatus'                   => 'Statut récepteur',
'exif-gpsmeasuremode'              => 'Mode de mesure',
'exif-gpsdop'                      => 'Précision de la mesure',
'exif-gpsspeedref'                 => 'Unité de vitesse du récepteur GPS',
'exif-gpsspeed'                    => 'Vitesse du récepteur GPS',
'exif-gpstrackref'                 => 'Référence pour la direction du mouvement',
'exif-gpstrack'                    => 'Direction du mouvement',
'exif-gpsimgdirectionref'          => 'Référence pour l’orientation de l’image',
'exif-gpsimgdirection'             => 'Direction de l’image',
'exif-gpsmapdatum'                 => 'Système géodésique utilisé',
'exif-gpsdestlatituderef'          => 'Référence pour la latitude de la destination',
'exif-gpsdestlatitude'             => 'Latitude de la destination',
'exif-gpsdestlongituderef'         => 'Référence pour la longitude de la destination',
'exif-gpsdestlongitude'            => 'Longitude de la destination',
'exif-gpsdestbearingref'           => 'Référence pour le relèvement de la destination',
'exif-gpsdestbearing'              => 'Relèvement de la destination',
'exif-gpsdestdistanceref'          => 'Référence pour la distance de la destination',
'exif-gpsdestdistance'             => 'Distance à la destination',
'exif-gpsprocessingmethod'         => 'Nom de la méthode de traitement du GPS',
'exif-gpsareainformation'          => 'Nom de la zone GPS',
'exif-gpsdatestamp'                => 'Date GPS',
'exif-gpsdifferential'             => 'Correction différentielle GPS',

# EXIF attributes
'exif-compression-1' => 'Non compressé',

'exif-unknowndate' => 'Date inconnue',

'exif-orientation-1' => 'Normale', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Inversée horizontalement', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Tournée de 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Inversée verticalement', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Tournée de 90° à gauche et inversée verticalement', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Tournée de 90° à droite', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Tournée de 90° à droite et inversée verticalement', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Tournée de 90° à gauche', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Données contiguës',
'exif-planarconfiguration-2' => 'Données séparées',

'exif-colorspace-ffff.h' => 'Non calibré',

'exif-componentsconfiguration-0' => 'N’existe pas',

'exif-exposureprogram-0' => 'Indéfini',
'exif-exposureprogram-1' => 'Manuel',
'exif-exposureprogram-2' => 'Programme normal',
'exif-exposureprogram-3' => 'Priorité à l’ouverture',
'exif-exposureprogram-4' => 'Priorité à l’obturateur',
'exif-exposureprogram-5' => 'Programme création (préférence à la profondeur de champ)',
'exif-exposureprogram-6' => 'Programme action (préférence à la vitesse d’obturation)',
'exif-exposureprogram-7' => 'Mode portrait (pour clichés de près avec arrière-plan flou)',
'exif-exposureprogram-8' => 'Mode paysage (pour des clichés de paysages nets)',

'exif-subjectdistance-value' => '{{PLURAL:$1|$1 mètre|$1 mètres}}',

'exif-meteringmode-0'   => 'Inconnu',
'exif-meteringmode-1'   => 'Moyenne',
'exif-meteringmode-2'   => 'Mesure centrale moyenne',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Palette',
'exif-meteringmode-6'   => 'Partiel',
'exif-meteringmode-255' => 'Autre',

'exif-lightsource-0'   => 'Inconnue',
'exif-lightsource-1'   => 'Lumière du jour',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungstène (lumière incandescente)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Temps clair',
'exif-lightsource-10'  => 'Temps nuageux',
'exif-lightsource-11'  => 'Ombre',
'exif-lightsource-12'  => 'Éclairage fluorescent « lumière du jour » (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Éclairage fluorescent blanc « jour » (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Éclairage fluorescent blanc « froid » (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Éclairage fluorescent blanc (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lumière standard A',
'exif-lightsource-18'  => 'Lumière standard B',
'exif-lightsource-19'  => 'Lumière standard C',
'exif-lightsource-24'  => 'Tungstène ISO de studio',
'exif-lightsource-255' => 'Autre source de lumière',

'exif-focalplaneresolutionunit-2' => 'pouces',

'exif-sensingmethod-1' => 'Non défini',
'exif-sensingmethod-2' => 'Capteur de zone de couleurs monochromatiques',
'exif-sensingmethod-3' => 'Capteur de zone de couleurs bichromatiques',
'exif-sensingmethod-4' => 'Capteur de zone de couleurs trichromatiques',
'exif-sensingmethod-5' => 'Capteur couleur séquenciel',
'exif-sensingmethod-7' => 'Capteur trilinéaire',
'exif-sensingmethod-8' => 'Capteur couleur linéaire séquentiel',

'exif-filesource-3' => 'Appareil photographique numérique',

'exif-scenetype-1' => 'Image directement photographiée',

'exif-customrendered-0' => 'Procédé normal',
'exif-customrendered-1' => 'Procédé personnalisé',

'exif-exposuremode-0' => 'Automatique',
'exif-exposuremode-1' => 'Manuelle',
'exif-exposuremode-2' => 'Mise entre parenthèses automatique',

'exif-whitebalance-0' => 'Automatique',
'exif-whitebalance-1' => 'Manuelle',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Paysage',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Scène de nuit',

'exif-gaincontrol-0' => 'Aucun',
'exif-gaincontrol-1' => 'Augmentation faible de l’acquisition',
'exif-gaincontrol-2' => 'Augmentation forte de l’acquisition',
'exif-gaincontrol-3' => 'Réduction faible de l’acquisition',
'exif-gaincontrol-4' => 'Réduction forte de l’acquisition',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Faible',
'exif-contrast-2' => 'Fort',

'exif-saturation-0' => 'Normale',
'exif-saturation-1' => 'Faible',
'exif-saturation-2' => 'Élevée',

'exif-sharpness-0' => 'Normale',
'exif-sharpness-1' => 'Douce',
'exif-sharpness-2' => 'Dure',

'exif-subjectdistancerange-0' => 'Inconnue',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Rapproché',
'exif-subjectdistancerange-3' => 'Distant',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Nord',
'exif-gpslatitude-s' => 'Sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Est',
'exif-gpslongitude-w' => 'Ouest',

'exif-gpsstatus-a' => 'Mesure en cours',
'exif-gpsstatus-v' => 'Interopérabilité de la mesure',

'exif-gpsmeasuremode-2' => 'Mesure à 2 dimensions',
'exif-gpsmeasuremode-3' => 'Mesure à 3 dimensions',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilomètre par heure',
'exif-gpsspeed-m' => 'Mile par heure',
'exif-gpsspeed-n' => 'Nœud',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direction vraie',
'exif-gpsdirection-m' => 'Nord magnétique',

# External editor support
'edit-externally'      => 'Modifier ce fichier en utilisant un application externe',
'edit-externally-help' => 'Voir [http://meta.wikimedia.org/wiki/Help:External_editors les instructions] pour plus d’informations.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'toutes',
'imagelistall'     => 'toutes',
'watchlistall2'    => 'tout',
'namespacesall'    => 'Tous',
'monthsall'        => 'tous',

# E-mail address confirmation
'confirmemail'            => 'Confirmer adresse de courriel',
'confirmemail_noemail'    => 'L’adresse de courriel configurée dans vos [[Special:Preferences|préférences]] n’est pas valide.',
'confirmemail_text'       => 'Ce wiki nécessite la vérification de votre adresse de courriel avant de pouvoir utiliser toute fonction de messagerie. Utilisez le bouton ci-dessous pour envoyer un courriel de confirmation à votre adresse. Le courriel contiendra un lien contenant un code, chargez ce lien dans votre navigateur pour valider votre adresse.',
'confirmemail_pending'    => '<div class="error"> Un code de confirmation vous a déjà été envoyé par e-mail ; si vous venez de créer votre compte, veuillez attendre quelques minutes que l’e-mail arrive avant de demander un nouveau code. </div>',
'confirmemail_send'       => 'Envoyer un code de confirmation',
'confirmemail_sent'       => 'Courriel de confirmation envoyé',
'confirmemail_oncreate'   => 'Un code de confirmation a été envoyé à votre adresse e-mail. Ce code n’est pas requis pour se connecter, mais vous en aurez besoin pour activer les fonctionnalités liées aux e-mails sur ce wiki.',
'confirmemail_sendfailed' => 'Impossible d’envoyer le courriel de confirmation. Vérifiez votre adresse.

Retour du programme de courriel : $1',
'confirmemail_invalid'    => 'Code de confirmation incorrect. Celui-ci a peut-être expiré',
'confirmemail_needlogin'  => 'Vous devez vous $1 pour confirmer votre adresse de courriel.',
'confirmemail_success'    => 'Votre adresse de courriel est confirmée. Vous pouvez maintenant vous connecter et profiter du wiki.',
'confirmemail_loggedin'   => 'Votre adresse est maintenant confirmée',
'confirmemail_error'      => 'Un problème est survenu en voulant enregistrer votre confirmation',
'confirmemail_subject'    => 'Confirmation d’adresse de courriel pour {{SITENAME}}',
'confirmemail_body'       => 'Quelqu’un, probablement vous avec l’adresse IP $1, a enregistré un compte « $2 » avec cette adresse de courriel sur le site {{SITENAME}}.

Pour confirmer que ce compte vous appartient vraiment et activer les fonctions de messagerie sur {{SITENAME}}, veuillez suivre le lien ci dessous dans votre navigateur :

$3

S’il ne s’agit pas de vous, n’ouvrez pas le lien. Ce code de confirmation expirera le $4.',

# Scary transclusion
'scarytranscludedisabled' => '[La transclusion interwiki est désactivée]',
'scarytranscludefailed'   => '[La récupération de modèle a échoué pour $1 ; désolé]',
'scarytranscludetoolong'  => '[L’URL est trop longue ; désolé]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks"> Rétroliens vers cet article :<br /> $1 </div>',
'trackbackremove'   => '([$1 Supprimer])',
'trackbacklink'     => 'Rétrolien',
'trackbackdeleteok' => 'Le rétrolien a été supprimé avec succès.',

# Delete conflict
'deletedwhileediting' => 'Attention : cette page a été supprimée après que vous avez commencé à la modifier.',
'confirmrecreate'     => "L’utilisateur [[User:$1|$1]] ([[User talk:$1|Discussion]]) a supprimé cette page, alors que vous aviez commencé à l’éditer, pour le motif suivant : 
: ''$2'' 
Veuillez confirmer que vous désirez recréer cet article.",
'recreate'            => 'Recréer',

# HTML dump
'redirectingto' => 'Redirection vers [[$1]]',

# action=purge
'confirm_purge'        => 'Voulez-vous rafraîchir cette page (purger le cache) ? $1',
'confirm_purge_button' => 'Confirmer',

# AJAX search
'searchcontaining' => 'Chercher les articles contenant « $1 ».',
'searchnamed'      => 'Chercher les articles nommés « $1 ».',
'articletitles'    => 'Articles commençant par « $1 »',
'hideresults'      => 'Cacher les résultats',
'useajaxsearch'    => 'Utiliser la recherche AJAX',

# Multipage image navigation
'imgmultipageprev'   => '← page précédente',
'imgmultipagenext'   => 'page suivante →',
'imgmultigo'         => 'Accéder !',
'imgmultigotopre'    => 'Accéder à la page',
'imgmultiparseerror' => 'Ce fichier image est apparemment corrompu ou incorrect, et {{SITENAME}} ne peut pas fournir une liste des pages.',

# Table pager
'ascending_abbrev'         => 'crois',
'descending_abbrev'        => 'décr',
'table_pager_next'         => 'Page suivante',
'table_pager_prev'         => 'Page précédente',
'table_pager_first'        => 'Première page',
'table_pager_last'         => 'Dernière page',
'table_pager_limit'        => 'Montrer $1 éléments par page',
'table_pager_limit_submit' => 'Accéder',
'table_pager_empty'        => 'Aucun résultat',

# Auto-summaries
'autosumm-blank'   => 'Résumé automatique : blanchiment',
'autosumm-replace' => 'Résumé automatique : contenu remplacé par « $1 ».',
'autoredircomment' => 'Redirection vers [[$1]]',
'autosumm-new'     => 'Nouvelle page : $1',

# Size units
'size-bytes'     => '$1 o',
'size-kilobytes' => '$1 ko',
'size-megabytes' => '$1 Mo',
'size-gigabytes' => '$1 Go',

# Live preview
'livepreview-loading' => 'Chargement …',
'livepreview-ready'   => 'Chargement … terminé !',
'livepreview-failed'  => 'L’aperçu rapide a échoué !
Essayez la prévisualisation normale.',
'livepreview-error'   => 'Impossible de se connecter : $1 "$2"
Essayez la prévisualisation normale.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Les modifications datant de moins de $1 secondes peuvent ne pas apparaître dans cette liste.',
'lag-warn-high'   => 'En raison d’une forte charge des bases de données, les modifications datant de moins de $1 secondes peuvent ne pas apparaître dans cette liste.',

# Watchlist editor
'watchlistedit-numitems'       => 'Votre liste de suivi contient {{PLURAL:$1|une page|$1 pages}}, sans compter les pages de discussion',
'watchlistedit-noitems'        => 'Votre liste de suivi ne contient aucune page.',
'watchlistedit-normal-title'   => 'Modification de la liste de suivi',
'watchlistedit-normal-legend'  => 'Enlever des pages de la liste de suivi',
'watchlistedit-normal-explain' => 'Les pages de votre liste de suivi sont visibles ci-dessous, classées par espace de noms. Pour enlever une page (et sa page de discussion) de la liste, sélectionnez la case à côté puis cliquez sur le bouton en bas. Vous pouvez aussi [[Special:Watchlist/raw|la modifier en mode brut]] ou [[Special:Watchlist/clear|la vider entièrement]].',
'watchlistedit-normal-submit'  => 'Enlever les pages sélectionnées',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Une page a été enlevée|$1 pages ont été enlevées}} de votre liste de suivi :',
'watchlistedit-raw-title'      => 'Modification de la liste de suivi (mode brut)',
'watchlistedit-raw-legend'     => 'Modification de la liste de suivi en mode brut',
'watchlistedit-raw-explain'    => 'La liste des pages de votre liste de suivi est montrée ci-dessous, sans les pages de discussion (automatiquement incluses) et triées par espace de noms. Vous pouvez modifier la liste : ajoutez les pages que vous voulez suivre (peu importe où), une page par ligne, et enlevez les pages que vous ne voulez plus suivre. Quand vous avez fini, cliquez sur le bouton en bas pour mettre la liste à jour. Vous pouvez aussi utiliser [[Special:Watchlist/edit|l’éditeur normal]].',
'watchlistedit-raw-titles'     => 'Pages :',
'watchlistedit-raw-submit'     => 'Mettre à jour la liste',
'watchlistedit-raw-done'       => 'Votre liste de suivi a été mise à jour.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Une page a été ajoutée|$1 pages ont été ajoutées}} :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Une page a été enlevée|$1 pages ont été enlevées}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Liste de suivi',
'watchlisttools-edit' => 'Voir et modifier la liste de suivi',
'watchlisttools-raw'  => 'Modifier la liste (mode brut)',

);
