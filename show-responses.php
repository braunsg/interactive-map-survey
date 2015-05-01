<!-- 

CREATOR
Steven Braun
braunsg

REPO
interactive-map-survey

ORIGINAL CREATION DATE
2014-08-22

DESCRIPTION
This code provides a template for a page listing all submitted comments
along with their specific locations (xy coordinates) on the maps

-->

<!DOCTYPE html>
<head>
	<title>Map Survey Responses</title>
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script type="text/javascript">

		$(document).ready(function() {

			pointCounter = 0;
			var selectedMap;
			var responseType;
			
			// Initialize map to be displayed
			selectedFloor = '2';
			selectMap('2');
			
			// The map had two response options -- one for library patrons and one for staff
			// These checkboxes determine what data to be displayed
			
			$(":checkbox").on("click",function() {
				var patronCheck = $("#patrons-checkbox").prop("checked");
				var staffCheck = $("#staff-checkbox").prop("checked");
				if(patronCheck == true && staffCheck == true) {
					responseType = "both";
				} else if(patronCheck == true) {
					responseType = "user";
				} else if(staffCheck == true) {
					responseType = "staff";
				} else {
					responseType = "none";
				}
				
				// Grab the data from the database
				$.post("inc/grab-data.php", {floor: selectedFloor, type: responseType}, function(response) {
				$("#responsesTable").html(response);
			
			});	

			});

		
		});


		function selectMap(floor) {	
			selectedFloor = floor;
			var patronCheck = $("#patrons-checkbox").prop("checked");
			var staffCheck = $("#staff-checkbox").prop("checked");
			if(patronCheck == true && staffCheck == true) {
				responseType = "both";
			} else if(patronCheck == true) {
				responseType = "user";
			} else if(staffCheck == true) {
				responseType = "staff";
			} else {
				responseType = "none";
			}
			
			// Grab the data from the database
			$.post("inc/grab-data.php", {floor: selectedFloor, type: responseType}, function(response) {
				$("#responsesTable").html(response);
			
			});	

			selectedMap = '#floor' + selectedFloor;
			$("div[id^=floor]").css('visibility','hidden');
			$("div[id^=tab-]").css('background-color','#FCF6CE');
			$("#tab-floor" + floor).css('background-color','#FFCC66');
			$(selectedMap)
				.css('visibility','visible');
		};	
		
		function sortResults(floor,sortType) {
			selectedFloor = floor;
			selectedType = 'both';
			
			// Grab the data from the database
			$.post("inc/grab-data.php", {floor: selectedFloor, type: selectedType, sort: sortType}, function(response) {
				$("#responsesTable").html(response);
			
			});	
		
		
		}
	
		function lineHover(id, type, resetColor) {
			if(type === 'in') {
				$("#line_" + id).css("background-color","#D9ECF5");
				d3.select("#point_" + id)
					.attr("r",7);
			} else if(type === 'out') {
				$("#line_" + id).css("background-color",resetColor);
				d3.select("#point_" + id)
					.attr("r",3);
			}
				
		}

		function pointHover(id, type, resetColor) {
			console.log(id);
			console.log(resetColor);
			if(type === 'in') {
				d3.select("#point_" + id).attr("r",7);
				d3.select("#line_" + id).style("background-color","#D9ECF5");
			} else if(type === 'out') {
				d3.select("#point_" + id).attr("r",3);
				d3.select("#line_" + id).style("background-color",resetColor);			

			}
				
		}
		

	
	</script>

	<style>

	/* Miscellaneous styles */
	
	.downSort img {
		width: 16px;
		height: 16px;
		margin-left: 4px;
		border: 1px solid #fff;
		vertical-align: bottom;
		cursor: pointer;
	}

	/* Body layout styles */

	body, html {
		margin:0px;
		padding:0px;
		overflow: auto;
		height: 100%;
		width: 100%;
	}

	#header {
		width: 100%;
		font-family: "Gill Sans", "Gill Sans MT", Calibri, sans-serif;
		margin: 0px;
		padding: 0px;
		left: 0px;
		top: 0px;
		border-bottom: 4px solid #003366;
	}		

	#header .title {
		position: relative;
		font-size: 28px;
		color: #003366;
		margin-bottom: 3px;
	}
	
	#responseTypes {
		height: 100%;
		display: inline;
	}
	
	.option {
		display: inline;
	}
	
	#mapBodyContainer {
		width: 650px;
		height: 600px;
		position: relative;
		margin: 0px;
		padding: 0px;
		left: 0px;
		float: left;
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
	
	.mapSvg {
		width: 648px;
		height: 568px;
	}
	
	#floor2 {
		visibility: visible;
	}

	#floor3 {
		visibility: hidden;

	}

	#floor4 {
		visibility: hidden;

	}

	/* Comment box layout styles */

	.commentContainer {
		width:277px;
		height: 100px;
		position: absolute;
		border-top: 1px solid #FFCC66;

	}

	.panelContainer {
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
	
	/* Responses table style */
	
	#responsesContainer {
		width:calc(100% - 655px);
		height: 100%;
		margin:0px;
		padding:0px;
		float:right;
		overflow-y: scroll;
	}

	#responsesContainer:after {
		clear: both;
	}

	#responsesTable {
		width: 100%;
	/* 	height: 100%; */
		margin: 0px;
		padding: 0px;
		overflow: auto;
		font-size: 14px;
		display: table;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
		width: 100%;
		margin:0px;
		padding: 0px;
	
	}


	#responsesTable .date {
		width: 20%;
		display: table-cell;
		padding: 5px;
		overflow: auto;
	}

	#responsesTable .comment {
		width: 38%;
		display: table-cell;
		padding: 5px;
		overflow: auto;
	}

	#responsesTable .xCoords {
		width: 14%;
		display: table-cell;
		padding: 5px;
		text-align: center;
		overflow: auto;
	}

	#responsesTable .yCoords {
		width: 14%;
		display: table-cell;
		padding: 5px;
		overflow: auto;
		text-align: center;
	}

	#responsesTable .responseType {
		width: 14%;
		display: table-cell;
		padding: 5px;
		overflow: auto;
		text-align: center;
	}
	
	#responsesHeader {
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
		width: 100%;
		overflow: auto;
		background-color: #294052;
		color: #ffffff;
		font-size: 14px;
		padding: 0px;
		display: table;
		font-weight: bold;
	}

	#responsesHeader .date {
		width: 20%;
		display: table-cell;
		padding: 5px;
	}

	#responsesHeader .comment {
		width: 38%;
		display: table-cell;
		padding: 5px;
	}

	#responsesHeader .xCoords {
		width: 14%;
		display: table-cell;
		padding: 5px;
		text-align: center;

	}

	#responsesHeader .yCoords {
		width: 14%;
		display: table-cell;
		text-align: center;
		padding: 5px;
	}
	
	#responsesHeader .responseType {
		width: 14%;
		display: table-cell;
		text-align: center;
		padding: 5px;
	}


	#responsesTable .row1 {
		width: 100%;
		background-color: #F9F6F4;
		padding: 5px;
		display: table-row;
		margin: 0px;
	}

	#responsesTable .row2 {
		width: 100%;
		background-color: #E9E0DB;
		padding: 5px;
		display: table-row;
		margin: 0px;
	}

	#responsesTable .row1:hover, #responsesTable .row2:hover {
		background-color: #D9ECF5;
	}
	
	#responsesContainer .sectionHeader {
		width: 100%;
		font-size: 25px;
		font-weight: bold;
		text-align: left;
		padding: 5px 0px 5px 0px;
		border-bottom: 2px solid #153450;
		margin: 0px;
		margin-top: 5px;
		color:#153450;
	/* 	text-indent: 5px; */
		clear: both;
	}	
	
	
