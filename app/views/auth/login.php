<?php
session_start();
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;
?>
<form action="/login" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="senha" placeholder="Senha">
    <button type="submit">Entrar</button>
</form>
