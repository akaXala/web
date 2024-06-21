document.addEventListener("DOMContentLoaded", () => {
    let objProductos = JSON.parse(productos);

    let filas = "";
    let col = 0;

    objProductos.forEach((objeto, indice)=>{
        if (indice % 3 == 0){
            filas += `<div class="container text-center">
                        <div class="row align-items-start">`;
        }

        filas += `
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <img src="${objeto.thumbnail}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">${objeto.title}</h5>
                        <p class="card-text">${objeto.description}</p>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
        `;

        if (col == 2){
            filas += `
                    </div>
                </div>
            `;
            col = 0;
        } else {
            col++;
        }
    });

    let verProductos = document.querySelector("div#verProductos");
    verProductos.innerHTML = filas;
});