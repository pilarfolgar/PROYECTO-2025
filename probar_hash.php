<?php
// Pega aquí la contraseña que quieres probar
$pass_ingresada = 'holacarola';

// Pega aquí el hash de la base de datos (completo, tal cual)
$hash_bd = '$2y$10$5m53TAijgcPMeuhvzua6p.WxnsOccMEQ/dkge4Y2QBqmXM1LoKDtO
';

echo "Contraseña ingresada: $pass_ingresada<br>";
echo "Hash de BD: $hash_bd<br>";

if (password_verify($pass_ingresada, $hash_bd)) {
    echo "<strong>✅ Coinciden: la contraseña es correcta</strong>";
} else {
    echo "<strong>❌ No coinciden: la contraseña es incorrecta</strong>";
}
?>
