<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — About Us Page
//  URL: /public/about.php
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/config.php';

// ── SEO ───────────────────────────────────────────────────────
$metaTitle       = 'About Us | I Tech IT Solutions – Enterprise IT Infrastructure';
$metaDescription = 'I Tech IT Solutions is a trusted Information Technology solution provider in Saudi Arabia, specializing in end-to-end infrastructure, security, and communication systems.';
$canonicalUrl    = SITE_URL . 'public/about.php';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ── Breadcrumb ────────────────────────────────────────────── -->
<nav class="bg-slate-50 border-b border-slate-100 py-3" aria-label="Breadcrumb">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10 flex items-center gap-2 text-sm text-slate-500">
    <a href="<?= SITE_URL ?>public/index.php" class="hover:text-[#5d1d1f] transition-colors">Home</a>
    <span class="text-slate-300">›</span>
    <span class="text-[#5d1d1f] font-semibold">About Us</span>
  </div>
</nav>

<!-- ── Hero ──────────────────────────────────────────────────── -->
<section class="bg-[#0f172a] py-20 lg:py-28 relative overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img src="<?= SITE_URL ?>assets/images/hero-datacenter.png" alt="About US" class="w-full h-full object-cover opacity-20 filter grayscale"/>
    <div class="absolute inset-0 bg-gradient-to-b from-[#0f172a]/90 via-[#0f172a]/60 to-[#0f172a]"></div>
  </div>
  <div class="relative z-10 max-w-screen-xl mx-auto px-6 lg:px-10 text-center" data-anim="slide-up">
    <span class="text-[#efac3a] font-black text-xs uppercase tracking-[0.4em] mb-4 block">Who We Are</span>
    <h1 class="text-4xl lg:text-7xl font-black text-white mb-6 leading-tight">Driving Enterprise <br/> <span class="text-[#5d1d1f] bg-clip-text text-transparent bg-gradient-to-r from-[#5d1d1f] to-[#efac3a] drop-shadow-sm">Tech Excellence</span></h1>
    <p class="text-slate-400 text-lg max-w-3xl mx-auto leading-relaxed">A trusted technology partner delivering reliable, scalable, and cost-effective solutions across the Kingdom of Saudi Arabia.</p>
  </div>
</section>

