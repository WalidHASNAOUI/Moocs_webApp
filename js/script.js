
function selectDir(targetE)
{
    // <DOM> receive the name of directory selected (folder name)
    let dirSelected = targetE.firstElementChild.nextElementSibling.innerHTML;
    let usrPath = document.querySelector("#usrPath");
    let xjs = new XMLHttpRequest();

    if(dirSelected.slice(-4) != ".mp4")
    {
        // prepare request to <apatch server> using <Ajax>
        let newTbody = "";
        xjs.open("GET","http://localhost/TPS/miniProject/php/access.php?dir="+dirSelected);
        xjs.onreadystatechange = (e)=>{
            if((e.target.readyState == 4) && (e.target.status == 200))
            {
                newTbody = JSON.parse(xjs.response);  //receive (json array)
                usrPath.innerHTML = usrPath.innerHTML + "/" + dirSelected;
                injectToTbody(newTbody);
            }
        }
        xjs.send();
    }else {
        let newSection = null;

        //prepare request to <apatch server>
        xjs.open("GET","http://localhost/TPS/miniProject/php/generateVideo.php?vd="+dirSelected);
        xjs.onreadystatechange = (e)=>{
            if((e.target.readyState == 4) && (e.target.status == 200))
            {
                newSection = xjs.responseText; //receive the <source div> (string)
                injectToSection(newSection);
            }
        }
        xjs.send();
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
        xjs.open("GET","http://localhost/TPS/miniProject/php/backward.php?path="+usrPath.innerHTML);
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
    xjs.open("GET","http://localhost/TPS/miniProject/php/farward.php?perm=Ok");
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