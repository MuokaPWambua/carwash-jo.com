<!DOCTYPE html>
<html lang="en">
<?php 
    include 'includes/head.php';
    include 'includes/functions.php';

    // Default current date
    $current_date = date("Y-m-d");
    $start_date = $current_date . " 00:00:00";
    $end_date = $current_date . " 23:59:59";

    if (isset($_POST['submit'])) {
        // If the form is submitted, use the provided dates
        $start_date = $_POST['start_date'] . " 00:00:00";
        $end_date = $_POST['end_date'] . " 23:59:59";
        $staff_id = $_POST['staff_id'];

        // Modify the query to filter by staff_id if provided
        $staff_condition = $staff_id ? "AND p.staff_id = '$staff_id'" : "";
    } else {
        // No specific staff_id, so the report will include all staff members
        $staff_condition = "";
    }
    // Fetch staff-related payments within the selected or default time range
    $query = "SELECT 
        e.id AS employee_id,
        e.name AS employee_name,
        e.employee_status AS status_type,
        e.employee_email AS employee_email,
        e.employee_contact AS employee_phone,
        e.employee_address AS employee_address,
        SUM(p.amount) AS total_payment,
        SUM(CASE WHEN q.status_type = 3 THEN st.service_cost ELSE 0 END) AS total_revenue,
        SUM(CASE WHEN q.status_type = 3 THEN st.service_cost * st.service_commission / 100 ELSE 0 END) AS total_commission
    FROM 
        staff e
    LEFT JOIN 
        queue q ON e.id = q.staff
    LEFT JOIN 
        payments p ON e.id = p.staff_id AND p.updated_at BETWEEN '$start_date' AND '$end_date'
    LEFT JOIN 
        service_type st ON q.service_type = st.id
    $staff_condition
    GROUP BY 
        e.id, e.name, e.employee_email, e.employee_contact, e.employee_address
    ORDER BY
        total_revenue DESC, total_commission DESC, total_payment DESC 
    LIMIT 1000";

    $staff_result = mysqli_query($con, $query);

    // Initialize variables to store the sums for all staff members
    $total_revenue = 0;
    $total_commission = 0;
    $total_payment = 0;

    // Fetch the results and calculate the totals
    while ($row = mysqli_fetch_assoc($staff_result)) {
        $total_revenue += $row['total_revenue'];
        $total_commission += $row['total_commission'];
        $total_payment += $row['total_payment'];
    }

    // Fetch payment records within the selected or default time range
    $payment_query = "SELECT 
        p.id AS payment_id,
        e.name AS employee_name,
        p.amount AS payment_amount,
        p.updated_at AS payment_date
    FROM 
        payments p
    LEFT JOIN 
        staff e ON p.staff_id = e.id
    WHERE 
        p.updated_at BETWEEN '$start_date' AND '$end_date'
    $staff_condition
    ORDER BY 
        p.updated_at DESC, p.amount DESC 
    LIMIT 1000";

    $payment_result = mysqli_query($con, $payment_query);

    // Fetch all staff members for the dropdown
    $staff_query = "SELECT * FROM staff";
    $staffs = mysqli_query($con, $staff_query);
?>

<body>
    <div class="wrapper">
        <?php include 'includes/nav.php'; ?>
        <div class="main">
            <?php include 'includes/navtop.php'; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-2">Staff Payment Reports</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" name="start_date" class="form-control" value="<?php echo $current_date; ?>">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" name="end_date" value="<?php echo $current_date; ?>">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                                <label for="staff_id">Staff</label>
                                                <select name="staff_id" class="form-control">
                                                    <option value="" selected>All Staff</option>
                                                    <?php
                                                    if (mysqli_num_rows($staffs) > 0) {
                                                        while($type = mysqli_fetch_assoc($staffs)) {
                                                            echo '<option value="'.$type["id"].'">'.$type["name"].'</option>'; 
                                                        }      
                                                    }
                                                    ?>
                                                </select>            
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3 " style="padding-top:1.8rem;">
                                                <button name="submit" type="submit" class="btn btn-primary w-100">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display Results -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body row text-center pt-5">
                                    <div class="col-3">
                                        <h4>TOTAL REVENUE</h4>
                                        <p class="lead">KSH <?php echo number_format($total_revenue, 2); ?></p>
                                    </div>
                                    <div class="col-3">
                                        <h4>TOTAL COMMISSION</h4>
                                        <p class="lead">KSH <?php echo number_format($total_commission, 2); ?></p>
                                    </div>
                                    <div class="col-3">
                                        <h4>TOTAL PAYMENTS</h4>
                                        <p class="lead">KSH <?php echo number_format($total_payment, 2); ?></p>
                                    </div>
                                    <div class="col-3">
                                        <h4>TOTAL DUE</h4>
                                        <p class="lead">KSH <?php echo number_format($total_payment-$total_commission, 2); ?></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card w-100">
                                <div class="card-body">
                                <table id="example" class="table table-striped w-100 table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Staff ID</th>
                                                    <th>Amount</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($payment_result) > 0) {
                                                    // Output data of each row
                                                    while($row = mysqli_fetch_assoc($payment_result)) {
                                                        echo '<tr>
                                                            <td>'.$row['payment_id'].'</td>
                                                            <td>'.$row['employee_name'].'</td>
                                                            <td> KSH '.number_format($row['payment_amount'], 2).'</td>
                                                            <td>'.date("Y-m-d", strtotime($row['payment_date'])).'</td>
                                                
                                                            <td class="table-action">
                                                                <a onclick="loadPayment('.$row['payment_id'].', pay=false)" data-id="'.$row['id'].'" type="button" class="btn" data-toggle="modal" data-target="#updateModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
                                                            </td>
                                                        </tr>';
                                                    }
                                                } else {
                                                    echo '<tr>
                                                            <td colspan="6">No Data</td>
                                                        </tr>';
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Staff </th>
                                                    <th>Amount</th>
                                                    <th>Created At</th>
                                                    
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- BEGIN update modal -->
        <div class="modal fade updateModal" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Records</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3" id="formData">
                        <!-- Form data will be loaded here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END update modal -->

                </div>
            </main>

            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
    <?php include 'includes/scripts.php'; ?>
</body>
</html>
