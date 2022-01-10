//var countSelected = 0;

function eventTrigger(id) {
  var str = "#id-";
  var selector = str.concat(id);
  if ($(selector).hasClass("marked")) {
    $(selector).removeClass("marked");
    $(selector).css("border", "4px solid #666");
	arrSelected[id] = false;
	//countSelected -= 1;
  } else {
    $(selector).addClass("marked");
    $(selector).css("border", "4px solid #76EE00");
	arrSelected[id] = true;
	//countSelected += 1;
  }
  //toggleSubmitButton();
}

/*function toggleSubmitButton() {
	var selector = "#id--1";
	if (countSelected < 1)
		$(selector).css("display",  "none");
	else
		$(selector).css("display",  "block");
}*/

function triggerFromArray() {
	var i;
	for(i=1; i<arrSelected.length; i++){
		if(arrSelected[i] == true)
			eventTrigger(i);
	}
}

function submitSelection() {
	//generate link
	var argsOfGet = "";
	var max = -1;
	var i;
	for(i=0; i<arrSelected.length; i++){ //arrSelected was declared from within html code
		if(arrSelected[i] == true){
			argsOfGet += "&" + i + "=1";
			max = (i>max)? i : max;
		}
	}
	//redirect
	window.location.href = "productsSelected.php?li=" + max + argsOfGet;
}
