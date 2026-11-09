<?php

declare(strict_types=1);

require __DIR__ . '/config.php';


if (isLoggedIn()) {
    redirect('game.php');
}

$error = '';


$username = getCookieString(COOKIE_USERNAME) ?? '';
$rememberMe = getCookieString(COOKIE_USERNAME) !== null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $rememberMe = isset($_POST['remember']);

    $validUser = hash_equals(VALID_USERNAME, $username);
    $validPass = hash_equals(VALID_PASSWORD, $password);

    if ($validUser && $validPass) {
      
        session_regenerate_id(true);
     
        $_SESSION[SESSION_USER] = $username;
        $_SESSION[SESSION_SCORE] = 0;
        $_SESSION[SESSION_ROLLS] = [];
        // null = no score cookie → first-time player
        // int  = total from their last finished game
        $_SESSION[SESSION_PREVIOUS] = getSavedScore($username);

        // cookie remember name of it ---
        if ($rememberMe) {
            setAppCookie(COOKIE_USERNAME, $username);
        } else {
            deleteAppCookie(COOKIE_USERNAME);
        }

        redirect('game.php');
    }

    $error = 'Invalid username or password.';
}

// use this for a message after the game end.
$gameEnded = is_string($_GET['ended'] ?? null) ? $_GET['ended'] : ''; // 'saved', 'nosave'''
$savedScore = $username !== '' ? getSavedScore($username) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dice Roller — Log in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card login-card">
        <div class="brand">
            <div class="brand-die" aria-hidden="true">⚄</div>
            <h1>Dice Roller</h1>
            <p class="subtitle">Sign in to start rolling</p>
        </div>

        <?php if ($gameEnded === 'saved'): ?>
            <div class="alert alert-success">
                Game over — your score has been saved. Thanks for playing!
            </div>
        <?php elseif ($gameEnded === 'nosave'): ?>
            <div class="alert alert-info">
                You logged out without rolling, so your saved score was kept.
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($savedScore !== null && $error === ''): ?>
            <div class="alert alert-info">
                Welcome back, <strong><?= e($username) ?></strong>!
                Your last saved total: <strong><?= e($savedScore) ?> pts</strong>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php" autocomplete="off">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   value="<?= e($username) ?>" required
                   <?= $username === '' ? 'autofocus' : '' ?>>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required
                   <?= $username !== '' ? 'autofocus' : '' ?>>

            <label class="checkbox">
                <input type="checkbox" name="remember" <?= $rememberMe ? 'checked' : '' ?>>
                Remember me
            </label>

            <button type="submit" class="btn btn-primary">Log In</button>
        </form>

        <p class="hint">Demo account: <code>player1</code> / <code>dice123</code></p>
    </main>
</body>
</html>