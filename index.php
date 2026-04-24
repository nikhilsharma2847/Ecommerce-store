<?php
require_once 'config.php';
include 'includes/header.php';

$query = "SELECT * FROM product ORDER BY product_id DESC";
$result = $conn->query($query);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$featured = array_slice($products, 0, 4);
?>

<section class="hero-banner" aria-label="Welcome">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="hero-grid">
        <div class="hero-copy">
            <span class="hero-kicker">
                <span class="hero-kicker__dot"></span>
                Spring collection live
            </span>
            <h1 class="hero-title">Design-led essentials for <span class="hero-title__accent">modern living</span></h1>
            <p class="hero-tagline">Curated gear, honest pricing, and delivery that respects your time. Shop pieces that look as good in your space as they do in your cart.</p>
            <div class="hero-actions">
                <a href="#latest-products" class="btn btn-primary btn-lg">Shop latest</a>
                <a href="#featured-products" class="btn btn-ghost btn-lg">See featured</a>
            </div>
            <dl class="hero-stats">
                <div class="hero-stat">
                    <dt class="hero-stat__value">12k+</dt>
                    <dd class="hero-stat__label">Happy orders</dd>
                </div>
                <div class="hero-stat">
                    <dt class="hero-stat__value">4.9</dt>
                    <dd class="hero-stat__label">Avg. rating</dd>
                </div>
                <div class="hero-stat">
                    <dt class="hero-stat__value">48h</dt>
                    <dd class="hero-stat__label">Fast dispatch</dd>
                </div>
            </dl>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <div class="hero-visual__frame">
                <img
                    src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&q=80"
                    alt=""
                    width="900"
                    height="675"
                    loading="eager"
                    decoding="async"
                >
                <div class="hero-visual__shine"></div>
            </div>
            <div class="hero-float hero-float--a">
                <span class="hero-float__icon" aria-hidden="true">✓</span>
                <span>Free shipping $50+</span>
            </div>
            <div class="hero-float hero-float--b">
                <span class="hero-float__label">Trending</span>
                <span class="hero-float__title">Audio &amp; desk</span>
            </div>
        </div>
    </div>
</section>

<div class="trust-strip">
    <div class="trust-item">
        <span class="trust-icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </span>
        <span><strong>Secure checkout</strong><span class="trust-sub">SSL encrypted</span></span>
    </div>
    <div class="trust-item">
        <span class="trust-icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        </span>
        <span><strong>Fast dispatch</strong><span class="trust-sub">Tracked delivery</span></span>
    </div>
    <div class="trust-item">
        <span class="trust-icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </span>
        <span><strong>Easy returns</strong><span class="trust-sub">30-day window</span></span>
    </div>
</div>

<?php if (count($featured) > 0): ?>
<section id="featured-products" class="home-section">
    <div class="section-head">
        <span class="section-label">Spotlight</span>
        <h2>Featured products</h2>
        <p class="section-sub">Staff picks and bestsellers flying off the shelf this week.</p>
    </div>
    <div class="product-grid product-grid--featured">
        <?php foreach ($featured as $row): ?>
            <?php $badge_label = 'Featured'; include 'includes/product-card.php'; ?>
        <?php endforeach; ?>
        <?php unset($badge_label); ?>
    </div>
</section>
<?php endif; ?>

<section id="latest-products" class="home-section">
    <div class="section-head">
        <span class="section-label">Catalog</span>
        <h2>Latest products</h2>
        <p class="section-sub">Fresh arrivals and restocks—updated as soon as new stock lands.</p>
    </div>
    <div class="product-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $row): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-catalog">No products in the database yet. Import sample data with <code>sql/sample_products.sql</code> or add products in the admin panel.</p>
        <?php endif; ?>
    </div>
</section>

<section class="reviews-section home-section" aria-labelledby="reviews-heading">
    <div class="section-head">
        <span class="section-label">Reviews</span>
        <h2 id="reviews-heading">Loved by real shoppers</h2>
        <p class="section-sub">We obsess over quality and service—here is what people say after unboxing.</p>
    </div>
    <div class="reviews-grid">
        <article class="review-card">
            <div class="review-card__top">
                <span class="review-avatar" aria-hidden="true">A</span>
                <div>
                    <p class="review-name">Alex M.</p>
                    <div class="review-stars" aria-hidden="true">★★★★★</div>
                </div>
            </div>
            <p class="review-text">“Shipping was fast and the packaging felt premium. The headphones sound incredible for the price.”</p>
        </article>
        <article class="review-card">
            <div class="review-card__top">
                <span class="review-avatar review-avatar--violet" aria-hidden="true">J</span>
                <div>
                    <p class="review-name">Jordan K.</p>
                    <div class="review-stars" aria-hidden="true">★★★★★</div>
                </div>
            </div>
            <p class="review-text">“Checkout took seconds and my order arrived two days early. I will definitely shop here again.”</p>
        </article>
        <article class="review-card">
            <div class="review-card__top">
                <span class="review-avatar review-avatar--cyan" aria-hidden="true">S</span>
                <div>
                    <p class="review-name">Sam R.</p>
                    <div class="review-stars" aria-hidden="true">★★★★☆</div>
                </div>
            </div>
            <p class="review-text">“Great selection of lifestyle goods. Support answered my sizing question within an hour.”</p>
        </article>
    </div>
</section>

<section class="cta-section" aria-labelledby="cta-heading">
    <div class="cta-inner">
        <div class="cta-glow" aria-hidden="true"></div>
        <div class="cta-content">
            <div class="cta-copy">
                <span class="section-label section-label--on-dark">Newsletter</span>
                <h2 id="cta-heading">Get drops before everyone else</h2>
                <p>Short notes on new arrivals and member-only discounts. Unsubscribe anytime.</p>
            </div>
            <form class="cta-form" action="#" method="post" onsubmit="return false;">
                <label class="visually-hidden" for="cta-email">Email address</label>
                <input type="email" id="cta-email" class="form-control cta-input" placeholder="you@example.com" autocomplete="email">
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
