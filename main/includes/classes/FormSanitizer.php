<?php
    class FormSanitizer {
        
        public static function sanitizeFormString($inputText) {
            $inputText = htmlspecialchars(strip_tags($inputText));
            return $inputText;
        }

        public static function sanitizeFormUsername($inputText) {
            $inputText = htmlspecialchars(strip_tags($inputText));
            $inputText = str_replace(" ", "", $inputText);
            return $inputText;
        }

        public static function sanitizeFormPassword($inputText) {
            $inputText = htmlspecialchars(strip_tags($inputText));
            return $inputText;
        }

        public static function sanitizeFormEmal($inputText) {
            $inputText = htmlspecialchars(strip_tags($inputText));
            $inputText = str_replace(" ", "", $inputText);
            $inputText = filter_var($inputText, FILTER_SANITIZE_EMAIL);
            return $inputText;
        }

    }
?>