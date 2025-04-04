
function dropDownLogout(){
    let logoutBox = document.querySelector(".logout-btn");

    if (logoutBox) {
        logoutBox.remove();
    }

    const box = document.querySelector(".logout-box");
    logoutBox = document.createElement("div"); 
    logoutBox.classList.add("logout-box","logout-btn");
  
    logoutBox.style.width = "90%";
    logoutBox.innerHTML = `
        <p style="text-align:center;color:red;margin:0;">Se déconnecter</p>
    `;
    
    box.appendChild(logoutBox);

    // logout event

    logoutBox.addEventListener("click", () => {
        logout();
    });
}

function resetDropDown(){
    const logoutBox = document.querySelector(".logout-btn");
    if (logoutBox) {
        logoutBox.remove();
    }
}

function logout(){
    fetch("Redirect", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({logout:"logout"})
    })
    .then(response=>{
        if (!response.ok){
            throw new Error("ajax fail !")
        }
        return response.json()
    })
    .then(data=>{
        console.log(data);
        window.location.href = "login";
        
    })
    .catch(error=>console.error("Erreur:",error));
    
}