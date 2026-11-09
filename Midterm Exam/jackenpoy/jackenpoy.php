<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROCK PAPER SCISSOR</title>
</head>
<body>
<div class="game">
    <h1>Jack en Poy</h1>

    <?php if ($accepted): ?>
    <div class="scoreboard">
        <div class="win">Wins<span><?= $score['win'] ?></span></div>
        <div class="draw">Draws<span><?= $score['draw'] ?></span></div>
        <div class="lose">Losses<span><?= $score['lose'] ?></span></div>
    </div>
    <?php else: ?>
        <p class="note">Score tracking is off. Accept cookies to save your score.</p>
    <?php endif; ?>

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


    <?php if ($last): ?>
        <div class="battle">
            <div class="side">
                <p>You</p>
                <img src="images/<?= $last['player'] ?>paper-r.png" alt="<?= $last['player'] ?>">
            </div>
            <div class="vs">VS</div>
            <div class="side">
                <p>Computer</p>
                <img src="images/<?= $last['computer'] ?>paper-r.png" alt="<?= $last['computer'] ?>">
            </div>
        </div>
        <h2 class="result <?= $last['result'] ?>"><?= $messages[$last['result']] ?></h2>
    <?php else: ?>
        <p class="hint">Pick your move!</p>
    <?php endif; ?>

    <form method="post" class="choices">
        <?php foreach ($choices as $c): ?>
            <button type="submit" name="choice" value="<?= $c ?>">
                <img src="images/<?= $c ?>paper-r.png" alt="<?= $c ?>">
                <span><?= ucfirst($c) ?></span>
            </button>
        <?php endforeach; ?>
    </form>

    <div class="footer-actions">
        <?php if ($accepted): ?>
            <form method="post"><button type="submit" name="reset" class="reset">Reset Score</button></form>
        <?php endif; ?>
        <?php if ($consent): ?>
            <form method="post"><button type="submit" name="consent" value="change" class="link-btn">Change cookie choice</button></form>
        <?php endif; ?>
    </div>
</div>

<?php if ($consent === null): ?>
<div class="cookie-banner">
    <p> This game uses a session cookie to remember your score. It is deleted when you close your browser.</p>
    <form method="post">
        <button type="submit" name="consent" value="accept" class="btn accept">Accept</button>
        <button type="submit" name="consent" value="reject" class="btn reject">Reject</button>
    </form>
</div>
<?php endif; ?>
</body>
</html>