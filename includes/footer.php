<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Reusable PHP Footer
//  File: includes/footer.php
//  Usage: require_once __DIR__ . '/../includes/footer.php';
// ═══════════════════════════════════════════════════════════
?>
</main><!-- /main-content -->

<!-- ══════════════════════════════════════════════════════════
     ENHANCED FOOTER
══════════════════════════════════════════════════════════ -->
<footer class="bg-[#0f172a] text-slate-300 relative overflow-hidden">
  
  <!-- Subtle Blueprint Pattern Layer -->
  <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
       style="background-image: linear-gradient(#5d1d1f 1px, transparent 1px), linear-gradient(90deg, #5d1d1f 1px, transparent 1px); background-size: 40px 40px;"></div>

  <div class="relative z-10 max-w-screen-xl mx-auto px-6 lg:px-10 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10">

      <!-- Col 1 – Branding -->
      <div class="lg:col-span-3">
        <div class="mb-5">
          <a href="<?= SITE_URL ?>public/index.php" class="inline-block">
            <img src="<?= SITE_URL . get_setting('site_logo') ?>" alt="<?= h(get_setting('site_name')) ?>" class="h-12 w-auto object-contain">
          </a>
        </div>
        <p class="text-slate-400 text-sm leading-relaxed mb-6">
          Enterprise-grade IT infrastructure, intelligent solutions, and 24/7 support &mdash; empowering businesses since 2010.
        </p>
      </div>

      <!-- Col 2 – Reach Us (Contact & Social) -->
      <div class="lg:col-span-3">
        <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 border-b border-[#efac3a]/20 pb-2 inline-block">Reach Us</h3>
        <ul class="space-y-4 text-sm mb-7">
          <li class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 text-[#efac3a] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-slate-400 leading-snug"><?= nl2br(h(get_setting('company_address'))) ?></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-[#efac3a] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <div class="flex flex-col">
              <a href="tel:<?= h(get_setting('company_phone')) ?>" class="text-slate-400 hover:text-[#efac3a] transition-colors duration-200"><?= h(get_setting('company_phone')) ?></a>
            </div>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-[#efac3a] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <a href="mailto:<?= h(get_setting('company_email')) ?>" class="text-slate-400 hover:text-[#efac3a] transition-colors duration-200 break-all"><?= h(get_setting('company_email')) ?></a>
          </li>
        </ul>

        <div class="flex gap-3 mb-6">
          <a href="<?= h(get_setting('social_linkedin')) ?>" target="_blank" aria-label="LinkedIn" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#0077b5] transition-colors duration-200 text-white">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
          </a>
          <a href="<?= h(get_setting('social_twitter')) ?>" target="_blank" aria-label="X (Twitter)" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-black transition-colors duration-200 text-white">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="<?= h(get_setting('social_facebook')) ?>" target="_blank" aria-label="Facebook" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1877f2] transition-colors duration-200 text-white">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
        </div>

        <a href="<?= h(get_setting('brochure_url')) ?>" target="_blank"
           class="inline-flex items-center gap-2 px-5 py-2.5 border border-[#efac3a]/60 text-[#efac3a] text-xs font-bold rounded-full hover:bg-[#5d1d1f] hover:text-white hover:border-[#5d1d1f] transition-all duration-200">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 3v12"/>
          </svg>
          Download Brochure
        </a>
      </div>

      <!-- Col 3 – Quick Links -->
      <div class="lg:col-span-2">
        <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 border-b border-[#efac3a]/20 pb-2 inline-block">Quick Links</h3>
        <ul class="space-y-3 text-sm">
          <li><a href="<?= SITE_URL ?>public/index.php"     class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Home</a></li>
          <li><a href="<?= SITE_URL ?>public/solutions.php" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Solutions</a></li>
          <li><a href="<?= SITE_URL ?>public/services.php"  class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Services</a></li>
          <li><a href="<?= SITE_URL ?>public/products.php"  class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Products</a></li>
          <li><a href="<?= SITE_URL ?>public/about.php"     class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">About Us</a></li>
          <li><a href="<?= SITE_URL ?>public/index.php#contact-section" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Contact</a></li>
        </ul>
      </div>

      <!-- Col 4 – Services -->
      <div class="lg:col-span-2">
        <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 border-b border-[#efac3a]/20 pb-2 inline-block">Services</h3>
        <ul class="space-y-3 text-sm">
          <li><a href="<?= SITE_URL ?>public/service.php?slug=datacenter-infrastructure" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Datacenter</a></li>
          <li><a href="<?= SITE_URL ?>public/service.php?slug=cabling" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Structured Cabling</a></li>
          <li><a href="<?= SITE_URL ?>public/service.php?slug=ip-surveillance" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">IP Surveillance</a></li>
          <li><a href="<?= SITE_URL ?>public/service.php?slug=firewall-utm" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Cybersecurity</a></li>
          <li><a href="<?= SITE_URL ?>public/service.php?slug=cloud-services" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Cloud Hosting</a></li>
          <li><a href="<?= SITE_URL ?>public/service.php?slug=switching-routing" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Core Networking</a></li>
        </ul>
      </div>

      <!-- Col 5 – Solutions -->
      <div class="lg:col-span-2">
        <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 border-b border-[#efac3a]/20 pb-2 inline-block">Solutions</h3>
        <ul class="space-y-3 text-sm">
          <li><a href="<?= SITE_URL ?>public/solution.php?slug=hotels-resorts" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Hotels &amp; Resorts</a></li>
          <li><a href="<?= SITE_URL ?>public/solution.php?slug=healthcare" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Healthcare</a></li>
          <li><a href="<?= SITE_URL ?>public/solution.php?slug=manufacturing" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Industrial / IoT</a></li>
          <li><a href="<?= SITE_URL ?>public/solution.php?slug=oil-gas" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Oil &amp; Gas</a></li>
          <li><a href="<?= SITE_URL ?>public/solution.php?slug=warehousing-logistics" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Smart Logistics</a></li>
          <li><a href="<?= SITE_URL ?>public/solution.php?slug=banking-finance" class="hover:text-[#efac3a] hover:translate-x-1 inline-block transition-all duration-200">Banking &amp; Finance</a></li>
        </ul>
      </div>

    </div>
  </div>

  <!-- Bottom bar -->
  <div class="border-t border-white/10">
    <div class="max-w-screen-xl mx-auto px-6 lg:px-10 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
      <p class="text-slate-500 text-sm text-center sm:text-left">
        &copy; <?= date('Y') ?> <span class="text-slate-400 font-semibold"><?= h(get_setting('site_name')) ?></span>. All rights reserved.
      </p>
      <div class="flex flex-wrap justify-center gap-5 text-xs text-slate-500">
        <a href="#" class="hover:text-[#efac3a] transition-colors duration-200">Privacy Policy</a>
        <a href="#" class="hover:text-[#efac3a] transition-colors duration-200">Terms of Service</a>
        <a href="#" class="hover:text-[#efac3a] transition-colors duration-200">Sitemap</a>
        <a href="<?= SITE_URL ?>public/index.php#contact-section" class="hover:text-[#efac3a] transition-colors duration-200">Contact</a>
      </div>
    </div>
  </div>

</footer>

<!-- ── Global animation engine ──────────────────────────────── -->
<script>
(function () {
  'use strict';

  /* 1. Hamburger menu & Mobile Navigation */
  const btn      = document.getElementById('hamburger-btn');
  const menu     = document.getElementById('mobile-menu');
  const icoOpen  = document.getElementById('icon-open');
  const icoClose = document.getElementById('icon-close');
  const header   = document.getElementById('site-header');

  if (btn && menu) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const isHidden = menu.classList.contains('hidden');
      
      if (isHidden) {
        // Opening
        menu.classList.remove('hidden');
        icoOpen.classList.add('hidden');
        icoClose.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden'; // Lock scroll
      } else {
        // Closing
        menu.classList.add('hidden');
        icoOpen.classList.remove('hidden');
        icoClose.classList.add('hidden');
        btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = ''; // Unlock scroll
      }
    });

    // Close menu when clicking links
    menu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.add('hidden');
        icoOpen.classList.remove('hidden');
        icoClose.classList.add('hidden');
        document.body.style.overflow = '';
      });
    });

    // Close on resize if > lg
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1024) {
        menu.classList.add('hidden');
        icoOpen.classList.remove('hidden');
        icoClose.classList.add('hidden');
        document.body.style.overflow = '';
      }
    });
  }

  /* 2. Navbar Scroll Effects */
  if (header) {
    const onScroll = function () {
      const isScrolled = window.scrollY > 20;
      header.classList.toggle('scrolled', isScrolled);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); 
  }

  /* 3. Intersection Observer (Scroll Reveal) */
  if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('anim-in');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('[data-anim]').forEach(el => revealObserver.observe(el));
  }

  /* 4. Stat Counter Animation */
  function animateCount(el) {
    const target = parseInt(el.textContent.replace(/\D/g, ''));
    if (isNaN(target)) return;
    const duration = 1500;
    const increment = target / (duration / 16);
    let current = 0;
    const update = () => {
      current += increment;
      if (current < target) {
        el.textContent = Math.round(current) + (el.textContent.includes('+') ? '+' : '');
        requestAnimationFrame(update);
      } else {
        el.textContent = target + (el.textContent.includes('+') ? '+' : '');
      }
    };
    update();
  }

  if ('IntersectionObserver' in window) {
    const countObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          countObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('.stat-num').forEach(el => countObserver.observe(el));
  }

})();
</script>

</body>
</html>

