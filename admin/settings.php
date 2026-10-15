<?php
// ═══════════════════════════════════════════════
//  Admin — Settings Management
// ═══════════════════════════════════════════════
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../config/config.php';

// Restricted to Admin Only
check_admin_only();

$adminTitle      = 'Site Settings';
$adminActivePage = 'settings';

// ── Handle Updates ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    foreach ($_POST['s'] as $key => $value) {
        $key_s   = db_escape($key, $conn);
        $value_s = db_escape($value, $conn);
        mysqli_query($conn, "UPDATE `settings` SET `setting_value`='$value_s' WHERE `setting_key`='$key_s'");
    }
    // Refresh local cache/globals
    header('Location: settings.php?msg=updated'); exit;
}

// Fetch all settings grouped by category
$categories = [];
$res = mysqli_query($conn, "SELECT * FROM `settings` ORDER BY `category` ASC, `setting_key` ASC");
while ($r = mysqli_fetch_assoc($res)) {
    $categories[$r['category']][] = $r;
}

require_once __DIR__ . '/_layout_head.php';
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-[#1a1a2e]">Global Settings</h2>
            <p class="text-slate-500 text-sm">Manage company contact info, social links, and website assets.</p>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold rounded-2xl flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        Site settings updated successfully and are now live.
    </div>
    <?php endif; ?>

    <form method="POST">
        <div class="space-y-8">
            <?php foreach ($categories as $cat => $items): ?>
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest"><?= h($cat) ?> Config</h3>
                    <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-400"><?= count($items) ?> keys</span>
                </div>
                <div class="p-8 space-y-6">
                    <?php foreach ($items as $item): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-6 border-b border-slate-50 last:border-0 last:pb-0">
                        <div class="lg:pt-2">
                            <label class="block text-[13px] font-black text-[#1a1a2e] mb-1"><?= slug_to_title($item['setting_key']) ?></label>
                            <p class="text-[11px] text-slate-400 italic"><?= h($item['description']) ?></p>
                        </div>
                        <div class="lg:col-span-2">
                            <?php if (strpos($item['setting_key'], 'description') !== false || strpos($item['setting_key'], 'address') !== false): ?>
                                <textarea name="s[<?= h($item['setting_key']) ?>]" rows="3"
                                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/30 focus:border-[#5d1d1f] transition-all"><?= h($item['setting_value']) ?></textarea>
                            <?php else: ?>
                                <input name="s[<?= h($item['setting_key']) ?>]" type="text" value="<?= h($item['setting_value']) ?>"
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#5d1d1f]/30 focus:border-[#5d1d1f] transition-all"/>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-10 flex items-center gap-4">
            <button type="submit" name="save_settings"
                    class="px-10 py-4 bg-[#5d1d1f] text-white font-black rounded-2xl shadow-xl shadow-[#5d1d1f]/30 hover:bg-[#4a1517] hover:scale-[1.02] transition-all border border-[#efac3a]/20 text-[#efac3a]">
                Save Changes & Apply
            </button>
            <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-[#5d1d1f] transition-colors">Discard changes</a>
        </div>
    </form>
</div>

<?php
mysqli_close($conn);
require_once __DIR__ . '/_layout_foot.php';
?>
