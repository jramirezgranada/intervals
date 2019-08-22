<?php

namespace App\Validators;

class FormValidator implements ValidatorInterface
{
    protected $data, $rules;
    public $errors;

    /**
     * FormValidator constructor.
     * @param $data
     * @param $rules
     */
    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [
            "status" => "error",
            "code" => 400,
            "message" => "There are erros, please check and try again",
            "data" => []
        ];
    }

    /**
     * Validate sent dat to validator
     * @return array
     */
    public function validate()
    {
        foreach ($this->rules as $field => $rule) {
            switch ($rule) {
                case 'required':
                    if (!isset($this->data[$field]) || empty($this->data[$field])) {
                        $this->addError($field, "$field is a mandantory field");
                    }
                    break;

                case 'email':
                    if (is_array($this->data[$field])) {
                        foreach ($this->data[$field] as $value) {
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError($field, "$field must be a valid email address");
                            }
                        }
                    } else {
                        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, "$field must be a valid email address");
                        }
                    }

                    break;

                case 'integer':
                    if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
                        $this->addError($field, "$field must be integer");
                    }

                    break;
            }
        }

        return count($this->errors["data"]) > 0 ? $this->errors : [];
    }

    /**
     * If an error is found is added to error array
     * @param $field
     * @param $message
     * @return mixed
     */
    protected function addError($field, $message)
    {
        return $this->errors["data"][$field][] = $message;
    }
}