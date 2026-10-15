<?php
// ═══════════════════════════════════════════════
//  Admin — User Management (v2)
//  Separated List & Dedicated Inner Add/Edit Page
// ═══════════════════════════════════════════════
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

// Restricted to Admin Only
check_admin_only();

$adminTitle      = 'Admin Users';
$adminActivePage = 'users';
$action          = $_GET['action'] ?? 'list';
$editId          = (int)($_GET['id'] ?? 0);

// ── Handle POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act  = $_POST['act'] ?? '';
    $u    = trim(db_escape($_POST['username'] ?? '', $conn));
    $f    = trim(db_escape($_POST['full_name'] ?? '', $conn));
    $role = db_escape($_POST['role'] ?? 'subadmin', $conn);
    $p    = $_POST['password'] ?? '';

    if ($act === 'add') {
        if (empty($u) || empty($f) || empty($p)) {
            $_SESSION['flash'] = ['msg' => 'All fields including password are required.', 'type' => 'error'];
        } else {
            // Check username uniqueness
            $chk = mysqli_query($conn, "SELECT id FROM `users` WHERE `username`='$u' LIMIT 1");
            if (mysqli_num_rows($chk) > 0) {
                $_SESSION['flash'] = ['msg' => "The username '@$u' is already in use.", 'type' => 'error'];
            } else {
                $hashed = password_hash($p, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`username`, `password`, `full_name`, `role`) VALUES ('$u', '$hashed', '$f', '$role')";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['flash'] = ['msg' => "User account '@$u' created successfully.", 'type' => 'success'];
                } else {
                    $_SESSION['flash'] = ['msg' => 'Database error: ' . mysqli_error($conn), 'type' => 'error'];
                }
            }
        }
        header('Location: users.php'); exit;

    } elseif ($act === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        if (empty($u) || empty($f)) {
            $_SESSION['flash'] = ['msg' => 'Username and Full Name cannot be empty.', 'type' => 'error'];
        } else {
            // Check if username taken by another user
            $chk = mysqli_query($conn, "SELECT id FROM `users` WHERE `username`='$u' AND `id`!=$id LIMIT 1");
            if (mysqli_num_rows($chk) > 0) {
                $_SESSION['flash'] = ['msg' => "Username '@$u' is already taken by another user.", 'type' => 'error'];
            } else {
                $passUpdate = '';
                if (!empty($p)) {
                    $hashed = password_hash($p, PASSWORD_DEFAULT);
                    $passUpdate = ", `password`='$hashed'";
                }
                $sql = "UPDATE `users` SET `username`='$u', `full_name`='$f', `role`='$role' $passUpdate WHERE `id`=$id";
                if (mysqli_query($conn, $sql)) {
                    // If edited self, update session user
                    $oldUserRes = mysqli_query($conn, "SELECT username FROM `users` WHERE id=$id");
                    if ($uRow = mysqli_fetch_assoc($oldUserRes)) {
                        if ($_SESSION['admin_user'] === $uRow['username']) {
                            $_SESSION['admin_user'] = $u;
                        }
                    }
                    $_SESSION['flash'] = ['msg' => "User account '@$u' updated successfully.", 'type' => 'success'];
                } else {
                    $_SESSION['flash'] = ['msg' => 'Database error: ' . mysqli_error($conn), 'type' => 'error'];
                }
            }
        }
        header('Location: users.php'); exit;

    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $res = mysqli_query($conn, "SELECT username FROM `users` WHERE id=$id");
        $uRow = mysqli_fetch_assoc($res);
        if ($uRow && $uRow['username'] === $_SESSION['admin_user']) {
            $_SESSION['flash'] = ['msg' => 'You cannot delete your own active account.', 'type' => 'error'];
        } else {
            mysqli_query($conn, "DELETE FROM `users` WHERE id=$id");
            $_SESSION['flash'] = ['msg' => 'User deleted successfully.', 'type' => 'success'];
        }
        header('Location: users.php'); exit;
    }
}

// ── Pagination Settings ─────────────────────────────────────
$perPage    = 10;
$page       = max(1, (int)($_GET['page'] ?? 1));

$countRes   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM `users`");
$totalRows  = (int)(mysqli_fetch_assoc($countRes)['c'] ?? 0);
$totalPages = max(1, (int)ceil($totalRows / $perPage));

if ($page > $totalPages) $page = $totalPages;
$offset     = ($page - 1) * $perPage;

// ── Fetch current page rows ────────────────────────────────
$users = [];
$res   = mysqli_query($conn, "SELECT * FROM `users` ORDER BY role ASC, username ASC LIMIT $perPage OFFSET $offset");
while ($r = mysqli_fetch_assoc($res)) { $users[] = $r; }

