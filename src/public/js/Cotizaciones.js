const selUnidad = document.getElementById('idUnidad');
const selRango = document.getElementById('idRango');
const inpCosto = document.getElementById('Costo');
const inpKms = document.getElementById('Kilometros');
const inpTotal = document.getElementById('Total');

function resetRango() {
    if (selRango) {
        selRango.innerHTML = '<option value="">-- Seleccione un Rango --</option>';
        selRango.selectedIndex = 0;
        selRango.disabled = true;
    }
}
function resetCosto() { if (inpCosto) inpCosto.value = ''; }
function resetKms() { if (inpKms) inpKms.value = ''; }
function resetTotal() { if (inpTotal) inpTotal.value = ''; }

function toCurrency(n) {
    return Number(n || 0).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
}
function parseCostoFromOption() {
    const opt = selRango?.options[selRango.selectedIndex];
    return opt?.dataset?.costo ? Number(opt.dataset.costo) : 0;
}
function calcAndRenderTotal() {
    const costo = parseCostoFromOption();
    const kms = Number(inpKms?.value || 0);
    const total = costo * (isNaN(kms) ? 0 : kms);
    if (inpTotal) inpTotal.value = total ? toCurrency(total) : '';
}

resetRango(); resetCosto(); resetKms(); resetTotal();

if (selUnidad) {
    selUnidad.addEventListener('change', async function () {
        const idUnidad = this.value;
        resetRango(); resetCosto(); resetKms(); resetTotal();
        if (!idUnidad) return;
        try {
            const res = await fetch(`/fleet/cotizaciones/obtenerRangos?idUnidad=${encodeURIComponent(idUnidad)}`);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            if (!Array.isArray(data) || data.length === 0) {
                selRango.innerHTML = '<option value="">Sin rangos disponibles</option>';
                return;
            }
            selRango.innerHTML = '<option value="">-- Seleccione un Rango --</option>';
            data.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.dataset.costo = r.Costo;
                opt.textContent = r.Rango;
                selRango.appendChild(opt);
            });
            selRango.disabled = false;
        } catch (e) {
            console.error(e);
            selRango.innerHTML = '<option value="">Error cargando rangos</option>';
        }
    });
}

if (selRango) {
    selRango.addEventListener('change', function () {
        const costo = parseCostoFromOption();
        if (inpCosto) inpCosto.value = costo ? toCurrency(costo) : '';
        calcAndRenderTotal();
    });
}

