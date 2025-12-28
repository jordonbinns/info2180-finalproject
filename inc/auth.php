<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (!isset($_SESSION["user"])) {
        http_response_code(401);
        echo "Not logged in";
        exit;
    }
}

function require_admin() {
    require_login();
    if ($_SESSION["user"]["role"] !== "Admin") {
        http_response_code(403);
        echo "Admin only";
        exit;
    }
}
