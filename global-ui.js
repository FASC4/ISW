// global-ui.js

(function () {
  // Esperar a que el DOM esté listo
  function onReady(fn) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", fn);
    } else {
      fn();
    }
  }

  onReady(() => {
    // ----------------------
    // 1. Cerrar otros submenús al abrir uno
    // ----------------------
    const submenuButtons = document.querySelectorAll('.btn-toggle[data-bs-toggle="collapse"]');

    if (submenuButtons.length > 0) {
      submenuButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const targetSelector = btn.getAttribute('data-bs-target') || btn.dataset.bsTarget;
          const current = targetSelector ? document.querySelector(targetSelector) : null;

          if (!current) return;

          // Si este se va a abrir...
          const willOpen = !current.classList.contains('show');

          if (willOpen) {
            // ...cerrar los demás colapsables abiertos dentro de .sidebar
            document.querySelectorAll('.sidebar .collapse.show').forEach(open => {
              if (open !== current) {
                // usar la API de Bootstrap Collapse para cerrarlos
                if (window.bootstrap && bootstrap.Collapse) {
                  new bootstrap.Collapse(open, { toggle: true });
                } else {
                  // fallback si bootstrap.Collapse no existe
                  open.classList.remove('show');
                }
              }
            });
          }
        });
      });
    }

    // ----------------------
    // 2. Toggle de sidebar (colapsar/expandir layout)
    // ----------------------
    const layout = document.getElementById('layoutRoot');
    const toggleBtn = document.querySelector('.sidebar-toggle');

    if (layout && toggleBtn) {
      function updateIcon() {
        const collapsed = layout.classList.contains('collapsed');
        toggleBtn.textContent = collapsed ? '⟩' : '⟨';
        toggleBtn.setAttribute('aria-expanded', String(!collapsed));
      }

      toggleBtn.addEventListener('click', () => {
        layout.classList.toggle('collapsed');
        updateIcon();
      });

      updateIcon();
    }
  });
})();
