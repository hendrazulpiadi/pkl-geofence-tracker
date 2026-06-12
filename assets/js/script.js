// Additional JavaScript utilities
document.addEventListener('DOMContentLoaded', function() {
    var alert = document.querySelector('.alert-dismissible');
    if (alert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    }
});
