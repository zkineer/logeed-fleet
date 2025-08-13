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
// ======================
// Apertura de modal Apple
// ======================
async function openAppleModal(title, placeholder, onSave, isMap = false, onClose = null) {
    return new Promise(async (resolve) => {
        if (!document.getElementById("appleModal")) {
            try {
                const res = await fetch("/fleet/clientes/modal");
                if (!res.ok) throw new Error("No se pudo cargar el modal");
                const html = await res.text();
                document.body.insertAdjacentHTML("beforeend", html);
            } catch (err) {
                console.error("Error cargando modal:", err);
                showNotification("error", "No se pudo abrir el modal");
                return;
            }
        }

        const modal     = document.getElementById("appleModal");
        const titleEl   = document.getElementById("appleModalTitle");
        const form      = document.getElementById("appleModalForm");
        const formGroup = form?.querySelector(".form-group");
        let inputEl     = document.getElementById("modalInput");

        if (!modal || !titleEl || !form) {
            console.error("openAppleModal: Elementos base del modal no encontrados");
            showNotification("error", "Error interno al abrir modal");
            return;
        }

        // Guardar contenido original
        if (!modal.dataset.originalContent && formGroup) {
            modal.dataset.originalContent = formGroup.innerHTML;
        }

        // Limpieza si es mapa
        if (isMap && formGroup) {
            formGroup.innerHTML = "";
        }

        // Si no es mapa, configurar input
        if (!isMap) {
            if (!inputEl && formGroup) {
                formGroup.innerHTML = `
                    <label for="modalInput">Nombre:</label>
                    <input type="text" id="modalInput" class="form-control">
                `;
                inputEl = document.getElementById("modalInput");
            }
            if (inputEl) {
                inputEl.value = "";
                inputEl.placeholder = placeholder;
            }
        }

        titleEl.textContent = title;
        modal.style.display = "flex";

        // Botón cerrar
        const btnClose = modal.querySelector(".apple-modal-close");
        if (btnClose) {
            btnClose.onclick = () => {
                modal.style.display = "none";
                restoreModalContent(modal);
                if (typeof onClose === "function") onClose();
            };
        }

        // Botón cancelar
        const btnCancel = modal.querySelector(".cancel-btn");
        if (btnCancel) {
            btnCancel.onclick = (e) => {
                e.preventDefault();
                modal.style.display = "none";
                restoreModalContent(modal);
                if (typeof onClose === "function") onClose();
            };
        }

        // Guardado
        form.onsubmit = (e) => {
            e.preventDefault();
            if (!isMap) {
                const input = document.getElementById("modalInput");
                onSave(input ? input.value.trim() : "");
            } else {
                onSave();
            }
            modal.style.display = "none";
            restoreModalContent(modal);
            if (typeof onClose === "function") onClose();
        };

        if (!isMap) {
            resetModalInput();
        }

        resolve();
    });
}

function resetModalInput() {
    const modalInput = document.getElementById("modalInput");
    const label = document.querySelector('#appleModalForm label[for="modalInput"]');

    if (!modalInput) {
        console.warn("resetModalInput: #modalInput no encontrado, se omite reset.");
        return;
    }

    modalInput.type = "text";
    modalInput.placeholder = "";
    modalInput.value = "";

    if (label) label.textContent = "Nombre:";

    const clone = modalInput.cloneNode(true);
    modalInput.parentNode.replaceChild(clone, modalInput);
}

function restoreModalContent(modal) {
    const modalContent = modal.querySelector(".apple-modal-content");
    const modalForm = document.getElementById("appleModalForm");
    const formGroup = modalForm?.querySelector(".form-group");

    // 🧹 Eliminar campos extra que agregó agregarCamposRango
    modalForm.querySelectorAll(".extra-field").forEach(el => el.remove());

    // Quitar clases y estilos especiales del mapa
    if (modalContent) {
        modalContent.classList.remove("wide");
        modalContent.removeAttribute("style");
    }
    if (modalForm) {
        modalForm.classList.remove("map-only");
    }

    // Restaurar HTML original del form-group
    if (formGroup) {
        if (modal.dataset.originalContent) {
            formGroup.innerHTML = modal.dataset.originalContent;
        } else {
            formGroup.innerHTML = `
                <label for="modalInput">Nombre:</label>
                <input type="text" id="modalInput" class="form-control">
            `;
        }
    }

    // Restaurar botón Cancelar
    const btnCancelar = modal.querySelector(".cancel-btn");
    if (btnCancelar) {
        btnCancelar.textContent = "Cancelar";
        btnCancelar.onclick = () => {
            modal.style.display = "none";
        };
    }

    // Restaurar botón Guardar
    const btnGuardar = modal.querySelector(".save-btn");
    if (btnGuardar) {
        btnGuardar.textContent = "Guardar";
        btnGuardar.onclick = null;
    }
}


// Obtener precio del diésel en Aguascalientes
async function fetchDieselAgs() {
    const url = 'https://www.nacionalgasolinero.com/';
    const apiUrl = `https://api.allorigins.win/get?url=${encodeURIComponent(url)}`;

    try {
        const res = await fetch(apiUrl);
        const data = await res.json();
        const html = data.contents;

        // Buscar el precio del diésel para Aguascalientes
        const match = html.match(/Aguascalientes.*?Di[eé]sel.*?\$([0-9]+\.[0-9]+)/si);
        return match ? parseFloat(match[1]) : null;
    } catch (error) {
        console.error("Error obteniendo precio del diésel:", error);
        return null;
    }
}

// Obtener tipo de cambio USD/MXN sin AllOrigins
async function fetchUsdRate() {
    try {
        const res = await fetch('https://open.er-api.com/v6/latest/USD');
        const data = await res.json();
        return data.rates.MXN;
    } catch (error) {
        console.error("Error obteniendo tipo de cambio USD:", error);
        return null;
    }
}


// Colocar iconos de tendencia
function setTrendIcon(element, change) {
    if (change > 0) {
        element.innerHTML = '⬆';
        element.className = 'up';
    } else if (change < 0) {
        element.innerHTML = '⬇';
        element.className = 'down';
    } else {
        element.innerHTML = '';
        element.className = '';
    }
}

let previousDieselPrice = null;
let previousUsdRate = null;

async function updateInfoBar() {
    const dieselEl = document.getElementById('diesel-price');
    const dieselTrend = document.getElementById('diesel-trend');
    const usdEl = document.getElementById('usd-mxn-rate');
    const usdTrend = document.getElementById('usd-trend');

    // Diésel
    const dieselPrice = await fetchDieselAgs();
    if (dieselPrice) {
        dieselEl.innerText = `$${dieselPrice.toFixed(3)} L/MXN`;
        setTrendIcon(dieselTrend, previousDieselPrice !== null ? dieselPrice - previousDieselPrice : 0);
        previousDieselPrice = dieselPrice;
    } else {
        dieselEl.innerText = 'No disponible';
        dieselTrend.innerText = '';
    }

    // USD
    const usdRate = await fetchUsdRate();
    if (usdRate) {
        usdEl.innerText = usdRate.toFixed(4);
        setTrendIcon(usdTrend, previousUsdRate !== null ? usdRate - previousUsdRate : 0);
        previousUsdRate = usdRate;
    } else {
        usdEl.innerText = 'No disponible';
        usdTrend.innerText = '';
    }
}

// Actualizar al cargar y cada 5 minutos
updateInfoBar();
setInterval(updateInfoBar, 300000);
