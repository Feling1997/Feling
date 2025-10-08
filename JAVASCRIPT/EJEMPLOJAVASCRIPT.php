<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Generador de Contraseñas - Selección Argentina</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap');

  /* Reset y base */
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    background: url('https://images.unsplash.com/photo-1508606572321-901ea4437076?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
    background-size: cover;
    color: #e0f7fa;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    overflow-x: hidden;
  }

  /* Overlay para difuminar fondo */
  body::before {
    content: '';
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 51, 102, 0.7);
    z-index: 0;
  }

  /* Contenedor principal */
  .container {
    position: relative;
    background: rgba(0, 51, 102, 0.9);
    border-radius: 20px;
    padding: 30px 40px 40px 40px;
    max-width: 480px;
    width: 100%;
    box-shadow:
      0 0 15px #00e5ff,
      0 0 30px #00bcd4,
      inset 0 0 40px #00acc1;
    z-index: 10;
  }

  /* Imagen jugadores */
  .players {
    width: 100%;
    height: 180px;
    background: url('https://i.ibb.co/2sB4TZb/argentina-players.png') no-repeat center center;
    background-size: contain;
    margin-bottom: 20px;
    filter: drop-shadow(0 0 15px #00e5ff);
    border-radius: 15px;
  }

  h2 {
    text-align: center;
    font-size: 3rem;
    margin-bottom: 25px;
    text-shadow:
      0 0 10px #00e5ff,
      0 0 30px #00bcd4,
      0 0 40px #00e5ff;
  }

  .option {
    margin-bottom: 18px;
  }

  label {
    font-weight: 700;
    font-size: 1.2rem;
    display: block;
    margin-bottom: 6px;
    color: #b2ebf2;
    text-shadow: 0 0 4px #00acc1;
  }

  input[type="number"] {
    width: 100%;
    padding: 12px;
    font-size: 1.3rem;
    border-radius: 12px;
    border: none;
    outline: none;
    box-shadow: inset 0 0 8px #00bcd4;
    background: #003366;
    color: #e0f7fa;
    font-weight: 700;
    transition: box-shadow 0.3s ease;
  }

  input[type="number"]:focus {
    box-shadow: 0 0 15px #00e5ff, inset 0 0 12px #00bcd4;
  }

  input[type="checkbox"] {
    width: 22px;
    height: 22px;
    accent-color: #00bcd4;
    margin-right: 12px;
    cursor: pointer;
    filter: drop-shadow(0 0 3px #00bcd4);
  }

  #resultado {
    margin-top: 25px;
    font-family: 'Courier New', monospace;
    font-size: 2.5rem;
    font-weight: 700;
    letter-spacing: 5px;
    color: #00e5ff;
    text-align: center;
    background: rgba(0, 77, 128, 0.85);
    padding: 20px;
    border-radius: 25px;
    box-shadow:
      0 0 25px #00bcd4,
      inset 0 0 40px #007ba7;
    user-select: all;
  }

  button {
    width: 100%;
    margin-top: 30px;
    padding: 18px;
    font-size: 1.8rem;
    font-weight: 900;
    color: #003366;
    background: linear-gradient(90deg, #00e5ff, #00bcd4);
    border: none;
    border-radius: 35px;
    cursor: pointer;
    box-shadow:
      0 0 30px #00e5ff,
      0 0 45px #00bcd4;
    transition: all 0.3s ease;
    user-select: none;
  }

  button:hover {
    background: linear-gradient(90deg, #00bcd4, #00e5ff);
    box-shadow:
      0 0 40px #00e5ff,
      0 0 60px #00bcd4;
    transform: scale(1.05) rotate(2deg);
  }

  #copiar {
    background: linear-gradient(90deg, #004d80, #007ba7);
    color: #b2ebf2;
    margin-top: 15px;
    box-shadow: 0 0 15px #00acc1;
  }

  #copiar:hover {
    background: linear-gradient(90deg, #007ba7, #004d80);
    box-shadow: 0 0 25px #00acc1;
    transform: scale(1.1);
  }

  .mensaje {
    margin-top: 20px;
    color: #00e5ff;
    font-weight: 700;
    font-size: 1.3rem;
    text-align: center;
    user-select: none;
    text-shadow: 0 0 8px #00acc1;
  }

  /* Responsive */
  @media (max-width: 520px) {
    h2 {
      font-size: 2.5rem;
    }
    #resultado {
      font-size: 2rem;
    }
    button {
      font-size: 1.5rem;
      padding: 14px;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <div class="players" aria-label="Imagen de jugadores de la selección Argentina"></div>
    <h2>Generador de Contraseñas</h2>

    <div class="option">
      <label for="longitud">Longitud de la contraseña:</label>
      <input type="number" id="longitud" value="12" min="4" max="30" />
    </div>

    <div class="option">
      <input type="checkbox" id="mayusculas" checked />
      <label for="mayusculas">Incluir Mayúsculas (A-Z)</label>
    </div>

    <div class="option">
      <input type="checkbox" id="minusculas" checked />
      <label for="minusculas">Incluir Minúsculas (a-z)</label>
    </div>

    <div class="option">
      <input type="checkbox" id="numeros" checked />
      <label for="numeros">Incluir Números (0-9)</label>
    </div>

    <div class="option">
      <input type="checkbox" id="simbolos" />
      <label for="simbolos">Incluir Símbolos (!@#$%)</label>
    </div>

    <button id="generar">Generar Contraseña</button>

    <div id="resultado" aria-live="polite" aria-atomic="true"></div>

    <button id="copiar" style="display:none;">Copiar al Portapapeles</button>

    <p id="mensaje" class="mensaje" role="alert" aria-live="assertive"></p>
  </div>

<script>
  const resultado = document.getElementById('resultado');
  const longitud = document.getElementById('longitud');
  const mayusculas = document.getElementById('mayusculas');
  const minusculas = document.getElementById('minusculas');
  const numeros = document.getElementById('numeros');
  const simbolos = document.getElementById('simbolos');
  const generarBtn = document.getElementById('generar');
  const copiarBtn = document.getElementById('copiar');
  const mensaje = document.getElementById('mensaje');

  const mayusculasChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  const minusculasChars = 'abcdefghijklmnopqrstuvwxyz';
  const numerosChars = '0123456789';
  const simbolosChars = '!@#$%&*?';

  function generarPassword() {
    let caracteres = '';
    if(mayusculas.checked) caracteres += mayusculasChars;
    if(minusculas.checked) caracteres += minusculasChars;
    if(numeros.checked) caracteres += numerosChars;
    if(simbolos.checked) caracteres += simbolosChars;

    if(caracteres.length === 0) {
      mensaje.textContent = '¡Selecciona al menos un tipo de carácter!';
      resultado.textContent = '';
      copiarBtn.style.display = 'none';
      return;
    }

    let password = '';
    for(let i = 0; i < longitud.value; i++) {
      password += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }

    resultado.textContent = password;
    mensaje.textContent = '';
    copiarBtn.style.display = 'block';
  }

  function copiarPassword() {
    navigator.clipboard.writeText(resultado.textContent).then(() => {
      mensaje.textContent = '¡Contraseña copiada al portapapeles!';
      setTimeout(() => mensaje.textContent = '', 3000);
    });
  }

  generarBtn.addEventListener('click', generarPassword);
  copiarBtn.addEventListener('click', copiarPassword);
</script>

</body>
</html>
