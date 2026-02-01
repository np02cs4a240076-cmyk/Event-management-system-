<?php
/**
 * Root redirect to public directory
 * This file redirects all requests to the public/index.php entry point
 */

// Redirect to public folder
header('Location: public/index.php');
exit;
