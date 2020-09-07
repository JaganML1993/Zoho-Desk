<?php
  session_start();
  $useremail = $_SESSION['useremail']; 

  require_once('connection.php');

  if($useremail != ''){

    $qry_user = mysqli_query($conn, "SELECT name FROM user WHERE email = '$useremail' ");
    $row_user = mysqli_fetch_assoc($qry_user);
    $username = $row_user['name'];
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
        <div class="col-md-12">
          <div class="card disp_tickets">
            <div class="row">
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Ticket ID: </b></label> T002</span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Created On: </b></label> 07 sep 2020 01:30 PM</span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Ticket Status: </b></label> Open</span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Subject: </b></label> Second Ticket</span>
              </div>
              <div class="col-md-8">
                <span class="text-secondary"><label><b>Description: </b></label> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua.</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="card disp_tickets">
            <div class="row">
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Ticket ID: </b></label> T001</span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Created On: </b></label> 07 sep 2020 12:30 PM</span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Ticket Status: </b></label> In Progress</span>
              </div>
              <div class="col-md-4">
                <span class="text-secondary"><label><b>Subject: </b></label> First Ticket</span>
              </div>
              <div class="col-md-8">
                <span class="text-secondary"><label><b>Description: </b></label> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
              </div>
            </div>
          </div>
        </div>
        
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
                    <option value="Customer relationship management">Customer relationship management</option>
                    <option value="Research and development">Research and development</option>
                    <option value="Support">Support</option>
                    <option value="Marketing">Marketing</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Category</label>
                  <select name="category" class="form-control" required="">
                    <option value="">Please Select</option>
                    <option value="Big Problem">Big Problem</option>
                    <option value="Small Problem">Small Problem</option>
                    <option value="Other Problem">Other Problem</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Subject</label>
                  <input type="text" name="subject" class="form-control" required="">
                </div>
                <div class="form-group col-md-4">
                  <label>Priority</label>
                  <select name="priority" class="form-control" required="">
                    <option value="">Please Select</option>
                    <option value="Low">Low</option>
                    <option value="Normal">Normal</option>
                    <option value="Hign">Hign</option>
                    <option value="Urgent">Urgent</option>
                  </select>
                </div>
                <div class="form-group col-md-8">
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
              <button type="submit" class="btn btn-success btn-sm">Submit</button>
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