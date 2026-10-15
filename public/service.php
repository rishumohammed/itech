<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Service Detail Page
//  URL: /public/service.php?slug=datacenter
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/config.php';

// ── Fetch service by slug ────────────────────────────────────
$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) { header('Location: ' . SITE_URL . 'public/index.php'); exit; }

$slug_safe = db_escape($slug, $conn);
$res       = mysqli_query($conn, "SELECT * FROM `services` WHERE `slug`='$slug_safe' LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    http_response_code(404);
    header('Location: ' . SITE_URL . 'public/index.php');
    exit;
}
$svc = mysqli_fetch_assoc($res);

// ── Determine Hero Background Image ──────────────────────────
$heroBg = '';
if (!empty($svc['image'])) {
    $heroBg = (strpos($svc['image'], 'uploads/') === 0) ? SITE_URL . $svc['image'] : SITE_URL . 'uploads/' . $svc['image'];
} elseif (!empty($svc['icon']) && strpos($svc['icon'], '.') !== false) {
    $heroBg = (strpos($svc['icon'], 'uploads/') === 0) ? SITE_URL . $svc['icon'] : SITE_URL . 'uploads/' . $svc['icon'];
} else {
    // Smart fallback by slug
    $slugMap = [
        'datacenter-infrastructure' => 'serv_datacenter.png',
        'cabling'                   => 'serv_cabling.png',
        'ip-surveillance'           => 'serv_surveillance.png',
        'cloud-services'            => 'serv_cloud.png',
        'wireless-microwave'        => 'bg_parallax_network.png',
        'switching-routing'         => 'noc_center.png',
        'firewall-utm'              => 'hero_enterprise.png',
        'vpn-solutions'             => 'itech_expert.png',
        'internet-services'         => 'mnc_hero.png',
    ];
    if (!empty($svc['slug']) && isset($slugMap[$svc['slug']])) {
        $heroBg = SITE_URL . 'uploads/' . $slugMap[$svc['slug']];
    }
}

// ── SEO ───────────────────────────────────────────────────────
$title           = h($svc['title']);
$metaTitle       = $svc['title'] . ' Services | ' . SITE_NAME;
$metaDescription = $svc['meta_description'] ?? ($svc['description'] ?: DEFAULT_META_DESCRIPTION);
$canonicalUrl    = SITE_URL . 'public/service.php?slug=' . urlencode($slug);
$metaOgImage     = !empty($heroBg) ? $heroBg : SITE_URL . 'assets/images/hero_datacenter.png';

