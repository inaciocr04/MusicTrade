function update(text) {
    let result_element = document.querySelector("#highlighting-content");
    // Update code
    result_element.innerText = text;
    // Syntax Highlight
    Prism.highlightElement(result_element);
  }

  result_element.innerHTML = text.replace(new RegExp("&", "g"), "&").replace(new RegExp("<", "g"), "<"); /* Global RegExp */

