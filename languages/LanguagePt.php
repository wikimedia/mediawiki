<?
/** Portuguese (Português)
 * This translation was made by:
 *  - Yves Marques Junqueira
 *  - Rodrigo Calanca Nishino
 *  - Nuno Tavares
 *  - Paulo Juntas
 *  - Manuel Menezes de Sequeira
 *  - Sérgio Ribeiro
 * from the Portuguese Wikipedia
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

#
# In general you should not make customizations in these language files
# directly, but should use the MediaWiki: special namespace to customize
# user interface messages through the wiki.
# See http://meta.wikipedia.org/wiki/MediaWiki_namespace
#

/* private */ $wgNamespaceNamesPt = array(
	NS_MEDIA            => 'Media', # -2
	NS_SPECIAL          => 'Especial', # -1
	NS_MAIN             => '', # 0
	NS_TALK             => 'Discussão', # 1
    NS_USER             => 'Usuário',
    NS_USER_TALK        => 'Usuário_Discussão',
/*
	Above entries are for PT_br. The following entries should
    be used instead. But:

     DO NOT USE THOSE ENTRIES WITHOUT MIGRATING STUFF ON
     WIKIMEDIA WEB SERVERS FIRST !! You will just break a lot
     of links 8-)

	NS_USER             => 'Utilizador', # 2
	NS_USER_TALK        => 'Utilizador_Discussão', # 3
*/
	NS_PROJECT          => $wgMetaNamespace, # 4
	NS_PROJECT_TALK     => $wgMetaNamespace.'_Discussão', # 5
	NS_IMAGE            => 'Imagem', # 6
	NS_IMAGE_TALK       => 'Imagem_Discussão', # 7
	NS_MEDIAWIKI        => 'MediaWiki', # 8
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Discussão', # 9
	NS_TEMPLATE         => 'Predefinição', # 10
	NS_TEMPLATE_TALK    => 'Predefinição_Discussão', # 11
	NS_HELP             => 'Ajuda', # 12
	NS_HELP_TALK        => 'Ajuda_Discussão', # 13
	NS_CATEGORY         => 'Categoria', # 14
	NS_CATEGORY_TALK    => 'Categoria_Discussão' # 15
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsPt = array(
	'Nenhuma', 'Fixo à esquerda', 'Fixo à direita', 'Flutuando à esquerda', 'Flutuando à direita'
);

/* private */ $wgSkinNamesPt = array(
	'standard' => 'Clássico',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Azul colonial',
	'davinci' => 'DaVinci',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
) + $wgSkinNamesEn;

# Whether to use user or default setting in Language::date()
/* private */ $wgDateFormatsPt = array(
	'Sem preferência',
	'16:12, Janeiro 15, 2001',
	'16:12, 15 Janeiro 2001',
	'16:12, 2001 Janeiro 15',
	'ISO 8601' => '2001-01-15 16:12:34'
);


# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsPt = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#REDIRECT', '#redir'    ),
	MAG_NOTOC                => array( 0,    '__NOTOC__'              ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__'           ),
	MAG_TOC                  => array( 0,    '__TOC__'                ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__'      ),
	MAG_START                => array( 0,    '__START__'              ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH'           ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'       ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV'     ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'             ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'         ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'            ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'            ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES'          ),
	MAG_PAGENAME             => array( 1,    'PAGENAME'               ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE'              ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE'              ),
	MAG_MSG                  => array( 0,    'MSG:'                   ),
	MAG_SUBST                => array( 0,    'SUBST:'                 ),
	MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
	MAG_END                  => array( 0,    '__END__'                ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
	MAG_IMG_MANUALTHUMB      => array( 1,    'thumbnail=$1', 'thumb=$1'),
	MAG_IMG_RIGHT            => array( 1,    'right', 'direita'       ),
	MAG_IMG_LEFT             => array( 1,    'left', 'esquerda'       ),
	MAG_IMG_NONE             => array( 1,    'none', 'nenhum'         ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre'       ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' ),
	MAG_INT                  => array( 0,    'INT:'                   ),
	MAG_SITENAME             => array( 1,    'SITENAME'               ),
	MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'                 ),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME'             ),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH'             ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'               ),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK'            ),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW'             ),
	MAG_REVISIONID           => array( 1,    'REVISIONID'             ),    
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

# required for copyrightwarning
global $wgRightsText;

/* private */ $wgAllMessagesPt = array(

# User preference toggles
'tog-underline' => 'Sublinhar links',
'tog-highlightbroken' => 'Formatar links quebrados <a href="" class="new">como isto</a> (alternativa: como isto<a href="" class="internal">?</a>).',
'tog-justify'   => 'Justificar parágrafos',
'tog-hideminor' => 'Esconder edições secundárias nas mudanças recentes',
'tog-usenewrc' => 'Mudanças recentes melhoradas (JavaScript)',
'tog-numberheadings' => 'Auto-numerar cabeçalhos',
'tog-showtoolbar'               => 'Mostrar barra de edição (JavaScript)',
'tog-editondblclick' => 'Editar páginas quando houver clique duplo (JavaScript)',
'tog-editsection'               => 'Habilitar edição de secção via links [editar]',
'tog-editsectiononrightclick'   => 'Habilitar edição de secção por clique <br /> com o botão direito no título da secção (JavaScript)',
'tog-showtoc'                   => 'Mostrar Tabela de Conteúdos (para artigos com mais de 3 cabeçalhos)',
'tog-rememberpassword' => 'Lembrar palavra-chave entre sessões',
'tog-editwidth' => 'Caixa de edição com largura completa',
'tog-watchdefault' => 'Adicionar páginas editadas à sua lista de artigos vigiados',
'tog-minordefault' => 'Marcar todas as edições como secundárias, por padrão',
'tog-previewontop' => 'Mostrar Previsão antes da caixa de edição ao invés de ser após',
'tog-previewonfirst' => 'Mostrar Previsão na primeira edição',
'tog-nocache' => 'Desactivar caching de páginas',
'tog-enotifwatchlistpages'      => 'Enviar-me um email quando houver mudanças nas páginas',
'tog-enotifusertalkpages'       => 'Enviar-me um email quando a minha página de discussão for editada',
'tog-enotifminoredits'          => 'Enviar-me um email também quando forem edições menores',
'tog-enotifrevealaddr'          => 'Revelar o meu endereço de email nas notificações',
'tog-shownumberswatching'       => 'mostrar o número de utilizadores a vigiar',
'tog-fancysig' => 'Assinaturas sem atalhos automáticos.',
'tog-externaleditor' => 'Utilizar editor externo por padrão',
'tog-externaldiff' => 'Utilizar diferenças externas por padrão',
'tog-showjumplinks' => 'Activar hiperligações de acessibilidade "ir para"',

'underline-always' => 'Sempre',
'underline-never' => 'Nunca',
'underline-default' => 'Padrão',

'skinpreview' => '(Pré-visualizar)',

# dates
'sunday' => 'Domingo',
'monday' => 'Segunda-feira',
'tuesday' => 'Terça-feira',
'wednesday' => 'Quarta-feira',
'thursday' => 'Quinta-feira',
'friday' => 'Sexta-feira',
'saturday' => 'Sábado',
'january' => 'Janeiro',
'february' => 'Fevereiro',
'march' => 'Março',
'april' => 'Abril',
'may_long' => 'Maio',
'june' => 'Junho',
'july' => 'Julho',
'august' => 'Agosto',
'september' => 'Setembro',
'october' => 'Outubro',
'november' => 'Novembro',
'december' => 'Dezembro',
'jan' => 'Jan',
'feb' => 'Fev',
'mar' => 'Mar',
'apr' => 'Abr',
'may' => 'Mai',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Ago',
'sep' => 'Set',
'oct' => 'Out',
'nov' => 'Nov',
'dec' => 'Dez',

# Bits of text used by many pages:
#
'categories1' => 'Categoria',
'categories' => 'Categorias',
'category' => 'Categoria',
'category_header' => 'Artigos na categoria "$1"',
'subcategories' => 'Subcategorias',


'linktrail'             => '/^([a-z]+)(.*)$/sD',
'linkprefix'            => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpage'              => 'Página principal',
'mainpagetext'  => 'Software Wiki instalado com sucesso.',
"mainpagedocfooter" => "Por favor consultar a [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentação de modo a alterar o interface]
e o [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide Guia dos Utilizadores] para ajuda sobre a configuração.",

'portal'                => 'Portal comunitário',
'portal-url'            => 'Project:Portal comunitário',
'about'                 => 'Sobre',
'aboutsite'      => 'Sobre',
'aboutpage'             => 'Project:Sobre',
'article' => 'Artigo',
'help'                  => 'Ajuda',
'helppage'              => 'Ajuda:Conteúdos',
'bugreports'    => 'Bug reports',
'bugreportspage' => 'Project:Bug_reports',
'sitesupport'   => 'Doações',
'sitesupport-url' => 'Project:Apoio',
'faq'                   => 'FAQ',
'faqpage'               => 'Project:FAQ',
'edithelp'              => 'Ajuda de edição',
'newwindow'             => '(abre numa nova janela)',
'edithelppage'  => 'Ajuda:Editar',
'cancel'                => 'Cancelar',
'qbfind'                => 'Procurar',
'qbbrowse'              => 'Navegar',
'qbedit'                => 'Editar',
'qbpageoptions' => 'Esta página',
'qbpageinfo'    => 'Informação da página',
'qbmyoptions'   => 'Minhas opções',
'qbspecialpages'        => 'Páginas especiais',
'moredotdotdot' => 'Mais...',
'mypage'                => 'Minha página',
'mytalk'                => 'Minha discussão',
'anontalk'              => 'Discussão para este IP',
'navigation' => 'Navegação',

# Metadata in edit box
'metadata' => '<b>Metadata</b> (para uma explicação ver <a href="$1">aqui</a>)',
'metadata_page' => 'Wikipedia:Metadata',

'currentevents' => 'Eventos actuais',
'currentevents-url' => 'Eventos actuais',

'disclaimers' => 'Disclaimers',
'disclaimerpage' => "Project:General_disclaimer",
'privacy' => 'Política de privacidade',
'privacypage' => 'Project:Política_de_privacidade',
'errorpagetitle' => "Erro",
'returnto'              => "Retornar para $1.",
'tagline'       => "Origem: {{SITENAME}}, a enciclopédia livre",
'whatlinkshere' => 'Artigos afluentes',
'help'                  => 'Ajuda',
'search'                => 'Pesquisa',
'go'            => 'Ir',
"history"             => 'Histórico',
'history_short' => 'História',
'updatedmarker' => 'actualizado desde a minha última visita',
'info_short'    => 'Informação',
'printableversion' => 'Versão para impressão',
'permalink'     => 'Ligação permanente',
'print' => 'Imprimir',
'edit' => 'Editar',
'editthispage'  => 'Editar esta página',
'delete' => 'Eliminar',
'deletethispage' => 'Eliminar esta página',
'undelete_short1' => 'Restaurar uma edição',
'undelete_short' => 'Restaurar $1 edições',
'protect' => 'Proteger',
'protectthispage' => 'Proteger esta página',
'unprotect' => 'Desproteger',
'unprotectthispage' => 'Desproteger esta página',
'newpage' => 'Nova página',
'talkpage'              => 'Discutir esta página',
'specialpage' => 'Página Especial',
'personaltools' => 'Ferramentas pessoais',
'postcomment'   => 'Envie um comentário',
'addsection'   => '+',
'articlepage'   => 'Ver artigo',
'subjectpage'   => 'Ver assunto', # For compatibility
'talk' => 'Discussão',
'views' => 'Vistas',
'toolbox' => 'Ferramentas',
'userpage' => 'Ver página de utilizador',
'wikipediapage' => 'Ver página do projecto',
'imagepage' =>       'Ver página de imagens',
'viewtalkpage' => 'Ver discussão',
'otherlanguages' => 'Outras línguas',
'redirectedfrom' => '(Redireccionado de <b>$1</b> para <b>{{PAGENAME}}</b>.)',
'redirectpagesub' => 'Página de redireccionamento',
'lastmodified'  => 'Esta página foi modificada pela última vez a $1.',
'viewcount'             => 'Esta página foi acedida $1 vezes.',
'copyright'     => 'Conteúdo disponível sob $1.',
'poweredby'     => "{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.",
'printsubtitle' => "(De {{SERVER}})",
'protectedpage' => 'Página protegida',
'administrators' => "Project:Administradores",
'jumpto' => 'Ir para:',
'jumptonavigation' => 'navegação',
'jumptosearch' => 'pesquisa',

'sysoptitle'    => 'Necessário acesso de Sysop',
'sysoptext'             => "A acção que requisitou só pode ser
executada por utilizadores com status de \"sysop\".<br />
Veja $1.",
'developertitle' => 'Necessário acesso de desenvolvedor',
'developertext' => 'A acção que requisitou só pode ser
executada por utilizadores com direitos de "desenvolvedor".<br />Veja $1.',

'badaccess'     => 'Erro de permissão',
'badaccesstext' => 'A acção que requesitou está limitada a utilizadores com permissão de "$2". Ver $1.',

'versionrequired' => 'Necessãria versão $1 do MediaWiki',
'versionrequiredtext' => 'Esta página requer a versão $1 do MediaWiki para ser utilizada. Consulte [[Special:Version]]',

'nbytes'                => '$1 bytes',
'ncategories'           => '$1 categorias',
'nrevisions'            => '$1 revisões',
'ok'                    => 'OK',
'sitetitle'             => "{{SITENAME}}",
'pagetitle'             => "$1 - {{SITENAME}}",
'sitesubtitle'  => '',
'retrievedfrom' => "Retirado de \"$1\"",
'newmessages' => "Você tem $1.",
'newmessageslink' => 'novas mensagens',
'editsection'=>'editar',
'toc' => 'Tabela de conteúdo',
'showtoc' => 'mostrar',
'hidetoc' => 'esconder',
'thisisdeleted' => "Ver ou restaurar $1?",
'viewdeleted' => 'Ver $1?',
'restorelink1' => 'uma edição eliminada',
'restorelink' => "$1 edições eliminadas",
'feedlinks' => 'Feed:',
'sitenotice'    => '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artigo',
'nstab-user' => 'Página de utilizador',
'nstab-media' => 'Media',
'nstab-special' => 'Especial',
'nstab-wp' => 'Sobre',
'nstab-image' => 'Ficheiro',
'nstab-mediawiki' => 'Mensagem',
'nstab-template' => 'Predefinição',
'nstab-help' => 'Ajuda',
'nstab-category' => 'Categoria',

# Main script and global functions
#
'nosuchaction'  => 'Acção não existente',
'nosuchactiontext' => 'A acção especificada pelo URL não é reconhecida pelo programa da Wikipédia',
'nosuchspecialpage' => 'Não existe a página especial requesitada',
'nospecialpagetext' => 'Requesitou uma página especial inválida; uma lista de páginas especiais válidas poderá ser encontrada em [[{{ns:special}}:Specialpages]].',

# General errors
#
'error'                 => 'Erro',
'databaseerror' => 'Erro na base de dados',
'dberrortext'   => "Ocorreu um erro de sintaxe na pesquisa à base de dados.
A última tentativa de busca na base de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função \"<tt>$2</tt>\".
MySQL retornou o erro \"<tt>$3: $4</tt>\".",
'dberrortextcl' => "Ocorre um erro de sintaxe na pesquisa à base de dados.
A última tentativa de busca na base de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função \"<tt>$2</tt>\".
MySQL retornou o erro \"<tt>$3: $4</tt>\".",
'noconnect'             => 'Pedimos desculpas, mas esta wiki está passando por algumas
dificuldades técnicas e não pode contactar o servidor da base de dados.',
'nodb'                  => "Não foi possível seleccionar a base de dados $1",
'cachederror'           => 'A página apresentada é uma cópia em cache da página requisitada, e pode não estar actualizada.',
'laggedslavemode'   => 'Aviso: A página poderá não conter actualizações recentes.',
'readonly'              => 'Base de dados somente para leitura',
'enterlockreason' => 'Introduza com um motivo para trancar, incluindo uma estimativa de quando poderá novamente ser editada',
'readonlytext'  => "A base de dados da {{SITENAME}} está actualmente trancada para novos
artigos e outras modificações, provavelmente por uma manutenção de rotina à base de dados, mais tarde voltará ao normal.

O administrador que fez o bloqueio oferece a seguinte explicação: $1",
'missingarticle' => "A base de dados não encontrou o texto de uma página que deveria ter encontrado: \"$1\".

Isto é geralmente causado pela procura de uma diferença num antigo ou num histórico que leva a uma página que foi eliminada.

Se este não for o caso, você pode ter encontrado um ''bug'' no software.
Por favor, tome nota do URL e comunique o erro a um [[Wikipedia:Administradores|administrador]].",
'readonly_lag' => "A base de dados foi automaticamente bloqueada para sincronização",
'internalerror' => 'Erro interno',
'filecopyerror' => "Não foi possível copiar o ficheiro \"$1\" para \"$2\".",
'filerenameerror' => "Não foi possível renomear o ficheiro \"$1\" para \"$2\".",
'filedeleteerror' => "Não foi possível eliminar o ficheiro \"$1\".",
'filenotfound'  => "Não foi possível encontrar o ficheiro \"$1\".",
'unexpected'    => "Valor não esperado: \"$1\"=\"$2\".",
'formerror'             => 'Erro: Não foi possível enviar o formulário',
'badarticleerror' => 'Esta acção não pode ser realizada nesta página.',
'cannotdelete'  => 'Não foi possível eliminar a página ou imagem especificada (Pode ter sido já eliminada por outro administrador.)',
'badtitle'              => 'Título inválido',
'badtitletext' => "O título de página requisitada era inválido, vazio, ou
uma ligação incorrecta de inter-linguagem ou título inter-wiki.",
'perfdisabled' => 'Esta opção foi temporariamente desabilitada,
devido a tornar a base de dados lenta demais a ponto de impossibilitar a wiki.',
'perfdisabledsub' => "Aqui pode ver uma cópia de $1 salvaguardada:", # obsolete?
'perfcached' => 'Os dados seguintes encontram-se na cache e podem não estar actualizados:',
'wrong_wfQuery_params' => "Parâmetros incorrectos para wfQuery()<br />
Function: $1<br />
Query: $2",
'viewsource' => 'Ver fonte',
'protectedtext' => "Esta página foi protegida para não permitir edições; existem inúmeros motivos para 
ocorrer esta situação, por favor consulte [[Project:Protected page]].

Pode ver e copiar o código fonte desta página:",
'sqlhidden' => '(Query SQL escondida)',

# Login and logout pages
#
'logouttitle'   => 'Saída de utilizador',
'logouttext'            => "<strong>Não está mais autentificado.</strong><br />
Pode continuar a utilizar a {{SITENAME}} anonimamente, ou pode autentificar-se
novamente como o mesmo nome de utilizador utilizador ou com um nome de utilizador diferente. Tenha em atenção que algumas páginas poderão continuar a ser mostradas como se tivesse ainda autentificado, até limpar a cache do seu navegador.\n",

'welcomecreation' => "== Bem-vindo, $1! ==

A sua conta foi criada. Não se esqueça de personalizar as suas [[Special:Preferences|preferências]] na {{SITENAME}}.",

'loginpagetitle' => 'Login de utilizador',
'yourname'              => 'Seu nome de utilizador',
'yourpassword'  => 'Palavra-chave',
'yourpasswordagain' => 'Reintroduza a sua palavra-chave',
'newusersonly'  => ' (somente novos utilizadores)',
'remembermypassword' => 'Lembrar a minha palavra-chave entre sessões.',
'yourdomainname'       => 'Seu domínio',
'externaldberror'      => 'Ocorreu um erro externo à base de dados durante a autenticação, ou não lhe é permitido actualizar a sua conta externa.',
'loginproblem'  => '<b>Houve um problema com a sua autenticação.</b><br />Tente novamente!',
'alreadyloggedin' => "<strong>Utilizador $1, você já está autentificado!</strong><br />",
'login'                 => 'Entrar',
'loginprompt'           => "Você necessita de ter os <i>cookies</i> ligados para poder autentificar-se na {{SITENAME}}.",
'userlogin'             => 'Criar uma conta ou entrar',
'logout'                => 'Sair',
'userlogout'    => 'Sair',
'notloggedin'   => 'Não autentificado',
'nologin'       => 'Não possui uma conta? $1.',
'nologinlink'   => 'Criar uma conta',
'createaccount' => 'Create account',
'gotaccount'    => 'Já possui uma conta? $1.',
'gotaccountlink'        => 'Entrar',
'createaccount' => 'Criar nova conta',
'createaccountmail'     => 'por email',
'badretype'             => 'As palavras-chaves que introduziu não são iguais.',
'userexists'    => 'O nome de utilizador que introduziu já existe. Por favor, escolha um nome diferente.',
'youremail'             => 'Email *',
'username'              => 'Utilizador: ',
'uid'                   => 'Identificação: ',
'yourrealname'          => 'Nome verdadeiro *',
'yourlanguage'  => 'Idioma:',
'yourvariant'  => 'Variante',
'yournick'              => 'Alcunha:',
'badsig'                => 'Assinatura inválida; verifique o código HTML utilizado.',
'email'                 => 'Email',
'emailforlost'          => "Os campos marcados são opcionais. Colocando o seu endereço de e-mail permite outras pessoas entrem em contacto consigo sem que tenha que revelar o seu e-mail a elas, e também é útil caso se esquecer da sua palavra-chave.<br /><br />O seu nome real, se o indicar, será utilizado para dar atribuição do seu trabalho.",
'prefs-help-email-enotif' => 'Este endereço é também utilizado para enviar-lhe notificações caso as active nas preferências.',
'prefs-help-realname'   => '* Nome verdadeiro (opcional): caso decida indicar, este será utilizado para lhe dar atributo do seu trabalho.',
'loginerror'    => 'Erro de autentificação',
'prefs-help-email'      => '* Email (opcional): Permite às pessoas que entrem em contacto consigo sem que tenha que revelar o seu endereço de e-mail a elas.',
'nocookiesnew'  => "The user account was created, but you are not logged in. {{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
'nocookieslogin'        => "{{SITENAME}} uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
'noname'                => 'Não colocou um nome de utilizador válido.',
'loginsuccesstitle' => 'Login bem sucedido',
'loginsuccess'  => "'''Encontra-se agora ligado à {{SITENAME}} como \"$1\"'''.",
'nosuchuser'    => "Não existe nenhum utilizador com o nome \"$1\".
Verifique o nome que introduziu, ou crie uma nova conta de utilizador.",
'nosuchusershort'       => "Não existe um utilizador com o nome \"$1\". Verifique o nome que introduziu.",
'wrongpassword'         => 'A palavra-chave que introduziu é inválida. Por favor tente novamente.',
'wrongpasswordempty'            => 'Palavra-chave introduzida está em branco. Por favor tente novamente.',
'mailmypassword'        => 'Enviar uma nova palavra-chave por e-mail',
'passwordremindertitle' => "Lembrador de palavras-chave da {{SITENAME}}",
'passwordremindertext' => "Alguém (provavelmente você, do endereço de IP $1) solicitou que fosse lhe envido uma nova palavra-chave para {{SERVERNAME}}.
A palavra para o utilizador \"$2\" é a partir de agora \"$3\". Pode agora entrar na sua conta e alterar a palavra-chave.

Caso tenha sido outra pessoa a fazer este pedido ou caso você já se tenha lembrado da sua palavra-chave e se não a desejar alterar, pode ignorar esta mensagem e continuar a utilizar a palavra-chave antiga.",
'noemail'                           => "Não existe um endereço de e-mail associado ao utilizador \"$1\".",
'passwordsent'  => "Uma nova palavra-chave encontra-se a ser enviada para o endereço de email associado ao utilizador \"$1\".
Por favor, volte a efectuar a autentificação ao recebê-la.",
'eauthentsent'             =>  "Um email de confirmação foi enviado para o endereço de correio electrónico nomeado. 
Antes de qualquer outro email seja enviado para a conta, terá seguir as instruções no email, 
de modo a confirmar que a conta é mesmo sua.",
'loginend'                          => '',
'signupend'                         => '{{int:loginend}}',
'mailerror'                 => "Erro a enviar o mail: $1",
'acct_creation_throttle_hit' => 'Pedimos desculpa, mas já foram criadas $1 contas por si. Não lhe é possível criar mais nenhuma.',
'emailauthenticated'        => 'O seu endereço de e-mail foi autenticado em $1.',
'emailnotauthenticated'     => 'O seu endereço de e-mail <strong>ainda não foi autenticado</strong>. Não lhe será enviado nenhum correio sobre nenhuma das seguintes funcionalidades.',
'noemailprefs'              => '<strong>Nenhum endereço de e-mail foi especificado</strong>, as seguintes funcionalidades não irão funcionar.',
'emailconfirmlink' => 'Confirme o seu endereço de e-mail',
'invalidemailaddress'   => 'O endereço de email não pode ser aceite devido a possuír um formato inválido. Por favor introduza um endereço bem formatado ou esvazie o campo.',

# Edit page toolbar
#
'bold_sample'      => 'Texto a negrito',
'bold_tip'         => 'Texto a negrito',
'italic_sample'    => 'Texto em itálico',
'italic_tip'       => 'Texto em itálico',
'link_sample'      => 'Título da ligação',
'link_tip'         => 'Ligação interna',
'extlink_sample'   => 'http://www.wikimedia.org ligação externa',
'extlink_tip'      => 'Ligação externa (lembre-se dos prefixos http://, ftp://, ...)',
'headline_sample'  => 'Texto de cabeçalho',
'headline_tip'     => 'Secção de nível 2',
'math_sample'      => 'Inserir fórmula aqui',
'math_tip'         => 'Fórmula matemática (LaTeX)',
'nowiki_sample'    => 'Inserir texto não-formatado aqui',
'nowiki_tip'       => 'Ignorar formato wiki',
'image_sample'     => 'Exemplo.jpg',
'image_tip'        => 'Imagem anexa',
'media_sample'     => 'Exemplo.ogg',
'media_tip'        => 'Ligação a ficheiro interno de multimédia',
'sig_tip'          => 'Sua assinatura com hora e data',
'hr_tip'           => 'Linha horizontal (utilize moderadamente)',

# Edit pages
#
'summary'               => 'Sumário',
'subject'               => 'Assunto/cabeçalho',
'minoredit'             => 'Marcar como edição menor',
'watchthis'             => 'Observar este artigo',
'savearticle'   => 'Salvar página',
'preview'               => 'Prever',
'showpreview'   => 'Mostrar previsão',
'showdiff'      => 'Mostrar alterações',
'blockedtitle'  => 'Utilizador está bloqueado',
'blockedtext'   => "O seu nome de utilizador ou endereço de IP foi bloqueado por $1.<br />
O motivo é: ''$2''

Pode contactar [[{{ns:special}}:emailuser/$4|$4]] ou outro
[[{{ns:4}}:Administradores|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade \"Contactar utilizador\" se não possuir uma conta na Wikipédia e um endereço de email válido indicado nas suas preferências de utilizador. E lembre-se que só se encontra impossibilitado de editar páginas.<br /><br />

'''O seu endereço de IP é $3.''' Por favor inclua o seu endereço de IP ao contactar um administrador sobre o bloqueio.",
'whitelistedittitle' => 'Autentificação necessária para visualizar',
'whitelistedittext' => 'Precisa de se [[Special:Userlogin|autentificar]] para poder visualizar páginas.',
'whitelistreadtitle' => 'Autentificação necessária para visualizar',
'whitelistreadtext' => 'Precisa de se [[Special:Userlogin|autentificar]] para poder visualizar páginas.',
'whitelistacctitle' => 'Não lhe é permitido criar uma conta',
'whitelistacctext' => 'De modo a poder criar contas de utilizador nesta Wiki terá que se [[Special:Userlogin|autentificar]] e possuir as devidas permissões.',
'loginreqtitle' => 'Autentificação Requesitada',
'loginreqlink' => 'autentificar-se',
'loginreqtext'  => 'Precisa de $1 para visualizar outras páginas.',
'accmailtitle' => 'Palavra-chave enviada.',
'accmailtext' => "A palavra-chave para '$1' foi enviada para $2.",
'newarticle'    => '(Novo)',
'newarticletext' =>
"Seguiu um link para um artigo que ainda não existe. Para criá-lo, escreva o seu conteúdo na caixa abaixo, mas se chegou aqui por engano clique no botão '''volta''' (ou ''back'') do seu navegador. Por favor, '''NÃO''' crie páginas apenas para fazer [[Project:Artigos pedidos|pedidos]] ou [[Project:Página de testes|testes]].

(Consulte [[Project:Ajuda|a página de ajuda]] para mais informações)",
'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext' => "----
''Esta é a página de discussão para um utilizador anónimo que ainda não criou uma conta ou que não a utiliza. De modo a que temos que utilizar o endereço de IP para identificá-lo(a). Um endereço de IP pode ser partilhado por vários utilizadores. Se é um utilizador anónimo e acha relevante que os comentários sejam direccionados a si, por favor [[{{ns:special}}:Userlogin|crie uma conta ou autentifique-se]] para evitar futuras confusões com outros utilizadores anónimos.''",
'noarticletext' => 'Não existe actualmente texto nesta página, pode [[{{ns:special}}:Search/{{PAGENAME}}|pesquisar pelo título desta página noutras páginas]] ou [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} editar esta página].',
'clearyourcache' => "'''Nota:''' Após salvar, terá de limpar a cache do seu navegador para ver as alterações.
'''Mozilla / Firefox / Safari:''' pressione ''Shift'' enquanto clica em ''Recarregar'', ou pressione ''Ctrl-Shift-R'' (''Cmd-Shift-R'' no Apple Mac); '''IE:''' pressione ''Ctrl'' enquanto clica em ''Recarregar'', ou pressione ''Ctrl-F5''; '''Konqueror:''': simplesmente clique no botão ''Recarregar'', ou pressione ''F5''; utilizadores do navegador '''Opera''' podem limpar completamente a sua cache em ''Ferramentas→Preferências''.",
'usercssjsyoucanpreview' => "<strong>Dica:</strong> Utilize o botão \"Mostrar previsão\" para testar seu novo CSS/JS antes de salvar.",
'usercsspreview' => "'''Lembre-se que está apenas a prever o seu CSS particular, e que ainda não foi salvo!'''",
'userjspreview' => "'''Lembre-se que está apenas a testar/prever o seu JavaScript particular, e que ainda não foi salvo!'''",
'updated'               => '(Actualizado)',
'note'                  => '<strong>Nota:</strong> ',
'previewnote'   => '<strong>Isto é apenas uma previsão, as modificações ainda não foram salvas!</strong>',
'session_fail_preview' => '<strong>Pedimos desculpas, mas não foi possível processar a sua edição devido à perda de dados da sua sessão. 
Por favor tente novamente. Caso continue a não funcionar, tente sair e voltar a entrar na sua conta.</strong>',
'previewconflict' => 'Esta previsão reflete o texto que está na área de edição acima e como ele aparecerá se você escolher salvar.',
'importing'		=> "Importando $1",
'editing'               => "Editando $1",
'editingsection'                => "Editando $1 (secção)",
'editingcomment'                => "Editando $1 (comentário)",
'editconflict'  => 'Conflito de edição: $1',
'explainconflict' => "Alguém mudou a página enquanto você a estava editando.<br />
A área de texto acima mostra o texto original.
Suas mudanças são mostradas na área abaixo
Você terá que mesclar suas modificações no texto existente.
<b>SOMENTE</b> o texto na área acima será salvo quando você pressionar \"Salvar página\".\n<br />",
'yourtext'              => 'Seu texto',
'storedversion' => 'Versão guardada',
'nonunicodebrowser' => "<strong>AVISO: O seu navegador não segue as especificações Unicode. Existe uma maneira para que possa editar com segurança os artigos: os caracteres não-ASCII aparecerão na caixa de edição no formato de códigos hexadecimais.</strong>",
'editingold'    => "<strong>CUIDADO: Encontra-se a editar uma revisão desactualizada deste artigo.
Se salvá-lo, todas as mudanças feitas a partir desta revisão serão perdidas.</strong>",
'yourdiff'              => 'Diferenças',
'copyrightwarning' => "Por favor note que todas as contribuições para a {{SITENAME}} são imediatamente colocadas sob a <b>GNU Free Documentation License</b> (consulte $1 para detalhes). Se você não quer que seu texto esteja sujeito a estes termos, então não o envie.<br/>
Você também garante que está nos enviando um artigo escrito por você mesmo, ou extraído de uma fonte em domínio público.
<strong>Não ENVIE </strong>",
'copyrightwarning2' => "Tenha em consideração que todas as contribuições para o projecto {{SITENAME}}
podem ser editadas, alteradas, ou removidas por outros contribuidores.
Se não deseja ver as suas contribuições alteradas sem consentimento, não as envie para esta Wiki.<br />
Adicionalmente, estar-nos-á a dar a sua palavra em como os teus são da sua autoria, ou copiados por fontes de domínio público ou similares (veja mais detalhes em $1).
<strong>NÃO ENVIE MATERIAL COM DIREITOS DE AUTOR SEM PERMISSÃO!</strong>",
'longpagewarning' => "<strong>AVISO: Esta página ocupa $1; alguns browsers verificam 
problemas em editar páginas maiores que 32kb.
Por favor, considere seccionar a página em secções de menor dimensão.</strong>",
'readonlywarning' => '<strong>AVISO: A base de dados foi bloqueada para manutenção, pelo que não poderá salvar a sua edição neste momento. Pode, no entanto, copiar o seu texto num editor externo e guardá-lo para posterior submissão.</strong>',
'protectedpagewarning' => "<strong>AVISO: Esta página foi protegida e apenas poderá ser editada por utilizadores com privilégios sysop (administradores). Certifique-se que está a respeitar as [[Project:Protected_page_guidelines|linhas de orientação para páginas protegidas]].</strong>",
'templatesused' => 'Predefinições utilizadas nesta página:',
'edittools' => '<!-- Text here will be shown below edit and upload forms. -->',
'nocreatetitle' => 'Limitada a criação de páginas',
'nocreatetext' => 'Este website tem restringida a habilidade de criar novas páginas.
Pode voltar atrás e editar uma página já existente, ou [[Special:Userlogin|autentificar-se ou criar uma conta]].',

# History pages
#
'revhistory'    => 'Histórico de revisões',
'nohistory'             => 'Não há histórico de edições para esta página.',
'revnotfound'   => 'Revisão não encontrada',
'revnotfoundtext' => "A antiga revisão desta página que requesitou não pode ser encontrada. Por favor verifique o URL que utilizou para aceder esta página.",
'loadhist'              => 'Carregando histórico',
'currentrev'    => 'Revisão actual',
'revisionasof'          => 'Revisão de $1',
'revisionasofwithlink'  => 'Revisão de $1; $2<br />$3 | $4',
'previousrevision'      => '← Versão anterior',
'nextrevision'          => 'Versão posterior →',
'currentrevisionlink'   => 'ver versão actual',
'cur'                   => 'act',
'next'                  => 'prox',
'last'                  => 'ult',
'orig'                  => 'orig',
'histlegend'    => 'Selecção de diferença: marque as caixas para das versões que deseja comparar e carregue no botão.<br />
Legenda: (actu) = diferenças da versão actual,
(ult) = diferença da versão precedente, m = edição minoritária',
'history_copyright'    => '-',
'deletedrev' => '[eliminada]',
'histfirst' => 'Mais antigas',
'histlast' => 'Mais recentes',

# Diffs
#
'difference'    => '(Diferença entre revisões)',
'loadingrev'    => 'carregando a pesquisa por diferenças',
'lineno'                => "Linha $1:",
'editcurrent'   => 'Editar a versão actual desta página',
'selectnewerversionfordiff' => 'Seleccione uma versão mais recente para comparação',
'selectolderversionfordiff' => 'Seleccione uma versão mais antiga para comparação',
'compareselectedversions' => 'Compare as versões seleccionadas',

# Search results
#
'searchresults' => 'Resultados de pesquisa',
'searchresulttext' => "Para mais informações de como pesquisar na {{SITENAME}}, consulte [[Project:Pesquisa|Pesquisando {{SITENAME}}]].",
'searchquery'   => "Para consulta \"$1\"",
'badquery'              => 'Linha de pesquisa inválida',
'badquerytext'  => 'Não foi possível processar seu pedido de pesquisa.
Aconteceu provavelmente porque tentou procurar uma palavra com menos de três letras. Isto também pode ter ocorrido porque digitou incorrectamente a expressão, por
exemplo "peixes <strong>e e</strong> escalas".
Por favor realize outro pedido de pesquisa.',
'matchtotals'   => "A pesquisa \"$1\" resultou $2 títulos de artigos
e $3 artigos com o texto procurado.",
'nogomatch' => 'Nenhuma página exactamente com [[$1|este título]] existe, tente com a pesquisa de texto completo.',
'titlematches'  => 'Resultados nos títulos dos artigos',
'notitlematches' => 'Nenhum título de página coincide',
'textmatches'   => 'Resultados dos textos dos artigos',
'notextmatches' => 'Nenhum texto nas páginas coincide',
'prevn'                 => "anteriores $1",
'nextn'                 => "próximos $1",
'viewprevnext'  => "Ver ($1) ($2) ($3).",
'showingresults' => "Mostrando <b>$1</b> resultados, começando no <b>$2</b>º.",
'showingresultsnum' => "Mostrando <b>$3</b> resultados começando com #<b>$2</b>.",
'nonefound'             => "<strong>Nota</strong>: pesquisas mal sucedidas são geralmente causadas devido ao uso de palavras muito comuns como \"tem\" e \"de\",
que não são indexadas, ou pela especificação de mais de um termo (somente as páginas contendo todos os termos aparecerão nos resultados).",
'powersearch' => 'Pesquisa',
'powersearchtext' => "
Procurar nos namespaces :<br />
$1<br />
$2 Lista redirecciona   Procurar por $3 $9",
"searchdisabled" => 'O motor de pesquisa na {{SITENAME}} foi desactivado por motivos de desempenho. Enquanto isso pode fazer a sua pesquisa através do Google ou do Yahoo!.<br />
Note que os índices do conteúdo da {{SITENAME}} destes sites podem estar desactualizados.',

'googlesearch' => '
<form method="get" action="http://www.google.com/search" id="googlesearch">
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />
    
    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="$3" />
  <div>
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>',
'blanknamespace' => '(Principal)',

# Preferences page
#
'preferences'   => 'Preferências',
'prefsnologin' => 'Não autenticado',
'prefsnologintext'      => "Precisa estar [[Special:Userlogin|autentificado]] para definir suas preferências.",
'prefsreset'    => 'Preferências restauradas da base de dados.',
'qbsettings'    => 'Barra Rápida',
'changepassword' => 'Alterar palavra-chave',
'skin'                  => 'Tema',
'math'                  => 'Matemática',
'dateformat'            => 'Formato da data',
'datedefault' => 'Sem preferência',
'datetime'              => 'Data e hora',
'math_failure'          => 'Falhou ao verificar gramática',
'math_unknown_error'    => 'Erro desconhecido',
'math_unknown_function' => 'Função desconhecida ',
'math_lexing_error'     => 'Erro léxico',
'math_syntax_error'     => 'Erro de sintaxe',
'math_image_error'      => 'Erro na conversão para PNG; Verifique a instalação do latex, dvips, gs e convert',
'math_bad_tmpdir'       => 'Ocorreram problemas na criação ou escrita na directoria temporária math',
'math_bad_output'       => 'Ocorreram problemas na criação ou escrita na directoria de resultados math',
'math_notexvc'  => 'Executável texvc não encontrado; Consulte math/README para instruções da configuração.',
'prefs-personal' => 'Perfil de utilizador',
'prefs-rc' => 'Mudanças recentes',
'prefs-misc' => 'Diversos',
'saveprefs'             => 'Salvar',
'resetprefs'    => 'Restaurar',
'oldpassword'   => 'Palavra-chave antiga',
'newpassword'   => 'Nova palavra-chave',
'retypenew'             => 'Reintroduza a nova palavra-chave',
'textboxsize'   => 'Opções de edição',
'rows'                  => 'Linhas:',
'columns'               => 'Colunas:',
'searchresultshead' => 'Pesquisa',
'resultsperpage' => 'Resultados por página:',
'contextlines'  => 'Linhas por resultado:',
'contextchars'  => 'Contexto por linha:',
'stubthreshold' => 'Variação para a visualização de esboços:',
'recentchangescount' => 'Número de artigos nas mudanças recentes:',
'savedprefs'    => 'As suas preferências foram salvas.',
'timezonelegend' => 'Fuso horário',
'timezonetext'  => 'Número de horas que o seu horário local difere do horário do servidor (UTC).',
'localtime'     => 'Hora local',
'timezoneoffset' => 'Diferença horária',
'servertime'    => 'Horário do servidor',
'guesstimezone' => 'Preencher a partir do navegador (browser)',
'allowemail'             => 'Permitir email de outros utilizadores',
'defaultns'             => 'Pesquisar nestes domínios por padrão:',
'default'               => 'padrão',
'files'                 => 'Ficheiros',

# User levels special page
#

# switching pan
'groups-lookup-group' => 'Gerir privilégios de grupo',
'groups-group-edit' => 'Grupos existentes: ',
'editgroup' => 'Editar Grupo',
'addgroup' => 'Adicionar Grupo',

'userrights-lookup-user' => 'Gerir grupos de utilizadores',
'userrights-user-editname' => 'Intruduza um nome de utilizador: ',
'editusergroup' => 'Editar Grupos de Utilizadores',

# group editing
'groups-editgroup'          => 'Editar grupo',
'groups-addgroup'           => 'Adicionar grupo',
'groups-editgroup-preamble' => 'If the name or description starts with a colon, the 
remainder will be treated as a message name, and hence the text will be localised 
using the MediaWiki namespace',
'groups-editgroup-name'     => 'Nome do grupo: ',
'groups-editgroup-description' => 'Group description (max 255 characters):<br />',
'savegroup'                 => 'Salvar Grupo',
'groups-tableheader'        => 'ID || Nome || Descrição || Direitos',
'groups-existing'           => 'Grupos existentes',
'groups-noname'             => 'Por favor especifique um nome válido',
'groups-already-exists'     => 'Um grupo com esse nome já existe',
'addgrouplogentry'          => 'Adicionado grupo $2',
'changegrouplogentry'       => 'Alterado grupo $2',
'renamegrouplogentry'       => 'Alterado o nome do grupo $2 para $3',

# user groups editing
#
'userrights-editusergroup' => 'Editar grupos do utilizador',
'saveusergroups' => 'Salvar Grupos do Utilizador',
'userrights-groupsmember' => 'Membro de:',
'userrights-groupsavailable' => 'Grupos disponíveis:',
'userrights-groupshelp' => 'Seleccione os grupos no qual deseja que o utilizador seja removido ou adicionado.
Grupos não seleccionados, não serão alterados. Pode seleccionar ou remover a selecção a um grupo com CTRL + Click esquerdo',
'userrights-logcomment' => 'Alterado membro do grupo de $1 para $2',

# Default group names and descriptions
# 
'group-anon-name'       => 'Anónimos',
'group-anon-desc'       => 'Utilizadores anónimos',
'group-loggedin-name'   => 'Utilizador',
'group-loggedin-desc'   => 'Utilizadores autentificados',
'group-admin-name'      => 'Administrador',
'group-admin-desc'      => 'Utilizadores de confiança capazes de bloquear utilizadores e eliminar artigos',
'group-bureaucrat-name' => 'Burocrata',
'group-bureaucrat-desc' => 'O grupo dos burocratas é capaz de nomear administradores',
'group-steward-name'    => 'Steward',
'group-steward-desc'    => 'Acesso total',

# Recent changes
#
'changes' => 'mudanças',
'recentchanges' => 'Mudanças recentes',
'recentchanges-url' => 'Special:Recentchanges',
'recentchangestext' => 'Veja as mais novas mudanças na {{SITENAME}} nesta página.',
'rcloaderr'             => 'Carregando mudanças recentes',
'rcnote'                => "Abaixo estão as últimas <strong>$1</strong> alterações nos últimos <strong>$2</strong> dias.",
'rcnotefrom'    => "Abaixo estão as mudanças desde <b>$2</b> (mostradas até <b>$1</b>).",
'rclistfrom'    => "Mostrar as novas alterações a partir de $1",
'showhideminor' => "$1 edições menores | $2 robôs | $3 utilizadores autentificados | $4 edições verificadas ",
'rclinks'               => "Mostrar as últimas $1 mudanças nos últimos $2 dias<br />$3",
'rchide'                => "em forma $4; $1 edições menores; $2 domínios secundários; $3 edições múltiplas.",
'rcliu'                 => "; $1 edições de utilizadores autentificados",
'diff'                  => 'dif',
'hist'                  => 'hist',
'hide'                  => 'Esconder',
'show'                  => 'Mostrar',
'tableform'             => 'tabela',
'listform'              => 'lista',
'nchanges'              => "$1 mudanças",
'minoreditletter' => 'm',
'newpageletter' => 'N',
'sectionlink' => '→',
'number_of_watching_users_RCview'       => '[$1]',
'number_of_watching_users_pageview'     => '[$1 utilizador/es a vigiar]',

# Upload
#
'upload'                => 'Carregar ficheiro',
'uploadbtn'             => 'Carregar ficheiro',
'uploadlink'    => 'Carregar imagens',
'reupload'              => 'Recarregar',
'reuploaddesc'  => 'Voltar ao formulário de carregamento.',
'uploadnologin' => 'Não autentificado',
'uploadnologintext'     => "Deve estar <a href=\"{{localurle:Special:Userlogin}}\">autentificado</a>
para carregar ficheiros.",
'upload_directory_read_only' => 'A directoria de envio ($1) não tem permissões de escrita pelo servidor Web.',
'uploaderror'   => 'Erro ao carregar',
'uploadtext'    =>
"
Utilize o formulário abaixo para carregar novos ficheiros,
para ver ou pesquisar imagens anteriormente carregadas
consulte a [[Special:Imagelist|lista de ficheiros carregados]], 
carregamentos e eliminações são também registados no [[Special:Log|registo do projecto]].

Para incluír a imagem numa página, utilize o link na forma de
'''[[{{ns:6}}:ficheiro.jpg]]''', 
'''[[{{ns:6}}:ficheiro.png|texto]]''' ou
'''[[{{ns:-2}}:ficheiro.ogg]]''' para uma ligação directa ao ficheiro.",
'uploadlog'             => 'registo de carregamento',
'uploadlogpage' => 'Registo de carregamento',
'uploadlogpagetext' => 'Segue-se uma lista dos carregamentos mais recentes.',
'filename'              => 'Nome do ficheiro',
'filedesc'              => 'Descrição do ficheiro',
'fileuploadsummary' => 'Sumário:',
'filestatus' => 'Estatuto de copyright',
'filesource' => 'Fonte',
'copyrightpage' => "Project:Direitos_de_autor",
'copyrightpagename' => "Direitos autorais da {{SITENAME}}",
'uploadedfiles' => 'Ficheiros carregados',
'ignorewarning'        => 'Ignorar aviso e salvar de qualquer forma.',
'ignorewarnings'        => 'Ignorar todos os avisos',
'minlength'             => 'O nome de um ficheiro tem de ter no mínimo três letras.',
'illegalfilename'       => 'O ficheiro "$1" possui caracteres que não são permitidos no título de uma página. Por favor altere o nome do ficheiro e tente carregar novamente.',
'badfilename'   => 'Nome do ficheiro foi alterado para "$1".',
'badfiletype'   => "\".$1\" é um formato de ficheiro não recomendado.",
'largefile'             => 'É recomendado que imagens não excedam $1 bytes em tamanho, o tamanho deste ficheiro é $2 bytes',
'largefileserver' => 'O tamanho deste ficheiro é superior ao qual o servidor encontra-se configurado para permitir.',
'emptyfile'             => 'O ficheiro que está a tentar carregar parece encontrar-se vazio. Isto poderá ser devido a um erro na escrita do nome do ficheiro. Por favor verifique se realmente deseja carregar este ficheiro.',
'fileexists'            => 'Já existe um ficheiro com este nome, por favor verifique $1 caso não tenha a certeza se deseja alterar o ficheiro actual.',
'fileexists-forbidden' => 'Já existe um ficheiro com este nome; por favor volte atrás e carregue este ficheiro sob um novo nome. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Já existe um ficheiro com este nome no repositório de ficheiros partilhados; por favor volte atrás e carregue este ficheiro sob um novo nome. [[Image:$1|thumb|center|$1]]',
'successfulupload' => 'Envio efectuado com sucesso',
'fileuploaded'  => "Ficheiro $1 enviado com sucesso.
Por favor siga este endereço: $2 para a página de descrição e preencha a informação acerca deste ficheiro, tais como a sua origem, quando foi criado e por quem, e quaisquer outros dados que tenha conhecimento sobre o mesmo. Caso este ficheiro seja uma imagem, pode inseri-lo desta forma: <tt>[[Imagem:$1|thumb|Descrição]]</tt>",
'uploadwarning' => 'Aviso de envio',
'savefile'              => 'Salvar ficheiro',
'uploadedimage' => "carregado \"[[$1]]\"",
'uploaddisabled' => 'Pedimos desculpas, o carregamento de ficheiros encontra-se desactivado.',
'uploadscripted' => 'This file contains HTML or script code that my be erroneously be interpreted by a web browser.',
'uploadcorrupt' => 'O ficheiro encontra-se corrompido ou tem uma extensão não permitida. Corrija o ficheiro e tente novamento.',
'uploadvirus' => 'O ficheiro contém vírus! Detalhes: $1',
'sourcefilename' => 'Nome do ficheiro de origem',
'destfilename' => 'Nome do ficheiro de destino',

'license' => 'Licença',
'nolicense' => 'Nenhuma seleccionada',

# Image list
#
'imagelist'             => 'Lista de ficheiros',
'imagelisttext' => "Segue-se uma lista de $1 ficheiros organizados $2.",
'getimagelist'  => 'carregando lista de ficheiros',
'ilsubmit'              => 'Procurar',
'showlast'              => "Mostrar os $1 ficheiros organizados $2.",
'byname'                => 'por nome',
'bydate'                => 'por data',
'bysize'                => 'por tamanho',
'imgdelete'             => 'eli',
'imgdesc'               => 'desc',
'imglegend'             => 'Legenda: (desc) = mostrar/editar descrição de imagem.',
'imghistory'    => 'História',
'revertimg'             => 'rev',
'deleteimg'             => 'eli',
'deleteimgcompletely'           => 'Eliminar todas revisões deste ficheiro',
'imghistlegend' => 'Legenda: (actu) = imagem actual, (eli) = eliminar versão antiga, (rev) = reverter para versão antiga.
<br /><i>Clique na data para ver as imagens carregadas nessa data</i>.',
'imagelinks'    => 'Links',
'linkstoimage'  => 'As seguintes páginas apontam para este ficheiro:',
'nolinkstoimage' => 'Nenhuma página aponta para este ficheiro.',
'sharedupload' => 'Este ficheiro encontra-se partilhado e pode ser utilizado por outros projectos.',
'shareduploadwiki' => 'Por favor consulte a $1 para mais informação.',
'shareduploadwiki-linktext' => 'página de descrição',
'shareddescriptionfollows' => '-',
'noimage'       => 'Nenhum ficheiro com este nome existe, se desejar pode $1',
'noimage-linktext'       => 'carrega-lo',
'uploadnewversion' => '[$1 Carregar uma nova versão deste ficheiro]',

# Unwatchedpages
#
'unwatchedpages' => 'Páginas não vigiadas',

# Statistics
#
'statistics'    => 'Estatísticas',
'sitestats'             => 'Estatísticas do site',
'userstats'             => 'Estatística dos utilizadores',
'sitestatstext' => "Há actualmente um total de '''$1''' páginas na base de dados.
Isto inclui páginas de \"discussão\", páginas sobre o projecto, páginas de rascunho, redireccionamentos, e outras que provavelmente não são qualificadas como artigos.
Excluindo estas, há '''$2''' páginas que provavelmente são artigos legítimos.

Há um total de '''$3''' páginas vistas, e '''$4''' edições em páginas
desde a instalação do software.
O que nos leva a aproximadamente '''$5''' edições por página, e '''$6''' vistas por edição.",
'userstatstext' => "Há actualmente '''$1''' utilizadores registados.
Destes, '''$2''' (ou '''$4''') são administradores (consulte $3).",

# Maintenance Page
#
'maintenance'           => 'Página de manutenção',
'maintnancepagetext'    => 'Esta página inclui várias ferramentas úteis para a manutenção. Algumas destas funcionalidades tendem a sobrecarregar a base de dados, por isso modere a sua utilização ;-)',
'maintenancebacklink'   => 'Voltar para a Página de manutenção',
'disambiguations'       => 'Página de desambiguações',
'disambiguationspage'   => 'Template:disambig',
'disambiguationstext'   => "As seguintes páginas ligam com uma <i>página de desambiguação</i>. Estas páginas deviam ligar com o tópico apropriado.<br />Qualquer página ligada com $1 é considerada página de desambiguação.<br />As ligações de outros domínios não são listadas aqui.",
'doubleredirects'       => 'Redireccionamentos duplos',
'doubleredirectstext'   => "Cada linha contém ligações para o primeiro e segundo redireccionamento, bem como a primeira linha de conteúdo do segundo redireccionamento, geralmente contendo a página destino \"real\", que devia ser o destino do primeiro redireccionamento.",
'doubleredirectsarrow'	=> '→',
'brokenredirects'       => 'Redireccionamentos quebrados',
'brokenredirectstext'   => 'Os seguintes redireccionamentos ligam para páginas inexistentes.',
'selflinks'             => 'Páginas que ligam consigo próprias',
'selflinkstext'             => 'As páginas seguintes ligam consigo próprias, o que é inútil.',
'mispeelings'           => 'Páginas com erros ortográficos',
'mispeelingstext'               => "As páginas seguintes contém erros ortográficos comuns, alguns deles listados em $1. Lá pode encontrar a grafia correcta (assim).",
'mispeelingspage'       => 'Lista de erros ortográficos comuns',
'missinglanguagelinks'  => 'Ligações interlinguísticas não encontradas',
'missinglanguagelinksbutton'    => 'Procurar ligações interlinguísticas inexistentes para',
'missinglanguagelinkstext'      => "Estas páginas <i>não</i> estão correctamente ligadas ao artigo relativo ao tema em $1. '''Não''' são incluídos redireccionamentos e subpáginas.",

# Miscellaneous special pages
#
'orphans'               => 'Páginas órfãs',
'validate'              => 'Validar página',
'lonelypages'   => 'Páginas órfãs',
'uncategorizedpages'    => 'Páginas não categorizadas',
'uncategorizedcategories'       => 'Categorias não categorizadas',
'unusedcategories' => 'Categorias não utilizadas',
'unusedimages'  => 'Ficheiros não utilizados',
'popularpages'  => 'Páginas populares',
'nviews'                => '$1 visitas',
'wantedcategories' => 'Categorias pedidas',
'wantedpages'   => 'Páginas pedidas',
'mostlinked'    => 'Páginas com mais afluentes',
'mostcategories' => 'Artigos com mais categorias',
'mostimages'    => 'Imagens com mais afluentes',
'mostrevisions' => 'Artigos com mais revisões',
'nlinks'                => '$1 links',
'allpages'              => 'Todas as páginas',
'prefixindex'   => 'Índice de prefixo',
'randompage'    => 'Página aleatória',
'randompage-url'=> 'Special:Random',
'shortpages'    => 'Páginas curtas',
'longpages'             => 'Páginas longas',
'deadendpages'  => 'Páginas sem saída',
'listusers'             => 'Lista de utilizadores',
'specialpages'  => 'Páginas especiais',
'spheading'             => 'Páginas especiais para todos os utilizadores',
'restrictedpheading'    => 'Páginas especiais restritas',
'protectpage'   => 'Proteger página',
'recentchangeslinked' => 'Alterações relacionadas',
'rclsub'                => "(para páginas linkadas de \"$1\")",
'debug'                 => 'Debug',
'newpages'              => 'Páginas novas',
'ancientpages'          => 'Páginas mais antigas',
'intl'          => 'Ligações interlínguas',
'move' => 'Mover',
'movethispage'  => 'Mover esta página',
'unusedimagestext' => '<p>Por favor note que outros websites como as Wikipédias internacionais podem apontar para uma imagem através de um URL directamente, e por isso pode estar aparecer aqui mesmo estando em uso.</p>',
'unusedcategoriestext' => 'As seguintes categorias existem embora nenhum artigo ou categoria faça uso delas.',
'booksources'   => 'Fontes de livros',
'categoriespagetext' => 'As seguintes categorias existem na wiki.',
'data'  => 'Dados',
'userrights' => 'Gestão de privilégios do utilizador',
'groups' => 'Grupos de utilizadores',

'booksourcetext' => "Abaixo encontra-se uma lista de ligações para outros websites que vendem livros novos ou usados, e poderão ter mais informações sobre os livros que procura.",
'isbn'  => 'ISBN',
'rfcurl' =>  'http://www.ietf.org/rfc/rfc$1.txt',
'pubmedurl' =>  'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'alphaindexline' => "$1 até $2",
'version'               => 'Versão',
'log'           => 'Registos',
'alllogstext'   => 'Exposição combinada de carregamento de ficheiros, eliminação, protecção, bloqueio, e de direitos.
Pode diminuir a lista escolhendo um tipo de registo, um nome de utilizar, ou uma página afectada.',

# Special:Allpages
'nextpage'          => 'Próxima página ($1)',
'allpagesfrom'          => 'Mostrar páginas começando em:',
'allarticles'           => 'Todos artigos',
'allnonarticles'        => 'Todos não-artigos',
'allinnamespace'        => 'Todas páginas (domínio $1)',
'allnotinnamespace'     => 'Todas páginas (não no domínio $1)',
'allpagesprev'          => 'Anterior',
'allpagesnext'          => 'Próximo',
'allpagessubmit'        => 'Ir',
'allpagesprefix'        => 'Exibir páginas com o prefixo:',

# E this user
#
'mailnologin'   => 'Nenhum endereço de envio',
'mailnologintext' => "Necessita de estar [[Special:Userlogin|autentificado]]
e de possuir um endereço de e-mail válido nas suas [[Special:Preferences|preferências]]
para enviar um e-mail a outros utilizadores.",
'emailuser'             => 'Contactar este utilizador',
'emailpage'             => 'Contactar utilizador',
'emailpagetext' => 'Se o utilizador introduziu um endereço válido de e-mail 
nas suas preferências, poderá usar o formulário abaixo para lhe enviar uma mensagem.
O endereço que introduziu nas suas preferências irá aparecer no campo "From" do e-mail 
para que o destinatário lhe possa responder.',
'usermailererror' => 'Objecto de correio retornou um erro: ',
'defemailsubject'  => "E-mail: {{SITENAME}}",
'noemailtitle'  => 'Sem endereço de e-mail',
'noemailtext'   => 'Este utilizador não especificou um endereço de e-mail válido, ou optou por não receber e-mail de outros utilizadores.',
'emailfrom'             => 'De',
'emailto'               => 'Para',
'emailsubject'  => 'Assunto',
'emailmessage'  => 'Mensagem',
'emailsend'             => 'Enviar',
'emailsent'             => 'E-mail enviado',
'emailsenttext' => 'A sua mensagem foi enviada.',

# Watchlist
#
'watchlist'                     => 'Artigos vigiados',
'watchlistsub'          => "(do utilizador \"$1\")",
'nowatchlist'           => 'Não existem itens na sua lista de artigos vigiados.',
'watchnologin'          => 'Não está autentificado',
'watchnologintext'      => 'Deve estar [[Special:Userlogin|autentificado]] para modificar sua lista de artigos vigiados.',
'addedwatch'            => 'Adicionado à lista',
'addedwatchtext'        => "A página \"$1\" foi adicionada à sua [[Special:Watchlist|lista de artigos vigiados]].
Modificações futuras neste artigo e páginas de discussão associadas serão listadas lá e a página aparecerá a <b>negrito</b> na [[Especial:Recentchanges|lista de mudanças recentes]], para que possa encontrá-la com maior facilidade.

Se desejar remover o artigo da sua lista de artigos vigiados, clique em \"Desinteressar-se\" na barra lateral ou de topo.",
'removedwatch'          => 'Removida da lista de artigos vigiados',
'removedwatchtext'      => "A página \"$1\" não é mais de seu interesse e portanto foi removida de sua lista de artigos vigiados",
'watch' => 'Vigiar',
'watchthispage'         => 'Vigiar esta página',
'unwatch' => 'Desinteressar-se',
'unwatchthispage'       => 'Parar de vigiar esta página',
'notanarticle'          => 'Não é um artigo',
'watchnochange'         => 'Nenhum dos itens vigiados foram editados no período exibido.',
'watchdetails'          => "* $1 páginas vigiadas, excluindo páginas de discussão
* [[Special:Watchlist/edit|Mostrar e editar a lista completa]]",
'wlheader-enotif'               => "* Notificação por email encontra-se activada.",
'wlheader-showupdated'   => "* Páginas modificadas desde a sua última visita são mostradas a '''negrito'''",
'watchmethod-recent'=> 'verificando edições recentes para os artigos vigiados',
'watchmethod-list'      => 'verificando páginas vigiadas para edições recentes',
'removechecked'         => 'Remover itens seleccionados',
'watchlistcontains' => "Sua lista contém $1 páginas.",
'watcheditlist'         => 'Aqui está uma lista alfabética de sua lista de artigos vigiados. Marque as caixas dos artigos que você deseja remover da lista e clique no botão \'Remover itens seleccionados\' na parte de baixo do ecrã (removendo uma página de discussão remove também a página associada e vice versa).',
'removingchecked'       => 'Removendo os itens solicitados de sua lista de artigos vigiados...',
'couldntremove'         => "Não foi possível remover o item '$1'...",
'iteminvalidname'       => "Problema com item '$1', nome inválido...",
'wlnote'                => 'Abaixo as últimas $1 mudanças nas últimas <b>$2</b> horas.',
'wlshowlast'            => 'Ver últimas $1 horas $2 dias $3',
'wlsaved'               => 'Esta é uma versão salva de sua lista de artigos vigiados.',
'wlhideshowown'         => '$1 minhas edições.',
'wlhideshowbots'        => '$1 edições por robôs.',
'wlshow'                => 'Mostrar',
'wlhide'                => 'Esconder',

'enotif_mailer'                 => '{{SITENAME}} Notification Mailer',
'enotif_reset'                  => 'Marcar todas páginas como visitadas',
'enotif_newpagetext'=> 'Isto é uma nova página.',
'changed'                       => 'alterada',
'created'                       => 'criada',
'enotif_subject'        => '{{SITENAME}}: A página $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited' => 'Consulte $1 para todas as alterações efectuadas desde a sua última visita.',
'enotif_body' => 'Caro $WATCHINGUSERNAME,

a página $PAGETITLE na {{SITENAME}} foi $CHANGEDORCREATED a $PAGEEDITDATE por $PAGEEDITOR, consulte $PAGETITLE_URL para a versão actual.

$NEWPAGE

Sumário de editor: $PAGESUMMARY $PAGEMINOREDIT

Contacte o editor:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Não haverão mais notificações no caso de futuras alterações a não ser que visite esta página. Poderá também restaurar as bandeiras de notificação para todas as suas páginas vigiadas na sua lista de artigos vigiados.

             O seu sistema de notificação amigável da {{SITENAME}}

--
Para alterar as suas preferências da lista de artigos vigiados, visite
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Contacto e assistência
{{SERVER}}{{localurl:Ajuda:Conteúdos}}',

# Delete/protect/revert
#
'deletepage'    => 'Eliminar página',
'confirm'               => 'Confirmar',
'excontent' => "conteúdo era: '$1'",
'excontentauthor' => "conteúdo era: '$1' (e o único editor era '$2')",
'exbeforeblank' => "conteúdo antes de esvaziar era: '$1'",
'exblank' => 'página esvaziada',
'confirmdelete' => 'Confirmar eliminação',
'deletesub'             => "(Eliminando \"$1\")",
'historywarning' => 'Aviso: A página que está prestes a eliminar possui um histórico: ',
'confirmdeletetext' => "Encontra-se prestes a eliminar permanentemente uma página ou uma imagem e todo o seu histórico da base de dados.
Por favor confirme que entende fazer isto, e que compreende as consequências, e que encontra-se a fazer isto de acordo com a [[Project:Política de eliminação|Política de eliminação]] do projecto.",
'actioncomplete' => 'Acção completada',
'deletedtext'   => "\"$1\" foi eliminada.
Consulte $2 para um registo de eliminações recentes.",
'deletedarticle' => "eliminada \"[[$1]]\"",
'dellogpage'    => 'Registo de eliminação',
'dellogpagetext' => 'Abaixo uma lista das eliminações mais recentes.',
'deletionlog'   => 'registo de eliminação',
'reverted'              => 'Revertido para versão mais nova',
'deletecomment' => 'Motivo de eliminação',
'imagereverted' => 'Reversão para versão mais nova foi bem sucedida.',
'rollback'              => 'Voltar edições',
'rollback_short' => 'Voltar',
'rollbacklink'  => 'voltar',
'rollbackfailed' => 'Reversão falhou',
'cantrollback'  => 'Não foi possível reverter a edição; o último contribuidor é o único autor deste artigo',
'alreadyrolled' => "Não foi possível reverter as edições de [[$1]]
por [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Discussão]]); alguém editou ou já reverteu o artigo.

A última edição foi de [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Discussão]]). ",

