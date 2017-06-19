function getfile(type){
	document.getElementById(type).click();
}

function getvalue(type){
	selectImage(type);
    // document.getElementById('selectedfile').value=document.getElementById(type).value
    //document.getElementById('selectedfile').style.display='inline';
  
} 
function selectImage(type) {

	
	var imageType=/^image\//;
	var image= document.getElementById(type);
	var preview=document.getElementById("preview");
	var file=image.files[0];
	if(!imageType.test(file.type))
		return false;
	
	var img=document.createElement("img");
	var old=document.getElementById("image");
	if(old)
		{
		preview.removeChild(old);
		}
	
	img.file=file;
	img.setAttribute("id", "image");
	preview.appendChild(img);
	var reader=new FileReader();
	reader.onload=(function(aImg) {return function(e){aImg.src=e.target.result;};})(img);
	reader.readAsDataURL(file)
		
}