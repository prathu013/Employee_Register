// Form Validation for Admin and Employee Forms
function validateForm(formName) {
    var form = document.forms[formName];
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].value == "") {
            alert("Please fill out all fields.");
            return false;
        }
    }
    return true;
}
