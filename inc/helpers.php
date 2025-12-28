<?php

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function clean_text($value) {
    return trim(filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
}

function clean_email($value) {
    return trim(filter_var($value, FILTER_SANITIZE_EMAIL));
}

function require_fields($data, $fields) {
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            json_response(["ok" => false, "error" => "Missing field: {$field}"], 400);
        }
    }
}
