<!DOCTYPE html>
<html lang="en">
   <?php
      include 'includes/head.php';
      
      // Get the current date if no parameters are provided
      $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : (isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d 00:00:00'));
      $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : (isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d 23:59:59'));

      // Define the query to get total revenue and commission
      $total_revenue_commission_query = "
         SELECT 
            COALESCE(SUM(CASE WHEN q.status_type = 3 THEN st.service_cost ELSE 0 END), 0) AS total_revenue,
            COALESCE(SUM(CASE WHEN q.status_type = 3 THEN st.service_cost * st.service_commission / 100 ELSE 0 END), 0) AS total_commission 
         FROM 
            queue q
         LEFT JOIN 
            service_type st ON q.service_type = st.id
         WHERE 
            q.in_time BETWEEN '$start_date' AND '$end_date' 
         AND q.status_type = 3;";
      
      // Execute the query
      $total_res_com = mysqli_query($con, $total_revenue_commission_query);
      
      // Fetch the result
      if ($row = mysqli_fetch_assoc($total_res_com)) {
         $total_revenue = $row['total_revenue'];
         $total_commission = $row['total_commission'];
      } else {
         $total_revenue = 0;
         $total_commission = 0;
      }

      $total_expenses_query =  "
         SELECT 
            SUM(expense_cost) AS total_expense
         FROM 
            expenses
         WHERE 
            created_at BETWEEN '$start_date' AND '$end_date'";

      $total_expenses = mysqli_query($con, $total_expenses_query);
      // Fetch the result
      if ($row = mysqli_fetch_assoc($total_expenses)) {
         $total_expense = $row['total_expense'];
      } else {
         $$total_expense = 0;
      }
      // Define the query to get the total number of staff
$total_staff_query = "SELECT COUNT(*) AS total_staff FROM staff";

// Execute the query
$total_staff_result = mysqli_query($con, $total_staff_query);

// Fetch the result
if ($row = mysqli_fetch_assoc($total_staff_result)) {
    $total_staff = $row['total_staff'];
} else {
    $total_staff = 0;
}  
// Define the query to get the total count of queue entries based on status type
$total_queue_status_query = "
    SELECT status_type, COUNT(*) AS total_count 
    FROM queue 
    GROUP BY status_type";

// Execute the query
$total_queue_status_result = mysqli_query($con, $total_queue_status_query);

// Initialize an array to hold the results
$total_queue_status = array();

// Fetch the results
while ($row = mysqli_fetch_assoc($total_queue_status_result)) {
    $total_queue_status[$row['status_type']] = $row['total_count'];
}
$completed = 0;
$initialized = 0;
$dispatched = 0;
$on_hold = 0;
$in_progress = 0;

// Output the total count based on status type
foreach ($total_queue_status as $status => $count) {
   if($status == 3){
      $completed = $count;
   }elseif($status == 2){
      $in_progress = $count;
   }elseif($status == 0){
      $on_hold = $count;
   }elseif($status == 1){
      $initialized = $count;
   }elseif($status==4){
      $dispatched = $count;
   }
}

   ?>
   <body>
      <div class="wrapper">
         <?php include 'includes/nav.php';?>
         <div class="main">
            <?php include 'includes/navtop.php';?>
            <main class="content">
               <div class="container-fluid p-0">
                  <div class="row text-center ">
                     <div class="col-lg-3  col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>TOTAL REVENUE</h4>
                           <p>
                              <strong>KSH <?php echo number_format($total_revenue, 2);?> </strong>
                           </p>
                        </div>
                     </div>
                  
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>TOTAL COMMISSION</h4>
                           <p>
                              <strong>KSH <?php echo number_format($total_commission, 2); ?></strong>
                           </p>
                        </div>
                     </div>

                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>TOTAL EXPENSE</h4>
                           <p>
                              <strong>KSH <?php echo number_format($total_expense, 2); ?></strong>
                           </p>
                        </div>
                     </div>

                     <div class="col-lg-3 col-md-4  col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>ESTIMATED PROFIT</h4>
                           <p>
                              <strong>KSH <?php echo number_format(($total_revenue-($total_commission +$total_expense)), 2); ?></strong>
                           </p>
                        </div>
                     </div>

                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>STAFFS</h4>
                           <p>
                              <strong><?php echo $total_staff?></strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>COMPLETED</h4>
                           <p>
                              <strong><?php echo $completed?></strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>ON HOLD</h4>
                           <p>
                              <strong><?php echo $on_hold?></strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>IN PROGRESS</h4>
                           <p>
                              <strong><?php echo $in_progress?></strong>
                           </p>
                        </div>
                     </div>                  
                  </div>
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0">Stats for <?php echo date("Y");?></h5>
                           </div>
                           <div class="card-body">
                              <canvas id="revenueChart"></canvas>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0">Stats for <?php echo date("Y");?></h5>
                           </div>
                           <div class="card-body">
                              <canvas id="myChart"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            <?php include 'includes/footer.php';?>
         </div>
      </div>
      <?php include 'includes/scripts.php';?>
      <?php include 'includes/loadBarChart.php';?>
   </body>
</html>