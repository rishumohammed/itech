<?php
// ═══════════════════════════════════════════════════════════
//  Admin — Services CRUD (v2) — with slug, full_description,
//          sub_services, use_cases
// ═══════════════════════════════════════════════════════════
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

$adminTitle      = 'Services';
$adminActivePage = 'services';
$action          = $_GET['action'] ?? 'list';
$editId          = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act      = $_POST['act']              ?? '';
    $title    = db_escape($_POST['title']            ?? '', $conn);
    $desc     = db_escape($_POST['description']      ?? '', $conn);
    $icon     = db_escape($_POST['icon']             ?? '', $conn);
    $slug     = db_escape(title_to_slug($_POST['title'] ?? ''), $conn);
    $fullDesc = db_escape($_POST['full_description'] ?? '', $conn);
    $subs     = db_escape($_POST['sub_services']     ?? '', $conn);
    $cases    = db_escape($_POST['use_cases']        ?? '', $conn);
    $meta     = db_escape($_POST['meta_description'] ?? '', $conn);

    if ($act === 'add' || $act === 'edit') {
        if (empty($title)) {
            $_SESSION['flash'] = ['msg' => 'Title is required.', 'type' => 'error'];
        } elseif ($act === 'add') {
            mysqli_query($conn,
                "INSERT INTO `services`
                    (`title`,`slug`,`description`,`icon`,`full_description`,`sub_services`,`use_cases`,`meta_description`)
                 VALUES
                    ('$title','$slug','$desc','$icon','$fullDesc','$subs','$cases','$meta')");
            $_SESSION['flash'] = ['msg' => 'Service added.', 'type' => 'success'];
        } else {
            $id = (int)($_POST['id'] ?? 0);
            mysqli_query($conn,
                "UPDATE `services`
                 SET `title`='$title', `slug`='$slug', `description`='$desc', `icon`='$icon',
                     `full_description`='$fullDesc', `sub_services`='$subs',
                     `use_cases`='$cases', `meta_description`='$meta'
                 WHERE `id`=$id");
            $_SESSION['flash'] = ['msg' => 'Service updated.', 'type' => 'success'];
        }
        header('Location: services.php'); exit;
    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        mysqli_query($conn, "DELETE FROM `services` WHERE `id`=$id");
        $_SESSION['flash'] = ['msg' => 'Service deleted.', 'type' => 'success'];
        header('Location: services.php'); exit;
    }
}

$rows = [];
$res  = mysqli_query($conn, "SELECT * FROM `services` ORDER BY `id` ASC");
if ($res) { while ($r = mysqli_fetch_assoc($res)) $rows[] = $r; }

$editRow = null;
if ($action === 'edit' && $editId) {
    $res2    = mysqli_query($conn, "SELECT * FROM `services` WHERE `id`=$editId LIMIT 1");
    $editRow = mysqli_fetch_assoc($res2);
}

require_once __DIR__ . '/_layout_head.php';
?>

<?php
$isFormView = ($action === 'add' || ($action === 'edit' && $editRow));
?>

<?php if ($isFormView): ?>
<!-- ══════════════════════════════════════════════════════════════
     INNER PAGE: ADD / EDIT SERVICE
══════════════════════════════════════════════════════════════ -->
<div class="max-w-5xl">
  <!-- Top Navigation & Header -->
  <div class="mb-6">
    <a href="services.php" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#5d1d1f] mb-3 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to All Services
    </a>
    <h2 class="text-2xl font-black text-[#1a1a2e]">
      <?= ($action === 'edit') ? 'Edit Service: ' . h($editRow['title']) : 'Add New Service' ?>
    </h2>
    <p class="text-slate-500 text-sm mt-1">
      <?= ($action === 'edit') ? 'Update service specifications, technical deliverables, and SEO.' : 'Create a new enterprise IT service for your website catalogue.' ?>
    </p>
  </div>

  <!-- Form Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form method="POST" class="space-y-6">
      <input type="hidden" name="act" value="<?= ($action === 'edit') ? 'edit' : 'add' ?>"/>
      <?php if ($action === 'edit' && $editRow): ?>
      <input type="hidden" name="id" value="<?= (int)$editRow['id'] ?>"/>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Service Title <span class="text-[#5d1d1f]">*</span></label>
          <input name="title" type="text" required placeholder="Datacenter Infrastructure"
                 value="<?= h($editRow['title'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
          <p class="text-slate-400 text-xs mt-1">Slug generated automatically</p>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Short Description</label>
          <input name="description" type="text" placeholder="One-line summary for cards..."
                 value="<?= h($editRow['description'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Icon Identifier</label>
          <input name="icon" type="text" placeholder="icon-datacenter"
                 value="<?= h($editRow['icon'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Meta Description <span class="font-normal text-slate-400">(SEO — max 160 chars)</span></label>
        <input name="meta_description" type="text" maxlength="320"
               placeholder="Brief summary for search engine snippets..."
               value="<?= h($editRow['meta_description'] ?? '') ?>"
               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Full Description <span class="font-normal text-slate-400">(detail page overview)</span></label>
        <textarea name="full_description" rows="5" placeholder="Detailed service description for the service detail page..."
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y"><?= h($editRow['full_description'] ?? '') ?></textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Sub-Services & Deliverables <span class="font-normal text-slate-400">(one per line)</span></label>
          <textarea name="sub_services" rows="6" placeholder="Design &amp; Planning&#10;Installation &amp; Commissioning&#10;Testing &amp; Sign-off&#10;Annual Maintenance Contract"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y font-mono"><?= h($editRow['sub_services'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Typical Use Cases & Industries <span class="font-normal text-slate-400">(one per line)</span></label>
          <textarea name="use_cases" rows="6" placeholder="Enterprise office rollout&#10;Multi-branch network expansion&#10;Cloud infrastructure migration"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y font-mono"><?= h($editRow['use_cases'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
        <button type="submit"
                class="px-8 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
          <?= ($action === 'edit') ? 'Update Service' : 'Publish Service' ?>
        </button>
        <a href="services.php" class="px-6 py-3 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ══════════════════════════════════════════════════════════════
     FIRST PAGE: TABLE VIEW + ADD BUTTON
══════════════════════════════════════════════════════════════ -->
<div class="space-y-6">
  <!-- Module Top Bar -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-black text-[#1a1a2e]">All Services</h2>
      <p class="text-slate-500 text-sm">Manage core IT engineering services displayed in your navigation and service directory.</p>
    </div>
    <div>
      <a href="services.php?action=add"
         class="inline-flex items-center gap-2 px-6 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add New Service
      </a>
    </div>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
        Total Services: <strong class="text-[#1a1a2e] text-sm"><?= count($rows) ?></strong>
      </span>
      <div class="relative">
        <input id="srvSearch" type="text" placeholder="Search services..."
               class="px-3 py-1.5 pl-8 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5d1d1f]"/>
        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="py-16 text-center">
        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <p class="text-slate-500 font-bold mb-1">No services added yet</p>
        <p class="text-slate-400 text-xs mb-4">Start by adding your company's core IT capabilities.</p>
        <a href="services.php?action=add" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#5d1d1f] text-white text-xs font-bold rounded-lg hover:bg-[#4a1719]">
          + Add New Service
        </a>
      </div>
    <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm min-w-[760px]">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Title</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Slug</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Short Description</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Public Page</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Added</th>
            <th class="px-5 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
          </tr>
        </thead>
        <tbody id="srvTableBody" class="divide-y divide-slate-50">
          <?php foreach ($rows as $row): ?>
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-5 py-4 text-slate-400 font-mono text-xs"><?= (int)$row['id'] ?></td>
            <td class="px-5 py-4 font-bold text-[#1a1a2e] whitespace-nowrap">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-[#5d1d1f]/10 text-[#5d1d1f] flex items-center justify-center text-xs font-black">
                  IT
                </div>
                <?= h($row['title']) ?>
              </div>
            </td>
            <td class="px-5 py-4">
              <code class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded-md"><?= h($row['slug'] ?? '—') ?></code>
            </td>
            <td class="px-5 py-4 text-slate-500 max-w-xs truncate"><?= h($row['description']) ?></td>
            <td class="px-5 py-4 whitespace-nowrap">
              <?php if (!empty($row['slug'])): ?>
              <a href="../public/service.php?slug=<?= urlencode($row['slug']) ?>" target="_blank"
                 class="px-2.5 py-1 bg-slate-100 text-slate-600 hover:text-black hover:bg-slate-200 text-xs font-bold rounded-lg transition-colors inline-flex items-center gap-1">
                View ↗
              </a>
              <?php else: ?><span class="text-slate-300 text-xs">—</span><?php endif; ?>
            </td>
            <td class="px-5 py-4 text-slate-400 text-xs whitespace-nowrap"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td class="px-5 py-4 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-2">
                <a href="services.php?action=edit&id=<?= (int)$row['id'] ?>"
                   class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg transition-colors">
                   Edit
                </a>
                <form method="POST"
                      onsubmit="return confirm('Permanently delete &quot;<?= h(addslashes($row['title'])) ?>&quot;? This cannot be undone.');"
                      class="inline">
                  <input type="hidden" name="act" value="delete"/>
                  <input type="hidden" name="id"  value="<?= (int)$row['id'] ?>"/>
                  <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition-colors">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
document.getElementById('srvSearch')?.addEventListener('input', function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll('#srvTableBody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
  });
});
</script>
<?php endif; ?>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
