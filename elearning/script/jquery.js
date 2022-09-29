function disableSubject(val){
    document.querySelectorAll(".materii-select option").forEach(opt => {
        if (opt.value == val) {
            console.log(opt.value)
            opt.disabled = true;
        }
    });
}

function disableSelectedSubjects(){
    console.log("CALLED DISABLE ALL");
    document.querySelectorAll(".materii-select option").forEach(opt => {
        opt.disabled = false;
    });
    for(i = 0;i<materiiArray.length;i++){
        disableSubject(materiiArray[i].value);
    }
}

function addAsociereDeleteEvents(){
    asociereDeletes = document.getElementsByClassName("asociere-delete-row");
    if(asociereDeletes != null){
        asociereTable = document.getElementById("asociereTable");
        for(i = 0;i<asociereDeletes.length;i++){
            deleteButton = asociereDeletes[i];
            deleteButton.onclick = function(){
                index = this.parentNode.parentNode.rowIndex;
                asociereTable.deleteRow(index);
                disableSelectedSubjects()
            }
        }
    }
}

function refreshIndexes(){
    rows = document.getElementById("asociereTable").rows;
    k = 1;
    for(i = 1;i<rows.length-1;i++){
        rows.cells[0].textContent = k;
        k++;
    }
}

function fetch_profesori(val,index){
    if(val != ""){
        $.ajax({
            type: 'post',
            url: 'phpscripts/fetch_profesori.php',
            data: {
                materie:val
            },
            success: function (response) {
                materiiArray = document.getElementsByClassName("materii-select");
                materiiValues = document.getElementsByClassName("materii-values");
                profesoriArray = document.getElementsByClassName("profesori");
                console.log("PROF LEN" + profesoriArray.length);
                index = -1;
                for(i = 0;i<materiiArray.length;i++){
                    materiiValues[i].value = materiiArray[i].value;
                    if(materiiArray[i].value == val){
                        index = i;
                    }
                }
                console.log("PROF INDEX : "+  index);
                console.log(profesoriArray.length);
                console.log(profesoriArray);
                selectProfesori = profesoriArray[index];
                selectProfesori.innerHTML = response;
                disableSelectedSubjects()
            }
        });
    }
}


function add_row(val){
    profesoriArray = document.getElementsByClassName("profesori");
    $.ajax({
        url: "phpscripts/add_row.php",
        type: "post",
        data: {
            index:val
        },
        success: function(response){
            console.log(response);
            //$("#asociereTable").find('tbody').append(response);
            $("#asociereTable").closest('table').find('tr:last').prev().after(response);
            disableSubject(val);
            disableSelectedSubjects()
            addAsociereDeleteEvents();
            refreshIndexes();
        }
        });
}

function deleteRow(id){
    $.ajax({
        url: "phpscripts/delete_row.php",
        type: "post",
        data: {
            id: id
        },
        success: function(response){
        }
    });
}

function addMaterie(prof){
    select = document.getElementById("selectAddMaterii");
    materie = select.value;
    $.ajax({
        url: "phpscripts/add_materie.php",
        type: "post",
        data: {
            materie: materie,
            prof: prof
        },
        success: function(response){
            console.log(response);
            list = document.getElementById("materiiList");
            newContent = document.createElement("div");
            newContent.className = "list-item";
            newContent.innerHTML = response;
            list.insertBefore(newContent,list.lastChild);
            modal = document.getElementById("modalMaterii");
            modal.style.display = "none";
        }
    });
}



function deleteMaterie(element, materie, prof){
    $.ajax({
        url: "phpscripts/delete_materie.php",
        type: "post",
        data: {
            materie: materie,
            prof: prof
        },
        success: function(response){
            list = document.getElementById("materiiList");
            element = element.parentNode.parentNode;
            list.removeChild(element);
            console.log(response);
        }
    });
}

function addMaterieInDOM(){
    select = document.getElementById("selectAddMaterii");
    idMaterie = select.value;
    listItems = document.getElementsByClassName("list-item-content");
    for(i = 0;i<listItems.length;i++){
        if(listItems[i].tagName == "div"){
            continue;
        }
        if(idMaterie == listItems[i].value){
            return;
        }
    }
    $.ajax({
        url: "phpscripts/fetch_materie.php",
        type: "post",
        data: {
            materie: idMaterie
        },
        success: function(response){
            list = document.getElementById("materiiList");
            newElement = document.createElement("div");
            newElement.className = "list-item"
            newElement.innerHTML = response;
            list.insertBefore(newElement,list.firstChild);
            modal = document.getElementById("modalMaterii");
            modal.style.display = "none";
        }
    });
}

