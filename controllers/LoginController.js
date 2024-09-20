
var loginForm = document.getElementById("loginForm");
loginForm.addEventListener("submit", (e) => {
    e.preventDefault();

    $("#alert_error").hide();

    let user = $("#user");
    let password = $("#password");

    //! Validaciones
    if (!isRequired(user.val())) {
        showError(user);
        return;
    } else {
        showSuccess(user);
    }

    if (!isRequired(password.val())) {
        showError(password);
        return;
    } else {
        showSuccess(password);
    }

    //Función si no hay errores de validación
    login(user.val(), password.val(), function (data) {


        if (data === null) {
            $("#alert_error").html('Usuario inexistente. Favor de contactar con administración');
            $("#alert_error").show();
            return;
        }
      
        sessionStorage.setItem('user_data', JSON.stringify(data));
        window.location.href = 'requests.html';

    });

});

/**
 * @param mixed email
 * @param mixed password
 * @param mixed callBack
 * 
 * @return Callback
 */
function login(username, password, callBack, callBackError = null) {

    let data = {
        username: username,
        password: password
    }
 
    const settings = {
        url: "controllers/AuthController.php",
        method: "POST",
        data: JSON.stringify(data),
        type: 0,
        beforeSend: function () {
            $('#spinner-div').show();
        },
        complete: function () {
            $('#spinner-div').hide();
        }
    };

    $.ajax(settings).done(function (response) {

        const data = response.data;
        callBack(data);

    }).fail(function (error) {

        console.log("Error", error);
        callBackError(error);
    });
}