if (inpKms) {
    ['input','change','blur'].forEach(evt =>
        inpKms.addEventListener(evt, calcAndRenderTotal)
    );
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    showNotification(data.type, data.message);
                    if (data.type === "success") {
                        form.reset();
                    }
                })
                .catch(() => {
                    showNotification("error", "Error de conexión con el servidor");
                });
        });
    }

    const buscarInput = document.getElementById("buscarCotizacion");
    const resultadosDiv = document.getElementById("resultadosBusqueda");
    const detalleCard = document.getElementById("detalleCotizacion");
    const detalleTitulo = document.querySelector("#detalleCotizacionCard h3");

    if (buscarInput && resultadosDiv && detalleCard && detalleTitulo) {
        let timeout = null;
        let selectedIndex = -1;

        buscarInput.addEventListener("focus", () => {
            if (buscarInput.value.trim() !== "") {
                buscarInput.value = "";
                resultadosDiv.innerHTML = "";
                resultadosDiv.style.display = "none";
                selectedIndex = -1;
            }
        });

        buscarInput.addEventListener("input", () => {
            clearTimeout(timeout);
            const term = buscarInput.value.trim();
            if (term.length < 1) {
                resultadosDiv.innerHTML = "";
                resultadosDiv.style.display = "none";
                return;
            }
            timeout = setTimeout(() => {
                fetch(`/fleet/cotizaciones/buscar?term=${encodeURIComponent(term)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            resultadosDiv.innerHTML = data.map(item => `
                                <div class="dropdown-item" data-id="${item.idCotizacion}">
                                    ${item.texto}
                                </div>
                            `).join("");
                            resultadosDiv.style.display = "block";
                            selectedIndex = -1;
                        } else {
                            resultadosDiv.innerHTML = "";
                            resultadosDiv.style.display = "none";
                        }
                    })
                    .catch(err => {
                        console.error("Error en búsqueda:", err);
                        resultadosDiv.innerHTML = "";
                        resultadosDiv.style.display = "none";
                    });
            }, 300);
        });

        buscarInput.addEventListener("keydown", (e) => {
            const items = resultadosDiv.querySelectorAll(".dropdown-item");
            if (items.length === 0) return;
            if (e.key === "ArrowDown") {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % items.length;
                actualizarSeleccion(items);
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                actualizarSeleccion(items);
            } else if (e.key === "Enter") {
                e.preventDefault();
                if (selectedIndex >= 0 && selectedIndex < items.length) {
                    items[selectedIndex].click();
                    buscarInput.blur();
                }
            }
        });

        function actualizarSeleccion(items) {
            items.forEach((item, i) => {
                item.classList.toggle("selected", i === selectedIndex);
            });
        }

        resultadosDiv.addEventListener("click", e => {
            if (e.target.classList.contains("dropdown-item")) {
                const id = e.target.dataset.id;
                const texto = e.target.textContent.trim();
                buscarInput.value = texto;
                fetch(`/fleet/cotizaciones/detalle/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        detalleTitulo.textContent = `Cotización #${data.idCotizacion}`;
                        detalleCard.innerHTML = `
                            <p><strong>Cliente:</strong> ${data.ClienteNombre}</p>
                            <p><strong>Unidad:</strong> ${data.idUnidad}</p>
                            <p><strong>Rango:</strong> ${data.idRango}</p>
                            <p><strong>Kilómetros:</strong> ${data.Kilometros}</p>
                            <p><strong>Costo:</strong> ${data.Costo}</p>
                            <p><strong>Total:</strong> ${data.Total}</p>
                            <p><strong>Fecha:</strong> ${data.FechaAdd}</p>
                        `;
                        resultadosDiv.innerHTML = "";
                        resultadosDiv.style.display = "none";
                        selectedIndex = -1;
                    });
            }
        });
    }

    initAppleModalEvents();
});

