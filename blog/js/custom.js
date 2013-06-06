/**
*Function to confirm permanent actions. use onClick.
*/

function confirmSubmit(){
	var agree = confirm("Deleting a comment is permanent - there is no undo. Are you sure?");
	if(agree)
		return true;
	else
		return false;

}

