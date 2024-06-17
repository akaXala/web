document.addEventListener("DOMContentLoaded", () => {
    let objProductos = JSON.parse(productos);

    let filas = "";
    let columnas = 0;

    objProductos.forEach((objeto, indice)=>{
        if (indice % 4 == 0) filas += `<div class="row>`;
        filas += `
            <div class="row">
                <div class="col">
                    <p>${objeto.id}</p>
                    <h1>${objeto.title}</h1>
                    <p>${objeto.description}</p>
                    <p>${objeto.category}</p>
                    <p>${objeto.price}</p>
                </div>
            </div>
        `;

        if (columnas == 3){
            filas += "</div> <!-- /row -->";
            columnas = 1;
        } else {
            columnas++;
        }
    });

    let verProductos = document.querySelector("div#verProductos");
    verProductos.innerHTML = filas;
});