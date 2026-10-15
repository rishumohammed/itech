<?php
// ═══════════════════════════════════════════════════════════
//  Admin — Products / Brands Management (v2)
//  Separated List & Inner Add/Edit Form Views
// ═══════════════════════════════════════════════════════════
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

$adminTitle      = 'Products & Brands';
$adminActivePage = 'products';
$action          = $_GET['action'] ?? 'list';
$editId          = (int)($_GET['id'] ?? 0);
$UPLOAD_DIR      = __DIR__ . '/../uploads/';

// Helper to format image path
function getImgSrc(?string $path): string {
    if (empty($path)) return '';
    if (strpos($path, 'uploads/') === 0) return '../' . $path;
    return '../uploads/' . $path;
}

// ── Logo upload helper ─────────────────────────────────────
function uploadProductLogo(): string {
    global $UPLOAD_DIR;
    if (empty($_FILES['logo']['name'])) return '';
    $ext  = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
    $safe = ['jpg','jpeg','png','webp','gif','svg'];
    if (!in_array($ext, $safe)) return '';
    $name = 'prod_' . uniqid('', true) . '.' . $ext;
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $UPLOAD_DIR . $name)) {
        return 'uploads/' . $name;
    }
    return '';
}

// ── Handle POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act   = $_POST['act'] ?? '';
    $name  = db_escape($_POST['name'] ?? '', $conn);
    $slug  = db_escape(title_to_slug($_POST['name'] ?? ''), $conn);
    $desc  = db_escape($_POST['description'] ?? '', $conn);
    $web   = db_escape($_POST['website'] ?? '', $conn);

    if ($act === 'add' || $act === 'edit') {
        if (empty($name)) {
            $_SESSION['flash'] = ['msg' => 'Brand name is required.', 'type' => 'error'];
        } else {
            $logo = uploadProductLogo();
            if ($act === 'add') {
                $logoSql = $logo ? "'$logo'" : "NULL";
                mysqli_query($conn, "INSERT INTO `products` (`name`,`slug`,`logo`,`description`,`website`) VALUES ('$name','$slug',$logoSql,'$desc','$web')");
                $_SESSION['flash'] = ['msg' => "Brand '$name' added successfully.", 'type' => 'success'];
            } else {
                $id = (int)($_POST['id'] ?? 0);
                $logoSql = $logo ? ", `logo`='$logo'" : "";
                mysqli_query($conn, "UPDATE `products` SET `name`='$name', `slug`='$slug', `description`='$desc', `website`='$web' $logoSql WHERE `id`=$id");
                $_SESSION['flash'] = ['msg' => "Brand '$name' updated.", 'type' => 'success'];
            }
        }
    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        mysqli_query($conn, "DELETE FROM `products` WHERE `id`=$id");
        $_SESSION['flash'] = ['msg' => 'Brand deleted successfully.', 'type' => 'success'];
    }
    header('Location: products.php'); exit;
}

// ── Pagination Settings ─────────────────────────────────────
$perPage    = 10;
$page       = max(1, (int)($_GET['page'] ?? 1));

$countRes   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM `products`");
$totalRows  = (int)(mysqli_fetch_assoc($countRes)['c'] ?? 0);
$totalPages = max(1, (int)ceil($totalRows / $perPage));

if ($page > $totalPages) $page = $totalPages;
$offset     = ($page - 1) * $perPage;

// ── Fetch current page rows ────────────────────────────────
$rows = [];
$res  = mysqli_query($conn, "SELECT * FROM `products` ORDER BY `id` ASC LIMIT $perPage OFFSET $offset");
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) { $rows[] = $r; }
}

$editRow = null;
if ($action === 'edit' && $editId) {
    $res2    = mysqli_query($conn, "SELECT * FROM `products` WHERE `id`=$editId LIMIT 1");
    $editRow = mysqli_fetch_assoc($res2);
}

$isFormView = ($action === 'add' || ($action === 'edit' && $editRow));

