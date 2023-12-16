const showPasswordButton = document.querySelector('.toggle-button-icon-eye');
const passwordInput = document.querySelector('.col-input-password');
const buttonShowPassword = document.getElementById('toggle-button-icon-eye');

showPasswordButton.addEventListener('click', () => {
const type = passwordInput.getAttribute('type');

if (type === 'password') {
  passwordInput.setAttribute('type', 'text');
  buttonShowPassword.style.backgroundColor = 'white';
} else {
  passwordInput.setAttribute('type', 'password');
  buttonShowPassword.style.backgroundColor = 'rgb(203, 205, 207)';
}
});

const showPasswordButton2 = document.querySelector('.toggle-button-icon-eye-confirm');
const passwordInput2 = document.querySelector('.col-input-password-confirm');
const buttonShowPassword2 = document.getElementById('toggle-button-icon-eye-confirm');

showPasswordButton2.addEventListener('click', () => {
const type = passwordInput2.getAttribute('type');

if (type === 'password') {
  passwordInput2.setAttribute('type', 'text');
  buttonShowPassword2.style.backgroundColor = 'white';
} else {
  passwordInput2.setAttribute('type', 'password');
  buttonShowPassword2.style.backgroundColor = 'rgb(203, 205, 207)';
}
});