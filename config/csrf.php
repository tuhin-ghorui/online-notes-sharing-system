<?php
/**
 * CSRF Protection Helper
 * Include this file wherever CSRF tokens are needed.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate and store a CSRF token in the session.
 * Returns the existing token if already set.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden HTML input field containing the CSRF token.
 */
function csrf_field(): void {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * Validate the submitted CSRF token against the one in the session.
 * Kills the request with a 403 if invalid.
 */
function csrf_verify(): void {
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('❌ Invalid CSRF token. Request blocked.');
    }
}

/**
 * Returns CSRF hidden input as a string (for use inside PHP echo blocks).
 */
function csrf_field_string(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}
