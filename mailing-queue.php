$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "testing";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
function send_mail()
{
  		//fetching record of manager who has status is active 
 	    $activeMailerData=getActiveCurrentMailer();
 	    //get record to check who had status is active previously
        $lastActiveMailerData=fetchLastActiveMailerData();
        $id=$activeMailerData['m_id'];//current active mailer id
        $mailTo=$activeMailerData['email'];
        if($lastActiveMailerData['m_id'] == $id)
        {
    	    $nextMailerId=1;//if current rec is the last row in the table,then active first one for countinue loop.
        }
        else
        {
    	    $nextMailerId=++$id;
        }
        activeNextMailer($nextMailerId,$tbl_name);
        if($mailTo != "")
        {
	        $to=$mailTo;
	        $name = $postData['user_name'];
		    $phone = $postData['user_phone'];
	        $email = $postData['user_email'];
	        $user_state = $postData['user_state'];
			$user_domain = $postData['user_domain'];
			$user_qualification = $postData['user_qualification'];
	        $subject = $name;
	        $mailBody = "<html><head></head>"
	                . "<body><p>Dear Team,</p>"
	                . "<p>New Enquiry Request By User,please contact with him soon...!!</p>"
	                . "<div style='width:350px !important;border:1px solid black !important;padding:15px !important;'>"
					. "<p>Name : <b>" . $name . "</b></p>"
					. "<p>Phone Number : <b>" . $phone . "</b></p>"
	                . "<p>Email : <b>" . $email . "</b></p>"       
					. "<p>State : <b>" . $user_state . "</b></p>"  
					. "<p>Domain : <b>" . $user_domain . "</b></p>" 
					. "<p>Higher Qualification : <b>" . $user_qualification . "</b></p>" 
					. "</div>"
	                . "</body></html>";
	        $headers = array('Content-Type: text/html; charset=UTF-8');
	        wp_mail($to, $subject, $mailBody, $headers);
        }
  }
  function getActiveCurrentMailer()
  {
  	 global $wpdb;
     $query="select * from manager_email_list where status='current'";
     $result = $conn->query($query);
    // print_r($result);   
     return $result->fetch_assoc();  
     
  }
  function fetchLastActiveMailerData()
  {
  	$conn = new mysqli($servername, $username, $password, $dbname);
    global $wpdb;
    $query="select * from manager_email_list ORDER BY m_id DESC limit 1";
    $result = $conn->query($query);
    return $result->fetch_assoc();   
  }
  function activeNextMailer($nextMailerId)
  {
     global $wpdb;
     $conn = new mysqli($servername, $username, $password, $dbname);
     $query="update manager_email_list SET  status='current'  where m_id = $nextMailerId";
     $conn->query($query);
     $query="update manager_email_list SET  status='sent'  where m_id != $nextMailerId";
     $conn->query($query);   
  }