<div class="bg-white space-y-20 py-24 max-w-screen-xl mx-auto px-6 lg:px-10">

  <!-- 1. Who We Are Section -->
  <section id="who-we-are" class="grid lg:grid-cols-2 gap-16 items-center">
    <div data-anim="slide-left">
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-8">Trusted Infrastructure <br/><span class="text-[#5d1d1f]">In Saudi Arabia</span></h2>
      <div class="space-y-6 text-slate-600 leading-relaxed text-lg">
        <p>I Tech IT Solutions is a trusted Information Technology solution provider specializing in delivering end-to-end infrastructure, security, and communication systems for enterprises across diverse industries.</p>
        <p>With a strong presence in the Kingdom of Saudi Arabia, we bring together technical expertise, certified professionals, and global technology partnerships to design and implement reliable, scalable, and cost-effective IT solutions.</p>
        <p>Our approach is rooted in understanding real-world operational challenges and delivering practical, future-ready technology systems that enhance efficiency, security, and connectivity.</p>
      </div>
    </div>
    <div class="relative" data-anim="slide-right">
       <div class="aspect-video bg-slate-100 rounded-[40px] overflow-hidden shadow-2xl border-8 border-slate-50">
          <img src="<?= SITE_URL ?>assets/images/team-engineers.png" class="w-full h-full object-cover" alt="I Tech Engineering Team"/>
       </div>
       <div class="absolute -bottom-6 -right-6 bg-[#5d1d1f] p-8 rounded-3xl text-white shadow-xl border border-[#efac3a]/20">
         <p class="text-xl font-black italic text-[#efac3a]">Certified Engineers & Reliable Partnerships</p>
       </div>
    </div>
  </section>

  <!-- 2. What We Do Section -->
  <section id="what-we-do" class="py-12" data-anim="slide-up">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-4">What We Do</h2>
      <p class="text-slate-500">Comprehensive solutions covering the entire technology spectrum</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php 
      $capabilities = [
        "Datacenter Infrastructure & Structured Cabling",
        "Enterprise Networking & Security Systems",
        "IP Video Surveillance & Access Control",
        "IP Telephony & Unified Communication",
        "Wireless & Connectivity Solutions",
        "Cloud Services & Internet Solutions"
      ];
      foreach($capabilities as $cap): ?>
      <div class="p-8 bg-slate-50 border border-slate-100 rounded-3xl hover:bg-white hover:shadow-xl hover:border-[#5d1d1f]/20 transition-all group">
         <div class="w-12 h-12 rounded-2xl bg-[#5d1d1f]/10 text-[#5d1d1f] flex items-center justify-center mb-6 group-hover:bg-[#5d1d1f] group-hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
         </div>
         <h4 class="text-lg font-black text-[#1a1a2e] mb-3"><?= h($cap) ?></h4>
         <p class="text-slate-500 text-sm leading-relaxed">From design and implementation to integration and support, we act as a complete technology partner.</p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 3. Industry Expertise Section -->
  <section id="industry-expertise" class="bg-slate-900 rounded-[60px] p-12 lg:p-20 text-white overflow-hidden relative" data-anim="scale">
    <div class="absolute top-0 right-0 p-10 opacity-10">
      <img src="<?= SITE_URL ?>assets/images/tech-network.png" class="w-64 h-64 object-contain" alt="Pattern"/>
    </div>
    <div class="relative z-10">
      <div class="max-w-3xl mb-16">
        <h2 class="text-4xl font-black mb-6">Our Industry Expertise</h2>
        <p class="text-slate-400 text-lg">Each solution is carefully engineered to meet the specific operational needs and challenges of the industry.</p>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-12">
        <?php 
        $industries = [
          "Hotels & Resorts", "Healthcare & Hospitals", "Retail & Commerce", "Manufacturing Plants",
          "Warehousing & Logistics", "Oil, Gas & Industrial", "Construction Sites", "Parking Management"
        ];
        foreach($industries as $ind): ?>
        <div>
          <h5 class="text-[#efac3a] font-black text-sm uppercase tracking-widest mb-2 border-b border-[#efac3a]/30 pb-2"><?= h($ind) ?></h5>
          <p class="text-slate-500 text-xs">Custom engineered solutions</p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 4. Strength Section -->
  <section id="strength-and-values" class="grid lg:grid-cols-2 gap-20">
    <div data-anim="slide-left">
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-8">Our Strength</h2>
      <ul class="space-y-4">
        <?php 
        $strengths = [
          "Certified and experienced engineering team",
          "Strong partnerships with global technology brands",
          "Proven experience in large-scale enterprise deployments",
          "Focus on quality, reliability, and long-term performance",
          "Cost-effective solutions without compromising standards"
        ];
        foreach($strengths as $val): ?>
        <li class="flex items-center gap-4 text-slate-700 font-bold">
          <div class="w-2 h-2 rounded-full bg-[#efac3a]"></div>
          <?= h($val) ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="space-y-12" data-anim="slide-right">
       <div class="p-10 bg-[#5d1d1f]/5 rounded-[40px] border border-[#5d1d1f]/10">
          <h3 class="text-2xl font-black text-[#5d1d1f] mb-4 uppercase tracking-widest">Our Vision</h3>
          <p class="text-slate-600 leading-relaxed italic">"To be a leading IT solutions provider recognized for delivering innovative, reliable, and scalable technology systems that empower businesses to grow and operate efficiently."</p>
       </div>
       <div class="p-10 bg-[#efac3a]/5 rounded-[40px] border border-[#efac3a]/10">
          <h3 class="text-2xl font-black text-[#efac3a] mb-4 uppercase tracking-widest">Our Mission</h3>
          <p class="text-slate-600 leading-relaxed italic">"To provide high-quality IT infrastructure and security solutions by combining technical excellence, industry knowledge, and customer-focused service, ensuring long-term value for our clients."</p>
       </div>
    </div>
  </section>

  <!-- 5. Final CTA -->
  <section id="why-partner" class="text-center py-20 bg-slate-50 rounded-[60px]" data-anim="slide-up">
    <div class="max-w-3xl mx-auto px-6">
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-8">Why Choose I Tech IT Solutions</h2>
      <p class="text-slate-600 text-lg leading-relaxed mb-12">At I Tech IT Solutions, we don’t just implement technology—we build integrated systems that support business success. Our commitment to quality, attention to detail, and deep understanding of enterprise environments make us a reliable partner for organizations seeking long-term, scalable IT solutions.</p>
      <a href="<?= SITE_URL ?>public/index.php#contact-section" class="inline-block px-12 py-5 bg-[#5d1d1f] text-white font-black rounded-full shadow-2xl hover:bg-[#4a1517] transition-all transform hover:-translate-y-1">Partner With Us &rarr;</a>
    </div>
  </section>

</div>

<?php 
require_once __DIR__ . '/../includes/footer.php';
?>
