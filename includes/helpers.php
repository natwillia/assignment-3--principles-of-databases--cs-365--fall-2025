<?php
include_once 'config.php';

// Create database connection
$connectionInfo = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
$myCon = new PDO(
    $connectionInfo,
    DBUSER,
    DBPASS
);

// Search all components of the database for keyword matches
function searchTuples($keyword, $encryptionKey) {
    global $myCon;

    $search = "
    SELECT
        users.username,
        websites.site_name,
        websites.site_url,
        accounts.comment,
        CAST(AES_DECRYPT(accounts.password, ?) AS CHAR) AS decrypted_password
    FROM accounts
    JOIN users ON accounts.user_id = users.user_id
    JOIN websites ON accounts.site_id = websites.site_id
    WHERE users.username LIKE ?
       OR websites.site_name LIKE ?
       OR websites.site_url LIKE ?
       OR users.email LIKE ?
       OR accounts.comment LIKE ?
";


    // Prepare the sql query
    $searchQuery = $myCon->prepare($search);
    // Execute the prepared staemnt and fill each  placeholder in order
    $searchQuery->execute([
        $encryptionKey,
        $keyword, $keyword, $keyword, $keyword, $keyword
    ]);

    // Return all rows that are matching as associative arrays
    return $searchQuery->fetchAll(PDO::FETCH_ASSOC);
}
