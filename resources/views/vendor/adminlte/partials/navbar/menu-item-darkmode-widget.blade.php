<li class="nav-item">
    <a class="nav-link" href="#" role="button" id="btn-dark-mode-toggle" title="Alternar modo escuro/claro">
        <i class="fas fa-moon"></i>
    </a>
</li>
@once
@push('js')
<script>
(function () {
    var btn = document.getElementById('btn-dark-mode-toggle');
    if (!btn) return;
    function syncIcon() {
        btn.querySelector('i').className = document.body.classList.contains('dark-mode')
            ? 'fas fa-sun' : 'fas fa-moon';
    }
    syncIcon();
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        var isDark = document.body.classList.toggle('dark-mode');
        localStorage.setItem('pmpr_dark_mode', isDark ? '1' : '0');
        syncIcon();
    });
})();
</script>
@endpush
@endonce
