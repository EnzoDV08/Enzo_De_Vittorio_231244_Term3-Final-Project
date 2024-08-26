

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    const passwordStrength = document.querySelector(".password-strength");
    const passwordMatch = document.querySelector(".password-match");

    passwordInput.addEventListener("input", function () {
        checkPasswordStrength(passwordInput.value);
    });

    confirmPasswordInput.addEventListener("input", function () {
        checkPasswordMatch(passwordInput.value, confirmPasswordInput.value);
    });

    function checkPasswordStrength(password) {
        let strength = "weak";
        let strengthText = "Weak";
        passwordStrength.style.display = "block";
        passwordStrength.innerHTML = "<span></span>";

        if (password.length >= 8) {
            strength = "medium";
            strengthText = "Average";
        }
        if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/)) {
            strength = "strong";
            strengthText = "Strong";
        }

        passwordStrength.innerHTML = `<span class="${strength}"></span><div class="${strength}-text">${strengthText}</div>`;
    }

    function checkPasswordMatch(password, confirmPassword) {
        if (password !== confirmPassword) {
            passwordMatch.style.display = "block";
        } else {
            passwordMatch.style.display = "none";
        }
    }
});
