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

Then(/^I should see a link for Recent changes$/) do
  on(MainPage).recent_changes_link_element.should be_visible
end

Then(/^I should see a link for Random page$/) do
  on(MainPage).random_page_link_element.should be_visible
end

Then(/^I should see a link for Help$/) do
  on(MainPage).help_link_element.should be_visible
end

Then(/^I should see a link for What links here$/) do
  on(MainPage).what_links_here_link_element.should be_visible
end

Then(/^I should see a link for Related changes$/) do
  on(MainPage).related_changes_link_element.should be_visible
end

Then(/^I should see a link for Special pages$/) do
  on(MainPage).special_pages_link_element.should be_visible
end

Then(/^I should see a link for Printable version$/) do
  on(MainPage).printable_version_link_element.should be_visible
end

Then(/^I should see a link for Permanent link$/) do
  on(MainPage).permanent_link_link_element.should be_visible
end

Then(/^I should see a link for Page information$/) do
  on(MainPage).page_information_link_element.should be_visible
end
