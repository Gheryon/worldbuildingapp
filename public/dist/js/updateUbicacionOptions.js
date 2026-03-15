/**
 * Gestión de selectores polimórficos para ubicaciones
 */
document.addEventListener('DOMContentLoaded', function () {
  const typeSelector = document.getElementById('ubicacion_type');
  const idSelector = document.getElementById('ubicacion_id');

  // Salida temprana si los elementos no existen en la página
  if (!typeSelector || !idSelector) return;

  function updateUbicacionOptions() {
    const selectedType = typeSelector.value;
    // window.ubicacionesData será la variable que llenaremos desde la vista
    const options = (window.ubicacionesData && window.ubicacionesData[selectedType]) ? window.ubicacionesData[selectedType] : {};

    // Limpiar selector manteniendo la opción por defecto
    idSelector.innerHTML = '<option value="">Seleccionar destino...</option>';

    // Llenar con nuevos datos
    Object.keys(options).forEach(function (id) {
      const option = document.createElement('option');
      option.value = id;
      option.textContent = options[id];

      // Comprobar si hay un valor pre-seleccionado (pasado desde la vista)
      if (window.selectedUbicacionId && id == window.selectedUbicacionId) {
        option.selected = true;
      }

      idSelector.appendChild(option);
    });

    // Refrescar select2 si existe
    if (typeof $.fn.select2 !== 'undefined') {
      $(idSelector).trigger('change.select2');
    }
  }

  typeSelector.addEventListener('change', updateUbicacionOptions);

  // Ejecución inicial
  updateUbicacionOptions();
});