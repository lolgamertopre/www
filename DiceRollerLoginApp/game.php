<?php

declare(strict_types=1);

require __DIR__ . '/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$_SESSION[SESSION_SCORE] ??= 0;
$_SESSION[SESSION_ROLLS] ??= [];


function scoreRoll(int $d1, int $d2): array
{
    $sum = $d1 + $d2;

    if ($d1 === 6 && $d2 === 6) return [50, 'Boxcars! Jackpot'];
    if ($d1 === 1 && $d2 === 1) return [-10, 'Snake Eyes! Penalty'];
    if ($d1 === $d2)            return [$sum * 2, 'Doubles! Points × 2'];
    if ($sum === 7)             return [14, 'Lucky Seven!'];
    return [$sum, 'Sum of the dice'];
}


function renderDie(int $value): string
{

    $pips = [
        1 => [4],
        2 => [0, 8],
        3 => [0, 4, 8],
        4 => [0, 2, 6, 8],
        5 => [0, 2, 4, 6, 8],
        6 => [0, 2, 3, 5, 6, 8],
    ];

    $html = '<div class="die" aria-label="Die showing ' . $value . '">';
    for ($i = 0; $i < 9; $i++) {
        $html .= in_array($i, $pips[$value], true) ? '<span class="pip"></span>' : '<span></span>';
    }
    return $html . '</div>';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'roll') {
    $d1 = random_int(1, 6);
    $d2 = random_int(1, 6);
    [$points, $label] = scoreRoll($d1, $d2);

    $before = $_SESSION[SESSION_SCORE];
    $after = max(0, $before + $points); // floor at zero
    $_SESSION[SESSION_SCORE] = $after;

    $_SESSION[SESSION_ROLLS][] = [
        'd1'     => $d1,
        'd2'     => $d2,
        'points' => $after - $before, // points actually applied
        'label'  => $label,
        'total'  => $after,
    ];

    redirect('game.php');
}


$username = $_SESSION[SESSION_USER];
$score = $_SESSION[SESSION_SCORE];
$rolls = $_SESSION[SESSION_ROLLS];
$previous = $_SESSION[SESSION_PREVIOUS] ?? null;
$returning = $previous !== null;
$lastRoll = $rolls ? $rolls[array_key_last($rolls)] : null;
$recentRolls = array_reverse(array_slice($rolls, -10, null, true), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dice Roller — Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card game-card">
        <header class="game-header">
            <div>
                <?php if ($returning): ?>
                    <h1>Welcome back, <?= e($username) ?>!</h1>
                    <p class="subtitle">Your previous game total was
                        <strong><?= e($previous) ?> pts</strong>. Can you beat it?</p>
                <?php else: ?>
                    <h1>Welcome, <?= e($username) ?>!</h1>
                    <p class="subtitle">This is your first game — good luck!</p>
                <?php endif; ?>
            </div>
        </header>

        <section class="scoreboard">
            <div class="stat stat-main">
                <span class="stat-label">Current score</span>
                <span class="stat-value"><?= e($score) ?></span>
            </div>
            <div class="stat">
                <span class="stat-label">Draws</span>
                <span class="stat-value"><?= count($rolls) ?></span>
            </div>
            <div class="stat">
                <span class="stat-label">Previous total</span>
                <span class="stat-value"><?= $returning ? e($previous) : '—' ?></span>
            </div>
        </section>

        <section class="table">
            <div class="dice-row">
                <?php if ($lastRoll): ?>
                    <?= renderDie($lastRoll['d1']) ?>
                    <?= renderDie($lastRoll['d2']) ?>
                <?php else: ?>
                    <div class="die die-empty">?</div>
                    <div class="die die-empty">?</div>
                <?php endif; ?>
            </div>

            <?php if ($lastRoll): ?>
                <p class="roll-result <?= $lastRoll['points'] < 0 ? 'neg' : ($lastRoll['points'] >= 20 ? 'big' : '') ?>">
                    <?= e($lastRoll['label']) ?>
                    <strong><?= $lastRoll['points'] >= 0 ? '+' : '' ?><?= e($lastRoll['points']) ?></strong>
                </p>
            <?php else: ?>
                <p class="roll-result">Press <em>Roll Dice</em> to make your first draw.</p>
            <?php endif; ?>

            <div class="actions">
                <form method="post" action="game.php">
                    <input type="hidden" name="action" value="roll">
                    <button type="submit" class="btn btn-primary btn-lg">🎲 Roll Dice</button>
                </form>
                <form method="post" action="end_game.php"
                      onsubmit="return confirm('End the game and save <?= e($score) ?> pts?');">
                    <button type="submit" class="btn btn-secondary btn-lg">End Game</button>
                </form>
            </div>
        </section>

        <section class="columns">
            <div class="panel">
                <h2>Scoring rules</h2>
                <ul class="rules">
                    <li><span>⚅ ⚅ Boxcars</span> <strong>50</strong></li>
                    <li><span>⚀ ⚀ Snake Eyes</span> <strong class="neg">−10</strong></li>
                    <li><span>Other doubles</span> <strong>sum × 2</strong></li>
                    <li><span>Lucky Seven</span> <strong>14</strong></li>
                    <li><span>Anything else</span> <strong>sum</strong></li>
                </ul>
                <p class="note">Your score can't drop below zero.</p>
            </div>

            <div class="panel">
                <h2>Recent draws</h2>
                <?php if ($recentRolls): ?>
                    <table class="history">
                        <thead>
                            <tr><th>#</th><th>Dice</th><th>Points</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentRolls as $i => $r): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= e($r['d1']) ?> + <?= e($r['d2']) ?></td>
                                    <td class="<?= $r['points'] < 0 ? 'neg' : '' ?>">
                                        <?= $r['points'] >= 0 ? '+' : '' ?><?= e($r['points']) ?>
                                    </td>
                                    <td><?= e($r['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="note">No draws yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>