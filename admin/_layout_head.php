<?php
// ── Admin layout: HEAD + SIDEBAR + TOPBAR ───────────────────
// Usage: require_once __DIR__ . '/_layout_head.php';
// Close with: require_once __DIR__ . '/_layout_foot.php';

$adminTitle       = $adminTitle       ?? 'Admin';
$adminActivePage  = $adminActivePage  ?? '';

$navItems = [
    'dashboard'  => ['label' => 'Dashboard',  'href' => 'index.php',      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    'enquiries'  => ['label' => 'Enquiries',  'href' => 'enquiries.php',  'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    'solutions'  => ['label' => 'Solutions',  'href' => 'solutions.php',  'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
    'services'   => ['label' => 'Services',   'href' => 'services.php',   'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    'products'   => ['label' => 'Products',   'href' => 'products.php',   'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
];

if (is_admin()) {
    $navItems['users']    = ['label' => 'Admin Users', 'href' => 'users.php',    'icon' => 'M12 4.354l1.1 3.383h3.558l-2.877 2.091 1.099 3.383L12 11.12l-2.88 2.09 1.1-3.383-2.878-2.091h3.558L12 4.354zM12 21a9 9 0 009-9 9 9 0 00-9-9 9 9 0 00-9 9 9 9 0 009 9z'];
    $navItems['settings'] = ['label' => 'Site Settings', 'href' => 'settings.php', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'];
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($adminTitle) ?> | I Tech Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <style>
    html, body {
      max-width: 100vw;
      overflow-x: hidden;
    }
    body { font-family: 'Inter', sans-serif; }
    .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200; }

    /* Custom subtle scrollbar for table container */
    .overflow-x-auto {
      -webkit-overflow-scrolling: touch;
    }
    .overflow-x-auto::-webkit-scrollbar {
      height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 9999px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Subtle scrollbar for sidebar */
    aside::-webkit-scrollbar {
      width: 4px;
    }
    aside::-webkit-scrollbar-track {
      background: transparent;
    }
    aside::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 9999px;
    }
  </style>
</head>
<body class="bg-slate-100 min-h-screen flex overflow-x-hidden antialiased">

<!-- ══ SIDEBAR ════════════════════════════════════════════════ -->
<aside class="w-64 h-screen sticky top-0 bg-[#1a1a2e] flex flex-col shrink-0 shadow-xl z-30 overflow-y-auto">

  <!-- Logo -->
  <div class="px-6 py-5 border-b border-white/10 shrink-0">
    <div class="flex items-center gap-2.5">
      <div class="w-8 h-8 rounded-lg bg-[#5d1d1f] flex items-center justify-center shadow border border-[#efac3a]/20">
        <span class="text-[#efac3a] font-extrabold text-sm leading-none">IT</span>
      </div>
      <div>
        <p class="text-white font-extrabold text-sm leading-tight tracking-tight">I Tech IT</p>
        <p class="text-slate-500 text-xs">Admin Panel</p>
      </div>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 px-3 py-5 space-y-1">
    <?php foreach ($navItems as $key => $item):
      $isActive = ($adminActivePage === $key);
    ?>
    <a href="<?= $item['href'] ?>"
       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
              <?= $isActive
                    ? 'bg-[#5d1d1f] text-white shadow-sm border border-[#efac3a]/20'
                    : 'text-slate-400 hover:text-white hover:bg-white/8' ?>">
      <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $item['icon'] ?>"/>
      </svg>
      <?= $item['label'] ?>
    </a>
    <?php endforeach; ?>
  </nav>

  <!-- View site + version -->
  <div class="px-3 py-4 border-t border-white/10 shrink-0">
    <a href="../public/index.php" target="_blank"
       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-slate-500 text-xs font-medium hover:text-white hover:bg-white/5 transition-all duration-200">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
      </svg>
      View Public Site
    </a>
  </div>
</aside>

<!-- ══ MAIN wrapper ═══════════════════════════════════════════ -->
<div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">

  <!-- TOPBAR -->
  <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between shadow-sm sticky top-0 z-20">
    <h1 class="text-lg font-black text-[#1a1a2e] truncate"><?= htmlspecialchars($adminTitle) ?></h1>
    <div class="flex items-center gap-4 shrink-0">
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-full bg-[#5d1d1f] flex items-center justify-center border border-[#efac3a]/20">
          <span class="text-[#efac3a] text-xs font-bold"><?= strtoupper(substr($adminUser, 0, 1)) ?></span>
        </div>
        <span class="text-sm font-semibold text-slate-700 hidden sm:inline"><?= htmlspecialchars($adminUser) ?></span>
      </div>
      <a href="logout.php"
         class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-red-50 hover:text-red-600 transition-all duration-200">
        Logout
      </a>
    </div>
  </header>

  <!-- Flash messages -->
  <?php if (!empty($_SESSION['flash'])): ?>
  <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div class="mx-8 mt-5 px-5 py-3 rounded-xl text-sm font-semibold
              <?= $flash['type'] === 'success'
                    ? 'bg-emerald-50 border border-emerald-200 text-emerald-800'
                    : 'bg-red-50 border border-red-200 text-red-800' ?>">
    <?= htmlspecialchars($flash['msg']) ?>
  </div>
  <?php endif; ?>

  <!-- Page content starts here -->
  <main class="flex-1 px-8 py-7 min-w-0">
