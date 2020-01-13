<?php

namespace ShangYou\Exceptions;

class WrapedValidationException extends \Exception
{
    private $_errors = [];


    /**
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->_errors = $errors;
        return $this;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

}