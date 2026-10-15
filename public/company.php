<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Company Page
//  URL: /public/company.php
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/config.php';

// ── SEO ───────────────────────────────────────────────────────
$metaTitle       = 'About I Tech IT Solutions | Enterprise Infrastructure Partner';
$metaDescription = 'Learn about our mission, our certified engineering workforce, and our successful projects across the Middle East and India.';
$canonicalUrl    = SITE_URL . 'public/company.php';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ── Breadcrumb ────────────────────────────────────────────── -->
<nav class="bg-slate-50 border-b border-slate-100 py-3" aria-label="Breadcrumb">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex items-center gap-2 text-sm text-slate-500">
    <a href="<?= SITE_URL ?>public/index.php" class="hover:text-[#5d1d1f] transition-colors">Home</a>
    <span class="text-slate-300">›</span>
    <span class="text-[#5d1d1f] font-semibold">Company</span>
  </div>
</nav>

<!-- ── Hero ──────────────────────────────────────────────────── -->
<section class="bg-[#0f172a] py-20 lg:py-28 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#5d1d1f_1px,transparent_1px)] [background-size:20px_20px]"></div>
  <div class="relative max-w-screen-xl mx-auto px-6 lg:px-10 text-center">
    <div data-anim="slide-up">
      <span class="text-[#efac3a] font-black text-xs uppercase tracking-[0.4em] mb-4 block">Our Identity</span>
      <h1 class="text-4xl lg:text-6xl font-black text-white mb-6 leading-tight">Driving Global <br/> <span class="text-[#5d1d1f] bg-clip-text text-transparent bg-gradient-to-r from-[#5d1d1f] to-[#efac3a] drop-shadow-sm">IT Infrastructure</span></h1>
      <p class="text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed">Built on a foundation of precision engineering and unyielding customer commitment.</p>
    </div>
  </div>
</section>

<!-- ── Navigation Anchors ────────────────────────────────────── -->
<div class="sticky top-20 lg:top-[120px] bg-white border-b border-slate-100 z-50 overflow-x-auto no-scrollbar">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex gap-8 whitespace-nowrap">
    <a href="#about"     class="py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#5d1d1f] border-b-2 border-transparent hover:border-[#5d1d1f] transition-all">About Us</a>
    <a href="#projects"  class="py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#5d1d1f] border-b-2 border-transparent hover:border-[#5d1d1f] transition-all">Projects</a>
    <a href="#workforce" class="py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#5d1d1f] border-b-2 border-transparent hover:border-[#5d1d1f] transition-all">Workforce</a>
    <a href="#legal"     class="py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#5d1d1f] border-b-2 border-transparent hover:border-[#5d1d1f] transition-all">Legal</a>
    <a href="#esg"       class="py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#5d1d1f] border-b-2 border-transparent hover:border-[#5d1d1f] transition-all">ESG</a>
  </div>
</div>

