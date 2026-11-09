
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jack en Poy</title>
    <style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Segoe UI", Arial, sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    padding: 20px;
}

.game {
    width: 100%;
    max-width: 640px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

h1 { font-size: 2.4rem; margin-bottom: 20px; letter-spacing: 2px; }

/* Scoreboard */
.scoreboard { display: flex; gap: 12px; margin-bottom: 25px; }
.scoreboard div {
    flex: 1;
    padding: 10px;
    border-radius: 12px;
    font-size: 0.9rem;
    background: rgba(0, 0, 0, 0.25);
}
.scoreboard span { display: block; font-size: 1.8rem; font-weight: bold; }
.scoreboard .win span  { color: #4ade80; }
.scoreboard .lose span { color: #f87171; }
.scoreboard .draw span { color: #fde047; }

/* Battle area */
.battle {
    display: flex;
    align-items: center;
    justify-content: space-around;
    margin-bottom: 15px;
    animation: pop 0.4s ease;
}
.side img {
    width: 120px;
    height: 120px;
    object-fit: contain;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    padding: 12px;
}
.side p { margin-bottom: 8px; font-weight: 600; }
.vs { font-size: 1.6rem; font-weight: bold; opacity: 0.8; }

.result { margin-bottom: 25px; font-size: 1.8rem; }
.result.win  { color: #4ade80; }
.result.lose { color: #f87171; }
.result.draw { color: #fde047; }
.hint { margin-bottom: 25px; font-size: 1.2rem; opacity: 0.9; }

/* Choice buttons */
.choices { display: flex; gap: 14px; justify-content: center; margin-bottom: 20px; }
.choices button {
    flex: 1;
    max-width: 170px;
    background: #fff;
    color: #333;
    border: none;
    border-radius: 16px;
    padding: 14px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: transform 0.2s, box-shadow 0.2s;
}
.choices button img { width: 80px; height: 80px; object-fit: contain; display: block; margin: 0 auto 8px; }
.choices button:hover { transform: translateY(-6px) scale(1.05); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); }
.choices button:active { transform: scale(0.97); }

.reset {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
    border-radius: 30px;
    padding: 8px 22px;
    cursor: pointer;
    font-size: 0.95rem;
    transition: background 0.2s;
}
.reset:hover { background: rgba(255, 255, 255, 0.2); }

@keyframes pop {
    from { transform: scale(0.8); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}

@media (max-width: 480px) {
    .choices { flex-direction: column; align-items: center; }
    .choices button { max-width: 100%; width: 100%; }
}

/* Cookie banner */
.cookie-banner {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 16px 20px;
    background: #1f1f2e;
    color: #fff;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.4);
    z-index: 100;
    animation: slideUp 0.4s ease;
}
.cookie-banner p { max-width: 520px; font-size: 0.95rem; }
.cookie-banner form { display: flex; gap: 10px; }
.btn {
    border: none;
    border-radius: 8px;
    padding: 10px 22px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.95rem;
    transition: transform 0.15s, opacity 0.15s;
}
.btn:hover { transform: translateY(-2px); opacity: 0.9; }
.btn.accept { background: #4ade80; color: #12351f; }
.btn.reject { background: #f87171; color: #3b0d0d; }

.note { margin-bottom: 20px; font-size: 0.9rem; opacity: 0.85; }

.footer-actions { display: flex; gap: 12px; justify-content: center; align-items: center; flex-wrap: wrap; }
.link-btn {
    background: none;
    border: none;
    color: #fff;
    text-decoration: underline;
    cursor: pointer;
    font-size: 0.85rem;
    opacity: 0.8;
}
.link-btn:hover { opacity: 1; }

@keyframes slideUp {
    from { transform: translateY(100%); }
    to   { transform: translateY(0); }
}

/* Each choice is its own form (rock.php / paper.php / scissors.php) */
.choices form { flex: 1; max-width: 170px; }
.choices form button { width: 100%; max-width: none; }
@media (max-width: 480px) {
    .choices form { max-width: 100%; width: 100%; }
}

    </style>
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
    <p>🍪 This game uses a session cookie to remember your score. It is deleted when you close your browser.</p>
    <form method="post">
        <button type="submit" name="consent" value="accept" class="btn accept">Accept</button>
        <button type="submit" name="consent" value="reject" class="btn reject">Reject</button>
    </form>
</div>
<?php endif; ?>
</body>
</html>
