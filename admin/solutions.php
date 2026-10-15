<?php
// ═══════════════════════════════════════════════════════════
//  Admin — Solutions CRUD (v2) — with slug auto-generation
// ═══════════════════════════════════════════════════════════
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

$adminTitle      = 'Solutions';
$adminActivePage = 'solutions';
$action          = $_GET['action'] ?? 'list';
$editId          = (int)($_GET['id'] ?? 0);
$UPLOAD_DIR      = __DIR__ . '/../uploads/';

// ── Image upload helper ────────────────────────────────────
function uploadSolutionImage(): string {
    global $UPLOAD_DIR;
    if (empty($_FILES['image']['name'])) return '';
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $ftype = $_FILES['image']['type'];
    if (!in_array($ftype, $allowed_types)) return '';
    // Size limit: 5MB
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) return '';
    $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $safe = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $safe)) return '';
    $name = 'sol_' . uniqid('', true) . '.' . $ext;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD_DIR . $name)) {
        return 'uploads/' . $name;
    }
    return '';
}

// ── Handle POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act      = $_POST['act']              ?? '';
    $title    = db_escape($_POST['title']            ?? '', $conn);
    $desc     = db_escape($_POST['description']      ?? '', $conn);
    $slug     = db_escape(title_to_slug($_POST['title'] ?? ''), $conn);
    $meta     = db_escape($_POST['meta_description'] ?? '', $conn);
    $fullDesc = db_escape($_POST['full_description'] ?? '', $conn);
    $challenges = db_escape($_POST['challenges']        ?? '', $conn);
    $ourSol    = db_escape($_POST['our_solution']      ?? '', $conn);
    $techs     = db_escape($_POST['technologies']      ?? '', $conn);

    if ($act === 'add' || $act === 'edit') {
        if (empty($title)) {
            $_SESSION['flash'] = ['msg' => 'Title is required.', 'type' => 'error'];
        } else {
            $img = uploadSolutionImage();
            if ($act === 'add') {
                $imgSql = $img ? "'$img'" : 'NULL';
                mysqli_query($conn,
                    "INSERT INTO `solutions` (`title`,`slug`,`description`,`meta_description`,`full_description`,`challenges`,`our_solution`,`technologies`,`image`)
                     VALUES ('$title','$slug','$desc','$meta','$fullDesc','$challenges','$ourSol','$techs',$imgSql)");
                $_SESSION['flash'] = ['msg' => 'Solution added successfully.', 'type' => 'success'];
            } else {
                $id     = (int)($_POST['id'] ?? 0);
                $imgSql = $img ? ", `image`='$img'" : '';
                mysqli_query($conn,
                    "UPDATE `solutions`
                     SET `title`='$title', `slug`='$slug', `description`='$desc',
                         `meta_description`='$meta', `full_description`='$fullDesc',
                         `challenges`='$challenges', `our_solution`='$ourSol', `technologies`='$techs' $imgSql
                     WHERE `id`=$id");
                $_SESSION['flash'] = ['msg' => 'Solution updated.', 'type' => 'success'];
            }
        }
    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        mysqli_query($conn, "DELETE FROM `solutions` WHERE `id`=$id");
        $_SESSION['flash'] = ['msg' => 'Solution deleted.', 'type' => 'success'];
    }
    header('Location: solutions.php'); exit;
}

// ── Pagination Settings ─────────────────────────────────────
$perPage    = 8;
$page       = max(1, (int)($_GET['page'] ?? 1));

$countRes   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM `solutions`");
$totalRows  = (int)(mysqli_fetch_assoc($countRes)['c'] ?? 0);
$totalPages = max(1, (int)ceil($totalRows / $perPage));

if ($page > $totalPages) $page = $totalPages;
$offset     = ($page - 1) * $perPage;

// ── Fetch current page rows ────────────────────────────────
$rows = [];
$res  = mysqli_query($conn, "SELECT * FROM `solutions` ORDER BY `id` ASC LIMIT $perPage OFFSET $offset");
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) { $rows[] = $r; }
}

