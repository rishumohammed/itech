<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Enterprise Header (v4 - Unified)
//  File: includes/header.php
//  Features: Quad-Mega Menus, Top Bar, Dynamic CMS Content
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ── Fetch dynamic menu items safely ─────────────────────────
$solutionsMenu = [];
$servicesMenu = [];
$productsMenu = [];

if ($conn) {
    try {
        $solRes = @mysqli_query($conn, "SELECT id, title, slug, description FROM `solutions` ORDER BY id ASC");
        if ($solRes) { while ($r = mysqli_fetch_assoc($solRes)) $solutionsMenu[] = $r; }
    } catch (Throwable $e) {}

    try {
        $serRes = @mysqli_query($conn, "SELECT id, title, slug, description, icon FROM `services` ORDER BY id ASC");
        if ($serRes) { while ($r = mysqli_fetch_assoc($serRes)) $servicesMenu[] = $r; }
    } catch (Throwable $e) {}

    try {
        $proRes = @mysqli_query($conn, "SELECT id, name, slug, description FROM `products` ORDER BY name ASC");
        if ($proRes) { while ($r = mysqli_fetch_assoc($proRes)) $productsMenu[] = $r; }
    } catch (Throwable $e) {}
}

// Company items (Managed as static or potentially dynamic)
$companyMenu = [
  ['title' => 'About Us', 'slug' => 'about-page', 'desc' => 'Our history, mission, and core values.'],
  ['title' => 'Our Projects', 'slug' => 'projects', 'desc' => 'Case studies of successful IT deployments.'],
  ['title' => 'Workforce', 'slug' => 'workforce', 'desc' => 'Meet our certified expert engineering team.'],
  ['title' => 'Legal & Compliance', 'slug' => 'legal', 'desc' => 'Governance, privacy, and certifications.'],
  ['title' => 'Careers', 'slug' => 'careers', 'desc' => 'Join the fastest growing IT team.'],
  ['title' => 'Certifications', 'slug' => 'certs', 'desc' => 'ISO and vendor-specific approvals.'],
  ['title' => 'ESG', 'slug' => 'esg', 'desc' => 'Environmental and Social Governance.'],
  ['title' => 'Partner with Us', 'slug' => 'partner', 'desc' => 'Strategic alliances and vendor relations.']
];

