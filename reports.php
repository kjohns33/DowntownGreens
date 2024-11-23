<?php
 
 	function getReportsInDateRange($start_date, $end_date) {
		$connection = connect();
		$start_date = mysqli_real_escape_string($connection, $start_date);
		$end_date = mysqli_real_escape_string($connection, $end_date);
		$query = "select * from dbEvents
				  where open_date >= '$start_date' and open_date <= '$end_date'
				  order by open_date asc";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			mysqli_close($connection);
			return null;
		}
		require_once('include/output.php');
		$events = array();
		while ($result_row = mysqli_fetch_assoc($result)) {
			$key = $result_row['open_date'];
			if (isset($events[$key])) {
				$events[$key] []= hsc($result_row);
			} else {
				$events[$key] = array(hsc($result_row));
			}
		}
		mysqli_close($connection);
		return $events;
	}

	function displayReportsForDateRange ($start_date, $end_date) { 
		$pieces = explode('-', $start_date);
		$pieces2 = explode('-', $end_date);
		$year = $pieces[0];
		$month = $pieces[1];
		$day = $pieces[2];
		$year2 = $pieces2[0];
		$month2 = $pieces2[1];
		$day2 = $pieces2[2];
		?>
        <h2>Grant Report <?php echo $month."/".$day."/".$year?> through <?php echo $month2."/".$day2."/".$year2?></h2>
            <?php 
                require_once('database/dbEvents.php');
                $grants = fetch_event_open_range($start_date, $end_date);
                if (count($grants) > 0): ?>
 		
				<?php 
					require_once('include/output.php');
					
					global $totalApplied;
					global $totalWaiting;
					global $totalAccepted;
					global $fundsAnticipated;
					global $fundsAwarded;
					
				 	$totalApplied = 0;
					$totalWaiting = 0;
					$totalAccepted = 0;
					$fundsAnticipated = 0;
					$fundsAwarded = 0;
					
					forEach($grants as $grant) {
						$status = $grant['completed'];
						$funds = $grant['amount'];
						if($status == "submitted"){
							$totalWaiting++;
						}
						if(($status == "submitted") || ($status == "declined") || 
						($status == "accepted") || ($status == "awarded")){
								$totalApplied++;
							}
						if($status == "accepted"){
							$totalAccepted++;
							$fundsAnticipated += $funds;
						}
						if($status == "awarded"){
							$fundsAwarded += $funds;
						}
					}
					echo "<b>".$totalApplied." Grants Applied<br>";
					echo $totalWaiting." Grants Applied and Waiting for Response<br>";
					echo $totalAccepted." Grants Accepted and Not Awarded Yet (Anticipated Award: $".number_format($fundsAnticipated,2)." )<br>";
					echo "<br>";
					echo "$".number_format($fundsAwarded, 2)." Total Awarded!<br>";
					echo " <br>";
					
				?>
				<div class="table-wrapper">
				<table class="general">
				<thead>
				<tr>
					<th><strong>Grant Name</strong></th>
					<th style="width:1px"><strong>Open Date</strong></th>
					<th style="width:1px"><strong>Due Date</strong></th>
					<th style="width:1px"><strong>Amount</strong></th>
					<th style="width:1px"><strong>Description</strong></th>
				</tr>
				</thead>
				<tbody class="standout">
					<?php				
					foreach ($grants as $grant) {
						$grantID = $grant['id'];
						$title = $grant['name'];
						$status = $grant['completed'];
						$openDate = $grant['open_date'];
						$dueDate = $grant['due_date'];
						$timePacked = $grant['due_date'];
                        $pieces2 = explode('-', $openDate);
                        $pieces = explode('-', $timePacked);
                        $year = $pieces[0];
                        $month = $pieces[1];
                        $day = $pieces[2];
                        $year2 = $pieces2[0];
                        $month2 = $pieces2[1];
                        $day2 = $pieces2[2];
						$descrip = $grant['description'];
						$funds = $grant['amount'];
						
						if($status != "awarded"){
							continue;
						}
						$fundsString = number_format($funds, 2);
						echo "
							<tr class='message' style='color:white;' data-message-id='$grantID'>
								<td>$title</td>
								<td>$month2/$day2/$year2</td>
								<td>$month/$day/$year</td>
								<td>$fundsString</td>
								<td>$descrip</td>
							</tr>";
					}
				?>
			</tbody>
		</table>
	</div>
	<?php else: ?>
            <p class="no-messages standout" style="color:white;">No grants in the date range.</p>
    <?php endif ?>
	<?php
	   echo "</select><br/>";
	?>
	<a class="button cancel" href="index.php" >Return to Dashboard</a>
	<?php
	
	}
?>
