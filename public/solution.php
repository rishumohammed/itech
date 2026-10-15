<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Solution Detail Page
//  URL: /public/solution.php?slug=hotels
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/config.php';

// ── Fetch solution by slug ───────────────────────────────────
$slug     = trim($_GET['slug'] ?? '');
if (empty($slug)) { header('Location: ' . SITE_URL . 'public/index.php'); exit; }

$slug_safe = db_escape($slug, $conn);
$res       = mysqli_query($conn, "SELECT * FROM `solutions` WHERE `slug`='$slug_safe' LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    http_response_code(404);
    header('Location: ' . SITE_URL . 'public/index.php');
    exit;
}
$sol = mysqli_fetch_assoc($res);

// ── SEO ───────────────────────────────────────────────────────
$title           = h($sol['title']);
$metaTitle       = $sol['title'] . ' IT Solutions | ' . SITE_NAME;
$metaDescription = $sol['meta_description'] ?: ($sol['description'] ?: DEFAULT_META_DESCRIPTION);
$canonicalUrl    = SITE_URL . 'public/solution.php?slug=' . urlencode($slug);
$metaOgImage     = !empty($sol['image']) ? SITE_URL . 'uploads/' . $sol['image'] : SITE_URL . 'assets/images/hero_datacenter.png';

