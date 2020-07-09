<?php
declare(strict_types=1);

namespace App\Service;

class FormValidator
{
    const MIN_LOGIN_LENGTH = 3;
    const MAX_LOGIN_LENGTH = 16;
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_STRING_LENGTH = 500;
    const MAX_TEXTAREA_LENGTH = 50000;

    public static function sanitizeString(string $data): ?string
    {
        if (($data !== '') && (mb_strlen($data) <= self::MAX_STRING_LENGTH)) {
            $data = trim($data);
            $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            $data = htmlspecialchars($data);

            return $data;
        }
        return null;
    }

    public static function sanitizeEmail(string $data): ?string
    {
        if ((($data !== '')) && mb_strlen($data) <= self::MAX_STRING_LENGTH) {
            $data = filter_var($data, FILTER_SANITIZE_EMAIL);

            return $data;
        }
        return null;
    }

    public static function sanitizeTextArea(string $data): ?string
    {
        if (($data !== '') && (mb_strlen($data) <= self::MAX_TEXTAREA_LENGTH)) {
            $data = htmlspecialchars($data);

            return $data;
        }
        return null;
    }

    public static function sanitizeLogin(string $data): ?string
    {
        if (($data !== '') && (mb_strlen($data) <= self::MAX_LOGIN_LENGTH) && (mb_strlen($data) >= self::MIN_LOGIN_LENGTH)) {
            $data = trim($data);
            $data = htmlspecialchars($data);

            return $data;
        }
        return null;
    }

    public static function sanitizePassword(string $data): ?string
    {
        if (($data !== '') && (mb_strlen($data) >= self::MIN_PASSWORD_LENGTH)) {
            $data = trim($data);

            return $data;
        }
        return null;
    }

    public static function isEmail(string $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) && !empty($value)) {
            return true;
        }
        return false;
    }

    public static function sanitizeInteger(int $value): ?int
    {
        if (is_int($value) && !empty($value)) {
            return $value;
        }
        return false;
    }
}
