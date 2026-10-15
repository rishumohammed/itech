<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Public Homepage (PHP-driven)
//  File: public/index.php
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/config.php';

// ── SEO meta for this page ───────────────────────────────────
$metaTitle       = 'I Tech IT Solutions | Enterprise IT Infrastructure & Security Solutions';
$metaDescription = 'Reliable, scalable, and cost-effective IT infrastructure, networking, cybersecurity, and cloud solutions. Based in Jeddah, Kingdom of Saudi Arabia.';
$metaKeywords    = 'enterprise IT solutions, datacenter Jeddah, networking company Saudi Arabia, cybersecurity IT, IP surveillance, IT infrastructure company, Cisco Fortinet Huawei partner Jeddah';
$canonicalUrl    = SITE_URL . 'public/index.php';

// ── Fetch from DB safely ─────────────────────────────────────
$solutions = $services = $products = [];
if ($conn) {
    try {
        $res = @mysqli_query($conn, "SELECT * FROM `solutions` ORDER BY id ASC");
        if ($res) { while ($r = mysqli_fetch_assoc($res)) $solutions[] = $r; }
    } catch (Throwable $e) {}

    try {
        $res = @mysqli_query($conn, "SELECT * FROM `services` ORDER BY id ASC");
        if ($res) { while ($r = mysqli_fetch_assoc($res)) $services[] = $r; }
    } catch (Throwable $e) {}

    try {
        $res = @mysqli_query($conn, "SELECT * FROM `products` ORDER BY id ASC");
        if ($res) { while ($r = mysqli_fetch_assoc($res)) $products[] = $r; }
    } catch (Throwable $e) {}
}

// ── Recover form data on validation error ────────────────────
$savedForm = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

require_once __DIR__ . '/../includes/header.php';
?>

