<?php
// ═══════════════════════════════════════════════
//  Admin — Enquiries View
// ═══════════════════════════════════════════════
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

$adminTitle      = 'Enquiries';
$adminActivePage = 'enquiries';

// ── Delete ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM `enquiries` WHERE `id`=$id");
    $_SESSION['flash'] = ['msg' => 'Enquiry deleted.', 'type' => 'success'];
    header('Location: enquiries.php'); exit;
}

// ── Paginate ─────────────────────────────────────────────────
$perPage = 15;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

$total   = (int)(mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM `enquiries`"))['c']);
$pages   = (int)ceil($total / $perPage);

$rows = [];
$res  = mysqli_query($conn, "SELECT * FROM `enquiries` ORDER BY `created_at` DESC LIMIT $perPage OFFSET $offset");
while ($r = mysqli_fetch_assoc($res)) { $rows[] = $r; }

require_once __DIR__ . '/_layout_head.php';
?>

<!-- Header bar -->
<div class="flex items-center justify-between mb-6">
  <div>
    <p class="text-slate-500 text-sm"><?= $total ?> total enquiries &mdash; page <?= $page ?> of <?= max(1,$pages) ?></p>
  </div>
</div>

<!-- Enquiries table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-slate-100">
    <h3 class="font-black text-[#1a1a2e]">All Enquiries</h3>
  </div>

  <?php if (empty($rows)): ?>
    <p class="px-6 py-12 text-sm text-slate-400 text-center">No enquiries submitted yet.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[760px]">
      <thead class="bg-slate-50 border-b border-slate-100">
        <tr>
          <?php foreach (['#','Name','Email','Phone','Company','Message','Date','Delete'] as $h): ?>
          <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap"><?= $h ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        <?php foreach ($rows as $e): ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-5 py-4 text-slate-400 font-mono text-xs"><?= $e['id'] ?></td>
          <td class="px-5 py-4 font-semibold text-[#1a1a2e] whitespace-nowrap"><?= htmlspecialchars($e['name']) ?></td>
          <td class="px-5 py-4">
            <a href="mailto:<?= htmlspecialchars($e['email']) ?>"
               class="text-[#5d1d1f] hover:text-[#efac3a] hover:underline whitespace-nowrap"><?= htmlspecialchars($e['email']) ?></a>
          </td>
          <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
            <div class="flex items-center gap-2">
              <?= htmlspecialchars($e['phone'] ?: '—') ?>
              <?php if (!empty($e['phone'])): ?>
              <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $e['phone']) ?>?text=<?= urlencode("Hello from I Tech IT Solutions. We received your enquiry regarding: " . mb_strimwidth($e['message'], 0, 100, '...')) ?>"
                 target="_blank" title="Contact via WhatsApp"
                 class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </a>
              <?php endif; ?>
            </div>
          </td>
          <td class="px-5 py-4 text-slate-600 whitespace-nowrap"><?= htmlspecialchars($e['company'] ?: '—') ?></td>
          <td class="px-5 py-4 text-slate-500 max-w-xs">
            <span title="<?= htmlspecialchars($e['message']) ?>" class="block truncate"><?= htmlspecialchars($e['message']) ?></span>
          </td>
          <td class="px-5 py-4 text-slate-400 text-xs whitespace-nowrap">
            <span class="block"><?= date('d M Y', strtotime($e['created_at'])) ?></span>
            <span class="block text-slate-300"><?= date('H:i', strtotime($e['created_at'])) ?></span>
          </td>
          <td class="px-5 py-4">
            <form method="POST" onsubmit="return confirm('Permanently delete this enquiry?');">
              <input type="hidden" name="delete_id" value="<?= $e['id'] ?>"/>
              <button class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition-colors">
                Delete
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
  <div class="px-6 py-4 border-t border-slate-100 flex items-center gap-2">
    <?php for ($p = 1; $p <= $pages; $p++): ?>
    <a href="?page=<?= $p ?>"
       class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all
              <?= $p === $page
                    ? 'bg-[#5d1d1f] text-[#efac3a] border border-[#efac3a]/20'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
      <?= $p ?>
    </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

  <?php endif; ?>
</div>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
