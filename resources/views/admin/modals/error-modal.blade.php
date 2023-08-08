<!--  Error  Modal -->
<div class="modal fade" id="errorModal" data-bs-backdrop="static"
     data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-5">
                <div class="row">
                    <div class="col-12 text-center ">
                        <img src="{{asset('assets/icons/trash.svg')}}"
                             class="img-fluid mb-1" alt="">
                        <h4 class="fw-600 my-2">Error</h4>
                            <p class="mb-0" id="showErrorResponseMsg">You want to delete this role?</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
