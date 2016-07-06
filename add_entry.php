<?php

  include('includes/header.php');

?>
    <h1>Add a Blog Entry</h1>

    <?php // Script 12.5 - add_entry.php | WEBD166 Bob Painter
          // This script adds a blog entry to the database.
    
    // Check if the form has been submitted using the $_SERVER array and using the element we want to check called "Request Method".
    // If the Request Method is post, we want to execute the block of code in the conditional IF statement. We'll read the information
    // from the submitted form. Use the double equal signs to compare the two values. If the Request Method is NOT Post then we well
    // skip over this block of code and the register form will be displayed as normal.
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Connect to the database. Use include('mysqli_connect.php') instead of require('mysqli_connect.php')
        include('mysqli_connect.php');
        print '<p>Hello!</p>';
        
        // Validate the form data. Use flag variable $problem:
        $problem = FALSE;
        
        // The mysquli_real_escape_string() funciton escapes special characters (such as aspostrophes in names) in a string for use
        // in an SQL string. For security purposes, mysqli_real_escape_string should be used on every text input in a form.
        
        // Apply strip_tags() to prevent cross-site scripting attacks. This function removes all HTML and PHP tags that users may
        // input into form fields. (page 100)
        
        // Always escape output.
        
        if (!empty($_POST['title']) && !empty($_POST['entry'])) {
            
        // Need to change the order of the arguments:
            $title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])) );
            $entry = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['entry'])) );
            
        } else {
            echo '<p class="alert-danger">Please submit both a title and an entry.';
            $problem = TRUE;
        }
        
        if (!$problem) {
            
        // Define the query. Because of the way auto-incrementing primary keys work, this query is also fine. (TIP #3 page 357)
            
            $query = "INSERT INTO entries
                      (title, entry, date_entered)
                      VALUES
                      ('$title', '$entry', NOW() )";
        
            if (@mysqli_query($dbc, $query)) {
                echo '<p class="alert-success">The blog entry has been added!</p>';
            } else {
                echo '<p class="alert-danger">Cound not add the entry because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
        }
        
        mysqli_close($dbc);
        
    }
    
    // Display the form:
    
    ?>

        <!-- As a good rule of thumb, use the same name for your form inputs as the corresponding column names in the database. (page 357) -->

        <form action="" method="post">
            <input type="text" name="title" size="40" maxsize="100" placeholder="Entry Title:">
            <textarea name="entry" cols="40" rows="5" placeholder="Entry Text:"></textarea>
            <input type="submit" name="submit" value="Post This Entry!">
        </form>

        <?php include('includes/footer.php'); ?>