function initAppleModalEvents() {
    const btnAddCliente = document.getElementById("addCliente");
    const btnAddUnidad = document.getElementById("addUnidad");
    const btnAddRango = document.getElementById("addRango");
    const modalForm = document.getElementById("appleModalForm");
    const formGroupContainer = modalForm.querySelector(".form-group");
    let modalTipo = "";

    if (btnAddCliente) {
        btnAddCliente.addEventListener("click", () => {
            limpiarCamposExtra();
            resetModalInput();
            modalTipo = "cliente";
            openAppleModal("Nuevo Cliente", "Nombre del cliente", () => {});
        });
    }

    if (btnAddUnidad) {
        btnAddUnidad.addEventListener("click", () => {
            limpiarCamposExtra();
            resetModalInput();
            modalTipo = "unidad";
            openAppleModal("Nueva Unidad", "Nombre de la unidad", () => {});
        });
    }

    if (btnAddRango) {
        btnAddRango.addEventListener("click", () => {
            limpiarCamposExtra();
            resetModalInput();
            modalTipo = "rango";
            openAppleModal("Nuevo Rango", "Rango; Ej (0 - 100)", () => {});
            agregarCamposRango();
        });
    }

    function limpiarCamposExtra() {
        const extras = modalForm.querySelectorAll(".extra-field");
        extras.forEach(e => e.remove());
    }

    function resetModalInput() {
        const modalInput = document.getElementById("modalInput");
        const label = document.querySelector('#appleModalForm label[for="modalInput"]');
        modalInput.type = "text";
        modalInput.placeholder = "";
        modalInput.value = "";
        if (label) label.textContent = "Nombre:";
        const clone = modalInput.cloneNode(true);
        modalInput.parentNode.replaceChild(clone, modalInput);
    }

    function agregarCamposRango() {
        const rangoLabel = document.querySelector('#appleModalForm label[for="modalInput"]');
        if (rangoLabel) rangoLabel.textContent = "Rango:";
        const rangoInput = document.getElementById("modalInput");
        rangoInput.type = "text";
        rangoInput.placeholder = "Rango; Ej (0 - 100)";
        rangoInput.addEventListener("beforeinput", e => {
            if (!/[0-9]/.test(e.data) && e.inputType !== "deleteContentBackward") e.preventDefault();
        });
        rangoInput.addEventListener("keydown", e => {
            // Si se presiona espacio y aún no existe el guion
            if (e.key === " " && !rangoInput.value.includes(" - ")) {
                e.preventDefault(); // Evita que el espacio se escriba
                rangoInput.value = rangoInput.value.trim().replace(/[^\d]/g, "") + " - ";
            }
        });

        rangoInput.addEventListener("input", () => {
            if (rangoInput.value.includes(" - ")) {
                let partes = rangoInput.value.split(" - ");
                let p1 = partes[0].replace(/[^\d]/g, "");
                let p2 = partes[1] ? partes[1].replace(/[^\d]/g, "") : "";
                rangoInput.value = p1 + " - " + p2;
            } else {
                // Solo números antes de que se ponga el guion
                rangoInput.value = rangoInput.value.replace(/[^\d]/g, "");
            }
        });

        const unidadGroup = document.createElement("div");
        unidadGroup.classList.add("form-group", "extra-field");
        unidadGroup.innerHTML = `
            <label for="modalUnidad">Unidad:</label>
            <select id="modalUnidad" name="modalUnidad" required>
                <option value="">-- Seleccione unidad --</option>
            </select>
        `;
        formGroupContainer.insertAdjacentElement("afterend", unidadGroup);
        actualizarListaUnidadesSelect(document.getElementById("modalUnidad"));
        const costoGroup = document.createElement("div");
        costoGroup.classList.add("form-group", "extra-field");
        costoGroup.innerHTML = `
            <label for="modalCosto">Costo:</label>
            <input type="number" id="modalCosto" name="modalCosto" step="0.01" required>
        `;
        unidadGroup.insertAdjacentElement("afterend", costoGroup);
    }

    function actualizarListaUnidadesSelect(selectElement) {
        fetch("/fleet/unidades/listar")
            .then(res => res.json())
            .then(unidades => {
                unidades.forEach(u => {
                    const opt = document.createElement("option");
                    opt.value = u.idUnidad;
                    opt.textContent = u.Nombre;
                    selectElement.appendChild(opt);
                });
            });
    }

    if (modalForm) {
        modalForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const nombre = document.getElementById("modalInput").value.trim();
            if (!nombre) {
                showNotification("error", "Por favor ingresa un nombre");
                return;
            }
            let url = "";
            let body = {};
            let callbackActualizar = null;
            if (modalTipo === "cliente") {
                url = "/fleet/clientes/guardar";
                body = { nombre: nombre };
                callbackActualizar = actualizarListaClientes;
            } else if (modalTipo === "unidad") {
                url = "/fleet/unidades/guardar";
                body = { nombre: nombre };
                callbackActualizar = actualizarListaUnidades;
            } else if (modalTipo === "rango") {
                const idUnidad = document.getElementById("modalUnidad").value;
                const costo = document.getElementById("modalCosto").value;
                if (!idUnidad || !costo) {
                    showNotification("error", "Debe seleccionar unidad y costo");
                    return;
                }
                url = "/fleet/unidades/guardarRango";
                body = { nombreRango: nombre, idUnidad: idUnidad, costo: costo };
                callbackActualizar = () => {
                    actualizarListaUnidades(null, idUnidad).then(() => {
                        actualizarListaRangos(idUnidad);
                        resetCosto();
                        resetTotal();
                    });
                };
            }
            fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(body)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showNotification("success", `${modalTipo} agregado correctamente`);
                        document.getElementById("appleModal").style.display = "none";
                        document.getElementById("modalInput").value = "";
                        limpiarCamposExtra();
                        if (typeof callbackActualizar === "function") {
                            callbackActualizar(nombre);
                        }
                    } else {
                        showNotification("error", data.message || `Error al guardar ${modalTipo}`);
                    }
                })
                .catch(() => {
                    showNotification("error", "Error de conexión con el servidor");
                });
        });
    }
}

