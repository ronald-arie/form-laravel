<?php

namespace App\Helpers\Form;

use App\Helpers\Form\FormTemplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Helpers\Inspira\General;

class Form extends FormTemplate {

    public $errors = null;
    public $col_label = 2;
    private $all_inputs;
    private $validator_rule = array();
    private $validator_message = array();
    private $Validator;
    private $master;
    private $key;
    private $attribut_temp = array();
    public $request;

    function __construct() {
        $this->setErrors(Session::get('errors'));
        $this->request = request();
    }

    function getErrors() {
        return $this->errors;
    }

    function getColLabel() {
        return $this->col_label;
    }

    function getJs() {
        return $this->js;
    }

    function printJs() {
        foreach ($this->js as $key => $js) {
            echo $js;
        }
    }

    function setErrors($errors) {
        $this->errors = $errors;
    }

    function setColLabel($col_label) {
        $this->col_label = $col_label;
    }

    public function validate() {
        $data_request = $this->getNameValueAllForValidate();
        $this->Validator = Validator::make($data_request, $this->validator_rule, $this->validator_message);
        if ($this->Validator->fails()) {
            $this->errors = $this->Validator->errors();
            return false;
        }
        return true;
    }

    function getValidator() {
        return $this->Validator;
    }

    private function setValidatorRuleMessage($name, array $validator = []) {
        $this->master[$name]['validator'] = array_merge($this->master[$name]['validator'], $validator);
        $label = $this->master[$name]['attributes']['label'] ?? ucwords($name);

        $this->validator_rule[$name] = $validator['rules'] ?? '';
        $validator['error_message'] = $validator['error_message'] ?? array();

        //menambahkan error_message dari class FormRequest
        foreach ($validator['error_message'] as $key => $value) {
            $this->validator_message[$name . '.' . $key] = $value;
        }

        //menambahkan error_message dari type rules nya
        $this->validator_message[$name] = $validator['error_message'];
        if (strpos($this->validator_rule[$name], 'required') !== false) {
            $this->validator_message[$name . '.required'] = 'The ' . $label . ' field is required.';
            $this->master[$name]['attributes']['required'] = null;
        }
        if (strpos($this->validator_rule[$name], 'image') !== false) {
            $this->validator_message[$name . '.image'] = 'The ' . $label . ' must be an image.';
        }


        if (@$validator) {
            foreach ($validator as $key => $value) {
                if (!isset($this->master[$name][$key])) {
                    $this->master[$name][$key] = $value;
                }
            }
        }
    }

    public function add($name, $type, array $validator = [], array $attributes = array()) {
        $this->master[$name] = [
            'name' => $name,
            'type' => $type,
            'validator' => array(),
            'attributes' => $attributes
        ];

        if ($type == 'select2_url' && @$attributes['multiple']) {
//            $name.='.*';
            $validator['rules'] .= '|array';
        }
        $this->setValidatorRuleMessage($name, $validator);
    }

