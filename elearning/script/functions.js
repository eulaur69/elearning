function checkInputs(){
    profesori = document.getElementsByClassName("profesori");
    materii = document.getElementsByClassName("materii-select")
    for(i = 0;i<profesori.length;i++){
        if(profesori[i].value == "0" || materii[i].value == "0"){
            alert("Nu pot exista valori goale.");
            return false;
        }
    }
    if(materii[profesori.length-1].value != "0" && profesori[profesori.length-1].value == "0"){
        alert("Nu pot exista valori goale.");
        return false;
    }
    enableAllOptions();
    return true;
}

function openOrCloseModalMaterii(){
    modal = document.getElementById("modalMaterii");
    styleDisplay = modal.style.display;
    styleDisplay = styleDisplay.trim();
    if(styleDisplay != "none" && styleDisplay != "block"){
        styleDisplay = "none";
    }
    if (styleDisplay != "none") {
        modal.style.display = "none";
      } else if (styleDisplay == "none") {
        modal.style.display = "block";
      }
}

function deleteMaterieFromDOM(element){
    list = document.getElementById("materiiList");
    element = element.parentNode.parentNode;
    list.removeChild(element);
}

function showAddMenuItem(item){
    forms = document.getElementsByClassName("add-form");
    for(i = 0;i<forms.length;i++){
        console.log(forms[i].id);
        forms[i].style.display = "none";
    }
    form = document.getElementById(item);
    form.style.display = "block";
    succesMessages = document.getElementsByClassName("succes-message");
    for(i = 0;i<succesMessages.length;i++){
        succesMessages[i].style.display = "none";
    }
    errorMessage = document.getElementById("error-message");
    if(errorMessage != null){
        errorMessage.parentNode.removeChild(errorMessage);
    }
    localStorage.setItem("lastAddMenuOption",item)
}

function showEditMenuItem(item){
    tableDivs = document.getElementsByClassName("edit-table");
    for(i = 0;i<tableDivs.length;i++){
        tableDivs[i].style.display = "none";
    }
    tableDiv = document.getElementById(item);
    tableDiv.style.display = "block";
    console.log(item);
    succesMessage = document.getElementById("succes-message");
    if(succesMessage != null){
        succesMessage.parentNode.removeChild(succesMessage);
    }
    errorMessage = document.getElementById("error-message");
    if(errorMessage != null){
        errorMessage.parentNode.removeChild(errorMessage);
    }
    localStorage.setItem("lastEditMenuOption",item)
}

function showLoginMenuItem(item){
    forms = document.getElementsByClassName("login-form");
    for(i = 0;i<forms.length;i++){
        forms[i].style.display = "none";
    }
    form = document.getElementById(item);
    form.style.display = "block";
    succesMessages = document.getElementsByClassName("succes-message");
    for(i = 0;i<succesMessages.length;i++){
        succesMessages[i].style.display = "none";
    }
    errorMessage = document.getElementById("error-message");
    if(errorMessage != null){
        errorMessage.parentNode.removeChild(errorMessage);
    }
    localStorage.setItem("lastLoginOption",item)
}

function showProfileItem(item){
    forms = document.getElementsByClassName("profile-item");
    for(i = 0;i<forms.length;i++){
        forms[i].style.display = "none";
    }
    form = document.getElementById(item);
    form.style.display = "block";
    succesMessages = document.getElementsByClassName("succes-message");
    for(i = 0;i<succesMessages.length;i++){
        succesMessages[i].style.display = "none";
    }
    errorMessage = document.getElementById("error-message");
    if(errorMessage != null){
        errorMessage.parentNode.removeChild(errorMessage);
    }
    localStorage.setItem("lastProfileOption",item)
}