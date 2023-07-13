  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <link rel="stylesheet" href="<?=root();?>assets/css/cropper.css">
  <link rel="stylesheet" href="<?=root();?>assets/css/main.css">
<style>
    img {
        max-width:100%;
    }
</style>
<div class="container">
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
                           <!-- <h3>Demo:</h3> -->
                                <img src="../preview.png" alt="Picture" id="image">
                              <div class="docs-data">
                                <input type="hidden" class="form-control" id="dataX" placeholder="x">
                                <input type="hidden" class="form-control" id="dataY" placeholder="y">
                                <input type="hidden" class="form-control" id="dataWidth" placeholder="width">
                                <input type="hidden" class="form-control" id="dataHeight" placeholder="height">
                                <input type="hidden" class="form-control" id="dataRotate" placeholder="rotate">
                                <input type="hidden" class="form-control" id="dataScaleX" placeholder="scaleX">
                                <input type="hidden" class="form-control" id="dataScaleY" placeholder="scaleY">
                            </div>
                              <br/>
                              <div class="row" id="actions">
                                        <div class="col-md-12 docs-buttons">
                                        <!--
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
                                            -->
                                            <div class="btn-group">
                                              <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                                                <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
                                                  <span class="fa fa-upload"></span> Upload
                                                </span>
                                              </label>
                                        </div>
                                        
                                        <div class="btn-group">
                                              <button type="button" class="btn btn-primary" id="btnround">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Crop">
                                                  <span class="fa fa-circle"></span> Round Crop
                                                </span>
                                              </button>
                                        </div>
                                        
                                        <div class="btn-group">
                                              <button type="button" class="btn btn-primary" id="btnrect">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Crop">
                                                  <span class="fa fa-crop"></span> Rectangle Crop
                                                </span>
                                              </button>
                                        </div>
                                        
                                        <div class="btn-group">
                                              <button type="button" class="btn btn-primary" id="cropround" data-method="getRoundedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Crop">
                                                  <span class="fa fa-check"></span> Crop
                                                </span>
                                              </button>
                                        </div>
                                        
                                        <div class="btn-group">
                                              <button type="button" class="btn btn-primary" id="croprect" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
                                                <span class="docs-tooltip" data-toggle="tooltip" title="Crop">
                                                  <span class="fa fa-check"></span> Crop
                                                </span>
                                              </button>
                                        </div>
                                    </div><!-- /.docs-buttons -->
                                    <div id="result">
                                        <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg" style="display:none">Download</a>
                                    </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
      </div>
    </div>
</div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="<?=root();?>assets/js/cropper.js"></script>
  <script src="<?=root();?>assets/js/main.js"></script>
  <script>
        $(document).ready(function() {
            $("#cropround, #croprect").hide();
            
            $( "#btnround" ).click(function() {     
               $(".cropper-view-box, .cropper-face").css("border-radius","50%");
               $("#cropround").show();
               $("#croprect").hide();
            });
            
            $( "#btnrect" ).click(function() {     
               $(".cropper-view-box, .cropper-face").css("border-radius","");
               $("#croprect").show();
               $("#cropround").hide();
            });
            
        });
  </script>
