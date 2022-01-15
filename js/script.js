function selectDir(targetE)
{
    // <DOM> receive the name of directory selected (folder name)
    let dirSelected = targetE.firstElementChild.nextElementSibling.innerHTML; // i guess it could be something wrong here!!!!!!!!!!
    console.log(dirSelected);
    let usrPath = document.querySelector("#usrPath");
    let xjs = new XMLHttpRequest();

    if ((dirSelected.slice(-4) != ".mp4") && (dirSelected.slice(-4) != ".pdf") && (dirSelected.slice(-4) != ".zip") && (dirSelected.slice(-4) != ".rar"))
    {
        // prepare request to <apatch server> using <Ajax>
        let newTbody = "";
        xjs.open("GET","http://este.ovh/moocs/php/access.php?dir="+dirSelected)+"&ext=";
        // xjs.open("GET","http://localhost/Moocs_webApp/php/access.php?dir="+dirSelected)+"&ext=";
        xjs.onreadystatechange = (e)=>{
            if((e.target.readyState == 4) && (e.target.status == 200))
            {
                if(e.target.responseText != "empty")
                {
                    newTbody = JSON.parse(xjs.response);  //receive (json array)
                    usrPath.innerHTML = usrPath.innerHTML + "/" + dirSelected;
                    injectToTbody(newTbody);
                }else {
                    document.querySelector("#errorHeader span").innerHTML = dirSelected;
                    document.querySelector("#errorEmpty").style.display = "flex";
                }
            }
        }
        xjs.send();
    }else if(dirSelected.slice(-4) == ".mp4") {
            let newSection = null;

            //prepare request to <apatch server>
            xjs.open("GET","http://este.ovh/moocs/php/generateVideo.php?vd="+dirSelected);
            xjs.onreadystatechange = (e)=>{
                if((e.target.readyState == 4) && (e.target.status == 200))
                {
                    newSection = xjs.responseText; //receive the <source div> (string)
                    injectToSection(newSection);
                }
            }
            xjs.send();
    }else if(dirSelected.slice(-4) == ".pdf") {
        location.href = "http://este.ovh/moocs/php/openPdf.php?file="+dirSelected+"&ext=pdf";
    }else if((dirSelected.slice(-4) == ".zip") || (dirSelected.slice(-4) == ".rar")) {
        console.log(dirSelected.slice(-4));
        console.log(dirSelected);
        location.href = "http://este.ovh/moocs/php/openPdf.php?file="+dirSelected+"&ext=zip";
    }
}

function backwardPath()
{
    const usrPath = document.querySelector("#usrPath");
    const usrPathArr = usrPath.innerHTML.split("/"); // <string> to <array>
    let newTbody = null;

    if(usrPathArr[usrPathArr.length-1] != "Moocs")
    {
        let xjs = new XMLHttpRequest();
        xjs.open("GET","http://este.ovh/moocs/php/backward.php?path="+usrPath.innerHTML);
        // xjs.open("GET","http://localhost/Moocs_webApp/php/backward.php?path="+usrPath.innerHTML);
        xjs.onreadystatechange = (e)=>{
            if((e.target.readyState == 4) && (e.target.status == 200))
            {
                newTbody = JSON.parse(xjs.response);
                injectToTbody(newTbody);

                // modify path in our page
                usrPathArr.splice(usrPathArr.length-1 , 1); //delete the last array element
                usrPath.innerHTML =  usrPathArr.join("/");// convert this <array> to <string> with "/" as separator
            }
        }
        xjs.send();
    }
}

function forwardPath() 
{
    let newTbody;
    let usrPath = document.querySelector("#usrPath");
    let xjs = new XMLHttpRequest();
    xjs.open("GET","http://este.ovh/moocs/php/farward.php?perm=Ok");
    // xjs.open("GET","http://localhost/Moocs_webApp/php/farward.php?perm=Ok");
    xjs.onreadystatechange = (e)=>{
        if((e.target.readyState == 4) && (e.target.status == 200))
        {
            newTbody = xjs.response;
            if(newTbody != "empty")
            {
                let jsonData = JSON.parse(newTbody);
                usrPath.innerHTML= jsonData.splice(jsonData.length-1,1)[0];
                injectToTbody(jsonData);
            }
        }
    }
    xjs.send();
}

// save bookmark
function saveBookMark()
{
    
}

// search cours
function searchRep(element)
{
    // get the user input 
    let usrInput = element.value;

    // get all cours <folders> 
    let coursFol = document.querySelectorAll(".courstitle"); 

    coursFol.forEach((e)=>{
        if(!e.innerHTML.toLowerCase().includes(usrInput.toLowerCase()))
        {
            e.parentElement.style.display = "none";
            // console.log(e.innerHTML.substring(0,usrInput.length));
        }
        else
            e.parentElement.style.display = "table-row";
    })
}

// inject the response to tbody div <DOM>
function injectToTbody(newTbody)
{
    tbodyDiv = document.querySelector("body>main>table>tbody");

    tbodyDiv.innerHTML = "";
    let tr = null;
    for(let e of newTbody)
    {
        tr = document.createElement('tr');
        tr.setAttribute("onclick","selectDir(this)");
        tr.innerHTML = e;
        tbodyDiv.appendChild(tr);
    }
}

function injectToSection(newVidoeSrc)
{
    let bodySection = document.querySelector("section");
    let videoDiv = document.createElement("video");
    videoDiv.setAttribute("width","100%");
    videoDiv.setAttribute("height","97%");
    videoDiv.setAttribute("controls","controls");
    videoDiv.innerHTML = newVidoeSrc;
    bodySection.innerHTML = "";
    bodySection.appendChild(videoDiv);
}

function closeErr(element)
{
    let errorDib = document.querySelector("#errorEmpty");
    errorDib.style.display = "none";
}