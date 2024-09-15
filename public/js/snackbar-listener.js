document.addEventListener("DOMContentLoaded", function () {
    var snackbar = document.getElementById("snackbar");
    if (snackbar) {
        snackbar.className = "snackbar show";
        setTimeout(function () {
            snackbar.className = snackbar.className.replace("show", "");
        }, 3000);
    }
});
