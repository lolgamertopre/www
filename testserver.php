<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
// ALL-IN-ONE version: save as jackenpoy.php (images go in the images/ folder)
$self = basename($_SERVER['SCRIPT_NAME']);
$choices = ['rock', 'paper', 'scissors'];
$beats   = ['rock' => 'scissors', 'paper' => 'rock', 'scissors' => 'paper'];
$secure  = !empty($_SERVER['HTTPS']);

/* ---------- Cookie consent ---------- */
function setConsent($value, $secure) {
    setcookie('jkp_consent', $value, [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

if (isset($_POST['consent'])) {
    if ($_POST['consent'] === 'accept') {
        setConsent('accepted', $secure);
    } elseif ($_POST['consent'] === 'reject') {
        setConsent('rejected', $secure);
        // Remove any existing session cookie + data
        if (isset($_COOKIE[session_name()])) {
            session_start();
            $_SESSION = [];
            session_destroy();
            setcookie(session_name(), '', time() - 3600, '/');
        }
    } elseif ($_POST['consent'] === 'change') {
        setcookie('jkp_consent', '', time() - 3600, '/');
    }
    header('Location: ' . $self);
    exit;
}

$consent  = $_COOKIE['jkp_consent'] ?? null;   // 'accepted' | 'rejected' | null
$accepted = ($consent === 'accepted');

/* ---------- Session cookie only if accepted ---------- */
if ($accepted) {
    session_set_cookie_params([
        'lifetime' => 0,              // session cookie: gone when browser closes
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
    if (!isset($_SESSION['score'])) {
        $_SESSION['score'] = ['win' => 0, 'lose' => 0, 'draw' => 0];
    }
}

/* ---------- Reset ---------- */
if ($accepted && isset($_POST['reset'])) {
    unset($_SESSION['score'], $_SESSION['last']);
    header('Location: ' . $self);
    exit;
}

/* ---------- Play ---------- */
$last = null;
if (isset($_POST['choice']) && in_array($_POST['choice'], $choices, true)) {
    $player   = $_POST['choice'];
    $computer = $choices[array_rand($choices)];
    $result   = ($player === $computer) ? 'draw' : ($beats[$player] === $computer ? 'win' : 'lose');
    $last     = compact('player', 'computer', 'result');

    if ($accepted) {
        $_SESSION['score'][$result]++;
        $_SESSION['last'] = $last;
        header('Location: ' . $self);
        exit;
    }
    // Rejected / undecided: show the result once, nothing is stored
}

if ($accepted) {
    $last  = $_SESSION['last'] ?? null;
    $score = $_SESSION['score'];
}

</body>
</html>
<?php
 
 ?>
 