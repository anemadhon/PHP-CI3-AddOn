<!-- Theme JS files -->
<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/visualization/d3/d3.min.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/forms/styling/switchery.min.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/pickers/daterangepicker.js"></script>


<script src="<?php echo base_url('/files/');?>assets/js/app.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/demo_pages/dashboard.js"></script>
<script src="<?php echo base_url('/files/');?>global_assets/js/demo_pages/form_multiselect.js"></script>
<!-- /theme JS files -->

<!-- dataTable -->
<script src="<?php echo base_url('/files/');?>global_assets/js/dataTable/jquery.dataTables.min.js"></script>
<!-- /dataTable-->

<script src="<?php echo base_url('/files/');?>global_assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>


<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- /SweetAlert -->

<script>
// onclick modal update profile in nav
updateConfirm = ()=>{
    $('#openMdlChangePW').modal();
}

$("#btn-update-password").click(function(){

    $('#btn-update-password').prop('disabled',true);
    $('#btn-update-password').html('loading..');

    let password = $("#f_password_nav").val();
	let konfirmasi_password = $("#f_confpassword_nav").val();

    if(password != konfirmasi_password) {
        alert("Periksa inputan, password tidak matching");
        $('#btn-update-password').prop('disabled',false);
        $('#btn-update-password').html('Ubah');
        return;
    }

    $.post("<?php echo site_url('login/forgotPassword'); ?>",{
            password: password,
            conf_password: konfirmasi_password
        },
        (res)=>{      
            let r = JSON.parse(res);

            if(r.success) {
                
                $('#errMsgSuccessNav').show();
                $('#errMsgSuccessNav').html(r.message);

                $('#btn-update-password').prop('disabled',false);
                $('#btn-update-password').html('Ubah');
                
                setTimeout(function(){ 
                    $('#openMdlChangePW').modal('hide')
                }, 750);
                

            } else {
                
                $('#errMsgFailNav').show();
                $('#errMsgFailNav').html(r.message);

                $('#btn-update-password').prop('disabled',false);
                $('#btn-update-password').html('Ubah');
                
            }
            
        }
    )

})


$(function(){
    var current = location.pathname;
    $('#nav li a').each(function(){
        
        const $this = $(this);
        if($this.attr('href').indexOf(current) !== -1){
            $this.parents('li.nav-item-submenu').addClass('nav-item-open');
            $this.parents('ul.nav-group-sub').css("display","block");
            $this.addClass('active');
        }
    });

    
}

)
</script>