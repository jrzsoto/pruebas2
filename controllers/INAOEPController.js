//Carga los spinners en cada ajax request
$.ajaxSetup({
    beforeSend: function () {
        $('#spinner-div').show();
    },
    complete: function () {
        $('#spinner-div').hide();
    }
});


/*
$(function () {
    
getINAOEPRequest();
//getINAOEPRequestFromFile();

});
*/

$(function () {

    const loginCheck = JSON.parse(sessionStorage.getItem('user_data'));
    if (loginCheck === null) {
        window.location.href = 'index.html';
    }

    var param = $('script[src$="INAOEPController.js"]').data('param');

    if (param === 1) {
        getINAOEPRequest();
    } else if (param === 2) {
        getINAOEPRequestFromFile();
    }
});


//petición desde servicio web

function getINAOEPRequest() {
    const settings = {
        url: "models/INAOEPRequestModel.php",
        method: "POST",
        type: 0
    };

    $.ajax(settings).done(function (response) {

        processINAOEPRequests(response, 1);

    }).fail(function (error) {

        console.log(error);

    });
}

//petición desde archivo json

function getINAOEPRequestFromFile() {

    const settings = {
        url: "models/INAOEPFileModel.php",
        method: "POST",
        type: 0,
    };

    $.ajax(settings).done(function (response) {


        processINAOEPRequests(response, 2);

    }).fail(function (error) {

        console.log(error);

    });
}


function processINAOEPRequests(json_data, option) {

    // Decodifica el JSON

    if (option == 1) {
        var data = json_data;
    } else {
        var data = JSON.parse(json_data);
    }


    var counter = 0;
    var tempEmail = "";
    var curp = "";
    var secondLastName = "";

    // Itera sobre cada solicitud
    data.forEach(function (solicitud) {

        // Accede a los datos de la solicitud
        // var solicitud = solicitud.Solicitud;
        var newRow = document.getElementById('mainTable').insertRow();

        //bloque de validación de datos
        curp = solicitud.CURP;
        secondLastName = solicitud.Materno;

        if (curp == "" || curp == null) {
            curp = "null";
        }
        if (secondLastName === "" || secondLastName == null) {
            secondLastName = "null";
        }

        var data = {
            names: solicitud.Nombres,
            last_name: solicitud.Paterno,
            second_last_name: secondLastName,
            email: solicitud.Email,
            curp: curp,
            id_general: solicitud.IDgenerales,
            gender: solicitud.Sexo,
            birth_date: solicitud.FechaNacimiento,
            birth_place: solicitud.LugarNacimiento,
            mobile_number: solicitud.TelMovil,
            register_date: solicitud.FechaRegistro,
            nationality: solicitud.Nacionalidad,
            admission_period: solicitud.PeriodoIngreso,
            academic_area: solicitud.tipoPosgrado,
            degree: solicitud.Posgrado,
            country: solicitud.Pais,
            formaEval: solicitud.formaEval
        };

        let btn_save = "<button type='button' class='btn btn-outline-success' onclick='verifyDataSave(" + JSON.stringify(data) + ")'><i class='fa-solid fa-floppy-disk'></i></button>";
        let btn_show_data = "<button type='button' class='btn btn-outline-primary' onclick='showAllData(" + JSON.stringify(data) + ")'><i class='fa-solid fa-eye'></i></button>";
        let btn_verify_data = "<button type='button' class='btn btn-outline-primary' onclick='verifyData(" + JSON.stringify(data) + ")'><i class='fa-solid fa-check'></i></button>";

        let control_text = '';
        if (solicitud.control !== null) {

            if (solicitud.control.estado === 'Registrado correctamente') {
                btn_save = '';
                control_text = '<span class="text-success">Registrado correctamente</span>'
            }

            if (solicitud.control.estado === 'Correo electrónico repetido') {
                btn_save = '';
                control_text = '<span class="text-danger">Correo electrónico repetido</span>'
            }
        }

        newRow.innerHTML = '<td scope="row">' + solicitud.IDgenerales + '</td>' +
            '<td>' + solicitud.Nombres + '</td><td>' + solicitud.Paterno + '</td><td>' + solicitud.Materno + '</td>' +
            '<td>' + solicitud.CURP + '</td><td>' + solicitud.Email + '</td>' +'</td><td>' + control_text +
            '<td><div class="btn-group btn-group-sm" role="group" aria-label="Small button group">' +
            btn_save+btn_show_data+btn_verify_data
            '</div></td>';


        // //Creo que con esto eliminas las duplicadas, pero ya lo hago en el php
        // if (solicitud.IDgenerales != counter)
        // //  if(solicitud.Email !== tempEmail)
        // {
        //     newRow.innerHTML = '<td scope="row">' + solicitud.IDgenerales + '</td>' +
        //         '<td>' + solicitud.Nombres + '</td><td>' + solicitud.Paterno + '</td><td>' + solicitud.Materno + '</td>' +
        //         '<td>' + solicitud.CURP + '</td><td>' + solicitud.Email + '</td>' +
        //         '<td><div class="btn-group btn-group-sm" role="group" aria-label="Small button group">' +
        //         "<button type='button' class='btn btn-outline-success' onclick='verifyDataSave(" + JSON.stringify(data) + ")'><i class='fa-solid fa-floppy-disk'></i></button>" +
        //         "<button type='button' class='btn btn-outline-primary' onclick='showAllData(" + JSON.stringify(data) + ")'><i class='fa-solid fa-eye'></i></button>" +
        //         "<button type='button' class='btn btn-outline-primary' onclick='verifyData(" + JSON.stringify(data) + ")'><i class='fa-solid fa-check'></i></button>" +
        //         '</div></td>';

        //     tempEmail = solicitud.Email;
        //     counter = solicitud.IDgenerales;
        // }

    });

}


