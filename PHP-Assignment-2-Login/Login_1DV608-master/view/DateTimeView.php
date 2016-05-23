<?php

class DateTimeView {


	public function show() {

		$timeString = date("l").", the ".date("j").$this->getSuffix(date("j"))." of ".date("F")." ".date("Y").", The time is ".date("H:i:s");

		return '<p>' . $timeString . '</p>';
	}

	//check what suffix should be added to the date
	public function getSuffix($date) {
		if($date == 1 || $date == 21 || $date == 31)
			return "st";
		else if($date == 2 || $date == 22)
			return "nd";
		else if($date == 3 || $date == 23 )
			return "rd";
		else
			return "th";

	}
}