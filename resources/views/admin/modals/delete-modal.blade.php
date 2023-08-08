<div class="modal fade" id="deleteModal" data-bs-backdrop="static"
     data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                {{--                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center ">
                        <img src="{{asset('assets/icons/trash.svg')}}"
                             class="img-fluid mb-1" alt="">
                        <h4 class="fw-600 my-2">Are You Sure</h4>
                        <p class="mb-0" id="delete_heading">You want to delete this?</p>
                    </div>
                </div>

                <div
                    class="modal-footer border-0 mx-auto justify-content-center">
                    <button type="button"
                            class="btn btn-light min-w-140 btn-lg rounded-2 fs-14" id="deleteCancelBtn"
                            data-bs-dismiss="modal">Cancel
                    </button>
                    <form id="deleteForm" onsubmit="return false;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="inputDeleteID">
                        <button type="submit"
                                id="deleteBtn"
                                class="btn btn-danger text-white min-w-140 btn-lg rounded-2 ms-2 fs-14 ">
                            Delete
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
