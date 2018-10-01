{{-- Toast Message--}}
<div id="snackbar"></div>

@if(Session::has('flash_message'))
    <script>
        Toast('{{ Session::get('flash_message') }}')
    </script>
@endif
@if(Session::has('flash_error'))
    <script>
        Toast('{{ Session::get('flash_error') }}')
    </script>
@endif

@if(Session::has('flash_popup'))
    <script>
        $(function(){
            $(".main_cancel_popup").modal('show');
        });
    </script>
    <div class="modal fade bs-example-modal-sm main_cancel_popup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm background_white center_txt_wrap">
            <div class="modal-header cancel_pop_header">
                <button type="button" class="close cancel_x" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="mySmallModalLabel">{{ Session::get('flash_popup.title') }}</h4>
            </div>
            <div class="modal-body">
                <p>{!! Session::get('flash_popup.msg') !!}</p>
            </div>
            <div class="modal_footer">
                <div class="ok_button_wrap_ver02">
                    <button type="button" id="canBtn" class="btn btn-brown waves-effect w-md waves-light m-b-5" data-dismiss="modal" aria-hidden="true" >확인</button>
                    <button type="button" id="comBtn" class="btn btn-orange  waves-effect w-md waves-light m-b-5" onclick="location.href='{{ Session::get('flash_popup.url') }}';">이동</button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endif