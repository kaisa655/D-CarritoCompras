// Versión estática para GitHub Pages basada en los productos del proyecto original.
// GitHub Pages no ejecuta PHP ni se conecta a MySQL. Se utiliza localStorage.
const catalogo = [
  { id: 1, nombre: "Laptop", precio: 12500, existencias: 10 },
  { id: 2, nombre: "Teclado", precio: 650, existencias: 20 },
  { id: 3, nombre: "Mouse", precio: 350, existencias: 30 },
  { id: 4, nombre: "Audífonos", precio: 850, existencias: 15 },
  { id: 5, nombre: "Monitor", precio: 4200, existencias: 8 }
];

const leer = (clave, defecto) => {
  try { return JSON.parse(localStorage.getItem(clave)) ?? defecto; }
  catch { return defecto; }
};
let carrito = leer("carrito", []);
const dinero = n => Number(n).toLocaleString("es-MX", { style: "currency", currency: "MXN" });
const guardar = () => localStorage.setItem("carrito", JSON.stringify(carrito));

function mostrarUsuario() {
  const usuario = localStorage.getItem("usuario");
  const saludo = document.getElementById("saludo");
  if (saludo) saludo.textContent = usuario ? "Bienvenido, " + usuario : "Usuario no identificado";
  const contador = document.getElementById("contador");
  if (contador) contador.textContent = carrito.reduce((s, p) => s + p.cantidad, 0);
}

function iniciarSesion() {
  const formulario = document.getElementById("form-login");
  if (!formulario) return;
  formulario.addEventListener("submit", e => {
    e.preventDefault();
    const nombre = document.getElementById("nombre").value.trim();
    if (!nombre) {
      document.getElementById("mensaje").textContent = "Escribe tu nombre para continuar.";
      return;
    }
    localStorage.setItem("usuario", nombre);
    window.location.href = "productos.html";
  });
}

function mostrarProductos() {
  const zona = document.getElementById("productos");
  if (!zona) return;
  zona.innerHTML = catalogo.map(p => `
    <article class="producto">
      <h3>${p.nombre}</h3>
      <p class="precio">${dinero(p.precio)}</p>
      <p>Existencias iniciales: ${p.existencias}</p>
      <label for="cantidad-${p.id}">Cantidad:</label>
      <input id="cantidad-${p.id}" type="number" min="1" max="${p.existencias}" value="1">
      <button type="button" data-agregar="${p.id}">Agregar al carrito</button>
    </article>
  `).join("");
  zona.addEventListener("click", e => {
    const boton = e.target.closest("[data-agregar]");
    if (!boton) return;
    const id = Number(boton.dataset.agregar);
    const cantidad = Number(document.getElementById("cantidad-" + id).value);
    const producto = catalogo.find(p => p.id === id);
    const existente = carrito.find(p => p.id === id);
    const solicitada = cantidad + (existente ? existente.cantidad : 0);
    const mensaje = document.getElementById("mensaje");
    if (!Number.isInteger(cantidad) || cantidad < 1 || solicitada > producto.existencias) {
      mensaje.textContent = "Selecciona una cantidad válida que no supere las existencias de demostración.";
      return;
    }
    if (existente) existente.cantidad = solicitada;
    else carrito.push({ id, nombre: producto.nombre, precio: producto.precio, cantidad });
    guardar(); mostrarUsuario();
    mensaje.textContent = "Se agregó " + producto.nombre + " al carrito.";
  });
}

function mostrarCarrito() {
  const filas = document.getElementById("filas-carrito");
  if (!filas) return;
  if (!carrito.length) {
    filas.innerHTML = '<tr><td colspan="5">Tu carrito está vacío.</td></tr>';
  } else {
    filas.innerHTML = carrito.map(p => `
      <tr>
        <td>${p.nombre}</td>
        <td>${dinero(p.precio)}</td>
        <td><input aria-label="Cantidad de ${p.nombre}" type="number" min="1" value="${p.cantidad}" data-cantidad="${p.id}"></td>
        <td>${dinero(p.precio * p.cantidad)}</td>
        <td><button class="eliminar" type="button" data-eliminar="${p.id}">Eliminar</button></td>
      </tr>
    `).join("");
  }
  const total = carrito.reduce((s, p) => s + p.precio * p.cantidad, 0);
  document.getElementById("total").textContent = "Total: " + dinero(total);
  filas.onchange = e => {
    const campo = e.target.closest("[data-cantidad]");
    if (!campo) return;
    const p = carrito.find(item => item.id === Number(campo.dataset.cantidad));
    const cantidad = Number(campo.value);
    const producto = catalogo.find(item => item.id === p.id);
    if (!Number.isInteger(cantidad) || cantidad < 1 || cantidad > producto.existencias) {
      alert("Cantidad no válida.");
      campo.value = p.cantidad;
      return;
    }
    p.cantidad = cantidad; guardar(); mostrarCarrito(); mostrarUsuario();
  };
  filas.onclick = e => {
    const boton = e.target.closest("[data-eliminar]");
    if (!boton) return;
    carrito = carrito.filter(p => p.id !== Number(boton.dataset.eliminar));
    guardar(); mostrarCarrito(); mostrarUsuario();
  };
  mostrarUsuario();
}

function configurarBotones() {
  const cerrar = document.getElementById("cerrar-sesion");
  if (cerrar) cerrar.addEventListener("click", () => {
    localStorage.removeItem("usuario");
    window.location.href = "index.html";
  });
  const finalizar = document.getElementById("finalizar");
  if (finalizar) finalizar.addEventListener("click", () => {
    const mensaje = document.getElementById("mensaje");
    if (!carrito.length) {
      mensaje.textContent = "Tu carrito está vacío.";
      return;
    }
    alert("Compra de demostración finalizada. No se modificó el inventario real.");
    carrito = []; guardar(); mostrarCarrito();
    mensaje.textContent = "La demostración de compra terminó correctamente.";
  });
}

document.addEventListener("DOMContentLoaded", () => {
  iniciarSesion();
  mostrarProductos();
  mostrarCarrito();
  mostrarUsuario();
  configurarBotones();
});