#   only shown if there is an edit comment
'editcomment' => "O sumário de edição era: \"<i>$1</i>\".",
'revertpage'    => "Revertidas edições por $2, para a última versão por $1",
'sessionfailure' => 'Foram detectados problemas com a sua sessão;
Esta acção foi cancelada como medida de protecção contra a intercepção de sessões.
Experimente usar o botão "Voltar atrás" e refrescar a página de onde veio, e repita o processo.',
'protectlogpage' => 'Registo de protecção',
'protectlogtext' => "Abaixo encontra-se o registo de protecção e desprotecção de páginas.
Veja [[Project:Página protegida]] para mais informações.",
'protectedarticle' => 'protegeu "[[$1]]"',
'unprotectedarticle' => 'desprotegeu "[[$1]]"',
'protectsub' => '(Protegendo "$1")',
'confirmprotecttext' => 'Deseja realmente proteger esta página?',
'confirmprotect' => 'Confirmar protecção',
'protectmoveonly' => 'Impedir apenas que a página seja movida.',
'protectcomment' => 'Motivo de protecção',
'unprotectsub' =>"(Desprotegendo \"$1\")",
'confirmunprotecttext' => 'Deseja realmente desproteger esta página?',
'confirmunprotect' => 'Confirmar desprotecção',
'unprotectcomment' => 'Motivo de desprotecção',

