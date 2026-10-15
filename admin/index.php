<?php
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

$adminTitle      = 'Dashboard';
$adminActivePage = 'dashboard';

// ── Stats ────────────────────────────────────────────────────
function rowCount(string $table, $conn): int {
    $r = mysqli_query($conn, "SELECT COUNT(*) AS c FROM `$table`");
    return (int)(mysqli_fetch_assoc($r)['c'] ?? 0);
}
$stats = [
    ['label' => 'Total Enquiries',  'count' => rowCount('enquiries', $conn), 'color' => '#5d1d1f', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',            'href' => 'enquiries.php'],
    ['label' => 'Solutions',        'count' => rowCount('solutions',  $conn), 'color' => '#1d4ed8', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5',                                           'href' => 'solutions.php'],
    ['label' => 'Services',         'count' => rowCount('services',   $conn), 'color' => '#047857', 'icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18',         'href' => 'services.php'],
    ['label' => 'Tech Brands',      'count' => rowCount('products',   $conn), 'color' => '#b45309', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',                                             'href' => 'products.php'],
];

// ── Latest 5 enquiries ───────────────────────────────────────
$latestEnq = [];
$res = mysqli_query($conn, "SELECT * FROM `enquiries` ORDER BY `created_at` DESC LIMIT 5");
while ($r = mysqli_fetch_assoc($res)) { $latestEnq[] = $r; }

require_once __DIR__ . '/_layout_head.php';
?>

<!-- Welcome -->
<div class="mb-8">
  <h2 class="text-2xl font-black text-[#1a1a2e] mb-1">Welcome back, <?= htmlspecialchars($adminUser) ?>! &#x1F44B;</h2>
  <p class="text-slate-500 text-sm">Here&rsquo;s a quick overview of your website data.</p>
</div>

<!-- Stat cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
  <?php foreach ($stats as $s): ?>
  <a href="<?= $s['href'] ?>"
     class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 block">
    <div class="flex items-center justify-between mb-4">
      <span class="text-slate-500 text-xs font-semibold"><?= $s['label'] ?></span>
      <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:<?= $s['color'] ?>18">
        <svg class="w-5 h-5" style="color:<?= $s['color'] ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="<?= $s['icon'] ?>"/>
        </svg>
      </div>
    </div>
    <p class="text-4xl font-black" style="color:<?= $s['color'] ?>"><?= $s['count'] ?></p>
  </a>
  <?php endforeach; ?>
</div>

<!-- Quick links -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
  <?php
  $quick = [
    ['+ New Solution', 'solutions.php?action=add', '#1d4ed8'],
    ['+ New Service',  'services.php?action=add',  '#047857'],
    ['+ New Product',  'products.php?action=add',  '#b45309'],
    ['View Enquiries', 'enquiries.php',             '#5d1d1f'],
  ];
  foreach ($quick as [$lbl, $url, $col]): ?>
  <a href="<?= $url ?>"
     class="text-center text-sm font-bold py-3 px-4 rounded-xl text-white transition-all hover:opacity-90 hover:-translate-y-0.5"
     style="background:<?= $col ?>">
    <?= $lbl ?>
  </a>
  <?php endforeach; ?>
</div>

<!-- Latest enquiries -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
    <h3 class="font-black text-[#1a1a2e]">Latest Enquiries</h3>
    <a href="enquiries.php" class="text-[#5d1d1f] hover:text-[#efac3a] text-sm font-bold hover:underline">View all &rarr;</a>
  </div>
  <?php if (empty($latestEnq)): ?>
    <p class="px-6 py-8 text-sm text-slate-400 text-center">No enquiries yet.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[650px]">
      <thead class="bg-slate-50 border-b border-slate-100">
        <tr>
          <?php foreach (['Name','Email','Company','Message','Date'] as $h): ?>
          <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider"><?= $h ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        <?php foreach ($latestEnq as $e): ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-5 py-3.5 font-semibold text-[#1a1a2e] whitespace-nowrap"><?= htmlspecialchars($e['name']) ?></td>
          <td class="px-5 py-3.5 text-[#5d1d1f]"><a href="mailto:<?= htmlspecialchars($e['email']) ?>" class="hover:text-[#efac3a] hover:underline"><?= htmlspecialchars($e['email']) ?></a></td>
          <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap"><?= htmlspecialchars($e['company'] ?: '—') ?></td>
          <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate"><?= htmlspecialchars($e['message']) ?></td>
          <td class="px-5 py-3.5 text-slate-400 text-xs whitespace-nowrap"><?= date('d M Y', strtotime($e['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
