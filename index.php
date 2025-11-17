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

    <!-- Update Form -->
    <form action="index.php" method="post">
      <div>
        <label for="update_site_name">Site Name:</label>
        <input type="text" id="update_site_name" name="update_site_name" placeholder="Targeted Site Name" required>
      </div>
      <div>
        <label for="new_url">New URL:</label>
        <input type="text" id="new_url" name="new_url" placeholder="Updated URL" required>
      </div>
      <div>
        <button type="submit" name="submit_update">Update URL</button>
      </div>
    </form>

    <!-- Insert Form -->
    <form action="index.php" method="post">
      <div>
        <label for="site_name">Site Name:</label>
        <input type="text" id="site_name" name="site_name" placeholder="Netflix" required>
      </div>
      <div>
        <label for="url">URL:</label>
        <input type="text" id="url" name="url" placeholder="https://www.netflix.com" required>
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
      </div>
      <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Username" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <div>
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" placeholder="Comment"></textarea>
      </div>
      <div>
        <button id="insertButton" type="submit" name="insert">Insert</button>
      </div>
    </form>

    <!-- Delete Form -->
    <form action="index.php" method="post">
      <div>
        <label for="delete_site_name">Site Name:</label>
        <input type="text" id="delete_site_name" name="delete_site_name" placeholder="Site Name to Delete" required>
      </div>
      <div>
        <button type="submit" name="submit_delete">Delete Entry</button>
      </div>
    </form>

<?php
require_once "includes/helpers.php";

// Search every component of the database and display all matching tuples in an HTML table
if (isset($_POST["submit_search"])) {

    // Ex: if user types max the $keyword becomes %max%
    $keyword = "%" . $_POST["search"] . "%";

    // Call helper function to search tuples in the database
    $foundTuples = searchTuples($keyword);

    // HTML table to display results and failed search queries
    echo "
    <table>
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

    /*
    Display each row and show the decrypted password
    to verify AES encryption/decryption is working
    */
    if ($foundTuples) {
        foreach ($foundTuples as $entry) {
            echo "
            <tr>
              <td>{$entry['username']}</td>
              <td>{$entry['site_name']}</td>
              <td>{$entry['site_url']}</td>
              <td>{$entry['comment']}</td>
              <td>{$entry['decrypted_password']}</td>
            </tr>
            ";
        }
    } else {
        // Informs the user no results were found if the resulting array is empty
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
    echo "<p>Results cleared</p>";
}

// Update a site's URL using another component (site name) as a pattern match
if (isset($_POST["submit_update"])) {
    $update_site_name = $_POST["update_site_name"];
    $new_url = $_POST["new_url"];

    $result = updateSiteUrl($update_site_name, $new_url);

    // If at least one row was updated the user will see a success message
    if ($result > 0) {
        echo "<p>URL successfully updated for: $update_site_name</p>";
    } else {
        echo "<p>Update failed. Site Name may not exist or match in the database.</p>";
    }
}

// Insert a new tuple
if (isset($_POST["insert"])) {
    $site_name = $_POST["site_name"];
    $url = $_POST["url"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $comment = $_POST["comment"];

    $insert = insertTuple($site_name, $url, $email, $username, $password, $comment);

    // Redirect to the same page to prevent duplicate form submissions
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// Delete a tuple based on a pattern match with site name
if (isset($_POST["submit_delete"])) {
    $site_name = $_POST["delete_site_name"];
    $delete = deleteTuple($site_name);

    if ($delete > 0) {
        echo "<p>Successfully deleted entry for: $site_name</p>";
    } else {
        echo "<p>Failed to delete: Site name may not exist.</p>";
    }
}
?>
  </body>
</html>
