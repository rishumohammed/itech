<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Product / Brand Detail Page
//  URL: /public/product.php?slug=cisco
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/config.php';

// ── Fetch product by slug ────────────────────────────────────
$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) { header('Location: ' . SITE_URL . 'public/index.php'); exit; }

$slug_safe = db_escape($slug, $conn);
$res       = mysqli_query($conn, "SELECT * FROM `products` WHERE `slug`='$slug_safe' LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    http_response_code(404);
    header('Location: ' . SITE_URL . 'public/index.php');
    exit;
}
$product = mysqli_fetch_assoc($res);

// ── SEO ───────────────────────────────────────────────────────
$brandName       = h($product['name']);
$metaTitle       = $product['name'] . ' — Technology Partner | ' . SITE_NAME;
$metaDescription = $product['description']
    ? mb_strimwidth($product['description'], 0, 160, '…')
    : 'I Tech IT Solutions is an authorised partner of ' . $product['name'] . '. We supply, install and support ' . $product['name'] . ' products across enterprise environments.';
$canonicalUrl    = SITE_URL . 'public/product.php?slug=' . urlencode($slug);
$hasLogo         = !empty($product['logo']);

// ── Other brands ─────────────────────────────────────────────
$others = [];
$oRes = mysqli_query($conn, "SELECT id,name,slug,logo FROM `products`
                              WHERE id != {$product['id']} ORDER BY id ASC LIMIT 8");
if ($oRes) { while ($r = mysqli_fetch_assoc($oRes)) $others[] = $r; }

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ── Breadcrumb ────────────────────────────────────────────── -->
<nav class="bg-slate-50 border-b border-slate-100 py-3" aria-label="Breadcrumb">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex items-center gap-2 text-sm text-slate-500">
    <a href="<?= SITE_URL ?>public/index.php" class="hover:text-[#5d1d1f] transition-colors">Home</a>
    <span class="text-slate-300">›</span>
    <a href="<?= SITE_URL ?>public/index.php#tech-partners" class="hover:text-[#5d1d1f] transition-colors">Technology Partners</a>
    <span class="text-slate-300">›</span>
    <span class="text-[#5d1d1f] font-semibold"><?= $brandName ?></span>
  </div>
</nav>

<!-- ── Hero Banner ───────────────────────────────────────────── -->
<section class="relative bg-[#0f172a] overflow-hidden py-16 lg:py-24 border-b border-slate-100">
  <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#5d1d1f_1px,transparent_1px)] [background-size:24px_24px]"></div>
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="flex flex-col lg:flex-row items-center lg:items-start gap-12">

      <!-- Logo / Icon -->
      <div data-anim="scale" class="shrink-0">
        <?php if ($hasLogo): ?>
        <div class="w-40 h-40 rounded-3xl border border-slate-100 shadow-xl bg-white flex items-center justify-center p-8">
          <img src="<?= SITE_URL ?>public/<?= h($product['logo']) ?>" alt="<?= $brandName ?> logo"
               class="max-w-full max-h-full object-contain"/>
        </div>
        <?php else: ?>
        <div class="w-40 h-40 rounded-3xl bg-gradient-to-br from-[#5d1d1f] to-[#1a1a2e] shadow-2xl flex items-center justify-center border border-[#efac3a]/20">
          <span class="text-[#efac3a] text-4xl font-black tracking-tight"><?= strtoupper(substr($product['name'], 0, 2)) ?></span>
        </div>
        <?php endif; ?>
      </div>

      <!-- Info -->
      <div data-anim="slide-right" class="flex-1">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#5d1d1f]/40 backdrop-blur-md text-[#fca5a5] text-xs font-black rounded-full uppercase tracking-widest mb-5 border border-white/10">
          Technology Partner
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-white mb-4"><?= $brandName ?></h1>

        <?php if (!empty($product['description'])): ?>
        <p class="text-slate-300 text-lg leading-relaxed max-w-2xl mb-6"><?= h($product['description']) ?></p>
        <?php else: ?>
        <p class="text-slate-500 text-base leading-relaxed max-w-2xl mb-6">
          I Tech IT Solutions is an authorised reseller and integration partner of <?= $brandName ?>.
          We supply, configure, and support <?= $brandName ?> products across enterprise, hospitality,
          healthcare, retail, and industrial environments.
        </p>
        <?php endif; ?>

        <div class="flex flex-wrap gap-3 mb-8">
          <a href="<?= SITE_URL ?>public/index.php#contact-section"
             class="btn-press px-7 py-3 bg-[#5d1d1f] text-white font-bold rounded-full hover:bg-[#4a1517] transition-all text-sm shadow-lg border border-[#efac3a]/20 text-[#efac3a]">
            Request <?= $brandName ?> Products
          </a>
          <?php if (!empty($product['website'])): ?>
          <a href="<?= h($product['website']) ?>" target="_blank" rel="noopener noreferrer"
             class="btn-press px-7 py-3 bg-slate-100 text-slate-700 font-bold rounded-full hover:bg-slate-200 transition-all text-sm">
            Official Website ↗
          </a>
          <?php endif; ?>
        </div>

        <!-- Partnership badges -->
        <div class="flex flex-wrap gap-4">
          <?php foreach (['Authorised Reseller', 'Certified Engineers', 'OEM Warranty Support', '24/7 Helpdesk'] as $badge): ?>
          <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-semibold text-slate-700"><?= $badge ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── What we offer with this brand ────────────────────────── -->
<section class="bg-slate-50 py-16">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="text-center max-w-xl mx-auto mb-12" data-anim>
      <h2 class="text-3xl font-black text-[#1a1a2e] mb-3 anim-underline">What We Offer</h2>
      <p class="text-slate-500 text-sm">End-to-end <?= $brandName ?> services — from procurement to post-support</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php
      $offers = [
          ['Supply & Procurement',       'Genuine <?= $brandName ?> products at competitive prices with full OEM warranty.',          'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z'],
          ['Design & Engineering',       'Expert solution architecture and capacity planning by certified engineers.',               'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
          ['Professional Installation',  'On-site deployment and commissioning following manufacturer best practices.',              'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
          ['Configuration & Testing',    'Factory default configuration, fine-tuning, performance testing and sign-off.',           'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
          ['AMC & Maintenance',          'Annual maintenance contracts with guaranteed SLA response times and replacement.',         'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
          ['Training & Handover',        'Hands-on staff training and complete documentation handover on completion.',              'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
      ];
      foreach ($offers as $i => [$offerTitle, $offerDesc, $iconPath]):
      ?>
      <div data-anim data-delay="<?= $i + 1 ?>" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-[#5d1d1f]/20 hover:-translate-y-1 transition-all duration-300">
        <div class="w-11 h-11 rounded-xl bg-[#5d1d1f]/10 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-[#5d1d1f]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $iconPath ?>"/>
          </svg>
        </div>
        <h3 class="font-bold text-[#1a1a2e] text-sm mb-2"><?= $offerTitle ?></h3>
        <p class="text-slate-500 text-xs leading-relaxed"><?= $offerDesc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── CTA Strip ─────────────────────────────────────────────── -->
<section class="bg-[#0f172a] py-14">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex flex-col sm:flex-row items-center justify-between gap-6" data-anim>
    <div>
      <h2 class="text-2xl font-black text-white mb-1">Ready to deploy <?= $brandName ?>?</h2>
      <p class="text-slate-400 text-sm">Get a competitive quote from our certified <?= $brandName ?> engineers today.</p>
    </div>
    <a href="<?= SITE_URL ?>public/index.php#contact-section"
       class="btn-press shrink-0 px-8 py-3.5 bg-[#5d1d1f] text-white font-bold rounded-full hover:bg-[#4a1517] transition-all text-sm shadow-lg border border-[#efac3a]/20 text-[#efac3a] whitespace-nowrap">
      Get a Quote
    </a>
  </div>
</section>

<!-- ── Other Technology Partners ─────────────────────────────── -->
<?php if (!empty($others)): ?>
<section class="bg-white py-14">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <h2 class="text-2xl font-black text-[#1a1a2e] mb-8" data-anim>Other Technology Partners</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
      <?php foreach ($others as $i => $o): ?>
      <a href="product.php?slug=<?= urlencode($o['slug'] ?? '') ?>"
         data-anim="scale" data-delay="<?= ($i % 8) + 1 ?>"
         class="group flex items-center justify-center h-16 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-md hover:border-[#5d1d1f]/20 transition-all duration-300 px-3">
        <?php if (!empty($o['logo'])): ?>
        <img src="<?= SITE_URL . h($o['logo']) ?>" alt="<?= h($o['name']) ?>"
             class="max-h-8 max-w-full object-contain opacity-50 group-hover:opacity-100 transition-opacity lazy-blur"/>
        <?php else: ?>
        <span class="text-slate-400 group-hover:text-[#5d1d1f] font-black text-sm tracking-tight transition-colors">
          <?= h($o['name']) ?>
        </span>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
if ($conn) { @mysqli_close($conn); }
require_once __DIR__ . '/../includes/footer.php';
?>
