// ======================
// Notificaciones
// ======================
function showNotification(type, message) {
    const notification = document.getElementById("notification");
    const messageEl = document.getElementById("notification-message");

    if (!notification || !messageEl) {
        console.error("Sistema de notificaciones no encontrado en el DOM");
        return;
    }

    const iconEl = notification.querySelector(".notification-icon");

    notification.className = `notification ${type}`;
    messageEl.textContent = message;
    if (iconEl) {
        iconEl.textContent = type === "success" ? "✔" : "✖";
    }

    notification.classList.add("show");

    setTimeout(() => {
        notification.classList.remove("show");
    }, 3000);
}

// ======================
// Apertura de modal Apple
// ======================
async function openAppleModal(title, placeholder, onSave) {
    // Si no existe el modal, intentamos cargarlo dinámicamente
    if (!document.getElementById("appleModal")) {
        try {
            const res = await fetch("/fleet/clientes/modal"); // Ruta que devuelve el HTML del modal
            if (!res.ok) throw new Error("No se pudo cargar el modal");
            const html = await res.text();
            document.body.insertAdjacentHTML("beforeend", html);
        } catch (err) {
            console.error("Error cargando modal:", err);
            showNotification("error", "No se pudo abrir el modal");
            return;
        }
    }

    // Reasignamos elementos después de inyectar
    const modal   = document.getElementById("appleModal");
    const titleEl = document.getElementById("appleModalTitle");
    const inputEl = document.getElementById("modalInput");
    const form    = document.getElementById("appleModalForm");

    if (!modal || !titleEl || !inputEl || !form) {
        console.error("Elementos del modal no encontrados");
        showNotification("error", "Error interno al abrir modal");
        return;
    }

    // Configuración del modal
    titleEl.textContent = title;
    inputEl.value = "";
    inputEl.placeholder = placeholder;
    modal.style.display = "flex";

    // Botón cerrar
    const btnClose = modal.querySelector(".apple-modal-close");
    if (btnClose) {
        btnClose.onclick = () => modal.style.display = "none";
    }

    // Botón cancelar
    const btnCancel = modal.querySelector(".cancel-btn");
    if (btnCancel) {
        btnCancel.onclick = () => modal.style.display = "none";
    }

    // Guardado
    form.onsubmit = (e) => {
        e.preventDefault();
        onSave(inputEl.value.trim());
        modal.style.display = "none";
    };
}

// ======================
// Cierre modal al hacer click fuera
// ======================
window.onclick = (e) => {
    const modal = document.getElementById("appleModal");
    if (modal && e.target === modal) {
        modal.style.display = "none";
    }
};
