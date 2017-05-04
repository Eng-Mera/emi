<?php
/**
 * OAuth 2.0 Invalid Credentials Exception
 *
 * @package     league/oauth2-server
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Exceptions;
use League\OAuth2\Server\Exception\OAuthException;

/**
 * Exception class
 */
class InvalidSocialException extends OAuthException
{
    /**
     * {@inheritdoc}
     */
    public $httpStatusCode = 401;

    /**
     * {@inheritdoc}
     */
    public $errorType = 'invalid_user_id';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('We can\'t find any user with the given details.');
    }
}
