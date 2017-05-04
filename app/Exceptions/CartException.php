<?php
/**
 * Cart Module Exception
 *
 * Class of exceptions which is thrown when errors occur that are
 * related to the cart module procedures like
 * notifications, helpers, listeners etc.
 * This helps in detecting exceptions related to business logic
 * so that the controller can send useful responses to api clients.
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Exceptions;

use Exception;

class CartException extends Exception
{

}