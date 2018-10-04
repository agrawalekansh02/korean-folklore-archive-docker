 <?php
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');
$dbConn = get_connection();

// if report history of quarter is selected
if (!isset($data[0]) || empty($data[0])) {
    header("Location: ".HOST."reports");
}

$quarter_id = $data[0];

// if submit write to file
if (isset($_POST['run_quarter']) && !empty($_POST['run_quarter'])){
    run_quarter_report($_POST['run_quarter']);

    //to clear any post data, so upon refreshing, reports don't run again
    header("Location: ".HOST."reports_history/".$quarter_id);
}

echo "<h3>" . get_quarter_by_id($quarter_id) . " REPORT HISTORY</h3><br>";

//get report history
$quarter_report_history =  "SELECT 
                                rh.id, 
                                rh.quarter_id,
                                rh.active_collectors, 
                                rh.new_consultants, 
                                rh.new_contexts, 
                                rh.new_data, 
                                rh.total_data_size, 
                                rh.report_time
                            FROM report_history AS rh
                            WHERE rh.quarter_id = $quarter_id
                            ORDER BY 
                                rh.report_time DESC";
$report_results = mysqli_query($dbConn, $quarter_report_history); ?>

<form method="post" action="" id='reports_history'>
    <input type='hidden' name='run_quarter' value='<?=$quarter_id;?>'>
    <input type='submit' value="Run New Report" >
</form>
<br>
<br>
<?php
if (mysqli_num_rows($report_results) > 0) { ?>
    <table class='reports'>
        <thead>
            <tr>
                <th>Active Collectors</th>
                <th>New Consultants</th>
                <th>New Contexts</th>
                <th>New Data</th>
                <th>Total Data Upload Size</th>
                <th>Last Run</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                while($row = mysqli_fetch_assoc($report_results)) { ?>
                    <tr>
                        <td><?=$row["active_collectors"];?></td>
                        <td><?=$row["new_consultants"];?></td>
                        <td><?=$row["new_contexts"];?></td>
                        <td><?=$row["new_data"];?></td>
                        <td><?=format_file_size($row["total_data_size"]);?></td>
                        <td><?=date('n/j/y g:i a', strtotime($row["report_time"]));?></td>
                    </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
<?php
} else echo "0 reports";

mysqli_close($dbConn); 