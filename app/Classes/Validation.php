<?php

namespace App\Classes;

use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\Validator;

class Validation extends Validator
{

    /**
     * Define your custom rules error messages
     * @var array
     */
    private $_custom_messages = [
        "alpha_dash_spaces"     => "The :attribute may only contain letters, spaces, and dashes.",
        "alpha_num_spaces"      => "The :attribute may only contain letters, numbers, and spaces.",
        "alpha_num_dash_spaces" => "The :attribute may only contain letters, numbers, dashed and spaces.",
        "us_phone_standards"    => "The :attribute may only contain numbers, dashes, parenthesis and spaces.",
        "phone"                 => "Please enter your valid phone number in international format.",
        "unique_phone"          => "The :attribute already exists.",
        "unique_encrypted"      => "The :attribute already exists.",
        "decimal"               => "The :attribute is not a valid decimal.",
        "edu"                   => "The :attribute value should end with .edu",
    ];

    /**
     * Constructor to register custom error messages in core
     * @param mixed  $translator
     * @param mixed  $data
     * @param array  $rules
     * @param array  $messages
     * @param array  $customAttributes
     */
    public function __construct( $translator, $data, $rules, $messages = array(), $customAttributes = array() ) {
        parent::__construct( $translator, $data, $rules, $messages, $customAttributes );

        $this->setCustomMessages( $this->_custom_messages );
    }

    /**
     * Allow only english alphabets and numbers
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateEnglishAlphaNum( $attribute, $value, $parameters, $validator )
    {
        return (bool) preg_match( "/^[A-Za-z0-9]+$/", $value );
    }

    /**
     * Allow only alphabets, spaces and dashes (hyphens and underscores)
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaDashSpaces( $attribute, $value, $parameters, $validator )
    {
        return (bool) preg_match( "/^[A-Za-z\s-_]+$/", $value );
    }

    /**
     * Allow only alphabets, numbers, and spaces
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaNumSpaces( $attribute, $value, $parameters, $validator )
    {
        return (bool) preg_match( "/^[A-Za-z0-9\s]+$/", $value );
    }

    /**
     * Allow only alphabets, numbers, spaces and dashes (hyphens and underscores)
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaNumDashSpaces( $attribute, $value, $parameters, $validator )
    {
        return (bool) preg_match( "/^[A-Za-z0-9\s-_]+$/", $value );
    }

    /**
     * Validates for decimal value given in string
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  Validator $validator
     * @return bool
     */
    protected function validateDecimal( $attribute, $value, $parameters, $validator )
    {
        $start = isset($parameters[0]) ? $parameters[0] : '';
        $end   = isset($parameters[1]) ? $parameters[1] : '';

        return (bool) preg_match( "/^(\-)?\d{1,".$start."}\.\d{1,".$end."}$/", $value );
    }

    /**
     * Allow only digits, parenthesis, period & dash.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateUsPhone( $attribute, $value, $parameters, $validator )
    {
        return (bool) preg_match( "/^[\+0-9\(\)\.\-\s]+$/", $value );
    }

    /**
     * Validate mobile number for Saudia Arabia
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateSaMobile( $attribute, $value, $parameters, $validator )
    {
        if ( strlen($value) < 9 )
            return false;

        if ( substr($value, -9, 1) != 5 )
            return false;

        return true;
    }

    /**
     * Validate phone number with the help of package `propaganistas/laravel-phone`
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validatePhone( $attribute, $value, $parameters, $validator )
    {
        if ( function_exists('phone') ) {
            try {
                return phone($value)->isOfCountry($parameters[0]);
            } catch (\Exception $e) {
                return false;
            }
        } else {
            throw new \Exception('Unable to validate your phone number since you do not have phone library package.');

        }
    }

    /**
     * Validate phone number with the help of package `propaganistas/laravel-phone`
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateUniquePhone( $attribute, $value, $parameters, $validator )
    {
        if ( function_exists('phone') ) {
            try {
                $value = phone($value, array_shift($parameters), 0);
            } catch (\Exception $e) {}
        }

        return call_user_func_array(array('parent', 'validateUnique'), [
            $attribute,
            $value,
            $parameters,
            $validator,
        ]);
    }

    /**
     * Validate value after encryption which stored in databsae.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateUniqueEncrypted( $attribute, $value, $parameters, $validator )
    {
        $value = \App\Classes\RijndaelEncryption::encrypt($value);
        $method = 'validateUnique';

        // First parameter which trigger rule to be executed it is optional, will trigger function if method exist.
        if ( preg_match('%^\[(\w+)\]$%', $parameters[0], $detectMethod) ) {
            $detectMethod = camel_case('validate_'.$detectMethod[1]);
            $method = method_exists($this, $detectMethod) ? $detectMethod : $method;
            array_shift($parameters);
        }

        return call_user_func_array(array($this, $method), [
            $attribute,
            $value,
            $parameters,
            $validator,
        ]);
    }

    protected function validateEdu( $attribute, $value, $parameters, $validator )
    {
        return (bool) (ends_with($value, '.edu'));
    }
}
