<?php

declare(strict_types=1);

session_set_cookie_params([
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();


const VALID_USERNAME = 'player1';
const VALID_PASSWORD = 'dice123';


const COOKIE_USERNAME = 'remember_username'; // set only when "Remember me" is checked
const COOKIE_SCORE_PREFIX = 'total_score_';  
const COOKIE_LIFETIME = 60 * 60 * 24 * 30;   // 30 days

const SESSION_USER = 'username';          // who is logged in
const SESSION_SCORE = 'player-score';     // running total for the current game
const SESSION_ROLLS = 'rolls';            // history of draws this game
const SESSION_PREVIOUS = 'previous_score'; // total read from the cookie at login (null = never played)


function setAppCookie(string $name, string $value): void
{
    setcookie($name, $value, [
        'expires'  => time() + COOKIE_LIFETIME,
        'path'     => '/',
        'httponly' => true, // JavaScript cannot read it
        'samesite' => 'Lax',
    ]);
    $_COOKIE[$name] = $value;
}


function deleteAppCookie(string $name): void
{
    setcookie($name, '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    unset($_COOKIE[$name]);
}

function getCookieString(string $name): ?string
{
    $value = $_COOKIE[$name] ?? null;
    return is_string($value) ? $value : null;
}


function scoreCookieName(string $username): string
{
    return COOKIE_SCORE_PREFIX . preg_replace('/[^A-Za-z0-9_]/', '_', $username);
}


function getSavedScore(string $username): ?int
{
    $value = getCookieString(scoreCookieName($username));

    // The length check also stops a huge number overflowing int.
    if ($value === null || !ctype_digit($value) || strlen($value) > 9) {
        return null;
    }
    return (int) $value;
}

function isLoggedIn(): bool
{
    return isset($_SESSION[SESSION_USER]);
}


function e(string|int $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}