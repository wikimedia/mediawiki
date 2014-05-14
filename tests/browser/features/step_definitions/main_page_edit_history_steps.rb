Given(/^I open the main wiki URL$/) do
  visit(MainPage)
end

When(/^I see the Main Page$/) do
  on(MainPage).page_content.should match Regexp.new("Main Page")
end

Then(/^I should see a link for View History$/) do
  on(MainPage).view_history_link_element.should be_visible
end

Then(/^I should see a link for Edit$/) do
  on(MainPage).edit_link_element.should be_visible
end
