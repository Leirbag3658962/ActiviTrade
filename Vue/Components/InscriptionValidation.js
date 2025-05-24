const form = document.getElementById('form');
const lastname = document.getElementById('lastname');
const firstname = document.getElementById('firstname');
const email = document.getElementById('email');
const birthdate = document.getElementById('birthdate');
const ville = document.getElementById('ville');
const telephone = document.getElementById('telephone');
const password = document.getElementById('password');
const password2 = document.getElementById('password2');
const cgu = document.getElementById('cgu');

form.addEventListener('submit', e => {
    e.preventDefault();

    const isValid = validateInputs();

    if (isValid) {
        form.submit();
    }
});

const setError = (element, message) => {
    let inputControl = element.parentElement;
    while (inputControl && !inputControl.classList.contains('input-control')) {
        inputControl = inputControl.parentElement;
    }
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
};

const setSuccess = element => {
    let inputControl = element.parentElement;
    while (inputControl && !inputControl.classList.contains('input-control')) {
        inputControl = inputControl.parentElement;
    }
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const isValidEmail = email => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()\[\]\\.,;:\s@"]+\.)+[^<>()\[\]\\.,;:\s@"]{2,})$/i;
    return re.test(String(email).toLowerCase());
};

const isPhoneNumber = number => {
    const re = /^(0|\+33)[1-9](\d{2}){4}$/;
    return re.test(String(number).toLowerCase());
};

const validateInputs = () => {
    let valid = true;
    const lastnameValue = lastname.value.trim();
    const firstnameValue = firstname.value.trim();
    const emailValue = email.value.trim();
    const birthdateValue = birthdate.value.trim();
    const villeValue = ville.value.trim();
    const telephoneValue = telephone.value.trim();
    const passwordValue = password.value.trim();
    const password2Value = password2.value.trim();

    if (lastnameValue === '') {
        setError(lastname, 'Le nom est requis');
        valid = false;
    } else {
        setSuccess(lastname);
    }

    if (firstnameValue === '') {
        setError(firstname, 'Le prénom est requis');
        valid = false;
    } else {
        setSuccess(firstname);
    }

    if (emailValue === '') {
        setError(email, 'L\'email est requis');
        valid = false;
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Email non valide');
        valid = false;
    } else {
        setSuccess(email);
    }

    if (birthdateValue === '') {
        setError(birthdate, 'La date de naissance est requise');
        valid = false;
    } else {
        setSuccess(birthdate);
    }

    if (villeValue === '') {
        setError(ville, 'La ville est requise');
        valid = false;
    } else {
        setSuccess(ville);
    }

    if (telephoneValue === '') {
        setError(telephone, 'Le numéro de téléphone est requis');
        valid = false;
    } else if (!isPhoneNumber(telephoneValue)) {
        setError(telephone, 'Numéro de téléphone non valide');
        valid = false;
    } else {
        setSuccess(telephone);
    }

    if (passwordValue === '') {
        setError(password, 'Le mot de passe est requis');
        valid = false;
    } else if (passwordValue.length < 8) {
        setError(password, 'Le mot de passe doit contenir au moins 8 caractères');
        valid = false;
    } else if (!/[A-Z]/.test(passwordValue)) {
        setError(password, 'Le mot de passe doit contenir au moins une lettre majuscule');
        valid = false;
    } else if (!/[a-z]/.test(passwordValue)) {
        setError(password, 'Le mot de passe doit contenir au moins une lettre minuscule');
        valid = false;
    } else if (!/[0-9]/.test(passwordValue)) {
        setError(password, 'Le mot de passe doit contenir au moins un chiffre');
        valid = false;
    } else if (!/[!@#$%^&*]/.test(passwordValue)) {
        setError(password, 'Le mot de passe doit contenir au moins un caractère spécial (!@#$%^&*)');
        valid = false;
    } else {
        setSuccess(password);
    }

    if (password2Value === '') {
        setError(password2, 'La confirmation du mot de passe est requise');
        valid = false;
    } else if (password2Value !== passwordValue) {
        setError(password2, 'Les mots de passe ne correspondent pas');
        valid = false;
    } else {
        setSuccess(password2);
    }

    if (!cgu.checked) {
        setError(cgu, 'Vous devez accepter les CGU');
        valid = false;
    } else {
        setSuccess(cgu);
    }

    return valid;
};

const validatorText = document.getElementById('password');
showHide = document.getElementById('showHide');
const passwordCheck = document.querySelectorAll('.password-check img');
validatorText.focus();

showHide.addEventListener("click", function(e) {
    validatorText.type = validatorText.type === "text" ? "password" : "text";
    e.target.src = e.target.src.endsWith("Hide.svg") ? "../img/Show.svg" : "../img/Hide.svg";
});

const confirmPasswordInput = document.getElementById("password2");
const toggleIconConfirm = document.getElementById("showHideConfirm");

toggleIconConfirm.addEventListener("click", function () {
    const type = confirmPasswordInput.getAttribute("type") === "password" ? "text" : "password";
    confirmPasswordInput.setAttribute("type", type);
    toggleIconConfirm.src = type === "password" ? "../img/Hide.svg" : "../img/Show.svg";
});


const combinations = [
    {regex: /.{8}/, key: 0},
    {regex: /[A-Z]/, key: 1},
    {regex: /[a-z]/, key: 2},
    {regex: /[0-9]/, key: 3},
    {regex: /[^A-Za-z0-9]/, key: 4}
];

validatorText.addEventListener("keyup", function(e) {
    combinations.forEach((item)=>{
        const isValid = item.regex.test(e.target.value);
        let checkItem = passwordCheck[item.key];

        if (isValid){
            checkItem.src = "../img/check.svg";
            checkItem.parentElement.style.color = "green";

        }else {
            checkItem.src = "../img/close.svg";
            checkItem.parentElement.style.color = "red";

        }
    })
});

const checkSamePassword = document.querySelector('.check-same-password');
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

password.addEventListener("input", updateSamePasswordCheck);
password2.addEventListener("input", updateSamePasswordCheck);