function populateMateriiSelect(clasa,idProf,pageType){
    $.ajax({
        url: "phpscripts/fetch_all_materii.php",
        type: "post",
        data: {
            clasa: clasa,
            profesor: idProf,
            type : pageType
        },
        success: function(response){
            materiiSelect = document.getElementById("materieEdit");
            noteContent = document.getElementById("noteContent");
            absenteContent = document.getElementById("absenteContent")
            if(materiiSelect){
                parent = materiiSelect.parentNode;
                parent.removeChild(materiiSelect);
            }
            if(noteContent){
                parent = noteContent.parentNode;
                parent.removeChild(noteContent);
            }
            if(absenteContent){
                parent = absenteContent.parentNode;
                parent.removeChild(absenteContent);
            }
            form = document.getElementById("catalog");
            newElement = document.createElement("div");
            newElement.innerHTML = response;
            catalog.appendChild(newElement);
        }
    });
}

function generateGradesTable(clasa,materie){
    $.ajax({
        url: "phpscripts/fetch_grades.php",
        type: "post",
        data: {
            clasa: clasa,
            materie: materie
        },
        success: function(response){
            noteContent = document.getElementById("noteContent");
            if(noteContent){
                parent = noteContent.parentNode;
                parent.removeChild(noteContent);
            }
            form = document.getElementById("catalog");
            newElement = document.createElement("div");
            newElement.id = "noteContent"
            newElement.innerHTML = response;
            form.appendChild(newElement);
        }
    });
}


function generateAbsencesTable(clasa,materie){
    $.ajax({
        url: "phpscripts/fetch_absente.php",
        type: "post",
        data: {
            clasa: clasa,
            materie: materie
        },
        success: function(response){
            noteContent = document.getElementById("absenteContent");
            if(noteContent){
                parent = noteContent.parentNode;
                parent.removeChild(noteContent);
            }
            form = document.getElementById("catalog");
            newElement = document.createElement("div");
            newElement.id = "absenteContent"
            newElement.innerHTML = response;
            form.appendChild(newElement);
        }
    });
}

function generateTabelAsociere(clasa){
    $.ajax({
        url: "phpscripts/fetch_tabel_asociere.php",
        type: "post",
        data: {
            clasa: clasa,
        },
        success: function(response){
            noteContent = document.getElementById("asociereContent");
            if(noteContent){
                parent = noteContent.parentNode;
                parent.removeChild(noteContent);
            }
            form = document.getElementById("asociere");
            newElement = document.createElement("div");
            newElement.id = "asociereContent"
            newElement.innerHTML = response;
            form.appendChild(newElement);
            disableAllSelectedSubjects();
            addAsociereDeleteEvents();
        }
    });
}


function validatePersonalInformation(formId){
    console.log("FORM ID : " + formId);
    var result = false;
    if(formId == "adaugareMaterie"){
        if (($('#materie').val().length > 0) &&
            ($('#cod').val().length  > 0) &&
            ($('#descriere').val().length > 0)) {
                result = true;
        }
    } else {
        if (($("#" + formId + ' #nume').val().length > 0) &&
            ($("#" + formId + ' #prenume').val().length  > 0) &&
            ($("#" + formId + ' #cnp').val().length == 13) &&
            ($("#" + formId + ' #judet').val().length  > 0) &&
            ($("#" + formId + ' #localitate').val().length > 0) &&
            ($("#" + formId + ' #strada').val().length > 0) &&
            ($("#" + formId + ' #numar').val().length  > 0) &&
            ($("#" + formId + ' #cod_postal').val().length == 6) &&
            ($("#" + formId + ' #cetatenie').val().length > 0) &&
            ($("#" + formId + ' #nationalitate').val().length  > 0)) {
                result = true;
            }
    }
    if(formId == "adaugareElev"){
        if($("#" + formId + ' #clasa').val() != null){
            result = true;
            console.log("E CLASA");
        } else console.log("NU E CLASA");
    }
    
    number_inputs = $("form#"+formId+" :input[type='number']");
    console.log("NUM IMPUTS : " + number_inputs.length);
    for(i = 0;i<number_inputs.length;i++){
        value = number_inputs.val();
        if(value <= 0 || (value - Math.floor(value) > 0)){
            result = false;
        }
    }
    if(result == false && document.getElementById("error-message") == null){
        content = document.getElementById("content");
        form = document.getElementById(formId);
        message = document.createElement("p");
        message.id = "error-message";
        message.innerHTML = "Exista date lipsa sau invalide."
        form.parentNode.insertBefore(message,form.nextSibling);
    }  
    
    console.log(number_inputs);
    return result;
}

function disableAllSelectedSubjects(){
    materiiArray = document.getElementsByClassName("materii-select");
    for(i = 0;i<materiiArray.length;i++){
        disableSubject(materiiArray[i].value);
    }
}

$(document).ready(function(){
    disableAllSelectedSubjects();
});