$editRow = null;
if ($action === 'edit' && $editId) {
    $res2 = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`=$editId LIMIT 1");
    $editRow = mysqli_fetch_assoc($res2);
}

$isFormView = ($action === 'add' || ($action === 'edit' && $editRow));

require_once __DIR__ . '/_layout_head.php';
?>

<?php if ($isFormView): ?>
<!-- ══════════════════════════════════════════════════════════════
     INNER PAGE: ADD / EDIT USER ACCOUNT
══════════════════════════════════════════════════════════════ -->
<div class="max-w-3xl">
  <!-- Top Navigation & Header -->
  <div class="mb-6">
    <a href="users.php" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#5d1d1f] mb-3 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to All Accounts
    </a>
    <h2 class="text-2xl font-black text-[#1a1a2e]">
      <?= ($action === 'edit') ? 'Edit Account: ' . h($editRow['full_name']) : 'Add New Admin Account' ?>
    </h2>
    <p class="text-slate-500 text-sm mt-1">
      <?= ($action === 'edit') ? 'Update account details, role permissions, or reset login password.' : 'Grant access to an administrative user with designated permissions.' ?>
    </p>
  </div>

  <!-- Form Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form method="POST" class="space-y-6">
      <input type="hidden" name="act" value="<?= ($action === 'edit') ? 'edit' : 'add' ?>"/>
      <?php if ($action === 'edit' && $editRow): ?>
      <input type="hidden" name="id" value="<?= (int)$editRow['id'] ?>"/>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Full Name <span class="text-[#5d1d1f]">*</span></label>
          <input name="full_name" type="text" required placeholder="John Doe"
                 value="<?= h($editRow['full_name'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Username <span class="text-[#5d1d1f]">*</span></label>
          <input name="username" type="text" required placeholder="johndoe"
                 value="<?= h($editRow['username'] ?? '') ?>"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
          <p class="text-slate-400 text-xs mt-1">Used to login to this admin panel</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">
            Password <?= ($action === 'edit') ? '<span class="text-slate-400 font-normal lowercase">(leave blank to keep current)</span>' : '<span class="text-[#5d1d1f]">*</span>' ?>
          </label>
          <input name="password" type="password" <?= ($action === 'add') ? 'required' : '' ?> placeholder="••••••••••••"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all"/>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Role & Privileges <span class="text-[#5d1d1f]">*</span></label>
          <select name="role" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/40 focus:border-[#5d1d1f] transition-all">
            <option value="subadmin" <?= (($editRow['role'] ?? '') === 'subadmin') ? 'selected' : '' ?>>Sub-Admin (Restricted to Content)</option>
            <option value="admin"    <?= (($editRow['role'] ?? '') === 'admin')    ? 'selected' : '' ?>>Main Admin (Full Access & Settings)</option>
          </select>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit"
                class="px-8 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
          <?= ($action === 'edit') ? 'Update Account' : 'Create Account' ?>
        </button>
        <a href="users.php" class="px-6 py-3 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all">
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
      <h2 class="text-2xl font-black text-[#1a1a2e]">Manage Accounts</h2>
      <p class="text-slate-500 text-sm">Control administrative users, permission privileges, and system access.</p>
    </div>
    <div>
      <a href="users.php?action=add"
         class="inline-flex items-center gap-2 px-6 py-3 bg-[#5d1d1f] text-white text-sm font-bold rounded-xl hover:bg-[#4a1719] shadow-md hover:shadow-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add New User
      </a>
    </div>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
        Total Accounts: <strong class="text-[#1a1a2e] text-sm"><?= $totalRows ?></strong>
      </span>
      <div class="relative">
        <input id="userSearch" type="text" placeholder="Search accounts..."
               class="px-3 py-1.5 pl-8 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5d1d1f]"/>
        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>

    <?php if (empty($users)): ?>
      <div class="py-16 text-center">
        <p class="text-slate-400 text-sm">No user accounts found.</p>
      </div>
    <?php else: ?>
    <div class="overflow-x-auto w-full">
      <table class="w-full text-sm min-w-[600px]">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">User</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Role</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Created</th>
            <th class="px-5 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
          </tr>
        </thead>
        <tbody id="userTableBody" class="divide-y divide-slate-50">
          <?php foreach ($users as $u): ?>
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-5 py-3.5 text-slate-400 font-mono text-xs"><?= (int)$u['id'] ?></td>
            <td class="px-5 py-3.5">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#5d1d1f]/10 text-[#5d1d1f] flex items-center justify-center font-black text-xs shrink-0">
                  <?= strtoupper(substr($u['username'], 0, 1)) ?>
                </div>
                <div>
                  <p class="font-bold text-[#1a1a2e] text-sm"><?= h($u['full_name']) ?></p>
                  <p class="text-xs text-slate-400">@<?= h($u['username']) ?></p>
                </div>
              </div>
            </td>
            <td class="px-5 py-3.5">
              <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?= $u['role'] === 'admin' ? 'bg-[#5d1d1f] text-[#efac3a]' : 'bg-slate-100 text-slate-600' ?>">
                <?= h($u['role']) ?>
              </span>
            </td>
            <td class="px-5 py-3.5 text-slate-400 text-xs whitespace-nowrap">
              <?= !empty($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '—' ?>
            </td>
            <td class="px-5 py-3.5 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-1.5">
                <a href="users.php?action=edit&id=<?= (int)$u['id'] ?>"
                   class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg transition-colors">
                  Edit
                </a>
                <?php if ($u['username'] !== $_SESSION['admin_user']): ?>
                <form method="POST"
                      onsubmit="return confirm('Permanently delete account for &quot;<?= h(addslashes($u['full_name'])) ?>&quot;?');"
                      class="inline">
                  <input type="hidden" name="act" value="delete"/>
                  <input type="hidden" name="id"  value="<?= (int)$u['id'] ?>"/>
                  <button type="submit" class="px-2.5 py-1 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition-colors">
                    Delete
                  </button>
                </form>
                <?php else: ?>
                <span class="px-2.5 py-1 text-slate-400 text-xs font-bold italic">Active</span>
                <?php endif; ?>
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
document.getElementById('userSearch')?.addEventListener('input', function() {
  const query = this.value.toLowerCase();
  document.querySelectorAll('#userTableBody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
  });
});
</script>
<?php endif; ?>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
