<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if ($itemId === false || $itemId === null || $itemId < 1) {
        $message = 'That item could not be found.';
    } elseif ($action === 'add') {
        $statement = $pdo->prepare(
            'SELECT i.id
             FROM items i
             INNER JOIN itemlocate il ON i.id = il.itemid
             WHERE i.id = :id AND il.stock > 0
             LIMIT 1'
        );
        $statement->execute(['id' => $itemId]);

        if ($statement->fetchColumn()) {
            $_SESSION['cart'][$itemId] = ($_SESSION['cart'][$itemId] ?? 0) + 1;
            header('Location: cart.php');
            exit;
        }

        $message = 'This item is not currently available.';
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$itemId]);
        header('Location: cart.php');
        exit;
    } else {
        $message = 'That cart action is not supported.';
    }
}

$cart = $_SESSION['cart'];
$items = [];

if ($cart) {
    $itemIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
    $statement = $pdo->prepare(
        "SELECT i.id, i.itemname, i.price, i.imgurl
         FROM items i
         WHERE i.id IN ($placeholders)
         ORDER BY i.itemname"
    );
    $statement->execute($itemIds);
    $items = $statement->fetchAll(PDO::FETCH_ASSOC);
}

$cartCount = array_sum($cart);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your cart | RR Sarees</title>
    <link rel="stylesheet" href="cataloguestyle.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="index.html">RR Sarees</a>
        <nav>
            <a href="index.html">Home</a>
            <a href="catalogue2.php">Catalogue</a>
            <a href="login.html">Account</a>
        </nav>
        <a class="cart" href="cart.php" aria-label="Shopping cart">Cart <span><?= $cartCount ?></span></a>
    </header>

    <main class="cart-page">
        <div class="catalogue-heading">
            <h1>Your cart</h1>
            <p><?= $cartCount ?> item<?= $cartCount === 1 ? '' : 's' ?></p>
        </div>

        <?php if ($message !== ''): ?>
            <p class="empty" role="alert"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (!$items): ?>
            <p class="empty">Your cart is empty. <a href="catalogue2.php">Browse the catalogue</a>.</p>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <article class="cart-item">
                        <img src="<?= htmlspecialchars($item['imgurl'] ?? '') ?>" alt="<?= htmlspecialchars($item['itemname']) ?>" onerror="this.src='https://placehold.co/600x750/f2e6dc/5c3424?text=RR+Sarees'">
                        <div class="cart-item-details">
                            <h2><?= htmlspecialchars($item['itemname']) ?></h2>
                            <p>Rs <?= number_format((float) $item['price'], 2) ?></p>
                            <p>Quantity: <?= (int) ($cart[$item['id']] ?? 0) ?></p>
                        </div>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="item_id" value="<?= (int) $item['id'] ?>">
                            <button class="remove-cart" type="submit">Remove</button>
                        </form>
                    </article>
                <?php endforeach; ?>
                <div class="cart-actions">
    <a class="return-btn" href="catalogue2.php">
        Return to Catalogue
    </a>

    <?php if (isset($_SESSION['userid'])): ?>
        <a class="checkout-btn" href="payment.php">
            Proceed to Payment
        </a>
    <?php endif; ?>
</div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
