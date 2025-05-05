const form = document.getElementById('form');
const lastname = document.getElementById('lastname');
const firstname = document.getElementById('firstname');
const email = document.getElementById('email');
const birthdate = document.getElementById('birthdate');
const ville = document.getElementById('ville');
const telephone = document.getElementById('telephone');
const password = document.getElementById('password');
const password2 = document.getElementById('password2');

form.addEventListener('submit', e => {
    e.preventDefault();

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

const isValidEmail = email => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()\[\]\\.,;:\s@"]+\.)+[^<>()\[\]\\.,;:\s@"]{2,})$/i;
    return re.test(String(email).toLowerCase());
};

const isPhoneNumber = number => {
    const re = /^(0|\+33)[1-9](\d{2}){4}$/;
    return re.test(String(number).toLowerCase());
};

const validateInputs = () => {
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
    } else {
        setSuccess(lastname);
    }

    if (firstnameValue === '') {
        setError(firstname, 'Le pr&eacute;nom est requis');
    } else {
        setSuccess(firstname);
    }

    if (emailValue === '') {
        setError(email, 'L\'email est requis');
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Email non valide');
    } else {
        setSuccess(email);
    }

    if (birthdateValue === '') {
        setError(birthdate, 'La date de naissance est requise');
    } else {
        setSuccess(birthdate);
    }

    if (villeValue === '') {
        setError(ville, 'La ville est requise');
    } else {
        setSuccess(ville);
    }

    if (telephoneValue === '') {
        setError(telephone, 'Le numéro de téléphone est requis');
    } else if (!isPhoneNumber(telephoneValue)) {
        setError(telephone, 'Numéro de téléphone non valide');
    } else {
        setSuccess(telephone);
    }

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
};