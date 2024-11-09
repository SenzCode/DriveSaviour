document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const resregisterBtn = document.getElementById('resregister');
    const loginBtn = document.getElementById('login');
    const resloginBtn = document.getElementById('reslogin');

    // Prevent form submission for resregister
    if (resregisterBtn) {
        resregisterBtn.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent the form from submitting
            console.log("Resregister button clicked");
            container.classList.add("active");
        });
    }

    // Toggle to show the sign-up form
    if (registerBtn) {
        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });
    }

    // Toggle to show the sign-in form
    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    }

    if (resloginBtn) {
        resloginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    }
});
