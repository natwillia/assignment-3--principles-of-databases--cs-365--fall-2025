<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Passwords Database</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <h1>Student Passwords Database</h1>
    </header>

    <!-- Search Form -->
    <form action="index.php" method="post">
      <div>
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Enter keyword">
      </div>
      <div>
        <button type="submit" name="submit_search">Search</button>
        <!-- Button to clear results -->
        <button type="submit" name="clear_results">Clear Results</button>
      </div>
    </form>

<?php
require_once "includes/helpers.php";

// Search
if (isset($_POST["submit_search"])) {

    // Ex: if user types food the $keyword becomes %food%
    $keyword = "%" . $_POST["search"] . "%";
    $encryptionKey = "mysecretpass1234";

    // Call helper function to search tuples in the database
    $results = searchTuples($keyword, $encryptionKey);

    // html table to display results and failed search queries
    echo "
    <table border='1'>
      <caption>Search Results</caption>

      <thead>
        <tr>
          <th scope='col'>Username</th>
          <th scope='col'>Site Name</th>
          <th scope='col'>URL</th>
          <th scope='col'>Comment</th>
          <th scope='col'>Password</th>
        </tr>
      </thead>

      <tbody>
    ";

// Loop through each returned row and display it in the table
if ($results) {
    foreach ($results as $row) {

        $plain  = $row['decrypted_password'];
        // hides the decrypted password in the browser and adapts the mask to its length
        $maskedPass = str_repeat('*', strlen($plain));

        echo "
        <tr>
          <td>{$row['username']}</td>
          <td>{$row['site_name']}</td>
          <td>{$row['site_url']}</td>
          <td>{$row['comment']}</td>
          <td>$maskedPass</td>
        </tr>
        ";
    }
} else {
    //Informs the user no results were found if the resulting array is empty
    echo "
      <tr>
        <td colspan='5'><em>No results found</em></td>
      </tr>
    ";
}

echo "
  </tbody>
</table>
";
}

// Inform the user they successfully cleared the results upon clicking Clear Results
if (isset($_POST["clear_results"])) {
    echo "<p>Results cleared.</p>";
}

?>
  </body>
</html>
