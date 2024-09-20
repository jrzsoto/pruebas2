//Carga los spinners en cada ajax request
$.ajaxSetup({
    beforeSend: function () {
        $('#spinner-div').show();
    },
    complete: function () {
        $('#spinner-div').hide();
    }
});


$(function () {

    const loginCheck = JSON.parse(sessionStorage.getItem('user_data'));
    if (loginCheck === null) {
        window.location.href = 'index.html';
    }
    
    getInconsistentRecords();
});


//Petición
function getInconsistentRecords() {
    const settings = {
        url: "models/InconsistentRecordsModel.php",
        method: "POST",
        type: 0
    };

    $.ajax(settings).done(function (response) {

        console.log(response);
        processRequests(response.data);

    }).fail(function (error) {

        console.log(error);

    });
}

function processRequests(data) {


    i = 0;
    // Itera sobre cada solicitud
    data.forEach(function (value) {

        // Accede a los datos de la solicitud
        // var solicitud = solicitud.Solicitud;
        var newRow = document.getElementById('mainTable').insertRow();

        i++;

            newRow.innerHTML = 
            '<td scope="row">' + i + '</td>' +
            '<td>' + value.matricula_alumno + '</td>'+    
            '<td>' + value.nombre + '</td>' +
            '<td>' + value.primer_apellido + '</td>'+
            '<td>' + value.segundo_apellido + '</td>'+
            '<td>' + value.curp + '</td>' +
            '<td>' + value.correo_electronico + '</td>' +            
            '<td>' + value.nombre_programa +  '</td>' +
            '<td>' + value.descripcion +  '</td>';
            //'<td>' + value.tipo_grado +  '</td>';
            
        
    });
}

function closeSession() {
    sessionStorage.clear();
    localStorage.clear();
    window.location.href = 'index.html';
}