// ── Related solutions (exclude current) ─────────────────────
$related = [];
$rRes = mysqli_query($conn, "SELECT id,title,slug,description,image FROM `solutions`
                              WHERE id != {$sol['id']} ORDER BY id ASC LIMIT 4");
if ($rRes) { while ($r = mysqli_fetch_assoc($rRes)) $related[] = $r; }

// ── Parse technologies list (comma-separated) ───────────────
$techList = [];
if (!empty($sol['technologies'])) {
    $techList = array_filter(array_map('trim', explode(',', $sol['technologies'])));
}

require_once __DIR__ . '/../includes/header.php';

// Helper: split textarea content into bullet points
function splitLines(string $text): array {
    $lines = preg_split('/\r?\n/', trim($text));
    return array_filter(array_map('trim', $lines));
}
?>

<!-- ── Breadcrumb ────────────────────────────────────────────── -->
<nav class="bg-slate-50 border-b border-slate-100 py-3" aria-label="Breadcrumb">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex items-center gap-2 text-sm text-slate-500">
    <a href="<?= SITE_URL ?>public/index.php" class="hover:text-[#5d1d1f] transition-colors">Home</a>
    <span class="text-slate-300">›</span>
    <a href="<?= SITE_URL ?>public/index.php#industry-solutions" class="hover:text-[#5d1d1f] transition-colors">Solutions</a>
    <span class="text-slate-300">›</span>
    <span class="text-[#5d1d1f] font-semibold"><?= $title ?></span>
  </div>
</nav>

<!-- ── Hero Banner ───────────────────────────────────────────── -->
<section class="relative bg-[#0f172a] overflow-hidden">
  <?php if (!empty($sol['image'])): ?>
  <img src="<?= SITE_URL ?>uploads/<?= h($sol['image']) ?>" alt="<?= $title ?>"
       class="absolute inset-0 w-full h-full object-cover opacity-30"/>
  <?php else: ?>
  <div class="absolute inset-0" style="background:radial-gradient(circle,rgba(93,29,31,.3) 1px,transparent 1px);background-size:32px 32px" aria-hidden="true"></div>
  <?php endif; ?>
  <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] via-[#0f172a]/80 to-[#0f172a]/40"></div>
  <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-[#5d1d1f]/15 rounded-full blur-3xl pointer-events-none"></div>

  <div class="relative max-w-screen-xl mx-auto px-6 lg:px-10 py-20 lg:py-28">
    <div class="max-w-2xl" data-anim="slide-left">
      <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#5d1d1f]/30 text-[#efac3a] text-xs font-bold rounded-full uppercase tracking-widest mb-6">
        <span class="w-1.5 h-1.5 bg-[#efac3a] rounded-full animate-pulse"></span>
        Industry Solution
      </span>
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
        <?= $title ?>
      </h1>
      <?php if (!empty($sol['description'])): ?>
      <p class="text-slate-300 text-lg leading-relaxed mb-8"><?= h($sol['description']) ?></p>
      <?php endif; ?>
      <div class="flex flex-wrap gap-3">
        <a href="<?= SITE_URL ?>public/index.php#contact-section"
           class="btn-press px-7 py-3 bg-[#5d1d1f] text-white font-bold rounded-full hover:bg-[#4a1517] hover:-translate-y-0.5 transition-all text-sm shadow-lg border border-[#efac3a]/20 text-[#efac3a]">
          Get a Quote
        </a>
        <a href="<?= SITE_URL ?>public/index.php#contact-section"
           class="btn-press px-7 py-3 bg-white/10 text-white font-bold rounded-full border border-white/20 hover:bg-white/20 hover:-translate-y-0.5 transition-all text-sm backdrop-blur-sm">
          Talk to an Expert
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ── Main content + Sidebar ────────────────────────────────── -->
<div class="max-w-screen-xl mx-auto px-6 lg:px-10 py-16 lg:py-20">
  <div class="flex flex-col lg:flex-row gap-12">

    <!-- ══ LEFT: content sections (2/3) ══ -->
    <div class="flex-1 min-w-0 space-y-14">

      <!-- 1. Industry Overview -->
      <section data-anim>
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-xl bg-[#5d1d1f]/10 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-[#5d1d1f]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <h2 class="text-2xl font-black text-[#1a1a2e]">Industry Overview</h2>
        </div>
        <?php if (!empty($sol['full_description'])): ?>
          <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-3">
            <?php foreach (splitLines($sol['full_description']) as $line): ?>
            <p><?= h($line) ?></p>
            <?php endforeach; ?>
          </div>
        <?php elseif (!empty($sol['description'])): ?>
          <p class="text-slate-600 leading-relaxed"><?= h($sol['description']) ?></p>
          <p class="text-slate-400 text-sm mt-4 italic">Detailed overview coming soon — contact us to learn more about our <?= $title ?> solutions.</p>
        <?php else: ?>
          <p class="text-slate-400 italic">Content coming soon. <a href="<?= SITE_URL ?>public/index.php#contact-section" class="text-[#5d1d1f] hover:underline">Contact us</a> for details.</p>
        <?php endif; ?>
      </section>

      <hr class="border-slate-100"/>

      <!-- 2. Key Challenges -->
      <section data-anim>
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
          <h2 class="text-2xl font-black text-[#1a1a2e]">Key Challenges</h2>
        </div>
        <?php
        $challengeLines = !empty($sol['challenges']) ? splitLines($sol['challenges']) : [];
        ?>
        <?php if (!empty($challengeLines)): ?>
        <ul class="space-y-3">
          <?php foreach ($challengeLines as $i => $c): ?>
          <li data-anim data-delay="<?= ($i % 6) + 1 ?>" class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
            <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
              </svg>
            </div>
            <p class="text-slate-700 text-sm leading-relaxed"><?= h($c) ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <?php foreach (['Network reliability & uptime', 'Cybersecurity threats', 'Legacy system integration', 'Scalability constraints'] as $i => $c): ?>
          <div data-anim data-delay="<?= $i + 1 ?>" class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
            <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0 mt-0.5 text-amber-600">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <p class="text-slate-700 text-sm"><?= $c ?></p>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="text-slate-400 text-xs mt-3 italic">Edit via Admin → Solutions to add industry-specific challenges.</p>
        <?php endif; ?>
      </section>

      <hr class="border-slate-100"/>

      <!-- 3. Our Solutions -->
      <section data-anim>
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h2 class="text-2xl font-black text-[#1a1a2e]">Our Solutions</h2>
        </div>
        <?php
        $ourLines = !empty($sol['our_solution']) ? splitLines($sol['our_solution']) : [];
        ?>
        <?php if (!empty($ourLines)): ?>
        <ul class="space-y-3">
          <?php foreach ($ourLines as $i => $s): ?>
          <li data-anim data-delay="<?= ($i % 6) + 1 ?>" class="flex items-start gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <p class="text-slate-700 text-sm leading-relaxed"><?= h($s) ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <?php foreach (['End-to-end IT infrastructure design', '24/7 monitoring & support', 'Vendor-certified implementation', 'Future-ready scalable architecture'] as $i => $s): ?>
          <div data-anim data-delay="<?= $i + 1 ?>" class="flex items-start gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-slate-700 text-sm"><?= $s ?></p>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="text-slate-400 text-xs mt-3 italic">Edit via Admin → Solutions to add specific solution points.</p>
        <?php endif; ?>
      </section>

      <!-- 4. Technologies Used -->
      <?php if (!empty($techList) || true): ?>
      <section data-anim>
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
            </svg>
          </div>
          <h2 class="text-2xl font-black text-[#1a1a2e]">Technologies Used</h2>
        </div>
        <div class="flex flex-wrap gap-3">
          <?php
          $displayTech = !empty($techList) ? $techList : ['Cisco', 'Fortinet', 'Huawei', 'Hikvision', 'Bosch', 'Honeywell'];
          foreach ($displayTech as $i => $tech):
          ?>
          <span data-anim="scale" data-delay="<?= ($i % 8) + 1 ?>"
                class="px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-xl border border-blue-100 hover:bg-blue-100 hover:border-blue-200 transition-all cursor-default">
            <?= h($tech) ?>
          </span>
          <?php endforeach; ?>
          <?php if (empty($techList)): ?>
          <p class="w-full text-slate-400 text-xs mt-1 italic">Edit via Admin → Solutions to add your specific technology stack.</p>
          <?php endif; ?>
        </div>
      </section>
      <?php endif; ?>

    </div><!-- /left -->

    <!-- ══ RIGHT: Sidebar (1/3) ══ -->
    <aside class="lg:w-80 shrink-0 space-y-6">

      <!-- Solution image -->
      <?php if (!empty($sol['image'])): ?>
      <div data-anim="scale" class="rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-slate-100">
        <img src="<?= SITE_URL ?>uploads/<?= h($sol['image']) ?>" alt="<?= $title ?>"
             class="w-full h-52 object-cover transition-transform duration-700 hover:scale-110"/>
      </div>
      <?php endif; ?>

      <!-- Quick info card -->
      <div data-anim class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
        <h3 class="font-black text-[#1a1a2e] text-sm uppercase tracking-wide mb-4">Solution Info</h3>
        <dl class="space-y-3 text-sm">
          <div class="flex justify-between items-start gap-2">
            <dt class="text-slate-500 shrink-0">Category</dt>
            <dd class="font-semibold text-[#1a1a2e] text-right"><?= $title ?></dd>
          </div>
          <div class="flex justify-between items-start gap-2">
            <dt class="text-slate-500 shrink-0">Support</dt>
            <dd class="font-semibold text-emerald-600">24/7 Available</dd>
          </div>
          <div class="flex justify-between items-start gap-2">
            <dt class="text-slate-500 shrink-0">Deployment</dt>
            <dd class="font-semibold text-[#1a1a2e]">On-site & Cloud</dd>
          </div>
          <div class="flex justify-between items-start gap-2">
            <dt class="text-slate-500 shrink-0">Warranty</dt>
            <dd class="font-semibold text-[#1a1a2e]">OEM Certified</dd>
          </div>
        </dl>
      </div>

      <!-- CTA card -->
      <div data-anim class="bg-gradient-to-br from-[#5d1d1f] to-[#1a1a2e] rounded-2xl p-6 text-white border border-[#efac3a]/20">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
        <h3 class="font-black text-white text-base mb-2">Get a Free Consultation</h3>
        <p class="text-slate-300 text-sm mb-5 leading-relaxed">Talk to our <?= $title ?> specialists and get a tailored IT roadmap for your business.</p>
        <a href="<?= SITE_URL ?>public/index.php#contact-section"
           class="block text-center px-5 py-2.5 bg-white text-[#5d1d1f] text-sm font-black rounded-xl hover:bg-slate-50 transition-all">
          Request Consultation
        </a>
      </div>

      <!-- Related solutions compact -->
      <?php if (!empty($related)): ?>
      <div data-anim>
        <h3 class="font-black text-[#1a1a2e] text-sm uppercase tracking-wide mb-4">Other Solutions</h3>
        <div class="space-y-3">
          <?php foreach ($related as $r): ?>
          <a href="solution.php?slug=<?= urlencode($r['slug'] ?? '') ?>"
             class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-[#5d1d1f]/20 hover:shadow-sm transition-all group">
            <div class="w-8 h-8 rounded-lg bg-[#5d1d1f]/10 flex items-center justify-center shrink-0 group-hover:bg-[#5d1d1f] transition-colors">
              <svg class="w-4 h-4 text-[#5d1d1f] group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
              </svg>
            </div>
            <div class="min-w-0">
              <p class="text-sm font-bold text-[#1a1a2e] group-hover:text-[#5d1d1f] transition-colors truncate"><?= h($r['title']) ?></p>
              <?php if (!empty($r['description'])): ?>
              <p class="text-xs text-slate-400 truncate"><?= h($r['description']) ?></p>
              <?php endif; ?>
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-[#5d1d1f] shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

    </aside><!-- /sidebar -->

  </div>
</div>

<!-- ── Full-width CTA Banner ──────────────────────────────────── -->
<section class="bg-[#0f172a] py-16">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 text-center" data-anim>
    <h2 class="text-3xl font-black text-white mb-4">Ready to Transform Your <?= $title ?> IT?</h2>
    <p class="text-slate-400 text-base mb-8 max-w-xl mx-auto">Let our certified engineers design and deploy a complete IT solution tailored to your industry needs.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="<?= SITE_URL ?>public/index.php#contact-section"
         class="btn-press px-8 py-3.5 bg-[#5d1d1f] text-white font-bold rounded-full hover:bg-[#4a1517] hover:-translate-y-0.5 transition-all text-sm shadow-lg border border-[#efac3a]/20 text-[#efac3a]">
        Get a Free Quote
      </a>
      <a href="<?= SITE_URL ?>public/index.php#services-overview"
         class="btn-press px-8 py-3.5 bg-white/10 text-white font-bold rounded-full border border-white/20 hover:bg-white/20 hover:-translate-y-0.5 transition-all text-sm">
        View All Services
      </a>
    </div>
  </div>
</section>

<?php
if ($conn) { @mysqli_close($conn); }
require_once __DIR__ . '/../includes/footer.php';
?>
