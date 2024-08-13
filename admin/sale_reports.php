<!DOCTYPE html>
<html lang="en">
<?php 
    include 'includes/head.php';
    include 'includes/functions.php';

    // Initialize variables for filters
    $staff_filter = "";
    $service_filter = "";
    $status_filter = "";
    $date_filter = "";
    $payment_filter = "";
    $where_conditions = [];
    
    $current_date = date("Y-m-d", strtotime("-1 month"));
    $start_date = $current_date . " 00:00:00";
    $ed = date("Y-m-d");
    $end_date = $ed . " 23:59:59";
    
    // Check if form is submitted
    if (isset($_POST['filter_sales'])) {
        $client_id = $_POST['client_id'] ?? '';
        $staff_id = $_POST['staff_id'] ?? '';
        $service_id = $_POST['service_id'] ?? '';
        $status_id = $_POST['status_id'] ?? '';
        $start_date = $_POST['start_date'] ?? $current_date;
        $end_date = $_POST['end_date'] ?? $ed;
    
        // Apply staff filter
        if (!empty($staff_id)) {
            $where_conditions[] = "q.staff = '$staff_id'";
            $payment_filter = "AND staff_id = '$staff_id'";
        }
        if (!empty($client_id)) {
            $where_conditions[] = "q.client_id = '$client_id'";
        }
        // Apply service filter
        if (!empty($service_id)) {
            $where_conditions[] = "q.service_type = '$service_id'";
        }
    
        // Apply status filter
        if (!empty($status_id)) {
            $where_conditions[] = "q.status_type = '$status_id'";
        }
    
        // Apply date filter
        if (!empty($start_date) && !empty($end_date)) {
            $where_conditions[] = "q.in_time BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
        }
    }
    
    // Build the WHERE clause if there are any conditions
    $where_clause = "";
    if (!empty($where_conditions)) {
        $where_clause = "WHERE " . implode(" AND ", $where_conditions);
    }
    
    $sales_query = "SELECT 
            q.id, 
            q.last_update,
            q.staff, 
            q.payment_method as payment_method,
            e.name as staff_name,
            q.in_time,
            q.out_time,
            q.amount_paid as amount_paid,
            st.type as 'service_type',
            q.status_type as 'status_type',
            s.name as 'status',
            c.first_name as owner_name,
            q.vehicle_number,
            st.id AS service_id,
            st.service_cost AS service_cost,
            st.service_commission AS service_commission,
            COALESCE(SUM(st.service_cost), 0) AS total_revenue,
            COALESCE(SUM(st.service_cost * st.service_commission / 100), 0) AS total_commission,
        CASE 
            WHEN st.service_cost - q.amount_paid > 0 THEN st.service_cost - q.amount_paid 
            ELSE 0 
            END AS total_due,
        CASE 
            WHEN q.amount_paid - st.service_cost > 0 THEN q.amount_paid - st.service_cost 
            ELSE 0 
            END AS total_advance  
        FROM 
            service_type st
        JOIN 
            queue q ON st.id = q.service_type
        JOIN 
            status_type s ON s.id = q.status_type
        JOIN 
            staff e ON e.id = q.staff 
        JOIN 
            clients c ON c.id = q.client_id
            $where_clause
        GROUP BY 
            q.id, 
            q.last_update,
            q.staff, 
            staff_name,
            q.in_time,
            q.out_time,
            st.type,
            q.status_type,
            s.name,
            owner_name,
            q.vehicle_number,
            st.id,
            st.type,
            st.service_cost,
            st.service_commission
        ORDER BY 
            q.last_update DESC";
    
    $sales_result = mysqli_query($con, $sales_query);

    $total_revenue = 0;
    $total_commission = 0;

    $total_due = 0;
    $total_advance = 0;

    while ($row = mysqli_fetch_assoc($sales_result)) {
        $total_revenue += $row['total_revenue'];
        $total_commission += $row['total_commission'];
        $total_due += $row['total_due'];
        $total_advance += $row['total_advance'];
    }

    $staff_query = "SELECT * FROM staff";
    $staffs = mysqli_query($con, $staff_query);

    $service_query = "SELECT * FROM service_type";
    $service = mysqli_query($con, $service_query);

    $status_query = "SELECT * FROM status_type";
    $status = mysqli_query($con, $status_query);

    $total_expense = 0;

    $expense_query = "SELECT * 
        FROM expenses 
        WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
        ORDER BY id ASC";

    $expense_result = mysqli_query($con, $expense_query);

    while ($row = mysqli_fetch_assoc($expense_result)) {
        $total_expense += $row['expense_cost'];
    }

    $total_payment = 0;

    $payment_query = "SELECT *
        FROM payments
        WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
        $payment_filter
        ORDER BY id ASC";

    $payment_result = mysqli_query($con, $payment_query);
    
    while ($row = mysqli_fetch_assoc($payment_result)) {
        $total_payment += $row['amount'];
    }
    $sales_result = mysqli_query($con, $sales_query);
    $clients_sql = "SELECT * FROM clients";
    $client_results = mysqli_query($con, $clients_sql);    
    $clients = [];

    while($type = mysqli_fetch_assoc($client_results)) {
        $clients[] = $type;
    }
