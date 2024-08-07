<?php
include 'dbconfig.php';

// Set the start and end dates
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : (isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-1 day')));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : (isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d H:i:s'));

// Query to get total revenue per day with status_type = 3
$revenue_query = "
    SELECT 
        DATE_FORMAT(q.in_time, '%Y-%m-%d %H:00:00') AS hour,    
        DATE(q.in_time) AS date,
        COALESCE(SUM(CASE WHEN q.status_type = 3 THEN st.service_cost ELSE 0 END), 0) AS total_revenue,
        COALESCE(SUM(CASE WHEN q.status_type = 3 THEN st.service_cost * st.service_commission / 100 ELSE 0 END), 0) AS total_commission    
    FROM 
        queue q
    LEFT JOIN 
        service_type st ON q.service_type = st.id
    WHERE 
        q.in_time BETWEEN '$start_date' AND '$end_date'
    GROUP BY 
        hour, date
    ORDER BY 
        hour, date ASC";

$revenue_result = mysqli_query($con, $revenue_query);

// Fetch the data
$dates = [];
$hours = [];
$revenues = [];

while ($row = mysqli_fetch_assoc($revenue_result)) {
    $dates[] = $row['date'];
    $hours[] = $row['hour'];
    $revenues[] = $row['total_revenue'];
}

// Convert PHP arrays to JSON
$dates_json = json_encode($dates);
$hours_json = json_encode($hours);
$revenues_json = json_encode($revenues);

$sql = "SELECT
    YEAR(last_update) AS year,
    MONTH(last_update) AS month,
    COUNT(DISTINCT id) AS joins
    FROM
    queue
    WHERE YEAR(last_update) = YEAR(CURDATE())
    GROUP BY
    YEAR(last_update), MONTH(last_update)";

$result = mysqli_query($con, $sql);
$data = [];
$labels = [];

while ($track = mysqli_fetch_assoc($result)) {
    $data[] = $track['joins'];
    $labels[] = date('F', mktime(0, 0, 0, $track['month'], 10));
}

function js_str($s) {
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_array($array) {
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var dataSet = <?php echo js_array($data); ?>;
    var labelSet = <?php echo js_array($labels); ?>;

    // Create the bar chart
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelSet,
            datasets: [{
                label: '# of Vehicles',
                data: dataSet,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        // options: {
        //     // indexAxis: 'y',
        // }
    });

    // Create the line chart
    var dates = <?php echo $dates_json; ?>;
    var hours = <?php echo $hours_json; ?>;
    var revenues = <?php echo $revenues_json; ?>;

    var ctx2 = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: hours,
            datasets: [{
                label: 'Total Revenue',
                data: revenues,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        displayFormats: {
                            day: 'MMM DD, YYYY'
                        
                        }
                        // unit: 'day'
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }],
                yAxes: [{
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue'
                    }
                }]
            }
        }
    });
</script>

