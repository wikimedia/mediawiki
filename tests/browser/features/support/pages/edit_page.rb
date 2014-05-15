class EditPage
  include PageObject

  text_area(:edit_page_content, id: "wpTextbox1")
  button(:save_button, id: "wpSave")

end