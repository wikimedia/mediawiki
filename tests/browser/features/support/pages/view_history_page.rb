class ViewHistoryPage
  include PageObject

  a(:view_history_link, href: /action=history/)
  a(:old_version_link, href: /oldid=/)
end
