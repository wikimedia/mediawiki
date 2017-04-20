class EditPage
  include PageObject

  text_area(:edit_page_content, id: 'wpTextbox1')
  button(:preview_button, css: '#wpPreview > input')
  button(:show_changes_button, css: '#wpDiff > input')
  button(:save_button, css: '#wpSave > input')
end
