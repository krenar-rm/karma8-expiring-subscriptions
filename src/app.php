<?php

if (!function_exists('check_email')) {
    function check_email(string $email): bool
    {
        sleep(\random_int(1, 1 * 60));

        return false;
    }
}

if (!function_exists('send_email')) {
    function send_email(string $email, string $from, string $to, string $subj, string $body): bool
    {
        sleep(\random_int(1, 10));

        return true;
    }
}

if (!function_exists('is_valid_email')) {
    function is_valid_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
