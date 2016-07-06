<?php

    // Script 12.9 - edit_entry.php | WEBD166 Bob Painter
    // This file is similar to delete_entry.php and will also be linked from the view_entries.php script (when a person clicks Edit).
    // A form will be displayed with the blog entry information, allowing for those values to be changed. Upon submitting the form, if
    // the data passes all of the validation routines, an UPDATE query will be run to update the database.
    
    // Connect to the database:
    
    include('includes/header.php');
    include('mysqli_connect.php');

    ?>
    
    <h1>Edit a Blog Entry</h1>
    
    <?php

    // This view_entries.php file will provide links to pages that will allow you to edit or delete an existing blog post record. The
    // links will pass the entry ID to the handling pages. This file is the handling page for the Edit link on the view_entries.php page.
    
    // If the page received a valid entry ID in the URL, define and execute a SELECT query
    
    if ( isset($_GET['id']) && is_numeric($_GET['id']) ) {
        
        // The SELECT statement selects the two column values from the database for the provided ID value.
        $query = "SELECT title, entry FROM entries WHERE entry_id={$_GET['id']}";
        $result = @mysqli_query($dbc, $query);
        
        // Retrieve the blog record, and display the entry in a form:
        if ($result) {
            $row = mysqli_fetch_array($result);
        
        // htmlentities does everything that htmlspecialchars does but goes further and encodes accent characters and symbols into their
        // HTML entity.
        
        // Make the form:
        
        echo '<form action="edit_entry.php" method="post">
              <input type="text" name="title" size="40" maxlength="100" placeholder="Entry Title:" value="' . htmlentities($row['title']) . '"/>
              <textarea name="entry" placeholder="Entry Text:" cols="40" rows="5">' . htmlentities($row['entry']) . '</textarea>
              <input type="hidden" name="id" value="' . $_GET['id'] . '">
              <input type="submit" name="submit" value="Update this Entry!">
              </form>';
        
    } else {
        
        // Couldn't get the information
        echo '<p class="alert-danger">Could not retrieve the blog entry because:<br>' . 
        mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
    }
        
    // Check for submission of the form. This conditional will be TRUE when the form is submitted.
        
    } elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
        
        // Validate and secure the form data:
        $problem = FALSE;
        
        if (!empty($_POST['title']) && !empty($_POST['entry'])) {
            
            // mysqli_real_escape_string(), makes data safe to use in queries. The strip_tags() function completely removes any HTML,
            // JS, or PHP tags. It's therefore the most foolproof function to use on submitted data.
        
            $title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])) );
            $entry = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['entry'])) );
        } else {
            echo '<p class="alert-danger">Please submit both a title and an entry.</p>';
            $problem = TRUE;
        }
        
    if (!$problem) {
        
        // Update the database. The query updates two fields - title and entry - using the values submitted by the form. This system
        // works because the form is preset with the exising values.
        
        $query = "UPDATE entries SET title ='$title', entry='$entry' WHERE entry_id={$_POST['id']}";
        
        // Execute the query
        $result = @mysqli_query($dbc, $query);
        
        // Report on the results of the update. The mysqli_affected_rows() function will return the number of rows in the database
        // affected by the most recent query.
        if (mysqli_affected_rows($dbc) == 1) {
            echo '<p class="alert-success">The blog entry has been updated.</p>';
            echo '<p><a href="view_entries.php">Go back and view entries</a></p>';
        } else {
            echo '<p class="alert-danger">Could not update the blog entry because:<br>'
            . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
        }
    } // No problem!
        
        } else { // No ID set
            echo '<p style="color: red;">This page has been accessed in error.</p>';
    } // End of Main IF
    
    // Close the connection
    mysqli_close($dbc);
    include('includes/footer.php');
?>
