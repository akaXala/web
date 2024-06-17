document.addEventListener("DOMContentLoaded", () => {
    let objProductos = JSON.parse(productos);

    let filas = "";
    let columnas = 0;

    objProductos.forEach((objeto, indice)=>{
        if (indice % 4 == 0) filas += `<div class="row>`;
        filas += `
            <div class="row">
                <div class="col">
                    <p>ID: ${objeto.id}</p>
                    <h1>Titulo: ${objeto.title}</h1>
                    <p>Descripción: ${objeto.description}</p>
                    <p>Categoria: ${objeto.category}</p>
                    <p>Precio: ${objeto.price}</p>
                    <p>Porcentaje de descuento: ${objeto.discountPercentage}</p>
                    <p>Rating: ${objeto.rating}</p>
                    <p>Stock: ${objeto.stock}</p>
                    <p>Tags: ${objeto.tags}</p>
                    <p>Brand: ${objeto.brand}</p>
                    <p>SKU: ${objeto.sku}</p>
                    <p>Peso: ${objeto.weight}</p>
                    <p>Dimensiones: ${objeto.dimensions}</p>
                    <p>Info. Garantia: ${objeto.warrantyInformation}</p>
                    <p>Info. Envio: ${objeto.shippingInformation}</p>
                    <p>Disponibilidad: ${objeto.availabilityStatus}</p>
                    <p>Reseñas: ${objeto.reviews}</p>
                    <p>Politica de devolución: ${objeto.returnPolicy}</p>
                    <p>Cantidad minina por pedidp: ${objeto.minimumOrderQuantity}</p>
                    <p>Meta${objeto.meta}</p>
                    <img src="${objeto.images[0]}" width=10% height=10%>
                    <p>Link imagen: ${objeto.images}</p>
                    <img src="${objeto.thumbnail}" width=10% height=10%>
                    <p>Link miniatura: ${objeto.thumbnail}</p>
                    <hr>
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