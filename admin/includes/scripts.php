<script src="js/vendor.js"></script>
<script src="js/app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="js/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    console.log("jQuery version:", $.fn.jquery); // Should log the jQuery version
    console.log("Is Select2 loaded?", typeof $.fn.select2 !== 'undefined');
    function loadlink(){
        var id = 1;
        $('#dashboard').html('<p class="text-center">Syncning...</p>');
        $.ajax({
          url: "includes/dashboardSync.php"
        }).done(function(data) {
          $('#dashboard').html(data); 
        });
    }
    
    loadlink(); 

    setInterval(function(){
        loadlink();
    }, 60000);

  
    $('#example').DataTable({
        "order": [[ 5, "asc" ]],
        stateSave: true,
        "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    } );
    
    window.loadData = function (id){
        $('#formData').html('<p class="text-center">Loading...</p>');
        $.ajax({
          url: "includes/loadVehicleData.php?info="+ id + ""
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }
    window.loadExpense = function (id){
        $('#formData').html('<p class="text-center">Loading...</p>');
        $.ajax({
          url: "includes/load_expense_data.php?info="+ id + ""
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }

    window.loadStaff = function (id){
        $('#formData').html('<p class="text-center">Loading...</p>');
        $.ajax({
          url: "includes/load_staff_data.php?info="+ id + ""
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }

    window.loadService = function (id){
        $('#formData').html('<p class="text-center">Loading...</p>');
        $.ajax({
          url: "includes/load_service_data.php?info="+ id + ""
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }

    window.loadPayments = function (id){
        $('#formData').html('<p class="text-center">Loading...</p>');
        $.ajax({
          url: "includes/load_payments_data.php?info="+ id + ""
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }

    window.loadPayment = function (id, pay=true){
        $('#formData').html('<p class="text-center">Loading...</p>');
        const url = pay? "includes/payment_form.php?pay="+ id + "" : "includes/payment_form.php?update="+ id + ""; 
        $.ajax({
          url: url
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }

    window.loadClient = function (id){
        $('#formData').html('<p class="text-center">Loading...</p>');
        $.ajax({
          url: "includes/load_client_data.php?info="+ id + ""
        }).done(function(data) {
          $('#formData').html(data); 
        });
    }

    $('select').select2();
});

</script>