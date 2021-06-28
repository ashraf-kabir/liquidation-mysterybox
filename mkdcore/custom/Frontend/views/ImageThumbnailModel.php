<div class="modal fade" id="mkd-media-image-thumbnail" tabindex="-1" role="dialog" aria-labelledby="media-gallery" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="media-gallery">Media Gallery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="mkd-media-gallery-container" style="height: 500px;overflow-y: scroll;">
                    <div class="row h-100" id="mkd-media-gallery-wrapper" style="overflow-y: scroll;"></div> 
                </div>
                <div class="container-fluid" id="mkd-media-upload-container">
                    <div class="row" id="mkd-media-upload-wrapper">
                        <div class="mkd-upload-btn-wrapper">
                            <button class="mkd-upload-btn">Upload A File</button>
                            <input type="file" name="imagefile" onchange="onFileSelected(event)"/>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="mkd-media-crop-container">
                    <div class="row" id="mkd-media-crop-wrapper">
                        <div id="mkd-crop-upload-container-wrapper">
                            <div id="mkd-crop-upload-container"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer mkd-media-panel-1">
                <button type="button" class="btn btn-primary" id="mkd-thumbnail-upload">Upload</button> 
                <button type="button" class="btn btn-warning mkd-close-modal" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-footer mkd-media-panel-2">
                <button type="button" class="btn btn-warning mkd-close-modal" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-footer mkd-media-panel-3">
                <button type="button" class="btn btn-primary js-crop" id="mkd-media-crop">Crop & Upload</button>
                <button type="button" class="btn btn-warning mkd-close-modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>