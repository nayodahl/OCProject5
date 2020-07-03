<?php
declare(strict_types=1);

namespace App\Service;

class FormValidator
{
    public function sanitizeString(string $data): ?string
    {
        if (($data !== '') && (mb_strlen($data) < 500)) {
            $data = trim($data);
            $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            $data = htmlspecialchars($data);

            return $data;
        }
        return null;
    }

    public function sanitizeEmail(string $data): ?string
    {
        if ((($data !== '')) && mb_strlen($data) < 500) {
            $data = filter_var($data, FILTER_SANITIZE_EMAIL);
            return $data;
        }
        return null;
    }

    public function isEmail(string $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) && !empty($value)) {
            return true;
        }
        return false;
    }
}
