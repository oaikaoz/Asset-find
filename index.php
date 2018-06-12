<!DOCTYPE html>
<html lang="en">
<head>
  <title>Find Asset</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</head>
<script type="text/javascript" src="js/quagga.min.js"></script>
<script src="js/superplaceholder.js"></script> 
<link href="https://fonts.googleapis.com/css?family=Kanit:300" rel="stylesheet">
<style>
	#interactive.viewport {position: relative; width: 100%; height: auto; overflow: hidden; text-align: center;}
	#interactive.viewport > canvas, #interactive.viewport > video {max-width: 100%;width: 100%;}
	canvas.drawing, canvas.drawingBuffer {position: absolute; left: 0; top: 0;}
      .head{
		padding-left: 25px;
		font-size: 16px;
		color:#515050;
      }
      .detail{ 
		font-size: 14px; 
		color:#cc5128;
      }
		.lds-ripple {
			display: inline-block;
			position: relative;
			width: 64px;
			height: 64px;
			}
		.lds-ripple div {
			position: absolute;
			border: 4px solid #fff;
			opacity: 1;
			border-radius: 50%;
			animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
			}
		.lds-ripple div:nth-child(2) {
			animation-delay: -0.5s;
			}
			@keyframes lds-ripple {
			0% {
				top: 28px;
				left: 28px;
				width: 0;
				height: 0;
				opacity: 1;
			}
			100% {
				top: -1px;
				left: -1px;
				width: 58px;
				height: 58px;
				opacity: 0;
			}
			}


</style>
<script type="text/javascript">

        function getData(tag){
          
          document.getElementById("data").innerHTML = "<center><br><br><br><br><div class='lds-ripple'><div></div><div></div></div></center>";                                    
           
            $.get("getContent.php?tag="+tag,	
                            function(data,status){
                             document.getElementById("data").innerHTML = data;                                    
                      });
          }


$(function() {
    superplaceholder({
			el: scanner_input,
			sentences: [ 'กดปุ่มเพื่อ Scan Barcode ' ],
			options: {
				letterDelay: 80,
				loop: true,
				startOnFocus: false
			}
        });
	var liveStreamConfig = {
			inputStream: {
				type : "LiveStream",
				constraints: {
					width: {min: 640},
					height: {min: 480},
					aspectRatio: {min: 1, max: 100},
					facingMode: "environment" // or "user" for the front camera
				}
			},
			locator: {
				patchSize: "medium",
				halfSample: true
			},
			numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
			decoder: {
				"readers":[
					{"format":"code_128_reader","config":{}}
				]
			},
			locate: true
		};
	var fileConfig = $.extend(
			{}, 
			liveStreamConfig,
			{
				inputStream: {
					size: 800
				}
			}
		);

	$('#livestream_scanner').on('shown.bs.modal', function (e) {
		Quagga.init(
			liveStreamConfig, 
			function(err) {
				if (err) {
					$('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');
					Quagga.stop();
					return;
				}
				Quagga.start();
			}
		);
    });
	
	Quagga.onProcessed(function(result) {
		var drawingCtx = Quagga.canvas.ctx.overlay,
			drawingCanvas = Quagga.canvas.dom.overlay;  
 
		if (result) {
		/*	if (result.boxes) {
				drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
				result.boxes.filter(function (box) {
					return box !== result.box;
				}).forEach(function (box) {
				//	Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
				});
            }
            */
 
			if (result.box) {
			//	Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
			}
 
			if (result.codeResult && result.codeResult.code) {
				Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
			}
        }
        
    });
     
	Quagga.onDetected(function(result) {    		
		if (result.codeResult.code){
			$('#scanner_input').val(result.codeResult.code);
            getData(result.codeResult.code);
			Quagga.stop();	
			setTimeout(function(){ $('#livestream_scanner').modal('hide'); }, 1000);			
		}
	});
    
    $('#livestream_scanner').on('hide.bs.modal', function(){
    	if (Quagga){
    		Quagga.stop();	
    	}
    });
	
	// Call Quagga.decodeSingle() for every file selected in the 
	// file input
	/*kaochange$("#livestream_scanner input:file").on("change", function(e) {
		if (e.target.files && e.target.files.length) {
			Quagga.decodeSingle($.extend({}, fileConfig, {src: URL.createObjectURL(e.target.files[0])}), function(result) {alert(result.codeResult.code);});
		}
	});  kaochange*/
});
</script>

<body  style='background-color: #b2b2b2; font-family: "Kanit", sans-serif;'>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Find Asset</a>
    </div>
    <ul class="nav navbar-nav">
    <div class="navbar-form navbar-right" >
      <div class="input-group">
        <input type="text" class="form-control" placeholder="กดปุ่ม เพื่อสแกน Barcode" name="search" id='scanner_input' disabled>
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit"  data-toggle="modal" data-target="#livestream_scanner">
            <i class="glyphicon glyphicon-barcode"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="container" >
    <div class="row">
        <div>
            <div>
                <div id='data'></div>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->






    <div class="modal" id="livestream_scanner">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2 class="modal-title">Barcode Scanner</h2>
                </div>
                <div class="modal-body" style="position: static">
                    <div id="interactive" class="viewport"></div>
                    <div class="error"></div>
                </div>
              <!--  <div class="modal-footer">
                    <label class="btn btn-default pull-left">
                        <i class="fa fa-camera"></i> Use camera app
                        <input type="file" accept="image/*;capture=camera" capture="camera" class="hidden" />
                    </label>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>

            -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


</div>

</body>
</html>



