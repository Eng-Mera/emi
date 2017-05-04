<?php
/**
 * Short description
 *
 * Long description for RestValidator.php (if any)...
 * https://laracasts.com/discuss/channels/general-discussion/how-to-return-error-code-of-validation-fields-in-rest-api/replies/23216
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class RestValidator extends Validator
{
    /**
     * Add a descriptive error message to the validator trait
     *
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     */
    protected function addError($attribute, $rule, $parameters)
    {
        parent::addError($attribute, $rule, $parameters);

        $message = $this->getMessage($attribute, $rule);

        $message = $this->doReplacements($message, $attribute, $rule, $parameters);

        $customMessage = new MessageBag();

        $customMessage->merge(['code' => strtolower($rule . '_rule_error')]);
        $customMessage->merge(['message' => $message]);

        $this->messages->add($attribute, $customMessage);//dd($this->messages);
    }
}