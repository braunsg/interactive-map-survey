<?php

// CREATOR
// Steven Braun
// braunsg
// 
// REPO
// interactive-map-survey
// 
// ORIGINAL CREATION DATE
// 2014-08-22
// 
// DESCRIPTION
// This code grabs submitted map survey comments that have been stored in a database
// and returns it in a format to be displayed in show-responses.php


$floor = $_POST['floor'];
$responseType = $_POST['type'];
$sortType = $_POST['sort'];
if(!$sortType) {
	$sortType = "dateStamp";
}

$divName = "'#map-" . $floor . "'";

?>

<script>
	var div = <?php echo $divName; ?>;
	d3.select(div).selectAll("circle").remove();
</script>

<?php

if($responseType === "none") {
	echo "<div style='text-align:center;font-style:italic;padding-top:5px'>Please select a response type option from the checkboxes above (U = user responses, S = staff responses)</div>";
} else {
	// Connect to MySQL server
	$con = mysqli_connect('database host', 'database user', 'database password', 'database name') or die(mysqli_connect_error());

	switch($responseType) {
		case "both":
			$responsesQuery = "SELECT * FROM user_responses WHERE floor = '$floor' ORDER BY $sortType DESC";
			break;
		case "user":
			$responsesQuery = "SELECT * FROM user_responses WHERE floor = '$floor' AND type='user-response' ORDER BY $sortType DESC";
			break;
		case "staff":
			$responsesQuery = "SELECT * FROM user_responses WHERE floor = '$floor' AND type='staff-survey' ORDER BY $sortType DESC";
			break;
	}
	$result = mysqli_query($con,$responsesQuery);
	$numRows = mysqli_num_rows($result);
	
	if($numRows == 0) {
		echo "<div style='text-align:center;font-style:italic;padding-top:5px'>No responses recorded; please select a different response type option from the checkboxes above (U = user responses, S = staff responses)</div>";	
	} else {
		$rowCounter = 0;
		while($row = mysqli_fetch_array($result)) {
			$rowCounter++;
			if($rowCounter % 2) {
				$rowClass='row1';
				$resetColor = '#F9F6F4';
			} else {
				$rowClass='row2';
				$resetColor = '#E9E0DB';
			}
		
			$responseDate = date('m-d-Y, H:i A', strtotime($row['dateStamp']));
			$xCoords = $row['xCoords'];
			$yCoords = $row['yCoords'];
			$comment = $row['comment'];
			$responseType = $row['type'];
			if($responseType === 'user-response') {
				$responseLabel = "User";
			} else if($responseType === 'staff-survey') {
				$responseLabel = "Staff";
			}
			$floor = $row['floor'];
			$ind = $row['ind'];
			$pointID = "'#map-" . $floor . "_" . $ind . "'";
			echo "<div class='" . $rowClass . "' id='line_" . $ind . "' onmouseover='lineHover(" . $ind . ",\"in\")' onmouseout='lineHover(" . $ind . ",\"out\",\"" . $resetColor . "\")'>";
			echo "<div class='date'>" . $responseDate . "</div>";
			echo "<div class='comment'>" . $comment . "</div>";
			echo "<div class='xCoords'>" . $xCoords . "</div>";
			echo "<div class='yCoords'>" . $yCoords . "</div>";
			echo "<div class='responseType'>" . $responseLabel . "</div>";
			echo "</div>";
	
			?>
	
			<script>
					var pointID = <?php echo (string)$ind; ?>;
						var getX = <?php echo $xCoords; ?>;
						var getY = <?php echo $yCoords; ?>;
						var resetColor = <?php echo "'" . $resetColor . "'"; ?>;
						d3.select(div)
							.append("circle")
							.datum({id: pointID, resetStyle: resetColor})
							.attr("cx", getX)
							.attr("cy", getY)
							.attr("r",3)
							.attr("fill","red")
							.attr("stroke", "#5B031A")
							.attr("id","point_" + pointID)
							.on("mouseover",function(d) {
								pointHover(d.id, "in");
							})
							.on("mouseout",function(d) { 
								pointHover(d.id, "out", d.resetStyle);
							});
			</script>
	
			<?php
		}
	}
}

?>