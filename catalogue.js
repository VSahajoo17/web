const inventory = [
    { id: 1, title: "Royal Crimson Katan Saree", category: "women", categoryLabel: "Women", price: 7500, oldPrice: 9000, locations: ["palma", "rose hill"], image: "asset/pic1.jpg", tag: "Best Seller" },
    { id: 2, title: "Classic Designer Kurta", category: "men", categoryLabel: "Men", price: 2400, oldPrice: null, locations: ["beau bassin", "palma"], image: "asset/pic2.jpg", tag: null },
    { id: 3, title: "Mint Green Flora Organza Saree", category: "women", categoryLabel: "Women", price: 4200, oldPrice: null, locations: ["berthaud", "rose hill"], image: "asset/pic3.jpg", tag: "New Arrival" },
    { id: 4, title: "Golden Zari Embroidered Clutch", category: "accessories", categoryLabel: "Accessories", price: 950, oldPrice: 1200, locations: ["beau bassin", "berthaud"], image: "asset/pic4.jpg", tag: null },
    { id: 5, title: "Pastel Rose Brocade Silk Saree", category: "women", categoryLabel: "Women", price: 12500, oldPrice: null, locations: ["palma", "berthaud"], image: "asset/pic5.jpg", tag: null },
    { id: 6, title: "Traditional Silk Sherwani", category: "men", categoryLabel: "Men", price: 15000, oldPrice: null, locations: ["rose hill", "beau bassin"], image: "asset/pic6.jpg", tag: "Premium" },
    { id: 7, title: "Handcrafted Jhumka Earrings", category: "accessories", categoryLabel: "Accessories", price: 650, oldPrice: null, locations: ["palma", "beau bassin"], image: "asset/pic7.jpg", tag: null },
    { id: 8, title: "Sunset Ombre Georgette Saree", category: "women", categoryLabel: "Women", price: 3800, oldPrice: null, locations: ["berthaud", "rose hill"], image: "asset/pic8.jpg", tag: null },
    { id: 9, title: "Men's Luxury Silk Nehru Jacket", category: "men", categoryLabel: "Men", price: 4900, oldPrice: 5500, locations: ["palma", "rose hill"], image: "asset/pic9.jpg", tag: null },
    { id: 10, title: "Beaded Statement Necklace", category: "accessories", categoryLabel: "Accessories", price: 1350, oldPrice: null, locations: ["berthaud", "beau bassin"], image: "asset/pic10.jpg", tag: null }
];

const grid = document.getElementById('grid');
const counter = document.getElementById('counter');
const modal = document.getElementById('product-modal');
const modalClose = document.querySelector('.modal-close');

function renderCatalogue() {
    grid.innerHTML = "";

    inventory.forEach(item => {
        const card = document.createElement('div');
        card.className = "product-card";
        
        card.setAttribute('data-id', item.id);
        card.setAttribute('data-category', item.category);
        card.setAttribute('data-price', item.price);
        card.setAttribute('data-locations', item.locations.join(' '));

        const badgeHtml = item.tag ? `<span class="badge ${item.tag === 'Best Seller' ? 'flag-best' : 'flag-new'}">${item.tag}</span>` : '';
        const oldPriceHtml = item.oldPrice ? `<span class="old-price">Rs ${item.oldPrice.toLocaleString()}</span>` : '';

        // Formatted properly with Mauritian local currency styling formatting
        card.innerHTML = `
            <div class="product-image">
                <img src="${item.image}" alt="${item.title}" class="saree-img">
                ${badgeHtml}
            </div>
            <div class="product-info">
                <span class="category-tag">${item.categoryLabel}</span>
                <h3>${item.title}</h3>
                <p class="store-availability">📍 ${item.locations.map(l => l.replace(/\b\w/g, c => c.toUpperCase())).join(', ')}</p>
                <div class="price-row">
                    <span class="price">Rs ${item.price.toLocaleString()}</span>
                    ${oldPriceHtml}
                </div>
                <button class="btn-view">View Details</button>
            </div>
        `;

        card.querySelector('.btn-view').addEventListener('click', () => openModal(item.id));
        grid.appendChild(card);
    });

    filterProducts();
}

function filterProducts() {
    const activeLocations = Array.from(document.querySelectorAll('.filter-location:checked')).map(cb => cb.value);
    const activeCategories = Array.from(document.querySelectorAll('.filter-category:checked')).map(cb => cb.value);
    const priceMode = document.querySelector('.filter-price:checked').value;
    
    const cards = document.querySelectorAll('.product-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        const price = parseFloat(card.getAttribute('data-price'));
        const cardLocs = card.getAttribute('data-locations').split(' ');

        const matchLocation = cardLocs.some(loc => activeLocations.includes(loc));
        const matchCategory = activeCategories.includes(cat);
        
        let matchPrice = false;
        if (priceMode === 'all') matchPrice = true;
        else if (priceMode === 'under-1500') matchPrice = price < 1500;
        else if (priceMode === '1500-5000') matchPrice = price >= 1500 && price <= 5000;
        else if (priceMode === 'above-5000') matchPrice = price > 5000;

        if (matchLocation && matchCategory && matchPrice) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    counter.textContent = `Showing ${visibleCount} Exquisite Piece${visibleCount === 1 ? '' : 's'}`;
}

function openModal(id) {
    const data = inventory.find(item => item.id === id);
    if (!data) return;

    document.getElementById('modal-title').textContent = data.title;
    document.getElementById('modal-tag').textContent = data.categoryLabel;
    document.getElementById('modal-price').textContent = `Rs ${data.price.toLocaleString()}`;
    document.getElementById('modal-location').textContent = `Available at: ${data.locations.map(l => l.replace(/\b\w/g, c => c.toUpperCase())).join(', ')}`;
    document.getElementById('modal-img').src = data.image;
    document.getElementById('modal-img').alt = data.title;

    modal.classList.add('active');
}

function closeModal() {
    modal.classList.remove('active');
}

document.querySelectorAll('.filters-sidebar input').forEach(input => {
    input.addEventListener('change', filterProducts);
});

modalClose.addEventListener('click', closeModal);
window.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
});

document.addEventListener('DOMContentLoaded', renderCatalogue);
