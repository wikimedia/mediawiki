<?php
/** Chinese (中文)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kuailong
 * @author PhiLiP
 * @author Philip
 * @author Shizhao
 * @author Wong128hk
 */

# Stub message file for converter code "zh"

$fallback = 'zh-hans';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT_TALK     => '$1_talk',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
);

$namespaceAliases = array(
	'媒体'	=> NS_MEDIA,
	'媒體'	=> NS_MEDIA,
	'特殊'  => NS_SPECIAL,
	'对话'  => NS_TALK,
	'對話'  => NS_TALK,
	'讨论'	=> NS_TALK,
	'討論'	=> NS_TALK,
	'用户'  => NS_USER,
	'用戶'  => NS_USER,
	'用户对话' => NS_USER_TALK,
	'用戶對話' => NS_USER_TALK,
	'用户讨论' => NS_USER_TALK,
	'用戶討論' => NS_USER_TALK,
	# This has never worked so it's unlikely to annoy anyone if I disable it -- TS
	# '{{SITENAME}}_对话' => NS_PROJECT_TALK
	# "{{SITENAME}}_對話" => NS_PROJECT_TALK
	'图像' => NS_FILE,
	'圖像' => NS_FILE,
	'档案' => NS_FILE,
	'檔案' => NS_FILE,
	'文件' => NS_FILE,
	'图像对话' => NS_FILE_TALK,
	'圖像對話' => NS_FILE_TALK,
	'图像讨论' => NS_FILE_TALK,
	'圖像討論' => NS_FILE_TALK,
	'档案对话' => NS_FILE_TALK,
	'檔案對話' => NS_FILE_TALK,
	'档案讨论' => NS_FILE_TALK,
	'檔案討論' => NS_FILE_TALK,
	'文件对话' => NS_FILE_TALK,
	'文件對話' => NS_FILE_TALK,
	'文件讨论' => NS_FILE_TALK,
	'文件討論' => NS_FILE_TALK,
	'模板'	=> NS_TEMPLATE,
	'样板'  => NS_TEMPLATE,
	'樣板'  => NS_TEMPLATE,
	'模板对话' => NS_TEMPLATE_TALK,
	'模板對話' => NS_TEMPLATE_TALK,
	'模板讨论' => NS_TEMPLATE_TALK,
	'模板討論' => NS_TEMPLATE_TALK,
	'样板对话' => NS_TEMPLATE_TALK,
	'樣板對話' => NS_TEMPLATE_TALK,
	'样板讨论' => NS_TEMPLATE_TALK,
	'樣板討論' => NS_TEMPLATE_TALK,
	'帮助'	=> NS_HELP,
	'幫助'  => NS_HELP,
	'帮助对话' => NS_HELP_TALK,
	'幫助對話' => NS_HELP_TALK,
	'帮助讨论' => NS_HELP_TALK,
	'幫助討論' => NS_HELP_TALK,
	'分类'	=> NS_CATEGORY,
	'分類'  => NS_CATEGORY,
	'分类对话' => NS_CATEGORY_TALK,
	'分類對話' => NS_CATEGORY_TALK,
	'分类讨论' => NS_CATEGORY_TALK,
	'分類討論' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( '双重重定向页面', '雙重重定向頁面' ),
	'BrokenRedirects'           => array( '损坏的重定向页', '損壞的重定向頁' ),
	'Userlogin'                 => array( '用户登录', '用戶登錄', '用戶登入', '用户登入' ),
	'Userlogout'                => array( '用户登出', '用戶登出' ),
	'CreateAccount'             => array( '创建账户', '創建帳戶' ),
	'Preferences'               => array( '参数设置', '參數設置' ),
	'Watchlist'                 => array( '监视列表', '監視列表' ),
	'Recentchanges'             => array( '最近更改' ),
	'Upload'                    => array( '上传文件', '上傳檔案', '上載檔案', '上载档案' ),
	'Listfiles'                 => array( '文件列表', '檔案列表', '档案列表' ),
	'Newimages'                 => array( '新建文件', '新建檔案', '新建档案' ),
	'Listusers'                 => array( '用户列表', '用戶列表' ),
	'Listgrouprights'           => array( '群组权限', '群組權限' ),
	'Statistics'                => array( '统计信息', '統計信息', '統計资讯', '统计资讯' ),
	'Randompage'                => array( '随机页面', '隨機頁面' ),
	'Lonelypages'               => array( '孤立页面', '孤立頁面' ),
	'Uncategorizedpages'        => array( '未归类页面', '未歸類頁面' ),
	'Uncategorizedcategories'   => array( '未归类分类', '未歸類分類' ),
	'Uncategorizedimages'       => array( '未归类文件', '未歸類文件', '未歸類檔案', '未归类档案' ),
	'Uncategorizedtemplates'    => array( '未归类模板', '未歸類模板' ),
	'Unusedcategories'          => array( '未使用分类', '未使用分類' ),
	'Unusedimages'              => array( '未使用文件', '未使用檔案', '未使用档案' ),
	'Wantedpages'               => array( '待撰页面', '待撰頁面' ),
	'Wantedcategories'          => array( '待撰分类', '待撰分類' ),
	'Wantedfiles'               => array( '需要的文件', '需要的檔案', '需要的档案' ),
	'Wantedtemplates'           => array( '需要的模板' ),
	'Mostlinked'                => array( '最多链接页面', '最多連結頁面' ),
	'Mostlinkedcategories'      => array( '最多链接分类', '最多連結分類' ),
	'Mostlinkedtemplates'       => array( '最多链接模板', '最多連結模板' ),
	'Mostimages'                => array( '最多链接文件', '最多鏈接文件', '最多連結檔案', '最多连结档案' ),
	'Mostcategories'            => array( '最多分类页面', '最多分類頁面' ),
	'Mostrevisions'             => array( '最多修订页面', '最多修訂頁面' ),
	'Fewestrevisions'           => array( '最少修订页面', '最少修訂頁面' ),
	'Shortpages'                => array( '短页面', '短頁面' ),
	'Longpages'                 => array( '长页面', '長頁面' ),
	'Newpages'                  => array( '最新页面', '最新頁面' ),
	'Ancientpages'              => array( '最早页面', '最早頁面' ),
	'Deadendpages'              => array( '断链页面', '斷鏈頁面', '斷連頁面', '断连页面' ),
	'Protectedpages'            => array( '已保护页面', '已保護頁面' ),
	'Protectedtitles'           => array( '已保护标题', '已保護標題' ),
	'Allpages'                  => array( '所有页面', '所有頁面' ),
	'Prefixindex'               => array( '前缀索引', '前綴索引' ),
	'Ipblocklist'               => array( '封禁列表' ),
	'Specialpages'              => array( '特殊页面', '特殊頁面' ),
	'Contributions'             => array( '用户贡献', '用戶貢獻' ),
	'Emailuser'                 => array( '电邮用户', '電郵用戶' ),
	'Confirmemail'              => array( '确认电子邮件', '確認電子郵件' ),
	'Whatlinkshere'             => array( '链入页面', '鏈入頁面', '連入頁面', '连入页面' ),
	'Recentchangeslinked'       => array( '链出更改', '鏈出更改', '連出更改', '连出更改' ),
	'Movepage'                  => array( '移动页面', '移動頁面' ),
	'Booksources'               => array( '网络书源', '網絡書源', '網路書源', '网路书源' ),
	'Categories'                => array( '页面分类', '頁面分類' ),
	'Export'                    => array( '导出页面', '導出頁面' ),
	'Version'                   => array( '版本信息', '版本資訊', '版本资讯' ),
	'Allmessages'               => array( '所有消息', '所有訊息', '所有讯息' ),
	'Log'                       => array( '日志', '日誌' ),
	'Undelete'                  => array( '恢复被删页面', '恢復被刪頁面' ),
	'Import'                    => array( '导入页面', '導入頁面' ),
	'Userrights'                => array( '用户权限', '用戶權限' ),
	'MIMEsearch'                => array( 'MIME搜索', 'MIME搜尋', 'MIME搜寻' ),
	'Unusedtemplates'           => array( '未使用模板' ),
	'Mypage'                    => array( '我的用户页', '我的用戶頁' ),
	'Mytalk'                    => array( '我的讨论页', '我的討論頁' ),
	'Mycontributions'           => array( '我的贡献', '我的貢獻' ),
	'Listadmins'                => array( '管理员列表', '管理員列表' ),
	'Listbots'                  => array( '机器人列表', '機器人列表' ),
	'Popularpages'              => array( '热点页面', '熱點頁面' ),
	'Search'                    => array( '搜索', '搜尋', '搜寻' ),
	'Resetpass'                 => array( '修改密码', '修改密碼' ),
	'MergeHistory'              => array( '合并历史', '合併歷史' ),
	'Filepath'                  => array( '文件路径', '文件路徑', '檔案路徑', '档案路径' ),
	'Invalidateemail'           => array( '不可识别的电邮地址', '不可識別的電郵地址' ),
	'Blankpage'                 => array( '空白页面', '空白頁面' ),
	'DeletedContributions'      => array( '已删除的用户贡献', '已刪除的用戶貢獻' ),
);

$messages = array(
# User preference toggles
'tog-norollbackdiff' => '進行回退後略過差異比較',

'newpage' => '最新页面',

# Edit pages
'editing' => '正在编辑 $1',

# Miscellaneous special pages
'newpages' => '最新页面',

# Move page
'move-redirect-suppressed' => '已禁止重新定向',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '简体',
'variantname-zh-hant' => '繁體',
'variantname-zh-cn'   => '大陆简体',
'variantname-zh-tw'   => '台灣正體',
'variantname-zh-hk'   => '香港繁體',
'variantname-zh-mo'   => '澳門繁體',
'variantname-zh-sg'   => '新加坡简体',
'variantname-zh-my'   => '大马简体',
'variantname-zh'      => '不转换',

);
