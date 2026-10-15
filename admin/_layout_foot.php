  </main><!-- /page content -->

  <footer class="px-4 sm:px-8 py-4 border-t border-slate-200 bg-white">
    <p class="text-xs text-slate-400">&copy; <?= date('Y') ?> I Tech IT Solutions &mdash; Admin Panel</p>
  </footer>

</div><!-- /main wrapper -->

<script>
function toggleSidebar() {
  const sb = document.getElementById('adminSidebar');
  const bd = document.getElementById('sidebarBackdrop');
  if (sb && bd) {
    const isClosed = sb.classList.contains('-translate-x-full');
    if (isClosed) {
      sb.classList.remove('-translate-x-full');
      bd.classList.remove('hidden');
    } else {
      sb.classList.add('-translate-x-full');
      bd.classList.add('hidden');
    }
  }
}
</script>

</body>
</html>
