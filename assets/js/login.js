

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector(".login-form");

    loginForm.addEventListener("submit", function (e) {
        const email = document.getElementById("email").value;
        const phone = document.getElementById("phone").value;
        const password = document.getElementById("password").value;

        
        if (!/^\d{10}$/.test(phone)) {
            e.preventDefault();
            displayError("Invalid phone number. Please enter a 10-digit number.");
            return;
        }

        
        if (password.length < 6) {
            e.preventDefault();
            displayError("Password must be at least 6 characters long.");
            return;
        }

        
        loginForm.classList.add("submitting");
        setTimeout(() => {
            loginForm.classList.remove("submitting");
        }, 1000);
    });

    function displayError(message) {
        const errorMessage = document.createElement("div");
        errorMessage.className = "error-message";
        errorMessage.textContent = message;
        loginForm.prepend(errorMessage);

        setTimeout(() => {
            errorMessage.remove();
        }, 3000);
    }
});
