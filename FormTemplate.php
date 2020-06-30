<?php

namespace App\Helpers\Form;

use Illuminate\Support\Arr;
use App\Helpers\Inspira\General;

class FormTemplate {

    protected $js = array();

    public function getAttributes($attributes = array()) {
        $attributes['class'] = 'form-control ' . @$attributes['class'];

        $text_attributes = '';
        foreach ($attributes as $key_attribute => $value_attribute) {
            $text_attributes .= $key_attribute;
            if ($value_attribute != '') {
                $text_attributes .= '="' . $value_attribute . '"';
            }
            $text_attributes .= ' ';
        }
        return $text_attributes;
    }

    public function input_base_header($name, $label, $value, $col_label = 0) {
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }
        if ($label == "" || $label == null) {
            $label = ucwords(str_replace('_', ' ', $name));
        }
        if ($col_label == null) {
            $col_label = 0;
        }

        $col_input = 12 - $col_label;
        if ($col_label == 0 || $col_label == 12) {
            $col_label = 12;
            $col_input = 12;
        }

        $result = '
        <div class="form-group m-form__group row form-input-' . $name . '">
            <label for="' . $name . '" class="col-' . $col_label . ' col-form-label">' . $label . '</label>
            <div class="col-' . $col_input . '">';
        return $result;
    }

    public function input_base_footer($name, $errors, $col_label = 0, $help = "") {
        if ($col_label == 0 || $col_label == 12) {
            $col_label = 12;
            $col_input = 12;
        }
        $error_message = '';
        if ($errors !== null) {
            $error_message = @$errors->first($name);
        }
        $result = '<span class="kt-form__help kt-font-danger">' . $error_message . '</span>';
        if (!empty($help)) {
            $result .= '<div><span class="m-form__help">' . $help . '</span></div>';
        }
        $result .= '
            </div>
        </div>';

        return $result;
    }

    public function input_search($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class=" kt-input-icon kt-input-icon--left">
                            <input type="text" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>
                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                <span><i class="la la-search"></i></span>
                            </span>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_text($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<input type="text" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_password($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<input type="password" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_number($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input input-number ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '"  ' . $text_attributes . '>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_number_box($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input input-number ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<input type="number" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_prefix_number_box($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input input-number ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">' . @$attributes['prefix'] . '</span></div>
                                <input type="text" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_suffix_number_box($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input input-number ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class="input-group">
                                <input type="number" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>
                                <div class="input-group-append"><span class="input-group-text">' . @$attributes['suffix'] . '</span></div>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_currency($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">' . @$attributes['prefix'] . '</span></div>
                                <input type="text" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        $this->js[$name] = '
                $(\'#' . $name . '\').mask("#.##0", {reverse: true});';
        return $input;
    }

    public function input_switch($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }
        $checked = '';
        if ($value) {
            $checked = 'checked="checked"';
        }

        $attributes['class'] = @$attributes['class'] . '';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<span class="kt-switch">
                                <label>
                                        <input value="1" type="checkbox" ' . $checked . '  name="' . $name . '" ' . $text_attributes . '>
                                        <span></span>
                                </label>
                        </span>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    function input_select($name, $label, $array_data, $value, $errors, $col_label = 2, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-select2';
        $attributes['placeholder'] = $attributes['placeholder'] ?? 'Select ' . $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<select name="' . $name . '"  id="' . $name . '" ' . $text_attributes . '>';
        // $input .= "<option></option>\n";
        foreach ($array_data as $key => $value_option) {
            $selected = (string) $value == (string) $value_option['id'] ? 'selected' : '';
            $input .= '           <option value = "' . $value_option['id'] . '" class = "' . @$value_option['class'] . '" ' . $selected . ' >' . $value_option['text'] . '</option>';
        }
        $input .= '</select>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        $allow_clear = $attributes['allow_clear'] ?? 'true';
        $this->js[$name] = '
                    $(\'#' . $name . '\').select2({
                        allowClear: ' . $allow_clear . ',
                        placeholder: \'' . $attributes['placeholder'] . '\',
                    });';

        return $input;
    }

    function input_select2_url($name, $label, $url, $url_api = null, $value, $errors, $col_label = 2, $help = "", array $attributes = array()) {
        $input = '';

        if (!is_array($value)) {
            $value = array();
        }
        if (old($name) != null || old($name) != "" || (@$attributes['multiple'] && isset($value[0]) && !isset($value[0]['id']))) {
            //multiple
            $old_value = old($name);
            if (@$attributes['multiple'] && isset($value[0]) && !isset($value[0]['id'])) {
                //jika $value tidak sesuai maka akan di dicari di API, $value yang lama akan ikut prosess nya dari oldvalue dan $value akan di kosongkan
                $old_value = $value;
                $value = array();
            }
            if (!is_array($old_value)) {
                $old_value = array();
                $old_value[] = old($name);
            }

            foreach ($old_value as $key => $each_old_value) {

                if ($url_api == null) {
                    $url_for_api = str_replace('admin/', '', $url); //tidak ada url api yang url segment nya admin
                    $url_explode = explode('/', $url_for_api);
                    //end cek data di value
                    $url_detail = $url_explode[1] . '/' . $each_old_value;
                } else {
                    $url_detail = $url_api . '/' . $each_old_value;
                }
                //cek data di value
                $is_on_array = false;
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2) && array_search($each_old_value, $value2)) {
                        $is_on_array = true;
                        break;
                    }
                }
                if ($is_on_array) {
                    continue;
                }
                $response = General::get_api($url_detail, 'get');
                if ($response['meta']['code'] == '200' && $response['meta']['status'] && isset($response['data']['detail']['id'])) {
                    $value[] = array(
                        'id' => $response['data']['detail']['id'],
                        'text' => $response['data']['detail']['name'],
                    );
                } else {
                    //jika pencarian menggukana ?id=
                    $url_detail = substr($url, 1) . '?id=' . $each_old_value;
                    $url_detail = str_replace('/data/select2', '', $url_detail);
                    $response = General::get_api($url_detail, 'get');
                    if ($response['meta']['code'] == '200' && $response['meta']['status'] && isset($response['data']['list'][0])) {
                        $value[] = array(
                            'id' => $response['data']['list'][0]['id'],
                            'text' => $response['data']['list'][0]['name'],
                        );
                    }
                }
            }
        }


        $attributes['class'] = @$attributes['class'] . ' m-select2';
        $attributes['placeholder'] = $attributes['placeholder'] ?? 'Select ' . $label;
        $text_attributes = $this->getAttributes($attributes);
        $multiple = $attributes['multiple'] ?? false;
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<select name="' . $name . ($multiple ? '[]' : '') . '"  id="' . $name . '" ' . $text_attributes . '>';
        foreach ($value as $key => $value_option) {
            $input .= '           <option value = "' . $value_option['id'] . '"  selected >' . $value_option['text'] . '</option>';
        }
        $input .= '</select>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        $sort = "";
//        if ($sorting) {
//            $sort = '.select2_sortable()';
//        }
        $allow_clear = $attributes['allow_clear'] ?? 'true';
        $this->js[$name] = '
        //select2 id=' . $name . '============

                    $(\'#' . $name . '\').select2({
                        allowClear: ' . $allow_clear . ',
                        placeholder: \'' . $attributes['placeholder'] . '\',
                        ajax: {
                            url: \'' . $url . '\',
                            dataType: \'json\',
                            delay: 250,
                            data: function (params) {
                                return {
                                    query: params.term,';

        if (@$attributes['data-parent']) {
            $data_parent = explode(',', $attributes['data-parent']);
            foreach ($data_parent as $parent) {
                $this->js[$name] .= "\n" . $parent . ': $(\'#' . $parent . '\').val(), ';
            };
        }

        $this->js[$name] .= '
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.items
                                };
                            }
                        },
                        //minimumInputLength: 2,
                    })' . $sort . ';';

        if (@$attributes['data-parent']) {
            $data_parent = explode(',', $attributes['data-parent']);
            foreach ($data_parent as $parent) {
                $this->js[$name] .= "
                    $('#$parent').change(function () {
                        $('#$name').val('').trigger('change');
                    });";
            };
        }

        $this->js[$name] .= "
        //end select2 id=$name============  \n";
        return $input;
    }

    function input_checkbox_group($name, $label, $array_data, $value, $errors, $col_label = 2, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $text_attributes = str_replace('form-control', '', $text_attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div ' . $text_attributes . '>';
        foreach ($array_data as $key => $value_option) {
            $checked = (($value == $value_option['id']) || (is_array($value) && in_array($value_option['id'], $value, true))) ? 'checked' : '';
            $text_attributes = str_replace('class="', 'class="' . $name . ' ', $text_attributes);
            $input .= '
                        <label class="kt-checkbox mr-4">
                            <input type="checkbox"
                                   id="' . $name . '_' . $value_option['id'] . '"
                                   name="' . $name . '[]' . '"
                                   value="' . $value_option['id'] . '"
                                   ' . $checked . ' ' . $text_attributes . '/> ' . $value_option['text'] . '
                            <span></span>
                        </label>';
        }
        $input .= '</div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_datepicker($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . 'm-input input-datepicker';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class="input-group">
                                <input autocomplete="off" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-calendar"></i></span></div>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_month_year($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . 'm-input input-month-year';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class="input-group">
                                <input autocomplete="off" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-calendar"></i></span></div>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_timepicker($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . 'm-input input-timepicker';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<input autocomplete="off" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_datepicker_timepicker($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        $value_datepicker = '';
        $value_timepicker = '';
        if (old($name . '_datepicker') != null || old($name . '_datepicker') != "" || old($name . 'timepicker') != null || old($name . '_timepicker') != "") {
            $value = old($name . '_datepicker') . ' ' . old($name . '_timepicker');
        }
        if ($value) {
            if (!strpos($value, ' ')) {
                $value = General::format_date($value, 'd/m/Y H:i:s');
            }
            $value_timepicker_datepicker = explode(' ', $value);
            $value_datepicker = $value_timepicker_datepicker[0];
            $value_timepicker = $value_timepicker_datepicker[1];
        }
        $attributes['class'] = @$attributes['class'] . 'm-input input-datepicker form-control col-7';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes_datepicker = $this->getAttributes($attributes);
        $text_attributes_timepicker = str_replace('input-datepicker form-control col-7', 'input-timepicker form-control col-4', $text_attributes_datepicker);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<div class="input-group">
                                <input type="text" autocomplete="off" name="' . $name . '_datepicker" id="' . $name . '_datepicker"  value="' . $value_datepicker . '"  ' . $text_attributes_datepicker . '>
                                <span class="input-group-text  " id="basic-addon1"><center><i class="fa fa-calendar"></i></center></span>
                                <input type="text" autocomplete="off" name="' . $name . '_timepicker" id="' . $name . '_timepicker"  value="' . $value_timepicker . '"  ' . $text_attributes_timepicker . '>
                        </div>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        return $input;
    }

    public function input_texteditor($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . 'm-input input-texteditor';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<textarea  name="' . $name . '" id="' . $name . '"  ' . $text_attributes . '>' . $value . '</textarea>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);

        $this->js[$name] = '
                    $(\'#' . $name . '\').summernote({'
                . 'height: 200});';
        if (isset($attributes['disabled'])) {
            $this->js[$name] .= '
                    $(\'#' . $name . '\').summernote(\'disable\');';
        }
        return $input;
    }

    public function input_upload($name, $label, $value, $errors, $col_label = 0, $help = "", array $attributes = array()) {
        $input = '';
        if (old($name) != null || old($name) != "") {
            $value = old($name);
        }

        $attributes['class'] = @$attributes['class'] . ' m-input ';
        $attributes['placeholder'] = $attributes['placeholder'] ?? $label;
        $text_attributes = $this->getAttributes($attributes);
        $input .= $this->input_base_header($name, $label, $value, $col_label);
        $input .= '<input type="file" name="' . $name . '" id="' . $name . '"  value="' . $value . '"  ' . $text_attributes . '>';
        $input .= $this->input_base_footer($name, $errors, $col_label, $help);
        if ($value) {
            $input .= '<img style="max-width:100%" src="' . $value . '"/>';
        }
        return $input;
    }

}
