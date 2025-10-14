<?php
require_once("nav_tokens.php");

function link_seguro($href, $texto, $attrs = '') {
    $t = generar_nav_token();
    $sep = (strpos($href, '?') === false) ? '?' : '&';
    $href_secure = $href . $sep . 't=' . urlencode($t);
    return "<a href=\"" . htmlspecialchars($href_secure) . "\" $attrs>" . htmlspecialchars($texto) . "</a>";
}
?>
