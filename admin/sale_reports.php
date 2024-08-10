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
    if (isset($_POST['submit'])) {
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
            st.id AS service_id,
            st.type AS service_name,
            st.service_cost AS service_cost,
            st.service_commission AS service_commission,
            COALESCE(SUM(st.service_cost), 0) AS total_revenue,
            COALESCE(SUM(st.service_cost * st.service_commission / 100), 0) AS total_commission    
        FROM 
            service_type st
        LEFT JOIN 
            queue q ON st.id = q.service_type
        $where_clause
        GROUP BY 
            st.id
        ORDER BY 
            st.id ASC";
    
    $sales_result = mysqli_query($con, $sales_query);

    $total_revenue = 0;
    $total_commission = 0;

    while ($row = mysqli_fetch_assoc($sales_result)) {
        $total_revenue += $row['total_revenue'];
        $total_commission += $row['total_commission'];
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
?>
<html>
    <body>
        <div class="wrapper">
            <?php include 'includes/nav.php'; ?>
            <div class="main">
                <?php include 'includes/navtop.php'; ?>
                <main class="content">
                    <div class="container-fluid p-0">
                        <h1 class="h3 mb-2">Sales Reports</h1>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="form-row">
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
                                                    <button name="submit" type="submit" class="btn btn-primary btn-fluid w-100">Filter</button>
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
                                                <h4 class='col'>TOTAL SALES</h4>
                                                <p class='lead col'>KSH <?php echo number_format($total_revenue, 2); ?></p>
                                            </div>
                                            <div class='row'>
                                                <h4 class='col'>TOTAL EXPENSE: </h4>
                                                <p class='lead col'>KSH <?php echo number_format($total_expense, 2); ?></p>
                                            </div>
                                            
                                        </div>
                                        <div class='col-4'>
                                            <div class='row'>
                                                <h4 class='col'>TOTAL COMMISSION:</h4>
                                                <p class='lead col'>KSH <?php echo number_format($total_commission, 2); ?></p>
                                            </div>
                                            <div class='row'>
                                                <h4 class='col'>TOTAL PAID: </h4>
                                                <p class='lead col'>KSH <?php echo number_format($total_payment, 2); ?></p>
                                            </div>

                                        </div>
                                        <div class='col-4'>
                                            <h4>PROFIT/LOSS </h4>
                                            <p class='text-muted'>sales - (commission paid + expense)</p>
                                            <p class='lead'>KSH <?php echo number_format($total_revenue - ($total_payment + $total_expense), 2); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>
                </main>
                <?php include 'includes/footer.php'; ?>
            </div>
        </div>
        <?php include 'includes/scripts.php'; ?>
    </body>
</html>
