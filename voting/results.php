<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("includes/conn.php")
// No session or access control needed for this public page

// HTML code for the public page
?>

<!DOCTYPE html>
<html>
<head>
    <title>Public Voting Result Page</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container">
    <h1>Welcome to the Public Page for viewing election results</h1>
    <!-- Content visible to all users -->
     <div class="row">
        <div class="col-xs-12">
          <h3>Votes Tally
            <span class="pull-right">
              <a href="votingsystem.dynamickeystone.com.ng/admin/print.php?file=print.pdf" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span>  View Result</a>
            </span>
          </h3>
        </div>
      </div>

      <?php
// Get the file name from the URL query string
if(isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = 'pdfs/' . $file;

    // Check if the file exists
    if(file_exists($filepath) && is_readable($filepath)) {
        // Set appropriate headers for PDF rendering
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $file . '"');
        
        // Output the PDF file content
        readfile($filepath);
        exit;
    } else {
        // Handle if the file doesn't exist or is not readable
        echo 'File not found.';
    }
} else {
    // Handle if no file is specified in the URL
    echo 'No file specified.';
}
?>

    <table class="table table-bordered" style="margin-top: 1rem">
        <tr>
            <thead>
            <th>Name</th>
            <th>Votes</th>
            </thead>
        </tr>
        <tbody>
     <?php

        $sql = "SELECT count(votes.voters_id) as votes, votes.candidate_id, candidates.firstname, candidates.lastname  FROM candidates  LEFT JOIN votes on candidates.id = votes.candidate_id  group by votes.candidate_id";
        $querys = $conn->query($sql);
        while($row = $querys->fetch_assoc()){
            echo "
            <tr>
                <th>". $row['firstname'] . " " . $row['lastname']. "</th>
                <th>". $row['votes'] ."</th>
            </tr>";


        }
      ?>
        </tbody>
    </table>

</body>
</html>
