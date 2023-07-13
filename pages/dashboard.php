<style>
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}
.table-striped tbody tr:nth-of-type(even) {
    background-color: rgba(0,0,0,.05);
}
    .fa-pen{
        color:bisque;
    }
    .fa-trash{
        color:red;
    }
    .fa-file-excel{
        color:lime;
    }
    .fa-file-archive{
        color:orchid;
    }
    .fa-check-circle{
        color:palegreen;
    }
    .fa-copy{
        color:pink;
    }
    .fa-download{
        color:turquoise;
    }
</style>
<?php
    $query = "SELECT * FROM `users` WHERE `username` = '".make_safe($_SESSION['cname'])."'";
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);
?>
<?php
unset($_SESSION['error']);
?>
<style>
    .dataTables_filter { display : none; }
    .search {
        padding: 0.375rem 0.75rem;
                                        font-size: 1rem;
                                        line-height: 1.5;
                                        color: #495057;
                                        background-color: #fff;
                                        background-clip: padding-box;
                                        border: 1px solid #ced4da;
                                        border-radius: 0.25rem;
                                        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
</style>
<div class="container" style="max-width: 100%">
    <div class="row">
        <div class="col-sm-12 mt-3">
            <div class="d-flex align-items-center mb-5">
                <div class="feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 me-3"><i class="bi bi-upload"></i></div>
                <h1 class="fw-bolder mb-0"><span class="text-gradient d-inline">Image Uploaded List</span></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="w-100 mt-3">  
            <table id="myTable" class="table table-striped" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th class="th-sm">ID</th>  
                  <th class="th-sm">Uploaded</th>
                  <th class="th-sm">Filename</th>
                  <th class="th-sm">Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>

var tabel = null;
$(document).ready(function() {
    
    tabel = $('#myTable').DataTable({
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "ordering": true, // Set true agar bisa di sorting
        "order": [[ 0, 'desc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
        "ajax":
        {
            "url": "<?=root('do/image_list/'); ?>", // URL file untuk proses select datanya
            "type": "POST"
        },
        "deferRender": true,
        <?php if(isset($_GET['id'])){ ?>
        "initComplete": function(settings, json) {
            <?php if(isset($_GET['id'])){ ?>
                $('tbody tr:nth-of-type(1)').css('background-color', '#800000');
            <?php } ?>
        },
        <?php } ?>
        "columns": [
            { "data": "id" },
            { "data": "created", "render": function ( data, type, row, meta ) {
                return '<div style="text-align: center">'+data+'</div>';
              }
            },
            { "data": "filename" },
            { "data": "action", "render": function ( data, type, row, meta ) {
                return  '<div style="text-align: center">' + 
                          '<a class="dl" href="<?=root();?>uploads/'+row.filename+'" download><i class="fas fa-download" title="Download"></i></a>'+
                        '</div>';
              }
            }
        ],
    });
});
</script>