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
function searchTuples($keyword) {
    global $myCon;

    $encryptionKey = "mysecretpass1234";

    $search =
    "SELECT
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
       OR accounts.comment LIKE ?";

    // Prepare the SQL statement
    $searchStatement = $myCon->prepare($search);

    // Execute the prepared statement and fill each placeholder in order
    $searchStatement->execute([
        $encryptionKey,
        $keyword, $keyword, $keyword, $keyword, $keyword
    ]);

    // Return all rows that match the keywords as associative arrays
    return $searchStatement->fetchAll(PDO::FETCH_ASSOC);
}

// Update the url of a site name that matches the pattern
function updateSiteUrl($site_name, $updated_url) {
    global $myCon;

    $update =
    "UPDATE websites
    SET site_url = ?
    WHERE site_name LIKE ?";

    $updateStatement = $myCon->prepare($update);
    $updateStatement->execute([$updated_url, $site_name]);

    // Return number of rows affected
    return $updateStatement->rowCount();
}

// Insert
function insertTuple($site_name, $site_url, $email, $username, $password, $comment) {
    global $myCon;

}
?>
