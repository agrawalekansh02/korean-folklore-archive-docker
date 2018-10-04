<?php
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');
$dbConn = get_connection();


// if submit write to file
if (isset($_POST['run_quarter']) && !empty($_POST['run_quarter'])){
    run_quarter_report($_POST['run_quarter']);

    //to clear any post data, so upon refreshing, reports don't run again
    header("Location: ".HOST."reports");
}


//get all quarters
$quarter_results = mysqli_query($dbConn, "SELECT quarter_id, UPPER(quarter_short_name) AS quarter_short_name FROM quarter ORDER BY quarter_id DESC");

//get report history
$report_history =  "SELECT 
                        rh.id, 
                        rh.quarter_id,
                        UPPER(q.quarter_short_name) AS quarter_short_name, 
                        rh.active_collectors, 
                        rh.new_consultants, 
                        rh.new_contexts, 
                        rh.new_data, 
                        rh.total_data_size, 
                        rh.report_time
                    FROM report_history AS rh
                    JOIN quarter AS q
                        ON rh.quarter_id = q.quarter_id
                    WHERE rh.id IN (SELECT MAX(id) FROM report_history GROUP BY quarter_id)
                    ORDER BY 
                        rh.quarter_id DESC";

$report_results = mysqli_query($dbConn, $report_history);

?>
<h3>REPORTS</h3>
<br>
Run New Report for Quarter: 
<?php
if (mysqli_num_rows($quarter_results) > 0) { ?>
    <form method="post" action="" id='reports_form'>
        <select name='run_quarter'>
            <?php
            while($row = mysqli_fetch_assoc($quarter_results)) {
                echo "<option value='".$row["quarter_id"]."'>" . $row["quarter_short_name"] . "</option>";
            }
            ?>
        ?>
        </select>
        <input type='submit' value="Run" >
    </form>
<?php
} else echo " No quarters available ";
?>

<br>
<br>

<?php
if (mysqli_num_rows($report_results) > 0) { ?>
    <table class='reports'>
        <thead>
            <tr>
                <th>Quarter</th>
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
                while($row = mysqli_fetch_assoc($report_results)) {?>
                    <tr>
                        <td><a href='reports_history/<?=$row["quarter_id"];?>'><?=$row["quarter_short_name"];?></a></td>
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
?>