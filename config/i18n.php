<?php
/**
 * i18n.php — Language detection, loading, and translation helpers.
 *
 * Priority order:
 *   1. $_SESSION['app_lang']  (explicit user override)
 *   2. $_COOKIE['app_lang']   (persisted override)
 *   3. HTTP_ACCEPT_LANGUAGE   (browser / system locale)
 *   4. 'en'                   (fallback)
 *
 * Supported: 'en', 'es'
 */

function detect_language(): string {
    $supported = ['en', 'es'];

    if (!empty($_SESSION['app_lang']) && in_array($_SESSION['app_lang'], $supported)) {
        return $_SESSION['app_lang'];
    }
    if (!empty($_COOKIE['app_lang']) && in_array($_COOKIE['app_lang'], $supported)) {
        return $_COOKIE['app_lang'];
    }

    $accept  = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
    $primary = strtolower(substr(trim($accept), 0, 2));
    return $primary === 'es' ? 'es' : 'en';
}

function load_language(string $lang): array {
    $file = __DIR__ . "/lang/{$lang}.php";
    if (!file_exists($file)) {
        $file = __DIR__ . '/lang/en.php';
    }
    return require $file;
}

/** Translate a key and HTML-escape the result (safe for HTML output). */
function __(string $key, ...$args): string {
    global $translations;
    $str = $translations[$key] ?? $key;
    if ($args) {
        $str = vsprintf($str, $args);
    }
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/** Translate a key without escaping (safe for PHP arrays, JS injection via json_encode). */
function __raw(string $key, ...$args): string {
    global $translations;
    $str = $translations[$key] ?? $key;
    if ($args) {
        $str = vsprintf($str, $args);
    }
    return $str;
}
