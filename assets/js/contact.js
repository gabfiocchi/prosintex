(function ($) {
    "use strict";

    $("form").submit(function (event) {
        event.preventDefault();
        var name = $("#name").val();
        var email = $("#email").val();
        var opt1 = $("#opt-1");
        var opt2 = $("#opt-2");
        var opt3 = $("#opt-3");
        var optMessage = [];
        if (opt1.is(":checked")) {
            optMessage.push(opt1.val());
        }
        if (opt2.is(":checked")) {
            optMessage.push(opt2.val());
        }
        if (opt3.is(":checked")) {
            optMessage.push(opt3.val());
        }

        var subject = "Consulta: " + (optMessage.length > 0 ? optMessage.join(', ').trim() : 'Otras consultas');
        var message = "De:" + name + "\n\n" + $("#message").val();
        

        $("#returnmessage").empty(); // To empty previous error/success message.
        // Checking for blank fields.
        if (name === '' || email === '' || subject === '' || message === '') {
            alert("Please Fill Required Fields");
        } else {
            // Returns successful data submission message when the entered information is stored in database.
            $.post("/mailer.php", {
                name,
                email,
                subject,
                message,
            }, function (data) {
                if (data.status === 200) {
                    $("#form")[0].reset();
                }

                // Append returned message to message paragraph.
                $("#returnmessage").append(data.message);
            });
        }
    });
})(jQuery);
