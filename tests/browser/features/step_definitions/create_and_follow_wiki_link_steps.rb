Given(/^I go to the "(.+)" page with content "(.+)"$/) do |page_title, page_content|
  @wikitext = page_content
  api.create_page page_title, page_content
  step "I am on the #{page_title} page"
end

Given(/^I am on the (.+) page$/) do |article|
  article = article.gsub(/ /, '_')
  visit(ZtargetPage, using_params: { article_name: article })
end

Given(/^I create page "(.*?)" with content "(.*?)"$/) do |page_title, page_content|
  api.create_page page_title, page_content
end

When(/^I click the Link Target link$/) do
  on(ZtargetPage).link_target_page_link
end

Then(/^I should be on the Link Target Test Page$/) do
  expect(@browser.url).to match /Link_Target_Test_Page/
end

Then(/^the page content should contain "(.*?)"$/) do |content|
  expect(on(ZtargetPage).page_content).to match content
end
