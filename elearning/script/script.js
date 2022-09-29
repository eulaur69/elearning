function disableSubject(val){
    document.querySelectorAll(".materii-select option").forEach(opt => {
        if (opt.value == val) {
            console.log(opt.value)
            opt.disabled = true;
        }
    });
}

function disableSelectedSubjects(){
    document.querySelectorAll(".materii-select option").forEach(opt => {
        opt.disabled = false;
    });
    for(i = 0;i<materiiArray.length;i++){
        disableSubject(materiiArray[i].value);
    }
}

function refreshIndexes(){
    rows = document.getElementById("asociereTable").rows;
    k = 1;
    for(i = 1;i<rows.length-1;i++){
        rows[i].cells[0].textContent = k;
        k++;
    }
}

function addAsociereDeleteEvents(){
    asociereDeletes = document.getElementsByClassName("asociere-delete-row");
    console.log(asociereDeletes);
    if(asociereDeletes != null){
        asociereTable = document.getElementById("asociereTable");
        for(i = 0;i<asociereDeletes.length;i++){
            deleteButton = asociereDeletes[i];
            deleteButton.onclick = function(){
                index = this.parentNode.parentNode.rowIndex;
                asociereTable.deleteRow(index);
                disableSelectedSubjects()
                refreshIndexes();
            }
        }
    }
}

window.onclick = function(event) {
    var targetId = event.target.id;
    console.log(targetId);
    if (targetId.includes("modalBtn")) {
        var id = targetId.slice(8);
        var modalId = "modalCard" + id;
        var modal = document.getElementById(modalId);
        modal.style.display = "block";
    }
    if (targetId.includes("closeModal")) {
        var id = targetId.slice(10);
        var modalId = "modalCard" + id;
        var modal = document.getElementById(modalId);
        modal.style.display = "none";
    }
    if (targetId.includes("modalCard")) {
        var modal = document.getElementById(targetId);
        modal.style.display = "none";
    }
}


window.onload = function() {
    window.addEventListener('click', function() {
        loginButton = document.getElementById("log-in-button");
        mobileButton = document.getElementById("menu-button");
        mobileMenu = document.getElementById("mobile-menu");
        if (event.target.id != mobileButton.id) {
            document.getElementById("mobile-menu").style.transform = "translate(-101%)";
        }
        if (loginButton != null) {
            if (event.target.id != loginButton.id && event.target.id != "user-icon") {
                document.getElementById("profile-menu").style.display = "none";
            }
        }
    });
    button = document.getElementById("log-in-button");
    if (button != null) {
        button.addEventListener('click', function() {
            menu = document.getElementById("profile-menu");
            menu.style.display = "block";
        });
    }

    button = document.getElementById("menu-button");
    button.addEventListener('click', function() {
        menu = document.getElementById("mobile-menu");
        menu.style.transform = "translateX(0)";
    });

    addForms = document.getElementsByClassName("add-form");
    editTables = document.getElementsByClassName("edit-table");
    loginForms = document.getElementsByClassName("login-form");
    profileItems = document.getElementsByClassName("profile-item");
    if(addForms.length > 0){
        item = localStorage.getItem("lastAddMenuOption");
        if(item != null){
            for(i = 0;i<addForms.length;i++){
                addForms[i].style.display = "none";
            }
            formToBeShown = document.getElementById(item);
            formToBeShown.style.display = "block";
        }
    }
    if(editTables.length > 0){
        item = localStorage.getItem("lastEditMenuOption");
        if(item != null){
            for(i = 0;i<editTables.length;i++){
                editTables[i].style.display = "none";
            }
            formToBeShown = document.getElementById(item);
            formToBeShown.style.display = "block";
        }
    }
    if(loginForms.length > 0){
        item = localStorage.getItem("lastLoginOption");
        if(item != null){
            for(i = 0;i<loginForms.length;i++){
                loginForms[i].style.display = "none";
            }
            formToBeShown = document.getElementById(item);
            formToBeShown.style.display = "block";
        }
    }
    if(profileItems.length > 0){
        item = localStorage.getItem("lastProfileOption");
        if(item != null){
            for(i = 0;i<profileItems.length;i++){
                profileItems[i].style.display = "none";
            }
            formToBeShown = document.getElementById(item);
            formToBeShown.style.display = "block";
        }
    }

    addAsociereDeleteEvents();
};