# Undelete
'undelete' => 'Ver páginas eliminadas',
'undeletepage' => 'Ver e restaurar páginas eliminadas',
'viewdeletedpage' => 'Ver páginas eliminadas',
'undeletepagetext' => 'As páginas seguintes foram eliminadas mas ainda permanecem na base de dados e podem ser restauradas. O arquivo pode ser limpo periodicamente.',
'undeletearticle' => 'Restaurar artigo eliminado',
'undeleterevisions' => "$1 revisões arquivadas",
'undeletehistory' => 'Se restaurar uma página, todas as revisões serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a eliminação, as revisões restauradas aparecerão primeiro no histórico e a página actual não será automaticamente trocada.',
'undeletehistorynoadmin' => 'Este artigo foi eliminado. O motivo para a eliminação é apresentado no súmario abaixo, junto dos detalhes do utilizador que editou esta página antes de eliminar. O texto actual destas revisões eliminadas encontra-se agora apenas disponível para administradores.',
'undeleterevision' => "Revisões eliminadas de $1",
'undeletebtn' => 'Restaurar!',
'undeletedarticle' => "restaurado \"[[$1]]\"",
'undeletedrevisions' => "$1 revisões restauradas",
'undeletedtext'   => "O artigo [[:$1|$1]] foi restaurado com sucesso.
É mantido um [[Especial:Log/delete|registo de eliminações]] e restauros recentes.",