<!-- HERO -->
<section id="hero" class="relative bg-[#0f172a] overflow-hidden py-28 lg:py-40">
  <!-- Background Video with Overlay -->
  <div class="absolute inset-0 z-0 overflow-hidden">
    <video 
      autoplay 
      loop 
      muted 
      playsinline 
      poster="<?= SITE_URL . h(get_setting('hero_poster')) ?>"
      class="w-full h-full object-cover opacity-50 transition-opacity duration-1000"
    >
      <source src="<?= SITE_URL . h(get_setting('hero_video')) ?>" type="video/mp4">
      <!-- Fallback image if video fails -->
      <img src="<?= SITE_URL . h(get_setting('hero_poster')) ?>" alt="Enterprise Datacenter" class="w-full h-full object-cover">
    </video>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] via-[#0f172a]/80 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent"></div>
  </div>
  
  <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[#5d1d1f]/15 rounded-full blur-[120px] pointer-events-none z-10"></div>
  
  <div class="relative z-20 max-w-screen-xl mx-auto px-6 lg:px-10 flex flex-col lg:flex-row items-center gap-16">
    <!-- Text -->
    <div class="flex-1 text-center lg:text-left" data-anim="slide-left">
      <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#5d1d1f]/40 backdrop-blur-md text-[#fca5a5] text-xs font-black rounded-full uppercase tracking-[0.2em] mb-8 border border-white/10">
        <span class="w-2 h-2 rounded-full bg-[#efac3a] animate-pulse"></span>
        Global Infrastructure Partner
      </span>
      <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.1] mb-8 tracking-tighter">
        Precision IT <br/> <span class="text-[#5d1d1f] bg-clip-text text-transparent bg-gradient-to-r from-[#5d1d1f] to-[#efac3a] drop-shadow-sm">Infrastructure</span>
      </h1>
      <p class="text-slate-300 text-xl leading-relaxed mb-10 max-w-xl">
        Delivering mission-critical networking, security, and cloud solutions for world-class enterprises.
      </p>
      <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start mb-14">
        <a href="#contact-section" class="btn-press px-10 py-4 bg-[#5d1d1f] text-white font-black rounded-full shadow-2xl shadow-[#5d1d1f]/30 hover:bg-[#4a1517] hover:border-[#efac3a]/30 border border-transparent hover:-translate-y-1 transition-all duration-300 text-sm tracking-wide">Start Your Project</a>
        <a href="#services-overview" class="btn-press px-10 py-4 bg-white/5 text-white font-black rounded-full border border-white/20 hover:bg-white/10 hover:-translate-y-1 transition-all duration-300 text-sm backdrop-blur-md">View Services</a>
      </div>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach([['500+','Deployments'],['15+','Years Experience'],['99.9%','Uptime Guaranteed'],['24/7','Expert Support']] as [$n,$l]): ?>
        <div class="p-1"><p class="text-3xl font-black text-white mb-1"><?= $n ?></p><p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest"><?= $l ?></p></div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Status Floating Card -->
    <div class="hidden xl:block w-80 shrink-0" data-anim="slide-right">
       <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-[40px] shadow-2xl">
          <div class="flex items-center gap-3 mb-6">
             <div class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></div>
             <span class="text-white font-black text-xs uppercase tracking-widest">NOC Live Status</span>
          </div>
          <div class="space-y-6">
             <?php foreach(['Network' => 100, 'Security' => 100, 'Cloud' => 99.9] as $k => $v): ?>
             <div>
                <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase mb-2"><span><?= $k ?></span> <span><?= $v ?>%</span></div>
                <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden"><div class="h-full bg-[#5d1d1f]" style="width:<?= $v ?>%"></div></div>
             </div>
             <?php endforeach; ?>
          </div>
       </div>
    </div>
  </div>
</section>

<!-- SERVICES (dynamic) -->
<section id="services-overview" class="py-24 bg-slate-50">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="text-center max-w-2xl mx-auto mb-14" data-anim>
      <span class="inline-block px-4 py-1.5 bg-[#5d1d1f]/10 text-[#5d1d1f] text-xs font-bold rounded-full uppercase tracking-widest mb-4">What We Do</span>
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-4 anim-underline">Our Core Services</h2>
      <p class="text-slate-500 text-base">Comprehensive IT infrastructure and security solutions</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
      <?php foreach ($services as $i => $svc): ?>
      <a href="service.php?slug=<?= urlencode($svc['slug'] ?? '') ?>"
         data-anim data-delay="<?= ($i % 8) + 1 ?>"
         class="group bg-white rounded-2xl p-7 border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-[#5d1d1f]/20 transition-all duration-300">
        <div class="w-12 h-12 rounded-xl bg-[#5d1d1f]/10 flex items-center justify-center group-hover:bg-[#5d1d1f] transition-colors duration-300 mb-4">
          <svg class="w-6 h-6 text-[#5d1d1f] group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </div>
        <h3 class="text-base font-bold text-[#1a1a2e] mb-1.5 group-hover:text-[#5d1d1f] transition-colors"><?= htmlspecialchars($svc['title']) ?></h3>
        <p class="text-slate-500 text-sm leading-relaxed"><?= htmlspecialchars($svc['description']) ?></p>
        <span class="mt-3 inline-flex items-center gap-1 text-[#efac3a] text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">
          View Details
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- NOC MONITORING SECTION -->
<section id="noc-monitoring" class="relative bg-[#0f172a] overflow-hidden py-32">
  <img src="<?= SITE_URL ?>uploads/noc_center.png" alt="NOC Center" class="absolute inset-0 w-full h-full object-cover opacity-20 filter grayscale"/>
  <div class="absolute inset-0 bg-gradient-to-b from-[#0f172a] via-[#0f172a]/60 to-[#0f172a]"></div>
  <div class="relative z-10 max-w-screen-xl mx-auto px-6 lg:px-10 text-center">
    <div data-anim="scale" class="max-w-3xl mx-auto">
      <span class="inline-flex items-center gap-2 px-6 py-2 bg-[#5d1d1f] text-white text-[10px] font-black rounded-full uppercase tracking-[0.3em] mb-8 shadow-2xl">
        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
        Active Monitoring
      </span>
      <h2 class="text-3xl lg:text-5xl font-black text-white mb-8 leading-tight">24/7 Global <span class="text-[#efac3a]">Operations Center</span></h2>
      <p class="text-slate-400 text-lg mb-12">Our state-of-the-art NOC proactively monitors your critical systems, ensuring proactive threat detection and 99.9% operational uptime.</p>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
        <?php foreach(['Proactive Alerts','Security Patching','Response < 15m'] as $feat): ?>
        <div class="p-6 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
           <p class="text-white font-black text-sm uppercase tracking-widest"><?= $feat ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- INDUSTRY SOLUTIONS (dynamic) -->
<section id="industry-solutions" class="py-24 bg-white">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="text-center max-w-2xl mx-auto mb-14" data-anim>
      <span class="inline-block px-4 py-1.5 bg-[#efac3a]/20 text-[#5d1d1f] text-xs font-bold rounded-full uppercase tracking-widest mb-4">Industries We Serve</span>
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-4 anim-underline">Industry Solutions</h2>
      <p class="text-slate-500 text-base">Tailored technology solutions for diverse industries</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($solutions as $i => $s): ?>
      <a href="solution.php?slug=<?= urlencode($s['slug'] ?? '') ?>"
         data-anim data-delay="<?= ($i % 8) + 1 ?>"
         class="group relative h-80 rounded-[40px] overflow-hidden border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
        <!-- Background Image -->
        <?php if(!empty($s['image'])): ?>
          <img src="<?= SITE_URL ?>uploads/<?= h($s['image']) ?>" alt="<?= h($s['title']) ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-30 group-hover:opacity-70 filter grayscale group-hover:grayscale-0"/>
        <?php endif; ?>
        
        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a2e] via-[#1a1a2e]/60 to-transparent z-10"></div>
        
        <div class="relative z-20 p-10 h-full flex flex-col justify-end">
          <div class="w-12 h-12 rounded-2xl bg-[#5d1d1f]/80 backdrop-blur-md flex items-center justify-center mb-6 group-hover:bg-[#5d1d1f] transition-colors border border-white/10">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
          </div>
          <h3 class="text-xl font-black text-white mb-2"><?= htmlspecialchars($s['title']) ?></h3>
          <p class="text-slate-300 text-xs leading-relaxed line-clamp-2 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500"><?= htmlspecialchars($s['description']) ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ABOUT US SUMMARY (Short version) -->
<section id="about-summary" class="py-24 bg-slate-50 overflow-hidden">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="flex flex-col lg:flex-row items-center gap-16">
      <!-- Content -->
      <div class="flex-1" data-anim="slide-left">
        <span class="inline-block px-4 py-1.5 bg-[#5d1d1f]/10 text-[#5d1d1f] text-xs font-bold rounded-full uppercase tracking-widest mb-5">About I Tech IT Solutions</span>
        <h2 class="text-4xl lg:text-5xl font-black text-[#1a1a2e] mb-8 leading-tight">Your Complete <br/> <span class="text-[#5d1d1f]">Technology Partner</span></h2>
        <div class="space-y-6 text-slate-600 leading-relaxed text-lg">
          <p>I Tech IT Solutions is a trusted Information Technology solution provider delivering end-to-end infrastructure, security, and communication systems for enterprises across multiple industries.</p>
          <p>With a strong presence in Saudi Arabia, we combine technical expertise, certified engineers, and global technology partnerships to provide reliable, scalable, and cost-effective solutions.</p>
          <p>From datacenter infrastructure and networking to surveillance, telephony, and cloud services, we act as a complete technology partner, helping businesses enhance connectivity, security, and operational efficiency.</p>
        </div>
        <div class="mt-10">
          <a href="<?= SITE_URL ?>public/about.php" class="inline-flex items-center gap-2 text-[#5d1d1f] font-black hover:text-[#efac3a] transition-colors group">
            Learn More About Our Story
            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M13 7l5 5-5 5M6 7l5 5-5 5"/></svg>
          </a>
        </div>
      </div>
      <!-- Image/Visual -->
      <div class="flex-1 relative" data-anim="slide-right">
        <div class="relative z-10 rounded-[60px] overflow-hidden shadow-2xl border-8 border-white">
          <img src="<?= SITE_URL ?>assets/images/tech-network.png" alt="I Tech IT Solutions Saudi Arabia" class="w-full h-[500px] object-cover"/>
        </div>
        <div class="absolute -top-10 -right-10 w-64 h-64 bg-[#efac3a] rounded-full blur-[100px] opacity-10"></div>
        <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-[#5d1d1f] rounded-full blur-[100px] opacity-10"></div>
      </div>
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section id="why-choose-us" class="py-24 bg-white overflow-hidden">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="flex flex-col lg:flex-row items-center gap-20">
      <!-- Image side -->
      <div class="flex-1 relative" data-anim="slide-left">
        <div class="relative z-10 rounded-[60px] overflow-hidden shadow-2xl border-8 border-slate-50 rotate-3">
          <img src="<?= SITE_URL ?>uploads/itech_expert.png" alt="I Tech Expert" class="w-full h-[500px] object-cover"/>
        </div>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#5d1d1f] rounded-full blur-[80px] opacity-20"></div>
        <div class="absolute -bottom-12 -right-12 p-8 bg-white rounded-3xl shadow-2xl border border-slate-100 z-20">
           <p class="text-3xl font-black text-[#5d1d1f] mb-1">150+</p>
           <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Certified Engineers</p>
        </div>
      </div>
      <!-- Content side -->
      <div class="flex-1" data-anim="slide-right">
        <span class="inline-block px-4 py-1.5 bg-[#efac3a]/20 text-[#5d1d1f] text-xs font-bold rounded-full uppercase tracking-widest mb-4">Our Advantage</span>
        <h2 class="text-4xl lg:text-5xl font-black text-[#1a1a2e] mb-8 leading-tight">Human Intelligence <br/>Technical <span class="text-[#5d1d1f]">Precision</span></h2>
        <div class="space-y-8">
          <?php foreach([
            ['Certified Expertise', 'Our engineers hold top-tier certifications from Cisco, Fortinet, and Microsoft.'],
            ['Vendor Neutral', 'We recommend the best technology fit for your specific business needs.'],
            ['Agile Delivery', 'Rapid deployment cycles and transparent project management.']
          ] as [$t, $d]): ?>
          <div class="flex gap-5">
            <div class="w-12 h-12 shrink-0 rounded-2xl bg-slate-50 flex items-center justify-center text-[#efac3a]">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
               <h4 class="font-black text-[#1a1a2e] mb-1"><?= $t ?></h4>
               <p class="text-slate-500 text-sm leading-relaxed"><?= $d ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TECHNOLOGY PARTNERS (dynamic) -->
<section id="tech-partners" class="py-20 bg-white">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="text-center mb-12" data-anim>
      <span class="inline-block px-4 py-1.5 bg-[#efac3a]/20 text-[#5d1d1f] text-xs font-bold rounded-full uppercase tracking-widest mb-4">Partners</span>
      <h2 class="text-4xl font-black text-[#1a1a2e] mb-3 anim-underline">Technology Partners</h2>
      <p class="text-slate-500 text-base">Trusted brands powering our solutions</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
      <?php foreach ($products as $i => $p): ?>
      <a href="product.php?slug=<?= urlencode($p['slug'] ?? '') ?>"
         data-anim="scale" data-delay="<?= ($i % 8) + 1 ?>"
         class="group flex items-center justify-center h-20 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-md hover:border-[#5d1d1f]/20 transition-all duration-300 px-6">
        <span class="text-slate-300 group-hover:text-[#efac3a] font-black text-xl tracking-tight transition-colors duration-300"><?= htmlspecialchars($p['name']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CONTACT FORM (posts to submit.php) -->
<section id="contact-section" class="bg-slate-50 py-20">
  <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">

      <!-- Form: 3/5 cols -->
      <div class="lg:col-span-3" data-anim="slide-left">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 lg:p-10">
          <span class="inline-block px-4 py-1.5 bg-[#5d1d1f]/10 text-[#5d1d1f] text-xs font-bold rounded-full uppercase tracking-widest mb-3">Enquire Now</span>
          <h2 class="text-3xl font-black text-[#1a1a2e] mb-1">Get in Touch</h2>
          <p class="text-slate-500 text-sm mb-8">Fill in the form and our team will respond within 24 hours. Fields marked <span class="text-[#5d1d1f]">*</span> are required.</p>

          <!-- Client-side error container -->
          <div id="form-errors" class="hidden mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl"></div>

          <form id="contact-form" action="<?= SITE_URL ?>submit.php" method="POST" novalidate class="space-y-5">

            <!-- Honeypot: hidden from humans, traps bots -->
            <div style="display:none" aria-hidden="true">
              <label>Leave this empty</label>
              <input type="text" name="website" tabindex="-1" autocomplete="off"/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label for="cf-name" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Full Name <span class="text-[#5d1d1f]">*</span></label>
                <input id="cf-name" name="full_name" type="text" required autocomplete="name"
                       placeholder="John Smith"
                       value="<?= h($savedForm['name'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
              </div>
              <div>
                <label for="cf-email" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Email Address <span class="text-[#5d1d1f]">*</span></label>
                <input id="cf-email" name="email" type="email" required autocomplete="email"
                       placeholder="john@company.com"
                       value="<?= h($savedForm['email'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label for="cf-phone" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Phone Number</label>
                <input id="cf-phone" name="phone" type="tel" autocomplete="tel"
                       placeholder="+91 484 000 0000"
                       value="<?= h($savedForm['phone'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
              </div>
              <div>
                <label for="cf-company" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Company Name</label>
                <input id="cf-company" name="company" type="text" autocomplete="organization"
                       placeholder="Your Company Ltd."
                       value="<?= h($savedForm['company'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
              </div>
            </div>

            <div>
              <label for="cf-message" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Message <span class="text-[#5d1d1f]">*</span></label>
              <textarea id="cf-message" name="message" rows="5" required
                        placeholder="Describe your IT requirements..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-none"><?= h($savedForm['message'] ?? '') ?></textarea>
              <p class="text-slate-400 text-xs mt-1 text-right"><span id="char-count">0</span> / 2000</p>
            </div>

            <button id="submit-btn" type="submit"
                    class="w-full py-3.5 bg-[#5d1d1f] text-white font-bold text-sm rounded-xl hover:bg-[#4a1517] hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed border border-[#efac3a]/20 text-[#efac3a]">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              <span id="submit-label">Send Message</span>
            </button>

          </form>
        </div>
      </div>

      <!-- Quick Cards: 2/5 cols -->
      <div class="lg:col-span-2 flex flex-col gap-5" data-anim="slide-right">
        <h3 class="text-2xl font-black text-[#1a1a2e]">How Can We Help?</h3>
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-[#5d1d1f]/20 transition-all">
          <h4 class="font-bold text-[#1a1a2e] mb-1">Get a Quote</h4>
          <p class="text-slate-500 text-sm mb-4">Request a tailored proposal for your IT project.</p>
          <a href="#contact-section" class="text-[#efac3a] text-sm font-bold hover:underline inline-flex items-center gap-1">Request Quote &rarr;</a>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-[#5d1d1f]/20 transition-all">
          <h4 class="font-bold text-[#1a1a2e] mb-1">Technical Support</h4>
          <p class="text-slate-500 text-sm mb-4">24/7 helpdesk and on-site support for all systems.</p>
          <a href="#contact-section" class="text-[#efac3a] text-sm font-bold hover:underline inline-flex items-center gap-1">Open a Ticket &rarr;</a>
        </div>
        <div class="bg-gradient-to-br from-[#5d1d1f] to-[#4a0010] rounded-2xl p-6 text-white overflow-hidden relative">
          <div class="absolute top-0 right-0 p-4 opacity-10">
            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
          </div>
          <h4 class="font-bold text-white mb-1">Contact Sales</h4>
          <p class="text-red-200 text-sm mb-4">Talk to a sales engineer about enterprise pricing.</p>
          <a href="#contact-section" class="inline-flex items-center gap-1.5 bg-white text-[#5d1d1f] text-xs font-black px-4 py-2 rounded-full hover:bg-white/90 transition-all">Talk to Sales &rarr;</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- JS: Client-side form validation + char counter -->
<script>
(function () {
  'use strict';
  var form    = document.getElementById('contact-form');
  var errors  = document.getElementById('form-errors');
  var btn     = document.getElementById('submit-btn');
  var label   = document.getElementById('submit-label');
  var msg     = document.getElementById('cf-message');
  var counter = document.getElementById('char-count');

  // Char counter
  if (msg && counter) {
    msg.addEventListener('input', function() {
      counter.textContent = this.value.length;
      counter.classList.toggle('text-red-500', this.value.length > 2000);
    });
  }

  // Client validation
  if (!form) return;
  form.addEventListener('submit', function (e) {
    var errs = [];
    var name  = document.getElementById('cf-name');
    var email = document.getElementById('cf-email');
    var msgEl = document.getElementById('cf-message');

    if (!name.value.trim() || name.value.trim().length < 2)
      errs.push('Please enter your full name (at least 2 characters).');

    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim())
      errs.push('Email address is required.');
    else if (!emailRe.test(email.value.trim()))
      errs.push('Please enter a valid email address.');

    if (!msgEl.value.trim() || msgEl.value.trim().length < 10)
      errs.push('Please write a message (at least 10 characters).');

    if (errs.length > 0) {
      e.preventDefault();
      errors.innerHTML = '&#x26A0; ' + errs.join('<br/>&#x26A0; ');
      errors.classList.remove('hidden');
      errors.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return false;
    }

    // Loading state
    btn.disabled  = true;
    label.textContent = 'Sending…';
    errors.classList.add('hidden');
  });
})();
</script>

<?php
if ($conn) { @mysqli_close($conn); }
require_once __DIR__ . '/../includes/footer.php';
?>
