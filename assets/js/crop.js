
        $(document).ready(function() {
            var imgInp = document.getElementById('inputImage');
            var image = document.getElementById('image');
            var button = document.getElementById('button');
            var result = document.getElementById('result');
            var croppable = false;
            var cropper;

            imgInp.onchange = function(evt) {
                const [file] = imgInp.files;
                if (file) {
                    // Generate a unique timestamp for the image URL
                    var timestamp = Date.now();

                    image.src = URL.createObjectURL(file)+'#time'+timestamp;
                    var imgfile = $(this).val();
                    console.log('Image source changed to: ' + imgfile);
                    
                    // Create a new FormData object
                    var formData = new FormData();
                    formData.append('image', file); // Append the selected file
            
                    // Send AJAX request to save the image in the uploads directory
                    $.ajax({
                        type: 'POST',
                        url: '<?=root();?>do/upload_image/',
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
            
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        ready: function() {
                            croppable = true;
                        }
                    });
                    $(".preview").hide();
                }
            };

            button.onclick = function() {
                var croppedCanvas;
                var roundedCanvas;
                var roundedImage;

                if (!croppable) {
                    return;
                }

                // Crop
                croppedCanvas = cropper.getCroppedCanvas();

                // Round
                roundedCanvas = getRoundedCanvas(croppedCanvas);

                // Show
                roundedImage = document.createElement('img');
                var br = document.createElement('br');
                var check = document.createElement("INPUT");
                check.setAttribute("type", "checkbox");
                check.setAttribute("id", "savedCheckbox");
                var label = document.createElement("LABEL");
                var t = document.createTextNode(" Save Image");
                label.appendChild(t);
                
                roundedImage.src = roundedCanvas.toDataURL();
                result.innerHTML = '';
                result.appendChild(roundedImage);
                result.appendChild(br);
                result.appendChild(check);
                result.appendChild(label);
                
                var savedCheckbox = document.getElementById('savedCheckbox');

                savedCheckbox.addEventListener('change', function() {
                  if (this.checked) {
                    // Get the base64 data of the cropped image
                    var imageData = roundedCanvas.toDataURL('image/png');
            
                    // Before sending the AJAX request to save the cropped image
                    // Show a "Please wait" notification
                    toastr.info('Please wait while the image is being saved...', { timeOut: 0 });
                    
                    // Send AJAX request to save the cropped image
                    $.ajax({
                        type: 'POST',
                        url: '<?=root();?>do/crop_image/',
                        data: {
                            image: imageData
                        },
                        success: function(response) {
                            console.log('Cropped image saved:', response);
                            // Hide the "Please wait" notification
                            toastr.clear();
                    
                            // Show success notification
                            toastr.success('Cropped image saved successfully!');
                    
                            // Create the download button
                            // ...
                        },
                        error: function() {
                            console.log('Error saving the cropped image.');
                            // Hide the "Please wait" notification
                            toastr.clear();
                    
                            // Show error notification
                            toastr.error('Error saving the cropped image.');
                        }
                    });
                    }
                });
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
        });