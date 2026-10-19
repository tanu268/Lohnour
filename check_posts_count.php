<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
define('DB_HOST', '127.0.0.1:3307');
['HTTP_HOST'] = 'localhost';
['SERVER_NAME'] = 'localhost';
['REQUEST_METHOD'] = 'GET';
require_once 'D:/xampp/htdocs/lohnour_old/wordpress/wp-load.php';

foreach (['/services/corporate/', '/coaching/', '/coaching/individual/', '/coaching/group/'] as \) {
    global \, \;
    \['REQUEST_URI'] = '/lohnour_old/wordpress' . \;
    \->query_vars = [];
    \->parse_request();
    \->query_posts();
    echo \ . ' => post_count=' . \->post_count . ' | is_404=' . (\->is_404() ? 'true' : 'false') . '\n';
}
