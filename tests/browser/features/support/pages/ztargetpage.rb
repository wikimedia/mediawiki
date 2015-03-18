class ZtargetPage < MainPage
  include PageObject

  page_url '<%=params[:article_name]%>'

  a(:link_target_page_link, text: 'link to the test target page')
end
