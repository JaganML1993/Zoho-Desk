<?php
  session_start();
  $useremail = $_SESSION['useremail']; 
  require_once('connection.php');

  if($useremail != ''){

    $qry_user = mysqli_query($conn, "SELECT name FROM user WHERE email = '$useremail' ");
    $row_user = mysqli_fetch_assoc($qry_user);
    $username = $row_user['name'];

    $auth_key = "9446933330c7f886fbdf16782906a9e0";
    $org_id = "60001280952";

    function contactidByMail($useremail, $auth_key, $org_id){

      $fetch_contacts = "https://desk.zoho.in/api/v1/contacts/search?email=".$useremail;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$fetch_contacts);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$auth_key , 'orgId:'.$org_id) );
      $result=curl_exec($ch);
      curl_close($ch);
      $contact_data = json_decode($result, true);

      $contactId = $contact_data['data'][0]['id'];
      return $contactId;
    }
    $contactId = contactidByMail($useremail, $auth_key, $org_id);
    
    function fetchTickets($contactId, $auth_key, $org_id){

      $fetch_url = "https://desk.zoho.in/api/v1/contacts/".$contactId."/tickets?include=departments,team,assignee";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$fetch_url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$auth_key , 'orgId:'.$org_id) );
      $result=curl_exec($ch);
      curl_close($ch);
      $ticket_data = json_decode($result, true);
      return $ticket_data;
    }
    
    function getTicketById($ticketID, $auth_key, $org_id){

      $description_url = "https://desk.zoho.in/api/v1/tickets/".$ticketID;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$description_url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$auth_key , 'orgId:'.$org_id) );
      $result=curl_exec($ch);
      curl_close($ch);
      
      $description_data = json_decode($result, true);
      return $description_data;
    }

    function fetchDepartments($auth_key, $org_id){

      $dept_url = "https://desk.zoho.in/api/v1/departments?isEnabled=true";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$dept_url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$auth_key , 'orgId:'.$org_id) );
      $result=curl_exec($ch);
      curl_close($ch);
      $department_data = json_decode($result, true);
      return $department_data;
    }

    //adding ticket to ZOHO DESK
    if(isset($_POST['submit_ticket'])){

      $create_url = "https://desk.zoho.in/api/v1/tickets";

      $department_arr = explode('|', $_POST['department']);
      $departmentId = $department_arr[0];
      $departmentName = $department_arr[1];

      $category = $_POST['category'];
      $priority = $_POST['priority'];
      $subject = $_POST['subject'];
      $description = $_POST['description'];
      $email = $_POST['email'];

      $post_data = [
                "email" => $email,
                "subject" => $subject,
                "description"  => $description,
                "status" => "Open",
                "category" => $category,
                "priority" => $priority,
                "channel" => "Web",
                "departmentId" => $departmentId,
                "contactId" => $contactId,
                "department" => [
                    "id" => $departmentId,
                    "name" => $departmentName 
                  ]
            ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $create_url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$auth_key , 'orgId:'.$org_id) );

      $response = curl_exec($ch);
      // $response = json_decode($response);
      // var_dump($response);

      if($response){
        echo "<script>alert('Ticket Created!'); window.location.href = 'tickets.php'; </script>";
      }

    }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Ticket Management</title>

    <style type="text/css">
      .disp_tickets{
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
      }
    </style>
  </head>
  <body>
    <div class="card">
      <div class="card-header text-secondary">
        <b>TICKETS</b>
      </div>
      <div class="card-body">
        <button class="btn btn-primary btn-sm" style="float: right;" data-toggle="modal" data-target=".add_ticket_modal">Add Ticket</button>
      </div>
      <!-- displying tickets -->
      <?php 

      $ticket_data = fetchTickets($contactId, $auth_key, $org_id);
      if(count($ticket_data) > 0){
      foreach ($ticket_data['data'] as $data_arr) {

          $ticketID = $data_arr['id'];
          $email = $data_arr['email'];
          $subject = $data_arr['subject'];
          $department = $data_arr['department']['name'];
          $ticketNumber = $data_arr['ticketNumber'];
          $createdTime = date('d M Y h:i A', strtotime($data_arr['createdTime']));
          $status = $data_arr['status'];
          // fetching description from individul ticket data 
          $ticket_details = getTicketById($ticketID, $auth_key, $org_id);
          $description = $ticket_details['description'];
      ?>
        <div class="col-md-12">
          <div class="card disp_tickets">
            <div class="row">
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Ticket ID: </b></label> <?= $ticketNumber ?></span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Created On: </b></label> <?= $createdTime ?></span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Ticket Status: </b></label> <?= $status ?></span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Subject: </b></label> <?= $subject ?></span>
              </div>
              <div class="col-md-8">
                <span class="text-secondary"><label><b>Description: </b></label> <?= $description ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php 
        } // foreach end
      }else{ ?>
        <h5 class="text-center text-secondary">No Tickets Found.</h5>
      <?php } ?>
        <!-- displaying ticket ends -->
        <br>
    </div>

    <!-- add ticket modal -->
    <div class="modal add_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header" style="background: #f7f7f7; ">
              <h5 class="modal-title">Creating New Ticket</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST">
            <div class="modal-body">
              <h6>Ticket Information</h6>
              <hr>
              <div class="row">
                <div class="form-group col-md-4">
                  <label>Department</label>
                  <select name="department" class="form-control" required="">
                    <option value="">Please Select</option>
                    <?php 
                      $department_data = fetchDepartments($auth_key, $org_id);

                      foreach ($department_data['data'] as $arr_dept) {
                        $department_id = $arr_dept['id'];
                        $department_name = $arr_dept['name'];
                     ?>
                    <option value="<?= $department_id.'|'.$department_name ?>"><?= $department_name ?></option>
                    <?php 
                      } //foreach end
                     ?>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Category</label>
                  <select name="category" class="form-control" required="">
                    <option value="">Please Select</option>
                    <option value="NEW Project CI/CD Pipeline Setup">NEW Project CI/CD Pipeline Setup</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Priority</label>
                  <select name="priority" class="form-control" required="">
                    <option value="">Please Select</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="Hign">Hign</option>
                  </select>
                </div>
                <div class="form-group col-md-12">
                  <label>Subject</label>
                  <input type="text" name="subject" class="form-control" required="">
                </div>
                <div class="form-group col-md-12">
                  <label>Description</label>
                   <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
              </div>
              <hr>
              <h6>Contact Details</h6>
              <hr>
              <div class="row">
                <div class="form-group col-md-4">
                  <label>Name</label>
                  <input type="text" name="name" value="<?= $username ?>" class="form-control" required="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Email</label>
                  <input type="email" name="email" value="<?= $useremail ?>" class="form-control" required="" readonly>
                </div>
              </div>

            </div>
            <div class="modal-footer" style="background: #f7f7f7; ">
              <button type="submit" class="btn btn-success btn-sm" name="submit_ticket">Submit</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
<?php }else{
  header('location:index.php');
} ?>