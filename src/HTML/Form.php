<?php

namespace App\HTML;


class Form
{

    private $data;
    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function input(string $key, string $label, string $placeholder = null): string
    {
        $value = $this->getValue($key);
        $type = $key === "password" ?  "password" : "text";
        return <<<HTML
        <div class="form-group">
            <label for="{$key}">{$label}</label>
            <input type="{$type}" id="{$key}" class="{$this->getInputClass($key)}" placeholder="{$placeholder}" name="{$key}" value="{$value}" required>
            {$this->getErrorFeedback($key)}
        </div>
    HTML;
    }

    public function select(string $key, string $label = null, array $items = [])
    {
        $optionHTML = [];
        $value = $this->getValue($key);
        foreach ($items as $k => $v) {
            $selected = in_array($k, $value) ? " selected" : "";
            $optionHTML[] = "<option value=\"$k\"$selected>$v</option>";
        }
        $optionHTML = implode('', $optionHTML);
        return <<<HTML
        <div class="form-group">
            <label for="{$key}">{$label}</label>
            <select id="{$key}" class="{$this->getInputClass($key)}" name="{$key}[]" required multiple>{$optionHTML}</select>
            {$this->getErrorFeedback($key)}
        </div>
    HTML;
    }

    public function textarea(string $key, string $label, string $placeholder = null, int $rows = null, int $cols = null): string
    {
        $rows = null ?: "7";
        $cols = null ?: "15";
        $value = $this->getValue($key);
        return <<<HTML
        <div class="form-group">
            <label for="{$key}">{$label}</label>
            <textarea id="{$key}" name="{$key}" rows="{$rows}" cols="{$cols}" type="text" placeholder="{$placeholder}"  class="{$this->getInputClass($key)}" required>{$value}</textarea>
            {$this->getErrorFeedback($key)}
        </div>
    HTML;
    }

    public function summerNote(string $key): string
    {
        return <<<HTML
           <script src="/summernote/summernote-bs4.js"></script>
           <script>$(document).ready(function() {
            $("#{$key}").summernote({
                    height: 300,
                    callbacks: {
                    onImageUpload : function(files, editor, welEditable) {
                        for(var i = files.length - 1; i >= 0; i--) {
                            sendFile(files[i], this);
                        }
                    },
                    onMediaDelete : function(target) {
                    deleteFile(target[0].src);
                    }
                  }
                });
            });
            function sendFile(file, el) {
            var form_data = new FormData();
            form_data.append('img', file);
            $.ajax({
                data: form_data,
                type: "POST",
                url: '/upload.php',
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                    $(el).summernote('editor.insertImage', url);
                }
            });
        }
            function deleteFile(src) {
            $.ajax({
            data: {src : src},
            type: "POST",
            url: "/upload.php", // replace with your url
            cache: false,
            success: function(resp) {
                 console.log(resp);
               }
            });
        }
        </script>
        HTML;
    }

    private function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }
        $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        $value = $this->data->$method();
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }

    private function getInputClass(string $key): string
    {
        $inputClass = "form-control";
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
        }
        return $inputClass;
    }

    private function getErrorFeedback(string $key): string
    {
        if (isset($this->errors[$key])) {
            if (is_array($this->errors[$key])) {
                $error = implode('<br>', $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }
            return '<div class="invalid-feedback">' . $error . '</div>';
        }
        return '';
    }
}
