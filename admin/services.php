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
$UPLOAD_DIR      = __DIR__ . '/../uploads/';

// Ensure 'image' column exists in services table
$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM `services` LIKE 'image'");
if ($colCheck && mysqli_num_rows($colCheck) === 0) {
    mysqli_query($conn, "ALTER TABLE `services` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `icon`");
}

function uploadServiceImage(): string {
    global $UPLOAD_DIR;
    if (empty($_FILES['image']['name'])) return '';
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $ftype = $_FILES['image']['type'] ?? '';
    if (!in_array($ftype, $allowed_types)) return '';
    // Size limit: 5MB
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) return '';
    $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $safe = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $safe)) return '';
    if (!is_dir($UPLOAD_DIR)) {
        @mkdir($UPLOAD_DIR, 0755, true);
    }
    $name = 'serv_' . uniqid('', true) . '.' . $ext;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD_DIR . $name)) {
        return 'uploads/' . $name;
    }
    return '';
}

function getImgSrc(?string $path): string {
    if (empty($path)) return '';
    if (strpos($path, 'uploads/') === 0) return '../' . $path;
    return '../uploads/' . $path;
}

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
            $img = uploadServiceImage();
            $imgSql = $img ? "'$img'" : 'NULL';
            mysqli_query($conn,
                "INSERT INTO `services`
                    (`title`,`slug`,`description`,`icon`,`image`,`full_description`,`sub_services`,`use_cases`,`meta_description`)
                 VALUES
                    ('$title','$slug','$desc','$icon',$imgSql,'$fullDesc','$subs','$cases','$meta')");
            $_SESSION['flash'] = ['msg' => 'Service added.', 'type' => 'success'];
        } else {
            $id = (int)($_POST['id'] ?? 0);
            $img = uploadServiceImage();
            $removeImg = !empty($_POST['remove_image']);
            $imgSql = '';
            if ($img) {
                $imgSql = ", `image`='$img'";
            } elseif ($removeImg) {
                $imgSql = ", `image`=NULL";
            }
            mysqli_query($conn,
                "UPDATE `services`
                 SET `title`='$title', `slug`='$slug', `description`='$desc', `icon`='$icon',
                     `full_description`='$fullDesc', `sub_services`='$subs',
                     `use_cases`='$cases', `meta_description`='$meta' $imgSql
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

// ── Pagination Settings ─────────────────────────────────────
$perPage    = 8;
$page       = max(1, (int)($_GET['page'] ?? 1));

$countRes   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM `services`");
$totalRows  = (int)(mysqli_fetch_assoc($countRes)['c'] ?? 0);
$totalPages = max(1, (int)ceil($totalRows / $perPage));

if ($page > $totalPages) $page = $totalPages;
$offset     = ($page - 1) * $perPage;

// ── Fetch current page rows ────────────────────────────────
$rows = [];
$res  = mysqli_query($conn, "SELECT * FROM `services` ORDER BY `id` ASC LIMIT $perPage OFFSET $offset");
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
      <?= ($action === 'edit') ? 'Update service specifications, technical deliverables, and hero background.' : 'Create a new enterprise IT service for your website catalogue.' ?>
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
          <p class="text-slate-400 text-xs mt-1">Displayed on overview cards and summary listings</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">
            Hero Background Image <?= ($action === 'edit') ? '<span class="font-normal text-slate-400">(leave empty to keep current)</span>' : '' ?>
          </label>
          <?php if (!empty($editRow['image'])): ?>
          <div class="mb-3 flex items-center gap-3 p-2.5 bg-slate-50 rounded-xl border border-slate-200">
            <img src="<?= h(getImgSrc($editRow['image'])) ?>" alt="Current image" class="w-16 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" onerror="this.onerror=null;this.src='../assets/images/hero_datacenter.png';"/>
            <div class="min-w-0 flex-1">
              <span class="block text-xs font-semibold text-slate-700 truncate"><?= h($editRow['image']) ?></span>
              <label class="inline-flex items-center gap-1.5 mt-1 text-xs text-rose-600 cursor-pointer font-medium">
                <input type="checkbox" name="remove_image" value="1" class="rounded text-rose-600 focus:ring-rose-500"> Remove background image
              </label>
            </div>
          </div>
          <?php endif; ?>
          <input name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif"
                 class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-500
                        file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                        file:bg-[#5d1d1f]/10 file:text-[#5d1d1f] file:font-bold file:text-xs cursor-pointer"/>
          <p class="text-slate-400 text-xs mt-1">Upload banner background for this service hero section (JPG, PNG, WebP up to 5MB).</p>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Icon Identifier</label>
          <input name="icon" type="text" placeholder="icon-datacenter"
                 value="<?= h($editRow['icon'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
          <p class="text-slate-400 text-xs mt-1">E.g., <code>icon-datacenter</code>, <code>icon-cabling</code>, <code>icon-camera</code></p>
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
        Total Services: <strong class="text-[#1a1a2e] text-sm"><?= $totalRows ?></strong>
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
        <p class="text-slate-500 font-bold mb-1">No services found</p>
        <p class="text-slate-400 text-xs mb-4">Add your company's core IT capabilities or check other pages.</p>
        <a href="services.php?action=add" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#5d1d1f] text-white text-xs font-bold rounded-lg hover:bg-[#4a1719]">
          + Add New Service
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
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Short Description</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Public Page</th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Added</th>
            <th class="px-4 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
          </tr>
        </thead>
        <tbody id="srvTableBody" class="divide-y divide-slate-50">
          <?php foreach ($rows as $row): ?>
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-4 py-3 text-slate-400 font-mono text-xs"><?= (int)$row['id'] ?></td>
            <td class="px-4 py-3">
              <?php 
                $imgThumb = !empty($row['image']) ? getImgSrc($row['image']) : '';
                if (empty($imgThumb)) {
                    $slugMap = [
                        'datacenter-infrastructure' => '../uploads/serv_datacenter.png',
                        'cabling'                   => '../uploads/serv_cabling.png',
                        'ip-surveillance'           => '../uploads/serv_surveillance.png',
                        'cloud-services'            => '../uploads/serv_cloud.png',
                        'wireless-microwave'        => '../uploads/bg_parallax_network.png',
                        'switching-routing'         => '../uploads/noc_center.png',
                        'firewall-utm'              => '../uploads/hero_enterprise.png',
                        'vpn-solutions'             => '../uploads/itech_expert.png',
                        'internet-services'         => '../uploads/mnc_hero.png',
                    ];
                    if (!empty($row['slug']) && isset($slugMap[$row['slug']])) {
                        $imgThumb = $slugMap[$row['slug']];
                    }
                }
              ?>
              <?php if (!empty($imgThumb)): ?>
              <img src="<?= h($imgThumb) ?>" alt="" class="w-12 h-9 object-cover rounded-lg border border-slate-200 bg-slate-100 shadow-sm" onerror="this.onerror=null;this.src='../assets/images/hero_datacenter.png';"/>
              <?php else: ?>
              <div class="w-12 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300 text-xs font-bold">No img</div>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 font-bold text-[#1a1a2e] whitespace-nowrap">
              <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#5d1d1f]/10 text-[#5d1d1f] flex items-center justify-center text-[10px] font-black">
                  IT
                </div>
                <?= h($row['title']) ?>
              </div>
            </td>
            <td class="px-4 py-3">
              <code class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded-md"><?= h($row['slug'] ?? '—') ?></code>
            </td>
            <td class="px-4 py-3 text-slate-500">
              <span class="block truncate max-w-[200px] text-xs text-slate-500" title="<?= h($row['description']) ?>"><?= h($row['description']) ?></span>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              <?php if (!empty($row['slug'])): ?>
              <a href="../public/service.php?slug=<?= urlencode($row['slug']) ?>" target="_blank"
                 class="px-2 py-1 bg-slate-100 text-slate-600 hover:text-black hover:bg-slate-200 text-xs font-bold rounded-lg transition-colors inline-flex items-center gap-1">
                View ↗
              </a>
              <?php else: ?><span class="text-slate-300 text-xs">—</span><?php endif; ?>
            </td>
            <td class="px-4 py-3 text-slate-400 text-xs whitespace-nowrap"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-1.5">
                <a href="services.php?action=edit&id=<?= (int)$row['id'] ?>"
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
