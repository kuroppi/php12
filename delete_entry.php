
    <?php // Script 12.8 - delete_entry.php | WEBD166 Bob Painter
    
    include('includes/header.php');
    include('mysqli_connect.php');
    
    ?>
    
    <h1>Delete an Entry</h1>
    
    <?php
    
    // This page receives the database record ID in the URL. It then displays the entry to confirm that the user wants to delete it.
    // If the user clicks the delete link, the record will be deleted.
    
    // Check for a valid user ID, through GET or POST:
    
    // To display the blog entry, the page must confirm that a numeric ID is received by the page. Because that value should come
    // first in the URL (when the user clicks the link in view_entries.php), reference the $_GET['id'].
    
    // HTML variable called id points to the entry_id value of the data record. The query string uses the GET method.
    
    if ( isset($_GET['id']) && is_numeric($_GET['id']) ) {
        
        // Define the query:
        $query = "SELECT title, entry FROM entries WHERE entry_id={$_GET['id']}";
        $result = @mysqli_query($dbc, $query);
        
        if ($result) {
            
            // Get the blog entry_id
            $row = mysqli_fetch_array($result);
            
            // Make the form:
            
            // The array will use the selected column names as its indexes: $row['title'], $row['entry]. As with any array, you
            // must refer to the columns exactly as they're defined in the database.
            
            print '<div class="delete"><form action="delete_entry.php" method="post">
                    <p>Are you sure you want to delete this entry?</p>
                    <h3>' . $row['title'] . '</h3>' . $row['entry'] . '<br>
                    <input type="hidden" name="id" value="' . $_GET['id'] . '">
                    <input class="input-delete" type="submit" name="submit" value="Delete this Entry!"></p>
                   </form></div>';
            
        } else {
            
            // Couldn't get the information:
            print '<p class="alert-danger">Could not retrieve the blog entry because:<br>' .
            mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
        }
    
    // Check for submission of the form
        
    } elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
    
        // The DELETE SQL command permanently removes a record (or records) from a table.
        // To delete only a single record from a table, add the LIMIT clause to the query.
        $query = "DELETE FROM entries WHERE entry_id={$_POST['id']} LIMIT 1";
    
        // Execute the query:
        $result = @mysqli_query($dbc, $query);
        
        // To see if a DELETE query worked, use the mysqli_affected_rows() function. The mysqli_affected_rows() function returns the
        // number of rows altered by the most recent query. If the query ran properly, one row was deleted, so this function should
        // return 1. If no, a message is printed.
        
        if (mysqli_affected_rows($dbc) == 1) {    
            print '<p class="alert-success">The blog entry has been deleted.</p>';
            print '<p><a href="view_entries.php">Go back and view entries</a></p>';
            
        } else {
            
            // Otherwise the MySQL error and query are printed for debugging purposes.
            print '<p class="alert-danger">Did not delete the blog entry because:<br>'
            . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
        }
        
        } else {
        
        // If no numeric ID value was passed to this page using either the GET method or the POST method, then this else clause takes
        // effect:
        print '<p style="color: red;">This page has been accessed in error.</p>';
    }
    
    mysqli_close($dbc);
    include('includes/footer.php');

?>

    
    