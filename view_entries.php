<?php

    include('includes/header.php');

    // Script 12.7 - view_entries.php | WEBD166 Bob Painter
        // This script retrieves blog entries from the databse.
    
    // Connect to the database
    include('mysqli_connect.php');
    
    $query = 'SELECT * FROM entries ORDER BY date_entered DESC';
    
    // Could also use this:
    // $result = mysqli_query($dbc, $query);
    
    // Run the query:
    
    if ($result = mysqli_query($dbc, $query)) {
    
    // Use a while loop to retrieve multiple records and loop through the results using mysqli_fetch_array() and retrieve and
    // display every record in your database:
    
    // The file displays data in the web page using two print statements:
    
    // The print statement creates an HTML link tag on the web page. This link has a URL that contains a query string. The question
    // mark is used as a separator and is not part of the query string. It adds an HTML variable called id, which points to the
    // entry_id value to the view_entries web page. It uses the entry_id value of the data record as the actual link text. (view source)
    
        while ($row = mysqli_fetch_array($result)) {
            print '<p><h3>' . $row['title'] . '</h3>' . $row['entry'] . "<br><br>
                   <a href=\"edit_entry.php?id={$row['entry_id']}\">Edit</a>
                   <a href=\"delete_entry.php?id={$row['entry_id']}\">Delete</a>
                   </p><hr>\n";
        }
      
    // If the query did not successfully run, some sort of MySQL error must have occured.
    // To find out what the error was, call the mysqli_error() function:
        
    } else {
        
        print '<p style="color: red;">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '.</p>
               <p>The query being run was ' . $query . '</p>';
        
    }
    
    // This function call is not required, because PHP will automatically close the connection at the end of a script, but it does
    // make for good programming form to incorporate it.
    
    mysqli_close($dbc);
    
    include('includes/footer.php');

    ?>


