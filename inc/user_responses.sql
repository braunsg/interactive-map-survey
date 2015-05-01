-- CREATOR
-- Steven Braun
-- braunsg
-- 
-- REPO
-- interactive-map-survey
-- 
-- ORIGINAL CREATION DATE
-- 2014-08-22
--
-- DESCRIPTION
-- Definition for table holding data from map survey responses

-- Table structure for table `user_responses`
--

CREATE TABLE IF NOT EXISTS `user_responses` (
  `ind` int(11) NOT NULL AUTO_INCREMENT,
  `dateStamp` datetime NOT NULL,
  `xCoords` int(5) NOT NULL,
  `yCoords` int(5) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `floor` varchar(1) NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`ind`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
