    // 1 có những tính năng mới
    // 2 giảm bớt 1 số tính năng
    // 3 4 default
    $(function() {
        $("#example1").DataTable({
            "ordering": true,
            "paging": true,
            "responsive": true,
            "lengthChange": true, // Chọn độ dài hiển thị
            "autoWidth": false,
            "buttons": ["copy", "csv", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#example3').DataTable();
        $('#example4').DataTable();
    });
  