// ── SEO meta ───────────────────────────────────────────────
$metaTitle       = $metaTitle       ?? DEFAULT_META_TITLE;
$metaDescription = $metaDescription ?? DEFAULT_META_DESCRIPTION;
$metaKeywords    = $metaKeywords    ?? DEFAULT_META_KEYWORDS;
$metaOgImage     = $metaOgImage     ?? SITE_URL . 'assets/images/hero_datacenter.png';
$canonicalUrl    = $canonicalUrl    ?? (SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/'));

// Helper to render Mega Menu - Unified Grid
function renderMegaMenu($id, $label, $items, $slugKey = 'slug', $titleKey = 'title', $descKey = 'description', $prefix = 'solution', $siteUrl = '') {
  if (empty($items)) return;
  $chunks = array_chunk($items, max(1, ceil(count($items)/4)));
  ?>
  <li class="nav-dropdown h-full flex items-center">
    <button class="px-5 py-2 text-[13px] font-black text-slate-600 flex items-center gap-1 group">
      <?= h($label) ?>
      <svg class="w-4 h-4 text-slate-300 group-hover:text-[#5d1d1f] transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div class="mega-content">
      <div class="max-w-screen-xl mx-auto px-10">
        <div class="grid grid-cols-4 gap-8 p-12 bg-white rounded-[32px] shadow-2xl border border-slate-100">
          <?php foreach ($chunks as $chunk): ?>
            <div class="flex flex-col gap-6">
              <?php foreach ($chunk as $item): 
                $link = $siteUrl . 'public/' . $prefix . '.php?slug=' . $item[$slugKey];
                if ($item[$slugKey] === 'about-page') {
                  $link = $siteUrl . 'public/about.php';
                }
              ?>
                <a href="<?= $link ?>" class="group/m block">
                  <h4 class="text-[14px] font-black text-[#1a1a2e] group-hover/m:text-[#5d1d1f] transition-colors mb-1.5 leading-tight"><?= h($item[$titleKey]) ?></h4>
                  <?php if(!empty($item[$descKey])): ?>
                    <p class="text-[11px] text-slate-400 leading-relaxed line-clamp-2"><?= h($item[$descKey]) ?></p>
                  <?php endif; ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </li>
  <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- ── Primary SEO ──────────────────────────────────────── -->
  <title><?= h($metaTitle) ?></title>
  <meta name="description" content="<?= h($metaDescription) ?>"/>
  <meta name="keywords"    content="<?= h($metaKeywords) ?>"/>
  <meta name="author"      content="I Tech IT Solutions"/>
  <link rel="canonical"    href="<?= h($canonicalUrl) ?>"/>

  <!-- ── Open Graph ────────────────────────────────────────── -->
  <meta property="og:type"        content="website"/>
  <meta property="og:site_name"   content="<?= h(get_setting('site_name')) ?>"/>
  <meta property="og:title"       content="<?= h($metaTitle) ?>"/>
  <meta property="og:description" content="<?= h($metaDescription) ?>"/>
  <meta property="og:image"       content="<?= h($metaOgImage) ?>"/>

  <!-- ── Tailwind CSS ─────────────────────────────────────── -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- ── Google Fonts ─────────────────────────────────────── -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>

  <style>
    body { font-family: 'Outfit', sans-serif; }
    html { scroll-behavior: smooth; }

    /* ── Dropdown / Mega Menu Transitions ─────────────────── */
    .nav-dropdown { position: static; } /* Relative to header wrapper */
    .mega-content {
      opacity: 0;
      visibility: hidden;
      transform: translateY(15px);
      transition: all 0.3s cubic-bezier(.16,1,.3,1);
      pointer-events: none;
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      width: 100%;
      z-index: 100;
      padding-top: 10px;
    }
    .nav-dropdown:hover > .mega-content {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      pointer-events: auto;
    }

    /* ── Navbar shadow ────────────────────────────────────── */
    #site-header { transition: all 0.4s ease; }
    #site-header.scrolled {
       box-shadow: 0 15px 50px rgba(0,0,0,0.06);
       background: rgba(255,255,255,0.99);
       backdrop-filter: blur(10px);
    }

    /* ── Preloader ────────────────────────────────────────── */
    #it-preloader {
      position: fixed; inset: 0; z-index: 10000;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      background: #0f172a; transition: opacity 0.7s ease, visibility 0.7s ease;
    }
    #it-preloader.it-done { opacity: 0; visibility: hidden; pointer-events: none; }
    
    @keyframes pulse-custom {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.05); opacity: 0.8; }
    }
    .animate-pulse-custom { animation: pulse-custom 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    .animate-ping-custom { animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite; }

    body.it-loading { overflow: hidden; }

    .group\/m:hover h4 { color: #5d1d1f; transform: translateX(6px); }
    .group\/m h4 { transition: 0.3s ease; }
  </style>
</head>
<body class="bg-white text-slate-800 antialiased it-loading">

<!-- ══════════════════════════════════════════════════════════
     PRELOADER
══════════════════════════════════════════════════════════ -->
<div id="it-preloader">
  <div class="relative flex flex-col items-center">
    <div class="absolute inset-0 w-24 h-24 bg-[#efac3a]/20 rounded-full animate-ping-custom"></div>
    <img src="<?= SITE_URL . get_setting('site_logo') ?>" alt="Loading..." class="h-20 w-auto relative z-10 animate-pulse-custom">
  </div>
</div>

<script>
window.addEventListener('load', () => { 
  setTimeout(() => {
    const pre = document.getElementById('it-preloader');
    if(pre) pre.classList.add('it-done'); 
    document.body.classList.remove('it-loading'); 
  }, 500); 
});
</script>

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-[#5d1d1f] text-white px-4 py-2 rounded-lg z-[200]">Skip to content</a>

<!-- ══════════════════════════════════════════════════════════
     TOP BAR
══════════════════════════════════════════════════════════ -->
<div class="bg-slate-50 border-b border-slate-100 hidden lg:block sticky top-0 z-[110]">
  <div class="max-w-screen-xl mx-auto px-10 h-10 flex items-center justify-between text-[11px] font-bold uppercase tracking-wider">
    <div class="flex items-center gap-6">
      <div class="flex items-center gap-3 text-slate-400">
        <button class="text-[#5d1d1f]">EN</button>
        <span class="w-px h-2 bg-slate-300"></span>
        <button class="hover:text-[#efac3a] transition-colors">عربي</button>
      </div>
      <div class="flex items-center gap-4 text-slate-400 border-l border-slate-200 pl-6">
        <a href="<?= h(get_setting('social_linkedin')) ?>" target="_blank" class="hover:text-[#0077b5] transition-colors">LinkedIn</a>
        <a href="<?= h(get_setting('social_twitter')) ?>" target="_blank" class="hover:text-black transition-colors">X</a>
      </div>
    </div>
    <div class="flex items-center gap-6 text-slate-500 font-bold">
      <a href="<?= SITE_URL ?>public/index.php#contact-section" class="hover:text-[#efac3a] transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.172l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg> Technical Support
      </a>
      <a href="<?= SITE_URL ?>public/index.php#contact-section" class="hover:text-[#efac3a] transition-colors border-l border-slate-200 pl-6">Contact Sales</a>
      <a href="<?= h(get_setting('brochure_url')) ?>" target="_blank" class="px-3 py-1 bg-[#5d1d1f] text-white rounded hover:bg-[#efac3a] hover:text-[#5d1d1f] transition-all">Brochure</a>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MAIN HEADER
══════════════════════════════════════════════════════════ -->
<header id="site-header" class="relative sticky top-0 lg:top-10 z-[100] bg-white border-b border-slate-100">
  <div class="max-w-screen-xl mx-auto px-10 h-20 flex items-center justify-between">
    
    <!-- Logo -->
    <a href="<?= SITE_URL ?>public/index.php" class="flex items-center gap-3 shrink-0 group">
      <img src="<?= SITE_URL . get_setting('site_logo') ?>" alt="<?= h(get_setting('site_name')) ?>" class="h-14 w-auto object-contain">
    </a>

    <!-- Desktop Navigation -->
    <nav class="hidden lg:flex items-center h-full mx-auto">
      <ul class="flex items-center h-full gap-2">
        <li><a href="<?= SITE_URL ?>public/index.php" class="px-5 py-2 text-[13px] font-black text-slate-600 hover:text-[#5d1d1f] transition-colors">Home</a></li>
        <?php
        renderMegaMenu('nav-sol', 'Solutions', $solutionsMenu, 'slug', 'title', 'description', 'solution', SITE_URL);
        renderMegaMenu('nav-ser', 'Services',  $servicesMenu,  'slug', 'title', 'description', 'service',  SITE_URL);
        renderMegaMenu('nav-pro', 'Products',  $productsMenu,  'slug', 'name',  'description', 'product',  SITE_URL);
        renderMegaMenu('nav-com', 'Company',   $companyMenu,   'slug', 'title', 'desc',        'company',  SITE_URL);
        ?>
      </ul>
    </nav>

    <!-- Actions -->
    <div class="flex items-center gap-5">
      <a href="<?= SITE_URL ?>public/index.php#contact-section" class="px-8 py-3.5 bg-[#5d1d1f] text-white text-[13px] font-black rounded-full shadow-xl shadow-[#5d1d1f]/25 hover:bg-[#4a1517] hover:scale-105 transition-all border border-[#efac3a]/20">
        Get a Quote
      </a>
      <button id="hamburger-btn" class="lg:hidden w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600">
        <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
  </div>

  <!-- Mobile Drawer -->
  <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 overflow-y-auto max-h-[85vh] px-8 py-10">
    <div class="space-y-10">
      <a href="<?= SITE_URL ?>public/index.php" class="block text-2xl font-black">Home</a>
      <?php foreach (['Solutions' => $solutionsMenu, 'Services' => $servicesMenu] as $label => $items): ?>
      <div class="space-y-4">
        <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= h($label) ?></h5>
        <div class="grid gap-3 pl-4 border-l-2 border-slate-100">
          <?php foreach (array_slice($items, 0, 10) as $idx => $i): ?>
            <a href="<?= SITE_URL ?>public/<?= strtolower(substr($label,0,-1)) ?>.php?slug=<?= $i['slug'] ?>" class="text-sm font-bold text-slate-600"><?= h($i['title'] ?? $i['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <div class="pt-8 border-t border-slate-100">
        <a href="tel:<?= h(get_setting('company_phone')) ?>" class="flex items-center gap-5 p-5 bg-slate-50 rounded-2xl">
          <div class="w-12 h-12 rounded-xl bg-[#5d1d1f] text-white flex items-center justify-center font-black">IT</div>
          <div><p class="text-xs font-black text-slate-400 uppercase tracking-widest">Support Line</p><p class="text-lg font-black text-[#1a1a2e]"><?= h(get_setting('company_phone')) ?></p></div>
        </a>
      </div>
    </div>
  </div>
</header>

<main id="main-content">
