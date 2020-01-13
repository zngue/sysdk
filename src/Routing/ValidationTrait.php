<?php
namespace ShangYou\Routing;


trait ValidationTrait
{

    /**
     * Validate the given parameters with the given rules.
     *
     * @param  array  $parameters
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function validateParameters($parameters, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($parameters, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException(app('request'), $validator);
        }
    }

}