require_once __DIR__ . '/_layout_head.php';
?>

<?php if ($isFormView): ?>
<!-- ══════════════════════════════════════════════════════════════
     INNER PAGE: ADD / EDIT BRAND & PRODUCT
══════════════════════════════════════════════════════════════ -->
<div class="max-w-4xl">
  <!-- Top Navigation & Header -->
  <div class="mb-6">
    <a href="products.php" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#5d1d1f] mb-3 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to All Brands & Partners
    </a>
    <h2 class="text-2xl font-black text-[#1a1a2e]">
      <?= ($action === 'edit') ? 'Edit Brand: ' . h($editRow['name']) : 'Add New Brand / Partner' ?>
    </h2>
    <p class="text-slate-500 text-sm mt-1">
      <?= ($action === 'edit') ? 'Update partner vendor information, website link, and brand logo.' : 'Add a new technology vendor or hardware partner brand to the website.' ?>
    </p>
  </div>

  <!-- Form Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form method="POST" enctype="multipart/form-data" class="space-y-6">
      <input type="hidden" name="act" value="<?= ($action === 'edit') ? 'edit' : 'add' ?>"/>
      <?php if ($action === 'edit' && $editRow): ?>
      <input type="hidden" name="id" value="<?= (int)$editRow['id'] ?>"/>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Brand Name <span class="text-[#5d1d1f]">*</span></label>
          <input name="name" type="text" required placeholder="Cisco, Huawei, Fortinet..."
                 value="<?= h($editRow['name'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
          <p class="text-slate-400 text-xs mt-1">Slug is auto-generated from the brand name</p>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Official Website URL</label>
          <input name="website" type="url" placeholder="https://www.cisco.com"
                 value="<?= h($editRow['website'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>
      </div>

      <!-- Logo Upload with Preview -->
      <div>
        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Brand Logo Image</label>
        <?php if (!empty($editRow['logo'])): ?>
        <div class="flex items-center gap-4 mb-3 p-3 bg-slate-50 rounded-xl border border-slate-200 max-w-md">
          <img src="<?= h(getImgSrc($editRow['logo'])) ?>" alt="" class="h-10 w-24 object-contain bg-white rounded border border-slate-200 p-1"/>
          <div>
            <p class="text-xs font-bold text-slate-700">Current Logo</p>
            <p class="text-[11px] text-slate-400">Choose a new file below to replace it</p>
          </div>
        </div>
        <?php endif; ?>
        <input name="logo" type="file" accept="image/*,.svg"
               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-[#5d1d1f]/10 file:text-[#5d1d1f] file:font-semibold file:text-xs cursor-pointer bg-slate-50"/>
        <p class="text-slate-400 text-xs mt-1">Accepts PNG, SVG, WebP, JPG, GIF (transparent PNG or SVG recommended)</p>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Description <span class="text-slate-400 font-normal">(displayed on products and partners page)</span></label>
        <textarea name="description" rows="4" placeholder="Brief about the brand, our tier partnership, and technical domains covered..."
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y"><?= h($editRow['description'] ?? '') ?></textarea>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
        <button type="submit"
                class="px-8 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
          <?= ($action === 'edit') ? 'Update Brand' : 'Publish Brand' ?>
        </button>
        <a href="products.php" class="px-6 py-3 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all">
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
      <h2 class="text-2xl font-black text-[#1a1a2e]">Products & Brands</h2>
      <p class="text-slate-500 text-sm">Manage global hardware vendors, authorized brands, and technology partner alliances.</p>
    </div>
    <div>
      <a href="products.php?action=add"
         class="inline-flex items-center gap-2 px-6 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add New Brand
      </a>
    </div>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
        Total Brands: <strong class="text-[#1a1a2e] text-sm"><?= $totalRows ?></strong>
      </span>
      <div class="relative">
        <input id="prodSearch" type="text" placeholder="Search brands..."
               class="px-3 py-1.5 pl-8 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5d1d1f]"/>
        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="py-16 text-center">
        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <p class="text-slate-500 font-bold mb-1">No brands found</p>
        <p class="text-slate-400 text-xs mb-4">Add your partner technology brands and vendor certifications.</p>
        <a href="products.php?action=add" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#5d1d1f] text-white text-xs font-bold rounded-lg hover:bg-[#4a1719]">
          + Add New Brand
        </a>
      </div>
    <?php else: ?>
    <div class="overflow-x-auto w-full">
      <table class="w-full text-sm min-w-[700px]">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Logo</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Brand Name</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Slug</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Website</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Description</th>
            <th class="px-4 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
          </tr>
        </thead>
        <tbody id="prodTableBody" class="divide-y divide-slate-50">
          <?php foreach ($rows as $row): ?>
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-4 py-3 text-slate-400 font-mono text-xs"><?= (int)$row['id'] ?></td>
            <td class="px-4 py-3">
              <?php if (!empty($row['logo'])): ?>
                <img src="<?= h(getImgSrc($row['logo'])) ?>" alt="" class="h-8 w-16 object-contain bg-white rounded-lg border border-slate-200 p-1 shadow-sm" onerror="this.style.display='none'"/>
              <?php else: ?>
                <span class="text-slate-300 text-xs italic">No logo</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 font-bold text-[#1a1a2e] whitespace-nowrap"><?= h($row['name']) ?></td>
            <td class="px-4 py-3">
              <code class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded-md"><?= h($row['slug']) ?></code>
            </td>
            <td class="px-4 py-3">
              <?php if (!empty($row['website'])): ?>
                <a href="<?= h($row['website']) ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-semibold inline-flex items-center gap-1 hover:underline">
                  Link ↗
                </a>
              <?php else: ?>
                <span class="text-slate-300 text-xs">—</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-slate-500">
              <span class="block truncate max-w-[200px] text-xs text-slate-500" title="<?= h($row['description']) ?>"><?= h($row['description'] ?: '—') ?></span>
            </td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-1.5">
                <a href="products.php?action=edit&id=<?= (int)$row['id'] ?>"
                   class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg transition-colors">
                  Edit
                </a>
                <form method="POST"
                      onsubmit="return confirm('Permanently delete brand &quot;<?= h(addslashes($row['name'])) ?>&quot;?');"
                      class="inline">
                  <input type="hidden" name="act" value="delete"/>
                  <input type="hidden" name="id"  value="<?= (int)$row['id'] ?>"/>
                  <button type="submit" class="px-2.5 py-1 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition-colors">
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

    <!-- Pagination Footer -->
    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
      <div class="text-xs text-slate-500 font-medium">
        Showing <strong class="text-slate-800 font-bold"><?= $totalRows > 0 ? ($offset + 1) : 0 ?></strong> to <strong class="text-slate-800 font-bold"><?= min($offset + $perPage, $totalRows) ?></strong> of <strong class="text-slate-800 font-bold"><?= $totalRows ?></strong> entries
      </div>
      <div class="flex items-center gap-1">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
            &larr; Prev
          </a>
        <?php else: ?>
          <span class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-100 text-xs font-bold text-slate-400 cursor-not-allowed">
            &larr; Prev
          </span>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <?php if ($p == $page): ?>
            <span class="px-3 py-1.5 rounded-lg bg-[#5d1d1f] text-white text-xs font-bold shadow-sm">
              <?= $p ?>
            </span>
          <?php else: ?>
            <a href="?page=<?= $p ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
              <?= $p ?>
            </a>
          <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
            Next &rarr;
          </a>
        <?php else: ?>
          <span class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-100 text-xs font-bold text-slate-400 cursor-not-allowed">
            Next &rarr;
          </span>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
  </div>
</div>

<script>
document.getElementById('prodSearch')?.addEventListener('input', function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll('#prodTableBody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
  });
});
</script>
<?php endif; ?>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
