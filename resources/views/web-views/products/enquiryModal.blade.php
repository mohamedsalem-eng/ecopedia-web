<div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $product->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('service.enquiry') }}" method="post" id="submit_enquiry"
                    enctype="multipart/form-data">
                    @csrf
                    <textarea name="description" cols="40" rows="5" class="form-control" placeholder="{{ \App\CPU\translate('notes') }}"
                        style="resize: none;"></textarea>
                    <br>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input"
                        accept="application/zip,application/x-7z-compressed,application/vnd.rar"
                        name="enquiry_file" id="enquiry_file_input">
                        <label class="custom-file-label"
                            for="enquiry_file_input">{{ \App\CPU\translate('choose_file') }}</label>
                    </div>
                    <br>
                    <input type="hidden" name="product_id" value="{{$product->id}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ \App\CPU\translate('close') }}</button>
                <button type="submit" form="submit_enquiry"
                    class="btn btn-primary">{{ \App\CPU\translate('send') }}</button>
            </div>
        </div>
    </div>
</div>
