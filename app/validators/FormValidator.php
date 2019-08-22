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
        foreach ($this->rules as $field => $rules) {

            foreach ($rules as $rule) {
                $otherFields = explode('|', $rule);

                switch ($otherFields[0]) {
                    case 'required':
                        if (!isset($this->data[$field]) || empty($this->data[$field])) {
                            $this->addError($field, "$field is a mandantory field");
                        }
                        break;

                    case 'integer':
                        if (isset($this->data[$field])) {
                            if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
                                $this->addError($field, "$field must be integer");
                            }
                        }
                        break;

                    case 'date_greater_than':
                        if (isset($this->data[$field])) {
                            if (isset($otherFields[1])) {
                                if (!isset($this->data[$field]) || $this->data[$field] < $this->data[$otherFields[1]]) {
                                    $this->addError($field, "$field must be greater than $otherFields[1]");
                                }
                            }
                        }
                        break;

                    case 'date':
                        if (isset($this->data[$field])) {
                            $timestamp = strtotime($this->data[$field]);

                            if (!$timestamp) {
                                $this->addError($field, "$field must be a valid date");
                            }
                        }
                        break;

                    case 'number':
                        if (isset($this->data[$field])) {
                            if (!is_numeric($this->data[$field])) {
                                $this->addError($field, "$field must be numeric");
                            }
                        }
                        break;
                }
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