// ── Related services ─────────────────────────────────────────
$related = [];
$rRes = mysqli_query($conn, "SELECT id,title,slug,description FROM `services`
                              WHERE id != {$svc['id']} ORDER BY id ASC LIMIT 4");
if ($rRes) { while ($r = mysqli_fetch_assoc($rRes)) $related[] = $r; }

// ── Parse sub-services and use cases (newline separated) ─────
function parseLines(string $text): array {
    $lines = preg_split('/\r?\n/', trim($text));
    return array_filter(array_map('trim', $lines));
}

$subServices = !empty($svc['sub_services']) ? parseLines($svc['sub_services']) : [];
$useCases    = !empty($svc['use_cases'])    ? parseLines($svc['use_cases'])    : [];

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ── Breadcrumb ────────────────────────────────────────────── -->
<nav class="bg-slate-50 border-b border-slate-100 py-3" aria-label="Breadcrumb">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex items-center gap-2 text-sm text-slate-500">
    <a href="<?= SITE_URL ?>public/index.php" class="hover:text-[#5d1d1f] transition-colors">Home</a>
    <span class="text-slate-300">›</span>
    <a href="<?= SITE_URL ?>public/index.php#services-overview" class="hover:text-[#5d1d1f] transition-colors">Services</a>
    <span class="text-slate-300">›</span>
    <span class="text-[#5d1d1f] font-semibold"><?= $title ?></span>
  </div>
</nav>

<!-- ── Hero Banner ───────────────────────────────────────────── -->
<section class="relative bg-[#0f172a] overflow-hidden py-24 lg:py-32">
  <?php if (!empty($heroBg)): ?>
    <img src="<?= h($heroBg) ?>" alt="<?= $title ?>" class="absolute inset-0 w-full h-full object-cover opacity-30 filter brightness-90"/>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] via-[#0f172a]/85 to-[#0f172a]/50"></div>
  <?php else: ?>
    <div class="absolute inset-0" style="background:radial-gradient(circle,rgba(93,29,31,.2) 1px,transparent 1px);background-size:28px 28px" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] via-[#0f172a]/80 to-[#0f172a]/40"></div>
  <?php endif; ?>
  <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#5d1d1f]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>

  <div class="relative max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="flex flex-col lg:flex-row items-center lg:items-start gap-10">

      <!-- Icon block -->
      <div data-anim="scale" class="w-24 h-24 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-2xl shrink-0">
        <?php if (!empty($svc['icon']) && strpos($svc['icon'], '.') !== false): ?>
          <img src="<?= SITE_URL ?>uploads/<?= h($svc['icon']) ?>" class="w-14 h-14 object-cover rounded-xl" alt="icon"/>
        <?php else: ?>
          <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        <?php endif; ?>
      </div>

      <!-- Text -->
      <div data-anim="slide-right">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#5d1d1f]/30 text-[#efac3a] text-xs font-bold rounded-full uppercase tracking-widest mb-5">
          <span class="w-1.5 h-1.5 bg-[#efac3a] rounded-full animate-pulse"></span>
          Core Service
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight mb-4"><?= $title ?></h1>
        <?php if (!empty($svc['description'])): ?>
        <p class="text-slate-300 text-lg leading-relaxed max-w-2xl"><?= h($svc['description']) ?></p>
        <?php endif; ?>
        <div class="flex flex-wrap gap-3 mt-8">
          <a href="<?= SITE_URL ?>public/index.php#contact-section"
             class="btn-press px-7 py-3 bg-[#5d1d1f] text-white font-bold rounded-full hover:bg-[#4a1517] transition-all text-sm shadow-lg border border-[#efac3a]/20 text-[#efac3a]">
            Request Service
          </a>
          <a href="<?= SITE_URL ?>public/index.php#contact-section"
             class="btn-press px-7 py-3 bg-white/10 text-white font-bold rounded-full border border-white/20 hover:bg-white/20 transition-all text-sm">
            Get a Quote
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── Full Description ───────────────────────────────────────── -->
<?php if (!empty($svc['full_description'])): ?>
<section class="bg-white py-14">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10" data-anim>
    <div class="max-w-3xl">
      <h2 class="text-2xl font-black text-[#1a1a2e] mb-5">About This Service</h2>
      <div class="text-slate-600 leading-relaxed space-y-3">
        <?php foreach (parseLines($svc['full_description']) as $line): ?>
        <p><?= h($line) ?></p>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── Sub-Services Grid ──────────────────────────────────────── -->
<section class="bg-slate-50 py-16">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="text-center max-w-xl mx-auto mb-12" data-anim>
      <h2 class="text-3xl font-black text-[#1a1a2e] mb-3 anim-underline">What's Included</h2>
      <p class="text-slate-500 text-sm">Comprehensive coverage across all aspects of <?= $title ?></p>
    </div>

    <?php
    $defaultSubs = [
        'Design & Planning'        => 'Expert needs assessment and solution architecture.',
        'Implementation'           => 'Professional on-site installation and configuration.',
        'Testing & Commissioning'  => 'Rigorous quality assurance before go-live.',
        'Training & Documentation' => 'Staff training and full technical documentation.',
        'Maintenance & Support'    => 'Ongoing monitoring, maintenance, and 24/7 helpdesk.',
        'Warranty & SLA'           => 'OEM-backed warranties and guaranteed response times.',
    ];
    ?>

    <?php if (!empty($subServices)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($subServices as $i => $sub): ?>
      <div data-anim data-delay="<?= ($i % 6) + 1 ?>" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-[#5d1d1f]/20 hover:-translate-y-1 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-[#5d1d1f]/10 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-[#5d1d1f]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <p class="font-semibold text-[#1a1a2e] text-sm"><?= h($sub) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($defaultSubs as $i => [$subTitle, $subDesc]): ?>
      <?php $idx = array_search($subTitle, array_keys($defaultSubs)); ?>
      <div data-anim data-delay="<?= ($idx % 6) + 1 ?>" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-[#5d1d1f]/20 hover:-translate-y-1 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-[#5d1d1f]/10 flex items-center justify-center mb-4">
          <svg class="w-5 h-5 text-[#5d1d1f]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="font-bold text-[#1a1a2e] text-sm mb-1"><?= $subTitle ?></h3>
        <p class="text-slate-500 text-xs leading-relaxed"><?= $subDesc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="text-slate-400 text-xs mt-4 text-center italic">Edit via Admin → Services to customise the sub-services list.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ── Use Cases ─────────────────────────────────────────────── -->
<section class="bg-white py-16">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="flex flex-col lg:flex-row gap-12 items-start">

      <!-- Left: use cases -->
      <div class="flex-1">
        <h2 class="text-3xl font-black text-[#1a1a2e] mb-8" data-anim>Use Cases</h2>

        <?php
        $defaultUseCases = [
            'Enterprise office rollout',
            'Multi-branch network expansion',
            'Data centre modernisation',
            'Cloud migration & hybrid setup',
            'Disaster recovery planning',
        ];
        $cases = !empty($useCases) ? $useCases : $defaultUseCases;
        ?>

        <ol class="space-y-4">
          <?php foreach ($cases as $i => $uc): ?>
          <li data-anim data-delay="<?= ($i % 6) + 1 ?>" class="flex items-center gap-4">
            <span class="flex-shrink-0 w-9 h-9 rounded-full bg-[#5d1d1f] text-[#efac3a] text-sm font-black flex items-center justify-center shadow">
              <?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?>
            </span>
            <div class="flex-1 p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 font-medium">
              <?= h($uc) ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ol>
        <?php if (empty($useCases)): ?>
        <p class="text-slate-400 text-xs mt-3 italic">Edit via Admin → Services to add industry-specific use cases.</p>
        <?php endif; ?>
      </div>

      <!-- Right: CTA card -->
      <div class="lg:w-72 shrink-0" data-anim="slide-right">
        <div class="bg-gradient-to-br from-[#5d1d1f] to-[#1a1a2e] rounded-2xl p-7 text-white sticky top-24 border border-[#efac3a]/20">
          <svg class="w-8 h-8 text-[#efac3a] mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
          </svg>
          <h3 class="font-black text-white text-lg mb-2">Need <?= $title ?>?</h3>
          <p class="text-slate-300 text-sm mb-6 leading-relaxed">Our certified engineers are ready to assess your requirements and design the perfect solution.</p>
          <a href="<?= SITE_URL ?>public/index.php#contact-section"
             class="block text-center px-5 py-3 bg-white text-[#5d1d1f] text-sm font-black rounded-xl hover:bg-slate-50 transition-all mb-3">
            Request a Quote
          </a>
          <a href="tel:+914844000000"
             class="block text-center px-5 py-2.5 bg-white/10 text-white text-sm font-semibold rounded-xl hover:bg-white/20 transition-all">
            📞 Call Us Now
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── Related Services ───────────────────────────────────────── -->
<?php if (!empty($related)): ?>
<section class="bg-slate-50 py-14">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <h2 class="text-2xl font-black text-[#1a1a2e] mb-8" data-anim>Related Services</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php foreach ($related as $i => $r): ?>
      <a href="service.php?slug=<?= urlencode($r['slug'] ?? '') ?>"
         data-anim data-delay="<?= $i + 1 ?>"
         class="group block bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-[#5d1d1f]/20 hover:-translate-y-1 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-[#5d1d1f]/10 flex items-center justify-center mb-4 group-hover:bg-[#5d1d1f] transition-colors">
          <svg class="w-5 h-5 text-[#5d1d1f] group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </div>
        <h3 class="font-bold text-[#1a1a2e] text-sm group-hover:text-[#5d1d1f] transition-colors"><?= h($r['title']) ?></h3>
        <?php if (!empty($r['description'])): ?>
        <p class="text-slate-400 text-xs mt-1 leading-relaxed"><?= h(mb_strimwidth($r['description'], 0, 70, '…')) ?></p>
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
