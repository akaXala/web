document.addEventListener("DOMContentLoaded", () => {
    let objProductos = JSON.parse(productos);

    objProductos.forEach((objeto, indice)=>{
    carta = `
        <div class="card" style="width: 18rem;">
            <img src="${objeto.thumbnail}" class="card-img-top">
            <div class="card-body">
                <h5 class="card-title">${objeto.title}</h5>
                <p class="card-text">${objeto.description}</p>
                <a href="#" class="btn btn-primary">Ir</a>
            </div>
        </div>
    `;
    });

    let verProductos = document.querySelector("div#verProductos");
    verProductos.innerHTML = carta;
});