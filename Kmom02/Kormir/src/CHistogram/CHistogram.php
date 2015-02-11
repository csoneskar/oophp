<?php
/**
 * A CHistogram class to print the result as a diagram
 *
 */
Class CHistogram {
	/**
	* Contructor
	*/
	public function __construct() {
	echo __METHOD__;
	}
	
	
	//Prepare the histogram for printing
	private function PrepareHistogram($rolls) {
		$countedArray = array_count_values($rolls);
		ksort($countedArray);
		return $countedArray;
	}
	
	//Creates the histogram
	public function GetHistogram($rolls) {
		$countedArray = $this->PrepareHistogram($rolls);
		return $countedArray;
	}
	
	//Creates histogram and includes empty
	public function GetHistogramIncludeEmpty($rolls, $faces) {
		$countedArray = $this->PrepareHistogram($rolls);
		$faceArray = array_fill(1, $faces, 0);
		$combinedArray = array_replace($faceArray, $countedArray);
		return $combinedArray;
	}
	
	//Print the histogram
	public function PrintHistogram($array) {
		foreach($array as $key => $val) {
			echo "<p>$key. ";
			for($i=0; $i<$val; $i++) {
				echo "*";
			}
			echo "</p>";
		}
	}
	
	/**
	 * Destructor
	 */
	public function __destruct() {
	  echo __METHOD__;
	}
}