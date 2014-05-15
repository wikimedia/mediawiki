Given(/^I go to the "(.+)" page with content "(.+)"$/) do |page_title, page_content|
  @wikitext = page_content
  on(APIPage).create page_title, page_content
  step "I am on the #{page_title} page"
end

Given(/^I am on the (.+) page$/) do |article|
  article = article.gsub(/ /, '_')
  visit(ZtargetPage, :using_params => {:article_name => article})
end

When(/^I click the Main Page link$/) do
  on(ZtargetPage).main_page_link
end

Then(/^I should be on the Main Page$/) do
  on(MainPage).main_page_title_element.should be_visible
end
