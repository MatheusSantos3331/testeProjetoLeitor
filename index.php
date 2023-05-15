<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca Produtos</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style_login.css">
    <link rel="stylesheet" href="style-main.css">
    
</head>
<body>
    <header class="container">
        <div class="imagem-logo">
            <a class="imagem-header" href="login.html"><img src="imgs/athena-software-logo@2x.png" alt="Imagem logo Athenas Software"></a>
        </div>
    </header>
    <main class="container-main">
        <div class="opcoes">
            <h2>Escolha a opção de verificação</h2>
            <button  onclick="mostrarCamera()">Codigo de barras</button>
            <button>Codigo interno</button>
            <button>Nome</button>

        </div>
        <div class="resultados">
            <h2>Verificar</h2>
            <div id="resultado"></div>
            <div  id="camera" style="display: none;"></div>

        </div>
        <script src="quagga.min.js"></script>

    <script>
        function mostrarCamera() {
            var cameraDiv = document.getElementById('camera');
            cameraDiv.style.display = 'block';
        }       

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#camera')
            },
            frequency:2,
            decoder: {
                readers: ["ean_reader"],
            }
        }, function (err) {
            if (err) {
                console.log(err);
                return
            }
            console.log("Initialization finished. Ready to start");
            Quagga.start();
        });
        Quagga.onProcessed(function(result){
            var drawingCtx = Quagga.canvas.ctx.overlay,
            drawingCanvas = Quagga.cancas.dom.overlay;
            if(result){
                if(result.boxes){
                    drawingCtx.clearRect(0,0,parseInt(drawingCanvas.getAttribute("width")),
                    parseInt(drawingCanvas.getAttribute("height")));
                    result.boxes.filter(function(box){
                        return box !== result.box;
                    }).forEach(function(box){
                        Quagga.ImageDebug.drawPath(box, {
                            x : 0,
                            y: 1
                        }, drawingCtx,{
                            color:"green",
                            lineWidth: 2
                        });

                    });
                }
            }
            if(result.box){
                Quagga.ImageDebug.drawPath(result.box, {
                    x:0,
                    y:1,
                }, drawingCtx,{
                    color: "#00F",
                    lineWidth: 2
                });
            }
            if(result.codeResult && result.codeResult.code){
                Quagga.ImageDebug.drawPath(result.line,{
                    x : 'x',
                    y : 'y'
                }, drawingCtx, {
                    color: 'red',
                    lineWidth: 3
                });
            }
        })

        Quagga.onDetected(function (data) {
            alertify.sucess('' + data.codeResult.code)
            console.log(data.codeResult.code);
            document.querySelector('#resultado').innerText = data.codeResult.code;
        });
    </script>
    </main>
</body>
</html>