<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Security Configuration
 * 
 * Protection contre les attaques CSRF, XSS, et gestion des authentifications
 */
class Security extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * CSRF Protection Method
     * --------------------------------------------------------------------------
     *
     * Protection Method for Cross Site Request Forgery protection.
     * Session-based CSRF protection is more secure than cookie-based.
     *
     * @var string 'cookie' or 'session'
     */
    public string $csrfProtection = 'session';

    /**
     * --------------------------------------------------------------------------
     * CSRF Token Randomization
     * --------------------------------------------------------------------------
     *
     * Randomize the CSRF Token for added security.
     * ENABLED for higher security - regenerates token on each request when needed.
     */
    public bool $tokenRandomize = true;

    /**
     * --------------------------------------------------------------------------
     * CSRF Token Name
     * --------------------------------------------------------------------------
     *
     * Token name for Cross Site Request Forgery protection.
     * Used in form inputs and headers.
     */
    public string $tokenName = 'csrf_token';

    /**
     * --------------------------------------------------------------------------
     * CSRF Header Name
     * --------------------------------------------------------------------------
     *
     * Header name for Cross Site Request Forgery protection.
     * Used for AJAX requests: X-CSRF-Token
     */
    public string $headerName = 'X-CSRF-Token';

    /**
     * --------------------------------------------------------------------------
     * CSRF Cookie Name
     * --------------------------------------------------------------------------
     *
     * Cookie name for Cross Site Request Forgery protection.
     * Note: Using session-based CSRF, this is less critical but kept for reference.
     */
    public string $cookieName = 'csrf_token_cookie';

    /**
     * --------------------------------------------------------------------------
     * CSRF Expires
     * --------------------------------------------------------------------------
     *
     * Expiration time for Cross Site Request Forgery protection cookie.
     *
     * Defaults to two hours (in seconds).
     */
    public int $expires = 7200;

    /**
     * --------------------------------------------------------------------------
     * CSRF Regenerate
     * --------------------------------------------------------------------------
     *
     * Regenerate CSRF Token on every submission.
     */
    public bool $regenerate = true;

    /**
     * --------------------------------------------------------------------------
     * CSRF Redirect
     * --------------------------------------------------------------------------
     *
     * Redirect to previous page with error on failure.
     *
     * @see https://codeigniter4.github.io/userguide/libraries/security.html#redirection-on-failure
     */
    public bool $redirect = (ENVIRONMENT === 'production');

    // =========================================================================
    // XSS PROTECTION
    // =========================================================================
    /**
     * XSS Protection Configuration
     *
     * Toutes les données affichées doivent être échappées avec esc()
     * pour prévenir les failles XSS.
     * 
     * Utilisation:
     *  - HTML: esc($data)
     *  - HTML Attributes: esc($data, 'attr')
     *  - URLs: esc($data, 'url')
     *  - JavaScript: esc($data, 'js')
     *  - CSS: esc($data, 'css')
     */

    // =========================================================================
    // AUTHENTICATION & PASSWORD HASHING
    // =========================================================================
    /**
     * Password Hashing Configuration
     *
     * Les mots de passe DOIVENT être stockés hashés utilisant:
     * password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])
     *
     * Vérification:
     * password_verify($plainPassword, $hashedPassword)
     *
     * JAMAIS stocker les mots de passe en plaintext!
     */
}
