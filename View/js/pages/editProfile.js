function editProfile(){
    const username   = document.getElementById("0").value.trim();
    const bio        = document.getElementById("1").value.trim();
    const birthdate  = document.getElementById("2").value.trim();
    const profilePic = document.getElementById("profile_pic").files[0];
    const banner     = document.getElementById("banner").files[0];

    if(!username || username.length < 3 || username.length > 50) return alert("Nom d'utilisateur invalide");
    if(bio.length > 160) return alert("Bio trop longue");
    if(!birthdate) return alert("Date de naissance invalide");

    let formData = new FormData();

    formData.append("username", username);
    formData.append("bio", bio);
    formData.append("birthdate", birthdate);
    
    if(profilePic) formData.append("profile_pic", profilePic);
    if(banner) formData.append("banner", banner);

    fetch("Edit", {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(result => {
      if(result.exists) alert("Nom d'utilisateur déjà pris");
      else if(result.success) location.reload();
      else if(result.error) alert(result.error);
    })
    .catch(() => alert("Erreur lors de la modification"));
}
document.getElementById("saveEdit").addEventListener("click", editProfile);
