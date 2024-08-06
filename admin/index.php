<!DOCTYPE html>
<html lang="en">
   <?php
      include 'includes/head.php';
      // Connect to the database
      // include 'includes/db_connect.php';
      
      // Get the current date if no parameters are provided
      $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : (isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d 00:00:00'));
      $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : (isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d 23:59:59'));
      echo $start_date;
      echo $end_date;

      // Define the query to get total revenue and commission
      $total_revenue_commission_query = "
         SELECT 
            SUM(st.service_cost) AS total_revenue,
            SUM(st.service_cost * st.service_commission / 100) AS total_commission
         FROM 
            queue q
         LEFT JOIN 
            service_type st ON q.service_type = st.id
         WHERE 
            q.in_time BETWEEN '$start_date' AND '$end_date'";
      
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
                     <div class="col-lg-3 col-md-4  col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>ESTIMATED PROFIT</h4>
                           <p>
                              <strong>KSH <?php echo number_format(($total_revenue-$total_commission), 2); ?></strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>STAFFS</h4>
                           <p>
                              <strong>10</strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>ON HOLD</h4>
                           <p>
                              <strong>10</strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>INITIALIZED</h4>
                           <p>
                              <strong>12</strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>IN PROGRESS</h4>
                           <p>
                              <strong>6</strong>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card p-2" style="border-bottom:3px solid aqua;">
                           <h4>COMPLETED</h4>
                           <p>
                              <strong>3</strong>
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