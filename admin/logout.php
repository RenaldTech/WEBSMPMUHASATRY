<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Cek login
if (!isLoggedIn()) {
    redirect('admin/login.php');
}

session_destroy();
redirect('admin/login.php');
