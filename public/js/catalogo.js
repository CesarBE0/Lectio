let allBooks = [];

async function loadBooks() {
    try {
        const response = await fetch('/api/books'); // URL LARAVEL
        if (!response.ok) throw new Error('Error servidor');
        allBooks = await response.json();
        renderBooks(allBooks);
        populateTypeFilter();
    } catch (error) {
        document.getElementById("books-list").innerHTML = `<div class="alert alert-danger">Error cargando catálogo</div>`;
    }
}

function renderBooks(list) {
    const container = document.getElementById("books-list");
    if (!container) return;
    container.innerHTML = "";

    // assetBaseUrl se define en el Blade
    const baseUrl = (typeof assetBaseUrl !== 'undefined') ? assetBaseUrl : '';

    list.forEach(b => {
        // Corrección: si la imagen en BD ya tiene 'img/', no lo repetimos, o ajustamos según guardes en BD.
        // Asumiendo que en BD guardas "img/libro1.png"
        const imgPath = b.image.startsWith('http') ? b.image : baseUrl + b.image;

        const col = document.createElement("div");
        col.className = "col-6 col-md-3";
        col.innerHTML = `
          <a href="/libro/${b.id}" class="text-decoration-none text-dark">
            <div class="card h-100 shadow-sm">
              <div style="height:250px; overflow:hidden;" class="d-flex align-items-center justify-content-center bg-light">
                 <img src="${imgPath}" alt="${b.title}" style="max-height:100%; max-width:100%;">
              </div>
              <div class="card-body">
                <h5 class="card-title text-truncate">${b.title}</h5>
                <p class="text-muted small mb-1">${b.author}</p>
                <p class="fw-bold text-primary">${parseFloat(b.price).toFixed(2)} €</p>
              </div>
            </div>
          </a>`;
        container.appendChild(col);
    });
}

// ... (El resto de funciones filterBooks y populateTypeFilter iguales a tu original) ...

function populateTypeFilter() {
    const dataList = document.getElementById("types-list");
    if (!dataList) return;
    const types = [...new Set(allBooks.map(b => b.type))];
    dataList.innerHTML = types.map(t => `<option value="${t}">`).join('');
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
    loadBooks();
    const form = document.getElementById("catalog-form");
    if (form) form.addEventListener("input", () => {
        // Lógica de filtro simple
        const q = document.getElementById("search-q").value.toLowerCase();
        const type = document.getElementById("filter-type").value.toLowerCase();
        const author = document.getElementById("filter-author").value.toLowerCase();

        const filtered = allBooks.filter(b =>
            b.title.toLowerCase().includes(q) &&
            b.type.toLowerCase().includes(type) &&
            b.author.toLowerCase().includes(author)
        );
        renderBooks(filtered);
    });
});
