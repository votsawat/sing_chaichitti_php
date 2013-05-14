<?php
//a helper function to display errors for form fields
function display_error( $array, $key ){
	if( isset( $array[$key]) ){
		$message = $array[$key];
		echo "<div class='error message'>$message</div>";
	}
}

//keep the value of a field after a form is submitted
function sticky_field( $field ){
	if( isset($field) ){
		echo $field;

	}

}

//for sticky checkboxes
function checked( $expected, $actual){
	if( $actual == $expected ){
		echo 'checked="checked"';
		
	}
}

//no close php