var noImg = Math.floor(Math.random()*3)+1;

function changeImg()
{
	noImg++; if(noImg>3) noImg = 1;
	var oImg = "<img src=\"gallery/00"+noImg+".jpg\" class=\"img-fluid\"/>";
	document.getElementById("slider").innerHTML = oImg;
	setTimeout("changeImg()",5000);
}