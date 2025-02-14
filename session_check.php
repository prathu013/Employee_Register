<?php
// session_check.php - Include this at the top of all protected pages
function checkSession() {
    $inactive = 1800; // 30 minutes inactivity timeout
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
        // Session expired
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
    $_SESSION['last_activity'] = time();
}
?>