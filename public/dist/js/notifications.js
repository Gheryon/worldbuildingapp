
  // Toastr notificaciones
  function showNotifications(data) {
    const defaultOptions = {
        "closeButton": true,
        "closeOnHover": true,
        "progressBar": false,
        "showDuration": 600,
        "preventDuplicates": true,
    };

    toastr.options = defaultOptions;

    if (data.success) {
        toastr.success(data.success);
    }

    if (data.error) {
        toastr.options.showDuration = 900;
        toastr.error(data.error);
    }

    if (data.info) {
        toastr.info(data.info);
    }

    if (data.warning) {
        toastr.warning(data.warning);
    }
}