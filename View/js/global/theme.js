
function loadTheme(){
    fetch("Edit", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({ldTheme:"ldTheme"})
    })
    .then(response=>{
        if (!response.ok){
            throw new Error("ajax fail !")
        }
        return response.json()
    })
    .then(data=>{
        if(data["theme"]==1){
            document.body.style.backgroundColor = ""
        } else {
            document.body.style.backgroundColor = "gray";
        }

    })
    .catch(error=>console.error("Erreur:",error));   
}


function changeTheme(){
    // 1) ajax,getting the current users theme
    // 2) apply the changed theme

    fetch("Edit", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({cgTheme:"cgTheme"})
    })
    .then(response=>{
        if (!response.ok){
            throw new Error("ajax fail !")
        }
        return response.json()
    })
    .then(data=>{
        if(data["theme"]==1){
            document.body.style.backgroundColor = "gray";
        } else {
            document.body.style.backgroundColor = ""
        }

    })
    .catch(error=>console.error("Erreur:",error));
}

loadTheme()