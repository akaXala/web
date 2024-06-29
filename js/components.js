class footjs extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.innerHTML = `
    <section id="footer" class="static-bottom">
    <footer class=" text-center text-dark pt-4" style="background-color: #CEE1F2;">
      <div class="container">
        <div class="row">
          <!-- Sección de Información -->
          <div class="col-md-4 mb-2">
            <h4>Direccion</h4>
            <ul class="list-unstyled">
              <li>
                <p>Calle Conjunto Alejandra 88 Puerta 551, San Quintín, Baja California,Mexico.<br>
                </p>
              </li>
              <li>
                <a href="#" class="text-dark text-decoration-none me-4"><i class="fab fa-facebook-f fa-xl"></i></a>
                <a href="#" class="text-dark text-decoration-none me-4"><i class="fab fa-twitter fa-xl"></i></a>
                <a href="#" class="text-dark text-decoration-none"><i class="fab fa-instagram fa-xl"></i></a>
              </li>
            </ul>
          </div>
          <!-- Sección de Enlaces -->
          <div class="col-md-4 mb-2">
            <h4>Correo Electronico</h4>
            <ul class="list-unstyled">
              <li><a href="#" class="text-dark text-decoration-none">Xala@gmail.com</a></li>
              <li><a href="#" class="text-dark text-decoration-none">Jeff@gmail.com</a></li>
              <li><a href="#" class="text-dark text-decoration-none">Torres@gmail.com</a></li>
              <li><a href="#" class="text-dark text-decoration-none">XalaStore@gmail.com</a></li>
            </ul>
          </div>
          <!-- Sección de Redes Sociales -->
          <div class="col-md-4 mb-2">
            <h5>Llamanos</h5>
            <ul class="list-unstyled">
              <li class="list-unstyled">+52 55 2134 9392</li>
              <li class="list-unstyled">+52 55 2134 9392</li>
              <li class="list-unstyled">+52 55 2134 9392</li>
              <li class="list-unstyled">+52 55 2134 9392</li>
              </li>
            </ul>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col">
            <p class="mb-0">&copy; 2024 Xclusive Store. Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
    </footer>
  </section>`;
  }
}
window.customElements.define("footer-js", footjs);
