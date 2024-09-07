function validatePassword() {
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('password-error');
    const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

    if (!passwordPattern.test(password)) {
        errorMessage.textContent = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.";
        return false;
    }

    errorMessage.textContent = "";
    return true;
}

function validateForm(event) {
    if (!validatePassword()) {
        event.preventDefault();
    }
}