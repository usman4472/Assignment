<?php 
$message = '';
$emailmessage = '';
$data=array();
if(isset($_POST["import"]))
{
	$dbname = $_POST['database_name'];
	$username = $_POST['full_name'];
	$email = $_POST['email'];
	$con = mysqli_connect("localhost", "root", "");

	// Check connection
		if ($con->connect_error) {
		    die("Connection failed: " . $con->connect_error);
		} 


	// Create database
		$sql = "CREATE DATABASE IF NOT EXISTS ". $_POST['database_name'];
		if ($con->query($sql) === TRUE) 
		{

				if($_FILES["sqlfile"]["name"] != '')
				{
				  $array = explode(".", $_FILES["sqlfile"]["name"]);
				  $extension = end($array);
					if($extension == 'sql')
					{

					 mysqli_select_db ($con, $dbname)or 
					 die($dbname);

						// Check connection
						if (!$con) {
						    die("Connection failed: " . mysqli_connect_error());
						}   
						else
						{
						  $output = '';
						  $count = 0;
						  $file_data=file($_FILES["sqlfile"]["tmp_name"]);
						   foreach($file_data as $row)
						   {
						     $start_character = substr(trim($row), 0, 2);
							    if($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '')
							    {
							     $output = $output . $row;
							     $end_character = substr(trim($row), -1, 1);
								    if($end_character == ';')
								    {
									    if(!mysqli_query($con, $output))
									    {
									       $count++;
									    }
									      $output = '';
								    }
							    }
						    }
							if($count > 0)
							{
							    $message = '<label class="text-danger">There is an error in Database Import</label>';
							}
							else
							{
							    $message = '<label class="text-success">SQL File Successfully Imported</label>';

							    $query = "SELECT * from `tbl_contact`";
 

							//loop the fetch and concactenate into string
								if ($result = mysqli_query($con, $query)) {
								        
								        $data[] = array('Id','FirstName', 'LastName', 'Address', 'Email', 'Phone', 'DateOfBirth');

								        while ($row = mysqli_fetch_assoc($result)) {
								      	$data[] = array($row['id'],$row['first_name'],$row['last_name'],$row['address'].$row['email'],$row['phone'],$row['date_of_birth']);
								      	}
				
								    $result->free();
								}
									$emailmessage = '<label class="text-success">Emailing Now.....</label>';
									 $resp=send_csv_mail($data, "Here is Today's Report:", $email);
									 
									if( $resp ){
									$emailmessage = '<label class="text-success">Email sent</label>';
									} else {
									$emailmessage = '<label class="text-danger">Email not sent </label>';
									}
									
							}
						} 

				    }
				    else
				    {
				   		$message = '<label class="text-danger">Invalid File</label>';
				    }
				}
				else
				{
				  $message = '<label class="text-danger">Please Select Sql File</label>';
				}
	                                   
		} 
		else 
		{
		    echo "Error creating database: " . $con->error;
		}


}

function create_csv_string($data2) {

  // Open temp file pointer
  if (!$fp = fopen('php://temp', 'w+')) return FALSE;

  // Loop data and write to file pointer
  foreach ($data2 as $line) fputcsv($fp, $line);

  // Place stream pointer at beginning
  rewind($fp);

  // Return the data
  return stream_get_contents($fp);

}


function send_csv_mail ($csvData, $body, $to,  $from = 'musman4472@gmail.com',$subject = 'your query result email with attachment' ) {

  // This will provide plenty adequate entropy
  $multipartSep = '-----'.md5(time()).'-----';

  // Arrays are much more readable
  $headers = array(
    "From: $from",
    "Reply-To: $from",
    "Content-Type: multipart/mixed; boundary=\"$multipartSep\""
  );


  // Make the attachment
 
 $attachment = chunk_split(base64_encode(create_csv_string($csvData))); 

  // Make the body of the message
  $body = "--$multipartSep\r\n"
        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
        . "Content-Transfer-Encoding: 7bit\r\n"
        . "\r\n"
        . "$body\r\n"
        . "--$multipartSep\r\n"
        . "Content-Type: text/csv\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"AFILE.csv\"\r\n"
        . "\r\n"
        . "$attachment\r\n"
        . "--$multipartSep--";

   // Send the email, return the result
   return @mail($to, $subject, $body, implode("\r\n", $headers)); 

}

?>

<!DOCTYPE html>  
<html>  
 <head>  
  <title>Import SQL File in Mysql Database</title>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
 </head>  
 <body>  
  <br /><br />  
  <div class="container" style="width:700px;">  
   <h3 align="center">Import SQL File in Mysql Database</h3>  
   <br />
   <div><?php echo $message; ?></div>
   <div><?php echo $emailmessage; ?></div>
   <form method="post" enctype="multipart/form-data">
   	<div class="form-group">
   		<label>Your Full Name</label>
   	<input type="text" name="full_name" class="form-control" required placeholder="Enter Your Name">
   	</div>
   	<div class="form-group">
   		<label>Your Email Address</label>
   	<input type="email" name="email" class="form-control" required placeholder="Enter Your Email Address">
   	</div>
   	<div class="form-group">
   		<label>Your Database Name  (only accept alphabet, numeric and underscore)</label>
   		<input type="text" name="database_name" class="form-control" pattern="^[a-zA-Z0-9_.-]*$" required placeholder="Enter Your Database Name either it created or not">
   	</div>
    <div class="form-group">
    <label>Select Sql File</label>
    <input type="file" name="sqlfile" />
	</div>
    <br />
    <input type="submit" name="import" class="btn btn-info" value="Import" />
   </form>
  </div>  
 </body>  
</html>
