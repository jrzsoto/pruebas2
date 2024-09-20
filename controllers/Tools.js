const isRequired = value => value === '' ? false : true;

const showError = (input) => {
  // get the form-field element
  const formField = input;
  // add the error class
  formField.removeClass('is-valid');
  formField.addClass('is-invalid');
};

const showSuccess = (input) => {
  // get the form-field element
  const formField = input;

  // remove the error class
  formField.removeClass('is-invalid');
  formField.addClass('is-valid');
}

const isEmailValid = (email) => {
  const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  return re.test(email);
};


function randomStr(length) {
  let result = '';
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  const charactersLength = characters.length;
  let counter = 0;
  while (counter < length) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
    counter += 1;
  }
  return result;
}

/**
 * 
 * Función para asegurar que un input acepte números
 * @param mixed evt
 * 
 * @return bool
 */
function isNumberKey(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
    key = event.clipboardData.getData('text/plain');
  } else {
    // Handle key press
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }

}