</style>
</head>
<body>

	<div id="header">
		<div class="title">
		Map Survey Responses
		</div>

	</div>
	<div id="mapBodyContainer">
		<div id="tabContainer">
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
	<div id="responsesContainer">
		<div id="responsesHeader">
			<div class='date'>Date<span onclick='javascript:sortResults(selectedFloor,"dateStamp");' class='downSort'><img src='inc/downsort.png'></span></div>
			<div class='comment'>Comment</div>
			<div class='xCoords'>X Coords<span onclick='javascript:sortResults(selectedFloor,"xCoords");' class='downSort'><img src='inc/downsort.png'></span></div>
			<div class='yCoords'>Y Coords<span onclick='javascript:sortResults(selectedFloor,"yCoords");' class='downSort'><img src='inc/downsort.png'></span></div>
			<div class='responseType'>Type: 
				<div id='responseTypes'>
					<span class='option'><input type='checkbox' id='patrons-checkbox' value='user-response' checked=true>U</span>
					<span class='option'><input type='checkbox' id='staff-checkbox' value='staff-survey'>S</span>
				</div>

			
			</div>
		</div>
		<div id="responsesTable">
		</div>
	</div>
	
	<script>
	
		// Here, we use D3 to dynamically generate points on each map indicating the locations
		// of submitted comments via xy coordinates
		
		var svg_fl2 = d3.selectAll('#floor2')
			.append('svg')
			.attr("id","map-2")
			.attr("class","mapSvg");
			
		var map_fl2 = svg_fl2.append("image")
			.attr("xlink:href","floorplans/floor_2-sm.png")
			.attr("width",650)
			.attr("height",570);


		var svg_fl3 = d3.selectAll('#floor3')
			.append('svg')
			.attr("id","map-3")
			.attr("class","mapSvg");
			
		var map_fl3 = svg_fl3.append("image")
			.attr("xlink:href","floorplans/floor_3-sm.png")
			.attr("width",650)
			.attr("height",570);

		var svg_fl4 = d3.selectAll('#floor4')
			.append('svg')
			.attr("id","map-4")
			.attr("class","mapSvg");
			
		var map_fl4 = svg_fl4.append("image")
			.attr("xlink:href","floorplans/floor_4-sm.png")
			.attr("width",650)
			.attr("height",570);

		function clickFunction(coords) {
			pointCounter++;
			var getX = coords[0];
			var getY = coords[1];
			svg.append("circle")
				.attr("cx", getX)
				.attr("cy", getY)
				.attr("r",5)
				.attr("fill","red")
				.attr("stroke", "#5B031A");
			d3.select("body")
				.append("div")
				.attr("id","commentBox_" + pointCounter);	
			d3.select("#commentBox_" + pointCounter)
				.append("div")
				.style("left", getX + 15 + "px")
				.style("top", (getY - 5) + "px")
				.attr("class","pointArrow");

			var div = d3.select("#commentBox_" + pointCounter)
				.append("div")
				.style("left",getX + 27 + "px")
				.style("top",(getY - 25) + "px")
				.style("position","absolute")

				.attr("class","textBox")
				.attr("id","commentDiv_" + pointCounter)

				.html(function() { return "<div class='commentFieldDiv'><textarea class='commentField' id='commentField_" + pointCounter + "' onclick='inputComment(" + pointCounter + ")'>Click here to share your thoughts about this space.</textarea></div><div class='commentSubmitDiv'><input class='submitCommentButton' type='button' onclick='submitComment(" + getX + "," + getY + "," + pointCounter + ")'></div>"; });


	
				
		}


</script>


</body>
</html>