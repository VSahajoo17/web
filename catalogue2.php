<?php
require 'db.php';

// Read filters from the address bar. Empty values mean "show all".
$locationId = filter_input(INPUT_GET, 'location', FILTER_VALIDATE_INT) ?: '';
$category = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');
$minPrice = filter_input(INPUT_GET, 'min_price', FILTER_VALIDATE_FLOAT);
$maxPrice = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);
$allowedCategories = ['women', 'men', 'accessories'];

if (!in_array($category, $allowedCategories, true)) {
    $category = '';
}

// Get choices for the location drop-down.
$locations = $pdo->query('SELECT id, location FROM locations ORDER BY location')->fetchAll(PDO::FETCH_ASSOC);

// An INNER JOIN ensures that displayed products have stock at a location.
$sql = 'SELECT DISTINCT i.id, i.itemname, i.price, i.imgurl, i.category
        FROM items i
        INNER JOIN itemlocate il ON i.id = il.itemid
        WHERE il.stock > 0';
$values = [];

if ($locationId !== '') {
    $sql .= ' AND il.locationid = :location';
    $values['location'] = $locationId;
}
if ($category !== '') {
    $sql .= ' AND i.category = :category';
    $values['category'] = $category;
}
if ($search !== '') {
    $sql .= ' AND i.itemname LIKE :search';
    $values['search'] = "%$search%";
}
if ($minPrice !== false && $minPrice !== null) {
    $sql .= ' AND i.price >= :min_price';
    $values['min_price'] = $minPrice;
}
if ($maxPrice !== false && $maxPrice !== null) {
    $sql .= ' AND i.price <= :max_price';
    $values['max_price'] = $maxPrice;
}

$sql .= ' ORDER BY i.itemname';
$statement = $pdo->prepare($sql);
$statement->execute($values);
$items = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue | RR Sarees</title>
    <link rel="stylesheet" href="cataloguestyle.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="index.html">RR Sarees</a>
        <nav>
            <a href="index.html">Home</a>
            <a class="active" href="catalogue2.php">Catalogue</a>
            <a href="login.html">Account</a>
        </nav>
        <button class="cart" type="button" aria-label="Shopping cart">Cart <span id="cart-count">0</span></button>
    </header>

    <main class="page-layout">
        <aside class="filters">
            <h1>Filters</h1>
            <form method="get" action="catalogue2.php">
                <label for="search">Search</label>
                <input id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search sarees">

                <label for="location">Available at</label>
                <select id="location" name="location">
                    <option value="">All locations</option>
                    <?php foreach ($locations as $location): ?>
                        <option value="<?= $location['id'] ?>" <?= (string) $locationId === (string) $location['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($location['location']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <fieldset>
                    <legend>Category</legend>
                    <?php foreach ($allowedCategories as $option): ?>
                        <label class="check-label">
                            <input type="radio" name="category" value="<?= $option ?>" <?= $category === $option ? 'checked' : '' ?>>
                            <?= ucfirst($option) ?>
                        </label>
                    <?php endforeach; ?>
                    <label class="check-label"><input type="radio" name="category" value="" <?= $category === '' ? 'checked' : '' ?>> All</label>
                </fieldset>

                <fieldset>
                    <legend>Price (Rs)</legend>
                    <div class="price-inputs">
                        <input name="min_price" type="number" min="0" step="0.01" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>" placeholder="Minimum">
                        <input name="max_price" type="number" min="0" step="0.01" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>" placeholder="Maximum">
                    </div>
                </fieldset>
                <button class="apply" type="submit">Apply filters</button>
                <a class="clear" href="catalogue2.php">Clear filters</a>
            </form>
        </aside>

        <section class="catalogue">
            <div class="catalogue-heading">
                <h2>Our collection</h2>
                <p><?= count($items) ?> item<?= count($items) === 1 ? '' : 's' ?> found</p>
            </div>
            <div class="item-grid">
                <?php foreach ($items as $item): ?>
                    <article class="card">
                        <img src="<?= htmlspecialchars($item['imgurl']) ?>" alt="<?= htmlspecialchars($item['itemname']) ?>" onerror="this.src='https://placehold.co/600x750/f2e6dc/5c3424?text=RR+Sarees'">
                        <div class="card-body">
                            <p class="category"><?= htmlspecialchars(ucfirst($item['category'])) ?></p>
                            <h3><?= htmlspecialchars($item['itemname']) ?></h3>
                            <p class="price">Rs <?= number_format((float) $item['price'], 2) ?></p>
                            <button class="add-cart" type="button" data-name="<?= htmlspecialchars($item['itemname']) ?>">Add to cart</button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php if (!$items): ?>
                <p class="empty">No items match these filters. Try clearing one of them.</p>
            <?php endif; ?>
        </section>
    </main>
    <script>
        let cartCount = 0;
        document.querySelectorAll('.add-cart').forEach(button => {
            button.addEventListener('click', () => {
                cartCount++;
                document.getElementById('cart-count').textContent = cartCount;
                button.textContent = 'Added';
                button.disabled = true;
            });
        });
    </script>
</body>
</html>
