<!-- Modal -->
<script>
    $(".confirm-delete").on( "click", function() {
        $('#confirm').modal('show'); // Show the Modal
        $('#confirm-title').text($(this).data("title")); // Add title
        $('#confirm-body').text($(this).data("body")); // Add body text
        $("#confirm-form").empty(); // Empty the container of any previous forms
        $('#'+$(this).data("form-id")).clone().appendTo("#confirm-form").removeClass('d-none'); // Add form and show the form
    });
</script>
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="confirm-body" style="color:#990000;font-weight:bold">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <span id="confirm-form"></span>
            </div>
        </div>
    </div>
</div>
