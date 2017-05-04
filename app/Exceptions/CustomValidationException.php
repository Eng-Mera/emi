<?php

namespace App\Exceptions;

class CustomValidationException extends \Exception {

    private $validator;

    public function __construct(\Illuminate\Validation\Validator $_validator) {
        $this->setValidator($_validator);
    }

    public function getValidator() {
        return $this->validator;
    }

    public function setValidator($_validator) {
        $this->validator = $_validator;
    }

}
