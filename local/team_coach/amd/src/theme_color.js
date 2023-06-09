(function() {
    // Get the color input field
    var colorField = document.getElementById('id_theme_color');
  
    // Create a color picker element
    var colorPicker = document.createElement('input');
    colorPicker.type = 'color';
  
    // Add an event listener to update the value of the input field when a color is selected
    colorPicker.addEventListener('input', function() {
      colorField.value = colorPicker.value;
    });
  
    // Insert the color picker element before the color input field
    colorField.parentNode.insertBefore(colorPicker, colorField);
  })();
  