<?php


declare(strict_types=1);

require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isLoggedIn()) {
    redirect('index.php');
}

$username = $_SESSION[SESSION_USER];
$total = (int) ($_SESSION[SESSION_SCORE] ?? 0);
$draws = count($_SESSION[SESSION_ROLLS] ?? []);


// Logging in and ending immediately keeps the previous score.
if ($draws > 0) {
    setAppCookie(scoreCookieName($username), (string) $total);
}


$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 3600,
        'path'     => $p['path'],
        'domain'   => $p['domain'],
        'secure'   => $p['secure'],
        'httponly' => $p['httponly'],
        'samesite' => $p['samesite'] ?: 'Lax',
    ]);
}

session_destroy();

// Tell the login screen its save or not.
redirect('index.php?ended=' . ($draws > 0 ? 'saved' : 'nosave'));