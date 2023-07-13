<style>
.img-container {
    max-height: 497px;
    min-height: 200px;
}
.img-container, .img-preview {
    background-color: #f7f7f7;
    text-align: center;
    width: 100%;
}

.preview{
    width: 100%;
}
</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />-->
    <script src="<?=root();?>assets/js/crop.js"></script>
    <script src="<?=root();?>assets/js/cropper.js"></script>
    <script src="<?=root();?>assets/js/main.js"></script>
    <link rel="stylesheet" href="<?=root();?>assets/css/cropper.css">
    <link rel="stylesheet" href="<?=root();?>assets/css/main.css">
<div class="container" style="max-width: 100%">
    <div class="row gx-5 justify-content-center">
        <div class="col-sm-12 mt-3">
        <section>
            <div class="d-flex align-items-center mb-5">
                <h2 class="text-secondary fw-bolder mb-0">Upload your image here</h2>
            </div>
            <div class="card shadow border-0 rounded-4 mb-5">
                <div class="card-body p-5">
                    <div class="row align-items-center gx-5">
                        <div class="col text-center text-lg-start mb-4 mb-lg-0">
                                    <form runat="server" onsubmit="return false;">
                                         <div class="img-container">
                                            <img id="image" src="../preview.png" alt="Picture">
                                          </div>
                                    <br>
                                    <div class="row" id="actions">
                                        <div class="col-md-12 docs-buttons">
                                            <!-- <h3>Toolbar:</h3> -->
                                           
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Zoom In">
                                                  <span class="fa fa-search-plus"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Zoom Out">
                                                  <span class="fa fa-search-minus"></span>
                                                </span>
                                              </button>
                                            </div>
                                    
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Move Left">
                                                  <span class="fa fa-arrow-left"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Move Right">
                                                  <span class="fa fa-arrow-right"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Move Up">
                                                  <span class="fa fa-arrow-up"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Move Down">
                                                  <span class="fa fa-arrow-down"></span>
                                                </span>
                                              </button>
                                            </div>
                                    
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Rotate Left">
                                                  <span class="fa fa-undo-alt"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Rotate Right">
                                                  <span class="fa fa-redo-alt"></span>
                                                </span>
                                              </button>
                                            </div>
                                    
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Flip Horizontal">
                                                  <span class="fa fa-arrows-alt-h"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Flip Vertical">
                                                  <span class="fa fa-arrows-alt-v"></span>
                                                </span>
                                              </button>
                                            </div>
                                    
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-primary" data-method="rounded" title="crop" onclick="rounded();">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Rounded">
                                                  <span class="fa fa-crop"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="rectangle" title="crop" onclick="rectangle();">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Rectangle">
                                                  <span class="fa fa-crop"></span>
                                                </span>
                                              </button>
                                              <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Reset">
                                                  <span class="fa fa-sync-alt"></span>
                                                </span>
                                              </button>
                                              <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                                                <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Upload">
                                                  <span class="fa fa-upload"></span>
                                                </span>
                                              </label>
                                               <!--<button type="button" class="btn btn-primary" data-method="crop" title="Crop">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
                                                  <span class="fa fa-check"></span>
                                                </span>
                                               </button>-->
                                            </div>
                                        </div><!-- /.docs-buttons -->
                                        <button class="btn btn-primary" id="button"><span class="fa fa-check"></span> Crop</button>
                                        <div id="result"></div>
                                    </div>
                                    </form>
                                    <br>
                                    
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </div>
</div>