?>
<html>
    <body>
        <div class="wrapper">
            <?php include 'includes/nav.php'; ?>
            <div class="main">
                <?php include 'includes/navtop.php'; ?>
                <main class="content">
                    <div class="container-fluid p-0">
                        <h1 class="h3 mb-2">SALE REPORT</h1>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-4">
                                                    <label for="inputState">Client</label>
                                                    <select name="client_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                            foreach ($clients as $type) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["first_name"] . '</option>';
                                                            }
                                                        ?>
                                                    </select>            
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="inputState">Staff</label>
                                                    <select name="staff_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                        if (mysqli_num_rows($staffs) > 0) {
                                                            while ($type = mysqli_fetch_assoc($staffs)) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["name"] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>            
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="inputState">Service</label>
                                                    <select name="service_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                        if (mysqli_num_rows($service) > 0) {
                                                            while ($type = mysqli_fetch_assoc($service)) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["type"] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>            
                                                </div> 
                                                <div class="form-group col-4">
                                                    <label for="inputState">Status Type</label>
                                                    <select name="status_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                        if (mysqli_num_rows($status) > 0) {
                                                            while ($type = mysqli_fetch_assoc($status)) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["name"] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>            
                                                </div>                                       
                                                <div class="form-group col-4">
                                                    <label for="inputEmail4">Start Date</label>
                                                    <input type="date" name="start_date" value="<?php echo $current_date ?>" class="form-control">
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="inputPassword4">End Date</label>
                                                    <input type="date" class="form-control" name="end_date" value="<?php echo $ed ?>">
                                                </div>

                                                <div class="col-4" style="padding-top:1.8rem;">
                                                    <button name="filter_sales" type="submit" class="btn btn-primary btn-fluid w-100">Filter</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body row text-center pt-5">
                                        <div class='col-4'>
                                            <div class='row'>
                                                <h5 class='col'>TOTAL SALES</h5>
                                                <p class='text-muted col'>KSH <?php echo number_format($total_revenue, 2); ?></p>
                                            </div>
                                            <div class='row'>
                                                <h5 class='col'>SALES DUE</h5>
                                                <p class='text-muted col'>KSH <?php echo number_format($total_due, 2); ?></p>
                                            </div>
                                            <div class='row'>
                                                <h5 class='col'>ADVANCE SALES</h5>
                                                <p class='text-muted col'>KSH <?php echo number_format($total_advance, 2); ?></p>
                                            </div>
                                            
                                        </div>
                                        <div class='col-4'>
                                            <div class='row'>
                                                <h5 class='col'>TOTAL COMMISSION:</h5>
                                                <p class='text-muted col'>KSH <?php echo number_format($total_commission, 2); ?></p>
                                            </div>
                                            <div class='row'>
                                                <h5 class='col'>COMMISSION PAID: </h5>
                                                <p class='text-muted col'>KSH <?php echo number_format($total_payment, 2); ?></p>
                                            </div>
                                            <div class='row'>
                                                <h5 class='col'>TOTAL EXPENSE: </h5>
                                                <p class='text-muted col'>KSH <?php echo number_format($total_expense, 2); ?></p>
                                            </div>
                                        </div>
                                        <div class='col-4'>
                                            <h4>PROFIT/LOSS </h4>
                                            <p class='text-muted'>sales - (commission paid + expense)</p>
                                            <h5>KSH <?php echo number_format($total_revenue - ($total_payment + $total_expense), 2); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                     <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                               	<table id="example" class="table table-striped table-bordered table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vehicle Number</th>
                                        <th>Client</th>
                                        <th>Staff</th>
                                        <th>Service</th>
                                        <th>Service Commission</th>
                                        <th>Service Cost</th>
                                        <th>Amount Paid</th>
                                        <th>Amount Due</th>
                                        <th>Advance Payment</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        <th>Last Update</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            if (mysqli_num_rows($sales_result) > 0) {
                              // output data of each row
                              while($row = mysqli_fetch_assoc($sales_result)) {
                                $icon = 'fa-car';
                                $status = 'text-success';
                                
                                if($row["status_type"] == '2'){
                                    $status = 'text-warning';
                                }else if($row["status_type"] == '0'){
                                    $status = 'text-danger';
                                }else if($row["status_type"] == '1'){
                                    $status = 'text-primary';
                                }else if($row["status_type"] == '4'){
                                    $status = 'text-alert';
                                }else if($row["status_type"] == '3'){
                                    $status = 'text-success';
                                }
                                
                                
                                $icon = 'fa-car';
                                
                                
                                echo '<tr>
                                    <td>'.$row['id'].'</td>
                                    <td><i class="align-middle fa '.$icon.'"> </i> '.$row['vehicle_number'].'</td>
                                    <td>'.$row['owner_name'].'</td>
                                    <td>'.$row['staff_name'].'</td>
                                    <td>'.$row['service_type'].'</td>
                                    <td> '.$row['service_commission'] .' %</td>
                                    <td> KSH '.number_format($row['service_cost'], 2).'</td>
                                    <td> KSH '.number_format($row['amount_paid'] , 2).'</td>
                                    <td> KSH '.number_format($row['service_cost'] > $row['amount_paid']? $row['service_cost']-$row['amount_paid'] : 0     , 2).'</td>
                                    <td> KSH '.number_format($row['service_cost'] < $row['amount_paid']? $row['amount_paid']-$row['service_cost'] : 0  , 2).'</td>
                                    <td>'.$row['payment_method'] .' </td>
                                    <td><span class="'.$status.'">'.$row['status'].'</span></td>
                                    <td>'.date('Y M j,  h:i A', strtotime($row['in_time'])).'</td>
                                    <td>'.($row['last_update'] != '' ? date('Y M j,  h:i A', strtotime($row['out_time'])) : null).'</td>
                                    <td class="table-action">
												<a onclick="loadData('.$row['id'].')" data-id="'.$row['id'].'" type="button" class="btn" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
											</td>
                                </tr>';
                            
                                ?> 
                            
                            <?php
                              }
                            } else {
                                echo '<tr>
                                        <td colspan="5">No Data</td>
                                    </tr>';
                            }
                          ?> 
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Vehicle Number</th>
                <th>Client</th>
                <th>Staff</th>
                <th>Service</th>
                <th>Service Commission</th>
                <th>Service Cost</th>
                <th>Amount Paid</th>
                <th>Amount Due</th>
                <th>Advance Payment</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Time In</th>
                <th>Last Update</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
    
                           </div>
                        </div>
                     </div>
                  </div>
                    </div>
                </main>
                <?php include 'includes/footer.php'; ?>
            </div>
        </div>
              
      									<!-- BEGIN delete modal -->
                                          <div class="modal fade deleteModal" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Update Records</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
												</div>
												<div class="modal-body m-3" id="formData">

												</div>
												
											</div>
										</div>
									</div>

        <?php include 'includes/scripts.php'; ?>
    </body>
</html>
