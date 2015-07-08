class ViewHistoryPage
  include PageObject

  a(:view_history_link, css: '#ca-history a')
  a(:old_version_link, css: '#pagehistory a.mw-changeslist-date')
end
