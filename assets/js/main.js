window.onload = function () {
  'use strict';

  var Cropper = window.Cropper;
  var URL = window.URL || window.webkitURL;
  //var container = document.querySelector('.img-container');
  //var image = container.getElementsByTagName('img').item(0);
  var image = document.getElementById('image');
  var download = document.getElementById('download');
  var actions = document.getElementById('actions');
  var dataX = document.getElementById('dataX');
  var dataY = document.getElementById('dataY');
  var dataHeight = document.getElementById('dataHeight');
  var dataWidth = document.getElementById('dataWidth');
  var dataRotate = document.getElementById('dataRotate');
  var dataScaleX = document.getElementById('dataScaleX');
  var dataScaleY = document.getElementById('dataScaleY');
  var options = {
    aspectRatio: 16 / 9,
    preview: '.img-preview',
    ready: function (e) {
      console.log(e.type);
    },
    cropstart: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropmove: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropend: function (e) {
      console.log(e.type, e.detail.action);
    },
    crop: function (e) {
      var data = e.detail;

      console.log(e.type);
      dataX.value = Math.round(data.x);
      dataY.value = Math.round(data.y);
      dataHeight.value = Math.round(data.height);
      dataWidth.value = Math.round(data.width);
      dataRotate.value = typeof data.rotate !== 'undefined' ? data.rotate : '';
      dataScaleX.value = typeof data.scaleX !== 'undefined' ? data.scaleX : '';
      dataScaleY.value = typeof data.scaleY !== 'undefined' ? data.scaleY : '';
    },
    zoom: function (e) {
      console.log(e.type, e.detail.ratio);
    }
  };
  var cropper = new Cropper(image, options);
  var originalImageURL = image.src;
  var uploadedImageType = 'image/png';
  var uploadedImageName = 'cropped.png';
  var uploadedImageURL;

  // Tooltip
  $('[data-toggle="tooltip"]').tooltip();

  // Buttons
  if (!document.createElement('canvas').getContext) {
    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
  }

  if (typeof document.createElement('cropper').style.transition === 'undefined') {
    $('button[data-method="rotate"]').prop('disabled', true);
    $('button[data-method="scale"]').prop('disabled', true);
  }

  // Download
  if (typeof download.download === 'undefined') {
    download.className += ' disabled';
    download.title = 'Your browser does not support download';
  }

  // Options
  /*actions.querySelector('.docs-toggles').onchange = function (event) {
    var e = event || window.event;
    var target = e.target || e.srcElement;
    var cropBoxData;
    var canvasData;
    var isCheckbox;
    var isRadio;

    if (!cropper) {
      return;
    }

    if (target.tagName.toLowerCase() === 'label') {
      target = target.querySelector('input');
    }

    isCheckbox = target.type === 'checkbox';
    isRadio = target.type === 'radio';

    if (isCheckbox || isRadio) {
      if (isCheckbox) {
        options[target.name] = target.checked;
        cropBoxData = cropper.getCropBoxData();
        canvasData = cropper.getCanvasData();

        options.ready = function () {
          console.log('ready');
          cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
        };
      } else {
        options[target.name] = target.value;
        options.ready = function () {
          console.log('ready');
        };
      }

      // Restart
      cropper.destroy();
      cropper = new Cropper(image, options);
    }
  };
*/
  // Methods
  actions.querySelector('.docs-buttons').onclick = function (event) {
    var e = event || window.event;
    var target = e.target || e.srcElement;
    var cropped;
    var result;
    var input;
    var data;

    if (!cropper) {
      return;
    }

    while (target !== this) {
      if (target.getAttribute('data-method')) {
        break;
      }

      target = target.parentNode;
    }

    if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
      return;
    }

    data = {
      method: target.getAttribute('data-method'),
      target: target.getAttribute('data-target'),
      option: target.getAttribute('data-option') || undefined,
      secondOption: target.getAttribute('data-second-option') || undefined
    };

    cropped = cropper.cropped;

    if (data.method) {
      if (typeof data.target !== 'undefined') {
        input = document.querySelector(data.target);

        if (!target.hasAttribute('data-option') && data.target && input) {
          try {
            data.option = JSON.parse(input.value);
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            cropper.clear();
          }

          break;

        case 'getCroppedCanvas':
          try {
            data.option = JSON.parse(data.option);
          } catch (e) {
            console.log(e.message);
          }

          if (uploadedImageType === 'image/png') {
            if (!data.option) {
              data.option = {};
            }

            data.option.fillColor = '#fff';
          }

          break;
      }

      result = cropper[data.method](data.option, data.secondOption);

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            cropper.crop();
          }

          break;

        case 'scaleX':
        case 'scaleY':
          target.setAttribute('data-option', -data.option);
          break;

        case 'getCroppedCanvas':
          cropper.crop();
          var divres = document.getElementById('result');
          if (result) {
            // Bootstrap's Modal
            //$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
            
            var br = document.createElement('br');
            var check = document.createElement("INPUT");
            check.setAttribute("type", "checkbox");
            check.setAttribute("id", "savedCheckbox");
            var label = document.createElement("LABEL");
            var t = document.createTextNode(" Save Image");
            label.appendChild(t);
            
            divres.innerHTML = '';
            divres.appendChild(result);
            divres.appendChild(br);
            divres.appendChild(check);
            divres.appendChild(label);
            
            var savedCheckbox = document.getElementById('savedCheckbox');

            savedCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    var imageData = result.toDataURL('image/png');
                    toastr.info('Please wait while the image is being saved...', { timeOut: 0 });
                    $.ajax({
                        type: 'POST',
                        url: baseUrl+'/do/crop_image/',
                        data: {
                            image: imageData
                        },
                        success: function(response) {
                            console.log('Cropped image saved:', response);
                            toastr.clear();
                            toastr.success('Cropped image saved successfully!');
                            // Create the download button
                            var downloadLink = document.createElement('a');
                            downloadLink.href = baseUrl+'/cropped/' + response; // Set the path to the saved image
                            downloadLink.download = response; // Set the filename for download
                            downloadLink.innerHTML = '&nbsp|&nbsp;Download';
                        
                            // Append the download button to the result container
                            divres.appendChild(downloadLink);
                        },
                        error: function() {
                            console.log('Error saving the cropped image.');
                            toastr.clear();
                            toastr.error('Error saving the cropped image.');
                        }
                    });
                }
            });

            if (!download.disabled) {
              download.download = uploadedImageName;
              download.href = result.toDataURL(uploadedImageType);
            }
          }

          break;
          
        case 'getRoundedCanvas':
            var divress = document.getElementById('result');
            var croppedCanvas;
            var roundedCanvas;
            var roundedImage;

          if (result) {
            if (!cropper) {
                return;
            }
                //crop
                cropper.crop();
                
                //Get Result
                croppedCanvas = cropper.getCroppedCanvas();

                // Rounded the Result
                roundedCanvas = getRoundedCanvas(croppedCanvas);
            
                // Show
                roundedImage = document.createElement('img');
                
                roundedImage.src = roundedCanvas.toDataURL();
                divress.innerHTML = '';
                divress.appendChild(roundedImage);
                
                var enter = document.createElement('br');
                var checkbox = document.createElement("INPUT");
                checkbox.setAttribute("type", "checkbox");
                checkbox.setAttribute("id", "savedCheckbox");
                var labelsave = document.createElement("LABEL");
                var textsave = document.createTextNode(" Save Image");
                labelsave.appendChild(textsave);
                
                divress.appendChild(enter);
                divress.appendChild(checkbox);
                divress.appendChild(labelsave);
            
                var savedCheck = document.getElementById('savedCheckbox');

                savedCheck.addEventListener('change', function() {
                if (this.checked) {
                    var imageData = roundedCanvas.toDataURL('image/png');
                    toastr.info('Please wait while the image is being saved...', { timeOut: 0 });
                    $.ajax({
                        type: 'POST',
                        url: baseUrl+'/do/crop_image/',
                        data: {
                            image: imageData
                        },
                        success: function(response) {
                            console.log('Cropped image saved:', response);
                            toastr.clear();
                            toastr.success('Cropped image saved successfully!');
                            // Create the download button
                            var downloadLink = document.createElement('a');
                            downloadLink.href = baseUrl+'/cropped/' + response; // Set the path to the saved image
                            downloadLink.download = response; // Set the filename for download
                            downloadLink.innerHTML = '&nbsp|&nbsp;Download';
                        
                            // Append the download button to the result container
                            divress.appendChild(downloadLink);
                        },
                        error: function() {
                            console.log('Error saving the cropped image.');
                            toastr.clear();
                            toastr.error('Error saving the cropped image.');
                        }
                    });
                }
            });

                
                
            if (!download.disabled) {
              download.download = uploadedImageName;
              download.href = result.toDataURL(uploadedImageType);
            }
          }

          break;

        case 'destroy':
          cropper = null;

          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
            uploadedImageURL = '';
            image.src = originalImageURL;
          }

          break;
      }

      if (typeof result === 'object' && result !== cropper && input) {
        try {
          input.value = JSON.stringify(result);
        } catch (e) {
          console.log(e.message);
        }
      }
    }
  };

   function getRoundedCanvas(sourceCanvas) {
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                var width = sourceCanvas.width;
                var height = sourceCanvas.height;

                canvas.width = width;
                canvas.height = height;
                context.imageSmoothingEnabled = true;
                context.drawImage(sourceCanvas, 0, 0, width, height);

                context.globalCompositeOperation = 'destination-in';
                context.beginPath();
                context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI);
                context.fill();

                return canvas;
            }
            
  document.body.onkeydown = function (event) {
    var e = event || window.event;

    if (e.target !== this || !cropper || this.scrollTop > 300) {
      return;
    }

    switch (e.keyCode) {
      case 37:
        e.preventDefault();
        cropper.move(-1, 0);
        break;

      case 38:
        e.preventDefault();
        cropper.move(0, -1);
        break;

      case 39:
        e.preventDefault();
        cropper.move(1, 0);
        break;

      case 40:
        e.preventDefault();
        cropper.move(0, 1);
        break;
    }
  };

   
  //base_url
  var getUrl = window.location;
  var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
  var croppable = false;
  var croppering;
  
  // Import image
  var inputImage = document.getElementById('inputImage');

  if (URL) {
    inputImage.onchange = function () {
      var files = this.files;
      var file;

      if (files && files.length) {
        file = files[0];

        if (/^image\/\w+/.test(file.type)) {
          uploadedImageType = file.type;
          uploadedImageName = file.name;

          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
          }

          image.src = uploadedImageURL = URL.createObjectURL(file);
          console.log('Image source changed to: ' + file.name);
                    // Create a new FormData object
                    var formData = new FormData();
                    formData.append('image', file); // Append the selected file
            
                    // Send AJAX request to save the image in the uploads directory
                    $.ajax({
                        type: 'POST',
                        url: baseUrl+'/do/upload_image/',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log('Uploaded image saved:', response);
                        },
                        error: function() {
                            console.log('Error saving the uploaded image.');
                            // Show error notification
                            toastr.error('Error saving the uploaded image.');
                        }
                    });
                    
                    croppering = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        ready: function() {
                            croppable = true;
                        }
                    });
          
          if (cropper) {
            cropper.destroy();
          }

          cropper = new Cropper(image, options);
          inputImage.value = null;
        } else {
          window.alert('Please choose an image file.');
        }
      }
    };
  } else {
    inputImage.disabled = true;
    inputImage.parentNode.className += ' disabled';
  }
};
