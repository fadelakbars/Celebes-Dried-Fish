    </div> <!-- End of content-body -->
</main> <!-- End of main-content -->
</div> <!-- End of admin-layout -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('adminSidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('sidebar-active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('sidebar-active')) {
                if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                    sidebar.classList.remove('sidebar-active');
                }
            }
        });
        
        // Prevent clicks inside sidebar from closing it
        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
</body>
</html>
