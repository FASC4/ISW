const validarLogin = () => {
  const user = document.getElementById('username').value;
  const pass = document.getElementById('password').value;

  if (user === 'admin' && pass === '1234') {
    window.location.href = "dashboard_ad.html";
  } else if (user === 'vendedor' && pass === 'abcd') {
    window.location.href = "dashboard_ve.html";
  } else {
    alert('Usuario o contraseña incorrectos');
  }
  return false;
};
