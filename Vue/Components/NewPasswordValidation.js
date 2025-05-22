document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resetPasswordForm');
    const password = document.getElementById('password');
    const password2 = document.getElementById('password2');

    const passwordCheck = document.querySelectorAll('.password-check img');
    const combinations = [
        {regex: /.{8}/, key: 0},
        {regex: /[A-Z]/, key: 1},
        {regex: /[a-z]/, key: 2},
        {regex: /[0-9]/, key: 3},
        {regex: /[^A-Za-z0-9]/, key: 4}
    ];

    password.addEventListener("keyup", function(e) {
        combinations.forEach((item)=>{
            const isValid = item.regex.test(e.target.value);
            let checkItem = passwordCheck[item.key];

            if (isValid){
                checkItem.src = "../img/check.svg";
                checkItem.parentElement.style.color = "green";
            } else {
                checkItem.src = "../img/close.svg";
                checkItem.parentElement.style.color = "red";
            }
        });
        updateSamePasswordCheck();
    });
    password2.addEventListener("keyup", updateSamePasswordCheck);

    let checkSamePassword = document.querySelector('.check-same-password');
    if (!checkSamePassword) {
        const div = document.createElement('div');
        div.className = "check-same-password";
        div.innerHTML = `<img src="../img/close.svg" />&nbsp;Les mots de passe sont identiques`;
        password2.closest('.input-control').appendChild(div);
        checkSamePassword = div;
    }
    const checkSamePasswordImg = checkSamePassword.querySelector('img');

    function updateSamePasswordCheck() {
        if (password.value && password2.value && password.value === password2.value) {
            checkSamePasswordImg.src = "../img/check.svg";
            checkSamePassword.style.color = "green";
        } else {
            checkSamePasswordImg.src = "../img/close.svg";
            checkSamePassword.style.color = "red";
        }
    }

    const toggleIcons = document.querySelectorAll('.toggle-password');
    toggleIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const targetId = icon.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            icon.src = isPassword ? '../img/Show.svg' : '../img/Hide.svg';
        });
    });

    form.addEventListener('submit', e => {
        validateInputs();
    });

    const setError = (element, message) => {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector('.error');

        errorDisplay.innerText = message;
        inputControl.classList.add('error');
        inputControl.classList.remove('success');
    };

    const setSuccess = element => {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector('.error');

        errorDisplay.innerText = '';
        inputControl.classList.add('success');
        inputControl.classList.remove('error');
    };

    const validateInputs = () => {    
        const passwordValue = password.value.trim();
        const password2Value = password2.value.trim();

        if (passwordValue === '') {
            setError(password, 'Le mot de passe est requis');
        } else if (passwordValue.length < 8) {
            setError(password, 'Le mot de passe doit contenir au moins 8 caractères');
        } else if (!/[A-Z]/.test(passwordValue)) {
            setError(password, 'Le mot de passe doit contenir au moins une lettre majuscule');
        } else if (!/[a-z]/.test(passwordValue)) {
            setError(password, 'Le mot de passe doit contenir au moins une lettre minuscule');
        } else if (!/[0-9]/.test(passwordValue)) {
            setError(password, 'Le mot de passe doit contenir au moins un chiffre');
        } else if (!/[!@#$%^&*]/.test(passwordValue)) {
            setError(password, 'Le mot de passe doit contenir au moins un caractère spécial (!@#$%^&*)');
        } else {
            setSuccess(password);
        }

        if (password2Value === '') {
            setError(password2, 'La confirmation du mot de passe est requise');
        } else if (password2Value !== passwordValue) {
            setError(password2, 'Les mots de passe ne correspondent pas');
        } else {
            setSuccess(password2);
        }
        updateSamePasswordCheck();
    };
});