<div class="space-y-24 py-20 max-w-screen-xl mx-auto px-6 lg:px-10">

  <!-- 1. About Us -->
  <section id="about" class="grid lg:grid-cols-2 gap-16 items-center" data-anim>
    <div>
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-6">Experience that <br/>Spans Continents</h2>
      <p class="text-slate-600 leading-relaxed mb-6">Founded with the goal of providing tier-1 IT infrastructure solutions, I Tech has grown into a trusted partner for enterprises across Hospitality, Healthcare, and Oil & Gas sectors.</p>
      <div class="grid grid-cols-2 gap-6 pt-6">
        <div>
          <h4 class="text-3xl font-black text-[#5d1d1f] mb-1">10+</h4>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Years Experience</p>
        </div>
        <div>
          <h4 class="text-3xl font-black text-[#5d1d1f] mb-1">500+</h4>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Success Projects</p>
        </div>
      </div>
    </div>
    <div class="relative">
      <div class="aspect-square bg-slate-100 rounded-3xl overflow-hidden shadow-2xl">
         <img src="<?= SITE_URL ?>uploads/serv_cloud.png" class="w-full h-full object-cover" alt="Team"/>
      </div>
      <div class="absolute -bottom-6 -left-6 bg-[#5d1d1f] p-8 rounded-3xl text-white shadow-xl border border-[#efac3a]/20">
        <p class="text-2xl font-black tracking-tight tracking-tighter italic text-[#efac3a]">"Your vision, our architecture."</p>
      </div>
    </div>
  </section>

  <!-- 2. Projects -->
  <section id="projects" data-anim>
     <div class="flex flex-col lg:flex-row justify-between items-end gap-6 mb-12">
        <div class="max-w-xl">
          <span class="text-[#5d1d1f] font-black text-xs uppercase tracking-widest">Global Portfolio</span>
          <h2 class="text-4xl font-black text-[#1a1a2e] mt-2">Successful Deployments</h2>
        </div>
        <a href="<?= SITE_URL ?>public/index.php#contact-section" class="px-8 py-3 bg-[#5d1d1f] text-white text-xs font-black rounded-xl hover:bg-[#4a1517] shadow-lg border border-[#efac3a]/20 text-[#efac3a] transition-all">Partner with Us</a>
     </div>
     <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach(['Luxury Resorts', 'Critical Care Hospitals', 'Financial Hubs'] as $p): ?>
        <div class="group p-8 bg-slate-50 border border-slate-100 rounded-3xl hover:bg-white hover:shadow-2xl hover:border-[#5d1d1f]/20 transition-all duration-500">
           <div class="w-12 h-12 rounded-2xl bg-[#5d1d1f]/10 flex items-center justify-center text-[#5d1d1f] mb-6 group-hover:bg-[#5d1d1f] group-hover:text-white transition-all">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
           </div>
           <h3 class="text-lg font-black text-[#1a1a2e] mb-3"><?= $p ?></h3>
           <p class="text-slate-500 text-sm leading-relaxed">Full-stack infrastructure and security deployment across multi-floor environments.</p>
        </div>
        <?php endforeach; ?>
     </div>
  </section>

  <!-- 3. Workforce -->
  <section id="workforce" data-anim class="bg-slate-50 rounded-[48px] p-12 lg:p-20 text-center">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-6">Our Certified Workforce</h2>
      <p class="text-slate-600 mb-10 leading-relaxed text-lg">Our engineering team holds certifications from Cisco, Fortinet, Hikvision, and more, ensuring every connection is built to manufacturer standards.</p>
      <div class="flex flex-wrap justify-center gap-4">
        <span class="px-6 py-3 bg-white shadow-sm border border-slate-200 rounded-2xl text-sm font-black text-[#1a1a2e]">CCIE Certified</span>
        <span class="px-6 py-3 bg-white shadow-sm border border-slate-200 rounded-2xl text-sm font-black text-[#1a1a2e]">Fortinet NSE</span>
        <span class="px-6 py-3 bg-white shadow-sm border border-slate-200 rounded-2xl text-sm font-black text-[#1a1a2e]">BICSI Installers</span>
        <span class="px-6 py-3 bg-white shadow-sm border border-slate-200 rounded-2xl text-sm font-black text-[#1a1a2e]">HCSA Experts</span>
      </div>
    </div>
  </section>

  <!-- 4. Legal & Compliance -->
  <section id="legal" class="grid lg:grid-cols-2 gap-16" data-anim>
     <div class="space-y-8">
        <h2 class="text-4xl font-black text-[#1a1a2e]">Governance & Compliance</h2>
        <p class="text-slate-600">I Tech operates under strict international guidelines to protect our clients' data and ensure operational transparency.</p>
        <div class="space-y-4">
           <details class="group bg-slate-50 rounded-2xl border border-slate-100 transition-all">
              <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                 <span class="font-black text-sm text-[#1a1a2e]">Data Privacy Policy</span>
                 <svg class="w-5 h-5 text-slate-400 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="px-6 pb-6 text-xs text-slate-500 leading-relaxed">Our infrastructure solutions are designed to be GDPR and local data protection compliant.</div>
           </details>
           <details class="group bg-slate-50 rounded-2xl border border-slate-100 transition-all">
              <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                 <span class="font-black text-sm text-[#1a1a2e]">Operational Ethics</span>
                 <svg class="w-5 h-5 text-slate-400 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="px-6 pb-6 text-xs text-slate-500 leading-relaxed">Transparency in vendor relations and procurement is a cornerstone of our partnership.</div>
           </details>
        </div>
     </div>
     <div class="bg-slate-900 rounded-3xl p-10 flex flex-col justify-center gap-6 shadow-2xl">
        <h4 class="text-white font-black text-xl">Need official documentation?</h4>
        <p class="text-slate-400 text-sm">Contact our legal team for compliance certificates or vendor authorization letters.</p>
        <a href="<?= SITE_URL ?>public/index.php#contact-section" class="px-8 py-3.5 bg-white text-slate-900 font-black rounded-xl text-center hover:bg-slate-200 transition-all">Request Legal Pack</a>
     </div>
  </section>

</div>

<?php 
require_once __DIR__ . '/../includes/footer.php';
?>
