<!-- 

CREATOR
Steven Braun
braunsg

REPO
interactive-map-survey

ORIGINAL CREATION DATE
2014-08-22

DESCRIPTION
This code provides a template for creating an interactive map survey. A map is displayed
on a page, and users can click on specific spots in the map and input comments

-->

<?php

// In our instance, we had two input types: 'user-response' for comments entered
// by patrons, and 'staff-survey' for staff tracking

	$typeVal = $_GET['input'];
	if(is_null($typeVal)) {
		$inputType = "user-response";
	} else {
		if($typeVal === "user") {
			$inputType = "user-response";
		} elseif($typeVal === "staff") {
			$inputType = "staff-survey";
		}
	}
?>

<!DOCTYPE html>
<head>
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function() {

			pointCounter = 0;
			var selectedMap;
			var selectedFloor;
			inputType = <?php echo "\"" . $inputType . "\""; ?>;
			
			// Initialize floor selection (multiple floors)			
			selectMap('2');
		
		});


		function submitComment(xCoord,yCoord,idNumber) {
			var getField = "#commentField_" + idNumber;
			var thisComment = $(getField).val();
			var getDiv = "#commentDiv_" + idNumber;
			var getBox = "#commentContainer_" + idNumber;
			
			// Send comment/data to script that interacts with database
			$.post("inc/record-data.php", {x: xCoord, y: yCoord, comment: thisComment, floor: selectedFloor, type: inputType});	
		
			$(getDiv).html("<div class='response'>Thanks for your feedback!</div>");
			$(getBox).delay(1000).fadeOut(500, function() {			
				$(getBox).remove();
			});
			$(selectedMap).css('pointer-events','auto');
		}
	

		function selectMap(floor) {	
			selectedFloor = floor;
			
			// Specify pixel bounds for each map
			// This helps determine if the popup comment box needs to flip directions
			
			switch(floor) {
				case '2':
					bounds = {x1: 4, y1: 19, x2: 645, y2: 527};
					break;
				case '3':
					bounds = {x1: 7, y1: 45, x2: 597, y2: 525};
					break;
				case '4':
					bounds = {x1: 6, y1: 4, x2: 643, y2: 564};
					break;
			}
			
			selectedMap = '#floor' + selectedFloor;
			$("div[id^=floor]").css('visibility','hidden');
			$("div[id^=tab-]").css('background-color','#FCF6CE');
			$("#tab-floor" + floor).css('background-color','#FFCC66');
			$(selectedMap)
				.css('visibility','visible')
				.off("click")
				.on("click",function(event) {
					pointCounter++;
					var parentOffset = $(selectedMap).offset();
					var getX = event.pageX - parentOffset.left - 2;
					var getY = event.pageY - parentOffset.top - 3;
					
					// Append popup div
					if((getX > bounds.x1 && getX < bounds.x2) && (getY > bounds.y1 && getY < bounds.y2)) {
						
						// If point clicked is too far to the right of the screen, flip popup box to the left
						if(getX > 375) {
							var appendDiv = "<div id='commentContainer_" + pointCounter + "' class='commentContainer_left' style='z-index:5;position:absolute;top: " + (getY - 12) + "px; left: " + (getX + 13 - 277) + "px'>\
												<div class='panelContainer'>\
												<img src='inc/starpoint.png' style='position:absolute; top:2px; left: 2px;'>\
												<img id='cancel' src='inc/cancel.png' style='position:absolute; top: 24px; left: 2px; cursor:pointer;'>\
												</div>\
												<div id='commentDiv_" + pointCounter + "' class='textBox' style='position: absolute; top: 0px; left: 0px;'>\
													<div class='commentFieldDiv'>\
														<textarea maxlength=500 class='commentField' id='commentField_" + pointCounter + "' onclick='inputComment(" + pointCounter + ")'>Click here to share your thoughts about this space.</textarea>\
													</div>\
													<div class='commentSubmitDiv'>\
														<input class='submitCommentButton' type='button' onclick='submitComment(" + getX + "," + getY + "," + pointCounter + ")'>\
													</div>\
												</div>\
											</div>";
											
						// Otherwise, popup box opens to the right
						} else {
							var appendDiv = "<div id='commentContainer_" + pointCounter + "' class='commentContainer_right' style='z-index:5;position:absolute;top: " + (getY - 12) + "px; left: " + (getX - 13) + "px'>\
												<div class='panelContainer'>\
												<img src='inc/starpoint.png' style='position:absolute; top:2px; left: 2px;'>\
												<img id='cancel' src='inc/cancel.png' style='position:absolute; top: 24px; left: 2px; cursor:pointer;'>\
												</div>\
												<div id='commentDiv_" + pointCounter + "' class='textBox' style='position: absolute; top: 0px; left: 25px;'>\
													<div class='commentFieldDiv'>\
														<textarea maxlength=500 class='commentField' id='commentField_" + pointCounter + "' onclick='inputComment(" + pointCounter + ")'>Click here to share your thoughts about this space.</textarea>\
													</div>\
													<div class='commentSubmitDiv'>\
														<input class='submitCommentButton' type='button' onclick='submitComment(" + getX + "," + getY + "," + pointCounter + ")'>\
													</div>\
												</div>\
											</div>";

						}
						$(selectedMap).css('pointer-events','none').append(appendDiv);	

						$("#commentContainer_" + pointCounter)
							.css('pointer-events','auto')
							.click(function(event) { event.stopPropagation(); });					

						$("#cancel").click(function(event) {
							$("#commentContainer_" + pointCounter).remove();
							event.stopPropagation();
							$(selectedMap).css('pointer-events','auto');
						});
				}

				});
		};	
	
	
		function inputComment(idNumber) {
			var getField = "#commentField_" + idNumber;
			$(getField).val("");
		}

	</script>

	<style>

	/* Body layout styles */

	body, html {
		margin:0px;
		padding:0px;
		overflow: auto;
		height: 100%;
		width: 100%;
	}

	#header {
		width: 650px;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		position: relative;
		left: 50%;
		margin-left: -325px;
	}		

	#header .title {
		font-size: 28px;
		color: #003366;
	}
	
	#header .content {
		font-size: 14px;
		text-indent: 0px;
		text-align: justify;
	}
	
	#bodyContainer {
		width: 650px;
		height: 600px;
		position: absolute;
		margin: 0px;
		padding: 0px;
		left: 50%;
		margin-left: -325px;
	}
	

	#tabContainer {
		z-index: 15;
		width: 100%;
		height: 30px;
		padding: 5px 5px 5px 0px;
		font-size: 14px;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	#tabContainer .tab {
		z-index: 15;
		width: 150px;
		height: 25px;
		margin: 0px 2px 0px 0px;
		float: left;
		border: 1px solid #000;
		border-color: #000;
		text-align: center;
		background-color: #FCF6CE;
		border-bottom: 0px;
		cursor: pointer;

	}

	#tabContainer .textLabel {
		height: 25px;
		line-height: 25px;
		margin:0px;
		padding: 0px;
	}

	.mapContainer {
		z-index: 5;
		width: 650px;
		height: 570px;
		position: absolute;
		top: 30px;
		left: 0px;
		margin: 0px;
		padding: 0px;
		border: 1px solid #000;
		display: block;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	#floor2 {
		visibility: visible;
		background: url('floorplans/floor_2-sm.png') 0px 0px no-repeat;
	}

	#floor3 {
		visibility: hidden;
		background: url('floorplans/floor_3-sm.png') 0px 0px no-repeat;

	}

	#floor4 {
		visibility: hidden;
		background: url('floorplans/floor_4-sm.png') 0px 0px no-repeat;

	}

	/* Comment box layout styles */
	
	/* Right-facing box */

	.commentContainer_right {
		width:277px;
		height: 100px;
		position: absolute;
		border-top: 1px solid #FFCC66;

	}

	.commentContainer_right .panelContainer {
		position:absolute;
		z-index: 25;
		width:26px;
		height:46px;
		border: 1px solid #FFCC66;
		background: #fff;
		border-right: 2px solid #fff;
		border-top: none;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	/* Left-facing box */

	.commentContainer_left {
		width:277px;
		height: 100px;
		position: absolute;
		border-top: 1px solid #FFCC66;

	}

	.commentContainer_left .panelContainer {
		position:absolute;
		left: 251px;
		z-index: 25;
		width:26px;
		height:46px;
		border: 1px solid #FFCC66;
		background: #fff;
		border-left: 2px solid #fff;
		border-top: none;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	/* Global style parameters */
	.textBox {
		z-index:5;
		width:250px;
		height: 100px;
		padding: 0px;
		margin: 0px;
		font-size: 12px;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		background: #ffffff;
		border: solid 1px #FFCC66;
		border-top:none;
		display: table;	
	}

	div.commentFieldDiv {
		position: relative;
		margin: 0px;
		padding: 5px;
		height: 100%;
		width: 80%;
		display: table-cell;
	}

	.commentField {
		color: gray;
		font-size: 14px;
		font-style: italic;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		margin: 0px;
		padding: 0px;
		width: 100%;
		height: 100%;
		border: none;
		resize: none;

	}

	.response {
		color: gray;
		font-size: 14px;
		font-style: italic;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		margin: 0px;
		width: 100%;
		height: 20px;
		position: absolute;
		top: 50%;
		margin-top: -10px;
		text-align: center;
		border: none;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		padding-left: 5px;

	}

	div.commentSubmitDiv {
		position: relative;
		margin: 0px;
		padding: 3px;
		height: 100%;
		width: 20%;
		display: table-cell;
	}

	.submitCommentButton {
		border: none;
		color: #ffffff;
		font-size: 12px;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		text-align: center;
		margin: 0px;
		padding: 0px;
		height: 100%;
		width: 100%;
		background: url('inc/pen.png') center no-repeat #FFCC66;
	}




	</style>
</head>
<body>
	<div id="header">
		<div class="title">
			<!-- Title -->
		</div>
		<div class="content">
			<!-- Descriptive content -->
		</div>
	</div>
	<div id="bodyContainer">
		<div id="tabContainer">
			<!-- Multiple tabs for multi-floor map selection -->
			<div class='tab' id='tab-floor2' onclick='selectMap("2");'><span class='textLabel'>Floor 2</span></div>
			<div class='tab' id='tab-floor3' onclick='selectMap("3");'><span class='textLabel'>Floor 3</span></div>
			<div class='tab' id='tab-floor4' onclick='selectMap("4");'><span class='textLabel'>Floor 4</span></div>
		</div>
		<div id="floor2" class="mapContainer">
		</div>
		<div id="floor3" class="mapContainer">
		</div>
		<div id="floor4" class="mapContainer">
		</div>
	</div>
</body>
</html>