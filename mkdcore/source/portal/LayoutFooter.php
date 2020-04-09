    </div>
</div>
<?php if (!$layout_clean_mode) { ?>
<div id="snackbar">xyzSaved</div>
<div class="modal fade" id="mkd-media-gallery" tabindex="-1" role="dialog" aria-labelledby="media-gallery" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="media-gallery">xyzMedia Gallery</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid" id="mkd-media-gallery-container" style="height: 500px;overflow-y: scroll;">
            <div class="row" id="mkd-media-gallery-wrapper">

            </div>
            <div class="text-center" id="mkd-load-more-container">
                <button class="btn btn-primary" id="mkd-load-more">xyzLoad More</button>
            </div>
          </div>
        <div class="container-fluid" id="mkd-media-upload-container">
            <div class="row" id="mkd-media-upload-wrapper">
              <div class="mkd-upload-btn-wrapper">
                <button class="mkd-upload-btn">xyzUpload a file</button>
                <input type="file" name="imagefile" onchange="onFileSelected(event)"/>
              </div>
            </div>
        </div>
        <div class="container-fluid" id="mkd-media-crop-container">
            <div class="row" id="mkd-media-crop-wrapper">
              <div id="mkd-crop-upload-container-wrapper">
                <div id="mkd-crop-upload-container">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer mkd-media-panel-1">
          <button type="button" class="btn btn-primary" id="mkd-media-upload">xyzUpload</button>
          <button type="button" class="btn btn-dark" id="mkd-media-choose">xyzChoose</button>
          <button type="button" class="btn btn-warning mkd-close-modal" data-dismiss="modal">xyzClose</button>
        </div>
        <div class="modal-footer mkd-media-panel-2">
          <button type="button" class="btn btn-warning mkd-close-modal" data-dismiss="modal">xyzClose</button>
        </div>
        <div class="modal-footer mkd-media-panel-3">
          <button type="button" class="btn btn-primary js-crop" id="mkd-media-crop">xyzCrop & Upload</button>
          <button type="button" class="btn btn-warning mkd-close-modal" data-dismiss="modal">xyzClose</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!-- Our JS -->
{{{js}}}
</body>

</html>