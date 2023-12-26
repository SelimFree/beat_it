<?php

/**
 * Validator class for form validation using a set of preconfigured rules.
 */
class Validator
{
    /**
     * Array of Rule instances.
     */
    private $rules = [];

    /**
     * Accumulated validation error essages in an associative array.
     * Key is input name, value is an array of validation message strings.
     * 
     */
    private $messages = [];

    /**
     * Validator class for form validation using a set of preconfigured rules.
     */
    public function __construct($rules)
    {
        //Check the existance of rule key names
        foreach ($rules as $key => $value) {
            if (!array_key_exists($key, $rules)) {
                throw new Exception("The rule must have a name");
            }
        }
        //Set up the validator
        $this->rules = $rules;
    }

    /**
     * Validates a form coming from a $_POST or $_GET superglobal.
     */
    public function validate($form)
    {
        $this->messages = [];
        $rules = $this->rules;
        foreach ($form as $key => $value) {
            $ruleset = $rules[$key];

            if (array_key_exists($key, $rules)) {
                $this->messages[$key] = [];
                $req = $ruleset['required'] === true;
                if ($req && !$value) {
                    array_push($this->messages[$key], "" . $key . "" . ' field is required.');
                }
                if (!$this->isValid($key, $value, $ruleset)) {
                    array_push($this->messages[$key], "" . $key . "" . ' field is invalid.');
                }
            }
        }
    }

    public function validateImage($files)
    {
        foreach ($files as $key => $value) {
            if (!$this->messages[$key]) {
                $this->messages[$key] = [];
            }
            if ($value["error"] > 0) {
                array_push($this->messages[$key], "" . $key . "" . ' field error: ' . $value["error"]);
            } else {
                // Move the uploaded file to a desired directory
                $targetDirectory = "assets/uploads/";
                $targetFile = $targetDirectory . basename($value["name"]);
                if (!move_uploaded_file($value["tmp_name"], $targetFile)) {
                    array_push($this->messages[$key], "" . $key . "" . ' field error: ' . "Failed to uplaod the file");
                    return;
                }
                return $targetFile;
            }
        }
    }

    /**
     * Returns the accumulated validation errors.
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Checks if a field is valid or not.
     */
    private function isValid($attributeName, $value, $ruleset)
    {
        switch ($attributeName) {
            case "email":
                return $this->validEmail($value);
            case "password":
                return $this->validPassword($value, $ruleset);
            case "password-repeat":
                return $this->matchPassword($value, $ruleset);
            default:
                return $this->validString($value);
        }
    }

    private function validEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private function validPassword($value, $ruleset)
    {
        if (!array_key_exists('validPassword', $ruleset)) {
            return true && $value;
        }
        return password_verify($value, $ruleset['validPassword']);
    }

    private function matchPassword($value, $ruleset)
    {
        if (!array_key_exists('matchPassword', $ruleset)) {
            return true && $value;
        }
        return $value == $ruleset['matchPassword'];
    }

    private function validString($value)
    {
        return true && $value;
    }
}
