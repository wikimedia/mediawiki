class EditPage
  include PageObject

  text_area(:edit_page_content, id: 'wpTextbox1')
  button(:preview_button, css: '#wpPreview > button')
  button(:show_changes_button, css: '#wpDiff > button')
  button(:save_button, css: '#wpSave > button')
end
