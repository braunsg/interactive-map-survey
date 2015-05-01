# interactive-map-survey

Created by Steven Braun, University of Minnesota Libraries

SHORT DESCRIPTION:
Template for creating a clickable map survey where users can select locations on a map and input comments

LONG DESCRIPTION:
This is an interactive map survey that was created for the Bio-Medical Library at the University of Minnesota. The map features images of the floorplans of the library; users were invited to click on their favorite (or least) places in the library and add comments about those places.

The tool was also used by library staff to track the concentration of table/study area/computer use throughout the day.

The results/comments and x, y coordinates are collected in a MySQL table/database. The results are displayed as a density plot over the images of the floorplans.

PROJECT FILES:
This repository consists of the following files:

map-survey.php      PHP/HTML that generates the interactive map (GUI)
show-responses.php  PHP/HTML that displays data collected
inc/                directory of relevant include files, INCLUDING table SQL definition
floorplans/         directory of floorplan images used to create map
