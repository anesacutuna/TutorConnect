<?php
// Copy this file to config.php and enter local values. Never commit config.php.
return [
    'db_host' => '127.0.0.1',
    'db_port' => 3306,
    'db_name' => 'tutorconnect',
    'db_user' => 'root',
    'db_password' => '',
    'jwt_secret' => 'REPLACE_WITH_A_RANDOM_SECRET_OF_AT_LEAST_32_CHARACTERS',
    'jwt_expiration' => 86400,
    'open_library_base_url' => 'https://openlibrary.org',
    'open_library_timeout' => 8,
    'open_library_connect_timeout' => 3,
    'open_library_user_agent' => 'TutorConnect/1.0 (contact: YOUR_EMAIL)',
];