$editRow = null;
if ($action === 'edit' && $editId) {
    $res2    = mysqli_query($conn, "SELECT * FROM `solutions` WHERE `id`=$editId LIMIT 1");
    $editRow = mysqli_fetch_assoc($res2);
}

require_once __DIR__ . '/_layout_head.php';
?>

<?php
// Helper to format image path
function getImgSrc(?string $path): string {
    if (empty($path)) return '';
    if (strpos($path, 'uploads/') === 0) return '../' . $path;
    return '../uploads/' . $path;
}

$isFormView = ($action === 'add' || ($action === 'edit' && $editRow));
?>

<?php if ($isFormView): ?>
<!-- ══════════════════════════════════════════════════════════════
     INNER PAGE: ADD / EDIT SOLUTION
══════════════════════════════════════════════════════════════ -->
<div class="max-w-5xl">
  <!-- Top Navigation & Header -->
  <div class="mb-6">
    <a href="solutions.php" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#5d1d1f] mb-3 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to All Solutions
    </a>
    <h2 class="text-2xl font-black text-[#1a1a2e]">
      <?= ($action === 'edit') ? 'Edit Solution: ' . h($editRow['title']) : 'Add New Solution' ?>
    </h2>
    <p class="text-slate-500 text-sm mt-1">
      <?= ($action === 'edit') ? 'Update the solution details, SEO attributes, and industry media.' : 'Create a new industry-specific solution for the public website.' ?>
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
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Title <span class="text-[#5d1d1f]">*</span></label>
          <input name="title" type="text" required placeholder="Hotels & Resorts"
                 value="<?= h($editRow['title'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
          <p class="text-slate-400 text-xs mt-1">URL slug will be generated automatically from title</p>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Short Description <span class="text-slate-400 font-normal">(for cards)</span></label>
          <input name="description" type="text" placeholder="One-line summary for cards..."
                 value="<?= h($editRow['description'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Meta Description <span class="text-slate-400 font-normal">(for SEO — recommended max 160 chars)</span></label>
        <input name="meta_description" type="text" maxlength="320"
               placeholder="Brief summary displayed in search engine results..."
               value="<?= h($editRow['meta_description'] ?? '') ?>"
               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Full Description <span class="text-slate-400 font-normal">(displayed in Overview section)</span></label>
        <textarea name="full_description" rows="5" placeholder="Detailed overview paragraph explaining this solution in depth..."
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y"><?= h($editRow['full_description'] ?? '') ?></textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Key Challenges <span class="text-slate-400 font-normal">(one per line)</span></label>
          <textarea name="challenges" rows="5" placeholder="Challenge 1&#10;Challenge 2&#10;Challenge 3..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y font-mono"><?= h($editRow['challenges'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Our Solutions <span class="text-slate-400 font-normal">(one per line)</span></label>
          <textarea name="our_solution" rows="5" placeholder="Delivered Solution 1&#10;Delivered Solution 2..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all resize-y font-mono"><?= h($editRow['our_solution'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Technologies & Brands <span class="text-slate-400 font-normal">(comma-separated)</span></label>
          <input name="technologies" type="text" placeholder="Cisco, Fortinet, Huawei, Ruckus..."
                 value="<?= h($editRow['technologies'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">
            Feature Image <?= ($action === 'edit') ? '<span class="font-normal text-slate-400">(leave empty to keep current)</span>' : '' ?>
          </label>
          <?php if (!empty($editRow['image'])): ?>
          <div class="mb-3 flex items-center gap-3 p-2 bg-slate-50 rounded-xl border border-slate-200">
            <img src="<?= h(getImgSrc($editRow['image'])) ?>" alt="Current image" class="w-16 h-12 object-cover rounded-lg border border-slate-200"/>
            <span class="text-xs text-slate-500">Current Image: <code class="text-slate-700"><?= h($editRow['image']) ?></code></span>
          </div>
          <?php endif; ?>
          <input name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif"
                 class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-500
                        file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                        file:bg-[#5d1d1f]/10 file:text-[#5d1d1f] file:font-bold file:text-xs cursor-pointer"/>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
        <button type="submit"
                class="px-8 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
          <?= ($action === 'edit') ? 'Update Solution' : 'Publish Solution' ?>
        </button>
        <a href="solutions.php" class="px-6 py-3 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all">
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
      <h2 class="text-2xl font-black text-[#1a1a2e]">All Solutions</h2>
      <p class="text-slate-500 text-sm">Manage industry IT infrastructure solutions displayed on your public website.</p>
    </div>
    <div>
      <a href="solutions.php?action=add"
         class="inline-flex items-center gap-2 px-6 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add New Solution
      </a>
    </div>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
        Total Solutions: <strong class="text-[#1a1a2e] text-sm"><?= $totalRows ?></strong>
      </span>
      <div class="relative">
        <input id="solSearch" type="text" placeholder="Search solutions..."
               class="px-3 py-1.5 pl-8 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5d1d1f]"/>
        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="py-16 text-center">
        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
        </div>
        <p class="text-slate-500 font-bold mb-1">No solutions found</p>
        <p class="text-slate-400 text-xs mb-4">Create your first industry solution or check other pages.</p>
        <a href="solutions.php?action=add" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#5d1d1f] text-white text-xs font-bold rounded-lg hover:bg-[#4a1719]">
          + Add New Solution
        </a>
      </div>
    <?php else: ?>
    <div class="overflow-x-auto w-full">
      <table class="w-full text-sm min-w-[700px]">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Image</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Title</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Slug</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Description</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Added</th>
            <th class="px-4 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
          </tr>
        </thead>
        <tbody id="solTableBody" class="divide-y divide-slate-50">
          <?php foreach ($rows as $row): ?>
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-4 py-3 text-slate-400 font-mono text-xs"><?= (int)$row['id'] ?></td>
            <td class="px-4 py-3">
              <?php if (!empty($row['image'])): ?>
              <img src="<?= h(getImgSrc($row['image'])) ?>" alt="" class="w-12 h-9 object-cover rounded-lg border border-slate-200 bg-slate-100 shadow-sm" onerror="this.onerror=null;this.src='../assets/images/tech-network.png';"/>
              <?php else: ?>
              <div class="w-12 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300 text-xs font-bold">No img</div>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 font-bold text-[#1a1a2e] whitespace-nowrap"><?= h($row['title']) ?></td>
            <td class="px-4 py-3">
              <code class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded-md"><?= h($row['slug'] ?? '—') ?></code>
            </td>
            <td class="px-4 py-3 text-slate-500">
              <span class="block truncate max-w-[200px] text-xs text-slate-500" title="<?= h($row['description']) ?>"><?= h($row['description']) ?></span>
            </td>
            <td class="px-4 py-3 text-slate-400 text-xs whitespace-nowrap"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-1.5">
                <a href="../public/solution.php?slug=<?= urlencode($row['slug']) ?>" target="_blank"
                   class="px-2 py-1 bg-slate-100 text-slate-600 hover:text-black hover:bg-slate-200 text-xs font-bold rounded-lg transition-colors" title="View Public Page">
                   View ↗
                </a>
                <a href="solutions.php?action=edit&id=<?= (int)$row['id'] ?>"
                   class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg transition-colors">
                   Edit
                </a>
                <form method="POST"
                      onsubmit="return confirm('Permanently delete &quot;<?= h(addslashes($row['title'])) ?>&quot;? This cannot be undone.');"
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
document.getElementById('solSearch')?.addEventListener('input', function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll('#solTableBody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
  });
});
</script>
<?php endif; ?>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