    public function generate($key) {

        $name = $this->master[$key]['name'];
        $type = $this->master[$key]['type'];
        $value = @$this->master[$key]['value'];
        $validator = $this->master[$key]['validator'];
        $attributes = $this->master[$key]['attributes'];

        $help = '';
        $label = ucwords(str_replace('_', ' ', $name));

        if (@$attributes['help']) {
            $help = $attributes['help'];
            unset($attributes['help']);
        }
        if (@$attributes['label']) {
            $label = $attributes['label'];
            unset($attributes['label']);
        }

        switch ($type) {
            case 'search':
                return $this->input_search($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'text':
                return $this->input_text($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'password':
                return $this->input_password($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'number':
                return $this->input_number($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'number_box':
                return $this->input_number_box($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'prefix_number_box':
                return $this->input_prefix_number_box($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'suffix_number_box':
                return $this->input_suffix_number_box($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'currency':
                $attributes['class'] = @$attributes['class'] . ' input-currency ';
                return $this->input_currency($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'float_dot':
                $attributes['class'] = @$attributes['class'] . ' input-float-dot ';
                return $this->input_text($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'float_comma':
                $attributes['class'] = @$attributes['class'] . ' input-float-comma ';
                return $this->input_text($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'switch':
                return $this->input_switch($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'select':
                $array_data = $this->master[$key]['data'] ?? array();
                return $this->input_select($name, $label, $array_data, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'select2_url':
                $url = $this->master[$key]['url'] ?? null;
                $url_api = $this->master[$key]['url-api'] ?? null;
                return $this->input_select2_url($name, $label, $url, $url_api, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'checkbox':
                $array_data = $this->master[$key]['data'] ?? array();
                $attributes['class'] = @$attributes['class'] . ' kt-checkbox-list';
                return $this->input_checkbox_group($name, $label, $array_data, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'checkbox_inline':
                $array_data = $this->master[$key]['data'] ?? array();
                $attributes['class'] = @$attributes['class'] . ' kt-checkbox-inline';
                return $this->input_checkbox_group($name, $label, $array_data, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'month_year':
                return $this->input_month_year($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'date':
                return $this->input_datepicker($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'time':
                return $this->input_timepicker($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'datepicker_timepicker':
                return $this->input_datepicker_timepicker($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'texteditor':
                return $this->input_texteditor($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            case 'upload':
                return $this->input_upload($name, $label, $value, $this->errors, $this->col_label, $help, $attributes);

            default:
                break;
        }
    }

    public function addValidator($name, $key_validator, $validator) {
        $validator_array = array(
            $key_validator => $validator
        );
        $this->setValidatorRuleMessage($name, $validator_array);
    }

    public function setValue($key, $value) {
        if (@$this->master[$key]) {
            $this->master[$key]['value'] = $value;
        }
    }

    public function addAttribute($name, $key_attribute, $value_attribute) {
        if (@$this->master[$name]) {
            $this->master[$name]['attributes'][$key_attribute] = $value_attribute;
        }
    }

    public function getAttribute($name, $key_attribute) {
        return @$this->master[$name]['attributes'][$key_attribute];
    }

    public function removeAttribute($name, $key_attribute) {
        unset($this->master[$name]['attributes'][$key_attribute]);
    }

    public function setData($key, $array_data) {
        if (@$this->master[$key]) {
            $this->master[$key]['data'] = $array_data;
        }
    }

    public function getAll() {
        $inputs = '';
        foreach ($this->master as $key => $value) {
            $inputs .= $this->getInput($key);
        }
        return $inputs;
    }

    public function generateAll() {
        foreach ($this->master as $key => $value) {
            echo $this->getInput($key);
        }
    }

    public function getInput($key) {
        if (!@$key) {
            return '';
        }
        $intput = $this->generate($key);
        return $intput;
    }

    /**
     *
     * @param string $key
     * @return $this
     */
    public function input($key) {
        foreach ($this->attribut_temp as $key_attribute => $value_attribute) {
            $this->master[$key]['attributes'][$key_attribute] = $value_attribute;
        }
        echo $this->getInput($key);
        $this->attribut_temp = array();
    }

    public function readOnly() {
        $this->attribut_temp['readonly'] = "";
        return $this;
    }

    public function attribute($key, $value = '') {
        $this->attribut_temp[$key] = $value;
        return $this;
    }

    public function setAllAttribute($key, $value = '') {
        foreach ($this->master as $key_master => $value_master) {
            if ($key == 'class' && isset($this->master[$key_master]['attributes'][$key])) {
                $this->master[$key_master]['attributes'][$key] .= $value;
                continue;
            }
            $this->master[$key_master]['attributes'][$key] = $value;
        }
    }

    public function getNameValue($name = null) {
        if (isset($this->master[$name])) {
            $value = $this->request->input($key) ?? null;
            if ($this->master[$key]['type'] == 'switch') {
                $value = false;
            }
            return $value;
        }
        return null;
    }

    public function getNameValueAll() {
        $result_input = array();
        foreach ($this->master as $key => $value) {
            $result_input[$key] = $this->request->input($key) ?? null;
            if ($value['type'] == 'password' && $result_input[$key] == null) {
                unset($result_input[$key]);
            }
            if ($this->master[$key]['type'] == 'switch' && $result_input[$key] == null) {
                $result_input[$key] = false;
            }
            if ($this->master[$key]['type'] == 'select2_url' && @$this->master[$key]['attributes']['multiple'] && $result_input[$key] == null) {
                $result_input[$key] = array();
            }
            if ($this->master[$key]['type'] == 'datepicker_timepicker') {
                $result_input[$key] = $this->request->input($key . '_datepicker') ?? '01/01/1970';
                $result_input[$key] .= ' ';
                $timepicker = $this->request->input($key . '_timepicker') ?? '07:00:00';
                $result_input[$key] .= strlen($timepicker) != 8 ? '0' . $timepicker : $timepicker;
                $result_input[$key] = str_replace('/', '-', $result_input[$key]);

                $vardate = General::dateToUnix($result_input[$key]);
                $result_input[$key] = date('Y-m-d H:i:s', $vardate);
//                $result_input[$key . '_unix'] = General::dateToUnix($result_input[$key]);
            }
            if ($this->master[$key]['type'] == 'date') {
                $result_input[$key] = $this->request->input($key) ?? '';
                if (!$result_input[$key]) {
                    continue;
                }
                $result_input[$key] = str_replace('/', '-', $result_input[$key]);

                $vardate = strtotime($result_input[$key]);
                $result_input[$key] = date('Y-m-d', $vardate);
            }
            if ($this->master[$key]['type'] == 'month_year') {
                $result_input[$key] = $this->request->input($key) ?? '';
                if (!$result_input[$key]) {
                    continue;
                }
                $exploded = explode('/', $result_input[$key]);
                $result_input[$key] = $exploded[1] . '-' . $exploded[0];
            }
            if ($this->master[$key]['type'] == 'upload' && $this->request->hasFile($key)) {
                $file = $this->request->file($key);
                $file_open = fopen($file->getPathname(), "r");
                $file_read = fread($file_open, filesize($file->getPathname()));
                $base64_file = 'data:image/png;base64,' . base64_encode($file_read);
                $result_input[$key] = $base64_file;
            }

            //default combo, karena pembataasan vendor dan office
            if (isset($value['attributes']['class']) && strpos('  ' . $value['attributes']['class'], 'fixed-disabled')) {
                $result_input[$key] = $this->getFixedDisableValue($value['type'], @$value['value']);
                if (!$result_input[$key]) {
                    unset($result_input[$key]);
                }
            }

            if ($this->master[$key]['type'] == 'currency') {
                $result_input[$key] = str_replace('.', '', $result_input[$key]);
            }
        }
        return $result_input;
    }

    public function getNameValueAllForValidate() {
        $result_input = $this->getNameValueAll();
        foreach ($this->master as $key => $value) {
            if ($this->master[$key]['type'] == 'upload') {
                if ($this->request->hasFile($key)) {
                    $result_input[$key] = $this->request->file($key);
                } else {
                    unset($result_input[$key]);
                }
            }
        }
        return $result_input;
    }

    private function getFixedDisableValue($type, $value_form) {
        switch ($type) {
            case 'select2':
                $fixed_value = $value_form;
                if (isset($value_form[0]['id'])) {
                    $fixed_value = $value_form[0]['id'];
                }
                return $fixed_value;
                break;

            case 'select2_url':
                $fixed_value = $value_form;
                if (isset($value_form[0]['id'])) {
                    $fixed_value = $value_form[0]['id'];
                }
                return $fixed_value;
                break;

            case 'select':
                $fixed_value = $value_form;
                if (isset($value_form[0]['id'])) {
                    $fixed_value = $value['value'][0]['id'];
                }
                return $fixed_value;
                break;

            default:
                return $value_form;
                break;
        }
    }

    public function getForm(string $name) {
        $form[$name] = $this->master[$name] ?? [];
        return $form;
    }

    public function addForm(array $form) {
        foreach ($form as $key => $value) {
            $this->add($value['name'], $value['type'], $value['validator'] ?? [], $value['attributes'] ?? []);
            $this->setValue($value['name'], @$value['value']);
        }
    }

}