function saveData(data) {

    const settings = {
        url: "controllers/RequestController.php",
        method: "POST",
        type: 0,
        data: JSON.stringify(data)
    };

    $.ajax(settings).done(function (response) {

        msgSaveData(response);
        console.log(response);


    }).fail(function (error) {

        console.log("error", error);
        msgSaveData(error);

    });
}


function verifyDataSave(data) {

    const settings = {
        url: "controllers/VerifyController.php",
        method: "POST",
        type: 0,
        data: JSON.stringify(data)
    };


    $.ajax(settings).done(function (response) {


        console.log(response);

        if (response.data > 0) {
            switch (response.data) {
                case 1:
                    console.log(response.data);
                    msgVerifyDataDoble(data)
                    break;
                case 2:
                    console.log(response.data);
                    msgVerifyDataDoble(data);
                    break;
                case 3:
                    console.log(response.data);
                    msgVerifyDataDoble(data)
                    break;
                case 4:
                    console.log(response.data);
                    msgVerifyDataCurp(data);
                    break;
                case 17:
                    console.log(response.data);
                    msgVerifyDataAge(data);
                    break;
                default:
                    console.log(response.data);
                    console.log('no registrado');

            }
        } else {
            saveData(data);
            console.log('registrado');
            console.log(settings);
        }


    }).fail(function (error) {

        console.log("error", error);
        msgSaveData(error);

    });


}

function verifyData(data) {

    const settings = {
        url: "controllers/VerifyController.php",
        method: "POST",
        type: 0,
        data: JSON.stringify(data)
    };


    $.ajax(settings).done(function (response) {

        msgVerifyData(response);
        console.log(response);
        console.log(settings);


    }).fail(function (error) {

        console.log("error", error);
        msgSaveData(error);

    });


}

function showAllData(data) {

    var modalBody = document.getElementById("modalBody");
    modalBody.innerHTML = "";

    for (var key in data) {
        modalBody.innerHTML += "<p><strong>" + key + ":</strong> " + data[key] + "</p>";
    }

    $('#myModal').modal('show');
}


function msgSaveData(data) {

    var modalBody = document.getElementById("modalBody");


    modalBody.innerHTML = "";

    for (var key in data) {
        modalBody.innerHTML += "<p><strong>" + key + ":</strong> " + data[key] + "</p>";
    }

    $('#myModal').modal('show');
}


function msgVerifyData(data) {

    var modalBody = document.getElementById("modalBody");
    modalBody.innerHTML = " Validación de correo electrónico: ";


    for (var key in data) {
        modalBody.innerHTML = "ya esiste una solicitud de admision si es 1 y no hay solicitud de admision si es 0"
        modalBody.innerHTML += "<p><strong> Número de veces en BD lll" + ":</strong> " + data[key] + "</p>";
    }


    if (data.hasOwnProperty(data) && data[data] > 1) {
        modalBody.innerHTML += "<p><strong>" + data + ":lll</strong> " + data[data] + "</p>";
    }
    else {

        modalBody.innerHTML += "<p><strong>" + data + ":</strong> " + data[data] + "</p>";
    }

    $('#myModal').modal('show');
}



function msgVerifyDataRefused(data) {

    var modalBody = document.getElementById("modalBody");
    modalBody.innerHTML = "no se realizo el registro, el correo tiene asociado otra solicitud de admision";

    $('#myModal').modal('show');
}


function msgVerifyDataCurp(data) {

    var modalBody = document.getElementById("modalBody");
    modalBody.innerHTML = " CURP NO VALIDA";
    $('#myModal').modal('show');
}

function msgVerifyDataDoble(data) {

    var modalBody = document.getElementById("modalBody");
    modalBody.innerHTML = "correo con registro solicitud de admision anterior";
    $('#myModal').modal('show');
}


function msgVerifyDataAge(data) {

    var modalBody = document.getElementById("modalBody");
    modalBody.innerHTML = "fecha no valida ";
    $('#myModal').modal('show');
}


// --------------------->
function registerAll() {

    const settings = {
        url: "controllers/RegisterAllController.php",
        method: "POST",
        type: 0,
    };

    $.ajax(settings).done(function (response) {

        console.log(response);

    }).fail(function (error) {

        console.log("error", error);
    });

}

function closeSession() {
    sessionStorage.clear();
    localStorage.clear();
    window.location.href = 'index.html';
}