# Namespace form on various pages
'namespace' => 'Domínio:',
'invert' => 'Inverter selecção',

# Contributions
#
'contributions' => 'Contribuições do utilizador',
'mycontris'     => 'Minhas contribuições',
'contribsub'    => "Para $1",
'nocontribs'    => 'Não foram encontradas mudanças com este critério.',
'ucnote'        => "Segue as últimas <b>$1</b> mudanças nos últimos <b>$2</b> dias deste utilizador.",
'uclinks'       => "Ver as últimas $1 mudanças; ver os últimos $2 dias.",
'uctop'         => ' (topo)' ,
'newbies'       => 'novatos',
'contribs-showhideminor' => '$1 edições menores',

# What links here
#
'whatlinkshere' => 'Artigos afluentes',
'notargettitle' => 'Sem alvo',
'notargettext'  => 'Não especificou uma página alvo ou utilizador para executar esta função.',
'linklistsub'   => '(Lista de ligações)',
'linkshere'             => 'Os seguintes artigos contêm ligações para este:',
'nolinkshere'   => 'Não há ligações para esta página.',
'isredirect'    => 'página de redireccionamento',

# Block/unblock IP
#
'blockip'               => 'Bloquear utilizador',
'blockiptext'   => "Utilize o formulário abaixo para bloquear o acesso à escrita de um endereço específico de IP ou nome de utilizador.
Isto só deve ser feito para prevenir vandalismo, e de acordo com a [[{{ns:4}}:Política|política da {{SITENAME}}]]. Preencha com um motivo específico (por exemplo, citando páginas que sofreram vandalismo).",
'ipaddress'             => 'Endereço de IP',
'ipadressorusername' => 'Endereço de IP ou nome de utilizador',
'ipbexpiry'             => 'Prazo',
'ipbreason'             => 'Motivo',
'ipbsubmit'             => 'Bloquear este utilizador',
'ipbother'              => 'Outro tempo',
'ipboptions'            => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,infinito:infinite',
'ipbotheroption'        => 'outro',
'badipaddress'  => 'O endereço de IP inválido',
'blockipsuccesssub' => 'Bloqueio bem sucedido',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|$1]] foi bloqueado.<br />Consulte a [[Special:Ipblocklist|lista de IPs bloqueados]] para rever os bloqueios.',
'unblockip'             => 'Desbloquear utilizador',
'unblockiptext' => 'Utilize o formulário a seguir para restaurar o acesso a escrita para um endereço de IP ou nome de utilizador previamente bloqueado.',
'ipusubmit'             => 'Desbloquear este utilizador',
'ipusuccess'    => "\"[[$1]]\" foi desbloqueado",
'ipblocklist'   => 'Lista de IPs bloqueados',
'blocklistline' => "$1, $2 bloqueou $3 ($4)",
'ipblocklistempty'      => 'A lista de IPs bloqueados encontra-se vazia.',
'infiniteblock' => 'infinito',
'expiringblock' => 'expira em $1',
'blocklink'             => 'bloquear',
'unblocklink'   => 'desbloquear',
'contribslink'  => 'contribs',
'autoblocker'   => "Foi automaticamente bloqueado pois partilha um endereço de IP com \"$1\". Motivo é: \"$2\".",
'blocklogpage'  => 'Registo de bloqueio',
'blocklogentry' => 'bloqueou \"[[$1]]\" com um tempo de expiração de $2',
'blocklogtext'  => 'Isto é um registo de acções de bloqueio e desbloqueio. Endereços IP sujeitos a bloqueio automático não são listados. Consulte a [[Special:Ipblocklist|lista de IPs bloqueados]] para obter a lista de bloqueios operativos e bloqueios actualmente válidos.',
'unblocklogentry'       => 'desbloqueou $1',
'range_block_disabled'  => 'A funcionalidade de bloquear gamas de IPs encontra-se desactivada.',
'ipb_expiry_invalid'    => 'Tempo de expiração inválido.',
'ip_range_invalid'      => "Gama de IPs inválida.",
'proxyblocker'  => 'Bloqueador de proxy',
'proxyblockreason'      => 'O seu endereço de IP foi bloqueado por ser um proxy público. Por favor contacte o seu fornecedor do serviço de Internet ou o apoio técnico e informe-os deste problema de segurança grave.',
'proxyblocksuccess'     => "Terminado.",
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'O seu endereço IP encontra-se listado como proxy aberto em [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'O seu endereço de IP encontra-se listado como proxy aberto no [http://www.sorbs.net SORBS] DNSBL. Não pode criar uma conta',

# Developer tools
#
'lockdb'                => 'Trancar base de dados',
'unlockdb'              => 'Destrancar base de dados',
'lockdbtext'    => 'Trancar a base de dados suspenderá a habilidade de todos os utilizadores de editarem páginas, mudarem suas preferências, lista de artigos vigiados e outras coisas que requerem mudanças na base de dados.<br />
Por favor confirme que realmente pretende fazer isso, e que vai destrancar a base de dados quando a manutenção estiver concluída.',
'unlockdbtext'  => 'Desbloquear a base de dados vai restaurar a habilidade de todos os utilizadores de editar  artigos,  mudar suas preferências, editar suas listas de artigos vigiados e outras coisas que requerem mudanças na base de dados. Por favor , confirme que realmente pretende fazer isso.',
'lockconfirm'   => 'Sim, eu realmente desejo trancar a base de dados.',
'unlockconfirm' => 'Sim, eu realmente desejo destrancar a base de dados.',
'lockbtn'               => 'Trancar base de dados',
'unlockbtn'             => 'Destrancar base de dados',
'locknoconfirm' => 'Você não marcou a caixa de confirmação.',
'lockdbsuccesssub' => 'Tranca bem sucedida',
'unlockdbsuccesssub' => 'Destranca bem sucedida',
'lockdbsuccesstext' => 'A base de dados da {{SITENAME}} foi trancada.
<br />Lembre-se de remover a tranca após a manutenção.',
'unlockdbsuccesstext' => 'A base de dados foi destrancada.',

# Make sysop
'makesysoptitle'        => 'Tornar um utilizador num administrador',
'makesysoptext'         => 'Este formulário é utilizado por burocratas para tornar utilizadores comuns em administradores.
Introduza o nome do utilizador na caixa e clique no botão para tornar o utilizador num administrador',
'makesysopname'         => 'Nome do utilizador:',
'makesysopsubmit'       => 'Tornar este utilizador num administrador',
'makesysopok'           => "<b>Utilizador \"$1\" é agora um administrador</b>",
'makesysopfail'         => "<b>Não foi possível tornar o utilizador \"$1\" num administrador. (Introduziu o nome correctamente?)</b>",
'setbureaucratflag' => 'Colocar flag de burocrata',
'setstewardflag'    => 'Colocar flag de steward',
'bureaucratlog'         => 'Registo de acções burocráticas',
'rightslogtext'         => 'Este é um registo de mudanças nos direitos dos utilizadores.',
'bureaucratlogentry'    => "Alterado grupo do membro de $1 de $2 para $3",
'rights'                        => 'Direitos:',
'set_user_rights'       => 'Definir direitos de utilizador',
'user_rights_set'       => "<b>Direitos de utilizador para \"$1\" actualizados</b>",
'set_rights_fail'       => "<b>Direitos de utilizador para \"$1\" não poderam ser definidos. (Introduziu o nome correctamente?)</b>",
'makesysop'         => 'Tornar um utilizador num administrador',
'already_sysop'     => 'Este utilizador já é um administrador',
'already_bureaucrat' => 'Este utilizador já é um burocrata',
'already_steward'   => 'Este utilizador já é um steward',

# Validation
'val_yes' => 'Sim',
'val_no' => 'Não',
'val_of' => '$1 de $2',
'val_revision' => 'Revisão',
'val_time' => 'Tempo',
'val_user_stats_title' => 'Validação do(a) utilizador(a) $1',
'val_my_stats_title' => 'Minha validação',
'val_list_header' => '<th>#</th><th>Tópico</th><th>Escala</th><th>Acção</th>',
'val_add' => 'Adicionar',
'val_del' => 'Apagar',
'val_show_my_ratings' => 'Mostrar as minhas validações',
'val_revision_number' => 'Revisão #$1',
'val_warning' => '<b>Nunca, <i>mas nunca</i>, altere algo aqui sem consenso <i>explícito</i> da comunidade!</b>',
'val_rev_for' => 'Revisões para $1',
'val_details_th_user' => 'Utilizador $1',
'val_validation_of' => 'Validação de "$1"',
'val_revision_of' => 'Revisão de $1',
'val_revision_changes_ok' => 'Suas avaliações foram guardadas!',
'val_rev_stats' => 'Ver as estatísticas de validação para "$1" <a href="$2">aqui</a>',
'val_revision_stats_link' => 'detalhes',
'val_iamsure' => 'Marque esta caixa caso realmente desejá-lo!',
'val_details_th' => '<sub>Utilizador</sub> \\ <sup>Tópico</sup>',
'val_clear_old' => 'Limpar os meus dados antigos de validação',
'val_merge_old' => 'Utilizar a minha anterior avaliação quando marcado \'Sem opinião\'',
'val_form_note' => "'''Dica:''' Fundindo os seus dados significa que para a revisão do artigo que seleccionou, todas as opções onde especificou ''sem opinião'' serão colocados com o valor e comentário da revisão mais recente pelo qual expressou uma opinião. Por exemplo, se deseja alterar uma única opção para uma versão nova, mas também desejando manter as suas outras preferências para este artigo nesta revisão, apenas seleccione a opção que deseja ''alterar'', e fundindo irá preencher as outras opções com as suas anteriores preferências.",
'val_noop' => 'Sem opinião',
'val_topic_desc_page' => 'Project:Validação de tópicos',
'val_votepage_intro' => 'Altere este texto <a href="{{SERVER}}{{localurl:MediaWiki:Val_votepage_intro}}">aqui</a>!',
'val_percent' => '<b>$1%</b><br />($2 of $3 pontos<br />por $4 utilizadores)',
'val_percent_single' => '<b>$1%</b><br />($2 of $3 pontos<br />por um utilizador)',
'val_total' => 'Total',
'val_version' => 'Versão',
'val_tab' => 'Validar',
'val_this_is_current_version' => 'esta é a versão mais recente',
'val_version_of' => "Versão de $1" ,
'val_table_header' => '<tr><th>Classe</th>$1<th colspan="4">Opinião</th>$1<th>Comentário</th></tr>',
'val_stat_link_text' => 'Estatísticas de validação para este artigo',
'val_view_version' => 'Ver esta revisão',
'val_validate_version' => 'Validar esta versão',
'val_user_validations' => 'Este utilizador validou $1 páginas.',
'val_no_anon_validation' => 'Tem de estar ligado para poder validar um artigo.',
'val_validate_article_namespace_only' => 'Apenas artigos podem ser validados. Esta página <i>não</i> se encontra no domínio principal.',
'val_validated' => 'Validação concluída.',
'val_article_lists' => 'Lista de artigos validados',
'val_page_validation_statistics' => 'Estatísticas de validação de página para $1',

# Move page
#
'movepage'              => 'Mover página',
'movepagetext'  => 'Utilizando o seguinte formulário poderá renomear uma página, movendo todo o histórico para o novo título. O título antigo será transformado num redireccionamento para o novo.
Links para as páginas antigas não serão mudados; certifique-se de [[Especial:Maintenance|verificar]] redireccionamentos quebrados ou artigos duplos. Você é responsável por certificar-se que os links continuam apontando para onde eles deveriam apontar.

Note que a página \'\'\'não\'\'\' será movida se já existir uma página com o novo título, a não ser que ele esteja vazio ou seja um redircecionamento e não tenha histórico de edições. Isto significa que pode renomear uma página de volta para o nome que tinha antigamente se cometer algum engano e que não pode sobrescrever uma página.

<b>CUIDADO!!!</b>
Isto pode ser uma mudança drástica e inesperada para uma página popular; por favor, tenha certeza de que compreende as consequências da mudança antes de avançar.',
'movepagetalktext' => 'A página de "discussão" associada, se existir, será automaticamente movida, \'\'\'a não ser que:\'\'\'
*Você esteja movendo uma página estre namespaces,
*Uma página de discussão (não-vazia) já exista sob o novo título, ou
*Você não marque a caixa abaixo.

Nestes casos, você terá que mover ou mesclar a página manualmente, se desejar.',
'movearticle'   => 'Mover página',
'movenologin'   => 'Não autentificado',
'movenologintext' => "Você deve ser um utilizador registado e [[Special:Userlogin|autentificado]]</a>
para mover uma página.",
'newtitle'              => 'Para novo título',
'movepagebtn'   => 'Mover página',
'pagemovedsub'  => 'Página movida com sucesso',
'pagemovedtext' => "Página \"[[$1]]\" movida para \"[[$2]]\".",
'articleexists' => 'Uma página com este título já existe, ou o título que escolheu é inválido.
Por favor, escolha outro nome.',
'talkexists'    => "'''A página em si foi movida com sucesso, porém a página de discussão não pode ser movida, pois, já existe uma com este título. Por favor, mescle-as manualmente.'''",
'movedto'               => 'movido para',
'movetalk'              => 'Mover também a página de "discussão", se aplicável.',
'talkpagemoved' => 'A página de \"discussão\" correspondente foi movida com sucesso.',
'talkpagenotmoved' => 'A página de discussão correspondente <strong>não</strong> foi movida.',
'1movedto2'             => "[[$1]] movido para [[$2]]",
'1movedto2_redir' => '[[$1]] movido para [[$2]] sob redireccionamento',
'movelogpage' => 'Registo de movimento',
'movelogpagetext' => 'Abaixo encontra-se uma lista de páginas movidas.',
'movereason'    => 'Motivo',
'revertmove'    => 'reverter',
'delete_and_move' => 'Eliminar e mover',
'delete_and_move_text'  => 
'==Eliminação necesária==
O artigo destinatário "[[$1]]" já existe. Deseja o eliminar de modo a poder mover?',
'delete_and_move_reason' => 'Eliminada para poder mover outra página para este título',
'selfmove' => "O título fonte e o título destinatário são os mesmos; não é possível mover uma página para o mesmo sítio.",
'immobile_namespace' => "O título destinatário é de um tipo especial; não é possível mover páginas para esse domínio.",

# Export

'export'                => 'Exportação de páginas',
'exporttext'    => 'É possível exportar o texto e o histórico de edições de uma página em particular num ficheiro XML. Poderá então importar esse conteúdo em outra wiki que utilize o software MediaWiki, ou transformar o conteúdo (via XSLT), ou ainda manter o ficheiro por motivos particulares.

Para exportar páginas, introduza os títulos na caixa de texto abaixo, um título por linha, e seleccione se deseja apenas a versão actual ou todas versões.

Se desejar pode utilizar uma ligação, por exemplo [[{{ns:Special}}:Export/{{Mediawiki:mainpage}}]] para o artigo [[{{Mediawiki:mainpage}}]].',
'exportcuronly' => 'Incluir apenas a revisão actual, não o histórico inteiro',

# Namespace 8 related

'allmessages'   => 'Todas mensagens de sistema',
'allmessagesname' => 'Nome',
'allmessagesdefault' => 'Texto padrão',
'allmessagescurrent' => 'Texto actual',
'allmessagestext'       => 'Esta é uma lista de todas mensagens de sistema disponíveis no domínio MediaWiki:.',
'allmessagesnotsupportedUI' => 'O seu actual idioma de interface <b>$1</b> não é suportado pelo {{ns:special}}:AllMessages deste sítio. ',
'allmessagesnotsupportedDB' => '{{ns:special}}:AllMessages não encontra-se operacional devido ao wgUseDatabaseMessages encontrar-se desligado.',

# Thumbnails

'thumbnail-more'        => 'Ampliar',
'missingimage'          => "<b>Imagem não encontrada</b><br /><i>$1</i>",
'filemissing'           => 'Ficheiro não encontrado',

# Special:Import
'import'        => 'Importar páginas',
'importinterwiki' => 'Importação transwiki',
'importtext'    => 'Por favor exporte o ficheiro da fonte wiki utilizando o utilitário Especial:Export, salve o ficheiro para o seu disco e importe-o aqui.',
'importfailed'  => "Importação falhou: $1",
'importnotext'  => 'Vazio ou sem texto',
'importsuccess' => 'Importação bem sucedida!',
'importhistoryconflict' => 'Existem conflitos de revisões no histórico (poderá já ter importado esta página antes)',
'importnosources' => 'Não foram definidas fontes de importação transwiki e o carregamento directo de históricos encontra-se desactivado.',
'importnofile' => 'Nenhum ficheiro de importação foi carregado.',
'importuploaderror' => 'O carregamento do ficheiro de importação falhou; talvez o ficheiro seja maior do que o tamanho de carregamento permitido.',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Pesquisar na {{SITENAME}} [alt-f]',
'tooltip-minoredit' => 'Marcar como edição menor [alt-i]',
'tooltip-save' => 'Salvar as alterações [alt-s]',
'tooltip-preview' => 'Prever as alterações, por favor utilizar antes de salvar! [alt-p]',
'tooltip-diff' => 'Mostrar alterações que fez a este texto. [alt-d]',
'tooltip-compareselectedversions' => 'Ver as diferenças entre as duas versões seleccionadas desta página. [alt-v]',
'tooltip-watch' => 'Adicionar esta página à sua lista de artigos vigiados [alt-w]',

# stylesheets
'Monobook.css' => '/* edite este ficheiro para alterar a skin monobook para o site inteiro */',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
'notacceptable' => 'O servidor não pode fornecer os dados num formato que o seu cliente possa ler.',

# Attribution

'anonymous' => 'Utilizador(es) anónimo(s) da {{SITENAME}}',
'siteuser' => '{{SITENAME}} utilizador $1',
'lastmodifiedby' => 'Esta página foi modificada pela última vez a $1 por $2.',
'and' => 'e',
'othercontribs' => 'Baseado no trabalho de $1.',
'others' => 'outros',
'siteusers' => '{{SITENAME}} utilizador(es) $1',
'creditspage' => 'Créditos da página',
'nocredits' => 'Não há informação disponível sobre os créditos desta página.',

# Spam protection

'spamprotectiontitle' => 'Filtro de protecção contra spam',
'spamprotectiontext' => 'A página que deseja salvar foi bloqueada pelo filtro de spam. Tal bloqueio foi provavelmente causado por uma ligação para um website externo.',
'spamprotectionmatch' => 'O seguinte texto activou o filtro de spam: $1',
'subcategorycount' => "Existem $1 subcategorias nesta categoria.",
'subcategorycount1' => "Existe $1 subcategoria nesta categoria.",
'categoryarticlecount' => "Existem $1 artigos nesta categoria.",
'categoryarticlecount1' => "Existe $1 artigo nesta categoria.",
'usenewcategorypage' => "1\n\nSet first character to \"0\" to disable the new category page layout.",
'listingcontinuesabbrev' => " cont.",

# Info page
'infosubtitle' => 'Informação para página',
'numedits' => 'Número de edições (artigo): $1',
'numtalkedits' => 'Número de edições (página de discussão): $1',
'numwatchers' => 'Number of watchers: $1',
'numauthors' => 'Número de autores distintos (artigo): $1',
'numtalkauthors' => 'Número de autores distintos (página de discussão): $1',

# Math options
'mw_math_png' => 'Gerar sempre PNG',
'mw_math_simple' => 'HTML caso seja simples, caso contrário PNG',
'mw_math_html' => 'HTML se possível, caso contrário PNG',
'mw_math_source' => 'Deixar como TeX (para navegadores de texto)',
'mw_math_modern' => 'Recomendado para navegadores modernos',
'mw_math_mathml' => 'MathML se possível (experimental)',

# Patrolling
'markaspatrolleddiff'   => "Mark as patrolled",
'markaspatrolledlink'   => "[$1]",
'markaspatrolledtext'   => "Mark this article as patrolled",
'markedaspatrolled'     => "Marked as patrolled",
'markedaspatrolledtext' => "The selected revision has been marked as patrolled.",
'rcpatroldisabled'      => "Recent Changes Patrol disabled",
'rcpatroldisabledtext'  => "The Recent Changes Patrol feature is currently disabled.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Minha página de utilizador\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'A página de utilizador para o ip que está a utilizar para editar\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Minha página de discussão\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Discussão sobre edições deste endereço de ip\');
ta[\'pt-preferences\'] = new Array(\'\',\'Minhas preferências\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Lista de artigos vigiados.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Lista das minhas contribuições\');
ta[\'pt-login\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Sair\');
ta[\'ca-talk\'] = new Array(\'t\',\'Discussão sobre o conteúdo da página\');
ta[\'ca-edit\'] = new Array(\'e\',\'Você pode editar esta página. Por favor, utilize o botão Mostrar Previsão antes de salvar.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Adicionar comentário a essa discussão.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Esta página está protegida; você pode exibir seu código, no entanto.\');
ta[\'ca-history\'] = new Array(\'h\',\'Edições anteriores desta página.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Proteger esta página\');
ta[\'ca-delete\'] = new Array(\'d\',\'Apagar esta página\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Restaurar edições feitas a esta página antes da eliminação\');
ta[\'ca-move\'] = new Array(\'m\',\'Mover esta página\');
ta[\'ca-watch\'] = new Array(\'w\',\'Adicionar esta página aos artigos vigiados\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Remover esta página dos artigos vigiados\');
ta[\'search\'] = new Array(\'f\',\'Pesquisar nesta wiki\');
ta[\'p-logo\'] = new Array(\'\',\'Página principal\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Visitar a página principal\');
ta[\'n-portal\'] = new Array(\'\',\'Sobre o projecto\');
ta[\'n-currentevents\'] = new Array(\'\',\'Informação temática sobre eventos actuais\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'A lista de mudanças recentes nesta wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Carregar página aleatória\');
ta[\'n-help\'] = new Array(\'\',\'Um local reservado para auxílio.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Ajude-nos\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Lista de todas as páginas que ligam-se a esta\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Mudanças recentes em páginas relacionadas a esta\');
ta[\'feed-rss\'] = new Array(\'\',\'Feed RSS desta página\');
ta[\'feed-atom\'] = new Array(\'\',\'Feed Atom desta página\');
ta[\'t-contributions\'] = new Array(\'\',\'Ver as contribuições deste utilizador\');
ta[\'t-emailuser\'] = new Array(\'\',\'Enviar um e-mail a este utilizador\');
ta[\'t-upload\'] = new Array(\'u\',\'Carregar imagens ou ficheiros media\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Lista de páginas especiais\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Ver o conteúdo da página\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Ver a página de utilizador\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Ver a página de media\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Esta é uma página especial, não pode ser editada.\');
ta[\'ca-nstab-wp\'] = new Array(\'a\',\'Ver a página de projecto\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Ver a página de imagem\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Ver a mensagem de sistema\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Ver a predefinição\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Ver a página de ajuda\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Ver a página da categoria\');',

# image deletion
'deletedrevision' => 'Versão antiga $1 apagada.',

# browsing diffs
'previousdiff' => '← Ver a alteração anterior',
'nextdiff' => 'Ver a alteração posterior →',

'imagemaxsize' => 'Limitar imagens nas página de descrição a: ',
'thumbsize'     => 'Tamanho de miniaturas: ',
'showbigimage' => 'Descarregar versão de maior resolução ($1x$2, $3 KB)',

'newimages' => 'Galeria de novos ficheiros',
'showhidebots' => '($1 robôs)',
'noimages'  => 'Nada para ver.',

# short names for language variants used for language conversion links. 
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Utilizador: ',
'speciallogtitlelabel' => 'Título: ',

'passwordtooshort' => 'A sua palavra-chave é demasiado curta. Deve ter no mínimo $1 caracteres.',

# Media Warning
'mediawarning' => '\'\'\'Aviso\'\'\': Este ficheiro pode conter código malicioso, ao executar o seu sistema poderá estar comprometido.
<hr>',

'fileinfo' => '$1KB, tipo MIME: <code>$2</code>',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Este ficheiro contém informação adicional, provavelmente adicionada a partir da câmara digital ou scanner utilizada para criar ou digitalizar a imagem. Caso o ficheiro tenha sido modificado a partir do seu estado original, alguns detalhes poderão não reflectir completamente as mudanças efectuadas.',
'metadata-expand' => 'Mostrar restantes detalhes',
'metadata-collapse' => 'Esconder restantes detalhes',
'metadata-fields' => 'Os campos EXIF metadata listados nesta mensagem serão
incluídos na apresentação da página de detalhes da imagem quando a tabela da metadata
for minimizada. Outros serão escondidos por defeito.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',
# Exif tags
'exif-imagewidth' =>'Largura',
'exif-imagelength' =>'Altura',
'exif-bitspersample' =>'Bits por componente',
'exif-compression' =>'Esquema de compressão',
'exif-photometricinterpretation' =>'Composição pixel',
'exif-orientation' =>'Orientação',
'exif-samplesperpixel' =>'Número de componentes',
'exif-planarconfiguration' =>'Arranjo de dados',
'exif-ycbcrsubsampling' =>'Subsampling ratio of Y to C',
'exif-ycbcrpositioning' =>'Posicionamento Y e C',
'exif-xresolution' =>'Resolução horizontal',
'exif-yresolution' =>'Resolução vertical',
'exif-resolutionunit' =>'Unit of X and Y resolution',
'exif-stripoffsets' =>'Localização de dados da imagem',
'exif-rowsperstrip' =>'Number of rows per strip',
'exif-stripbytecounts' =>'Bytes per compressed strip',
'exif-jpeginterchangeformat' =>'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' =>'Bytes de dados JPEG',
'exif-transferfunction' =>'Função de transferência',
'exif-whitepoint' =>'White point chromaticity',
'exif-primarychromaticities' =>'Chromaticities of primarities',
'exif-ycbcrcoefficients' =>'Color space transformation matrix coefficients',
'exif-referenceblackwhite' =>'Par de valores de referência de preto e branco',
'exif-datetime' =>'Data e hora de modificação do ficheiro',
'exif-imagedescription' =>'Título',
'exif-make' =>'Fabricante da câmara',
'exif-model' =>'Modelo da câmara',
'exif-software' =>'Software utilizado',
'exif-artist' =>'Autor',
'exif-copyright' =>'Licença',
'exif-exifversion' =>'Versão Exif',
'exif-flashpixversion' =>'Versão de Flashpix suportada',
'exif-colorspace' =>'Espaço de cor',
'exif-componentsconfiguration' =>'Significado de cada componente',
'exif-compressedbitsperpixel' =>'Modo de compressão de imagem',
'exif-pixelydimension' =>'Largura de imagem válida',
'exif-pixelxdimension' =>'Altura de imagem válida',
'exif-makernote' =>'Anotações do fabricante',
'exif-usercomment' =>'Comentários de utilizadores',
'exif-relatedsoundfile' =>'Ficheiro áudio relacionado',
'exif-datetimeoriginal' =>'Data e hora de geração de dados',
'exif-datetimedigitized' =>'Data e hora de digitalização',
'exif-subsectime' =>'DateTime subseconds',
'exif-subsectimeoriginal' =>'DateTimeOriginal subseconds',
'exif-subsectimedigitized' =>'DateTimeDigitized subseconds',
'exif-exposuretime' =>'Tempo de exposição',
'exif-exposuretime-format' => '$1 sec ($2)',
'exif-fnumber' =>'Número F',
'exif-fnumber-format' =>'f/$1',
'exif-exposureprogram' =>'Programa de exposição',
'exif-spectralsensitivity' =>'Spectral sensitivity',
'exif-isospeedratings' =>'Taxa de velocidade ISO',
'exif-oecf' =>'Optoelectronic conversion factor',
'exif-shutterspeedvalue' =>'Shutter speed',
'exif-aperturevalue' =>'Aperture',
'exif-brightnessvalue' =>'Brightness',
'exif-exposurebiasvalue' =>'Exposure bias',
'exif-maxaperturevalue' =>'Maximum land aperture',
'exif-subjectdistance' =>'Distância do sujeito',
'exif-meteringmode' =>'Metering mode',
'exif-lightsource' =>'Fonte de luz',
'exif-flash' =>'Flash',
'exif-focallength' =>'Comprimento de foco da lente',
'exif-focallength-format' =>'$1 mm',
'exif-subjectarea' =>'Área de sujeito',
'exif-flashenergy' =>'Energia do flash',
'exif-spatialfrequencyresponse' =>'Spatial frequency response',
'exif-focalplanexresolution' =>'Focal plane X resolution',
'exif-focalplaneyresolution' =>'Focal plane Y resolution',
'exif-focalplaneresolutionunit' =>'Focal plane resolution unit',
'exif-subjectlocation' =>'Subject location',
'exif-exposureindex' =>'Exposure index',
'exif-sensingmethod' =>'Sensing method',
'exif-filesource' =>'Fonte do ficheiro',
'exif-scenetype' =>'Scene type',
'exif-cfapattern' =>'CFA pattern',
'exif-customrendered' =>'Custom image processing',
'exif-exposuremode' =>'Modo de exposição',
'exif-whitebalance' =>'White Balance',
'exif-digitalzoomratio' =>'Digital zoom ratio',
'exif-focallengthin35mmfilm' =>'Focal length in 35 mm film',
'exif-scenecapturetype' =>'Scene capture type',
'exif-gaincontrol' =>'Scene control',
'exif-contrast' =>'Contrast',
'exif-saturation' =>'Saturation',
'exif-sharpness' =>'Sharpness',
'exif-devicesettingdescription' =>'Descrição das configurações do dispositivo',
'exif-subjectdistancerange' =>'Distância de alcance do sujeito',
'exif-imageuniqueid' =>'Unique image ID',
'exif-gpsversionid' =>'GPS tag version',
'exif-gpslatituderef' =>'North or South Latitude',
'exif-gpslatitude' =>'Latitude',
'exif-gpslongituderef' =>'East or West Longitude',
'exif-gpslongitude' =>'Longitude',
'exif-gpsaltituderef' =>'Altitude reference',
'exif-gpsaltitude' =>'Altitude',
'exif-gpstimestamp' =>'Tempo GPS (relógio atómico)',
'exif-gpssatellites' =>'Satélites utilizados para a medição',
'exif-gpsstatus' =>'Estado do receptor',
'exif-gpsmeasuremode' =>'Modo da medição',
'exif-gpsdop' =>'Precisão da medição',
'exif-gpsspeedref' =>'Unidade da velocidade',
'exif-gpsspeed' =>'Velocidade do receptor GPS',
'exif-gpstrackref' =>'Referência para a direcção do movimento',
'exif-gpstrack' =>'Direcção do movimento',
'exif-gpsimgdirectionref' =>'Referência para a direcção da imagem',
'exif-gpsimgdirection' =>'Direcção da imagem',
'exif-gpsmapdatum' =>'Utilizados dados do estudo Geodetic',
'exif-gpsdestlatituderef' =>'Referência para a latitude do destino',
'exif-gpsdestlatitude' =>'Latitude do destino',
'exif-gpsdestlongituderef' =>'Referência para a longitude do destino',
'exif-gpsdestlongitude' =>'Longitude do destino',
'exif-gpsdestbearingref' =>'Reference for bearing of destination',
'exif-gpsdestbearing' =>'Bearing of destination',
'exif-gpsdestdistanceref' =>'Referência de distância para o destino',
'exif-gpsdestdistance' =>'Distância para o destino',
'exif-gpsprocessingmethod' =>'Nome do método de processamento do GPS',
'exif-gpsareainformation' =>'Nome da área do GPS',
'exif-gpsdatestamp' =>'Data do GPS',
'exif-gpsdifferential' =>'Correcção do diferencial do GPS',

# Make & model, can be wikified in order to link to the camera and model name

'exif-make-value' => '$1',
'exif-model-value' =>'$1',
'exif-software-value' => '$1',

# Exif attributes

'exif-compression-1' => 'Descomprimido',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normal', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Flipped horizontally', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotated 180Â°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Flipped vertically', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotated 90Â° CCW and flipped vertically', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotated 90Â° CW', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotated 90Â° CW and flipped vertically', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotated 90Â° CCW', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'formato irregular',
'exif-planarconfiguration-2' => 'formato plano',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'não existe',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Não definido',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Aperture priority',
'exif-exposureprogram-4' => 'Shutter priority',
'exif-exposureprogram-5' => 'Creative program (biased toward depth of field)',
'exif-exposureprogram-6' => 'Programa de movimento (tende a velocidade de disparo mais rápida)',
'exif-exposureprogram-7' => 'Modo de retrato (para fotos em <i>closeup</i> com o fundo fora de foco)',
'exif-exposureprogram-8' => 'Modo de paisagem (para fotos de paisagem com o fundo em foco)',

'exif-subjectdistance-value' => '$1 metros',


'exif-meteringmode-0' => 'Unknown',
'exif-meteringmode-1' => 'Average',
'exif-meteringmode-2' => 'CenterWeightedAverage',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'MultiSpot',
'exif-meteringmode-5' => 'Pattern',
'exif-meteringmode-6' => 'Partial',
'exif-meteringmode-255' => 'Other',

'exif-lightsource-0' => 'Desconhecida',
'exif-lightsource-1' => 'Luz do dia',
'exif-lightsource-2' => 'Fluorescente',
'exif-lightsource-3' => 'Tungsten (incandescent light)',
'exif-lightsource-4' => 'Flash',
'exif-lightsource-9' => 'Fine weather',
'exif-lightsource-10' => 'Tempo nublado',
'exif-lightsource-11' => 'Shade',
'exif-lightsource-12' => 'Daylight fluorescent (D 5700 â€“ 7100K)',
'exif-lightsource-13' => 'Day white fluorescent (N 4600 â€“ 5400K)',
'exif-lightsource-14' => 'Cool white fluorescent (W 3900 â€“ 4500K)',
'exif-lightsource-15' => 'White fluorescent (WW 3200 â€“ 3700K)',
'exif-lightsource-17' => 'Standard light A',
'exif-lightsource-18' => 'Standard light B',
'exif-lightsource-19' => 'Standard light C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studio tungsten',
'exif-lightsource-255' => 'Other light source',

'exif-focalplaneresolutionunit-2' => 'polegadas',

'exif-sensingmethod-1' => 'Undefined',
'exif-sensingmethod-2' => 'One-chip color area sensor',
'exif-sensingmethod-3' => 'Two-chip color area sensor',
'exif-sensingmethod-4' => 'Three-chip color area sensor',
'exif-sensingmethod-5' => 'Color sequential area sensor',
'exif-sensingmethod-7' => 'Trilinear sensor',
'exif-sensingmethod-8' => 'Color sequential linear sensor',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'A directly photographed image',

'exif-customrendered-0' => 'Processo normal',
'exif-customrendered-1' => 'Processo personalizado',

'exif-exposuremode-0' => 'Exposição automática',
'exif-exposuremode-1' => 'Exposição manual',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'Auto white balance',
'exif-whitebalance-1' => 'Manual white balance',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landscape',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Night scene',

'exif-gaincontrol-0' => 'None',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Soft',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Low saturation',
'exif-saturation-2' => 'High saturation',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Soft',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Desconhecida',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Vista próxima',
'exif-subjectdistancerange-3' => 'Vista distante',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'North latitude',
'exif-gpslatitude-s' => 'South latitude',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'East longitude',
'exif-gpslongitude-w' => 'West longitude',

'exif-gpsstatus-a' => 'Measurement in progress',
'exif-gpsstatus-v' => 'Measurement interoperability',

'exif-gpsmeasuremode-2' => '2-dimensional measurement',
'exif-gpsmeasuremode-3' => '3-dimensional measurement',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Quilómetros por hora',
'exif-gpsspeed-m' => 'Milhas por hora',
'exif-gpsspeed-n' => 'Nós',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'True direction',
'exif-gpsdirection-m' => 'Magnetic direction',

# external editor support
'edit-externally' => 'Editar este ficheiro utilizando uma aplicação externa',
'edit-externally-help' => 'Consulte as [http://meta.wikimedia.org/wiki/Help:External_editors instruções de instalação] para mais informação.',

# 'all' in various places, this might be different for inflicted languages
'recentchangesall' => 'todas',
'imagelistall' => 'todas',
'watchlistall1' => 'todas',
'watchlistall2' => 'todas',
'namespacesall' => 'todas',

# E-mail address confirmation
'confirmemail' => 'Confirmar endereço de E-mail',
'confirmemail_text' => "Esta wiki requer que valide o seu endereço de e-mail antes de utilizar as funcionalidades que requerem um endereço de e-mail. Active o botão abaixo para enviar uma confirmação para o seu endereço de e-mail. A mensagem incluíra um endereço que contém um código; carregue o endereço no seu navegador para confirmar que o seu endereço de e-mail encontra-se válido.",
'confirmemail_send' => 'Enviar código de confirmação',
'confirmemail_sent' => 'E-mail de confirmação enviado.',
'confirmemail_sendfailed' => 'Não foi possível enviar o email de confirmação. Por favor verifique o seu endereço de e-mail.',
'confirmemail_invalid' => 'Código de confirmação inválido. O código poderá ter expirado.',
'confirmemail_success' => 'O seu endereço de e-mail foi confirmado. Pode agora se ligar.',
'confirmemail_loggedin' => 'O seu endereço de e-mail foi agora confirmado.',
'confirmemail_error' => 'Alguma coisa correu mal ao guardar a sua confirmação.',

'confirmemail_subject' => '{{SITENAME}} confirmação de endereço de e-mail',
'confirmemail_body' => "Alguém, provavelmente você com o endereço de IP $1, registou uma conta \"$2\" com este endereço de e-mail na {{SITENAME}}.

Para confirmar que esta conta realmente é sua e para activar
as funcionalidades de e-mail na {{SITENAME}}, abra o seguinte endereço no seu navegador:

$3

Caso este *não* seja você, não siga o endereço. Este código de confirmação
irá expirar a $4.",

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Try exact match',
'searchfulltext' => 'Pesquisar no texto completo',
'createarticle' => 'Criar artigo',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
'scarytranscludefailed' => '[Template fetch failed for $1; sorry]',
'scarytranscludetoolong' => '[URL is too long; sorry]',

# Trackbacks
'trackbackbox' => "<div id='mw_trackbacks'>
Trackbacks for this article:<br/>
$1
</div>",
'trackback' => "; $4$5 : [$2 $1]",
'trackbackexcerpt' => "; $4$5 : [$2 $1]: $3",
'trackbackremove' => ' ([$1 Eliminar])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'The trackback was successfully deleted.',

# delete conflict

'deletedwhileediting' => 'Aviso: Esta página foi eliminada após você ter começado a editar!',
'confirmrecreate' => 'O utilizador [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|Discussão]]) eliminou este artigo após você ter começado a editar, pelo seguinte motivo:
: \'\'$2\'\'
Por favor confirme que realmente deseja recriar este artigo.',
'recreate' => 'Recriar',
'tooltip-recreate' => '',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Redireccionando para [[$1]]...',

# action=purge
'confirm_purge' => "Limpar a memória cache desta página?\n\n$1",
'confirm_purge_button' => 'OK',
);

class LanguagePt extends LanguageUtf8 {

	function timeanddate( $ts, $adj = false ) {
		return $this->time( $ts, $adj ) . ', ' . $this->date( $ts, $adj );
	}

	/**
	 * Portuguese numeric format is 123 456,78
	 */
	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ', ' );
	}

	/**
	* Exports $wgNamespaceNamesPt
	* @return array
	*/
	function getNamespaces() {
		global $wgNamespaceNamesPt;
		return $wgNamespaceNamesPt;
	}
	
	/**
	* Exports $wgQuickbarSettingsPt
	* @return array
	*/
	function getQuickbarSettings() {
		global $wgQuickbarSettingsPt;
		return $wgQuickbarSettingsPt;
	}

	/**
	* Exports $wgSkinNamesPt
	* @return array
	*/
	function getSkinNames() {
		global $wgSkinNamesPt;
		return $wgSkinNamesPt;
	}

	/**
	* Exports $wgDateFormatsPt
	* @return array
	*/
	function getDateFormats() {
		global $wgDateFormatsPt;
		return $wgDateFormatsPt;
	}

	function getMessage( $key ) {
		global $wgAllMessagesPt;
		if ( isset( $wgAllMessagesPt[$key] ) ) {
			return $wgAllMessagesPt[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	* Exports $wgMagicWordsPt
	* @return array
	*/
	function getMagicWords()  {
		global $wgMagicWordsPt;
		return $wgMagicWordsPt;
	}
}
?>