function actualizarListaClientes(nombreSeleccionado = null) {
    fetch("/fleet/clientes/listar")
        .then(res => res.json())
        .then(clientes => {
            const selectCliente = document.getElementById("IdCliente");
            if (!selectCliente) return;
            selectCliente.innerHTML = '<option value="">-- Seleccione un cliente --</option>';
            clientes.forEach(c => {
                const opt = document.createElement("option");
                opt.value = c.IdCliente;
                opt.textContent = c.Nombre;
                selectCliente.appendChild(opt);
            });
            if (nombreSeleccionado) {
                const opt = Array.from(selectCliente.options)
                    .find(o => o.textContent === nombreSeleccionado);
                if (opt) opt.selected = true;
            }
        })
        .catch(err => console.error("Error actualizando clientes:", err));
}

function actualizarListaUnidades(nombreSeleccionado = null, idSeleccionado = null) {
    return fetch("/fleet/unidades/listar")
        .then(res => res.json())
        .then(unidades => {
            const selectUnidad = document.getElementById("idUnidad");
            if (!selectUnidad) return;
            selectUnidad.innerHTML = '<option value="">-- Seleccione una unidad --</option>';
            unidades.forEach(u => {
                const opt = document.createElement("option");
                opt.value = u.idUnidad;
                opt.textContent = u.Nombre;
                selectUnidad.appendChild(opt);
            });
            if (nombreSeleccionado) {
                const opt = Array.from(selectUnidad.options)
                    .find(o => o.textContent === nombreSeleccionado);
                if (opt) opt.selected = true;
            }
            if (idSeleccionado) {
                selectUnidad.value = idSeleccionado;
            }
        })
        .catch(err => console.error("Error actualizando unidades:", err));
}

function actualizarListaRangos(idUnidad) {
    const selectRango = document.getElementById("idRango");
    if (!selectRango) return;
    selectRango.innerHTML = '<option value="">-- Seleccione un Rango --</option>';
    selectRango.disabled = true;
    if (!idUnidad) return;
    fetch(`/fleet/cotizaciones/obtenerRangos?idUnidad=${encodeURIComponent(idUnidad)}`)
        .then(res => res.json())
        .then(rangos => {
            console.log("Unidad solicitada:", idUnidad, "Rangos devueltos:", rangos);
            selectRango.innerHTML = '<option value="">-- Seleccione un Rango --</option>';
            if (!Array.isArray(rangos) || rangos.length === 0) {
                selectRango.innerHTML = '<option value="">Sin rangos disponibles</option>';
                selectRango.disabled = true;
                return;
            }
            rangos.forEach(r => {
                const opt = document.createElement("option");
                opt.value = r.id;
                opt.dataset.costo = r.Costo;
                opt.textContent = r.Rango;
                selectRango.appendChild(opt);
            });
            selectRango.disabled = false;
        })
        .catch(err => {
            console.error("Error actualizando rangos:", err);
            selectRango.innerHTML = '<option value="">Error cargando rangos</option>';
            selectRango.disabled = true;
        });
}
