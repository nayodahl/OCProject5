<?php
declare(strict_types=1);

namespace App\Service;

class FormValidator
{
    public static function sanitize(string $data): ?string
    {
        if ((isset($data) && ($data != '')) && strlen($data) < 500 ) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);

            return $data;
        }
        else return null;
    }

    public static function is_email(string $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) && !empty($value)) {
            return true;
        }
        else return false;
    }

}